@extends('layouts.master')

@section('title', 'Nurse Dashboard')
@section('page-title', 'Nurse Dashboard')
@section('breadcrumb', 'Home / Nurse Dashboard')

@push('styles')
    <style>
        .stat-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.1rem 1.25rem;
            height: 100%;
            transition: box-shadow .15s
        }

        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, .07)
        }

        .stat-label {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: .4rem
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1
        }

        .stat-sub {
            font-size: 11px;
            color: #94a3b8;
            margin-top: .3rem
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0
        }

        .section-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            height: 100%
        }

        .section-header {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .section-title {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin: 0
        }

        .patient-row {
            display: flex;
            align-items: center;
            padding: 10px 16px;
            border-bottom: 1px solid #f8fafc;
            gap: 10px;
            transition: background .1s
        }

        .patient-row:last-child {
            border-bottom: none
        }

        .patient-row:hover {
            background: #f8fafc
        }

        .patient-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0
        }

        .patient-name {
            font-size: 13px;
            font-weight: 500;
            color: #1e293b
        }

        .patient-sub {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 1px
        }

        .order-row {
            padding: 10px 16px;
            border-bottom: 1px solid #f8fafc;
            display: flex;
            align-items: flex-start;
            gap: 10px
        }

        .order-row:last-child {
            border-bottom: none
        }

        .order-priority {
            width: 6px;
            border-radius: 3px;
            align-self: stretch;
            flex-shrink: 0
        }

        .priority-STAT {
            background: #dc2626
        }

        .priority-Urgent {
            background: #d97706
        }

        .priority-Routine {
            background: #16a34a
        }

        .vital-due-row {
            padding: 10px 16px;
            border-bottom: 1px solid #f8fafc;
            display: flex;
            align-items: center;
            gap: 10px
        }

        .vital-due-row:last-child {
            border-bottom: none
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #dc2626;
            animation: pulse 1.2s infinite;
            flex-shrink: 0
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1)
            }

            50% {
                opacity: .5;
                transform: scale(1.3)
            }
        }

        .activity-row {
            padding: 9px 16px;
            border-bottom: 1px solid #f8fafc;
            display: flex;
            align-items: center;
            gap: 10px
        }

        .activity-row:last-child {
            border-bottom: none
        }

        .activity-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0
        }

        .shift-badge {
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600
        }

        .shift-Morning {
            background: #fefce8;
            color: #ca8a04;
            border: 1px solid #fde68a
        }

        .shift-Evening {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe
        }

        .shift-Night {
            background: #1e1b4b;
            color: #a5b4fc;
            border: 1px solid #3730a3
        }

        .empty-msg {
            padding: 2rem;
            text-align: center;
            color: #94a3b8;
            font-size: 13px
        }

        .empty-msg i {
            font-size: 26px;
            display: block;
            margin-bottom: .5rem
        }

        .btn-xs {
            padding: 2px 8px;
            font-size: 11px
        }
    </style>
@endpush

