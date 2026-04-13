@extends('layouts.master')

@section('title', $prescription->prescription_number)
@section('page-title', 'Prescription Detail')
@section('breadcrumb', 'Home / Pharmacy / Prescriptions / ' . $prescription->prescription_number)

@section('content')

<div class="row g-3">

    {{-- LEFT --}}
    <div class="col-12 col-lg-4">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:12px">
            <div style="padding:20px;border-bottom:1px solid #f1f5f9">
                <div style="font-size:20px;font-weight:700;color:#6366f1;letter-spacing:.05em">
                    {{ $prescription->prescription_number }}
                </div>
                <div style="margin-top:8px">
                    @php
                        $colors = ['Pending'=>'#fef9c3;color:#854d0e','Partial'=>'#dbeafe;color:#1e40af','Dispensed'=>'#dcfce7;color:#166534','Cancelled'=>'#fee2e2;color:#991b1b'];
                        $c = $colors[$prescription->status] ?? '#f1f5f9;color:#374151';
                    @endphp
                    <span style="font-size:12px;padding:4px 12px;border-radius:20px;font-weight:600;background:{{ $c }}">
                        {{ $prescription->status }}
                    </span>
                    @if($prescription->is_expired)
                        <span style="font-size:12px;padding:4px 12px;border-radius:20px;font-weight:600;background:#fee2e2;color:#991b1b;margin-left:4px">
                            Expired
                        </span>
                    @endif
                </div>
            </div>

            @php
                $rows = [
                    ['label'=>'Patient',    'value'=> $prescription->patient->name . ' (' . $prescription->patient->mrn . ')'],
                    ['label'=>'Doctor',     'value'=> $prescription->doctor ? 'Dr. ' . $prescription->doctor->name : '—'],
                    ['label'=>'Date',       'value'=> $prescription->prescribed_date->format('d M Y')],
                    ['label'=>'Valid until','value'=> $prescription->valid_until ? $prescription->valid_until->format('d M Y') : '—'],
                    ['label'=>'Diagnosis',  'value'=> $prescription->diagnosis ?? '—'],
                ];
            @endphp
            @foreach($rows as $row)
            <div style="display:flex;padding:9px 16px;border-bottom:1px solid #f1f5f9;font-size:13px">
                <span style="min-width:90px;color:#94a3b8">{{ $row['label'] }}</span>
                <span style="color:#1e293b;font-weight:500">{{ $row['value'] }}</span>
            </div>
            @endforeach

            @if($prescription->notes)
            <div style="padding:12px 16px;font-size:13px;color:#374151;background:#fafafa">
                {{ $prescription->notes }}
            </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="d-grid gap-2">
            @if(in_array($prescription->status, ['Pending','Partial']))
                <a href="{{ route('pharmacy.dispensings.create', ['prescription_id' => $prescription->id]) }}"
                   class="btn btn-success" style="height:42px;font-size:14px;font-weight:500">
                    <i class="bi bi-capsule me-2"></i>Dispense medicines
                </a>
                <form method="POST" action="{{ route('pharmacy.prescriptions.cancel', $prescription->id) }}"
                      onsubmit="return confirm('Cancel this prescription?')">
                    @csrf
                    <button class="btn btn-outline-danger w-100" style="height:40px;font-size:13px">
                        <i class="bi bi-x-circle me-1"></i>Cancel prescription
                    </button>
                </form>
            @endif
            <a href="{{ route('pharmacy.prescriptions.index') }}"
               class="btn btn-outline-secondary" style="height:40px;font-size:13px">Back</a>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="col-12 col-lg-8">

        {{-- Medicine Items --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:12px">
            <div style="padding:14px 20px;border-bottom:1px solid #e2e8f0;font-size:13px;font-weight:600;color:#1e293b">
                <i class="bi bi-capsule me-2 text-primary"></i>Prescribed medicines
            </div>
            <div class="table-responsive">
                <table class="table mb-0" style="font-size:13px">
                    <thead style="background:#f8fafc">
                        <tr>
                            <th style="padding:9px 16px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0">Medicine</th>
                            <th style="padding:9px 16px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0">Dosage</th>
                            <th style="padding:9px 16px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0">Frequency</th>
                            <th style="padding:9px 16px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0">Days</th>
                            <th style="padding:9px 16px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0">Qty</th>
                            <th style="padding:9px 16px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0">Dispensed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prescription->items as $item)
                        <tr>
                            <td style="padding:11px 16px;border-bottom:1px solid #f1f5f9">
                                <div style="font-weight:500">{{ $item->medicine->name }}</div>
                                <div style="font-size:11px;color:#94a3b8">{{ $item->medicine->generic_name }}</div>
                                @if($item->instructions)
                                    <div style="font-size:11px;color:#6366f1">{{ $item->instructions }}</div>
                                @endif
                            </td>
                            <td style="padding:11px 16px;border-bottom:1px solid #f1f5f9">{{ $item->dosage }}</td>
                            <td style="padding:11px 16px;border-bottom:1px solid #f1f5f9">
                                <span style="background:#f1f5f9;padding:2px 8px;border-radius:6px;font-size:12px;font-weight:500">
                                    {{ $item->frequency }}
                                </span>
                            </td>
                            <td style="padding:11px 16px;border-bottom:1px solid #f1f5f9">{{ $item->duration_days }}d</td>
                            <td style="padding:11px 16px;border-bottom:1px solid #f1f5f9;font-weight:600">{{ $item->quantity }}</td>
                            <td style="padding:11px 16px;border-bottom:1px solid #f1f5f9">
                                @if($item->is_fully_dispensed)
                                    <span style="font-size:11px;padding:2px 8px;border-radius:20px;background:#dcfce7;color:#166534;font-weight:500">
                                        Done ({{ $item->dispensed_qty }})
                                    </span>
                                @elseif($item->dispensed_qty > 0)
                                    <span style="font-size:11px;padding:2px 8px;border-radius:20px;background:#dbeafe;color:#1e40af;font-weight:500">
                                        {{ $item->dispensed_qty }}/{{ $item->quantity }}
                                    </span>
                                @else
                                    <span style="font-size:11px;color:#94a3b8">Not dispensed</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Dispensing History --}}
        @if($prescription->dispensings->count())
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden">
            <div style="padding:14px 20px;border-bottom:1px solid #e2e8f0;font-size:13px;font-weight:600;color:#1e293b">
                <i class="bi bi-clock-history me-2 text-success"></i>Dispensing history
            </div>
            @foreach($prescription->dispensings as $dsp)
            <div style="padding:14px 20px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;font-size:13px">
                <div>
                    <div style="font-weight:500;color:#1e293b">{{ $dsp->dispense_number }}</div>
                    <div style="font-size:12px;color:#94a3b8">{{ $dsp->dispensed_at->format('d M Y h:i A') }}</div>
                </div>
                <div style="text-align:right">
                    <div style="font-weight:600;color:#1e293b">Rs {{ number_format($dsp->total_amount, 2) }}</div>
                    <span style="font-size:11px;padding:2px 8px;border-radius:20px;font-weight:500;
                        {{ $dsp->payment_status == 'Paid' ? 'background:#dcfce7;color:#166534' : 'background:#fee2e2;color:#991b1b' }}">
                        {{ $dsp->payment_status }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>

@endsection