@extends('layouts.master')

@section('title', $employee->full_name)
@section('page-title', 'Employee Profile')
@section('breadcrumb', 'Home / Employees / ' . $employee->employee_id)

@push('styles')
<style>
    .info-section{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:12px}
    .info-section-header{padding:12px 16px;border-bottom:1px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;gap:8px;font-size:12px;font-weight:600;color:#374151}
    .info-section-icon{width:24px;height:24px;border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0}
    .info-row{display:flex;padding:9px 16px;border-bottom:1px solid #f1f5f9;font-size:13px}
    .info-row:last-child{border-bottom:none}
    .info-label{min-width:130px;color:#94a3b8;flex-shrink:0}
    .info-value{color:#1e293b;font-weight:500}

    .stat-box{border-radius:10px;padding:14px;text-align:center}
    .stat-box-value{font-size:22px;font-weight:700;line-height:1}
    .stat-box-label{font-size:11px;margin-top:4px;font-weight:500}
</style>
@endpush

@section('content')

<div class="row g-3">

    {{-- ── LEFT COLUMN ── --}}
    <div class="col-12 col-lg-4">

        {{-- Profile Card --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:24px;text-align:center;margin-bottom:12px">
            @if($employee->photo_url)
                <img src="{{ $employee->photo_url }}" alt="{{ $employee->full_name }}"
                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin:0 auto 12px;display:block;border:3px solid #e2e8f0">
            @else
                <div style="width:80px;height:80px;border-radius:50%;background:#dbeafe;color:#1d4ed8;font-size:26px;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                    {{ $employee->initials }}
                </div>
            @endif

            <div style="font-size:17px;font-weight:600;color:#1e293b">{{ $employee->full_name }}</div>
            <div style="font-size:13px;color:#6366f1;font-weight:500;margin-top:2px">{{ $employee->designation }}</div>
            <div style="font-size:12px;color:#94a3b8;margin-top:2px">{{ $employee->employee_id }}</div>

            <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
                @php
                    $statusColors = [
                        'Active'     => 'background:#dcfce7;color:#166534',
                        'On Leave'   => 'background:#fef9c3;color:#854d0e',
                        'Suspended'  => 'background:#fee2e2;color:#991b1b',
                        'Terminated' => 'background:#f3f4f6;color:#374151',
                        'Resigned'   => 'background:#f3f4f6;color:#374151',
                        'Retired'    => 'background:#f3f4f6;color:#374151',
                    ];
                    $sc = $statusColors[$employee->employment_status] ?? 'background:#f3f4f6;color:#374151';
                @endphp
                <span style="font-size:11px;padding:3px 10px;border-radius:20px;font-weight:600;{{ $sc }}">
                    {{ $employee->employment_status }}
                </span>
                <span style="font-size:11px;padding:3px 10px;border-radius:6px;background:#f1f5f9;color:#475569;font-weight:500">
                    {{ $employee->employment_type }}
                </span>
            </div>

            {{-- Stats --}}
            <div class="row g-2 mt-3">
                <div class="col-6">
                    <div class="stat-box" style="background:#eff6ff">
                        <div class="stat-box-value" style="color:#1d4ed8">{{ $employee->service_years }}</div>
                        <div class="stat-box-label" style="color:#3b82f6">Years service</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-box" style="background:#f0fdf4">
                        <div class="stat-box-value" style="color:#16a34a">{{ $employee->age ?? '—' }}</div>
                        <div class="stat-box-label" style="color:#22c55e">Age</div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 mt-4">
                <a href="{{ route('employees.edit', $employee->id) }}"
                   class="btn btn-sm btn-outline-primary" style="font-size:13px">
                    <i class="bi bi-pencil me-1"></i>Edit employee
                </a>
                @if($employee->is_doctor ?? false)
                    <a href="{{ route('doctors.show', $employee->doctor->id) }}"
                       class="btn btn-sm btn-outline-info" style="font-size:13px">
                        <i class="bi bi-person-badge me-1"></i>View doctor profile
                    </a>
                @endif
                <form method="POST" action="{{ route('employees.destroy', $employee->id) }}"
                      onsubmit="return confirm('Remove {{ $employee->full_name }}?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger w-100" style="font-size:13px">
                        <i class="bi bi-trash me-1"></i>Remove employee
                    </button>
                </form>
            </div>
        </div>

        {{-- Employment Quick Info --}}
        <div class="info-section">
            <div class="info-section-header">
                <div class="info-section-icon" style="background:#fff7ed">
                    <i class="bi bi-briefcase" style="color:#d97706;font-size:11px"></i>
                </div>
                Employment
            </div>
            @php
                $empRows = [
                    ['Department',   $employee->department],
                    ['Designation',  $employee->designation],
                    ['Job grade',    $employee->job_grade ?? '—'],
                    ['Shift',        $employee->shift],
                    ['Joining date', $employee->joining_date->format('d M Y')],
                    ['Salary',       'Rs ' . number_format($employee->basic_salary, 0) . ' / ' . strtolower($employee->salary_type)],
                    ['System access',$employee->has_system_access ? 'Yes' : 'No'],
                ];
            @endphp
            @foreach($empRows as [$label, $value])
            <div class="info-row">
                <span class="info-label">{{ $label }}</span>
                <span class="info-value">{{ $value }}</span>
            </div>
            @endforeach
        </div>

    </div>

    {{-- ── RIGHT COLUMN ── --}}
    <div class="col-12 col-lg-8">

        {{-- Personal Info --}}
        <div class="info-section">
            <div class="info-section-header">
                <div class="info-section-icon" style="background:#eff6ff">
                    <i class="bi bi-person" style="color:#1d4ed8;font-size:11px"></i>
                </div>
                Personal information
            </div>
            <div class="row g-0">
                <div class="col-md-6">
                    @php
                        $personalRows = [
                            ['Father\'s name', $employee->father_name ?? '—'],
                            ['Date of birth',  $employee->date_of_birth?->format('d M Y') ?? '—'],
                            ['Gender',         $employee->gender],
                            ['Marital status', $employee->marital_status],
                            ['Blood group',    $employee->blood_group ?? '—'],
                            ['CNIC',           $employee->cnic ?? '—'],
                            ['Nationality',    $employee->nationality],
                        ];
                    @endphp
                    @foreach($personalRows as [$label, $value])
                    <div class="info-row">
                        <span class="info-label">{{ $label }}</span>
                        <span class="info-value">{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="col-md-6">
                    @php
                        $contactRows = [
                            ['Personal phone',  $employee->personal_phone],
                            ['Office phone',    $employee->office_phone ?? '—'],
                            ['Personal email',  $employee->personal_email ?? '—'],
                            ['Office email',    $employee->office_email ?? '—'],
                            ['Emergency',       ($employee->emergency_contact_phone ?? '—') . ' (' . ($employee->emergency_contact_relation ?? '') . ')'],
                            ['City',            $employee->city ?? '—'],
                            ['Province',        $employee->province ?? '—'],
                        ];
                    @endphp
                    @foreach($contactRows as [$label, $value])
                    <div class="info-row">
                        <span class="info-label">{{ $label }}</span>
                        <span class="info-value">{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Education & Experience --}}
        <div class="info-section">
            <div class="info-section-header">
                <div class="info-section-icon" style="background:#fdf4ff">
                    <i class="bi bi-mortarboard" style="color:#7e22ce;font-size:11px"></i>
                </div>
                Education & experience
            </div>
            <div class="row g-0">
                <div class="col-md-6">
                    <div class="info-row"><span class="info-label">Qualification</span><span class="info-value">{{ $employee->highest_qualification ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Institution</span><span class="info-value">{{ $employee->institution ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Grad year</span><span class="info-value">{{ $employee->graduation_year ?? '—' }}</span></div>
                </div>
                <div class="col-md-6">
                    <div class="info-row"><span class="info-label">Experience</span><span class="info-value">{{ $employee->total_experience_years }} years</span></div>
                    <div class="info-row"><span class="info-label">Prev employer</span><span class="info-value">{{ $employee->previous_employer ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Prev designation</span><span class="info-value">{{ $employee->previous_designation ?? '—' }}</span></div>
                </div>
            </div>
        </div>

        {{-- Bank & Salary --}}
        <div class="info-section">
            <div class="info-section-header">
                <div class="info-section-icon" style="background:#f0fdf4">
                    <i class="bi bi-bank" style="color:#15803d;font-size:11px"></i>
                </div>
                Bank & salary
            </div>
            <div class="row g-0">
                <div class="col-md-6">
                    <div class="info-row"><span class="info-label">Bank</span><span class="info-value">{{ $employee->bank_name ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Account no.</span><span class="info-value">{{ $employee->bank_account_number ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Branch</span><span class="info-value">{{ $employee->bank_branch ?? '—' }}</span></div>
                </div>
                <div class="col-md-6">
                    <div class="info-row"><span class="info-label">Basic salary</span><span class="info-value" style="color:#15803d;font-weight:600">Rs {{ number_format($employee->basic_salary, 0) }} / {{ strtolower($employee->salary_type) }}</span></div>
                    <div class="info-row"><span class="info-label">NTN</span><span class="info-value">{{ $employee->ntn_number ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Tax filer</span><span class="info-value">{{ $employee->is_tax_filer ? 'Yes' : 'No' }}</span></div>
                </div>
            </div>
        </div>

        {{-- Working Days --}}
        @if($employee->working_days)
        <div class="info-section">
            <div class="info-section-header">
                <div class="info-section-icon" style="background:#ecfeff">
                    <i class="bi bi-calendar-week" style="color:#0e7490;font-size:11px"></i>
                </div>
                Shift & working days
            </div>
            <div style="padding:14px 16px;display:flex;gap:8px;flex-wrap:wrap;align-items:center">
                <span style="font-size:12px;padding:4px 10px;border-radius:6px;background:#f1f5f9;color:#475569;font-weight:500">
                    {{ $employee->shift }}
                    @if($employee->shift_start)
                        — {{ \Carbon\Carbon::parse($employee->shift_start)->format('h:i A') }}
                        to {{ \Carbon\Carbon::parse($employee->shift_end)->format('h:i A') }}
                    @endif
                </span>
                @foreach($employee->working_days as $day)
                    <span style="font-size:11px;padding:3px 10px;border-radius:6px;background:#eff6ff;color:#1d4ed8;font-weight:600">{{ $day }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Notes --}}
        @if($employee->notes)
        <div class="info-section">
            <div class="info-section-header">
                <div class="info-section-icon" style="background:#f8fafc">
                    <i class="bi bi-journal-text" style="color:#64748b;font-size:11px"></i>
                </div>
                Notes
            </div>
            <div style="padding:14px 16px;font-size:13px;color:#374151;line-height:1.7">
                {{ $employee->notes }}
            </div>
        </div>
        @endif

    </div>
</div>

@endsection