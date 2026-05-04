<?php

namespace App\Http\Controllers\BloodBank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodDonor;
use App\Models\Patient;
use Illuminate\Validation\Rule;

class BloodDonorController extends Controller
{
    public function index(Request $request)
    {
        $query = BloodDonor::withCount('donations');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                    ->orWhere('donor_id', 'like', "%$s%")
                    ->orWhere('cnic', 'like', "%$s%")
                    ->orWhere('phone', 'like', "%$s%");
            });
        }

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        if ($request->filled('donor_type')) {
            $query->where('donor_type', $request->donor_type);
        }

        if ($request->filled('eligibility')) {
            $query->where('is_eligible', $request->eligibility === 'eligible');
        }

        $donors = $query->orderBy('name')->paginate(15)->withQueryString();

        $stats = [
            'total' => BloodDonor::count(),
            'eligible' => BloodDonor::eligible()->count(),
            'voluntary' => BloodDonor::where('donor_type', 'Voluntary')->count(),
            'today' => BloodDonor::whereDate('created_at', today())->count(),
        ];

        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

        return view('bloodbank.blood_donor_index', compact('donors', 'stats', 'bloodGroups'));
    }

    public function create()
    {
        $patients = Patient::whereIn('status', ['Active', 'Admitted'])->orderBy('name')->get();
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
        return view('bloodbank.blood_donor_create', compact('patients', 'bloodGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'father_name' => 'nullable|string|max:200',
            'date_of_birth' => 'required|date|before:-18 years',
            'gender' => 'required|in:Male,Female,Other',
            'blood_group' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'weight_kg' => 'nullable|numeric|min:45|max:200',
            'cnic' => 'nullable|string|max:15|unique:blood_donors,cnic',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:200',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'donor_type' => 'required|in:Voluntary,Replacement,Autologous,Directed',
            'patient_id' => 'nullable|exists:patients,id',
            'notes' => 'nullable|string',
        ]);

        BloodDonor::create($validated);
        return redirect()->route('blood-bank.donors.index')
            ->with('success', 'Donor registered successfully.');
    }

    public function show(BloodDonor $donor)
    {
        $donor->load(['donations' => fn($q) => $q->latest(), 'patient']);
        return view('bloodbank.blood_donor_show', compact('donor'));
    }

    public function edit(BloodDonor $donor)
    {
        $patients = Patient::whereIn('status', ['Active', 'Admitted'])->orderBy('name')->get();
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
        return view('bloodbank.blood_donor_edit', compact('donor', 'patients', 'bloodGroups'));
    }

    public function update(Request $request, BloodDonor $donor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'father_name' => 'nullable|string|max:200',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'blood_group' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'weight_kg' => 'nullable|numeric|min:45',
            'cnic' => ['nullable', 'string', 'max:15', Rule::unique('blood_donors', 'cnic')->ignore($donor->id)],
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'donor_type' => 'required|in:Voluntary,Replacement,Autologous,Directed',
            'is_eligible' => 'boolean',
            'ineligibility_reason' => 'nullable|string',
            'eligible_from' => 'nullable|date',
            'patient_id' => 'nullable|exists:patients,id',
            'notes' => 'nullable|string',
        ]);

        $donor->update(array_merge($validated, [
            'is_eligible' => $request->boolean('is_eligible', true),
        ]));

        return redirect()->route('blood-bank.donors.show', $donor)
            ->with('success', 'Donor record updated.');
    }

    public function destroy(BloodDonor $donor)
    {
        $donor->delete();
        return redirect()->route('blood-bank.donors.index')
            ->with('success', 'Donor removed.');
    }
}
