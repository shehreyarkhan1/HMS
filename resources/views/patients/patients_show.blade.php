@extends('layouts.master')

@section('title', $patient->name)
@section('page-title', 'Patient Details')
@section('breadcrumb', 'Home / Patients / ' . $patient->mrn)

@section('content')

<div class="row g-3">

    {{-- LEFT: Patient Profile --}}
    <div class="col-12 col-lg-4">

        {{-- Profile Card --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:24px;text-align:center;margin-bottom:12px">
            <div style="width:64px;height:64px;border-radius:50%;background:#dbeafe;color:#1d4ed8;font-size:22px;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                {{ $patient->initials }}
            </div>
            <div style="font-size:17px;font-weight:600;color:#1e293b">{{ $patient->name }}</div>
            <div style="font-size:13px;color:#94a3b8;margin-top:2px">{{ $patient->mrn }}</div>

            <div class="d-flex justify-content-center gap-2 mt-3">
                <span style="font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500;
                    {{ $patient->status == 'Active' ? 'background:#dcfce7;color:#166534' :
                      ($patient->status == 'Admitted' ? 'background:#dbeafe;color:#1e40af' :
                       'background:#f3f4f6;color:#374151') }}">
                    {{ $patient->status }}
                </span>
                <span style="font-size:11px;padding:3px 10px;border-radius:6px;font-weight:500;
                    {{ $patient->patient_type == 'OPD' ? 'background:#eff6ff;color:#1d4ed8' :
                      ($patient->patient_type == 'IPD' ? 'background:#f0fdf4;color:#15803d' :
                       'background:#fff7ed;color:#c2410c') }}">
                    {{ $patient->patient_type }}
                </span>
            </div>

            <div class="d-grid gap-2 mt-4">
                <a href="{{ route('patients.edit', $patient->id) }}"
                   class="btn btn-sm btn-outline-primary" style="font-size:13px">
                    <i class="bi bi-pencil me-1"></i>Edit patient
                </a>
                <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}"
                   class="btn btn-sm btn-success" style="font-size:13px">
                    <i class="bi bi-calendar-plus me-1"></i>New appointment
                </a>
            </div>
        </div>

        {{-- Info Card --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden">
            <div style="padding:12px 16px;border-bottom:1px solid #e2e8f0;font-size:12px;font-weight:600;color:#374151;background:#f8fafc">
                <i class="bi bi-person me-2 text-primary"></i>Patient info
            </div>
            <div style="padding:4px 0">
                @php
                    $rows = [
                        ['label' => 'Age',            'value' => $patient->age . ' years'],
                        ['label' => 'Date of birth',  'value' => $patient->date_of_birth->format('d M Y')],
                        ['label' => 'Gender',         'value' => $patient->gender],
                        ['label' => 'Blood group',    'value' => $patient->blood_group ?? '—'],
                        ['label' => 'CNIC',           'value' => $patient->cnic ?? '—'],
                        ['label' => 'Phone',          'value' => $patient->phone],
                        ['label' => 'Emergency',      'value' => ($patient->emergency_contact ?? '—') . ' (' . ($patient->emergency_relation ?? '') . ')'],
                        ['label' => 'City',           'value' => $patient->city ?? '—'],
                        ['label' => 'Doctor',         'value' => $patient->doctor->name ?? '— Not assigned —'],
                        ['label' => 'Registered',     'value' => $patient->created_at->format('d M Y')],
                    ];
                @endphp
                @foreach($rows as $row)
                <div style="display:flex;padding:9px 16px;border-bottom:1px solid #f1f5f9;font-size:13px">
                    <span style="min-width:100px;color:#94a3b8">{{ $row['label'] }}</span>
                    <span style="color:#1e293b;font-weight:500">{{ $row['value'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- RIGHT: Tabs Content --}}
    <div class="col-12 col-lg-8">

        {{-- Address & Notes --}}
        @if($patient->address || $patient->notes)
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:12px">
            @if($patient->address)
            <div style="margin-bottom:12px">
                <div style="font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px">
                    Address
                </div>
                <div style="font-size:13px;color:#374151">{{ $patient->address }}</div>
            </div>
            @endif
            @if($patient->notes)
            <div>
                <div style="font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px">
                    Notes
                </div>
                <div style="font-size:13px;color:#374151;background:#fafafa;padding:10px;border-radius:8px;border:1px solid #f1f5f9">
                    {{ $patient->notes }}
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Appointments --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden">
            <div style="padding:14px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between">
                <span style="font-size:13px;font-weight:600;color:#1e293b">
                    <i class="bi bi-calendar2-week me-2 text-primary"></i>Appointment history
                </span>
                <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}"
                   class="btn btn-sm btn-outline-success py-0 px-3" style="font-size:12px">
                    + Add
                </a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0" style="font-size:13px">
                    <thead style="background:#f8fafc">
                        <tr>
                            <th style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;padding:10px 16px;border-bottom:1px solid #e2e8f0">Date</th>
                            <th style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;padding:10px 16px;border-bottom:1px solid #e2e8f0">Doctor</th>
                            <th style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;padding:10px 16px;border-bottom:1px solid #e2e8f0">Department</th>
                            <th style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;padding:10px 16px;border-bottom:1px solid #e2e8f0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patient->appointments as $appt)
                        <tr>
                            <td style="padding:11px 16px;border-bottom:1px solid #f1f5f9;color:#374151">
                                {{ $appt->appointment_date->format('d M Y') }}
                            </td>
                            <td style="padding:11px 16px;border-bottom:1px solid #f1f5f9;color:#374151">
                                {{ $appt->doctor->name ?? '—' }}
                            </td>
                            <td style="padding:11px 16px;border-bottom:1px solid #f1f5f9;color:#374151">
                                {{ $appt->doctor->department ?? '—' }}
                            </td>
                            <td style="padding:11px 16px;border-bottom:1px solid #f1f5f9">
                                <span style="font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500;background:#dcfce7;color:#166534">
                                    {{ $appt->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:32px;color:#94a3b8;font-size:13px">
                                No appointments yet.
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