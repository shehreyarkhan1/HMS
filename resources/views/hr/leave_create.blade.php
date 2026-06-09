@extends('layouts.master')
@section('title', 'New Leave Request')
@section('page-title', 'New Leave Request')
@section('breadcrumb', 'HR / Leaves / New')

@push('styles')
<style>
    .form-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden}
    .form-card-header{padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px}
    .form-card-body{padding:1.5rem}
    .form-label{font-size:12px;font-weight:600;color:#64748b;margin-bottom:.4rem}
    .balance-card{background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;padding:1rem}
    .bal-item{display:flex;justify-content:space-between;align-items:center;padding:.4rem 0;border-bottom:1px solid #f1f5f9}
    .bal-item:last-child{border-bottom:none}
    .bal-label{font-size:12px;color:#64748b}
    .bal-val{font-size:13px;font-weight:700;color:#1e293b}
    .bal-avail{color:#16a34a}
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-semibold" style="color:#1e293b">New Leave Request</h5>
        <p class="mb-0" style="font-size:13px;color:#94a3b8">Submit a leave application for an employee</p>
    </div>
    <a href="{{ route('hr.leaves.index') }}" class="btn btn-light btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<form action="{{ route('hr.leaves.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row g-3">
    {{-- LEFT --}}
    <div class="col-12 col-lg-8">
        <div class="form-card">
            <div class="form-card-header">
                <div style="width:36px;height:36px;border-radius:9px;background:#eff6ff;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:16px">
                    <i class="bi bi-calendar-heart"></i>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:600;color:#1e293b">Leave Details</div>
                    <div style="font-size:12px;color:#94a3b8">Fill in the leave application information</div>
                </div>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    {{-- Employee --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required id="employeeSelect">
                            <option value="">Select employee…</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}"
                                    {{ old('employee_id', $selectedEmployee?->id) == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Leave Type --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                        <select name="leave_type_id" class="form-select @error('leave_type_id') is-invalid @enderror" required>
                            <option value="">Select type…</option>
                            @foreach($leaveTypes as $lt)
                                <option value="{{ $lt->id }}" {{ old('leave_type_id') == $lt->id ? 'selected' : '' }}>
                                    {{ $lt->name }} ({{ $lt->days_per_year }} days/yr)
                                </option>
                            @endforeach
                        </select>
                        @error('leave_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Dates --}}
                    <div class="col-6">
                        <label class="form-label">From Date <span class="text-danger">*</span></label>
                        <input type="date" name="from_date" class="form-control @error('from_date') is-invalid @enderror"
                               value="{{ old('from_date') }}" required>
                        @error('from_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label">To Date <span class="text-danger">*</span></label>
                        <input type="date" name="to_date" class="form-control @error('to_date') is-invalid @enderror"
                               value="{{ old('to_date') }}" required>
                        @error('to_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Half day --}}
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="half_day" value="1" id="halfDay"
                                   {{ old('half_day') ? 'checked' : '' }}>
                            <label class="form-check-label" for="halfDay" style="font-size:13px">Half Day Leave</label>
                        </div>
                    </div>
                    <div class="col-12" id="halfDayTypeWrap" style="{{ old('half_day') ? '' : 'display:none' }}">
                        <label class="form-label">Half Day Type</label>
                        <select name="half_day_type" class="form-select">
                            <option value="Morning" {{ old('half_day_type') == 'Morning' ? 'selected' : '' }}>Morning</option>
                            <option value="Afternoon" {{ old('half_day_type') == 'Afternoon' ? 'selected' : '' }}>Afternoon</option>
                        </select>
                    </div>

                    {{-- Reason --}}
                    <div class="col-12">
                        <label class="form-label">Reason <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3"
                                  placeholder="Brief reason for leave…" required>{{ old('reason') }}</textarea>
                        @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Document --}}
                    <div class="col-12">
                        <label class="form-label">Supporting Document <small class="text-muted">(PDF / Image, max 2MB)</small></label>
                        <input type="file" name="document_path" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT — Balance info --}}
    <div class="col-12 col-lg-4">
        @if($selectedEmployee)
        <div class="form-card mb-3">
            <div class="form-card-header">
                <div style="width:36px;height:36px;border-radius:9px;background:#f0fdf4;color:#16a34a;display:flex;align-items:center;justify-content:center;font-size:16px">
                    <i class="bi bi-bar-chart"></i>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:600;color:#1e293b">Leave Balances</div>
                    <div style="font-size:12px;color:#94a3b8">{{ $selectedEmployee->first_name }}</div>
                </div>
            </div>
            <div style="padding:1rem 1.25rem">
                @forelse($selectedEmployee->leaveBalances as $bal)
                <div class="balance-card mb-2">
                    <div style="font-size:12px;font-weight:700;color:#1e293b;margin-bottom:.5rem">
                        {{ $bal->leaveType->name ?? '—' }}
                    </div>
                    <div class="bal-item">
                        <span class="bal-label">Allocated</span>
                        <span class="bal-val">{{ $bal->allocated_days }}</span>
                    </div>
                    <div class="bal-item">
                        <span class="bal-label">Used</span>
                        <span class="bal-val">{{ $bal->used_days }}</span>
                    </div>
                    <div class="bal-item">
                        <span class="bal-label">Remaining</span>
                        <span class="bal-val bal-avail">{{ $bal->remaining_days }}</span>
                    </div>
                </div>
                @empty
                    <p style="font-size:12px;color:#94a3b8;text-align:center;padding:.5rem 0">No balances configured</p>
                @endforelse
            </div>
        </div>
        @endif

        <div style="background:#eff6ff;border-radius:10px;padding:1rem;border:1px solid #bfdbfe">
            <div style="font-size:12px;font-weight:600;color:#1d4ed8;margin-bottom:.5rem">
                <i class="bi bi-info-circle me-1"></i> Note
            </div>
            <ul style="font-size:12px;color:#1e40af;margin:0;padding-left:1.2rem">
                <li>Fri & Sat are weekends (not counted)</li>
                <li>Overlapping requests are blocked</li>
                <li>Balance is checked before submission</li>
            </ul>
        </div>
    </div>

    {{-- Footer --}}
    <div class="col-12">
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('hr.leaves.index') }}" class="btn btn-light btn-sm">Cancel</a>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-send me-1"></i> Submit Request
            </button>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('halfDay').addEventListener('change', function() {
    document.getElementById('halfDayTypeWrap').style.display = this.checked ? '' : 'none';
});
</script>
@endpush
