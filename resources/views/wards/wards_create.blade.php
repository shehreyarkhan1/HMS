@extends('layouts.master')

@section('title', 'Add Ward')
@section('page-title', 'Add New Ward')
@section('breadcrumb', 'Home / Wards / New')

@push('styles')
<style>
    .form-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:16px}
    .form-card-header{padding:13px 20px;border-bottom:1px solid #e2e8f0;background:#f8fafc}
    .form-card-header h6{margin:0;font-size:13px;font-weight:600;color:#374151}
    .form-card-body{padding:20px}
    .form-label{font-size:12px;font-weight:500;color:#374151;margin-bottom:5px}
    .form-control,.form-select{font-size:13px;border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;color:#1e293b}
    .form-control:focus,.form-select:focus{border-color:#93c5fd;box-shadow:0 0 0 3px rgba(59,130,246,.1)}
    .form-control.is-invalid,.form-select.is-invalid{border-color:#f87171}
    .invalid-feedback{font-size:11px}
    .required-star{color:#ef4444;margin-left:2px}

    .ward-type-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:8px}
    .ward-type-btn{border:1.5px solid #e2e8f0;border-radius:8px;padding:10px 8px;text-align:center;cursor:pointer;transition:all .15s;font-size:12px;font-weight:500;color:#64748b;background:#fff}
    .ward-type-btn:hover{border-color:#93c5fd;color:#1d4ed8}
    .ward-type-btn.selected{border-color:#1d4ed8;background:#eff6ff;color:#1d4ed8}
    .ward-type-btn i{display:block;font-size:18px;margin-bottom:4px}
</style>
@endpush

@section('content')

<form method="POST" action="{{ route('wards.store') }}">
@csrf

<div class="row g-3">

    <div class="col-12 col-lg-8">

        {{-- Ward Type --}}
        <div class="form-card">
            <div class="form-card-header">
                <h6><i class="bi bi-building me-2 text-primary"></i>Ward type</h6>
            </div>
            <div class="form-card-body">
                @php
                    $types = [
                        ['value'=>'General',      'icon'=>'bi-hospital',         'label'=>'General'],
                        ['value'=>'ICU',           'icon'=>'bi-heart-pulse',      'label'=>'ICU'],
                        ['value'=>'CCU',           'icon'=>'bi-activity',         'label'=>'CCU'],
                        ['value'=>'NICU',          'icon'=>'bi-person-hearts',    'label'=>'NICU'],
                        ['value'=>'Surgical',      'icon'=>'bi-scissors',         'label'=>'Surgical'],
                        ['value'=>'Maternity',     'icon'=>'bi-gender-female',    'label'=>'Maternity'],
                        ['value'=>'Pediatric',     'icon'=>'bi-emoji-smile',      'label'=>'Pediatric'],
                        ['value'=>'Orthopedic',    'icon'=>'bi-bandaid',          'label'=>'Orthopedic'],
                        ['value'=>'Private',       'icon'=>'bi-star',             'label'=>'Private'],
                        ['value'=>'Semi-Private',  'icon'=>'bi-star-half',        'label'=>'Semi-Private'],
                    ];
                @endphp
                <div class="ward-type-grid">
                    @foreach($types as $t)
                    <div class="ward-type-btn {{ old('type') == $t['value'] ? 'selected' : '' }}"
                         onclick="selectType('{{ $t['value'] }}', this)">
                        <i class="bi {{ $t['icon'] }}"></i>
                        {{ $t['label'] }}
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="type" id="ward_type" value="{{ old('type') }}">
                @error('type') <div class="text-danger mt-2" style="font-size:11px">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Basic Info --}}
        <div class="form-card">
            <div class="form-card-header">
                <h6><i class="bi bi-info-circle me-2 text-success"></i>Ward information</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Ward name <span class="required-star">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="e.g. General Male Ward A">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Total beds <span class="required-star">*</span></label>
                        <input type="number" name="total_beds"
                               class="form-control @error('total_beds') is-invalid @enderror"
                               value="{{ old('total_beds', 10) }}" min="1" max="500">
                        @error('total_beds') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Floor</label>
                        <select name="floor" class="form-select @error('floor') is-invalid @enderror">
                            <option value="">Select floor</option>
                            @foreach(['Ground','1st','2nd','3rd','4th','5th'] as $f)
                                <option value="{{ $f }}" {{ old('floor') == $f ? 'selected' : '' }}>{{ $f }} Floor</option>
                            @endforeach
                        </select>
                        @error('floor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Block</label>
                        <select name="block" class="form-select @error('block') is-invalid @enderror">
                            <option value="">Select block</option>
                            @foreach(['A','B','C','D','E'] as $b)
                                <option value="{{ $b }}" {{ old('block') == $b ? 'selected' : '' }}>Block {{ $b }}</option>
                            @endforeach
                        </select>
                        @error('block') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Bed charges <span style="color:#94a3b8">(per day Rs)</span></label>
                        <input type="number" name="bed_charges"
                               class="form-control @error('bed_charges') is-invalid @enderror"
                               value="{{ old('bed_charges', 0) }}" min="0" step="50">
                        @error('bed_charges') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="2"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Optional ward description...">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-12 col-lg-4">

        <div class="form-card" style="border-color:#bfdbfe">
            <div class="form-card-header" style="background:#eff6ff">
                <h6 style="color:#1d4ed8"><i class="bi bi-magic me-2"></i>Auto setup</h6>
            </div>
            <div class="form-card-body" style="font-size:13px;color:#374151;line-height:2">
                <div><i class="bi bi-check-circle text-success me-2"></i>Ward code auto-generated</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>Beds auto-created</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>All beds start Available</div>
                <div><i class="bi bi-check-circle text-success me-2"></i>ICU beds get ICU type</div>
            </div>
        </div>

        {{-- Preview --}}
        <div class="form-card">
            <div class="form-card-header">
                <h6><i class="bi bi-eye me-2"></i>Preview</h6>
            </div>
            <div class="form-card-body" id="preview" style="font-size:13px;color:#374151">
                <div style="color:#94a3b8">Fill form to see preview...</div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary" style="height:42px;font-size:14px;font-weight:500">
                <i class="bi bi-building-add me-2"></i>Create ward
            </button>
            <a href="{{ route('wards.index') }}"
               class="btn btn-outline-secondary" style="height:42px;font-size:14px">Cancel</a>
        </div>
    </div>
</div>

</form>
@endsection

@push('scripts')
<script>
function selectType(val, el) {
    document.querySelectorAll('.ward-type-btn').forEach(b => b.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('ward_type').value = val;
    updatePreview();
}

function updatePreview() {
    const name  = document.querySelector('[name=name]').value || '—';
    const beds  = document.querySelector('[name=total_beds]').value || '0';
    const type  = document.getElementById('ward_type').value || '—';
    const fee   = document.querySelector('[name=bed_charges]').value || '0';
    const floor = document.querySelector('[name=floor]').value || '—';

    document.getElementById('preview').innerHTML = `
        <div style="margin-bottom:6px"><strong>${name}</strong></div>
        <div style="color:#64748b">Type: ${type}</div>
        <div style="color:#64748b">Beds: ${beds}</div>
        <div style="color:#64748b">Floor: ${floor}</div>
        <div style="color:#16a34a;font-weight:500">Rs ${parseInt(fee).toLocaleString()}/day</div>
    `;
}

document.querySelectorAll('[name=name],[name=total_beds],[name=bed_charges],[name=floor]')
        .forEach(el => el.addEventListener('input', updatePreview));
document.querySelector('[name=floor]').addEventListener('change', updatePreview);
</script>
@endpush