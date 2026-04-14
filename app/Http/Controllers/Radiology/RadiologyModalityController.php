<?php

namespace App\Http\Controllers\Radiology;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RadiologyModality;


class RadiologyModalityController extends Controller
{
    public function index()
    {
        $modalities = RadiologyModality::withCount('exams')
            ->orderBy('name')
            ->paginate(20);

        return view('radiology.modality_index', compact('modalities'));
    }

    public function create()
    {
        return view('radiology.modality_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:radiology_modalities,code',
            'description' => 'nullable|string|max:500',
            'requires_contrast' => 'boolean',
            'requires_preparation' => 'boolean',
            'preparation_instructions' => 'nullable|string|max:1000',
            'average_duration_minutes' => 'nullable|integer|min:1|max:480',
            'is_active' => 'boolean',
        ]);

        RadiologyModality::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'requires_contrast' => $request->boolean('requires_contrast'),
            'requires_preparation' => $request->boolean('requires_preparation'),
            'preparation_instructions' => $request->preparation_instructions,
            'average_duration_minutes' => $request->average_duration_minutes ?? 30,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('radiology.modalities.index')
            ->with('success', 'Modality created successfully.');
    }

    public function edit(RadiologyModality $modality)
    {
        return view('radiology.modality_edit', compact('modality'));
    }

    public function update(Request $request, RadiologyModality $modality)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:radiology_modalities,code,' . $modality->id,
            'description' => 'nullable|string|max:500',
            'requires_contrast' => 'boolean',
            'requires_preparation' => 'boolean',
            'preparation_instructions' => 'nullable|string|max:1000',
            'average_duration_minutes' => 'nullable|integer|min:1|max:480',
            'is_active' => 'boolean',
        ]);

        $modality->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'requires_contrast' => $request->boolean('requires_contrast'),
            'requires_preparation' => $request->boolean('requires_preparation'),
            'preparation_instructions' => $request->preparation_instructions,
            'average_duration_minutes' => $request->average_duration_minutes ?? 30,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('radiology.modalities.index')
            ->with('success', 'Modality updated successfully.');
    }

    public function destroy(RadiologyModality $modality)
    {
        if ($modality->exams()->exists()) {
            return back()->with('error', 'Cannot delete — this modality has exams linked to it.');
        }

        $modality->delete();

        return back()->with('success', 'Modality deleted.');
    }

    public function toggleStatus(RadiologyModality $modality)
    {
        $modality->update(['is_active' => !$modality->is_active]);

        return back()->with('success', 'Status updated.');
    }
}
