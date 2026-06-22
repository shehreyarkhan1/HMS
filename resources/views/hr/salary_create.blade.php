@extends('layouts.master')
@section('title', 'Create Salary Structure')
@section('page-title', 'Create Salary Structure')
@section('breadcrumb', 'HR / Salary / Create')

@push('styles')
    <style>
        .form-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden
        }

        .form-card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 10px
        }

        .form-card-body {
            padding: 1.5rem
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: .4rem
        }
    </style>
@endpush

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 class="mb-0 fw-semibold" style="color:#1e293b">Create Salary Structure</h5>
            <p class="mb-0" style="font-size:13px;color:#94a3b8">
                {{ $employee->first_name }} {{ $employee->last_name }} &middot; {{ $employee->employee_id }}
            </p>
        </div>
        <a href="{{ route('hr.salary.show', $employee) }}" class="btn btn-light btn-sm"><i
                class="bi bi-arrow-left me-1"></i>Back</a>
    </div>

    <form action="{{ route('hr.salary.store', $employee) }}" method="POST">
        @csrf
        @include('hr.salary_form', ['employee' => $employee, 'structure' => null])
    </form>
@endsection
