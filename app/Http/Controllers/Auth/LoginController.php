<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // ← yeh add karo
use App\Models\ActivityLog; // ← yeh add karo
use App\Facades\AuditLog; // ← yeh add karo
class LoginController extends Controller
{
    // ── Show login form ──────────────────────────────────────────────────
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route($this->redirectByRole());
        }

        return view('auth.login');
    }

    // ── Handle login ─────────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $credentials = [
            $field => $request->login,
            'password' => $request->password,
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => 'Invalid credentials. Please try again.']);
        }

        // Active check — attempt ke baad
        if (! Auth::user()->is_active) {
            Auth::logout();

            return back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => 'Your account has been deactivated. Contact HR.']);
        }

        $request->session()->regenerate();
        AuditLog::login($request->login, success: true);

        return redirect()->intended(route($this->redirectByRole()));
    }

    // ── Logout ───────────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
AuditLog::logout();
        return redirect()->route('login');
    }

    // ── Role → Dashboard mapping ─────────────────────────────────────────
    protected function redirectByRole(): string
    {
        return match (Auth::user()->role) {
            'super_admin' => 'dashboard',
            'doctor' => 'doctor.dashboard',
            'receptionist' => 'dashboard',
            'nurse' => 'dashboard',
            'lab_technician' => 'lab.orders.index',
            'radiologist' => 'radiology.orders.index',
            'pharmacist' => 'pharmacy.medicines.index',
            'hr_manager' => 'employees.index',
            'accountant' => 'billing.index',
            default => 'dashboard',
        };
    }
}
