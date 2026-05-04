@extends('layouts.master')

@section('title', 'Dr. ' . ($doctor->employee?->first_name . ' ' . $doctor->employee?->last_name))
@section('page-title', 'Doctor Profile')
@section('breadcrumb', 'Home / Doctors / ' . $doctor->doctor_id)

@section('content')

@php
    $emp      = $doctor->employee;
    $fullName = $emp ? $emp->first_name . ' ' . $emp->last_name : '—';
    $initials = $emp ? strtoupper(substr($emp->first_name,0,1).substr($emp->last_name,0,1)) : 'DR';
    $photo    = $emp?->photo ? asset('storage/'.$emp->photo) : null;
    $days     = $doctor->available_days ?? [];
@endphp

<div class="row g-3">

    {{-- LEFT: Profile Card --}}
    <div class="col-12 col-lg-4">

        {{-- Profile --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:24px;text-align:center;margin-bottom:12px">

            @if($photo)
                <img src="{{ $photo }}" alt="{{ $fullName }}"
                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin:0 auto 12px;display:block;border:3px solid #e2e8f0">
            @else
                <div style="width:80px;height:80px;border-radius:50%;background:#dbeafe;color:#1d4ed8;font-size:26px;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                    {{ $initials }}
                </div>
            @endif

            <div style="font-size:17px;font-weight:600;color:#1e293b">Dr. {{ $fullName }}</div>
            <div style="font-size:13px;color:#6366f1;font-weight:500;margin-top:2px">{{ $doctor->specialization }}</div>
            <div style="font-size:12px;color:#94a3b8;margin-top:2px">{{ $doctor->doctor_id }}</div>

            {{-- Badges --}}
            <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
                @php $avClass = str_replace(' ', '-', $doctor->availability); @endphp
                <span style="font-size:11px;padding:3px 10px;border-radius:20px;font-weight:600;
                    {{ $doctor->availability == 'Available'       ? 'background:#dcfce7;color:#166534' :
                      ($doctor->availability == 'In Consultation' ? 'background:#dbeafe;color:#1e40af' :
                      ($doctor->availability == 'On Leave'        ? 'background:#fef9c3;color:#854d0e' :
                       'background:#fee2e2;color:#991b1b')) }}">
                    {{ $doctor->availability }}
                </span>
                <span style="font-size:11px;padding:3px 10px;border-radius:6px;background:#f5f3ff;color:#6d28d9;font-weight:500">
                    {{ $doctor->doctor_type }}
                </span>
                @if($emp?->shift)
                <span style="font-size:11px;padding:3px 10px;border-radius:6px;background:#f1f5f9;color:#475569;font-weight:500">
                    {{ $emp->shift }}
                </span>
                @endif
            </div>

            {{-- Available Days --}}
            @if(count($days))
            <div class="d-flex justify-content-center gap-1 mt-3 flex-wrap">
                @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $d)
                    <span style="font-size:10px;padding:2px 7px;border-radius:4px;font-weight:600;
                        {{ in_array($d, $days) ? 'background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe' : 'background:#f8fafc;color:#cbd5e1;border:1px solid #f1f5f9' }}">
                        {{ $d }}
                    </span>
                @endforeach
            </div>
            @endif

            {{-- Actions --}}
            <div class="d-grid gap-2 mt-4">
                <a href="{{ route('doctors.edit', $doctor->id) }}"
                   class="btn btn-sm btn-outline-primary" style="font-size:13px">
                    <i class="bi bi-pencil me-1"></i>Edit profile
                </a>
                <form method="POST" action="{{ route('doctors.destroy', $doctor->id) }}"
                      onsubmit="return confirm('Remove Dr. {{ $fullName }}?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger w-100" style="font-size:13px">
                        <i class="bi bi-trash me-1"></i>Remove doctor
                    </button>
                </form>
            </div>
        </div>

        {{-- Doctor Details --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:12px">
            <div style="padding:12px 16px;border-bottom:1px solid #e2e8f0;font-size:12px;font-weight:600;color:#374151;background:#f8fafc">
                <i class="bi bi-clipboard2-pulse me-2 text-danger"></i>Clinical details
            </div>
            @php
                $clinicalRows = [
                    ['icon'=>'bi-award',           'label'=>'Qualification',  'value'=> $doctor->qualification],
                    ['icon'=>'bi-patch-check',      'label'=>'PMDC',           'value'=> $doctor->pmdc_number ?? '—'],
                    ['icon'=>'bi-building',         'label'=>'Department',     'value'=> ($emp?->department ?? '—') . ($doctor->sub_department ? ' / '.$doctor->sub_department : '')],
                    ['icon'=>'bi-cash',             'label'=>'Fee',            'value'=> 'Rs ' . number_format($doctor->consultation_fee, 0)],
                    ['icon'=>'bi-hourglass-split',  'label'=>'Avg. mins',      'value'=> $doctor->avg_consultation_mins . ' mins'],
                    ['icon'=>'bi-person-plus',      'label'=>'New patients',   'value'=> $doctor->accepts_new_patients ? 'Accepted' : 'Not accepting'],
                ];
            @endphp
            @foreach($clinicalRows as $row)
            <div style="display:flex;align-items:center;padding:9px 16px;border-bottom:1px solid #f1f5f9;font-size:13px">
                <i class="bi {{ $row['icon'] }}" style="width:20px;color:#94a3b8;flex-shrink:0"></i>
                <span style="min-width:90px;color:#94a3b8;margin-left:6px">{{ $row['label'] }}</span>
                <span style="color:#1e293b;font-weight:500">{{ $row['value'] }}</span>
            </div>
            @endforeach
        </div>

        {{-- Employee Details --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden">
            <div style="padding:12px 16px;border-bottom:1px solid #e2e8f0;font-size:12px;font-weight:600;color:#374151;background:#f8fafc">
                <i class="bi bi-person-badge me-2 text-primary"></i>Employee info
            </div>
            @php
                $empRows = [
                    ['icon'=>'bi-telephone',   'label'=>'Phone',   'value'=> $emp?->personal_phone ?? '—'],
                    ['icon'=>'bi-envelope',    'label'=>'Email',   'value'=> $emp?->office_email ?? $emp?->personal_email ?? '—'],
                    ['icon'=>'bi-person-vcard','label'=>'CNIC',    'value'=> $emp?->cnic ?? '—'],
                    ['icon'=>'bi-gender-ambiguous','label'=>'Gender','value'=> $emp?->gender ?? '—'],
                    ['icon'=>'bi-clock',       'label'=>'Shift',   'value'=> ($emp?->shift ?? '—') . ($emp?->shift_start ? ' (' . \Carbon\Carbon::parse($emp->shift_start)->format('h:i A') . ' – ' . \Carbon\Carbon::parse($emp->shift_end)->format('h:i A') . ')' : '')],
                    ['icon'=>'bi-calendar',    'label'=>'Joined',  'value'=> $emp?->joining_date?->format('d M Y') ?? '—'],
                ];
            @endphp
            @foreach($empRows as $row)
            <div style="display:flex;align-items:center;padding:9px 16px;border-bottom:1px solid #f1f5f9;font-size:13px">
                <i class="bi {{ $row['icon'] }}" style="width:20px;color:#94a3b8;flex-shrink:0"></i>
                <span style="min-width:90px;color:#94a3b8;margin-left:6px">{{ $row['label'] }}</span>
                <span style="color:#1e293b;font-weight:500">{{ $row['value'] }}</span>
            </div>
            @endforeach
            {{-- Link to employee profile --}}
            <div style="padding:10px 16px">
                <a href="{{ route('employees.show', $emp?->id) }}"
                   style="font-size:12px;color:#3b82f6;text-decoration:none">
                    <i class="bi bi-box-arrow-up-right me-1"></i>View full employee profile
                </a>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-12 col-lg-8">

        {{-- Stats --}}
        <div class="row g-3 mb-3">
            <div class="col-4">
                <div style="background:#eff6ff;border-radius:10px;padding:16px;text-align:center">
                    <div style="font-size:26px;font-weight:700;color:#1d4ed8">{{ $stats['total_patients'] }}</div>
                    <div style="font-size:12px;color:#3b82f6;margin-top:2px">Total patients</div>
                </div>
            </div>
            <div class="col-4">
                <div style="background:#f0fdf4;border-radius:10px;padding:16px;text-align:center">
                    <div style="font-size:26px;font-weight:700;color:#16a34a">{{ $stats['total_appointments'] }}</div>
                    <div style="font-size:12px;color:#22c55e;margin-top:2px">Total appointments</div>
                </div>
            </div>
            <div class="col-4">
                <div style="background:#fefce8;border-radius:10px;padding:16px;text-align:center">
                    <div style="font-size:26px;font-weight:700;color:#ca8a04">{{ $stats['today_appointments'] }}</div>
                    <div style="font-size:12px;color:#eab308;margin-top:2px">Today</div>
                </div>
            </div>
        </div>

        {{-- Bio --}}
        @if($doctor->bio)
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:12px">
            <div style="font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px">
                About
            </div>
            <p style="font-size:13px;color:#374151;line-height:1.7;margin:0">{{ $doctor->bio }}</p>
        </div>
        @endif

        {{-- Clinical Notes (internal) --}}
        @if($doctor->clinical_notes)
        <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:16px;margin-bottom:12px">
            <div style="font-size:12px;font-weight:600;color:#92400e;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px">
                <i class="bi bi-lock me-1"></i>Clinical notes (internal)
            </div>
            <p style="font-size:13px;color:#78350f;line-height:1.7;margin:0">{{ $doctor->clinical_notes }}</p>
        </div>
        @endif

        {{-- Recent Patients --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden">
            <div style="padding:14px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between">
                <span style="font-size:13px;font-weight:600;color:#1e293b">
                    <i class="bi bi-people me-2 text-primary"></i>Assigned patients
                </span>
                <a href="{{ route('patients.index', ['doctor_id' => $doctor->id]) }}"
                   style="font-size:12px;color:#3b82f6;text-decoration:none">View all</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0" style="font-size:13px">
                    <thead style="background:#f8fafc">
                        <tr>
                            <th style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;padding:9px 16px;border-bottom:1px solid #e2e8f0">MRN</th>
                            <th style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;padding:9px 16px;border-bottom:1px solid #e2e8f0">Patient</th>
                            <th style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;padding:9px 16px;border-bottom:1px solid #e2e8f0">Type</th>
                            <th style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;padding:9px 16px;border-bottom:1px solid #e2e8f0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctor->patients->take(8) as $patient)
                        <tr>
                            <td style="padding:10px 16px;border-bottom:1px solid #f1f5f9">
                                <code style="font-size:11px;color:#6366f1">{{ $patient->mrn }}</code>
                            </td>
                            <td style="padding:10px 16px;border-bottom:1px solid #f1f5f9">
                                <a href="{{ route('patients.show', $patient->id) }}"
                                   style="color:#1e293b;text-decoration:none;font-weight:500">
                                    {{ $patient->name }}
                                </a>
                            </td>
                            <td style="padding:10px 16px;border-bottom:1px solid #f1f5f9">
                                <span style="font-size:11px;padding:2px 8px;border-radius:6px;font-weight:500;
                                    {{ $patient->patient_type == 'OPD' ? 'background:#eff6ff;color:#1d4ed8' :
                                      ($patient->patient_type == 'IPD' ? 'background:#f0fdf4;color:#15803d' :
                                       'background:#fff7ed;color:#c2410c') }}">
                                    {{ $patient->patient_type }}
                                </span>
                            </td>
                            <td style="padding:10px 16px;border-bottom:1px solid #f1f5f9">
                                <span style="font-size:11px;padding:2px 8px;border-radius:20px;font-weight:500;
                                    {{ $patient->status == 'Active' ? 'background:#dcfce7;color:#166534' :
                                      ($patient->status == 'Admitted' ? 'background:#dbeafe;color:#1e40af' :
                                       'background:#f3f4f6;color:#374151') }}">
                                    {{ $patient->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:28px;color:#94a3b8;font-size:13px">
                                No patients assigned yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection