@extends('layouts.master')

@section('title', 'Appointment Calendar')
@section('page-title', 'Calendar')
@section('breadcrumb', 'Home / Appointments / Calendar')

@push('styles')
<style>
    .cal-wrapper { max-width:1000px; margin:0 auto; }

    /* Header nav */
    .cal-nav { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; }
    .cal-month-title { font-size:20px; font-weight:700; color:#1e293b; }
    .cal-nav-btn {
        height:36px; padding:0 14px;
        border:1px solid #e2e8f0; border-radius:8px;
        background:#fff; color:#374151; font-size:13px;
        display:flex; align-items:center; gap:6px;
        text-decoration:none; transition:background .15s;
    }
    .cal-nav-btn:hover { background:#f8fafc; color:#1e293b; }

    /* Grid */
    .cal-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:1px; background:#e2e8f0; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; }
    .cal-day-header { background:#f8fafc; padding:8px; text-align:center; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; }
    .cal-cell { background:#fff; min-height:100px; padding:8px; }
    .cal-cell.other-month { background:#fafafa; }
    .cal-cell.today { background:#eff6ff; }
    .cal-date { font-size:12px; font-weight:600; color:#64748b; margin-bottom:4px; }
    .cal-cell.today .cal-date { color:#1d4ed8; }

    /* Appointment chips */
    .cal-chip {
        font-size:10px; padding:2px 6px; border-radius:4px; margin-bottom:2px;
        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
        display:block; text-decoration:none; font-weight:500;
    }
    .chip-OPD       { background:#dbeafe; color:#1d4ed8; }
    .chip-IPD       { background:#dcfce7; color:#15803d; }
    .chip-Emergency { background:#fee2e2; color:#991b1b; }
    .chip-Follow-up { background:#f3e8ff; color:#7e22ce; }
    .cal-more { font-size:10px; color:#94a3b8; margin-top:2px; }

    /* Doctor filter */
    .cal-controls {
        background:#fff; border:1px solid #e2e8f0; border-radius:10px;
        padding:14px 18px; margin-bottom:20px;
        display:flex; align-items:center; gap:10px; flex-wrap:wrap;
    }
    .cal-controls select {
        height:36px; border:1px solid #e2e8f0; border-radius:8px;
        font-size:13px; padding:0 12px; background:#f8fafc; color:#374151;
    }
</style>
@endpush

@section('content')
<div class="cal-wrapper">

    {{-- Controls --}}
    <div class="cal-controls">
        <form method="GET" action="{{ route('appointments.calendar') }}"
              class="d-flex align-items:center gap-2 flex-wrap" style="gap:8px;display:flex">
            <input type="hidden" name="month" value="{{ $month }}">
            <input type="hidden" name="year"  value="{{ $year }}">
            <select name="doctor_id" onchange="this.form.submit()">
                <option value="">All doctors</option>
                @foreach($doctors as $d)
                    <option value="{{ $d->id }}" {{ $doctorId == $d->id ? 'selected' : '' }}>
                        {{ $d->name }}
                    </option>
                @endforeach
            </select>
            <a href="{{ route('appointments.create') }}"
               class="btn btn-sm btn-success px-3" style="height:36px;font-size:13px">
                <i class="bi bi-plus-lg me-1"></i>New
            </a>
            <a href="{{ route('appointments.index') }}"
               class="btn btn-sm btn-outline-secondary px-3" style="height:36px;font-size:13px">
                <i class="bi bi-list me-1"></i>List view
            </a>
        </form>
    </div>

    {{-- Month navigation --}}
    <div class="cal-nav">
        @php
            $prevMonth = $month == 1 ? 12 : $month - 1;
            $prevYear  = $month == 1 ? $year - 1 : $year;
            $nextMonth = $month == 12 ? 1 : $month + 1;
            $nextYear  = $month == 12 ? $year + 1 : $year;
        @endphp
        <a href="{{ route('appointments.calendar', ['month'=>$prevMonth,'year'=>$prevYear,'doctor_id'=>$doctorId]) }}"
           class="cal-nav-btn">
            <i class="bi bi-chevron-left"></i> Prev
        </a>
        <span class="cal-month-title">{{ $startDate->format('F Y') }}</span>
        <a href="{{ route('appointments.calendar', ['month'=>$nextMonth,'year'=>$nextYear,'doctor_id'=>$doctorId]) }}"
           class="cal-nav-btn">
            Next <i class="bi bi-chevron-right"></i>
        </a>
    </div>

    {{-- Calendar grid --}}
    <div class="cal-grid">
        {{-- Day headers --}}
        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
            <div class="cal-day-header">{{ $day }}</div>
        @endforeach

        {{-- Cells --}}
        @php
            $firstDayOfWeek = $startDate->copy()->startOfMonth()->dayOfWeek; // 0=Sun
            $daysInMonth    = $startDate->daysInMonth;
            $today          = now()->format('Y-m-d');
        @endphp

        {{-- Blank cells before month starts --}}
        @for($i = 0; $i < $firstDayOfWeek; $i++)
            <div class="cal-cell other-month"></div>
        @endfor

        {{-- Month days --}}
        @for($day = 1; $day <= $daysInMonth; $day++)
            @php
                $dateKey = $startDate->copy()->setDay($day)->format('Y-m-d');
                $dayAppts = $appointments->get($dateKey, collect());
                $isToday  = $dateKey === $today;
            @endphp
            <div class="cal-cell {{ $isToday ? 'today' : '' }}">
                <div class="cal-date">{{ $day }}</div>
                @foreach($dayAppts->take(3) as $a)
                    <a href="{{ route('appointments.show', $a->id) }}"
                       class="cal-chip chip-{{ $a->type }}" title="{{ $a->patient->name ?? '' }} — {{ $a->reason }}">
                        {{ $a->formatted_time }} {{ $a->patient->name ?? '—' }}
                    </a>
                @endforeach
                @if($dayAppts->count() > 3)
                    <div class="cal-more">+{{ $dayAppts->count() - 3 }} more</div>
                @endif
            </div>
        @endfor

        {{-- Trailing blanks to complete last week --}}
        @php $trailing = (7 - ($firstDayOfWeek + $daysInMonth) % 7) % 7; @endphp
        @for($i = 0; $i < $trailing; $i++)
            <div class="cal-cell other-month"></div>
        @endfor
    </div>

</div>
@endsection