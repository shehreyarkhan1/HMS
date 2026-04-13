<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LabTestCategory;
use App\Models\LabSampleType;

class LabMasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // ════════════════════════════════════════
        //  LAB TEST CATEGORIES
        // ════════════════════════════════════════
        $categories = [
            [
                'name'        => 'Hematology',
                'code'        => 'CAT-HEM',
                'description' => 'Blood cell counts, clotting studies, hemoglobin disorders',
            ],
            [
                'name'        => 'Biochemistry',
                'code'        => 'CAT-BIO',
                'description' => 'Metabolic panels, liver function, kidney function, enzymes',
            ],
            [
                'name'        => 'Microbiology',
                'code'        => 'CAT-MIC',
                'description' => 'Culture & sensitivity, bacterial and fungal identification',
            ],
            [
                'name'        => 'Immunology & Serology',
                'code'        => 'CAT-IMM',
                'description' => 'Antibody/antigen tests, hepatitis markers, HIV, typhoid',
            ],
            [
                'name'        => 'Endocrinology',
                'code'        => 'CAT-END',
                'description' => 'Hormone levels — thyroid, diabetes, adrenal, reproductive',
            ],
            [
                'name'        => 'Urine Analysis',
                'code'        => 'CAT-URN',
                'description' => 'Routine urine, microscopy, urine culture',
            ],
            [
                'name'        => 'Stool Analysis',
                'code'        => 'CAT-STL',
                'description' => 'Routine stool, occult blood, parasitology',
            ],
            [
                'name'        => 'Coagulation',
                'code'        => 'CAT-COA',
                'description' => 'PT, APTT, INR, fibrinogen, D-dimer',
            ],
            [
                'name'        => 'Tumor Markers',
                'code'        => 'CAT-TUM',
                'description' => 'PSA, CEA, CA-125, AFP, CA 19-9',
            ],
            [
                'name'        => 'Molecular / PCR',
                'code'        => 'CAT-PCR',
                'description' => 'PCR-based tests — COVID, Hepatitis viral load, TB',
            ],
            [
                'name'        => 'Histopathology',
                'code'        => 'CAT-HIS',
                'description' => 'Tissue biopsy examination',
            ],
            [
                'name'        => 'Cytology',
                'code'        => 'CAT-CYT',
                'description' => 'Pap smear, FNAC, sputum cytology',
            ],
        ];

        foreach ($categories as $cat) {
            LabTestCategory::firstOrCreate(
                ['code' => $cat['code']],
                array_merge($cat, ['is_active' => true])
            );
        }

        $this->command->info('✅  Lab Test Categories seeded (' . count($categories) . ')');

        // ════════════════════════════════════════
        //  LAB SAMPLE TYPES
        // ════════════════════════════════════════
        $sampleTypes = [
            [
                'name'                    => 'Whole Blood (EDTA)',
                'code'                    => 'SMP-BLD-EDTA',
                'container_type'          => 'EDTA Vacutainer',
                'color_code'             => 'Purple / Lavender',
                'volume_required'         => 3,
                'requires_fasting'        => false,
                'collection_instructions' => 'Collect in EDTA (purple top) tube. Mix gently by inversion 8-10 times. Do NOT shake.',
                'description'             => 'Used for CBC, blood grouping, HbA1c, ESR',
            ],
            [
                'name'                    => 'Serum (Plain)',
                'code'                    => 'SMP-SRM-PLN',
                'container_type'          => 'Plain / Red Top Vacutainer',
                'color_code'             => 'Red',
                'volume_required'         => 5,
                'requires_fasting'        => false,
                'collection_instructions' => 'Collect in plain red top tube. Allow to clot for 30 minutes. Centrifuge at 3000 rpm for 10 min.',
                'description'             => 'Used for biochemistry, serology, immunology tests',
            ],
            [
                'name'                    => 'Serum (Fasting)',
                'code'                    => 'SMP-SRM-FST',
                'container_type'          => 'Plain / Red Top Vacutainer',
                'color_code'             => 'Red',
                'volume_required'         => 5,
                'requires_fasting'        => true,
                'collection_instructions' => 'Patient must fast for 8-12 hours. Collect in plain red top tube. Centrifuge after clotting.',
                'description'             => 'Used for fasting glucose, lipid profile, LFT, RFT',
            ],
            [
                'name'                    => 'Plasma (Citrate)',
                'code'                    => 'SMP-PLS-CIT',
                'container_type'          => 'Sodium Citrate Vacutainer',
                'color_code'             => 'Light Blue',
                'volume_required'         => 3,
                'requires_fasting'        => false,
                'collection_instructions' => 'Collect in blue top citrate tube. Fill to the mark exactly (ratio 9:1). Mix gently immediately.',
                'description'             => 'Used for coagulation studies — PT, APTT, INR, D-Dimer',
            ],
            [
                'name'                    => 'Plasma (Heparin)',
                'code'                    => 'SMP-PLS-HEP',
                'container_type'          => 'Heparin Vacutainer',
                'color_code'             => 'Green',
                'volume_required'         => 4,
                'requires_fasting'        => false,
                'collection_instructions' => 'Collect in green top heparin tube. Mix by inversion 8-10 times.',
                'description'             => 'Used for emergency biochemistry, ammonia levels',
            ],
            [
                'name'                    => 'Urine (Random)',
                'code'                    => 'SMP-URN-RND',
                'container_type'          => 'Sterile Plastic Cup',
                'color_code'             => 'Yellow',
                'volume_required'         => 10,
                'requires_fasting'        => false,
                'collection_instructions' => 'Collect mid-stream urine in a clean sterile container. Label immediately.',
                'description'             => 'Used for routine urine analysis (R/E), urine dipstick',
            ],
            [
                'name'                    => 'Urine (24-hour)',
                'code'                    => 'SMP-URN-24H',
                'container_type'          => '24-hour Urine Container',
                'color_code'             => 'Yellow',
                'volume_required'         => null,
                'requires_fasting'        => false,
                'collection_instructions' => 'Discard first morning void. Collect all urine for next 24 hours. Keep refrigerated. Note total volume.',
                'description'             => 'Used for creatinine clearance, protein/creatinine ratio, cortisol',
            ],
            [
                'name'                    => 'Urine (Culture)',
                'code'                    => 'SMP-URN-CUL',
                'container_type'          => 'Sterile Boric Acid Container',
                'color_code'             => 'Yellow',
                'volume_required'         => 10,
                'requires_fasting'        => false,
                'collection_instructions' => 'Clean catch mid-stream urine in a sterile boric acid container. Send to lab within 2 hours.',
                'description'             => 'Used for urine culture & sensitivity (C/S)',
            ],
            [
                'name'                    => 'Stool',
                'code'                    => 'SMP-STL-RND',
                'container_type'          => 'Stool Container',
                'color_code'             => 'Brown',
                'volume_required'         => null,
                'requires_fasting'        => false,
                'collection_instructions' => 'Collect a pea-sized sample in the stool container. Avoid contamination with urine or water. Bring within 2 hours.',
                'description'             => 'Used for routine stool exam (R/E), occult blood, culture',
            ],
            [
                'name'                    => 'Throat Swab',
                'code'                    => 'SMP-SWB-THR',
                'container_type'          => 'Sterile Swab (Transport Medium)',
                'color_code'             => 'White',
                'volume_required'         => null,
                'requires_fasting'        => false,
                'collection_instructions' => 'Depress tongue, firmly swab both tonsils and posterior pharynx. Place swab in transport medium immediately.',
                'description'             => 'Used for throat culture, Group A Strep rapid test',
            ],
            [
                'name'                    => 'Nasal / NP Swab',
                'code'                    => 'SMP-SWB-NAS',
                'container_type'          => 'Nasopharyngeal Swab (VTM)',
                'color_code'             => 'White',
                'volume_required'         => null,
                'requires_fasting'        => false,
                'collection_instructions' => 'Insert flexible NP swab into nostril parallel to palate. Rotate and withdraw. Place in VTM immediately.',
                'description'             => 'Used for COVID-19 PCR, influenza, RSV',
            ],
            [
                'name'                    => 'CSF (Cerebrospinal Fluid)',
                'code'                    => 'SMP-CSF',
                'container_type'          => 'Sterile Tubes (3 aliquots)',
                'color_code'             => 'Clear',
                'volume_required'         => 3,
                'requires_fasting'        => false,
                'collection_instructions' => 'Collected by lumbar puncture by physician. Distribute into 3 numbered tubes. Transport immediately at room temperature.',
                'description'             => 'Used for meningitis workup — biochemistry, culture, cytology',
            ],
            [
                'name'                    => 'Tissue / Biopsy',
                'code'                    => 'SMP-TIS-BIO',
                'container_type'          => 'Formalin Container (10%)',
                'color_code'             => 'Amber',
                'volume_required'         => null,
                'requires_fasting'        => false,
                'collection_instructions' => 'Place fresh tissue in 10% neutral buffered formalin. Volume of formalin should be 10x the tissue volume. Label with site & side.',
                'description'             => 'Used for histopathology and cytology examination',
            ],
            [
                'name'                    => 'Blood (Culture)',
                'code'                    => 'SMP-BLD-CUL',
                'container_type'          => 'Blood Culture Bottles (Aerobic + Anaerobic)',
                'color_code'             => 'Orange',
                'volume_required'         => 10,
                'requires_fasting'        => false,
                'collection_instructions' => 'Strict aseptic technique. Collect before antibiotics if possible. Inject 5ml in aerobic and 5ml in anaerobic bottle.',
                'description'             => 'Used for blood culture & sensitivity (sepsis workup)',
            ],
        ];

        foreach ($sampleTypes as $st) {
            LabSampleType::firstOrCreate(
                ['code' => $st['code']],
                array_merge($st, ['is_active' => true])
            );
        }

        $this->command->info('✅  Lab Sample Types seeded (' . count($sampleTypes) . ')');
    }
}