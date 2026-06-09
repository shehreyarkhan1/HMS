<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DisciplinaryAction;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class DisciplinaryController extends Controller
{

    // ── INDEX ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = DisciplinaryAction::with(['employee', 'issuedBy'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', fn ($q) =>
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
            );
        }

        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $actions   = $query->paginate(20)->withQueryString();
        $employees = Employee::where('employment_status', 'Active')
            ->orderBy('first_name')->get();

        $stats = [
            'total'       => DisciplinaryAction::count(),
            'open'        => DisciplinaryAction::active()->count(),
            'this_month'  => DisciplinaryAction::whereMonth('action_date', now()->month)->count(),
            'suspensions' => DisciplinaryAction::where('action_type', 'Suspension')
                ->where('status', '!=', 'Closed')->count(),
        ];

        return view('hr.disciplinary_index', compact('actions', 'employees', 'stats'));
    }

    // ── CREATE ────────────────────────────────────────────────────────
    public function create(Request $request)
    {
        $employees = Employee::where('employment_status', 'Active')
            ->orderBy('first_name')->get();

        $selectedEmployee = $request->filled('employee_id')
            ? Employee::find($request->employee_id)
            : null;

        return view('hr.disciplinary_create', compact('employees', 'selectedEmployee'));
    }

    // ── STORE ─────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'          => 'required|exists:employees,id',
            'incident_date'        => 'required|date|before_or_equal:today',
            'incident_type'        => 'required|in:Misconduct,Insubordination,Tardiness,Absenteeism,Negligence,Harassment,Fraud,Violence,Policy Violation,Other',
            'incident_description' => 'required|string',
            'action_type'          => 'required|in:Verbal Warning,Written Warning,Show Cause Notice,Suspension,Demotion,Salary Deduction,Termination,Other',
            'action_date'          => 'required|date',
            'action_details'       => 'required|string',
            'suspension_from'      => 'nullable|date|required_if:action_type,Suspension',
            'suspension_to'        => 'nullable|date|after_or_equal:suspension_from',
            'suspension_paid'      => 'boolean',
            'deduction_amount'     => 'nullable|numeric|min:0|required_if:action_type,Salary Deduction',
            'deduction_month'      => 'nullable|string|max:20',
            'response_deadline'    => 'nullable|date|after:action_date',
            'document_path'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes'                => 'nullable|string',
        ]);

        // Calculate suspension days
        if ($request->action_type === 'Suspension'
            && $request->filled('suspension_from')
            && $request->filled('suspension_to')) {
            $validated['suspension_days'] = Carbon::parse($request->suspension_from)
                ->diffInDays(Carbon::parse($request->suspension_to)) + 1;
        }

        // Document upload
        if ($request->hasFile('document_path')) {
            $validated['document_path'] = $request->file('document_path')
                ->store('disciplinary-docs', 'public');
        }

        $validated['issued_by']       = Auth::user()->employee?->id;
        $validated['suspension_paid'] = $request->boolean('suspension_paid');

        $action = DisciplinaryAction::create($validated);

        return redirect()->route('hr.disciplinary.show', $action)
            ->with('success', "Disciplinary action #{$action->action_number} issued.");
    }

    // ── SHOW ──────────────────────────────────────────────────────────
    public function show(DisciplinaryAction $disciplinary)
    {
        $disciplinary->load(['employee', 'issuedBy', 'reviewedBy']);

        return view('hr.disciplinary_show', compact('disciplinary'));
    }

    // ── EDIT ──────────────────────────────────────────────────────────
    public function edit(DisciplinaryAction $disciplinary)
    {
        if (in_array($disciplinary->status, ['Resolved', 'Closed'])) {
            return redirect()->route('hr.disciplinary.show', $disciplinary)
                ->with('error', 'Closed actions cannot be edited.');
        }

        $employees = Employee::where('employment_status', 'Active')
            ->orderBy('first_name')->get();

        return view('hr.disciplinary_edit', compact('disciplinary', 'employees'));
    }

    // ── UPDATE ────────────────────────────────────────────────────────
    public function update(Request $request, DisciplinaryAction $disciplinary)
    {
        $validated = $request->validate([
            'incident_description' => 'required|string',
            'action_details'       => 'required|string',
            'response_deadline'    => 'nullable|date',
            'notes'                => 'nullable|string',
            'status'               => 'required|in:Issued,Acknowledged,Under Review,Resolved,Escalated,Closed',
        ]);

        $disciplinary->update($validated);

        return redirect()->route('hr.disciplinary.show', $disciplinary)
            ->with('success', 'Disciplinary action updated.');
    }

    // ── RECORD EMPLOYEE RESPONSE ──────────────────────────────────────
    public function recordResponse(Request $request, DisciplinaryAction $disciplinary)
    {
        $request->validate([
            'employee_response' => 'required|string',
        ]);

        $disciplinary->update([
            'employee_response'       => $request->employee_response,
            'response_received'       => true,
            'response_received_date'  => today(),
            'status'                  => 'Under Review',
        ]);

        return back()->with('success', 'Employee response recorded.');
    }

    // ── RESOLVE ───────────────────────────────────────────────────────
    public function resolve(Request $request, DisciplinaryAction $disciplinary)
    {
        $request->validate([
            'review_notes' => 'required|string',
        ]);

        $disciplinary->update([
            'status'      => 'Resolved',
            'reviewed_by' => Auth::user()->employee?->id,
            'notes'       => $disciplinary->notes . "\n\nResolution: " . $request->review_notes,
        ]);

        return back()->with('success', 'Disciplinary action resolved.');
    }

    // ── APPEAL ────────────────────────────────────────────────────────
    public function appeal(Request $request, DisciplinaryAction $disciplinary)
    {
        $request->validate([
            'appeal_details' => 'required|string',
            'appeal_outcome' => 'required|in:Upheld,Overturned,Modified',
        ]);

        $disciplinary->update([
            'is_appealed'    => true,
            'appeal_details' => $request->appeal_details,
            'appeal_outcome' => $request->appeal_outcome,
            'status'         => $request->appeal_outcome === 'Overturned' ? 'Closed' : 'Resolved',
        ]);

        return back()->with('success', 'Appeal recorded.');
    }
}
