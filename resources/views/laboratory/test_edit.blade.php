@extends('layouts.master')

@section('title', 'Edit Test — ' . $labTest->name)
@section('page-title', 'Laboratory — Edit Test')
@section('breadcrumb', 'Home / Laboratory / Tests / Edit')

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
    </style>
@endpush

@section('content')

    {{-- Edit identifier strip --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('lab.tests.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <span style="font-size:15px;font-weight:700;color:#1e293b">{{ $labTest->name }}</span>
            <code style="font-size:12px;color:#6366f1;margin-left:8px">{{ $labTest->test_code }}</code>
        </div>
        @if($labTest->is_active)
            <span style="font-size:11px;background:#dcfce7;color:#166534;padding:3px 10px;border-radius:20px;font-weight:500">Active</span>
        @else
            <span style="font-size:11px;background:#f1f5f9;color:#64748b;padding:3px 10px;border-radius:20px;font-weight:500">Inactive</span>
        @endif
    </div>

    <form method="POST" action="{{ route('lab.tests.update', $labTest->id) }}">
        @csrf
        @method('PUT')

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
                                    value="{{ old('name', $labTest->name) }}">
                                @error('name')
                                    <div class="text-danger" style="font-size:11px;margin-top:4px">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label-sm">Test Code</label>
                                <input type="text" class="fc" value="{{ $labTest->test_code }}"
                                    disabled style="background:#f1f5f9;color:#94a3b8;cursor:not-allowed">
                                <div style="font-size:11px;color:#94a3b8;margin-top:3px">Auto-generated, not editable</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label-sm">Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="fc" required>
                                    <option value="">— Select category —</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id', $labTest->category_id) == $cat->id ? 'selected' : '' }}>
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
                                            {{ old('sample_type_id', $labTest->sample_type_id) == $st->id ? 'selected' : '' }}>
                                            {{ $st->name }}
                                            @if($st->container_type) ({{ $st->container_type }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label-sm">Price (Rs) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="fc" required min="0" step="0.01"
                                    value="{{ old('price', $labTest->price) }}">
                                @error('price')
                                    <div class="text-danger" style="font-size:11px;margin-top:4px">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label-sm">Unit <span style="color:#94a3b8;font-weight:400">(result unit)</span></label>
                                <input type="text" name="unit" class="fc"
                                    value="{{ old('unit', $labTest->unit) }}"
                                    placeholder="mg/dL, g/dL, %">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label-sm">Turnaround (hours) <span class="text-danger">*</span></label>
                                <input type="number" name="turnaround_hours" class="fc" required min="0"
                                    value="{{ old('turnaround_hours', $labTest->turnaround_hours) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label-sm">Method</label>
                                <input type="text" name="method" class="fc"
                                    value="{{ old('method', $labTest->method) }}"
                                    placeholder="ELISA, PCR, Microscopy, etc.">
                            </div>

                            <div class="col-md-4 d-flex align-items-end pb-1 gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="requires_fasting"
                                        value="1" id="requires_fasting"
                                        {{ old('requires_fasting', $labTest->requires_fasting) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="requires_fasting" style="font-size:13px;color:#374151">
                                        Fasting
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                        value="1" id="is_active"
                                        {{ old('is_active', $labTest->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active" style="font-size:13px;color:#374151">
                                        Active
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label-sm">Description</label>
                                <textarea name="description" class="fc"
                                    placeholder="Optional...">{{ old('description', $labTest->description) }}</textarea>
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
                                    value="{{ old('normal_range', $labTest->normal_range) }}"
                                    placeholder="e.g. 4.5 - 11.0">
                                <div style="font-size:11px;color:#94a3b8;margin-top:3px">
                                    Used when no gender/age specific range is set
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-sm">Male Specific</label>
                                <input type="text" name="normal_range_male" class="fc"
                                    value="{{ old('normal_range_male', $labTest->normal_range_male) }}"
                                    placeholder="e.g. 13.5 - 17.5">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-sm">Female Specific</label>
                                <input type="text" name="normal_range_female" class="fc"
                                    value="{{ old('normal_range_female', $labTest->normal_range_female) }}"
                                    placeholder="e.g. 12.0 - 15.5">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label-sm">Child (&lt;12 yrs)</label>
                                <input type="text" name="normal_range_child" class="fc"
                                    value="{{ old('normal_range_child', $labTest->normal_range_child) }}"
                                    placeholder="e.g. 11.0 - 16.0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label-sm">Elderly (&gt;60 yrs)</label>
                                <input type="text" name="normal_range_elderly" class="fc"
                                    value="{{ old('normal_range_elderly', $labTest->normal_range_elderly) }}"
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
                            <i class="bi bi-save text-primary"></i>Update Test
                        </div>
                        <div class="form-section-body">

                            {{-- Meta info --}}
                            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px;margin-bottom:16px;font-size:12px">
                                <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                                    <span style="color:#94a3b8">Code</span>
                                    <code style="color:#6366f1">{{ $labTest->test_code }}</code>
                                </div>
                                <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                                    <span style="color:#94a3b8">Category</span>
                                    <span style="color:#374151;font-weight:500">{{ $labTest->category->name }}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                                    <span style="color:#94a3b8">Created</span>
                                    <span style="color:#374151">{{ $labTest->created_at->format('d M Y') }}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between">
                                    <span style="color:#94a3b8">Orders (total)</span>
                                    <span style="color:#374151;font-weight:600">{{ $labTest->orderItems()->count() }}</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100"
                                style="height:40px;font-size:14px;font-weight:600">
                                <i class="bi bi-check-lg me-2"></i>Update Test
                            </button>
                            <a href="{{ route('lab.tests.index') }}"
                                class="btn btn-outline-secondary w-100 mt-2"
                                style="height:38px;font-size:13px">
                                Cancel
                            </a>

                            {{-- Quick toggle active --}}
                            <div style="margin-top:12px;padding-top:12px;border-top:1px solid #e2e8f0">
                                <form method="POST" action="{{ route('lab.tests.toggleActive', $labTest->id) }}">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-sm w-100 {{ $labTest->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                        style="font-size:12px;height:34px">
                                        <i class="bi bi-{{ $labTest->is_active ? 'toggle-off' : 'toggle-on' }} me-1"></i>
                                        {{ $labTest->is_active ? 'Deactivate Test' : 'Activate Test' }}
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection