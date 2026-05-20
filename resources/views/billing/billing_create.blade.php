@extends('layouts.master')

@section('title', 'Create New Bill')
@section('page-title', 'Billing — New Bill')
@section('breadcrumb', 'Home / Billing / New Bill')

@push('styles')
    <style>
        .card-section {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden
        }

        /* ─── FIX: Bill Info card ko overflow visible karo taake dropdown dikhay ─── */
        .card-section.bill-info-card {
            overflow: visible;
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
            outline: none;
            transition: border-color .2s
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

        .form-control-sm,
        .form-select-sm {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            padding: 6px 10px;
            color: #374151;
            background: #f8fafc;
            width: 100%;
            outline: none;
            transition: all 0.2s;
        }

        .form-control-sm:focus,
        .form-select-sm:focus {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* ── Bill Summary Card ── */
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

        .action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .btn-draft {
            background: #81d700;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 13px;
            font-weight: 500;
            color: #000000;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background .15s;
        }

        .btn-draft:hover {
            background: #f1f5f9;
        }

        .btn-finalize {
            background: #ffb32f;
            border: 1px solid #d97706;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 13px;
            font-weight: 600;
            color: #000000;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background .15s;
        }

        .btn-finalize:hover {
            background: #d97706;
        }

        /* ── Quick Add Services ── */
        .quick-services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
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

        .badge-consultation {
            background: #E1F5EE;
            color: #085041;
        }

        .badge-laboratory {
            background: #E6F1FB;
            color: #0C447C;
        }

        .badge-procedure {
            background: #FAEEDA;
            color: #633806;
        }

        .badge-radiology {
            background: #EEEDFE;
            color: #3C3489;
        }

        .badge-pharmacy {
            background: #FCEBEB;
            color: #791F1F;
        }

        .badge-default {
            background: #f1f5f9;
            color: #475569;
        }

        /* ── Load Pending Button Animation ── */
        @keyframes btnPulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.18);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(59, 130, 246, 0);
            }
        }

        @keyframes borderGlow {

            0%,
            100% {
                border-color: #e2e8f0;
            }

            50% {
                border-color: #93c5fd;
            }
        }

        @keyframes dotPing {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.4;
                transform: scale(0.7);
            }
        }

        .load-pending-btn {
            height: 38px;
            font-size: 13px;
            font-weight: 500;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
            color: #374151;
            width: 100%;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            position: relative;
            overflow: hidden;
            transition: background .2s, color .2s, border-color .2s, box-shadow .2s;
            animation: btnPulse 2.4s ease-in-out infinite, borderGlow 2.4s ease-in-out infinite;
        }

        .load-pending-btn:hover {
            animation: none;
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        }

        .load-pending-btn .dot {
            position: absolute;
            top: 7px;
            right: 9px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #3b82f6;
            animation: dotPing 2.4s ease-in-out infinite;
        }

        /* ── Patient Dropdown — HIGH z-index, fixed position ── */
        #patientDropdown {
            display: none;
            position: fixed;
            /* fixed taake kisi bhi overflow se na ruke */
            z-index: 9999;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            max-height: 280px;
            overflow-y: auto;
            min-width: 320px;
        }

        #patientDropdown .patient-row {
            padding: 10px 14px;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background .15s;
        }

        #patientDropdown .patient-row:last-child {
            border-bottom: none;
        }

        #patientDropdown .patient-row:hover {
            background: #f0f9ff;
        }

        #patientDropdown .patient-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #e0f2fe;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            color: #0369a1;
            flex-shrink: 0;
        }

        #patientDropdown .patient-name {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
        }

        #patientDropdown .patient-meta {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* ── Scroll highlight on Bill Items card ── */
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
            animation: highlightPulse 0.8s ease-out;
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

    <form action="{{ route('billing.store') }}" method="POST" id="billForm">
        @csrf
        <div class="row g-4">

            {{-- ─── LEFT (col-lg-8) ──────────────────────────────────────── --}}
            <div class="col-lg-8">

                {{-- Bill Info — overflow:visible class lagayi taake dropdown dikhay --}}
                <div class="card-section bill-info-card mb-4">
                    <div class="card-section-header">
                        <span style="font-size:14px;font-weight:600;color:#1e293b">
                            <i class="bi bi-person-badge me-2 text-primary"></i>Bill Information
                        </span>
                    </div>
                    <div class="p-4">
                        <div class="row g-3">

                            {{-- Patient Search --}}
                            <div class="col-md-4">
                                <div class="form-label-sm">Patient <span style="color:#dc2626">*</span></div>

                                {{-- Hidden input jo actual patient_id submit karega --}}
                                <input type="hidden" name="patient_id" id="patientId"
                                    value="{{ old('patient_id', $selectedPatient?->id) }}">

                                {{-- Search Input --}}
                                <div style="position:relative">
                                    <div style="position:relative">
                                        <i class="bi bi-search"
                                            style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:13px;pointer-events:none"></i>
                                        <input type="text" id="patientSearch" class="form-control-clean"
                                            style="padding-left:30px" placeholder="Search by name, MRN, or phone..."
                                            value="{{ $selectedPatient ? $selectedPatient->name . ' — ' . $selectedPatient->mrn : '' }}"
                                            autocomplete="off">
                                    </div>

                                    {{-- Dropdown — position fixed, JS se coordinates set honge --}}
                                    <div id="patientDropdown"></div>
                                </div>

                                {{-- Selected patient badge --}}
                                <div id="patientBadge"
                                    style="display:{{ $selectedPatient ? 'flex' : 'none' }};
                                           align-items:center;gap:6px;margin-top:6px;
                                           background:#eff6ff;border:1px solid #bfdbfe;
                                           border-radius:8px;padding:6px 10px;font-size:12px;color:#1e40af">
                                    <i class="bi bi-person-check-fill"></i>
                                    <span id="patientBadgeText">
                                        {{ $selectedPatient ? $selectedPatient->name . ' (' . $selectedPatient->patient_type . ')' : '' }}
                                    </span>
                                    <button type="button" onclick="clearPatient()"
                                        style="margin-left:auto;background:none;border:none;color:#93c5fd;cursor:pointer;padding:0;font-size:14px">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-label-sm">Bill Date <span style="color:#dc2626">*</span></div>
                                <input type="date" name="bill_date" class="form-control-clean"
                                    value="{{ old('bill_date', date('Y-m-d')) }}" required>
                            </div>

                            <div class="col-md-2">
                                <div class="form-label-sm">Bill Type <span style="color:#dc2626">*</span></div>
                                <select name="bill_type" class="form-select-clean" required>
                                    @foreach (['OPD', 'IPD', 'Emergency'] as $t)
                                        <option value="{{ $t }}"
                                            {{ old('bill_type', 'OPD') === $t ? 'selected' : '' }}>{{ $t }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" onclick="loadPendingServices()" class="load-pending-btn">
                                    <i class="bi bi-cloud-download text-primary"></i>
                                    Load Pending Services
                                    <span class="dot"></span>
                                </button>
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
                    <div id="emptyMsg" style="text-align:center;padding:30px;color:#94a3b8;font-size:13px">
                        <i class="bi bi-plus-circle" style="font-size:32px;display:block;margin-bottom:8px;opacity:.4"></i>
                        Click "Add Item" or "Load Pending Services" to begin
                    </div>
                </div>

                {{-- Notes --}}
                <div class="card-section mb-4">
                    <div class="p-4">
                        <div class="form-label-sm">Notes / Remarks</div>
                        <textarea name="notes" class="form-control-clean" rows="2" placeholder="Optional internal notes...">{{ old('notes') }}</textarea>
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
                        @php
                            $catKey = strtolower($category);
                            $badgeClass = match ($catKey) {
                                'consultation' => 'badge-consultation',
                                'laboratory', 'lab' => 'badge-laboratory',
                                'procedure', 'procedures' => 'badge-procedure',
                                'radiology' => 'badge-radiology',
                                'pharmacy' => 'badge-pharmacy',
                                default => 'badge-default',
                            };
                        @endphp

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

            {{-- ─── RIGHT (col-lg-4) ─────────────────────────────────────── --}}
            <div class="col-lg-4">
                <div style="position:sticky;top:80px">
                    <div class="summary-card">
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
                                        class="form-control-clean" value="{{ old('discount_amount', 0) }}"
                                        min="0" step="0.01" oninput="recalcSummary()">
                                </div>
                                <div>
                                    <div class="form-label-sm">Tax / Extra (Rs.)</div>
                                    <input type="number" name="tax_amount" id="taxAmount" class="form-control-clean"
                                        value="{{ old('tax_amount', 0) }}" min="0" step="0.01"
                                        oninput="recalcSummary()">
                                </div>
                                <div class="full">
                                    <div class="form-label-sm">Discount Reason</div>
                                    <input type="text" name="discount_reason" class="form-control-clean"
                                        placeholder="e.g. Senior discount, Welfare..."
                                        value="{{ old('discount_reason') }}">
                                </div>
                            </div>

                            <div class="net-row">
                                <span class="net-label">
                                    <i class="bi bi-coin me-1"></i>Net Payable
                                </span>
                                <span class="net-value" id="summaryNet">Rs. 0</span>
                            </div>

                            <div class="action-grid">
                                <button type="submit" class="btn-draft">
                                    <i class="bi bi-save" style="font-size:14px"></i>Save Draft
                                </button>
                                <button type="submit" class="btn-finalize"
                                    onclick="document.getElementById('submitAction').value='finalize'">
                                    <i class="bi bi-check-circle" style="font-size:14px"></i>Save & Finalize
                                </button>
                            </div>

                            <input type="hidden" name="submit_action" id="submitAction" value="draft">
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

            // ─── Utility ──────────────────────────────────────────────────
            function formatDecimalInput(input) {
                let val = input.value.replace(/[^0-9.]/g, '');
                const parts = val.split('.');
                if (parts.length > 2) val = parts[0] + '.' + parts.slice(1).join('');
                input.value = val;
            }

            function formatCurrency(amount) {
                const num = parseFloat(amount) || 0;
                return 'Rs. ' + num.toLocaleString('en-PK', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }

            function escHtml(str) {
                return String(str ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

            // ─── Scroll to Bill Items card + highlight ─────────────────────
            function scrollToBillItems() {
                const card = document.getElementById('billItemsCard');
                if (!card) return;
                card.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                // Highlight animation
                card.classList.remove('bill-items-highlight');
                void card.offsetWidth; // reflow to restart animation
                card.classList.add('bill-items-highlight');
                setTimeout(() => card.classList.remove('bill-items-highlight'), 900);
            }

            // ─── Bill Items ───────────────────────────────────────────────
            function addItemRow(data = {}) {
                const i = idx++;
                const qty = data.quantity ?? 1;
                const prc = data.unit_price ?? 0;
                const dsc = data.discount ?? 0;
                const rawTotal = (parseFloat(qty) * parseFloat(prc)) - parseFloat(dsc);
                const tot = Math.max(0, Math.round(rawTotal * 100) / 100);

                const typeOptions = serviceTypes.map(t =>
                    `<option value="${t}" ${data.service_type === t ? 'selected' : ''}>${t}</option>`
                ).join('');

                const row = `
                <tr id="row-${i}">
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
                    discount: 0
                });
                scrollToBillItems();
            }

            // ─── Load Pending Services ─────────────────────────────────────
            // ─── Loaded items track karne ke liye ─────────────────────────
            const loadedRefs = new Set();

            function loadPendingServices() {
                const pid = document.getElementById('patientId')?.value;
                if (!pid) {
                    alert('Please select a patient first.');
                    return;
                }

                const btn = document.querySelector('.load-pending-btn');

                // Already loaded check
                if (btn.dataset.loaded === pid) {
                    alert('Pending services already loaded for this patient.');
                    return;
                }

                // Button loading state
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Loading...';

                fetch(`/billing/patient/${pid}/pending-services`)
                    .then(r => r.json())
                    .then(list => {
                        if (!list || list.length === 0) {
                            alert('No pending unpaid services found for this patient.');
                            // Button reset
                            btn.disabled = false;
                            btn.innerHTML =
                                '<i class="bi bi-cloud-download me-1 text-primary"></i>Load Pending Services<span class="dot"></span>';
                            return;
                        }

                        let added = 0;
                        let skipped = 0;

                        list.forEach(s => {
                            // Duplicate check — reference_type + reference_id se
                            const refKey = `${s.reference_type}_${s.reference_id}`;

                            if (s.reference_type && s.reference_id && loadedRefs.has(refKey)) {
                                skipped++;
                                return; // skip duplicate
                            }

                            // Set mein add karo
                            if (s.reference_type && s.reference_id) {
                                loadedRefs.add(refKey);
                            }

                            addItemRow({
                                service_type: s.service_type || 'Service',
                                description: s.description || s.name || '',
                                reference_type: s.reference_type || '',
                                reference_id: s.reference_id || '',
                                quantity: parseFloat(s.quantity) || 1,
                                unit_price: parseFloat(s.unit_price) || 0,
                                discount: parseFloat(s.discount) || 0,
                            });
                            added++;
                        });

                        // Button — loaded state (dobara click nahi hoga)
                        btn.dataset.loaded = pid; // is patient ke liye loaded mark karo
                        btn.disabled = true;
                        btn.innerHTML = '<i class="bi bi-check-circle-fill me-1 text-success"></i>Services Loaded';
                        btn.style.background = '#f0fdf4';
                        btn.style.borderColor = '#86efac';
                        btn.style.color = '#15803d';
                        btn.style.animation = 'none';

                        if (skipped > 0) {
                            console.info(`${skipped} duplicate service(s) skipped.`);
                        }

                        scrollToBillItems();
                    })
                    .catch(err => {
                        console.error('Load error:', err);
                        alert('Could not load services. Please add items manually.');
                        // Error pe reset
                        btn.disabled = false;
                        btn.innerHTML =
                            '<i class="bi bi-cloud-download me-1 text-primary"></i>Load Pending Services<span class="dot"></span>';
                    });
            }

            // ─── Patient Live Search ───────────────────────────────────────
            let searchTimeout = null;
            const searchInput = document.getElementById('patientSearch');
            const dropdown = document.getElementById('patientDropdown');

            // Dropdown ko input ke bilkul neeche position karna — fixed coordinates
            function positionDropdown() {
                const rect = searchInput.getBoundingClientRect();
                dropdown.style.top = (rect.bottom + 4) + 'px';
                dropdown.style.left = rect.left + 'px';
                dropdown.style.width = rect.width + 'px';
            }

            searchInput.addEventListener('input', function() {
                const q = this.value.trim();

                if (document.getElementById('patientId').value) {
                    clearPatient(false);
                }

                clearTimeout(searchTimeout);

                if (q.length < 2) {
                    dropdown.style.display = 'none';
                    return;
                }

                positionDropdown();
                dropdown.style.display = 'block';
                dropdown.innerHTML = `
                    <div style="padding:14px;text-align:center;color:#94a3b8;font-size:13px">
                        <i class="bi bi-hourglass-split me-1"></i>Searching...
                    </div>`;

                searchTimeout = setTimeout(() => {
                    fetch(`/billing/ajax/patient-search?q=${encodeURIComponent(q)}`)
                        .then(r => r.json())
                        .then(patients => {
                            if (!patients.length) {
                                dropdown.innerHTML = `
                                    <div style="padding:14px;text-align:center;color:#94a3b8;font-size:13px">
                                        <i class="bi bi-person-x me-1"></i>No patient found
                                    </div>`;
                                return;
                            }

                            dropdown.innerHTML = patients.map(p => `
                                <div class="patient-row"
                                     onclick="selectPatient(${p.id}, '${escHtml(p.name)}', '${escHtml(p.mrn)}', '${escHtml(p.patient_type)}')">
                                    <div class="patient-avatar">
                                        ${escHtml(p.name.charAt(0).toUpperCase())}
                                    </div>
                                    <div style="min-width:0">
                                        <div class="patient-name">${escHtml(p.name)}</div>
                                        <div class="patient-meta">
                                            MRN: ${escHtml(p.mrn)} &nbsp;·&nbsp; ${escHtml(p.patient_type)}
                                            ${p.phone ? '&nbsp;·&nbsp; ' + escHtml(p.phone) : ''}
                                        </div>
                                    </div>
                                </div>
                            `).join('');
                        })
                        .catch(() => {
                            dropdown.innerHTML = `
                                <div style="padding:14px;text-align:center;color:#dc2626;font-size:13px">
                                    Search failed. Try again.
                                </div>`;
                        });
                }, 300);
            });

            // Scroll ya resize pe bhi dropdown reposition karo
            window.addEventListener('scroll', () => {
                if (dropdown.style.display !== 'none') positionDropdown();
            }, {
                passive: true
            });

            window.addEventListener('resize', () => {
                if (dropdown.style.display !== 'none') positionDropdown();
            });

            function selectPatient(id, name, mrn, type) {
                document.getElementById('patientId').value = id;
                searchInput.value = name + ' — ' + mrn;
                document.getElementById('patientBadgeText').textContent = name + ' (' + type + ')';
                document.getElementById('patientBadge').style.display = 'flex';
                dropdown.style.display = 'none';

                // ← Patient change hone par button reset karo
                resetLoadBtn();

                setTimeout(() => scrollToBillItems(), 150);
            }

            function clearPatient(clearInput = true) {
                document.getElementById('patientId').value = '';
                document.getElementById('patientBadge').style.display = 'none';
                if (clearInput) searchInput.value = '';

                // ← Patient clear hone par button reset karo
                resetLoadBtn();
            }

            // Click bahar — dropdown band karo
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#patientSearch') && !e.target.closest('#patientDropdown')) {
                    dropdown.style.display = 'none';
                }
            });

            // ─── Pre-loaded patients (from query string) ──────────────────
            @if ($selectedPatient && count($pendingServices))
                document.addEventListener('DOMContentLoaded', () => {
                    @foreach ($pendingServices as $s)
                        addItemRow(@json($s));
                    @endforeach
                });
            @endif

            document.addEventListener('DOMContentLoaded', () => {
                const discInput = document.getElementById('discountAmount');
                const taxInput = document.getElementById('taxAmount');
                if (discInput) discInput.addEventListener('input', recalcSummary);
                if (taxInput) taxInput.addEventListener('input', recalcSummary);
            });

            function resetLoadBtn() {
                const btn = document.querySelector('.load-pending-btn');
                if (!btn) return;
                btn.disabled = false;
                btn.dataset.loaded = ''; // loaded flag clear
                btn.innerHTML =
                    '<i class="bi bi-cloud-download me-1 text-primary"></i>Load Pending Services<span class="dot"></span>';
                btn.style.background = '';
                btn.style.borderColor = '';
                btn.style.color = '';
                btn.style.animation = '';
                loadedRefs.clear(); // track set bhi clear karo
            }
        </script>
    @endpush

@endsection
