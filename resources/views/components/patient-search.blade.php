{{--
    ┌─────────────────────────────────────────────────────────────────┐
    │  Patient Search Component                                        │
    │  Usage:                                                          │
    │    <x-patient-search />                                          │
    │    <x-patient-search :patient="$selectedPatient" />             │
    │    <x-patient-search name="patient_id" required="false" />      │
    │                                                                  │
    │  Props:                                                          │
    │    patient  → pre-selected Patient model (optional)             │
    │    name     → hidden input name (default: patient_id)           │
    │    required → show asterisk & enforce selection (default: true) │
    │    label    → field label (default: Patient)                     │
    └─────────────────────────────────────────────────────────────────┘
--}}

@props([
    'patient' => null,
    'name' => 'patient_id',
    'required' => true,
    'label' => 'Patient',
])

@php
    // Unique ID — agar ek page pe component multiple baar use ho
    $uid = 'ps_' . uniqid();
@endphp

{{-- ── Scoped styles — sirf pehli instance pe inject hongi ── --}}
@once
    <style>
        /* ── Patient Search Component ─────────────────────────────── */
        .ps-wrap {
            position: relative;
        }

        .ps-input-wrap {
            position: relative;
        }

        .ps-input-wrap .ps-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 13px;
            pointer-events: none;
        }

        .ps-input {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            color: #374151;
            background: #f8fafc;
            padding: 8px 12px 8px 30px;
            width: 100%;
            outline: none;
            transition: border-color .2s, background .2s;
        }

        .ps-input:focus {
            border-color: #93c5fd;
            background: #fff;
        }

        /* ── Dropdown ─────────────────────────────────────────────── */
        .ps-dropdown {
            display: none;
            position: fixed;
            z-index: 9999;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            max-height: 280px;
            overflow-y: auto;
            min-width: 300px;
        }

        .ps-dropdown .ps-row {
            padding: 10px 14px;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background .15s;
        }

        .ps-dropdown .ps-row:last-child {
            border-bottom: none;
        }

        .ps-dropdown .ps-row:hover {
            background: #f0f9ff;
        }

        .ps-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #e0f2fe;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            color: #0369a1;
            flex-shrink: 0;
            text-transform: uppercase;
        }

        .ps-pname {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
        }

        .ps-pmeta {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .ps-msg {
            padding: 14px;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
        }

        /* ── Selected badge ───────────────────────────────────────── */
        .ps-badge {
            display: none;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 12px;
            color: #1e40af;
        }

        .ps-badge.active {
            display: flex;
        }

        .ps-badge-text {
            flex: 1;
            min-width: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ps-clear-btn {
            background: none;
            border: none;
            color: #93c5fd;
            cursor: pointer;
            padding: 0;
            font-size: 14px;
            line-height: 1;
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }

        .ps-clear-btn:hover {
            color: #3b82f6;
        }
    </style>
@endonce

{{-- ── Component markup ─────────────────────────────────────────── --}}
<div class="ps-wrap" id="{{ $uid }}_wrap">

    {{-- Label --}}
    <div class="form-label-sm"
        style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px">
        {{ $label }}
        @if ($required)
            <span style="color:#dc2626">*</span>
        @endif
    </div>

    {{-- Hidden value input --}}
    <input type="hidden" name="{{ $name }}" id="{{ $uid }}_id" value="{{ $patient?->id }}"
        @if ($required) required @endif>

    {{-- Search input --}}
    <div class="ps-input-wrap">
        <i class="bi bi-search ps-icon"></i>
        <input type="text" id="{{ $uid }}_search" class="ps-input"
            placeholder="Search by name, MRN, or phone..."
            value="{{ $patient ? $patient->name . ' — ' . $patient->mrn : '' }}" autocomplete="off">
    </div>

    {{-- Dropdown --}}
    <div class="ps-dropdown" id="{{ $uid }}_dropdown"></div>

    {{-- Selected patient badge --}}
    <div class="ps-badge {{ $patient ? 'active' : '' }}" id="{{ $uid }}_badge">
        <i class="bi bi-person-check-fill" style="flex-shrink:0"></i>
        <span class="ps-badge-text" id="{{ $uid }}_badge_text">
            {{ $patient ? $patient->name . ' (' . $patient->patient_type . ') — ' . $patient->mrn : '' }}
        </span>
        <button type="button" class="ps-clear-btn" id="{{ $uid }}_clear">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

</div>

{{-- ── Per-instance script ───────────────────────────────────────── --}}
<script>
    (function() {
        const UID = '{{ $uid }}';
        const REQUIRED = {{ $required ? 'true' : 'false' }};

        const searchInput = document.getElementById(UID + '_search');
        const hiddenInput = document.getElementById(UID + '_id');
        const dropdown = document.getElementById(UID + '_dropdown');
        const badge = document.getElementById(UID + '_badge');
        const badgeText = document.getElementById(UID + '_badge_text');
        const clearBtn = document.getElementById(UID + '_clear');

        let searchTimeout = null;

        // ── Position dropdown under the input (fixed coords) ──────────
        function positionDropdown() {
            const rect = searchInput.getBoundingClientRect();
            dropdown.style.top = (rect.bottom + 4) + 'px';
            dropdown.style.left = rect.left + 'px';
            dropdown.style.width = rect.width + 'px';
        }

        // ── Escape HTML ────────────────────────────────────────────────
        function esc(str) {
            return String(str ?? '')
                .replace(/&/g, '&amp;').replace(/</g, '&lt;')
                .replace(/>/g, '&gt;').replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        // ── Select patient ─────────────────────────────────────────────
        function selectPatient(id, name, mrn, type) {
            hiddenInput.value = id;
            searchInput.value = name + ' — ' + mrn;
            badgeText.textContent = name + ' (' + type + ') — ' + mrn;
            badge.classList.add('active');
            dropdown.style.display = 'none';

            // Custom event — parent page sun sakta hai agar zaroorat ho
            searchInput.dispatchEvent(new CustomEvent('patient-selected', {
                bubbles: true,
                detail: {
                    id,
                    name,
                    mrn,
                    type
                }
            }));
        }

        // ── Clear selection ────────────────────────────────────────────
        function clearPatient(clearText = true) {
            hiddenInput.value = '';
            badge.classList.remove('active');
            if (clearText) searchInput.value = '';

            searchInput.dispatchEvent(new CustomEvent('patient-cleared', {
                bubbles: true
            }));
        }

        // ── Show loading / message state ───────────────────────────────
        function showMsg(icon, text, color) {
            dropdown.innerHTML = `
            <div class="ps-msg">
                <i class="bi bi-${icon} me-1" style="color:${color ?? '#94a3b8'}"></i>${esc(text)}
            </div>`;
        }

        // ── Live search ────────────────────────────────────────────────
        searchInput.addEventListener('input', function() {
            const q = this.value.trim();

            // Agar kuch tha selected aur user ne change kiya — clear karo
            if (hiddenInput.value) {
                clearPatient(false);
            }

            clearTimeout(searchTimeout);

            if (q.length < 2) {
                dropdown.style.display = 'none';
                return;
            }

            positionDropdown();
            dropdown.style.display = 'block';
            showMsg('hourglass-split', 'Searching...');

            searchTimeout = setTimeout(function() {
                fetch('/billing/ajax/patient-search?q=' + encodeURIComponent(q))
                    .then(r => r.json())
                    .then(function(patients) {
                        if (!patients.length) {
                            showMsg('person-x', 'No patient found');
                            return;
                        }

                        dropdown.innerHTML = patients.map(function(p) {
                            const initial = esc((p.name || '?').charAt(0)
                                .toUpperCase());
                            return `
                        <div class="ps-row"
                             onclick="(function(){
                                 const id='${esc(String(p.id))}',
                                       name='${esc(p.name)}',
                                       mrn='${esc(p.mrn)}',
                                       type='${esc(p.patient_type)}';
                                 document.getElementById('${UID}_search').dispatchEvent(
                                     Object.assign(new Event('_ps_select'), {_ps:{id,name,mrn,type}})
                                 );
                             })()">
                            <div class="ps-avatar">${initial}</div>
                            <div style="min-width:0">
                                <div class="ps-pname">${esc(p.name)}</div>
                                <div class="ps-pmeta">
                                    MRN: ${esc(p.mrn)}
                                    &nbsp;·&nbsp; ${esc(p.patient_type)}
                                    ${p.phone ? '&nbsp;·&nbsp; ' + esc(p.phone) : ''}
                                </div>
                            </div>
                        </div>`;
                        }).join('');
                    })
                    .catch(function() {
                        showMsg('exclamation-triangle', 'Search failed. Try again.', '#dc2626');
                    });
            }, 300);
        });

        // ── Internal select event (onclick se aata hai) ────────────────
        searchInput.addEventListener('_ps_select', function(e) {
            const {
                id,
                name,
                mrn,
                type
            } = e._ps;
            selectPatient(id, name, mrn, type);
        });

        // ── Clear button ───────────────────────────────────────────────
        clearBtn.addEventListener('click', function() {
            clearPatient(true);
        });

        // ── Reposition on scroll / resize ─────────────────────────────
        window.addEventListener('scroll', function() {
            if (dropdown.style.display !== 'none') positionDropdown();
        }, {
            passive: true
        });

        window.addEventListener('resize', function() {
            if (dropdown.style.display !== 'none') positionDropdown();
        });

        // ── Click outside — dropdown band karo ────────────────────────
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#' + UID + '_wrap') &&
                !e.target.closest('#' + UID + '_dropdown')) {
                dropdown.style.display = 'none';
            }
        });

    })();
</script>
