<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// class LabSampleTypeSeeder extends Seeder
// {
//     public function run(): void
//     {
//         $sampleTypes = [
//             [
//                 'name' => 'Blood',
//                 'code' => 'SMP-BLD',
//                 'container_type' => 'Vacutainer',
//                 'color_code' => 'Red',
//                 'volume_required' => 5,
//                 'requires_fasting' => false,
//                 'collection_instructions' => 'Collect in red-top tube, allow to clot for 30 minutes',
//                 'description' => 'Whole blood sample for various tests',
//                 'is_active' => true,
//             ],
//             [
//                 'name' => 'Serum',
//                 'code' => 'SMP-SRM',
//                 'container_type' => 'Vacutainer',
//                 'color_code' => 'Yellow',
//                 'volume_required' => 3,
//                 'requires_fasting' => true,
//                 'collection_instructions' => 'Collect in gel separator tube, centrifuge after clotting',
//                 'description' => 'Serum sample for biochemistry tests',
//                 'is_active' => true,
//             ],
//             [
//                 'name' => 'Plasma',
//                 'code' => 'SMP-PLM',
//                 'container_type' => 'Vacutainer',
//                 'color_code' => 'Purple',
//                 'volume_required' => 3,
//                 'requires_fasting' => false,
//                 'collection_instructions' => 'Collect in EDTA tube, mix gently, do not allow to clot',
//                 'description' => 'Plasma sample with anticoagulant',
//                 'is_active' => true,
//             ],
//             [
//                 'name' => 'Urine',
//                 'code' => 'SMP-URN',
//                 'container_type' => 'Sterile Cup',
//                 'color_code' => null,
//                 'volume_required' => 50,
//                 'requires_fasting' => false,
//                 'collection_instructions' => 'Collect midstream urine in sterile container',
//                 'description' => 'Urine sample for analysis',
//                 'is_active' => true,
//             ],
//             [
//                 'name' => 'Stool',
//                 'code' => 'SMP-STL',
//                 'container_type' => 'Stool Container',
//                 'color_code' => null,
//                 'volume_required' => 10,
//                 'requires_fasting' => false,
//                 'collection_instructions' => 'Collect fresh stool sample in provided container',
//                 'description' => 'Stool sample for microscopy and culture',
//                 'is_active' => true,
//             ],
//             [
//                 'name' => 'Sputum',
//                 'code' => 'SMP-SPT',
//                 'container_type' => 'Sputum Cup',
//                 'color_code' => null,
//                 'volume_required' => 5,
//                 'requires_fasting' => false,
//                 'collection_instructions' => 'Collect early morning sputum after deep cough',
//                 'description' => 'Sputum for TB and bacterial culture',
//                 'is_active' => true,
//             ],
//         ];

//         foreach ($sampleTypes as $type) {
//             DB::table('lab_sample_types')->insert(array_merge($type, [
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]));
//         }

//         $this->command->info('✅ 6 Lab Sample Types seeded');
//     }
// }

// class LabTestCategorySeeder extends Seeder
// {
//     public function run(): void
//     {
//         $categories = [
//             [
//                 'name' => 'Hematology',
//                 'code' => 'CAT-001',
//                 'description' => 'Blood cell counts and related tests',
//                 'is_active' => true,
//             ],
//             [
//                 'name' => 'Biochemistry',
//                 'code' => 'CAT-002',
//                 'description' => 'Chemical analysis of blood and body fluids',
//                 'is_active' => true,
//             ],
//             [
//                 'name' => 'Microbiology',
//                 'code' => 'CAT-003',
//                 'description' => 'Culture and sensitivity tests',
//                 'is_active' => true,
//             ],
//             [
//                 'name' => 'Immunology',
//                 'code' => 'CAT-004',
//                 'description' => 'Antibody and antigen tests',
//                 'is_active' => true,
//             ],
//             [
//                 'name' => 'Clinical Pathology',
//                 'code' => 'CAT-005',
//                 'description' => 'Urine, stool, and body fluid analysis',
//                 'is_active' => true,
//             ],
//             [
//                 'name' => 'Endocrinology',
//                 'code' => 'CAT-006',
//                 'description' => 'Hormone level tests',
//                 'is_active' => true,
//             ],
//             [
//                 'name' => 'Cardiology',
//                 'code' => 'CAT-007',
//                 'description' => 'Cardiac markers and lipid profile',
//                 'is_active' => true,
//             ],
//         ];

//         foreach ($categories as $category) {
//             DB::table('lab_test_categories')->insert(array_merge($category, [
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]));
//         }

//         $this->command->info('✅ 7 Lab Test Categories seeded');
//     }
// }

