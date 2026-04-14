@extends('layouts.master')

@section('title', 'Radiology Modalities')
@section('page-title', 'Radiology — Modalities')
@section('breadcrumb', 'Home / Radiology / Modalities')

@push('styles')
<style>
    .card-section { background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden }
    .card-section-header {
        padding:14px 20px;border-bottom:1px solid #e2e8f0;
        display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px
    }
    .table-clean th {
        font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;
        letter-spacing:.05em;border-bottom:1px solid #e2e8f0 !important;
        padding:10px 16px !important;background:#f8fafc
    }
    .table-clean td {
        font-size:13px;color:#374151;padding:12px 16px !important;
        vertical-align:middle;border-bottom:1px solid #f1f5f9 !important
    }
    .table-clean tr:last-child td { border-bottom:none !important }
    .table-clean tr:hover td { background:#fafafa }
    .badge-active   { background:#dcfce7;color:#166534;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
    .badge-inactive { background:#f1f5f9;color:#64748b;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500 }
    .tag-yes { background:#dbeafe;color:#1e40af;font-size:11px;padding:2px 8px;border-radius:20px }
    .tag-no  { background:#f1f5f9;color:#94a3b8;font-size:11px;padding:2px 8px;border-radius:20px }
</style>
@endpush

@section('content')

@foreach(['success','error'] as $msg)
    @if(session($msg))
        <div class="alert alert-{{ $msg === 'error' ? 'danger' : 'success' }} d-flex align-items-center gap-2 mb-4"
            style="border-radius:10px;font-size:13px">
            <i class="bi bi-{{ $msg === 'error' ? 'x-circle' : 'check-circle' }}-fill"></i>
            {{ session($msg) }}
        </div>
    @endif
@endforeach

<div class="card-section">
    <div class="card-section-header">
        <span style="font-size:14px;font-weight:600;color:#1e293b">
            <i class="bi bi-camera me-2 text-primary"></i>Imaging Modalities
        </span>
        <a href="{{ route('radiology.modalities.create') }}" class="btn btn-sm btn-success px-3"
            style="height:36px;font-size:13px;display:flex;align-items:center">
            <i class="bi bi-plus-lg me-1"></i>Add Modality
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Avg. Duration</th>
                    <th>Contrast</th>
                    <th>Preparation</th>
                    <th>Exams</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($modalities as $m)
                    <tr>
                        <td>
                            <div style="font-weight:600;color:#1e293b">{{ $m->name }}</div>
                            @if($m->description)
                                <div style="font-size:11px;color:#94a3b8">{{ Str::limit($m->description, 60) }}</div>
                            @endif
                        </td>
                        <td><code style="font-size:12px;color:#6366f1">{{ $m->code }}</code></td>
                        <td style="color:#64748b">{{ $m->average_duration_minutes }} min</td>
                        <td>
                            <span class="{{ $m->requires_contrast ? 'tag-yes' : 'tag-no' }}">
                                {{ $m->requires_contrast ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td>
                            <span class="{{ $m->requires_preparation ? 'tag-yes' : 'tag-no' }}">
                                {{ $m->requires_preparation ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td>
                            <span style="font-size:12px;font-weight:600;color:#374151">
                                {{ $m->exams_count }}
                            </span>
                        </td>
                        <td>
                            <span class="{{ $m->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $m->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('radiology.modalities.edit', $m->id) }}"
                                    class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST"
                                    action="{{ route('radiology.modalities.toggle', $m->id) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-secondary py-0 px-2"
                                        style="font-size:12px"
                                        title="{{ $m->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="bi bi-{{ $m->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                    </button>
                                </form>
                                @if($m->exams_count === 0)
                                    <form method="POST"
                                        action="{{ route('radiology.modalities.destroy', $m->id) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('Delete {{ $m->name }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger py-0 px-2"
                                            style="font-size:12px" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5" style="color:#94a3b8">
                            <i class="bi bi-camera" style="font-size:40px;display:block;margin-bottom:8px;opacity:.3"></i>
                            No modalities found.
                            <a href="{{ route('radiology.modalities.create') }}" class="text-primary ms-1">Add first</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($modalities->hasPages())
        <div class="d-flex justify-content-between align-items-center px-4 py-3"
            style="border-top:1px solid #e2e8f0">
            <span style="font-size:12px;color:#94a3b8">
                Showing {{ $modalities->firstItem() }}–{{ $modalities->lastItem() }}
                of {{ $modalities->total() }}
            </span>
            {{ $modalities->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection