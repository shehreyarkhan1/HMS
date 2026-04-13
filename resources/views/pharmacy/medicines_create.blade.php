{{--
=================================================================
FILE 1: resources/views/pharmacy/medicines/create.blade.php
=================================================================
--}}
@extends('layouts.master')
@section('title', 'Add Medicine')
@section('page-title', 'Add Medicine')
@section('breadcrumb', 'Home / Pharmacy / Medicines / New')

@push('styles')
<style>
    .form-wrapper { max-width:860px; margin:0 auto; }
    .form-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-bottom:20px; }
    .form-card-header { padding:13px 22px; border-bottom:1px solid #e2e8f0; background:#f8fafc; display:flex; align-items:center; gap:10px; }
    .section-icon { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:13px; }
    .section-title { font-size:13px; font-weight:600; color:#1e293b; }
    .form-card-body { padding:22px; }
    .form-label-custom { font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.05em; margin-bottom:5px; }
    .form-control-custom, .form-select-custom {
        height:40px; border:1px solid #e2e8f0; border-radius:8px;
        font-size:13px; color:#1e293b; background:#f8fafc;
        padding:0 12px; width:100%; transition:border-color .15s, box-shadow .15s;
    }
    .form-control-custom:focus, .form-select-custom:focus { outline:none; border-color:#3b82f6; background:#fff; box-shadow:0 0 0 3px rgba(59,130,246,.12); }
    textarea.form-control-custom { height:auto; padding:10px 12px; }
    .form-control-custom.is-invalid, .form-select-custom.is-invalid { border-color:#f87171; }
    .field-error { font-size:11px; color:#dc2626; margin-top:4px; }
    .btn-save { height:42px; padding:0 28px; background:linear-gradient(135deg,#1e40af,#3b82f6); color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:7px; cursor:pointer; box-shadow:0 2px 8px rgba(59,130,246,.35); }
    .btn-save:hover { opacity:.92; }
    .btn-cancel { height:42px; padding:0 20px; border:1px solid #e2e8f0; border-radius:9px; background:#fff; color:#475569; font-size:13px; font-weight:500; display:flex; align-items:center; gap:7px; text-decoration:none; }
    .btn-cancel:hover { background:#f8fafc; color:#1e293b; }
</style>
@endpush

@section('content')
<div class="form-wrapper">

    @if($errors->any())
    <div class="alert alert-danger d-flex align-items-start gap-2 mb-4" style="border-radius:10px;font-size:13px">
        <i class="bi bi-exclamation-triangle-fill mt-1"></i>
        <div><strong>Please fix:</strong>
            <ul class="mb-0 mt-1 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('pharmacy.medicines.store') }}" novalidate>
        @csrf

        {{-- Basic Info --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#dbeafe;color:#1d4ed8"><i class="bi bi-capsule-pill"></i></div>
                <span class="section-title">Medicine Information</span>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-label-custom">Brand / Trade Name <span class="text-danger">*</span></div>
                        <input type="text" name="name" class="form-control-custom @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="e.g. Panadol, Brufen">
                        @error('name')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <div class="form-label-custom">Generic Name</div>
                        <input type="text" name="generic_name" class="form-control-custom @error('generic_name') is-invalid @enderror"
                               value="{{ old('generic_name') }}" placeholder="e.g. Paracetamol, Ibuprofen">
                        @error('generic_name')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-custom">Manufacturer / Brand</div>
                        <input type="text" name="brand" class="form-control-custom"
                               value="{{ old('brand') }}" placeholder="e.g. GSK, Searle">
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-custom">Category <span class="text-danger">*</span></div>
                        <select name="category" class="form-select-custom @error('category') is-invalid @enderror">
                            @foreach(['Tablet','Capsule','Syrup','Injection','Cream','Drops','Inhaler','Powder','Other'] as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-custom">Unit <span class="text-danger">*</span></div>
                        <select name="unit" class="form-select-custom">
                            @foreach(['Tablet','Capsule','Strip','ml','mg','g','Vial','Ampoule','Tube','Bottle','Sachet','Piece'] as $u)
                                <option value="{{ $u }}" {{ old('unit', 'Tablet') == $u ? 'selected' : '' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-custom">Storage Condition</div>
                        <select name="storage_condition" class="form-select-custom">
                            <option value="">— None —</option>
                            @foreach(['Room Temperature','Refrigerate (2-8°C)','Freeze','Away from light','Keep dry'] as $s)
                                <option value="{{ $s }}" {{ old('storage_condition') == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check mb-2">
                            <input type="checkbox" name="requires_prescription" id="requires_prescription"
                                   class="form-check-input" {{ old('requires_prescription') ? 'checked' : '' }}>
                            <label class="form-check-label" for="requires_prescription" style="font-size:13px">
                                Requires prescription (Rx)
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-label-custom">Description</div>
                        <textarea name="description" rows="2" class="form-control-custom"
                                  placeholder="Uses, side effects, contraindications...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pricing & Stock --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="section-icon" style="background:#dcfce7;color:#15803d"><i class="bi bi-currency-dollar"></i></div>
                <span class="section-title">Pricing &amp; Stock Settings</span>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-label-custom">Purchase Price (Rs) <span class="text-danger">*</span></div>
                        <input type="number" name="purchase_price" class="form-control-custom @error('purchase_price') is-invalid @enderror"
                               value="{{ old('purchase_price', 0) }}" min="0" step="0.01">
                        @error('purchase_price')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-custom">Sale Price (Rs) <span class="text-danger">*</span></div>
                        <input type="number" name="sale_price" class="form-control-custom @error('sale_price') is-invalid @enderror"
                               value="{{ old('sale_price', 0) }}" min="0" step="0.01">
                        @error('sale_price')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <div class="form-label-custom">Reorder Level <span class="text-danger">*</span></div>
                        <input type="number" name="reorder_level" class="form-control-custom @error('reorder_level') is-invalid @enderror"
                               value="{{ old('reorder_level', 10) }}" min="0">
                        @error('reorder_level')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div style="margin-top:12px;padding:10px 14px;background:#fffbeb;border-radius:8px;font-size:12px;color:#854d0e">
                    <i class="bi bi-info-circle me-1"></i>
                    Stock will be added separately via batches after saving. Batches track expiry dates and supplier info.
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="form-card" style="padding:16px 22px">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <a href="{{ route('pharmacy.medicines.index') }}" class="btn-cancel">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-lg"></i> Save Medicine
                </button>
            </div>
        </div>
    </form>
</div>
@endsection