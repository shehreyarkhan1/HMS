<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;              // ← ye add karo
use App\Models\ActivityLog;
use App\Facades\AuditLog;

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
        $throttleKey = Str::lower($request->login).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'login' => "Too many attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $credentials = [
            $field => $request->login,
            'password' => $request->password,
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60); // 60 second decay
            $this->safeAuditLog(fn () => AuditLog::login($request->login, success: false));

            return back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => 'Invalid credentials. Please try again.']);
        }

        RateLimiter::clear($throttleKey); // success pe reset

        if (! Auth::user()->is_active) {
            Auth::logout();

            return back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => 'Your account has been deactivated. Contact HR.']);
        }

        $request->session()->regenerate();

        // Audit log fail bhi ho jaye, login process rukna nahi chahiye
        $this->safeAuditLog(fn () => AuditLog::login($request->login, success: true));

        return redirect()->intended(route($this->redirectByRole()));
    }

    public function logout(Request $request)
    {
        $this->safeAuditLog(fn () => AuditLog::logout());

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ── Helper: audit log fail ho to sirf log karo, exception throw mat hone do ──
    private function safeAuditLog(callable $callback): void
    {
        try {
            $callback();
        } catch (\Throwable $e) {
            Log::error('Audit log failed', [
                'error' => $e->getMessage(),
            ]);
        }
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
