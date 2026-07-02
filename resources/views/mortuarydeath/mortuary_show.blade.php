@extends('layouts.master')

@section('title', $mortuary->mortuary_id . ' — Mortuary Record')
@section('page-title', 'Mortuary Record')
@section('breadcrumb', 'Home / Mortuary / ' . $mortuary->mortuary_id)

@push('styles')
    <style>
        .card-section {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-section-header {
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .section-body {
            padding: 20px;
        }

        /* Info grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
        }

        .info-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .info-grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }

        .info-item .lbl {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .info-item .val {
            font-size: 13px;
            color: #1e293b;
            font-weight: 500;
        }

        .info-item .val.empty {
            color: #cbd5e1;
            font-style: italic;
        }

        /* Status badges */
        .badge-status {
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 600;
            white-space: nowrap;
        }

        .s-Admitted {
            background: #dbeafe;
            color: #1e40af;
        }

        .s-Postmortem-Pending {
            background: #fef9c3;
            color: #854d0e;
        }

        .s-Postmortem-Done {
            background: #e0f2fe;
            color: #0369a1;
        }

        .s-Certificate-Issued {
            background: #ede9fe;
            color: #5b21b6;
        }

        .s-Released {
            background: #dcfce7;
            color: #166534;
        }

        .s-Transferred {
            background: #f3f4f6;
            color: #374151;
        }

        .s-Unclaimed {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Manner badges */
        .m-Natural {
            background: #f0fdf4;
            color: #15803d;
        }

        .m-Accidental {
            background: #fff7ed;
            color: #c2410c;
        }

        .m-Homicidal {
            background: #fee2e2;
            color: #991b1b;
        }

        .m-Suicidal {
            background: #fef2f2;
            color: #b91c1c;
        }

        .m-Undetermined {
            background: #f3f4f6;
            color: #374151;
        }

        /* MLC alert */
        .mlc-alert {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            border-left: 4px solid #dc2626;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #991b1b;
        }

        /* Timeline */
        .timeline {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }

        .tl-step {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .tl-step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 14px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #e2e8f0;
            z-index: 0;
        }

        .tl-step:not(:last-child).done::after {
            background: #22c55e;
        }

        .tl-dot {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #f1f5f9;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            position: relative;
            z-index: 1;
            border: 2px solid #e2e8f0;
        }

        .tl-dot.done {
            background: #dcfce7;
            color: #166534;
            border-color: #86efac;
        }

        .tl-dot.active {
            background: #dbeafe;
            color: #1e40af;
            border-color: #3b82f6;
        }

        .tl-label {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 6px;
            text-align: center;
            font-weight: 500;
        }

        .tl-label.active {
            color: #1e40af;
            font-weight: 700;
        }

        .tl-label.done {
            color: #166534;
        }

        /* Cause box */
        .cause-box {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .cause-header {
            background: #f8fafc;
            padding: 8px 14px;
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .06em;
            border-bottom: 1px solid #e2e8f0;
        }

        .cause-row {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 10px 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .cause-row:last-child {
            border-bottom: none;
        }

        .cause-part {
            font-size: 10px;
            font-weight: 700;
            color: #7f1d1d;
            text-transform: uppercase;
            min-width: 60px;
            padding-top: 1px;
        }

        .cause-val {
            font-size: 13px;
            color: #1e293b;
            font-weight: 500;
        }

        .cause-empty {
            font-size: 12px;
            color: #cbd5e1;
            font-style: italic;
        }

        /* Action buttons */
        .action-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #fff;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
            width: 100%;
        }

        .action-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            text-decoration: none;
            color: #1e293b;
        }

        .action-btn .action-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .action-btn .action-arrow {
            margin-left: auto;
            color: #cbd5e1;
            font-size: 12px;
        }

        .action-btn.disabled {
            opacity: .45;
            cursor: not-allowed;
            pointer-events: none;
        }

        .action-btn.primary {
            border-color: #bfdbfe;
            background: #eff6ff;
            color: #1e40af;
        }

        .action-btn.primary:hover {
            background: #dbeafe;
        }

        .action-btn.success {
            border-color: #86efac;
            background: #f0fdf4;
            color: #166534;
        }

        .action-btn.success:hover {
            background: #dcfce7;
        }

        .action-btn.danger {
            border-color: #fca5a5;
            background: #fef2f2;
            color: #991b1b;
        }

        .action-btn.danger:hover {
            background: #fee2e2;
        }

        /* Certificate list */
        .cert-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .cert-item:last-child {
            margin-bottom: 0;
        }

        /* Section divider */
        .sec-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 16px 0;
        }

        /* Postmortem status */
        .pm-pending {
            background: #fef9c3;
            color: #854d0e;
        }

        .pm-progress {
            background: #e0f2fe;
            color: #0369a1;
        }

        .pm-done {
            background: #dcfce7;
            color: #166534;
        }

        .pm-none {
            background: #f3f4f6;
            color: #64748b;
        }
    </style>
@endpush

@section('content')


    {{-- MLC Alert --}}
    @if ($mortuary->is_medico_legal)
        <div class="mlc-alert">
            <i class="bi bi-shield-exclamation" style="font-size:22px;flex-shrink:0"></i>
            <div>
                <strong style="font-size:14px">Medico Legal Case (MLC)</strong>
                <div style="font-size:12px;margin-top:2px;opacity:.85">
                    MLC No: <strong>{{ $mortuary->mlc_number ?? 'Not Assigned' }}</strong>
                    @if ($mortuary->police_station)
                        &nbsp;·&nbsp; Police Station: <strong>{{ $mortuary->police_station }}</strong>
                    @endif
                    @if ($mortuary->fir_number)
                        &nbsp;·&nbsp; FIR: <strong>{{ $mortuary->fir_number }}</strong>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Status Timeline --}}
    @php
        $steps = [
            ['label' => 'Admitted', 'icon' => '1'],
            ['label' => 'Postmortem', 'icon' => '2'],
            ['label' => 'Certificate', 'icon' => '3'],
            ['label' => 'Released', 'icon' => '4'],
        ];
        $currentStep = match ($mortuary->status) {
            'Admitted', 'Postmortem Pending' => 0,
            'Postmortem Done' => 1,
            'Certificate Issued' => 2,
            'Released' => 3,
            default => 0,
        };
    @endphp
    <div class="timeline mb-4">
        @foreach ($steps as $i => $step)
            <div class="tl-step {{ $i < $currentStep ? 'done' : '' }}">
                <div class="tl-dot {{ $i < $currentStep ? 'done' : ($i === $currentStep ? 'active' : '') }}">
                    @if ($i < $currentStep)
                        <i class="bi bi-check" style="font-size:12px"></i>
                    @else
                        {{ $step['icon'] }}
                    @endif
                </div>
                <div class="tl-label {{ $i < $currentStep ? 'done' : ($i === $currentStep ? 'active' : '') }}">
                    {{ $step['label'] }}
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">

        {{-- ── LEFT col-lg-8 ──────────────────────────────────────── --}}
        <div class="col-lg-8">

            {{-- 1. Death Information --}}
            <div class="card-section">
                <div class="card-section-header">
                    <div class="header-icon" style="background:#fee2e2;color:#991b1b"><i class="bi bi-person-x"></i></div>
                    <span style="font-size:14px;font-weight:600;color:#1e293b">Death Information</span>
                    @php $sc = str_replace(' ','-',$mortuary->status); @endphp
                    <span class="badge-status s-{{ $sc }} ms-auto">{{ $mortuary->status }}</span>
                </div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="lbl">Mortuary ID</div>
                            <div class="val"><code
                                    style="color:#6366f1;font-size:13px">{{ $mortuary->mortuary_id }}</code></div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Patient</div>
                            <div class="val">
                                <a href="{{ route('patients.show', $mortuary->patient_id) }}"
                                    style="color:#1e40af;text-decoration:none;font-weight:600">
                                    {{ $mortuary->patient?->name }}
                                </a>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">MRN</div>
                            <div class="val">{{ $mortuary->patient?->mrn }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Age / Gender</div>
                            <div class="val">{{ $mortuary->patient?->age }} yrs / {{ $mortuary->patient?->gender }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Date of Death</div>
                            <div class="val">{{ \Carbon\Carbon::parse($mortuary->death_datetime)->format('d M Y') }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Time of Death</div>
                            <div class="val">{{ \Carbon\Carbon::parse($mortuary->death_datetime)->format('h:i A') }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Death Location</div>
                            <div class="val">{{ $mortuary->death_location }}
                                @if ($mortuary->ward)
                                    <span style="color:#94a3b8;font-size:11px">— {{ $mortuary->ward }}
                                        @if ($mortuary->bed_number)
                                            / Bed {{ $mortuary->bed_number }}
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Manner of Death</div>
                            <div class="val">
                                @php $mc = str_replace(' ','-',$mortuary->manner_of_death); @endphp
                                <span class="badge-status m-{{ $mc }}">{{ $mortuary->manner_of_death }}</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Days in Mortuary</div>
                            <div class="val">{{ $mortuary->days_in_mortuary }} day(s)</div>
                        </div>
                        @if ($mortuary->locker_number)
                            <div class="info-item">
                                <div class="lbl">Locker</div>
                                <div class="val">{{ $mortuary->locker_number }}</div>
                            </div>
                        @endif
                        @if ($mortuary->declaringDoctor)
                            <div class="info-item">
                                <div class="lbl">Declared By</div>
                                <div class="val">Dr. {{ $mortuary->declaringDoctor->employee->first_name }}
                                    {{ $mortuary->declaringDoctor->employee->last_name }}</div>
                            </div>
                        @endif
                        @if ($mortuary->declared_at)
                            <div class="info-item">
                                <div class="lbl">Declared At</div>
                                <div class="val">
                                    {{ \Carbon\Carbon::parse($mortuary->declared_at)->format('d M Y, h:i A') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 2. Cause of Death --}}
            <div class="card-section">
                <div class="card-section-header">
                    <div class="header-icon" style="background:#fef9c3;color:#854d0e"><i class="bi bi-clipboard2-pulse"></i>
                    </div>
                    <span style="font-size:14px;font-weight:600;color:#1e293b">Cause of Death</span>
                    <span style="font-size:11px;color:#94a3b8;margin-left:4px">ICD-10 Standard</span>
                </div>
                <div class="section-body">
                    <div class="cause-box">
                        <div class="cause-header">Medical Certificate of Cause of Death</div>
                        <div class="cause-row">
                            <div class="cause-part">Part I (a)<br><span
                                    style="font-weight:400;font-size:9px;color:#94a3b8">Immediate</span></div>
                            <div class="cause-val">{{ $mortuary->immediate_cause }}</div>
                        </div>
                        <div class="cause-row">
                            <div class="cause-part">Part I (b)<br><span
                                    style="font-weight:400;font-size:9px;color:#94a3b8">Intermediate</span></div>
                            @if ($mortuary->intermediate_cause)
                                <div class="cause-val">{{ $mortuary->intermediate_cause }}</div>
                            @else
                                <div class="cause-empty">Not specified</div>
                            @endif
                        </div>
                        <div class="cause-row">
                            <div class="cause-part">Part I (c)<br><span
                                    style="font-weight:400;font-size:9px;color:#94a3b8">Underlying</span></div>
                            @if ($mortuary->underlying_cause)
                                <div class="cause-val">{{ $mortuary->underlying_cause }}</div>
                            @else
                                <div class="cause-empty">Not specified</div>
                            @endif
                        </div>
                        <div class="cause-row">
                            <div class="cause-part">Part II<br><span
                                    style="font-weight:400;font-size:9px;color:#94a3b8">Contributing</span></div>
                            @if ($mortuary->contributing_cause)
                                <div class="cause-val">{{ $mortuary->contributing_cause }}</div>
                            @else
                                <div class="cause-empty">None</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Postmortem --}}
            @if ($mortuary->postmortem_required)
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#ede9fe;color:#5b21b6"><i class="bi bi-hospital"></i>
                        </div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Postmortem</span>
                        @php
                            $pmClass = match ($mortuary->postmortem_status) {
                                'Completed' => 'pm-done',
                                'In Progress' => 'pm-progress',
                                'Pending' => 'pm-pending',
                                default => 'pm-none',
                            };
                        @endphp
                        <span class="badge-status {{ $pmClass }} ms-auto">{{ $mortuary->postmortem_status }}</span>
                    </div>
                    <div class="section-body">
                        @if ($mortuary->postmortem_status === 'Pending' || $mortuary->postmortem_status === 'In Progress')
                            <div
                                style="background:#fef9c3;border:1px solid #fde68a;border-radius:8px;padding:10px 14px;font-size:13px;color:#854d0e;margin-bottom:14px">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Postmortem is <strong>{{ $mortuary->postmortem_status }}</strong>.
                                <a href="{{ route('mortuary.edit', $mortuary->id) }}"
                                    style="color:#854d0e;font-weight:600;margin-left:6px">
                                    Update postmortem details →
                                </a>
                            </div>
                        @endif
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="lbl">Ordered By</div>
                                <div class="val">{{ $mortuary->postmortem_ordered_by ?? '—' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="lbl">Performed By</div>
                                <div class="val">
                                    @if ($mortuary->postmortemDoctor)
                                        Dr. {{ $mortuary->postmortemDoctor->employee->first_name }}
                                        {{ $mortuary->postmortemDoctor->employee->last_name }}
                                    @else
                                        <span class="empty">Not assigned</span>
                                    @endif
                                </div>
                            </div>
                            @if ($mortuary->postmortem_started_at)
                                <div class="info-item">
                                    <div class="lbl">Started At</div>
                                    <div class="val">
                                        {{ \Carbon\Carbon::parse($mortuary->postmortem_started_at)->format('d M Y, h:i A') }}
                                    </div>
                                </div>
                            @endif
                            @if ($mortuary->postmortem_completed_at)
                                <div class="info-item">
                                    <div class="lbl">Completed At</div>
                                    <div class="val">
                                        {{ \Carbon\Carbon::parse($mortuary->postmortem_completed_at)->format('d M Y, h:i A') }}
                                    </div>
                                </div>
                            @endif
                            @if ($mortuary->postmortem_report_number)
                                <div class="info-item">
                                    <div class="lbl">Report No.</div>
                                    <div class="val">{{ $mortuary->postmortem_report_number }}</div>
                                </div>
                            @endif
                        </div>
                        @if ($mortuary->postmortem_findings)
                            <div
                                style="margin-top:14px;padding:12px 14px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0">
                                <div
                                    style="font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px">
                                    Findings</div>
                                <div style="font-size:13px;color:#374151;line-height:1.6">
                                    {{ $mortuary->postmortem_findings }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- 4. MLC Details --}}
            @if ($mortuary->is_medico_legal)
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#fee2e2;color:#991b1b"><i
                                class="bi bi-shield-exclamation"></i></div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Medico Legal Details</span>
                    </div>
                    <div class="section-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="lbl">MLC Number</div>
                                <div class="val">{{ $mortuary->mlc_number ?? '—' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="lbl">Police Station</div>
                                <div class="val">{{ $mortuary->police_station ?? '—' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="lbl">FIR Number</div>
                                <div class="val">{{ $mortuary->fir_number ?? '—' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="lbl">Investigating Officer</div>
                                <div class="val">{{ $mortuary->investigating_officer ?? '—' }}</div>
                            </div>
                            @if ($mortuary->police_informed_at)
                                <div class="info-item">
                                    <div class="lbl">Police Informed At</div>
                                    <div class="val">
                                        {{ \Carbon\Carbon::parse($mortuary->police_informed_at)->format('d M Y, h:i A') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- 5. Certificates Issued --}}
            <div class="card-section">
                <div class="card-section-header">
                    <div class="header-icon" style="background:#dbeafe;color:#1e40af"><i
                            class="bi bi-file-earmark-text"></i></div>
                    <span style="font-size:14px;font-weight:600;color:#1e293b">Death Certificates</span>
                    <span class="ms-auto"
                        style="font-size:12px;color:#94a3b8">{{ $mortuary->deathCertificates->count() }} issued</span>
                </div>
                <div class="section-body">
                    @forelse($mortuary->deathCertificates as $cert)
                        <div class="cert-item">
                            <div>
                                <div style="font-weight:600;font-size:13px;color:#1e293b">
                                    {{ $cert->certificate_number }}
                                    @if ($cert->copy_number > 1)
                                        <span
                                            style="font-size:10px;background:#fef9c3;color:#854d0e;padding:1px 7px;border-radius:4px;margin-left:6px">DUPLICATE</span>
                                    @endif
                                </div>
                                <div style="font-size:11px;color:#94a3b8;margin-top:3px">
                                    {{ $cert->purpose }} &nbsp;·&nbsp;
                                    Issued to: <strong>{{ $cert->issued_to_name }}</strong>
                                    ({{ $cert->issued_to_relation }})
                                    &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($cert->issued_at)->format('d M Y') }}
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @if ($cert->is_verified)
                                    <span
                                        style="font-size:11px;background:#dcfce7;color:#166534;padding:2px 9px;border-radius:10px;font-weight:500">Verified</span>
                                @else
                                    <span
                                        style="font-size:11px;background:#fef9c3;color:#854d0e;padding:2px 9px;border-radius:10px;font-weight:500">Pending</span>
                                @endif
                                <a href="{{ route('mortuary.certificates.print', $cert->id) }}"
                                    class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px"
                                    target="_blank" title="Print">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:24px;color:#94a3b8;font-size:13px">
                            <i class="bi bi-file-earmark-x"
                                style="font-size:30px;display:block;margin-bottom:8px;opacity:.3"></i>
                            No certificates issued yet.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 6. Body Release --}}
            @if ($mortuary->bodyRelease)
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#f0fdf4;color:#15803d"><i
                                class="bi bi-box-arrow-right"></i></div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Body Release Record</span>
                        <span class="ms-auto"
                            style="font-size:11px;background:#dcfce7;color:#166534;padding:3px 10px;border-radius:20px;font-weight:500">
                            Released {{ \Carbon\Carbon::parse($mortuary->bodyRelease->released_at)->format('d M Y') }}
                        </span>
                    </div>
                    <div class="section-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="lbl">Released To</div>
                                <div class="val">{{ $mortuary->bodyRelease->released_to_name }}</div>
                            </div>
                            <div class="info-item">
                                <div class="lbl">Relation</div>
                                <div class="val">{{ $mortuary->bodyRelease->released_to_relation }}</div>
                            </div>
                            <div class="info-item">
                                <div class="lbl">CNIC</div>
                                <div class="val">{{ $mortuary->bodyRelease->released_to_cnic }}</div>
                            </div>
                            <div class="info-item">
                                <div class="lbl">Transport</div>
                                <div class="val">{{ $mortuary->bodyRelease->transport_type ?? '—' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="lbl">Destination</div>
                                <div class="val">{{ $mortuary->bodyRelease->destination }}</div>
                            </div>
                            <div class="info-item">
                                <div class="lbl">Released By</div>
                                <div class="val">{{ $mortuary->bodyRelease->releasedBy->first_name ?? '—' }}
                                    {{ $mortuary->bodyRelease->releasedBy->last_name ?? '' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Notes --}}
            @if ($mortuary->notes)
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#f0fdf4;color:#15803d"><i class="bi bi-sticky"></i>
                        </div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Notes</span>
                    </div>
                    <div class="section-body">
                        <p style="font-size:13px;color:#374151;line-height:1.7;margin:0">{{ $mortuary->notes }}</p>
                    </div>
                </div>
            @endif

        </div>{{-- end col-lg-8 --}}

        {{-- ── RIGHT col-lg-4 ─────────────────────────────────────── --}}
        <div class="col-lg-4">
            <div style="position:sticky;top:80px;display:flex;flex-direction:column;gap:16px">

                {{-- Actions Card --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#f8fafc;color:#374151"><i
                                class="bi bi-lightning-charge"></i></div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Actions</span>
                    </div>
                    <div class="section-body" style="display:flex;flex-direction:column;gap:8px">

                        {{-- Edit --}}
                        @if ($mortuary->status !== 'Released')
                            <a href="{{ route('mortuary.edit', $mortuary->id) }}" class="action-btn">
                                <div class="action-icon" style="background:#fef9c3;color:#854d0e"><i
                                        class="bi bi-pencil"></i></div>
                                <div>
                                    <div style="font-weight:600">Edit Record</div>
                                    <div style="font-size:11px;color:#94a3b8">Update death details</div>
                                </div>
                                <i class="bi bi-chevron-right action-arrow"></i>
                            </a>
                        @endif

                        {{-- Issue Certificate --}}
                        @php
                            $canIssueCert =
                                !$mortuary->is_released &&
                                (!$mortuary->is_medico_legal || $mortuary->postmortem_status === 'Completed') &&
                                !in_array($mortuary->postmortem_status, ['Pending', 'In Progress']);
                        @endphp
                        <a href="{{ $canIssueCert ? route('mortuary.certificates.create', $mortuary->id) : '#' }}"
                            class="action-btn primary {{ !$canIssueCert ? 'disabled' : '' }}">
                            <div class="action-icon" style="background:#dbeafe;color:#1e40af"><i
                                    class="bi bi-file-earmark-plus"></i></div>
                            <div>
                                <div style="font-weight:600">Issue Certificate</div>
                                <div style="font-size:11px;color:#6b7280">
                                    @if (!$canIssueCert && $mortuary->postmortem_status === 'Pending')
                                        Complete postmortem first
                                    @elseif(!$canIssueCert && $mortuary->is_released)
                                        Already released
                                    @else
                                        Issue death certificate
                                    @endif
                                </div>
                            </div>
                            <i class="bi bi-chevron-right action-arrow"></i>
                        </a>

                        {{-- Print Certificate --}}
                        @if ($mortuary->latestCertificate)
                            <a href="{{ route('mortuary.certificates.print', $mortuary->latestCertificate->id) }}"
                                class="action-btn" target="_blank">
                                <div class="action-icon" style="background:#f3f4f6;color:#374151"><i
                                        class="bi bi-printer"></i></div>
                                <div>
                                    <div style="font-weight:600">Print Certificate</div>
                                    <div style="font-size:11px;color:#94a3b8">
                                        {{ $mortuary->latestCertificate->certificate_number }}</div>
                                </div>
                                <i class="bi bi-chevron-right action-arrow"></i>
                            </a>
                        @endif

                        {{-- Release Body --}}
                        @php
                            $canRelease = $mortuary->deathCertificates->count() > 0 && !$mortuary->bodyRelease;
                        @endphp
                        @if (!$mortuary->bodyRelease)
                            <a href="{{ $canRelease ? route('mortuary.release.create', $mortuary->id) : '#' }}"
                                class="action-btn success {{ !$canRelease ? 'disabled' : '' }}">
                                <div class="action-icon" style="background:#dcfce7;color:#166534"><i
                                        class="bi bi-box-arrow-right"></i></div>
                                <div>
                                    <div style="font-weight:600">Release Body</div>
                                    <div style="font-size:11px;color:#6b7280">
                                        {{ !$canRelease ? 'Issue certificate first' : 'Process body release' }}
                                    </div>
                                </div>
                                <i class="bi bi-chevron-right action-arrow"></i>
                            </a>
                        @endif

                        <div class="sec-divider"></div>

                        {{-- View Patient --}}
                        <a href="{{ route('patients.show', $mortuary->patient_id) }}" class="action-btn">
                            <div class="action-icon" style="background:#ede9fe;color:#5b21b6"><i
                                    class="bi bi-person"></i></div>
                            <div>
                                <div style="font-weight:600">View Patient</div>
                                <div style="font-size:11px;color:#94a3b8">{{ $mortuary->patient?->mrn }}</div>
                            </div>
                            <i class="bi bi-chevron-right action-arrow"></i>
                        </a>

                        {{-- Delete --}}
                        @if (!in_array($mortuary->status, ['Released', 'Certificate Issued']))
                            <form method="POST" action="{{ route('mortuary.destroy', $mortuary->id) }}"
                                onsubmit="return confirm('Delete this mortuary record? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn danger" style="border:none">
                                    <div class="action-icon" style="background:#fee2e2;color:#991b1b"><i
                                            class="bi bi-trash"></i></div>
                                    <div>
                                        <div style="font-weight:600">Delete Record</div>
                                        <div style="font-size:11px;color:#94a3b8">Permanent removal</div>
                                    </div>
                                </button>
                            </form>
                        @endif

                    </div>
                </div>

                {{-- Next of Kin --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#dbeafe;color:#1e40af"><i class="bi bi-people"></i>
                        </div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Next of Kin</span>
                        @if ($mortuary->nok_informed)
                            <span class="ms-auto"
                                style="font-size:11px;background:#dcfce7;color:#166534;padding:2px 8px;border-radius:10px;font-weight:500">Informed</span>
                        @else
                            <span class="ms-auto"
                                style="font-size:11px;background:#fee2e2;color:#991b1b;padding:2px 8px;border-radius:10px;font-weight:500">Not
                                Informed</span>
                        @endif
                    </div>
                    <div class="section-body">
                        @if ($mortuary->nok_name)
                            <div style="display:flex;flex-direction:column;gap:10px">
                                <div class="info-item">
                                    <div class="lbl">Name</div>
                                    <div class="val">{{ $mortuary->nok_name }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="lbl">Relation</div>
                                    <div class="val">{{ $mortuary->nok_relation }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="lbl">Phone</div>
                                    <div class="val">{{ $mortuary->nok_phone ?? '—' }}</div>
                                </div>
                                @if ($mortuary->nok_cnic)
                                    <div class="info-item">
                                        <div class="lbl">CNIC</div>
                                        <div class="val">{{ $mortuary->nok_cnic }}</div>
                                    </div>
                                @endif
                                @if ($mortuary->nok_informed_at)
                                    <div class="info-item">
                                        <div class="lbl">Informed At</div>
                                        <div class="val">
                                            {{ \Carbon\Carbon::parse($mortuary->nok_informed_at)->format('d M Y, h:i A') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div style="font-size:13px;color:#94a3b8;text-align:center;padding:10px">No next of kin
                                recorded.</div>
                        @endif
                    </div>
                </div>

                {{-- Record Meta --}}
                <div class="card-section">
                    <div class="section-body">
                        <div style="display:flex;flex-direction:column;gap:8px">
                            <div class="info-item">
                                <div class="lbl">Created</div>
                                <div class="val" style="font-size:12px">
                                    {{ $mortuary->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                            <div class="info-item">
                                <div class="lbl">Last Updated</div>
                                <div class="val" style="font-size:12px">
                                    {{ $mortuary->updated_at->format('d M Y, h:i A') }}</div>
                            </div>
                            @if ($mortuary->admittedBy)
                                <div class="info-item">
                                    <div class="lbl">Admitted By</div>
                                    <div class="val" style="font-size:12px">{{ $mortuary->admittedBy->first_name }}
                                        {{ $mortuary->admittedBy->last_name }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection
