<?php

namespace App\Http\Controllers\Biometric;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\BiometricLog;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class BiometricController extends Controller
{
      public function show(Employee $employee)
    {
        $enrollNumber = $employee->biometric_id;

        $totalLogs = $enrollNumber
            ? BiometricLog::where('enroll_number', $enrollNumber)->count()
            : 0;

        $processedLogs = $enrollNumber
            ? BiometricLog::where('enroll_number', $enrollNumber)->where('is_processed', true)->count()
            : 0;

        $unmatchedLogs = $enrollNumber
            ? BiometricLog::where('enroll_number', $enrollNumber)->where('is_processed', false)->count()
            : 0;

        $recentLogs = $enrollNumber
            ? BiometricLog::where('enroll_number', $enrollNumber)
                ->latest('punch_time')
                ->limit(20)
                ->get()
            : collect();

        return view('hr.biometric_assign', compact(
            'employee',
            'totalLogs',
            'processedLogs',
            'unmatchedLogs',
            'recentLogs',
        ));
    }
    // ─────────────────────────────────────────────────────────────────
    // ZKTeco machine IP whitelist (optional — set in .env)
    // BIOMETRIC_ALLOWED_IPS=192.168.1.100,192.168.1.101
    // ─────────────────────────────────────────────────────────────────
    private function verifyMachineIp(Request $request): bool
    {
        $allowed = config('biometric.allowed_ips', []);

        if (empty($allowed)) {
            return true; // No whitelist configured → allow all
        }

        return in_array($request->ip(), $allowed, true);
    }

    // ─────────────────────────────────────────────────────────────────
    // PUSH — ZKTeco machine ATTLOG push endpoint
    // Machine URL config: http://your-domain.com/biometric/push
    // ─────────────────────────────────────────────────────────────────
    public function push(Request $request)
    {
        if (! $this->verifyMachineIp($request)) {
            Log::warning('ZKTeco: Rejected push from unknown IP: '.$request->ip());

            return response('Unauthorized', 401);
        }

        $sn = $request->input('SN') ?? $request->input('sn', 'unknown');
        $table = strtoupper($request->input('table', ''));

        // Only process attendance logs — ignore OPERLOG (operations)
        if ($table !== 'ATTLOG') {
            return response('OK', 200);
        }

        // ZKTeco sends data as 'Stamp' or 'stamp' parameter
        $attlog = $request->input('Stamp') ?? $request->input('stamp', '');

        if (empty(trim($attlog))) {
            return response('OK', 200);
        }

        Log::info("ZKTeco Push [{$sn}]: received ".substr_count($attlog, "\n") + 1 .' punch(es)');

        $lines = array_filter(
            explode("\n", trim($attlog)),
            fn ($l) => ! empty(trim($l))
        );

        foreach ($lines as $line) {
            $this->processLine($sn, trim($line));
        }

        return response('OK', 200);
    }

    // ─────────────────────────────────────────────────────────────────
    // Parse one ATTLOG line and process it
    // Format: "EnrollNum DateTime VerifyMode InOutMode WorkCode\n"
    // Example: "1 2026-06-19 09:05:32 1 0 0"
    // ─────────────────────────────────────────────────────────────────
    private function processLine(string $machineSerial, string $line): void
    {
        $parts = preg_split('/\s+/', $line);

        if (count($parts) < 2) {
            Log::debug("ZKTeco: Skipping malformed line: {$line}");

            return;
        }

        $enrollNumber = $parts[0];
        $datetimeStr = $parts[1].(isset($parts[2]) && str_contains($parts[2], ':') ? ' '.$parts[2] : '');
        $verifyMode = isset($parts[2]) && ! str_contains($parts[2], ':') ? (int) $parts[2] : (isset($parts[3]) ? (int) $parts[3] : 1);
        $inOutMode = isset($parts[3]) && ! str_contains($parts[3], ':') ? (int) $parts[3] : (isset($parts[4]) ? (int) $parts[4] : 255);

        // inOutMode 255 = machine doesn't distinguish — treat as "auto"
        if ($inOutMode === 255) {
            $inOutMode = self::IN_OUT_AUTO;
        }

        try {
            $punchTime = Carbon::parse($datetimeStr);
        } catch (\Exception $e) {
            Log::warning("ZKTeco: Cannot parse datetime '{$datetimeStr}' in line: {$line}");

            return;
        }

        $this->processLog($machineSerial, $enrollNumber, $punchTime, $verifyMode, $inOutMode);
    }

    // In/out mode constants
    const IN_OUT_CHECKIN = 0;

    const IN_OUT_CHECKOUT = 1;

    const IN_OUT_AUTO = 255; // Machine doesn't know — we decide based on time

