<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $prescriptions = [];
        $prescriptionItems = [];
        $prescriptionId = 1;

        // Prescription 1: Diabetes patient
        $prescriptions[] = [
            'id' => $prescriptionId,
            'prescription_number' => 'RX-00001',
            'patient_id' => 1, // Ali Raza
            'doctor_id' => 3, // Dr. Muhammad Tariq
            'appointment_id' => 1,
            'status' => 'Dispensed',
            'prescribed_date' => $now->copy()->subDays(5)->format('Y-m-d'),
            'valid_until' => $now->copy()->addDays(25)->format('Y-m-d'),
            'diagnosis' => 'Type 2 Diabetes Mellitus, controlled',
            'notes' => 'Continue medication, regular exercise advised',
            'created_at' => $now->copy()->subDays(5),
            'updated_at' => $now->copy()->subDays(4),
        ];

        $prescriptionItems[] = [
            'prescription_id' => $prescriptionId,
            'medicine_id' => 7, // Glucophage
            'dosage' => '500mg',
            'frequency' => '1-0-1 (Morning & Night)',
            'duration_days' => 30,
            'quantity' => 60,
            'dispensed_qty' => 60,
            'instructions' => 'Take after meals',
            'created_at' => $now->copy()->subDays(5),
            'updated_at' => $now->copy()->subDays(4),
        ];

        $prescriptionId++;

        // Prescription 2: Cardiac patient
        $prescriptions[] = [
            'id' => $prescriptionId,
            'prescription_number' => 'RX-00002',
            'patient_id' => 3, // Hassan Ali
            'doctor_id' => 1, // Dr. Ahmed Hassan - Cardiology
            'appointment_id' => 2,
            'status' => 'Dispensed',
            'prescribed_date' => $now->copy()->subDays(3)->format('Y-m-d'),
            'valid_until' => $now->copy()->addDays(27)->format('Y-m-d'),
            'diagnosis' => 'Hypertension, Grade 2',
            'notes' => 'Monitor BP daily, reduce salt intake',
            'created_at' => $now->copy()->subDays(3),
            'updated_at' => $now->copy()->subDays(2),
        ];

        $prescriptionItems[] = [
            'prescription_id' => $prescriptionId,
            'medicine_id' => 9, // Concor
            'dosage' => '5mg',
            'frequency' => '1-0-0 (Morning)',
            'duration_days' => 30,
            'quantity' => 30,
            'dispensed_qty' => 30,
            'instructions' => 'Take before breakfast',
            'created_at' => $now->copy()->subDays(3),
            'updated_at' => $now->copy()->subDays(2),
        ];

        $prescriptionItems[] = [
            'prescription_id' => $prescriptionId,
            'medicine_id' => 10, // Norvasc
            'dosage' => '5mg',
            'frequency' => '0-0-1 (Night)',
            'duration_days' => 30,
            'quantity' => 30,
            'dispensed_qty' => 30,
            'instructions' => 'Take after dinner',
            'created_at' => $now->copy()->subDays(3),
            'updated_at' => $now->copy()->subDays(2),
        ];

        $prescriptionId++;

        // Prescription 3: Pediatric patient (Asthma)
        $prescriptions[] = [
            'id' => $prescriptionId,
            'prescription_number' => 'RX-00003',
            'patient_id' => 5, // Zain Abbas
            'doctor_id' => 5, // Dr. Zainab Ahmed - Pediatrics
            'appointment_id' => null,
            'status' => 'Partial',
            'prescribed_date' => $now->format('Y-m-d'),
            'valid_until' => $now->copy()->addDays(30)->format('Y-m-d'),
            'diagnosis' => 'Bronchial Asthma, Mild Persistent',
            'notes' => 'Avoid cold weather exposure, use inhaler as needed',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $prescriptionItems[] = [
            'prescription_id' => $prescriptionId,
            'medicine_id' => 24, // Ventolin Inhaler
            'dosage' => '2 puffs',
            'frequency' => 'SOS (When needed)',
            'duration_days' => 30,
            'quantity' => 1,
            'dispensed_qty' => 1,
            'instructions' => 'Use during breathing difficulty',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $prescriptionItems[] = [
            'prescription_id' => $prescriptionId,
            'medicine_id' => 18, // Calpol Syrup
            'dosage' => '5ml',
            'frequency' => 'TDS (If fever)',
            'duration_days' => 5,
            'quantity' => 1,
            'dispensed_qty' => 0,
            'instructions' => 'Give if temperature exceeds 100°F',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $prescriptionId++;

        // Prescription 4: Skin infection
        $prescriptions[] = [
            'id' => $prescriptionId,
            'prescription_number' => 'RX-00004',
            'patient_id' => 4, // Ayesha Bibi
            'doctor_id' => 9, // Dr. Hina Javed - Dermatology
            'appointment_id' => 4,
            'status' => 'Pending',
            'prescribed_date' => $now->format('Y-m-d'),
            'valid_until' => $now->copy()->addDays(15)->format('Y-m-d'),
            'diagnosis' => 'Allergic Dermatitis',
            'notes' => 'Avoid contact with allergens, apply cream twice daily',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $prescriptionItems[] = [
            'prescription_id' => $prescriptionId,
            'medicine_id' => 14, // Cetrine
            'dosage' => '10mg',
            'frequency' => '0-0-1 (Night)',
            'duration_days' => 10,
            'quantity' => 10,
            'dispensed_qty' => 0,
            'instructions' => 'Take at bedtime',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $prescriptionId++;

        // Prescription 5: Bacterial infection
        $prescriptions[] = [
            'id' => $prescriptionId,
            'prescription_number' => 'RX-00005',
            'patient_id' => 11, // Sara Khan
            'doctor_id' => 6, // Dr. Bilal Khan - Pediatrics
            'appointment_id' => 5,
            'status' => 'Dispensed',
            'prescribed_date' => $now->copy()->subDays(2)->format('Y-m-d'),
            'valid_until' => $now->copy()->addDays(5)->format('Y-m-d'),
            'diagnosis' => 'Upper Respiratory Tract Infection',
            'notes' => 'Complete antibiotic course, plenty of fluids',
            'created_at' => $now->copy()->subDays(2),
            'updated_at' => $now->copy()->subDay(),
        ];

        $prescriptionItems[] = [
            'prescription_id' => $prescriptionId,
            'medicine_id' => 4, // Augmentin
            'dosage' => '625mg',
            'frequency' => '1-0-1 (Twice daily)',
            'duration_days' => 7,
            'quantity' => 14,
            'dispensed_qty' => 14,
            'instructions' => 'Take after meals',
            'created_at' => $now->copy()->subDays(2),
            'updated_at' => $now->copy()->subDay(),
        ];

        $prescriptionItems[] = [
            'prescription_id' => $prescriptionId,
            'medicine_id' => 13, // Rexcof Syrup
            'dosage' => '5ml',
            'frequency' => 'TDS (Three times)',
            'duration_days' => 7,
            'quantity' => 1,
            'dispensed_qty' => 1,
            'instructions' => 'Take after meals',
            'created_at' => $now->copy()->subDays(2),
            'updated_at' => $now->copy()->subDay(),
        ];

        $prescriptionId++;

        // Prescription 6: Gastric issues
        $prescriptions[] = [
            'id' => $prescriptionId,
            'prescription_number' => 'RX-00006',
            'patient_id' => 10, // Imran Khalid
            'doctor_id' => 3, // Dr. Muhammad Tariq
            'appointment_id' => null,
            'status' => 'Dispensed',
            'prescribed_date' => $now->copy()->subWeek()->format('Y-m-d'),
            'valid_until' => $now->copy()->addWeeks(3)->format('Y-m-d'),
            'diagnosis' => 'Gastroesophageal Reflux Disease (GERD)',
            'notes' => 'Avoid spicy food, eat small frequent meals',
            'created_at' => $now->copy()->subWeek(),
            'updated_at' => $now->copy()->subWeek()->addDay(),
        ];

        $prescriptionItems[] = [
            'prescription_id' => $prescriptionId,
            'medicine_id' => 11, // Risek
            'dosage' => '20mg',
            'frequency' => '1-0-0 (Morning)',
            'duration_days' => 30,
            'quantity' => 30,
            'dispensed_qty' => 30,
            'instructions' => 'Take before breakfast on empty stomach',
            'created_at' => $now->copy()->subWeek(),
            'updated_at' => $now->copy()->subWeek()->addDay(),
        ];

        $prescriptionItems[] = [
            'prescription_id' => $prescriptionId,
            'medicine_id' => 12, // Motilium
            'dosage' => '10mg',
            'frequency' => '1-0-1-1 (Before meals)',
            'duration_days' => 15,
            'quantity' => 45,
            'dispensed_qty' => 45,
            'instructions' => 'Take 30 minutes before meals',
            'created_at' => $now->copy()->subWeek(),
            'updated_at' => $now->copy()->subWeek()->addDay(),
        ];

        $prescriptionId++;

        // Prescription 7: Pain management
        $prescriptions[] = [
            'id' => $prescriptionId,
            'prescription_number' => 'RX-00007',
            'patient_id' => 8, // Bilal Ahmed (Fracture patient)
            'doctor_id' => 8, // Dr. Imran Rasheed - Orthopedics
            'appointment_id' => null,
            'status' => 'Dispensed',
            'prescribed_date' => $now->format('Y-m-d'),
            'valid_until' => $now->copy()->addDays(10)->format('Y-m-d'),
            'diagnosis' => 'Femur fracture, post-operative care',
            'notes' => 'Bed rest, pain management, physiotherapy after 2 weeks',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $prescriptionItems[] = [
            'prescription_id' => $prescriptionId,
            'medicine_id' => 2, // Brufen
            'dosage' => '400mg',
            'frequency' => '1-1-1 (TDS)',
            'duration_days' => 10,
            'quantity' => 30,
            'dispensed_qty' => 30,
            'instructions' => 'Take after meals for pain',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $prescriptionItems[] = [
            'prescription_id' => $prescriptionId,
            'medicine_id' => 4, // Augmentin (post-op antibiotic)
            'dosage' => '625mg',
            'frequency' => '1-0-1',
            'duration_days' => 7,
            'quantity' => 14,
            'dispensed_qty' => 14,
            'instructions' => 'Complete the course',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // Insert all data
        DB::table('prescriptions')->insert($prescriptions);
        DB::table('prescription_items')->insert($prescriptionItems);

        $this->command->info('✅ ' . count($prescriptions) . ' Prescriptions seeded with ' . count($prescriptionItems) . ' items');
    }
}