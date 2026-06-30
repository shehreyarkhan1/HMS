@extends('layouts.guest')

@section('title', 'Set Your Password')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-12 col-md-6 col-lg-5 col-xl-4">

                <!-- Logo Section (Optional) -->
                <div class="text-center mb-4">
                    <a href="/">
                        <img src="https://via.placeholder.com/150x40?text=LOGO" alt="Logo" class="img-fluid"
                            style="max-height: 40px;">
                    </a>
                </div>

                <div class="card border-0 shadow-lg" style="border-radius: 16px;">
                    <div class="card-body p-4 p-md-5">

                        <!-- Header -->
                        <div class="mb-4">
                            <h3 class="fw-bold text-dark mb-2">Set New Password</h3>
                            <p class="text-muted small">Please create a strong password to secure your account at
                                <strong>{{ config('app.name') }}</strong>.</p>
                        </div>

                        <!-- Alerts -->
                        @if (session('success'))
                            <div class="alert alert-success border-0 small mb-4"
                                style="background-color: #f0fdf4; color: #166534;">
                                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger border-0 small mb-4"
                                style="background-color: #fef2f2; color: #991b1b;">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Form -->
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <!-- Email (Readonly for better UX) -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control bg-light border-start-0"
                                        value="{{ old('email', request('email')) }}" readonly>
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small">New Password</label>
                                <div class="input-group shadow-sm-hover">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i
                                            class="bi bi-lock"></i></span>
                                    <input type="password" name="password" id="password"
                                        class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                                        placeholder="••••••••" required>
                                    <button class="input-group-text bg-white border-start-0 text-muted" type="button"
                                        onclick="togglePass('password', 'eye1')">
                                        <i id="eye1" class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger extra-small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text extra-small">Minimum 8 characters with letters & numbers.</div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-secondary small">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i
                                            class="bi bi-shield-check"></i></span>
                                    <input type="password" name="password_confirmation" id="password_conf"
                                        class="form-control border-start-0 border-end-0" placeholder="••••••••" required>
                                    <button class="input-group-text bg-white border-start-0 text-muted" type="button"
                                        onclick="togglePass('password_conf', 'eye2')">
                                        <i id="eye2" class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm"
                                style="border-radius: 8px; transition: all 0.3s ease;">
                                Set Password & Sign In <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </form>

                    </div>
                </div>

                <!-- Footer Link -->
                <div class="text-center mt-4">
                    <p class="text-muted small">Back to <a href="{{ route('login') }}"
                            class="text-decoration-none fw-semibold">Login</a></p>
                </div>

            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f8fafc;
            /* Modern light-gray background */
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
            border-radius: 0.375rem;
        }

        .input-group-text {
            transition: all 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
        }

        .extra-small {
            font-size: 0.75rem;
        }
    </style>
@endsection

@push('scripts')
    <script>
        function togglePass(id, eyeId) {
            const input = document.getElementById(id);
            const icon = document.getElementById(eyeId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("bi-eye", "bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("bi-eye-slash", "bi-eye");
            }
        }
    </script>
@endpush
