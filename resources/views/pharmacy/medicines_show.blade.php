@extends('layouts.master')
@section('title', 'Medicine — ' . ($medicine->name ?? 'N/A'))
@section('page-title', 'Medicine Detail')
@section('breadcrumb', 'Home / Pharmacy / Medicines / ' . $medicine->name)

@push('styles')
<style>
    .show-wrapper { max-width:900px; margin:0 auto; }

    .med-hero {
        background:linear-gradient(135deg,#1e3a5f,#2563eb);
        border-radius:14px; padding:22px 28px; margin-bottom:24px;
        display:flex; align-items:center; gap:20px; color:#fff;
        position:relative; overflow:hidden;
    }
    .med-hero::after { content:''; position:absolute; right:-30px; top:-30px; width:160px; height:160px; border-radius:50%; background:rgba(255,255,255,.06); }
    .hero-icon { width:56px; height:56px; border-radius:12px; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); display:flex; align-items:center; justify-content:center; font-size:24px; flex-shrink:0; }
    .hero-code { font-size:10px; opacity:.65; letter-spacing:.08em; text-transform:uppercase; }
    .hero-name { font-size:20px; font-weight:700; }
    .hero-meta { font-size:12px; opacity:.75; margin-top:3px; }
    .hero-stock { margin-left:auto; text-align:right; }
    .hero-stock-num { font-size:32px; font-weight:800; line-height:1; }
    .hero-stock-label { font-size:11px; opacity:.7; }

    .info-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-bottom:20px; }
    .info-card-header { padding:13px 20px; border-bottom:1px solid #e2e8f0; background:#f8fafc; display:flex; align-items:center; gap:10px; }
    .info-card-body { padding:20px; }
    .section-icon { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:13px; }
    .section-title { font-size:13px; font-weight:600; color:#1e293b; }

    .kv-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:16px; }
    .kv-label { font-size:10px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; margin-bottom:4px; }
    .kv-value { font-size:13px; font-weight:500; color:#1e293b; }

    /* Batch table */
    .batch-table th { font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; padding:9px 14px !important; background:#f8fafc; border-bottom:1px solid #e2e8f0 !important; }
    .batch-table td { font-size:13px; padding:11px 14px !important; vertical-align:middle; border-bottom:1px solid #f1f5f9 !important; }
    .batch-table tr:last-child td { border-bottom:none !important; }

    .badge-Active    { background:#dcfce7; color:#166534; font-size:11px; padding:3px 10px; border-radius:20px; font-weight:500; }
    .badge-Expired   { background:#fee2e2; color:#991b1b; font-size:11px; padding:3px 10px; border-radius:20px; font-weight:500; }
    .badge-Exhausted { background:#f3f4f6; color:#6b7280; font-size:11px; padding:3px 10px; border-radius:20px; font-weight:500; }

    /* Add stock form */
    .add-stock-form .form-label-custom { font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.05em; margin-bottom:5px; }
    .add-stock-form .form-control-custom, .add-stock-form .form-select-custom {
        height:38px; border:1px solid #e2e8f0; border-radius:8px;
        font-size:13px; color:#1e293b; background:#f8fafc; padding:0 12px; width:100%;
    }
    .add-stock-form .form-control-custom:focus { outline:none; border-color:#3b82f6; background:#fff; box-shadow:0 0 0 3px rgba(59,130,246,.12); }
    .field-error { font-size:11px; color:#dc2626; margin-top:3px; }
</style>
@endpush

@section('content')
<div class="show-wrapper">

    @if(session('success'))
    <div class="alert d-flex align-items-center gap-2 mb-3"
         style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;font-size:13px;color:#166534;padding:12px 16px">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert d-flex align-items-center gap-2 mb-3"
         style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;font-size:13px;color:#991b1b;padding:12px 16px">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
    </div>
    @endif

    {{-- Hero --}}
    <div class="med-hero">
        <div class="hero-icon">
            <i class="bi bi-capsule-pill"></i>
        </div>
        <div>
            <div class="hero-code">{{ $medicine->medicine_code }}</div>
            <div class="hero-name">
                {{ $medicine->name }}
                @if($medicine->requires_prescription)
                    <span style="font-size:11px;background:rgba(255,255,255,.2);padding:2px 8px;border-radius:12px;margin-left:6px">Rx</span>
                @endif
            </div>
            <div class="hero-meta">
                {{ $medicine->generic_name ?? '—' }}
                @if($medicine->brand) · {{ $medicine->brand }} @endif
                · {{ $medicine->category }} · {{ $medicine->unit }}
            </div>
        </div>
        <div class="hero-stock">
            <div class="hero-stock-num">{{ number_format($medicine->total_stock) }}</div>
            <div class="hero-stock-label">{{ $medicine->unit }}s in stock</div>
            <div style="margin-top:4px;font-size:11px;opacity:.75">
                Reorder at {{ $medicine->reorder_level }}
            </div>
        </div>
    </div>

    {{-- Expiring soon alert --}}
    @if($expiringBatches->count() > 0)
    <div class="alert d-flex align-items-center gap-2 mb-3"
         style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;font-size:13px;color:#854d0e;padding:12px 16px">
        <i class="bi bi-clock-history"></i>
        <strong>{{ $expiringBatches->count() }} batch(es) expiring within 30 days</strong>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-12 col-lg-7">

            {{-- Medicine Details --}}
            <div class="info-card">
                <div class="info-card-header">
                    <div class="section-icon" style="background:#dbeafe;color:#1d4ed8"><i class="bi bi-info-circle-fill"></i></div>
                    <span class="section-title">Details</span>
                </div>
                <div class="info-card-body">
                    <div class="kv-grid">
                        <div><div class="kv-label">Category</div><div class="kv-value">{{ $medicine->category }}</div></div>
                        <div><div class="kv-label">Unit</div><div class="kv-value">{{ $medicine->unit }}</div></div>
                        <div><div class="kv-label">Purchase price</div><div class="kv-value">Rs {{ number_format($medicine->purchase_price, 2) }}</div></div>
                        <div><div class="kv-label">Sale price</div><div class="kv-value">Rs {{ number_format($medicine->sale_price, 2) }}</div></div>
                        <div><div class="kv-label">Storage</div><div class="kv-value">{{ $medicine->storage_condition ?? '—' }}</div></div>
                        <div>
                            <div class="kv-label">Stock status</div>
                            <div class="kv-value">
                                @if($medicine->is_out_of_stock)
                                    <span style="color:#dc2626;font-weight:600">Out of stock</span>
                                @elseif($medicine->is_low_stock)
                                    <span style="color:#d97706;font-weight:600">Low stock</span>
                                @else
                                    <span style="color:#16a34a;font-weight:600">In stock</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($medicine->description)
                    <div style="margin-top:14px;padding-top:14px;border-top:1px solid #f1f5f9">
                        <div class="kv-label">Description</div>
                        <div style="font-size:13px;color:#374151;margin-top:4px;line-height:1.6">{{ $medicine->description }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Batches --}}
            <div class="info-card">
                <div class="info-card-header">
                    <div class="section-icon" style="background:#fef9c3;color:#854d0e"><i class="bi bi-box-seam-fill"></i></div>
                    <span class="section-title">Stock batches</span>
                    <span style="margin-left:auto;font-size:12px;color:#94a3b8">FEFO order</span>
                </div>
                <div class="table-responsive">
                    <table class="table batch-table mb-0">
                        <thead>
                            <tr>
                                <th>Batch</th>
                                <th>Expiry</th>
                                <th>Stock</th>
                                <th>Supplier</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($medicine->batches->sortBy('expiry_date') as $batch)
                            <tr>
                                <td>
                                    <code style="font-size:12px">{{ $batch->batch_number }}</code>
                                </td>
                                <td>
                                    <div style="font-weight:500;{{ $batch->is_expired ? 'color:#dc2626' : ($batch->is_expiring_soon ? 'color:#d97706' : '') }}">
                                        {{ $batch->expiry_date->format('d M Y') }}
                                    </div>
                                    @if(!$batch->is_expired)
                                        <div style="font-size:11px;color:#94a3b8">{{ $batch->days_to_expiry }}d left</div>
                                    @endif
                                </td>
                                <td style="font-weight:600">{{ number_format($batch->quantity_in_stock) }}</td>
                                <td style="font-size:12px;color:#94a3b8">{{ $batch->supplier_name ?? '—' }}</td>
                                <td><span class="badge-{{ $batch->status }}">{{ $batch->status }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4" style="color:#94a3b8;font-size:13px">
                                    No batches yet — add stock using the form.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            {{-- Add Stock Form --}}
            <div class="info-card add-stock-form">
                <div class="info-card-header">
                    <div class="section-icon" style="background:#dcfce7;color:#15803d"><i class="bi bi-plus-circle-fill"></i></div>
                    <span class="section-title">Add stock (new batch)</span>
                </div>
                <div class="info-card-body">
                    @if($errors->any())
                    <div class="alert alert-danger mb-3" style="font-size:12px;border-radius:8px">
                        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                    </div>
                    @endif

                    <form method="POST" action="{{ route('pharmacy.medicines.add-stock', $medicine->id) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-label-custom">Batch number <span class="text-danger">*</span></div>
                                <input type="text" name="batch_number" class="form-control-custom @error('batch_number') is-invalid @enderror"
                                       value="{{ old('batch_number') }}" placeholder="e.g. BT-2025-001">
                                @error('batch_number')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6">
                                <div class="form-label-custom">Expiry date <span class="text-danger">*</span></div>
                                <input type="date" name="expiry_date" class="form-control-custom @error('expiry_date') is-invalid @enderror"
                                       value="{{ old('expiry_date') }}" min="{{ today()->addDay()->format('Y-m-d') }}">
                                @error('expiry_date')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6">
                                <div class="form-label-custom">Manufacture date</div>
                                <input type="date" name="manufacture_date" class="form-control-custom"
                                       value="{{ old('manufacture_date') }}" max="{{ today()->format('Y-m-d') }}">
                            </div>
                            <div class="col-6">
                                <div class="form-label-custom">Quantity <span class="text-danger">*</span></div>
                                <input type="number" name="quantity" class="form-control-custom @error('quantity') is-invalid @enderror"
                                       value="{{ old('quantity') }}" min="1" placeholder="0">
                                @error('quantity')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6">
                                <div class="form-label-custom">Purchase price (Rs) <span class="text-danger">*</span></div>
                                <input type="number" name="purchase_price" class="form-control-custom @error('purchase_price') is-invalid @enderror"
                                       value="{{ old('purchase_price', $medicine->purchase_price) }}" min="0" step="0.01">
                                @error('purchase_price')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <div class="form-label-custom">Supplier name</div>
                                <input type="text" name="supplier_name" class="form-control-custom"
                                       value="{{ old('supplier_name') }}" placeholder="Supplier / distributor">
                            </div>
                            <div class="col-12">
                                <div class="form-label-custom">Supplier invoice #</div>
                                <input type="text" name="supplier_invoice" class="form-control-custom"
                                       value="{{ old('supplier_invoice') }}" placeholder="Invoice number">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success w-100" style="height:40px;font-size:13px;font-weight:500">
                                    <i class="bi bi-plus-lg me-1"></i> Add Stock
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Actions --}}
            <div class="info-card" style="padding:14px 18px">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('pharmacy.medicines.index') }}"
                       class="btn btn-outline-secondary btn-sm" style="font-size:13px">
                        <i class="bi bi-arrow-left me-1"></i>All Medicines
                    </a>
                    <a href="{{ route('pharmacy.medicines.edit', $medicine->id) }}"
                       class="btn btn-outline-warning btn-sm" style="font-size:13px">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection