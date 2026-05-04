<?php

namespace App\Http\Controllers\Prescription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
            // Patients
            $patients = Patient::select('id', 'name', 'mrn')
                ->orderBy('name')
                ->get();
                
            // Active Doctors
            $doctors = Doctor::with('employee')
                ->where('is_active', true)
                ->get()
                ->sortBy('employee.first_name');



            // Available Medicines
            $medicines = Medicine::select('id', 'name', 'generic_name', 'unit', 'sale_price')
                ->where('is_active', true)
                ->where('total_stock', '>', 0)
                ->orderBy('name')
                ->get();

            // Selected Patient (safe handling)
            $selectedPatient = null;
            if ($request->filled('patient_id')) {
                $selectedPatient = Patient::find($request->patient_id);

                // Optional safety check
                if (!$selectedPatient) {
                    return redirect()->back()->with('error', 'Selected patient not found.');
                }
            }

            return view('prescription.prescriptions_create', compact(
                'patients',
                'doctors',
                'medicines',
                'selectedPatient'
            ));

        } catch (\Throwable $e) {

            // Log error
            Log::error('Prescription Create Page Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            // Response handling
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load prescription create page.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Something went wrong while opening the create page.');
        }
    }

    // ===== STORE =====
    public function store(Request $request)
    {
        try {
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
                    'message' => 'Failed to create prescription.'
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
                'dispensings.items.medicine'
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