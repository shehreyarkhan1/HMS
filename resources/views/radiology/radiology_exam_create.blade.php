@extends('layouts.master')

@section('title', 'Create Radiology Exam')
@section('page-title', 'Radiology — Add New Exam')
@section('breadcrumb', 'Home / Radiology / Exams / Create')

@push('styles')
    <style>
        .form-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .form-section-title {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: .025em;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f5f9;
            display: flex;
            align-items: center;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .form-control, .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            color: #1e293b;
            background-color: #f8fafc;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            background-color: #fff;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .input-group-text {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #64748b;
            font-weight: 600;
        }

        .helper-text {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 4px;
        }

        .btn-save {
            background: #6366f1;
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-save:hover {
            background: #4f46e5;
            transform: translateY(-1px);
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 22px;
        }

        /* Custom Checkbox Styling */
        .form-check-input:checked {
            background-color: #6366f1;
            border-color: #6366f1;
        }

        .required-dot::after {
            content: " *";
            color: #ef4444;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <form action="{{ route('radiology.exams.store') }}" method="POST">
            @csrf

            <div class="row">
                {{-- Left Column: Basic & Pricing --}}
                <div class="col-lg-8">
                    <div class="form-card p-4 mb-4">
                        <div class="form-section-title">
                            <i class="bi bi-info-circle me-2 text-primary"></i> General Information
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label required-dot">Exam Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                    placeholder="e.g. MRI Brain with Contrast" value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Exam Code</label>
                                <input type="text" name="exam_code" class="form-control @error('exam_code') is-invalid @enderror" 
                                    placeholder="RAD-001" value="{{ old('exam_code') }}">
                                @error('exam_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required-dot">Modality</label>
                                <select name="modality_id" class="form-select @error('modality_id') is-invalid @enderror" required>
                                    <option value="">Select Modality</option>
                                    @foreach($modalities as $modality)
                                        <option value="{{ $modality->id }}" {{ old('modality_id') == $modality->id ? 'selected' : '' }}>
                                            {{ $modality->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('modality_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Body Part</label>
                                <select name="body_part_id" class="form-select @error('body_part_id') is-invalid @enderror">
                                    <option value="">Select Body Part</option>
                                    @foreach($bodyParts as $part)
                                        <option value="{{ $part->id }}" {{ old('body_part_id') == $part->id ? 'selected' : '' }}>
                                            {{ $part->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Brief Description</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Short summary of the exam...">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="form-section-title mt-5">
                            <i class="bi bi-currency-dollar me-2 text-success"></i> Pricing & Duration
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label required-dot">Standard Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs</span>
                                    <input type="number" name="price" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                        placeholder="0.00" value="{{ old('price') }}" required>
                                </div>
                                @error('price') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label required-dot">Scan Duration</label>
                                <div class="input-group">
                                    <input type="number" name="duration_minutes" class="form-control @error('duration_minutes') is-invalid @enderror" 
                                        placeholder="30" value="{{ old('duration_minutes', 30) }}" required>
                                    <span class="input-group-text">Min</span>
                                </div>
                                @error('duration_minutes') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label required-dot">Turnaround Time</label>
                                <div class="input-group">
                                    <input type="number" name="turnaround_hours" class="form-control @error('turnaround_hours') is-invalid @enderror" 
                                        placeholder="24" value="{{ old('turnaround_hours', 24) }}" required>
                                    <span class="input-group-text">Hrs</span>
                                </div>
                                @error('turnaround_hours') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-card p-4 mb-4">
                        <div class="form-section-title">
                            <i class="bi bi-clipboard-pulse me-2 text-danger"></i> Clinical Details
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Clinical Indications</label>
                                <textarea name="clinical_indications" class="form-control" rows="3" 
                                    placeholder="When should this exam be ordered?">{{ old('clinical_indications') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contraindications</label>
                                <textarea name="contraindications" class="form-control" rows="3" 
                                    placeholder="e.g. Pregnancy, Pacemaker, etc.">{{ old('contraindications') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Settings & Preparation --}}
                <div class="col-lg-4">
                    <div class="form-card p-4 mb-4">
                        <div class="form-section-title">
                            <i class="bi bi-gear me-2 text-secondary"></i> Exam Settings
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="isActive">Active Status</label>
                                <div class="helper-text">Inactive exams won't appear in order forms.</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="requires_consent" id="reqConsent" value="1" {{ old('requires_consent') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="reqConsent">Requires Consent</label>
                                <div class="helper-text">Patient signature required before scan.</div>
                            </div>
                        </div>

                        <hr class="my-4 text-faded">

                        <div class="mb-4">
                            <label class="form-label d-block mb-2">Contrast Requirements</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="requires_contrast" id="reqContrast" value="1" {{ old('requires_contrast') ? 'checked' : '' }}>
                                <label class="form-check-label" for="reqContrast">Requires Contrast Agent</label>
                            </div>
                            <div id="contrastTypeWrapper" style="{{ old('requires_contrast') ? '' : 'display:none' }}">
                                <input type="text" name="contrast_type" class="form-control mt-2" placeholder="e.g. Gadolinium, Iodine" value="{{ old('contrast_type') }}">
                                <div class="helper-text">Specify agent type</div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label d-block mb-2">Preparation</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="requires_preparation" id="reqPrep" value="1" {{ old('requires_preparation') ? 'checked' : '' }}>
                                <label class="form-check-label" for="reqPrep">Requires Preparation</label>
                            </div>
                            <div id="prepInstructionsWrapper" style="{{ old('requires_preparation') ? '' : 'display:none' }}">
                                <textarea name="preparation_instructions" class="form-control mt-2" rows="3" placeholder="e.g. 6 hours fasting required...">{{ old('preparation_instructions') }}</textarea>
                                <div class="helper-text">Instructions for the patient</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-card p-4">
                        <button type="submit" class="btn btn-save w-100 mb-2">
                            <i class="bi bi-check-lg me-1"></i> Save Exam
                        </button>
                        <a href="{{ route('radiology.exams.index') }}" class="btn btn-outline-secondary w-100">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Contrast Type Input
            const reqContrast = document.getElementById('reqContrast');
            const contrastWrapper = document.getElementById('contrastTypeWrapper');
            
            reqContrast.addEventListener('change', function() {
                contrastWrapper.style.display = this.checked ? 'block' : 'none';
            });

            // Toggle Preparation Textarea
            const reqPrep = document.getElementById('reqPrep');
            const prepWrapper = document.getElementById('prepInstructionsWrapper');
            
            reqPrep.addEventListener('change', function() {
                prepWrapper.style.display = this.checked ? 'block' : 'none';
            });
        });
    </script>
@endpush