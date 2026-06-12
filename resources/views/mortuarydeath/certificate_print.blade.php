<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Death Certificate — {{ $certificate->certificate_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #d1d5db;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 20px;
        }

        /* ── Print toolbar ── */
        .print-toolbar {
            width: 210mm;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding: 10px 16px;
            background: #1e293b;
            border-radius: 10px;
            color: #fff;
        }

        .print-toolbar-title {
            font-size: 13px;
            font-weight: 600;
        }

        .print-toolbar-sub {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .btn-print {
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 8px 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-print:hover {
            background: #2563eb;
        }

        /* ── A4 Page ── */
        .page {
            width: 210mm;
            min-height: 297mm;
            background: #fff;
            position: relative;
            box-shadow: 0 8px 48px rgba(0, 0, 0, .22);
        }

        /* ── Decorative borders ── */
        .border-outer {
            position: absolute;
            inset: 9mm;
            border: 2.5px solid #7f1d1d;
            pointer-events: none;
            z-index: 10;
        }

        .border-inner {
            position: absolute;
            inset: 11.5mm;
            border: 1px solid #c9a96e;
            pointer-events: none;
            z-index: 10;
        }

        /* Corner ornaments */
        .corner {
            position: absolute;
            width: 16px;
            height: 16px;
            border-color: #7f1d1d;
            border-style: solid;
            z-index: 11;
        }

        .corner-tl {
            top: 7mm;
            left: 7mm;
            border-width: 2px 0 0 2px;
        }

        .corner-tr {
            top: 7mm;
            right: 7mm;
            border-width: 2px 2px 0 0;
        }

        .corner-bl {
            bottom: 7mm;
            left: 7mm;
            border-width: 0 0 2px 2px;
        }

        .corner-br {
            bottom: 7mm;
            right: 7mm;
            border-width: 0 2px 2px 0;
        }

        /* ── Watermark ── */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 70px;
            font-weight: 700;
            color: rgba(127, 29, 29, 0.04);
            font-family: 'Crimson Pro', serif;
            white-space: nowrap;
            pointer-events: none;
            z-index: 0;
            letter-spacing: 8px;
            text-transform: uppercase;
        }

        /* ── Content ── */
        .content {
            position: relative;
            z-index: 1;
            padding: 18mm 20mm 16mm;
        }

        /* ── Header ── */
        .header {
            text-align: center;
            padding-bottom: 14px;
            border-bottom: 2px solid #7f1d1d;
            margin-bottom: 14px;
        }

        .hospital-logo-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-bottom: 8px;
        }

        .logo-circle {
            width: 52px;
            height: 52px;
            border: 2px solid #7f1d1d;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #7f1d1d;
        }

        .hospital-name {
            font-family: 'Crimson Pro', serif;
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
            letter-spacing: .5px;
        }

        .hospital-meta {
            font-size: 9.5px;
            color: #555;
            margin-top: 2px;
            line-height: 1.6;
        }

        .divider-ornament {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin: 10px 0;
            color: #c9a96e;
            font-size: 14px;
        }

        .divider-ornament::before,
        .divider-ornament::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, transparent, #c9a96e);
        }

        .divider-ornament::after {
            background: linear-gradient(to left, transparent, #c9a96e);
        }

        .cert-title {
            font-family: 'Crimson Pro', serif;
            font-size: 28px;
            font-weight: 700;
            color: #7f1d1d;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .cert-subtitle {
            font-size: 9px;
            color: #777;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 3px;
        }

        .cert-meta-row {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 9.5px;
            color: #444;
        }

        .cert-meta-row .val {
            font-weight: 700;
            color: #1a1a1a;
        }

        /* MLC badge */
        .mlc-badge {
            display: inline-block;
            background: #fee2e2;
            color: #991b1b;
            font-size: 8.5px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 3px;
            letter-spacing: 1px;
            margin-left: 8px;
            vertical-align: middle;
            border: 1px solid #fca5a5;
        }

        /* ── Section ── */
        .sec-heading {
            font-size: 8.5px;
            font-weight: 700;
            color: #7f1d1d;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-bottom: 1px solid #e8d5b7;
            padding-bottom: 4px;
            margin: 14px 0 9px;
        }

        /* ── Info grid ── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 20px;
        }

        .info-grid.single {
            grid-template-columns: 1fr;
        }

        .info-grid.triple {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .info-row {
            display: flex;
            flex-direction: column;
            gap: 2px;
            padding: 4px 0;
            border-bottom: 1px dotted #ddd;
        }

        .info-row .lbl {
            font-size: 8.5px;
            color: #777;
            text-transform: uppercase;
            letter-spacing: .8px;
            font-weight: 600;
        }

        .info-row .val {
            font-size: 11px;
            color: #1a1a1a;
            font-weight: 600;
            min-height: 14px;
        }

        /* ── Cause of death box ── */
        .cause-box {
            border: 1.5px solid #c9a96e;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 6px;
            background: #fffdf8;
        }

        .cause-box-header {
            background: #7f1d1d;
            color: #fff;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 5px 12px;
        }

        .cause-row {
            display: flex;
            align-items: flex-start;
            padding: 6px 12px;
            border-bottom: 1px solid #f0e8d5;
            gap: 12px;
        }

        .cause-row:last-child {
            border-bottom: none;
        }

        .cause-part-label {
            font-size: 8.5px;
            color: #7f1d1d;
            font-weight: 700;
            text-transform: uppercase;
            min-width: 70px;
            padding-top: 1px;
            line-height: 1.4;
        }

        .cause-part-val {
            font-size: 11px;
            color: #1a1a1a;
            font-weight: 600;
            flex: 1;
            line-height: 1.4;
        }

        .cause-part-empty {
            font-size: 10px;
            color: #bbb;
            font-style: italic;
        }

        /* ── Declaration ── */
        .declaration {
            background: #fafaf8;
            border: 1px solid #e8d5b7;
            border-left: 3px solid #7f1d1d;
            padding: 10px 14px;
            font-size: 10px;
            color: #333;
            line-height: 1.75;
            margin: 14px 0;
            font-style: italic;
        }

        .declaration strong {
            font-style: normal;
            color: #1a1a1a;
            font-weight: 700;
        }

        /* ── Issued To box ── */
        .issued-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 10px 14px;
        }

        /* ── Signature section ── */
        .sig-section {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 14px;
            margin-top: 22px;
            padding-top: 16px;
            border-top: 1px solid #e8d5b7;
        }

        .sig-box {
            text-align: center;
        }

        .sig-space {
            height: 44px;
            border-bottom: 1.5px solid #333;
            margin-bottom: 6px;
        }

        .sig-name {
            font-size: 10px;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1.3;
        }

        .sig-title {
            font-size: 8.5px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-top: 2px;
        }

        .sig-reg {
            font-size: 8.5px;
            color: #888;
            margin-top: 2px;
        }

        /* ── Footer ── */
        .cert-footer {
            margin-top: 18px;
            padding-top: 10px;
            border-top: 1px dashed #ccc;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .footer-note {
            font-size: 8px;
            color: #999;
            line-height: 1.6;
            max-width: 60%;
        }

        .qr-placeholder {
            width: 52px;
            height: 52px;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 7px;
            color: #aaa;
            text-align: center;
            border-radius: 4px;
        }

        /* ── Duplicate stamp ── */
        .duplicate-stamp {
            position: absolute;
            top: 40mm;
            right: 22mm;
            transform: rotate(15deg);
            border: 3px solid #dc2626;
            color: #dc2626;
            font-size: 18px;
            font-weight: 900;
            padding: 4px 10px;
            letter-spacing: 3px;
            opacity: .25;
            font-family: 'Crimson Pro', serif;
            pointer-events: none;
            z-index: 5;
        }

        /* ── Print styles ── */
        @media print {
            body {
                background: none;
                padding: 0;
            }

            .print-toolbar {
                display: none !important;
            }

            .page {
                box-shadow: none;
                margin: 0;
                width: 100%;
                min-height: 100vh;
            }

            .content {
                padding: 14mm 18mm;
            }
        }
    </style>
