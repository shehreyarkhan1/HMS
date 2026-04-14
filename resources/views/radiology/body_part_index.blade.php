@extends('layouts.master')

@section('title', 'Body Parts')
@section('page-title', 'Radiology — Body Parts')
@section('breadcrumb', 'Home / Radiology / Body Parts')

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
            gap: 10px
        }

        .table-clean th {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 10px 16px !important;
            background: #f8fafc
        }

        .table-clean td {
            font-size: 13px;
            color: #374151;
            padding: 11px 16px !important;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9 !important
        }

        .table-clean tr:last-child td {
            border-bottom: none !important
        }

        .table-clean tr:hover td {
            background: #fafafa
        }

        .badge-active {
            background: #dcfce7;
            color: #166534;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .badge-inactive {
            background: #f1f5f9;
            color: #64748b;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .region-badge {
            background: #ede9fe;
            color: #5b21b6;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        /* ── Custom Modal ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .45);
            z-index: 1050;
            align-items: center;
            justify-content: center;
            padding: 16px
        }

        .modal-overlay.show {
            display: flex
        }

        .modal-box {
            background: #fff;
            border-radius: 14px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .18);
            animation: modalIn .18s ease
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: translateY(-12px) scale(.97)
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1)
            }
        }

        .modal-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .modal-header h6 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
            color: #1e293b
        }

        .modal-close {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            border: none;
            background: #f1f5f9;
            color: #64748b;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .15s
        }

        .modal-close:hover {
            background: #e2e8f0
        }

        .modal-body {
            padding: 20px
        }

        .modal-footer {
            padding: 14px 20px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 8px
        }

        .field-label {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px
        }

        .toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px
        }

        .toggle-row label {
            font-size: 13px;
            color: #374151;
            font-weight: 500;
            margin: 0;
            cursor: pointer
        }
    </style>
@endpush

