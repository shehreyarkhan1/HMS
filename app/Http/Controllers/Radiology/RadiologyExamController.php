<?php

namespace App\Http\Controllers\Radiology;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RadiologyExam;
use App\Models\RadiologyModality;
use App\Models\RadiologyBodyPart;
use App\Models\RadiologyOrder;
use App\Models\Patient;
use App\Models\Doctor;
class RadiologyExamController extends Controller
{
    /**
     * Display a listing of exams
     */
    public function index(Request $request)
    {
        $query = RadiologyExam::with(['modality', 'bodyPart'])
            ->latest('created_at');

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('exam_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('modality_id')) {
            $query->where('modality_id', $request->modality_id);
        }

        if ($request->filled('body_part_id')) {
            $query->where('body_part_id', $request->body_part_id);
        }

        if ($request->filled('requires_contrast')) {
            $query->where('requires_contrast', $request->requires_contrast === 'yes');
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === 'active');
        }

        $exams = $query->paginate(20)->withQueryString();

        $modalities = RadiologyModality::active()->orderBy('name')->get();
        $bodyParts = RadiologyBodyPart::active()->orderBy('name')->get();

        $stats = [
            'total' => RadiologyExam::count(),
            'active' => RadiologyExam::where('is_active', true)->count(),
            'inactive' => RadiologyExam::where('is_active', false)->count(),
            'with_contrast' => RadiologyExam::where('requires_contrast', true)->count(),
        ];

        return view('radiology.radiology_exam_index', compact('exams', 'modalities', 'bodyParts', 'stats'));
    }

    /**
     * Show the form for creating a new exam
     */
    public function create()
    {
        $modalities = RadiologyModality::active()->orderBy('name')->get();
        $bodyParts = RadiologyBodyPart::active()->orderBy('name')->get();

        return view('radiology.radiology_exam_create', compact('modalities', 'bodyParts'));
    }

    /**
     * Store a newly created exam in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'exam_code' => 'nullable|string|max:50|unique:radiology_exams,exam_code',
            'modality_id' => 'required|exists:radiology_modalities,id',
            'body_part_id' => 'nullable|exists:radiology_body_parts,id',
            'price' => 'required|numeric|min:0',
            'requires_contrast' => 'boolean',
            'contrast_type' => 'nullable|string|max:100',
            'requires_preparation' => 'boolean',
            'preparation_instructions' => 'nullable|string|max:2000',
            'turnaround_hours' => 'required|integer|min:1|max:168',
            'duration_minutes' => 'required|integer|min:5|max:480',
            'clinical_indications' => 'nullable|string|max:2000',
            'contraindications' => 'nullable|string|max:2000',
            'requires_consent' => 'boolean',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        RadiologyExam::create([
            'name' => $request->name,
            'exam_code' => $request->exam_code,
            'modality_id' => $request->modality_id,
            'body_part_id' => $request->body_part_id,
            'price' => $request->price,
            'requires_contrast' => $request->boolean('requires_contrast'),
            'contrast_type' => $request->contrast_type,
            'requires_preparation' => $request->boolean('requires_preparation'),
            'preparation_instructions' => $request->preparation_instructions,
            'turnaround_hours' => $request->turnaround_hours,
            'duration_minutes' => $request->duration_minutes,
            'clinical_indications' => $request->clinical_indications,
            'contraindications' => $request->contraindications,
            'requires_consent' => $request->boolean('requires_consent'),
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('radiology.exams.index')
            ->with('success', 'Radiology exam created successfully.');
    }

    /**
     * Display the specified exam
     */
    public function show(RadiologyExam $radiologyExam)
    {
        $radiologyExam->load(['modality', 'bodyPart', 'orderItems.order.patient']);

        $recentOrders = $radiologyExam->orderItems()
            ->with('order.patient')
            ->latest()
            ->take(10)
            ->get();

        return view('radiology.radiology_exam_show', compact('radiologyExam', 'recentOrders'));
    }

    /**
     * Show the form for editing the specified exam
     */
    public function edit(RadiologyExam $radiologyExam)
    {
        $modalities = RadiologyModality::active()->orderBy('name')->get();
        $bodyParts = RadiologyBodyPart::active()->orderBy('name')->get();

        return view('radiology.radiology_exam_edit', compact('radiologyExam', 'modalities', 'bodyParts'));
    }

    /**
     * Update the specified exam in storage
     */
    public function update(Request $request, RadiologyExam $radiologyExam)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'exam_code' => 'nullable|string|max:50|unique:radiology_exams,exam_code,' . $radiologyExam->id,
            'modality_id' => 'required|exists:radiology_modalities,id',
            'body_part_id' => 'nullable|exists:radiology_body_parts,id',
            'price' => 'required|numeric|min:0',
            'requires_contrast' => 'boolean',
            'contrast_type' => 'nullable|string|max:100',
            'requires_preparation' => 'boolean',
            'preparation_instructions' => 'nullable|string|max:2000',
            'turnaround_hours' => 'required|integer|min:1|max:168',
            'duration_minutes' => 'required|integer|min:5|max:480',
            'clinical_indications' => 'nullable|string|max:2000',
            'contraindications' => 'nullable|string|max:2000',
            'requires_consent' => 'boolean',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $radiologyExam->update([
            'name' => $request->name,
            'exam_code' => $request->exam_code,
            'modality_id' => $request->modality_id,
            'body_part_id' => $request->body_part_id,
            'price' => $request->price,
            'requires_contrast' => $request->boolean('requires_contrast'),
            'contrast_type' => $request->contrast_type,
            'requires_preparation' => $request->boolean('requires_preparation'),
            'preparation_instructions' => $request->preparation_instructions,
            'turnaround_hours' => $request->turnaround_hours,
            'duration_minutes' => $request->duration_minutes,
            'clinical_indications' => $request->clinical_indications,
            'contraindications' => $request->contraindications,
            'requires_consent' => $request->boolean('requires_consent'),
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('radiology.exams.index')
            ->with('success', 'Radiology exam updated successfully.');
    }

    /**
     * Remove the specified exam from storage
     */
    public function destroy(RadiologyExam $radiologyExam)
    {
        // Check if exam is being used in any orders
        if ($radiologyExam->orderItems()->exists()) {
            return back()->with('error', 'Cannot delete exam that has been ordered. Mark as inactive instead.');
        }

        $radiologyExam->delete();

        return redirect()->route('radiology.exams.index')
            ->with('success', 'Radiology exam deleted successfully.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(RadiologyExam $radiologyExam)
    {
        $radiologyExam->update([
            'is_active' => !$radiologyExam->is_active
        ]);

        $status = $radiologyExam->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Exam {$status} successfully.");
    }
}
