<div class="modal-body" style="padding:1.5rem;">
    <div class="row g-3">
        <div class="col-6">
            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Name <span
                    class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $lt->name ?? '') }}" placeholder="e.g. Annual Leave" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-6">
            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Code <span
                    class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                value="{{ old('code', $lt->code ?? '') }}" placeholder="e.g. AL" maxlength="10" required>
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Description</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Brief description">{{ old('description', $lt->description ?? '') }}</textarea>
        </div>
        <div class="col-4">
            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Days per Year <span
                    class="text-danger">*</span></label>
            <input type="number" name="days_per_year" class="form-control" min="0" max="365"
                value="{{ old('days_per_year', $lt->days_per_year ?? '') }}" required>
        </div>
        <div class="col-4">
            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Max Consecutive Days</label>
            <input type="number" name="max_consecutive_days" class="form-control" min="1"
                value="{{ old('max_consecutive_days', $lt->max_consecutive_days ?? '') }}">
        </div>
        <div class="col-4">
            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Notice Days Required</label>
            <input type="number" name="notice_days_required" class="form-control" min="0"
                value="{{ old('notice_days_required', $lt->notice_days_required ?? '') }}">
        </div>
        <div class="col-4">
            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Min Service Days</label>
            <input type="number" name="min_service_days" class="form-control" min="0"
                value="{{ old('min_service_days', $lt->min_service_days ?? '') }}" placeholder="0">
        </div>
        <div class="col-4">
            <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b">Max Carry Forward</label>
            <input type="number" name="max_carry_forward" class="form-control" min="0"
                value="{{ old('max_carry_forward', $lt->max_carry_forward ?? '') }}">
        </div>

        {{-- Toggles --}}
        <div class="col-12">
            <div class="row g-2">
                @foreach ([['is_paid', 'Paid Leave', '#16a34a'], ['carry_forward', 'Carry Forward', '#1d4ed8'], ['encashable', 'Encashable', '#b45309'], ['requires_document', 'Requires Document', '#7c3aed'], ['applicable_male', 'Applicable (Male)', '#0891b2'], ['applicable_female', 'Applicable (Female)', '#db2777'], ['is_active', 'Active', '#64748b']] as [$field, $label, $color])
                    <div class="col-6 col-md-4">
                        <div class="form-check form-switch d-flex align-items-center gap-2" style="padding-left:2.5rem">
                            <input class="form-check-input" type="checkbox" name="{{ $field }}" value="1"
                                id="{{ ($prefix ?? 'add') . $field }}"
                                {{ old($field, $lt->$field ?? ($field === 'is_active' || $field === 'applicable_male' || $field === 'applicable_female' || $field === 'is_paid' ? true : false)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ ($prefix ?? 'add') . $field }}"
                                style="font-size:12px; font-weight:600; color:{{ $color }}">{{ $label }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
