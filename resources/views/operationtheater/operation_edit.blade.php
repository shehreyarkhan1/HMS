@extends('layouts.master')

@section('title', 'Edit — ' . $ot->surgery_id)
@section('page-title', 'Edit Surgery')
@section('breadcrumb', 'Home / OT / ' . $ot->surgery_id . ' / Edit')

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
    .form-control:focus, .form-select:focus { border-color:#93c5fd; background:#fff; box-shadow:none; }
    textarea.form-control { height:auto; padding:10px 12px; }
    .required-star { color:#ef4444; }

    .team-row { background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px; margin-bottom:8px; }
    .team-row select { font-size:13px; border:1px solid #e2e8f0; border-radius:6px; background:#fff; height:34px; padding:0 10px; width:100%; }

    .check-card { display:flex; align-items:center; gap:10px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px 14px; cursor:pointer; }
    .check-card input[type=checkbox] { width:16px; height:16px; accent-color:#6366f1; }
    .check-card-label { font-size:13px; color:#374151; font-weight:500; }
    .check-card-sub   { font-size:11px; color:#94a3b8; }

    /* Post-op section — only shown when status=Completed */
    #postop-section { display:none; }
    #postop-section.show { display:block; }
</style>
@endpush

@section('content')

<form method="POST" action="{{ route('ot.schedules.update', $ot->id) }}">
@csrf @method('PUT')

<div class="row g-4">

    <div class="col-12 col-xl-8">

        {{-- Patient & Room --}}
        <div class="form-section">
            <div class="section-title"><i class="bi bi-person-plus"></i>Patient & Room</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Patient <span class="required-star">*</span></label>
                    <select name="patient_id" class="form-select" required>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ $ot->patient_id == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }} ({{ $patient->mrn }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">OT Room</label>
                    <select name="ot_room_id" class="form-select">
                        <option value="">— None —</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ $ot->ot_room_id == $room->id ? 'selected' : '' }}>
                                {{ $room->room_code }} – {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Surgery Details --}}
        <div class="form-section">
            <div class="section-title"><i class="bi bi-clipboard2-pulse"></i>Surgery Details</div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Diagnosis <span class="required-star">*</span></label>
                    <input type="text" name="diagnosis" class="form-control" value="{{ old('diagnosis', $ot->diagnosis) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Procedure Name <span class="required-star">*</span></label>
                    <input type="text" name="procedure_name" class="form-control" value="{{ old('procedure_name', $ot->procedure_name) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Procedure Details</label>
                    <textarea name="procedure_details" class="form-control" rows="3">{{ old('procedure_details', $ot->procedure_details) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Surgery Type <span class="required-star">*</span></label>
                    <select name="surgery_type" class="form-select" required>
                        @foreach(['Elective','Urgent','Emergency','Diagnostic'] as $t)
                            <option value="{{ $t }}" {{ old('surgery_type', $ot->surgery_type) == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Priority <span class="required-star">*</span></label>
                    <select name="priority" class="form-select" required>
                        @foreach(['Routine','Priority','Urgent','Emergency'] as $p)
                            <option value="{{ $p }}" {{ old('priority', $ot->priority) == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Anesthesia Type</label>
                    <select name="anesthesia_type" class="form-select">
                        <option value="">— Select —</option>
                        @foreach(['General','Local','Regional','Spinal','Epidural','Sedation','None'] as $a)
                            <option value="{{ $a }}" {{ old('anesthesia_type', $ot->anesthesia_type) == $a ? 'selected' : '' }}>{{ $a }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Scheduling --}}
        <div class="form-section">
            <div class="section-title"><i class="bi bi-calendar3"></i>Scheduling</div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Date <span class="required-star">*</span></label>
                    <input type="date" name="scheduled_date" class="form-control" value="{{ old('scheduled_date', $ot->scheduled_date->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Start Time <span class="required-star">*</span></label>
                    <input type="time" name="scheduled_time" class="form-control" value="{{ old('scheduled_time', substr($ot->scheduled_time, 0, 5)) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Duration (mins) <span class="required-star">*</span></label>
                    <input type="number" name="estimated_duration_mins" class="form-control" value="{{ old('estimated_duration_mins', $ot->estimated_duration_mins) }}" min="10" max="600" required>
                </div>
            </div>
        </div>

        {{-- Post-op (shown when status = Completed) --}}
        <div class="form-section" id="postop-section">
            <div class="section-title"><i class="bi bi-heart-pulse"></i>Post-operative Notes</div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Post-op Notes</label>
                    <textarea name="post_op_notes" class="form-control" rows="3">{{ old('post_op_notes', $ot->post_op_notes) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Complications</label>
                    <textarea name="complications" class="form-control" rows="2">{{ old('complications', $ot->complications) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Post-op Destination</label>
                    <select name="post_op_destination" class="form-select">
                        <option value="">— Select —</option>
                        @foreach(['Recovery Room','ICU','HDU','General Ward','Discharged'] as $dest)
                            <option value="{{ $dest }}" {{ old('post_op_destination', $ot->post_op_destination) == $dest ? 'selected' : '' }}>{{ $dest }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="form-section">
            <div class="section-title"><i class="bi bi-journal-text"></i>Notes & Instructions</div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Pre-op Instructions</label>
                    <textarea name="pre_op_instructions" class="form-control" rows="3">{{ old('pre_op_instructions', $ot->pre_op_instructions) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">General Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $ot->notes) }}</textarea>
                </div>
            </div>
        </div>

        {{-- OT Team --}}
        <div class="form-section">
            <div class="section-title"><i class="bi bi-people"></i>OT Team Members</div>
            <div id="team-container">
                @foreach($ot->teamMembers as $i => $member)
                <div class="team-row">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label style="font-size:11px;color:#64748b;display:block;margin-bottom:3px">Role</label>
                            <select name="team[{{ $i }}][role]">
                                @foreach(['Assistant Surgeon','Scrub Nurse','Circulating Nurse','OT Technician','Anesthesia Technician','Perfusionist','Observer','Other'] as $role)
                                    <option value="{{ $role }}" {{ $member->role == $role ? 'selected' : '' }}>{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label style="font-size:11px;color:#64748b;display:block;margin-bottom:3px">Doctor</label>
                            <select name="team[{{ $i }}][doctor_id]">
                                <option value="">— None —</option>
                                @foreach($surgeons as $doc)
                                    <option value="{{ $doc->id }}" {{ $member->doctor_id == $doc->id ? 'selected' : '' }}>
                                        Dr. {{ $doc->employee?->first_name }} {{ $doc->employee?->last_name }}
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
                            <button type="button" class="btn btn-sm btn-outline-danger px-2 remove-team" style="height:34px">
                                <i class="bi bi-trash" style="font-size:11px"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <button type="button" id="add-team-member" class="btn btn-sm btn-outline-primary px-3 mt-1" style="font-size:12px">
                <i class="bi bi-plus-lg me-1"></i>Add Team Member
            </button>

            <template id="team-row-template">
                <div class="team-row">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label style="font-size:11px;color:#64748b;display:block;margin-bottom:3px">Role</label>
                            <select name="team[__INDEX__][role]">
                                @foreach(['Assistant Surgeon','Scrub Nurse','Circulating Nurse','OT Technician','Anesthesia Technician','Perfusionist','Observer','Other'] as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label style="font-size:11px;color:#64748b;display:block;margin-bottom:3px">Doctor</label>
                            <select name="team[__INDEX__][doctor_id]">
                                <option value="">— None —</option>
                                @foreach($surgeons as $doc)
                                    <option value="{{ $doc->id }}">Dr. {{ $doc->employee?->first_name }} {{ $doc->employee?->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label style="font-size:11px;color:#64748b;display:block;margin-bottom:3px">Staff</label>
                            <select name="team[__INDEX__][employee_id]">
                                <option value="">— None —</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger px-2 remove-team" style="height:34px">
                                <i class="bi bi-trash" style="font-size:11px"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

    </div>

    {{-- RIGHT COLUMN --}}
    <div class="col-12 col-xl-4">

        {{-- Status --}}
        <div class="form-section">
            <div class="section-title"><i class="bi bi-activity"></i>Status</div>
            <select name="status" class="form-select" id="status-select" required>
                @foreach(['Scheduled','Confirmed','Preparing','In-Progress','Completed','Postponed','Cancelled'] as $s)
                    <option value="{{ $s }}" {{ old('status', $ot->status) == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>

            {{-- Postpone / Cancel reason --}}
            <div id="reason-box" class="mt-3" style="display:none">
                <label class="form-label" id="reason-label">Reason</label>
                <input type="text" name="postpone_reason" id="postpone-reason" class="form-control" value="{{ old('postpone_reason', $ot->postpone_reason) }}" style="display:none" placeholder="Reason for postponement">
                <input type="text" name="cancellation_reason" id="cancel-reason" class="form-control" value="{{ old('cancellation_reason', $ot->cancellation_reason) }}" style="display:none" placeholder="Reason for cancellation">
                <input type="date" name="rescheduled_date" id="reschedule-date" class="form-control mt-2" value="{{ old('rescheduled_date', $ot->rescheduled_date?->format('Y-m-d')) }}" style="display:none">
            </div>
        </div>

        {{-- Medical Team --}}
        <div class="form-section">
            <div class="section-title"><i class="bi bi-person-badge"></i>Medical Team</div>
            <div class="mb-3">
                <label class="form-label">Primary Surgeon <span class="required-star">*</span></label>
                <select name="surgeon_id" class="form-select" required>
                    @foreach($surgeons as $doc)
                        <option value="{{ $doc->id }}" {{ old('surgeon_id', $ot->surgeon_id) == $doc->id ? 'selected' : '' }}>
                            Dr. {{ $doc->employee?->first_name }} {{ $doc->employee?->last_name }} — {{ $doc->specialization }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Anesthesiologist</label>
                <select name="anesthesiologist_id" class="form-select">
                    <option value="">— None —</option>
                    @foreach($anesthesiologists as $doc)
                        <option value="{{ $doc->id }}" {{ old('anesthesiologist_id', $ot->anesthesiologist_id) == $doc->id ? 'selected' : '' }}>
                            Dr. {{ $doc->employee?->first_name }} {{ $doc->employee?->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Consent --}}
        <div class="form-section">
            <div class="section-title"><i class="bi bi-shield-check"></i>Consent & Pre-op</div>
            <div class="mb-3">
                <label class="check-card" for="consent_obtained">
                    <input type="checkbox" name="consent_obtained" id="consent_obtained" value="1"
                           {{ old('consent_obtained', $ot->consent_obtained) ? 'checked' : '' }}>
                    <div><div class="check-card-label">Consent Obtained</div><div class="check-card-sub">Patient / guardian signed</div></div>
                </label>
            </div>
            <div class="mb-3">
                <label class="form-label">Consent By</label>
                <input type="text" name="consent_by" class="form-control" value="{{ old('consent_by', $ot->consent_by) }}">
            </div>
            <label class="check-card" for="pre_op_assessment_done">
                <input type="checkbox" name="pre_op_assessment_done" id="pre_op_assessment_done" value="1"
                       {{ old('pre_op_assessment_done', $ot->pre_op_assessment_done) ? 'checked' : '' }}>
                <div><div class="check-card-label">Pre-op Assessment Done</div><div class="check-card-sub">Fitness confirmed</div></div>
            </label>
        </div>

        {{-- Buttons --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px">
            <button type="submit" class="btn btn-warning w-100 mb-2" style="height:40px;font-size:14px;font-weight:500">
                <i class="bi bi-check-lg me-2"></i>Save Changes
            </button>
            <a href="{{ route('ot.schedules.show', $ot->id) }}"
               class="btn btn-outline-secondary w-100" style="height:40px;font-size:14px">Cancel</a>
        </div>

    </div>
</div>
</form>

@endsection

@push('scripts')
<script>
// ── Team members ──────────────────────────────────────────────────────────
let teamIndex = {{ $ot->teamMembers->count() }};

document.getElementById('add-team-member').addEventListener('click', function () {
    const template  = document.getElementById('team-row-template');
    const container = document.getElementById('team-container');
    const clone     = template.content.cloneNode(true);
    clone.querySelectorAll('[name]').forEach(el => {
        el.name = el.name.replace('__INDEX__', teamIndex);
    });
    container.appendChild(clone);
    teamIndex++;
    container.querySelector('.team-row:last-child .remove-team')
        .addEventListener('click', function () { this.closest('.team-row').remove(); });
});

document.querySelectorAll('.remove-team').forEach(btn => {
    btn.addEventListener('click', function () { this.closest('.team-row').remove(); });
});

// ── Status change → show/hide fields ──────────────────────────────────────
const statusSelect  = document.getElementById('status-select');
const reasonBox     = document.getElementById('reason-box');
const postponeInput = document.getElementById('postpone-reason');
const cancelInput   = document.getElementById('cancel-reason');
const rescheduleInput = document.getElementById('reschedule-date');
const postopSection = document.getElementById('postop-section');
const reasonLabel   = document.getElementById('reason-label');

function handleStatusChange() {
    const val = statusSelect.value;

    // Post-op section
    postopSection.classList.toggle('show', val === 'Completed');

    // Reason box
    postponeInput.style.display = 'none';
    cancelInput.style.display   = 'none';
    rescheduleInput.style.display = 'none';

    if (val === 'Postponed') {
        reasonBox.style.display  = 'block';
        postponeInput.style.display  = 'block';
        rescheduleInput.style.display = 'block';
        reasonLabel.textContent  = 'Postpone Reason';
    } else if (val === 'Cancelled') {
        reasonBox.style.display = 'block';
        cancelInput.style.display = 'block';
        reasonLabel.textContent  = 'Cancellation Reason';
    } else {
        reasonBox.style.display = 'none';
    }
}

statusSelect.addEventListener('change', handleStatusChange);
handleStatusChange(); // run on load
</script>
@endpush