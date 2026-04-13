<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\DoctorSeeder;
use Database\Seeders\LabSampleTypeSeeder;
use Database\Seeders\LabTestCategorySeeder;
use Database\Seeders\MedicineSeeder;
use Database\Seeders\WardSeeder;
use Database\Seeders\LabTestSeeder;
use Database\Seeders\MedicineBatchSeeder;
use Database\Seeders\BedSeeder;
use Database\Seeders\PatientSeeder;
use Database\Seeders\AppointmentSeeder;
use Database\Seeders\PrescriptionSeeder;
use Database\Seeders\DispensingSeeder;
use Database\Seeders\LabOrderSeeder;
use Database\Seeders\LabSampleSeeder;
use Database\Seeders\LabResultSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
                // STEP 1: Master Tables
            DoctorSeeder::class,
            PatientSeeder::class,
            LabTestCategorySeeder::class,
            LabSampleTypeSeeder::class,
            MedicineSeeder::class,
            WardSeeder::class,

                // STEP 2: Dependent Master
            LabTestSeeder::class,
            MedicineBatchSeeder::class,
            BedSeeder::class,

                // STEP 3: Transactions
            AppointmentSeeder::class,
            LabOrderSeeder::class,      // ← pehle orders (lab_sample_id = null)

                // STEP 4: Lab Flow
            LabSampleSeeder::class,     // ← samples seed + order_items update
            LabResultSeeder::class,

                // STEP 5: Pharmacy
            PrescriptionSeeder::class,
            DispensingSeeder::class,

        ]);

        $this->command->info('🎉 Hospital Management System seeded successfully!');
    }

    // public function run()
    // {
    //     $this->call(LabMasterDataSeeder::class);
    // }
    // public function run(): void
    // {
    //     // User::factory(10)->create();

    //     User::factory()->create([
    //         'name' => 'Test User',
    //         'email' => 'test@example.com',
    //     ]);
    // }
}
