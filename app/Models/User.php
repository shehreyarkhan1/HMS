<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'employee_id',
        'name',
        'username',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    // ===== RELATIONSHIPS =====

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // ===== ROLE HELPERS =====

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    // ===== MODULE PERMISSIONS =====

    public function canAccess(string $module): bool
    {
        if ($this->isSuperAdmin())
            return true;

        $permissions = [
            'dashboard' => ['receptionist', 'doctor', 'nurse', 'lab_technician', 'radiologist', 'pharmacist', 'hr_manager', 'accountant'],
            'doctors' => ['receptionist', 'hr_manager'],
            'patients' => ['receptionist', 'doctor', 'nurse'],
            'appointments' => ['receptionist', 'doctor', 'nurse'],
            'staff' => ['hr_manager'],
            'wards' => ['nurse', 'doctor', 'receptionist'],
            'pharmacy' => ['pharmacist', 'doctor'],
            'lab' => ['lab_technician', 'doctor'],
            'radiology' => ['radiologist', 'doctor'],
            'billing' => ['accountant', 'receptionist'],
            'reports' => ['accountant', 'hr_manager'],
            'settings' => [],
            'users' => [],
        ];

        return in_array($this->role, $permissions[$module] ?? []);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
}