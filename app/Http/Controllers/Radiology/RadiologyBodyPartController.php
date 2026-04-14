<?php

namespace App\Http\Controllers\Radiology;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RadiologyBodyPart;


class RadiologyBodyPartController extends Controller
{
    // Region options — used in both create & edit
    public const REGIONS = [
        'Head & Neck',
        'Thorax',
        'Abdomen',
        'Pelvis',
        'Spine',
        'Upper Extremity',
        'Lower Extremity',
        'Whole Body',
        'Other',
    ];

    public function index()
    {
        $bodyParts = RadiologyBodyPart::orderBy('region')->orderBy('name')->paginate(25);
        $regions = self::REGIONS;

        return view('radiology.body_part_index', compact('bodyParts', 'regions'));
    }

    public function create()
    {
        $regions = self::REGIONS;
        return view('radiology.body_parts.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:radiology_body_parts,code',
            'region' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        RadiologyBodyPart::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'region' => $request->region,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('radiology.body-parts.index')
            ->with('success', 'Body part created successfully.');
    }

    public function edit(RadiologyBodyPart $bodyPart)
    {
        $regions = self::REGIONS;
        return view('radiology.body_parts.edit', compact('bodyPart', 'regions'));
    }

    public function update(Request $request, RadiologyBodyPart $bodyPart)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:radiology_body_parts,code,' . $bodyPart->id,
            'region' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $bodyPart->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'region' => $request->region,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('radiology.body-parts.index')
            ->with('success', 'Body part updated successfully.');
    }

    public function destroy(RadiologyBodyPart $bodyPart)
    {
        if ($bodyPart->exams()->exists()) {
            return back()->with('error', 'Cannot delete — exams are linked to this body part.');
        }

        $bodyPart->delete();

        return back()->with('success', 'Body part deleted.');
    }

    public function toggleStatus(RadiologyBodyPart $bodyPart)
    {
        $bodyPart->update(['is_active' => !$bodyPart->is_active]);
        return back()->with('success', 'Status updated.');
    }
}
