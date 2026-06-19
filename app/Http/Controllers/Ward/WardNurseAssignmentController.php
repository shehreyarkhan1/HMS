<?php

namespace App\Http\Controllers\Ward;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ward;
use App\Models\WardNurseAssignment;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class WardNurseAssignmentController extends Controller
{
    public function index()
    {
        $wards = Ward::where('is_active', true)
            ->with(['nurseAssignments' => function ($q) {
                $q->activeToday()->with('nurse');
            }])
            ->get();

        $allAssignments = WardNurseAssignment::with(['nurse', 'ward', 'assignedBy'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('wards.nurse_assignment_index', compact('wards', 'allAssignments'));
    }

    public function create()
    {
        $wards  = Ward::where('is_active', true)->get();
        $nurses = User::where('role', 'nurse')
                      ->where('is_active', true)->with('employee')
                      ->get();
                    //   dump($wards,$nurses);
        return view('wards.nurse_assignment_create', compact('wards', 'nurses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ward_id'    => 'required|exists:wards,id',
            'user_id'    => 'required|exists:users,id',
            'shift'      => 'required|in:Morning,Evening,Night',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after:start_date',
        ]);

        // Conflict check — same nurse same shift same waqt
        $conflict = WardNurseAssignment::where('user_id', $request->user_id)
            ->where('shift', $request->shift)
            ->where('is_active', true)
            ->where('start_date', '<=', $request->start_date)
            ->where(function ($q) use ($request) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $request->start_date);
            })->exists();

        if ($conflict) {
            return back()
                ->withErrors(['user_id' => 'This nurse is already assigned to another ward for this shift.'])
                ->withInput();
        }

        WardNurseAssignment::create([
            'ward_id'     => $request->ward_id,
            'user_id'     => $request->user_id,
            'shift'       => $request->shift,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'is_active'   => true,
            'assigned_by' => Auth::id(),
        ]);

        return redirect()->route('ward.nurse-assignments.index')
            ->with('success', 'Nurse ward ko assign ho gayi!');
    }

    public function destroy(WardNurseAssignment $assignment)
    {
        $assignment->update([
            'is_active' => false,
            'end_date'  => today(),
        ]);
        return back()->with('success', 'Assignment remove kar di gayi.');
    }
}
