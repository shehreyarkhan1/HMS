<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Show the settings page for a given group.
     * Default group: general
     */
    public function index(string $group = 'general')
    {
        $validGroups = ['general', 'billing', 'patient', 'lab', 'pharmacy', 'hr'];

        if (! in_array($group, $validGroups)) {
            $group = 'general';
        }

        $settings = Setting::byGroup($group);

        $tabs = [
            'general'  => ['icon' => 'bi-building-fill-add', 'label' => 'General'],
            'billing'  => ['icon' => 'bi-receipt',           'label' => 'Billing'],
            'patient'  => ['icon' => 'bi-person-heart',      'label' => 'Patient'],
            'lab'      => ['icon' => 'bi-eyedropper',        'label' => 'Laboratory'],
            'pharmacy' => ['icon' => 'bi-capsule',           'label' => 'Pharmacy'],
            'hr'       => ['icon' => 'bi-people-fill',       'label' => 'HR & Payroll'],
        ];

        return view('setting.setting_index', compact('settings', 'group', 'tabs'));
    }

    /**
     * Save settings for a given group.
     */
    public function update(Request $request, string $group = 'general')
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            if ($key === 'hospital_logo' && $request->hasFile('hospital_logo')) {
                continue;
            }
            Setting::set($key, $value ?? '');
        }

        // Handle logo file upload
        if ($request->hasFile('hospital_logo')) {
            $request->validate([
                'hospital_logo' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
            ]);

            $oldLogo = Setting::get('hospital_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('hospital_logo')->store('settings', 'public');
            Setting::set('hospital_logo', $path);
        }

        Setting::clearCache();

        return redirect()
            ->route('settings.index', ['group' => $group])
            ->with('success', 'Settings saved successfully.');
    }
}
