@extends('layouts.master')
@section('title', 'Attendance Summary')
@section('page-title', 'Attendance Summary')
@section('breadcrumb', 'HR / Attendance / Summary')

@push('styles')
    <style>
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            border: 1px solid #e2e8f0;
            height: 100%;
            transition: box-shadow .15s
        }

        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, .07)
        }

        .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: .5rem
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
            margin-bottom: .3rem
        }

        .stat-sub {
            font-size: 12px;
            color: #94a3b8
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0
        }

        .section-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-bottom: 1rem
        }

        .section-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b
        }

        .att-row {
            display: flex;
            align-items: center;
            padding: .65rem 1.25rem;
            border-bottom: 1px solid #f8fafc;
            gap: 12px;
            transition: background .1s
        }

        .att-row:last-child {
            border-bottom: none
        }

        .att-row:hover {
            background: #f8fafc
        }

        .status-badge {
            font-size: 10px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 20px;
            white-space: nowrap
        }

        .s-present {
            background: #dcfce7;
            color: #16a34a
        }

        .s-absent {
            background: #fee2e2;
            color: #dc2626
        }

        .s-late {
            background: #fef3c7;
            color: #b45309
        }

        .s-leave {
            background: #ede9fe;
            color: #7c3aed
        }

        .s-half {
            background: #dbeafe;
            color: #1d4ed8
        }

        .s-holiday {
            background: #f0fdf4;
            color: #16a34a
        }

        .s-wfh {
            background: #e0f2fe;
            color: #0284c7
        }

        .s-weekend {
            background: #f1f5f9;
            color: #94a3b8
        }
    </style>
@endpush

@section('content')
    @php
        $monthName = \Carbon\Carbon::createFromDate($year, $month)->format('F Y');
        $totalHours = floor($summary['total_working_minutes'] / 60);
        $totalMins = $summary['total_working_minutes'] % 60;
        $otHours = floor($summary['total_overtime_minutes'] / 60);
        $otMins = $summary['total_overtime_minutes'] % 60;
    @endphp

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 class="mb-0 fw-semibold" style="color:#1e293b">
                {{ $employee->first_name }} {{ $employee->last_name }} — Attendance
            </h5>
            <p class="mb-0" style="font-size:13px;color:#94a3b8">
                {{ $monthName }} &middot; {{ $employee->employee_id }} &middot; {{ $employee->department }}
            </p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <form method="GET" class="d-flex gap-2">
                <select name="month" class="form-select form-select-sm" style="width:auto">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('M') }}</option>
                    @endfor
                </select>
                <input type="number" name="year" class="form-control form-control-sm" style="width:80px"
                    value="{{ $year }}">
                <button type="submit" class="btn btn-primary btn-sm">Go</button>
            </form>
            <a href="{{ route('hr.attendance.index') }}" class="btn btn-light btn-sm"><i
                    class="bi bi-arrow-left me-1"></i>Back</a>
        </div>
    </div>

    {{-- Summary stats --}}
    <div class="row g-3 mb-4">
        @foreach ([['Present', $summary['present'], '#dcfce7', '#16a34a', 'bi-person-check', 'days'], ['Late', $summary['late'], '#fef3c7', '#b45309', 'bi-clock-history', 'days'], ['Absent', $summary['absent'], '#fee2e2', '#dc2626', 'bi-person-x', 'days'], ['On Leave', $summary['on_leave'], '#ede9fe', '#7c3aed', 'bi-person-dash', 'days'], ['Work Hrs', "{$totalHours}h {$totalMins}m", '#eff6ff', '#1d4ed8', 'bi-clock', 'total'], ['Overtime', "{$otHours}h {$otMins}m", '#fef3c7', '#b45309', 'bi-clock-fill', 'OT hours']] as [$lbl, $val, $bg, $clr, $ico, $sub])
            <div class="col-6 col-xl-2">
                <div class="stat-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="stat-label">{{ $lbl }}</div>
                            <div class="stat-value">{{ $val }}</div>
                            <div class="stat-sub">{{ $sub }}</div>
                        </div>
                        <div class="stat-icon" style="background:{{ $bg }};color:{{ $clr }}"><i
                                class="bi {{ $ico }}"></i></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Daily records --}}
    <div class="section-card">
        <div class="section-header">
            <span class="section-title"><i class="bi bi-list-ul me-2" style="color:#1d4ed8"></i>Daily Records —
                {{ $monthName }}</span>
            <span style="font-size:12px;color:#94a3b8">{{ $attendances->count() }} records</span>
        </div>

        @forelse($attendances as $att)
            @php
                $statusClass = match ($att->status) {
                    'Present' => 's-present',
                    'Absent' => 's-absent',
                    'Late' => 's-late',
                    'On Leave' => 's-leave',
                    'Half Day' => 's-half',
                    'Holiday' => 's-holiday',
                    'Work From Home' => 's-wfh',
                    default => 's-weekend',
                };
                $workHrs = floor($att->working_minutes / 60);
                $workMin = $att->working_minutes % 60;
            @endphp
            <div class="att-row">
                <div style="min-width:48px;text-align:center">
                    <div style="font-size:18px;font-weight:800;color:#1e293b;line-height:1">
                        {{ \Carbon\Carbon::parse($att->date)->format('d') }}</div>
                    <div style="font-size:10px;color:#94a3b8;text-transform:uppercase">
                        {{ \Carbon\Carbon::parse($att->date)->format('D') }}</div>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:13px;font-weight:500;color:#1e293b">
                        @if ($att->check_in)
                            <i class="bi bi-box-arrow-in-right" style="color:#16a34a"></i>
                            {{ \Carbon\Carbon::parse($att->check_in)->format('H:i') }}
                        @endif
                        @if ($att->check_out)
                            &nbsp; <i class="bi bi-box-arrow-right" style="color:#dc2626"></i>
                            {{ \Carbon\Carbon::parse($att->check_out)->format('H:i') }}
                        @endif
                        @if (!$att->check_in && !$att->check_out)
                            <span style="color:#94a3b8;font-size:12px">No check-in/out</span>
                        @endif
                    </div>
                    <div style="font-size:11px;color:#94a3b8;margin-top:2px">
                        @if ($att->working_minutes)
                            {{ $workHrs }}h {{ $workMin }}m worked
                        @endif
                        @if ($att->overtime_minutes > 0)
                            &middot; OT: {{ floor($att->overtime_minutes / 60) }}h {{ $att->overtime_minutes % 60 }}m
                        @endif
                        @if ($att->late_minutes > 0)
                            &middot; Late by {{ $att->late_minutes }}m
                        @endif
                        @if ($att->notes)
                            &middot; {{ $att->notes }}
                        @endif
                    </div>
                </div>
                <span class="status-badge {{ $statusClass }}">{{ $att->status }}</span>
                @if ($att->is_regularized)
                    <span style="font-size:10px;font-weight:600;color:#7c3aed" title="{{ $att->regularization_reason }}">
                        <i class="bi bi-pencil-square me-1"></i>Regularized
                    </span>
                @endif
            </div>
        @empty
            <div style="padding:3rem;text-align:center;color:#94a3b8;font-size:13px">
                <i class="bi bi-calendar-x" style="font-size:28px;display:block;margin-bottom:.5rem;color:#cbd5e1"></i>
                No records for {{ $monthName }}
            </div>
        @endforelse
    </div>
@endsection
