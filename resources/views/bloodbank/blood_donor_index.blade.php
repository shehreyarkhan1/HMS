@extends('layouts.master')

@section('title', 'Blood Donors')
@section('page-title', 'Blood Donors')
@section('breadcrumb', 'Home / Blood Bank / Donors')

@push('styles')
<style>
    .stat-pill { background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:14px 20px; }
    .stat-pill-label { font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; }
    .stat-pill-value { font-size:22px; font-weight:700; color:#1e293b; }

    .filter-bar input, .filter-bar select {
        height:36px; border:1px solid #e2e8f0; border-radius:8px;
        font-size:13px; padding:0 12px; color:#374151; background:#f8fafc;
    }
    .filter-bar input:focus, .filter-bar select:focus { outline:none; border-color:#93c5fd; background:#fff; }

    .donor-card {
        background:#fff; border:1px solid #e2e8f0; border-radius:12px;
        padding:18px 20px; transition:box-shadow 0.2s,transform 0.2s; height:100%;
    }
    .donor-card:hover { box-shadow:0 6px 20px rgba(0,0,0,0.07); transform:translateY(-2px); }

    .donor-avatar {
        width:46px; height:46px; border-radius:50%;
        display:flex; align-items:center; justify-content:center;
        font-size:16px; font-weight:700; flex-shrink:0;
    }
    .blood-badge {
        font-size:12px; font-weight:800; padding:4px 10px; border-radius:8px;
        background:#fee2e2; color:#b91c1c; letter-spacing:.02em;
    }

    .eligibility-badge { font-size:10px; padding:2px 8px; border-radius:20px; font-weight:600; }
    .elig-yes  { background:#dcfce7; color:#166534; }
    .elig-no   { background:#fee2e2; color:#991b1b; }

    .type-pill { font-size:10px; padding:2px 8px; border-radius:6px; background:#f5f3ff; color:#6d28d9; font-weight:600; }
    .card-divider { border:none; border-top:1px solid #f1f5f9; margin:10px 0; }
    .info-row { display:flex; gap:6px; align-items:center; font-size:12px; color:#64748b; margin-bottom:5px; }
    .info-row i { width:14px; color:#94a3b8; flex-shrink:0; }
</style>
@endpush

@section('content')

    {{-- Top --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('blood-bank.index') }}"
           class="btn btn-sm btn-outline-secondary px-3" style="height:34px;font-size:13px">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
        <a href="{{ route('blood-bank.donors.create') }}"
           class="btn btn-sm btn-success px-3" style="height:34px;font-size:13px">
            <i class="bi bi-plus-lg me-1"></i>Register Donor
        </a>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-pill"><div class="stat-pill-label">Total</div><div class="stat-pill-value">{{ $stats['total'] }}</div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill"><div class="stat-pill-label">Eligible</div><div class="stat-pill-value" style="color:#16a34a">{{ $stats['eligible'] }}</div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill"><div class="stat-pill-label">Voluntary</div><div class="stat-pill-value" style="color:#2563eb">{{ $stats['voluntary'] }}</div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill"><div class="stat-pill-label">Registered Today</div><div class="stat-pill-value">{{ $stats['today'] }}</div></div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:14px 20px;margin-bottom:16px">
        <form method="GET" action="{{ route('blood-bank.donors.index') }}"
              class="d-flex align-items-center gap-2 flex-wrap filter-bar">

            <input type="text" name="search" placeholder="Search name, CNIC, phone, ID..."
                   value="{{ request('search') }}" style="width:220px">

            <select name="blood_group">
                <option value="">All Blood Groups</option>
                @foreach($bloodGroups as $bg)
                    <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                @endforeach
            </select>

            <select name="donor_type">
                <option value="">All Types</option>
                @foreach(['Voluntary','Replacement','Autologous','Directed'] as $t)
                    <option value="{{ $t }}" {{ request('donor_type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>

            <select name="eligibility">
                <option value="">All</option>
                <option value="eligible"   {{ request('eligibility') == 'eligible'   ? 'selected' : '' }}>Eligible</option>
                <option value="ineligible" {{ request('eligibility') == 'ineligible' ? 'selected' : '' }}>Ineligible</option>
            </select>

            <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                <i class="bi bi-search me-1"></i>Filter
            </button>
            @if(request()->hasAny(['search','blood_group','donor_type','eligibility']))
                <a href="{{ route('blood-bank.donors.index') }}"
                   class="btn btn-sm btn-outline-secondary px-3" style="height:36px;font-size:13px">Clear</a>
            @endif
        </form>
    </div>

    {{-- Donors Grid --}}
    @if($donors->count())
    <div class="row g-3 mb-3">
        @foreach($donors as $donor)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="donor-card">

                {{-- Header --}}
                <div class="d-flex align-items-start gap-3 mb-2">
                    <div class="donor-avatar" style="background:#fef2f2;color:#b91c1c">
                        {{ strtoupper(substr($donor->name, 0, 2)) }}
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div style="font-size:14px;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            {{ $donor->name }}
                        </div>
                        <div style="font-size:11px;color:#94a3b8;margin-top:1px">
                            {{ $donor->donor_id }}
                            <span class="type-pill ms-1">{{ $donor->donor_type }}</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="blood-badge">{{ $donor->blood_group }}</div>
                        <div class="mt-1">
                            <span class="eligibility-badge {{ $donor->canDonateNow() ? 'elig-yes' : 'elig-no' }}">
                                <i class="bi bi-{{ $donor->canDonateNow() ? 'check' : 'x' }}-circle me-1"></i>
                                {{ $donor->canDonateNow() ? 'Eligible' : 'Not Eligible' }}
                            </span>
                        </div>
                    </div>
                </div>

                <hr class="card-divider">

                <div class="mb-2">
                    <div class="info-row"><i class="bi bi-telephone"></i>{{ $donor->phone }}</div>
                    <div class="info-row">
                        <i class="bi bi-calendar3"></i>
                        Age {{ $donor->age }} · {{ $donor->gender }}
                    </div>
                    @if($donor->city)
                    <div class="info-row"><i class="bi bi-geo-alt"></i>{{ $donor->city }}</div>
                    @endif
                    <div class="info-row">
                        <i class="bi bi-droplet"></i>
                        <strong style="color:#374151">{{ $donor->donations_count }}</strong>
                        donations
                        @if($donor->last_donation_date)
                            · Last: {{ $donor->last_donation_date->format('d M Y') }}
                        @endif
                    </div>
                    @if($donor->next_eligible_date && $donor->next_eligible_date->isFuture())
                    <div class="info-row">
                        <i class="bi bi-clock" style="color:#f59e0b"></i>
                        <span style="color:#d97706">
                            Next eligible: {{ $donor->next_eligible_date->format('d M Y') }}
                        </span>
                    </div>
                    @endif
                </div>

                <hr class="card-divider">

                <div class="d-flex gap-1">
                    <a href="{{ route('blood-bank.donors.show', $donor->id) }}"
                       class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('blood-bank.donors.edit', $donor->id) }}"
                       class="btn btn-sm btn-outline-warning py-0 px-2" style="font-size:12px">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('blood-bank.donors.destroy', $donor->id) }}"
                          onsubmit="return confirm('Remove {{ $donor->name }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:12px">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($donors->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-2">
        <span style="font-size:12px;color:#94a3b8">
            Showing {{ $donors->firstItem() }}–{{ $donors->lastItem() }} of {{ $donors->total() }} donors
        </span>
        {{ $donors->links('pagination::bootstrap-5') }}
    </div>
    @endif

    @else
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:60px;text-align:center">
        <i class="bi bi-people" style="font-size:48px;color:#e2e8f0;display:block;margin-bottom:12px"></i>
        <div style="font-size:15px;font-weight:500;color:#374151;margin-bottom:6px">No donors found</div>
        <a href="{{ route('blood-bank.donors.create') }}" class="btn btn-primary btn-sm px-4 mt-2">
            <i class="bi bi-plus-lg me-1"></i>Register Donor
        </a>
    </div>
    @endif

@endsection