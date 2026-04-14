@push('styles')
<style>
    .form-card { background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;max-width:700px;margin:0 auto }
    .form-card-header { padding:16px 22px;border-bottom:1px solid #e2e8f0;font-size:14px;font-weight:600;color:#1e293b;display:flex;align-items:center;gap:8px }
    .form-card-body  { padding:22px }
    .field-label { font-size:12px;font-weight:600;color:#374151;margin-bottom:5px }
    .field-hint  { font-size:11px;color:#94a3b8;margin-top:3px }
    .toggle-row  {
        display:flex;align-items:center;justify-content:space-between;
        padding:12px 14px;background:#f8fafc;border:1px solid #e2e8f0;
        border-radius:8px;cursor:pointer
    }
    .toggle-row label { font-size:13px;color:#374151;font-weight:500;cursor:pointer;margin:0 }
    .toggle-row small { font-size:11px;color:#94a3b8 }
</style>
@endpush

<div class="form-card">
    <div class="form-card-header">
        <i class="bi bi-camera text-primary"></i>
        {{ $modality ? 'Edit Modality — ' . $modality->name : 'New Modality' }}
    </div>
    <div class="form-card-body">
        <form method="POST" action="{{ $route }}">
            @csrf
            @if($method === 'PUT') @method('PUT') @endif

            @if($errors->any())
                <div class="alert alert-danger mb-3" style="font-size:13px;border-radius:8px">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-3">
                {{-- Name --}}
                <div class="col-md-7">
                    <div class="field-label">Modality Name *</div>
                    <input type="text" name="name"
                        class="form-control form-control-sm @error('name') is-invalid @enderror"
                        value="{{ old('name', $modality?->name) }}"
                        placeholder="e.g. CT Scan, MRI, X-Ray" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Code --}}
                <div class="col-md-5">
                    <div class="field-label">Code *</div>
                    <input type="text" name="code"
                        class="form-control form-control-sm @error('code') is-invalid @enderror"
                        value="{{ old('code', $modality?->code) }}"
                        placeholder="e.g. MOD-CT" style="text-transform:uppercase" required>
                    <div class="field-hint">Must be unique</div>
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Description --}}
                <div class="col-12">
                    <div class="field-label">Description</div>
                    <textarea name="description" class="form-control form-control-sm" rows="2"
                        placeholder="Brief description of this modality...">{{ old('description', $modality?->description) }}</textarea>
                </div>

                {{-- Duration --}}
                <div class="col-md-5">
                    <div class="field-label">Average Duration (minutes)</div>
                    <input type="number" name="average_duration_minutes"
                        class="form-control form-control-sm"
                        value="{{ old('average_duration_minutes', $modality?->average_duration_minutes ?? 30) }}"
                        min="1" max="480">
                </div>

                {{-- Toggles --}}
                <div class="col-12">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="toggle-row">
                                <div>
                                    <label for="requires_contrast">Requires Contrast</label><br>
                                    <small>IV dye needed</small>
                                </div>
                                <input type="hidden" name="requires_contrast" value="0">
                                <input type="checkbox" name="requires_contrast" id="requires_contrast"
                                    value="1" class="form-check-input"
                                    {{ old('requires_contrast', $modality?->requires_contrast) ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="toggle-row">
                                <div>
                                    <label for="requires_preparation">Requires Preparation</label><br>
                                    <small>Fasting, bowel prep etc.</small>
                                </div>
                                <input type="hidden" name="requires_preparation" value="0">
                                <input type="checkbox" name="requires_preparation" id="requires_preparation"
                                    value="1" class="form-check-input"
                                    {{ old('requires_preparation', $modality?->requires_preparation) ? 'checked' : '' }}
                                    onchange="togglePrepField(this)">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="toggle-row">
                                <div>
                                    <label for="is_active">Active</label><br>
                                    <small>Available for ordering</small>
                                </div>
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="is_active"
                                    value="1" class="form-check-input"
                                    {{ old('is_active', $modality ? $modality->is_active : true) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Preparation Instructions --}}
                <div class="col-12" id="prepField"
                    style="display:{{ old('requires_preparation', $modality?->requires_preparation) ? 'block' : 'none' }}">
                    <div class="field-label">Preparation Instructions</div>
                    <textarea name="preparation_instructions" class="form-control form-control-sm" rows="3"
                        placeholder="Patient preparation steps before scan...">{{ old('preparation_instructions', $modality?->preparation_instructions) }}</textarea>
                </div>

                {{-- Buttons --}}
                <div class="col-12 d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-primary px-4" style="font-size:13px">
                        <i class="bi bi-check-lg me-1"></i>{{ $btnLabel }}
                    </button>
                    <a href="{{ route('radiology.modalities.index') }}"
                        class="btn btn-outline-secondary px-3" style="font-size:13px">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function togglePrepField(cb) {
        document.getElementById('prepField').style.display = cb.checked ? 'block' : 'none';
    }
</script>
@endpush