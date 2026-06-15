@extends('layouts.master')

@section('title', 'Audit Log Detail')

@section('content')
    <div class="container-fluid px-4">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('audit.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
            <div>
                <h5 class="mb-0 fw-semibold">Log #{{ $log->id }} — Detail</h5>
                <small class="text-muted">{{ $log->created_at->format('l, d M Y \a\t H:i:s') }}</small>
            </div>
        </div>

        <div class="row g-4">

            {{-- ── Main info ── --}}
            <div class="col-12 col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-primary"></i>
                        <span class="fw-semibold">Event Details</span>
                        <span class="badge bg-{{ $log->severity_badge }} ms-auto">{{ ucfirst($log->severity) }}</span>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th class="text-muted fw-normal ps-0" style="width:160px">Description</th>
                                <td>{{ $log->description }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-normal ps-0">Module</th>
                                <td><span
                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">{{ $log->module }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-normal ps-0">Action</th>
                                <td><i class="bi {{ $log->action_icon }} me-1"></i>{{ ucfirst($log->action) }}</td>
                            </tr>
                            @if ($log->model_type)
                                <tr>
                                    <th class="text-muted fw-normal ps-0">Record</th>
                                    <td class="font-monospace small">{{ class_basename($log->model_type) }}
                                        #{{ $log->model_id }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th class="text-muted fw-normal ps-0">URL</th>
                                <td class="font-monospace small text-muted">{{ $log->method }} {{ $log->url }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Changes --}}
                @if ($log->old_values || $log->new_values)
                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2">
                            <i class="bi bi-arrow-left-right text-warning"></i>
                            <span class="fw-semibold">Changes</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="row g-0">
                                <div class="col-6 border-end p-3">
                                    <div class="small text-muted fw-semibold mb-2 text-uppercase"
                                        style="font-size:11px;letter-spacing:.06em">Before</div>
                                    @if ($log->old_values)
                                        @foreach ($log->old_values as $key => $val)
                                            <div class="d-flex gap-2 mb-1">
                                                <span class="text-muted small"
                                                    style="min-width:110px">{{ $key }}</span>
                                                <span
                                                    class="small text-danger font-monospace">{{ is_array($val) ? json_encode($val) : $val }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </div>
                                <div class="col-6 p-3">
                                    <div class="small text-muted fw-semibold mb-2 text-uppercase"
                                        style="font-size:11px;letter-spacing:.06em">After</div>
                                    @if ($log->new_values)
                                        @foreach ($log->new_values as $key => $val)
                                            <div class="d-flex gap-2 mb-1">
                                                <span class="text-muted small"
                                                    style="min-width:110px">{{ $key }}</span>
                                                <span
                                                    class="small text-success font-monospace">{{ is_array($val) ? json_encode($val) : $val }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── Who + Where ── --}}
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle text-success"></i>
                        <span class="fw-semibold">Who</span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                style="width:44px;height:44px;flex-shrink:0">
                                <i class="bi bi-person-fill text-primary fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $log->user_name ?? 'System' }}</div>
                                <div class="small text-muted">{{ $log->user_role ?? 'Unknown role' }}</div>
                            </div>
                        </div>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th class="text-muted fw-normal ps-0 small">IP Address</th>
                                <td class="small font-monospace">{{ $log->ip_address ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-normal ps-0 small">Browser</th>
                                <td class="small text-truncate" style="max-width:160px" title="{{ $log->user_agent }}">
                                    {{ $log->user_agent ? str($log->user_agent)->limit(50) : '—' }}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-normal ps-0 small">Time</th>
                                <td class="small">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Extra context if any --}}
                @if ($log->extra)
                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2">
                            <i class="bi bi-three-dots text-secondary"></i>
                            <span class="fw-semibold">Extra Context</span>
                        </div>
                        <div class="card-body">
                            <pre class="small text-muted mb-0" style="font-size:12px">{{ json_encode($log->extra, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