@section('content')

    {{-- Flash messages --}}
    @foreach(['success', 'error'] as $msg)
        @if(session($msg))
            <div class="alert alert-{{ $msg === 'error' ? 'danger' : 'success' }} d-flex align-items-center gap-2 mb-4"
                style="border-radius:10px;font-size:13px">
                <i class="bi bi-{{ $msg === 'error' ? 'x-circle' : 'check-circle' }}-fill"></i>
                {{ session($msg) }}
            </div>
        @endif
    @endforeach

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="alert alert-danger d-flex align-items-start gap-2 mb-4" style="border-radius:10px;font-size:13px">
            <i class="bi bi-x-circle-fill mt-1"></i>
            <ul class="mb-0 ps-2">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- ══ Table Card ══ --}}
    <div class="card-section">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-person-bounding-box me-2 text-primary"></i>Body Parts
            </span>
            <button type="button" onclick="openAddModal()" class="btn btn-sm btn-success px-3"
                style="height:36px;font-size:13px;display:flex;align-items:center">
                <i class="bi bi-plus-lg me-1"></i>Add Body Part
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-clean mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Region</th>
                        <th>Status</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bodyParts as $bp)
                        <tr>
                            <td style="font-weight:500;color:#1e293b">{{ $bp->name }}</td>
                            <td><code style="font-size:12px;color:#6366f1">{{ $bp->code }}</code></td>
                            <td>
                                @if($bp->region)
                                    <span class="region-badge">{{ $bp->region }}</span>
                                @else
                                    <span style="color:#94a3b8">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="{{ $bp->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $bp->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">

                                    {{-- Edit → modal --}}
                                    <button type="button"
                                        onclick='openEditModal(@json($bp->id), @json($bp->name), @json($bp->code), @json($bp->region ?? ""), @json($bp->is_active))'
                                        class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    {{-- Toggle --}}
                                    <form method="POST" action="{{ route('radiology.body-parts.toggle', $bp->id) }}"
                                        class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:12px"
                                            title="{{ $bp->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi bi-{{ $bp->is_active ? 'toggle-on text-success' : 'toggle-off' }}"></i>
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('radiology.body-parts.destroy', $bp->id) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('Delete {{ str_replace("'", "\\'", $bp->name) }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:12px"
                                            title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5" style="color:#94a3b8">
                                <i class="bi bi-person-bounding-box"
                                    style="font-size:40px;display:block;margin-bottom:8px;opacity:.3"></i>
                                No body parts found.
                                <button onclick="openAddModal()" class="btn btn-link btn-sm p-0 ms-1 text-primary">
                                    Add first
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bodyParts->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid #e2e8f0">
                <span style="font-size:12px;color:#94a3b8">
                    Showing {{ $bodyParts->firstItem() }}–{{ $bodyParts->lastItem() }}
                    of {{ $bodyParts->total() }}
                </span>
                {{ $bodyParts->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>


    {{-- ══════════════════════════════════════
    ADD Modal
    ════════════════════════════════════════ --}}
    <div class="modal-overlay" id="addModal" onclick="closeOnBackdrop(event,'addModal')">
        <div class="modal-box">
            <div class="modal-header">
                <h6><i class="bi bi-plus-circle me-2 text-success"></i>Add Body Part</h6>
                <button class="modal-close" onclick="closeModal('addModal')">&times;</button>
            </div>
            <form method="POST" action="{{ route('radiology.body-parts.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-7">
                            <div class="field-label">Name *</div>
                            <input type="text" name="name" class="form-control form-control-sm"
                                placeholder="e.g. Chest, Brain, Abdomen" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-5">
                            <div class="field-label">Code *</div>
                            <input type="text" name="code" class="form-control form-control-sm" placeholder="BP-CHEST"
                                value="{{ old('code') }}" style="text-transform:uppercase"
                                oninput="this.value=this.value.toUpperCase()" required>
                        </div>
                        <div class="col-12">
                            <div class="field-label">Region</div>
                            <select name="region" class="form-select form-select-sm">
                                <option value="">— Select region —</option>
                                @foreach($regions as $r)
                                    <option value="{{ $r }}" {{ old('region') === $r ? 'selected' : '' }}>
                                        {{ $r }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="toggle-row">
                                <label for="add_is_active">Active</label>
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="add_is_active" value="1"
                                    class="form-check-input" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('addModal')"
                        class="btn btn-sm btn-outline-secondary px-3">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success px-4">
                        <i class="bi bi-check-lg me-1"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ══════════════════════════════════════
    EDIT Modal
    ════════════════════════════════════════ --}}
    <div class="modal-overlay" id="editModal" onclick="closeOnBackdrop(event,'editModal')">
        <div class="modal-box">
            <div class="modal-header">
                <h6><i class="bi bi-pencil me-2 text-primary"></i>Edit Body Part</h6>
                <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
            </div>
            <form method="POST" id="editForm" action="">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-7">
                            <div class="field-label">Name *</div>
                            <input type="text" name="name" id="edit_name" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-5">
                            <div class="field-label">Code *</div>
                            <input type="text" name="code" id="edit_code" class="form-control form-control-sm"
                                style="text-transform:uppercase" oninput="this.value=this.value.toUpperCase()" required>
                        </div>
                        <div class="col-12">
                            <div class="field-label">Region</div>
                            <select name="region" id="edit_region" class="form-select form-select-sm">
                                <option value="">— Select region —</option>
                                @foreach($regions as $r)
                                    <option value="{{ $r }}">{{ $r }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="toggle-row">
                                <label for="edit_is_active">Active</label>
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                                    class="form-check-input">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('editModal')"
                        class="btn btn-sm btn-outline-secondary px-3">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const baseUrl = "{{ rtrim(route('radiology.body-parts.index'), '/') }}";

        function openModal(id) {
            document.getElementById(id).classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('show');
            document.body.style.overflow = '';
        }
        function closeOnBackdrop(e, id) {
            if (e.target === document.getElementById(id)) closeModal(id);
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeModal('addModal');
                closeModal('editModal');
            }
        });

        function openAddModal() {
            openModal('addModal');
            setTimeout(() => document.querySelector('#addModal input[name="name"]').focus(), 180);
        }

        function openEditModal(id, name, code, region, isActive) {
            document.getElementById('editForm').action = baseUrl + '/' + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_code').value = code;
            document.getElementById('edit_is_active').checked = isActive;

            const sel = document.getElementById('edit_region');
            for (let o of sel.options) {
                o.selected = (o.value === region);
            }

            openModal('editModal');
            setTimeout(() => document.getElementById('edit_name').focus(), 180);
        }

        // { { --If validation fails and it was an add operation, reopen modal-- } }
        @if($errors->any() && !old('_method'))
            document.addEventListener('DOMContentLoaded', () => openAddModal());
        @endif
    </script>
@endpush