    // ─────────────────────────────────────────────────────────────────
    // Core logic: save raw log + update attendance record
    // ─────────────────────────────────────────────────────────────────
    private function processLog(
        string $machineSerial,
        string $enrollNumber,
        Carbon $punchTime,
        int $verifyMode,
        int $inOutMode,
    ): void {
        DB::transaction(function () use (
            $machineSerial, $enrollNumber, $punchTime, $verifyMode, $inOutMode
        ) {
            $date = $punchTime->toDateString();
            $timeStr = $punchTime->format('H:i:s');

            // ── 1. Find employee by biometric_id ──────────────────────
            // Cache for 5 min — avoids DB hit on every punch
            $employee = Cache::remember(
                "biometric_employee_{$enrollNumber}",
                300,
                fn () => Employee::where('biometric_id', $enrollNumber)
                    ->where('employment_status', 'Active')
                    ->first()
            );

            // ── 2. Save raw punch (always, even if employee not found) ─
            try {
                BiometricLog::updateOrCreate(
                    [
                        'enroll_number' => $enrollNumber,
                        'punch_time' => $punchTime,
                        'machine_serial' => $machineSerial,
                    ],
                    [
                        'employee_id' => $employee?->id,
                        'verify_mode' => $verifyMode,
                        'in_out_mode' => $inOutMode,
                        'is_processed' => $employee !== null,
                        'error_note' => $employee ? null : 'Employee biometric_id not mapped',
                    ]
                );
            } catch (\Exception $e) {
                Log::error("ZKTeco: Failed to save BiometricLog for enroll #{$enrollNumber}: ".$e->getMessage());
            }

            if (! $employee) {
                Log::warning("ZKTeco: No active employee for biometric enroll #{$enrollNumber} — punch saved as unmatched");

                return;
            }

            // ── 3. Load or create today's attendance record ────────────
            $attendance = Attendance::firstOrNew([
                'employee_id' => $employee->id,
                'date' => $date,
            ]);

            // If already completed via biometric and not changed — skip redundant saves
            if ($attendance->source === 'Biometric' && $attendance->check_in && $attendance->check_out) {
                $existingOut = Carbon::parse($attendance->check_out);

                // Punch is earlier than current check-out, and inOutMode is checkout → ignore
                if ($inOutMode === self::IN_OUT_CHECKOUT && $punchTime->lt($existingOut)) {
                    return;
                }
            }

            // ── 4. Determine check-in / check-out ─────────────────────
            $isCheckIn = $inOutMode === self::IN_OUT_CHECKIN;
            $isCheckOut = $inOutMode === self::IN_OUT_CHECKOUT;

            if ($inOutMode === self::IN_OUT_AUTO) {
                // Auto-detect: first punch of the day = check-in, subsequent = check-out
                $isCheckIn = ! $attendance->check_in;
                $isCheckOut = (bool) $attendance->check_in && $timeStr > $attendance->check_in;
            }

            if ($isCheckIn) {
                // Keep earliest punch as check-in
                if (! $attendance->check_in || $timeStr < $attendance->check_in) {
                    $attendance->check_in = $timeStr;
                }
            }

            if ($isCheckOut) {
                // Keep latest punch as check-out
                if (! $attendance->check_out || $timeStr > $attendance->check_out) {
                    $attendance->check_out = $timeStr;
                }
            }

            // ── 5. Recalculate working minutes, overtime, late ─────────
            if ($attendance->check_in && $attendance->check_out) {
                $checkIn = Carbon::parse($date.' '.$attendance->check_in);
                $checkOut = Carbon::parse($date.' '.$attendance->check_out);

                // Sanity check: checkout must be after checkin
                if ($checkOut->lte($checkIn)) {
                    Log::warning("ZKTeco: check_out <= check_in for employee #{$employee->id} on {$date} — skipping time calc");
                } else {
                    $workingMins = $checkOut->diffInMinutes($checkIn);

                    // Standard working day: use employee's shift or fallback to setting
                    $standardMins = ($employee->weekly_hours / 5) * 60; // per day from weekly config

                    $attendance->working_minutes = $workingMins;
                    $attendance->overtime_minutes = max(0, $workingMins - (int) $standardMins);

                    // Late minutes: measured from employee shift_start (or 09:00 default)
                    $shiftStartTime = $employee->shift_start ?? '09:00:00';
                    $shiftStart = Carbon::parse($date.' '.$shiftStartTime);

                    $attendance->late_minutes = $checkIn->gt($shiftStart)
                        ? (int) $shiftStart->diffInMinutes($checkIn)
                        : 0;
                }
            }

            // ── 6. Status ─────────────────────────────────────────────
            $attendance->status = $this->calculateStatus($attendance, $employee, $date);
            $attendance->source = 'Biometric';
            $attendance->save();

            Log::info("ZKTeco: Attendance updated — {$employee->first_name} {$employee->last_name} [{$date}] status={$attendance->status}");
        });
    }

