@extends('layouts.master')

@section('title', 'Edit Bill #' . $bill->bill_number)
@section('page-title', 'Billing — Edit Bill')
@section('breadcrumb', 'Home / Billing / ' . $bill->bill_number . ' / Edit')

@push('styles')
    <style>
        .card-section {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden
        }

        .card-section-header {
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px
        }

        .form-label-sm {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 5px
        }

        .form-control-clean,
        .form-select-clean {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            color: #374151;
            background: #f8fafc;
            padding: 8px 12px;
            width: 100%;
            outline: none
        }

        .form-control-clean:focus,
        .form-select-clean:focus {
            border-color: #93c5fd;
            background: #fff
        }

        .table-items th {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding: 8px 12px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0
        }

        .table-items td {
            padding: 8px 10px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9
        }

        .table-items tr:last-child td {
            border-bottom: none
        }

        .table-items input,
        .table-items select {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 12px;
            padding: 5px 8px;
            color: #374151;
            background: #f8fafc;
            width: 100%;
            outline: none
        }

        .table-items input:focus,
        .table-items select:focus {
            border-color: #93c5fd;
            background: #fff
        }

        .summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-size: 13px
        }

        .summary-row.total {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            margin-top: 4px
        }

        .quick-btn {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 7px 12px;
            font-size: 12px;
            color: #374151;
            background: #fff;
            cursor: pointer;
            width: 100%;
            margin-bottom: 6px
        }

        .quick-btn:hover {
            border-color: #93c5fd;
            background: #f0f9ff
        }

        .quick-btn .price {
            font-weight: 600;
            color: #6366f1
        }

        .section-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 6px
        }
    </style>
@endpush

