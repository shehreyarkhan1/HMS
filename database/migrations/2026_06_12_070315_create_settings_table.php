<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('general'); // general, billing, lab, pharmacy, hr
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, number, boolean, image, select
            $table->string('label');                  // Human-readable label for UI
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // ── Default settings seed ──────────────────────────────────────
        $settings = [

            // General
            ['group' => 'general', 'key' => 'hospital_name',    'value' => 'Medicare Hospital',          'type' => 'text',    'label' => 'Hospital Name'],
            ['group' => 'general', 'key' => 'hospital_slogan',  'value' => 'Caring for a Better Tomorrow', 'type' => 'text',    'label' => 'Slogan / Tagline'],
            ['group' => 'general', 'key' => 'hospital_address', 'value' => 'Peshawar, Khyber Pakhtunkhwa, Pakistan', 'type' => 'text', 'label' => 'Address'],
            ['group' => 'general', 'key' => 'hospital_phone',   'value' => '',                            'type' => 'text',    'label' => 'Phone Number'],
            ['group' => 'general', 'key' => 'hospital_email',   'value' => '',                            'type' => 'text',    'label' => 'Email Address'],
            ['group' => 'general', 'key' => 'hospital_website', 'value' => '',                            'type' => 'text',    'label' => 'Website URL'],
            ['group' => 'general', 'key' => 'hospital_logo',    'value' => '',                            'type' => 'image',   'label' => 'Hospital Logo'],
            ['group' => 'general', 'key' => 'timezone',         'value' => 'Asia/Karachi',                'type' => 'text',    'label' => 'Timezone'],
            ['group' => 'general', 'key' => 'date_format',      'value' => 'd/m/Y',                       'type' => 'text',    'label' => 'Date Format'],

            // Billing
            ['group' => 'billing', 'key' => 'currency',         'value' => 'PKR',                         'type' => 'text',    'label' => 'Currency Code'],
            ['group' => 'billing', 'key' => 'currency_symbol',  'value' => '₨',                           'type' => 'text',    'label' => 'Currency Symbol'],
            ['group' => 'billing', 'key' => 'bill_prefix',      'value' => 'BILL-',                       'type' => 'text',    'label' => 'Bill Number Prefix'],
            ['group' => 'billing', 'key' => 'tax_percentage',   'value' => '0',                           'type' => 'number',  'label' => 'Tax Percentage (%)'],
            ['group' => 'billing', 'key' => 'bill_footer_note', 'value' => 'Thank you for choosing Medicare Hospital.', 'type' => 'text', 'label' => 'Bill Footer Note'],

            // Patient
            ['group' => 'patient', 'key' => 'mrn_prefix',       'value' => 'MRN-',                        'type' => 'text',    'label' => 'MRN Prefix'],

            // Lab
            ['group' => 'lab',     'key' => 'lab_report_footer', 'value' => 'Results verified by licensed pathologist.', 'type' => 'text', 'label' => 'Lab Report Footer'],
            ['group' => 'lab',     'key' => 'lab_name',         'value' => 'Medicare Laboratory',         'type' => 'text',    'label' => 'Laboratory Name'],

            // Pharmacy
            ['group' => 'pharmacy', 'key' => 'low_stock_threshold', 'value' => '10',                        'type' => 'number',  'label' => 'Low Stock Alert Threshold'],
            ['group' => 'pharmacy', 'key' => 'expiry_alert_days', 'value' => '30',                         'type' => 'number',  'label' => 'Expiry Alert (days before)'],

            // HR
            ['group' => 'hr',      'key' => 'working_hours_per_day', 'value' => '8',                      'type' => 'number',  'label' => 'Working Hours Per Day'],
            ['group' => 'hr',      'key' => 'payroll_cycle',     'value' => 'monthly',                    'type' => 'text',    'label' => 'Payroll Cycle'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insert(array_merge($setting, [
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
