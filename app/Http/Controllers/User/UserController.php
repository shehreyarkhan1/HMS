<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of all users with search & filter.
     */
    public function index(Request $request)
    {
        $query = User::with('employee')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->paginate(10)->withQueryString();
        $roles = $this->getRoles();
        $stats = $this->getStats();

        return view('users.user_index', compact('users', 'roles', 'stats'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = $this->getRoles();
        $employees = $this->getAvailableEmployees();

        return view('users.user_create', compact('roles', 'employees'));
    }

    /**
     * Store a newly created user in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['nullable', 'exists:employees,id'],
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(array_keys($this->getRoles()))],
            'is_active' => ['boolean'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $user = User::create($validated);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "User \"{$user->name}\" created successfully.");
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('employee');
        $roles = $this->getRoles();

        return view('users.user_show', compact('user', 'roles'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = $this->getRoles();

        // Available = employees without a user account + current user's own employee (if linked)
        $employees = Employee::where(function ($q) use ($user) {
            $q->whereDoesntHave('user')
                ->orWhere('id', $user->employee_id);
        })->select('id', 'first_name', 'employee_id')
            ->orderBy('first_name')
            ->get();

        return view('users.user_edit', compact('user', 'roles', 'employees'));
    }

    /**
     * Update the specified user in the database.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'employee_id' => ['nullable', 'exists:employees,id'],
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_]+$/', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(array_keys($this->getRoles()))],
            'is_active' => ['boolean'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $user->update($validated);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "User \"{$user->name}\" updated successfully.");
    }

    /**
     * Toggle user active/inactive status.
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "User \"{$user->name}\" has been {$status}.");
    }

    /**
     * Remove the specified user from the database.
     */
    public function destroy(User $user)
    {
        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User \"{$name}\" deleted successfully.");
    }

    // ─── Private Helpers ──────────────────────────────────────────────────────

    private function getRoles(): array
    {
        return [
            'super_admin' => 'Super Admin',
            'receptionist' => 'Receptionist',
            'doctor' => 'Doctor',
            'nurse' => 'Nurse',
            'lab_technician' => 'Lab Technician',
            'radiologist' => 'Radiologist',
            'pharmacist' => 'Pharmacist',
            'hr_manager' => 'HR Manager',
            'accountant' => 'Accountant',
        ];
    }

    private function getStats(): array
    {
        return [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'doctors' => User::where('role', 'doctor')->count(),
        ];
    }

    /**
     * Employees who do not yet have a user account.
     */
    private function getAvailableEmployees($currentEmployeeId = null)
    {
        return Employee::select('id', 'first_name', 'last_name', 'employee_id')
            ->where(function ($query) use ($currentEmployeeId) {
                $query->whereDoesntHave('user'); // Unhe dikhao jin ka user nahi bana
                if ($currentEmployeeId) {
                    $query->orWhere('id', $currentEmployeeId); // Edit ke waqt current employee ko bhi dikhao
                }
            })
            ->orderBy('first_name')
            ->get();
    }
}