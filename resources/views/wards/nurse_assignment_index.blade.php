@extends('layouts.master')
@section('title', 'Nurse Assignments')
@section('page-title', 'Nurse Ward Assignments')
@section('breadcrumb', 'Home / Ward / Nurse Assignments')

@section('content')

{{-- Success/Error --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-1" style="font-weight:600">Nurse Ward Assignments</h5>
        <p class="text-muted mb-0" style="font-size:13px">Assign The Nurses to every ward and managme it</p>
    </div>
    <a href="{{ route('ward.nurse-assignments.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Assign The Nurse
    </a>
</div>

{{-- Ward wise Summary Cards --}}
<div class="row g-3 mb-4">
    @foreach($wards as $ward)
    <div class="col-12 col-md-6 col-lg-4">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <div style="font-size:14px;font-weight:600;color:#1e293b">{{ $ward->name }}</div>
                    <div style="font-size:11px;color:#94a3b8">{{ $ward->ward_code }} &bull; {{ $ward->type }}</div>
                </div>
                <span class="badge bg-primary" style="font-size:11px">
                    {{ $ward->nurseAssignments->count() }} nurses
                </span>
            </div>

            @if($ward->nurseAssignments->count() > 0)
                @foreach($ward->nurseAssignments as $assignment)
                <div style="display:flex;align-items:center;gap:8px;padding:6px 0;border-top:1px solid #f1f5f9">
                    <div style="width:28px;height:28px;border-radius:50%;background:#dbeafe;color:#1d4ed8;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        {{ strtoupper(substr($assignment->nurse->name ?? 'N', 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-size:12px;font-weight:500;color:#1e293b">{{ $assignment->nurse->name ?? '—' }}</div>
                        <div style="font-size:11px;color:#94a3b8">
                            @php
                                $shiftColors = ['Morning'=>'#ca8a04','Evening'=>'#1d4ed8','Night'=>'#6d28d9'];
                                $color = $shiftColors[$assignment->shift] ?? '#64748b';
                            @endphp
                            <span style="color:{{ $color }};font-weight:600">{{ $assignment->shift }}</span>
                            &bull; From {{ $assignment->start_date->format('d M') }}
                            @if($assignment->end_date) — {{ $assignment->end_date->format('d M') }} @else (Permanent) @endif
                        </div>
                    </div>
                    <form method="POST" action="{{ route('ward.nurse-assignments.destroy', $assignment->id) }}"
                          onsubmit="return confirm('Assignment remove ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger" style="padding:2px 7px;font-size:11px">
                            <i class="bi bi-x"></i>
                        </button>
                    </form>
                </div>
                @endforeach
            @else
                <div style="text-align:center;color:#94a3b8;font-size:12px;padding:12px 0">
                    <i class="bi bi-person-dash"></i> No Nurse Assign
                </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- All Assignments Table --}}
<div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden">
    <div style="padding:12px 16px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
        <span style="font-size:13px;font-weight:600;color:#1e293b">
            <i class="bi bi-list-ul me-2 text-primary"></i>Tamam Assignments
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:13px">
            <thead style="background:#f8fafc">
                <tr>
                    <th style="padding:10px 16px;font-weight:600;color:#64748b;font-size:11px;text-transform:uppercase">Nurse</th>
                    <th style="padding:10px 16px;font-weight:600;color:#64748b;font-size:11px;text-transform:uppercase">Ward</th>
                    <th style="padding:10px 16px;font-weight:600;color:#64748b;font-size:11px;text-transform:uppercase">Shift</th>
                    <th style="padding:10px 16px;font-weight:600;color:#64748b;font-size:11px;text-transform:uppercase">Start</th>
                    <th style="padding:10px 16px;font-weight:600;color:#64748b;font-size:11px;text-transform:uppercase">End</th>
                    <th style="padding:10px 16px;font-weight:600;color:#64748b;font-size:11px;text-transform:uppercase">Status</th>
                    <th style="padding:10px 16px;font-weight:600;color:#64748b;font-size:11px;text-transform:uppercase">Assigned By</th>
                    <th style="padding:10px 16px;font-weight:600;color:#64748b;font-size:11px;text-transform:uppercase">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allAssignments as $a)
                <tr>
                    <td style="padding:10px 16px;vertical-align:middle">
                        <div style="font-weight:500;color:#1e293b">{{ $a->nurse->name ?? '—' }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ $a->nurse->email ?? '' }}</div>
                    </td>
                    <td style="padding:10px 16px;vertical-align:middle">{{ $a->ward->name ?? '—' }}</td>
                    <td style="padding:10px 16px;vertical-align:middle">
                        @php
                            $badges = ['Morning'=>'warning','Evening'=>'primary','Night'=>'dark'];
                            $b = $badges[$a->shift] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $b }}" style="font-size:11px">{{ $a->shift }}</span>
                    </td>
                    <td style="padding:10px 16px;vertical-align:middle">{{ $a->start_date->format('d M Y') }}</td>
                    <td style="padding:10px 16px;vertical-align:middle">
                        {{ $a->end_date ? $a->end_date->format('d M Y') : 'Permanent' }}
                    </td>
                    <td style="padding:10px 16px;vertical-align:middle">
                        @if($a->is_active)
                            <span class="badge bg-success" style="font-size:11px">Active</span>
                        @else
                            <span class="badge bg-secondary" style="font-size:11px">Inactive</span>
                        @endif
                    </td>
                    <td style="padding:10px 16px;vertical-align:middle;font-size:12px;color:#64748b">
                        {{ $a->assignedBy->name ?? '—' }}
                    </td>
                    <td style="padding:10px 16px;vertical-align:middle">
                        @if($a->is_active)
                        <form method="POST" action="{{ route('ward.nurse-assignments.destroy', $a->id) }}"
                              onsubmit="return confirm('Assignment remove karein?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm" style="font-size:11px;padding:2px 8px">
                                Remove
                            </button>
                        </form>
                        @else
                            <span style="font-size:11px;color:#94a3b8">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;color:#94a3b8">
                        <i class="bi bi-person-dash" style="font-size:28px;display:block;margin-bottom:.5rem"></i>
                        There is not assignement yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($allAssignments->hasPages())
    <div style="padding:12px 16px;border-top:1px solid #f1f5f9">
        {{ $allAssignments->links() }}
    </div>
    @endif
</div>

@endsection
