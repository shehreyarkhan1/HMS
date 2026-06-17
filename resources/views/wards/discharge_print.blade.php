{{-- resources/views/wards/discharge_print.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Discharge Summary — {{ $discharge->discharge_number }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Arial,sans-serif;font-size:12px;color:#000;padding:20px}
        .header{text-align:center;border-bottom:2px solid #000;padding-bottom:12px;margin-bottom:16px}
        .hospital-name{font-size:20px;font-weight:700}
        .doc-title{font-size:15px;font-weight:600;margin-top:4px;text-transform:uppercase;letter-spacing:.05em}
        .doc-number{font-size:11px;color:#555;margin-top:2px}
        .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:0;border:1px solid #000;margin-bottom:14px}
        .info-cell{padding:6px 10px;border-right:1px solid #ccc;border-bottom:1px solid #ccc}
        .info-cell:nth-child(even){border-right:none}
        .info-label{font-size:10px;color:#555;text-transform:uppercase;margin-bottom:2px}
        .info-value{font-size:12px;font-weight:600}
        .section{margin-bottom:12px;border:1px solid #ccc;border-radius:4px;overflow:hidden}
        .section-title{background:#f0f0f0;padding:6px 10px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #ccc}
        .section-body{padding:8px 10px;font-size:12px;line-height:1.7}
        .two-col{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .footer{margin-top:30px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px}
        .sign-box{text-align:center;border-top:1px solid #000;padding-top:6px;font-size:11px}
        @media print{body{padding:0} .no-print{display:none}}
    </style>
</head>
<body>

<div class="no-print" style="margin-bottom:16px">
    <button onclick="window.print()" style="padding:8px 20px;background:#1d4ed8;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px">
        🖨️ Print / Save as PDF
    </button>
</div>

{{-- Header --}}
<div class="header">
    @php use App\Models\Setting; @endphp
    <div class="hospital-name">{{ Setting::get('hospital_name', 'MediCore Hospital') }}</div>
    <div style="font-size:11px;color:#555">{{ Setting::get('hospital_address', '') }} | {{ Setting::get('hospital_phone', '') }}</div>
    <div class="doc-title">Discharge Summary</div>
    <div class="doc-number">{{ $discharge->discharge_number }} | Status: {{ $discharge->status }}</div>
</div>

{{-- Patient Info Grid --}}
<div class="info-grid">
    <div class="info-cell"><div class="info-label">Patient Name</div><div class="info-value">{{ $discharge->patient->name }}</div></div>
    <div class="info-cell"><div class="info-label">MRN</div><div class="info-value">{{ $discharge->patient->mrn }}</div></div>
    <div class="info-cell"><div class="info-label">Age / Gender</div><div class="info-value">{{ $discharge->patient->age ?? '—' }} yrs / {{ $discharge->patient->gender ?? '—' }}</div></div>
    <div class="info-cell"><div class="info-label">Blood Group</div><div class="info-value">{{ $discharge->patient->blood_group ?? '—' }}</div></div>
    <div class="info-cell"><div class="info-label">Ward / Bed</div><div class="info-value">{{ $discharge->bed->ward->name ?? '—' }} — {{ $discharge->bed->bed_number }}</div></div>
    <div class="info-cell"><div class="info-label">Discharging Doctor</div><div class="info-value">Dr. {{ $discharge->doctor->employee->full_name ?? '—' }}</div></div>
    <div class="info-cell"><div class="info-label">Admission Date</div><div class="info-value">{{ $discharge->admitted_date->format('d M Y') }}</div></div>
    <div class="info-cell"><div class="info-label">Discharge Date</div><div class="info-value">{{ $discharge->discharge_date->format('d M Y') }}</div></div>
    <div class="info-cell"><div class="info-label">Total Stay</div><div class="info-value">{{ $discharge->total_days }} days</div></div>
    <div class="info-cell"><div class="info-label">Discharge Type / Condition</div><div class="info-value">{{ $discharge->discharge_type }} / {{ $discharge->condition_at_discharge }}</div></div>
</div>

{{-- Clinical Sections --}}
<div class="section">
    <div class="section-title">Admission Diagnosis</div>
    <div class="section-body">{{ $discharge->admission_diagnosis }}</div>
</div>

<div class="section">
    <div class="section-title">Final Diagnosis</div>
    <div class="section-body">{{ $discharge->final_diagnosis }}</div>
</div>

<div class="section">
    <div class="section-title">Treatment Summary</div>
    <div class="section-body">{{ $discharge->treatment_summary }}</div>
</div>

@if($discharge->procedures_done)
<div class="section">
    <div class="section-title">Procedures Done</div>
    <div class="section-body">{{ $discharge->procedures_done }}</div>
</div>
@endif

<div class="two-col">
    <div class="section">
        <div class="section-title">Discharge Instructions</div>
        <div class="section-body">{{ $discharge->discharge_instructions }}</div>
    </div>
    @if($discharge->medications_on_discharge)
    <div class="section">
        <div class="section-title">Medications on Discharge</div>
        <div class="section-body" style="white-space:pre-line">{{ $discharge->medications_on_discharge }}</div>
    </div>
    @endif
</div>

<div class="two-col">
    @if($discharge->diet_instructions)
    <div class="section">
        <div class="section-title">Diet Instructions</div>
        <div class="section-body">{{ $discharge->diet_instructions }}</div>
    </div>
    @endif
    @if($discharge->activity_instructions)
    <div class="section">
        <div class="section-title">Activity Instructions</div>
        <div class="section-body">{{ $discharge->activity_instructions }}</div>
    </div>
    @endif
</div>

@if($discharge->follow_up_date)
<div class="section">
    <div class="section-title">Follow-up</div>
    <div class="section-body">
        Date: <strong>{{ $discharge->follow_up_date->format('d M Y') }}</strong>
        @if($discharge->follow_up_with) | With: <strong>{{ $discharge->follow_up_with }}</strong> @endif
    </div>
</div>
@endif

{{-- Signatures --}}
<div class="footer">
    <div class="sign-box">Patient / Guardian Signature</div>
    <div class="sign-box">Nurse Signature<br><small>{{ $discharge->processedBy->name ?? '—' }}</small></div>
    <div class="sign-box">Doctor Signature<br><small>Dr. {{ $discharge->doctor->employee->full_name ?? '—' }}</small></div>
</div>

<div style="text-align:center;margin-top:20px;font-size:10px;color:#999">
    Printed on {{ now()->format('d M Y, h:i A') }} | {{ Setting::get('hospital_name', 'MediCore HMS') }}
</div>

</body>
</html>
