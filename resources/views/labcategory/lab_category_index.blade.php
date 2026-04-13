@extends('layouts.master')

@section('title', 'Lab Categories')
@section('page-title', 'Laboratory — Test Categories')
@section('breadcrumb', 'Home / Laboratory / Settings / Categories')

@push('styles')
    <style>
        .stat-pill { background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:14px 20px }
        .stat-pill-label { font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em }
        .stat-pill-value { font-size:22px;font-weight:700;color:#1e293b }
        .card-section { background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden }
        .card-section-header { padding:14px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;gap:10px }
        .table-clean th { font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0!important;padding:10px 16px!important;background:#f8fafc }
        .table-clean td { font-size:13px;color:#374151;padding:12px 16px!important;vertical-align:middle;border-bottom:1px solid #f1f5f9!important }
        .table-clean tr:last-child td { border-bottom:none!important }
        .table-clean tr:hover td { background:#fafafa }

        /* Inline add form */
        .add-form-row td { background:#f0fdf4!important;border-bottom:1px solid #bbf7d0!important }
        .add-input { height:34px;border:1px solid #e2e8f0;border-radius:7px;font-size:13px;padding:0 10px;background:#fff;width:100% }
        .add-input:focus { outline:none;border-color:#93c5fd;box-shadow:0 0 0 3px rgba(147,197,253,.15) }

        /* Modal */
        .modal-content { border-radius:12px;border:1px solid #e2e8f0 }
        .modal-header { border-bottom:1px solid #e2e8f0;padding:14px 20px }
        .modal-footer { border-top:1px solid #e2e8f0;padding:12px 20px }
        .edit-input { height:36px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;padding:0 12px;background:#f8fafc;width:100% }
        .edit-input:focus { outline:none;border-color:#93c5fd;background:#fff;box-shadow:0 0 0 3px rgba(147,197,253,.15) }
        textarea.edit-input { height:70px;padding:8px 12px }
        .edit-label { font-size:12px;font-weight:600;color:#374151;margin-bottom:4px;display:block }
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
                <div class="stat-pill-label">Total</div>
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
                <div class="stat-pill-label">Inactive</div>
                <div class="stat-pill-value" style="color:#94a3b8">{{ $stats['inactive'] }}</div>
            </div>
        </div>
    </div>

    <div class="card-section">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-tags me-2 text-primary"></i>Test Categories
            </span>
            <span style="font-size:12px;color:#94a3b8">Add a new row directly in the table below</span>
        </div>

        <div class="table-responsive">
            <table class="table table-clean mb-0">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Category Name</th>
                        <th>Code</th>
                        <th>Tests</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    {{-- ── Inline Add Row ── --}}
                    <tr class="add-form-row">
                        <td style="color:#94a3b8;font-size:12px">New</td>
                        <form method="POST" action="{{ route('lab.categories.store') }}" id="addCatForm">
                            @csrf
                            <td>
                                <input type="text" name="name" class="add-input" required
                                    placeholder="e.g. Hematology"
                                    value="{{ old('name') }}"
                                    style="width:200px">
                                @error('name')
                                    <div class="text-danger" style="font-size:11px;margin-top:3px">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <span style="font-size:11px;color:#94a3b8">Auto</span>
                            </td>
                            <td>—</td>
                            <td>
                                <input type="text" name="description" class="add-input"
                                    placeholder="Optional description..."
                                    value="{{ old('description') }}"
                                    style="width:220px">
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
                    @forelse($categories as $cat)
                        <tr>
                            <td style="color:#94a3b8;font-size:12px">{{ $loop->iteration }}</td>
                            <td>
                                <div style="font-weight:500;color:#1e293b">{{ $cat->name }}</div>
                            </td>
                            <td>
                                <code style="font-size:11px;color:#6366f1">{{ $cat->code }}</code>
                            </td>
                            <td>
                                <span style="font-size:12px;font-weight:500;
                                    color:{{ $cat->tests_count > 0 ? '#374151' : '#94a3b8' }}">
                                    {{ $cat->tests_count }} test(s)
                                </span>
                            </td>
                            <td style="color:#64748b;font-size:12px;max-width:250px">
                                {{ $cat->description ?? '—' }}
                            </td>
                            <td>
                                @if($cat->is_active)
                                    <span style="font-size:11px;background:#dcfce7;color:#166534;padding:3px 10px;border-radius:20px;font-weight:500">Active</span>
                                @else
                                    <span style="font-size:11px;background:#f1f5f9;color:#64748b;padding:3px 10px;border-radius:20px;font-weight:500">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    {{-- Edit --}}
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary py-0 px-2"
                                        style="font-size:12px"
                                        title="Edit"
                                        onclick="openEditModal(
                                            {{ $cat->id }},
                                            '{{ addslashes($cat->name) }}',
                                            '{{ addslashes($cat->description ?? '') }}',
                                            {{ $cat->is_active ? 'true' : 'false' }}
                                        )">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    {{-- Toggle --}}
                                    <form method="POST"
                                        action="{{ route('lab.categories.toggleActive', $cat->id) }}"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm {{ $cat->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} py-0 px-2"
                                            style="font-size:12px"
                                            title="{{ $cat->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi bi-{{ $cat->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    @if($cat->tests_count == 0)
                                        <form method="POST"
                                            action="{{ route('lab.categories.destroy', $cat->id) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('Delete category \'{{ $cat->name }}\'?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-outline-danger py-0 px-2"
                                                style="font-size:12px" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button"
                                            class="btn btn-sm btn-outline-secondary py-0 px-2"
                                            style="font-size:12px;opacity:.4;cursor:not-allowed"
                                            title="Cannot delete — has {{ $cat->tests_count }} test(s)" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5" style="color:#94a3b8">
                                <i class="bi bi-tags" style="font-size:36px;display:block;margin-bottom:8px"></i>
                                No categories yet. Add one above.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid #e2e8f0">
                <span style="font-size:12px;color:#94a3b8">
                    Showing {{ $categories->firstItem() }}–{{ $categories->lastItem() }} of {{ $categories->total() }}
                </span>
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    {{-- ═══ Edit Modal ═══ --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" style="font-size:14px;font-weight:600;color:#1e293b">
                        <i class="bi bi-pencil me-2 text-primary"></i>Edit Category
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editForm" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" style="padding:20px">
                        <div class="mb-3">
                            <label class="edit-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editName" class="edit-input" required>
                        </div>
                        <div class="mb-3">
                            <label class="edit-label">Description</label>
                            <textarea name="description" id="editDescription" class="edit-input"
                                placeholder="Optional..."></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                value="1" id="editIsActive">
                            <label class="form-check-label" for="editIsActive" style="font-size:13px;color:#374151">
                                Active
                            </label>
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
    function openEditModal(id, name, description, isActive) {
        document.getElementById('editForm').action = '/lab/categories/' + id;
        document.getElementById('editName').value = name;
        document.getElementById('editDescription').value = description;
        document.getElementById('editIsActive').checked = isActive;
        new bootstrap.Modal(document.getElementById('editModal')).show();
    }
</script>
@endpush