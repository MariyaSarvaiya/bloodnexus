@extends('layouts.admin')
@section('title','Medical History')
@section('content')
<div class="admin-pagebar">
    <div>
        <h1 class="page-title">Donor Medical History</h1>
        <div class="admin-page-subtitle">Live medical-history answers submitted by registered donors.</div>
    </div>
</div>

<div class="admin-card mb-4">
    <div class="admin-card-body">
        <form class="row g-2" method="GET">
            <div class="col-md-9">
                <input class="form-control filter-control" name="search" value="{{ request('search') }}" placeholder="Search donor name, email, phone, city or area">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn-admin btn-dark flex-fill">Search</button>
                <a class="btn-admin btn-light-admin flex-fill text-center" href="{{ route('admin.medical.history') }}">Reset</a>
            </div>
        </form>
    </div>
</div>

@php
$medicalQuestions = [
    'chronic_condition' => 'Long-term / chronic medical condition',
    'regular_medicines' => 'Regular medicines',
    'allergies' => 'Known allergy',
    'recent_surgery' => 'Recent surgery / medical procedure',
    'fever_infection' => 'Current fever / infection',
    'blood_transfusion' => 'Recent blood transfusion',
    'tattoo_piercing' => 'Recent tattoo / piercing',
    'recent_donation' => 'Blood donation within last 3 months',
];
@endphp

<div class="row g-4">
@forelse($donors as $donor)
    @php
        $history = is_string($donor->medical_history) ? json_decode($donor->medical_history, true) : $donor->medical_history;
        $history = is_array($history) ? $history : [];
    @endphp
    <div class="col-12">
        <div class="admin-card" style="overflow:hidden">
            <div class="admin-card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h3 class="h5 fw-bold mb-0">{{ $donor->name }}</h3>
                            <span class="badge bg-danger-subtle text-danger">{{ $donor->blood_group }}</span>
                            <span class="badge-soft {{ $donor->isAvailable() ? 'status-completed' : 'status-cancelled' }}">{{ $donor->isAvailable() ? 'AVAILABLE' : 'UNAVAILABLE' }}</span>
                        </div>
                        <div class="small text-secondary mt-1">
                            {{ $donor->email }} · {{ $donor->phone }} · {{ $donor->city }}{{ $donor->area ? ' · '.$donor->area : '' }}
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="small text-secondary">Medical History</div>
                        <strong>{{ count($history) }}/{{ count($medicalQuestions) }} answers saved</strong>
                    </div>
                </div>

                @if($history)
                    <div class="row g-2">
                        @foreach($medicalQuestions as $key => $label)
                            <div class="col-md-6 col-xl-3">
                                <div class="p-3 rounded-4 h-100" style="background:#f8fafc;border:1px solid #e8edf4">
                                    <div class="small text-secondary mb-2">{{ $label }}</div>
                                    @if(($history[$key] ?? null) === 'yes')
                                        <span class="badge-soft status-cancelled"><i class="bi bi-check-circle-fill me-1"></i>YES</span>
                                    @elseif(($history[$key] ?? null) === 'no')
                                        <span class="badge-soft status-completed"><i class="bi bi-x-circle-fill me-1"></i>NO</span>
                                    @else
                                        <span class="badge-soft"><i class="bi bi-dash-circle me-1"></i>NOT ANSWERED</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 rounded-4" style="background:#f8fafc;border:1px solid #e8edf4;color:#64748b">
                        <i class="bi bi-info-circle me-1"></i>No medical history has been submitted by this donor yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="col-12"><div class="admin-card"><div class="admin-card-body empty-state text-center py-5">No donor medical-history records found.</div></div></div>
@endforelse
</div>

<div class="mt-4">{{ $donors->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
@endsection
