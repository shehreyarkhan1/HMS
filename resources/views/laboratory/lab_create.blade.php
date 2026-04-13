@extends('layouts.master')

@section('title', 'New Lab Order')
@section('page-title', 'Laboratory — New Order')
@section('breadcrumb', 'Home / Laboratory / Orders / New')

@push('styles')
    <style>
        .form-section {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px
        }

        .form-section-header {
            padding: 12px 20px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .form-section-body {
            padding: 20px
        }

        .form-label-sm {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px
        }

        .form-control-sm-custom {
            height: 36px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            padding: 0 12px;
            color: #374151;
            background: #f8fafc;
            width: 100%
        }

        .form-control-sm-custom:focus {
            outline: none;
            border-color: #93c5fd;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(147, 197, 253, .15)
        }

        /* Test search */
        .test-search-box {
            position: relative
        }

        .test-dropdown {
            position: absolute;
            top: 38px;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
            z-index: 999;
            max-height: 260px;
            overflow-y: auto;
            display: none
        }

        .test-dropdown.show {
            display: block
        }

        .test-option {
            padding: 10px 14px;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center
        }

        .test-option:last-child {
            border-bottom: none
        }

        .test-option:hover {
            background: #f8fafc
        }

        .test-option-name {
            font-size: 13px;
            font-weight: 500;
            color: #1e293b
        }

        .test-option-meta {
            font-size: 11px;
            color: #94a3b8
        }

        .test-option-price {
            font-size: 13px;
            font-weight: 600;
            color: #16a34a
        }

        /* Selected tests table */
        .selected-tests-table th {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            padding: 8px 12px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0
        }

        .selected-tests-table td {
            font-size: 13px;
            padding: 10px 12px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9
        }

        .remove-test {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            padding: 2px 6px;
            border-radius: 4px
        }

        .remove-test:hover {
            background: #fee2e2
        }

        .total-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 16px 20px
        }
    </style>
@endpush