@section('content')

    {{-- ── Not Assigned Warning ── --}}
    @if (!$myAssignment && !($isAdminView ?? false))
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:32px 24px;text-align:center">
            <i class="bi bi-building-x" style="font-size:40px;color:#dc2626"></i>
            <div style="font-size:18px;font-weight:600;color:#dc2626;margin-top:12px">Koi Ward Assign Nahi</div>
            <div style="font-size:13px;color:#64748b;margin-top:6px">
                Aapko abhi tak kisi ward mein assign nahi kiya gaya.<br>
                Meherbani kar ke HR ya Admin se rabta karein.
            </div>
        </div>
    @else
        @php $hour = now()->hour; @endphp

        {{-- ── Welcome Banner ── --}}
        <div
            style="background:linear-gradient(135deg,#1d4ed8 0%,#0369a1 100%);border-radius:14px;padding:20px 24px;margin-bottom:20px;color:#fff">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <div style="font-size:18px;font-weight:600">
                        {{ $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening') }},
                        {{ $nurse->name }} 👋
                    </div>
                    <div style="font-size:13px;opacity:.8;margin-top:4px">
                        <i class="bi bi-clock me-1"></i>{{ now()->format('l, d M Y — h:i A') }}
                    </div>
                    <div style="font-size:12px;opacity:.7;margin-top:3px">
                        <i class="bi bi-building me-1"></i>
                        {{ $isAdminView ?? false ? 'All Wards (Admin View)' : $myAssignment->ward->name ?? '—' }}
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="shift-badge shift-{{ $shift }}">
                        <i class="bi bi-brightness-{{ $shift === 'Night' ? 'low' : 'high' }} me-1"></i>
                        {{ $shift }} Shift
                    </span>
                    @if ($stats['urgent_orders'] > 0)
                        <span
                            style="background:#dc2626;color:#fff;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600;animation:pulse 1.5s infinite">
                            <i class="bi bi-exclamation-triangle me-1"></i>{{ $stats['urgent_orders'] }} URGENT
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Stat Cards ── --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="stat-label">My Patients</div>
                            <div class="stat-value">{{ $stats['total_patients'] }}</div>
                            <div class="stat-sub">currently admitted</div>
                        </div>
                        <div class="stat-icon" style="background:#eff6ff;color:#1d4ed8"><i class="bi bi-people"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="stat-label">Pending Orders</div>
                            <div class="stat-value" style="{{ $stats['pending_orders'] > 0 ? 'color:#d97706' : '' }}">
                                {{ $stats['pending_orders'] }}</div>
                            <div class="stat-sub">need action</div>
                        </div>
                        <div class="stat-icon" style="background:#fffbeb;color:#d97706"><i
                                class="bi bi-clipboard2-check"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="stat-label">STAT / Urgent</div>
                            <div class="stat-value" style="{{ $stats['urgent_orders'] > 0 ? 'color:#dc2626' : '' }}">
                                {{ $stats['urgent_orders'] }}</div>
                            <div class="stat-sub">immediate action</div>
                        </div>
                        <div class="stat-icon" style="background:#fef2f2;color:#dc2626"><i
                                class="bi bi-exclamation-circle"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="stat-label">Vitals Due</div>
                            <div class="stat-value" style="{{ $stats['vitals_due'] > 0 ? 'color:#dc2626' : '' }}">
                                {{ $stats['vitals_due'] }}</div>
                            <div class="stat-sub">> 6 hrs ago</div>
                        </div>
                        <div class="stat-icon" style="background:#fdf4ff;color:#9333ea"><i class="bi bi-heart-pulse"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="stat-label">My Notes Today</div>
                            <div class="stat-value text-success">{{ $stats['my_notes_today'] }}</div>
                            <div class="stat-sub">notes written</div>
                        </div>
                        <div class="stat-icon" style="background:#f0fdf4;color:#16a34a"><i class="bi bi-journal-text"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="stat-label">My Vitals Today</div>
                            <div class="stat-value text-primary">{{ $stats['my_vitals_today'] }}</div>
                            <div class="stat-sub">vitals recorded</div>
                        </div>
                        <div class="stat-icon" style="background:#eff6ff;color:#1d4ed8"><i class="bi bi-activity"></i></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Main Grid ── --}}
        <div class="row g-3">

            {{-- LEFT COL --}}
            <div class="col-12 col-lg-4">

                @if ($vitalsDue->count() > 0)
                    <div class="section-card mb-3" style="border-color:#fecaca">
                        <div class="section-header" style="background:#fef2f2">
                            <span class="section-title" style="color:#dc2626">
                                <i class="bi bi-heart-pulse me-2"></i>Vitals Due
                                <span class="badge bg-danger ms-1" style="font-size:10px">{{ $vitalsDue->count() }}</span>
                            </span>
                        </div>
                        @foreach ($vitalsDue as $bed)
                            <div class="vital-due-row">
                                <div class="pulse-dot"></div>
                                <div class="flex-grow-1">
                                    <div class="patient-name">{{ $bed->patient->name ?? '—' }}</div>
                                    <div class="patient-sub">Bed {{ $bed->bed_number }} — {{ $bed->ward->name ?? '—' }}
                                    </div>
                                </div>
                                <a href="{{ route('ward.patient.show', $bed->patient_id) }}"
                                    class="btn btn-danger btn-xs">Record</a>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="section-card">
                    <div class="section-header">
                        <span class="section-title">
                            <i class="bi bi-people me-2 text-primary"></i>My Ward Patients
                        </span>
                        <span style="font-size:11px;color:#94a3b8">{{ $occupiedBeds->count() }} admitted</span>
                    </div>
                    @forelse($occupiedBeds as $bed)
                        <div class="patient-row">
                            <div class="patient-avatar">{{ strtoupper(substr($bed->patient->name ?? 'P', 0, 1)) }}</div>
                            <div class="flex-grow-1">
                                <div class="patient-name">{{ $bed->patient->name ?? '—' }}</div>
                                <div class="patient-sub">
                                    Bed {{ $bed->bed_number }}
                                    @if ($bed->admitted_at)
                                        &bull; {{ \Carbon\Carbon::parse($bed->admitted_at)->diffInDays(now()) }}d
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('ward.patient.show', $bed->patient_id) }}"
                                class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    @empty
                        <div class="empty-msg"><i class="bi bi-people"></i>No patients admitted</div>
                    @endforelse
                </div>
            </div>

            {{-- MIDDLE COL --}}
            <div class="col-12 col-lg-5">

                <div class="section-card mb-3">
                    <div class="section-header">
                        <span class="section-title">
                            <i class="bi bi-clipboard2-check me-2 text-warning"></i>Pending Doctor Orders
                        </span>
                        @if ($pendingOrders->count() > 0)
                            <span class="badge bg-warning text-dark"
                                style="font-size:10px">{{ $pendingOrders->count() }}</span>
                        @endif
                    </div>
                    @forelse($pendingOrders as $order)
                        <div class="order-row">
                            <div class="order-priority priority-{{ $order->priority }}"></div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $order->title }}</span>
                                    @if ($order->priority === 'STAT')
                                        <span class="badge bg-danger" style="font-size:10px">STAT</span>
                                    @elseif($order->priority === 'Urgent')
                                        <span class="badge bg-warning text-dark" style="font-size:10px">Urgent</span>
                                    @endif
                                </div>
                                <div style="font-size:12px;color:#64748b">{{ Str::limit($order->details, 60) }}</div>
                                <div style="font-size:11px;color:#94a3b8;margin-top:3px">
                                    <i class="bi bi-person-badge me-1"></i>
                                    Dr.
                                    {{ $order->doctor->employee->full_name ?? ($order->doctor->employee->first_name ?? 'Unknown') }}
                                    &bull; <i class="bi bi-person me-1"></i>{{ $order->patient->name ?? '—' }}
                                    &bull; {{ $order->ordered_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-1">
                                @if ($order->status === 'Pending')
                                    <form method="POST" action="{{ route('ward.orders.acknowledge', $order->id) }}">
                                        @csrf
                                        <button class="btn btn-outline-primary btn-xs">Ack</button>
                                    </form>
                                @elseif($order->status === 'Acknowledged')
                                    <form method="POST" action="{{ route('ward.orders.complete', $order->id) }}">
                                        @csrf
                                        <button class="btn btn-outline-success btn-xs">Done</button>
                                    </form>
                                @endif
                                <a href="{{ route('ward.patient.show', $order->patient_id) }}"
                                    class="btn btn-outline-secondary btn-xs text-center">
                                    <i class="bi bi-eye" style="font-size:10px"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-msg"><i class="bi bi-clipboard2-check"></i>No pending orders</div>
                    @endforelse
                </div>

                @if ($urgentNotes->count() > 0)
                    <div class="section-card" style="border-color:#fde68a">
                        <div class="section-header" style="background:#fffbeb">
                            <span class="section-title" style="color:#b45309">
                                <i class="bi bi-exclamation-triangle me-2"></i>Needs Doctor Attention
                                <span class="badge bg-warning text-dark ms-1"
                                    style="font-size:10px">{{ $urgentNotes->count() }}</span>
                            </span>
                        </div>
                        @foreach ($urgentNotes as $note)
                            <div class="patient-row">
                                <div class="patient-avatar" style="background:#fef3c7;color:#d97706">
                                    <i class="bi bi-exclamation" style="font-size:14px"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="patient-name">{{ $note->patient->name ?? '—' }}</div>
                                    <div class="patient-sub">{{ Str::limit($note->note, 60) }}</div>
                                    <div style="font-size:11px;color:#94a3b8">
                                        <i class="bi bi-person-nurse me-1"></i>{{ $note->nurse->name ?? '—' }}
                                        &bull; {{ $note->noted_at->diffForHumans() }}
                                    </div>
                                </div>
                                <a href="{{ route('ward.patient.show', $note->patient_id) }}"
                                    class="btn btn-sm btn-outline-warning py-0 px-2" style="font-size:12px">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>

            {{-- RIGHT COL --}}
            <div class="col-12 col-lg-3">

                <div class="section-card mb-3">
                    <div class="section-header">
                        <span class="section-title">
                            <i class="bi bi-person-check me-2 text-success"></i>My Activity Today
                        </span>
                    </div>

                    @if ($myTodayVitals->count() > 0)
                        <div
                            style="padding:8px 16px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #f1f5f9">
                            Vitals Recorded ({{ $myTodayVitals->count() }})
                        </div>
                        @foreach ($myTodayVitals->take(5) as $vital)
                            <div class="activity-row">
                                <div class="activity-dot" style="background:#1d4ed8"></div>
                                <div class="flex-grow-1">
                                    <div style="font-size:12px;font-weight:500;color:#1e293b">
                                        {{ $vital->patient->name ?? '—' }}</div>
                                    <div style="font-size:11px;color:#94a3b8">
                                        {{ $vital->shift }} &bull; {{ $vital->recorded_at->format('h:i A') }}
                                        @if ($vital->systolic_bp)
                                            &bull; BP {{ $vital->systolic_bp }}/{{ $vital->diastolic_bp }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if ($myTodayNotes->count() > 0)
                        <div
                            style="padding:8px 16px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #f1f5f9;border-top:1px solid #f1f5f9">
                            Notes Written ({{ $myTodayNotes->count() }})
                        </div>
                        @foreach ($myTodayNotes->take(5) as $note)
                            <div class="activity-row">
                                <div class="activity-dot" style="background:#16a34a"></div>
                                <div class="flex-grow-1">
                                    <div style="font-size:12px;font-weight:500;color:#1e293b">
                                        {{ $note->patient->name ?? '—' }}</div>
                                    <div style="font-size:11px;color:#94a3b8">
                                        {{ $note->note_type }} &bull; {{ $note->noted_at->format('h:i A') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if ($myTodayVitals->count() === 0 && $myTodayNotes->count() === 0)
                        <div class="empty-msg"><i class="bi bi-clipboard2"></i>No activity yet today</div>
                    @endif
                </div>

                <div class="section-card">
                    <div class="section-header">
                        <span class="section-title">
                            <i class="bi bi-lightning me-2 text-warning"></i>Quick Actions
                        </span>
                    </div>
                    <div style="padding:12px 16px;display:grid;gap:8px">
                        <a href="{{ route('wards.index') }}" class="btn btn-outline-primary btn-sm text-start"
                            style="font-size:13px">
                            <i class="bi bi-building me-2"></i>View All Wards
                        </a>
                        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary btn-sm text-start"
                            style="font-size:13px">
                            <i class="bi bi-calendar2 me-2"></i>Appointments
                        </a>
                        <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary btn-sm text-start"
                            style="font-size:13px">
                            <i class="bi bi-person-lines-fill me-2"></i>All Patients
                        </a>
                    </div>
                </div>

            </div>
        </div>

    @endif
@endsection

@push('scripts')
    <script>
        setTimeout(() => location.reload(), 3 * 60 * 1000);
    </script>
@endpush
