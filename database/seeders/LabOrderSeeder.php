<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LabOrderSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $orders = [];
        $orderItems = [];
        $orderId = 1;

        // Lab Order 1: Diabetes patient - routine checkup
        $orders[] = [
            'id' => $orderId,
            'order_number' => 'LAB-00001',
            'patient_id' => 1,
            'doctor_id' => 3,
            'appointment_id' => 1,
            'order_date' => $now->copy()->subDays(5),
            'priority' => 'Routine',
            'status' => 'Completed',
            'total_amount' => 1650.00,
            'discount' => 0,
            'paid_amount' => 1650.00,
            'payment_status' => 'Paid',
            'report_delivered' => true,
            'report_delivered_at' => $now->copy()->subDays(4),
            'notes' => 'Routine diabetes monitoring',
            'created_at' => $now->copy()->subDays(5),
            'updated_at' => $now->copy()->subDays(4),
        ];

        // lab_sample_id = null (LabSampleSeeder baad mein update karega)
        $orderItems[] = [
            'lab_order_id' => $orderId,
            'lab_test_id' => 4,
            'lab_sample_id' => null,
            'price' => 150.00,
            'discount' => 0,
            'final_price' => 150.00,
            'status' => 'Completed',
            'technician_name' => 'Lab Tech Ali',
            'completed_at' => $now->copy()->subDays(4)->setTime(10, 30),
            'created_at' => $now->copy()->subDays(5),
            'updated_at' => $now->copy()->subDays(4),
        ];

        $orderItems[] = [
            'lab_order_id' => $orderId,
            'lab_test_id' => 5,
            'lab_sample_id' => null,
            'price' => 1200.00,
            'discount' => 0,
            'final_price' => 1200.00,
            'status' => 'Completed',
            'technician_name' => 'Lab Tech Ali',
            'completed_at' => $now->copy()->subDays(4)->setTime(14, 0),
            'created_at' => $now->copy()->subDays(5),
            'updated_at' => $now->copy()->subDays(4),
        ];

        $orderItems[] = [
            'lab_order_id' => $orderId,
            'lab_test_id' => 9,
            'lab_sample_id' => null,
            'price' => 300.00,
            'discount' => 0,
            'final_price' => 300.00,
            'status' => 'Completed',
            'technician_name' => 'Lab Tech Zainab',
            'completed_at' => $now->copy()->subDays(4)->setTime(11, 0),
            'created_at' => $now->copy()->subDays(5),
            'updated_at' => $now->copy()->subDays(4),
        ];

        $orderId++;

        // Lab Order 2: Cardiac patient
        $orders[] = [
            'id' => $orderId,
            'order_number' => 'LAB-00002',
            'patient_id' => 6,
            'doctor_id' => 1,
            'appointment_id' => null,
            'order_date' => $now->copy()->subDays(3),
            'priority' => 'Urgent',
            'status' => 'Completed',
            'total_amount' => 4400.00,
            'discount' => 400.00,
            'paid_amount' => 4000.00,
            'payment_status' => 'Paid',
            'report_delivered' => true,
            'report_delivered_at' => $now->copy()->subDays(2),
            'notes' => 'Pre-operative cardiac surgery workup',
            'created_at' => $now->copy()->subDays(3),
            'updated_at' => $now->copy()->subDays(2),
        ];

        $orderItems[] = ['lab_order_id' => $orderId, 'lab_test_id' => 1, 'lab_sample_id' => null, 'price' => 500.00, 'discount' => 0, 'final_price' => 500.00, 'status' => 'Completed', 'technician_name' => 'Lab Tech Hassan', 'completed_at' => $now->copy()->subDays(2)->setTime(9, 0), 'created_at' => $now->copy()->subDays(3), 'updated_at' => $now->copy()->subDays(2)];
        $orderItems[] = ['lab_order_id' => $orderId, 'lab_test_id' => 6, 'lab_sample_id' => null, 'price' => 1500.00, 'discount' => 200.00, 'final_price' => 1300.00, 'status' => 'Completed', 'technician_name' => 'Lab Tech Hassan', 'completed_at' => $now->copy()->subDays(2)->setTime(11, 0), 'created_at' => $now->copy()->subDays(3), 'updated_at' => $now->copy()->subDays(2)];
        $orderItems[] = ['lab_order_id' => $orderId, 'lab_test_id' => 7, 'lab_sample_id' => null, 'price' => 1200.00, 'discount' => 100.00, 'final_price' => 1100.00, 'status' => 'Completed', 'technician_name' => 'Lab Tech Ali', 'completed_at' => $now->copy()->subDays(2)->setTime(12, 30), 'created_at' => $now->copy()->subDays(3), 'updated_at' => $now->copy()->subDays(2)];
        $orderItems[] = ['lab_order_id' => $orderId, 'lab_test_id' => 8, 'lab_sample_id' => null, 'price' => 1000.00, 'discount' => 100.00, 'final_price' => 900.00, 'status' => 'Completed', 'technician_name' => 'Lab Tech Ali', 'completed_at' => $now->copy()->subDays(2)->setTime(13, 0), 'created_at' => $now->copy()->subDays(3), 'updated_at' => $now->copy()->subDays(2)];
        $orderItems[] = ['lab_order_id' => $orderId, 'lab_test_id' => 11, 'lab_sample_id' => null, 'price' => 800.00, 'discount' => 0, 'final_price' => 800.00, 'status' => 'Completed', 'technician_name' => 'Lab Tech Fatima', 'completed_at' => $now->copy()->subDays(2)->setTime(15, 0), 'created_at' => $now->copy()->subDays(3), 'updated_at' => $now->copy()->subDays(2)];
        $orderItems[] = ['lab_order_id' => $orderId, 'lab_test_id' => 12, 'lab_sample_id' => null, 'price' => 1000.00, 'discount' => 0, 'final_price' => 1000.00, 'status' => 'Completed', 'technician_name' => 'Lab Tech Fatima', 'completed_at' => $now->copy()->subDays(2)->setTime(15, 30), 'created_at' => $now->copy()->subDays(3), 'updated_at' => $now->copy()->subDays(2)];

        $orderId++;

        // Lab Order 3: Pediatric
        $orders[] = [
            'id' => $orderId,
            'order_number' => 'LAB-00003',
            'patient_id' => 11,
            'doctor_id' => 6,
            'appointment_id' => 5,
            'order_date' => $now->copy()->subDays(2),
            'priority' => 'Routine',
            'status' => 'Processing',
            'total_amount' => 800.00,
            'discount' => 0,
            'paid_amount' => 800.00,
            'payment_status' => 'Paid',
            'report_delivered' => false,
            'report_delivered_at' => null,
            'notes' => 'Fever investigation',
            'created_at' => $now->copy()->subDays(2),
            'updated_at' => $now,
        ];

        $orderItems[] = ['lab_order_id' => $orderId, 'lab_test_id' => 1, 'lab_sample_id' => null, 'price' => 500.00, 'discount' => 0, 'final_price' => 500.00, 'status' => 'Processing', 'technician_name' => 'Lab Tech Hassan', 'completed_at' => null, 'created_at' => $now->copy()->subDays(2), 'updated_at' => $now];
        $orderItems[] = ['lab_order_id' => $orderId, 'lab_test_id' => 9, 'lab_sample_id' => null, 'price' => 300.00, 'discount' => 0, 'final_price' => 300.00, 'status' => 'Completed', 'technician_name' => 'Lab Tech Zainab', 'completed_at' => $now->copy()->subDay()->setTime(16, 0), 'created_at' => $now->copy()->subDays(2), 'updated_at' => $now->copy()->subDay()];

        $orderId++;

        // Lab Order 4: Emergency STAT
        $orders[] = [
            'id' => $orderId,
            'order_number' => 'LAB-00004',
            'patient_id' => 16,
            'doctor_id' => 13,
            'appointment_id' => null,
            'order_date' => $now->copy()->subHours(6),
            'priority' => 'STAT',
            'status' => 'Completed',
            'total_amount' => 500.00,
            'discount' => 0,
            'paid_amount' => 0,
            'payment_status' => 'Unpaid',
            'report_delivered' => true,
            'report_delivered_at' => $now->copy()->subHours(5),
            'notes' => 'Emergency cardiac markers - STAT',
            'created_at' => $now->copy()->subHours(6),
            'updated_at' => $now->copy()->subHours(5),
        ];

        $orderItems[] = ['lab_order_id' => $orderId, 'lab_test_id' => 1, 'lab_sample_id' => null, 'price' => 500.00, 'discount' => 0, 'final_price' => 500.00, 'status' => 'Completed', 'technician_name' => 'Lab Tech Hassan (Night shift)', 'completed_at' => $now->copy()->subHours(5)->setTime(4, 30), 'created_at' => $now->copy()->subHours(6), 'updated_at' => $now->copy()->subHours(5)];

        $orderId++;

        // Lab Order 5: Sample Collected
        $orders[] = [
            'id' => $orderId,
            'order_number' => 'LAB-00005',
            'patient_id' => 10,
            'doctor_id' => 3,
            'appointment_id' => null,
            'order_date' => $now,
            'priority' => 'Routine',
            'status' => 'Sample Collected',
            'total_amount' => 2000.00,
            'discount' => 200.00,
            'paid_amount' => 1800.00,
            'payment_status' => 'Paid',
            'report_delivered' => false,
            'report_delivered_at' => null,
            'notes' => 'Lipid profile and liver function tests',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $orderItems[] = ['lab_order_id' => $orderId, 'lab_test_id' => 6, 'lab_sample_id' => null, 'price' => 1500.00, 'discount' => 150.00, 'final_price' => 1350.00, 'status' => 'Pending', 'technician_name' => null, 'completed_at' => null, 'created_at' => $now, 'updated_at' => $now];
        $orderItems[] = ['lab_order_id' => $orderId, 'lab_test_id' => 7, 'lab_sample_id' => null, 'price' => 1200.00, 'discount' => 50.00, 'final_price' => 1150.00, 'status' => 'Pending', 'technician_name' => null, 'completed_at' => null, 'created_at' => $now, 'updated_at' => $now];

        $orderId++;

        // Lab Order 6: Culture test
        $orders[] = [
            'id' => $orderId,
            'order_number' => 'LAB-00006',
            'patient_id' => 14,
            'doctor_id' => 3,
            'appointment_id' => null,
            'order_date' => $now->copy()->subDay(),
            'priority' => 'Routine',
            'status' => 'Processing',
            'total_amount' => 1500.00,
            'discount' => 0,
            'paid_amount' => 1500.00,
            'payment_status' => 'Paid',
            'report_delivered' => false,
            'report_delivered_at' => null,
            'notes' => 'UTI suspected, culture ordered',
            'created_at' => $now->copy()->subDay(),
            'updated_at' => $now,
        ];

        $orderItems[] = ['lab_order_id' => $orderId, 'lab_test_id' => 15, 'lab_sample_id' => null, 'price' => 1500.00, 'discount' => 0, 'final_price' => 1500.00, 'status' => 'Processing', 'technician_name' => 'Lab Tech Fatima', 'completed_at' => null, 'created_at' => $now->copy()->subDay(), 'updated_at' => $now];

        DB::table('lab_orders')->insert($orders);
        DB::table('lab_order_items')->insert($orderItems);

        $this->command->info('✅ ' . count($orders) . ' Lab Orders seeded with ' . count($orderItems) . ' items');
    }
}