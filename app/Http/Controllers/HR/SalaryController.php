<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\SalaryStructure;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    // ── INDEX — All employees salary overview ─────────────────────────
    public function index(Request $request)
    {
        $query = Employee::with('salaryStructure')
            ->where('employment_status', 'Active');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $employees = $query->orderBy('first_name')->paginate(20)->withQueryString();
        $departments = Employee::distinct()->pluck('department')->sort();

        $stats = [
            'total_payroll' => SalaryStructure::where('is_current', true)->sum('net_salary'),
            'avg_salary' => SalaryStructure::where('is_current', true)->avg('gross_salary'),
            'highest_salary' => SalaryStructure::where('is_current', true)->max('gross_salary'),
            'with_structure' => SalaryStructure::where('is_current', true)->count(),
            'without_structure' => Employee::where('employment_status', 'Active')
                ->whereDoesntHave('salaryStructure')->count(),
        ];

        return view('hr.salary_index', compact('employees', 'departments', 'stats'));
    }

    // ── SHOW — Employee salary history ────────────────────────────────
    public function show(Employee $employee)
    {
        $structures = SalaryStructure::forEmployee($employee->id)
            ->orderByDesc('effective_from')
            ->get();

        return view('hr.salary_show', compact('employee', 'structures'));
    }

    // ── CREATE ────────────────────────────────────────────────────────
    public function create(Employee $employee)
    {
        $current = $employee->salaryStructure;

        return view('hr.salary_create', compact('employee', 'current'));
    }

    // ── STORE ─────────────────────────────────────────────────────────
    public function store(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'house_rent_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'meal_allowance' => 'nullable|numeric|min:0',
            'special_allowance' => 'nullable|numeric|min:0',
            'other_allowance' => 'nullable|numeric|min:0',
            'other_allowance_description' => 'nullable|string|max:255',
            'eobi_applicable' => 'boolean',
            'eobi_employee_share' => 'nullable|numeric|min:0',
            'eobi_employer_share' => 'nullable|numeric|min:0',
            'is_tax_exempt' => 'boolean',
            'tax_slab' => 'nullable|string|max:100',
            'income_tax_monthly' => 'nullable|numeric|min:0',
            'provident_fund' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'other_deduction' => 'nullable|numeric|min:0',
            'other_deduction_description' => 'nullable|string|max:255',
            'effective_from' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['employee_id'] = $employee->id;
        $validated['is_current'] = true;
        $validated['created_by'] = Auth::id();
        $validated['eobi_applicable'] = $request->boolean('eobi_applicable', true);
        $validated['is_tax_exempt'] = $request->boolean('is_tax_exempt');

        // Fill nulls with 0
        $numericFields = [
            'house_rent_allowance', 'medical_allowance', 'transport_allowance',
            'meal_allowance', 'special_allowance', 'other_allowance',
            'eobi_employee_share', 'eobi_employer_share', 'income_tax_monthly',
            'provident_fund', 'loan_deduction', 'other_deduction',
        ];

        foreach ($numericFields as $field) {
            $validated[$field] = $validated[$field] ?? 0;
        }

        $structure = SalaryStructure::create($validated);

        // Also update employee basic_salary
        $employee->update(['basic_salary' => $validated['basic_salary']]);

        return redirect()
            ->route('hr.salary.show', $employee)
            ->with('success', 'Salary structure created. Net salary: PKR '.
                number_format($structure->net_salary, 2));
    }

    // ── EDIT ──────────────────────────────────────────────────────────
    public function edit(Employee $employee, SalaryStructure $structure)
    {
        return view('hr.salary_edit', compact('employee', 'structure'));
    }

    // ── UPDATE ────────────────────────────────────────────────────────
    public function update(Request $request, Employee $employee, SalaryStructure $structure)
    {
        // Can only edit current structure
        if (! $structure->is_current) {
            return back()->with('error', 'Only current salary structure can be edited.');
        }

        $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'house_rent_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'meal_allowance' => 'nullable|numeric|min:0',
            'special_allowance' => 'nullable|numeric|min:0',
            'other_allowance' => 'nullable|numeric|min:0',
            'other_allowance_description' => 'nullable|string|max:255',
            'eobi_applicable' => 'boolean',
            'eobi_employee_share' => 'nullable|numeric|min:0',
            'eobi_employer_share' => 'nullable|numeric|min:0',
            'is_tax_exempt' => 'boolean',
            'tax_slab' => 'nullable|string|max:100',
            'income_tax_monthly' => 'nullable|numeric|min:0',
            'provident_fund' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'other_deduction' => 'nullable|numeric|min:0',
            'other_deduction_description' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['eobi_applicable'] = $request->boolean('eobi_applicable', true);
        $validated['is_tax_exempt'] = $request->boolean('is_tax_exempt');

        $structure->update($validated);

        // Sync employee basic_salary
        $employee->update(['basic_salary' => $validated['basic_salary']]);

        return redirect()
            ->route('hr.salary.show', $employee)
            ->with('success', 'Salary structure updated.');
    }
}
