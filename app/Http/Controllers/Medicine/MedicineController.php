<?php

namespace App\Http\Controllers\Medicine;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('stock')) {
            match ($request->stock) {
                'low' => $query->lowStock(),
                'out' => $query->outOfStock(),
                default => null,
            };
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $medicines = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Medicine::count(),
            'low_stock' => Medicine::lowStock()->count(),
            'out' => Medicine::outOfStock()->count(),
            'expiring' => MedicineBatch::where('status', 'Active')
                ->whereBetween('expiry_date', [now(), now()->addDays(30)])
                ->count(),
        ];

        return view('pharmacy.medicines_index', compact('medicines', 'stats'));
    }

    // ── CREATE ──
    public function create()
    {
        return view('pharmacy.medicines_create');
    }

    // ── STORE ──
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:150',
        'generic_name' => 'nullable|string|max:150',
        'brand' => 'nullable|string|max:100',
        'category' => 'required|in:Tablet,Capsule,Syrup,Injection,Cream,Drops,Inhaler,Powder,Other',
        'unit' => 'required|string|max:50',
        'purchase_price' => 'required|numeric|min:0',
        'sale_price' => 'required|numeric|min:0',
        'reorder_level' => 'required|integer|min:0',
        // requires_prescription hata do yahan se
        'storage_condition' => 'nullable|string|max:100',
        'description' => 'nullable|string',
    ]);

   Medicine::create([
    ...$request->only([
        'name', 'generic_name', 'brand', 'category', 'unit',
        'purchase_price', 'sale_price', 'reorder_level',
        'storage_condition', 'description'
    ]),
    'requires_prescription' => $request->boolean('requires_prescription'),
]);

    return redirect()
        ->route('pharmacy.medicines.index')
        ->with('success', 'Medicine added successfully!');
}

    // ── SHOW ──
    public function show(Medicine $medicine)
    {
        $medicine->load('batches');
        $expiringBatches = $medicine->batches()
            ->where('status', 'Active')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->get();

        return view('pharmacy.medicines_show', compact('medicine', 'expiringBatches'));
    }

    // ── EDIT ──
    public function edit(Medicine $medicine)
    {
        return view('pharmacy.medicines_edit', compact('medicine'));
    }

    // ── UPDATE ──
    public function update(Request $request, Medicine $medicine)
{
    $request->validate([
        'name' => 'required|string|max:150',
        'generic_name' => 'nullable|string|max:150',
        'brand' => 'nullable|string|max:100',
        'category' => 'required|in:Tablet,Capsule,Syrup,Injection,Cream,Drops,Inhaler,Powder,Other',
        'unit' => 'required|string|max:50',
        'purchase_price' => 'required|numeric|min:0',
        'sale_price' => 'required|numeric|min:0',
        'reorder_level' => 'required|integer|min:0',
        // requires_prescription hata do yahan se bhi
        'storage_condition' => 'nullable|string|max:100',
        'description' => 'nullable|string',
    ]);

    $medicine->update([
    ...$request->only([
        'name', 'generic_name', 'brand', 'category', 'unit',
        'purchase_price', 'sale_price', 'reorder_level',
        'storage_condition', 'description'
    ]),
    'requires_prescription' => $request->boolean('requires_prescription'),
    'is_active' => $request->boolean('is_active'),
]);

    return redirect()
        ->route('pharmacy.medicines.show', $medicine->id)
        ->with('success', 'Medicine updated successfully!');
}

    // ── DESTROY ──
    public function destroy(Medicine $medicine)
    {
        if ($medicine->total_stock > 0) {
            return back()->with('error', 'Cannot delete medicine with stock. Mark as inactive instead.');
        }
        $medicine->delete();

        return redirect()
            ->route('pharmacy.medicines.index')
            ->with('success', "Medicine '{$medicine->name}' removed.");
    }

    // ── ADD STOCK (New Batch) ──
    public function addStock(Request $request, Medicine $medicine)
    {
        $request->validate([
            'batch_number' => 'required|string|max:100',
            'expiry_date' => 'required|date|after:today',
            'manufacture_date' => 'nullable|date|before:today',
            'quantity' => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0',
            'supplier_name' => 'nullable|string|max:150',
            'supplier_invoice' => 'nullable|string|max:100',
        ]);

        // Check duplicate batch
        $exists = MedicineBatch::where('medicine_id', $medicine->id)
            ->where('batch_number', $request->batch_number)
            ->exists();
        if ($exists) {
            return back()->withInput()
                ->withErrors(['batch_number' => 'This batch number already exists for this medicine.']);
        }

        MedicineBatch::create([
            'medicine_id' => $medicine->id,
            'batch_number' => $request->batch_number,
            'expiry_date' => $request->expiry_date,
            'manufacture_date' => $request->manufacture_date,
            'quantity_received' => $request->quantity,
            'quantity_in_stock' => $request->quantity,
            'purchase_price' => $request->purchase_price,
            'supplier_name' => $request->supplier_name,
            'supplier_invoice' => $request->supplier_invoice,
            'status' => 'Active',
        ]);

        // medicine syncStock() auto called via MedicineBatch boot()

        return back()->with('success', "Stock added! {$request->quantity} units of batch {$request->batch_number}.");
    }
}
