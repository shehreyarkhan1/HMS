<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    // ── INDEX ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Employee::latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }
        if ($request->filled('status')) {
            $query->where('employment_status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('employment_type', $request->type);
        }
        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        $employees = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Employee::count(),
            'active' => Employee::where('employment_status', 'Active')->count(),
            'on_leave' => Employee::where('employment_status', 'On Leave')->count(),
            'inactive' => Employee::whereIn('employment_status', ['Terminated', 'Resigned', 'Retired'])->count(),
        ];

        $departments = Employee::DEPARTMENTS;
        $designations = Employee::designations();

        return view('employee.employee_index', compact('employees', 'stats', 'departments', 'designations'));
    }

    // ── CREATE ────────────────────────────────────────────────────────
    public function create()
    {
        $departments = Employee::DEPARTMENTS;
        $designations = Employee::designations();

        return view('employee.employee_create', compact('departments', 'designations'));
    }

    // ── STORE ─────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'father_name' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'required|in:Male,Female,Other',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'cnic' => 'nullable|string|size:13|unique:employees,cnic',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'personal_phone' => 'required|string|max:15',
            'office_phone' => 'nullable|string|max:15',
            'personal_email' => 'nullable|email',
            'office_email' => 'nullable|email|unique:employees,office_email',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:15',
            'emergency_contact_relation' => 'nullable|string|max:50',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'city' => 'nullable|string|max:50',
            'province' => 'nullable|string|max:50',
            'department' => 'required|string',
            'designation' => 'required|string|max:100',
            'job_grade' => 'nullable|string|max:50',
            'employment_type' => 'required|in:Permanent,Contractual,Probationary,Part-Time,Intern,Daily-Wage',
            'employment_status' => 'required|in:Active,On Leave,Suspended,Terminated,Resigned,Retired',
            'joining_date' => 'required|date',
            'confirmation_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date',
            'shift' => 'required|in:Morning,Evening,Night,Rotating,Custom',
            'shift_start' => 'nullable|date_format:H:i',
            'shift_end' => 'nullable|date_format:H:i',
            'weekly_hours' => 'nullable|integer|min:1|max:168',
            'highest_qualification' => 'nullable|string|max:100',
            'basic_salary' => 'nullable|numeric|min:0',
            'salary_type' => 'required|in:Monthly,Daily,Hourly',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string',
        ]);

        $data = $request->except('photo');
        $data['is_tax_filer'] = $request->has('is_tax_filer');
        $data['has_system_access'] = $request->has('has_system_access');
        $data['working_days'] = $request->working_days ?? [];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee = Employee::create($data);

        return redirect()
            ->route('employees.show', $employee->id)
            ->with('success', "Employee {$employee->full_name} added! ID: {$employee->employee_id}");
    }

    // ── SHOW ──────────────────────────────────────────────────────────
    public function show(Employee $employee)
    {
        $employee->load('doctor');
        return view('employee.employee_show', compact('employee'));
    }

    // ── EDIT ──────────────────────────────────────────────────────────
    public function edit(Employee $employee)
    {
        $departments = Employee::DEPARTMENTS;
        $designations = Employee::designations();

        return view('employee.employee_edit', compact('employee', 'departments', 'designations'));
    }

    // ── UPDATE ────────────────────────────────────────────────────────
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'personal_phone' => 'required|string|max:15',
            'cnic' => 'nullable|string|size:13|unique:employees,cnic,' . $employee->id,
            'office_email' => 'nullable|email|unique:employees,office_email,' . $employee->id,
            'department' => 'required|string',
            'designation' => 'required|string|max:100',
            'employment_type' => 'required',
            'employment_status' => 'required',
            'joining_date' => 'required|date',
            'shift' => 'required',
            'basic_salary' => 'nullable|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('photo');
        $data['is_tax_filer'] = $request->has('is_tax_filer');
        $data['has_system_access'] = $request->has('has_system_access');
        $data['working_days'] = $request->working_days ?? [];

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($data);

        return redirect()
            ->route('employees.show', $employee->id)
            ->with('success', 'Employee updated successfully!');
    }

    // ── DESTROY ───────────────────────────────────────────────────────
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', "Employee {$employee->full_name} removed.");
    }
}