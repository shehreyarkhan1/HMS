@extends('layouts.master')

@section('title', 'Edit Ward')
@section('page-title', 'Edit Ward')
@section('breadcrumb', 'Home / Wards / ' . $ward->ward_code . ' / Edit')

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
</style>
@endpush

@section('content')

<form method="POST" action="{{ route('wards.update', $ward->id) }}">
@csrf @method('PUT')

<div class="row g-3">
    <div class="col-12 col-lg-8">
        <div class="form-card">
            <div class="form-card-header">
                <h6><i class="bi bi-building me-2 text-primary"></i>Ward information</h6>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Ward name *</label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $ward->name) }}">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Type *</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror">
                            @foreach(['General','ICU','CCU','NICU','Surgical','Maternity','Pediatric','Orthopedic','Private','Semi-Private'] as $t)
                                <option value="{{ $t }}" {{ old('type',$ward->type) == $t ? 'selected':'' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Floor</label>
                        <select name="floor" class="form-select">
                            <option value="">None</option>
                            @foreach(['Ground','1st','2nd','3rd','4th','5th'] as $f)
                                <option value="{{ $f }}" {{ old('floor',$ward->floor) == $f ? 'selected':'' }}>{{ $f }} Floor</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Block</label>
                        <select name="block" class="form-select">
                            <option value="">None</option>
                            @foreach(['A','B','C','D','E'] as $b)
                                <option value="{{ $b }}" {{ old('block',$ward->block) == $b ? 'selected':'' }}>Block {{ $b }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Bed charges (Rs/day)</label>
                        <input type="number" name="bed_charges" class="form-control"
                               value="{{ old('bed_charges', $ward->bed_charges) }}" min="0" step="50">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="2" class="form-control">{{ old('description', $ward->description) }}</textarea>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                                   {{ old('is_active', $ward->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active" style="font-size:13px">Ward is active</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="form-card" style="border-color:#fde68a">
            <div class="form-card-body" style="background:#fffbeb">
                <div style="font-size:12px;color:#92400e;font-weight:600">Ward code</div>
                <div style="font-size:22px;font-weight:700;color:#b45309">{{ $ward->ward_code }}</div>
                <div style="font-size:11px;color:#d97706">Cannot be changed</div>
                <div style="margin-top:12px;font-size:12px;color:#92400e">
                    Total beds: <strong>{{ $ward->total_beds }}</strong><br>
                    <span style="font-size:11px;color:#d97706">Add beds from ward detail page</span>
                </div>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-warning" style="height:42px;font-size:14px;font-weight:500">
                <i class="bi bi-save me-2"></i>Update ward
            </button>
            <a href="{{ route('wards.show', $ward->id) }}"
               class="btn btn-outline-secondary" style="height:42px;font-size:14px">Cancel</a>
        </div>
    </div>
</div>

</form>
@endsection