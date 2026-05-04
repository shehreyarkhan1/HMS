{{--
    Shared form partial — used in both Add and Edit modals.
    Variables:
      $editing (bool) — true when in edit modal
--}}
@php $editing = $editing ?? false; @endphp

<div class="row g-3">
    <div class="col-md-3">
        <label class="form-label">Room Code *</label>
        <input type="text" name="room_code" class="form-control" required
               placeholder="OT-01" maxlength="20">
    </div>
    <div class="col-md-5">
        <label class="form-label">Room Name *</label>
        <input type="text" name="name" class="form-control" required
               placeholder="e.g. Main Operation Theatre">
    </div>
    <div class="col-md-4">
        <label class="form-label">Room Type *</label>
        <select name="room_type" class="form-select" required>
            @foreach(['General','Cardiac','Neurology','Orthopedic','Gynecology','ENT','Eye','Trauma','Emergency'] as $type)
                <option value="{{ $type }}">{{ $type }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Status *</label>
        <select name="status" class="form-select" required>
            @foreach(['Available','Occupied','Cleaning','Maintenance','Out of Service'] as $s)
                <option value="{{ $s }}">{{ $s }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Floor</label>
        <input type="text" name="floor" class="form-control" placeholder="e.g. 2nd">
    </div>
    <div class="col-md-4">
        <label class="form-label">Block / Wing</label>
        <input type="text" name="block" class="form-control" placeholder="e.g. A">
    </div>

    {{-- Equipment checkboxes --}}
    <div class="col-12">
        <label class="form-label" style="display:block;margin-bottom:8px">Equipment / Features</label>
        <div class="row g-2">
            <div class="col-6 col-md-4">
                <label class="check-row">
                    <input type="checkbox" name="has_anesthesia_machine" value="1">
                    Anesthesia Machine
                </label>
            </div>
            <div class="col-6 col-md-4">
                <label class="check-row">
                    <input type="checkbox" name="has_ventilator" value="1">
                    Ventilator
                </label>
            </div>
            <div class="col-6 col-md-4">
                <label class="check-row">
                    <input type="checkbox" name="has_laparoscopy" value="1">
                    Laparoscopy
                </label>
            </div>
            <div class="col-6 col-md-4">
                <label class="check-row">
                    <input type="checkbox" name="has_c_arm" value="1">
                    C-Arm (X-Ray)
                </label>
            </div>
            <div class="col-6 col-md-4">
                <label class="check-row">
                    <input type="checkbox" name="is_laminar_flow" value="1">
                    Laminar Air Flow
                </label>
            </div>
            @if($editing)
            <div class="col-6 col-md-4">
                <label class="check-row">
                    <input type="checkbox" name="is_active" value="1">
                    Active
                </label>
            </div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Equipment Notes</label>
        <textarea name="equipment_notes" class="form-control" rows="2"
                  placeholder="Additional equipment or special notes..."></textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">General Notes</label>
        <textarea name="notes" class="form-control" rows="2"
                  placeholder="Any other relevant notes..."></textarea>
    </div>
</div>