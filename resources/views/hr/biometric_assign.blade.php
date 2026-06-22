@extends('layouts.master')
@section('title', 'Biometric ID — ' . $employee->first_name . ' ' . $employee->last_name)
@section('page-title', 'Biometric Assignment')
@section('breadcrumb', 'HR / Employees / Biometric')

@push('styles')
<style>
    .form-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden
    }

    .form-card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 10px
    }

    .form-card-body {
        padding: 1.5rem
    }

    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: .4rem
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        border: 1px solid #e2e8f0;
        height: 100%;
        transition: box-shadow .15s
    }

    .stat-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, .07)
    }

    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: .5rem
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
        margin-bottom: .4rem
    }

    .stat-sub {
        font-size: 12px;
        color: #94a3b8
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0
    }

    .punch-row {
        display: flex;
        align-items: center;
        padding: .7rem 1.25rem;
        border-bottom: 1px solid #f8fafc;
        gap: 12px;
        font-size: 13px;
        transition: background .1s
    }

    .punch-row:last-child {
        border-bottom: none
    }

    .punch-row:hover {
        background: #f8fafc
    }

    .status-badge {
        font-size: 10px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 20px;
        white-space: nowrap
    }

    .s-processed {
        background: #dcfce7;
        color: #16a34a
    }

    .s-unmatched {
        background: #fee2e2;
        color: #dc2626
    }

    .empty-state {
        padding: 3rem;
        text-align: center;
        color: #94a3b8;
        font-size: 13px
    }

    .empty-state i {
        font-size: 32px;
        display: block;
        margin-bottom: .75rem;
        color: #cbd5e1
    }

    .info-banner {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: .875rem 1.1rem;
        font-size: 13px;
        color: #1e40af
    }

    .warn-banner {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 10px;
        padding: .875rem 1.1rem;
        font-size: 13px;
        color: #92400e
    }
</style>
@endpush

@section('content')

