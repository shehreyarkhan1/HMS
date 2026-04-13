@extends('layouts.master')

@section('title', 'New Prescription')
@section('page-title', 'New Prescription')
@section('breadcrumb', 'Home / Pharmacy / Prescriptions / New')

@push('styles')
<style>
    .form-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:16px}
    .form-card-header{padding:13px 20px;border-bottom:1px solid #e2e8f0;background:#f8fafc}
    .form-card-header h6{margin:0;font-size:13px;font-weight:600;color:#374151}
    .form-card-body{padding:20px}
    .form-label{font-size:12px;font-weight:500;color:#374151;margin-bottom:5px}
    .form-control,.form-select{font-size:13px;border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;color:#1e293b}
    .form-control:focus,.form-select:focus{border-color:#93c5fd;box-shadow:0 0 0 3px rgba(59,130,246,.1)}
    .form-control.is-invalid,.form-select.is-invalid{border-color:#f87171}
    .invalid-feedback{font-size:11px}
    .required-star{color:#ef4444;margin-left:2px}

    .rx-item-row{background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px;margin-bottom:10px;position:relative}
    .rx-item-row:hover{border-color:#93c5fd}
    .remove-item{position:absolute;top:10px;right:10px;background:none;border:none;color:#ef4444;font-size:16px;cursor:pointer;padding:2px 6px;border-radius:4px}
    .remove-item:hover{background:#fee2e2}

    .freq-btn{border:1px solid #e2e8f0;border-radius:6px;padding:4px 10px;font-size:12px;background:#fff;cursor:pointer;color:#64748b;transition:all .15s}
    .freq-btn:hover,.freq-btn.active{border-color:#1d4ed8;background:#eff6ff;color:#1d4ed8}
</style>
@endpush

@section('content')

<form method="POST" action="{{ route('pharmacy.prescriptions.store') }}" id="rxForm">
@csrf

<div class="row g-3">
    <div class="col-12 col-lg-8">

        {{-- Patient & Doctor --}}
        <div class="form-card">
            <div class="form-card-header">
                <h6><i class="bi bi-person me-2 text-primary"></i>Patient & doctor</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Patient <span class="required-star">*</span></label>
                        <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror">
                            <option value="">Select patient...</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}"
                                    {{ (old('patient_id', $selectedPatient?->id) == $p->id) ? 'selected' : '' }}>
                                    {{ $p->name }} — {{ $p->mrn }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Doctor</label>
                        <select name="doctor_id" class="form-select">
                            <option value="">Select doctor...</option>
                            @foreach($doctors as $d)
                                <option value="{{ $d->id }}" {{ old('doctor_id') == $d->id ? 'selected':'' }}>
                                    Dr. {{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Prescribed date <span class="required-star">*</span></label>
                        <input type="date" name="prescribed_date"
                               class="form-control @error('prescribed_date') is-invalid @enderror"
                               value="{{ old('prescribed_date', date('Y-m-d')) }}">
                        @error('prescribed_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Valid until</label>
                        <input type="date" name="valid_until" class="form-control"
                               value="{{ old('valid_until') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Diagnosis</label>
                        <input type="text" name="diagnosis" class="form-control"
                               value="{{ old('diagnosis') }}" placeholder="e.g. Fever, Flu">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="2" class="form-control"
                                  placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Medicine Items --}}
        <div class="form-card">
            <div class="form-card-header d-flex align-items-center justify-content-between">
                <h6><i class="bi bi-capsule me-2 text-success"></i>Medicines</h6>
                <button type="button" class="btn btn-sm btn-outline-success px-3" style="font-size:12px" onclick="addItem()">
                    <i class="bi bi-plus-lg me-1"></i>Add medicine
                </button>
            </div>
            <div class="form-card-body">
                @error('items') <div class="text-danger mb-3" style="font-size:12px">{{ $message }}</div> @enderror

                <div id="itemsContainer">
                    {{-- First row --}}
                    <div class="rx-item-row" id="item_0">
                        <button type="button" class="remove-item" onclick="removeItem(0)" style="display:none">
                            <i class="bi bi-x-circle"></i>
                        </button>
                        <div class="row g-2">
                            {{-- Line 1 --}}
                            <div class="col-md-5">
                                <label class="form-label">Medicine <span class="required-star">*</span></label>
                                <select name="items[0][medicine_id]" class="form-select" onchange="fillQty(this, 0)">
                                    <option value="">Select medicine...</option>
                                    @foreach($medicines as $med)
                                        <option value="{{ $med->id }}"
                                                data-price="{{ $med->sale_price }}"
                                                data-unit="{{ $med->unit }}">
                                            {{ $med->name }}
                                            @if($med->generic_name) ({{ $med->generic_name }}) @endif
                                            — {{ $med->total_stock }} in stock
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Dosage</label>
                                <input type="text" name="items[0][dosage]" class="form-control" placeholder="500mg">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Days</label>
                                <input type="number" name="items[0][duration_days]" class="form-control" value="3" min="1">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Qty</label>
                                <input type="number" name="items[0][quantity]" class="form-control" value="1" min="1">
                            </div>

                            {{-- Line 2 --}}
                            <div class="col-md-6">
                                <label class="form-label">Frequency</label>
                                <input type="text" name="items[0][frequency]" id="freq_0" class="form-control" placeholder="1-0-1">
                                <div class="d-flex gap-1 mt-1 flex-wrap">
                                    @foreach(['1-0-1','1-1-1','0-0-1','TDS','BD','OD'] as $f)
                                        <span class="freq-btn" onclick="setFreq(0, '{{ $f }}')">{{ $f }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Instructions</label>
                                <input type="text" name="items[0][instructions]" class="form-control"
                                       placeholder="After meal, before sleep...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-12 col-lg-4">
        <div class="form-card" style="border-color:#bfdbfe">
            <div class="form-card-header" style="background:#eff6ff">
                <h6 style="color:#1d4ed8"><i class="bi bi-info-circle me-2"></i>Quick info</h6>
            </div>
            <div class="form-card-body" style="font-size:13px;color:#374151;line-height:2">
                <div><i class="bi bi-check-circle text-success me-2"></i>RX number auto-generated</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>Multiple medicines allowed</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>Dispense from prescription page</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>Status updates automatically</div>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary" style="height:42px;font-size:14px;font-weight:500">
                <i class="bi bi-file-medical me-2"></i>Create prescription
            </button>
            <a href="{{ route('pharmacy.prescriptions.index') }}"
               class="btn btn-outline-secondary" style="height:42px;font-size:14px">Cancel</a>
        </div>
    </div>
</div>

</form>

{{-- Hidden template for new rows --}}
<template id="itemTemplate">
    <div class="rx-item-row" id="item___IDX__">
        <button type="button" class="remove-item" onclick="removeItem(__IDX__)">
            <i class="bi bi-x-circle"></i>
        </button>
        <div class="row g-2">
            {{-- Line 1 --}}
            <div class="col-md-5">
                <label class="form-label">Medicine <span class="required-star">*</span></label>
                <select name="items[__IDX__][medicine_id]" class="form-select">
                    <option value="">Select medicine...</option>
                    @foreach($medicines as $med)
                        <option value="{{ $med->id }}" data-unit="{{ $med->unit }}">
                            {{ $med->name }}@if($med->generic_name) ({{ $med->generic_name }}) @endif — {{ $med->total_stock }} in stock
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Dosage</label>
                <input type="text" name="items[__IDX__][dosage]" class="form-control" placeholder="500mg">
            </div>
            <div class="col-md-2">
                <label class="form-label">Days</label>
                <input type="number" name="items[__IDX__][duration_days]" class="form-control" value="3" min="1">
            </div>
            <div class="col-md-2">
                <label class="form-label">Qty</label>
                <input type="number" name="items[__IDX__][quantity]" class="form-control" value="1" min="1">
            </div>

            {{-- Line 2 --}}
            <div class="col-md-6">
                <label class="form-label">Frequency</label>
                <input type="text" name="items[__IDX__][frequency]" id="freq___IDX__" class="form-control" placeholder="1-0-1">
                <div class="d-flex gap-1 mt-1 flex-wrap">
                    <span class="freq-btn" onclick="setFreq(__IDX__, '1-0-1')">1-0-1</span>
                    <span class="freq-btn" onclick="setFreq(__IDX__, '1-1-1')">1-1-1</span>
                    <span class="freq-btn" onclick="setFreq(__IDX__, '0-0-1')">0-0-1</span>
                    <span class="freq-btn" onclick="setFreq(__IDX__, 'TDS')">TDS</span>
                    <span class="freq-btn" onclick="setFreq(__IDX__, 'BD')">BD</span>
                    <span class="freq-btn" onclick="setFreq(__IDX__, 'OD')">OD</span>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Instructions</label>
                <input type="text" name="items[__IDX__][instructions]" class="form-control" placeholder="After meal, before sleep...">
            </div>
        </div>
    </div>
</template>

@endsection

@push('scripts')
<script>
let itemCount = 1;

function addItem() {
    const tmpl = document.getElementById('itemTemplate').innerHTML
                         .replaceAll('__IDX__', itemCount);
    document.getElementById('itemsContainer').insertAdjacentHTML('beforeend', tmpl);
    document.getElementById('item_0').querySelector('.remove-item').style.display = 'block';
    itemCount++;
}

function removeItem(idx) {
    const el = document.getElementById('item_' + idx);
    if (el) el.remove();
    if (document.querySelectorAll('.rx-item-row').length === 1) {
        document.querySelector('.rx-item-row .remove-item').style.display = 'none';
    }
}

function setFreq(idx, val) {
    const input = document.getElementById('freq_' + idx);
    if (input) input.value = val;
}
</script>
@endpush