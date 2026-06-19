@extends('layouts.master')
@section('title', 'Nurse Assign Karein')
@section('page-title', 'Nurse Ward Assignment')
@section('breadcrumb', 'Home / Ward / Nurse Assignments / Create')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">

        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden">
            <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;background:#f8fafc">
                <h6 style="margin:0;font-weight:600;color:#1e293b">
                    <i class="bi bi-person-nurse me-2 text-primary"></i>Assign the ward to nurse
                </h6>
            </div>

            <form method="POST" action="{{ route('ward.nurse-assignments.store') }}" style="padding:20px">
                @csrf

                {{-- Ward --}}
                <div class="mb-3">
                    <label class="form-label" style="font-size:13px;font-weight:600;color:#374151">
                        Ward <span class="text-danger">*</span>
                    </label>
                    <select name="ward_id" class="form-select @error('ward_id') is-invalid @enderror" required>
                        <option value="">--  Select the Ward --</option>
                        @foreach($wards as $ward)
                        <option value="{{ $ward->id }}" {{ old('ward_id') == $ward->id ? 'selected' : '' }}>
                            {{ $ward->name }} ({{ $ward->ward_code }}) — {{ $ward->type }}
                        </option>
                        @endforeach
                    </select>
                    @error('ward_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nurse --}}
                <div class="mb-3">
                    <label class="form-label" style="font-size:13px;font-weight:600;color:#374151">
                        Nurse <span class="text-danger">*</span>
                    </label>
                    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        <option value="">-- Select The Nurse --</option>
                        @foreach($nurses as $nurse)
                        <option value="{{ $nurse->id }}" {{ old('user_id') == $nurse->id ? 'selected' : '' }}>
                            {{ $nurse->name }} ({{ $nurse->email }})
                        </option>
                        @endforeach
                    </select>
                    @error('user_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Shift --}}
                <div class="mb-3">
                    <label class="form-label" style="font-size:13px;font-weight:600;color:#374151">
                        Shift <span class="text-danger">*</span>
                    </label>
                    <div class="d-flex gap-3">
                        @foreach(['Morning', 'Evening', 'Night'] as $s)
                        <label style="cursor:pointer;flex:1">
                            <input type="radio" name="shift" value="{{ $s }}"
                                   {{ old('shift', 'Morning') === $s ? 'checked' : '' }}
                                   style="display:none" class="shift-radio">
                            <div class="shift-option text-center py-2 px-3 rounded border"
                                 style="font-size:13px;font-weight:500;transition:.15s">
                                @if($s === 'Morning') 🌅
                                @elseif($s === 'Evening') 🌆
                                @else 🌙
                                @endif
                                {{ $s }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('shift')
                    <div class="text-danger" style="font-size:12px;margin-top:4px">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Dates --}}
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label" style="font-size:13px;font-weight:600;color:#374151">
                            Start Date <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="start_date"
                               value="{{ old('start_date', today()->format('Y-m-d')) }}"
                               class="form-control @error('start_date') is-invalid @enderror" required>
                        @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label" style="font-size:13px;font-weight:600;color:#374151">
                            End Date
                            <span style="font-size:11px;color:#94a3b8">(khali = permanent)</span>
                        </label>
                        <input type="date" name="end_date"
                               value="{{ old('end_date') }}"
                               class="form-control @error('end_date') is-invalid @enderror">
                        @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Assign
                    </button>
                    <a href="{{ route('ward.nurse-assignments.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .shift-radio:checked + .shift-option {
        background: #eff6ff;
        border-color: #1d4ed8 !important;
        color: #1d4ed8;
    }
    .shift-option:hover { background: #f8fafc; }
</style>

<script>
    // Shift radio visual update
    document.querySelectorAll('.shift-radio').forEach(radio => {
        radio.addEventListener('change', () => {
            document.querySelectorAll('.shift-option').forEach(el => {
                el.style.background = '';
                el.style.borderColor = '';
                el.style.color = '';
            });
            if (radio.checked) {
                radio.nextElementSibling.style.background = '#eff6ff';
                radio.nextElementSibling.style.borderColor = '#1d4ed8';
                radio.nextElementSibling.style.color = '#1d4ed8';
            }
        });
        // Initial state
        if (radio.checked) {
            radio.nextElementSibling.style.background = '#eff6ff';
            radio.nextElementSibling.style.borderColor = '#1d4ed8';
            radio.nextElementSibling.style.color = '#1d4ed8';
        }
    });
</script>
@endsection
