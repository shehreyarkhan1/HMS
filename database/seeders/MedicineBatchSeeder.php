<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MedicineBatchSeeder extends Seeder
{
    public function run(): void
    {
        $batches = [];
        $now = Carbon::now();

        // Generate batches for each medicine
        for ($medicineId = 1; $medicineId <= 25; $medicineId++) {
            // Each medicine gets 2-3 batches
            $numBatches = rand(2, 3);
            
            for ($i = 1; $i <= $numBatches; $i++) {
                $manufactureDate = $now->copy()->subMonths(rand(1, 12));
                $expiryDate = $manufactureDate->copy()->addYears(rand(2, 3));
                
                $quantityReceived = rand(50, 500);
                $quantityInStock = $i === 1 ? rand(10, $quantityReceived) : rand(0, $quantityReceived);
                
                $status = 'Active';
                if ($expiryDate->isPast()) {
                    $status = 'Expired';
                } elseif ($quantityInStock === 0) {
                    $status = 'Exhausted';
                }

                $batches[] = [
                    'medicine_id' => $medicineId,
                    'batch_number' => 'BATCH-' . str_pad($medicineId, 3, '0', STR_PAD_LEFT) . '-' . chr(64 + $i),
                    'expiry_date' => $expiryDate->format('Y-m-d'),
                    'manufacture_date' => $manufactureDate->format('Y-m-d'),
                    'quantity_received' => $quantityReceived,
                    'quantity_in_stock' => $quantityInStock,
                    'purchase_price' => rand(100, 5000) / 100,
                    'supplier_name' => ['Ferozsons Laboratories', 'Bosch Pharmaceuticals', 'Searle Pakistan', 'Getz Pharma'][rand(0, 3)],
                    'supplier_invoice' => 'INV-' . $now->year . '-' . rand(1000, 9999),
                    'status' => $status,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('medicine_batches')->insert($batches);
        $this->command->info('✅ ' . count($batches) . ' Medicine Batches seeded successfully');
    }
}