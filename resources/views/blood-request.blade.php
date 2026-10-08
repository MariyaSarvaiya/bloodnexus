@extends('layouts.app')

@section('content')

<style>
    .request-page {
        min-height: calc(100vh - 80px);
        padding: 50px 0 80px;
        background:
            radial-gradient(circle at top left, rgba(220, 38, 38, .10), transparent 35%),
            radial-gradient(circle at bottom right, rgba(37, 99, 235, .08), transparent 35%),
            #f8fafc;
    }

    .request-hero {
        background: linear-gradient(135deg, #991b1b, #dc2626, #7f1d1d);
        color: white;
        border-radius: 28px;
        padding: 38px;
        margin-bottom: 28px;
        box-shadow: 0 20px 45px rgba(127, 29, 29, .20);
    }

    .request-hero h1 {
        font-size: 36px;
        font-weight: 800;
    }

    .request-card {
        background: white;
        border-radius: 25px;
        padding: 32px;
        box-shadow: 0 15px 40px rgba(15, 23, 42, .08);
        border: 1px solid #eef2f7;
    }

    .section-title {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 18px;
        color: #0f172a;
    }

    .form-label {
        font-weight: 700;
        color: #334155;
    }

    .form-control,
    .form-select {
        border-radius: 13px;
        padding: 13px 15px;
        border: 1px solid #e2e8f0;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, .10);
    }

    .donor-selected {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 18px;
        padding: 18px;
        margin-bottom: 28px;
    }

    .blood-circle {
        width: 55px;
        height: 55px;
        border-radius: 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #dc2626;
        color: white;
        font-weight: 800;
        font-size: 17px;
    }

    .urgency-card {
        border: 1px solid #e2e8f0;
        border-radius: 15px;
        padding: 15px;
        cursor: pointer;
        transition: .2s;
    }

    .urgency-card:hover {
        border-color: #dc2626;
        background: #fff7f7;
    }

    .submit-btn {
        border: 0;
        border-radius: 14px;
        padding: 15px 25px;
        width: 100%;
        color: white;
        font-weight: 800;
        font-size: 16px;
        background: linear-gradient(135deg, #dc2626, #991b1b);
        box-shadow: 0 10px 25px rgba(220, 38, 38, .20);
        transition: .25s;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(220, 38, 38, .30);
    }

    .info-box {
        background: #f8fafc;
        border-radius: 18px;
        padding: 20px;
        border: 1px solid #e2e8f0;
    }
</style>


<div class="request-page">

    <div class="container">

        {{-- HERO --}}
        <div class="request-hero">

            <h1 class="mb-2">
                Request Blood 🩸
            </h1>

            <p class="mb-0 opacity-75">
                Provide the patient details below and submit your blood
                requirement securely.
            </p>

        </div>


        <div class="row g-4">

            {{-- MAIN FORM --}}
            <div class="col-lg-8">

                <div class="request-card">

                    {{-- SELECTED DONOR --}}

                    @if(isset($donor) && $donor)

                        <div class="donor-selected">

                            <div class="d-flex align-items-center gap-3">

                                <div class="blood-circle">
                                    {{ $donor->blood_group }}
                                </div>

                                <div>

                                    <div class="fw-bold fs-5">
                                        {{ $donor->name }}
                                    </div>

                                    <div class="text-muted small">
                                        📍 {{ $donor->city }}
                                    </div>

                                    <span class="badge bg-success mt-1">
                                        ● Available
                                    </span>

                                </div>

                            </div>

                            <div class="small text-danger fw-bold mt-2">🎯 This request will be delivered directly to this donor.</div>

                        </div>

                    @else

                        <div class="alert alert-info rounded-4">

                            💡 You can submit a general blood request.
                            If you want a specific donor, first use
                            <strong>Find Blood</strong>.

                        </div>

                    @endif


                    <form
                        method="POST"
                        action="{{ route('blood.request.store') }}"
                    >

                        @csrf

                        {{-- DONOR ID --}}

                        @if(isset($donor) && $donor)

                            <input
                                type="hidden"
                                name="donor_id"
                                value="{{ $donor->id }}"
                            >

                        @endif


                        {{-- PATIENT DETAILS --}}

                        <div class="section-title">
                            👤 Patient Details
                        </div>

                        <div class="row g-3 mb-4">

                            <div class="col-md-5">
                                <label class="form-label">Blood is Required For</label>
                                <select name="request_for" class="form-select" required>
                                    <option value="self" @selected(old('request_for')==='self')>🧑 For Myself</option>
                                    <option value="relative" @selected(old('request_for','relative')==='relative')>👨‍👩‍👧 For Relative / Family Member</option>
                                </select>
                                <input type="hidden" name="requester_type" value="relative">
                                @error('request_for')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-7">

                                <label class="form-label">
                                    Patient Name
                                </label>

                                <input
                                    type="text"
                                    name="patient_name"
                                    class="form-control"
                                    value="{{ old('patient_name') }}"
                                    placeholder="Enter patient name"
                                    required
                                >

                                @error('patient_name')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <div class="col-md-4">
                                <label class="form-label">Why is Blood Needed?</label>
                                <select name="reason" class="form-select" required>
                                    <option value="">Select Reason</option>
                                    <option value="accident" @selected(old('reason')==='accident')>🚑 Accident / Trauma</option>
                                    <option value="dialysis" @selected(old('reason')==='dialysis')>🩺 Dialysis</option>
                                    <option value="surgery" @selected(old('reason')==='surgery')>🏥 Surgery / Operation</option>
                                    <option value="pregnancy" @selected(old('reason')==='pregnancy')>🤰 Pregnancy / Delivery</option>
                                    <option value="regular_treatment" @selected(old('reason')==='regular_treatment')>🔄 Regular Treatment</option>
                                    <option value="other" @selected(old('reason')==='other')>📋 Other Medical Need</option>
                                </select>
                                @error('reason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Blood Group</label>

                                <select
                                    name="blood_group"
                                    class="form-select"
                                    required
                                >

                                    <option value="">
                                        Select Blood Group
                                    </option>

                                    @foreach([
                                        'A+',
                                        'A-',
                                        'B+',
                                        'B-',
                                        'AB+',
                                        'AB-',
                                        'O+',
                                        'O-'
                                    ] as $group)

                                        <option
                                            value="{{ $group }}"
                                            @selected(old('blood_group', $donor->blood_group ?? '') === $group)
                                        >
                                            {{ $group }}
                                        </option>

                                    @endforeach

                                </select>

                                @error('blood_group')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        {{-- HOSPITAL / CITY --}}

                        <div class="section-title">
                            🏥 Hospital Information
                        </div>

                        <div class="row g-3 mb-4">

                            <div class="col-md-7">

                                <label class="form-label">
                                    Hospital
                                </label>

                                <input
                                    type="text"
                                    name="hospital"
                                    class="form-control"
                                    value="{{ old('hospital') }}"
                                    placeholder="Hospital name"
                                    required
                                >

                                @error('hospital')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <div class="col-md-5">

                                <label class="form-label">
                                    City
                                </label>

                                <input
                                    type="text"
                                    name="city"
                                    class="form-control"
                                    value="{{ old('city', auth()->user()->city ?? '') }}"
                                    placeholder="City"
                                    required
                                >

                                @error('city')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label">Area / Locality</label>
                                <input type="text" name="area" class="form-control" value="{{ old('area') }}" placeholder="e.g. Satellite, Vastrapur, Maninagar" required>
                                @error('area')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- CONTACT / UNITS --}}

                        <div class="section-title">
                            📞 Requirement Details
                        </div>

                        <div class="row g-3 mb-4">

                            <div class="col-md-7">

                                <label class="form-label">
                                    Contact Number
                                </label>

                                <input
                                    type="text"
                                    name="contact"
                                    class="form-control"
                                    value="{{ old('contact', auth()->user()->phone ?? '') }}"
                                    placeholder="Contact number"
                                    required
                                >

                                @error('contact')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <div class="col-md-5">

                                <label class="form-label">
                                    Blood Units
                                </label>

                                <input
                                    type="number"
                                    name="units"
                                    class="form-control"
                                    min="1"
                                    max="20"
                                    value="{{ old('units', 1) }}"
                                    required
                                >

                                @error('units')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        {{-- URGENCY --}}

                        <div class="section-title">
                            🚨 Urgency
                        </div>
                        <label class="d-flex align-items-center gap-3 p-3 rounded-4 mb-3" style="background:#fff7ed;border:1px solid #fed7aa;cursor:pointer">
                            <input class="form-check-input" type="checkbox" name="emergency_mode" value="1" @checked(old('emergency_mode'))>
                            <span><strong>🚨 Activate Emergency Mode</strong><br><small class="text-muted">Prioritise this request for compatible donors across cities.</small></span>
                        </label>

                        <div class="row g-2 mb-4">

                            <div class="col-md-4">

                                <label class="urgency-card d-block">

                                    <input
                                        type="radio"
                                        name="urgency"
                                        value="normal"
                                        class="form-check-input me-2"
                                        @checked(old('urgency', 'normal') === 'normal')
                                    >

                                    <strong>Normal</strong>

                                    <div class="small text-muted mt-1">
                                        Regular requirement
                                    </div>

                                </label>

                            </div>


                            <div class="col-md-4">

                                <label class="urgency-card d-block">

                                    <input
                                        type="radio"
                                        name="urgency"
                                        value="urgent"
                                        class="form-check-input me-2"
                                        @checked(old('urgency') === 'urgent')
                                    >

                                    <strong>Urgent</strong>

                                    <div class="small text-muted mt-1">
                                        Required soon
                                    </div>

                                </label>

                            </div>


                            <div class="col-md-4">

                                <label class="urgency-card d-block">

                                    <input
                                        type="radio"
                                        name="urgency"
                                        value="critical"
                                        class="form-check-input me-2"
                                        @checked(old('urgency') === 'critical')
                                    >

                                    <strong class="text-danger">
                                        Critical 🚨
                                    </strong>

                                    <div class="small text-muted mt-1">
                                        Emergency requirement
                                    </div>

                                </label>

                            </div>

                        </div>

                        @error('urgency')
                            <div class="text-danger small mb-3">
                                {{ $message }}
                            </div>
                        @enderror


                        {{-- MESSAGE --}}

                        <div class="section-title">
                            💬 Additional Message
                        </div>

                        <textarea
                            name="message"
                            class="form-control mb-4"
                            rows="5"
                            maxlength="2000"
                            placeholder="Add any important information about the blood requirement..."
                        >{{ old('message') }}</textarea>

                        @error('message')
                            <div class="text-danger small mb-3">
                                {{ $message }}
                            </div>
                        @enderror


                        {{-- SUBMIT --}}

                        <button
                            type="submit"
                            class="submit-btn"
                        >
                            🩸 Submit Blood Request
                        </button>

                    </form>

                </div>

            </div>


            {{-- SIDE INFORMATION --}}

            <div class="col-lg-4">

                <div class="request-card">

                    <div class="section-title">
                        🛡️ Request Information
                    </div>

                    <div class="info-box mb-3">

                        <strong>
                            🔒 Your information is protected
                        </strong>

                        <p class="text-muted small mb-0 mt-2">
                            Your request details are used to connect
                            you with suitable blood donors.
                        </p>

                    </div>


                    <div class="info-box mb-3">

                        <strong>
                            🩸 Donor Matching
                        </strong>

                        <p class="text-muted small mb-0 mt-2">
                            Selecting a donor from Find Blood connects
                            your request directly to that donor.
                        </p>

                    </div>


                    <div class="info-box">

                        <strong>
                            🚨 Emergency
                        </strong>

                        <p class="text-muted small mb-0 mt-2">
                            For critical requirements, select
                            <strong>Critical</strong> urgency.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection