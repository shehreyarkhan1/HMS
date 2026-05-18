<?php

namespace App\Http\Controllers\Death;

use App\Http\Controllers\Controller;
use App\Models\DeathCertificate;
use App\Models\Doctor;
use App\Models\Employee;
use App\Models\MortuaryRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeathCertificateController extends Controller
{
    // ── CREATE — Alag page ───────────────────────────────────────────
    public function create(MortuaryRecord $mortuary)
    {
        // MLC check — postmortem complete hona chahiye
        if ($mortuary->is_medico_legal && $mortuary->postmortem_status !== 'Completed') {
            return redirect()
                ->route('mortuary.show', $mortuary->id)
                ->with('error', 'Cannot issue certificate: Postmortem not yet completed for MLC case. Please update postmortem status first.');
        }

        // Already released check
        if ($mortuary->is_released) {
            return redirect()
                ->route('mortuary.show', $mortuary->id)
                ->with('error', 'Body already released. Cannot issue new certificate.');
        }

        $mortuary->load(['patient', 'declaringDoctor.employee', 'deathCertificates']);
        $doctors = Doctor::with('employee')->where('is_active', true)->get();
        $employees = Employee::where('employment_status', 'Active')->orderBy('first_name')->get();

        return view('mortuarydeath.certificate_create', compact('mortuary', 'doctors', 'employees'));
    }

    // ── STORE ────────────────────────────────────────────────────────
    public function store(Request $request, MortuaryRecord $mortuary)
    {
        if ($mortuary->is_medico_legal && $mortuary->postmortem_status !== 'Completed') {
            return redirect()
                ->route('mortuary.show', $mortuary->id)
                ->with('error', 'Cannot issue certificate: Postmortem not yet completed.');
        }

        $validated = $request->validate([
            'certificate_type' => 'required|in:Hospital Death Certificate,Medico Legal Certificate,Stillbirth Certificate,Duplicate',
            'purpose' => 'required|in:Burial / Funeral,NADRA Registration,Legal Proceedings,Insurance Claim,Embassy / Visa,Other',
            'issued_to_name' => 'required|string|max:100',
            'issued_to_cnic' => 'nullable|string|max:15',
            'issued_to_relation' => 'required|string|max:50',
            'issued_to_phone' => 'nullable|string|max:15',
            'issued_to_address' => 'nullable|string',
            'signed_by_doctor' => 'required|exists:doctors,id',
            'verified_by' => 'nullable|exists:employees,id',
            'issued_by' => 'nullable|exists:employees,id',
            'issued_at' => 'required|date',
            'total_copies' => 'required|integer|min:1|max:10',
            'fee_charged' => 'nullable|numeric|min:0',
            // 'fee_paid' => 'boolean',
            'remarks' => 'nullable|string',
        ]);

        $validated['fee_paid'] = false;
        $validated['mortuary_record_id'] = $mortuary->id;
        $validated['is_verified'] = ! empty($validated['verified_by']);
        $validated['verified_at'] = ! empty($validated['verified_by']) ? now() : null;

        try {
            $certificate = DeathCertificate::create($validated);

            return redirect()
                ->route('mortuary.show', $mortuary->id)
                ->with('success', "Death Certificate {$certificate->certificate_number} issued successfully.");

        } catch (\Exception $e) {
            Log::error('Certificate issue failed: '.$e->getMessage());

            return back()->withInput()->with('error', 'Failed to issue certificate. '.$e->getMessage());
        }
    }

    // ── PRINT ─────────────────────────────────────────────────────────
    public function print(DeathCertificate $certificate)
    {
        $certificate->load([
            'mortuaryRecord.patient',
            'signingDoctor.employee',
            'verifiedBy',
            'issuedBy',
        ]);

        return view('mortuarydeath.certificate_print', compact('certificate'));
    }

    // ── VERIFY ───────────────────────────────────────────────────────
    public function verify(Request $request, DeathCertificate $certificate)
    {
        $request->validate(['verified_by' => 'required|exists:employees,id']);

        $certificate->update([
            'is_verified' => true,
            'verified_by' => $request->verified_by,
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Certificate verified successfully.');
    }

    // ── DESTROY ──────────────────────────────────────────────────────
    public function destroy(DeathCertificate $certificate)
    {
        if ($certificate->is_verified) {
            return back()->with('error', 'Verified certificates cannot be deleted.');
        }

        $mortuaryId = $certificate->mortuary_record_id;
        $certificate->delete();

        return redirect()
            ->route('mortuary.show', $mortuaryId)
            ->with('success', 'Certificate removed.');
    }
}
