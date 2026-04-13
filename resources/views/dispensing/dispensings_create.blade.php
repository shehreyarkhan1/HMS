{{-- ============================================================ --}}
{{-- resources/views/pharmacy/dispensings_create.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.master')

@section('title', 'Dispense Medicines')
@section('page-title', 'Dispense Medicines')
@section('breadcrumb', 'Home / Pharmacy / Dispensing / New')

@push('styles')
    <style>
        .form-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 16px
        }

        .form-card-header {
            padding: 13px 20px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc
        }

        .form-card-header h6 {
            margin: 0;
            font-size: 13px;
            font-weight: 600;
            color: #374151
        }

        .form-card-body {
            padding: 20px
        }

        .form-label {
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 5px
        }

        .form-control,
        .form-select {
            font-size: 13px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            color: #1e293b
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .1)
        }

        .dsp-item-row {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 10px;
            position: relative
        }

        .remove-item {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            color: #ef4444;
            font-size: 16px;
            cursor: pointer;
            padding: 2px 6px;
            border-radius: 4px
        }

        .total-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 16px;
            text-align: center
        }

        .total-value {
            font-size: 28px;
            font-weight: 700;
            color: #1d4ed8
        }
    </style>
@endpush

@section('content')

    <form method="POST" action="{{ route('pharmacy.dispensings.store') }}" id="dspForm">
        @csrf

        @if($prescription)
            <input type="hidden" name="prescription_id" value="{{ $prescription->id }}">
            <input type="hidden" name="patient_id" value="{{ $prescription->patient_id }}">
        @endif

        <div class="row g-3">
            <div class="col-12 col-lg-8">

                {{-- Prescription Info (if from RX) --}}
                @if($prescription)
                    <div
                        style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:16px 20px;margin-bottom:16px">
                        <div style="font-size:13px;font-weight:600;color:#166534;margin-bottom:8px">
                            <i class="bi bi-file-medical me-2"></i>Dispensing from prescription
                            {{ $prescription->prescription_number }}
                        </div>
                        <div style="font-size:13px;color:#374151">
                            Patient: <strong>{{ $prescription->patient->name }}</strong> ({{ $prescription->patient->mrn }})
                            &bull; Doctor:
                            <strong>{{ $prescription->doctor ? 'Dr. ' . $prescription->doctor->name : '—' }}</strong>
                        </div>
                    </div>
                @else
                    <div class="form-card">
                        <div class="form-card-header">
                            <h6><i class="bi bi-person me-2 text-primary"></i>Patient</h6>
                        </div>
                        <div class="form-card-body">
                            <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror">
                                <option value="">Select patient...</option>
                                @foreach($patients as $p)
                                    <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} — {{ $p->mrn }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id') <div class="text-danger mt-1" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif

                {{-- Medicines --}}
                <div class="form-card">
                    <div class="form-card-header d-flex align-items-center justify-content-between">
                        <h6><i class="bi bi-capsule me-2 text-success"></i>Medicines to dispense</h6>
                        <button type="button" class="btn btn-sm btn-outline-success px-3" style="font-size:12px"
                            onclick="addDspItem()">
                            <i class="bi bi-plus-lg me-1"></i>Add medicine
                        </button>
                    </div>
                    <div class="form-card-body">
                        <div id="dspContainer">
                            @if($prescription)
                                @foreach($prescription->items->where('is_fully_dispensed', false) as $i => $rxItem)
                                    <div class="dsp-item-row">
                                        <input type="hidden" name="items[{{ $i }}][prescription_item_id]" value="{{ $rxItem->id }}">
                                        <input type="hidden" name="items[{{ $i }}][medicine_id]" value="{{ $rxItem->medicine_id }}">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-md-5">
                                                <label class="form-label">Medicine</label>
                                                <div
                                                    style="font-size:13px;font-weight:500;padding:8px 12px;background:#fff;border:1px solid #e2e8f0;border-radius:8px">
                                                    {{ $rxItem->medicine->name }}
                                                    <span style="font-size:11px;color:#94a3b8">· {{ $rxItem->dosage }}
                                                        {{ $rxItem->frequency }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Batch</label>
                                                <select name="items[{{ $i }}][medicine_batch_id]" class="form-select">
                                                    @foreach($rxItem->medicine->batches->where('status', 'Active')->where('quantity_in_stock', '>', 0) as $batch)
                                                        <option value="{{ $batch->id }}">
                                                            {{ $batch->batch_number }} — Exp: {{ $batch->expiry_date->format('M Y') }}
                                                            ({{ $batch->quantity_in_stock }} left)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Qty (Remaining: {{ $rxItem->remaining_qty }})</label>
                                                <input type="number" name="items[{{ $i }}][quantity]" class="form-control"
                                                    value="{{ $rxItem->remaining_qty }}" min="1" max="{{ $rxItem->remaining_qty }}"
                                                    onchange="calcTotal()">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="dsp-item-row" id="dspItem_0">
                                    <button type="button" class="remove-item"
                                        onclick="this.closest('.dsp-item-row').remove();calcTotal()" style="display:none">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                    <div class="row g-2 align-items-center">
                                        <div class="col-md-5">
                                            <label class="form-label">Medicine</label>
                                            <select name="items[0][medicine_id]" class="form-select med-select"
                                                onchange="loadBatches(this,0)">
                                                <option value="">Select medicine...</option>
                                                @foreach($medicines as $med)
                                                    <option value="{{ $med->id }}" data-price="{{ $med->sale_price }}">
                                                        {{ $med->name }} ({{ $med->total_stock }} in stock)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Batch</label>
                                            <select name="items[0][medicine_batch_id]" class="form-select" id="batch_0">
                                                <option value="">Select medicine first</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Qty</label>
                                            <input type="number" name="items[0][quantity]" class="form-control qty-input"
                                                value="1" min="1" onchange="calcTotal()">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT --}}
            <div class="col-12 col-lg-4">
                <div class="form-card">
                    <div class="form-card-header">
                        <h6><i class="bi bi-cash me-2 text-success"></i>Payment</h6>
                    </div>
                    <div class="form-card-body">
                        <div class="total-box mb-3">
                            <div style="font-size:12px;color:#3b82f6;margin-bottom:4px">Total amount</div>
                            <div class="total-value" id="totalDisplay">Rs 0</div>
                        </div>
                        <label class="form-label">Payment status</label>
                        <select name="payment_status" class="form-select @error('payment_status') is-invalid @enderror">
                            <option value="Paid" {{ old('payment_status', 'Paid') == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Unpaid" {{ old('payment_status') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="Partial" {{ old('payment_status') == 'Partial' ? 'selected' : '' }}>Partial
                            </option>
                        </select>
                        <label class="form-label mt-3">Notes</label>
                        <textarea name="notes" rows="2" class="form-control"
                            placeholder="Optional notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success" style="height:42px;font-size:14px;font-weight:500">
                        <i class="bi bi-capsule me-2"></i>Dispense medicines
                    </button>
                    <a href="{{ route('pharmacy.dispensings.index') }}" class="btn btn-outline-secondary"
                        style="height:42px;font-size:14px">Cancel</a>
                </div>
            </div>
        </div>

    </form>

    {{-- Medicine batches data for JS --}}
    @php
        $medicinesData = $medicines->map(function ($m) {
            return [
                'id' => $m->id,
                'price' => $m->sale_price,
                'batches' => $m->batches
                    ->where('status', 'Active')
                    ->where('quantity_in_stock', '>', 0)
                    ->values()
                    ->map(function ($b) {
                        return [
                            'id' => $b->id,
                            'label' => $b->batch_number . ' — Exp: ' . \Carbon\Carbon::parse($b->expiry_date)->format('M Y') . ' (' . $b->quantity_in_stock . ' left)',
                            'stock' => $b->quantity_in_stock,
                        ];
                    })->values()
            ];
        })->values();
    @endphp

    <script>
        const medicinesData = @json($medicinesData);
    </script>
    <script>

        function loadBatches(select, idx) {
            const medId = parseInt(select.value);
            const med = medicinesData.find(m => m.id === medId);
            const batchSel = document.getElementById('batch_' + idx);
            if (!batchSel) return;
            batchSel.innerHTML = '<option value="">Select batch...</option>';
            if (med && med.batches.length) {
                med.batches.forEach(b => {
                    batchSel.innerHTML += `<option value="${b.id}" data-stock="${b.stock}">${b.label}</option>`;
                });
            }
            calcTotal();
        }

        function calcTotal() {
            let total = 0;
            document.querySelectorAll('.dsp-item-row').forEach(row => {
                const medSel = row.querySelector('select[name*="medicine_id"]');
                const qty = parseInt(row.querySelector('input[name*="quantity"]')?.value) || 0;
                if (medSel) {
                    const opt = medSel.options[medSel.selectedIndex];
                    const price = parseFloat(opt?.dataset?.price || 0);
                    total += price * qty;
                }
            });
            document.getElementById('totalDisplay').textContent = 'Rs ' + total.toFixed(2);
        }

        function addDspItem() {
            const idx = dspCount++;
            const html = `
            <div class="dsp-item-row">
                <button type="button" class="remove-item" onclick="this.closest('.dsp-item-row').remove();calcTotal()">
                    <i class="bi bi-x-circle"></i>
                </button>
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <label class="form-label">Medicine</label>
                        <select name="items[${idx}][medicine_id]" class="form-select" onchange="loadBatches(this,${idx})">
                            <option value="">Select medicine...</option>
                            ${medicinesData.map(m => `<option value="${m.id}" data-price="${m.price}">${m.id}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Batch</label>
                        <select name="items[${idx}][medicine_batch_id]" class="form-select" id="batch_${idx}">
                            <option value="">Select medicine first</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Qty</label>
                        <input type="number" name="items[${idx}][quantity]" class="form-control qty-input" value="1" min="1"
                            onchange="calcTotal()">
                    </div>
                </div>
            </div>`;
            document.getElementById('dspContainer').insertAdjacentHTML('beforeend', html);
            document.querySelectorAll('.remove-item').forEach(b => b.style.display = 'block');
        }
    </script>

@endsection