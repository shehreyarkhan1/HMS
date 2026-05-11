{{-- ============================================================
     resources/views/billing/service-charges/index.blade.php
     ============================================================ --}}
@extends('layouts.master')
@section('title', 'Service Charges')
@section('page-title', 'Billing — Service Charges')
@section('breadcrumb', 'Home / Billing / Service Charges')

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
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px
        }

        .table-clean th {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 10px 16px !important;
            background: #f8fafc
        }

        .table-clean td {
            font-size: 13px;
            color: #374151;
            padding: 12px 16px !important;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9 !important
        }

        .table-clean tr:last-child td {
            border-bottom: none !important
        }

        .table-clean tr:hover td {
            background: #fafafa
        }

        .cat-Consultation {
            background: #ede9fe;
            color: #6d28d9
        }

        .cat-Procedure {
            background: #e0f2fe;
            color: #0369a1
        }

        .cat-Bed-Charges {
            background: #f1f5f9;
            color: #475569
        }

        .cat-OT-Charges {
            background: #fee2e2;
            color: #991b1b
        }

        .cat-Blood-Bank {
            background: #ffe4e6;
            color: #be123c
        }

        .cat-Service {
            background: #dcfce7;
            color: #166534
        }

        .cat-Other {
            background: #f8fafc;
            color: #64748b
        }

        .cat-badge {
            font-size: 10px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500
        }
    </style>
@endpush

@section('content')

    @if (session('success'))
        <div class="alert d-flex align-items-center gap-2 mb-4"
            style="background:#dcfce7;border:1px solid #86efac;border-radius:10px;color:#166534;font-size:13px">
            <i class="bi bi-check-circle-fill fs-5"></i>{{ session('success') }}
        </div>
    @endif

    <div class="card-section">
        <div class="card-section-header">
            <span style="font-size:14px;font-weight:600;color:#1e293b">
                <i class="bi bi-gear me-2 text-primary"></i>Service Charge Rates
            </span>
            <div class="d-flex gap-2">
                <a href="{{ route('billing.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size:13px">
                    <i class="bi bi-arrow-left me-1"></i>Back to Billing
                </a>
                <a href="{{ route('billing.service-charges.create') }}" class="btn btn-sm btn-success"
                    style="font-size:13px">
                    <i class="bi bi-plus-lg me-1"></i>Add Charge
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-clean mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th style="text-align:right">Default Price</th>
                        <th>Status</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($charges as $charge)
                        <tr>
                            <td style="font-family:monospace;font-size:12px;color:#6366f1;font-weight:600">
                                {{ $charge->code }}</td>
                            <td style="font-weight:500;color:#1e293b">{{ $charge->name }}
                                @if ($charge->description)
                                    <div style="font-size:11px;color:#94a3b8">{{ $charge->description }}</div>
                                @endif
                            </td>
                            <td><span
                                    class="cat-badge cat-{{ str_replace(' ', '-', $charge->category) }}">{{ $charge->category }}</span>
                            </td>
                            <td style="text-align:right;font-weight:600;color:#1e293b">Rs.
                                {{ number_format($charge->default_price, 2) }}</td>
                            <td>
                                @if ($charge->is_active)
                                    <span
                                        style="background:#dcfce7;color:#166534;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500">Active</span>
                                @else
                                    <span
                                        style="background:#f1f5f9;color:#64748b;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('billing.service-charges.edit', $charge) }}"
                                        class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:12px"
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('billing.service-charges.toggle', $charge) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('PATCH')
                                        <button
                                            class="btn btn-sm btn-outline-{{ $charge->is_active ? 'warning' : 'success' }} py-0 px-2"
                                            style="font-size:12px"
                                            title="{{ $charge->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi bi-{{ $charge->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('billing.service-charges.destroy', $charge) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:12px"
                                            onclick="return confirm('Delete this service charge?')" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5" style="color:#94a3b8">
                                <i class="bi bi-gear" style="font-size:40px;display:block;margin-bottom:8px"></i>
                                No service charges yet.
                                <a href="{{ route('billing.service-charges.create') }}" class="text-primary ms-1">Add first
                                    charge</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
