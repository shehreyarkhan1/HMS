<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabTestCategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('lab_test_categories')->truncate();

        DB::table('lab_test_categories')->insert([
            [
                'name' => 'Hematology',
                'code' => 'CAT-HEM',
                'description' => 'Blood related tests like CBC, ESR.',
                'is_active' => true,
            ],
            [
                'name' => 'Biochemistry',
                'code' => 'CAT-BIO',
                'description' => 'Tests related to chemical processes in body.',
                'is_active' => true,
            ],
            [
                'name' => 'Microbiology',
                'code' => 'CAT-MIC',
                'description' => 'Bacteria, virus and infection testing.',
                'is_active' => true,
            ],
            [
                'name' => 'Immunology',
                'code' => 'CAT-IMM',
                'description' => 'Immune system related tests.',
                'is_active' => true,
            ],
            [
                'name' => 'Serology',
                'code' => 'CAT-SER',
                'description' => 'Serum-based diagnostic testing.',
                'is_active' => true,
            ],
            // New category 6 - Endocrinology
            [
                'name' => 'Endocrinology',
                'code' => 'CAT-END',
                'description' => 'Hormone-related tests like thyroid, cortisol.',
                'is_active' => true,
            ],
            // New category 7 - Cardiology
            [
                'name' => 'Cardiology',
                'code' => 'CAT-CAR',
                'description' => 'Heart-related tests like lipid profile.',
                'is_active' => true,
            ],
        ]);
    }
}