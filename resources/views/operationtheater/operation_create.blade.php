@extends('layouts.master')

@section('title', 'Schedule Surgery')
@section('page-title', 'Schedule Surgery')
@section('breadcrumb', 'Home / OT / Schedule Surgery')

@push('styles')
<style>
    .form-section {
        background:#fff; border:1px solid #e2e8f0; border-radius:12px;
        padding:24px; margin-bottom:20px;
    }
    .section-title {
        font-size:13px; font-weight:600; color:#374151;
        text-transform:uppercase; letter-spacing:.05em;
        padding-bottom:12px; margin-bottom:20px;
        border-bottom:1px solid #f1f5f9;
        display:flex; align-items:center; gap:8px;
    }
    .section-title i { color:#6366f1; font-size:14px; }

    .form-label { font-size:12px; font-weight:500; color:#374151; margin-bottom:4px; }

    .form-control, .form-select {
        font-size:13px; border:1px solid #e2e8f0; border-radius:8px;
        color:#374151; background:#f8fafc; height:38px; padding:0 12px;
    }
    .form-control:focus, .form-select:focus {
        border-color:#93c5fd; background:#fff; box-shadow:none;
    }
    textarea.form-control { height:auto; padding:10px 12px; }

    .required-star { color:#ef4444; }

    /* Team member row */
    .team-row {
        background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px;
        padding:12px; margin-bottom:8px;
    }
    .team-row select, .team-row input {
        font-size:13px; border:1px solid #e2e8f0; border-radius:6px;
        background:#fff; height:34px; padding:0 10px; width:100%;
    }

    /* Checkbox toggle */
    .check-card {
        display:flex; align-items:center; gap:10px;
        background:#f8fafc; border:1px solid #e2e8f0;
        border-radius:8px; padding:10px 14px; cursor:pointer;
    }
    .check-card input[type=checkbox] { width:16px; height:16px; accent-color:#6366f1; }
    .check-card-label { font-size:13px; color:#374151; font-weight:500; }
    .check-card-sub   { font-size:11px; color:#94a3b8; }
</style>
@endpush

@section('content')

<form method="POST" action="{{ route('ot.schedules.store') }}">
@csrf

<div class="row g-4">

    {{-- ── LEFT COLUMN ──────────────────────────────────────────────────── --}}
    <div class="col-12 col-xl-8">

        {{-- Patient & Room --}}
        <div class="form-section">
            <div class="section-title">
                <i class="bi bi-person-plus"></i>
                Patient & Room
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Patient <span class="required-star">*</span></label>
                    <select name="patient_id" class="form-select" required>
                        <option value="">— Select Patient —</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }} ({{ $patient->mrn }})
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="text-danger" style="font-size:11px;margin-top:4px">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">OT Room</label>
                    <select name="ot_room_id" class="form-select">
                        <option value="">— Assign Later —</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('ot_room_id') == $room->id ? 'selected' : '' }}>
                                {{ $room->room_code }} – {{ $room->name }} ({{ $room->room_type }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Surgery Details --}}
        <div class="form-section">
            <div class="section-title">
                <i class="bi bi-clipboard2-pulse"></i>
                Surgery Details
            </div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Diagnosis / Indication <span class="required-star">*</span></label>
                    <input type="text" name="diagnosis" class="form-control"
                           value="{{ old('diagnosis') }}" placeholder="Pre-operative diagnosis" required>
                    @error('diagnosis') <div class="text-danger" style="font-size:11px;margin-top:4px">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Procedure Name <span class="required-star">*</span></label>
                    <input type="text" name="procedure_name" class="form-control"
                           value="{{ old('procedure_name') }}" placeholder="e.g. Appendectomy, CABG, Laparoscopic Cholecystectomy" required>
                    @error('procedure_name') <div class="text-danger" style="font-size:11px;margin-top:4px">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Procedure Details</label>
                    <textarea name="procedure_details" class="form-control" rows="3"
                              placeholder="Detailed description of the surgical procedure...">{{ old('procedure_details') }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Surgery Type <span class="required-star">*</span></label>
                    <select name="surgery_type" class="form-select" required>
                        @foreach(['Elective','Urgent','Emergency','Diagnostic'] as $t)
                            <option value="{{ $t }}" {{ old('surgery_type', 'Elective') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Priority <span class="required-star">*</span></label>
                    <select name="priority" class="form-select" required>
                        @foreach(['Routine','Priority','Urgent','Emergency'] as $p)
                            <option value="{{ $p }}" {{ old('priority', 'Routine') == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Anesthesia Type</label>
                    <select name="anesthesia_type" class="form-select">
                        <option value="">— Select —</option>
                        @foreach(['General','Local','Regional','Spinal','Epidural','Sedation','None'] as $a)
                            <option value="{{ $a }}" {{ old('anesthesia_type') == $a ? 'selected' : '' }}>{{ $a }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Scheduling --}}
        <div class="form-section">
            <div class="section-title">
                <i class="bi bi-calendar3"></i>
                Scheduling
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Date <span class="required-star">*</span></label>
                    <input type="date" name="scheduled_date" class="form-control"
                           value="{{ old('scheduled_date', today()->format('Y-m-d')) }}"
                           min="{{ today()->format('Y-m-d') }}" required>
                    @error('scheduled_date') <div class="text-danger" style="font-size:11px;margin-top:4px">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Start Time <span class="required-star">*</span></label>
                    <input type="time" name="scheduled_time" class="form-control"
                           value="{{ old('scheduled_time') }}" required>
                    @error('scheduled_time') <div class="text-danger" style="font-size:11px;margin-top:4px">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Estimated Duration (mins) <span class="required-star">*</span></label>
                    <input type="number" name="estimated_duration_mins" class="form-control"
                           value="{{ old('estimated_duration_mins', 60) }}" min="10" max="600" required>
                    @error('estimated_duration_mins') <div class="text-danger" style="font-size:11px;margin-top:4px">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Pre-op Instructions --}}
        <div class="form-section">
            <div class="section-title">
                <i class="bi bi-journal-text"></i>
                Pre-operative Instructions
            </div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Pre-op Instructions</label>
                    <textarea name="pre_op_instructions" class="form-control" rows="3"
                              placeholder="NPO from midnight, bowel prep, medication hold instructions...">{{ old('pre_op_instructions') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Pre-op Assessment Notes</label>
                    <textarea name="pre_op_assessment_notes" class="form-control" rows="2"
                              placeholder="ASA classification, fitness for surgery, investigations...">{{ old('pre_op_assessment_notes') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Additional Notes</label>
                    <textarea name="notes" class="form-control" rows="2"
                              placeholder="Any special requirements or notes...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        {{-- OT Team --}}
        <div class="form-section">
            <div class="section-title">
                <i class="bi bi-people"></i>
                OT Team Members
                <span style="font-size:11px;color:#94a3b8;font-weight:400;text-transform:none;letter-spacing:0">
                    (Assistant surgeons, scrub nurses, technicians)
                </span>
            </div>

            <div id="team-container">
                {{-- JS will add rows here --}}
            </div>

            <button type="button" id="add-team-member"
                    class="btn btn-sm btn-outline-primary px-3 mt-1" style="font-size:12px">
                <i class="bi bi-plus-lg me-1"></i>Add Team Member
            </button>

            {{-- Template (hidden) --}}
            <template id="team-row-template">
                <div class="team-row">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label style="font-size:11px;color:#64748b;display:block;margin-bottom:3px">Role</label>
                            <select name="team[__INDEX__][role]" required>
                                <option value="">— Role —</option>
                                @foreach(['Assistant Surgeon','Scrub Nurse','Circulating Nurse','OT Technician','Anesthesia Technician','Perfusionist','Observer','Other'] as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label style="font-size:11px;color:#64748b;display:block;margin-bottom:3px">Doctor (if applicable)</label>
                            <select name="team[__INDEX__][doctor_id]">
                                <option value="">— Select Doctor —</option>
                                @foreach($surgeons as $doc)
                                    <option value="{{ $doc->id }}">
                                        Dr. {{ $doc->employee?->first_name }} {{ $doc->employee?->last_name }}
                                        ({{ $doc->specialization }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label style="font-size:11px;color:#64748b;display:block;margin-bottom:3px">Staff / Nurse</label>
                            <select name="team[__INDEX__][employee_id]">
                                <option value="">— Select Staff —</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">
                                        {{ $emp->first_name }} {{ $emp->last_name }}
                                        ({{ $emp->designation }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger px-2 remove-team"
                                    style="height:34px">
                                <i class="bi bi-trash" style="font-size:11px"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

    </div>

    {{-- ── RIGHT COLUMN ─────────────────────────────────────────────────── --}}
    <div class="col-12 col-xl-4">

        {{-- Surgeon & Anesthesiologist --}}
        <div class="form-section">
            <div class="section-title">
                <i class="bi bi-person-badge"></i>
                Medical Team
            </div>
            <div class="mb-3">
                <label class="form-label">Primary Surgeon <span class="required-star">*</span></label>
                <select name="surgeon_id" class="form-select" required>
                    <option value="">— Select Surgeon —</option>
                    @foreach($surgeons as $doc)
                        <option value="{{ $doc->id }}" {{ old('surgeon_id') == $doc->id ? 'selected' : '' }}>
                            Dr. {{ $doc->employee?->first_name }} {{ $doc->employee?->last_name }}
                            — {{ $doc->specialization }}
                        </option>
                    @endforeach
                </select>
                @error('surgeon_id') <div class="text-danger" style="font-size:11px;margin-top:4px">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="form-label">Anesthesiologist</label>
                    <select name="anesthesiologist_id" class="form-select @error('anesthesiologist_id') is-invalid @enderror">
                        <option value="">— Select Anesthesiologist —</option>
                        
                        {{-- Yahan $anesthesiologists use karein --}}
                        @foreach($anesthesiologists as $doc)
                            <option value="{{ $doc->id }}" {{ old('anesthesiologist_id') == $doc->id ? 'selected' : '' }}>
                                Dr. {{ $doc->employee?->first_name }} {{ $doc->employee?->last_name }} 
                                ({{ $doc->specialization }}) {{-- Optional: Sath specialization dikhane ke liye --}}
                            </option>
                        @endforeach
                    </select>
                    @error('anesthesiologist_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
            </div>
        </div>

        {{-- Consent & Assessment --}}
        <div class="form-section">
            <div class="section-title">
                <i class="bi bi-shield-check"></i>
                Consent & Assessment
            </div>
            <div class="mb-3">
                <label class="check-card" for="consent_obtained">
                    <input type="checkbox" name="consent_obtained" id="consent_obtained"
                           value="1" {{ old('consent_obtained') ? 'checked' : '' }}>
                    <div>
                        <div class="check-card-label">Consent Obtained</div>
                        <div class="check-card-sub">Patient / guardian has signed consent</div>
                    </div>
                </label>
            </div>
            <div class="mb-3">
                <label class="form-label">Consent Given By</label>
                <input type="text" name="consent_by" class="form-control"
                       value="{{ old('consent_by') }}" placeholder="Patient name or guardian name">
            </div>
            <div>
                <label class="check-card" for="pre_op_assessment_done">
                    <input type="checkbox" name="pre_op_assessment_done" id="pre_op_assessment_done"
                           value="1" {{ old('pre_op_assessment_done') ? 'checked' : '' }}>
                    <div>
                        <div class="check-card-label">Pre-op Assessment Done</div>
                        <div class="check-card-sub">Fitness for surgery confirmed</div>
                    </div>
                </label>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px">
            <button type="submit" class="btn btn-success w-100 mb-2" style="height:40px;font-size:14px;font-weight:500">
                <i class="bi bi-calendar-check me-2"></i>Schedule Surgery
            </button>
            <a href="{{ route('ot.index') }}"
               class="btn btn-outline-secondary w-100" style="height:40px;font-size:14px">
                Cancel
            </a>
        </div>

    </div>

</div>
</form>

@endsection

@push('scripts')
<script>
let teamIndex = 0;

document.getElementById('add-team-member').addEventListener('click', function () {
    const template = document.getElementById('team-row-template');
    const container = document.getElementById('team-container');
    const clone = template.content.cloneNode(true);

    // Replace __INDEX__ with real index
    clone.querySelectorAll('[name]').forEach(el => {
        el.name = el.name.replace('__INDEX__', teamIndex);
    });

    container.appendChild(clone);
    teamIndex++;

    // Remove button
    container.querySelector('.team-row:last-child .remove-team')
        .addEventListener('click', function () {
            this.closest('.team-row').remove();
        });
});
</script>
@endpush