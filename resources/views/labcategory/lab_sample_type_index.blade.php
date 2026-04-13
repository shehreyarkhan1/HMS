@extends('layouts.master')

@section('title', 'Sample Types')
@section('page-title', 'Laboratory — Sample Types')
@section('breadcrumb', 'Home / Laboratory / Settings / Sample Types')

@push('styles')
    <style>
        .stat-pill { background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:14px 20px }
        .stat-pill-label { font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em }
        .stat-pill-value { font-size:22px;font-weight:700;color:#1e293b }
        .card-section { background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden }
        .card-section-header { padding:14px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;gap:10px }
        .table-clean th { font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0!important;padding:10px 16px!important;background:#f8fafc }
        .table-clean td { font-size:13px;color:#374151;padding:11px 16px!important;vertical-align:middle;border-bottom:1px solid #f1f5f9!important }
        .table-clean tr:last-child td { border-bottom:none!important }
        .table-clean tr:hover td { background:#fafafa }

        .add-form-row td { background:#f0fdf4!important;border-bottom:1px solid #bbf7d0!important }
        .add-input { height:34px;border:1px solid #e2e8f0;border-radius:7px;font-size:13px;padding:0 10px;background:#fff;width:100% }
        .add-input:focus { outline:none;border-color:#93c5fd;box-shadow:0 0 0 3px rgba(147,197,253,.15) }

        /* Color dot */
        .color-dot { width:12px;height:12px;border-radius:50%;border:1px solid rgba(0,0,0,.12);display:inline-block;margin-right:5px;vertical-align:middle }

        /* Modal */
        .modal-content { border-radius:12px;border:1px solid #e2e8f0 }
        .modal-header { border-bottom:1px solid #e2e8f0;padding:14px 20px }
        .modal-footer { border-top:1px solid #e2e8f0;padding:12px 20px }
        .edit-input { height:36px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;padding:0 12px;background:#f8fafc;width:100% }
        .edit-input:focus { outline:none;border-color:#93c5fd;background:#fff;box-shadow:0 0 0 3px rgba(147,197,253,.15) }
        textarea.edit-input { height:70px;padding:8px 12px }
        .edit-label { font-size:12px;font-weight:600;color:#374151;margin-bottom:4px;display:block }

        /* Color map for tubes */
        .tube-Purple, .tube-Lavender { background:#a78bfa }
        .tube-Red     { background:#f87171 }
        .tube-Blue, .tube-Light-Blue { background:#60a5fa }
        .tube-Green   { background:#4ade80 }
        .tube-Yellow  { background:#facc15 }
        .tube-Orange  { background:#fb923c }
        .tube-Brown   { background:#92400e }
        .tube-White   { background:#e2e8f0;border:1px solid #cbd5e1 }
        .tube-Clear   { background:#f8fafc;border:1px solid #cbd5e1 }
        .tube-Amber   { background:#fbbf24 }
    </style>
@endpush

@section('content')

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" style="border-radius:10px;font-size:13px">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible mb-4" style="border-radius:10px;font-size:13px">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="stat-pill">
                <div class="stat-pill-label">Total Types</div>
                <div class="stat-pill-value">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-pill">
                <div class="stat-pill-label">Active</div>
                <div class="stat-pill-value" style="color:#16a34a">{{ $stats['active'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-pill">
                <div class="stat-pill-label">Fasting Required</div>
                <div class="stat-pill-value" style="color:#d97706">{{ $stats['fasting'] }}</div>
            </div>
        </div>
    </div>

    <div class="card-section">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-droplet-fill me-2" style="color:#0ea5e9"></i>Sample Types
            </span>
            <span style="font-size:12px;color:#94a3b8">Add a new row directly in the table below</span>
        </div>

        <div class="table-responsive">
            <table class="table table-clean mb-0">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th>Name</th>
                        <th>Container</th>
                        <th>Color</th>
                        <th>Vol (ml)</th>
                        <th>Tests</th>
                        <th>Flags</th>
                        <th>Status</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    {{-- ── Inline Add Row ── --}}
                    <tr class="add-form-row">
                        <td style="color:#94a3b8;font-size:11px">New</td>
                        <form method="POST" action="{{ route('lab.sample-types.store') }}">
                            @csrf
                            <td>
                                <input type="text" name="name" class="add-input" required
                                    placeholder="e.g. Whole Blood (EDTA)"
                                    value="{{ old('name') }}"
                                    style="min-width:180px">
                                @error('name')
                                    <div class="text-danger" style="font-size:11px;margin-top:3px">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <input type="text" name="container_type" class="add-input"
                                    placeholder="EDTA Vacutainer..."
                                    value="{{ old('container_type') }}"
                                    style="min-width:150px">
                            </td>
                            <td>
                                <input type="text" name="color_code" class="add-input"
                                    placeholder="Purple, Red..."
                                    value="{{ old('color_code') }}"
                                    style="width:100px">
                            </td>
                            <td>
                                <input type="number" name="volume_required" class="add-input"
                                    placeholder="ml" min="1"
                                    value="{{ old('volume_required') }}"
                                    style="width:70px">
                            </td>
                            <td>—</td>
                            <td>
                                <div class="form-check form-check-inline" style="margin:0">
                                    <input class="form-check-input" type="checkbox" name="requires_fasting"
                                        value="1" id="add_fasting"
                                        {{ old('requires_fasting') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="add_fasting" style="font-size:12px;color:#374151">
                                        Fasting
                                    </label>
                                </div>
                            </td>
                            <td>
                                <span style="font-size:11px;background:#dcfce7;color:#166534;padding:2px 9px;border-radius:20px">Active</span>
                            </td>
                            <td style="text-align:right">
                                <button type="submit" class="btn btn-sm btn-success px-3" style="font-size:12px;height:32px">
                                    <i class="bi bi-plus-lg me-1"></i>Add
                                </button>
                            </td>
                        </form>
                    </tr>

                    {{-- ── Existing rows ── --}}
                    @forelse($sampleTypes as $st)
                        @php
                            // First word of color for CSS class
                            $colorClass = 'tube-' . str_replace([' ', '/'], ['-', '-'], explode(',', $st->color_code ?? '')[0]);
                        @endphp
                        <tr>
                            <td style="color:#94a3b8;font-size:11px">{{ $loop->iteration }}</td>
                            <td>
                                <div style="font-weight:500;color:#1e293b">{{ $st->name }}</div>
                                <div style="font-size:11px;color:#94a3b8">{{ $st->code }}</div>
                            </td>
                            <td style="font-size:12px;color:#64748b">{{ $st->container_type ?? '—' }}</td>
                            <td>
                                @if($st->color_code)
                                    <span class="color-dot {{ $colorClass }}"></span>
                                    <span style="font-size:12px;color:#64748b">{{ $st->color_code }}</span>
                                @else
                                    <span style="color:#94a3b8">—</span>
                                @endif
                            </td>
                            <td style="font-size:12px;color:#64748b">
                                {{ $st->volume_required ? $st->volume_required . ' ml' : '—' }}
                            </td>
                            <td>
                                <span style="font-size:12px;font-weight:500;color:{{ $st->tests_count > 0 ? '#374151' : '#94a3b8' }}">
                                    {{ $st->tests_count }}
                                </span>
                            </td>
                            <td>
                                @if($st->requires_fasting)
                                    <span style="font-size:10px;background:#fef9c3;color:#854d0e;padding:2px 8px;border-radius:20px;font-weight:500">Fasting</span>
                                @else
                                    <span style="color:#94a3b8;font-size:11px">—</span>
                                @endif
                            </td>
                            <td>
                                @if($st->is_active)
                                    <span style="font-size:11px;background:#dcfce7;color:#166534;padding:3px 10px;border-radius:20px;font-weight:500">Active</span>
                                @else
                                    <span style="font-size:11px;background:#f1f5f9;color:#64748b;padding:3px 10px;border-radius:20px;font-weight:500">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px"
                                        title="Edit"
                                        onclick="openEditModal({{ $st->id }}, {{ $st->toJson() }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <form method="POST"
                                        action="{{ route('lab.sample-types.toggleActive', $st->id) }}"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm {{ $st->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} py-0 px-2"
                                            style="font-size:12px"
                                            title="{{ $st->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi bi-{{ $st->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                                        </button>
                                    </form>

                                    @if($st->tests_count == 0 && $st->samples_count == 0)
                                        <form method="POST"
                                            action="{{ route('lab.sample-types.destroy', $st->id) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('Delete \'{{ $st->name }}\'?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-outline-danger py-0 px-2"
                                                style="font-size:12px">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button"
                                            class="btn btn-sm btn-outline-secondary py-0 px-2"
                                            style="font-size:12px;opacity:.4;cursor:not-allowed" disabled
                                            title="In use — cannot delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5" style="color:#94a3b8">
                                <i class="bi bi-droplet" style="font-size:36px;display:block;margin-bottom:8px"></i>
                                No sample types yet. Add one above.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sampleTypes->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid #e2e8f0">
                <span style="font-size:12px;color:#94a3b8">
                    Showing {{ $sampleTypes->firstItem() }}–{{ $sampleTypes->lastItem() }} of {{ $sampleTypes->total() }}
                </span>
                {{ $sampleTypes->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    {{-- ═══ Edit Modal ═══ --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:560px">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" style="font-size:14px;font-weight:600;color:#1e293b">
                        <i class="bi bi-pencil me-2" style="color:#0ea5e9"></i>Edit Sample Type
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editForm" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" style="padding:20px">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="edit-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="editName" class="edit-input" required>
                            </div>
                            <div class="col-md-4 d-flex align-items-end pb-1 gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="requires_fasting"
                                        value="1" id="editFasting">
                                    <label class="form-check-label" for="editFasting" style="font-size:13px">Fasting</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                        value="1" id="editIsActive">
                                    <label class="form-check-label" for="editIsActive" style="font-size:13px">Active</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="edit-label">Container Type</label>
                                <input type="text" name="container_type" id="editContainer" class="edit-input"
                                    placeholder="EDTA Vacutainer, Cup, Swab...">
                            </div>
                            <div class="col-md-3">
                                <label class="edit-label">Color</label>
                                <input type="text" name="color_code" id="editColor" class="edit-input"
                                    placeholder="Purple, Red...">
                            </div>
                            <div class="col-md-3">
                                <label class="edit-label">Volume (ml)</label>
                                <input type="number" name="volume_required" id="editVolume" class="edit-input"
                                    min="1" placeholder="ml">
                            </div>
                            <div class="col-12">
                                <label class="edit-label">Collection Instructions</label>
                                <textarea name="collection_instructions" id="editInstructions"
                                    class="edit-input" style="height:80px"
                                    placeholder="Step-by-step collection notes..."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="edit-label">Description</label>
                                <textarea name="description" id="editDescription"
                                    class="edit-input"
                                    placeholder="Optional short description..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3"
                            data-bs-dismiss="modal" style="font-size:13px">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4" style="font-size:13px">
                            <i class="bi bi-check-lg me-1"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openEditModal(id, data) {
        const base = '{{ url("lab/sample-types") }}';
        document.getElementById('editForm').action = base + '/' + id;
        document.getElementById('editName').value         = data.name || '';
        document.getElementById('editContainer').value   = data.container_type || '';
        document.getElementById('editColor').value       = data.color_code || '';
        document.getElementById('editVolume').value      = data.volume_required || '';
        document.getElementById('editInstructions').value= data.collection_instructions || '';
        document.getElementById('editDescription').value = data.description || '';
        document.getElementById('editFasting').checked   = data.requires_fasting == 1;
        document.getElementById('editIsActive').checked  = data.is_active == 1;
        new bootstrap.Modal(document.getElementById('editModal')).show();
    }
</script>
@endpush