@extends('layouts.master')

@section('title', 'Doctors')
@section('page-title', 'Doctors')
@section('breadcrumb', 'Home / Doctors')

@push('styles')
<style>
    .stat-pill { background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:14px 20px; }
    .stat-pill-label { font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; }
    .stat-pill-value { font-size:22px; font-weight:700; color:#1e293b; }

    .filter-bar input, .filter-bar select {
        height:36px; border:1px solid #e2e8f0; border-radius:8px;
        font-size:13px; padding:0 12px; color:#374151; background:#f8fafc;
    }
    .filter-bar input:focus, .filter-bar select:focus {
        outline:none; border-color:#93c5fd; background:#fff;
    }

    .doctor-card {
        background:#fff; border:1px solid #e2e8f0; border-radius:12px;
        padding:20px; transition:box-shadow 0.2s, transform 0.2s; height:100%;
    }
    .doctor-card:hover { box-shadow:0 6px 20px rgba(0,0,0,0.07); transform:translateY(-2px); }

    .doctor-avatar {
        width:56px; height:56px; border-radius:50%;
        display:flex; align-items:center; justify-content:center;
        font-size:18px; font-weight:700;
        background:#dbeafe; color:#1d4ed8; flex-shrink:0;
    }
    .doctor-avatar img { width:100%; height:100%; border-radius:50%; object-fit:cover; }

    .avail-badge { font-size:10px; padding:2px 8px; border-radius:20px; font-weight:600; display:inline-flex; align-items:center; gap:4px; }
    .avail-Available         { background:#dcfce7; color:#166534; }
    .avail-In-Consultation   { background:#dbeafe; color:#1e40af; }
    .avail-On-Leave          { background:#fef9c3; color:#854d0e; }
    .avail-Off-Duty          { background:#fee2e2; color:#991b1b; }

    .avail-dot { width:6px; height:6px; border-radius:50%; }
    .dot-Available         { background:#16a34a; }
    .dot-In-Consultation   { background:#3b82f6; }
    .dot-On-Leave          { background:#ca8a04; }
    .dot-Off-Duty          { background:#dc2626; }

    .shift-pill { font-size:11px; padding:2px 8px; border-radius:6px; background:#f1f5f9; color:#475569; font-weight:500; }
    .card-divider { border:none; border-top:1px solid #f1f5f9; margin:12px 0; }
    .info-row { display:flex; gap:6px; align-items:center; font-size:12px; color:#64748b; margin-bottom:6px; }
    .info-row i { width:14px; color:#94a3b8; flex-shrink:0; }
    .fee-badge { background:#f0fdf4; color:#15803d; font-size:12px; font-weight:600; padding:3px 10px; border-radius:6px; }
    .type-pill { font-size:10px; padding:2px 8px; border-radius:6px; background:#f5f3ff; color:#6d28d9; font-weight:600; }
</style>
@endpush

@section('content')

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Total doctors</div>
                <div class="stat-pill-value">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Available</div>
                <div class="stat-pill-value" style="color:#16a34a">{{ $stats['available'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">On leave</div>
                <div class="stat-pill-value" style="color:#ca8a04">{{ $stats['on_leave'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Off duty</div>
                <div class="stat-pill-value" style="color:#dc2626">{{ $stats['off_duty'] }}</div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:14px 20px;margin-bottom:16px">
        <form method="GET" action="{{ route('doctors.index') }}"
              class="d-flex align-items-center gap-2 flex-wrap filter-bar">

            <input type="text" name="search" placeholder="Search name, ID, specialization..."
                   value="{{ request('search') }}" style="width:220px">

            <select name="department">
                <option value="">All departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                        {{ $dept }}
                    </option>
                @endforeach
            </select>

            <select name="availability">
                <option value="">All availability</option>
                <option value="Available"       {{ request('availability') == 'Available'       ? 'selected' : '' }}>Available</option>
                <option value="In Consultation" {{ request('availability') == 'In Consultation' ? 'selected' : '' }}>In Consultation</option>
                <option value="On Leave"        {{ request('availability') == 'On Leave'        ? 'selected' : '' }}>On Leave</option>
                <option value="Off Duty"        {{ request('availability') == 'Off Duty'        ? 'selected' : '' }}>Off Duty</option>
            </select>

            <select name="shift">
                <option value="">All shifts</option>
                <option value="Morning"  {{ request('shift') == 'Morning'  ? 'selected' : '' }}>Morning</option>
                <option value="Evening"  {{ request('shift') == 'Evening'  ? 'selected' : '' }}>Evening</option>
                <option value="Night"    {{ request('shift') == 'Night'    ? 'selected' : '' }}>Night</option>
                <option value="Rotating" {{ request('shift') == 'Rotating' ? 'selected' : '' }}>Rotating</option>
            </select>

            <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                <i class="bi bi-search me-1"></i>Filter
            </button>

            @if(request()->hasAny(['search','department','availability','shift']))
                <a href="{{ route('doctors.index') }}"
                   class="btn btn-sm btn-outline-secondary px-3" style="height:36px;font-size:13px">
                    Clear
                </a>
            @endif

            <a href="{{ route('doctors.create') }}"
               class="btn btn-sm btn-success px-3 ms-auto" style="height:36px;font-size:13px">
                <i class="bi bi-plus-lg me-1"></i>Add doctor
            </a>
        </form>
    </div>

    {{-- Doctors Grid --}}
    @if($doctors->count())
    <div class="row g-3 mb-3">
        @foreach($doctors as $doctor)
        @php
            $emp      = $doctor->employee;
            $fullName = $emp ? $emp->first_name . ' ' . $emp->last_name : '—';
            $avClass  = str_replace(' ', '-', $doctor->availability);
            $initials = $emp ? strtoupper(substr($emp->first_name,0,1) . substr($emp->last_name,0,1)) : 'DR';
            $photo    = $emp?->photo ? asset('storage/' . $emp->photo) : null;
        @endphp
        <div class="col-12 col-md-6 col-xl-4">
            <div class="doctor-card">

                {{-- Header --}}
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="doctor-avatar">
                        @if($photo)
                            <img src="{{ $photo }}" alt="{{ $fullName }}">
                        @else
                            {{ $initials }}
                        @endif
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div style="font-size:14px;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            Dr. {{ $fullName }}
                        </div>
                        <div style="font-size:12px;color:#6366f1;font-weight:500">
                            {{ $doctor->specialization }}
                        </div>
                        <div style="font-size:11px;color:#94a3b8;margin-top:1px">
                            {{ $doctor->doctor_id }}
                            <span class="type-pill ms-1">{{ $doctor->doctor_type }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="avail-badge avail-{{ $avClass }}">
                            <span class="avail-dot dot-{{ $avClass }}"></span>
                            {{ $doctor->availability }}
                        </span>
                    </div>
                </div>

                <hr class="card-divider">

                {{-- Info --}}
                <div class="mb-3">
                    <div class="info-row">
                        <i class="bi bi-building"></i>
                        {{ $emp?->department ?? '—' }}
                        @if($doctor->sub_department)
                            <span style="color:#94a3b8">/ {{ $doctor->sub_department }}</span>
                        @endif
                    </div>
                    <div class="info-row">
                        <i class="bi bi-award"></i>
                        {{ $doctor->qualification }}
                    </div>
                    <div class="info-row">
                        <i class="bi bi-telephone"></i>
                        {{ $emp?->personal_phone ?? '—' }}
                    </div>
                    <div class="info-row">
                        <i class="bi bi-clock"></i>
                        <span class="shift-pill">{{ $emp?->shift ?? '—' }}</span>
                        @if($emp?->shift_start && $emp?->shift_end)
                            <span style="color:#94a3b8">
                                {{ \Carbon\Carbon::parse($emp->shift_start)->format('h:i A') }}
                                –
                                {{ \Carbon\Carbon::parse($emp->shift_end)->format('h:i A') }}
                            </span>
                        @endif
                    </div>
                    @if($doctor->pmdc_number)
                    <div class="info-row">
                        <i class="bi bi-patch-check"></i>
                        PMDC: {{ $doctor->pmdc_number }}
                    </div>
                    @endif
                </div>

                <hr class="card-divider">

                {{-- Footer --}}
                <div class="d-flex align-items-center justify-content-between">
                    <span class="fee-badge">
                        Rs {{ number_format($doctor->consultation_fee, 0) }}
                    </span>
                    <div class="d-flex gap-1">
                        <a href="{{ route('doctors.show', $doctor->id) }}"
                           class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('doctors.edit', $doctor->id) }}"
                           class="btn btn-sm btn-outline-warning py-0 px-2" style="font-size:12px">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('doctors.destroy', $doctor->id) }}"
                              onsubmit="return confirm('Remove Dr. {{ $fullName }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:12px">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($doctors->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-2">
        <span style="font-size:12px;color:#94a3b8">
            Showing {{ $doctors->firstItem() }}–{{ $doctors->lastItem() }}
            of {{ $doctors->total() }} doctors
        </span>
        {{ $doctors->links('pagination::bootstrap-5') }}
    </div>
    @endif

    @else
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:60px;text-align:center">
        <i class="bi bi-person-badge" style="font-size:48px;color:#e2e8f0;display:block;margin-bottom:12px"></i>
        <div style="font-size:15px;font-weight:500;color:#374151;margin-bottom:6px">No doctors found</div>
        <div style="font-size:13px;color:#94a3b8;margin-bottom:16px">Add your first doctor to get started</div>
        <a href="{{ route('doctors.create') }}" class="btn btn-primary btn-sm px-4">
            <i class="bi bi-plus-lg me-1"></i>Add doctor
        </a>
    </div>
    @endif

@endsection