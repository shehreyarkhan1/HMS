<?php

namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LabTest;
use App\Models\LabTestCategory;     
use App\Models\LabSampleType;


class LabTestController extends Controller
{
    public function index(Request $request)
    {
        $query = LabTest::with('category', 'sampleType');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }

        if ($request->filled('status')) {
            $active = $request->status === 'active';
            $query->where('is_active', $active);
        }

        $tests = $query->orderBy('name')->paginate(25)->withQueryString();
        $categories = LabTestCategory::active()->orderBy('name')->get();

        $stats = [
            'total' => LabTest::count(),
            'active' => LabTest::where('is_active', true)->count(),
            'fasting' => LabTest::where('requires_fasting', true)->count(),
            'categories' => LabTestCategory::count(),
        ];

        return view('laboratory.test_index', compact('tests', 'categories', 'stats'));
    }

    // ─────────────────────────────────────────────
    //  CREATE
    // ─────────────────────────────────────────────
    public function create()
    {
        $categories = LabTestCategory::active()->orderBy('name')->get();
        $sampleTypes = LabSampleType::active()->orderBy('name')->get();

        return view('laboratory.test_create', compact('categories', 'sampleTypes'));
    }

    // ─────────────────────────────────────────────
    //  STORE
    // ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:lab_test_categories,id',
            'sample_type_id' => 'nullable|exists:lab_sample_types,id',
            'price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'normal_range' => 'nullable|string|max:100',
            'normal_range_male' => 'nullable|string|max:100',
            'normal_range_female' => 'nullable|string|max:100',
            'normal_range_child' => 'nullable|string|max:100',
            'normal_range_elderly' => 'nullable|string|max:100',
            'turnaround_hours' => 'required|integer|min:0',
            'method' => 'nullable|string|max:100',
            'requires_fasting' => 'boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        LabTest::create($request->all());

        return redirect()->route('lab.tests.index')
            ->with('success', 'Test added successfully.');
    }

    // ─────────────────────────────────────────────
    //  EDIT
    // ─────────────────────────────────────────────
    public function edit(LabTest $labTest)
    {
        $categories = LabTestCategory::active()->orderBy('name')->get();
        $sampleTypes = LabSampleType::active()->orderBy('name')->get();

        return view('laboratory.test_edit', compact('labTest', 'categories', 'sampleTypes'));
    }

    // ─────────────────────────────────────────────
    //  UPDATE
    // ─────────────────────────────────────────────
    public function update(Request $request, LabTest $labTest)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:lab_test_categories,id',
            'sample_type_id' => 'nullable|exists:lab_sample_types,id',
            'price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'normal_range' => 'nullable|string|max:100',
            'normal_range_male' => 'nullable|string|max:100',
            'normal_range_female' => 'nullable|string|max:100',
            'normal_range_child' => 'nullable|string|max:100',
            'normal_range_elderly' => 'nullable|string|max:100',
            'turnaround_hours' => 'required|integer|min:0',
            'method' => 'nullable|string|max:100',
            'requires_fasting' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        $labTest->update($request->all());

        return redirect()->route('lab.tests.index')
            ->with('success', 'Test updated successfully.');
    }

    // ─────────────────────────────────────────────
    //  TOGGLE ACTIVE
    // ─────────────────────────────────────────────
    public function toggleActive(LabTest $labTest)
    {
        $labTest->update(['is_active' => !$labTest->is_active]);

        return back()->with('success', 'Test status updated.');
    }

    // ─────────────────────────────────────────────
    //  AJAX: Get test price (for order form)
    // ─────────────────────────────────────────────
    public function getPrice(LabTest $labTest)
    {
        return response()->json([
            'price' => $labTest->price,
            'unit' => $labTest->unit,
            'requires_fasting' => $labTest->requires_fasting,
            'sample_type' => $labTest->sampleType?->name,
            'turnaround' => $labTest->turnaround_label,
        ]);
    }
}
