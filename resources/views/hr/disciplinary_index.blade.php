@extends('layouts.master')
@section('title', 'Disciplinary Actions')
@section('page-title', 'Disciplinary Actions')
@section('breadcrumb', 'HR / Disciplinary')

@push('styles')
    <style>
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
            margin-bottom: .3rem
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

        .section-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden
        }

        .section-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b
        }

        .filter-bar {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem
        }

        .da-row {
            display: flex;
            align-items: center;
            padding: .85rem 1.25rem;
            border-bottom: 1px solid #f8fafc;
            gap: 12px;
            transition: background .1s
        }

        .da-row:last-child {
            border-bottom: none
        }

        .da-row:hover {
            background: #f8fafc
        }

        .emp-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #fee2e2;
            color: #dc2626;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0
        }

        .da-info {
            flex: 1;
            min-width: 0
        }

        .da-name {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b
        }

        .da-meta {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 1px
        }

        .action-badge {
            font-size: 10px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 20px;
            white-space: nowrap
        }

        .a-verbal {
            background: #dbeafe;
            color: #1d4ed8
        }

        .a-written {
            background: #fef3c7;
            color: #b45309
        }

        .a-show {
            background: #ede9fe;
            color: #7c3aed
        }

        .a-suspension {
            background: #fee2e2;
            color: #dc2626
        }

        .a-demotion {
            background: #fef9c3;
            color: #a16207
        }

        .a-deduction {
            background: #f0fdf4;
            color: #16a34a
        }

        .a-termination {
            background: #fce7f3;
            color: #db2777
        }

        .a-other {
            background: #f1f5f9;
            color: #64748b
        }

        .status-badge {
            font-size: 10px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 20px;
            white-space: nowrap
        }

        .s-issued {
            background: #fef3c7;
            color: #b45309
        }

        .s-ack {
            background: #dbeafe;
            color: #1d4ed8
        }

        .s-review {
            background: #ede9fe;
            color: #7c3aed
        }

        .s-resolved {
            background: #dcfce7;
            color: #16a34a
        }

        .s-closed {
            background: #f1f5f9;
            color: #64748b
        }

        .s-escalated {
            background: #fee2e2;
            color: #dc2626
        }

        .btn-icon {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            border: 1px solid #e2e8f0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #64748b;
            text-decoration: none;
            transition: all .15s;
            background: #fff
        }

        .btn-icon:hover {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe
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
    </style>
@endpush

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 class="mb-0 fw-semibold" style="color:#1e293b">Disciplinary Actions</h5>
            <p class="mb-0" style="font-size:13px;color:#94a3b8">Employee disciplinary records and case management</p>
        </div>
        <a href="{{ route('hr.disciplinary.create') }}" class="btn btn-danger btn-sm">
            <i class="bi bi-plus-lg me-1"></i> New Action
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Total Cases</div>
                        <div class="stat-value">{{ $stats['total'] }}</div>
                        <div class="stat-sub">all time</div>
                    </div>
                    <div class="stat-icon" style="background:#fee2e2;color:#dc2626"><i
                            class="bi bi-exclamation-triangle"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Open Cases</div>
                        <div class="stat-value">{{ $stats['open'] }}</div>
                        <div class="stat-sub">active</div>
                    </div>
                    <div class="stat-icon" style="background:#fef3c7;color:#b45309"><i class="bi bi-folder2-open"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">This Month</div>
                        <div class="stat-value">{{ $stats['this_month'] }}</div>
                        <div class="stat-sub">new cases</div>
                    </div>
                    <div class="stat-icon" style="background:#eff6ff;color:#1d4ed8"><i class="bi bi-calendar3"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Suspensions</div>
                        <div class="stat-value">{{ $stats['suspensions'] }}</div>
                        <div class="stat-sub">active</div>
                    </div>
                    <div class="stat-icon" style="background:#fce7f3;color:#db2777"><i class="bi bi-person-slash"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="filter-bar">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label mb-1" style="font-size:11px;font-weight:600;color:#64748b">SEARCH</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Name / Employee ID…"
                    value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label mb-1" style="font-size:11px;font-weight:600;color:#64748b">ACTION TYPE</label>
                <select name="action_type" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach (['Verbal Warning', 'Written Warning', 'Show Cause Notice', 'Suspension', 'Demotion', 'Salary Deduction', 'Termination', 'Other'] as $t)
                        <option {{ request('action_type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label mb-1" style="font-size:11px;font-weight:600;color:#64748b">STATUS</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach (['Issued', 'Acknowledged', 'Under Review', 'Resolved', 'Escalated', 'Closed'] as $s)
                        <option {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
            </div>
        </form>
    </div>

    <div class="section-card">
        <div class="section-header">
            <span class="section-title"><i class="bi bi-exclamation-triangle me-2" style="color:#dc2626"></i>Cases</span>
            <span style="font-size:12px;color:#94a3b8">{{ $actions->total() }} records</span>
        </div>

        @forelse($actions as $action)
            @php
                $actionClass = match ($action->action_type) {
                    'Verbal Warning' => 'a-verbal',
                    'Written Warning' => 'a-written',
                    'Show Cause Notice' => 'a-show',
                    'Suspension' => 'a-suspension',
                    'Demotion' => 'a-demotion',
                    'Salary Deduction' => 'a-deduction',
                    'Termination' => 'a-termination',
                    default => 'a-other',
                };
                $statusClass = match ($action->status) {
                    'Acknowledged' => 's-ack',
                    'Under Review' => 's-review',
                    'Resolved' => 's-resolved',
                    'Closed' => 's-closed',
                    'Escalated' => 's-escalated',
                    default => 's-issued',
                };
                $emp = $action->employee;
            @endphp
            <div class="da-row">
                <div class="emp-avatar">{{ strtoupper(substr($emp->first_name ?? 'E', 0, 1) . substr($emp->last_name ?? '', 0, 1)) }}
                </div>
                <div class="da-info">
                    <div class="da-name">{{ $emp->first_name ?? '' }} {{ $emp->last_name ?? '' }}</div>
                    <div class="da-meta">
                        {{ $action->action_number }} &middot;
                        {{ $action->incident_type }} &middot;
                        {{ \Carbon\Carbon::parse($action->action_date)->format('d M Y') }}
                    </div>
                </div>
                <span class="action-badge {{ $actionClass }}">{{ $action->action_type }}</span>
                <span class="status-badge {{ $statusClass }}">{{ $action->status }}</span>
                @if ($action->is_appealed)
                    <span style="font-size:10px;color:#7c3aed;font-weight:600"><i
                            class="bi bi-flag me-1"></i>Appealed</span>
                @endif
                <a href="{{ route('hr.disciplinary.show', $action) }}" class="btn-icon" title="View"><i
                        class="bi bi-eye"></i></a>
            </div>
        @empty
            <div class="empty-state"><i class="bi bi-shield-check"></i>No disciplinary actions found.</div>
        @endforelse
    </div>

    <div class="mt-3 d-flex justify-content-center">{{ $actions->links() }}</div>
@endsection
