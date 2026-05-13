@extends('layouts.master')
@section('title', isset($charge) ? 'Edit Service Charge' : 'New Service Charge')
@section('page-title', 'Billing — ' . (isset($charge) ? 'Edit Charge' : 'New Charge'))
@section('breadcrumb', 'Home / Billing / Service Charges / ' . (isset($charge) ? 'Edit' : 'New'))

@push('styles')
    <style>
        .card-section {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden
        }

        .card-section-header {
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0
        }

        .form-label-sm {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 5px;
            display: block;
        }

        .form-control-clean,
        .form-select-clean {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            color: #374151;
            background: #f8fafc;
            padding: 8px 12px;
            width: 100%;
            outline: none;
            transition: border-color .2s, background .2s;
        }

        .form-control-clean:focus,
        .form-select-clean:focus {
            border-color: #93c5fd;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(147, 197, 253, 0.2);
        }

        /* Blood component field — subtle highlight so user knows it's special */
        .blood-component-wrap {
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 12px 14px;
        }

        .blood-component-wrap .form-label-sm {
            color: #dc2626;
        }

        .blood-component-wrap .form-select-clean {
            background: #fff;
            border-color: #fca5a5;
        }

        .blood-component-wrap .form-select-clean:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .blood-hint {
            font-size: 11px;
            color: #ef4444;
            margin-top: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">

            @if ($errors->any())
                <div class="alert d-flex align-items-start gap-2 mb-4"
                    style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;color:#991b1b;font-size:13px">
                    <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                    <ul class="mb-0 ps-2">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-section">
                <div class="card-section-header">
                    <span style="font-size:14px;font-weight:600;color:#1e293b">
                        <i class="bi bi-gear me-2 text-primary"></i>{{ isset($charge) ? 'Edit' : 'New' }} Service Charge
                    </span>
                </div>
                <div class="p-4">
                    <form
                        action="{{ isset($charge) ? route('billing.service-charges.update', $charge) : route('billing.service-charges.store') }}"
                        method="POST">
                        @csrf
                        @if (isset($charge))
                            @method('PUT')
                        @endif

                        <div class="row g-3">

                            {{-- Name --}}
                            <div class="col-md-6">
                                <label class="form-label-sm">Name <span style="color:#dc2626">*</span></label>
                                <input type="text" name="name" class="form-control-clean"
                                    value="{{ old('name', $charge->name ?? '') }}" placeholder="e.g. General Consultation"
                                    required>
                            </div>

                            {{-- Code --}}
                            <div class="col-md-3">
                                <label class="form-label-sm">Code <span style="color:#dc2626">*</span></label>
                                <input type="text" name="code" class="form-control-clean"
                                    value="{{ old('code', $charge->code ?? '') }}" placeholder="e.g. BLD-003" required
                                    style="text-transform:uppercase" {{ isset($charge) ? 'readonly' : '' }}>
                            </div>

                            {{-- Category --}}
                            <div class="col-md-3">
                                <label class="form-label-sm">Category <span style="color:#dc2626">*</span></label>
                                <select name="category" class="form-select-clean" required id="categorySelect">
                                    @foreach (\App\Models\BillServiceCharge::categories() as $cat)
                                        <option value="{{ $cat }}"
                                            {{ old('category', $charge->category ?? '') === $cat ? 'selected' : '' }}>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Default Price --}}
                            <div class="col-md-4">
                                <label class="form-label-sm">Default Price (Rs.) <span
                                        style="color:#dc2626">*</span></label>
                                <input type="number" name="default_price" class="form-control-clean"
                                    value="{{ old('default_price', $charge->default_price ?? 0) }}" min="0"
                                    step="0.01" required>
                            </div>

                            {{-- Description --}}
                            <div class="col-md-8">
                                <label class="form-label-sm">Description</label>
                                <input type="text" name="description" class="form-control-clean"
                                    value="{{ old('description', $charge->description ?? '') }}"
                                    placeholder="Optional short description...">
                            </div>

                            {{-- Blood Component — only shown when category = Blood Bank --}}
                            <div class="col-12" id="bloodComponentWrapper"
                                style="{{ old('category', $charge->category ?? '') === 'Blood Bank' ? '' : 'display:none' }}">
                                <div class="blood-component-wrap">
                                    <label class="form-label-sm">
                                        <i class="bi bi-droplet-fill me-1"></i>Blood Component
                                    </label>
                                    <select name="blood_component" class="form-select-clean">
                                        <option value="">-- Not linked to a component --</option>
                                        @foreach (['Whole Blood', 'Packed RBC', 'Platelets', 'Fresh Frozen Plasma', 'Cryoprecipitate'] as $comp)
                                            <option value="{{ $comp }}"
                                                {{ old('blood_component', $charge->blood_component ?? '') === $comp ? 'selected' : '' }}>
                                                {{ $comp }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="blood-hint">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Select only if this charge maps to a specific blood component for auto-billing.
                                    </div>
                                </div>
                            </div>

                            {{-- Active toggle (edit only) --}}
                            @if (isset($charge))
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                                            value="1"
                                            {{ old('is_active', $charge->is_active ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isActive" style="font-size:13px;color:#374151">
                                            Active (visible in bill creation)
                                        </label>
                                    </div>
                                </div>
                            @endif

                        </div>{{-- end row --}}

                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3"
                            style="border-top:1px solid #e2e8f0">
                            <a href="{{ route('billing.service-charges.index') }}"
                                style="font-size:13px;color:#64748b;text-decoration:none">
                                <i class="bi bi-arrow-left me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4" style="font-size:13px">
                                <i class="bi bi-save me-1"></i>{{ isset($charge) ? 'Update' : 'Create' }} Charge
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Blood Component field sirf tab dikhao jab Category = Blood Bank ho
        const categorySelect = document.getElementById('categorySelect');
        const bloodWrapper = document.getElementById('bloodComponentWrapper');

        function toggleBloodField() {
            if (categorySelect.value === 'Blood Bank') {
                bloodWrapper.style.display = 'block';
            } else {
                bloodWrapper.style.display = 'none';
                // Reset value agar category change ho
                bloodWrapper.querySelector('select').value = '';
            }
        }

        categorySelect.addEventListener('change', toggleBloodField);
    </script>
@endpush
