@extends('layouts.master')

@section('title', 'Radiology Exams')
@section('page-title', 'Radiology — Exam Catalog')
@section('breadcrumb', 'Home / Radiology / Exams')

@push('styles')
    <style>
        .stat-pill {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 20px
        }

        .stat-pill-label {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em
        }

        .stat-pill-value {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b
        }

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

        .filter-bar input,
        .filter-bar select {
            height: 36px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            padding: 0 12px;
            color: #374151;
            background: #f8fafc
        }

        .filter-bar input:focus,
        .filter-bar select:focus {
            outline: none;
            border-color: #93c5fd;
            background: #fff
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
            padding: 12px 16px !important;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9 !important
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
            background: #fee2e2;
            color: #991b1b;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .contrast-yes {
            background: #fef3c7;
            color: #92400e;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 600
        }

        .contrast-no {
            background: #f1f5f9;
            color: #64748b;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 500
        }
    </style>
@endpush

@section('content')

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Total Exams</div>
                <div class="stat-pill-value">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Active</div>
                <div class="stat-pill-value" style="color:#16a34a">{{ $stats['active'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">Inactive</div>
                <div class="stat-pill-value" style="color:#dc2626">{{ $stats['inactive'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div class="stat-pill-label">With Contrast</div>
                <div class="stat-pill-value" style="color:#d97706">{{ $stats['with_contrast'] }}</div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card-section">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-card-list me-2 text-primary"></i>Exam Catalog
            </span>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <form method="GET" class="d-flex gap-2 flex-wrap filter-bar">
                    <input type="text" name="search" placeholder="Exam name, code..." value="{{ request('search') }}"
                        style="width:190px">
                    <select name="modality_id">
                        <option value="">All modalities</option>
                        @foreach ($modalities as $m)
                            <option value="{{ $m->id }}" {{ request('modality_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="body_part_id">
                        <option value="">All body parts</option>
                        @foreach ($bodyParts as $bp)
                            <option value="{{ $bp->id }}" {{ request('body_part_id') == $bp->id ? 'selected' : '' }}>
                                {{ $bp->name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="requires_contrast">
                        <option value="">Contrast filter</option>
                        <option value="yes" {{ request('requires_contrast') === 'yes' ? 'selected' : '' }}>Requires
                            Contrast</option>
                        <option value="no" {{ request('requires_contrast') === 'no' ? 'selected' : '' }}>No Contrast
                        </option>
                    </select>
                    <select name="is_active">
                        <option value="">All status</option>
                        <option value="active" {{ request('is_active') === 'active' ? 'selected' : '' }}>Active only
                        </option>
                        <option value="inactive" {{ request('is_active') === 'inactive' ? 'selected' : '' }}>Inactive only
                        </option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    @if (request()->hasAny(['search', 'modality_id', 'body_part_id', 'requires_contrast', 'is_active']))
                        <a href="{{ route('radiology.exams.index') }}" class="btn btn-sm btn-outline-secondary px-3"
                            style="height:36px;font-size:13px">Clear</a>
                    @endif
                </form>
                <a href="{{ route('radiology.exams.create') }}" class="btn btn-sm btn-success px-3"
                    style="height:36px;font-size:13px">
                    <i class="bi bi-plus-lg me-1"></i>New Exam
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-clean mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Exam Name</th>
                        <th>Modality</th>
                        <th>Body Part</th>
                        <th>Price</th>
                        <th>Contrast</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exams as $exam)
                        <tr>
                            <td><code style="font-size:12px;color:#6366f1">{{ $exam->exam_code }}</code></td>
                            <td>
                                <div style="font-weight:500;color:#1e293b">{{ $exam->name }}</div>
                                @if ($exam->description)
                                    <div style="font-size:11px;color:#94a3b8">{{ Str::limit($exam->description, 50) }}</div>
                                @endif
                            </td>
                            <td style="color:#64748b">{{ $exam->modality->name ?? '—' }}</td>
                            <td style="color:#64748b">{{ $exam->bodyPart->name ?? '—' }}</td>
                            <td style="font-weight:600;color:#1e293b">Rs {{ number_format($exam->price, 0) }}</td>
                            <td>
                                <span class="contrast-{{ $exam->requires_contrast ? 'yes' : 'no' }}">
                                    {{ $exam->requires_contrast ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td style="color:#64748b;font-size:12px">{{ $exam->duration_minutes }} min</td>
                            <td>
                                <span class="badge-{{ $exam->is_active ? 'active' : 'inactive' }}">
                                    {{ $exam->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-1 flex-wrap">
                                    <a href="{{ route('radiology.exams.show', $exam->id) }}"
                                        class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('radiology.exams.edit', $exam->id) }}"
                                        class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:12px" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Toggle Status --}}
                                    <form method="POST" action="{{ route('radiology.exams.toggleStatus', $exam->id) }}"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning py-0 px-2"
                                            style="font-size:12px" title="{{ $exam->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi bi-{{ $exam->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    @if (!$exam->orderItems()->exists())
                                        <form method="POST" action="{{ route('radiology.exams.destroy', $exam->id) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this exam?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2"
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
                            <td colspan="9" class="text-center py-5" style="color:#94a3b8">
                                <i class="bi bi-card-list"
                                    style="font-size:40px;display:block;margin-bottom:8px;opacity:.3"></i>
                                No exams found.
                                <a href="{{ route('radiology.exams.create') }}" class="text-primary ms-1">Create first</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($exams->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid #e2e8f0">
                <span style="font-size:12px;color:#94a3b8">
                    Showing {{ $exams->firstItem() }}–{{ $exams->lastItem() }} of {{ $exams->total() }}
                </span>
                {{ $exams->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection