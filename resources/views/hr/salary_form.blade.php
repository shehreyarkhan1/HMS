<div class="row g-3">
    {{-- LEFT --}}
    <div class="col-12 col-lg-8">

        {{-- Earnings --}}
        <div class="form-card mb-3">
            <div class="form-card-header">
                <div style="width:36px;height:36px;border-radius:9px;background:#dcfce7;color:#16a34a;display:flex;align-items:center;justify-content:center;font-size:16px"><i class="bi bi-plus-circle"></i></div>
                <div><div style="font-size:14px;font-weight:600;color:#1e293b">Earnings / Allowances</div><div style="font-size:12px;color:#94a3b8">Monthly salary components</div></div>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-6 col-md-4">
                        <label class="form-label">Basic Salary <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">PKR</span>
                            <input type="number" name="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror"
                                   value="{{ old('basic_salary', $structure->basic_salary ?? '') }}" min="0" step="100" required id="basicSalary">
                        </div>
                        @error('basic_salary')<div class="text-danger" style="font-size:11px">{{ $message }}</div>@enderror
                    </div>
                    @foreach([
                        ['house_rent_allowance','House Rent Allowance'],
                        ['medical_allowance','Medical Allowance'],
                        ['transport_allowance','Transport Allowance'],
                        ['meal_allowance','Meal Allowance'],
                        ['special_allowance','Special Allowance'],
                    ] as [$field,$label])
                    <div class="col-6 col-md-4">
                        <label class="form-label">{{ $label }}</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">PKR</span>
                            <input type="number" name="{{ $field }}" class="form-control sal-component"
                                   value="{{ old($field, $structure->$field ?? 0) }}" min="0" step="100">
                        </div>
                    </div>
                    @endforeach
                    <div class="col-6 col-md-4">
                        <label class="form-label">Other Allowance</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">PKR</span>
                            <input type="number" name="other_allowance" class="form-control sal-component"
                                   value="{{ old('other_allowance', $structure->other_allowance ?? 0) }}" min="0" step="100">
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label">Other Allowance Description</label>
                        <input type="text" name="other_allowance_description" class="form-control form-control-sm"
                               value="{{ old('other_allowance_description', $structure->other_allowance_description ?? '') }}" placeholder="e.g. Performance Bonus">
                    </div>
                    {{-- Gross preview --}}
                    <div class="col-12">
                        <div style="background:#eff6ff;border-radius:10px;padding:.85rem 1rem;border:1px solid #bfdbfe;display:flex;justify-content:space-between;align-items:center">
                            <span style="font-size:13px;font-weight:600;color:#1d4ed8">Gross Salary (Preview)</span>
                            <span id="grossPreview" style="font-size:18px;font-weight:800;color:#1d4ed8">PKR 0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Deductions --}}
        <div class="form-card mb-3">
            <div class="form-card-header">
                <div style="width:36px;height:36px;border-radius:9px;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:16px"><i class="bi bi-dash-circle"></i></div>
                <div><div style="font-size:14px;font-weight:600;color:#1e293b">Deductions</div><div style="font-size:12px;color:#94a3b8">Monthly deductions from gross</div></div>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <div class="col-6 col-md-4">
                        <label class="form-label">Income Tax / Month</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">PKR</span>
                            <input type="number" name="income_tax_monthly" class="form-control sal-deduction"
                                   value="{{ old('income_tax_monthly', $structure->income_tax_monthly ?? 0) }}" min="0" step="10">
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label">Tax Slab</label>
                        <input type="text" name="tax_slab" class="form-control form-control-sm"
                               value="{{ old('tax_slab', $structure->tax_slab ?? '') }}" placeholder="e.g. 15%">
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" name="is_tax_exempt" value="1" id="isTaxExempt"
                                   {{ old('is_tax_exempt', $structure->is_tax_exempt ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isTaxExempt" style="font-size:12px">Tax Exempt</label>
                        </div>
                    </div>
                    @foreach([
                        ['eobi_employee_share','EOBI (Employee Share)'],
                        ['eobi_employer_share','EOBI (Employer Share)'],
                        ['provident_fund','Provident Fund'],
                        ['loan_deduction','Loan Deduction'],
                    ] as [$field,$label])
                    <div class="col-6 col-md-4">
                        <label class="form-label">{{ $label }}</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">PKR</span>
                            <input type="number" name="{{ $field }}" class="form-control sal-deduction"
                                   value="{{ old($field, $structure->$field ?? 0) }}" min="0" step="10">
                        </div>
                    </div>
                    @endforeach
                    <div class="col-6 col-md-4">
                        <label class="form-label">Other Deduction</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">PKR</span>
                            <input type="number" name="other_deduction" class="form-control sal-deduction"
                                   value="{{ old('other_deduction', $structure->other_deduction ?? 0) }}" min="0" step="10">
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label">Other Deduction Description</label>
                        <input type="text" name="other_deduction_description" class="form-control form-control-sm"
                               value="{{ old('other_deduction_description', $structure->other_deduction_description ?? '') }}">
                    </div>
                    {{-- EOBI toggle --}}
                    <div class="col-6 col-md-4">
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" name="eobi_applicable" value="1" id="eobiApplicable"
                                   {{ old('eobi_applicable', $structure->eobi_applicable ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="eobiApplicable" style="font-size:12px">EOBI Applicable</label>
                        </div>
                    </div>

                    {{-- Net preview --}}
                    <div class="col-12">
                        <div style="background:#f0fdf4;border-radius:10px;padding:.85rem 1rem;border:1px solid #bbf7d0;display:flex;justify-content:space-between;align-items:center">
                            <span style="font-size:13px;font-weight:600;color:#16a34a">Net Salary (Preview)</span>
                            <span id="netPreview" style="font-size:18px;font-weight:800;color:#16a34a">PKR 0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="col-12 col-lg-4">
        <div class="form-card mb-3">
            <div class="form-card-header">
                <div style="width:36px;height:36px;border-radius:9px;background:#eff6ff;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:16px"><i class="bi bi-calendar3"></i></div>
                <div><div style="font-size:14px;font-weight:600;color:#1e293b">Effective Date</div></div>
            </div>
            <div class="form-card-body">
                @if(!isset($structure))
                <div class="mb-3">
                    <label class="form-label">Effective From <span class="text-danger">*</span></label>
                    <input type="date" name="effective_from" class="form-control @error('effective_from') is-invalid @enderror"
                           value="{{ old('effective_from', today()->toDateString()) }}" required>
                    @error('effective_from')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @endif
                <div>
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Reason for revision…">{{ old('notes', $structure->notes ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Summary box --}}
        <div style="background:#1e293b;border-radius:12px;padding:1.25rem;color:#fff">
            <div style="font-size:12px;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.75rem">Salary Summary</div>
            <div style="display:flex;justify-content:space-between;margin-bottom:.4rem">
                <span style="font-size:12px;color:#94a3b8">Gross</span>
                <span id="summGross" style="font-size:13px;font-weight:600">0</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:.4rem">
                <span style="font-size:12px;color:#94a3b8">Deductions</span>
                <span id="summDed" style="font-size:13px;font-weight:600;color:#f87171">0</span>
            </div>
            <div style="border-top:1px solid #334155;margin:.75rem 0"></div>
            <div style="display:flex;justify-content:space-between">
                <span style="font-size:14px;font-weight:600;color:#fff">Net</span>
                <span id="summNet" style="font-size:20px;font-weight:800;color:#4ade80">0</span>
            </div>
        </div>

        {{-- Submit --}}
        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('hr.salary.show', $employee) }}" class="btn btn-light btn-sm flex-fill">Cancel</a>
            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                <i class="bi bi-check-lg me-1"></i>
                {{ isset($structure) ? 'Update' : 'Create' }} Structure
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function recalc() {
    let gross = 0;
    document.querySelectorAll('.sal-component, #basicSalary').forEach(el => gross += parseFloat(el.value) || 0);
    let ded = 0;
    document.querySelectorAll('.sal-deduction').forEach(el => ded += parseFloat(el.value) || 0);
    let net = Math.max(0, gross - ded);
    const fmt = v => 'PKR ' + v.toLocaleString('en-PK');
    document.getElementById('grossPreview').textContent = fmt(gross);
    document.getElementById('netPreview').textContent   = fmt(net);
    document.getElementById('summGross').textContent    = gross.toLocaleString('en-PK');
    document.getElementById('summDed').textContent      = ded.toLocaleString('en-PK');
    document.getElementById('summNet').textContent      = net.toLocaleString('en-PK');
}
document.querySelectorAll('.sal-component, .sal-deduction, #basicSalary').forEach(el => el.addEventListener('input', recalc));
recalc();
</script>
@endpush
