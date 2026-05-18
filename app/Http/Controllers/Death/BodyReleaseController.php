<?php

namespace App\Http\Controllers\Death;

use App\Http\Controllers\Controller;
use App\Models\BodyReleaseRecord;
use App\Models\MortuaryRecord;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BodyReleaseController extends Controller
{
   // ── CREATE — Alag page ───────────────────────────────────────────
    public function create(MortuaryRecord $mortuary)
    {
        // Certificate check
        if (!$mortuary->deathCertificates()->exists()) {
            return redirect()
                ->route('mortuary.show', $mortuary->id)
                ->with('error', 'Issue a death certificate first before releasing the body.');
        }

        // Already released check
        if ($mortuary->bodyRelease) {
            return redirect()
                ->route('mortuary.show', $mortuary->id)
                ->with('error', 'Body has already been released.');
        }

        $mortuary->load(['patient', 'deathCertificates', 'latestCertificate']);
        $employees = Employee::where('employment_status', 'Active')->orderBy('first_name')->get();

        return view('mortuarydeath.release_create', compact('mortuary', 'employees'));
    }

    // ── STORE ────────────────────────────────────────────────────────
    public function store(Request $request, MortuaryRecord $mortuary)
    {
        if ($mortuary->bodyRelease) {
            return redirect()
                ->route('mortuary.show', $mortuary->id)
                ->with('error', 'Body has already been released.');
        }

        if (!$mortuary->deathCertificates()->exists()) {
            return redirect()
                ->route('mortuary.show', $mortuary->id)
                ->with('error', 'Cannot release body: Death certificate not yet issued.');
        }

        $validated = $request->validate([
            'released_to_name'           => 'required|string|max:100',
            'released_to_cnic'           => 'required|string|max:15',
            'released_to_relation'       => 'required|string|max:50',
            'released_to_phone'          => 'required|string|max:15',
            'released_to_address'        => 'nullable|string',
            'witness_1_name'             => 'nullable|string|max:100',
            'witness_1_cnic'             => 'nullable|string|max:15',
            'witness_2_name'             => 'nullable|string|max:100',
            'witness_2_cnic'             => 'nullable|string|max:15',
            'released_at'                => 'required|date',
            'released_by'                => 'required|exists:employees,id',
            'transport_type'             => 'nullable|in:Hospital Ambulance,Private Ambulance,Private Vehicle,On Foot,Other',
            'vehicle_number'             => 'nullable|string|max:20',
            'destination'                => 'required|string|max:255',
            'death_certificate_provided' => 'boolean',
            'death_certificate_number'   => 'nullable|string|max:50',
            'belongings_returned'        => 'boolean',
            'belongings_list'            => 'nullable|string',
            'valuables_amount'           => 'nullable|numeric|min:0',
            'valuables_returned'         => 'boolean',
            'police_clearance_obtained'  => 'boolean',
            'police_clearance_number'    => $mortuary->is_medico_legal ? 'required|string|max:50' : 'nullable|string|max:50',
            'notes'                      => 'nullable|string',
        ], [
            'police_clearance_number.required' => 'Police clearance number is required for MLC cases.',
        ]);

        $validated['mortuary_record_id'] = $mortuary->id;

        try {
            BodyReleaseRecord::create($validated);

            return redirect()
                ->route('mortuary.show', $mortuary->id)
                ->with('success', 'Body released successfully. Record has been updated.');

        } catch (\Exception $e) {
            Log::error('Body release failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Release failed: ' . $e->getMessage());
        }
    }
}
