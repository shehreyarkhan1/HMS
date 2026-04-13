@extends('layouts.master')
@section('title', 'Edit Medicine — ' . $medicine->name)
@section('page-title', 'Edit Medicine')
@section('breadcrumb', 'Home / Pharmacy / Medicines / Edit')

@push('styles')
<style>
    .form-wrapper { max-width:860px; margin:0 auto; }

    /* ── Hero ── */
    .med-hero {
        background:linear-gradient(135deg,#1e3a5f,#2563eb);
        border-radius:14px; padding:20px 28px; margin-bottom:24px;
        display:flex; align-items:center; gap:18px; color:#fff;
        position:relative; overflow:hidden;
    }
    .med-hero::after { content:''; position:absolute; right:-30px; top:-30px; width:140px; height:140px; border-radius:50%; background:rgba(255,255,255,.06); }
    .hero-icon { width:52px; height:52px; border-radius:12px; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0; }
    .hero-code { font-size:10px; opacity:.65; letter-spacing:.08em; text-transform:uppercase; }
    .hero-name { font-size:18px; font-weight:700; }
    .hero-meta { font-size:12px; opacity:.75; margin-top:2px; }
    .hero-badge { margin-left:auto; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); border-radius:20px; padding:4px 14px; font-size:12px; font-weight:600; }

    /* ── Form Card ── */
    .form-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-bottom:20px; }
    .form-card-header { padding:13px 22px; border-bottom:1px solid #e2e8f0; background:#f8fafc; display:flex; align-items:center; gap:10px; }
    .section-icon { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:13px; }
    .section-title { font-size:13px; font-weight:600; color:#1e293b; }
    .form-card-body { padding:22px; }

    /* ── Controls ── */
    .form-label-custom { font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.05em; margin-bottom:5px; }
    .form-control-custom, .form-select-custom {
        height:40px; border:1px solid #e2e8f0; border-radius:8px;
        font-size:13px; color:#1e293b; background:#f8fafc;
        padding:0 12px; width:100%;
        transition:border-color .15s, box-shadow .15s, background .15s;
    }
    .form-control-custom:focus, .form-select-custom:focus {
        outline:none; border-color:#3b82f6; background:#fff;
        box-shadow:0 0 0 3px rgba(59,130,246,.12);
    }
    textarea.form-control-custom { height:auto; padding:10px 12px; }
    .form-control-custom.is-invalid, .form-select-custom.is-invalid { border-color:#f87171; background:#fff5f5; }
    .field-error { font-size:11px; color:#dc2626; margin-top:4px; }

    /* ── Readonly field ── */
    .field-readonly {
        height:40px; border:1px solid #e2e8f0; border-radius:8px;
        font-size:13px; color:#94a3b8; background:#f8fafc;
        padding:0 12px; width:100%; display:flex; align-items:center;
        cursor:not-allowed;
    }

    /* ── Stock info box ── */
    .stock-info-box {
        background:#f8fafc; border:1px solid #e2e8f0;
        border-radius:10px; padding:14px 18px;
        display:flex; align-items:center; gap:16px;
    }
    .stock-num { font-size:28px; font-weight:800; color:#1e293b; line-height:1; }
    .stock-label { font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; }

    /* ── Action buttons ── */
    .btn-save {
        height:42px; padding:0 28px;
        background:linear-gradient(135deg,#1e40af,#3b82f6);
        color:#fff; border:none; border-radius:9px;
        font-size:13px; font-weight:600;
        display:flex; align-items:center; gap:7px;
        cursor:pointer; transition:opacity .15s, transform .1s;
        box-shadow:0 2px 8px rgba(59,130,246,.35);
    }
    .btn-save:hover { opacity:.92; transform:translateY(-1px); }
    .btn-cancel {
        height:42px; padding:0 20px;
        border:1px solid #e2e8f0; border-radius:9px;
        background:#fff; color:#475569;
        font-size:13px; font-weight:500;
        display:flex; align-items:center; gap:7px;
        text-decoration:none; transition:background .15s;
    }
    .btn-cancel:hover { background:#f8fafc; color:#1e293b; }
</style>
@endpush

@section('content')
<div class="form-wrapper">

    {{-- Validation errors --}}
    @if($errors->any())
    <div class="alert alert-danger d-flex align-items-start gap-2 mb-4"
         style="border-radius:10px;font-size:13px">
        <i class="bi bi-exclamation-triangle-fill mt-1"></i>
        <div>
            <strong>Please fix the following:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Hero Banner --}}
    <div class="med-hero">
        <div class="hero-icon"><i class="bi bi-capsule-pill"></i></div>
        <div>
            <div class="hero-code">{{ $medicine->medicine_code }}</div>
            <div class="hero-name">{{ $medicine->name }}</div>
            <div class="hero-meta">
                {{ $medicine->generic_name ?? '—' }}
                @if($medicine->brand) · {{ $medicine->brand }} @endif
                · {{ $medicine->category }}
            </div>
        </div>
        <span class="hero-badge">Editing</span>
    </div>

    <form method="POST" action="{{ route('pharmacy.medicines.update', $medicine->id) }}" novalidate>
        @csrf
        @method('PUT')

        {{-- ① Medicine Information --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#dbeafe;color:#1d4ed8">
                    <i class="bi bi-capsule-pill"></i>
                </div>
                <span class="section-title">Medicine Information</span>
            </div>
            <div class="form-card-body">
                <div class="row g-3">

                    {{-- Medicine code — readonly --}}
                    <div class="col-md-4">
                        <div class="form-label-custom">Medicine Code</div>
                        <div class="field-readonly">{{ $medicine->medicine_code }}</div>
                        <div style="font-size:11px;color:#94a3b8;margin-top:4px">Auto-generated, cannot change</div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-label-custom">Brand / Trade Name <span class="text-danger">*</span></div>
                        <input type="text" name="name"
                               class="form-control-custom @error('name') is-invalid @enderror"
                               value="{{ old('name', $medicine->name) }}"
                               placeholder="e.g. Panadol, Brufen">
                        @error('name')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-label-custom">Generic Name</div>
                        <input type="text" name="generic_name"
                               class="form-control-custom @error('generic_name') is-invalid @enderror"
                               value="{{ old('generic_name', $medicine->generic_name) }}"
                               placeholder="e.g. Paracetamol, Ibuprofen">
                        @error('generic_name')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-label-custom">Manufacturer / Brand</div>
                        <input type="text" name="brand"
                               class="form-control-custom"
                               value="{{ old('brand', $medicine->brand) }}"
                               placeholder="e.g. GSK, Searle">
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">Category <span class="text-danger">*</span></div>
                        <select name="category"
                                class="form-select-custom @error('category') is-invalid @enderror">
                            @foreach(['Tablet','Capsule','Syrup','Injection','Cream','Drops','Inhaler','Powder','Other'] as $cat)
                                <option value="{{ $cat }}"
                                    {{ old('category', $medicine->category) == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">Unit <span class="text-danger">*</span></div>
                        <select name="unit" class="form-select-custom">
                            @foreach(['Tablet','Capsule','Strip','ml','mg','g','Vial','Ampoule','Tube','Bottle','Sachet','Piece'] as $u)
                                <option value="{{ $u }}"
                                    {{ old('unit', $medicine->unit) == $u ? 'selected' : '' }}>
                                    {{ $u }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">Storage Condition</div>
                        <select name="storage_condition" class="form-select-custom">
                            <option value="">— None —</option>
                            @foreach(['Room Temperature','Refrigerate (2-8°C)','Freeze','Away from light','Keep dry'] as $s)
                                <option value="{{ $s }}"
                                    {{ old('storage_condition', $medicine->storage_condition) == $s ? 'selected' : '' }}>
                                    {{ $s }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="requires_prescription"
                                   id="requires_prescription" class="form-check-input"
                                   {{ old('requires_prescription', $medicine->requires_prescription) ? 'checked' : '' }}>
                            <label class="form-check-label" for="requires_prescription"
                                   style="font-size:13px">
                                Requires prescription (Rx)
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-label-custom">Description</div>
                        <textarea name="description" rows="2" class="form-control-custom"
                                  placeholder="Uses, side effects, contraindications...">{{ old('description', $medicine->description) }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        {{-- ② Pricing & Stock Settings --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#dcfce7;color:#15803d">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <span class="section-title">Pricing &amp; Stock Settings</span>
            </div>
            <div class="form-card-body">

                {{-- Current stock info --}}
                <div class="stock-info-box mb-4">
                    @php
                        $stockColor = $medicine->is_out_of_stock ? '#dc2626'
                                    : ($medicine->is_low_stock ? '#d97706' : '#166534');
                    @endphp
                    <div>
                        <div class="stock-label">Current stock</div>
                        <div class="stock-num" style="color:{{ $stockColor }}">
                            {{ number_format($medicine->total_stock) }}
                        </div>
                        <div style="font-size:12px;color:#94a3b8">{{ $medicine->unit }}s</div>
                    </div>
                    <div style="width:1px;background:#e2e8f0;align-self:stretch"></div>
                    <div style="font-size:12px;color:#64748b;line-height:1.8">
                        <div>Active batches: <strong>{{ $medicine->batches->where('status','Active')->count() }}</strong></div>
                        <div>Reorder at: <strong>{{ $medicine->reorder_level }} {{ $medicine->unit }}s</strong></div>
                    </div>
                    <a href="{{ route('pharmacy.medicines.show', $medicine->id) }}"
                       class="btn btn-sm btn-outline-primary ms-auto" style="font-size:12px">
                        <i class="bi bi-plus-lg me-1"></i>Add stock
                    </a>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-label-custom">Purchase Price (Rs) <span class="text-danger">*</span></div>
                        <input type="number" name="purchase_price"
                               class="form-control-custom @error('purchase_price') is-invalid @enderror"
                               value="{{ old('purchase_price', $medicine->purchase_price) }}"
                               min="0" step="0.01">
                        @error('purchase_price')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">Sale Price (Rs) <span class="text-danger">*</span></div>
                        <input type="number" name="sale_price"
                               class="form-control-custom @error('sale_price') is-invalid @enderror"
                               value="{{ old('sale_price', $medicine->sale_price) }}"
                               min="0" step="0.01">
                        @error('sale_price')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-label-custom">Reorder Level <span class="text-danger">*</span></div>
                        <input type="number" name="reorder_level"
                               class="form-control-custom @error('reorder_level') is-invalid @enderror"
                               value="{{ old('reorder_level', $medicine->reorder_level) }}"
                               min="0">
                        @error('reorder_level')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

            </div>
        </div>

        {{-- ③ Status --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#fef9c3;color:#854d0e">
                    <i class="bi bi-toggle-on"></i>
                </div>
                <span class="section-title">Status</span>
            </div>
            <div class="form-card-body">
                <div class="form-check">
                    <input type="checkbox" name="is_active" id="is_active"
                           class="form-check-input"
                           {{ old('is_active', $medicine->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active" style="font-size:13px">
                        Medicine is active
                        <span style="font-size:11px;color:#94a3b8;display:block;margin-top:2px">
                            Inactive medicines won't appear in prescriptions or dispensing.
                        </span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Action Bar --}}
        <div class="form-card" style="padding:16px 22px">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex gap-2">
                    <a href="{{ route('pharmacy.medicines.show', $medicine->id) }}" class="btn-cancel">
                        <i class="bi bi-arrow-left"></i> Back to Detail
                    </a>
                    <a href="{{ route('pharmacy.medicines.index') }}" class="btn-cancel">
                        <i class="bi bi-list"></i> All Medicines
                    </a>
                </div>
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-lg"></i> Save Changes
                </button>
            </div>
        </div>

    </form>
</div>
@endsection