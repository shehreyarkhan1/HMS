
@extends('layouts.master')

@section('title', 'Discharge Summary')
@section('page-title', 'Discharge Summary')
@section('breadcrumb', 'Home / Wards / Discharge / ' . $discharge->discharge_number)

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-semibold">{{ $discharge->discharge_number }}</h5>
        <small class="text-muted">{{ $discharge->patient->name }} — {{ $discharge->discharge_date->format('d M Y') }}</small>
    </div>
    <div class="d-flex gap-2">
        @if($discharge->status === 'Draft')
            <form method="POST" action="{{ route('ward.discharge.finalize', $discharge->id) }}">
                @csrf
                <button class="btn btn-success btn-sm" style="height:36px;font-size:13px">
                    <i class="bi bi-check-circle me-1"></i>Finalize
                </button>
            </form>
        @endif
        <a href="{{ route('ward.discharge.print', $discharge->id) }}" target="_blank"
           class="btn btn-outline-primary btn-sm" style="height:36px;font-size:13px">
            <i class="bi bi-printer me-1"></i>Print
        </a>
        <a href="{{ route('wards.index') }}"
           class="btn btn-outline-secondary btn-sm" style="height:36px;font-size:13px">Back</a>
    </div>
</div>

<div class="row g-3">
    {{-- Patient Info --}}
    <div class="col-12 col-md-6">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:18px">
            <div style="font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:12px">Patient Information</div>
            <table class="table table-sm table-borderless mb-0" style="font-size:13px">
                <tr><td class="text-muted" style="width:140px">Name</td><td class="fw-medium">{{ $discharge->patient->name }}</td></tr>
                <tr><td class="text-muted">MRN</td><td>{{ $discharge->patient->mrn }}</td></tr>
                <tr><td class="text-muted">Bed / Ward</td><td>{{ $discharge->bed->bed_number }} — {{ $discharge->bed->ward->name ?? '—' }}</td></tr>
                <tr><td class="text-muted">Admitted</td><td>{{ $discharge->admitted_date->format('d M Y') }}</td></tr>
                <tr><td class="text-muted">Discharged</td><td>{{ $discharge->discharge_date->format('d M Y') }}</td></tr>
                <tr><td class="text-muted">Total Days</td><td>{{ $discharge->total_days }} days</td></tr>
                <tr><td class="text-muted">Discharge Type</td>
                    <td><span class="badge {{ $discharge->discharge_type === 'Normal' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $discharge->discharge_type }}</span></td>
                </tr>
                <tr><td class="text-muted">Condition</td><td>{{ $discharge->condition_at_discharge }}</td></tr>
                <tr><td class="text-muted">Doctor</td><td>Dr. {{ $discharge->doctor->employee->full_name ?? '—' }}</td></tr>
                <tr><td class="text-muted">Processed by</td><td>{{ $discharge->processedBy->name ?? '—' }}</td></tr>
            </table>
        </div>
    </div>

    {{-- Status --}}
    <div class="col-12 col-md-6">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:18px">
            <div style="font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:12px">Status</div>
            <div class="text-center py-3">
                <div style="font-size:48px">{{ $discharge->status === 'Final' ? '✅' : ($discharge->status === 'Printed' ? '🖨️' : '📝') }}</div>
                <div style="font-size:16px;font-weight:600;margin-top:8px;color:#1e293b">{{ $discharge->status }}</div>
                @if($discharge->finalized_at)
                    <div style="font-size:12px;color:#94a3b8">Finalized: {{ $discharge->finalized_at->format('d M Y, h:i A') }}</div>
                @endif
            </div>
            @if($discharge->follow_up_date)
            <div style="background:#eff6ff;border-radius:8px;padding:12px;margin-top:12px">
                <div style="font-size:12px;color:#1d4ed8;font-weight:600"><i class="bi bi-calendar-check me-2"></i>Follow-up Appointment</div>
                <div style="font-size:13px;color:#1e293b;margin-top:4px">{{ $discharge->follow_up_date->format('d M Y') }}</div>
                @if($discharge->follow_up_with)
                    <div style="font-size:12px;color:#64748b">with {{ $discharge->follow_up_with }}</div>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- Clinical Details --}}
    @foreach([
        ['Admission Diagnosis', $discharge->admission_diagnosis, 'bi-clipboard-pulse', '#dc2626'],
        ['Final Diagnosis', $discharge->final_diagnosis, 'bi-clipboard2-check', '#16a34a'],
        ['Treatment Summary', $discharge->treatment_summary, 'bi-heart-pulse', '#1d4ed8'],
        ['Procedures Done', $discharge->procedures_done, 'bi-bandaid', '#9333ea'],
        ['Discharge Instructions', $discharge->discharge_instructions, 'bi-card-checklist', '#d97706'],
        ['Medications on Discharge', $discharge->medications_on_discharge, 'bi-capsule', '#0891b2'],
        ['Diet Instructions', $discharge->diet_instructions, 'bi-egg-fried', '#16a34a'],
        ['Activity Instructions', $discharge->activity_instructions, 'bi-bicycle', '#7c3aed'],
    ] as [$label, $value, $icon, $color])
        @if($value)
        <div class="col-12 col-md-6">
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px">
                <div style="font-size:12px;font-weight:600;color:{{ $color }};margin-bottom:8px">
                    <i class="bi {{ $icon }} me-2"></i>{{ $label }}
                </div>
                <p style="font-size:13px;color:#374151;margin:0;line-height:1.7">{{ $value }}</p>
            </div>
        </div>
        @endif
    @endforeach
</div>

@endsection
