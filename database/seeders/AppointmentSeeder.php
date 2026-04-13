<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $appointments = [];

        // Past appointments (Completed)
        for ($i = 1; $i <= 15; $i++) {
            $daysAgo = rand(1, 30);
            $appointmentDate = $now->copy()->subDays($daysAgo);
            
            $appointments[] = [
                'patient_id' => rand(1, 20),
                'doctor_id' => rand(1, 15),
                'appointment_date' => $appointmentDate->format('Y-m-d'),
                'appointment_time' => sprintf('%02d:%02d:00', rand(9, 16), [0, 15, 30, 45][rand(0, 3)]),
                'duration_minutes' => 15,
                'token_number' => $i,
                'type' => ['OPD', 'Follow-up'][rand(0, 1)],
                'status' => 'Completed',
                'reason' => ['Fever', 'Cough', 'Chest Pain', 'Headache', 'Back Pain'][rand(0, 4)],
                'notes' => 'Patient consulted, prescribed medicines',
                'consulted_at' => $appointmentDate->addMinutes(rand(0, 30)),
                'follow_up_date' => $appointmentDate->copy()->addDays(7)->format('Y-m-d'),
                'cancelled_by' => null,
                'cancellation_reason' => null,
                'created_at' => $appointmentDate->copy()->subDays(rand(1, 3)),
                'updated_at' => $now,
            ];
        }

        // Today's appointments (Mix of statuses)
        $today = $now->copy();
        
        // Morning appointments
        $appointments[] = [
            'patient_id' => 1,
            'doctor_id' => 3, // Dr. Muhammad Tariq - General Medicine
            'appointment_date' => $today->format('Y-m-d'),
            'appointment_time' => '09:00:00',
            'duration_minutes' => 15,
            'token_number' => 1,
            'type' => 'OPD',
            'status' => 'Completed',
            'reason' => 'Diabetes follow-up',
            'notes' => 'Blood sugar controlled, continue current medication',
            'consulted_at' => $today->copy()->setTime(9, 5),
            'follow_up_date' => $today->copy()->addMonth()->format('Y-m-d'),
            'cancelled_by' => null,
            'cancellation_reason' => null,
            'created_at' => $today->copy()->subDays(2),
            'updated_at' => $now,
        ];

        $appointments[] = [
            'patient_id' => 3,
            'doctor_id' => 1, // Dr. Ahmed Hassan - Cardiology
            'appointment_date' => $today->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'duration_minutes' => 20,
            'token_number' => 2,
            'type' => 'Follow-up',
            'status' => 'In-Progress',
            'reason' => 'Cardiac checkup',
            'notes' => 'ECG scheduled',
            'consulted_at' => $today->copy()->setTime(10, 10),
            'follow_up_date' => null,
            'cancelled_by' => null,
            'cancellation_reason' => null,
            'created_at' => $today->copy()->subWeek(),
            'updated_at' => $now,
        ];

        $appointments[] = [
            'patient_id' => 5,
            'doctor_id' => 5, // Dr. Zainab Ahmed - Pediatrics
            'appointment_date' => $today->format('Y-m-d'),
            'appointment_time' => '11:00:00',
            'duration_minutes' => 15,
            'token_number' => 3,
            'type' => 'OPD',
            'status' => 'Confirmed',
            'reason' => 'Asthma checkup',
            'notes' => 'Patient waiting',
            'consulted_at' => null,
            'follow_up_date' => null,
            'cancelled_by' => null,
            'cancellation_reason' => null,
            'created_at' => $today->copy()->subDays(3),
            'updated_at' => $now,
        ];

        // Afternoon appointments
        $appointments[] = [
            'patient_id' => 9,
            'doctor_id' => 11, // Dr. Saad Mahmood - Neurology
            'appointment_date' => $today->format('Y-m-d'),
            'appointment_time' => '14:00:00',
            'duration_minutes' => 20,
            'token_number' => 4,
            'type' => 'OPD',
            'status' => 'Scheduled',
            'reason' => 'Migraine consultation',
            'notes' => null,
            'consulted_at' => null,
            'follow_up_date' => null,
            'cancelled_by' => null,
            'cancellation_reason' => null,
            'created_at' => $today->copy()->subDays(1),
            'updated_at' => $now,
        ];

        $appointments[] = [
            'patient_id' => 12,
            'doctor_id' => 10, // Dr. Usman Ali - ENT
            'appointment_date' => $today->format('Y-m-d'),
            'appointment_time' => '15:30:00',
            'duration_minutes' => 15,
            'token_number' => 5,
            'type' => 'OPD',
            'status' => 'Cancelled',
            'reason' => 'Ear infection',
            'notes' => null,
            'consulted_at' => null,
            'follow_up_date' => null,
            'cancelled_by' => 'Patient',
            'cancellation_reason' => 'Patient feeling better, not coming',
            'created_at' => $today->copy()->subDays(2),
            'updated_at' => $today->copy()->setTime(14, 0),
        ];

        // Tomorrow's appointments
        $tomorrow = $now->copy()->addDay();
        
        $appointments[] = [
            'patient_id' => 2,
            'doctor_id' => 7, // Dr. Sana Farooq - Gynecology
            'appointment_date' => $tomorrow->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'duration_minutes' => 20,
            'token_number' => 1,
            'type' => 'OPD',
            'status' => 'Scheduled',
            'reason' => 'Prenatal checkup',
            'notes' => '2nd trimester checkup',
            'consulted_at' => null,
            'follow_up_date' => null,
            'cancelled_by' => null,
            'cancellation_reason' => null,
            'created_at' => $now->copy()->subDays(3),
            'updated_at' => $now,
        ];

        $appointments[] = [
            'patient_id' => 4,
            'doctor_id' => 9, // Dr. Hina Javed - Dermatology
            'appointment_date' => $tomorrow->format('Y-m-d'),
            'appointment_time' => '16:00:00',
            'duration_minutes' => 15,
            'token_number' => 2,
            'type' => 'Follow-up',
            'status' => 'Scheduled',
            'reason' => 'Skin allergy follow-up',
            'notes' => 'Check response to antihistamines',
            'consulted_at' => null,
            'follow_up_date' => null,
            'cancelled_by' => null,
            'cancellation_reason' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // Next week appointments
        $nextWeek = $now->copy()->addWeek();
        
        for ($i = 0; $i < 10; $i++) {
            $appointmentDate = $nextWeek->copy()->addDays($i % 5);
            
            $appointments[] = [
                'patient_id' => rand(1, 20),
                'doctor_id' => rand(1, 15),
                'appointment_date' => $appointmentDate->format('Y-m-d'),
                'appointment_time' => sprintf('%02d:%02d:00', rand(9, 16), [0, 15, 30, 45][rand(0, 3)]),
                'duration_minutes' => 15,
                'token_number' => $i + 1,
                'type' => ['OPD', 'Follow-up'][rand(0, 1)],
                'status' => 'Scheduled',
                'reason' => ['General Checkup', 'Follow-up Visit', 'Lab Report Review'][rand(0, 2)],
                'notes' => null,
                'consulted_at' => null,
                'follow_up_date' => null,
                'cancelled_by' => null,
                'cancellation_reason' => null,
                'created_at' => $now->copy()->subDays(rand(1, 5)),
                'updated_at' => $now,
            ];
        }

        // Emergency appointments
        $appointments[] = [
            'patient_id' => 16,
            'doctor_id' => 13, // Dr. Kashif Raza - Emergency
            'appointment_date' => $today->format('Y-m-d'),
            'appointment_time' => '03:30:00',
            'duration_minutes' => 30,
            'token_number' => null,
            'type' => 'Emergency',
            'status' => 'Completed',
            'reason' => 'Severe chest pain',
            'notes' => 'Cardiac arrest suspected, ECG done, admitted to ICU',
            'consulted_at' => $today->copy()->setTime(3, 35),
            'follow_up_date' => null,
            'cancelled_by' => null,
            'cancellation_reason' => null,
            'created_at' => $today->copy()->setTime(3, 30),
            'updated_at' => $today->copy()->setTime(5, 0),
        ];

        // No-show appointment
        $yesterday = $now->copy()->subDay();
        $appointments[] = [
            'patient_id' => 14,
            'doctor_id' => 3,
            'appointment_date' => $yesterday->format('Y-m-d'),
            'appointment_time' => '11:00:00',
            'duration_minutes' => 15,
            'token_number' => 8,
            'type' => 'OPD',
            'status' => 'No-show',
            'reason' => 'Diabetes checkup',
            'notes' => 'Patient did not arrive',
            'consulted_at' => null,
            'follow_up_date' => null,
            'cancelled_by' => null,
            'cancellation_reason' => null,
            'created_at' => $yesterday->copy()->subDays(2),
            'updated_at' => $yesterday->copy()->setTime(12, 0),
        ];

        DB::table('appointments')->insert($appointments);
        $this->command->info('✅ ' . count($appointments) . ' Appointments seeded successfully');
    }
}