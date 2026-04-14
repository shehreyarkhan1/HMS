@extends('layouts.master')

@section('title', 'New Radiology Order')
@section('page-title', 'Radiology — New Order')
@section('breadcrumb', 'Home / Radiology / Orders / New')

@push('styles')
<style>
    .form-card {
        background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:20px
    }
    .form-card-header {
        padding:14px 20px;border-bottom:1px solid #e2e8f0;
        font-size:14px;font-weight:600;color:#1e293b;
        display:flex;align-items:center;gap:8px
    }
    .form-card-body { padding:20px }
    .field-label { font-size:12px;font-weight:600;color:#374151;margin-bottom:5px }

    .exam-catalog {
        border:1px solid #e2e8f0;border-radius:10px;overflow:hidden
    }
    .modality-group-header {
        background:#f8fafc;padding:10px 16px;
        font-size:12px;font-weight:700;color:#475569;
        text-transform:uppercase;letter-spacing:.05em;
        border-bottom:1px solid #e2e8f0;
        cursor:pointer;
        display:flex;align-items:center;justify-content:space-between
    }
    .exam-row {
        display:flex;align-items:center;padding:10px 16px;
        border-bottom:1px solid #f1f5f9;gap:12px;
        transition:background .1s
    }
    .exam-row:last-child { border-bottom:none }
    .exam-row:hover { background:#fafafa }
    .exam-row.selected { background:#eff6ff }
    .exam-name { font-size:13px;color:#1e293b;flex:1;font-weight:500 }
    .exam-meta { font-size:11px;color:#94a3b8 }
    .exam-price { font-size:13px;font-weight:600;color:#374151;min-width:80px;text-align:right }

    .selected-exams-box {
        background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;
        min-height:80px;padding:12px
    }
    .selected-exam-tag {
        display:inline-flex;align-items:center;gap:6px;
        background:#fff;border:1px solid #e2e8f0;border-radius:8px;
        padding:6px 10px;margin:4px;font-size:12px
    }
    .total-bar {
        background:#fff;border:1px solid #e2e8f0;border-radius:10px;
        padding:14px 20px;margin-top:16px;
        display:flex;justify-content:space-between;align-items:center
    }
</style>
@endpush

@section('content')

<form method="POST" action="{{ route('radiology.orders.store') }}" id="orderForm">
@csrf

<div class="row g-4">
    {{-- LEFT COLUMN --}}
    <div class="col-lg-8">

        {{-- Patient & Doctor --}}
        <div class="form-card">
            <div class="form-card-header">
                <i class="bi bi-person-circle text-primary"></i> Patient & Doctor
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="field-label">Patient *</div>
                        <select name="patient_id" class="form-select form-select-sm" required>
                            <option value="">— Select patient —</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}"
                                    {{ optional($selectedPatient)->id == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} ({{ $p->mrn }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="field-label">Referring Doctor *</div>
                        <select name="doctor_id" class="form-select form-select-sm" required>
                            <option value="">— Select doctor —</option>
                            @foreach($doctors as $d)
                                <option value="{{ $d->id }}"
                                    {{ optional($selectedDoctor)->id == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Order Date *</div>
                        <input type="datetime-local" name="order_date"
                            class="form-control form-control-sm"
                            value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Priority *</div>
                        <select name="priority" class="form-select form-select-sm" required>
                            <option value="Routine">Routine</option>
                            <option value="Urgent">Urgent</option>
                            <option value="STAT">STAT — Immediate</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Schedule Scan</div>
                        <input type="datetime-local" name="scheduled_at" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6">
                        <div class="field-label">Clinical History</div>
                        <textarea name="clinical_history" class="form-control form-control-sm" rows="2"
                            placeholder="Patient symptoms, prior diagnoses..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <div class="field-label">Clinical Indication</div>
                        <textarea name="clinical_indication" class="form-control form-control-sm" rows="2"
                            placeholder="Reason for requesting this study..."></textarea>
                    </div>
                    <div class="col-12">
                        <div class="field-label">Notes</div>
                        <textarea name="notes" class="form-control form-control-sm" rows="1"
                            placeholder="Additional notes..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Exam Catalog --}}
        <div class="form-card">
            <div class="form-card-header">
                <i class="bi bi-grid-3x3-gap text-primary"></i> Select Exams
                <span style="font-size:11px;color:#94a3b8;font-weight:400">Click to add / remove</span>
            </div>
            <div class="form-card-body">
                <div class="mb-3">
                    <input type="text" id="examSearch" class="form-control form-control-sm"
                        placeholder="Search exam name..."
                        onkeyup="filterExams(this.value)">
                </div>

                <div class="exam-catalog">
                    @foreach($examsByModality as $modalityId => $group)
                        <div class="modality-group" data-modality="{{ $modalityId }}">
                            <div class="modality-group-header"
                                onclick="toggleGroup({{ $modalityId }})">
                                <span>
                                    <i class="bi bi-camera me-2"></i>
                                    {{ $group['modality']->name }}
                                    <span style="font-weight:400;color:#94a3b8">
                                        ({{ $group['exams']->count() }} exams)
                                    </span>
                                </span>
                                <i class="bi bi-chevron-down" id="chevron-{{ $modalityId }}"></i>
                            </div>
                            <div id="group-body-{{ $modalityId }}">
                                @foreach($group['exams'] as $exam)
                                    <div class="exam-row" id="exam-row-{{ $exam->id }}"
                                        onclick="toggleExam({{ $exam->id }}, '{{ addslashes($exam->name) }}', {{ $exam->price }}, {{ $exam->requires_contrast ? 'true' : 'false' }})">
                                        <div class="flex-grow-1">
                                            <div class="exam-name">{{ $exam->name }}</div>
                                            <div class="exam-meta">
                                                {{ $exam->exam_code }}
                                                @if($exam->bodyPart) · {{ $exam->bodyPart->name }} @endif
                                                @if($exam->requires_contrast)
                                                    · <span style="color:#d97706">Contrast</span>
                                                @endif
                                                @if($exam->requires_preparation)
                                                    · <span style="color:#7c3aed">Prep required</span>
                                                @endif
                                                · {{ $exam->turnaround_hours }}h TAT
                                            </div>
                                        </div>
                                        <div class="exam-price">Rs {{ number_format($exam->price, 0) }}</div>
                                        <div id="check-{{ $exam->id }}" style="color:#22c55e;display:none">
                                            <i class="bi bi-check-circle-fill fs-5"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT COLUMN —  selected + billing --}}
    <div class="col-lg-4">
        <div class="form-card" style="position:sticky;top:20px">
            <div class="form-card-header">
                <i class="bi bi-cart-check text-success"></i> Selected Exams
            </div>
            <div class="form-card-body">
                <div class="selected-exams-box" id="selectedBox">
                    <div id="emptyMsg" style="font-size:12px;color:#94a3b8;text-align:center;padding:20px 0">
                        No exams selected yet. Click from the catalog.
                    </div>
                </div>

                {{-- hidden inputs container --}}
                <div id="hiddenInputs"></div>

                <div class="total-bar mt-3">
                    <div>
                        <div style="font-size:11px;color:#94a3b8">Sub-total</div>
                        <div style="font-size:18px;font-weight:700;color:#1e293b" id="subtotalDisplay">Rs 0</div>
                    </div>
                </div>

                <div class="row g-2 mt-1">
                    <div class="col-6">
                        <div class="field-label">Discount (Rs)</div>
                        <input type="number" name="discount" id="discountInput"
                            class="form-control form-control-sm" min="0" step="1"
                            value="0" oninput="updateNetDisplay()">
                    </div>
                    <div class="col-6">
                        <div class="field-label">Paid (Rs)</div>
                        <input type="number" name="paid_amount"
                            class="form-control form-control-sm" min="0" step="1" value="0">
                    </div>
                    <div class="col-12">
                        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:10px 14px;display:flex;justify-content:space-between;align-items:center">
                            <span style="font-size:12px;color:#166534;font-weight:600">Net Amount</span>
                            <span style="font-size:16px;font-weight:700;color:#166534" id="netDisplay">Rs 0</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-3" id="submitBtn" disabled>
                    <i class="bi bi-check-lg me-2"></i>Create Radiology Order
                </button>
            </div>
        </div>
    </div>

</div>
</form>

@endsection

@push('scripts')
<script>
    let selected = {};
    let subtotal = 0;

    function toggleExam(id, name, price, requiresContrast) {
        if (selected[id]) {
            removeExam(id);
        } else {
            addExam(id, name, price, requiresContrast);
        }
    }

    function addExam(id, name, price, requiresContrast) {
        selected[id] = { name, price, discount: 0 };
        document.getElementById('emptyMsg')?.remove();

        const tag = document.createElement('div');
        tag.className = 'selected-exam-tag';
        tag.id = 'tag-' + id;
        tag.innerHTML = `
            <span>${name}</span>
            <span style="color:#6366f1;font-weight:600">Rs ${price.toLocaleString()}</span>
            ${requiresContrast ? '<span style="background:#fef3c7;color:#92400e;font-size:10px;padding:1px 6px;border-radius:10px">Contrast</span>' : ''}
            <span onclick="removeExam(${id})" style="cursor:pointer;color:#ef4444;font-size:14px">&times;</span>
        `;
        document.getElementById('selectedBox').appendChild(tag);

        // Hidden inputs
        const wrap = document.getElementById('hiddenInputs');
        wrap.insertAdjacentHTML('beforeend', `
            <input type="hidden" name="exams[${id}][id]" value="${id}" id="hi-id-${id}">
            <input type="hidden" name="exams[${id}][price]" value="${price}" id="hi-price-${id}">
            <input type="hidden" name="exams[${id}][discount]" value="0" id="hi-disc-${id}">
        `);

        document.getElementById('exam-row-' + id).classList.add('selected');
        document.getElementById('check-' + id).style.display = '';
        recalc();
    }

    function removeExam(id) {
        delete selected[id];
        document.getElementById('tag-' + id)?.remove();
        ['hi-id-', 'hi-price-', 'hi-disc-'].forEach(p => document.getElementById(p + id)?.remove());
        document.getElementById('exam-row-' + id).classList.remove('selected');
        document.getElementById('check-' + id).style.display = 'none';
        if (Object.keys(selected).length === 0) {
            document.getElementById('selectedBox').insertAdjacentHTML('beforeend',
                '<div id="emptyMsg" style="font-size:12px;color:#94a3b8;text-align:center;padding:20px 0">No exams selected yet.</div>');
        }
        recalc();
    }

    function recalc() {
        subtotal = Object.values(selected).reduce((sum, e) => sum + e.price, 0);
        document.getElementById('subtotalDisplay').textContent = 'Rs ' + subtotal.toLocaleString();
        updateNetDisplay();
        document.getElementById('submitBtn').disabled = Object.keys(selected).length === 0;
    }

    function updateNetDisplay() {
        const disc = parseInt(document.getElementById('discountInput').value) || 0;
        const net = Math.max(0, subtotal - disc);
        document.getElementById('netDisplay').textContent = 'Rs ' + net.toLocaleString();
    }

    function filterExams(term) {
        document.querySelectorAll('.exam-row').forEach(row => {
            const name = row.querySelector('.exam-name').textContent.toLowerCase();
            row.style.display = name.includes(term.toLowerCase()) ? '' : 'none';
        });
    }

    function toggleGroup(id) {
        const body    = document.getElementById('group-body-' + id);
        const chevron = document.getElementById('chevron-' + id);
        const isHidden = body.style.display === 'none';
        body.style.display    = isHidden ? '' : 'none';
        chevron.className     = isHidden ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
    }
</script>
@endpush