@section('content')

    <form method="POST" action="{{ route('lab.orders.store') }}" id="orderForm">
        @csrf

        <div class="row g-4">
            {{-- Left column --}}
            <div class="col-lg-8">

                {{-- Patient & Doctor --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <i class="bi bi-person-circle text-primary"></i>
                        Patient & Doctor Information
                    </div>
                    <div class="form-section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-sm">Patient <span class="text-danger">*</span></label>
                                <select name="patient_id" class="form-control-sm-custom" required>
                                    <option value="">Select patient...</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}"
                                            {{ (old('patient_id') == $patient->id || optional($selectedPatient)->id == $patient->id) ? 'selected' : '' }}>
                                            {{ $patient->name }} — {{ $patient->mrn }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id') <div class="text-danger" style="font-size:11px;margin-top:4px">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-sm">Referring Doctor <span class="text-danger">*</span></label>
                                <select name="doctor_id" class="form-control-sm-custom" required>
                                    <option value="">Select doctor...</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}"
                                            {{ (old('doctor_id') == $doctor->id || optional($selectedDoctor)->id == $doctor->id) ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctor_id') <div class="text-danger" style="font-size:11px;margin-top:4px">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-sm">Order Date <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="order_date"
                                    class="form-control-sm-custom"
                                    value="{{ old('order_date', now()->format('Y-m-d\TH:i')) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-sm">Priority <span class="text-danger">*</span></label>
                                <select name="priority" class="form-control-sm-custom" required>
                                    <option value="Routine" {{ old('priority','Routine') == 'Routine' ? 'selected' : '' }}>Routine</option>
                                    <option value="Urgent" {{ old('priority') == 'Urgent' ? 'selected' : '' }}>Urgent (2-4 hrs)</option>
                                    <option value="STAT" {{ old('priority') == 'STAT' ? 'selected' : '' }}>🚨 STAT (Immediate)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-sm">Notes</label>
                                <input type="text" name="notes" class="form-control-sm-custom"
                                    placeholder="Optional notes..." value="{{ old('notes') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Test Selection --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <i class="bi bi-flask text-primary"></i>
                        Select Tests
                    </div>
                    <div class="form-section-body">

                        {{-- Search --}}
                        <div class="test-search-box mb-3">
                            <input type="text" id="testSearch" class="form-control-sm-custom"
                                placeholder="Search test by name or code..." autocomplete="off">
                            <div class="test-dropdown" id="testDropdown">
                                @foreach($testsByCategory as $category => $tests)
                                    <div style="padding:6px 14px;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;background:#f8fafc;letter-spacing:.06em">
                                        {{ $category }}
                                    </div>
                                    @foreach($tests as $test)
                                        <div class="test-option" data-id="{{ $test->id }}"
                                            data-name="{{ $test->name }}"
                                            data-code="{{ $test->test_code }}"
                                            data-price="{{ $test->price }}"
                                            data-unit="{{ $test->unit }}"
                                            data-sample="{{ $test->sampleType?->name ?? '' }}"
                                            data-fasting="{{ $test->requires_fasting ? '1' : '0' }}"
                                            data-turnaround="{{ $test->turnaround_label }}">
                                            <div>
                                                <div class="test-option-name">{{ $test->name }}</div>
                                                <div class="test-option-meta">
                                                    {{ $test->test_code }}
                                                    @if($test->sampleType) · {{ $test->sampleType->name }} @endif
                                                    @if($test->requires_fasting) · <span style="color:#d97706">Fasting</span> @endif
                                                </div>
                                            </div>
                                            <div class="test-option-price">Rs {{ number_format($test->price, 0) }}</div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>

                        {{-- Selected Tests Table --}}
                        <div id="noTestsMsg" style="text-align:center;padding:30px;color:#94a3b8;font-size:13px">
                            <i class="bi bi-flask" style="font-size:32px;display:block;margin-bottom:6px"></i>
                            No tests added yet. Search above to add tests.
                        </div>

                        <div id="testsTableWrapper" style="display:none">
                            <table class="table selected-tests-table mb-0" style="border:1px solid #e2e8f0;border-radius:8px;overflow:hidden">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Test</th>
                                        <th>Sample</th>
                                        <th style="text-align:right">Price (Rs)</th>
                                        <th style="text-align:right">Discount</th>
                                        <th style="text-align:right">Final</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="testsTableBody">
                                    {{-- rows injected by JS --}}
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Right column --}}
            <div class="col-lg-4">

                {{-- Order Summary --}}
                <div class="form-section" style="position:sticky;top:20px">
                    <div class="form-section-header">
                        <i class="bi bi-receipt text-primary"></i>
                        Order Summary
                    </div>
                    <div class="form-section-body">

                        <div class="total-box mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span style="font-size:13px;color:#64748b">Subtotal</span>
                                <span style="font-size:13px;font-weight:600" id="summarySubtotal">Rs 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span style="font-size:13px;color:#64748b">Item Discounts</span>
                                <span style="font-size:13px;color:#dc2626" id="summaryItemDiscount">−Rs 0</span>
                            </div>
                            <hr style="margin:8px 0;border-color:#bbf7d0">
                            <div class="d-flex justify-content-between">
                                <span style="font-size:14px;font-weight:700;color:#1e293b">Total</span>
                                <span style="font-size:16px;font-weight:700;color:#16a34a" id="summaryTotal">Rs 0</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-sm">Order Discount (Rs)</label>
                            <input type="number" name="discount" id="orderDiscount"
                                class="form-control-sm-custom" value="0" min="0" step="1">
                        </div>

                        <div class="mb-4">
                            <label class="form-label-sm">Paid Amount (Rs)</label>
                            <input type="number" name="paid_amount" id="paidAmount"
                                class="form-control-sm-custom" value="0" min="0" step="1">
                        </div>

                        {{-- Fasting warning --}}
                        <div id="fastingWarning" class="alert mb-3" style="display:none;background:#fefce8;border:1px solid #fde047;border-radius:8px;font-size:12px;color:#854d0e;padding:10px 14px">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Fasting required</strong> for one or more selected tests.
                        </div>

                        <button type="submit" class="btn btn-success w-100" style="height:40px;font-size:14px;font-weight:600">
                            <i class="bi bi-plus-circle me-2"></i>Create Lab Order
                        </button>
                        <a href="{{ route('lab.orders.index') }}"
                            class="btn btn-outline-secondary w-100 mt-2" style="height:38px;font-size:13px">
                            Cancel
                        </a>

                    </div>
                </div>

            </div>
        </div>

        {{-- Hidden inputs container --}}
        <div id="hiddenInputs"></div>

    </form>

@endsection

@push('scripts')
<script>
    const selectedTests = {};

    // ── Search ──
    const searchInput = document.getElementById('testSearch');
    const dropdown    = document.getElementById('testDropdown');
    const allOptions  = [...document.querySelectorAll('.test-option')];

    searchInput.addEventListener('focus', () => dropdown.classList.add('show'));
    searchInput.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        allOptions.forEach(opt => {
            const match = opt.dataset.name.toLowerCase().includes(q) ||
                          opt.dataset.code.toLowerCase().includes(q);
            opt.style.display = match ? '' : 'none';
        });
        // Show/hide category headers
        document.querySelectorAll('.test-dropdown > div[style*="background:#f8fafc"]').forEach(header => {
            const nextSibs = [];
            let el = header.nextElementSibling;
            while (el && !el.style.background?.includes('#f8fafc')) {
                if (el.classList.contains('test-option')) nextSibs.push(el);
                el = el.nextElementSibling;
            }
            header.style.display = nextSibs.some(o => o.style.display !== 'none') ? '' : 'none';
        });
        dropdown.classList.add('show');
    });

    document.addEventListener('click', e => {
        if (!e.target.closest('.test-search-box')) dropdown.classList.remove('show');
    });

    // ── Add test ──
    allOptions.forEach(opt => {
        opt.addEventListener('click', () => {
            const id = opt.dataset.id;
            if (selectedTests[id]) return; // already added
            selectedTests[id] = {
                id:        id,
                name:      opt.dataset.name,
                code:      opt.dataset.code,
                price:     parseFloat(opt.dataset.price),
                discount:  0,
                sample:    opt.dataset.sample,
                fasting:   opt.dataset.fasting === '1',
                turnaround:opt.dataset.turnaround,
            };
            renderTable();
            searchInput.value = '';
            dropdown.classList.remove('show');
        });
    });

    // ── Remove test ──
    document.getElementById('testsTableBody').addEventListener('click', e => {
        const btn = e.target.closest('.remove-test');
        if (!btn) return;
        delete selectedTests[btn.dataset.id];
        renderTable();
    });

    // ── Discount input ──
    document.getElementById('testsTableBody').addEventListener('input', e => {
        if (e.target.classList.contains('disc-input')) {
            const id = e.target.dataset.id;
            selectedTests[id].discount = parseFloat(e.target.value) || 0;
            updateTotals();
            updateHiddenInputs();
        }
    });

    // ── Render ──
    function renderTable() {
        const tbody   = document.getElementById('testsTableBody');
        const noMsg   = document.getElementById('noTestsMsg');
        const wrapper = document.getElementById('testsTableWrapper');
        const keys    = Object.keys(selectedTests);

        noMsg.style.display   = keys.length ? 'none' : 'block';
        wrapper.style.display = keys.length ? 'block' : 'none';

        tbody.innerHTML = keys.map((id, i) => {
            const t     = selectedTests[id];
            const final = Math.max(0, t.price - t.discount);
            return `
            <tr>
                <td style="color:#94a3b8">${i + 1}</td>
                <td>
                    <div style="font-weight:500;color:#1e293b">${t.name}</div>
                    <div style="font-size:11px;color:#94a3b8">${t.code}${t.fasting ? ' · <span style="color:#d97706">Fasting</span>' : ''}</div>
                </td>
                <td style="font-size:12px;color:#64748b">${t.sample || '—'}</td>
                <td style="text-align:right;font-weight:500">Rs ${t.price.toLocaleString()}</td>
                <td style="text-align:right">
                    <input type="number" class="disc-input" data-id="${id}"
                        value="${t.discount}" min="0" max="${t.price}" step="1"
                        style="width:80px;height:28px;border:1px solid #e2e8f0;border-radius:6px;font-size:12px;padding:0 8px;text-align:right">
                </td>
                <td style="text-align:right;font-weight:600;color:#16a34a">Rs ${final.toLocaleString()}</td>
                <td>
                    <button type="button" class="remove-test" data-id="${id}" title="Remove">
                        <i class="bi bi-x-lg" style="font-size:12px"></i>
                    </button>
                </td>
            </tr>`;
        }).join('');

        updateTotals();
        updateHiddenInputs();
        updateFastingWarning();
    }

    function updateTotals() {
        let subtotal = 0, itemDisc = 0;
        Object.values(selectedTests).forEach(t => {
            subtotal += t.price;
            itemDisc += t.discount;
        });
        const net = Math.max(0, subtotal - itemDisc);
        document.getElementById('summarySubtotal').textContent    = 'Rs ' + subtotal.toLocaleString();
        document.getElementById('summaryItemDiscount').textContent = '−Rs ' + itemDisc.toLocaleString();
        document.getElementById('summaryTotal').textContent        = 'Rs ' + net.toLocaleString();
    }

    function updateHiddenInputs() {
        const container = document.getElementById('hiddenInputs');
        container.innerHTML = Object.values(selectedTests).map((t, i) => `
            <input type="hidden" name="tests[${i}][id]"       value="${t.id}">
            <input type="hidden" name="tests[${i}][price]"    value="${t.price}">
            <input type="hidden" name="tests[${i}][discount]" value="${t.discount}">
        `).join('');
    }

    function updateFastingWarning() {
        const hasFasting = Object.values(selectedTests).some(t => t.fasting);
        document.getElementById('fastingWarning').style.display = hasFasting ? 'block' : 'none';
    }

    // ── Validate at submit ──
    document.getElementById('orderForm').addEventListener('submit', function (e) {
        if (Object.keys(selectedTests).length === 0) {
            e.preventDefault();
            alert('Please add at least one test.');
            document.getElementById('testSearch').focus();
        }
    });
</script>
@endpush