    // ─────────────────────────────────────────────────────────────────
    // Status calculation (matches HR manual logic)
    // ─────────────────────────────────────────────────────────────────
    private function calculateStatus(Attendance $attendance, Employee $employee, string $date): string
    {
        if (! $attendance->check_in) {
            return 'Absent';
        }

        $shiftStartTime = $employee->shift_start ?? '09:00:00';
        $shiftStart = Carbon::parse($date.' '.$shiftStartTime);
        $gracePeriodMins = 15; // 15-min grace before marking "Late"
        $halfDayMins = 240; // Less than 4 hrs = half day

        $checkIn = Carbon::parse($date.' '.$attendance->check_in);

        // Half-day check (requires checkout data)
        if ($attendance->check_in && $attendance->check_out) {
            if ($attendance->working_minutes < $halfDayMins) {
                return 'Half Day';
            }
        }

        // Late check
        if ($checkIn->gt($shiftStart->copy()->addMinutes($gracePeriodMins))) {
            return 'Late';
        }

        return 'Present';
    }

    // ─────────────────────────────────────────────────────────────────
    // RE-PROCESS UNMATCHED — Run when HR maps a new biometric_id
    // Call this from a job or artisan command after mapping
    // ─────────────────────────────────────────────────────────────────
    public function reprocessUnmatched(): void
    {
        $unmatchedLogs = BiometricLog::unprocessed()
            ->orderBy('punch_time')
            ->get();

        $processed = 0;

        foreach ($unmatchedLogs as $log) {
            $employee = Employee::where('biometric_id', $log->enroll_number)
                ->where('employment_status', 'Active')
                ->first();

            if (! $employee) {
                continue;
            }

            // Now we have a match — process it
            $this->processLog(
                $log->machine_serial,
                $log->enroll_number,
                Carbon::parse($log->punch_time),
                $log->verify_mode,
                $log->in_out_mode,
            );

            $log->update([
                'employee_id' => $employee->id,
                'is_processed' => true,
                'error_note' => null,
            ]);

            // Clear the cache so future punches pick up the mapping
            Cache::forget("biometric_employee_{$log->enroll_number}");

            $processed++;
        }

        Log::info("ZKTeco: Reprocessed {$processed} previously unmatched punch(es)");
    }

    // ─────────────────────────────────────────────────────────────────
    // STATUS — HR dashboard widget
    // GET /biometric/status
    // ─────────────────────────────────────────────────────────────────
    public function syncStatus()
    {
        $stats = [
            'total_logs_today' => BiometricLog::today()->count(),
            'processed_today' => BiometricLog::today()->where('is_processed', true)->count(),
            'unmatched_today' => BiometricLog::today()->where('is_processed', false)->count(),
            'total_unmatched_all' => BiometricLog::unprocessed()->count(),
            'last_punch_at' => BiometricLog::latest('punch_time')->value('punch_time'),
            'currently_in_office' => Attendance::whereDate('date', today())
                ->whereNotNull('check_in')
                ->whereNull('check_out')
                ->where('source', 'Biometric')
                ->count(),
        ];

        return response()->json($stats);
    }

    // ─────────────────────────────────────────────────────────────────
    // ASSIGN BIOMETRIC ID — HR assigns enroll number to employee
    // POST /hr/employees/{employee}/biometric-id
    // ─────────────────────────────────────────────────────────────────
    public function assignBiometricId(Request $request, Employee $employee)
    {
        $request->validate([
            'biometric_id' => [
                'required',
                'string',
                'max:20',
                // Must not already be assigned to someone else
                Rule::unique('employees', 'biometric_id')
                    ->ignore($employee->id),
            ],
        ]);

        $employee->update(['biometric_id' => $request->biometric_id]);

        // Clear cache so next punch uses the new mapping
        Cache::forget("biometric_employee_{$request->biometric_id}");

        // Trigger reprocess of any historical unmatched punches for this enroll number
        BiometricLog::where('enroll_number', $request->biometric_id)
            ->where('is_processed', false)
            ->get()
            ->each(function ($log) use ($employee) {
                $this->processLog(
                    $log->machine_serial,
                    $log->enroll_number,
                    Carbon::parse($log->punch_time),
                    $log->verify_mode,
                    $log->in_out_mode,
                );

                $log->update([
                    'employee_id' => $employee->id,
                    'is_processed' => true,
                    'error_note' => null,
                ]);
            });

        return back()->with('success', "Biometric ID {$request->biometric_id} assigned to {$employee->first_name}. ".
            BiometricLog::where('enroll_number', $request->biometric_id)->count().' historical punch(es) processed.');
    }
      public function removeBiometricId(Employee $employee)
    {
        $oldId = $employee->biometric_id;

        $employee->update(['biometric_id' => null]);

        Cache::forget("biometric_employee_{$oldId}");

        return back()->with('success', "Biometric ID #{$oldId} removed from {$employee->first_name}.");
    }
}
