@extends('layouts.master')

@section('title', 'Audit Log')

@section('content')
<div class="container-fluid px-4">

    {{-- ── Header ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-semibold">
                <i class="bi bi-shield-check me-2 text-primary"></i>Audit Log
            </h4>
            <small class="text-muted">Complete activity trail — who did what, when</small>
        </div>
    </div>

    {{-- ── Stats Cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Today's Events</div>
                    <div class="fs-4 fw-bold text-primary">{{ number_format($stats['today_total']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Critical Today</div>
                    <div class="fs-4 fw-bold text-danger">{{ number_format($stats['today_critical']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Active Users Today</div>
                    <div class="fs-4 fw-bold text-success">{{ number_format($stats['today_users']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">All-time Events</div>
                    <div class="fs-4 fw-bold text-secondary">{{ number_format($stats['total_all_time']) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Filters ── --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('audit.index') }}" class="row g-2 align-items-end">

                <div class="col-12 col-md-3">
                    <label class="form-label form-label-sm text-muted">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="User, description, IP…" value="{{ request('search') }}">
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label form-label-sm text-muted">Module</label>
                    <select name="module" class="form-select form-select-sm">
                        <option value="">All Modules</option>
                        @foreach ($modules as $mod)
                            <option value="{{ $mod }}" @selected(request('module') === $mod)>{{ $mod }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label form-label-sm text-muted">Action</label>
                    <select name="action" class="form-select form-select-sm">
                        <option value="">All Actions</option>
                        @foreach ($actions as $act)
                            <option value="{{ $act }}" @selected(request('action') === $act)>{{ ucfirst($act) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-md-1">
                    <label class="form-label form-label-sm text-muted">Severity</label>
                    <select name="severity" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="critical" @selected(request('severity') === 'critical')>Critical</option>
                        <option value="high"     @selected(request('severity') === 'high')>High</option>
                        <option value="medium"   @selected(request('severity') === 'medium')>Medium</option>
                        <option value="low"      @selected(request('severity') === 'low')>Low</option>
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label form-label-sm text-muted">User</label>
                    <select name="user_id" class="form-select form-select-sm">
                        <option value="">All Users</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-md-1">
                    <label class="form-label form-label-sm text-muted">From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm"
                           value="{{ request('date_from') }}">
                </div>

                <div class="col-6 col-md-1">
                    <label class="form-label form-label-sm text-muted">To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm"
                           value="{{ request('date_to') }}">
                </div>

                <div class="col-12 col-md-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('audit.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- ── Table ── --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:160px">Time</th>
                            <th style="width:140px">User</th>
                            <th style="width:90px">Module</th>
                            <th style="width:90px">Action</th>
                            <th>Description</th>
                            <th style="width:80px">Severity</th>
                            <th style="width:110px">IP Address</th>
                            <th style="width:60px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr class="{{ $log->severity === 'critical' ? 'table-danger' : ($log->severity === 'high' ? 'table-warning' : '') }}">

                                <td class="ps-3 text-muted small">
                                    {{ $log->created_at->format('d M y, H:i:s') }}
                                </td>

                                <td>
                                    <span class="fw-medium small">{{ $log->user_name ?? '—' }}</span>
                                    @if($log->user_role)
                                        <br><span class="badge bg-light text-secondary border" style="font-size:10px">{{ $log->user_role }}</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 small">
                                        {{ $log->module }}
                                    </span>
                                </td>

                                <td>
                                    <i class="bi {{ $log->action_icon }} me-1"></i>
                                    <span class="small">{{ ucfirst($log->action) }}</span>
                                </td>

                                <td class="small text-truncate" style="max-width:260px" title="{{ $log->description }}">
                                    {{ $log->description }}
                                </td>

                                <td>
                                    <span class="badge bg-{{ $log->severity_badge }}">
                                        {{ ucfirst($log->severity) }}
                                    </span>
                                </td>

                                <td class="small text-muted font-monospace">
                                    {{ $log->ip_address ?? '—' }}
                                </td>

                                <td>
                                    <a href="{{ route('audit.show', $log) }}"
                                       class="btn btn-sm btn-outline-secondary py-0 px-2">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No activity logs found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($logs->hasPages())
            <div class="card-footer bg-transparent border-0 py-3">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