class LabTestSeeder extends Seeder
{
    public function run(): void
    {
        $tests = [
            // Hematology
            [
                'name' => 'Complete Blood Count (CBC)',
                'test_code' => 'T-001',
                'category_id' => 1,
                'sample_type_id' => 3, // Plasma
                'price' => 500.00,
                'unit' => null,
                'normal_range' => 'See individual parameters',
                'normal_range_male' => null,
                'normal_range_female' => null,
                'normal_range_child' => null,
                'normal_range_elderly' => null,
                'turnaround_hours' => 2,
                'method' => 'Automated Hematology Analyzer',
                'requires_fasting' => false,
                'is_active' => true,
                'description' => 'Complete blood count with differential',
            ],
            [
                'name' => 'Hemoglobin (Hb)',
                'test_code' => 'T-002',
                'category_id' => 1,
                'sample_type_id' => 1, // Blood
                'price' => 200.00,
                'unit' => 'g/dL',
                'normal_range' => null,
                'normal_range_male' => '13.5-17.5',
                'normal_range_female' => '12.0-15.5',
                'normal_range_child' => '11.0-14.0',
                'normal_range_elderly' => '12.0-16.0',
                'turnaround_hours' => 1,
                'method' => 'Spectrophotometry',
                'requires_fasting' => false,
                'is_active' => true,
                'description' => 'Hemoglobin level measurement',
            ],

            // Biochemistry
            [
                'name' => 'Blood Sugar (Random)',
                'test_code' => 'T-003',
                'category_id' => 2,
                'sample_type_id' => 2, // Serum
                'price' => 150.00,
                'unit' => 'mg/dL',
                'normal_range' => '70-140',
                'normal_range_male' => '70-140',
                'normal_range_female' => '70-140',
                'normal_range_child' => '60-100',
                'normal_range_elderly' => '80-160',
                'turnaround_hours' => 1,
                'method' => 'Glucose Oxidase',
                'requires_fasting' => false,
                'is_active' => true,
                'description' => 'Random blood glucose level',
            ],
            [
                'name' => 'Blood Sugar (Fasting)',
                'test_code' => 'T-004',
                'category_id' => 2,
                'sample_type_id' => 2,
                'price' => 150.00,
                'unit' => 'mg/dL',
                'normal_range' => '70-100',
                'normal_range_male' => '70-100',
                'normal_range_female' => '70-100',
                'normal_range_child' => '60-100',
                'normal_range_elderly' => '70-110',
                'turnaround_hours' => 2,
                'method' => 'Glucose Oxidase',
                'requires_fasting' => true,
                'is_active' => true,
                'description' => 'Fasting blood glucose (8-10 hours fast)',
            ],
            [
                'name' => 'HbA1c (Glycated Hemoglobin)',
                'test_code' => 'T-005',
                'category_id' => 2,
                'sample_type_id' => 1,
                'price' => 1200.00,
                'unit' => '%',
                'normal_range' => '4.0-5.6',
                'normal_range_male' => '4.0-5.6',
                'normal_range_female' => '4.0-5.6',
                'normal_range_child' => null,
                'normal_range_elderly' => '4.0-6.0',
                'turnaround_hours' => 24,
                'method' => 'HPLC',
                'requires_fasting' => false,
                'is_active' => true,
                'description' => '3-month average blood sugar control',
            ],
            [
                'name' => 'Lipid Profile',
                'test_code' => 'T-006',
                'category_id' => 7, // Cardiology
                'sample_type_id' => 2,
                'price' => 1500.00,
                'unit' => 'mg/dL',
                'normal_range' => 'See individual components',
                'normal_range_male' => null,
                'normal_range_female' => null,
                'normal_range_child' => null,
                'normal_range_elderly' => null,
                'turnaround_hours' => 4,
                'method' => 'Enzymatic Colorimetry',
                'requires_fasting' => true,
                'is_active' => true,
                'description' => 'Total Cholesterol, HDL, LDL, Triglycerides',
            ],
            [
                'name' => 'Liver Function Tests (LFTs)',
                'test_code' => 'T-007',
                'category_id' => 2,
                'sample_type_id' => 2,
                'price' => 1200.00,
                'unit' => 'U/L',
                'normal_range' => 'See individual parameters',
                'normal_range_male' => null,
                'normal_range_female' => null,
                'normal_range_child' => null,
                'normal_range_elderly' => null,
                'turnaround_hours' => 6,
                'method' => 'Automated Chemistry Analyzer',
                'requires_fasting' => true,
                'is_active' => true,
                'description' => 'ALT, AST, ALP, Bilirubin, Proteins',
            ],
            [
                'name' => 'Kidney Function Tests (KFTs)',
                'test_code' => 'T-008',
                'category_id' => 2,
                'sample_type_id' => 2,
                'price' => 1000.00,
                'unit' => 'mg/dL',
                'normal_range' => 'See individual parameters',
                'normal_range_male' => null,
                'normal_range_female' => null,
                'normal_range_child' => null,
                'normal_range_elderly' => null,
                'turnaround_hours' => 6,
                'method' => 'Automated Chemistry Analyzer',
                'requires_fasting' => false,
                'is_active' => true,
                'description' => 'Urea, Creatinine, Uric Acid, Electrolytes',
            ],

            // Clinical Pathology
            [
                'name' => 'Urine Complete Examination',
                'test_code' => 'T-009',
                'category_id' => 5,
                'sample_type_id' => 4, // Urine
                'price' => 300.00,
                'unit' => null,
                'normal_range' => 'See report',
                'normal_range_male' => null,
                'normal_range_female' => null,
                'normal_range_child' => null,
                'normal_range_elderly' => null,
                'turnaround_hours' => 2,
                'method' => 'Microscopy & Dipstick',
                'requires_fasting' => false,
                'is_active' => true,
                'description' => 'Physical, chemical and microscopic examination',
            ],
            [
                'name' => 'Stool Complete Examination',
                'test_code' => 'T-010',
                'category_id' => 5,
                'sample_type_id' => 5, // Stool
                'price' => 400.00,
                'unit' => null,
                'normal_range' => 'See report',
                'normal_range_male' => null,
                'normal_range_female' => null,
                'normal_range_child' => null,
                'normal_range_elderly' => null,
                'turnaround_hours' => 3,
                'method' => 'Microscopy',
                'requires_fasting' => false,
                'is_active' => true,
                'description' => 'Physical and microscopic examination',
            ],

            // Immunology
            [
                'name' => 'Hepatitis B Surface Antigen (HBsAg)',
                'test_code' => 'T-011',
                'category_id' => 4,
                'sample_type_id' => 2,
                'price' => 800.00,
                'unit' => null,
                'normal_range' => 'Non-Reactive',
                'normal_range_male' => 'Non-Reactive',
                'normal_range_female' => 'Non-Reactive',
                'normal_range_child' => 'Non-Reactive',
                'normal_range_elderly' => 'Non-Reactive',
                'turnaround_hours' => 4,
                'method' => 'ELISA',
                'requires_fasting' => false,
                'is_active' => true,
                'description' => 'Hepatitis B screening test',
            ],
            [
                'name' => 'Anti-HCV (Hepatitis C)',
                'test_code' => 'T-012',
                'category_id' => 4,
                'sample_type_id' => 2,
                'price' => 1000.00,
                'unit' => null,
                'normal_range' => 'Non-Reactive',
                'normal_range_male' => 'Non-Reactive',
                'normal_range_female' => 'Non-Reactive',
                'normal_range_child' => 'Non-Reactive',
                'normal_range_elderly' => 'Non-Reactive',
                'turnaround_hours' => 4,
                'method' => 'ELISA',
                'requires_fasting' => false,
                'is_active' => true,
                'description' => 'Hepatitis C antibody test',
            ],
            [
                'name' => 'HIV 1 & 2 Screening',
                'test_code' => 'T-013',
                'category_id' => 4,
                'sample_type_id' => 2,
                'price' => 1200.00,
                'unit' => null,
                'normal_range' => 'Non-Reactive',
                'normal_range_male' => 'Non-Reactive',
                'normal_range_female' => 'Non-Reactive',
                'normal_range_child' => 'Non-Reactive',
                'normal_range_elderly' => 'Non-Reactive',
                'turnaround_hours' => 6,
                'method' => 'ELISA',
                'requires_fasting' => false,
                'is_active' => true,
                'description' => 'HIV antibody screening',
            ],

            // Endocrinology
            [
                'name' => 'Thyroid Profile (T3, T4, TSH)',
                'test_code' => 'T-014',
                'category_id' => 6,
                'sample_type_id' => 2,
                'price' => 1800.00,
                'unit' => null,
                'normal_range' => 'See individual parameters',
                'normal_range_male' => null,
                'normal_range_female' => null,
                'normal_range_child' => null,
                'normal_range_elderly' => null,
                'turnaround_hours' => 24,
                'method' => 'Chemiluminescence',
                'requires_fasting' => false,
                'is_active' => true,
                'description' => 'Complete thyroid function tests',
            ],

            // Microbiology
            [
                'name' => 'Urine Culture & Sensitivity',
                'test_code' => 'T-015',
                'category_id' => 3,
                'sample_type_id' => 4,
                'price' => 1500.00,
                'unit' => null,
                'normal_range' => 'No growth',
                'normal_range_male' => 'No growth',
                'normal_range_female' => 'No growth',
                'normal_range_child' => 'No growth',
                'normal_range_elderly' => 'No growth',
                'turnaround_hours' => 48,
                'method' => 'Culture',
                'requires_fasting' => false,
                'is_active' => true,
                'description' => 'Bacterial culture and antibiotic sensitivity',
            ],
        ];

        foreach ($tests as $test) {
            DB::table('lab_tests')->insert(array_merge($test, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✅ 15 Lab Tests seeded');
    }
}