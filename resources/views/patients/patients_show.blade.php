@extends('layouts.master')

@section('title', 'Patient — ' . $patient->name)
@section('page-title', 'Patient Profile')
@section('breadcrumb', 'Home / Patients / Details')

@section('content')
    <div class="row g-4">

        {{-- LEFT SIDEBAR: Profile & Info --}}
        <div class="col-12 col-lg-4">

            {{-- Profile Card --}}
            <div
                style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; text-align:center; margin-bottom:16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02)">
                <div
                    style="width:70px; height:70px; border-radius:50%; background:#dbeafe; color:#1d4ed8; font-size:24px; font-weight:700; display:flex; align-items:center; justify-content:center; margin:0 auto 12px">
                    {{ $patient->initials }}
                </div>
                <h5 style="font-weight:700; color:#1e293b; margin-bottom:4px">{{ $patient->name }}</h5>
                <p style="font-size:13px; color:#64748b; margin-bottom:16px">{{ $patient->mrn }}</p>

                <div class="d-flex justify-content-center gap-2 mb-4">
                    <span
                        style="font-size:11px; padding:4px 12px; border-radius:20px; font-weight:600;
                        {{ $patient->status == 'Active' ? 'background:#dcfce7;color:#166534' : 'background:#f3f4f6;color:#374151' }}">
                        <i class="bi bi-circle-fill me-1" style="font-size:7px"></i> {{ $patient->status }}
                    </span>
                    <span
                        style="font-size:11px; padding:4px 12px; border-radius:20px; font-weight:600; background:#eff6ff; color:#1d4ed8">
                        {{ $patient->patient_type }}
                    </span>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-outline-primary btn-sm"
                        style="border-radius:8px">
                        <i class="bi bi-pencil me-1"></i> Edit Profile
                    </a>
                </div>
            </div>

            {{-- Vital Info List --}}
            <div
                style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.02)">
                <div
                    style="padding:14px 16px; border-bottom:1px solid #e2e8f0; font-size:13px; font-weight:700; color:#334155; background:#f8fafc">
                    <i class="bi bi-info-circle me-2 text-primary"></i> Patient Information
                </div>
                <div style="padding:8px 0">
                    @php
                        $details = [
                            ['icon' => 'calendar-event', 'label' => 'Age', 'val' => $patient->age . ' Years'],
                            [
                                'icon' => 'calendar3',
                                'label' => 'DOB',
                                'val' => $patient->date_of_birth->format('d M Y'),
                            ],
                            ['icon' => 'gender-ambiguous', 'label' => 'Gender', 'val' => $patient->gender],
                            [
                                'icon' => 'droplet-fill',
                                'label' => 'Blood Group',
                                'val' => $patient->blood_group ?? 'Not Set',
                            ],
                            ['icon' => 'telephone', 'label' => 'Phone', 'val' => $patient->phone],
                            ['icon' => 'geo-alt', 'label' => 'City', 'val' => $patient->city ?? '—'],
                            [
                                'icon' => 'person-badge',
                                'label' => 'Assigned Dr.',
                                'val' => $patient->doctor->name ?? 'Unassigned',
                            ],
                            ['icon' => 'card-text', 'label' => 'CNIC', 'val' => $patient->cnic ?? '—'],
                        ];
                    @endphp
                    @foreach ($details as $item)
                        <div
                            style="display:flex; justify-content:space-between; padding:10px 16px; border-bottom:1px solid #f1f5f9; font-size:13px">
                            <span style="color:#94a3b8"><i
                                    class="bi bi-{{ $item['icon'] }} me-2"></i>{{ $item['label'] }}</span>
                            <span style="color:#1e293b; font-weight:600">{{ $item['val'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT: Quick Actions & Tabs --}}
        <div class="col-12 col-lg-8">

            {{-- 1. QUICK ACTIONS --}}
            <div
                style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:18px; margin-bottom:20px; box-shadow: 0 2px 4px rgba(0,0,0,0.02)">
                <div
                    style="font-size:11px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:14px">
                    <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Quick Clinical Actions
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}"
                        class="btn btn-primary btn-sm px-3" style="border-radius:8px">
                        <i class="bi bi-calendar-plus me-1"></i> Appointment
                    </a>
                    <a href="{{ route('lab.orders.create', ['patient_id' => $patient->id]) }}"
                        class="btn btn-warning btn-sm px-3 text-dark"
                        style="border-radius:8px; background:#fcd34d; border:none">
                        <i class="bi bi-clipboard2-plus me-1"></i> Lab Order
                    </a>
                    <a href="{{ route('radiology.orders.create', ['patient_id' => $patient->id]) }}"
                        class="btn btn-info btn-sm px-3 text-white" style="border-radius:8px">
                        <i class="bi bi-radioactive me-1"></i> Radiology
                    </a>
                    <a href="{{ route('pharmacy.prescriptions.create', ['patient_id' => $patient->id]) }}"
                        class="btn btn-success btn-sm px-3" style="border-radius:8px">
                        <i class="bi bi-capsule me-1"></i> Prescription
                    </a>
                </div>
            </div>

            {{-- 2. ADDRESS & NOTES --}}
            @if ($patient->address || $patient->notes)
                <div class="row g-3 mb-4">
                    @if ($patient->address)
                        <div class="col-md-6">
                            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px">
                                <div
                                    style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:4px">
                                    Permanent Address</div>
                                <div style="font-size:13px; color:#1e293b">{{ $patient->address }}</div>
                            </div>
                        </div>
                    @endif
                    @if ($patient->notes)
                        <div class="col-md-6">
                            <div style="background:#fff7ed; border:1px solid #ffedd5; border-radius:10px; padding:12px">
                                <div
                                    style="font-size:11px; font-weight:700; color:#c2410c; text-transform:uppercase; margin-bottom:4px">
                                    Clinical Notes</div>
                                <div style="font-size:13px; color:#1e293b">{{ $patient->notes }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- 3. HISTORY TABS --}}
            <div
                style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.02)">
                <ul class="nav nav-tabs px-3 pt-2" id="patientTab" role="tablist"
                    style="background:#f8fafc; border-bottom:1px solid #e2e8f0">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#appointments"
                            style="font-size:13px; font-weight:600">
                            Appointments <span class="badge bg-primary ms-1"
                                style="font-size:10px">{{ $patient->appointments->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#lab" style="font-size:13px; font-weight:600">Lab
                            Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#radiology"
                            style="font-size:13px; font-weight:600">Radiology</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#prescriptions"
                            style="font-size:13px; font-weight:600">Prescriptions</a>
                    </li>
                </ul>

                <div class="tab-content p-4">

                    {{-- Appointments Tab --}}
                    <div class="tab-pane fade show active" id="appointments">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" style="font-size:13px">
                                <thead class="table-light">
                                    <tr>
                                        <th>Visit Date</th>
                                        <th>Doctor</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($patient->appointments->sortByDesc('appointment_date') as $appt)
                                        <tr>
                                            <td>
                                                <div style="font-weight:600; color:#1e293b">
                                                    {{ $appt->appointment_date->format('d M Y') }}</div>
                                                <div style="font-size:11px; color:#94a3b8">{{ $appt->formatted_time }}
                                                </div>
                                            </td>
                                            <td>
                                                <div style="font-weight:500">{{ $appt->doctor->name ?? '—' }}</div>
                                                <div style="font-size:11px; color:#94a3b8">
                                                    {{ $appt->doctor->department ?? '' }}</div>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = match ($appt->status) {
                                                        'Completed' => 'bg-success',
                                                        'Cancelled' => 'bg-danger',
                                                        'In-Progress' => 'bg-warning text-dark',
                                                        default => 'bg-primary',
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }}"
                                                    style="font-size:10px">{{ $appt->status }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('appointments.show', $appt->id) }}"
                                                    class="btn btn-light btn-sm"
                                                    style="border:1px solid #e2e8f0; font-size:11px">View Details</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">No appointment history
                                                found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Lab Orders Tab --}}
                    <div class="tab-pane fade" id="lab">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" style="font-size:13px">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($patient->labOrders as $order)
                                        <tr>
                                            <td><code style="color:#e11d48">{{ $order->order_number }}</code></td>
                                            <td>{{ $order->order_date->format('d M Y') }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $order->priority == 'STAT' ? 'bg-danger' : 'bg-secondary' }}"
                                                    style="font-size:10px">{{ $order->priority }}</span>
                                            </td>
                                            <td><span class="badge bg-info"
                                                    style="font-size:10px">{{ $order->status }}</span></td>
                                            <td class="text-end">
                                                <a href="{{ route('lab.orders.show', $order->id) }}"
                                                    class="btn btn-light btn-sm"
                                                    style="border:1px solid #e2e8f0; font-size:11px">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">No lab orders found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Radiology Tab --}}
                    <div class="tab-pane fade" id="radiology">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" style="font-size:13px">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($patient->radiologyOrders as $rad)
                                        <tr>
                                            <td><code>{{ $rad->order_number }}</code></td>
                                            <td>{{ $rad->order_date->format('d M Y') }}</td>
                                            <td><span class="badge bg-purple"
                                                    style="background:#7c3aed; font-size:10px">{{ $rad->status }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('radiology.orders.show', $rad->id) }}"
                                                    class="btn btn-light btn-sm"
                                                    style="border:1px solid #e2e8f0; font-size:11px">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">No radiology orders
                                                found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Prescriptions Tab --}}
                    <div class="tab-pane fade" id="prescriptions">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" style="font-size:13px">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Doctor</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($patient->prescriptions as $rx)
                                        <tr>
                                            <td>{{ $rx->created_at->format('d M Y') }}</td>
                                            <td>{{ $rx->doctor->name ?? '—' }}</td>
                                            <td><span class="badge bg-success"
                                                    style="font-size:10px">{{ $rx->status }}</span></td>
                                            <td class="text-end">
                                                <a href="{{ route('pharmacy.prescriptions.show', $rx->id) }}"
                                                    class="btn btn-light btn-sm"
                                                    style="border:1px solid #e2e8f0; font-size:11px">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">No prescriptions found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