</head>

<body>

    {{-- Print Toolbar --}}
    <div class="print-toolbar">
        <div>
            <div class="print-toolbar-title">Death Certificate — {{ $certificate->certificate_number }}</div>
            <div class="print-toolbar-sub">
                {{ $certificate->mortuaryRecord->patient->name }} ·
                {{ \Carbon\Carbon::parse($certificate->mortuaryRecord->death_datetime)->format('d M Y') }}
            </div>
        </div>
        <div style="display:flex;gap:10px;align-items:center">
            <a href="{{ route('mortuary.show', $certificate->mortuary_record_id) }}"
                style="color:#94a3b8;font-size:12px;text-decoration:none">
                ← Back to Record
            </a>
            <button class="btn-print" onclick="window.print()">
                🖨 Print Certificate
            </button>
        </div>
    </div>

    {{-- ══ A4 Certificate Page ══ --}}
    <div class="page">

        {{-- Borders & corners --}}
        <div class="border-outer"></div>
        <div class="border-inner"></div>
        <div class="corner corner-tl"></div>
        <div class="corner corner-tr"></div>
        <div class="corner corner-bl"></div>
        <div class="corner corner-br"></div>

        {{-- Watermark --}}
        <div class="watermark">Official Document</div>

        {{-- Duplicate stamp --}}
        @if ($certificate->is_duplicate)
            <div class="duplicate-stamp">DUPLICATE</div>
        @endif

        <div class="content">

            {{-- ── HEADER ── --}}
            <div class="header">
                <div class="hospital-logo-row">
                    <div class="logo-circle">✚</div>
                    <div>
                        <div class="hospital-name">{{ $layoutsSetting['hospital_name'] ?? 'City General Hospital' }}
                        </div>
                        <div class="hospital-meta">
                            {{ $layoutsSetting['hospital_address'] ?? 'Main Road, City — Pakistan' }}<br>
                            Tel: {{ $layoutsSetting['hospital_phone'] ?? '+92-XXX-XXXXXXX' }} &nbsp;|&nbsp;
                            Reg. No: {{ $layoutsSetting['hospital_reg'] ?? 'PMDC/HOSP/XXXX' }}
                        </div>
                    </div>
                </div>

                <div class="divider-ornament">✦</div>

                <div class="cert-title">
                    Death Certificate
                    @if ($certificate->mortuaryRecord->is_medico_legal)
                        <span class="mlc-badge">MLC</span>
                    @endif
                </div>
                <div class="cert-subtitle">{{ $certificate->certificate_type }}</div>

                <div class="cert-meta-row">
                    <div>Certificate No: <span class="val">{{ $certificate->certificate_number }}</span></div>
                    <div>Issue Date: <span
                            class="val">{{ \Carbon\Carbon::parse($certificate->issued_at)->format('d F Y') }}</span>
                    </div>
                    <div>Copy: <span class="val">{{ $certificate->copy_number }} of
                            {{ $certificate->total_copies }}</span></div>
                </div>
            </div>

            {{-- ── DECEASED INFORMATION ── --}}
            <div class="sec-heading">I. Deceased Information</div>
            <div class="info-grid">
                <div class="info-row">
                    <span class="lbl">Full Name</span>
                    <span class="val">{{ $certificate->mortuaryRecord->patient->name }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Father's Name</span>
                    <span class="val">{{ $certificate->mortuaryRecord->patient->father_name ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Date of Birth</span>
                    <span class="val">
                        {{ $certificate->mortuaryRecord->patient->date_of_birth
                            ? \Carbon\Carbon::parse($certificate->mortuaryRecord->patient->date_of_birth)->format('d M Y')
                            : '—' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="lbl">Age at Death</span>
                    <span class="val">{{ $certificate->mortuaryRecord->patient->age }} Years</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Gender</span>
                    <span class="val">{{ $certificate->mortuaryRecord->patient->gender }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">CNIC</span>
                    <span class="val">{{ $certificate->mortuaryRecord->patient->cnic ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">MRN / Patient ID</span>
                    <span class="val">{{ $certificate->mortuaryRecord->patient->mrn }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Blood Group</span>
                    <span class="val">{{ $certificate->mortuaryRecord->patient->blood_group ?? '—' }}</span>
                </div>
                <div class="info-row" style="grid-column: span 2">
                    <span class="lbl">Permanent Address</span>
                    <span class="val">{{ $certificate->mortuaryRecord->patient->address ?? '—' }}</span>
                </div>
            </div>

            {{-- ── DEATH DETAILS ── --}}
            <div class="sec-heading">II. Death Details</div>
            <div class="info-grid triple">
                <div class="info-row">
                    <span class="lbl">Date of Death</span>
                    <span
                        class="val">{{ \Carbon\Carbon::parse($certificate->mortuaryRecord->death_datetime)->format('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Time of Death</span>
                    <span
                        class="val">{{ \Carbon\Carbon::parse($certificate->mortuaryRecord->death_datetime)->format('h:i A') }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Manner of Death</span>
                    <span class="val">{{ $certificate->mortuaryRecord->manner_of_death }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Place of Death</span>
                    <span class="val">{{ $certificate->mortuaryRecord->death_location }}</span>
                </div>
                @if ($certificate->mortuaryRecord->ward)
                    <div class="info-row">
                        <span class="lbl">Ward / Unit</span>
                        <span class="val">{{ $certificate->mortuaryRecord->ward }}
                            @if ($certificate->mortuaryRecord->bed_number)
                                / Bed {{ $certificate->mortuaryRecord->bed_number }}
                            @endif
                        </span>
                    </div>
                @endif
                <div class="info-row">
                    <span class="lbl">Mortuary ID</span>
                    <span class="val">{{ $certificate->mortuaryRecord->mortuary_id }}</span>
                </div>
            </div>

            {{-- ── CAUSE OF DEATH ── --}}
            <div class="sec-heading">III. Medical Certificate of Cause of Death</div>
            <div class="cause-box">
                <div class="cause-box-header">Cause of Death — ICD-10 Classification</div>
                <div class="cause-row">
                    <div class="cause-part-label">Part I (a)<br><span
                            style="font-weight:400;font-size:7.5px;color:#aaa">Immediate Cause</span></div>
                    <div class="cause-part-val">{{ $certificate->mortuaryRecord->immediate_cause }}</div>
                </div>
                <div class="cause-row">
                    <div class="cause-part-label">Part I (b)<br><span
                            style="font-weight:400;font-size:7.5px;color:#aaa">Intermediate Cause</span></div>
                    <div class="cause-part-val">
                        {{ $certificate->mortuaryRecord->intermediate_cause ?? '' }}
                        @if (!$certificate->mortuaryRecord->intermediate_cause)
                            <span class="cause-part-empty">Not specified</span>
                        @endif
                    </div>
                </div>
                <div class="cause-row">
                    <div class="cause-part-label">Part I (c)<br><span
                            style="font-weight:400;font-size:7.5px;color:#aaa">Underlying Cause</span></div>
                    <div class="cause-part-val">
                        {{ $certificate->mortuaryRecord->underlying_cause ?? '' }}
                        @if (!$certificate->mortuaryRecord->underlying_cause)
                            <span class="cause-part-empty">Not specified</span>
                        @endif
                    </div>
                </div>
                <div class="cause-row">
                    <div class="cause-part-label">Part II<br><span
                            style="font-weight:400;font-size:7.5px;color:#aaa">Contributing Cause</span></div>
                    <div class="cause-part-val">
                        {{ $certificate->mortuaryRecord->contributing_cause ?? '' }}
                        @if (!$certificate->mortuaryRecord->contributing_cause)
                            <span class="cause-part-empty">None</span>
                        @endif
                    </div>
                </div>
                @if ($certificate->mortuaryRecord->postmortem_required)
                    <div class="cause-row" style="background:#fff8f8">
                        <div class="cause-part-label">Postmortem<br><span
                                style="font-weight:400;font-size:7.5px;color:#aaa">Status</span></div>
                        <div class="cause-part-val" style="color:#7f1d1d">
                            {{ $certificate->mortuaryRecord->postmortem_status }}
                            @if ($certificate->mortuaryRecord->postmortem_report_number)
                                — Report No: {{ $certificate->mortuaryRecord->postmortem_report_number }}
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── MLC INFO ── --}}
            @if ($certificate->mortuaryRecord->is_medico_legal)
                <div class="sec-heading">IV. Medico Legal Information</div>
                <div class="info-grid triple">
                    <div class="info-row">
                        <span class="lbl">MLC Number</span>
                        <span class="val">{{ $certificate->mortuaryRecord->mlc_number ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Police Station</span>
                        <span class="val">{{ $certificate->mortuaryRecord->police_station ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">FIR Number</span>
                        <span class="val">{{ $certificate->mortuaryRecord->fir_number ?? '—' }}</span>
                    </div>
                </div>
            @endif

            {{-- ── ISSUED TO ── --}}
            <div class="sec-heading">{{ $certificate->mortuaryRecord->is_medico_legal ? 'V' : 'IV' }}. Certificate
                Issued To</div>
            <div class="issued-box">
                <div class="info-grid">
                    <div class="info-row">
                        <span class="lbl">Name</span>
                        <span class="val">{{ $certificate->issued_to_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Relation to Deceased</span>
                        <span class="val">{{ $certificate->issued_to_relation }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">CNIC</span>
                        <span class="val">{{ $certificate->issued_to_cnic ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Phone</span>
                        <span class="val">{{ $certificate->issued_to_phone ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Purpose</span>
                        <span class="val">{{ $certificate->purpose }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Issued By (Staff)</span>
                        <span class="val">{{ $certificate->issuedBy?->first_name }}
                            {{ $certificate->issuedBy?->last_name }}</span>
                    </div>
                </div>
            </div>

            {{-- ── DECLARATION ── --}}
            <div class="declaration">
                This is to certify that <strong>{{ $certificate->mortuaryRecord->patient->name }}</strong>,
                {{ $certificate->mortuaryRecord->patient->gender }}, aged
                <strong>{{ $certificate->mortuaryRecord->patient->age }} years</strong>,
                was declared dead on
                <strong>{{ \Carbon\Carbon::parse($certificate->mortuaryRecord->death_datetime)->format('d F Y') }}</strong>
                at
                <strong>{{ \Carbon\Carbon::parse($certificate->mortuaryRecord->death_datetime)->format('h:i A') }}</strong>
                at <strong>{{ config('app.hospital_name', 'City General Hospital') }}</strong>.
                The cause of death as recorded is <strong>{{ $certificate->mortuaryRecord->immediate_cause }}</strong>.
                This certificate is issued on the request of the Next of Kin for the purpose of
                <strong>{{ $certificate->purpose }}</strong>.
            </div>

            {{-- ── SIGNATURES ── --}}
            <div class="sig-section">
                {{-- Signing Doctor --}}
                <div class="sig-box">
                    <div class="sig-space"></div>
                    <div class="sig-name">
                        Dr. {{ $certificate->signingDoctor->employee->first_name }}
                        {{ $certificate->signingDoctor->employee->last_name }}
                    </div>
                    <div class="sig-title">Certifying Doctor</div>
                    <div class="sig-reg">{{ $certificate->signingDoctor->specialization }}</div>
                    @if ($certificate->signingDoctor->pmdc_number)
                        <div class="sig-reg">PMDC: {{ $certificate->signingDoctor->pmdc_number }}</div>
                    @endif
                </div>

                {{-- Verified By --}}
                <div class="sig-box">
                    <div class="sig-space"></div>
                    <div class="sig-name">
                        @if ($certificate->verifiedBy)
                            {{ $certificate->verifiedBy->first_name }} {{ $certificate->verifiedBy->last_name }}
                        @else
                            ______________________
                        @endif
                    </div>
                    <div class="sig-title">Medical Superintendent / MS</div>
                    <div class="sig-reg">
                        @if ($certificate->is_verified)
                            Verified on {{ \Carbon\Carbon::parse($certificate->verified_at)->format('d M Y') }}
                        @else
                            Pending Verification
                        @endif
                    </div>
                </div>

                {{-- Hospital Stamp --}}
                <div class="sig-box">
                    <div class="sig-space"
                        style="border: 1.5px dashed #ccc; display:flex; align-items:center; justify-content:center; color:#ccc; font-size:9px; letter-spacing:.5px">
                        HOSPITAL STAMP
                    </div>
                    <div class="sig-name">Authorized Signatory</div>
                    <div class="sig-title">{{ config('app.hospital_name', 'City General Hospital') }}</div>
                </div>
            </div>

            {{-- ── FOOTER ── --}}
            <div class="cert-footer">
                <div class="footer-note">
                    <strong>IMPORTANT:</strong> This certificate is issued for official purposes only.
                    Any tampering, alteration or misuse of this document is a criminal offence under
                    Pakistani law. This document should be registered with NADRA within 60 days of death.<br>
                    Generated: {{ now()->format('d M Y, h:i A') }}
                    @if ($certificate->remarks)
                        | Remarks: {{ $certificate->remarks }}
                    @endif
                </div>
                <div class="qr-placeholder">
                    QR<br>Verify
                </div>
            </div>

        </div>{{-- end content --}}
    </div>{{-- end page --}}

    <script>
        // Auto focus for keyboard shortcut
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>

</body>

</html>
