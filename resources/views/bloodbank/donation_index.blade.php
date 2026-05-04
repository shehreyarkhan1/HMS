@extends('layouts.master')

@section('title', 'Blood Donations')
@section('page-title', 'Blood Donations')
@section('breadcrumb', 'Home / Blood Bank / Donations')

@push('styles')
<style>
    .stat-pill { background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:14px 20px; }
    .stat-pill-label { font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; }
    .stat-pill-value { font-size:22px; font-weight:700; color:#1e293b; }

    .filter-bar input, .filter-bar select {
        height:36px; border:1px solid #e2e8f0; border-radius:8px;
        font-size:13px; padding:0 12px; color:#374151; background:#f8fafc;
    }
    .filter-bar input:focus, .filter-bar select:focus { outline:none; border-color:#93c5fd; background:#fff; }

    .table-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; }
    table { width:100%; border-collapse:collapse; }
    thead th {
        background:#f8fafc; font-size:11px; font-weight:600; color:#64748b;
        text-transform:uppercase; letter-spacing:.05em; padding:10px 16px;
        border-bottom:1px solid #e2e8f0; white-space:nowrap;
    }
    tbody td { padding:12px 16px; font-size:13px; color:#374151; border-bottom:1px solid #f8fafc; }
    tbody tr:last-child td { border-bottom:none; }
    tbody tr:hover td { background:#fafafa; }

    .status-badge, .screening-badge { font-size:10px; padding:2px 8px; border-radius:20px; font-weight:600; }
    .s-Available  { background:#dcfce7; color:#166534; }
    .s-Reserved   { background:#dbeafe; color:#1e40af; }
    .s-Issued     { background:#f1f5f9; color:#475569; }
    .s-Expired    { background:#fef9c3; color:#854d0e; }
    .s-Discarded  { background:#fee2e2; color:#991b1b; }
    .sc-Passed    { background:#dcfce7; color:#166534; }
    .sc-Pending   { background:#fef9c3; color:#854d0e; }
    .sc-Failed    { background:#fee2e2; color:#991b1b; }
    .sc-Discarded { background:#f1f5f9; color:#475569; }

    .blood-badge { font-size:12px; font-weight:800; padding:3px 8px; border-radius:6px; background:#fee2e2; color:#b91c1c; }
    .expiry-warn { color:#d97706; font-weight:600; }
    .expiry-crit { color:#dc2626; font-weight:600; }

    .modal-body .form-label { font-size:12px; font-weight:500; color:#374151; margin-bottom:4px; }
    .modal-body .form-control, .modal-body .form-select {
        font-size:13px; border:1px solid #e2e8f0; border-radius:8px;
        background:#f8fafc; height:36px; padding:0 12px;
    }
    .modal-body .form-control:focus, .modal-body .form-select:focus { border-color:#93c5fd; background:#fff; box-shadow:none; }
    .modal-body .check-row { display:flex; align-items:center; gap:8px; font-size:13px; color:#374151; padding:6px 0; }
    .modal-body .check-row input { accent-color:#ef4444; width:15px; height:15px; }
</style>
@endpush

@section('content')

    {{-- Top --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('blood-bank.index') }}"
           class="btn btn-sm btn-outline-secondary px-3" style="height:34px;font-size:13px">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
        <button type="button" class="btn btn-sm btn-success px-3" style="height:34px;font-size:13px"
                data-bs-toggle="modal" data-bs-target="#recordDonationModal">
            <i class="bi bi-plus-lg me-1"></i>Record Donation
        </button>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-pill"><div class="stat-pill-label">Total</div><div class="stat-pill-value">{{ $stats['total'] }}</div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill"><div class="stat-pill-label">Available</div><div class="stat-pill-value" style="color:#16a34a">{{ $stats['available'] }}</div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill"><div class="stat-pill-label">Expiring (3 days)</div><div class="stat-pill-value" style="color:#d97706">{{ $stats['expiring'] }}</div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-pill"><div class="stat-pill-label">Today</div><div class="stat-pill-value">{{ $stats['today'] }}</div></div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:14px 20px;margin-bottom:16px">
        <form method="GET" action="{{ route('blood-bank.donations.index') }}"
              class="d-flex align-items-center gap-2 flex-wrap filter-bar">
            <input type="text" name="search" placeholder="Donation ID, bag number, donor name..."
                   value="{{ request('search') }}" style="width:220px">

            <select name="blood_group">
                <option value="">All Blood Groups</option>
                @foreach($bloodGroups as $bg)
                    <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                @endforeach
            </select>
            <select name="component">
                <option value="">All Components</option>
                @foreach(['Whole Blood','Packed RBC','Platelets','Fresh Frozen Plasma','Cryoprecipitate'] as $c)
                    <option value="{{ $c }}" {{ request('component') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>
            <select name="status">
                <option value="">All Status</option>
                @foreach(['Available','Reserved','Issued','Expired','Discarded'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
            <select name="screening_status">
                <option value="">All Screening</option>
                @foreach(['Pending','Passed','Failed','Discarded'] as $s)
                    <option value="{{ $s }}" {{ request('screening_status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-sm btn-primary px-3" style="height:36px;font-size:13px">
                <i class="bi bi-search me-1"></i>Filter
            </button>
            @if(request()->hasAny(['search','blood_group','component','status','screening_status']))
                <a href="{{ route('blood-bank.donations.index') }}"
                   class="btn btn-sm btn-outline-secondary px-3" style="height:36px;font-size:13px">Clear</a>
            @endif
        </form>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="font-size:13px;border-radius:10px" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" style="font-size:13px;border-radius:10px" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Table --}}
    @if($donations->count())
    <div class="table-card mb-3">
        <table>
            <thead>
                <tr>
                    <th>Donation ID</th>
                    <th>Donor</th>
                    <th>Blood Group</th>
                    <th>Component</th>
                    <th>Volume</th>
                    <th>Date</th>
                    <th>Expiry</th>
                    <th>Screening</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donations as $donation)
                @php
                    $days     = $donation->daysUntilExpiry();
                    $exClass  = $days === 0 ? 'expiry-crit' : ($days <= 3 ? 'expiry-warn' : '');
                    $scClass  = 'sc-' . $donation->screening_status;
                    $sClass   = 's-' . $donation->status;
                @endphp
                <tr>
                    <td style="font-family:monospace;font-size:12px;color:#6366f1">{{ $donation->donation_id }}</td>
                    <td>
                        <div style="font-weight:500">{{ $donation->donor->name }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ $donation->donor->donor_id }}</div>
                    </td>
                    <td><span class="blood-badge">{{ $donation->blood_group }}</span></td>
                    <td style="font-size:12px">{{ $donation->component }}</td>
                    <td style="font-size:12px">{{ $donation->volume_ml }} ml</td>
                    <td style="font-size:12px">{{ $donation->donation_date->format('d M Y') }}</td>
                    <td class="{{ $exClass }}" style="font-size:12px">
                        {{ $donation->expiry_date->format('d M Y') }}
                        @if($days <= 5)
                            <div style="font-size:10px">({{ $days }}d)</div>
                        @endif
                    </td>
                    <td><span class="screening-badge {{ $scClass }}">{{ $donation->screening_status }}</span></td>
                    <td><span class="status-badge {{ $sClass }}">{{ $donation->status }}</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            @if($donation->screening_status === 'Pending')
                            <button type="button"
                                    class="btn btn-sm btn-outline-info py-0 px-2 screening-btn"
                                    style="font-size:11px"
                                    data-bs-toggle="modal" data-bs-target="#screeningModal"
                                    data-id="{{ $donation->id }}"
                                    data-donation="{{ $donation->donation_id }}"
                                    title="Update Screening">
                                <i class="bi bi-shield-check"></i>
                            </button>
                            @endif
                            <form method="POST" action="{{ route('blood-bank.donations.destroy', $donation->id) }}"
                                  onsubmit="return confirm('Remove {{ $donation->donation_id }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:11px">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($donations->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-2">
        <span style="font-size:12px;color:#94a3b8">
            Showing {{ $donations->firstItem() }}–{{ $donations->lastItem() }} of {{ $donations->total() }}
        </span>
        {{ $donations->links('pagination::bootstrap-5') }}
    </div>
    @endif

    @else
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:60px;text-align:center">
        <i class="bi bi-droplet" style="font-size:48px;color:#e2e8f0;display:block;margin-bottom:12px"></i>
        <div style="font-size:15px;font-weight:500;color:#374151;margin-bottom:6px">No donations recorded</div>
        <button type="button" class="btn btn-primary btn-sm px-4 mt-2"
                data-bs-toggle="modal" data-bs-target="#recordDonationModal">
            <i class="bi bi-plus-lg me-1"></i>Record Donation
        </button>
    </div>
    @endif


    {{-- ── RECORD DONATION MODAL ───────────────────────────────────────── --}}
    <div class="modal fade" id="recordDonationModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius:16px;border:none">
                <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:20px 24px">
                    <h5 class="modal-title" style="font-size:15px;font-weight:600">Record Blood Donation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('blood-bank.donations.store') }}">
                    @csrf
                    <div class="modal-body" style="padding:24px">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Donor <span style="color:#ef4444">*</span></label>
                                <select name="donor_id" class="form-select" required>
                                    <option value="">— Select Eligible Donor —</option>
                                    @foreach($donors as $donor)
                                        <option value="{{ $donor->id }}">
                                            {{ $donor->name }} — {{ $donor->blood_group }} ({{ $donor->donor_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Blood Group <span style="color:#ef4444">*</span></label>
                                <select name="blood_group" class="form-select" required>
                                    <option value="">— Select —</option>
                                    @foreach($bloodGroups as $bg)
                                        <option value="{{ $bg }}">{{ $bg }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Component <span style="color:#ef4444">*</span></label>
                                <select name="component" class="form-select" required>
                                    @foreach(['Whole Blood','Packed RBC','Platelets','Fresh Frozen Plasma','Cryoprecipitate'] as $c)
                                        <option value="{{ $c }}">{{ $c }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Donation Date <span style="color:#ef4444">*</span></label>
                                <input type="date" name="donation_date" class="form-control"
                                       value="{{ today()->format('Y-m-d') }}" max="{{ today()->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Volume (ml)</label>
                                <input type="number" name="volume_ml" class="form-control" value="450" min="100" max="600">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Bag Number</label>
                                <input type="text" name="bag_number" class="form-control" placeholder="Physical bag label">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Collected By</label>
                                <select name="collected_by" class="form-select">
                                    <option value="">— Select Staff —</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">
                                            {{ $emp->first_name }} {{ $emp->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2" style="height:auto;padding:8px 12px"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:16px 24px">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm px-4">
                            <i class="bi bi-droplet me-1"></i>Record Donation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── SCREENING MODAL ─────────────────────────────────────────────── --}}
    <div class="modal fade" id="screeningModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:16px;border:none">
                <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:20px 24px">
                    <h5 class="modal-title" style="font-size:15px;font-weight:600">
                        Update Screening — <span id="screening-donation-id" style="color:#6366f1"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="screeningForm" action="">
                    @csrf @method('PATCH')
                    <div class="modal-body" style="padding:24px">
                        <div class="mb-3">
                            <label class="form-label">Screening Result <span style="color:#ef4444">*</span></label>
                            <select name="screening_status" class="form-select" required>
                                @foreach(['Pending','Passed','Failed','Discarded'] as $s)
                                    <option value="{{ $s }}">{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="background:#f8fafc;border-radius:8px;padding:14px;margin-bottom:12px">
                            <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px">Tests Performed</div>
                            <label class="check-row"><input type="checkbox" name="hiv_tested" value="1"> HIV</label>
                            <label class="check-row"><input type="checkbox" name="hbsag_tested" value="1"> HBsAg (Hepatitis B)</label>
                            <label class="check-row"><input type="checkbox" name="hcv_tested" value="1"> HCV (Hepatitis C)</label>
                            <label class="check-row"><input type="checkbox" name="vdrl_tested" value="1"> VDRL (Syphilis)</label>
                            <label class="check-row"><input type="checkbox" name="malaria_tested" value="1"> Malaria</label>
                        </div>
                        <div>
                            <label class="form-label">Notes</label>
                            <textarea name="screening_notes" class="form-control" rows="2" style="height:auto;padding:8px 12px"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:16px 24px">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Save Result</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.screening-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        document.getElementById('screening-donation-id').textContent = this.dataset.donation;
        document.getElementById('screeningForm').action = `/blood-bank/donations/${id}/screening`;
    });
});
</script>
@endpush