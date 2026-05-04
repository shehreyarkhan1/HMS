<?php

namespace App\Http\Controllers\BloodBank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\BloodDonation;
use App\Models\BloodCrossmatch;

class BloodCrossmatchController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'blood_request_id' => 'required|exists:blood_requests,id',
            'blood_donation_id' => 'required|exists:blood_donations,id',
            'method' => 'required|in:Immediate Spin,AHG,Electronic,Saline',
            'performed_by' => 'nullable|exists:employees,id',
            'notes' => 'nullable|string',
        ]);

        BloodCrossmatch::create(array_merge($validated, [
            'result' => 'Pending',
            'performed_at' => now(),
        ]));

        // Update request status
        BloodRequest::find($validated['blood_request_id'])
            ->update(['status' => 'Crossmatch']);

        // Reserve the donation bag
        BloodDonation::find($validated['blood_donation_id'])
            ->update(['status' => 'Reserved']);

        return back()->with('success', 'Cross-match initiated.');
    }

    public function updateResult(Request $request, BloodCrossmatch $crossmatch)
    {
        $request->validate([
            'result' => 'required|in:Compatible,Incompatible',
            'notes' => 'nullable|string',
        ]);

        $crossmatch->update([
            'result' => $request->result,
            'notes' => $request->notes,
            'performed_at' => now(),
        ]);

        // If incompatible → release the bag
        if ($request->result === 'Incompatible') {
            $crossmatch->donation->update(['status' => 'Available']);
            $crossmatch->request->update(['status' => 'Approved']);
        } elseif ($request->result === 'Compatible') {
            $crossmatch->request->update(['status' => 'Approved']);
        }

        return back()->with('success', 'Cross-match result recorded.');
    }
}
