@extends('layouts.master')

@section('title', 'System Settings')

@section('page-title', 'System Settings')
@section('breadcrumb', 'System / Settings')

@section('content')
    <div class="row g-4">

        {{-- Sidebar Tabs --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <span class="fw-semibold"
                        style="font-size:13px; color:#64748b; text-transform:uppercase; letter-spacing:.06em;">
                        Configuration
                    </span>
                </div>
                <div class="card-body p-2">
                    <nav class="nav flex-column gap-1">
                        @foreach ($tabs as $tabKey => $tab)
                            <a href="{{ route('settings.index', ['group' => $tabKey]) }}"
                                class="nav-item {{ $group === $tabKey ? 'active' : '' }}">
                                <i class="bi {{ $tab['icon'] }}"></i>
                                {{ $tab['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>
        </div>

        {{-- Settings Form --}}
        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                    <i class="bi {{ $tabs[$group]['icon'] }} text-primary"></i>
                    <span class="fw-semibold">{{ $tabs[$group]['label'] }} Settings</span>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('settings.update', ['group' => $group]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            @forelse($settings as $setting)
                                <div class="{{ $setting->type === 'image' ? 'col-12' : 'col-md-6' }}">

                                    {{-- Text / Number --}}
                                    @if (in_array($setting->type, ['text', 'number']))
                                        <label class="form-label fw-medium" style="font-size:13.5px">
                                            {{ $setting->label }}
                                        </label>
                                        <input type="{{ $setting->type === 'number' ? 'number' : 'text' }}"
                                            name="{{ $setting->key }}" value="{{ old($setting->key, $setting->value) }}"
                                            class="form-control @error($setting->key) is-invalid @enderror"
                                            @if ($setting->type === 'number') min="0" step="any" @endif>
                                        @if ($setting->description)
                                            <small class="text-muted">{{ $setting->description }}</small>
                                        @endif

                                        {{-- Boolean --}}
                                    @elseif($setting->type === 'boolean')
                                        <label class="form-label fw-medium"
                                            style="font-size:13.5px">{{ $setting->label }}</label>
                                        <div class="form-check form-switch mt-1">
                                            <input class="form-check-input" type="checkbox" name="{{ $setting->key }}"
                                                id="{{ $setting->key }}" value="1"
                                                {{ old($setting->key, $setting->value) == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ $setting->key }}"
                                                style="font-size:13px; color:#64748b">
                                                {{ $setting->value == '1' ? 'Enabled' : 'Disabled' }}
                                            </label>
                                        </div>

                                        {{-- Image upload --}}
                                    @elseif($setting->type === 'image')
                                        <label class="form-label fw-medium"
                                            style="font-size:13.5px">{{ $setting->label }}</label>
                                        <div class="row align-items-center g-3">
                                            <div class="col-auto">
                                                @if ($setting->value)
                                                    <img src="{{ asset('storage/' . $setting->value) }}"
                                                        alt="Hospital Logo" class="rounded border bg-light"
                                                        style="height:72px; width:auto; max-width:180px; object-fit:contain; padding:6px">
                                                @else
                                                    <div class="rounded border d-flex align-items-center justify-content-center bg-light"
                                                        style="height:72px; width:140px;">
                                                        <i class="bi bi-image text-muted fs-3"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col">
                                                <input type="file" name="hospital_logo"
                                                    class="form-control @error('hospital_logo') is-invalid @enderror"
                                                    accept="image/*">
                                                <small class="text-muted">PNG, JPG or SVG — max 2MB</small>
                                                @error('hospital_logo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif

                                    @error($setting->key)
                                        <div class="text-danger" style="font-size:12px; margin-top:4px">{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            @empty
                                <div class="col-12">
                                    <p class="text-muted text-center py-4">No settings found for this group.</p>
                                </div>
                            @endforelse
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-floppy me-1"></i>Save Settings
                            </button>
                            <a href="{{ route('settings.index', ['group' => $group]) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-1"></i>Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
