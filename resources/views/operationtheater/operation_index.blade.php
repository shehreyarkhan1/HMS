@extends('layouts.master')

@section('title', 'Operation Theatre')
@section('page-title', 'Operation Theatre')
@section('breadcrumb', 'Home / OT')

@push('styles')
<style>
    /* ── STAT PILLS ─────────────────────────────────────────────── */
    .stat-pill { background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:14px 20px; }
    .stat-pill-label { font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; }
    .stat-pill-value { font-size:22px; font-weight:700; color:#1e293b; }

    /* ── FILTER BAR ─────────────────────────────────────────────── */
    .filter-bar input, .filter-bar select {
        height:36px; border:1px solid #e2e8f0; border-radius:8px;
        font-size:13px; padding:0 12px; color:#374151; background:#f8fafc;
    }
    .filter-bar input:focus, .filter-bar select:focus {
        outline:none; border-color:#93c5fd; background:#fff;
    }

    /* ── SURGERY CARD ───────────────────────────────────────────── */
    .surgery-card {
        background:#fff; border:1px solid #e2e8f0; border-radius:12px;
        padding:18px 20px; transition:box-shadow 0.2s, transform 0.2s; height:100%;
    }
    .surgery-card:hover { box-shadow:0 6px 20px rgba(0,0,0,0.07); transform:translateY(-2px); }

    /* Emergency card left border */
    .surgery-card.priority-Emergency { border-left:3px solid #dc2626; }
    .surgery-card.priority-Urgent    { border-left:3px solid #f59e0b; }
    .surgery-card.priority-Priority  { border-left:3px solid #3b82f6; }

    /* ── STATUS & PRIORITY BADGES ───────────────────────────────── */
    .status-badge, .priority-badge, .type-badge {
        font-size:10px; padding:2px 8px; border-radius:20px;
        font-weight:600; display:inline-flex; align-items:center; gap:4px;
    }

    /* Status colors */
    .status-Scheduled    { background:#dbeafe; color:#1e40af; }
    .status-Confirmed    { background:#e0f2fe; color:#0c4a6e; }
    .status-Preparing    { background:#fef9c3; color:#854d0e; }
    .status-In-Progress  { background:#dcfce7; color:#166534; }
    .status-Completed    { background:#f1f5f9; color:#475569; }
    .status-Postponed    { background:#fff7ed; color:#9a3412; }
    .status-Cancelled    { background:#fee2e2; color:#991b1b; }

    /* Priority colors */
    .priority-badge-Routine    { background:#f1f5f9; color:#64748b; }
    .priority-badge-Priority   { background:#e0f2fe; color:#0369a1; }
    .priority-badge-Urgent     { background:#fff7ed; color:#c2410c; }
    .priority-badge-Emergency  { background:#fee2e2; color:#b91c1c; }

    /* Surgery type colors */
    .type-Elective    { background:#f5f3ff; color:#6d28d9; }
    .type-Urgent      { background:#fff7ed; color:#c2410c; }
    .type-Emergency   { background:#fee2e2; color:#b91c1c; }
    .type-Diagnostic  { background:#ecfdf5; color:#065f46; }

    .card-divider { border:none; border-top:1px solid #f1f5f9; margin:12px 0; }
    .info-row { display:flex; gap:6px; align-items:center; font-size:12px; color:#64748b; margin-bottom:6px; }
    .info-row i { width:14px; color:#94a3b8; flex-shrink:0; }

    /* ── STATUS DOT ─────────────────────────────────────────────── */
    .status-dot { width:7px; height:7px; border-radius:50%; flex-shrink:0; }
    .dot-Scheduled   { background:#3b82f6; }
    .dot-Confirmed   { background:#0ea5e9; }
    .dot-Preparing   { background:#f59e0b; }
    .dot-In-Progress { background:#22c55e; animation: pulse-green 1.5s infinite; }
    .dot-Completed   { background:#94a3b8; }
    .dot-Postponed   { background:#f97316; }
    .dot-Cancelled   { background:#ef4444; }

    @keyframes pulse-green {
        0%, 100% { opacity:1; transform:scale(1); }
        50% { opacity:.6; transform:scale(1.3); }
    }

    /* ── OT ROOM TAG ────────────────────────────────────────────── */
    .room-tag {
        background:#f0fdf4; color:#15803d; font-size:11px;
        font-weight:600; padding:2px 8px; border-radius:6px;
    }

    /* ── SURGERY ID ─────────────────────────────────────────────── */
    .surgery-id { font-size:11px; color:#94a3b8; font-family:monospace; }
</style>
@endpush

@section('content')

    {{-- ── TOP ACTIONS ──────────────────────────────────────────────────── --}}
    <div class="d-flex justify-content-end gap-2 mb-3">
        <a href="{{ route('ot.rooms.index') }}"
           class="btn btn-sm btn-outline-secondary px-3" style="height:36px;font-size:13px;display:flex;align-items:center">
            <i class="bi bi-door-open me-1"></i>Manage OT Rooms
        </a>
        <a href="{{ route('ot.schedules.create') }}"
           class="btn btn-sm btn-success px-3" style="height:36px;font-size:13px;display:flex;align-items:center">
            <i class="bi bi-plus-lg me-1"></i>Schedule Surgery
        </a>
    </div>

    {{-- ── STATS ───────────────────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Today Total</div>
                <div class="stat-pill-value">{{ $stats['today_total'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Pending Today</div>
                <div class="stat-pill-value" style="color:#2563eb">{{ $stats['today_pending'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">In Progress</div>
                <div class="stat-pill-value" style="color:#16a34a">{{ $stats['in_progress'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Completed Today</div>
                <div class="stat-pill-value" style="color:#475569">{{ $stats['completed_today'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">This Month</div>
                <div class="stat-pill-value">{{ $stats['this_month'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Emergency Today</div>
                <div class="stat-pill-value" style="color:#dc2626">{{ $stats['emergency'] }}</div>
            </div>
        </div>
    </div>

    {{-- ── FILTER BAR ──────────────────────────────────────────────────── --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:14px 20px;margin-bottom:16px">
        <form method="GET" action="{{ route('ot.index') }}"
              class="d-flex align-items-center gap-2 flex-wrap filter-bar">

            <input type="text" name="search"
                   placeholder="Search surgery ID, procedure, patient..."
                   value="{{ request('search') }}" style="width:230px">

            <input type="date" name="date" value="{{ request('date') }}"
                   title="Filter by date">

            <select name="status">
                <option value="">All status</option>
                @foreach(['Scheduled','Confirmed','Preparing','In-Progress','Completed','Postponed','Cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>

            <select name="surgery_type">
                <option value="">All types</option>
                @foreach(['Elective','Urgent','Emergency','Diagnostic'] as $t)
                    <option value="{{ $t }}" {{ request('surgery_type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>

            <select name="priority">
                <option value="">All priority</option>
                @foreach(['Routine','Priority','Urgent','Emergency'] as $p)
                    <option value="{{ $p }}" {{ request('priority') == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>

            <select name="ot_room_id">
                <option value="">All rooms</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ request('ot_room_id') == $room->id ? 'selected' : '' }}>
                        {{ $room->room_code }} – {{ $room->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                <i class="bi bi-search me-1"></i>Filter
            </button>

            @if(request()->hasAny(['search','date','status','surgery_type','priority','ot_room_id']))
                <a href="{{ route('ot.index') }}"
                   class="btn btn-sm btn-outline-secondary px-3" style="height:36px;font-size:13px">
                    Clear
                </a>
            @endif

        </form>
    </div>

    {{-- ── SCHEDULE GRID ───────────────────────────────────────────────── --}}
    @if($schedules->count())
    <div class="row g-3 mb-3">
        @foreach($schedules as $schedule)
        @php
            $statusClass = 'status-' . str_replace(' ', '-', $schedule->status);
            $dotClass    = 'dot-' . str_replace(' ', '-', $schedule->status);
            $priorityBorder = 'priority-' . $schedule->priority;
        @endphp
        <div class="col-12 col-md-6 col-xl-4">
            <div class="surgery-card {{ $priorityBorder }}">

                {{-- Header --}}
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div>
                        <div style="font-size:14px;font-weight:600;color:#1e293b">
                            {{ $schedule->procedure_name }}
                        </div>
                        <div class="surgery-id mt-1">{{ $schedule->surgery_id }}</div>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-1">
                        <span class="status-badge {{ $statusClass }}">
                            <span class="status-dot {{ $dotClass }}"></span>
                            {{ $schedule->status }}
                        </span>
                    </div>
                </div>

                {{-- Type & Priority --}}
                <div class="d-flex gap-1 mb-2 flex-wrap">
                    <span class="type-badge type-{{ $schedule->surgery_type }}">
                        {{ $schedule->surgery_type }}
                    </span>
                    <span class="priority-badge priority-badge-{{ $schedule->priority }}">
                        <i class="bi bi-flag-fill" style="font-size:8px"></i>
                        {{ $schedule->priority }}
                    </span>
                    @if($schedule->anesthesia_type)
                        <span style="background:#f8fafc;color:#64748b;font-size:10px;padding:2px 8px;border-radius:6px;font-weight:500">
                            {{ $schedule->anesthesia_type }}
                        </span>
                    @endif
                </div>

                <hr class="card-divider">

                {{-- Info --}}
                <div class="mb-2">
                    <div class="info-row">
                        <i class="bi bi-person"></i>
                        <strong style="color:#374151">{{ $schedule->patient->name }}</strong>
                        <span style="color:#94a3b8;font-size:11px">{{ $schedule->patient->mrn }}</span>
                    </div>
                    <div class="info-row">
                        <i class="bi bi-person-badge"></i>
                        Dr. {{ $schedule->surgeon->employee?->first_name }} {{ $schedule->surgeon->employee?->last_name }}
                    </div>
                    @if($schedule->anesthesiologist)
                    <div class="info-row">
                        <i class="bi bi-lungs"></i>
                        Dr. {{ $schedule->anesthesiologist->employee?->first_name }} {{ $schedule->anesthesiologist->employee?->last_name }}
                        <span style="color:#94a3b8;font-size:10px">(Anesthesia)</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <i class="bi bi-calendar3"></i>
                        {{ $schedule->scheduled_date->format('d M Y') }}
                        <span style="color:#374151;font-weight:500">
                            {{ \Carbon\Carbon::parse($schedule->scheduled_time)->format('h:i A') }}
                        </span>
                        <span style="color:#94a3b8;font-size:11px">
                            (~{{ $schedule->estimated_duration_mins }} min)
                        </span>
                    </div>
                    @if($schedule->otRoom)
                    <div class="info-row">
                        <i class="bi bi-door-open"></i>
                        <span class="room-tag">{{ $schedule->otRoom->room_code }}</span>
                        {{ $schedule->otRoom->name }}
                    </div>
                    @endif
                </div>

                {{-- Diagnosis --}}
                <div style="background:#f8fafc;border-radius:8px;padding:8px 10px;font-size:12px;color:#475569;margin-bottom:10px">
                    <span style="color:#94a3b8">Dx:</span> {{ Str::limit($schedule->diagnosis, 60) }}
                </div>

                {{-- Consent & Pre-op indicators --}}
                <div class="d-flex gap-2 mb-3">
                    <span style="font-size:10px;padding:2px 8px;border-radius:6px;font-weight:600;
                        {{ $schedule->consent_obtained ? 'background:#dcfce7;color:#166534' : 'background:#fee2e2;color:#991b1b' }}">
                        <i class="bi bi-{{ $schedule->consent_obtained ? 'check' : 'x' }}-circle me-1"></i>
                        Consent
                    </span>
                    <span style="font-size:10px;padding:2px 8px;border-radius:6px;font-weight:600;
                        {{ $schedule->pre_op_assessment_done ? 'background:#dcfce7;color:#166534' : 'background:#fef9c3;color:#854d0e' }}">
                        <i class="bi bi-{{ $schedule->pre_op_assessment_done ? 'check' : 'clock' }}-circle me-1"></i>
                        Pre-op
                    </span>
                </div>

                <hr class="card-divider">

                {{-- Actions --}}
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex gap-1">
                        <a href="{{ route('ot.schedules.show', $schedule->id) }}"
                           class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if($schedule->isEditable())
                        <a href="{{ route('ot.schedules.edit', $schedule->id) }}"
                           class="btn btn-sm btn-outline-warning py-0 px-2" style="font-size:12px" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @endif
                        <form method="POST" action="{{ route('ot.schedules.destroy', $schedule->id) }}"
                              onsubmit="return confirm('Remove {{ $schedule->surgery_id }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:12px" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>

                    {{-- Quick status change --}}
                    @if(in_array($schedule->status, ['Scheduled','Confirmed','Preparing','In-Progress']))
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary py-0 px-2 dropdown-toggle" style="font-size:12px"
                                data-bs-toggle="dropdown">
                            Update Status
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="font-size:12px;min-width:140px">
                            @foreach(['Confirmed','Preparing','In-Progress','Completed','Postponed','Cancelled'] as $st)
                                @if($st !== $schedule->status)
                                <li>
                                    <button class="dropdown-item quick-status-btn"
                                            data-id="{{ $schedule->id }}"
                                            data-status="{{ $st }}">
                                        {{ $st }}
                                    </button>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($schedules->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-2">
        <span style="font-size:12px;color:#94a3b8">
            Showing {{ $schedules->firstItem() }}–{{ $schedules->lastItem() }}
            of {{ $schedules->total() }} surgeries
        </span>
        {{ $schedules->links('pagination::bootstrap-5') }}
    </div>
    @endif

    @else
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:60px;text-align:center">
        <i class="bi bi-heart-pulse" style="font-size:48px;color:#e2e8f0;display:block;margin-bottom:12px"></i>
        <div style="font-size:15px;font-weight:500;color:#374151;margin-bottom:6px">No surgeries found</div>
        <div style="font-size:13px;color:#94a3b8;margin-bottom:16px">Schedule a surgery to get started</div>
        <a href="{{ route('ot.schedules.create') }}" class="btn btn-primary btn-sm px-4">
            <i class="bi bi-plus-lg me-1"></i>Schedule Surgery
        </a>
    </div>
    @endif

@endsection

@push('scripts')
<script>
// ── Quick status update via AJAX ─────────────────────────────────────────
document.querySelectorAll('.quick-status-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id     = this.dataset.id;
        const status = this.dataset.status;
        if (!confirm(`Change status to "${status}"?`)) return;

        fetch(`/ot/schedules/${id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ status }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) location.reload();
        });
    });
});
</script>
@endpush