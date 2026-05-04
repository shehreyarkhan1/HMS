@extends('layouts.master')

@section('title', 'Blood Bank')
@section('page-title', 'Blood Bank')
@section('breadcrumb', 'Home / Blood Bank')

@push('styles')
<style>
    /* ── STAT PILLS ─────────────────────────────────────────────── */
    .stat-pill { background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:14px 20px; }
    .stat-pill-label { font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; }
    .stat-pill-value { font-size:22px; font-weight:700; color:#1e293b; }

    /* ── BLOOD GROUP GRID ───────────────────────────────────────── */
    .blood-group-card {
        background:#fff; border:1px solid #e2e8f0; border-radius:10px;
        padding:14px; text-align:center; transition:box-shadow 0.2s;
    }
    .blood-group-card:hover { box-shadow:0 4px 14px rgba(0,0,0,0.08); }

    .blood-drop {
        width:44px; height:44px; border-radius:50% 50% 50% 0;
        transform:rotate(-45deg); display:flex; align-items:center;
        justify-content:center; margin:0 auto 8px;
    }
    .blood-drop span { transform:rotate(45deg); font-size:13px; font-weight:700; }

    .bg-aplus   { background:#fee2e2; color:#b91c1c; }
    .bg-aminus  { background:#fecaca; color:#991b1b; }
    .bg-bplus   { background:#fde68a; color:#92400e; }
    .bg-bminus  { background:#fef3c7; color:#854d0e; }
    .bg-oplus   { background:#d1fae5; color:#065f46; }
    .bg-ominus  { background:#a7f3d0; color:#064e3b; }
    .bg-abplus  { background:#dbeafe; color:#1e40af; }
    .bg-abminus { background:#bfdbfe; color:#1d4ed8; }

    .units-count { font-size:20px; font-weight:800; color:#1e293b; }
    .units-label { font-size:10px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; }
    .stock-bar   { height:4px; border-radius:2px; background:#f1f5f9; margin-top:8px; overflow:hidden; }
    .stock-fill  { height:100%; border-radius:2px; transition:width 0.4s; }

    /* ── SECTION CARD ───────────────────────────────────────────── */
    .section-card {
        background:#fff; border:1px solid #e2e8f0; border-radius:12px;
        padding:0; overflow:hidden; margin-bottom:20px;
    }
    .section-card-header {
        padding:14px 20px; border-bottom:1px solid #f1f5f9;
        display:flex; align-items:center; justify-content:space-between;
    }
    .section-card-title {
        font-size:13px; font-weight:600; color:#374151;
        display:flex; align-items:center; gap:8px;
    }
    .section-card-title i { color:#6366f1; }
    .section-card-body { padding:16px 20px; }

    /* ── REQUEST ROW ────────────────────────────────────────────── */
    .request-row {
        display:flex; align-items:center; gap:12px; padding:10px 0;
        border-bottom:1px solid #f8fafc; font-size:13px;
    }
    .request-row:last-child { border-bottom:none; }

    .urgency-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
    .urg-Emergency { background:#ef4444; animation:pulse-r 1.5s infinite; }
    .urg-Urgent    { background:#f59e0b; }
    .urg-Routine   { background:#94a3b8; }
    @keyframes pulse-r {
        0%,100% { opacity:1; transform:scale(1); }
        50% { opacity:.5; transform:scale(1.4); }
    }

    .req-status {
        font-size:10px; padding:2px 8px; border-radius:20px; font-weight:600;
    }
    .rs-Pending       { background:#fef9c3; color:#854d0e; }
    .rs-Under-Review  { background:#e0f2fe; color:#0c4a6e; }
    .rs-Crossmatch    { background:#dbeafe; color:#1e40af; }
    .rs-Approved      { background:#dcfce7; color:#166534; }
    .rs-Fulfilled     { background:#f1f5f9; color:#475569; }
    .rs-Rejected      { background:#fee2e2; color:#991b1b; }

    /* ── EXPIRY ALERT ROW ───────────────────────────────────────── */
    .expiry-row {
        display:flex; align-items:center; gap:12px; padding:8px 0;
        border-bottom:1px solid #f8fafc; font-size:12px;
    }
    .expiry-row:last-child { border-bottom:none; }
    .expiry-badge {
        font-size:10px; padding:2px 8px; border-radius:6px; font-weight:600;
        white-space:nowrap;
    }
    .exp-critical { background:#fee2e2; color:#991b1b; }
    .exp-warning  { background:#fef9c3; color:#854d0e; }
    .exp-ok       { background:#dcfce7; color:#166534; }
</style>
@endpush

@section('content')

    {{-- ── TOP ACTIONS ─────────────────────────────────────────────────── --}}
    <div class="d-flex justify-content-end gap-2 mb-3">
        <a href="{{ route('blood-bank.donations.index') }}"
           class="btn btn-sm btn-outline-secondary px-3" style="height:36px;font-size:13px;display:flex;align-items:center">
            <i class="bi bi-droplet me-1"></i>Donations
        </a>
        <a href="{{ route('blood-bank.donors.index') }}"
           class="btn btn-sm btn-outline-secondary px-3" style="height:36px;font-size:13px;display:flex;align-items:center">
            <i class="bi bi-people me-1"></i>Donors
        </a>
        <a href="{{ route('blood-bank.requests.index') }}"
           class="btn btn-sm btn-success px-3" style="height:36px;font-size:13px;display:flex;align-items:center">
            <i class="bi bi-plus-lg me-1"></i>New Blood Request
        </a>
    </div>

    {{-- ── STAT PILLS ──────────────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Units Available</div>
                <div class="stat-pill-value" style="color:#16a34a">{{ $stats['units_available'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Pending Requests</div>
                <div class="stat-pill-value" style="color:#2563eb">{{ $stats['pending_requests'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Emergency Requests</div>
                <div class="stat-pill-value" style="color:#dc2626">{{ $stats['emergency_requests'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Expiring in 3 Days</div>
                <div class="stat-pill-value" style="color:#d97706">{{ $stats['expiring_soon'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Total Donors</div>
                <div class="stat-pill-value">{{ $stats['total_donors'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Eligible Donors</div>
                <div class="stat-pill-value" style="color:#16a34a">{{ $stats['eligible_donors'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Donations Today</div>
                <div class="stat-pill-value">{{ $stats['donations_today'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Issues Today</div>
                <div class="stat-pill-value">{{ $stats['issues_today'] }}</div>
            </div>
        </div>
    </div>

    {{-- ── BLOOD STOCK GRID ─────────────────────────────────────────────── --}}
    <div class="section-card mb-4">
        <div class="section-card-header">
            <div class="section-card-title">
                <i class="bi bi-droplet-half"></i>
                Current Blood Stock (Whole Blood)
            </div>
            <a href="{{ route('blood-bank.donations.index') }}" style="font-size:12px;color:#6366f1;text-decoration:none">
                View All Bags →
            </a>
        </div>
        <div class="section-card-body">
            @php
                $bgClasses = ['A+'=>'bg-aplus','A-'=>'bg-aminus','B+'=>'bg-bplus','B-'=>'bg-bminus',
                              'O+'=>'bg-oplus','O-'=>'bg-ominus','AB+'=>'bg-abplus','AB-'=>'bg-abminus'];
            @endphp
            <div class="row g-3">
                @foreach($bloodGroups as $bg)
                @php
                    $stock    = $stockGrid[$bg] ?? null;
                    $units    = $stock ? $stock->units_available : 0;
                    $maxUnits = 20;
                    $fillPct  = min(100, ($units / $maxUnits) * 100);
                    $fillColor= $units == 0 ? '#ef4444' : ($units <= 2 ? '#f59e0b' : '#22c55e');
                @endphp
                <div class="col-6 col-md-3">
                    <div class="blood-group-card">
                        <div class="blood-drop {{ $bgClasses[$bg] }}">
                            <span>{{ $bg }}</span>
                        </div>
                        <div class="units-count">{{ $units }}</div>
                        <div class="units-label">units</div>
                        <div class="stock-bar">
                            <div class="stock-fill" style="width:{{ $fillPct }}%;background:{{ $fillColor }}"></div>
                        </div>
                        @if($units == 0)
                            <div style="font-size:10px;color:#dc2626;font-weight:600;margin-top:4px">CRITICAL</div>
                        @elseif($units <= 2)
                            <div style="font-size:10px;color:#d97706;font-weight:600;margin-top:4px">LOW</div>
                        @else
                            <div style="font-size:10px;color:#16a34a;margin-top:4px">OK</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- ── RECENT REQUESTS ─────────────────────────────────────── --}}
        <div class="col-12 col-xl-7">
            <div class="section-card">
                <div class="section-card-header">
                    <div class="section-card-title">
                        <i class="bi bi-clipboard-heart"></i>
                        Recent Blood Requests
                    </div>
                    <a href="{{ route('blood-bank.requests.index') }}"
                       style="font-size:12px;color:#6366f1;text-decoration:none">View All →</a>
                </div>
                <div class="section-card-body">
                    @forelse($recentRequests as $req)
                    @php $sc = 'rs-' . str_replace(' ', '-', $req->status); @endphp
                    <div class="request-row">
                        <span class="urgency-dot urg-{{ $req->urgency }}"></span>
                        <div style="flex:1;min-width:0">
                            <div style="font-weight:600;color:#1e293b;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                {{ $req->patient->name }}
                            </div>
                            <div style="font-size:11px;color:#94a3b8">
                                {{ $req->request_id }} · {{ $req->indication }}
                            </div>
                        </div>
                        <div class="text-center" style="min-width:48px">
                            <div style="font-size:16px;font-weight:700;color:#b91c1c">{{ $req->blood_group }}</div>
                            <div style="font-size:10px;color:#94a3b8">{{ $req->units_required }}u</div>
                        </div>
                        <span class="req-status {{ $sc }}">{{ $req->status }}</span>
                        <a href="{{ route('blood-bank.requests.show', $req->id) }}"
                           style="color:#6366f1;font-size:12px">
                            <i class="bi bi-arrow-right-circle"></i>
                        </a>
                    </div>
                    @empty
                    <div style="text-align:center;padding:30px;color:#94a3b8;font-size:13px">
                        No requests yet
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── EXPIRING SOON ALERTS ─────────────────────────────────── --}}
        <div class="col-12 col-xl-5">
            <div class="section-card">
                <div class="section-card-header">
                    <div class="section-card-title">
                        <i class="bi bi-exclamation-triangle" style="color:#f59e0b"></i>
                        Bags Expiring Within 5 Days
                    </div>
                    <span style="font-size:11px;color:#94a3b8">{{ $expiringDonations->count() }} bags</span>
                </div>
                <div class="section-card-body">
                    @forelse($expiringDonations as $don)
                    @php
                        $days = $don->daysUntilExpiry();
                        $expClass = $days === 0 ? 'exp-critical' : ($days <= 2 ? 'exp-warning' : 'exp-ok');
                    @endphp
                    <div class="expiry-row">
                        <div style="width:36px;height:36px;border-radius:50%;background:#fee2e2;color:#b91c1c;
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:11px;font-weight:700;flex-shrink:0">
                            {{ $don->blood_group }}
                        </div>
                        <div style="flex:1;min-width:0">
                            <div style="font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                {{ $don->donor->name }}
                            </div>
                            <div style="color:#94a3b8;font-size:11px">
                                {{ $don->donation_id }} · {{ $don->component }}
                            </div>
                        </div>
                        <span class="expiry-badge {{ $expClass }}">
                            {{ $days === 0 ? 'Today' : $days . 'd left' }}
                        </span>
                    </div>
                    @empty
                    <div style="text-align:center;padding:30px;color:#94a3b8;font-size:13px">
                        <i class="bi bi-check-circle" style="font-size:24px;color:#dcfce7;display:block;margin-bottom:8px"></i>
                        No bags expiring soon
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection