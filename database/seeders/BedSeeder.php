<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BedSeeder extends Seeder
{
    public function run(): void
    {
        $beds = [];
        $now = Carbon::now();
        
        // Ward configurations: [ward_id, total_beds, bed_type]
        $wardConfigs = [
            [1, 30, 'Standard'],     // General Male
            [2, 25, 'Standard'],     // General Female
            [3, 12, 'ICU'],          // ICU
            [4, 8, 'ICU'],           // CCU
            [5, 10, 'ICU'],          // NICU
            [6, 20, 'Standard'],     // Surgical Male
            [7, 18, 'Standard'],     // Surgical Female
            [8, 15, 'Standard'],     // Maternity
            [9, 20, 'Standard'],     // Pediatric
            [10, 16, 'Standard'],    // Orthopedic
            [11, 10, 'Private'],     // Private Rooms
            [12, 12, 'Semi-Private'], // Semi-Private
        ];

        $occupiedPatients = [6, 7, 8, 18]; // Patient IDs that are admitted

        foreach ($wardConfigs as list($wardId, $totalBeds, $bedType)) {
            for ($i = 1; $i <= $totalBeds; $i++) {
                $bedNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
                
                // Randomly occupy some beds
                $isOccupied = rand(1, 100) <= 30; // 30% occupancy
                
                $bed = [
                    'bed_number' => $bedNumber,
                    'ward_id' => $wardId,
                    'type' => $bedType,
                    'status' => 'Available',
                    'patient_id' => null,
                    'admitted_at' => null,
                    'discharged_at' => null,
                    'notes' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // Assign occupied beds for admitted patients
                if ($wardId === 3 && $i === 1) {
                    // ICU - Patient 6 (Cardiac surgery)
                    $bed['status'] = 'Occupied';
                    $bed['patient_id'] = 6;
                    $bed['admitted_at'] = $now->copy()->subDays(2)->format('Y-m-d');
                    $bed['notes'] = 'Post cardiac surgery, stable condition';
                } elseif ($wardId === 8 && $i === 1) {
                    // Maternity - Patient 7 (C-section)
                    $bed['status'] = 'Occupied';
                    $bed['patient_id'] = 7;
                    $bed['admitted_at'] = $now->copy()->subDays(1)->format('Y-m-d');
                    $bed['notes'] = 'C-section scheduled for tomorrow';
                } elseif ($wardId === 10 && $i === 1) {
                    // Orthopedic - Patient 8 (Fractured leg)
                    $bed['status'] = 'Occupied';
                    $bed['patient_id'] = 8;
                    $bed['admitted_at'] = $now->format('Y-m-d');
                    $bed['notes'] = 'Road accident victim, fractured femur';
                } elseif ($wardId === 3 && $i === 2) {
                    // ICU - Patient 18 (Stroke)
                    $bed['status'] = 'Occupied';
                    $bed['patient_id'] = 18;
                    $bed['admitted_at'] = $now->copy()->subHours(6)->format('Y-m-d');
                    $bed['notes'] = 'Stroke patient, critical but stable';
                } elseif ($isOccupied && $wardId !== 3 && $wardId !== 4) {
                    // Random occupancy for other wards (not ICU/CCU)
                    $bed['status'] = 'Occupied';
                    $bed['admitted_at'] = $now->copy()->subDays(rand(1, 7))->format('Y-m-d');
                    $bed['notes'] = 'Regular admission';
                } elseif (rand(1, 100) <= 5) {
                    // 5% beds under maintenance
                    $bed['status'] = 'Maintenance';
                    $bed['notes'] = 'Scheduled maintenance';
                }

                $beds[] = $bed;
            }
        }

        // Insert in chunks for better performance
        foreach (array_chunk($beds, 100) as $chunk) {
            DB::table('beds')->insert($chunk);
        }

        $this->command->info('✅ ' . count($beds) . ' Beds seeded successfully');
    }
}