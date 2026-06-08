@extends('layouts.master')

@section('title', 'Dispensing Detail — ' . $dispensing->dispense_number)
@section('page-title', 'Dispensing Detail')
@section('breadcrumb', 'Home / Pharmacy / Dispensing / ' . $dispensing->dispense_number)

@section('content')

<div class="row g-3">

    {{-- LEFT --}}
    <div class="col-12 col-lg-4">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:12px">
            <div style="padding:20px;border-bottom:1px solid #f1f5f9">
                <div style="font-size:20px;font-weight:700;color:#6366f1;letter-spacing:.05em">
                    {{ $dispensing->dispense_number }}
                </div>
                <div style="margin-top:8px">
                    @php
                        $colors = ['Paid' => '#dcfce7;color:#166534', 'Unpaid' => '#fee2e2;color:#991b1b', 'Partial' => '#fef9c3;color:#854d0e'];
                        $c = $colors[$dispensing->payment_status] ?? '#f1f5f9;color:#374151';
                    @endphp
                    <span style="font-size:12px;padding:4px 12px;border-radius:20px;font-weight:600;background:{{ $c }}">
                        {{ $dispensing->payment_status }}
                    </span>
                </div>
            </div>

            @php
                $rows = [
                    ['label' => 'Patient', 'value' => $dispensing->patient->name . ' (' . $dispensing->patient->mrn . ')'],
                    ['label' => 'Prescription', 'value' => $dispensing->prescription ? $dispensing->prescription->prescription_number : 'Walk-in (No Prescription)'],
                    ['label' => 'Date & Time', 'value' => $dispensing->dispensed_at->format('d M Y h:i A')],
                    ['label' => 'Total Amount', 'value' => 'Rs ' . number_format($dispensing->total_amount, 2)],
                ];
            @endphp
            @foreach($rows as $row)
            <div style="display:flex;padding:9px 16px;border-bottom:1px solid #f1f5f9;font-size:13px">
                <span style="min-width:105px;color:#94a3b8">{{ $row['label'] }}</span>
                <span style="color:#1e293b;font-weight:500;word-break:break-word">{{ $row['value'] }}</span>
            </div>
            @endforeach

            @if($dispensing->notes)
            <div style="padding:12px 16px;font-size:13px;color:#374151;background:#fafafa">
                <strong>Notes:</strong><br>
                {{ $dispensing->notes }}
            </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="d-grid gap-2">
            @if($dispensing->prescription)
                <a href="{{ route('pharmacy.prescriptions.show', $dispensing->prescription_id) }}"
                   class="btn btn-outline-primary" style="height:40px;font-size:13px">
                    <i class="bi bi-file-medical me-1"></i>View Prescription
                </a>
            @endif
            <a href="{{ route('pharmacy.dispensings.index') }}"
               class="btn btn-outline-secondary" style="height:40px;font-size:13px">Back to List</a>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="col-12 col-lg-8">

        {{-- Dispensed Medicines --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden">
            <div style="padding:14px 20px;border-bottom:1px solid #e2e8f0;font-size:13px;font-weight:600;color:#1e293b">
                <i class="bi bi-capsule me-2 text-primary"></i>Dispensed medicines
            </div>
            <div class="table-responsive">
                <table class="table mb-0" style="font-size:13px">
                    <thead style="background:#f8fafc">
                        <tr>
                            <th style="padding:9px 16px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0">Medicine</th>
                            <th style="padding:9px 16px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0">Batch</th>
                            <th style="padding:9px 16px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;text-align:right">Qty</th>
                            <th style="padding:9px 16px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;text-align:right">Unit Price</th>
                            <th style="padding:9px 16px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;text-align:right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dispensing->items as $item)
                        <tr>
                            <td style="padding:11px 16px;border-bottom:1px solid #f1f5f9">
                                <div style="font-weight:500">{{ $item->medicine->name }}</div>
                                <div style="font-size:11px;color:#94a3b8">{{ $item->medicine->generic_name }}</div>
                            </td>
                            <td style="padding:11px 16px;border-bottom:1px solid #f1f5f9">
                                <code style="font-size:12px;color:#475569">{{ $item->batch->batch_number ?? '—' }}</code>
                            </td>
                            <td style="padding:11px 16px;border-bottom:1px solid #f1f5f9;text-align:right;font-weight:600">
                                {{ $item->quantity }}
                            </td>
                            <td style="padding:11px 16px;border-bottom:1px solid #f1f5f9;text-align:right">
                                Rs {{ number_format($item->unit_price, 2) }}
                            </td>
                            <td style="padding:11px 16px;border-bottom:1px solid #f1f5f9;text-align:right;font-weight:600;color:#1e293b">
                                Rs {{ number_format($item->total_price, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="background:#f8fafc;padding:16px 20px;display:flex;justify-content:space-between;align-items:center;border-top:1px solid #e2e8f0">
                <span style="font-size:13px;font-weight:600;color:#475569">Total Dispensed Amount</span>
                <span style="font-size:16px;font-weight:700;color:#16a34a">Rs {{ number_format($dispensing->total_amount, 2) }}</span>
            </div>
        </div>

    </div>
</div>

@endsection
