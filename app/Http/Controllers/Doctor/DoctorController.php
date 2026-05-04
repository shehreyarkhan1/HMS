<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    // ===== INDEX =====
    public function index(Request $request)
    {
        $query = Doctor::with('employee')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('employee', function ($eq) use ($request) {
                    $eq->where('first_name', 'like', '%' . $request->search . '%')
                        ->orWhere('last_name', 'like', '%' . $request->search . '%');
                })
                    ->orWhere('doctor_id', 'like', '%' . $request->search . '%')
                    ->orWhere('specialization', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('department')) {
            $query->whereHas('employee', fn($q) => $q->where('department', $request->department));
        }

        if ($request->filled('availability')) {
            $query->where('availability', $request->availability);
        }

        if ($request->filled('shift')) {
            $query->whereHas('employee', fn($q) => $q->where('shift', $request->shift));
        }

        $doctors = $query->paginate(12)->withQueryString();

        $stats = [
            'total' => Doctor::count(),
            'available' => Doctor::where('availability', 'Available')->count(),
            'on_leave' => Doctor::where('availability', 'On Leave')->count(),
            'off_duty' => Doctor::where('availability', 'Off Duty')->count(),
        ];

        // Sirf Clinical departments filter mein dikhao
        $departments = collect(Employee::DEPARTMENTS)
            ->filter(fn($d) => str_starts_with($d, 'Clinical —'))
            ->values()
            ->all();

        return view('doctors.doctors_index', compact('doctors', 'stats', 'departments'));
    }

    // ===== CREATE =====
    public function create()
    {
        // Sirf Clinical departments
        $departments = collect(Employee::DEPARTMENTS)
            ->filter(fn($d) => str_starts_with($d, 'Clinical —'))
            ->values()
            ->all();

        // Active employees jo pehle se doctor nahi bane aur Clinical department mein hain
        $employees = Employee::whereDoesntHave('doctor')
            ->where('employment_status', 'Active')
            ->where('department', 'like', 'Clinical — %')
            ->orderBy('first_name')
            ->get();

        return view('doctors.doctors_create', compact('departments', 'employees'));
    }

    // ===== STORE =====
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id|unique:doctors,employee_id',
            'specialization' => 'required|string|max:100',
            'qualification' => 'required|string|max:100',
            'pmdc_number' => 'nullable|string|max:50',
            'doctor_type' => 'required|in:Consultant,Medical Officer,House Officer,Visiting,Specialist',
            'sub_department' => 'nullable|string|max:100',
            'consultation_fee' => 'nullable|numeric|min:0',
            'avg_consultation_mins' => 'nullable|integer|min:5|max:120',
            'availability' => 'required|in:Available,In Consultation,On Leave,Off Duty',
            'available_days' => 'nullable|array',
            'available_days.*' => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'bio' => 'nullable|string',
            'clinical_notes' => 'nullable|string',
            'accepts_new_patients' => 'nullable|boolean',
        ]);

        $doctor = DB::transaction(function () use ($request) {
            $data = $request->except('_token');
            $data['accepts_new_patients'] = $request->has('accepts_new_patients');

            $doctor = Doctor::create($data);

            return $doctor;
        });

        return redirect()
            ->route('doctors.show', $doctor->id)
            ->with('success', "Dr. {$doctor->employee->full_name} added! ID: {$doctor->doctor_id}");
    }

    // ===== SHOW =====
    public function show(Doctor $doctor)
    {
        $doctor->load('employee', 'patients', 'appointments');

        $stats = [
            'total_patients' => $doctor->patients()->count(),
            'total_appointments' => $doctor->appointments()->count(),
            'today_appointments' => $doctor->appointments()
                ->whereDate('appointment_date', today())
                ->count(),
        ];

        return view('doctors.doctors_show', compact('doctor', 'stats'));
    }

    // ===== EDIT =====
    public function edit(Doctor $doctor)
    {
        $doctor->load('employee');

        $departments = collect(Employee::DEPARTMENTS)
            ->filter(fn($d) => str_starts_with($d, 'Clinical —'))
            ->values()
            ->all();

        return view('doctors.doctors_edit', compact('doctor', 'departments'));
    }

    // ===== UPDATE =====
    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'specialization' => 'required|string|max:100',
            'qualification' => 'required|string|max:100',
            'pmdc_number' => 'nullable|string|max:50',
            'doctor_type' => 'required|in:Consultant,Medical Officer,House Officer,Visiting,Specialist',
            'sub_department' => 'nullable|string|max:100',
            'consultation_fee' => 'nullable|numeric|min:0',
            'avg_consultation_mins' => 'nullable|integer|min:5|max:120',
            'availability' => 'required|in:Available,In Consultation,On Leave,Off Duty',
            'available_days' => 'nullable|array',
            'available_days.*' => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'bio' => 'nullable|string',
            'clinical_notes' => 'nullable|string',
            'accepts_new_patients' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $doctor) {
            $data = $request->except(['_token', '_method']);
            $data['accepts_new_patients'] = $request->has('accepts_new_patients');
            $data['is_active'] = $request->has('is_active');

            $doctor->update($data);
        });

        return redirect()
            ->route('doctors.show', $doctor->id)
            ->with('success', 'Doctor updated successfully!');
    }

    // ===== DESTROY =====
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return redirect()
            ->route('doctors.index')
            ->with('success', "Doctor removed.");
    }

    // ===== TOGGLE AVAILABILITY (AJAX) =====
    public function toggleAvailability(Doctor $doctor)
    {
        $new = $doctor->availability === 'Available' ? 'Off Duty' : 'Available';
        $doctor->update(['availability' => $new]);

        return response()->json([
            'status' => 'success',
            'availability' => $new,
        ]);
    }
}