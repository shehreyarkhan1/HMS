<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    private array $departments = [
        'General OPD',
        'Cardiology',
        'Gynecology',
        'Pediatrics',
        'Surgery',
        'Orthopedics',
        'Neurology',
        'ENT',
        'Dermatology',
        'Radiology',
        'Pathology',
        'ICU',
        'Emergency',
        'Psychiatry',
        'Urology',
        'Ophthalmology',
    ];

    // ===== INDEX =====
    public function index(Request $request)
    {
        $query = Doctor::latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('availability')) {
            $query->where('availability', $request->availability);
        }

        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        $doctors = $query->paginate(12)->withQueryString();

        $stats = [
            'total' => Doctor::count(),
            'available' => Doctor::where('availability', 'Available')->count(),
            'on_leave' => Doctor::where('availability', 'On Leave')->count(),
            'off_duty' => Doctor::where('availability', 'Off Duty')->count(),
        ];

        $departments = $this->departments;

        return view('doctors.doctors_index', compact('doctors', 'stats', 'departments'));
    }

    // ===== CREATE =====
    public function create()
    {
        $departments = $this->departments;
        return view('doctors.doctors_create', compact('departments'));
    }

    // ===== STORE =====
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'specialization' => 'required|string|max:100',
            'qualification' => 'required|string|max:100',
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email|unique:doctors,email',
            'cnic' => 'nullable|string|size:13|unique:doctors,cnic',
            'gender' => 'required|in:Male,Female,Other',
            'department' => 'required|string',
            'consultation_fee' => 'nullable|numeric|min:0',
            'availability' => 'required|in:Available,On Leave,Off Duty',
            'shift' => 'required|in:Morning,Evening,Night,Full Day',
            'shift_start' => 'nullable|date_format:H:i,H:i:s',
            'shift_end' => 'nullable|date_format:H:i,H:i:s',
            'bio' => 'nullable|string',
            'photo' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('doctors', 'public');
        }

        $doctor = Doctor::create($data);

        return redirect()
            ->route('doctors.show', $doctor->id)
            ->with('success', "Dr. {$doctor->name} added successfully! ID: {$doctor->doctor_id}");
    }

    // ===== SHOW =====
    public function show(Doctor $doctor)
    {
        $doctor->load('patients', 'appointments');

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
        $departments = $this->departments;
        return view('doctors.doctors_edit', compact('doctor', 'departments'));
    }

    // ===== UPDATE =====
    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'specialization' => 'required|string|max:100',
            'qualification' => 'required|string|max:100',
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email|unique:doctors,email,' . $doctor->id,
            'cnic' => 'nullable|string|size:13|unique:doctors,cnic,' . $doctor->id,
            'gender' => 'required|in:Male,Female,Other',
            'department' => 'required|string',
            'consultation_fee' => 'nullable|numeric|min:0',
            'availability' => 'required|in:Available,On Leave,Off Duty',
            'shift' => 'required|in:Morning,Evening,Night,Full Day',
            'shift_start' => 'nullable|date_format:H:i,H:i:s',
            'shift_end' => 'nullable|date_format:H:i,H:i:s',
            'bio' => 'nullable|string',
            'is_active' => 'boolean',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('photo');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('photo')) {
            if ($doctor->photo) {
                Storage::disk('public')->delete($doctor->photo);
            }
            $data['photo'] = $request->file('photo')->store('doctors', 'public');
        }

        $doctor->update($data);

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
            ->with('success', "Dr. {$doctor->name} removed.");
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
