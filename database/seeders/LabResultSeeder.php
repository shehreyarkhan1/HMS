<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LabResultSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Debug line - temporarily add karo
        $count = DB::table('lab_order_items')->count();
        $this->command->info('Debug: lab_order_items count = ' . $count);
        // ── Dynamically fetch lab_order_item IDs ──
        $items = DB::table('lab_order_items')->get();

        if ($items->isEmpty()) {
            $this->command->error('❌ lab_order_items empty hai — pehle LabOrderSeeder chalao');
            return;
        }

        // Helper: order_id + test_id se item ID dhundho
        $getItemId = function ($orderId, $testId) use ($items) {
            $item = $items->where('lab_order_id', $orderId)
                ->where('lab_test_id', $testId)
                ->first();
            return $item?->id;
        };

        $results = [];

        // Order 1 - Blood Sugar Fasting (test_id=4)
        $itemId = $getItemId(1, 4);
        if ($itemId) {
            $results[] = [
                'lab_order_item_id' => $itemId,
                'result_value' => '118',
                'unit' => 'mg/dL',
                'normal_range' => '70-100',
                'flag' => 'High',
                'is_abnormal' => true,
                'previous_value' => '125',
                'previous_date' => $now->copy()->subMonth()->format('Y-m-d'),
                'remarks' => 'Improved from last test but still above normal',
                'reported_at' => $now->copy()->subDays(4)->setTime(10, 45),
                'verified_by' => null,
                'verified_at' => null,
                'is_verified' => false,
                'created_at' => $now->copy()->subDays(4),
                'updated_at' => $now->copy()->subDays(4),
            ];
        }

        // Order 1 - HbA1c (test_id=5)
        $itemId = $getItemId(1, 5);
        if ($itemId) {
            $results[] = [
                'lab_order_item_id' => $itemId,
                'result_value' => '6.8',
                'unit' => '%',
                'normal_range' => '4.0-5.6',
                'flag' => 'High',
                'is_abnormal' => true,
                'previous_value' => '7.2',
                'previous_date' => $now->copy()->subMonths(3)->format('Y-m-d'),
                'remarks' => 'Better control compared to 3 months ago',
                'reported_at' => $now->copy()->subDays(4)->setTime(14, 15),
                'verified_by' => null,
                'verified_at' => null,
                'is_verified' => false,
                'created_at' => $now->copy()->subDays(4),
                'updated_at' => $now->copy()->subDays(4),
            ];
        }

        // Order 1 - Urine Complete (test_id=9)
        $itemId = $getItemId(1, 9);
        if ($itemId) {
            $results[] = [
                'lab_order_item_id' => $itemId,
                'result_value' => 'Normal',
                'unit' => null,
                'normal_range' => 'See report',
                'flag' => 'Normal',
                'is_abnormal' => false,
                'previous_value' => null,
                'previous_date' => null,
                'remarks' => 'No protein, glucose, or ketones detected',
                'reported_at' => $now->copy()->subDays(4)->setTime(11, 15),
                'verified_by' => null,
                'verified_at' => null,
                'is_verified' => false,
                'created_at' => $now->copy()->subDays(4),
                'updated_at' => $now->copy()->subDays(4),
            ];
        }

        // Order 2 - CBC (test_id=1)
        $itemId = $getItemId(2, 1);
        if ($itemId) {
            $results[] = [
                'lab_order_item_id' => $itemId,
                'result_value' => 'See detailed report',
                'unit' => null,
                'normal_range' => 'See individual parameters',
                'flag' => 'Normal',
                'is_abnormal' => false,
                'previous_value' => null,
                'previous_date' => null,
                'remarks' => 'All parameters within normal limits',
                'reported_at' => $now->copy()->subDays(2)->setTime(9, 30),
                'verified_by' => null,
                'verified_at' => null,
                'is_verified' => false,
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now->copy()->subDays(2),
            ];
        }

        // Order 2 - Lipid Profile (test_id=6)
        $itemId = $getItemId(2, 6);
        if ($itemId) {
            $results[] = [
                'lab_order_item_id' => $itemId,
                'result_value' => 'Total Cholesterol: 245 mg/dL',
                'unit' => 'mg/dL',
                'normal_range' => '<200',
                'flag' => 'High',
                'is_abnormal' => true,
                'previous_value' => '238',
                'previous_date' => $now->copy()->subMonths(6)->format('Y-m-d'),
                'remarks' => 'Elevated cholesterol, LDL: 165 (High), HDL: 42 (Low)',
                'reported_at' => $now->copy()->subDays(2)->setTime(11, 30),
                'verified_by' => null,
                'verified_at' => null,
                'is_verified' => false,
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now->copy()->subDays(2),
            ];
        }

        // Order 2 - LFTs (test_id=7)
        $itemId = $getItemId(2, 7);
        if ($itemId) {
            $results[] = [
                'lab_order_item_id' => $itemId,
                'result_value' => 'ALT: 32 U/L, AST: 28 U/L',
                'unit' => 'U/L',
                'normal_range' => 'ALT: 7-56, AST: 10-40',
                'flag' => 'Normal',
                'is_abnormal' => false,
                'previous_value' => null,
                'previous_date' => null,
                'remarks' => 'Liver function normal',
                'reported_at' => $now->copy()->subDays(2)->setTime(12, 45),
                'verified_by' => null,
                'verified_at' => null,
                'is_verified' => false,
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now->copy()->subDays(2),
            ];
        }

        // Order 2 - KFTs (test_id=8)
        $itemId = $getItemId(2, 8);
        if ($itemId) {
            $results[] = [
                'lab_order_item_id' => $itemId,
                'result_value' => 'Creatinine: 1.2 mg/dL, Urea: 38 mg/dL',
                'unit' => 'mg/dL',
                'normal_range' => 'Creatinine: 0.7-1.3, Urea: 15-40',
                'flag' => 'Normal',
                'is_abnormal' => false,
                'previous_value' => null,
                'previous_date' => null,
                'remarks' => 'Kidney function normal',
                'reported_at' => $now->copy()->subDays(2)->setTime(13, 15),
                'verified_by' => null,
                'verified_at' => null,
                'is_verified' => false,
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now->copy()->subDays(2),
            ];
        }

        // Order 2 - HBsAg (test_id=11)
        $itemId = $getItemId(2, 11);
        if ($itemId) {
            $results[] = [
                'lab_order_item_id' => $itemId,
                'result_value' => 'Non-Reactive',
                'unit' => null,
                'normal_range' => 'Non-Reactive',
                'flag' => 'Normal',
                'is_abnormal' => false,
                'previous_value' => null,
                'previous_date' => null,
                'remarks' => 'Negative for Hepatitis B',
                'reported_at' => $now->copy()->subDays(2)->setTime(15, 15),
                'verified_by' => null,
                'verified_at' => null,
                'is_verified' => false,
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now->copy()->subDays(2),
            ];
        }

        // Order 2 - Anti-HCV (test_id=12)
        $itemId = $getItemId(2, 12);
        if ($itemId) {
            $results[] = [
                'lab_order_item_id' => $itemId,
                'result_value' => 'Non-Reactive',
                'unit' => null,
                'normal_range' => 'Non-Reactive',
                'flag' => 'Normal',
                'is_abnormal' => false,
                'previous_value' => null,
                'previous_date' => null,
                'remarks' => 'Negative for Hepatitis C',
                'reported_at' => $now->copy()->subDays(2)->setTime(15, 45),
                'verified_by' => null,
                'verified_at' => null,
                'is_verified' => false,
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now->copy()->subDays(2),
            ];
        }

        // Order 3 - Urine Complete (test_id=9) — completed item
        $itemId = $getItemId(3, 9);
        if ($itemId) {
            $results[] = [
                'lab_order_item_id' => $itemId,
                'result_value' => 'Normal',
                'unit' => null,
                'normal_range' => 'See report',
                'flag' => 'Normal',
                'is_abnormal' => false,
                'previous_value' => null,
                'previous_date' => null,
                'remarks' => 'No abnormality detected',
                'reported_at' => $now->copy()->subDay()->setTime(16, 15),
                'verified_by' => null,
                'verified_at' => null,
                'is_verified' => false,
                'created_at' => $now->copy()->subDay(),
                'updated_at' => $now->copy()->subDay(),
            ];
        }

        // Order 4 - Emergency CBC (test_id=1)
        $itemId = $getItemId(4, 1);
        if ($itemId) {
            $results[] = [
                'lab_order_item_id' => $itemId,
                'result_value' => 'Hb: 11.2 g/dL (Low)',
                'unit' => 'g/dL',
                'normal_range' => '13.5-17.5',
                'flag' => 'Low',
                'is_abnormal' => true,
                'previous_value' => null,
                'previous_date' => null,
                'remarks' => 'Mild anemia, WBC normal, Platelets adequate',
                'reported_at' => $now->copy()->subHours(5)->setTime(4, 45),
                'verified_by' => null,
                'verified_at' => null,
                'is_verified' => false,
                'created_at' => $now->copy()->subHours(5),
                'updated_at' => $now->copy()->subHours(5),
            ];
        }

        if (empty($results)) {
            $this->command->warn('⚠️  Koi result insert nahi hua — lab_order_items check karo');
            return;
        }

        DB::table('lab_results')->insert($results);
        $this->command->info('✅ ' . count($results) . ' Lab Results seeded');
    }
}