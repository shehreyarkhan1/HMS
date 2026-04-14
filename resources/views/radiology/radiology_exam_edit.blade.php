@extends('layouts.master')

@section('title', 'Edit Exam — ' . $radiologyExam->name)
@section('page-title', 'Radiology — Update Exam')
@section('breadcrumb', 'Home / Radiology / Exams / Edit')

@push('styles')
    {{-- Re-using Create Styles for Consistency --}}
    <style>
        .form-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; }
        .form-section-title { font-size: 14px; font-weight: 700; color: #1e293b; text-transform: uppercase; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f1f5f9; display: flex; align-items: center; }
        .form-label { font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px; }
        .form-control, .form-select { border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; font-size: 14px; background-color: #f8fafc; }
        .form-control:focus { background-color: #fff; border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); outline: none; }
        .required-dot::after { content: " *"; color: #ef4444; }
        .helper-text { font-size: 11px; color: #94a3b8; margin-top: 4px; }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <form action="{{ route('radiology.exams.update', $radiologyExam->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-8">
                    <div class="form-card p-4 mb-4">
                        <div class="form-section-title text-primary">
                            <i class="bi bi-pencil-square me-2"></i> Basic Information
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label required-dot">Exam Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $radiologyExam->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Exam Code</label>
                                <input type="text" name="exam_code" class="form-control @error('exam_code') is-invalid @enderror" 
                                    value="{{ old('exam_code', $radiologyExam->exam_code) }}">
                                @error('exam_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required-dot">Modality</label>
                                <select name="modality_id" class="form-select @error('modality_id') is-invalid @enderror" required>
                                    @foreach($modalities as $modality)
                                        <option value="{{ $modality->id }}" {{ old('modality_id', $radiologyExam->modality_id) == $modality->id ? 'selected' : '' }}>
                                            {{ $modality->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Body Part</label>
                                <select name="body_part_id" class="form-select">
                                    <option value="">Select Body Part</option>
                                    @foreach($bodyParts as $part)
                                        <option value="{{ $part->id }}" {{ old('body_part_id', $radiologyExam->body_part_id) == $part->id ? 'selected' : '' }}>
                                            {{ $part->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-section-title mt-5 text-success">
                            <i class="bi bi-cash-coin me-2"></i> Pricing & Logistics
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label required-dot">Price (Rs)</label>
                                <input type="number" name="price" step="0.01" class="form-control" 
                                    value="{{ old('price', $radiologyExam->price) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required-dot">Duration (Min)</label>
                                <input type="number" name="duration_minutes" class="form-control" 
                                    value="{{ old('duration_minutes', $radiologyExam->duration_minutes) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required-dot">TAT (Hrs)</label>
                                <input type="number" name="turnaround_hours" class="form-control" 
                                    value="{{ old('turnaround_hours', $radiologyExam->turnaround_hours) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-card p-4 mb-4">
                        <div class="form-section-title text-danger">
                            <i class="bi bi-clipboard2-pulse me-2"></i> Clinical & Contraindications
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Clinical Indications</label>
                                <textarea name="clinical_indications" class="form-control" rows="3">{{ old('clinical_indications', $radiologyExam->clinical_indications) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contraindications</label>
                                <textarea name="contraindications" class="form-control" rows="3">{{ old('contraindications', $radiologyExam->contraindications) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-card p-4 mb-4">
                        <div class="form-section-title text-secondary">
                            <i class="bi bi-sliders me-2"></i> Status & Rules
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', $radiologyExam->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="isActive">Active Status</label>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="requires_consent" id="reqConsent" value="1" {{ old('requires_consent', $radiologyExam->requires_consent) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="reqConsent">Requires Consent</label>
                        </div>

                        <hr>

                        <div class="mb-4">
                            <label class="form-label d-block mb-2">Contrast Agent</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="requires_contrast" id="reqContrast" value="1" {{ old('requires_contrast', $radiologyExam->requires_contrast) ? 'checked' : '' }}>
                                <label class="form-check-label" for="reqContrast">Requires Contrast</label>
                            </div>
                            <div id="contrastTypeWrapper" class="mt-2" style="{{ $radiologyExam->requires_contrast ? '' : 'display:none' }}">
                                <input type="text" name="contrast_type" class="form-control" placeholder="Agent type" value="{{ old('contrast_type', $radiologyExam->contrast_type) }}">
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label d-block mb-2">Preparation</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="requires_preparation" id="reqPrep" value="1" {{ old('requires_preparation', $radiologyExam->requires_preparation) ? 'checked' : '' }}>
                                <label class="form-check-label" for="reqPrep">Requires Preparation</label>
                            </div>
                            <div id="prepInstructionsWrapper" class="mt-2" style="{{ $radiologyExam->requires_preparation ? '' : 'display:none' }}">
                                <textarea name="preparation_instructions" class="form-control" rows="3">{{ old('preparation_instructions', $radiologyExam->preparation_instructions) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-card p-4">
                        <button type="submit" class="btn btn-primary w-100 mb-2 py-2 fw-bold">Update Exam</button>
                        <a href="{{ route('radiology.exams.index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reqContrast = document.getElementById('reqContrast');
            const contrastWrapper = document.getElementById('contrastTypeWrapper');
            reqContrast.addEventListener('change', function() {
                contrastWrapper.style.display = this.checked ? 'block' : 'none';
            });

            const reqPrep = document.getElementById('reqPrep');
            const prepWrapper = document.getElementById('prepInstructionsWrapper');
            reqPrep.addEventListener('change', function() {
                prepWrapper.style.display = this.checked ? 'block' : 'none';
            });
        });
    </script>
@endpush