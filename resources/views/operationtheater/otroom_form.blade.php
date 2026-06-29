{{--
    Shared form partial — used in both Add and Edit modals.
    Variables:
      $editing  (bool)    — true when in edit modal
      $room     (OtRoom)  — passed only when editing
--}}
@php
    $editing = $editing ?? false;
    $room    = isset($room) ? $room : null;

    // Add modal mein koi bhi room data nahi chahiye
    // Edit modal mein sirf tab data aaye jab _modal === 'edit'
    $isAddModal  = !$editing;
    $isEditModal = $editing;

    // Agar Add modal hai aur errors hain, room null rakho
    if ($isAddModal) {
        $room = null;
    }

    $prefix = $editing ? 'edit_' : 'add_';
@endphp

{{-- ─── Global validation alert (non-field errors) ─────────────────────────── --}}
@if($errors->any())
<div class="alert alert-danger alert-dismissible d-flex align-items-start gap-2 mb-3 py-2 px-3" role="alert">
    <i class="bi bi-exclamation-triangle-fill mt-1 flex-shrink-0"></i>
    <div class="flex-grow-1">
        <strong class="d-block mb-1">Please fix the following errors:</strong>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li class="small">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-3">

    {{-- ── Room Code ─────────────────────────────────────────────────────────── --}}
    <div class="col-md-3">
        <label for="{{ $prefix }}room_code" class="form-label">
            Room Code <span class="text-danger">*</span>
        </label>
        <input
            type="text"
            id="{{ $prefix }}room_code"
            name="room_code"
            class="form-control @error('room_code') is-invalid @enderror"
            value="{{ old('room_code', $room?->room_code) }}"
            placeholder="OT-01"
            maxlength="20"
            required
        >
        @error('room_code')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
            </div>
        @enderror
        <div class="valid-feedback">Looks good!</div>
    </div>

    {{-- ── Room Name ─────────────────────────────────────────────────────────── --}}
    <div class="col-md-5">
        <label for="{{ $prefix }}name" class="form-label">
            Room Name <span class="text-danger">*</span>
        </label>
        <input
            type="text"
            id="{{ $prefix }}name"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $room?->name) }}"
            placeholder="e.g. Main Operation Theatre"
            required
        >
        @error('name')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
            </div>
        @enderror
    </div>

    {{-- ── Room Type ─────────────────────────────────────────────────────────── --}}
    <div class="col-md-4">
        <label for="{{ $prefix }}room_type" class="form-label">
            Room Type <span class="text-danger">*</span>
        </label>
        <select
            id="{{ $prefix }}room_type"
            name="room_type"
            class="form-select @error('room_type') is-invalid @enderror"
            required
        >
            <option value="" disabled {{ old('room_type', $room?->room_type) ? '' : 'selected' }}>
                — Select type —
            </option>
            @foreach(['General','Cardiac','Neurology','Orthopedic','Gynecology','ENT','Eye','Trauma','Emergency'] as $type)
                <option value="{{ $type }}"
                    {{ old('room_type', $room?->room_type) === $type ? 'selected' : '' }}>
                    {{ $type }}
                </option>
            @endforeach
        </select>
        @error('room_type')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
            </div>
        @enderror
    </div>

    {{-- ── Status ────────────────────────────────────────────────────────────── --}}
    <div class="col-md-4">
        <label for="{{ $prefix }}status" class="form-label">
            Status <span class="text-danger">*</span>
        </label>
        <select
            id="{{ $prefix }}status"
            name="status"
            class="form-select @error('status') is-invalid @enderror"
            required
        >
            <option value="" disabled {{ old('status', $room?->status) ? '' : 'selected' }}>
                — Select status —
            </option>
            @foreach(['Available','Occupied','Cleaning','Maintenance','Out of Service'] as $s)
                <option value="{{ $s }}"
                    {{ old('status', $room?->status) === $s ? 'selected' : '' }}>
                    {{ $s }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
            </div>
        @enderror
    </div>

    {{-- ── Floor ─────────────────────────────────────────────────────────────── --}}
    <div class="col-md-4">
        <label for="{{ $prefix }}floor" class="form-label">Floor</label>
        <input
            type="text"
            id="{{ $prefix }}floor"
            name="floor"
            class="form-control @error('floor') is-invalid @enderror"
            value="{{ old('floor', $room?->floor) }}"
            placeholder="e.g. 2nd"
        >
        @error('floor')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
            </div>
        @enderror
    </div>

    {{-- ── Block / Wing ──────────────────────────────────────────────────────── --}}
    <div class="col-md-4">
        <label for="{{ $prefix }}block" class="form-label">Block / Wing</label>
        <input
            type="text"
            id="{{ $prefix }}block"
            name="block"
            class="form-control @error('block') is-invalid @enderror"
            value="{{ old('block', $room?->block) }}"
            placeholder="e.g. A"
        >
        @error('block')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
            </div>
        @enderror
    </div>

    {{-- ── Equipment Checkboxes ──────────────────────────────────────────────── --}}
    <div class="col-12">
        <label class="form-label d-block mb-2">Equipment / Features</label>

        @php
            $features = [
                'has_anesthesia_machine' => 'Anesthesia Machine',
                'has_ventilator'         => 'Ventilator',
                'has_laparoscopy'        => 'Laparoscopy',
                'has_c_arm'              => 'C-Arm (X-Ray)',
                'is_laminar_flow'        => 'Laminar Air Flow',
            ];
        @endphp

        <div class="row g-2">
            @foreach($features as $field => $label)
            <div class="col-6 col-md-4">
                <div class="form-check @error($field) is-invalid @enderror">
                    <input
                        type="checkbox"
                        id="{{ $prefix }}{{ $field }}"
                        name="{{ $field }}"
                        value="1"
                        class="form-check-input @error($field) is-invalid @enderror"
                        {{ old($field, $room?->$field) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="{{ $prefix }}{{ $field }}">
                        {{ $label }}
                    </label>
                </div>
                @error($field)
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>
            @endforeach

            @if($editing)
            <div class="col-6 col-md-4">
                <div class="form-check">
                    <input
                        type="checkbox"
                        id="{{ $prefix }}is_active"
                        name="is_active"
                        value="1"
                        class="form-check-input @error('is_active') is-invalid @enderror"
                        {{ old('is_active', $room?->is_active ?? true) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="{{ $prefix }}is_active">
                        Active
                    </label>
                </div>
                @error('is_active')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>
            @endif
        </div>
    </div>

    {{-- ── Equipment Notes ───────────────────────────────────────────────────── --}}
    <div class="col-md-6">
        <label for="{{ $prefix }}equipment_notes" class="form-label">Equipment Notes</label>
        <textarea
            id="{{ $prefix }}equipment_notes"
            name="equipment_notes"
            class="form-control @error('equipment_notes') is-invalid @enderror"
            rows="2"
            placeholder="Additional equipment or special notes..."
        >{{ old('equipment_notes', $room?->equipment_notes) }}</textarea>
        @error('equipment_notes')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
            </div>
        @enderror
    </div>

    {{-- ── General Notes ─────────────────────────────────────────────────────── --}}
    <div class="col-md-6">
        <label for="{{ $prefix }}notes" class="form-label">General Notes</label>
        <textarea
            id="{{ $prefix }}notes"
            name="notes"
            class="form-control @error('notes') is-invalid @enderror"
            rows="2"
            placeholder="Any other relevant notes..."
        >{{ old('notes', $room?->notes) }}</textarea>
        @error('notes')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
            </div>
        @enderror
    </div>

</div>

{{-- ─── Re-open modal on validation failure ─────────────────────────────────── --}}
@if($errors->any() && old('_modal') === 'edit')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Edit modal reopen karo aur action set karo
    var roomId = '{{ old('_edit_room_id') }}';
    var form   = document.getElementById('editRoomForm');
    if (form && roomId) {
        form.action = '/ot/rooms/' + roomId;
        // Hidden field bhi update karo
        var hiddenId = form.querySelector('[name=_edit_room_id]');
        if (hiddenId) hiddenId.value = roomId;
    }
    var el = document.getElementById('editRoomModal');
    if (el) bootstrap.Modal.getOrCreateInstance(el).show();
});
</script>
@elseif($errors->any() && old('_modal') === 'add')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('addRoomModal');
    if (el) bootstrap.Modal.getOrCreateInstance(el).show();
});
</script>
@endif
