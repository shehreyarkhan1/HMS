<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabSampleTypeSeeder extends Seeder
{
    public function run()
    {
        DB::table('lab_sample_types')->truncate();

        DB::table('lab_sample_types')->insert([
            [
                'name' => 'Blood',
                'code' => 'SMP-BLD',
                'container_type' => 'Vacutainer',
                'color_code' => 'Red',
                'volume_required' => 5,
                'requires_fasting' => true,
                'collection_instructions' => 'Collect after 8-12 hours fasting.',
                'description' => 'Used for blood tests like CBC, glucose.',
                'is_active' => true,
            ],
            [
                'name' => 'Urine',
                'code' => 'SMP-URN',
                'container_type' => 'Cup',
                'color_code' => 'Yellow',
                'volume_required' => 10,
                'requires_fasting' => false,
                'collection_instructions' => 'Collect midstream urine sample.',
                'description' => 'Used for urine routine examination.',
                'is_active' => true,
            ],
            [
                'name' => 'Stool',
                'code' => 'SMP-STL',
                'container_type' => 'Container',
                'color_code' => 'Brown',
                'volume_required' => null,
                'requires_fasting' => false,
                'collection_instructions' => 'Collect fresh stool sample.',
                'description' => 'Used for stool analysis.',
                'is_active' => true,
            ],
            [
                'name' => 'Saliva',
                'code' => 'SMP-SLV',
                'container_type' => 'Tube',
                'color_code' => 'Transparent',
                'volume_required' => 2,
                'requires_fasting' => false,
                'collection_instructions' => 'Do not eat 30 minutes before sample.',
                'description' => 'Used for hormonal and DNA tests.',
                'is_active' => true,
            ],
            [
                'name' => 'Swab',
                'code' => 'SMP-SWB',
                'container_type' => 'Swab Stick',
                'color_code' => 'Blue',
                'volume_required' => null,
                'requires_fasting' => false,
                'collection_instructions' => 'Use sterile swab for collection.',
                'description' => 'Used for throat/nasal testing.',
                'is_active' => true,
            ],
        ]);
    }
}