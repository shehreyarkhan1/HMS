@extends('layouts.master')

@section('title', 'OT Rooms')
@section('page-title', 'OT Rooms')
@section('breadcrumb', 'Home / OT / Rooms')

@push('styles')
<style>
    .stat-pill { background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:14px 20px; }
    .stat-pill-label { font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; }
    .stat-pill-value { font-size:22px; font-weight:700; color:#1e293b; }

    .room-card {
        background:#fff; border:1px solid #e2e8f0; border-radius:12px;
        padding:18px 20px; transition:box-shadow 0.2s; height:100%;
    }
    .room-card:hover { box-shadow:0 4px 16px rgba(0,0,0,0.07); }

    .room-status-badge {
        font-size:10px; padding:3px 10px; border-radius:20px; font-weight:600;
        display:inline-flex; align-items:center; gap:4px;
    }
    .rs-Available      { background:#dcfce7; color:#166534; }
    .rs-Occupied       { background:#fee2e2; color:#991b1b; }
    .rs-Cleaning       { background:#fef9c3; color:#854d0e; }
    .rs-Maintenance    { background:#fff7ed; color:#9a3412; }
    .rs-Out-of-Service { background:#f1f5f9; color:#475569; }

    .room-code {
        font-size:13px; font-weight:700; color:#1e293b;
        font-family:monospace; background:#f1f5f9;
        padding:4px 10px; border-radius:6px; display:inline-block;
    }
    .feature-dot {
        width:8px; height:8px; border-radius:50%;
        display:inline-block; margin-right:4px;
    }
    .feature-on  { background:#22c55e; }
    .feature-off { background:#e2e8f0; }

    .feature-label { font-size:11px; color:#64748b; }

    /* Modal form */
    .modal-body .form-label { font-size:12px; font-weight:500; color:#374151; margin-bottom:4px; }
    .modal-body .form-control, .modal-body .form-select {
        font-size:13px; border:1px solid #e2e8f0; border-radius:8px;
        background:#f8fafc; height:36px; padding:0 12px;
    }
    .modal-body .form-control:focus, .modal-body .form-select:focus {
        border-color:#93c5fd; background:#fff; box-shadow:none;
    }
    .modal-body textarea.form-control { height:auto; padding:8px 12px; }
    .modal-body .check-row { display:flex; align-items:center; gap:8px; font-size:13px; color:#374151; }
    .modal-body .check-row input { accent-color:#6366f1; width:15px; height:15px; }
</style>
@endpush

@section('content')

{{-- Top --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <a href="{{ route('ot.index') }}"
       class="btn btn-sm btn-outline-secondary px-3" style="height:34px;font-size:13px">
        <i class="bi bi-arrow-left me-1"></i>Back to OT
    </a>
    <button type="button" class="btn btn-sm btn-success px-3" style="height:34px;font-size:13px"
            data-bs-toggle="modal" data-bs-target="#addRoomModal">
        <i class="bi bi-plus-lg me-1"></i>Add OT Room
    </button>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-pill">
            <div class="stat-pill-label">Total Rooms</div>
            <div class="stat-pill-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-pill">
            <div class="stat-pill-label">Available</div>
            <div class="stat-pill-value" style="color:#16a34a">{{ $stats['available'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-pill">
            <div class="stat-pill-label">Occupied</div>
            <div class="stat-pill-value" style="color:#dc2626">{{ $stats['occupied'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-pill">
            <div class="stat-pill-label">Maintenance</div>
            <div class="stat-pill-value" style="color:#f59e0b">{{ $stats['maintenance'] }}</div>
        </div>
    </div>
</div>

{{-- Success/Error Alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" style="font-size:13px;border-radius:10px" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" style="font-size:13px;border-radius:10px" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Rooms Grid --}}
@if($rooms->count())
<div class="row g-3 mb-3">
    @foreach($rooms as $room)
    @php $statusClass = 'rs-' . str_replace(' ', '-', $room->status); @endphp
    <div class="col-12 col-md-6 col-xl-4">
        <div class="room-card">

            {{-- Header --}}
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div>
                    <span class="room-code">{{ $room->room_code }}</span>
                    <div style="font-size:14px;font-weight:600;color:#1e293b;margin-top:6px">{{ $room->name }}</div>
                    <div style="font-size:12px;color:#6366f1;margin-top:2px">{{ $room->room_type }}</div>
                </div>
                <span class="room-status-badge {{ $statusClass }}">
                    {{ $room->status }}
                </span>
            </div>

            {{-- Location --}}
            @if($room->floor || $room->block)
            <div style="font-size:12px;color:#64748b;margin-bottom:10px">
                <i class="bi bi-geo-alt me-1" style="color:#94a3b8"></i>
                @if($room->floor) Floor {{ $room->floor }} @endif
                @if($room->block) / Block {{ $room->block }} @endif
            </div>
            @endif

            {{-- Features --}}
            <div style="background:#f8fafc;border-radius:8px;padding:10px;margin-bottom:12px">
                <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px">Equipment</div>
                <div class="d-flex flex-wrap gap-x-3 gap-y-1" style="gap:6px">
                    <span class="feature-label">
                        <span class="feature-dot {{ $room->has_anesthesia_machine ? 'feature-on' : 'feature-off' }}"></span>
                        Anesthesia
                    </span>
                    <span class="feature-label">
                        <span class="feature-dot {{ $room->has_ventilator ? 'feature-on' : 'feature-off' }}"></span>
                        Ventilator
                    </span>
                    <span class="feature-label">
                        <span class="feature-dot {{ $room->has_laparoscopy ? 'feature-on' : 'feature-off' }}"></span>
                        Laparoscopy
                    </span>
                    <span class="feature-label">
                        <span class="feature-dot {{ $room->has_c_arm ? 'feature-on' : 'feature-off' }}"></span>
                        C-Arm
                    </span>
                    <span class="feature-label">
                        <span class="feature-dot {{ $room->is_laminar_flow ? 'feature-on' : 'feature-off' }}"></span>
                        Laminar Flow
                    </span>
                </div>
            </div>

            {{-- Today's count --}}
            <div style="font-size:12px;color:#64748b;margin-bottom:12px">
                <i class="bi bi-calendar3 me-1" style="color:#94a3b8"></i>
                Today: <strong style="color:#1e293b">{{ $room->today_schedules }}</strong> surgeries
                &nbsp;|&nbsp;
                <span style="color:{{ $room->is_active ? '#16a34a' : '#dc2626' }}">
                    {{ $room->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <hr style="border-color:#f1f5f9;margin:10px 0">

            {{-- Actions --}}
            <div class="d-flex gap-2">
                <button type="button"
                        class="btn btn-sm btn-outline-warning py-0 px-2 edit-room-btn" style="font-size:12px"
                        data-bs-toggle="modal" data-bs-target="#editRoomModal"
                        data-room="{{ json_encode($room) }}">
                    <i class="bi bi-pencil me-1"></i>Edit
                </button>
                <form method="POST" action="{{ route('ot.rooms.destroy', $room->id) }}"
                      onsubmit="return confirm('Delete {{ $room->room_code }}?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:12px">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                </form>
            </div>

        </div>
    </div>
    @endforeach
</div>

{{ $rooms->links('pagination::bootstrap-5') }}

@else
<div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:60px;text-align:center">
    <i class="bi bi-door-open" style="font-size:48px;color:#e2e8f0;display:block;margin-bottom:12px"></i>
    <div style="font-size:15px;font-weight:500;color:#374151;margin-bottom:6px">No OT Rooms added yet</div>
    <div style="font-size:13px;color:#94a3b8;margin-bottom:16px">Add your first operation theatre room</div>
    <button type="button" class="btn btn-primary btn-sm px-4" data-bs-toggle="modal" data-bs-target="#addRoomModal">
        <i class="bi bi-plus-lg me-1"></i>Add OT Room
    </button>
</div>
@endif


{{-- ── ADD ROOM MODAL ──────────────────────────────────────────────────── --}}
<div class="modal fade" id="addRoomModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:20px 24px">
                <h5 class="modal-title" style="font-size:15px;font-weight:600">Add OT Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('ot.rooms.store') }}">
                @csrf
                <div class="modal-body" style="padding:24px">
                    @include('operationtheater.otroom_form')
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:16px 24px">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm px-4">
                        <i class="bi bi-plus-lg me-1"></i>Add Room
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── EDIT ROOM MODAL ─────────────────────────────────────────────────── --}}
<div class="modal fade" id="editRoomModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:20px 24px">
                <h5 class="modal-title" style="font-size:15px;font-weight:600">Edit OT Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editRoomForm" action="">
                @csrf @method('PUT')
                <div class="modal-body" style="padding:24px">
                    @include('operationtheater.otroom_form', ['editing' => true])
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:16px 24px">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm px-4">
                        <i class="bi bi-check-lg me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.edit-room-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const room = JSON.parse(this.dataset.room);
        const form = document.getElementById('editRoomForm');
        form.action = `/ot/rooms/${room.id}`;

        // Populate fields
        form.querySelector('[name=room_code]').value = room.room_code;
        form.querySelector('[name=name]').value      = room.name;
        form.querySelector('[name=room_type]').value = room.room_type;
        form.querySelector('[name=status]').value    = room.status;
        form.querySelector('[name=floor]').value     = room.floor ?? '';
        form.querySelector('[name=block]').value     = room.block ?? '';
        form.querySelector('[name=equipment_notes]').value = room.equipment_notes ?? '';
        form.querySelector('[name=notes]').value     = room.notes ?? '';

        // Checkboxes
        ['has_anesthesia_machine','has_ventilator','has_laparoscopy','has_c_arm','is_laminar_flow','is_active'].forEach(f => {
            const el = form.querySelector(`[name=${f}]`);
            if (el) el.checked = !!room[f];
        });
    });
});
</script>
@endpush