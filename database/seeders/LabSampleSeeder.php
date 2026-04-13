<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LabSampleSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('lab_samples')->truncate();

        // Order IDs hardcoded (LabOrderSeeder se match karte hain)
        $samples = [
            [
                'sample_number'  => 'SMP-00001',
                'lab_order_id'   => 1,
                'sample_type_id' => 2, // Serum (Fasting)
                'status'         => 'Completed',
                'collected_at'   => $now->copy()->subDays(5)->setTime(8, 30),
                'received_at'    => $now->copy()->subDays(5)->setTime(8, 45),
                'processed_at'   => $now->copy()->subDays(4)->setTime(10, 0),
                'collected_by'   => 'Phlebotomist Ahmed',
                'notes'          => 'Patient fasted for 10 hours',
                'created_at'     => $now->copy()->subDays(5),
                'updated_at'     => $now->copy()->subDays(4),
            ],
            [
                'sample_number'  => 'SMP-00002',
                'lab_order_id'   => 1,
                'sample_type_id' => 5, // Urine Random
                'status'         => 'Completed',
                'collected_at'   => $now->copy()->subDays(5)->setTime(8, 30),
                'received_at'    => $now->copy()->subDays(5)->setTime(8, 45),
                'processed_at'   => $now->copy()->subDays(4)->setTime(10, 30),
                'collected_by'   => 'Patient self-collected',
                'notes'          => 'Midstream urine',
                'created_at'     => $now->copy()->subDays(5),
                'updated_at'     => $now->copy()->subDays(4),
            ],
            [
                'sample_number'  => 'SMP-00003',
                'lab_order_id'   => 2,
                'sample_type_id' => 2,
                'status'         => 'Completed',
                'collected_at'   => $now->copy()->subDays(3)->setTime(7, 0),
                'received_at'    => $now->copy()->subDays(3)->setTime(7, 15),
                'processed_at'   => $now->copy()->subDays(2)->setTime(8, 30),
                'collected_by'   => 'Phlebotomist Fatima',
                'notes'          => 'Pre-operative sample, patient fasted',
                'created_at'     => $now->copy()->subDays(3),
                'updated_at'     => $now->copy()->subDays(2),
            ],
            [
                'sample_number'  => 'SMP-00004',
                'lab_order_id'   => 3,
                'sample_type_id' => 3, // Plasma (EDTA)
                'status'         => 'In Process',
                'collected_at'   => $now->copy()->subDays(2)->setTime(9, 0),
                'received_at'    => $now->copy()->subDays(2)->setTime(9, 15),
                'processed_at'   => $now->copy()->subDay()->setTime(10, 0),
                'collected_by'   => 'Phlebotomist Ahmed',
                'notes'          => 'Pediatric patient, small volume',
                'created_at'     => $now->copy()->subDays(2),
                'updated_at'     => $now,
            ],
            [
                'sample_number'  => 'SMP-00005',
                'lab_order_id'   => 3,
                'sample_type_id' => 5,
                'status'         => 'Completed',
                'collected_at'   => $now->copy()->subDays(2)->setTime(9, 0),
                'received_at'    => $now->copy()->subDays(2)->setTime(9, 15),
                'processed_at'   => $now->copy()->subDay()->setTime(15, 30),
                'collected_by'   => 'Mother assisted',
                'notes'          => null,
                'created_at'     => $now->copy()->subDays(2),
                'updated_at'     => $now->copy()->subDay(),
            ],
            [
                'sample_number'  => 'SMP-00006',
                'lab_order_id'   => 4,
                'sample_type_id' => 1, // Whole Blood EDTA
                'status'         => 'Completed',
                'collected_at'   => $now->copy()->subHours(6)->setTime(3, 35),
                'received_at'    => $now->copy()->subHours(6)->setTime(3, 40),
                'processed_at'   => $now->copy()->subHours(5)->setTime(4, 15),
                'collected_by'   => 'Emergency Nurse',
                'notes'          => 'STAT sample - Emergency',
                'created_at'     => $now->copy()->subHours(6),
                'updated_at'     => $now->copy()->subHours(5),
            ],
            [
                'sample_number'  => 'SMP-00007',
                'lab_order_id'   => 5,
                'sample_type_id' => 2,
                'status'         => 'Received',
                'collected_at'   => $now->copy()->setTime(9, 30),
                'received_at'    => $now->copy()->setTime(9, 45),
                'processed_at'   => null,
                'collected_by'   => 'Phlebotomist Zainab',
                'notes'          => 'Fasting sample for lipid profile',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'sample_number'  => 'SMP-00008',
                'lab_order_id'   => 6,
                'sample_type_id' => 5,
                'status'         => 'In Process',
                'collected_at'   => $now->copy()->subDay()->setTime(10, 0),
                'received_at'    => $now->copy()->subDay()->setTime(10, 15),
                'processed_at'   => $now->copy()->subDay()->setTime(11, 0),
                'collected_by'   => 'Patient self-collected',
                'notes'          => 'Culture in progress - 48 hours required',
                'created_at'     => $now->copy()->subDay(),
                'updated_at'     => $now,
            ],
        ];

        DB::table('lab_samples')->insert($samples);

        // ─── Ab lab_order_items mein lab_sample_id update karo ───
        // Sample IDs auto-increment se 1-8 honge
        // Har order ke items ko us order ka pehla sample assign karo

        // Order 1 ke items (item IDs 1,2,3) → SMP-00001 (id=1)
        DB::table('lab_order_items')
            ->where('lab_order_id', 1)
            ->update(['lab_sample_id' => 1]);

        // Order 2 ke items (item IDs 4-9) → SMP-00003 (id=3)
        DB::table('lab_order_items')
            ->where('lab_order_id', 2)
            ->update(['lab_sample_id' => 3]);

        // Order 3, test CBC (item 10) → SMP-00004 (id=4)
        DB::table('lab_order_items')
            ->where('lab_order_id', 3)
            ->where('lab_test_id', 1)
            ->update(['lab_sample_id' => 4]);

        // Order 3, test Urine (item 11) → SMP-00005 (id=5)
        DB::table('lab_order_items')
            ->where('lab_order_id', 3)
            ->where('lab_test_id', 9)
            ->update(['lab_sample_id' => 5]);

        // Order 4 (item 12) → SMP-00006 (id=6)
        DB::table('lab_order_items')
            ->where('lab_order_id', 4)
            ->update(['lab_sample_id' => 6]);

        // Order 5 (items 13,14) → SMP-00007 (id=7)
        DB::table('lab_order_items')
            ->where('lab_order_id', 5)
            ->update(['lab_sample_id' => 7]);

        // Order 6 (item 15) → SMP-00008 (id=8)
        DB::table('lab_order_items')
            ->where('lab_order_id', 6)
            ->update(['lab_sample_id' => 8]);

        $this->command->info('✅ ' . count($samples) . ' Lab Samples seeded + lab_order_items updated');
    }
}