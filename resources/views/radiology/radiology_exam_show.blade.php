@extends('layouts.master')

@section('title', 'Exam Details — ' . $radiologyExam->name)
@section('page-title', 'Radiology — Exam Profile')
@section('breadcrumb', 'Home / Radiology / Exams / ' . $radiologyExam->exam_code)

@push('styles')
    <style>
        .profile-header {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .info-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: 600;
            letter-spacing: .05em;
            margin-bottom: 4px;
        }
        .info-value {
            font-size: 14px;
            color: #1e293b;
            font-weight: 500;
        }
        .detail-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            height: 100%;
        }
        .detail-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
        }
        .detail-card-body { padding: 20px; }
        
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        .prep-box {
            background: #f8fafc;
            border-left: 4px solid #6366f1;
            padding: 15px;
            border-radius: 0 8px 8px 0;
        }
        .price-tag {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
        }
    </style>
@endpush

@section('content')
    {{-- Header Section --}}
    <div class="profile-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex gap-3">
            <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary d-flex align-items-center justify-content-center" style="width:64px;height:64px">
                <i class="bi bi-activity fs-2"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h4 class="mb-0 fw-bold text-slate-800">{{ $radiologyExam->name }}</h4>
                    <span class="badge {{ $radiologyExam->is_active ? 'bg-success' : 'bg-danger' }} bg-opacity-10 {{ $radiologyExam->is_active ? 'text-success' : 'text-danger' }} px-3">
                        <span class="status-indicator {{ $radiologyExam->is_active ? 'bg-success' : 'bg-danger' }}"></span>
                        {{ $radiologyExam->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="text-muted mt-1">
                    <code class="text-primary fw-medium">{{ $radiologyExam->exam_code }}</code> 
                    <span class="mx-2 text-slate-300">|</span>
                    <i class="bi bi-tag-fill me-1"></i> {{ $radiologyExam->modality->name ?? 'N/A' }}
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('radiology.exams.edit', $radiologyExam->id) }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('radiology.exams.index') }}" class="btn btn-primary px-4">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Side: Core Details --}}
        <div class="col-lg-8">
            <div class="detail-card mb-4">
                <div class="detail-card-header">
                    <i class="bi bi-file-text me-2 text-primary"></i> Exam Overview
                </div>
                <div class="detail-card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-label">Clinical Indications</div>
                            <div class="info-value text-wrap">{{ $radiologyExam->clinical_indications ?: 'No specific indications recorded.' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Contraindications</div>
                            <div class="info-value text-danger">{{ $radiologyExam->contraindications ?: 'None reported.' }}</div>
                        </div>
                        <div class="col-12">
                            <div class="info-label">Description</div>
                            <div class="info-value">{{ $radiologyExam->description ?: 'No description provided.' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Orders Table --}}
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="bi bi-clock-history me-2 text-success"></i> Recent Orders (Last 10)
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size:13px">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Order #</th>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="pe-4 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $item)
                                <tr>
                                    <td class="ps-4 fw-bold text-primary">{{ $item->order->order_number }}</td>
                                    <td>{{ $item->order->patient->name ?? 'Unknown' }}</td>
                                    <td>{{ $item->order->order_date->format('d M, Y') }}</td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="#" class="btn btn-sm btn-light border">View Order</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted italic">No orders found for this exam yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right Side: Stats & Preparation --}}
        <div class="col-lg-4">
            <div class="detail-card mb-4">
                <div class="detail-card-header">
                    <i class="bi bi-cash-stack me-2 text-success"></i> Commercials
                </div>
                <div class="detail-card-body">
                    <div class="price-tag mb-1">Rs {{ number_format($radiologyExam->price, 0) }}</div>
                    <div class="text-muted small mb-4">Standard pricing per procedure</div>
                    
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="info-label">Duration</div>
                            <div class="info-value"><i class="bi bi-stopwatch me-1"></i> {{ $radiologyExam->duration_minutes }} Min</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">TAT</div>
                            <div class="info-value"><i class="bi bi-lightning-charge me-1"></i> {{ $radiologyExam->turnaround_hours }} Hrs</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="bi bi-shield-check me-2 text-warning"></i> Requirements
                </div>
                <div class="detail-card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Contrast Required</span>
                            <span class="fw-bold {{ $radiologyExam->requires_contrast ? 'text-warning' : '' }}">
                                {{ $radiologyExam->requires_contrast ? 'Yes (' . $radiologyExam->contrast_type . ')' : 'No' }}
                            </span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Consent Form</span>
                            <span class="fw-bold">{{ $radiologyExam->requires_consent ? 'Mandatory' : 'Not Required' }}</span>
                        </li>
                    </ul>

                    @if($radiologyExam->requires_preparation)
                        <div class="mt-4">
                            <div class="info-label mb-2">Preparation Instructions</div>
                            <div class="prep-box small">
                                {{ $radiologyExam->preparation_instructions }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection