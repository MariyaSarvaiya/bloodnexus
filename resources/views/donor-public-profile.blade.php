@extends('layouts.app')

@section('title', $donor->name.' | Donor Medical Profile')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-9">
            <div class="p-4 p-md-5 rounded-5 text-white mb-4" style="background:linear-gradient(135deg,#0b1730,#17456d 55%,#c91f32);box-shadow:0 25px 70px rgba(15,35,60,.18);overflow:hidden;position:relative;">
                <div style="position:relative;z-index:2">
                    <span class="badge rounded-pill bg-white text-danger px-3 py-2">🩸 DONOR PROFILE</span>
                    <h1 class="fw-black mt-3 mb-2">{{ $donor->name }}</h1>
                    <p class="mb-0 opacity-75">{{ $donor->blood_group }} · {{ $donor->city }}{{ $donor->area ? ' · '.$donor->area : '' }} · {{ $donor->is_available ? 'Available' : 'Currently unavailable' }}</p>
                </div>
            </div>
            <div class="card border-0 rounded-5 shadow-sm overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div><h3 class="fw-bold mb-1">🩺 Medical History</h3><p class="text-secondary mb-0">Information shared by the donor for blood-need review.</p></div>
                        <span class="badge rounded-pill bg-light text-danger px-3 py-2">DONOR PROVIDED</span>
                    </div>
                    @php
                        $profileMedical = is_string($donor->medical_history) ? json_decode($donor->medical_history, true) : [];
                        if (!is_array($profileMedical)) $profileMedical = [];
                        $profileQuestions = [
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
                    @if($profileMedical)
                        <div class="row g-3">
                            @foreach($profileQuestions as $key => $label)
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 h-100" style="background:#f8fafc;border:1px solid #e8edf4;">
                                        <div class="small text-secondary">{{ $label }}</div>
                                        <div class="fw-bold mt-1 {{ ($profileMedical[$key] ?? 'no') === 'yes' ? 'text-danger' : 'text-success' }}">
                                            {{ strtoupper($profileMedical[$key] ?? 'no') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 rounded-4" style="background:#f8fafc;border:1px solid #e8edf4;">No medical history has been provided by this donor.</div>
                    @endif
                    <div class="row g-3 mt-3">
                        <div class="col-md-4"><div class="p-3 rounded-4 bg-light"><small class="text-secondary">Blood Group</small><div class="fw-bold text-danger fs-4">{{ $donor->blood_group }}</div></div></div>
                        <div class="col-md-4"><div class="p-3 rounded-4 bg-light"><small class="text-secondary">City / Area</small><div class="fw-bold">{{ $donor->city }}{{ $donor->area ? ' / '.$donor->area : '' }}</div></div></div>
                        <div class="col-md-4"><div class="p-3 rounded-4 bg-light"><small class="text-secondary">Availability</small><div class="fw-bold {{ $donor->is_available ? 'text-success' : 'text-secondary' }}">{{ $donor->is_available ? 'Available' : 'Unavailable' }}</div></div></div>
                    </div>
                    <a href="{{ route('blood.search') }}" class="btn btn-dark rounded-pill px-4 mt-4">← Back to Donor Search</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