@section('content')

    @if ($errors->any())
        <div class="alert mb-4 d-flex align-items-start gap-2"
            style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;color:#991b1b;font-size:13px">
            <i class="bi bi-exclamation-triangle-fill mt-1"></i>
            <ul class="mb-0 ps-2">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('billing.show', $bill) }}" style="font-size:13px;color:#64748b;text-decoration:none">
            <i class="bi bi-arrow-left me-1"></i>Back to Bill
        </a>
        <span style="color:#e2e8f0">/</span>
        <span style="font-size:13px;color:#1e293b;font-weight:600">Editing {{ $bill->bill_number }}</span>
    </div>

    <form action="{{ route('billing.update', $bill) }}" method="POST" id="billForm">
        @csrf @method('PUT')
        <div class="row g-4">

            <div class="col-lg-8">

                <div class="card-section mb-4">
                    <div class="card-section-header">
                        <span style="font-size:14px;font-weight:600;color:#1e293b">
                            <i class="bi bi-person-badge me-2 text-primary"></i>Bill Information
                        </span>
                    </div>
                    <div class="p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-label-sm">Patient</div>
                                <div
                                    style="background:#f1f5f9;border:1px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;font-weight:600;color:#374151">
                                    {{ $bill->patient->name }} — {{ $bill->patient->mrn }}
                                </div>
                                {{-- Patient cannot be changed on edit --}}
                            </div>
                            <div class="col-md-3">
                                <div class="form-label-sm">Bill Date <span style="color:#dc2626">*</span></div>
                                <input type="date" name="bill_date" class="form-control-clean"
                                    value="{{ old('bill_date', $bill->bill_date->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-3">
                                <div class="form-label-sm">Bill Type <span style="color:#dc2626">*</span></div>
                                <select name="bill_type" class="form-select-clean" required>
                                    @foreach (['OPD', 'IPD', 'Emergency'] as $t)
                                        <option value="{{ $t }}"
                                            {{ old('bill_type', $bill->bill_type) === $t ? 'selected' : '' }}>{{ $t }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-section mb-4">
                    <div class="card-section-header">
                        <span style="font-size:14px;font-weight:600;color:#1e293b">
                            <i class="bi bi-list-ul me-2 text-success"></i>Bill Items
                        </span>
                        <button type="button" onclick="addItemRow()"
                            style="font-size:12px;padding:5px 14px;border:1px solid #22c55e;border-radius:8px;background:#fff;color:#16a34a;cursor:pointer;font-weight:600">
                            <i class="bi bi-plus-lg me-1"></i>Add Item
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-items mb-0" style="min-width:700px">
                            <thead>
                                <tr>
                                    <th style="width:130px">Type</th>
                                    <th>Description</th>
                                    <th style="width:65px">Qty</th>
                                    <th style="width:110px">Unit Price</th>
                                    <th style="width:100px">Discount</th>
                                    <th style="width:110px;text-align:right">Total</th>
                                    <th style="width:36px"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody"></tbody>
                        </table>
                    </div>
                    <div id="emptyMsg" style="display:none;text-align:center;padding:20px;color:#94a3b8;font-size:13px">
                        No items. Click "Add Item" to add.
                    </div>
                </div>

                <div class="card-section">
                    <div class="p-4">
                        <div class="form-label-sm">Notes / Remarks</div>
                        <textarea name="notes" class="form-control-clean" rows="2" placeholder="Optional notes...">{{ old('notes', $bill->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="summary-box mb-4" style="position:sticky;top:80px">
                    <div style="font-size:13px;font-weight:700;color:#1e293b;margin-bottom:16px">
                        <i class="bi bi-calculator me-2 text-warning"></i>Bill Summary
                    </div>
                    <div class="summary-row"><span style="color:#64748b">Subtotal</span><span
                            style="font-weight:600;color:#1e293b" id="summarySubtotal">Rs. 0</span></div>

                    <div style="margin-bottom:12px">
                        <div class="form-label-sm" style="margin-bottom:5px">Discount (Rs.)</div>
                        <input type="number" name="discount_amount" id="discountAmount" class="form-control-clean"
                            value="{{ old('discount_amount', $bill->discount_amount) }}" min="0" step="0.01"
                            onchange="recalcSummary()" style="margin-bottom:6px">
                        <input type="text" name="discount_reason" class="form-control-clean" placeholder="Reason..."
                            value="{{ old('discount_reason', $bill->discount_reason) }}">
                    </div>
                    <div style="margin-bottom:12px">
                        <div class="form-label-sm" style="margin-bottom:5px">Tax / Extra (Rs.)</div>
                        <input type="number" name="tax_amount" id="taxAmount" class="form-control-clean"
                            value="{{ old('tax_amount', $bill->tax_amount) }}" min="0" step="0.01"
                            onchange="recalcSummary()">
                    </div>
                    <div class="summary-row" style="color:#dc2626;font-size:12px"><span>Discount</span><span
                            id="summaryDiscount">− Rs. 0</span></div>
                    <div class="summary-row" style="color:#0891b2;font-size:12px"><span>Tax / Extra</span><span
                            id="summaryTax">+ Rs. 0</span></div>
                    <div class="summary-row total"><span>Net Payable</span><span style="color:#d97706"
                            id="summaryNet">Rs. 0</span></div>

                    <div style="display:flex;flex-direction:column;gap:8px;margin-top:16px">
                        <button type="submit" class="btn btn-warning w-100 fw-semibold" style="font-size:13px">
                            <i class="bi bi-save me-1"></i>Save Changes
                        </button>
                        <a href="{{ route('billing.show', $bill) }}" class="btn btn-outline-secondary w-100"
                            style="font-size:13px">Cancel</a>
                    </div>
                </div>

                <div class="card-section">
                    <div class="card-section-header">
                        <span style="font-size:13px;font-weight:600;color:#1e293b"><i
                                class="bi bi-grid me-2 text-primary"></i>Quick Add</span>
                    </div>
                    <div class="p-3">
                        @foreach ($serviceCharges->groupBy('category') as $category => $charges)
                            <div class="section-label mt-2">{{ $category }}</div>
                            @foreach ($charges as $charge)
                                <button type="button" class="quick-btn"
                                    onclick='quickAdd(@json($charge))'>
                                    <span>{{ $charge->name }}</span>
                                    <span class="price">Rs. {{ number_format($charge->default_price, 0) }}</span>
                                </button>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </form>

    @push('scripts')
        <script>
            let idx = 0;
            const serviceTypes = @json(\App\Models\BillItem::serviceTypes());
            const existingItems = @json($bill->items);

            function addItemRow(data = {}) {
                const i = idx++;
                const qty = data.quantity ?? 1;
                const prc = data.unit_price ?? 0;
                const dsc = data.discount ?? 0;
                const tot = Math.max(0, qty * prc - dsc);
                const typeOptions = serviceTypes.map(t =>
                    `<option value="${t}" ${data.service_type===t?'selected':''}>${t}</option>`
                ).join('');

                document.getElementById('itemsBody').insertAdjacentHTML('beforeend', `
        <tr id="row-${i}">
            <td>
                <select name="items[${i}][service_type]" required>${typeOptions}</select>
                <input type="hidden" name="items[${i}][reference_type]" value="${data.reference_type??''}">
                <input type="hidden" name="items[${i}][reference_id]"   value="${data.reference_id??''}">
            </td>
            <td><input type="text" name="items[${i}][description]" value="${data.description??''}" placeholder="Description..." required></td>
            <td><input type="number" name="items[${i}][quantity]"   class="qty" value="${qty}" min="0.01" step="0.01" onchange="recalcRow(${i})"></td>
            <td><input type="number" name="items[${i}][unit_price]" class="prc" value="${prc}" min="0"    step="0.01" onchange="recalcRow(${i})"></td>
            <td><input type="number" name="items[${i}][discount]"   class="dsc" value="${dsc}" min="0"    step="0.01" onchange="recalcRow(${i})"></td>
            <td style="text-align:right;font-size:12px;font-weight:600;color:#1e293b;padding-right:14px">
                <span id="tot-${i}">Rs. ${tot.toFixed(0)}</span>
            </td>
            <td style="text-align:center">
                <button type="button" onclick="removeRow(${i})" style="border:none;background:none;color:#ef4444;font-size:15px;cursor:pointer">
                    <i class="bi bi-x-lg"></i>
                </button>
            </td>
        </tr>
    `);
                document.getElementById('emptyMsg').style.display = 'none';
                recalcSummary();
            }

            function removeRow(i) {
                document.getElementById('row-' + i)?.remove();
                if (!document.getElementById('itemsBody').children.length)
                    document.getElementById('emptyMsg').style.display = 'block';
                recalcSummary();
            }

            function recalcRow(i) {
                const row = document.getElementById('row-' + i);
                const qty = parseFloat(row.querySelector('.qty').value) || 0;
                const prc = parseFloat(row.querySelector('.prc').value) || 0;
                const dsc = parseFloat(row.querySelector('.dsc').value) || 0;
                document.getElementById('tot-' + i).textContent = 'Rs. ' + Math.max(0, qty * prc - dsc).toFixed(0);
                recalcSummary();
            }

            function recalcSummary() {
                let sub = 0;
                document.querySelectorAll('[id^="tot-"]').forEach(el => {
                    sub += parseFloat(el.textContent.replace('Rs. ', '')) || 0;
                });
                const disc = parseFloat(document.getElementById('discountAmount').value) || 0;
                const tax = parseFloat(document.getElementById('taxAmount').value) || 0;
                document.getElementById('summarySubtotal').textContent = 'Rs. ' + sub.toFixed(0);
                document.getElementById('summaryDiscount').textContent = '− Rs. ' + disc.toFixed(0);
                document.getElementById('summaryTax').textContent = '+ Rs. ' + tax.toFixed(0);
                document.getElementById('summaryNet').textContent = 'Rs. ' + Math.max(0, sub - disc + tax).toFixed(0);
            }

            function quickAdd(c) {
                addItemRow({
                    service_type: c.category === 'Consultation' ? 'Consultation' : 'Service',
                    description: c.name,
                    quantity: 1,
                    unit_price: parseFloat(c.default_price)
                });
            }

            // Load existing items on page load
            document.addEventListener('DOMContentLoaded', () => {
                existingItems.forEach(item => addItemRow({
                    service_type: item.service_type,
                    description: item.description,
                    reference_type: item.reference_type,
                    reference_id: item.reference_id,
                    quantity: parseFloat(item.quantity),
                    unit_price: parseFloat(item.unit_price),
                    discount: parseFloat(item.discount),
                }));
            });
        </script>
    @endpush
@endsection
