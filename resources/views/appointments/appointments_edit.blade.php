@extends('layouts.master')

@section('title', 'Edit Appointment')
@section('page-title', 'Edit Appointment')
@section('breadcrumb', 'Home / Appointments / Edit')

@push('styles')
<style>
    .edit-wrapper { max-width: 860px; margin: 0 auto; }

    /* ── Hero Banner ── */
    .appt-hero {
        background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
        border-radius: 14px;
        padding: 22px 28px;
        margin-bottom: 24px;
        display: flex; align-items: center; gap: 20px;
        color: #fff; position: relative; overflow: hidden;
    }
    .appt-hero::after {
        content: ''; position: absolute; right: -30px; top: -30px;
        width: 160px; height: 160px; border-radius: 50%;
        background: rgba(255,255,255,.06);
    }
    .hero-token {
        width: 58px; height: 58px; border-radius: 12px;
        background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
        display: flex; flex-direction: column;
        align-items: center; justify-content: center; flex-shrink: 0;
    }
    .hero-token-label { font-size: 9px; opacity: .65; letter-spacing: .05em; text-transform: uppercase; }
    .hero-token-num   { font-size: 20px; font-weight: 800; line-height: 1; }
    .hero-name  { font-size: 18px; font-weight: 700; }
    .hero-meta  { font-size: 12px; opacity: .75; margin-top: 3px; }
    .hero-badge {
        margin-left: auto;
        background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
        border-radius: 20px; padding: 4px 14px;
        font-size: 12px; font-weight: 600;
    }

    /* ── Form Card ── */
    .form-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
    .form-card-header {
        padding: 13px 22px; border-bottom: 1px solid #e2e8f0; background: #f8fafc;
        display: flex; align-items: center; gap: 10px;
    }
    .section-icon { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 13px; }
    .section-title { font-size: 13px; font-weight: 600; color: #1e293b; }
    .form-card-body { padding: 22px; }

    /* ── Controls ── */
    .form-label-custom { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 5px; }
    .form-control-custom, .form-select-custom {
        height: 40px; border: 1px solid #e2e8f0; border-radius: 8px;
        font-size: 13px; color: #1e293b; background: #f8fafc;
        padding: 0 12px; width: 100%;
        transition: border-color .15s, box-shadow .15s, background .15s;
    }
    .form-control-custom:focus, .form-select-custom:focus {
        outline: none; border-color: #3b82f6; background: #fff;
        box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    }
    textarea.form-control-custom { height: auto; padding: 10px 12px; }
    .form-control-custom.is-invalid,
    .form-select-custom.is-invalid { border-color: #f87171; background: #fff5f5; }
    .field-error { font-size: 11px; color: #dc2626; margin-top: 4px; }

    /* ── Type radio pills ── */
    .type-radio-group { display: flex; gap: 8px; flex-wrap: wrap; }
    .type-radio input[type="radio"] { display: none; }
    .type-radio label {
        display: flex; align-items: center; gap: 6px;
        height: 40px; padding: 0 16px;
        border: 1px solid #e2e8f0; border-radius: 8px;
        font-size: 13px; color: #374151;
        cursor: pointer; background: #f8fafc; transition: all .15s;
    }
    .type-radio input[type="radio"]:checked + label {
        border-color: #3b82f6; background: #eff6ff; color: #1d4ed8; font-weight: 500;
    }

    /* ── Availability indicator ── */
    .avail-badge {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 12px; padding: 4px 12px; border-radius: 20px; font-weight: 500; margin-top: 8px;
    }
    .avail-free     { background: #dcfce7; color: #166534; }
    .avail-conflict { background: #fee2e2; color: #991b1b; }
    .avail-loading  { background: #f1f5f9; color: #64748b; }

    /* ── Cancellation section (conditional) ── */
    #cancellation-section { display: none; }
    #cancellation-section.show { display: block; }

    /* ── Action buttons ── */
    .btn-save {
        height: 42px; padding: 0 28px;
        background: linear-gradient(135deg, #1e40af, #3b82f6);
        color: #fff; border: none; border-radius: 9px;
        font-size: 13px; font-weight: 600;
        display: flex; align-items: center; gap: 7px;
        cursor: pointer; transition: opacity .15s, transform .1s;
        box-shadow: 0 2px 8px rgba(59,130,246,.35);
    }
    .btn-save:hover { opacity: .92; transform: translateY(-1px); }
    .btn-cancel {
        height: 42px; padding: 0 20px;
        border: 1px solid #e2e8f0; border-radius: 9px;
        background: #fff; color: #475569;
        font-size: 13px; font-weight: 500;
        display: flex; align-items: center; gap: 7px;
        text-decoration: none; transition: background .15s;
    }
    .btn-cancel:hover { background: #f8fafc; color: #1e293b; }
</style>
@endpush

@section('content')
<div class="edit-wrapper">

    {{-- Validation errors --}}
    @if($errors->any())
    <div class="alert alert-danger d-flex align-items-start gap-2 mb-4"
         style="border-radius:10px;font-size:13px">
        <i class="bi bi-exclamation-triangle-fill mt-1"></i>
        <div>
            <strong>Please fix the following:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Hero Banner --}}
    <div class="appt-hero">
        <div class="hero-token">
            <div class="hero-token-label">Token</div>
            <div class="hero-token-num">
                {{ $appointment->token_number ? '#'.$appointment->token_number : '—' }}
            </div>
        </div>
        <div>
            <div class="hero-name">{{ $appointment->patient->name ?? 'Unknown Patient' }}</div>
            <div class="hero-meta">
                {{ $appointment->patient->mrn ?? '' }}
                &nbsp;·&nbsp; Booked {{ $appointment->created_at->format('d M Y') }}
            </div>
        </div>
        <span class="hero-badge">Editing</span>
    </div>

    <form method="POST" action="{{ route('appointments.update', $appointment->id) }}" novalidate>
        @csrf
        @method('PUT')

        {{-- ① Patient & Doctor --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#dbeafe;color:#1d4ed8">
                    <i class="bi bi-person-fill"></i>
                </div>
                <span class="section-title">Patient &amp; Doctor</span>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-label-custom">Patient <span class="text-danger">*</span></div>
                        <select name="patient_id"
                                class="form-select-custom @error('patient_id') is-invalid @enderror">
                            <option value="">— Select patient —</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('patient_id', $appointment->patient_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} — {{ $p->mrn }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-label-custom">Doctor</div>
                        <select name="doctor_id" id="doctor_id"
                                class="form-select-custom @error('doctor_id') is-invalid @enderror">
                            <option value="">— No preference —</option>
                            @foreach($doctors as $d)
                                <option value="{{ $d->id }}"
                                    {{ old('doctor_id', $appointment->doctor_id) == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ② Schedule --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#dcfce7;color:#15803d">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
                <span class="section-title">Schedule</span>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-label-custom">Date <span class="text-danger">*</span></div>
                        <input type="date" name="appointment_date" id="appt_date"
                               class="form-control-custom @error('appointment_date') is-invalid @enderror"
                               value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}">
                        @error('appointment_date')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">Time</div>
                        
                        <input type="time" name="appointment_time" id="appt_time"
                               class="form-control-custom @error('appointment_time') is-invalid @enderror"
                               value="{{ old('appointment_time', $appointment->appointment_time) }}">
                        <div id="avail-indicator" style="display:none"></div>
                        @error('appointment_time')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">Duration (minutes)</div>
                        <select name="duration_minutes" class="form-select-custom">
                            @foreach([10, 15, 20, 30, 45, 60] as $d)
                                <option value="{{ $d }}"
                                    {{ old('duration_minutes', $appointment->duration_minutes) == $d ? 'selected' : '' }}>
                                    {{ $d }} min
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="form-label-custom">Appointment Type <span class="text-danger">*</span></div>
                        <div class="type-radio-group">
                            @foreach(['OPD','IPD','Follow-up','Emergency'] as $t)
                            <div class="type-radio">
                                <input type="radio" id="type_{{ $t }}" name="type" value="{{ $t }}"
                                    {{ old('type', $appointment->type) == $t ? 'checked' : '' }}>
                                <label for="type_{{ $t }}">{{ $t }}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('type')<div class="field-error mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ③ Status --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#fef9c3;color:#854d0e">
                    <i class="bi bi-lightning-fill"></i>
                </div>
                <span class="section-title">Status</span>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-label-custom">Current Status <span class="text-danger">*</span></div>
                        <select name="status" id="status_select"
                                class="form-select-custom @error('status') is-invalid @enderror">
                            @foreach(['Scheduled','Confirmed','In-Progress','Completed','Cancelled','No-show'] as $s)
                                <option value="{{ $s }}"
                                    {{ old('status', $appointment->status) == $s ? 'selected' : '' }}>
                                    {{ $s }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">Follow-up Date</div>
                        <input type="date" name="follow_up_date"
                               class="form-control-custom @error('follow_up_date') is-invalid @enderror"
                               value="{{ old('follow_up_date', $appointment->follow_up_date?->format('Y-m-d')) }}">
                        @error('follow_up_date')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Cancellation fields — show only when Cancelled --}}
                <div id="cancellation-section"
                     class="mt-3 pt-3 {{ old('status', $appointment->status) === 'Cancelled' ? 'show' : '' }}"
                     style="border-top:1px solid #f1f5f9">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-label-custom">Cancelled By</div>
                            <select name="cancelled_by"
                                    class="form-select-custom @error('cancelled_by') is-invalid @enderror">
                                <option value="">— Select —</option>
                                @foreach(['Patient','Doctor','Admin'] as $cb)
                                    <option value="{{ $cb }}"
                                        {{ old('cancelled_by', $appointment->cancelled_by) == $cb ? 'selected' : '' }}>
                                        {{ $cb }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cancelled_by')<div class="field-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <div class="form-label-custom">Cancellation Reason</div>
                            <input type="text" name="cancellation_reason"
                                   class="form-control-custom @error('cancellation_reason') is-invalid @enderror"
                                   value="{{ old('cancellation_reason', $appointment->cancellation_reason) }}"
                                   placeholder="e.g. Patient request, Doctor unavailable…">
                            @error('cancellation_reason')<div class="field-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ④ Visit Details --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#fef3c7;color:#b45309">
                    <i class="bi bi-clipboard2-fill"></i>
                </div>
                <span class="section-title">Visit Details</span>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-label-custom">Chief Complaint / Reason</div>
                        <input type="text" name="reason"
                               class="form-control-custom @error('reason') is-invalid @enderror"
                               value="{{ old('reason', $appointment->reason) }}"
                               placeholder="e.g. Fever, Follow-up for diabetes…">
                        @error('reason')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <div class="form-label-custom">Notes</div>
                        <textarea name="notes" rows="3"
                                  class="form-control-custom @error('notes') is-invalid @enderror"
                                  placeholder="Reception or clinical notes…">{{ old('notes', $appointment->notes) }}</textarea>
                        @error('notes')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Bar --}}
        <div class="form-card" style="padding:16px 22px">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex gap-2">
                    <a href="{{ route('appointments.show', $appointment->id) }}" class="btn-cancel">
                        <i class="bi bi-arrow-left"></i> Back to Detail
                    </a>
                    <a href="{{ route('appointments.index') }}" class="btn-cancel">
                        <i class="bi bi-list"></i> All Appointments
                    </a>
                </div>
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-lg"></i> Save Changes
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
// ── Show/hide cancellation fields based on status ──
const statusSelect       = document.getElementById('status_select');
const cancellationSection = document.getElementById('cancellation-section');

function toggleCancellation() {
    if (statusSelect.value === 'Cancelled') {
        cancellationSection.classList.add('show');
    } else {
        cancellationSection.classList.remove('show');
    }
}
statusSelect.addEventListener('change', toggleCancellation);

// ── Doctor availability check ──
const doctorSel = document.getElementById('doctor_id');
const dateSel   = document.getElementById('appt_date');
const timeSel   = document.getElementById('appt_time');
const indicator = document.getElementById('avail-indicator');

function checkAvailability() {
    const doctorId = doctorSel.value;
    const date     = dateSel.value;
    const time     = timeSel.value;

    if (!doctorId || !date || !time) {
        indicator.style.display = 'none';
        return;
    }

    indicator.style.display = 'block';
    indicator.innerHTML = '<span class="avail-badge avail-loading"><i class="bi bi-hourglass-split"></i> Checking…</span>';

    fetch(`{{ route('appointments.availability') }}?doctor_id=${doctorId}&date=${date}`)
        .then(r => r.json())
        .then(data => {
            const conflict = data.booked_slots.includes(time);
            if (conflict) {
                indicator.innerHTML = '<span class="avail-badge avail-conflict"><i class="bi bi-x-circle-fill"></i> Time slot already booked</span>';
            } else {
                indicator.innerHTML = `<span class="avail-badge avail-free"><i class="bi bi-check-circle-fill"></i> Slot available · ${data.total_booked} appointments that day</span>`;
            }
        })
        .catch(() => { indicator.style.display = 'none'; });
}

doctorSel.addEventListener('change', checkAvailability);
dateSel.addEventListener('change',   checkAvailability);
timeSel.addEventListener('change',   checkAvailability);
</script>
@endpush