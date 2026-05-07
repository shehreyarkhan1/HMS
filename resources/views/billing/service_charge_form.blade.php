@extends('layouts.master')
@section('title', isset($charge) ? 'Edit Service Charge' : 'New Service Charge')
@section('page-title', 'Billing — ' . (isset($charge) ? 'Edit Charge' : 'New Charge'))
@section('breadcrumb', 'Home / Billing / Service Charges / ' . (isset($charge) ? 'Edit' : 'New'))

@push('styles')
<style>
    .card-section { background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden }
    .card-section-header { padding:14px 20px;border-bottom:1px solid #e2e8f0 }
    .form-label-sm { font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px }
    .form-control-clean, .form-select-clean {
        border:1px solid #e2e8f0;border-radius:8px;font-size:13px;color:#374151;
        background:#f8fafc;padding:8px 12px;width:100%;outline:none
    }
    .form-control-clean:focus, .form-select-clean:focus { border-color:#93c5fd;background:#fff }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">

        @if($errors->any())
        <div class="alert d-flex align-items-start gap-2 mb-4"
            style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;color:#991b1b;font-size:13px">
            <i class="bi bi-exclamation-triangle-fill mt-1"></i>
            <ul class="mb-0 ps-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="card-section">
            <div class="card-section-header">
                <span style="font-size:14px;font-weight:600;color:#1e293b">
                    <i class="bi bi-gear me-2 text-primary"></i>{{ isset($charge) ? 'Edit' : 'New' }} Service Charge
                </span>
            </div>
            <div class="p-4">
                <form action="{{ isset($charge) ? route('billing.service-charges.update', $charge) : route('billing.service-charges.store') }}"
                    method="POST">
                    @csrf
                    @if(isset($charge)) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-label-sm">Name <span style="color:#dc2626">*</span></div>
                            <input type="text" name="name" class="form-control-clean"
                                value="{{ old('name', $charge->name ?? '') }}"
                                placeholder="e.g. General Consultation" required>
                        </div>
                        <div class="col-md-3">
                            <div class="form-label-sm">Code <span style="color:#dc2626">*</span></div>
                            <input type="text" name="code" class="form-control-clean"
                                value="{{ old('code', $charge->code ?? '') }}"
                                placeholder="e.g. CONS-GEN" required style="text-transform:uppercase"
                                {{ isset($charge) ? 'readonly' : '' }}>
                        </div>
                        <div class="col-md-3">
                            <div class="form-label-sm">Category <span style="color:#dc2626">*</span></div>
                            <select name="category" class="form-select-clean" required>
                                @foreach(\App\Models\BillServiceCharge::categories() as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $charge->category ?? '') === $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-label-sm">Default Price (Rs.) <span style="color:#dc2626">*</span></div>
                            <input type="number" name="default_price" class="form-control-clean"
                                value="{{ old('default_price', $charge->default_price ?? 0) }}"
                                min="0" step="0.01" required>
                        </div>
                        <div class="col-md-8">
                            <div class="form-label-sm">Description</div>
                            <input type="text" name="description" class="form-control-clean"
                                value="{{ old('description', $charge->description ?? '') }}"
                                placeholder="Optional short description...">
                        </div>
                        @if(isset($charge))
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                                    {{ old('is_active', $charge->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive" style="font-size:13px;color:#374151">
                                    Active (visible in bill creation)
                                </label>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top:1px solid #e2e8f0">
                        <a href="{{ route('billing.service-charges.index') }}" style="font-size:13px;color:#64748b;text-decoration:none">
                            <i class="bi bi-arrow-left me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4" style="font-size:13px">
                            <i class="bi bi-save me-1"></i>{{ isset($charge) ? 'Update' : 'Create' }} Charge
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection