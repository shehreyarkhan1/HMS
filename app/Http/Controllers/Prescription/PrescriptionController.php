<?php

namespace App\Http\Controllers\Prescription;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PrescriptionController extends Controller
{
    // ===== INDEX =====
    public function index(Request $request)
    {
        try {
            $query = Prescription::with(['patient', 'doctor'])->latest();

            // Filters
            if ($request->filled('search')) {
                $query->search($request->search);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date')) {
                $query->whereDate('prescribed_date', $request->date);
            }

            // Pagination
            $prescriptions = $query->paginate(15)->withQueryString();

            // Stats (optimized)
            $stats = Prescription::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'Partial' THEN 1 ELSE 0 END) as partial,
                SUM(CASE WHEN status = 'Dispensed' THEN 1 ELSE 0 END) as dispensed")->first();

            return view('prescription.prescriptions_index', compact('prescriptions', 'stats'));

        } catch (\Throwable $e) {

            // Log detailed error for developer
            Log::error('Prescription Index Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            // User-friendly response
            return redirect()->back()->with('error', 'Something went wrong while loading prescriptions.');
        }
    }

    // ===== CREATE =====
    public function create(Request $request)
    {
        try {
            $doctors = Doctor::with('employee')
                ->where('is_active', true)
                ->get()
                ->sortBy('employee.first_name');

            $medicines = Medicine::select('id', 'name', 'generic_name', 'unit', 'sale_price')
                ->where('is_active', true)
                ->where('total_stock', '>', 0)
                ->orderBy('name')
                ->get();


            $selectedPatient = null;
            $selectedDoctor = null;

            // Doctor logged in hai tو apna record auto-select
            if (auth()->user()->hasRole('doctor')) {
                $selectedDoctor = Doctor::whereHas('employee', function ($q) {
                    $q->whereHas('user', function ($q2) {
                        $q2->where('id', auth()->id());
                    });
                })->first();
            }

            // Appointment se patient
            if ($request->filled('appointment_id')) {
                $appt = Appointment::with('patient', 'doctor')->find($request->appointment_id);
                if ($appt) {
                    $selectedPatient = $appt->patient;
                    if (! auth()->user()->hasRole('doctor')) {
                        $selectedDoctor = $appt->doctor;
                    }
                }
            }

            // Direct patient_id se
            if (! $selectedPatient && $request->filled('patient_id')) {
                $selectedPatient = Patient::find($request->patient_id);
            }

            return view('prescription.prescriptions_create', compact(
                'doctors', 'medicines', 'selectedPatient', 'selectedDoctor'
            ));

        } catch (\Throwable $e) {
            Log::error('Prescription Create Page Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->back()->with('error', 'Something went wrong while opening the create page.');
        }
    }

    // ===== STORE =====
    public function store(Request $request)
    {
        // ✅ Validation
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'prescribed_date' => 'required|date',
            'valid_until' => 'nullable|date|after:prescribed_date',
            'diagnosis' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.dosage' => 'required|string|max:100',
            'items.*.frequency' => 'required|string|max:100',
            'items.*.duration_days' => 'required|integer|min:1',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.instructions' => 'nullable|string|max:200',
        ]);

        try {
            DB::beginTransaction();

            // ✅ Create Prescription
            $prescription = Prescription::create([
                'patient_id' => $validated['patient_id'],
                'doctor_id' => $validated['doctor_id'] ?? null,
                'appointment_id' => $validated['appointment_id'] ?? null,
                'prescribed_date' => $validated['prescribed_date'],
                'valid_until' => $validated['valid_until'] ?? null,
                'diagnosis' => $validated['diagnosis'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'Pending',
            ]);

            // ✅ Insert Items
            foreach ($validated['items'] as $item) {
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id' => $item['medicine_id'],
                    'dosage' => $item['dosage'],
                    'frequency' => $item['frequency'],
                    'duration_days' => $item['duration_days'],
                    'quantity' => $item['quantity'],
                    'instructions' => $item['instructions'] ?? null,
                    'dispensed_qty' => 0,
                ]);
            }

            DB::commit();

            // Doctor redirect
            if (auth()->user()->hasRole('doctor')) {
                if ($request->filled('appointment_id')) {
                    return redirect()->route('appointments.show', $request->appointment_id)
                        ->with('success', "Prescription {$prescription->prescription_number} created!");
                }

                return redirect()->route('patients.show', $prescription->patient_id)
                    ->with('success', "Prescription {$prescription->prescription_number} created!");
            }

            return redirect()
                ->route('pharmacy.prescriptions.show', $prescription->id)
                ->with('success', "Prescription {$prescription->prescription_number} created!");

        } catch (\Throwable $e) {

            DB::rollBack();

            // ❗ Log full error
            Log::error('Prescription Store Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            // API support
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create prescription.',
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong while creating prescription.');
        }
    }

    // ===== SHOW =====
    public function show(Prescription $prescription)
    {
        try {
            $prescription->load([
                'patient',
                'doctor',
                'items.medicine',
                'dispensings.items.medicine',
            ]);

            return view('prescription.prescriptions_show', compact('prescription'));

        } catch (\Throwable $e) {

            Log::error('Prescription Show Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'prescription_id' => $prescription->id ?? null,
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to load prescription details.');
        }
    }
}
