<?php

namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LabTest;
use App\Models\LabTestCategory;
use App\Models\LabSampleType;
class LabSampleTypeController extends Controller
{
    public function index()
    {
        $sampleTypes = LabSampleType::withCount('tests', 'samples')
            ->orderBy('name')
            ->paginate(20);

        $stats = [
            'total' => LabSampleType::count(),
            'active' => LabSampleType::where('is_active', true)->count(),
            'fasting' => LabSampleType::where('requires_fasting', true)->count(),
        ];

        return view('labcategory.lab_sample_type_index', compact('sampleTypes', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:lab_sample_types,name',
            'container_type' => 'nullable|string|max:100',
            'color_code' => 'nullable|string|max:50',
            'volume_required' => 'nullable|integer|min:1',
            'requires_fasting' => 'boolean',
            'collection_instructions' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:500',
        ]);

        LabSampleType::create([
            'name' => $request->name,
            'container_type' => $request->container_type,
            'color_code' => $request->color_code,
            'volume_required' => $request->volume_required,
            'requires_fasting' => $request->boolean('requires_fasting'),
            'collection_instructions' => $request->collection_instructions,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return back()->with('success', 'Sample type "' . $request->name . '" added successfully.');
    }

    public function update(Request $request, LabSampleType $labSampleType)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:lab_sample_types,name,' . $labSampleType->id,
            'container_type' => 'nullable|string|max:100',
            'color_code' => 'nullable|string|max:50',
            'volume_required' => 'nullable|integer|min:1',
            'requires_fasting' => 'boolean',
            'collection_instructions' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $labSampleType->update([
            'name' => $request->name,
            'container_type' => $request->container_type,
            'color_code' => $request->color_code,
            'volume_required' => $request->volume_required,
            'requires_fasting' => $request->boolean('requires_fasting'),
            'collection_instructions' => $request->collection_instructions,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Sample type updated successfully.');
    }

    public function destroy(LabSampleType $labSampleType)
    {
        if ($labSampleType->tests()->exists()) {
            return back()->with('error', 'Cannot delete — ' . $labSampleType->tests()->count() . ' test(s) use this sample type.');
        }

        if ($labSampleType->samples()->exists()) {
            return back()->with('error', 'Cannot delete — this sample type has collected samples in records.');
        }

        $name = $labSampleType->name;
        $labSampleType->delete();

        return back()->with('success', 'Sample type "' . $name . '" deleted.');
    }

    public function toggleActive(LabSampleType $labSampleType)
    {
        $labSampleType->update(['is_active' => !$labSampleType->is_active]);

        return back()->with('success', 'Sample type ' . ($labSampleType->is_active ? 'activated' : 'deactivated') . '.');
    }
}
