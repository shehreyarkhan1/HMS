<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DispensingSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $dispensings = [];
        $dispensingItems = [];
        $dispensingId = 1;

        // Dispensing 1: For Prescription RX-00001 (Diabetes)
        $totalAmount = 60 * 12.00; // 60 tablets of Glucophage
        $dispensings[] = [
            'id' => $dispensingId,
            'dispense_number' => 'DSP-00001',
            'prescription_id' => 1,
            'patient_id' => 1,
            'dispensed_at' => $now->copy()->subDays(4),
            'total_amount' => $totalAmount,
            'payment_status' => 'Paid',
            'notes' => 'Full prescription dispensed',
            'created_at' => $now->copy()->subDays(4),
            'updated_at' => $now->copy()->subDays(4),
        ];

        $dispensingItems[] = [
            'dispensing_id' => $dispensingId,
            'prescription_item_id' => 1,
            'medicine_id' => 7,
            'medicine_batch_id' => 7, // Corresponding batch
            'quantity' => 60,
            'unit_price' => 12.00,
            'total_price' => 720.00,
            'created_at' => $now->copy()->subDays(4),
            'updated_at' => $now->copy()->subDays(4),
        ];

        $dispensingId++;

        // Dispensing 2: For Prescription RX-00002 (Cardiac)
        $totalAmount = (30 * 15.00) + (30 * 12.00); // Concor + Norvasc
        $dispensings[] = [
            'id' => $dispensingId,
            'dispense_number' => 'DSP-00002',
            'prescription_id' => 2,
            'patient_id' => 3,
            'dispensed_at' => $now->copy()->subDays(2),
            'total_amount' => $totalAmount,
            'payment_status' => 'Paid',
            'notes' => null,
            'created_at' => $now->copy()->subDays(2),
            'updated_at' => $now->copy()->subDays(2),
        ];

        $dispensingItems[] = [
            'dispensing_id' => $dispensingId,
            'prescription_item_id' => 2,
            'medicine_id' => 9,
            'medicine_batch_id' => 9,
            'quantity' => 30,
            'unit_price' => 15.00,
            'total_price' => 450.00,
            'created_at' => $now->copy()->subDays(2),
            'updated_at' => $now->copy()->subDays(2),
        ];

        $dispensingItems[] = [
            'dispensing_id' => $dispensingId,
            'prescription_item_id' => 3,
            'medicine_id' => 10,
            'medicine_batch_id' => 10,
            'quantity' => 30,
            'unit_price' => 12.00,
            'total_price' => 360.00,
            'created_at' => $now->copy()->subDays(2),
            'updated_at' => $now->copy()->subDays(2),
        ];

        $dispensingId++;

        // Dispensing 3: For Prescription RX-00003 (Pediatric - Partial)
        $totalAmount = 650.00; // Only Ventolin Inhaler
        $dispensings[] = [
            'id' => $dispensingId,
            'dispense_number' => 'DSP-00003',
            'prescription_id' => 3,
            'patient_id' => 5,
            'dispensed_at' => $now,
            'total_amount' => $totalAmount,
            'payment_status' => 'Paid',
            'notes' => 'Calpol Syrup out of stock, will dispense later',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $dispensingItems[] = [
            'dispensing_id' => $dispensingId,
            'prescription_item_id' => 4,
            'medicine_id' => 24,
            'medicine_batch_id' => 24,
            'quantity' => 1,
            'unit_price' => 650.00,
            'total_price' => 650.00,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $dispensingId++;

        // Dispensing 4: For Prescription RX-00005 (Bacterial infection)
        $totalAmount = (14 * 22.00) + 120.00; // Augmentin + Rexcof Syrup
        $dispensings[] = [
            'id' => $dispensingId,
            'dispense_number' => 'DSP-00004',
            'prescription_id' => 5,
            'patient_id' => 11,
            'dispensed_at' => $now->copy()->subDay(),
            'total_amount' => $totalAmount,
            'payment_status' => 'Paid',
            'notes' => null,
            'created_at' => $now->copy()->subDay(),
            'updated_at' => $now->copy()->subDay(),
        ];

        $dispensingItems[] = [
            'dispensing_id' => $dispensingId,
            'prescription_item_id' => 6,
            'medicine_id' => 4,
            'medicine_batch_id' => 4,
            'quantity' => 14,
            'unit_price' => 22.00,
            'total_price' => 308.00,
            'created_at' => $now->copy()->subDay(),
            'updated_at' => $now->copy()->subDay(),
        ];

        $dispensingItems[] = [
            'dispensing_id' => $dispensingId,
            'prescription_item_id' => 7,
            'medicine_id' => 13,
            'medicine_batch_id' => 13,
            'quantity' => 1,
            'unit_price' => 120.00,
            'total_price' => 120.00,
            'created_at' => $now->copy()->subDay(),
            'updated_at' => $now->copy()->subDay(),
        ];

        $dispensingId++;

        // Dispensing 5: For Prescription RX-00006 (Gastric)
        $totalAmount = (30 * 9.00) + (45 * 6.00); // Risek + Motilium
        $dispensings[] = [
            'id' => $dispensingId,
            'dispense_number' => 'DSP-00005',
            'prescription_id' => 6,
            'patient_id' => 10,
            'dispensed_at' => $now->copy()->subWeek()->addDay(),
            'total_amount' => $totalAmount,
            'payment_status' => 'Paid',
            'notes' => null,
            'created_at' => $now->copy()->subWeek()->addDay(),
            'updated_at' => $now->copy()->subWeek()->addDay(),
        ];

        $dispensingItems[] = [
            'dispensing_id' => $dispensingId,
            'prescription_item_id' => 8,
            'medicine_id' => 11,
            'medicine_batch_id' => 11,
            'quantity' => 30,
            'unit_price' => 9.00,
            'total_price' => 270.00,
            'created_at' => $now->copy()->subWeek()->addDay(),
            'updated_at' => $now->copy()->subWeek()->addDay(),
        ];

        $dispensingItems[] = [
            'dispensing_id' => $dispensingId,
            'prescription_item_id' => 9,
            'medicine_id' => 12,
            'medicine_batch_id' => 12,
            'quantity' => 45,
            'unit_price' => 6.00,
            'total_price' => 270.00,
            'created_at' => $now->copy()->subWeek()->addDay(),
            'updated_at' => $now->copy()->subWeek()->addDay(),
        ];

        $dispensingId++;

        // Dispensing 6: For Prescription RX-00007 (Pain management)
        $totalAmount = (30 * 4.50) + (14 * 22.00); // Brufen + Augmentin
        $dispensings[] = [
            'id' => $dispensingId,
            'dispense_number' => 'DSP-00006',
            'prescription_id' => 7,
            'patient_id' => 8,
            'dispensed_at' => $now,
            'total_amount' => $totalAmount,
            'payment_status' => 'Paid',
            'notes' => 'Post-operative medication',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $dispensingItems[] = [
            'dispensing_id' => $dispensingId,
            'prescription_item_id' => 10,
            'medicine_id' => 2,
            'medicine_batch_id' => 2,
            'quantity' => 30,
            'unit_price' => 4.50,
            'total_price' => 135.00,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $dispensingItems[] = [
            'dispensing_id' => $dispensingId,
            'prescription_item_id' => 11,
            'medicine_id' => 4,
            'medicine_batch_id' => 4,
            'quantity' => 14,
            'unit_price' => 22.00,
            'total_price' => 308.00,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $dispensingId++;

        // Direct dispensing without prescription (OTC medicines)
        $dispensings[] = [
            'id' => $dispensingId,
            'dispense_number' => 'DSP-00007',
            'prescription_id' => null,
            'patient_id' => 15,
            'dispensed_at' => $now->copy()->subDays(3),
            'total_amount' => 20.00,
            'payment_status' => 'Paid',
            'notes' => 'OTC purchase - Panadol for fever',
            'created_at' => $now->copy()->subDays(3),
            'updated_at' => $now->copy()->subDays(3),
        ];

        $dispensingItems[] = [
            'dispensing_id' => $dispensingId,
            'prescription_item_id' => null,
            'medicine_id' => 1,
            'medicine_batch_id' => 1,
            'quantity' => 10,
            'unit_price' => 2.00,
            'total_price' => 20.00,
            'created_at' => $now->copy()->subDays(3),
            'updated_at' => $now->copy()->subDays(3),
        ];

        $dispensingId++;

        // Unpaid dispensing
        $dispensings[] = [
            'id' => $dispensingId,
            'dispense_number' => 'DSP-00008',
            'prescription_id' => null,
            'patient_id' => 12,
            'dispensed_at' => $now,
            'total_amount' => 54.00,
            'payment_status' => 'Unpaid',
            'notes' => 'Payment pending',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $dispensingItems[] = [
            'dispensing_id' => $dispensingId,
            'prescription_item_id' => null,
            'medicine_id' => 14,
            'medicine_batch_id' => 14,
            'quantity' => 10,
            'unit_price' => 3.00,
            'total_price' => 30.00,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $dispensingItems[] = [
            'dispensing_id' => $dispensingId,
            'prescription_item_id' => null,
            'medicine_id' => 1,
            'medicine_batch_id' => 1,
            'quantity' => 12,
            'unit_price' => 2.00,
            'total_price' => 24.00,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        DB::table('dispensings')->insert($dispensings);
        DB::table('dispensing_items')->insert($dispensingItems);

        $this->command->info('✅ ' . count($dispensings) . ' Dispensings seeded with ' . count($dispensingItems) . ' items');
    }
}