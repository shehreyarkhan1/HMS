@extends('layouts.master')
@section('title', 'Leave Types')
@section('page-title', 'Leave Types')
@section('breadcrumb', 'HR / Leave Types')

@push('styles')
    <style>
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            border: 1px solid #e2e8f0;
            height: 100%;
            transition: box-shadow .15s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, .07);
        }

        .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: .5rem;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
            margin-bottom: .4rem;
        }

        .stat-sub {
            font-size: 12px;
            color: #94a3b8;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .section-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .section-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        .lt-row {
            display: flex;
            align-items: center;
            padding: .85rem 1.25rem;
            border-bottom: 1px solid #f8fafc;
            gap: 12px;
            transition: background .1s;
        }

        .lt-row:last-child {
            border-bottom: none;
        }

        .lt-row:hover {
            background: #f8fafc;
        }

        .lt-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #eff6ff;
            color: #1d4ed8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .lt-info {
            flex: 1;
            min-width: 0;
        }

        .lt-name {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
        }

        .lt-meta {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .tag {
            font-size: 10px;
            font-weight: 600;
            padding: 2px 9px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .tag-paid {
            background: #dcfce7;
            color: #16a34a;
        }

        .tag-unpaid {
            background: #fee2e2;
            color: #dc2626;
        }

        .tag-carry {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .tag-enc {
            background: #fef3c7;
            color: #b45309;
        }

        .tag-doc {
            background: #ede9fe;
            color: #7c3aed;
        }

        .tag-inactive {
            background: #f1f5f9;
            color: #94a3b8;
        }

        .btn-icon {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            transition: all .15s;
            background: #fff;
            color: #64748b;
            text-decoration: none;
        }

        .btn-icon:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .btn-icon.danger:hover {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fca5a5;
        }

        .empty-state {
            padding: 3rem;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
        }

        .empty-state i {
            font-size: 32px;
            display: block;
            margin-bottom: .75rem;
            color: #cbd5e1;
        }

        .days-pill {
            font-size: 12px;
            font-weight: 700;
            color: #1d4ed8;
            background: #eff6ff;
            padding: 3px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }
    </style>
@endpush

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 class="mb-0 fw-semibold" style="color:#1e293b">Leave Types</h5>
            <p class="mb-0" style="font-size:13px; color:#94a3b8">Configure leave categories and entitlements</p>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addLtModal">
            <i class="bi bi-plus-lg me-1"></i> Add Leave Type
        </button>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Total Types</div>
                        <div class="stat-value">{{ $leaveTypes->count() }}</div>
                        <div class="stat-sub">configured</div>
                    </div>
                    <div class="stat-icon" style="background:#eff6ff;color:#1d4ed8"><i class="bi bi-card-list"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Active</div>
                        <div class="stat-value">{{ $leaveTypes->where('is_active', true)->count() }}</div>
                        <div class="stat-sub">in use</div>
                    </div>
                    <div class="stat-icon" style="background:#dcfce7;color:#16a34a"><i class="bi bi-check-circle"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Paid Leave</div>
                        <div class="stat-value">{{ $leaveTypes->where('is_paid', true)->count() }}</div>
                        <div class="stat-sub">types</div>
                    </div>
                    <div class="stat-icon" style="background:#fef3c7;color:#b45309"><i class="bi bi-cash-coin"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">With Carry Fwd</div>
                        <div class="stat-value">{{ $leaveTypes->where('carry_forward', true)->count() }}</div>
                        <div class="stat-sub">types</div>
                    </div>
                    <div class="stat-icon" style="background:#ede9fe;color:#7c3aed"><i class="bi bi-arrow-right-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section-card">
        <div class="section-header">
            <span class="section-title"><i class="bi bi-card-list me-2" style="color:#1d4ed8"></i>All Leave Types</span>
        </div>

        @if ($leaveTypes->isEmpty())
            <div class="empty-state"><i class="bi bi-inbox"></i>No leave types configured yet.</div>
        @else
            @foreach ($leaveTypes as $lt)
                <div class="lt-row {{ !$lt->is_active ? 'opacity-50' : '' }}">
                    <div class="lt-icon"><i class="bi bi-calendar-heart"></i></div>
                    <div class="lt-info">
                        <div class="lt-name">{{ $lt->name }}
                            <span class="text-muted" style="font-size:11px; font-weight:400">({{ $lt->code }})</span>
                        </div>
                        <div class="lt-meta">
                            @if ($lt->description)
                                {{ Str::limit($lt->description, 60) }} &middot;
                            @endif
                            Max {{ $lt->max_consecutive_days ?? '—' }} consecutive days
                            @if ($lt->notice_days_required)
                                &middot; {{ $lt->notice_days_required }}d notice
                            @endif
                        </div>
                    </div>

                    {{-- Days --}}
                    <span class="days-pill">{{ $lt->days_per_year }} days/yr</span>

                    {{-- Tags --}}
                    <div class="d-flex gap-1 flex-wrap">
                        <span
                            class="tag {{ $lt->is_paid ? 'tag-paid' : 'tag-unpaid' }}">{{ $lt->is_paid ? 'Paid' : 'Unpaid' }}</span>
                        @if ($lt->carry_forward)
                            <span class="tag tag-carry">Carry Fwd</span>
                        @endif
                        @if ($lt->encashable)
                            <span class="tag tag-enc">Encashable</span>
                        @endif
                        @if ($lt->requires_document)
                            <span class="tag tag-doc">Doc Required</span>
                        @endif
                        @if (!$lt->is_active)
                            <span class="tag tag-inactive">Inactive</span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-1">
                        <a href="#" class="btn-icon" title="Edit" data-bs-toggle="modal"
                            data-bs-target="#editLtModal" data-id="{{ $lt->id }}" data-name="{{ $lt->name }}"
                            data-code="{{ $lt->code }}" data-description="{{ $lt->description }}"
                            data-days_per_year="{{ $lt->days_per_year }}" data-is_paid="{{ $lt->is_paid ? '1' : '0' }}"
                            data-carry_forward="{{ $lt->carry_forward ? '1' : '0' }}"
                            data-max_carry_forward="{{ $lt->max_carry_forward }}"
                            data-encashable="{{ $lt->encashable ? '1' : '0' }}"
                            data-requires_document="{{ $lt->requires_document ? '1' : '0' }}"
                            data-min_service_days="{{ $lt->min_service_days }}"
                            data-max_consecutive_days="{{ $lt->max_consecutive_days }}"
                            data-notice_days_required="{{ $lt->notice_days_required }}"
                            data-applicable_male="{{ $lt->applicable_male ? '1' : '0' }}"
                            data-applicable_female="{{ $lt->applicable_female ? '1' : '0' }}"
                            data-is_active="{{ $lt->is_active ? '1' : '0' }}">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('hr.leave-types.toggle', $lt) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-icon"
                                title="{{ $lt->is_active ? 'Deactivate' : 'Activate' }}">
                                <i class="bi bi-{{ $lt->is_active ? 'toggle-on text-success' : 'toggle-off text-muted' }}"
                                    style="font-size:14px"></i>
                            </button>
                        </form>
                        <form action="{{ route('hr.leave-types.destroy', $lt) }}" method="POST"
                            onsubmit="return confirm('Delete {{ addslashes($lt->name) }}? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon danger" title="Delete"><i
                                    class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- ══ ADD MODAL ══ --}}
    <div class="modal fade" id="addLtModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius:14px; border:1px solid #e2e8f0;">
                <div class="modal-header" style="border-bottom:1px solid #f1f5f9; padding:1.1rem 1.5rem;">
                    <h6 class="modal-title fw-semibold" style="color:#1e293b"><i class="bi bi-plus-circle me-2"
                            style="color:#1d4ed8"></i>Add Leave Type</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('hr.leave-types.store') }}" method="POST">
                    @csrf
                    @include('hr.leave_type_form', ['lt' => null])
                    <div class="modal-footer" style="border-top:1px solid #f1f5f9; padding:1rem 1.5rem;">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-lg me-1"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ EDIT MODAL ══ --}}
    <div class="modal fade" id="editLtModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius:14px; border:1px solid #e2e8f0;">
                <div class="modal-header" style="border-bottom:1px solid #f1f5f9; padding:1.1rem 1.5rem;">
                    <h6 class="modal-title fw-semibold" style="color:#1e293b"><i class="bi bi-pencil-square me-2"
                            style="color:#1d4ed8"></i>Edit Leave Type</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editLtForm" method="POST">
                    @csrf @method('PUT')
                    @include('hr.leave_type_form', ['lt' => null, 'prefix' => 'edit_'])
                    <div class="modal-footer" style="border-top:1px solid #f1f5f9; padding:1rem 1.5rem;">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-lg me-1"></i>
                            Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.getElementById('editLtModal').addEventListener('show.bs.modal', function(e) {
            const b = e.relatedTarget;
            const f = document.getElementById('editLtForm');
            f.action = `{{ url('hr/leave-types') }}/${b.dataset.id}`;

            // Text fields
            const fields = ['name', 'code', 'description', 'days_per_year', 'max_carry_forward',
                'min_service_days', 'max_consecutive_days', 'notice_days_required'
            ];
            fields.forEach(k => {
                // camelCase convert karo dataset key ke liye
                const dataKey = k.replace(/_([a-z])/g, (_, c) => c.toUpperCase());
                const el = f.querySelector(`[name="${k}"]`);
                if (el) el.value = b.dataset[dataKey] || '';
            });

            // Checkboxes — prefix 'edit_' lagao
            const checks = ['is_paid', 'carry_forward', 'encashable', 'requires_document',
                'applicable_male', 'applicable_female', 'is_active'
            ];
            checks.forEach(k => {
                const dataKey = k.replace(/_([a-z])/g, (_, c) => c.toUpperCase());
                const el = document.getElementById('edit_' + k); // ← prefix 'edit_'
                if (el) el.checked = b.dataset[dataKey] === '1';
            });
        });
        @if ($errors->any())
            new bootstrap.Modal(document.getElementById('addLtModal')).show();
        @endif
    </script>
@endpush
