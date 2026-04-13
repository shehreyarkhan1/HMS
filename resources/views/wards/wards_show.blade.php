@extends('layouts.master')

@section('title', $ward->name)
@section('page-title', $ward->name)
@section('breadcrumb', 'Home / Wards / ' . $ward->ward_code)

@push('styles')
<style>
    .bed-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:10px}

    .bed-card{border-radius:10px;padding:12px;border:1.5px solid;cursor:pointer;transition:transform .15s,box-shadow .15s;position:relative}
    .bed-card:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.08)}

    .bed-Available   {background:#f0fdf4;border-color:#bbf7d0}
    .bed-Occupied    {background:#fef2f2;border-color:#fecaca}
    .bed-Reserved    {background:#fffbeb;border-color:#fde68a}
    .bed-Maintenance {background:#f9fafb;border-color:#e5e7eb}

    .bed-number{font-size:13px;font-weight:700;margin-bottom:4px}
    .bed-Available .bed-number    {color:#15803d}
    .bed-Occupied .bed-number     {color:#dc2626}
    .bed-Reserved .bed-number     {color:#ca8a04}
    .bed-Maintenance .bed-number  {color:#6b7280}

    .bed-status-label{font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.05em}
    .bed-Available .bed-status-label    {color:#16a34a}
    .bed-Occupied .bed-status-label     {color:#dc2626}
    .bed-Reserved .bed-status-label     {color:#ca8a04}
    .bed-Maintenance .bed-status-label  {color:#6b7280}

    .bed-patient{font-size:11px;color:#374151;margin-top:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .bed-days{font-size:10px;color:#94a3b8;margin-top:2px}

    .bed-icon{position:absolute;top:8px;right:8px;font-size:14px}
    .bed-Available .bed-icon    {color:#16a34a}
    .bed-Occupied .bed-icon     {color:#dc2626}
    .bed-Reserved .bed-icon     {color:#ca8a04}
    .bed-Maintenance .bed-icon  {color:#9ca3af}

    .legend-dot{width:10px;height:10px;border-radius:50%;display:inline-block}
</style>
@endpush

@section('content')

<div class="row g-3">

    {{-- Ward Info --}}
    <div class="col-12">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div class="d-flex align-items-center gap-3">
                <div style="width:44px;height:44px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center">
                    <i class="bi bi-building" style="font-size:20px;color:#1d4ed8"></i>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:600;color:#1e293b">{{ $ward->name }}</div>
                    <div style="font-size:12px;color:#94a3b8">
                        {{ $ward->ward_code }} &bull; {{ $ward->type }}
                        @if($ward->floor) &bull; {{ $ward->floor }} Floor @endif
                        @if($ward->block) &bull; Block {{ $ward->block }} @endif
                    </div>
                </div>
            </div>
            <div class="d-flex gap-3" style="font-size:13px">
                <span style="color:#16a34a"><i class="bi bi-circle-fill me-1" style="font-size:8px"></i>{{ $ward->beds->where('status','Available')->count() }} Available</span>
                <span style="color:#dc2626"><i class="bi bi-circle-fill me-1" style="font-size:8px"></i>{{ $ward->beds->where('status','Occupied')->count() }} Occupied</span>
                <span style="color:#ca8a04"><i class="bi bi-circle-fill me-1" style="font-size:8px"></i>{{ $ward->beds->where('status','Reserved')->count() }} Reserved</span>
                <span style="color:#9ca3af"><i class="bi bi-circle-fill me-1" style="font-size:8px"></i>{{ $ward->beds->where('status','Maintenance')->count() }} Maint.</span>
            </div>
            <div class="d-flex gap-2">
                <span style="font-size:13px;font-weight:600;color:#15803d;background:#f0fdf4;padding:4px 12px;border-radius:8px">
                    Rs {{ number_format($ward->bed_charges, 0) }}/day
                </span>
                <a href="{{ route('wards.edit', $ward->id) }}"
                   class="btn btn-sm btn-outline-warning" style="font-size:13px">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <a href="{{ route('wards.index') }}"
                   class="btn btn-sm btn-outline-secondary" style="font-size:13px">
                    Back
                </a>
            </div>
        </div>
    </div>

    {{-- Assign Patient Form --}}
    @if($patients->count() > 0 && $ward->beds->where('status','Available')->count() > 0)
    <div class="col-12">
        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:16px 20px">
            <div style="font-size:13px;font-weight:600;color:#1e40af;margin-bottom:10px">
                <i class="bi bi-person-plus me-2"></i>Assign patient to a bed
            </div>
            <form method="POST" action="{{ route('wards.assign-bed') }}" class="d-flex gap-2 flex-wrap">
                @csrf
                <select name="patient_id" class="form-select" style="max-width:240px;font-size:13px;height:36px;border:1px solid #bfdbfe;border-radius:8px">
                    <option value="">Select IPD patient...</option>
                    @foreach($patients as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->mrn }})</option>
                    @endforeach
                </select>
                <select name="bed_id" class="form-select" style="max-width:200px;font-size:13px;height:36px;border:1px solid #bfdbfe;border-radius:8px">
                    <option value="">Select available bed...</option>
                    @foreach($ward->beds->where('status','Available') as $bed)
                        <option value="{{ $bed->id }}">{{ $bed->bed_number }} ({{ $bed->type }})</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm px-4" style="height:36px;font-size:13px">
                    <i class="bi bi-check-lg me-1"></i>Assign
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Beds Grid --}}
    <div class="col-12">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px">
            <div style="font-size:13px;font-weight:600;color:#1e293b;margin-bottom:16px">
                <i class="bi bi-grid me-2 text-primary"></i>Bed map — {{ $ward->total_beds }} beds total
            </div>

            <div class="bed-grid">
                @foreach($ward->beds->sortBy('bed_number') as $bed)
                <div class="bed-card bed-{{ $bed->status }}"
                     data-bed-id="{{ $bed->id }}"
                     data-status="{{ $bed->status }}"
                     onclick="showBedActions({{ $bed->id }}, '{{ $bed->status }}', '{{ $bed->bed_number }}')">

                    <i class="bi {{ $bed->status == 'Available' ? 'bi-check-circle' : ($bed->status == 'Occupied' ? 'bi-person-fill' : ($bed->status == 'Reserved' ? 'bi-clock' : 'bi-tools')) }} bed-icon"></i>

                    <div class="bed-number">{{ $bed->bed_number }}</div>
                    <div class="bed-status-label">{{ $bed->status }}</div>

                    @if($bed->status == 'Occupied' && $bed->patient)
                        <div class="bed-patient">{{ $bed->patient->name }}</div>
                        @if($bed->admitted_at)
                        <div class="bed-days">
                            {{ $bed->admitted_at->diffInDays(now()) }} days
                        </div>
                        @endif
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- Action Modal --}}
<div id="bedModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:24px;width:340px;max-width:90vw">
        <div style="font-size:15px;font-weight:600;color:#1e293b;margin-bottom:4px" id="modalTitle">Bed Actions</div>
        <div style="font-size:12px;color:#94a3b8;margin-bottom:16px" id="modalSub"></div>
        <div id="modalActions" class="d-grid gap-2"></div>
        <button onclick="closeModal()" class="btn btn-outline-secondary w-100 mt-2" style="font-size:13px">Cancel</button>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showBedActions(bedId, status, bedNum) {
    document.getElementById('modalTitle').textContent = 'Bed ' + bedNum;
    document.getElementById('modalSub').textContent = 'Status: ' + status;
    const actions = document.getElementById('modalActions');
    actions.innerHTML = '';

    if (status === 'Occupied') {
        actions.innerHTML = `
            <form method="POST" action="/wards/beds/${bedId}/discharge">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-danger w-100" style="font-size:13px"
                        onclick="return confirm('Discharge patient from this bed?')">
                    <i class="bi bi-box-arrow-right me-2"></i>Discharge patient
                </button>
            </form>`;
    } else if (status !== 'Occupied') {
        const opts = status === 'Available'
            ? [['Reserved','warning'],['Maintenance','secondary']]
            : [['Available','success'],['Maintenance','secondary']];
        opts.forEach(([s, c]) => {
            actions.innerHTML += `
                <form method="POST" action="/wards/beds/${bedId}/status">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="status" value="${s}">
                    <button type="submit" class="btn btn-${c} w-100" style="font-size:13px">
                        Mark as ${s}
                    </button>
                </form>`;
        });
    }

    document.getElementById('bedModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('bedModal').style.display = 'none';
}

document.getElementById('bedModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endpush