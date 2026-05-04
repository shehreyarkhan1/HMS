<?php

namespace App\Http\Controllers\BloodBank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodInventory;
use App\Models\BloodDonor;
use App\Models\BloodDonation;
use App\Models\BloodRequest;
use App\Models\BloodIssue;
use App\Models\BloodCrossmatch;

class BloodBankController extends Controller
{
    public function index()
    {
        // Inventory summary — all blood groups × components
        $inventory = BloodInventory::orderBy('blood_group')->orderBy('component')->get()
            ->groupBy('blood_group');

        // Totals for top stats
        $stats = [
            'total_donors' => BloodDonor::count(),
            'eligible_donors' => BloodDonor::eligible()->count(),
            'units_available' => BloodInventory::sum('units_available'),
            'pending_requests' => BloodRequest::whereIn('status', ['Pending', 'Under Review', 'Crossmatch', 'Approved'])->count(),
            'emergency_requests' => BloodRequest::where('urgency', 'Emergency')
                ->whereNotIn('status', ['Fulfilled', 'Cancelled', 'Rejected'])->count(),
            'expiring_soon' => BloodDonation::expiringSoon(3)->count(),
            'donations_today' => BloodDonation::whereDate('donation_date', today())->count(),
            'issues_today' => BloodIssue::whereDate('issued_at', today())->count(),
        ];

        // Quick inventory grid — all 8 blood groups
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
        $stockGrid = [];
        foreach ($bloodGroups as $bg) {
            $stockGrid[$bg] = BloodInventory::where('blood_group', $bg)
                ->where('component', 'Whole Blood')->first();
        }

        // Recent requests
        $recentRequests = BloodRequest::with(['patient', 'doctor.employee'])
            ->latest()->limit(8)->get();

        // Expiring donations alert
        $expiringDonations = BloodDonation::with('donor')
            ->expiringSoon(5)
            ->orderBy('expiry_date')
            ->limit(10)->get();

        return view('bloodbank.dashboard_index', compact(
            'stats',
            'inventory',
            'stockGrid',
            'bloodGroups',
            'recentRequests',
            'expiringDonations'
        ));
    }
}
