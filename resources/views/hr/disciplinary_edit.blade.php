@extends('layouts.master')
@section('title', 'Edit Disciplinary Action')
@section('page-title', 'Edit Disciplinary Action')
@section('breadcrumb', 'HR / Disciplinary / Edit')

@push('styles')
<style>
    .form-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;margin-bottom:1rem}
    .form-card-header{padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px}
    .form-card-body{padding:1.5rem}
    .form-label{font-size:12px;font-weight:600;color:#64748b;margin-bottom:.4rem}
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-semibold" style="color:#1e293b">Edit Disciplinary Action</h5>
        <p class="mb-0" style="font-size:13px;color:#94a3b8">
            {{ $disciplinary->action_number }} &middot;
            {{ $disciplinary->employee->first_name ?? '' }} {{ $disciplinary->employee->last_name ?? '' }}
        </p>
    </div>
    <a href="{{ route('hr.disciplinary.show', $disciplinary) }}" class="btn btn-light btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<form action="{{ route('hr.disciplinary.update', $disciplinary) }}" method="POST">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-12 col-lg-8">

            <div class="form-card">
                <div class="form-card-header">
                    <div style="width:36px;height:36px;border-radius:9px;background:#fef3c7;color:#b45309;display:flex;align-items:center;justify-content:center">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:600;color:#1e293b">Update Action</div>
                        <div style="font-size:12px;color:#94a3b8">Edit status, details and notes</div>
                    </div>
                </div>
                <div class="form-card-body">
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                @foreach(['Issued','Acknowledged','Under Review','Resolved','Escalated','Closed'] as $s)
                                    <option {{ old('status', $disciplinary->status) == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Incident Description <span class="text-danger">*</span></label>
                            <textarea name="incident_description" class="form-control" rows="3" required>{{ old('incident_description', $disciplinary->incident_description) }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Action Details <span class="text-danger">*</span></label>
                            <textarea name="action_details" class="form-control" rows="3" required>{{ old('action_details', $disciplinary->action_details) }}</textarea>
                        </div>

                        <div class="col-6">
                            <label class="form-label">Response Deadline</label>
                            <input type="date" name="response_deadline" class="form-control"
                                   value="{{ old('response_deadline', $disciplinary->response_deadline) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes', $disciplinary->notes) }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="col-12 col-lg-4">
            {{-- Read-only info --}}
            <div class="form-card mb-3">
                <div class="form-card-header">
                    <div style="width:36px;height:36px;border-radius:9px;background:#eff6ff;color:#1d4ed8;display:flex;align-items:center;justify-content:center">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div><div style="font-size:14px;font-weight:600;color:#1e293b">Case Info</div></div>
                </div>
                <div style="padding:1rem 1.25rem;font-size:13px;color:#374151">
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color:#94a3b8">Action Type</span>
                        <strong>{{ $disciplinary->action_type }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span style="color:#94a3b8">Incident</span>
                        <strong>{{ $disciplinary->incident_type }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span style="color:#94a3b8">Issued</span>
                        <strong>{{ \Carbon\Carbon::parse($disciplinary->action_date)->format('d M Y') }}</strong>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('hr.disciplinary.show', $disciplinary) }}" class="btn btn-light btn-sm flex-fill">Cancel</a>
                <button type="submit" class="btn btn-warning btn-sm text-white flex-fill">
                    <i class="bi bi-check-lg me-1"></i>Update
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
