@extends('layouts.master')

@section('title', 'Mortuary')
@section('page-title', 'Mortuary Management')
@section('breadcrumb', 'Home / Mortuary')

@push('styles')
<style>
    .stat-pill {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 20px;
    }
    .stat-pill-label { font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; }
    .stat-pill-value { font-size: 22px; font-weight: 700; color: #1e293b; }

    .card-section { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    .card-section-header { padding: 14px 20px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }

    .filter-bar { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .filter-bar input, .filter-bar select {
        height: 36px; border: 1px solid #e2e8f0; border-radius: 8px;
        font-size: 13px; padding: 0 12px; color: #374151; background: #f8fafc;
    }
    .filter-bar input:focus, .filter-bar select:focus {
        outline: none; border-color: #93c5fd; background: #fff;
    }

    .table-clean th {
        font-size: 11px; font-weight: 600; color: #94a3b8;
        text-transform: uppercase; letter-spacing: .05em;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 10px 16px !important; background: #f8fafc;
    }
    .table-clean td {
        font-size: 13px; color: #374151;
        padding: 12px 16px !important; vertical-align: middle;
        border-bottom: 1px solid #f1f5f9 !important;
    }
    .table-clean tr:last-child td { border-bottom: none !important; }
    .table-clean tr:hover td { background: #fafafa; }

    .badge-status { font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 500; white-space: nowrap; }
    .badge-Admitted             { background: #dbeafe; color: #1e40af; }
    .badge-Postmortem-Pending   { background: #fef9c3; color: #854d0e; }
    .badge-Postmortem-Done      { background: #e0f2fe; color: #0369a1; }
    .badge-Certificate-Issued   { background: #ede9fe; color: #5b21b6; }
    .badge-Released             { background: #dcfce7; color: #166534; }
    .badge-Transferred          { background: #f3f4f6; color: #374151; }
    .badge-Unclaimed            { background: #fee2e2; color: #991b1b; }

    .manner-pill { font-size: 11px; padding: 2px 8px; border-radius: 6px; font-weight: 500; }
    .manner-Natural      { background: #f0fdf4; color: #15803d; }
    .manner-Accidental   { background: #fff7ed; color: #c2410c; }
    .manner-Homicidal    { background: #fee2e2; color: #991b1b; }
    .manner-Suicidal     { background: #fef2f2; color: #b91c1c; }
    .manner-Undetermined { background: #f3f4f6; color: #374151; }

    .mlc-badge {
        font-size: 10px; padding: 2px 7px; border-radius: 4px;
        background: #fee2e2; color: #991b1b; font-weight: 700;
        letter-spacing: .04em;
    }
</style>
@endpush

@section('content')

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Total Records</div>
                <div class="stat-pill-value">{{ number_format($stats['total']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">In Mortuary</div>
                <div class="stat-pill-value" style="color:#1e40af">{{ number_format($stats['admitted']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Postmortem Pending</div>
                <div class="stat-pill-value" style="color:#854d0e">{{ number_format($stats['pending_postmortem']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">MLC Active</div>
                <div class="stat-pill-value" style="color:#991b1b">{{ number_format($stats['mlc']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Unclaimed</div>
                <div class="stat-pill-value" style="color:#b91c1c">{{ number_format($stats['unclaimed']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Today's Deaths</div>
                <div class="stat-pill-value" style="color:#374151">{{ number_format($stats['today']) }}</div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card-section">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-journal-medical me-2 text-secondary"></i>Mortuary Records
            </span>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <form method="GET" action="{{ route('mortuary.index') }}" class="filter-bar">
                    <input type="text" name="search" placeholder="Search ID, patient, MRN..."
                           value="{{ request('search') }}" style="width:200px">

                    <select name="status">
                        <option value="">All Status</option>
                        @foreach(['Admitted','Postmortem Pending','Postmortem Done','Certificate Issued','Released','Transferred','Unclaimed'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>

                    <select name="manner">
                        <option value="">All Manner</option>
                        @foreach(['Natural','Accidental','Homicidal','Suicidal','Undetermined'] as $m)
                            <option value="{{ $m }}" {{ request('manner') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>

                    <select name="mlc">
                        <option value="">MLC / Non-MLC</option>
                        <option value="yes" {{ request('mlc') == 'yes' ? 'selected' : '' }}>MLC Only</option>
                        <option value="no"  {{ request('mlc') == 'no'  ? 'selected' : '' }}>Non-MLC Only</option>
                    </select>

                    <input type="date" name="date_from" value="{{ request('date_from') }}" title="From Date">
                    <input type="date" name="date_to"   value="{{ request('date_to') }}"   title="To Date">

                    <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>

                    @if(request()->hasAny(['search','status','manner','mlc','date_from','date_to']))
                        <a href="{{ route('mortuary.index') }}"
                           class="btn btn-sm btn-outline-secondary px-3" style="height:36px;font-size:13px">Clear</a>
                    @endif
                </form>

                <a href="{{ route('mortuary.create') }}"
                   class="btn btn-sm btn-danger px-3" style="height:36px;font-size:13px">
                    <i class="bi bi-plus-lg me-1"></i>New Record
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-clean mb-0">
                <thead>
                    <tr>
                        <th>Mortuary ID</th>
                        <th>Patient</th>
                        <th>Death Date/Time</th>
                        <th>Location</th>
                        <th>Cause of Death</th>
                        <th>Manner</th>
                        <th>Declaring Doctor</th>
                        <th>Status</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:6px">
                                <code style="font-size:12px;color:#6366f1">{{ $record->mortuary_id }}</code>
                                @if($record->is_medico_legal)
                                    <span class="mlc-badge">MLC</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:500;color:#1e293b">{{ $record->patient->name }}</div>
                            <div style="font-size:11px;color:#94a3b8">{{ $record->patient->mrn }} · {{ $record->patient->age }} yrs / {{ $record->patient->gender }}</div>
                        </td>
                        <td>
                            <div style="font-weight:500">{{ \Carbon\Carbon::parse($record->death_datetime)->format('d M Y') }}</div>
                            <div style="font-size:11px;color:#94a3b8">{{ \Carbon\Carbon::parse($record->death_datetime)->format('h:i A') }}</div>
                        </td>
                        <td>
                            <span style="font-size:12px">{{ $record->death_location }}</span>
                            @if($record->ward)
                                <div style="font-size:11px;color:#94a3b8">{{ $record->ward }} {{ $record->bed_number ? '/ Bed '.$record->bed_number : '' }}</div>
                            @endif
                        </td>
                        <td style="max-width:160px">
                            <div style="font-size:12px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="{{ $record->immediate_cause }}">
                                {{ $record->immediate_cause }}
                            </div>
                        </td>
                        <td>
                            <span class="manner-pill manner-{{ str_replace(' ', '', $record->manner_of_death) }}">
                                {{ $record->manner_of_death }}
                            </span>
                        </td>
                        <td>
                            {{ $record->declaringDoctor?->employee?->first_name ?? '—' }}
                            {{ $record->declaringDoctor?->employee?->last_name ?? '' }}
                        </td>
                        <td>
                            @php $statusClass = str_replace(' ', '-', $record->status); @endphp
                            <span class="badge-status badge-{{ $statusClass }}">
                                {{ $record->status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('mortuary.show', $record->id) }}"
                                   class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($record->status !== 'Released')
                                <a href="{{ route('mortuary.edit', $record->id) }}"
                                   class="btn btn-sm btn-outline-warning py-0 px-2" style="font-size:12px" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif
                                @if(!in_array($record->status, ['Released','Certificate Issued']))
                                <form method="POST" action="{{ route('mortuary.destroy', $record->id) }}"
                                      onsubmit="return confirm('Delete mortuary record {{ $record->mortuary_id }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:12px" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5" style="color:#94a3b8">
                            <i class="bi bi-journal-medical" style="font-size:40px;display:block;margin-bottom:8px;opacity:.3"></i>
                            No mortuary records found.
                            <a href="{{ route('mortuary.create') }}" class="text-primary ms-1">Add first record</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
        <div class="d-flex justify-content-between align-items-center px-4 py-3"
             style="border-top:1px solid #e2e8f0">
            <span style="font-size:12px;color:#94a3b8">
                Showing {{ $records->firstItem() }}–{{ $records->lastItem() }}
                of {{ $records->total() }} records
            </span>
            {{ $records->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

@endsection
