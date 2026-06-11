<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveType;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Models\Employee;


class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::orderBy('name')->get();

        return view('hr.leave_type_index', compact('leaveTypes'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:100|unique:leave_types,name',
        'code' => 'required|string|max:10|unique:leave_types,code',
        'description' => 'nullable|string',
        'days_per_year' => 'required|integer|min:0|max:365',
        'is_paid' => 'boolean',
        'carry_forward' => 'boolean',
        'max_carry_forward' => 'nullable|integer|min:0',
        'encashable' => 'boolean',
        'min_service_days' => 'nullable|integer|min:0',
        'max_consecutive_days' => 'nullable|integer|min:1',
        'notice_days_required' => 'nullable|integer|min:0',
        'requires_document' => 'boolean',
        'document_description' => 'nullable|string|max:255',
        'applicable_male' => 'boolean',
        'applicable_female' => 'boolean',
        'applicable_employment_types' => 'nullable|array',
        'is_active' => 'boolean',
    ]);

    // Boolean values handle karna
    $validated['is_paid'] = $request->boolean('is_paid', true);
    $validated['carry_forward'] = $request->boolean('carry_forward');
    $validated['encashable'] = $request->boolean('encashable');
    $validated['requires_document'] = $request->boolean('requires_document');
    $validated['applicable_male'] = $request->boolean('applicable_male', true);
    $validated['applicable_female'] = $request->boolean('applicable_female', true);
    $validated['is_active'] = $request->boolean('is_active', true);

    // Method 1: Nullable Integers ko 0 par set karna (Industry Standard)
    // Agar form se value nahi aayi to ye Null ko 0 kar dega taake DB error na de
    $validated['max_carry_forward'] = $validated['max_carry_forward'] ?? 0;
    $validated['min_service_days'] = $validated['min_service_days'] ?? 0;
    $validated['max_consecutive_days'] = $validated['max_consecutive_days'] ?? 0;
    $validated['notice_days_required'] = $validated['notice_days_required'] ?? 0;

    LeaveType::create($validated);

    return redirect()->route('hr.leave-types.index')
        ->with('success', "Leave type \"{$validated['name']}\" created successfully.");
}

    public function update(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:leave_types,name,'.$leaveType->id,
            'code' => 'required|string|max:10|unique:leave_types,code,'.$leaveType->id,
            'description' => 'nullable|string',
            'days_per_year' => 'required|integer|min:0|max:365',
            'is_paid' => 'boolean',
            'carry_forward' => 'boolean',
            'max_carry_forward' => 'nullable|integer|min:0',
            'encashable' => 'boolean',
            'min_service_days' => 'nullable|integer|min:0',
            'max_consecutive_days' => 'nullable|integer|min:1',
            'notice_days_required' => 'nullable|integer|min:0',
            'requires_document' => 'boolean',
            'document_description' => 'nullable|string|max:255',
            'applicable_male' => 'boolean',
            'applicable_female' => 'boolean',
            'applicable_employment_types' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['is_paid'] = $request->boolean('is_paid', true);
        $validated['carry_forward'] = $request->boolean('carry_forward');
        $validated['encashable'] = $request->boolean('encashable');
        $validated['requires_document'] = $request->boolean('requires_document');
        $validated['applicable_male'] = $request->boolean('applicable_male', true);
        $validated['applicable_female'] = $request->boolean('applicable_female', true);
        $validated['is_active'] = $request->boolean('is_active', true);

        $leaveType->update($validated);

        return redirect()->route('hr.leave-types.index')
            ->with('success', "Leave type \"{$leaveType->name}\" updated successfully.");
    }

    public function destroy(LeaveType $leaveType)
    {
        // Check if any leave requests exist
        if ($leaveType->leaveRequests()->exists()) {
            return redirect()->route('hr.leave-types.index')
                ->with('error', 'Cannot delete — leave requests exist for this type.');
        }

        $name = $leaveType->name;
        $leaveType->delete();

        return redirect()->route('hr.leave-types.index')
            ->with('success', "Leave type \"{$name}\" deleted.");
    }

    public function toggle(LeaveType $leaveType)
    {
        $leaveType->update(['is_active' => ! $leaveType->is_active]);
        $status = $leaveType->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Leave type \"{$leaveType->name}\" {$status}.");
    }
}
