@extends('layouts.master')

@section('title', 'Blood Requests')
@section('page-title', 'Blood Requests')
@section('breadcrumb', 'Home / Blood Bank / Requests')

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

    /* ── REQUEST CARD ───────────────────────────────────────────── */
    .req-card {
        background:#fff; border:1px solid #e2e8f0; border-radius:12px;
        padding:18px 20px; transition:box-shadow 0.2s; height:100%;
    }
    .req-card:hover { box-shadow:0 4px 16px rgba(0,0,0,0.07); }
    .req-card.urg-Emergency { border-left:3px solid #dc2626; }
    .req-card.urg-Urgent    { border-left:3px solid #f59e0b; }

    .urgency-badge { font-size:10px; padding:2px 8px; border-radius:20px; font-weight:600; display:inline-flex; align-items:center; gap:4px; }
    .urg-badge-Emergency { background:#fee2e2; color:#b91c1c; }
    .urg-badge-Urgent    { background:#fff7ed; color:#c2410c; }
    .urg-badge-Routine   { background:#f1f5f9; color:#475569; }

    .status-badge { font-size:10px; padding:2px 8px; border-radius:20px; font-weight:600; }
    .rs-Pending               { background:#fef9c3; color:#854d0e; }
    .rs-Under-Review          { background:#e0f2fe; color:#0c4a6e; }
    .rs-Crossmatch            { background:#dbeafe; color:#1e40af; }
    .rs-Approved              { background:#dcfce7; color:#166534; }
    .rs-Partially-Fulfilled   { background:#e0f2fe; color:#0c4a6e; }
    .rs-Fulfilled             { background:#f1f5f9; color:#475569; }
    .rs-Cancelled             { background:#f1f5f9; color:#475569; }
    .rs-Rejected              { background:#fee2e2; color:#991b1b; }

    .urgency-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
    .urg-dot-Emergency { background:#ef4444; animation:pulse-r 1.5s infinite; }
    .urg-dot-Urgent    { background:#f59e0b; }
    .urg-dot-Routine   { background:#94a3b8; }
    @keyframes pulse-r { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(1.4)} }

    .blood-badge { font-size:16px; font-weight:800; padding:6px 12px; border-radius:10px; background:#fee2e2; color:#b91c1c; line-height:1; }
    .card-divider { border:none; border-top:1px solid #f1f5f9; margin:10px 0; }
    .info-row { display:flex; gap:6px; align-items:center; font-size:12px; color:#64748b; margin-bottom:5px; }
    .info-row i { width:14px; color:#94a3b8; flex-shrink:0; }

    /* ── MODAL FORM STYLES ──────────────────────────────────────── */
    .modal-body .form-label { font-size:12px; font-weight:500; color:#374151; margin-bottom:4px; }
    .modal-body .form-control,
    .modal-body .form-select {
        font-size:13px; border:1px solid #e2e8f0; border-radius:8px;
        background:#f8fafc; height:36px; padding:0 12px;
    }
    .modal-body .form-control:focus,
    .modal-body .form-select:focus { border-color:#93c5fd; background:#fff; box-shadow:none; }
    .modal-body textarea.form-control { height:auto; padding:8px 12px; }
</style>
@endpush

@section('content')

    {{-- ── TOP ACTIONS ─────────────────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('blood-bank.index') }}"
           class="btn btn-sm btn-outline-secondary px-3" style="height:34px;font-size:13px">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
        <button type="button" class="btn btn-sm btn-success px-3" style="height:34px;font-size:13px"
                data-bs-toggle="modal" data-bs-target="#newRequestModal">
            <i class="bi bi-plus-lg me-1"></i>New Blood Request
        </button>
    </div>

    {{-- ── STATS ───────────────────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Pending</div>
                <div class="stat-pill-value" style="color:#d97706">{{ $stats['pending'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Emergency</div>
                <div class="stat-pill-value" style="color:#dc2626">{{ $stats['emergency'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Today's Requests</div>
                <div class="stat-pill-value">{{ $stats['today'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Fulfilled Today</div>
                <div class="stat-pill-value" style="color:#16a34a">{{ $stats['fulfilled'] }}</div>
            </div>
        </div>
    </div>

    {{-- ── ALERTS ───────────────────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="font-size:13px;border-radius:10px" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" style="font-size:13px;border-radius:10px" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── FILTER BAR ──────────────────────────────────────────────────── --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:14px 20px;margin-bottom:16px">
        <form method="GET" action="{{ route('blood-bank.requests.index') }}"
              class="d-flex align-items-center gap-2 flex-wrap filter-bar">

            <input type="text" name="search"
                   placeholder="Request ID, patient name, MRN..."
                   value="{{ request('search') }}" style="width:220px">

            <select name="blood_group">
                <option value="">All Blood Groups</option>
                @foreach($bloodGroups as $bg)
                    <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                @endforeach
            </select>

            <select name="urgency">
                <option value="">All Urgency</option>
                @foreach(['Routine','Urgent','Emergency'] as $u)
                    <option value="{{ $u }}" {{ request('urgency') == $u ? 'selected' : '' }}>{{ $u }}</option>
                @endforeach
            </select>

            <select name="status">
                <option value="">All Status</option>
                @foreach(['Pending','Under Review','Crossmatch','Approved','Partially Fulfilled','Fulfilled','Cancelled','Rejected'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                <i class="bi bi-search me-1"></i>Filter
            </button>

            @if(request()->hasAny(['search','blood_group','urgency','status']))
                <a href="{{ route('blood-bank.requests.index') }}"
                   class="btn btn-sm btn-outline-secondary px-3" style="height:36px;font-size:13px">Clear</a>
            @endif
        </form>
    </div>

    {{-- ── REQUESTS GRID ────────────────────────────────────────────────── --}}
    @if($requests->count())
    <div class="row g-3 mb-3">
        @foreach($requests as $req)
        @php
            $urgClass  = 'urg-' . $req->urgency;
            $statusKey = 'rs-' . str_replace(' ', '-', $req->status);
        @endphp
        <div class="col-12 col-md-6 col-xl-4">
            <div class="req-card {{ $urgClass }}">

                {{-- Header --}}
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div>
                        <div style="font-size:14px;font-weight:600;color:#1e293b">
                            {{ $req->patient->name }}
                        </div>
                        <div style="font-size:11px;color:#94a3b8;margin-top:2px">
                            {{ $req->request_id }} · {{ $req->patient->mrn }}
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="blood-badge">{{ $req->blood_group }}</span>
                        <div style="font-size:11px;color:#94a3b8;margin-top:4px">
                            {{ $req->units_required }} unit{{ $req->units_required > 1 ? 's' : '' }}
                        </div>
                    </div>
                </div>

                {{-- Badges --}}
                <div class="d-flex gap-1 flex-wrap mb-2">
                    <span class="urgency-badge urg-badge-{{ $req->urgency }}">
                        <span class="urgency-dot urg-dot-{{ $req->urgency }}"></span>
                        {{ $req->urgency }}
                    </span>
                    <span class="status-badge {{ $statusKey }}">{{ $req->status }}</span>
                    @if($req->units_approved > 0)
                        <span style="font-size:10px;background:#f0fdf4;color:#15803d;padding:2px 8px;border-radius:6px;font-weight:600">
                            {{ $req->units_approved }} approved
                        </span>
                    @endif
                </div>

                <hr class="card-divider">

                {{-- Info rows --}}
                <div class="mb-2">
                    <div class="info-row">
                        <i class="bi bi-clipboard-heart"></i>
                        {{ $req->component }}
                    </div>
                    <div class="info-row">
                        <i class="bi bi-activity"></i>
                        {{ Str::limit($req->indication, 50) }}
                    </div>
                    @if($req->doctor)
                    <div class="info-row">
                        <i class="bi bi-person-badge"></i>
                        Dr. {{ $req->doctor->employee?->first_name }} {{ $req->doctor->employee?->last_name }}
                    </div>
                    @endif
                    @if($req->ward)
                    <div class="info-row">
                        <i class="bi bi-hospital"></i>
                        Ward: {{ $req->ward }}
                        @if($req->bed_number) · Bed: {{ $req->bed_number }} @endif
                    </div>
                    @endif
                    <div class="info-row">
                        <i class="bi bi-clock"></i>
                        {{ $req->created_at->diffForHumans() }}
                    </div>
                </div>

                <hr class="card-divider">

                {{-- Actions --}}
                <div class="d-flex gap-1 align-items-center">
                    <a href="{{ route('blood-bank.requests.show', $req->id) }}"
                       class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px">
                        <i class="bi bi-eye me-1"></i>Process
                    </a>

                    @if(in_array($req->status, ['Pending','Under Review']))
                    <button type="button"
                            class="btn btn-sm btn-outline-success py-0 px-2 approve-btn"
                            style="font-size:12px"
                            data-bs-toggle="modal"
                            data-bs-target="#approveModal"
                            data-id="{{ $req->id }}"
                            data-units="{{ $req->units_required }}">
                        <i class="bi bi-check2 me-1"></i>Approve
                    </button>
                    @endif

                    @if(!in_array($req->status, ['Fulfilled','Crossmatch']))
                    <form method="POST"
                          action="{{ route('blood-bank.requests.destroy', $req->id) }}"
                          onsubmit="return confirm('Remove request {{ $req->request_id }}?')"
                          class="ms-auto">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:12px">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>

            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($requests->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-2">
        <span style="font-size:12px;color:#94a3b8">
            Showing {{ $requests->firstItem() }}–{{ $requests->lastItem() }}
            of {{ $requests->total() }} requests
        </span>
        {{ $requests->links('pagination::bootstrap-5') }}
    </div>
    @endif

    @else
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:60px;text-align:center">
        <i class="bi bi-clipboard-heart" style="font-size:48px;color:#e2e8f0;display:block;margin-bottom:12px"></i>
        <div style="font-size:15px;font-weight:500;color:#374151;margin-bottom:6px">No blood requests</div>
        <div style="font-size:13px;color:#94a3b8;margin-bottom:16px">Submit a new blood request to get started</div>
        <button type="button" class="btn btn-primary btn-sm px-4"
                data-bs-toggle="modal" data-bs-target="#newRequestModal">
            <i class="bi bi-plus-lg me-1"></i>New Request
        </button>
    </div>
    @endif


    {{-- ══════════════════════════════════════════════════════════════════
         MODALS
    ══════════════════════════════════════════════════════════════════ --}}

    {{-- ── NEW REQUEST MODAL ───────────────────────────────────────────── --}}
    <div class="modal fade" id="newRequestModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius:16px;border:none">
                <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:20px 24px">
                    <h5 class="modal-title" style="font-size:15px;font-weight:600">New Blood Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('blood-bank.requests.store') }}">
                    @csrf
                    <div class="modal-body" style="padding:24px">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Patient <span style="color:#ef4444">*</span></label>
                                <select name="patient_id" class="form-select" required>
                                    <option value="">— Select Patient —</option>
                                    @foreach($patients as $p)
                                        <option value="{{ $p->id }}">
                                            {{ $p->name }} ({{ $p->mrn }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Requesting Doctor</label>
                                <select name="doctor_id" class="form-select">
                                    <option value="">— Select —</option>
                                    @foreach($doctors as $doc)
                                        <option value="{{ $doc->id }}">
                                            Dr. {{ $doc->employee?->first_name }}
                                            {{ $doc->employee?->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Blood Group <span style="color:#ef4444">*</span></label>
                                <select name="blood_group" class="form-select" required>
                                    <option value="">— Select —</option>
                                    @foreach($bloodGroups as $bg)
                                        <option value="{{ $bg }}">{{ $bg }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Component <span style="color:#ef4444">*</span></label>
                                <select name="component" class="form-select" required>
                                    @foreach(['Whole Blood','Packed RBC','Platelets','Fresh Frozen Plasma','Cryoprecipitate'] as $c)
                                        <option value="{{ $c }}">{{ $c }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Units <span style="color:#ef4444">*</span></label>
                                <input type="number" name="units_required" class="form-control"
                                       value="1" min="1" max="20" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Hb (g/dL)</label>
                                <input type="number" name="patient_hemoglobin" class="form-control"
                                       step="0.1" min="1" max="25" placeholder="e.g. 7.2">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Urgency <span style="color:#ef4444">*</span></label>
                                <select name="urgency" class="form-select" required>
                                    @foreach(['Routine','Urgent','Emergency'] as $u)
                                        <option value="{{ $u }}">{{ $u }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Ward</label>
                                <input type="text" name="ward" class="form-control" placeholder="Ward name">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Bed Number</label>
                                <input type="text" name="bed_number" class="form-control" placeholder="Bed no.">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Indication / Reason <span style="color:#ef4444">*</span></label>
                                <input type="text" name="indication" class="form-control" required
                                       placeholder="e.g. Pre-op preparation, Severe anaemia, Trauma">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2"
                                          style="height:auto;padding:8px 12px"></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:16px 24px">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm px-4">
                            <i class="bi bi-send me-1"></i>Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── APPROVE MODAL ────────────────────────────────────────────────── --}}
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:16px;border:none">
                <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:20px 24px">
                    <h5 class="modal-title" style="font-size:15px;font-weight:600">
                        <i class="bi bi-check-circle me-2" style="color:#16a34a"></i>
                        Approve Blood Request
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="approveForm" action="">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="Approved">
                    <div class="modal-body" style="padding:24px">
                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label">
                                    Units Approved <span style="color:#ef4444">*</span>
                                </label>
                                <input type="number" name="units_approved" id="approve-units"
                                       class="form-control" min="1" max="20" required
                                       placeholder="How many units to approve">
                                <div style="font-size:11px;color:#94a3b8;margin-top:4px">
                                    Can be less than or equal to units requested
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Processed By</label>
                                <select name="processed_by" class="form-select">
                                    <option value="">— Select Staff —</option>
                                    @foreach(\App\Models\Employee::where('employment_status','Active')
                                        ->orderBy('first_name')->get() as $emp)
                                        <option value="{{ $emp->id }}">
                                            {{ $emp->first_name }} {{ $emp->last_name }}
                                            ({{ $emp->designation }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:16px 24px">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm px-4">
                            <i class="bi bi-check-lg me-1"></i>Approve Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
// ── Approve modal — set form action + prefill units ───────────────────
document.querySelectorAll('.approve-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id    = this.dataset.id;
        const units = this.dataset.units;
        document.getElementById('approveForm').action = `/blood-bank/requests/${id}/status`;
        document.getElementById('approve-units').value = units;
    });
});
</script>
@endpush