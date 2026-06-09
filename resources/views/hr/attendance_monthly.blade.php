@extends('layouts.master')
@section('title', 'Monthly Attendance Grid')
@section('page-title', 'Monthly Attendance Grid')
@section('breadcrumb', 'HR / Attendance / Monthly')

@push('styles')
<style>
    .section-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden}
    .section-header{padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between}
    .section-title{font-size:14px;font-weight:600;color:#1e293b}
    .grid-wrap{overflow-x:auto}
    table{border-collapse:separate;border-spacing:0;min-width:900px}
    table thead th{
        font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;
        color:#94a3b8;background:#f8fafc;padding:8px 6px!important;
        text-align:center;border-bottom:1px solid #e2e8f0!important;
        border-right:1px solid #f1f5f9!important;white-space:nowrap;
    }
    table thead th.emp-col{text-align:left;min-width:160px;position:sticky;left:0;background:#f8fafc;z-index:2}
    table tbody td{
        font-size:11px;padding:6px!important;text-align:center;
        border-bottom:1px solid #f8fafc!important;border-right:1px solid #f8fafc!important;
        vertical-align:middle!important;
    }
    table tbody td.emp-col{
        text-align:left;position:sticky;left:0;background:#fff;z-index:1;
        padding:6px 10px!important;min-width:160px;
    }
    .cell{width:26px;height:26px;border-radius:6px;font-size:10px;font-weight:700;display:inline-flex;align-items:center;justify-content:center;cursor:default;transition:transform .1s}
    .cell:hover{transform:scale(1.15)}
    .c-P{background:#dcfce7;color:#16a34a}
    .c-A{background:#fee2e2;color:#dc2626}
    .c-L{background:#fef3c7;color:#b45309}
    .c-H{background:#f1f5f9;color:#64748b}
    .c-W{background:#ede9fe;color:#7c3aed}
    .c-WFH{background:#dbeafe;color:#1d4ed8;font-size:8px}
    .c-HD{background:#fef9c3;color:#a16207}
    .c-hol{background:#dcfce7;color:#16a34a}
    .c-emp{background:#f1f5f9;color:#cbd5e1}
    .c-weekend{background:#f8fafc;color:#cbd5e1}
    .emp-cell-inner{display:flex;align-items:center;gap:8px}
    .emp-av{width:28px;height:28px;border-radius:50%;background:#dbeafe;color:#1d4ed8;font-size:10px;font-weight:600;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .emp-name{font-size:12px;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:110px}
    .legend-dot{width:18px;height:18px;border-radius:4px;display:inline-flex;align-items:center;justify-content:center;font-size:9px;font-weight:700}
    .nav-btn{width:30px;height:30px;border-radius:8px;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;color:#64748b;text-decoration:none;font-size:13px;transition:all .15s}
    .nav-btn:hover{background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe}
</style>
@endpush

@section('content')
@php
    use Carbon\Carbon;
    $monthName = Carbon::createFromDate($year, $month)->format('F Y');
    $prevMonth = Carbon::createFromDate($year, $month)->subMonth();
    $nextMonth = Carbon::createFromDate($year, $month)->addMonth();
@endphp

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-semibold" style="color:#1e293b">Monthly Attendance Grid</h5>
        <p class="mb-0" style="font-size:13px;color:#94a3b8">{{ $monthName }} — all employees</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('hr.attendance.monthly', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" class="nav-btn"><i class="bi bi-chevron-left"></i></a>
        <span style="font-size:14px;font-weight:700;color:#1e293b;padding:0 4px">{{ $monthName }}</span>
        <a href="{{ route('hr.attendance.monthly', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" class="nav-btn"><i class="bi bi-chevron-right"></i></a>
        <a href="{{ route('hr.attendance.index', ['month' => $month, 'year' => $year]) }}" class="btn btn-light btn-sm"><i class="bi bi-list me-1"></i>List View</a>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <span class="section-title"><i class="bi bi-grid-3x3-gap me-2" style="color:#1d4ed8"></i>Attendance Matrix</span>
        <span style="font-size:12px;color:#94a3b8">{{ $employees->count() }} employees &middot; {{ count($dates) }} days</span>
    </div>
    <div class="grid-wrap">
        <table>
            <thead>
                <tr>
                    <th class="emp-col">Employee</th>
                    @foreach($dates as $date)
                    @php
                        $isWeekend = $date->isFriday() || $date->isSaturday();
                        $isHoliday = in_array($date->format('Y-m-d'), $holidays);
                        $isToday   = $date->isToday();
                    @endphp
                    <th style="{{ $isToday ? 'background:#eff6ff;color:#1d4ed8' : ($isWeekend ? 'color:#ef4444' : '') }}">
                        {{ $date->format('d') }}<br>{{ $date->format('D') }}
                    </th>
                    @endforeach
                    <th>P</th><th>A</th><th>L</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $emp)
                @php
                    $empAtt  = $attendances[$emp->id] ?? collect();
                    $pCnt = 0; $aCnt = 0; $lCnt = 0;
                @endphp
                <tr>
                    <td class="emp-col">
                        <div class="emp-cell-inner">
                            <div class="emp-av">{{ strtoupper(substr($emp->first_name,0,1).substr($emp->last_name,0,1)) }}</div>
                            <div>
                                <div class="emp-name">{{ $emp->first_name }} {{ $emp->last_name }}</div>
                                <div style="font-size:10px;color:#94a3b8">{{ $emp->employee_id }}</div>
                            </div>
                        </div>
                    </td>
                    @foreach($dates as $date)
                    @php
                        $key = $date->format('Y-m-d');
                        $isWeekend = $date->isFriday() || $date->isSaturday();
                        $isHoliday = in_array($key, $holidays);
                        $record    = $empAtt[$key] ?? null;
                        if ($record) {
                            $status = $record->status;
                            $cellClass = match($status) {
                                'Present'        => 'c-P',
                                'Absent'         => 'c-A',
                                'Late'           => 'c-L',
                                'On Leave'       => 'c-W',
                                'Half Day'       => 'c-HD',
                                'Work From Home' => 'c-WFH',
                                'Holiday'        => 'c-hol',
                                default          => 'c-weekend',
                            };
                            $cellText = match($status) {
                                'Present'        => 'P',
                                'Absent'         => 'A',
                                'Late'           => 'L',
                                'On Leave'       => 'OL',
                                'Half Day'       => 'HD',
                                'Work From Home' => 'WFH',
                                'Holiday'        => 'H',
                                default          => 'W',
                            };
                            if (in_array($status, ['Present','Late','Work From Home'])) $pCnt++;
                            elseif ($status === 'Absent') $aCnt++;
                            elseif ($status === 'On Leave') $lCnt++;
                        } elseif ($isHoliday) {
                            $cellClass = 'c-hol'; $cellText = 'H';
                        } elseif ($isWeekend) {
                            $cellClass = 'c-weekend'; $cellText = 'W';
                        } else {
                            $cellClass = 'c-emp'; $cellText = '—';
                        }
                        $title = $record ? $record->status . ($record->check_in ? ' | In: '.\Carbon\Carbon::parse($record->check_in)->format('H:i') : '') : ($isHoliday ? 'Holiday' : ($isWeekend ? 'Weekend' : 'Not marked'));
                    @endphp
                    <td title="{{ $title }}">
                        <div class="cell {{ $cellClass }}">{{ $cellText }}</div>
                    </td>
                    @endforeach
                    <td><span style="font-size:11px;font-weight:700;color:#16a34a">{{ $pCnt }}</span></td>
                    <td><span style="font-size:11px;font-weight:700;color:#dc2626">{{ $aCnt }}</span></td>
                    <td><span style="font-size:11px;font-weight:700;color:#7c3aed">{{ $lCnt }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- Legend --}}
    <div style="padding:.75rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;gap:10px;flex-wrap:wrap;align-items:center">
        @foreach([
            ['c-P','P','Present'],['c-A','A','Absent'],['c-L','L','Late'],
            ['c-W','OL','On Leave'],['c-HD','HD','Half Day'],['c-WFH','WFH','WFH'],
            ['c-hol','H','Holiday'],['c-weekend','W','Weekend'],
        ] as [$cls,$lbl,$full])
        <div style="display:flex;align-items:center;gap:4px;font-size:11px;color:#64748b">
            <div class="cell {{ $cls }}" style="width:20px;height:20px;font-size:9px">{{ $lbl }}</div>
            {{ $full }}
        </div>
        @endforeach
    </div>
</div>
@endsection
