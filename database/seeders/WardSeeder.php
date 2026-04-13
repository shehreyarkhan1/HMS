<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WardSeeder extends Seeder
{
    public function run(): void
    {
        $wards = [
            [
                'name' => 'General Male Ward',
                'ward_code' => 'W-001',
                'type' => 'General',
                'total_beds' => 30,
                'floor' => 'Ground Floor',
                'block' => 'A',
                'bed_charges' => 500.00,
                'description' => 'General male patient ward with basic facilities',
                'is_active' => true,
            ],
            [
                'name' => 'General Female Ward',
                'ward_code' => 'W-002',
                'type' => 'General',
                'total_beds' => 25,
                'floor' => 'Ground Floor',
                'block' => 'B',
                'bed_charges' => 500.00,
                'description' => 'General female patient ward with basic facilities',
                'is_active' => true,
            ],
            [
                'name' => 'ICU - Intensive Care Unit',
                'ward_code' => 'W-003',
                'type' => 'ICU',
                'total_beds' => 12,
                'floor' => '1st Floor',
                'block' => 'A',
                'bed_charges' => 5000.00,
                'description' => 'Critical care unit with advanced monitoring',
                'is_active' => true,
            ],
            [
                'name' => 'CCU - Coronary Care Unit',
                'ward_code' => 'W-004',
                'type' => 'CCU',
                'total_beds' => 8,
                'floor' => '1st Floor',
                'block' => 'B',
                'bed_charges' => 6000.00,
                'description' => 'Specialized cardiac care unit',
                'is_active' => true,
            ],
            [
                'name' => 'NICU - Neonatal ICU',
                'ward_code' => 'W-005',
                'type' => 'NICU',
                'total_beds' => 10,
                'floor' => '2nd Floor',
                'block' => 'A',
                'bed_charges' => 7000.00,
                'description' => 'Neonatal intensive care for newborns',
                'is_active' => true,
            ],
            [
                'name' => 'Surgical Ward Male',
                'ward_code' => 'W-006',
                'type' => 'Surgical',
                'total_beds' => 20,
                'floor' => '2nd Floor',
                'block' => 'B',
                'bed_charges' => 1500.00,
                'description' => 'Post-operative care ward for male patients',
                'is_active' => true,
            ],
            [
                'name' => 'Surgical Ward Female',
                'ward_code' => 'W-007',
                'type' => 'Surgical',
                'total_beds' => 18,
                'floor' => '2nd Floor',
                'block' => 'C',
                'bed_charges' => 1500.00,
                'description' => 'Post-operative care ward for female patients',
                'is_active' => true,
            ],
            [
                'name' => 'Maternity Ward',
                'ward_code' => 'W-008',
                'type' => 'Maternity',
                'total_beds' => 15,
                'floor' => '3rd Floor',
                'block' => 'A',
                'bed_charges' => 2000.00,
                'description' => 'Labor and delivery ward',
                'is_active' => true,
            ],
            [
                'name' => 'Pediatric Ward',
                'ward_code' => 'W-009',
                'type' => 'Pediatric',
                'total_beds' => 20,
                'floor' => '3rd Floor',
                'block' => 'B',
                'bed_charges' => 1200.00,
                'description' => 'Children and infant care ward',
                'is_active' => true,
            ],
            [
                'name' => 'Orthopedic Ward',
                'ward_code' => 'W-010',
                'type' => 'Orthopedic',
                'total_beds' => 16,
                'floor' => '3rd Floor',
                'block' => 'C',
                'bed_charges' => 1800.00,
                'description' => 'Bone and joint surgery recovery ward',
                'is_active' => true,
            ],
            [
                'name' => 'Private Rooms Block A',
                'ward_code' => 'W-011',
                'type' => 'Private',
                'total_beds' => 10,
                'floor' => '4th Floor',
                'block' => 'A',
                'bed_charges' => 8000.00,
                'description' => 'Private single rooms with AC and attached bath',
                'is_active' => true,
            ],
            [
                'name' => 'Semi-Private Rooms',
                'ward_code' => 'W-012',
                'type' => 'Semi-Private',
                'total_beds' => 12,
                'floor' => '4th Floor',
                'block' => 'B',
                'bed_charges' => 4000.00,
                'description' => 'Double occupancy rooms with enhanced facilities',
                'is_active' => true,
            ],
        ];

        foreach ($wards as $ward) {
            DB::table('wards')->insert(array_merge($ward, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✅ 12 Wards seeded successfully');
    }
}