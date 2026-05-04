<?php

namespace App\Http\Controllers\BloodBank;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\Employee;
use App\Models\BloodDonation;
use App\Models\BloodCrossmatch;

class BloodRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = BloodRequest::with(['patient', 'doctor.employee']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('request_id', 'like', "%$s%")
                    ->orWhereHas('patient', fn($q) => $q->where('name', 'like', "%$s%")->orWhere('mrn', 'like', "%$s%"));
            });
        }
        if ($request->filled('blood_group'))
            $query->where('blood_group', $request->blood_group);
        if ($request->filled('urgency'))
            $query->where('urgency', $request->urgency);
        if ($request->filled('status'))
            $query->where('status', $request->status);

        $requests = $query
            ->orderByRaw("
        CASE 
            WHEN urgency = 'Emergency' THEN 1
            WHEN urgency = 'Urgent' THEN 2
            WHEN urgency = 'Routine' THEN 3
            ELSE 4
        END
    ")
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
        $patients = Patient::whereIn('status', ['Active', 'Admitted'])->orderBy('name')->get();
        $doctors = Doctor::with('employee')->where('is_active', true)->get();
        $employees = Employee::where('employment_status', 'Active')->orderBy('first_name')->get();

        $stats = [
            'pending' => BloodRequest::where('status', 'Pending')->count(),
            'emergency' => BloodRequest::where('urgency', 'Emergency')->whereNotIn('status', ['Fulfilled', 'Cancelled', 'Rejected'])->count(),
            'today' => BloodRequest::whereDate('created_at', today())->count(),
            'fulfilled' => BloodRequest::whereDate('fulfilled_at', today())->count(),
        ];

        return view('bloodbank.blood_request_index', compact(
            'requests',
            'bloodGroups',
            'patients',
            'doctors',
            'employees',
            'stats'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'blood_group' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'component' => 'required|in:Whole Blood,Packed RBC,Platelets,Fresh Frozen Plasma,Cryoprecipitate',
            'units_required' => 'required|integer|min:1|max:20',
            'urgency' => 'required|in:Routine,Urgent,Emergency',
            'indication' => 'required|string|max:500',
            'ward' => 'nullable|string|max:100',
            'bed_number' => 'nullable|string|max:20',
            'patient_hemoglobin' => 'nullable|numeric|min:1|max:25',
            'notes' => 'nullable|string',
        ]);

        BloodRequest::create($validated);
        return redirect()->route('blood-bank.requests.index')
            ->with('success', 'Blood request submitted.');
    }

    public function show(BloodRequest $request)
    {
        $request->load([
            'patient',
            'doctor.employee',
            'processedBy',
            'crossmatches.donation.donor',
            'crossmatches.performedBy',
            'issues.issuedBy',
        ]);

        $availableBags = BloodDonation::available()
            ->where('blood_group', $request->blood_group)
            ->where('component', $request->component)
            ->get();

        return view('bloodbank.blood_request_show', compact('request', 'availableBags'));
    }


    public function updateStatus(Request $request, $id) // Model binding ki jagah ID use ki hai
    {
        $request->validate([
            'status' => 'required|in:Pending,Under Review,Crossmatch,Approved,Partially Fulfilled,Fulfilled,Cancelled,Rejected',
            'units_approved' => 'nullable|integer|min:0',
            'rejection_reason' => 'nullable|string|max:500',
            'processed_by' => 'nullable|exists:employees,id',
        ]);

        // Request ko find karein
        $bloodRequest = BloodRequest::findOrFail($id);

        // Manual update (Is se logic kharab nahi hoga aur update ki guarantee hogi)
        $bloodRequest->status = $request->status;

        if ($request->filled('units_approved')) {
            $bloodRequest->units_approved = $request->units_approved;
        }

        if ($request->filled('rejection_reason')) {
            $bloodRequest->rejection_reason = $request->rejection_reason;
        }

        if ($request->filled('processed_by')) {
            $bloodRequest->processed_by = $request->processed_by;
        }

        // Timestamps update
        if ($request->status === 'Approved') {
            $bloodRequest->approved_at = now();
        }

        if ($request->status === 'Fulfilled') {
            $bloodRequest->fulfilled_at = now();
        }

        // Final Save
        $bloodRequest->save();

        return back()->with('success', 'Request status updated to ' . $request->status);
    }

    public function destroy(BloodRequest $bloodRequest)
    {
        if (in_array($bloodRequest->status, ['Fulfilled', 'Crossmatch'])) {
            return back()->with('error', 'Cannot delete a fulfilled or in-crossmatch request.');
        }
        $bloodRequest->delete();
        return redirect()->route('blood-bank.requests.index')->with('success', 'Request removed.');
    }

}