{{-- ── Page header ──────────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-semibold" style="color:#1e293b">Biometric Assignment</h5>
        <p class="mb-0" style="font-size:13px;color:#94a3b8">
            {{ $employee->first_name }} {{ $employee->last_name }}
            &middot; {{ $employee->employee_id }}
            &middot; {{ $employee->department }}
        </p>
    </div>
    <a href="javascript:history.back()" class="btn btn-light btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

{{-- ── Alerts ───────────────────────────────────────────────── --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 mb-3" role="alert">
        <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->has('biometric_id'))
    <div class="alert alert-danger py-2 mb-3">
        <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first('biometric_id') }}
    </div>
@endif

{{-- ── Stats row ────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="stat-label">Enroll #</div>
                    <div class="stat-value" style="font-size:22px">
                        {{ $employee->biometric_id ?? '—' }}
                    </div>
                    <div class="stat-sub">ZKTeco ID</div>
                </div>
                <div class="stat-icon" style="background:{{ $employee->biometric_id ? '#dcfce7' : '#fee2e2' }};color:{{ $employee->biometric_id ? '#16a34a' : '#dc2626' }}">
                    <i class="bi bi-fingerprint"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="stat-label">Total Punches</div>
                    <div class="stat-value">{{ $totalLogs }}</div>
                    <div class="stat-sub">all time</div>
                </div>
                <div class="stat-icon" style="background:#dbeafe;color:#1d4ed8">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="stat-label">Processed</div>
                    <div class="stat-value">{{ $processedLogs }}</div>
                    <div class="stat-sub">attendance created</div>
                </div>
                <div class="stat-icon" style="background:#dcfce7;color:#16a34a">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="stat-label">Unmatched</div>
                    <div class="stat-value">{{ $unmatchedLogs }}</div>
                    <div class="stat-sub">pending sync</div>
                </div>
                <div class="stat-icon" style="background:#fef3c7;color:#b45309">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">

    {{-- ── LEFT: Assignment form ─────────────────────────────── --}}
    <div class="col-lg-5">

        {{-- Current status banner --}}
        @if($employee->biometric_id)
            <div class="info-banner mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-fingerprint fs-5"></i>
                <div>
                    <div class="fw-semibold">Enroll #{{ $employee->biometric_id }} mapped</div>
                    <div style="font-size:12px;opacity:.8">
                        ZKTeco machine se punches aa rahe hain
                    </div>
                </div>
            </div>
        @else
            <div class="warn-banner mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-fingerprint fs-5"></i>
                <div>
                    <div class="fw-semibold">Biometric ID assign nahi hua</div>
                    <div style="font-size:12px;opacity:.8">
                        Machine se attendance record nahi hoga
                    </div>
                </div>
            </div>
        @endif

        {{-- Assign form --}}
        <div class="form-card">
            <div class="form-card-header">
                <i class="bi bi-fingerprint" style="color:#1d4ed8;font-size:16px"></i>
                <span style="font-size:14px;font-weight:600;color:#1e293b">
                    {{ $employee->biometric_id ? 'Update Biometric ID' : 'Assign Biometric ID' }}
                </span>
            </div>
            <div class="form-card-body">
                <form action="{{ route('hr.employees.biometric-id', $employee) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">
                            ZKTeco Enroll Number <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="biometric_id"
                            value="{{ old('biometric_id', $employee->biometric_id) }}"
                            class="form-control @error('biometric_id') is-invalid @enderror"
                            placeholder="e.g. 1, 2, 14"
                            maxlength="20"
                            autocomplete="off"
                        >
                        <div class="form-text" style="font-size:11px;color:#94a3b8">
                            ZKTeco machine pe finger register karte waqt jo number milta hai
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-{{ $employee->biometric_id ? 'arrow-repeat' : 'check-lg' }} me-1"></i>
                        {{ $employee->biometric_id ? 'Update & Re-Sync' : 'Assign & Sync Punches' }}
                    </button>
                </form>

                {{-- Remove button (only if already mapped) --}}
                @if($employee->biometric_id)
                    <hr style="border-color:#f1f5f9">
                    <button
                        class="btn btn-outline-danger btn-sm w-100"
                        data-bs-toggle="modal"
                        data-bs-target="#removeModal"
                    >
                        <i class="bi bi-unlink me-1"></i>Remove Biometric Mapping
                    </button>
                @endif
            </div>
        </div>

        {{-- How to find enroll number guide --}}
        <div class="form-card mt-3">
            <div class="form-card-header">
                <i class="bi bi-question-circle" style="color:#7c3aed;font-size:16px"></i>
                <span style="font-size:14px;font-weight:600;color:#1e293b">Enroll Number Kaise Milega?</span>
            </div>
            <div class="form-card-body" style="padding:1.25rem 1.5rem">
                <ol class="mb-0 ps-3" style="font-size:13px;color:#475569;line-height:2">
                    <li>ZKTeco machine ki screen pe <strong>Menu</strong> dabao</li>
                    <li><strong>User Management</strong> ya <strong>User Info</strong> mein jao</li>
                    <li>Is employee ka naam dhundho</li>
                    <li>Wahan diya hua <strong>ID / User No.</strong> yahan dalein</li>
                </ol>
            </div>
        </div>

    </div>

    {{-- ── RIGHT: Recent punch logs ─────────────────────────── --}}
    <div class="col-lg-7">
        <div class="form-card">
            <div class="form-card-header">
                <i class="bi bi-clock-history" style="color:#1d4ed8;font-size:16px"></i>
                <span style="font-size:14px;font-weight:600;color:#1e293b">Recent Biometric Punches</span>
                <span class="ms-auto" style="font-size:12px;color:#94a3b8">Last 20 records</span>
            </div>

            @forelse($recentLogs as $log)
                <div class="punch-row">
                    <i class="bi bi-{{ $log->in_out_mode === 0 ? 'box-arrow-in-right' : 'box-arrow-right' }}"
                       style="color:{{ $log->in_out_mode === 0 ? '#16a34a' : '#dc2626' }};font-size:14px;flex-shrink:0"></i>

                    <div style="flex:1;min-width:0">
                        <div style="font-size:13px;font-weight:600;color:#1e293b">
                            {{ \Carbon\Carbon::parse($log->punch_time)->format('D, d M Y') }}
                        </div>
                        <div style="font-size:11px;color:#94a3b8">
                            {{ \Carbon\Carbon::parse($log->punch_time)->format('H:i:s') }}
                            &middot; Machine: {{ $log->machine_serial }}
                            &middot; Enroll: #{{ $log->enroll_number }}
                        </div>
                    </div>

                    <span style="font-size:12px;color:#64748b;flex-shrink:0">
                        {{ $log->in_out_mode === 0 ? 'Check In' : ($log->in_out_mode === 1 ? 'Check Out' : 'Auto') }}
                    </span>

                    <span class="status-badge {{ $log->is_processed ? 's-processed' : 's-unmatched' }}">
                        {{ $log->is_processed ? 'Processed' : 'Unmatched' }}
                    </span>
                </div>
            @empty
                <div class="empty-state">
                    <i class="bi bi-fingerprint"></i>
                    Koi biometric punch nahi mila.<br>
                    <span style="font-size:12px">
                        @if($employee->biometric_id)
                            Machine se abhi tak koi data nahi aaya.
                        @else
                            Pehle enroll number assign karo.
                        @endif
                    </span>
                </div>
            @endforelse
        </div>
    </div>

</div>{{-- row --}}


{{-- ── Remove confirmation modal ───────────────────────────── --}}
@if($employee->biometric_id)
<div class="modal fade" id="removeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;border:1px solid #e2e8f0">
            <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:1.1rem 1.5rem">
                <h6 class="modal-title fw-semibold" style="color:#1e293b">
                    <i class="bi bi-unlink me-2" style="color:#dc2626"></i>Mapping Remove Karen?
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem">
                <p class="mb-1" style="font-size:14px;color:#1e293b">
                    <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong>
                    ka biometric enroll <strong>#{{ $employee->biometric_id }}</strong>
                    remove ho jayega.
                </p>
                <p class="mb-0" style="font-size:13px;color:#94a3b8">
                    Iske baad machine se attendance record nahi hoga.
                    Purane attendance records mehfooz rahenge.
                </p>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:1rem 1.5rem">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('hr.employees.biometric-id.remove', $employee) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-unlink me-1"></i>Haan, Remove Karo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
