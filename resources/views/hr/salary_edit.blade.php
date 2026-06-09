@extends('layouts.master')
@section('title', 'Edit Salary Structure')
@section('page-title', 'Edit Salary Structure')
@section('breadcrumb', 'HR / Salary / Edit')

@push('styles')
<style>
    .form-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden}
    .form-card-header{padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px}
    .form-card-body{padding:1.5rem}
    .form-label{font-size:12px;font-weight:600;color:#64748b;margin-bottom:.4rem}
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-semibold" style="color:#1e293b">Edit Salary Structure</h5>
        <p class="mb-0" style="font-size:13px;color:#94a3b8">
            {{ $employee->first_name }} {{ $employee->last_name }} &middot;
            Effective: {{ \Carbon\Carbon::parse($structure->effective_from)->format('d M Y') }}
        </p>
    </div>
    <a href="{{ route('hr.salary.show', $employee) }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<div style="background:#fef3c7;border-radius:10px;border:1px solid #fde68a;padding:.85rem 1.1rem;margin-bottom:1rem;font-size:13px;color:#b45309">
    <i class="bi bi-exclamation-triangle me-2"></i>
    Editing current structure. Changes will affect next payroll run.
</div>

<form action="{{ route('hr.salary.update', [$employee, $structure]) }}" method="POST">
    @csrf @method('PUT')
    @include('hr.salary_form', ['employee' => $employee, 'structure' => $structure])
</form>
@endsection
