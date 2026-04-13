<?php

namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LabTest;
use App\Models\LabTestCategory;
use App\Models\LabSampleType;

class LabTestCategoryController extends Controller
{
    public function index()
    {
        $categories = LabTestCategory::withCount('tests')
            ->orderBy('name')
            ->paginate(20);

        $stats = [
            'total' => LabTestCategory::count(),
            'active' => LabTestCategory::where('is_active', true)->count(),
            'inactive' => LabTestCategory::where('is_active', false)->count(),
        ];

        return view('labcategory.lab_category_index', compact('categories', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:lab_test_categories,name',
            'description' => 'nullable|string|max:500',
        ]);

        LabTestCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return back()->with('success', 'Category "' . $request->name . '" added successfully.');
    }

    public function update(Request $request, LabTestCategory $labTestCategory)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:lab_test_categories,name,' . $labTestCategory->id,
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $labTestCategory->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(LabTestCategory $labTestCategory)
    {
        if ($labTestCategory->tests()->exists()) {
            return back()->with('error', 'Cannot delete — this category has ' . $labTestCategory->tests()->count() . ' test(s) linked to it.');
        }

        $name = $labTestCategory->name;
        $labTestCategory->delete();

        return back()->with('success', 'Category "' . $name . '" deleted.');
    }

    public function toggleActive(LabTestCategory $labTestCategory)
    {
        $labTestCategory->update(['is_active' => !$labTestCategory->is_active]);

        return back()->with('success', 'Category ' . ($labTestCategory->is_active ? 'activated' : 'deactivated') . '.');
    }
}
