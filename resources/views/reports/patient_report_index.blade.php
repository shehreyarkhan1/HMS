@extends('layouts.master')

@section('title', 'Patient Reports')
@section('page-title', 'Reports — Patient Search')
@section('breadcrumb', 'Home / Reports / Patients')

@push('styles')
    <style>
        .stat-pill {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 20px
        }

        .stat-pill-label {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em
        }

        .stat-pill-value {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b
        }

        .card-section {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden
        }

        .card-section-header {
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px
        }

        .filter-bar input,
        .filter-bar select {
            height: 36px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            padding: 0 12px;
            color: #374151;
            background: #f8fafc
        }

        .filter-bar input:focus,
        .filter-bar select:focus {
            outline: none;
            border-color: #93c5fd;
            background: #fff
        }

        .table-clean th {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 10px 16px !important;
            background: #f8fafc
        }

        .table-clean td {
            font-size: 13px;
            color: #374151;
            padding: 12px 16px !important;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9 !important
        }

        .table-clean tr:last-child td {
            border-bottom: none !important
        }

        .table-clean tr:hover td {
            background: #fafafa
        }

        .pt-Active {
            background: #dcfce7;
            color: #166534;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .pt-Admitted {
            background: #dbeafe;
            color: #1e40af;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .pt-Discharged {
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .pt-Deceased {
            background: #fee2e2;
            color: #991b1b;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }

        .type-OPD {
            background: #e0f2fe;
            color: #0369a1;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 500
        }

        .type-IPD {
            background: #fef3c7;
            color: #92400e;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 500
        }

        .type-Emergency {
            background: #fee2e2;
            color: #991b1b;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 500
        }

        .search-hero {
            background: linear-gradient(135deg, #1f244d 0%, #343b2f 100%);
            border-radius: 12px;
            padding: 32px 28px;
            margin-bottom: 24px;
            color: #fff
        }

        .search-hero h4 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 6px
        }

        .search-hero p {
            font-size: 13px;
            opacity: .8;
            margin: 0 0 18px
        }

        .search-hero .search-input {
            height: 44px;
            border-radius: 10px;
            border: none;
            font-size: 14px;
            padding: 0 16px;
            width: 340px;
            max-width: 100%;
            color: #1e293b
        }

        .search-hero .search-input:focus {
            outline: 2px solid #93c5fd
        }

        .search-hero .btn-search {
            height: 44px;
            border-radius: 10px;
            background: #fff;
            color: #1d4ed8;
            font-weight: 600;
            font-size: 14px;
            border: none;
            padding: 0 22px;
            cursor: pointer
        }

        .search-hero .btn-search:hover {
            background: #eff6ff
        }

        .mini-count {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            padding: 2px 7px;
            border-radius: 8px;
            font-weight: 500;
            min-width: 22px;
            text-align: center
        }
    </style>
@endpush

@section('content')

    {{-- Search Hero --}}
    <div class="search-hero">
        <h4><i class="bi bi-file-earmark-person-fill me-2"></i>Patient Full Report</h4>
        <p>Search by MRN, patient name, phone, or CNIC to view complete medical history</p>
        <form method="GET" action="{{ route('reports.patients.index') }}" class="d-flex gap-2 flex-wrap align-items-center">
            <input type="text" name="search" class="search-input" placeholder="MRN-00001, patient name, 0300-..."
                value="{{ request('search') }}" autofocus>
            <button type="submit" class="btn-search">
                <i class="bi bi-search me-1"></i>Search
            </button>
            @if (request('search'))
                <a href="{{ route('reports.patients.index') }}" style="color:#bfdbfe;font-size:13px;text-decoration:none">
                    <i class="bi bi-x-circle me-1"></i>Clear
                </a>
            @endif
        </form>
    </div>

    @if (request('search'))

        @if ($patients->count())

            {{-- Result count --}}
            <p style="font-size:13px;color:#64748b;margin-bottom:12px">
                <strong>{{ $patients->total() }}</strong> patient(s) found for
                "<strong>{{ request('search') }}</strong>"
            </p>

            <div class="card-section">
                <div class="card-section-header">
                    <span style="font-size:14px;font-weight:600;color:#1e293b">
                        <i class="bi bi-people-fill me-2 text-primary"></i>Search results
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-clean mb-0">
                        <thead>
                            <tr>
                                <th>MRN</th>
                                <th>Patient</th>
                                <th>Age / Gender</th>
                                <th>Blood Group</th>
                                <th>Phone</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th style="text-align:center">Visits</th>
                                <th style="text-align:center">Labs</th>
                                <th style="text-align:center">Radiology</th>
                                <th style="text-align:center">Bills</th>
                                <th style="text-align:right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patients as $p)
                                <tr>
                                    <td>
                                        <code style="font-size:12px;color:#6366f1">{{ $p->mrn }}</code>
                                    </td>
                                    <td>
                                        <div style="font-weight:500;color:#1e293b">{{ $p->name }}</div>
                                        @if ($p->father_name)
                                            <div style="font-size:11px;color:#94a3b8">S/O {{ $p->father_name }}</div>
                                        @endif
                                    </td>
                                    <td style="font-size:12px;color:#64748b">
                                        {{ $p->date_of_birth ? \Carbon\Carbon::parse($p->date_of_birth)->age . ' yrs' : '—' }}
                                        / {{ $p->gender }}
                                    </td>
                                    <td>
                                        @if ($p->blood_group)
                                            <span
                                                style="background:#fee2e2;color:#991b1b;font-size:11px;padding:2px 8px;border-radius:8px;font-weight:600">
                                                {{ $p->blood_group }}
                                            </span>
                                        @else
                                            <span style="color:#94a3b8">—</span>
                                        @endif
                                    </td>
                                    <td style="font-size:12px;color:#64748b">{{ $p->phone }}</td>
                                    <td><span class="type-{{ $p->patient_type }}">{{ $p->patient_type }}</span></td>
                                    <td><span class="pt-{{ $p->status }}">{{ $p->status }}</span></td>
                                    <td style="text-align:center">
                                        <span class="mini-count">{{ $p->appointments_count }}</span>
                                    </td>
                                    <td style="text-align:center">
                                        <span class="mini-count">{{ $p->lab_orders_count }}</span>
                                    </td>
                                    <td style="text-align:center">
                                        <span class="mini-count">{{ $p->radiology_orders_count }}</span>
                                    </td>
                                    <td style="text-align:center">
                                        <span class="mini-count">{{ $p->bills_count }}</span>
                                    </td>
                                    <td style="text-align:right">
                                        <a href="{{ route('reports.patients.show', $p->id) }}"
                                            class="btn btn-sm btn-primary px-3"
                                            style="font-size:12px;height:30px;display:inline-flex;align-items:center;gap:4px">
                                            <i class="bi bi-file-earmark-text-fill"></i> Full Report
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($patients->hasPages())
                    <div class="d-flex justify-content-between align-items-center px-4 py-3"
                        style="border-top:1px solid #e2e8f0">
                        <span style="font-size:12px;color:#94a3b8">
                            Showing {{ $patients->firstItem() }}–{{ $patients->lastItem() }}
                            of {{ $patients->total() }}
                        </span>
                        {{ $patients->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        @else
            <div class="card-section">
                <div class="text-center py-5" style="color:#94a3b8">
                    <i class="bi bi-person-x" style="font-size:42px;display:block;margin-bottom:10px;opacity:.3"></i>
                    <div style="font-size:14px">No patient found for "<strong>{{ request('search') }}</strong>"</div>
                    <div style="font-size:12px;margin-top:6px">Try MRN number, full name, or phone number</div>
                </div>
            </div>
        @endif
    @else
        {{-- Initial state --}}
        <div class="card-section">
            <div class="text-center py-5" style="color:#94a3b8">
                <i class="bi bi-search" style="font-size:42px;display:block;margin-bottom:10px;opacity:.2"></i>
                <div style="font-size:14px">Search a patient to view their complete report</div>
                <div style="font-size:12px;margin-top:6px">Use MRN, name, CNIC, or phone number above</div>
            </div>
        </div>
    @endif

@endsection
