
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $payslip->payslip_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; color: #1e293b; background: #fff; }
        .page { width: 100%; max-width: 750px; margin: 0 auto; padding: 20px; }

        .header { background: #1d4ed8; color: white; padding: 20px; border-radius: 10px 10px 0 0; }
        .company-name { font-size: 18px; font-weight: 800; }
        .period { font-size: 11px; opacity: .8; margin-top: 3px; }
        .emp-name { font-size: 16px; font-weight: 700; margin-top: 12px; }
        .emp-meta { font-size: 11px; opacity: .75; margin-top: 3px; }
        .divider { border-top: 1px solid rgba(255,255,255,.25); margin: 12px 0; }

        .body { border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 10px 10px; padding: 20px; }

        .section-title { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #94a3b8; border-bottom: 1px solid #f1f5f9; padding-bottom: 5px; margin-bottom: 8px; margin-top: 16px; }

        .att-grid { display: grid; grid-template-columns: repeat(8, 1fr); gap: 6px; }
        .att-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px; text-align: center; }
        .att-num { font-size: 16px; font-weight: 700; }
        .att-lbl { font-size: 9px; color: #94a3b8; margin-top: 1px; }

        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 8px; }

        .row { display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px solid #f8fafc; }
        .row:last-child { border-bottom: none; }
        .row .lbl { color: #64748b; }
        .row .val { font-weight: 600; }
        .total-row { display: flex; justify-content: space-between; padding: 6px 0; border-top: 2px solid #e2e8f0; margin-top: 4px; font-weight: 700; }

        .net-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 14px 16px; display: flex; justify-content: space-between; align-items: center; margin-top: 16px; }
        .net-label { font-size: 14px; font-weight: 700; color: #16a34a; }
        .net-val { font-size: 22px; font-weight: 800; color: #16a34a; }

        .footer { text-align: center; font-size: 10px; color: #94a3b8; margin-top: 16px; border-top: 1px solid #f1f5f9; padding-top: 10px; }

        .sig-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 24px; }
        .sig-box { border-top: 1px solid #e2e8f0; padding-top: 6px; text-align: center; font-size: 10px; color: #94a3b8; }

        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .page { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- Print button --}}
    <div class="no-print" style="text-align:right;margin-bottom:12px">
        <button onclick="window.print()" style="padding:6px 16px;background:#1d4ed8;color:#fff;border:none;border-radius:8px;font-size:12px;cursor:pointer">
            Print Payslip
        </button>
        <button onclick="window.close()" style="padding:6px 16px;background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0;border-radius:8px;font-size:12px;cursor:pointer;margin-left:6px">
            Close
        </button>
    </div>

    <div class="header">
        <div style="display:flex;justify-content:space-between;align-items:flex-start">
            <div>
                <div class="company-name">MediCore Hospital</div>
                <div class="period">Salary Slip &bull; {{ $payslip->payrollRun->month_name }}</div>
            </div>
            <div style="text-align:right;font-size:11px;opacity:.8">
                {{ $payslip->payslip_number }}<br>
                Generated: {{ now()->format('d M Y') }}
            </div>
        </div>
        <div class="divider"></div>
        <div style="display:flex;justify-content:space-between;align-items:flex-end">
            <div>
                <div class="emp-name">{{ $payslip->employee->first_name }} {{ $payslip->employee->last_name }}</div>
                <div class="emp-meta">
                    {{ $payslip->employee->employee_id }} &bull;
                    {{ $payslip->employee->designation }} &bull;
                    {{ $payslip->employee->department }}
                </div>
            </div>
            <div style="text-align:right;font-size:11px;opacity:.75">
                @if($payslip->bank_name) {{ $payslip->bank_name }}<br> @endif
                @if($payslip->bank_account_number) A/C: {{ $payslip->bank_account_number }} @endif
            </div>
        </div>
    </div>

    <div class="body">

        {{-- Attendance --}}
        <div class="section-title">Attendance Summary</div>
        <div class="att-grid">
            @foreach([
                [$payslip->total_working_days,'Work Days'],
                [$payslip->present_days,'Present'],
                [$payslip->absent_days,'Absent'],
                [$payslip->late_days,'Late'],
                [$payslip->half_days,'Half Day'],
                [$payslip->leave_days,'Leave'],
                [$payslip->holiday_days,'Holiday'],
                [number_format($payslip->overtime_hours,1),'OT Hrs'],
            ] as [$n,$l])
            <div class="att-box"><div class="att-num">{{ $n }}</div><div class="att-lbl">{{ $l }}</div></div>
            @endforeach
        </div>

        <div class="two-col">
            {{-- Earnings --}}
            <div>
                <div class="section-title">Earnings</div>
                @foreach([
                    ['Basic Salary',    $payslip->basic_salary],
                    ['House Rent',      $payslip->house_rent_allowance],
                    ['Medical',         $payslip->medical_allowance],
                    ['Transport',       $payslip->transport_allowance],
                    ['Meal',            $payslip->meal_allowance],
                    ['Special',         $payslip->special_allowance],
                    ['Other',           $payslip->other_allowance],
                    ['Overtime',        $payslip->overtime_amount],
                    ['Bonus',           $payslip->bonus],
                ] as [$lbl,$val])
                @if($val > 0)
                <div class="row"><span class="lbl">{{ $lbl }}</span><span class="val">{{ number_format($val) }}</span></div>
                @endif
                @endforeach
                <div class="total-row"><span>Gross</span><span style="color:#1d4ed8">{{ number_format($payslip->gross_salary) }}</span></div>
            </div>

            {{-- Deductions --}}
            <div>
                <div class="section-title">Deductions</div>
                @foreach([
                    ['Income Tax',      $payslip->income_tax_monthly],
                    ['EOBI',            $payslip->eobi_employee_share],
                    ['Provident Fund',  $payslip->provident_fund],
                    ['Loan',            $payslip->loan_deduction],
                    ['Absent',          $payslip->absent_deduction],
                    ['Late',            $payslip->late_deduction],
                    ['Other',           $payslip->other_deduction],
                ] as [$lbl,$val])
                @if($val > 0)
                <div class="row"><span class="lbl">{{ $lbl }}</span><span class="val" style="color:#dc2626">{{ number_format($val) }}</span></div>
                @endif
                @endforeach
                <div class="total-row"><span>Total Deductions</span><span style="color:#dc2626">{{ number_format($payslip->total_deductions) }}</span></div>
            </div>
        </div>

        <div class="net-box">
            <div>
                <div class="net-label">Net Salary Payable</div>
                @if($payslip->paid_on)<div style="font-size:10px;color:#94a3b8">Paid: {{ \Carbon\Carbon::parse($payslip->paid_on)->format('d M Y') }}</div>@endif
            </div>
            <div class="net-val">PKR {{ number_format($payslip->net_salary) }}</div>
        </div>

        {{-- Signatures --}}
        <div class="sig-row">
            <div class="sig-box">Employee Signature</div>
            <div class="sig-box">HR Manager</div>
            <div class="sig-box">Authorized Signatory</div>
        </div>

        <div class="footer">
            This is a computer-generated payslip. &bull; {{ $payslip->payslip_number }} &bull; MediCore Hospital Management System
        </div>
    </div>
</div>
</body>
</html>
