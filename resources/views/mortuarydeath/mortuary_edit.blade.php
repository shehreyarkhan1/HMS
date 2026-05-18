@extends('layouts.master')

@section('title', 'Edit — ' . $mortuary->mortuary_id)
@section('page-title', 'Edit Mortuary Record')
@section('breadcrumb', 'Home / Mortuary / ' . $mortuary->mortuary_id . ' / Edit')

@push('styles')
<style>
    .card-section { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
    .card-section-header { padding: 14px 20px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 10px; }
    .header-icon { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; }
    .section-body { padding: 20px; }
    .form-label-sm { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .04em; margin-bottom: 5px; }
    .form-control-clean, .form-select-clean {
        border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #374151;
        background: #f8fafc; padding: 8px 12px; width: 100%; outline: none; transition: border-color .2s;
    }
    .form-control-clean:focus, .form-select-clean:focus { border-color: #93c5fd; background: #fff; box-shadow: 0 0 0 3px rgba(59,130,246,.08); }
    .required-star { color: #dc2626; }
</style>
@endpush

@section('content')

    @if($errors->any())
        <div class="alert mb-4 d-flex align-items-start gap-2"
             style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;color:#991b1b;font-size:13px">
            <i class="bi bi-exclamation-triangle-fill mt-1"></i>
            <ul class="mb-0 ps-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('mortuary.update', $mortuary->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">

                {{-- 1. Death Info --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#fee2e2;color:#991b1b"><i class="bi bi-person-x"></i></div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Death Information</span>
                        <code style="font-size:11px;color:#6366f1;margin-left:auto">{{ $mortuary->mortuary_id }}</code>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            {{-- Patient — readonly in edit --}}
                            <div class="col-md-6">
                                <div class="form-label-sm">Patient</div>
                                <div style="background:#f1f5f9;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:13px;color:#64748b">
                                    {{ $mortuary->patient->name }} — {{ $mortuary->patient->mrn }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Date & Time of Death <span class="required-star">*</span></div>
                                <input type="datetime-local" name="death_datetime" class="form-control-clean"
                                       value="{{ old('death_datetime', \Carbon\Carbon::parse($mortuary->death_datetime)->format('Y-m-d\TH:i')) }}" required>
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-sm">Death Location <span class="required-star">*</span></div>
                                <select name="death_location" class="form-select-clean" required>
                                    @foreach(['Ward','ICU','CCU','Emergency','OT','DOA','Outside Hospital'] as $loc)
                                        <option value="{{ $loc }}" {{ old('death_location', $mortuary->death_location) == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-sm">Ward</div>
                                <input type="text" name="ward" class="form-control-clean"
                                       value="{{ old('ward', $mortuary->ward) }}">
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-sm">Bed Number</div>
                                <input type="text" name="bed_number" class="form-control-clean"
                                       value="{{ old('bed_number', $mortuary->bed_number) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Cause of Death --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#fef9c3;color:#854d0e"><i class="bi bi-clipboard2-pulse"></i></div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Cause of Death</span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-label-sm">Part I (a) — Immediate Cause <span class="required-star">*</span></div>
                                <input type="text" name="immediate_cause" class="form-control-clean"
                                       value="{{ old('immediate_cause', $mortuary->immediate_cause) }}" required>
                            </div>
                            <div class="col-12">
                                <div class="form-label-sm">Part I (b) — Intermediate Cause</div>
                                <input type="text" name="intermediate_cause" class="form-control-clean"
                                       value="{{ old('intermediate_cause', $mortuary->intermediate_cause) }}">
                            </div>
                            <div class="col-12">
                                <div class="form-label-sm">Part I (c) — Underlying Cause</div>
                                <input type="text" name="underlying_cause" class="form-control-clean"
                                       value="{{ old('underlying_cause', $mortuary->underlying_cause) }}">
                            </div>
                            <div class="col-12">
                                <div class="form-label-sm">Part II — Contributing Cause</div>
                                <input type="text" name="contributing_cause" class="form-control-clean"
                                       value="{{ old('contributing_cause', $mortuary->contributing_cause) }}">
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Manner of Death <span class="required-star">*</span></div>
                                <select name="manner_of_death" class="form-select-clean" required>
                                    @foreach(['Natural','Accidental','Homicidal','Suicidal','Undetermined'] as $m)
                                        <option value="{{ $m }}" {{ old('manner_of_death', $mortuary->manner_of_death) == $m ? 'selected' : '' }}>{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Declaring Doctor</div>
                                <select name="declared_by" class="form-select-clean">
                                    <option value="">— Select Doctor —</option>
                                    @foreach($doctors as $doc)
                                        <option value="{{ $doc->id }}" {{ old('declared_by', $mortuary->declared_by) == $doc->id ? 'selected' : '' }}>
                                            Dr. {{ $doc->employee->first_name }} {{ $doc->employee->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Declared At</div>
                                <input type="datetime-local" name="declared_at" class="form-control-clean"
                                       value="{{ old('declared_at', $mortuary->declared_at ? \Carbon\Carbon::parse($mortuary->declared_at)->format('Y-m-d\TH:i') : '') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. Postmortem --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#ede9fe;color:#5b21b6"><i class="bi bi-hospital"></i></div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Postmortem</span>
                        <span class="ms-auto badge {{ $mortuary->postmortem_status === 'Completed' ? 'bg-success' : 'bg-warning text-dark' }}" style="font-size:11px">
                            {{ $mortuary->postmortem_status }}
                        </span>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-label-sm">Status</div>
                                <select name="postmortem_status" class="form-select-clean">
                                    @foreach(['Not Required','Pending','In Progress','Completed'] as $ps)
                                        <option value="{{ $ps }}" {{ old('postmortem_status', $mortuary->postmortem_status) == $ps ? 'selected' : '' }}>{{ $ps }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-sm">Performed By</div>
                                <select name="postmortem_by" class="form-select-clean">
                                    <option value="">— Select Doctor —</option>
                                    @foreach($doctors as $doc)
                                        <option value="{{ $doc->id }}" {{ old('postmortem_by', $mortuary->postmortem_by) == $doc->id ? 'selected' : '' }}>
                                            Dr. {{ $doc->employee->first_name }} {{ $doc->employee->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-sm">Report Number</div>
                                <input type="text" name="postmortem_report_number" class="form-control-clean"
                                       value="{{ old('postmortem_report_number', $mortuary->postmortem_report_number) }}">
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Started At</div>
                                <input type="datetime-local" name="postmortem_started_at" class="form-control-clean"
                                       value="{{ old('postmortem_started_at', $mortuary->postmortem_started_at ? \Carbon\Carbon::parse($mortuary->postmortem_started_at)->format('Y-m-d\TH:i') : '') }}">
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Completed At</div>
                                <input type="datetime-local" name="postmortem_completed_at" class="form-control-clean"
                                       value="{{ old('postmortem_completed_at', $mortuary->postmortem_completed_at ? \Carbon\Carbon::parse($mortuary->postmortem_completed_at)->format('Y-m-d\TH:i') : '') }}">
                            </div>
                            <div class="col-12">
                                <div class="form-label-sm">Findings</div>
                                <textarea name="postmortem_findings" class="form-control-clean" rows="3">{{ old('postmortem_findings', $mortuary->postmortem_findings) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. MLC --}}
                <div class="card-section">
                    <div class="card-section-header">
                        <div class="header-icon" style="background:#fee2e2;color:#991b1b"><i class="bi bi-shield-exclamation"></i></div>
                        <span style="font-size:14px;font-weight:600;color:#1e293b">Medico Legal Case</span>
                        @if($mortuary->is_medico_legal)
                            <span class="ms-auto" style="font-size:11px;background:#fee2e2;color:#991b1b;padding:3px 10px;border-radius:20px;font-weight:600">MLC Active</span>
                        @endif
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label style="font-size:13px;cursor:pointer;display:flex;align-items:center;gap:8px">
                                    <input type="checkbox" name="is_medico_legal" value="1"
                                           {{ old('is_medico_legal', $mortuary->is_medico_legal) ? 'checked' : '' }}
                                           style="width:16px;height:16px">
                                    <span style="font-weight:500">This is a Medico Legal Case</span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-sm">MLC Number</div>
                                <input type="text" name="mlc_number" class="form-control-clean"
                                       value="{{ old('mlc_number', $mortuary->mlc_number) }}">
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-sm">Police Station</div>
                                <input type="text" name="police_station" class="form-control-clean"
                                       value="{{ old('police_station', $mortuary->police_station) }}">
                            </div>
                            <div class="col-md-4">
                                <div class="form-label-sm">FIR Number</div>
                                <input type="text" name="fir_number" class="form-control-clean"
                                       value="{{ old('fir_number', $mortuary->fir_number) }}">
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Investigating Officer</div>
                                <input type="text" name="investigating_officer" class="form-control-clean"
                                       value="{{ old('investigating_officer', $mortuary->investigating_officer) }}">
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-sm">Police Informed At</div>
                                <input type="datetime-local" name="police_informed_at" class="form-control-clean"
                                       value="{{ old('police_informed_at', $mortuary->police_informed_at ? \Carbon\Carbon::parse($mortuary->police_informed_at)->format('Y-m-d\TH:i') : '') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="card-section">
                    <div class="section-body">
                        <div class="form-label-sm">Notes</div>
                        <textarea name="notes" class="form-control-clean" rows="3">{{ old('notes', $mortuary->notes) }}</textarea>
                    </div>
                </div>

            </div>{{-- end col-lg-8 --}}

            {{-- RIGHT --}}
            <div class="col-lg-4">
                <div style="position:sticky;top:80px">

                    {{-- NOK --}}
                    <div class="card-section">
                        <div class="card-section-header">
                            <div class="header-icon" style="background:#dbeafe;color:#1e40af"><i class="bi bi-people"></i></div>
                            <span style="font-size:14px;font-weight:600;color:#1e293b">Next of Kin</span>
                        </div>
                        <div class="section-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-label-sm">Name</div>
                                    <input type="text" name="nok_name" class="form-control-clean" value="{{ old('nok_name', $mortuary->nok_name) }}">
                                </div>
                                <div class="col-6">
                                    <div class="form-label-sm">Relation</div>
                                    <input type="text" name="nok_relation" class="form-control-clean" value="{{ old('nok_relation', $mortuary->nok_relation) }}">
                                </div>
                                <div class="col-6">
                                    <div class="form-label-sm">Phone</div>
                                    <input type="text" name="nok_phone" class="form-control-clean" value="{{ old('nok_phone', $mortuary->nok_phone) }}">
                                </div>
                                <div class="col-12">
                                    <div class="form-label-sm">CNIC</div>
                                    <input type="text" name="nok_cnic" class="form-control-clean" value="{{ old('nok_cnic', $mortuary->nok_cnic) }}">
                                </div>
                                <div class="col-12">
                                    <label style="font-size:13px;cursor:pointer;display:flex;align-items:center;gap:8px">
                                        <input type="checkbox" name="nok_informed" value="1"
                                               {{ old('nok_informed', $mortuary->nok_informed) ? 'checked' : '' }}
                                               style="width:15px;height:15px">
                                        Family Informed
                                    </label>
                                </div>
                                <div class="col-12">
                                    <div class="form-label-sm">Informed At</div>
                                    <input type="datetime-local" name="nok_informed_at" class="form-control-clean"
                                           value="{{ old('nok_informed_at', $mortuary->nok_informed_at ? \Carbon\Carbon::parse($mortuary->nok_informed_at)->format('Y-m-d\TH:i') : '') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Body Info --}}
                    <div class="card-section">
                        <div class="card-section-header">
                            <div class="header-icon" style="background:#f0fdf4;color:#15803d"><i class="bi bi-box-seam"></i></div>
                            <span style="font-size:14px;font-weight:600;color:#1e293b">Body Information</span>
                        </div>
                        <div class="section-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="form-label-sm">Locker</div>
                                    <input type="text" name="locker_number" class="form-control-clean"
                                           value="{{ old('locker_number', $mortuary->locker_number) }}">
                                </div>
                                <div class="col-6">
                                    <div class="form-label-sm">Weight (kg)</div>
                                    <input type="number" name="body_weight_kg" class="form-control-clean"
                                           value="{{ old('body_weight_kg', $mortuary->body_weight_kg) }}" step="0.1">
                                </div>
                                <div class="col-12">
                                    <div class="form-label-sm">Condition</div>
                                    <select name="body_condition" class="form-select-clean">
                                        <option value="">— Select —</option>
                                        @foreach(['Normal','Decomposed','Burned','Traumatic','Other'] as $c)
                                            <option value="{{ $c }}" {{ old('body_condition', $mortuary->body_condition) == $c ? 'selected' : '' }}>{{ $c }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning" style="padding:10px;font-size:14px;font-weight:600;border-radius:10px;color:#000">
                            <i class="bi bi-check-circle me-2"></i>Update Record
                        </button>
                        <a href="{{ route('mortuary.show', $mortuary->id) }}"
                           class="btn btn-outline-secondary" style="padding:10px;font-size:13px;border-radius:10px">
                            <i class="bi bi-arrow-left me-2"></i>Cancel
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </form>

@endsection
