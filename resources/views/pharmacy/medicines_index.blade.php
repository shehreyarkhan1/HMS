@extends('layouts.master')

@section('title', 'Medicines')
@section('page-title', 'Pharmacy — Medicines')
@section('breadcrumb', 'Home / Pharmacy / Medicines')

@push('styles')
<style>
    .stat-pill { background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:14px 20px; }
    .stat-pill-label { font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; }
    .stat-pill-value { font-size:22px; font-weight:700; color:#1e293b; }

    .card-section { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; }
    .card-section-header { padding:14px 20px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }

    .filter-bar { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
    .filter-bar input, .filter-bar select {
        height:36px; border:1px solid #e2e8f0; border-radius:8px;
        font-size:13px; padding:0 12px; color:#374151; background:#f8fafc;
    }
    .filter-bar input:focus, .filter-bar select:focus { outline:none; border-color:#93c5fd; background:#fff; }

    .table-clean th {
        font-size:11px; font-weight:600; color:#94a3b8;
        text-transform:uppercase; letter-spacing:.05em;
        border-bottom:1px solid #e2e8f0 !important;
        padding:10px 16px !important; background:#f8fafc;
    }
    .table-clean td {
        font-size:13px; color:#374151;
        padding:12px 16px !important; vertical-align:middle;
        border-bottom:1px solid #f1f5f9 !important;
    }
    .table-clean tr:last-child td { border-bottom:none !important; }
    .table-clean tr:hover td { background:#fafafa; }

    /* Stock status */
    .stock-in    { background:#dcfce7; color:#166534; font-size:11px; padding:3px 10px; border-radius:20px; font-weight:500; }
    .stock-low   { background:#fef9c3; color:#854d0e; font-size:11px; padding:3px 10px; border-radius:20px; font-weight:500; }
    .stock-out   { background:#fee2e2; color:#991b1b; font-size:11px; padding:3px 10px; border-radius:20px; font-weight:500; }

    /* Category pill */
    .cat-pill { font-size:11px; padding:2px 8px; border-radius:6px; background:#f1f5f9; color:#475569; font-weight:500; }

    /* Rx badge */
    .rx-badge { font-size:10px; padding:2px 6px; border-radius:4px; background:#fef3c7; color:#b45309; font-weight:600; }

    /* Stock number */
    .stock-num { font-size:15px; font-weight:700; }
    .stock-low-num  { color:#d97706; }
    .stock-out-num  { color:#dc2626; }
    .stock-good-num { color:#166534; }
</style>
@endpush

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-pill">
            <div class="stat-pill-label">Total medicines</div>
            <div class="stat-pill-value">{{ number_format($stats['total']) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-pill">
            <div class="stat-pill-label">Low stock</div>
            <div class="stat-pill-value" style="color:#d97706">{{ number_format($stats['low_stock']) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-pill">
            <div class="stat-pill-label">Out of stock</div>
            <div class="stat-pill-value" style="color:#dc2626">{{ number_format($stats['out']) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-pill">
            <div class="stat-pill-label">Expiring (30d)</div>
            <div class="stat-pill-value" style="color:#d97706">{{ number_format($stats['expiring']) }}</div>
        </div>
    </div>
</div>

{{-- Alerts --}}
@if($stats['out'] > 0)
<div class="alert d-flex align-items-center gap-2 mb-3"
     style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;font-size:13px;color:#991b1b;padding:12px 16px">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <strong>{{ $stats['out'] }} medicine(s) out of stock</strong> — reorder immediately.
    <a href="{{ route('pharmacy.medicines.index', ['stock' => 'out']) }}"
       class="ms-auto text-danger" style="font-size:12px">View</a>
</div>
@endif

@if($stats['expiring'] > 0)
<div class="alert d-flex align-items-center gap-2 mb-3"
     style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;font-size:13px;color:#854d0e;padding:12px 16px">
    <i class="bi bi-clock-history"></i>
    <strong>{{ $stats['expiring'] }} batch(es) expiring within 30 days</strong>
</div>
@endif

{{-- Table --}}
<div class="card-section">
    <div class="card-section-header">
        <span style="font-size:14px;font-weight:600;color:#1e293b">
            <i class="bi bi-capsule me-2 text-primary"></i>Medicine inventory
        </span>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <form method="GET" action="{{ route('pharmacy.medicines.index') }}" class="filter-bar">
                <input type="text" name="search" placeholder="Name, generic, code..."
                       value="{{ request('search') }}" style="width:200px">

                <select name="category">
                    <option value="">All categories</option>
                    @foreach(['Tablet','Capsule','Syrup','Injection','Cream','Drops','Inhaler','Powder','Other'] as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>

                <select name="stock">
                    <option value="">All stock</option>
                    <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Low stock</option>
                    <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Out of stock</option>
                </select>

                <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                @if(request()->hasAny(['search','category','stock','status']))
                    <a href="{{ route('pharmacy.medicines.index') }}"
                       class="btn btn-sm btn-outline-secondary px-3" style="height:36px;font-size:13px">Clear</a>
                @endif
            </form>

            <a href="{{ route('pharmacy.medicines.create') }}"
               class="btn btn-sm btn-success px-3" style="height:36px;font-size:13px">
                <i class="bi bi-plus-lg me-1"></i>Add medicine
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Medicine</th>
                    <th>Category</th>
                    <th>Stock</th>
                    <th>Sale price</th>
                    <th>Reorder level</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicines as $med)
                <tr>
                    <td>
                        <code style="font-size:12px;color:#6366f1">{{ $med->medicine_code }}</code>
                    </td>
                    <td>
                        <div style="font-weight:500;color:#1e293b">
                            {{ $med->name }}
                            @if($med->requires_prescription)
                                <span class="rx-badge ms-1">Rx</span>
                            @endif
                        </div>
                        <div style="font-size:11px;color:#94a3b8">
                            {{ $med->generic_name ?? '—' }}
                            @if($med->brand) · {{ $med->brand }} @endif
                        </div>
                    </td>
                    <td><span class="cat-pill">{{ $med->category }}</span></td>
                    <td>
                        @php
                            $stockClass = $med->is_out_of_stock ? 'stock-out-num'
                                        : ($med->is_low_stock ? 'stock-low-num' : 'stock-good-num');
                        @endphp
                        <span class="stock-num {{ $stockClass }}">{{ number_format($med->total_stock) }}</span>
                        <span style="font-size:11px;color:#94a3b8"> {{ $med->unit }}</span>
                    </td>
                    <td style="font-weight:500">Rs {{ number_format($med->sale_price, 2) }}</td>
                    <td style="color:#94a3b8">{{ $med->reorder_level }} {{ $med->unit }}</td>
                    <td>
                        @if($med->is_out_of_stock)
                            <span class="stock-out">Out of stock</span>
                        @elseif($med->is_low_stock)
                            <span class="stock-low">Low stock</span>
                        @else
                            <span class="stock-in">In stock</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('pharmacy.medicines.show', $med->id) }}"
                               class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('pharmacy.medicines.edit', $med->id) }}"
                               class="btn btn-sm btn-outline-warning py-0 px-2" style="font-size:12px" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('pharmacy.medicines.destroy', $med->id) }}"
                                  onsubmit="return confirm('Delete {{ $med->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger py-0 px-2"
                                        style="font-size:12px" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5" style="color:#94a3b8">
                        <i class="bi bi-capsule" style="font-size:40px;display:block;margin-bottom:8px"></i>
                        No medicines found.
                        <a href="{{ route('pharmacy.medicines.create') }}" class="text-primary ms-1">Add first medicine</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($medicines->hasPages())
    <div class="d-flex justify-content-between align-items-center px-4 py-3"
         style="border-top:1px solid #e2e8f0">
        <span style="font-size:12px;color:#94a3b8">
            Showing {{ $medicines->firstItem() }}–{{ $medicines->lastItem() }}
            of {{ $medicines->total() }} medicines
        </span>
        {{ $medicines->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection