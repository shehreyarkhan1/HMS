<?php

namespace App\Http\Controllers\Ward;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ward;
use App\Models\Bed;
use App\Models\Patient;

class WardController extends Controller
{
    public function index()
    {
        $wards = Ward::withCount('beds')
            ->with('beds')
            ->latest()
            ->get();

        $stats = [
            'total_wards' => $wards->count(),
            'total_beds' => Bed::count(),
            'available_beds' => Bed::where('status', 'Available')->count(),
            'occupied_beds' => Bed::where('status', 'Occupied')->count(),
            'reserved_beds' => Bed::where('status', 'Reserved')->count(),
            'maintenance_beds' => Bed::where('status', 'Maintenance')->count(),
        ];

        return view('wards.wards_index', compact('wards', 'stats'));
    }

    // ===== CREATE WARD =====
    public function create()
    {
        return view('wards.wards_create');
    }

    // ===== STORE WARD =====
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:General,ICU,CCU,NICU,Surgical,Maternity,Pediatric,Orthopedic,Private,Semi-Private',
            'total_beds' => 'required|integer|min:1|max:500',
            'floor' => 'nullable|string|max:50',
            'block' => 'nullable|string|max:50',
            'bed_charges' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $ward = Ward::create($request->all());

        // Auto-create beds
        for ($i = 1; $i <= $ward->total_beds; $i++) {
            Bed::create([
                'bed_number' => $ward->ward_code . '-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'ward_id' => $ward->id,
                'type' => in_array($ward->type, ['ICU', 'CCU', 'NICU']) ? 'ICU' : 'Standard',
                'status' => 'Available',
            ]);
        }

        return redirect()
            ->route('wards.show', $ward->id)
            ->with('success', "Ward '{$ward->name}' created with {$ward->total_beds} beds!");
    }

    // ===== SHOW WARD =====
    public function show(Ward $ward)
    {
        $ward->load('beds.patient');
        $patients = Patient::where('patient_type', 'IPD')
            ->where('status', 'Active')
            ->whereDoesntHave('bed')
            ->get();

        return view('wards.wards_show', compact('ward', 'patients'));
    }

    // ===== EDIT WARD =====
    public function edit(Ward $ward)
    {
        return view('wards.wards_edit', compact('ward'));
    }

    // ===== UPDATE WARD =====
    public function update(Request $request, Ward $ward)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:General,ICU,CCU,NICU,Surgical,Maternity,Pediatric,Orthopedic,Private,Semi-Private',
            'floor' => 'nullable|string|max:50',
            'block' => 'nullable|string|max:50',
            'bed_charges' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            // is_active hata do validation se
        ]);

        $data = $request->except(['total_beds', '_token', '_method']);
        $data['is_active'] = $request->has('is_active'); // checkbox
        $ward->update($data);

        return redirect()
            ->route('wards.show', $ward->id)
            ->with('success', 'Ward updated successfully!');
    }

    // ===== DELETE WARD =====
    public function destroy(Ward $ward)
    {
        if ($ward->beds()->where('status', 'Occupied')->count() > 0) {
            return back()->with('error', 'Cannot delete ward with occupied beds!');
        }
        $ward->beds()->delete();
        $ward->delete();

        return redirect()->route('wards.index')
            ->with('success', "Ward '{$ward->name}' deleted.");
    }

    // ===== ASSIGN PATIENT TO BED =====
    public function assignBed(Request $request)
    {
        $request->validate([
            'bed_id' => 'required|exists:beds,id',
            'patient_id' => 'required|exists:patients,id',
        ]);

        $bed = Bed::findOrFail($request->bed_id);
        $patient = Patient::findOrFail($request->patient_id);

        if ($bed->status !== 'Available') {
            return back()->with('error', 'This bed is not available!');
        }

        $bed->assignPatient($patient);

        return back()->with('success', "Patient {$patient->name} assigned to bed {$bed->bed_number}!");
    }

    // ===== DISCHARGE PATIENT =====
    public function discharge(Bed $bed)
    {
        if ($bed->status !== 'Occupied') {
            return back()->with('error', 'Bed is not occupied!');
        }

        $patientName = $bed->patient->name ?? 'Patient';
        $bed->discharge();

        return back()->with('success', "{$patientName} discharged successfully!");
    }

    // ===== CHANGE BED STATUS =====
    public function changeBedStatus(Request $request, Bed $bed)
    {
        $request->validate([
            'status' => 'required|in:Available,Reserved,Maintenance',
        ]);

        if ($bed->status === 'Occupied') {
            return back()->with('error', 'Cannot change status of occupied bed!');
        }

        $bed->update(['status' => $request->status]);

        return back()->with('success', "Bed {$bed->bed_number} status updated to {$request->status}!");
    }
}
