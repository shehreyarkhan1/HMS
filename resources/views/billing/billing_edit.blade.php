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
            overflow: hidden;
        }

        .card-section-header {
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .form-label-sm {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 5px;
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
            outline: none;
            transition: border-color .2s;
        }

        .form-control-clean:focus,
        .form-select-clean:focus {
            border-color: #93c5fd;
            background: #fff;
        }

        /* ── Locked patient field ── */
        .patient-locked {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .patient-locked .lock-icon {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: #e0f2fe;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0369a1;
            font-size: 12px;
            flex-shrink: 0;
        }

        /* ── Bill Items table ── */
        .table-items th {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding: 8px 12px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-items td {
            padding: 8px 10px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-items tr:last-child td {
            border-bottom: none;
        }

        .form-control-sm,
        .form-select-sm {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 12px;
            padding: 5px 8px;
            color: #374151;
            background: #f8fafc;
            width: 100%;
            outline: none;
            transition: all .2s;
        }

        .form-control-sm:focus,
        .form-select-sm:focus {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .1);
        }

        /* ── Bill Summary card — identical to create.blade ── */
        .summary-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .summary-card-header {
            padding: 13px 18px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8fafc;
        }

        .summary-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: #fef3c7;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ff9e2f;
            font-size: 15px;
        }

        .summary-card-body {
            padding: 16px;
            background: #f8fafc;
        }

        .metric-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 14px;
        }

        .metric-tile {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
        }

        .metric-tile .mlabel {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .metric-tile .mval {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }

        .adj-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 14px;
        }

        .adj-section .full {
            grid-column: span 2;
        }

        .net-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            margin-top: 4px;
            margin-bottom: 16px;
        }

        .net-label {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
        }

        .net-value {
            font-size: 18px;
            font-weight: 700;
            color: #d97706;
        }

        /* ── Action buttons ── */
        .action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .btn-cancel {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
            transition: background .15s;
        }

        .btn-cancel:hover {
            background: #f1f5f9;
            color: #374151;
        }

        .btn-save {
            background: #ffb32f;
            border: 1px solid #d97706;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 13px;
            font-weight: 600;
            color: #000;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background .15s;
        }

        .btn-save:hover {
            background: #d97706;
        }

        /* ── Bill status badge on header ── */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .status-draft {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .status-finalized {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        /* ── Quick Add Services ── */
        .quick-services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 8px;
            padding: 16px;
        }

        .quick-svc-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 12px;
            color: #374151;
            background: #fff;
            cursor: pointer;
            transition: border-color .15s, background .15s;
            text-align: left;
            gap: 8px;
        }

        .quick-svc-btn:hover {
            border-color: #93c5fd;
            background: #f0f9ff;
        }

        .quick-svc-btn:hover .svc-add-circle {
            opacity: 1;
        }

        .quick-svc-info {
            display: flex;
            flex-direction: column;
            gap: 3px;
            flex: 1;
            min-width: 0;
        }

        .quick-svc-name {
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .quick-svc-price {
            font-size: 12px;
            font-weight: 700;
            color: #185FA5;
        }

        .svc-add-circle {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #E6F1FB;
            color: #185FA5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
            opacity: 0;
            transition: opacity .15s;
        }

        .cat-section-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em;
            padding: 12px 16px 4px;
        }

        .cat-section-label:not(:first-child) {
            border-top: 1px solid #f1f5f9;
        }

        /* ── Scroll highlight ── */
        @keyframes highlightPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.3);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(59, 130, 246, 0.1);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }

        .bill-items-highlight {
            animation: highlightPulse .8s ease-out;
        }

        /* ── Existing item row — subtle tint ── */
        .existing-item-row {
            background: #fafeff;
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

    {{-- ── Back nav + bill number ── --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('billing.show', $bill) }}"
            style="font-size:13px;color:#64748b;text-decoration:none;display:inline-flex;align-items:center;gap:4px">
            <i class="bi bi-arrow-left"></i>Back to Bill
        </a>
        <span style="color:#e2e8f0">/</span>
        <span style="font-size:13px;color:#1e293b;font-weight:600">{{ $bill->bill_number }}</span>
        @php
            $statusClass = match ($bill->status) {
                'draft' => 'status-draft',
                'finalized' => 'status-finalized',
                'paid' => 'status-paid',
                'cancelled' => 'status-cancelled',
                default => 'status-draft',
            };
        @endphp
        <span class="status-pill {{ $statusClass }}">
            <i class="bi bi-circle-fill" style="font-size:6px"></i>
            {{ ucfirst($bill->status) }}
        </span>
    </div>

    <form action="{{ route('billing.update', $bill) }}" method="POST" id="billForm">
        @csrf @method('PUT')
        <div class="row g-4">

            {{-- ─── LEFT (col-lg-8) ──────────────────────────────────── --}}
            <div class="col-lg-8">

                {{-- Bill Information --}}
                <div class="card-section mb-4">
                    <div class="card-section-header">
                        <span style="font-size:14px;font-weight:600;color:#1e293b">
                            <i class="bi bi-person-badge me-2 text-primary"></i>Bill Information
                        </span>
                    </div>
                    <div class="p-4">
                        <div class="row g-3">

                            {{-- Locked patient --}}
                            <div class="col-md-5">
                                <div class="form-label-sm">Patient</div>
                                <div class="patient-locked">
                                    <div class="lock-icon"><i class="bi bi-person-fill"></i></div>
                                    <div style="min-width:0">
                                        <div
                                            style="font-size:13px;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                            {{ $bill->patient->name }}
                                        </div>
                                        <div style="font-size:11px;color:#94a3b8;margin-top:1px">
                                            MRN: {{ $bill->patient->mrn }}
                                            &nbsp;·&nbsp; {{ $bill->patient->patient_type }}
                                        </div>
                                    </div>
                                    <i class="bi bi-lock-fill ms-auto" style="color:#cbd5e1;font-size:12px"
                                        title="Patient cannot be changed on edit"></i>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-label-sm">Bill Date <span style="color:#dc2626">*</span></div>
                                <input type="date" name="bill_date" class="form-control-clean"
                                    value="{{ old('bill_date', $bill->bill_date->format('Y-m-d')) }}" required>
                            </div>

                            <div class="col-md-2">
                                <div class="form-label-sm">Bill Type <span style="color:#dc2626">*</span></div>
                                <select name="bill_type" class="form-select-clean" required>
                                    @foreach (['OPD', 'IPD', 'Emergency'] as $t)
                                        <option value="{{ $t }}"
                                            {{ old('bill_type', $bill->bill_type) === $t ? 'selected' : '' }}>
                                            {{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <div
                                    style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:8px 12px;font-size:12px;color:#92400e;width:100%;display:flex;align-items:center;gap:6px">
                                    <i class="bi bi-pencil-square" style="font-size:14px;color:#d97706"></i>
                                    <span>Editing existing bill</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Bill Items --}}
                <div class="card-section mb-4" id="billItemsCard">
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
                                    <th style="width:100px">Qty</th>
                                    <th style="width:110px">Unit Price</th>
                                    <th style="width:80px">Discount</th>
                                    <th style="width:110px;text-align:right">Total</th>
                                    <th style="width:36px"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody"></tbody>
                        </table>
                    </div>
                    <div id="emptyMsg" style="display:none;text-align:center;padding:30px;color:#94a3b8;font-size:13px">
                        <i class="bi bi-plus-circle" style="font-size:32px;display:block;margin-bottom:8px;opacity:.4"></i>
                        No items. Click "Add Item" or use Quick Add Services below.
                    </div>
                </div>

                {{-- Notes --}}
                <div class="card-section mb-4">
                    <div class="p-4">
                        <div class="form-label-sm">Notes / Remarks</div>
                        <textarea name="notes" class="form-control-clean" rows="2" placeholder="Optional internal notes...">{{ old('notes', $bill->notes) }}</textarea>
                    </div>
                </div>

                {{-- Quick Add Services --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <span style="font-size:14px;font-weight:600;color:#1e293b">
                            <i class="bi bi-grid me-2 text-primary"></i>Quick Add Services
                        </span>
                        <span style="font-size:11px;color:#94a3b8">Click any service to add it to the bill</span>
                    </div>

                    @forelse($serviceCharges->groupBy('category') as $category => $charges)
                        <div class="cat-section-label">{{ $category }}</div>
                        <div class="quick-services-grid">
                            @foreach ($charges as $charge)
                                <button type="button" class="quick-svc-btn"
                                    onclick='quickAdd(@json($charge))'>
                                    <div class="quick-svc-info">
                                        <span class="quick-svc-name">{{ $charge->name }}</span>
                                        <span class="quick-svc-price">Rs.
                                            {{ number_format($charge->default_price, 0) }}</span>
                                    </div>
                                    <div class="svc-add-circle">
                                        <i class="bi bi-plus"></i>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @empty
                        <div style="text-align:center;padding:24px;color:#94a3b8;font-size:13px">
                            No service charges configured.
                            <a href="{{ route('billing.service-charges.index') }}" style="color:#6366f1">
                                Setup service charges →
                            </a>
                        </div>
                    @endforelse
                </div>

            </div>{{-- end col-lg-8 --}}

            {{-- ─── RIGHT (col-lg-4) ────────────────────────────────── --}}
            <div class="col-lg-4">
                <div style="position:sticky;top:80px">

                    {{-- Bill Summary — exact same design as create.blade --}}
                    <div class="summary-card mb-4">
                        <div class="summary-card-header">
                            <div class="summary-icon">
                                <i class="bi bi-calculator text-warning"></i>
                            </div>
                            <span style="font-size:13px;font-weight:700;color:#1e293b">
                                <i class="bi bi-calculator me-2 text-warning"></i>Bill Summary
                            </span>
                        </div>
                        <div class="summary-card-body">

                            <div class="metric-grid">
                                <div class="metric-tile">
                                    <div class="mlabel">Subtotal</div>
                                    <div class="mval" id="summarySubtotal">Rs. 0</div>
                                </div>
                                <div class="metric-tile">
                                    <div class="mlabel">Items</div>
                                    <div class="mval" style="color:#185FA5" id="summaryItems">0</div>
                                </div>
                                <div class="metric-tile">
                                    <div class="mlabel">Discount</div>
                                    <div class="mval" style="color:#A32D2D" id="summaryDiscount">− Rs. 0</div>
                                </div>
                                <div class="metric-tile">
                                    <div class="mlabel">Tax / Extra</div>
                                    <div class="mval" style="color:#0F6E56" id="summaryTax">+ Rs. 0</div>
                                </div>
                            </div>

                            <div class="adj-section">
                                <div>
                                    <div class="form-label-sm">Discount (Rs.)</div>
                                    <input type="number" name="discount_amount" id="discountAmount"
                                        class="form-control-clean"
                                        value="{{ old('discount_amount', $bill->discount_amount) }}" min="0"
                                        step="0.01" oninput="recalcSummary()">
                                </div>
                                <div>
                                    <div class="form-label-sm">Tax / Extra (Rs.)</div>
                                    <input type="number" name="tax_amount" id="taxAmount" class="form-control-clean"
                                        value="{{ old('tax_amount', $bill->tax_amount) }}" min="0" step="0.01"
                                        oninput="recalcSummary()">
                                </div>
                                <div class="full">
                                    <div class="form-label-sm">Discount Reason</div>
                                    <input type="text" name="discount_reason" class="form-control-clean"
                                        placeholder="e.g. Senior discount, Welfare..."
                                        value="{{ old('discount_reason', $bill->discount_reason) }}">
                                </div>
                            </div>

                            <div class="net-row">
                                <span class="net-label">
                                    <i class="bi bi-coin me-1"></i>Net Payable
                                </span>
                                <span class="net-value" id="summaryNet">Rs. 0</span>
                            </div>

                            <div class="action-grid">
                                <a href="{{ route('billing.show', $bill) }}" class="btn-cancel">
                                    <i class="bi bi-x-lg" style="font-size:13px"></i>Cancel
                                </a>
                                <button type="submit" class="btn-save">
                                    <i class="bi bi-save" style="font-size:14px"></i>Save Changes
                                </button>
                            </div>

                        </div>
                    </div>

                    {{-- Bill meta info tile --}}
                    <div class="summary-card">
                        <div class="summary-card-header">
                            <div class="summary-icon" style="background:#f0fdf4">
                                <i class="bi bi-info-circle" style="color:#16a34a;font-size:14px"></i>
                            </div>
                            <span style="font-size:13px;font-weight:600;color:#1e293b">Bill Details</span>
                        </div>
                        <div style="padding:14px 16px">
                            <div style="display:grid;gap:8px">
                                <div style="display:flex;justify-content:space-between;font-size:12px">
                                    <span style="color:#94a3b8">Bill Number</span>
                                    <span style="font-weight:600;color:#1e293b">{{ $bill->bill_number }}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;font-size:12px">
                                    <span style="color:#94a3b8">Created</span>
                                    <span
                                        style="font-weight:500;color:#374151">{{ $bill->created_at->format('d M Y') }}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;font-size:12px">
                                    <span style="color:#94a3b8">Status</span>
                                    <span class="status-pill {{ $statusClass }}">{{ ucfirst($bill->status) }}</span>
                                </div>
                                @if ($bill->paid_amount > 0)
                                    <div
                                        style="border-top:1px solid #f1f5f9;padding-top:8px;display:flex;justify-content:space-between;font-size:12px">
                                        <span style="color:#94a3b8">Paid Amount</span>
                                        <span style="font-weight:600;color:#16a34a">Rs.
                                            {{ number_format($bill->paid_amount, 0) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>{{-- end col-lg-4 --}}

        </div>
    </form>

    @push('scripts')
        <script>
            let idx = 0;
            const serviceTypes = @json(\App\Models\BillItem::serviceTypes());
            const existingItems = @json($bill->items);

            // ─── Utility ────────────────────────────────────────────────
            function formatCurrency(amount) {
                const num = parseFloat(amount) || 0;
                return 'Rs. ' + num.toLocaleString('en-PK', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }

            function escHtml(str) {
                return String(str ?? '')
                    .replace(/&/g, '&amp;').replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;').replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

            function formatDecimalInput(input) {
                let val = input.value.replace(/[^0-9.]/g, '');
                const parts = val.split('.');
                if (parts.length > 2) val = parts[0] + '.' + parts.slice(1).join('');
                input.value = val;
            }

            // ─── Scroll to Bill Items + highlight ───────────────────────
            function scrollToBillItems() {
                const card = document.getElementById('billItemsCard');
                if (!card) return;
                card.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                card.classList.remove('bill-items-highlight');
                void card.offsetWidth;
                card.classList.add('bill-items-highlight');
                setTimeout(() => card.classList.remove('bill-items-highlight'), 900);
            }

            // ─── Bill Items ──────────────────────────────────────────────
            function addItemRow(data = {}, isExisting = false) {
                const i = idx++;
                const qty = data.quantity ?? 1;
                const prc = data.unit_price ?? 0;
                const dsc = data.discount ?? 0;
                const rawTotal = (parseFloat(qty) * parseFloat(prc)) - parseFloat(dsc);
                const tot = Math.max(0, Math.round(rawTotal * 100) / 100);

                const typeOptions = serviceTypes.map(t =>
                    `<option value="${t}" ${data.service_type === t ? 'selected' : ''}>${t}</option>`
                ).join('');

                const rowClass = isExisting ? 'class="existing-item-row"' : '';

                const row = `
                <tr id="row-${i}" ${rowClass}>
                    <td>
                        <select name="items[${i}][service_type]" class="form-select-sm" required>
                            ${typeOptions}
                        </select>
                        <input type="hidden" name="items[${i}][reference_type]" value="${escHtml(data.reference_type ?? '')}">
                        <input type="hidden" name="items[${i}][reference_id]"   value="${escHtml(String(data.reference_id ?? ''))}">
                    </td>
                    <td>
                        <input type="text" name="items[${i}][description]"
                               class="form-control-sm"
                               value="${escHtml(data.description ?? '')}"
                               placeholder="Service description..." required>
                    </td>
                    <td>
                        <input type="number" name="items[${i}][quantity]"
                               class="qty form-control-sm"
                               value="${qty}" min="0" step="any"
                               onchange="recalcRow(${i})"
                               oninput="formatDecimalInput(this)">
                    </td>
                    <td>
                        <input type="number" name="items[${i}][unit_price]"
                               class="prc form-control-sm"
                               value="${prc}" min="0" step="0.01"
                               onchange="recalcRow(${i})">
                    </td>
                    <td>
                        <input type="number" name="items[${i}][discount]"
                               class="dsc form-control-sm"
                               value="${dsc}" min="0" step="0.01"
                               onchange="recalcRow(${i})">
                    </td>
                    <td style="text-align:right;font-size:13px;font-weight:600;color:#1e293b;padding-right:12px">
                        <span id="tot-${i}">${formatCurrency(tot)}</span>
                    </td>
                    <td style="text-align:center">
                        <button type="button" onclick="removeRow(${i})"
                                class="btn btn-link text-danger p-0" style="font-size:16px">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </td>
                </tr>`;

                document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);
                document.getElementById('emptyMsg').style.display = 'none';
                recalcSummary();
            }

            function removeRow(i) {
                const row = document.getElementById('row-' + i);
                if (row) row.remove();
                if (!document.getElementById('itemsBody').children.length) {
                    document.getElementById('emptyMsg').style.display = 'block';
                }
                recalcSummary();
            }

            function recalcRow(i) {
                const row = document.getElementById('row-' + i);
                if (!row) return;
                const qty = parseFloat(row.querySelector('.qty').value) || 0;
                const prc = parseFloat(row.querySelector('.prc').value) || 0;
                const dsc = parseFloat(row.querySelector('.dsc').value) || 0;
                const tot = Math.max(0, Math.round(((qty * prc) - dsc) * 100) / 100);
                document.getElementById('tot-' + i).textContent = formatCurrency(tot);
                recalcSummary();
            }

            function recalcSummary() {
                let sub = 0;
                document.querySelectorAll('[id^="tot-"]').forEach(el => {
                    sub += parseFloat(el.textContent.replace(/Rs\. |,/g, '').trim()) || 0;
                });
                const disc = parseFloat(document.getElementById('discountAmount')?.value) || 0;
                const tax = parseFloat(document.getElementById('taxAmount')?.value) || 0;
                const net = Math.max(0, Math.round((sub - disc + tax) * 100) / 100);
                const items = document.getElementById('itemsBody').children.length;

                document.getElementById('summarySubtotal').textContent = formatCurrency(sub);
                document.getElementById('summaryItems').textContent = items;
                document.getElementById('summaryDiscount').textContent = '− ' + formatCurrency(disc);
                document.getElementById('summaryTax').textContent = '+ ' + formatCurrency(tax);
                document.getElementById('summaryNet').textContent = formatCurrency(net);
            }

            function quickAdd(c) {
                addItemRow({
                    service_type: c.category === 'Consultation' ? 'Consultation' : 'Service',
                    description: c.name,
                    quantity: 1,
                    unit_price: parseFloat(c.default_price) || 0,
                    discount: 0,
                });
                scrollToBillItems();
            }

            // ─── Load existing items on page ready ───────────────────────
            document.addEventListener('DOMContentLoaded', () => {
                existingItems.forEach(item => addItemRow({
                    service_type: item.service_type,
                    description: item.description,
                    reference_type: item.reference_type,
                    reference_id: item.reference_id,
                    quantity: parseFloat(item.quantity),
                    unit_price: parseFloat(item.unit_price),
                    discount: parseFloat(item.discount),
                }, true)); // isExisting = true → light tint

                // discount / tax listeners
                const discInput = document.getElementById('discountAmount');
                const taxInput = document.getElementById('taxAmount');
                if (discInput) discInput.addEventListener('input', recalcSummary);
                if (taxInput) taxInput.addEventListener('input', recalcSummary);
            });
        </script>
    @endpush

@endsection
