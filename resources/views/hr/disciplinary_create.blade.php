@extends('layouts.master')
@section('title', 'New Disciplinary Action')
@section('page-title', 'New Disciplinary Action')
@section('breadcrumb', 'HR / Disciplinary / New')

@push('styles')
    <style>
        .form-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-bottom: 1rem
        }

        .form-card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 10px
        }

        .form-card-body {
            padding: 1.5rem
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: .4rem
        }
    </style>
@endpush

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 class="mb-0 fw-semibold" style="color:#1e293b">New Disciplinary Action</h5>
            <p class="mb-0" style="font-size:13px;color:#94a3b8">Issue a formal disciplinary action against an employee</p>
        </div>
        <a href="{{ route('hr.disciplinary.index') }}" class="btn btn-light btn-sm"><i
                class="bi bi-arrow-left me-1"></i>Back</a>
    </div>

    <form action="{{ route('hr.disciplinary.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-12 col-lg-8">

                {{-- Incident --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div
                            style="width:36px;height:36px;border-radius:9px;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-exclamation-triangle"></i></div>
                        <div>
                            <div style="font-size:14px;font-weight:600;color:#1e293b">Incident Details</div>
                            <div style="font-size:12px;color:#94a3b8">What happened?</div>
                        </div>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Employee <span class="text-danger">*</span></label>
                                <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror"
                                    required>
                                    <option value="">Select employee…</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}"
                                            {{ old('employee_id', $selectedEmployee?->id) == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Incident Type <span class="text-danger">*</span></label>
                                <select name="incident_type"
                                    class="form-select @error('incident_type') is-invalid @enderror" required>
                                    <option value="">Select…</option>
                                    @foreach (['Misconduct', 'Insubordination', 'Tardiness', 'Absenteeism', 'Negligence', 'Harassment', 'Fraud', 'Violence', 'Policy Violation', 'Other'] as $t)
                                        <option {{ old('incident_type') == $t ? 'selected' : '' }}>{{ $t }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('incident_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Incident Date <span class="text-danger">*</span></label>
                                <input type="date" name="incident_date"
                                    class="form-control @error('incident_date') is-invalid @enderror"
                                    value="{{ old('incident_date', today()->toDateString()) }}"
                                    max="{{ today()->toDateString() }}" required>
                                @error('incident_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Incident Description <span class="text-danger">*</span></label>
                                <textarea name="incident_description" class="form-control @error('incident_description') is-invalid @enderror"
                                    rows="4" placeholder="Describe the incident in detail…" required>{{ old('incident_description') }}</textarea>
                                @error('incident_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div
                            style="width:36px;height:36px;border-radius:9px;background:#fef3c7;color:#b45309;display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-shield-exclamation"></i></div>
                        <div>
                            <div style="font-size:14px;font-weight:600;color:#1e293b">Action Taken</div>
                            <div style="font-size:12px;color:#94a3b8">What action is being taken?</div>
                        </div>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label">Action Type <span class="text-danger">*</span></label>
                                <select name="action_type" class="form-select @error('action_type') is-invalid @enderror"
                                    required id="actionType">
                                    <option value="">Select…</option>
                                    @foreach (['Verbal Warning', 'Written Warning', 'Show Cause Notice', 'Suspension', 'Demotion', 'Salary Deduction', 'Termination', 'Other'] as $t)
                                        <option {{ old('action_type') == $t ? 'selected' : '' }}>{{ $t }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('action_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Action Date <span class="text-danger">*</span></label>
                                <input type="date" name="action_date" class="form-control"
                                    value="{{ old('action_date', today()->toDateString()) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Action Details <span class="text-danger">*</span></label>
                                <textarea name="action_details" class="form-control" rows="3" required placeholder="Describe the action…">{{ old('action_details') }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Response Deadline</label>
                                <input type="date" name="response_deadline" class="form-control"
                                    value="{{ old('response_deadline') }}">
                            </div>

                            {{-- Suspension fields --}}
                            <div id="suspensionFields" style="{{ old('action_type') == 'Suspension' ? '' : 'display:none' }}"
                                class="col-12">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Suspension From <span class="text-danger">*</span></label>
                                        <input type="date" name="suspension_from" class="form-control"
                                            value="{{ old('suspension_from') }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Suspension To <span class="text-danger">*</span></label>
                                        <input type="date" name="suspension_to" class="form-control"
                                            value="{{ old('suspension_to') }}">
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="suspension_paid"
                                                value="1" id="suspPaid"
                                                {{ old('suspension_paid') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="suspPaid" style="font-size:12px">Paid
                                                Suspension</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Deduction fields --}}
                            <div id="deductionFields"
                                style="{{ old('action_type') == 'Salary Deduction' ? '' : 'display:none' }}"
                                class="col-12">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Deduction Amount <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">PKR</span>
                                            <input type="number" name="deduction_amount" class="form-control"
                                                value="{{ old('deduction_amount') }}" min="0">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Deduction Month</label>
                                        <input type="text" name="deduction_month" class="form-control"
                                            value="{{ old('deduction_month') }}" placeholder="e.g. June 2025">
                                    </div>
                                </div>
                            </div>

                            {{-- Document --}}
                            <div class="col-12">
                                <label class="form-label">Supporting Document</label>
                                <input type="file" name="document_path" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Internal notes…">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="col-12 col-lg-4">
                <div class="form-card">
                    <div class="form-card-header">
                        <div
                            style="width:36px;height:36px;border-radius:9px;background:#eff6ff;color:#1d4ed8;display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-info-circle"></i></div>
                        <div>
                            <div style="font-size:14px;font-weight:600;color:#1e293b">Guidelines</div>
                        </div>
                    </div>
                    <div style="padding:1rem 1.25rem;font-size:12px;color:#64748b;line-height:1.7">
                        <p><strong style="color:#1e293b">Verbal Warning</strong><br>First offense minor issues</p>
                        <p><strong style="color:#1e293b">Written Warning</strong><br>Second offense or serious issue</p>
                        <p><strong style="color:#1e293b">Show Cause Notice</strong><br>Requires written explanation from
                            employee</p>
                        <p><strong style="color:#1e293b">Suspension</strong><br>Temporary removal from duty</p>
                        <p><strong style="color:#1e293b">Termination</strong><br>End of employment</p>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('hr.disciplinary.index') }}" class="btn btn-light btn-sm flex-fill">Cancel</a>
                    <button type="submit" class="btn btn-danger btn-sm flex-fill"><i
                            class="bi bi-check-lg me-1"></i>Issue Action</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.getElementById('actionType').addEventListener('change', function() {
            document.getElementById('suspensionFields').style.display = this.value === 'Suspension' ? '' : 'none';
            document.getElementById('deductionFields').style.display = this.value === 'Salary Deduction' ? '' :
                'none';
        });
    </script>
@endpush
