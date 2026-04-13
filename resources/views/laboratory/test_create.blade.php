@extends('layouts.master')

@section('title', 'New Lab Test')
@section('page-title', 'Laboratory — New Test')
@section('breadcrumb', 'Home / Laboratory / Tests / New')

@push('styles')
    <style>
        .form-section {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px
        }

        .form-section-header {
            padding: 12px 20px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .form-section-body {
            padding: 20px
        }

        .form-label-sm {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
            display: block
        }

        .fc {
            height: 36px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            padding: 0 12px;
            color: #374151;
            background: #f8fafc;
            width: 100%
        }

        .fc:focus {
            outline: none;
            border-color: #93c5fd;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(147, 197, 253, .15)
        }

        textarea.fc {
            height: 80px;
            padding: 8px 12px
        }

        select.fc option[value=""] {
            color: #94a3b8
        }
    </style>
@endpush

@section('content')

    <form method="POST" action="{{ route('lab.tests.store') }}">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">

                {{-- Basic Info --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <i class="bi bi-flask text-primary"></i>
                        Basic Information
                    </div>
                    <div class="form-section-body">
                        <div class="row g-3">

                            <div class="col-md-8">
                                <label class="form-label-sm">Test Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="fc" required
                                    value="{{ old('name') }}"
                                    placeholder="e.g. Complete Blood Count">
                                @error('name')
                                    <div class="text-danger" style="font-size:11px;margin-top:4px">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label-sm">Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="fc" required>
                                    <option value="">— Select category —</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="text-danger" style="font-size:11px;margin-top:4px">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label-sm">Sample Type</label>
                                <select name="sample_type_id" class="fc">
                                    <option value="">— None / Not applicable —</option>
                                    @foreach($sampleTypes as $st)
                                        <option value="{{ $st->id }}"
                                            {{ old('sample_type_id') == $st->id ? 'selected' : '' }}>
                                            {{ $st->name }}
                                            @if($st->container_type) ({{ $st->container_type }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label-sm">Price (Rs) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="fc" required min="0" step="0.01"
                                    value="{{ old('price') }}" placeholder="500">
                                @error('price')
                                    <div class="text-danger" style="font-size:11px;margin-top:4px">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label-sm">Unit <span style="color:#94a3b8;font-weight:400">(result unit)</span></label>
                                <input type="text" name="unit" class="fc"
                                    value="{{ old('unit') }}"
                                    placeholder="e.g. mg/dL, g/dL, %">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label-sm">Turnaround (hours) <span class="text-danger">*</span></label>
                                <input type="number" name="turnaround_hours" class="fc" required min="0"
                                    value="{{ old('turnaround_hours', 24) }}" placeholder="24">
                                @error('turnaround_hours')
                                    <div class="text-danger" style="font-size:11px;margin-top:4px">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label-sm">Method <span style="color:#94a3b8;font-weight:400">(optional)</span></label>
                                <input type="text" name="method" class="fc"
                                    value="{{ old('method') }}"
                                    placeholder="ELISA, PCR, Microscopy, etc.">
                            </div>

                            <div class="col-md-4 d-flex align-items-end pb-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="requires_fasting"
                                        value="1" id="requires_fasting"
                                        {{ old('requires_fasting') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="requires_fasting" style="font-size:13px;color:#374151">
                                        Requires Fasting
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label-sm">Description <span style="color:#94a3b8;font-weight:400">(optional)</span></label>
                                <textarea name="description" class="fc"
                                    placeholder="Any additional notes about this test...">{{ old('description') }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Normal Ranges --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <i class="bi bi-bar-chart-line text-success"></i>
                        Normal Ranges
                        <span style="font-size:11px;font-weight:400;color:#94a3b8">
                            Format: <code style="font-size:11px;background:#f1f5f9;padding:1px 5px;border-radius:4px">4.5 - 11.0</code>
                            or <code style="font-size:11px;background:#f1f5f9;padding:1px 5px;border-radius:4px">Negative</code>
                        </span>
                    </div>
                    <div class="form-section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-sm">General / Fallback</label>
                                <input type="text" name="normal_range" class="fc"
                                    value="{{ old('normal_range') }}"
                                    placeholder="e.g. 4.5 - 11.0">
                                <div style="font-size:11px;color:#94a3b8;margin-top:3px">
                                    Used when no gender/age specific range is set
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-sm">Male Specific</label>
                                <input type="text" name="normal_range_male" class="fc"
                                    value="{{ old('normal_range_male') }}"
                                    placeholder="e.g. 13.5 - 17.5">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-sm">Female Specific</label>
                                <input type="text" name="normal_range_female" class="fc"
                                    value="{{ old('normal_range_female') }}"
                                    placeholder="e.g. 12.0 - 15.5">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label-sm">Child (&lt;12 yrs)</label>
                                <input type="text" name="normal_range_child" class="fc"
                                    value="{{ old('normal_range_child') }}"
                                    placeholder="e.g. 11.0 - 16.0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label-sm">Elderly (&gt;60 yrs)</label>
                                <input type="text" name="normal_range_elderly" class="fc"
                                    value="{{ old('normal_range_elderly') }}"
                                    placeholder="e.g. 11.5 - 17.0">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right: Save panel --}}
            <div class="col-lg-4">
                <div style="position:sticky;top:20px">
                    <div class="form-section">
                        <div class="form-section-header">
                            <i class="bi bi-save text-primary"></i>Save Test
                        </div>
                        <div class="form-section-body">

                            {{-- Quick preview --}}
                            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px;margin-bottom:16px;font-size:12px;color:#64748b">
                                <div style="font-weight:600;color:#374151;margin-bottom:6px">
                                    <i class="bi bi-info-circle me-1 text-primary"></i>Before saving
                                </div>
                                <ul style="margin:0;padding-left:16px;line-height:1.8">
                                    <li>Test code is auto-generated (T-0001)</li>
                                    <li>At least <strong>General</strong> normal range recommended</li>
                                    <li>Price is required</li>
                                </ul>
                            </div>

                            <button type="submit" class="btn btn-success w-100"
                                style="height:40px;font-size:14px;font-weight:600">
                                <i class="bi bi-plus-circle me-2"></i>Create Test
                            </button>
                            <a href="{{ route('lab.tests.index') }}"
                                class="btn btn-outline-secondary w-100 mt-2"
                                style="height:38px;font-size:13px">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection