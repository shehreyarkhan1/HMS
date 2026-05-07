<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Billing\BillServiceCharge;

class BillServiceChargeController extends Controller
{
    public function index()
    {
        $charges = BillServiceCharge::orderBy('category')->orderBy('name')->get();

        return view('billing.service-charges.index', compact('charges'));
    }

    public function create()
    {
        return view('billing.service-charges.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:bill_service_charges,code',
            'category' => 'required|in:'.implode(',', BillServiceCharge::categories()),
            'default_price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        BillServiceCharge::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'category' => $request->category,
            'default_price' => $request->default_price,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('billing.service-charges.index')
            ->with('success', 'Service charge "'.$request->name.'" created successfully.');
    }

    public function edit(BillServiceCharge $charge)
    {
        return view('billing.service-charges.form', compact('charge'));
    }

    public function update(Request $request, BillServiceCharge $charge)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:'.implode(',', BillServiceCharge::categories()),
            'default_price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $charge->update([
            'name' => $request->name,
            'category' => $request->category,
            'default_price' => $request->default_price,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('billing.service-charges.index')
            ->with('success', 'Service charge updated successfully.');
    }

    public function destroy(BillServiceCharge $charge)
    {
        $charge->delete();

        return redirect()->route('billing.service-charges.index')
            ->with('success', 'Service charge deleted.');
    }

    public function toggle(BillServiceCharge $charge)
    {
        $charge->update(['is_active' => ! $charge->is_active]);
        $status = $charge->is_active ? 'activated' : 'deactivated';

        return back()->with('success', '"'.$charge->name.'" has been '.$status.'.');
    }
}
