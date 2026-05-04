<?php

namespace App\Http\Controllers\BloodBank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodDonation;
use App\Models\BloodDonor;
use App\Models\Employee;
use App\Models\BloodInventory;
use Illuminate\Support\Facades\DB;

class BloodDonationController extends Controller
{
     public function index(Request $request)
    {
        $query = BloodDonation::with('donor');
 
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('donation_id', 'like', "%$s%")
                  ->orWhere('bag_number', 'like', "%$s%")
                  ->orWhereHas('donor', fn($q) => $q->where('name', 'like', "%$s%"));
            });
        }
        if ($request->filled('blood_group'))      $query->where('blood_group', $request->blood_group);
        if ($request->filled('component'))        $query->where('component', $request->component);
        if ($request->filled('status'))           $query->where('status', $request->status);
        if ($request->filled('screening_status')) $query->where('screening_status', $request->screening_status);
 
        $donations   = $query->orderByDesc('donation_date')->paginate(15)->withQueryString();
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
        $donors      = BloodDonor::eligible()->orderBy('name')->get();
        $employees   = Employee::where('employment_status', 'Active')->orderBy('first_name')->get();
        $stats = [
            'total'       => BloodDonation::count(),
            'available'   => BloodDonation::available()->count(),
            'expiring'    => BloodDonation::expiringSoon(3)->count(),
            'today'       => BloodDonation::whereDate('donation_date', today())->count(),
        ];
 
        return view('bloodbank.donation_index', compact(
            'donations', 'bloodGroups', 'donors', 'employees', 'stats'
        ));
    }
 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_id'          => 'required|exists:blood_donors,id',
            'donation_date'     => 'required|date|before_or_equal:today',
            'donation_time'     => 'nullable',
            'blood_group'       => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'volume_ml'         => 'nullable|numeric|min:100|max:600',
            'bag_number'        => 'nullable|string|max:50|unique:blood_donations,bag_number',
            'component'         => 'required|in:Whole Blood,Packed RBC,Platelets,Fresh Frozen Plasma,Cryoprecipitate',
            'collected_by'      => 'nullable|exists:employees,id',
            'notes'             => 'nullable|string',
        ]);
 
        BloodDonation::create($validated);
        return back()->with('success', 'Donation recorded. Bag sent for screening.');
    }
 
    public function updateScreening(Request $request, BloodDonation $donation)
    {
        $validated = $request->validate([
            'screening_status' => 'required|in:Pending,Passed,Failed,Discarded',
            'hiv_tested'       => 'boolean',
            'hbsag_tested'     => 'boolean',
            'hcv_tested'       => 'boolean',
            'vdrl_tested'      => 'boolean',
            'malaria_tested'   => 'boolean',
            'screening_notes'  => 'nullable|string',
        ]);
 
        DB::transaction(function () use ($validated, $request, $donation) {
            $oldStatus = $donation->screening_status;
 
            $donation->update(array_merge($validated, [
                'hiv_tested'     => $request->boolean('hiv_tested'),
                'hbsag_tested'   => $request->boolean('hbsag_tested'),
                'hcv_tested'     => $request->boolean('hcv_tested'),
                'vdrl_tested'    => $request->boolean('vdrl_tested'),
                'malaria_tested' => $request->boolean('malaria_tested'),
            ]));
 
            // If failed → remove from available stock
            if ($validated['screening_status'] === 'Failed' && $oldStatus !== 'Failed') {
                $donation->update(['status' => 'Discarded']);
                BloodInventory::deductUnits($donation->blood_group, $donation->component);
            }
        });
 
        return back()->with('success', 'Screening result updated.');
    }
 
    public function destroy(BloodDonation $donation)
    {
        if (in_array($donation->status, ['Reserved', 'Issued'])) {
            return back()->with('error', 'Cannot delete a reserved or issued bag.');
        }
        $donation->delete();
        return back()->with('success', 'Donation record removed.');
    }
}
