<?php

namespace App\Http\Controllers\Biometric;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BiometricController extends Controller
{
    // ── PUSH — ZKTeco machine se data aata hai ────────────────────────
    public function push(Request $request)
    {
        // ZKTeco ADMS push format
        // Machine bhejti hai: sn, stamp, table, ext
        Log::info('ZKTeco Push:', $request->all());

        $sn = $request->input('sn');    // Machine serial number
        $table = $request->input('table'); // 'ATTLOG' = attendance, 'OPERLOG' = operations

        if ($table !== 'ATTLOG') {
            return response('OK', 200);
        }

        // ATTLOG format: "EnrollNumber\tDateTime\tVerifyMode\tInOutMode\tWorkCode\n"
        $attlog = $request->input('Stamp') ?? $request->input('stamp') ?? '';

        $lines = explode("\n", trim($attlog));

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            $parts = preg_split('/\s+/', $line);

            if (count($parts) < 2) {
                continue;
            }

            $enrollNumber = $parts[0];   // Employee biometric ID
            $datetime = $parts[1];   // 2025-06-01 09:05:00
            $verifyMode = $parts[2] ?? 1; // 1=finger, 4=card, 15=face
            $inOutMode = $parts[3] ?? 0; // 0=check-in, 1=check-out, 4=break

            $this->processLog($sn, $enrollNumber, $datetime, (int) $inOutMode);
        }

        return response('OK', 200);
    }

    // ── Process single biometric log ──────────────────────────────────
    private function processLog(
        string $machineSerial,
        string $enrollNumber,
        string $datetime,
        int $inOutMode
    ): void {
        try {
            $punchTime = Carbon::parse($datetime);
            $date = $punchTime->toDateString();
            $time = $punchTime->format('H:i:s');

            // Employee dhundo by biometric_id (enroll number)
            $employee = Employee::where('biometric_id', $enrollNumber)
                ->where('employment_status', 'Active')
                ->first();

            if (! $employee) {
                Log::warning("ZKTeco: No employee found for enroll #{$enrollNumber}");
                // Raw log save karo for later mapping
                BiometricLog::create([
                    'machine_serial' => $machineSerial,
                    'enroll_number' => $enrollNumber,
                    'punch_time' => $punchTime,
                    'in_out_mode' => $inOutMode,
                    'is_processed' => false,
                    'error_note' => 'Employee not found',
                ]);

                return;
            }

            // Raw log save karo
            BiometricLog::updateOrCreate(
                [
                    'enroll_number' => $enrollNumber,
                    'punch_time' => $punchTime,
                ],
                [
                    'machine_serial' => $machineSerial,
                    'employee_id' => $employee->id,
                    'in_out_mode' => $inOutMode,
                    'is_processed' => true,
                ]
            );

            // Attendance record update
            $attendance = Attendance::firstOrNew([
                'employee_id' => $employee->id,
                'date' => $date,
            ]);

            // Check-in (0) ya unknown — pehli punch
            if ($inOutMode === 0 || ! $attendance->check_in) {
                if (! $attendance->check_in || $time < $attendance->check_in) {
                    $attendance->check_in = $time;
                }
            }

            // Check-out (1) — baad wali punch
            if ($inOutMode === 1 || ($attendance->check_in && $time > $attendance->check_in)) {
                if (! $attendance->check_out || $time > $attendance->check_out) {
                    $attendance->check_out = $time;
                }
            }

            // Status calculate
            $attendance->status = $this->calculateStatus(
                $attendance->check_in,
                $attendance->check_out,
                $employee
            );

            // Working minutes
            if ($attendance->check_in && $attendance->check_out) {
                $in = Carbon::parse($attendance->check_in);
                $out = Carbon::parse($attendance->check_out);

                $attendance->working_minutes = $out->diffInMinutes($in);
                $attendance->overtime_minutes = max(0, $attendance->working_minutes - 480);

                // Late minutes (after 09:00)
                $shiftStart = Carbon::parse($employee->shift_start ?? '09:00:00');
                $checkIn = Carbon::parse($attendance->check_in);
                if ($checkIn->gt($shiftStart)) {
                    $attendance->late_minutes = $shiftStart->diffInMinutes($checkIn);
                }
            }

            $attendance->source = 'Biometric';
            $attendance->save();

        } catch (\Exception $e) {
            Log::error('ZKTeco processLog error: '.$e->getMessage(), [
                'enroll' => $enrollNumber,
                'time' => $datetime,
            ]);
        }
    }

    // ── Calculate attendance status ───────────────────────────────────
    private function calculateStatus(
        ?string $checkIn,
        ?string $checkOut,
        Employee $employee
    ): string {
        if (! $checkIn) {
            return 'Absent';
        }

        $shiftStart = Carbon::parse($employee->shift_start ?? '09:00:00');
        $lateLimit = $shiftStart->copy()->addMinutes(30); // 30 min grace
        $halfDayMins = 240; // 4 hours = half day

        $in = Carbon::parse($checkIn);

        // Half day check
        if ($checkOut) {
            $out = Carbon::parse($checkOut);
            $minutes = $out->diffInMinutes($in);
            if ($minutes < $halfDayMins) {
                return 'Half Day';
            }
        }

        // Late check
        if ($in->gt($lateLimit)) {
            return 'Late';
        }

        return 'Present';
    }

    // ── Sync Status — HR dashboard ke liye ───────────────────────────
    public function syncStatus()
    {
        $stats = [
            'total_logs_today' => BiometricLog::whereDate('punch_time', today())->count(),
            'processed_today' => BiometricLog::whereDate('punch_time', today())
                ->where('is_processed', true)->count(),
            'unmatched_today' => BiometricLog::whereDate('punch_time', today())
                ->where('is_processed', false)->count(),
            'last_sync' => BiometricLog::latest('punch_time')
                ->value('punch_time'),
        ];

        return response()->json($stats);
    }
}
