@extends('layouts.master')

@section('title', 'Lab Tests')
@section('page-title', 'Laboratory — Tests')
@section('breadcrumb', 'Home / Laboratory / Tests')

@push('styles')
    <style>
        .stat-pill { background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:14px 20px }
        .stat-pill-label { font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em }
        .stat-pill-value { font-size:22px;font-weight:700;color:#1e293b }
        .card-section { background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden }
        .card-section-header { padding:14px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px }
        .filter-bar input,.filter-bar select { height:36px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;padding:0 12px;color:#374151;background:#f8fafc }
        .filter-bar input:focus,.filter-bar select:focus { outline:none;border-color:#93c5fd;background:#fff }
        .table-clean th { font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0!important;padding:10px 16px!important;background:#f8fafc }
        .table-clean td { font-size:13px;color:#374151;padding:12px 16px!important;vertical-align:middle;border-bottom:1px solid #f1f5f9!important }
        .table-clean tr:last-child td { border-bottom:none!important }
        .table-clean tr:hover td { background:#fafafa }
    </style>
@endpush

@section('content')

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Total Tests</div>
                <div class="stat-pill-value">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Active</div>
                <div class="stat-pill-value" style="color:#16a34a">{{ $stats['active'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Fasting Required</div>
                <div class="stat-pill-value" style="color:#d97706">{{ $stats['fasting'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Categories</div>
                <div class="stat-pill-value" style="color:#6366f1">{{ $stats['categories'] }}</div>
            </div>
        </div>
    </div>

    <div class="card-section">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-flask me-2 text-primary"></i>All lab tests
            </span>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <form method="GET" action="{{ route('lab.tests.index') }}"
                    class="d-flex gap-2 flex-wrap filter-bar">
                    <input type="text" name="search" placeholder="Test name, code, method..."
                        value="{{ request('search') }}" style="width:200px">
                    <select name="category_id">
                        <option value="">All categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="status">
                        <option value="">All status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    @if(request()->hasAny(['search','category_id','status']))
                        <a href="{{ route('lab.tests.index') }}" class="btn btn-sm btn-outline-secondary px-3"
                            style="height:36px;font-size:13px">Clear</a>
                    @endif
                </form>
                <a href="{{ route('lab.tests.create') }}" class="btn btn-sm btn-success px-3"
                    style="height:36px;font-size:13px">
                    <i class="bi bi-plus-lg me-1"></i>New test
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-clean mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Test Name</th>
                        <th>Category</th>
                        <th>Sample</th>
                        <th>Price</th>
                        <th>Normal Range</th>
                        <th>TAT</th>
                        <th>Flags</th>
                        <th>Status</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tests as $test)
                        <tr>
                            <td><code style="font-size:12px;color:#6366f1">{{ $test->test_code }}</code></td>
                            <td>
                                <div style="font-weight:500;color:#1e293b">{{ $test->name }}</div>
                                @if($test->method)
                                    <div style="font-size:11px;color:#94a3b8">{{ $test->method }}</div>
                                @endif
                            </td>
                            <td>
                                <span style="font-size:12px;background:#f1f5f9;color:#475569;padding:2px 9px;border-radius:20px;font-weight:500">
                                    {{ $test->category->name }}
                                </span>
                            </td>
                            <td style="color:#64748b;font-size:12px">{{ $test->sampleType?->name ?? '—' }}</td>
                            <td style="font-weight:600;color:#1e293b">Rs {{ number_format($test->price, 0) }}</td>
                            <td style="font-size:12px;color:#64748b">{{ $test->normal_range ?? '—' }}</td>
                            <td style="font-size:12px;color:#64748b">{{ $test->turnaround_label }}</td>
                            <td>
                                @if($test->requires_fasting)
                                    <span style="font-size:10px;background:#fef9c3;color:#854d0e;padding:2px 8px;border-radius:20px;font-weight:500">
                                        Fasting
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($test->is_active)
                                    <span style="font-size:11px;background:#dcfce7;color:#166534;padding:3px 10px;border-radius:20px;font-weight:500">Active</span>
                                @else
                                    <span style="font-size:11px;background:#f1f5f9;color:#64748b;padding:3px 10px;border-radius:20px;font-weight:500">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('lab.tests.edit', $test->id) }}"
                                        class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('lab.tests.toggleActive', $test->id) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm {{ $test->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} py-0 px-2"
                                            style="font-size:12px"
                                            title="{{ $test->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi bi-{{ $test->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5" style="color:#94a3b8">
                                <i class="bi bi-flask" style="font-size:40px;display:block;margin-bottom:8px"></i>
                                No tests found.
                                <a href="{{ route('lab.tests.create') }}" class="text-primary ms-1">Add first test</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tests->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid #e2e8f0">
                <span style="font-size:12px;color:#94a3b8">
                    Showing {{ $tests->firstItem() }}–{{ $tests->lastItem() }} of {{ $tests->total() }}
                </span>
                {{ $tests->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection