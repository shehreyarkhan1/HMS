
@extends('layouts.master')

@section('title', 'Discharge Patient')
@section('page-title', 'Discharge Patient')
@section('breadcrumb', 'Home / Wards / Discharge / ' . $patient->name)

@push('styles')
<style>
    .form-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:16px}
    .form-card-header{padding:13px 20px;border-bottom:1px solid #e2e8f0;background:#f8fafc}
    .form-card-header h6{margin:0;font-size:13px;font-weight:600;color:#374151}
    .form-card-body{padding:20px}
    .form-label{font-size:12px;font-weight:500;color:#374151;margin-bottom:5px}
    .form-control,.form-select{font-size:13px;border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px}
    .form-control:focus,.form-select:focus{border-color:#93c5fd;box-shadow:0 0 0 3px rgba(59,130,246,.1)}
    .required-star{color:#ef4444}
</style>
@endpush

@section('content')

{{-- Patient Info Banner --}}
<div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:14px 20px;margin-bottom:20px">
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-person-fill" style="font-size:20px;color:#d97706"></i>
        <div>
            <div style="font-size:14px;font-weight:600;color:#92400e">{{ $patient->name }}</div>
            <div style="font-size:12px;color:#b45309">
                MRN: {{ $patient->mrn }}
                @if($bed) &bull; Bed: {{ $bed->bed_number }} ({{ $bed->ward->name ?? '—' }})
                &bull; Admitted: {{ \Carbon\Carbon::parse($bed->admitted_at)->format('d M Y') }}
                ({{ \Carbon\Carbon::parse($bed->admitted_at)->diffInDays(now()) }} days)
                @endif
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('ward.discharge.store', $patient->id) }}">
@csrf

<div class="row g-3">
    <div class="col-12 col-lg-8">

        {{-- Discharge Info --}}
        <div class="form-card">
            <div class="form-card-header">
                <h6><i class="bi bi-box-arrow-right me-2 text-danger"></i>Discharge Information</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Discharging Doctor <span class="required-star">*</span></label>
                        <select name="doctor_id" class="form-select" required>
                            <option value="">Select doctor</option>
                            @foreach($doctors as $doc)
                                <option value="{{ $doc->id }}" {{ ($doctor && $doctor->id == $doc->id) ? 'selected' : '' }}>
                                    Dr. {{ $doc->employee->full_name ?? $doc->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Discharge Date <span class="required-star">*</span></label>
                        <input type="date" name="discharge_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Discharge Type <span class="required-star">*</span></label>
                        <select name="discharge_type" class="form-select" required>
                            <option value="Normal">Normal (Recovered)</option>
                            <option value="LAMA">LAMA (Against Medical Advice)</option>
                            <option value="Referred">Referred to Another Hospital</option>
                            <option value="Expired">Expired</option>
                            <option value="Absconded">Absconded</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Condition at Discharge <span class="required-star">*</span></label>
                        <select name="condition_at_discharge" class="form-select" required>
                            <option value="Recovered">Recovered</option>
                            <option value="Improved">Improved</option>
                            <option value="Same">Same</option>
                            <option value="Deteriorated">Deteriorated</option>
                            <option value="Expired">Expired</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Clinical Summary --}}
        <div class="form-card">
            <div class="form-card-header">
                <h6><i class="bi bi-file-medical me-2 text-primary"></i>Clinical Summary</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Admission Diagnosis <span class="required-star">*</span></label>
                        <textarea name="admission_diagnosis" rows="2" class="form-control"
                            placeholder="Diagnosis at the time of admission..." required></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Final Diagnosis <span class="required-star">*</span></label>
                        <textarea name="final_diagnosis" rows="2" class="form-control"
                            placeholder="Final confirmed diagnosis..." required></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Treatment Summary <span class="required-star">*</span></label>
                        <textarea name="treatment_summary" rows="3" class="form-control"
                            placeholder="Summary of treatment given during hospital stay..." required></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Procedures Done</label>
                        <textarea name="procedures_done" rows="2" class="form-control"
                            placeholder="Surgeries, procedures performed..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Discharge Instructions --}}
        <div class="form-card">
            <div class="form-card-header">
                <h6><i class="bi bi-card-checklist me-2 text-success"></i>Discharge Instructions</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Discharge Instructions <span class="required-star">*</span></label>
                        <textarea name="discharge_instructions" rows="3" class="form-control"
                            placeholder="Instructions for patient after discharge..." required></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Medications on Discharge</label>
                        <textarea name="medications_on_discharge" rows="3" class="form-control"
                            placeholder="Medicine name, dose, frequency, duration...&#10;e.g. Tab. Paracetamol 500mg TDS x 5 days"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Diet Instructions</label>
                        <textarea name="diet_instructions" rows="2" class="form-control"
                            placeholder="Dietary advice..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Activity Instructions</label>
                        <textarea name="activity_instructions" rows="2" class="form-control"
                            placeholder="Bed rest, walking, exercise restrictions..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Follow-up Date</label>
                        <input type="date" name="follow_up_date" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Follow-up With (Doctor)</label>
                        <input type="text" name="follow_up_with" class="form-control"
                            placeholder="Dr. name or department...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Additional Notes</label>
                        <textarea name="notes" rows="2" class="form-control" placeholder="Any additional notes..."></textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Right sidebar --}}
    <div class="col-12 col-lg-4">
        <div class="form-card" style="border-color:#fca5a5">
            <div class="form-card-header" style="background:#fef2f2">
                <h6 style="color:#dc2626"><i class="bi bi-exclamation-triangle me-2"></i>Confirm Discharge</h6>
            </div>
            <div class="form-card-body">
                <p style="font-size:13px;color:#374151;line-height:1.7">
                    This will:
                </p>
                <ul style="font-size:13px;color:#374151;line-height:2">
                    <li>Create discharge summary</li>
                    <li>Free up bed <strong>{{ $bed->bed_number ?? '—' }}</strong></li>
                    <li>Mark patient as discharged</li>
                </ul>
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-danger" style="height:42px;font-size:14px;font-weight:500">
                        <i class="bi bi-box-arrow-right me-2"></i>Confirm Discharge
                    </button>
                    <a href="{{ route('ward.patient.show', $patient->id) }}"
                       class="btn btn-outline-secondary" style="height:42px;font-size:14px">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>

</form>
@endsection
