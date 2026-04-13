<?php

namespace App\Http\Controllers\Dispensing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Prescription;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Dispensing;
use App\Models\DispensingItem;
use App\Models\PrescriptionItem;
use App\Models\Patient;
use Carbon\Carbon;
class DispensingController extends Controller
{
    // ===== INDEX =====
    public function index(Request $request)
    {
        $query = Dispensing::with('patient', 'prescription')->latest('dispensed_at');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('dispense_number', 'ilike', "%{$request->search}%")
                    ->orWhereHas('patient', fn($p) => $p->where('name', 'ilike', "%{$request->search}%"));
            });
        }
        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }
        if ($request->filled('date')) {
            $query->whereDate('dispensed_at', $request->date);
        }

        $dispensings = $query->paginate(15)->withQueryString();

        $stats = [
            'today_count' => Dispensing::whereDate('dispensed_at', today())->count(),
            'today_revenue' => Dispensing::whereDate('dispensed_at', today())->sum('total_amount'),
            'unpaid' => Dispensing::where('payment_status', 'Unpaid')->count(),
            'total_revenue' => Dispensing::where('payment_status', 'Paid')->sum('total_amount'),
        ];

        return view('dispensing.dispensings_index', compact('dispensings', 'stats'));
    }

    // ===== CREATE (Dispense from Prescription) =====
    public function create(Request $request)
    {
        $prescription = null;
        if ($request->filled('prescription_id')) {
            $prescription = Prescription::with('patient', 'doctor', 'items.medicine')
                ->findOrFail($request->prescription_id);
        }

        $patients = Patient::select('id', 'name', 'mrn')->orderBy('name')->get();
        $medicines = Medicine::with('batches')
            ->where('is_active', true)
            ->where('total_stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('dispensing.dispensings_create', compact('prescription', 'patients', 'medicines'));
    }

    // ===== STORE =====
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'prescription_id' => 'nullable|exists:prescriptions,id',
            'payment_status' => 'required|in:Paid,Unpaid,Partial',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.medicine_batch_id' => 'required|exists:medicine_batches,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.prescription_item_id' => 'nullable|exists:prescription_items,id',
        ]);

        DB::transaction(function () use ($request) {

            $totalAmount = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $batch = MedicineBatch::findOrFail($item['medicine_batch_id']);

                // Check stock
                if ($batch->quantity_in_stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock in batch {$batch->batch_number}!");
                }

                $unitPrice = $batch->medicine->sale_price;
                $totalPrice = $unitPrice * $item['quantity'];
                $totalAmount += $totalPrice;

                // Deduct from batch
                $batch->decrement('quantity_in_stock', $item['quantity']);
                if ($batch->quantity_in_stock == 0) {
                    $batch->update(['status' => 'Exhausted']);
                }

                $itemsData[] = [
                    'medicine_id' => $item['medicine_id'],
                    'medicine_batch_id' => $item['medicine_batch_id'],
                    'prescription_item_id' => $item['prescription_item_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ];

                // Update dispensed_qty on prescription item
                if (!empty($item['prescription_item_id'])) {
                    PrescriptionItem::find($item['prescription_item_id'])
                            ?->increment('dispensed_qty', $item['quantity']);
                }
            }

            // Create dispensing
            $dispensing = Dispensing::create([
                'patient_id' => $request->patient_id,
                'prescription_id' => $request->prescription_id,
                'dispensed_at' => now(),
                'total_amount' => $totalAmount,
                'payment_status' => $request->payment_status,
                'notes' => $request->notes,
            ]);

            // Create dispensing items
            foreach ($itemsData as $data) {
                $dispensing->items()->create($data);
            }

            // Sync medicine total_stock
            foreach ($request->items as $item) {
                Medicine::find($item['medicine_id'])?->syncStock();
            }

            // Sync prescription status
            if ($request->prescription_id) {
                Prescription::find($request->prescription_id)?->syncStatus();
            }
        });

        return redirect()
            ->route('pharmacy.dispensings.index')
            ->with('success', 'Medicines dispensed successfully!');
    }

    // ===== SHOW =====
    public function show(Dispensing $dispensing)
    {
        $dispensing->load('patient', 'prescription.doctor', 'items.medicine', 'items.batch');

        // First medicine (assuming single or main)
        $medicine = $dispensing->items->first()->medicine ?? null;

        // Expiring batches (next 30 days)
        $expiringBatches = $dispensing->items
            ->pluck('batch')
            ->filter(function ($batch) {
                return $batch && $batch->expiry_date <= now()->addDays(30);
            });

        return view('pharmacy.medicines_show', compact(
            'dispensing',
            'medicine',
            'expiringBatches'
        ));
    }

    // ===== MARK AS PAID =====
    public function markPaid(Dispensing $dispensing)
    {
        $dispensing->update(['payment_status' => 'Paid']);
        return back()->with('success', 'Payment marked as paid!');
    }
}
