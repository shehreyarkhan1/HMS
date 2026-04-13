@extends('layouts.master')

@section('title', 'Wards & Beds')
@section('page-title', 'Wards & Beds')
@section('breadcrumb', 'Home / Wards & Beds')

@push('styles')
<style>
    .stat-pill{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:14px 20px}
    .stat-pill-label{font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em}
    .stat-pill-value{font-size:22px;font-weight:700;color:#1e293b}

    .ward-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;transition:box-shadow .2s}
    .ward-card:hover{box-shadow:0 6px 20px rgba(0,0,0,.07)}
    .ward-card-header{padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between}
    .ward-card-body{padding:16px 20px}

    .type-badge{font-size:11px;padding:3px 10px;border-radius:20px;font-weight:600}
    .type-ICU,.type-CCU,.type-NICU{background:#fee2e2;color:#991b1b}
    .type-General,.type-Surgical{background:#eff6ff;color:#1e40af}
    .type-Maternity,.type-Pediatric{background:#fdf4ff;color:#7e22ce}
    .type-Private,.type-Semi-Private{background:#f0fdf4;color:#15803d}
    .type-Orthopedic{background:#fff7ed;color:#c2410c}

    .bed-bar-wrap{background:#f1f5f9;border-radius:4px;height:8px;overflow:hidden;margin:6px 0}
    .bed-bar-fill{height:100%;border-radius:4px;transition:width .8s ease}

    .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-top:10px}
    .info-item{font-size:12px;color:#64748b;display:flex;align-items:center;gap:5px}
    .info-item i{width:13px;color:#94a3b8}
</style>
@endpush

@section('content')

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Wards</div>
                <div class="stat-pill-value">{{ $stats['total_wards'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Total beds</div>
                <div class="stat-pill-value">{{ $stats['total_beds'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Available</div>
                <div class="stat-pill-value" style="color:#16a34a">{{ $stats['available_beds'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Occupied</div>
                <div class="stat-pill-value" style="color:#dc2626">{{ $stats['occupied_beds'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Reserved</div>
                <div class="stat-pill-value" style="color:#d97706">{{ $stats['reserved_beds'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-pill">
                <div class="stat-pill-label">Maintenance</div>
                <div class="stat-pill-value" style="color:#6b7280">{{ $stats['maintenance_beds'] }}</div>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div style="font-size:14px;font-weight:600;color:#1e293b">
            <i class="bi bi-building me-2 text-primary"></i>All wards
        </div>
        <a href="{{ route('wards.create') }}"
           class="btn btn-sm btn-success px-3" style="height:36px;font-size:13px;line-height:2.2">
            <i class="bi bi-plus-lg me-1"></i>Add ward
        </a>
    </div>

    {{-- Wards Grid --}}
    @if($wards->count())
    <div class="row g-3">
        @foreach($wards as $ward)
        @php
            $available = $ward->beds->where('status','Available')->count();
            $occupied  = $ward->beds->where('status','Occupied')->count();
            $reserved  = $ward->beds->where('status','Reserved')->count();
            $maint     = $ward->beds->where('status','Maintenance')->count();
            $total     = $ward->total_beds ?: 1;
            $pct       = round(($occupied / $total) * 100);
            $barColor  = $pct >= 90 ? '#dc2626' : ($pct >= 70 ? '#d97706' : '#16a34a');
            $typeClass = str_replace([' ','-'], ['-','-'], $ward->type);
        @endphp
        <div class="col-12 col-md-6 col-xl-4">
            <div class="ward-card">
                <div class="ward-card-header">
                    <div>
                        <div style="font-size:14px;font-weight:600;color:#1e293b">{{ $ward->name }}</div>
                        <div style="font-size:11px;color:#94a3b8;margin-top:1px">{{ $ward->ward_code }}</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="type-badge type-{{ $typeClass }}">{{ $ward->type }}</span>
                        @if(!$ward->is_active)
                            <span style="font-size:10px;padding:2px 7px;border-radius:10px;background:#f3f4f6;color:#6b7280">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="ward-card-body">
                    {{-- Occupancy Bar --}}
                    <div class="d-flex justify-content-between align-items-center" style="font-size:12px;color:#64748b">
                        <span>Occupancy</span>
                        <span style="font-weight:600;color:#1e293b">{{ $pct }}%</span>
                    </div>
                    <div class="bed-bar-wrap">
                        <div class="bed-bar-fill" style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
                    </div>

                    {{-- Bed Status --}}
                    <div class="d-flex gap-3 mt-2 mb-3" style="font-size:12px">
                        <span style="color:#16a34a"><i class="bi bi-circle-fill" style="font-size:7px"></i> {{ $available }} Available</span>
                        <span style="color:#dc2626"><i class="bi bi-circle-fill" style="font-size:7px"></i> {{ $occupied }} Occupied</span>
                        @if($reserved) <span style="color:#d97706"><i class="bi bi-circle-fill" style="font-size:7px"></i> {{ $reserved }} Reserved</span> @endif
                        @if($maint)    <span style="color:#6b7280"><i class="bi bi-circle-fill" style="font-size:7px"></i> {{ $maint }} Maint.</span> @endif
                    </div>

                    {{-- Info --}}
                    <div class="info-grid">
                        <div class="info-item"><i class="bi bi-grid-3x2"></i>{{ $ward->total_beds }} total beds</div>
                        <div class="info-item"><i class="bi bi-cash"></i>Rs {{ number_format($ward->bed_charges,0) }}/day</div>
                        @if($ward->floor)
                        <div class="info-item"><i class="bi bi-layers"></i>{{ $ward->floor }} Floor</div>
                        @endif
                        @if($ward->block)
                        <div class="info-item"><i class="bi bi-building"></i>Block {{ $ward->block }}</div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('wards.show', $ward->id) }}"
                           class="btn btn-sm btn-primary flex-grow-1" style="font-size:12px">
                            <i class="bi bi-grid me-1"></i>View beds
                        </a>
                        <a href="{{ route('wards.edit', $ward->id) }}"
                           class="btn btn-sm btn-outline-warning px-2" style="font-size:12px">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('wards.destroy', $ward->id) }}"
                              onsubmit="return confirm('Delete {{ $ward->name }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger px-2" style="font-size:12px">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:60px;text-align:center">
        <i class="bi bi-building" style="font-size:48px;color:#e2e8f0;display:block;margin-bottom:12px"></i>
        <div style="font-size:15px;font-weight:500;color:#374151;margin-bottom:6px">No wards found</div>
        <div style="font-size:13px;color:#94a3b8;margin-bottom:16px">Add your first ward to get started</div>
        <a href="{{ route('wards.create') }}" class="btn btn-primary btn-sm px-4">
            <i class="bi bi-plus-lg me-1"></i>Add ward
        </a>
    </div>
    @endif

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.bed-bar-fill').forEach(b => {
        const w = b.style.width; b.style.width = '0';
        setTimeout(() => b.style.width = w, 100);
    });
</script>
@endpush