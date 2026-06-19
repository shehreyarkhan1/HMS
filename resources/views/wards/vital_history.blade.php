{{-- resources/views/wards/tabs/vitals_history.blade.php --}}
<div style="overflow-x:auto">
    <table class="table table-sm table-hover mb-0" style="font-size:12px">
        <thead class="table-light">
            <tr>
                <th class="ps-3">Time</th>
                <th>Shift</th>
                <th>BP</th>
                <th>SpO2</th>
                <th>Temp</th>
                <th>Pulse</th>
                <th>RR</th>
                <th>Pain</th>
                <th>GCS</th>
                <th>Glucose</th>
                <th>By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vitals as $v)
            <tr>
                <td class="ps-3 text-muted">
                    {{ $v->recorded_at->format('d M') }}<br>
                    <span style="font-size:10px">{{ $v->recorded_at->format('h:i A') }}</span>
                </td>

                <td>
                    <span class="badge bg-light text-secondary border" style="font-size:10px">
                        {{ $v->shift }}
                    </span>
                </td>

                {{-- BP --}}
                <td class="{{ $v->bp_status === 'critical' ? 'text-danger fw-bold' : ($v->bp_status === 'high' ? 'text-danger' : ($v->bp_status === 'low' ? 'text-primary' : '')) }}">
                    @if($v->systolic_bp)
                        {{ $v->systolic_bp }}/{{ $v->diastolic_bp }}
                        @if($v->bp_status !== 'normal' && $v->bp_status !== 'unknown')
                            <i class="bi bi-{{ $v->bp_status === 'high' || $v->bp_status === 'critical' ? 'arrow-up' : 'arrow-down' }}" style="font-size:10px"></i>
                        @endif
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>

                {{-- SpO2 --}}
                <td class="{{ $v->spo2_status === 'critical' ? 'text-danger fw-bold' : ($v->spo2_status === 'low' ? 'text-warning' : '') }}">
                    @if($v->oxygen_saturation)
                        {{ $v->oxygen_saturation }}%
                        @if($v->spo2_status === 'critical') <i class="bi bi-exclamation-circle-fill text-danger" style="font-size:10px"></i> @endif
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>

                {{-- Temp --}}
                <td class="{{ $v->temp_status === 'critical' ? 'text-danger fw-bold' : ($v->temp_status === 'high' ? 'text-danger' : ($v->temp_status === 'low' ? 'text-primary' : '')) }}">
                    {{ $v->temperature ? $v->temperature.'°F' : '—' }}
                </td>

                {{-- Pulse --}}
                <td>{{ $v->pulse_rate ? $v->pulse_rate.' bpm' : '—' }}</td>

                {{-- RR --}}
                <td>{{ $v->respiratory_rate ? $v->respiratory_rate.'/m' : '—' }}</td>

                {{-- Pain --}}
                <td>
                    @if($v->pain_score !== null)
                        <span class="{{ $v->pain_score >= 7 ? 'text-danger fw-bold' : ($v->pain_score >= 4 ? 'text-warning' : '') }}">
                            {{ $v->pain_score }}/10
                        </span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>

                {{-- GCS --}}
                <td>
                    @if($v->gcs_score)
                        <span class="{{ $v->gcs_score < 9 ? 'text-danger fw-bold' : ($v->gcs_score < 13 ? 'text-warning' : '') }}">
                            {{ $v->gcs_score }}/15
                        </span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>

                {{-- Glucose --}}
                <td>{{ $v->blood_glucose ? $v->blood_glucose.' mg/dL' : '—' }}</td>

                {{-- Recorded By ← nurse ka naam clearly ── --}}
                <td>
                    <span style="font-size:11px">
                        <i class="bi bi-person-fill me-1 text-muted"></i>
                        {{ $v->recordedBy->name ?? '—' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center text-muted py-4">
                    <i class="bi bi-heart-pulse d-block fs-4 mb-2"></i>
                    No vitals recorded yet
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($vitals->count() > 0)
<div style="padding:10px 16px;font-size:11px;color:#94a3b8;border-top:1px solid #f1f5f9">
    Showing last {{ $vitals->count() }} records for {{ $patient->name }}
</div>
@endif
