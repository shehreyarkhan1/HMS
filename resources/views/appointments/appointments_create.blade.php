@extends('layouts.master')

@section('title', 'Book Appointment')
@section('page-title', 'Book Appointment')
@section('breadcrumb', 'Home / Appointments / New')

@push('styles')
<style>
    .form-wrapper { max-width:860px; margin:0 auto; }

    .form-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-bottom:20px; }
    .form-card-header { padding:14px 22px; border-bottom:1px solid #e2e8f0; background:#f8fafc; display:flex; align-items:center; gap:10px; }
    .form-card-header .section-icon { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:13px; }
    .form-card-header .section-title { font-size:13px; font-weight:600; color:#1e293b; }
    .form-card-body { padding:22px; }

    .form-label-custom { font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.05em; margin-bottom:5px; }
    .form-control-custom, .form-select-custom {
        height:40px; border:1px solid #e2e8f0; border-radius:8px;
        font-size:13px; color:#1e293b; background:#f8fafc;
        padding:0 12px; width:100%;
        transition:border-color .15s, box-shadow .15s;
    }
    .form-control-custom:focus, .form-select-custom:focus {
        outline:none; border-color:#3b82f6; background:#fff;
        box-shadow:0 0 0 3px rgba(59,130,246,.12);
    }
    textarea.form-control-custom { height:auto; padding:10px 12px; }
    .form-control-custom.is-invalid, .form-select-custom.is-invalid { border-color:#f87171; background:#fff5f5; }
    .field-error { font-size:11px; color:#dc2626; margin-top:4px; }

    /* Availability indicator */
    .avail-badge {
        display:inline-flex; align-items:center; gap:6px;
        font-size:12px; padding:4px 12px; border-radius:20px;
        font-weight:500; margin-top:8px;
    }
    .avail-free     { background:#dcfce7; color:#166534; }
    .avail-conflict { background:#fee2e2; color:#991b1b; }
    .avail-loading  { background:#f1f5f9; color:#64748b; }

    /* Type radio pills */
    .type-radio-group { display:flex; gap:8px; flex-wrap:wrap; }
    .type-radio input[type="radio"] { display:none; }
    .type-radio label {
        display:flex; align-items:center; gap:6px;
        height:40px; padding:0 16px; border:1px solid #e2e8f0;
        border-radius:8px; font-size:13px; color:#374151;
        cursor:pointer; background:#f8fafc; transition:all .15s;
    }
    .type-radio input[type="radio"]:checked + label { border-color:#3b82f6; background:#eff6ff; color:#1d4ed8; font-weight:500; }

    /* Action bar */
    .btn-save {
        height:42px; padding:0 28px;
        background:linear-gradient(135deg, #1e40af, #3b82f6);
        color:#fff; border:none; border-radius:9px;
        font-size:13px; font-weight:600;
        display:flex; align-items:center; gap:7px;
        cursor:pointer; transition:opacity .15s, transform .1s;
        box-shadow:0 2px 8px rgba(59,130,246,.35);
    }
    .btn-save:hover { opacity:.92; transform:translateY(-1px); }
    .btn-cancel {
        height:42px; padding:0 20px;
        border:1px solid #e2e8f0; border-radius:9px;
        background:#fff; color:#475569;
        font-size:13px; font-weight:500;
        display:flex; align-items:center; gap:7px;
        text-decoration:none; transition:background .15s;
    }
    .btn-cancel:hover { background:#f8fafc; color:#1e293b; }
</style>
@endpush

@section('content')
<div class="form-wrapper">

    @if($errors->any())
    <div class="alert alert-danger d-flex align-items-start gap-2 mb-4" style="border-radius:10px;font-size:13px">
        <i class="bi bi-exclamation-triangle-fill mt-1"></i>
        <div>
            <strong>Please fix the following:</strong>
            <ul class="mb-0 mt-1 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('appointments.store') }}" novalidate>
        @csrf

        {{-- ① Patient & Doctor --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#dbeafe;color:#1d4ed8"><i class="bi bi-person-fill"></i></div>
                <span class="section-title">Patient &amp; Doctor</span>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-label-custom">Patient <span class="text-danger">*</span></div>
                        <select name="patient_id" id="patient_id"
                                class="form-select-custom @error('patient_id') is-invalid @enderror">
                            <option value="">— Select patient —</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('patient_id', $selectedPatient?->id) == $p->id ? 'selected' : '' }}>
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
                                    {{ old('doctor_id') == $d->id ? 'selected' : '' }}>
                                    {{ $d->full_display }}
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
                <div class="section-icon" style="background:#dcfce7;color:#15803d"><i class="bi bi-calendar-check-fill"></i></div>
                <span class="section-title">Schedule</span>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-label-custom">Date <span class="text-danger">*</span></div>
                        <input type="date" name="appointment_date" id="appt_date"
                               class="form-control-custom @error('appointment_date') is-invalid @enderror"
                               value="{{ old('appointment_date', today()->format('Y-m-d')) }}"
                               min="{{ today()->format('Y-m-d') }}">
                        @error('appointment_date')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">Time</div>
                        <input type="time" name="appointment_time" id="appt_time"
                               class="form-control-custom @error('appointment_time') is-invalid @enderror"
                               value="{{ old('appointment_time') }}">
                        {{-- Availability indicator --}}
                        <div id="avail-indicator" style="display:none"></div>
                        @error('appointment_time')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">Duration (minutes)</div>
                        <select name="duration_minutes" class="form-select-custom">
                            @foreach([10,15,20,30,45,60] as $d)
                                <option value="{{ $d }}" {{ old('duration_minutes', 15) == $d ? 'selected' : '' }}>
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
                                    {{ old('type', 'OPD') == $t ? 'checked' : '' }}>
                                <label for="type_{{ $t }}">{{ $t }}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('type')<div class="field-error mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ③ Visit Details --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#fef3c7;color:#b45309"><i class="bi bi-clipboard2-fill"></i></div>
                <span class="section-title">Visit Details</span>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-label-custom">Chief Complaint / Reason</div>
                        <input type="text" name="reason"
                               class="form-control-custom @error('reason') is-invalid @enderror"
                               value="{{ old('reason') }}"
                               placeholder="e.g. Fever, Follow-up for diabetes, Chest pain…">
                        @error('reason')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-custom">Notes</div>
                        <textarea name="notes" rows="3"
                                  class="form-control-custom @error('notes') is-invalid @enderror"
                                  placeholder="Reception or clinical notes…">{{ old('notes') }}</textarea>
                        @error('notes')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-custom">Follow-up Date</div>
                        <input type="date" name="follow_up_date"
                               class="form-control-custom @error('follow_up_date') is-invalid @enderror"
                               value="{{ old('follow_up_date') }}">
                        @error('follow_up_date')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Bar --}}
        <div class="form-card" style="padding:16px 22px">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <a href="{{ route('appointments.index') }}" class="btn-cancel">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn-save">
                    <i class="bi bi-calendar-plus"></i> Book Appointment
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
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
dateSel.addEventListener('change', checkAvailability);
timeSel.addEventListener('change', checkAvailability);
</script>
@endpush