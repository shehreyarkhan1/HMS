@extends('layouts.master')

@section('title', 'Holidays ' . $year)
@section('page-title', 'Holiday Calendar')
@section('breadcrumb', 'HR / Holidays')

@push('styles')
    <style>
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            border: 1px solid #e2e8f0;
            height: 100%;
            transition: box-shadow 0.15s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.07);
        }

        .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
            margin-bottom: 0.4rem;
        }

        .stat-sub {
            font-size: 12px;
            color: #94a3b8;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .section-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .section-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        /* Holiday type badges */
        .htype-badge {
            font-size: 10px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .htype-public {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .htype-national {
            background: #dcfce7;
            color: #16a34a;
        }

        .htype-religious {
            background: #fef3c7;
            color: #b45309;
        }

        .htype-hospital {
            background: #ede9fe;
            color: #7c3aed;
        }

        .htype-optional {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Table rows */
        .h-row {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid #f8fafc;
            gap: 12px;
            transition: background 0.1s;
        }

        .h-row:last-child {
            border-bottom: none;
        }

        .h-row:hover {
            background: #f8fafc;
        }

        .h-date-box {
            min-width: 52px;
            height: 52px;
            border-radius: 10px;
            background: #eff6ff;
            color: #1d4ed8;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .h-date-day {
            font-size: 18px;
            font-weight: 700;
            line-height: 1;
        }

        .h-date-month {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .h-info {
            flex: 1;
            min-width: 0;
        }

        .h-name {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
        }

        .h-desc {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .h-days-pill {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            background: #f1f5f9;
            color: #64748b;
            white-space: nowrap;
        }

        .inactive-row {
            opacity: 0.45;
        }

        .recurring-icon {
            color: #1d4ed8;
            font-size: 12px;
        }

        /* Month group header */
        .month-group {
            padding: 0.5rem 1.25rem;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Year nav */
        .year-nav {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .year-nav a {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.15s;
        }

        .year-nav a:hover {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe;
        }

        .year-nav .year-label {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            padding: 0 6px;
        }

        /* Mini calendar dots */
        .cal-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 3px;
            padding: 0.75rem 1rem;
        }

        .cal-day-lbl {
            font-size: 9px;
            font-weight: 700;
            text-align: center;
            color: #94a3b8;
            text-transform: uppercase;
            padding: 2px 0;
        }

        .cal-day {
            font-size: 11px;
            text-align: center;
            padding: 3px 0;
            border-radius: 5px;
            color: #64748b;
            line-height: 1.4;
        }

        .cal-day.holiday {
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: 700;
        }

        .cal-day.today-dot {
            background: #1d4ed8;
            color: #fff;
            font-weight: 700;
        }

        .cal-day.weekend {
            color: #ef4444;
        }

        .cal-day.empty {
            visibility: hidden;
        }

        .empty-state {
            padding: 3rem;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
        }

        .empty-state i {
            font-size: 32px;
            display: block;
            margin-bottom: 0.75rem;
            color: #cbd5e1;
        }

        /* Delete confirm */
        .btn-icon {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.15s;
            background: #fff;
            color: #64748b;
            text-decoration: none;
        }

        .btn-icon:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .btn-icon.danger:hover {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fca5a5;
        }
    </style>
@endpush

@section('content')
    @php
        use Carbon\Carbon;
        $today = now()->format('Y-m-d');
        $grouped = $holidays->groupBy(fn($h) => Carbon::parse($h->date)->format('F'));
        $upcomingCnt = $holidays->filter(fn($h) => $h->date >= $today && $h->is_active)->count();
        $pastCnt = $holidays->filter(fn($h) => $h->date < $today)->count();
        $typeCounts = $holidays->where('is_active', true)->groupBy('type')->map->count();

        // Dates set for mini calendar (current month)
        $calMonth = now()->month;
        $calYear = $year;
        $calStart = Carbon::createFromDate($calYear, $calMonth, 1);
        $calEnd = $calStart->copy()->endOfMonth();
        $holidayDays = $holidays->pluck('date')->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))->toArray();
    @endphp

    {{-- ── TOP ROW ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 class="mb-0 fw-semibold" style="color:#1e293b">Holiday Calendar</h5>
            <p class="mb-0" style="font-size:13px; color:#94a3b8">Manage public and hospital holidays for
                {{ $year }}</p>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addHolidayModal">
            <i class="bi bi-plus-lg me-1"></i> Add Holiday
        </button>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Total Holidays</div>
                        <div class="stat-value">{{ $holidays->where('is_active', true)->count() }}</div>
                        <div class="stat-sub">in {{ $year }}</div>
                    </div>
                    <div class="stat-icon" style="background:#eff6ff; color:#1d4ed8"><i class="bi bi-calendar-heart"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Upcoming</div>
                        <div class="stat-value">{{ $upcomingCnt }}</div>
                        <div class="stat-sub">remaining this year</div>
                    </div>
                    <div class="stat-icon" style="background:#f0fdf4; color:#16a34a"><i class="bi bi-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Religious</div>
                        <div class="stat-value">{{ $typeCounts['Religious Holiday'] ?? 0 }}</div>
                        <div class="stat-sub">holidays this year</div>
                    </div>
                    <div class="stat-icon" style="background:#fef3c7; color:#b45309"><i class="bi bi-star"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">National</div>
                        <div class="stat-value">{{ $typeCounts['National Holiday'] ?? 0 }}</div>
                        <div class="stat-sub">gazetted holidays</div>
                    </div>
                    <div class="stat-icon" style="background:#dcfce7; color:#16a34a"><i class="bi bi-flag"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── MAIN GRID ── --}}
    <div class="row g-3">

        {{-- LEFT — Holiday list ──────────────────────────────────────── --}}
        <div class="col-12 col-lg-8">
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title">
                        <i class="bi bi-calendar3 me-2" style="color:#1d4ed8"></i>
                        {{ $year }} Holidays
                    </span>
                    {{-- Year navigation --}}
                    <div class="year-nav">
                        <a href="{{ route('hr.holidays.index', ['year' => $year - 1]) }}" title="Previous year">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                        <span class="year-label">{{ $year }}</span>
                        <a href="{{ route('hr.holidays.index', ['year' => $year + 1]) }}" title="Next year">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>

                @if ($holidays->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-calendar-x"></i>
                        No holidays added for {{ $year }}.<br>
                        <button class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal"
                            data-bs-target="#addHolidayModal">
                            <i class="bi bi-plus-lg me-1"></i> Add First Holiday
                        </button>
                    </div>
                @else
                    @foreach ($grouped as $monthName => $monthHolidays)
                        <div class="month-group">{{ $monthName }}</div>
                        @foreach ($monthHolidays as $holiday)
                            @php
                                $isPast = $holiday->date < $today;
                                $dateObj = Carbon::parse($holiday->date);
                                $dateTo = $holiday->date_to ? Carbon::parse($holiday->date_to) : null;
                                $typeClass = match ($holiday->type) {
                                    'National Holiday' => 'htype-national',
                                    'Religious Holiday' => 'htype-religious',
                                    'Hospital Holiday' => 'htype-hospital',
                                    'Optional' => 'htype-optional',
                                    default => 'htype-public',
                                };
                            @endphp
                            <div class="h-row {{ !$holiday->is_active ? 'inactive-row' : '' }}">
                                {{-- Date box --}}
                                <div class="h-date-box" style="{{ $isPast ? 'background:#f1f5f9; color:#94a3b8;' : '' }}">
                                    <div class="h-date-day">{{ $dateObj->format('d') }}</div>
                                    <div class="h-date-month">{{ $dateObj->format('M') }}</div>
                                </div>

                                {{-- Info --}}
                                <div class="h-info">
                                    <div class="h-name">
                                        {{ $holiday->name }}
                                        @if ($holiday->is_recurring)
                                            <i class="bi bi-arrow-repeat recurring-icon ms-1" title="Recurring yearly"></i>
                                        @endif
                                        @if (!$holiday->is_active)
                                            <span class="htype-badge htype-optional ms-1">Inactive</span>
                                        @endif
                                    </div>
                                    <div class="h-desc">
                                        {{ $dateObj->format('l, d F Y') }}
                                        @if ($dateTo && $dateTo->ne($dateObj))
                                            &rarr; {{ $dateTo->format('d F Y') }}
                                        @endif
                                        @if ($holiday->description)
                                            &nbsp;&middot;&nbsp;{{ Str::limit($holiday->description, 50) }}
                                        @endif
                                    </div>
                                </div>

                                {{-- Type badge --}}
                                <span class="htype-badge {{ $typeClass }}">
                                    {{ $holiday->type }}
                                </span>

                                {{-- Days pill --}}
                                <span class="h-days-pill">
                                    {{ $holiday->total_days ?? 1 }} day{{ ($holiday->total_days ?? 1) > 1 ? 's' : '' }}
                                </span>

                                {{-- Actions --}}
                                <div class="d-flex gap-1">
                                    <a href="#" class="btn-icon" title="Edit" data-bs-toggle="modal"
                                        data-bs-target="#editHolidayModal" data-id="{{ $holiday->id }}"
                                        data-name="{{ $holiday->name }}" data-date="{{ $holiday->date }}"
                                        data-date_to="{{ $holiday->date_to }}" data-type="{{ $holiday->type }}"
                                        data-description="{{ $holiday->description }}"
                                        data-is_active="{{ $holiday->is_active ? '1' : '0' }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('hr.holidays.destroy', $holiday) }}" method="POST"
                                        onsubmit="return confirm('Delete {{ addslashes($holiday->name) }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                @endif
            </div>
        </div>

        {{-- RIGHT — Mini calendar + summary ────────────────────────────── --}}
        <div class="col-12 col-lg-4">

            {{-- Mini calendar (current month) --}}
            <div class="section-card mb-3">
                <div class="section-header">
                    <span class="section-title">
                        <i class="bi bi-calendar2 me-2" style="color:#1d4ed8"></i>
                        {{ now()->format('F Y') }}
                    </span>
                </div>
                @php
                    $startDow = $calStart->dayOfWeek; // 0=Sun
                    $daysInMonth = $calEnd->day;
                    $dayLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                @endphp
                <div class="cal-grid">
                    @foreach ($dayLabels as $lbl)
                        <div class="cal-day-lbl">{{ $lbl }}</div>
                    @endforeach
                    {{-- Empty cells before first day --}}
                    @for ($e = 0; $e < $startDow; $e++)
                        <div class="cal-day empty">-</div>
                    @endfor
                    {{-- Days --}}
                    @for ($d = 1; $d <= $daysInMonth; $d++)
                        @php
                            $thisDate = Carbon::createFromDate($calYear, $calMonth, $d)->format('Y-m-d');
                            $dow = Carbon::parse($thisDate)->dayOfWeek;
                            $isHol = in_array($thisDate, $holidayDays);
                            $isTod = $thisDate === $today;
                            $isWknd = in_array($dow, [5, 6]); // Fri, Sat
                            $cls = $isTod ? 'today-dot' : ($isHol ? 'holiday' : ($isWknd ? 'weekend' : ''));
                        @endphp
                        <div class="cal-day {{ $cls }}"
                            title="{{ $isHol ? $holidays->firstWhere('date', $thisDate)?->name ?? 'Holiday' : '' }}">
                            {{ $d }}
                        </div>
                    @endfor
                </div>
                <div style="padding: 0 1rem 0.75rem; display:flex; gap:12px; flex-wrap:wrap;">
                    <span style="font-size:10px; color:#64748b; display:flex; align-items:center; gap:4px;">
                        <span
                            style="width:10px;height:10px;border-radius:3px;background:#dbeafe;display:inline-block"></span>Holiday
                    </span>
                    <span style="font-size:10px; color:#64748b; display:flex; align-items:center; gap:4px;">
                        <span
                            style="width:10px;height:10px;border-radius:3px;background:#1d4ed8;display:inline-block"></span>Today
                    </span>
                    <span style="font-size:10px; color:#ef4444; font-weight:600;">Fri/Sat</span>
                    <span style="font-size:10px; color:#64748b;">= Weekend</span>
                </div>
            </div>

            {{-- Type breakdown --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title">
                        <i class="bi bi-pie-chart me-2" style="color:#1d4ed8"></i>
                        By Type
                    </span>
                </div>
                @php
                    $typesMeta = [
                        'Public Holiday' => ['htype-public', '#1d4ed8'],
                        'National Holiday' => ['htype-national', '#16a34a'],
                        'Religious Holiday' => ['htype-religious', '#b45309'],
                        'Hospital Holiday' => ['htype-hospital', '#7c3aed'],
                        'Optional' => ['htype-optional', '#64748b'],
                    ];
                    $total = $holidays->where('is_active', true)->count() ?: 1;
                @endphp
                @foreach ($typesMeta as $typeName => [$cls, $color])
                    @php $cnt = $typeCounts[$typeName] ?? 0; @endphp
                    <div
                        style="padding: 0.6rem 1.25rem; border-bottom: 1px solid #f8fafc; display:flex; align-items:center; gap:10px;">
                        <span class="htype-badge {{ $cls }}"
                            style="min-width:100px; text-align:center;">{{ $typeName }}</span>
                        <div style="flex:1; height:5px; background:#f1f5f9; border-radius:3px; overflow:hidden;">
                            <div
                                style="height:100%; width:{{ round(($cnt / $total) * 100) }}%; background:{{ $color }}; border-radius:3px;">
                            </div>
                        </div>
                        <span
                            style="font-size:12px; font-weight:600; color:#1e293b; min-width:20px; text-align:right;">{{ $cnt }}</span>
                    </div>
                @endforeach
            </div>

        </div>
    </div>


    {{-- ════════════════════════════════════════════════════════
     ADD HOLIDAY MODAL
════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="addHolidayModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 20px 60px rgba(0,0,0,0.12);">
                <div class="modal-header" style="border-bottom:1px solid #f1f5f9; padding:1.1rem 1.5rem;">
                    <h6 class="modal-title fw-semibold" style="color:#1e293b">
                        <i class="bi bi-calendar-plus me-2" style="color:#1d4ed8"></i>Add Holiday
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('hr.holidays.store') }}" method="POST">
                    @csrf
                    <div class="modal-body" style="padding:1.5rem;">
                        <div class="row g-3">

                            {{-- Name --}}
                            <div class="col-12">
                                <label class="form-label" style="font-size:12px; font-weight:600; color:#64748b;">Holiday
                                    Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="e.g. Eid ul Fitr" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Type --}}
                            <div class="col-12">
                                <label class="form-label" style="font-size:12px; font-weight:600; color:#64748b;">Type
                                    <span class="text-danger">*</span></label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Select type</option>
                                    @foreach (['Public Holiday', 'National Holiday', 'Religious Holiday', 'Hospital Holiday', 'Optional'] as $t)
                                        <option value="{{ $t }}" {{ old('type') == $t ? 'selected' : '' }}>
                                            {{ $t }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Dates --}}
                            <div class="col-6">
                                <label class="form-label" style="font-size:12px; font-weight:600; color:#64748b;">From
                                    Date <span class="text-danger">*</span></label>
                                <input type="date" name="date"
                                    class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}"
                                    required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label" style="font-size:12px; font-weight:600; color:#64748b;">To Date
                                    <small class="text-muted">(optional)</small></label>
                                <input type="date" name="date_to"
                                    class="form-control @error('date_to') is-invalid @enderror"
                                    value="{{ old('date_to') }}">
                                @error('date_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <label class="form-label"
                                    style="font-size:12px; font-weight:600; color:#64748b;">Description</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Short note (optional)">{{ old('description') }}</textarea>
                            </div>

                            {{-- Recurring --}}
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_recurring" value="1"
                                        id="is_recurring" {{ old('is_recurring') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_recurring" style="font-size:13px;">
                                        <i class="bi bi-arrow-repeat me-1" style="color:#1d4ed8"></i>
                                        Recurring yearly
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f1f5f9; padding:1rem 1.5rem;">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-check-lg me-1"></i> Save Holiday
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ════════════════════════════════════════════════════════
     EDIT HOLIDAY MODAL
════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="editHolidayModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 20px 60px rgba(0,0,0,0.12);">
                <div class="modal-header" style="border-bottom:1px solid #f1f5f9; padding:1.1rem 1.5rem;">
                    <h6 class="modal-title fw-semibold" style="color:#1e293b">
                        <i class="bi bi-pencil-square me-2" style="color:#1d4ed8"></i>Edit Holiday
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editHolidayForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body" style="padding:1.5rem;">
                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label" style="font-size:12px; font-weight:600; color:#64748b;">Holiday
                                    Name <span class="text-danger">*</span></label>
                                <input type="text" id="edit_name" name="name" class="form-control" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label" style="font-size:12px; font-weight:600; color:#64748b;">Type
                                    <span class="text-danger">*</span></label>
                                <select id="edit_type" name="type" class="form-select" required>
                                    @foreach (['Public Holiday', 'National Holiday', 'Religious Holiday', 'Hospital Holiday', 'Optional'] as $t)
                                        <option value="{{ $t }}">{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6">
                                <label class="form-label" style="font-size:12px; font-weight:600; color:#64748b;">From
                                    Date <span class="text-danger">*</span></label>
                                <input type="date" id="edit_date" name="date" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label" style="font-size:12px; font-weight:600; color:#64748b;">To
                                    Date</label>
                                <input type="date" id="edit_date_to" name="date_to" class="form-control">
                            </div>

                            <div class="col-12">
                                <label class="form-label"
                                    style="font-size:12px; font-weight:600; color:#64748b;">Description</label>
                                <textarea id="edit_description" name="description" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                        id="edit_is_active">
                                    <label class="form-check-label" for="edit_is_active"
                                        style="font-size:13px;">Active</label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f1f5f9; padding:1rem 1.5rem;">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-check-lg me-1"></i> Update Holiday
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Populate edit modal
        document.getElementById('editHolidayModal').addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            const form = document.getElementById('editHolidayForm');
            const id = btn.dataset.id;
            const baseUrl = "{{ url('hr/holidays') }}";

            form.action = `${baseUrl}/${id}`;
            document.getElementById('edit_name').value = btn.dataset.name || '';
            document.getElementById('edit_date').value = btn.dataset.date || '';
            document.getElementById('edit_date_to').value = btn.dataset.date_to || '';
            document.getElementById('edit_type').value = btn.dataset.type || '';
            document.getElementById('edit_description').value = btn.dataset.description || '';
            document.getElementById('edit_is_active').checked = btn.dataset.is_active === '1';
        });

        // Reopen add modal on validation error
        @if ($errors->any())
            var addModal = new bootstrap.Modal(document.getElementById('addHolidayModal'));
            addModal.show();
        @endif
    </script>
@endpush
