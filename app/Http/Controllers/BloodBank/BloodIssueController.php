<?php

namespace App\Http\Controllers\BloodBank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\BloodDonation;
use App\Models\BloodIssue;

class BloodIssueController extends Controller
{
     public function store(Request $request)
    {
        $validated = $request->validate([
            'blood_request_id'  => 'required|exists:blood_requests,id',
            'blood_donation_id' => 'required|exists:blood_donations,id',
            'patient_id'        => 'required|exists:patients,id',
            'blood_group'       => 'required|string',
            'component'         => 'required|string',
            'bag_number'        => 'nullable|string|max:50',
            'volume_ml'         => 'nullable|numeric',
            'issued_by'         => 'nullable|exists:employees,id',
            'notes'             => 'nullable|string',
        ]);
 
        BloodIssue::create(array_merge($validated, ['issued_at' => now()]));
 
        // Update request status
        $bloodRequest = BloodRequest::find($validated['blood_request_id']);
        $issuedCount  = BloodIssue::where('blood_request_id', $bloodRequest->id)->count();
        $newStatus    = $issuedCount >= $bloodRequest->units_approved ? 'Fulfilled' : 'Partially Fulfilled';
        $bloodRequest->update(['status' => $newStatus, 'fulfilled_at' => now()]);
 
        return back()->with('success', 'Blood issued successfully.');
    }
 
    public function updateReaction(Request $request, BloodIssue $issue)
    {
        $request->validate([
            'reaction_observed' => 'boolean',
            'reaction_type'     => 'required|in:None,Febrile,Allergic,Haemolytic,TACO,TRALI,Other',
            'reaction_notes'    => 'nullable|string',
        ]);
 
        $issue->update([
            'reaction_observed'          => $request->boolean('reaction_observed'),
            'reaction_type'              => $request->reaction_type,
            'reaction_notes'             => $request->reaction_notes,
            'transfusion_started_at'     => $request->transfusion_started_at ?? $issue->transfusion_started_at,
            'transfusion_completed_at'   => $request->transfusion_completed_at,
        ]);
 
        return back()->with('success', 'Transfusion record updated.');
    }
}
