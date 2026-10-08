@extends('layouts.app')

@section('title', 'Request Blood Donor - BloodNexus')

@section('content')

<div class="container py-5">

    {{-- ================================
         PAGE HEADER
    ================================= --}}
    <div class="text-center mb-5">

        <div style="font-size: 55px;">
            🩸
        </div>

        <h1 class="fw-bold text-danger">
            Request Blood Donor
        </h1>

        <p class="text-muted">
            Send a blood request directly to
            <strong>{{ $donor->name }}</strong>.
        </p>

    </div>


    {{-- ================================
         ERROR MESSAGE
    ================================= --}}
    @if(session('error'))

        <div class="alert alert-danger rounded-4">
            {{ session('error') }}
        </div>

    @endif


    {{-- ================================
         VALIDATION ERRORS
    ================================= --}}
    @if($errors->any())

        <div class="alert alert-danger rounded-4">

            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="row g-4 justify-content-center">

        {{-- ================================
             DONOR INFORMATION
        ================================= --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-lg rounded-4 h-100">

                <div class="card-body p-4 text-center">

                    <div style="font-size: 65px;">
                        ❤️
                    </div>

                    <h3 class="fw-bold mt-3">
                        {{ $donor->name }}
                    </h3>

                    <div class="my-3">

                        <span class="badge bg-danger fs-5 px-4 py-2 rounded-pill">

                            🩸 {{ $donor->blood_group }}

                        </span>

                    </div>

                    <hr>

                    <p class="mb-2">

                        <strong>📍 City</strong><br>

                        <span class="text-muted">
                            {{ $donor->city }}
                        </span>

                    </p>


                    <p class="mb-2">

                        <strong>📞 Contact</strong><br>

                        <span class="text-muted">
                            Contact details will be available after acceptance.
                        </span>

                    </p>


                    @if($donor->is_available)

                        <div class="alert alert-success rounded-4 mt-4">

                            🟢 <strong>Available</strong>

                            <br>

                            <small>
                                This donor is currently available.
                            </small>

                        </div>

                    @else

                        <div class="alert alert-secondary rounded-4">

                            ⚪ Currently unavailable

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- ================================
             REQUEST FORM
        ================================= --}}
        <div class="col-lg-7">

            <div class="card border-0 shadow-lg rounded-4">

                <div class="card-body p-4 p-md-5">

                    <h3 class="fw-bold mb-4">

                        🚨 Blood Requirement Details

                    </h3>


                    <form
                        action="{{ route('donor.request.store', $donor) }}"
                        method="POST"
                    >

                        @csrf


                        {{-- PATIENT NAME --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Patient Name
                            </label>

                            <input
                                type="text"
                                name="patient_name"
                                class="form-control form-control-lg"
                                value="{{ old('patient_name') }}"
                                placeholder="Enter patient name"
                                required
                            >

                        </div>


                        {{-- BLOOD GROUP --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Required Blood Group
                            </label>

                            <select
                                name="blood_group"
                                class="form-select form-select-lg"
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
                                        @selected(
                                            old('blood_group', $donor->blood_group)
                                            === $group
                                        )
                                    >
                                        {{ $group }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="row">

                            {{-- HOSPITAL --}}
                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label fw-semibold">
                                        Hospital
                                    </label>

                                    <input
                                        type="text"
                                        name="hospital"
                                        class="form-control form-control-lg"
                                        value="{{ old('hospital') }}"
                                        placeholder="Hospital name"
                                        required
                                    >

                                </div>

                            </div>

                            {{-- CITY --}}
                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label fw-semibold">
                                        City
                                    </label>

                                    <input
                                        type="text"
                                        name="city"
                                        class="form-control form-control-lg"
                                        value="{{ old('city', $donor->city) }}"
                                        placeholder="City"
                                        required
                                    >

                                </div>

                            </div>

                        </div>


                        {{-- URGENCY --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Urgency Level
                            </label>

                            <select
                                name="urgency"
                                class="form-select form-select-lg"
                                required
                            >

                                <option value="">
                                    Select Urgency
                                </option>

                                <option
                                    value="normal"
                                    @selected(old('urgency') === 'normal')
                                >
                                    🟢 Normal
                                </option>

                                <option
                                    value="urgent"
                                    @selected(old('urgency') === 'urgent')
                                >
                                    🟠 Urgent
                                </option>

                                <option
                                    value="critical"
                                    @selected(old('urgency') === 'critical')
                                >
                                    🔴 Critical Emergency
                                </option>

                            </select>

                        </div>


                        {{-- CONTACT PHONE --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Contact Phone
                            </label>

                            <input
                                type="text"
                                name="contact_phone"
                                class="form-control form-control-lg"
                                value="{{ old('contact_phone', auth()->user()->phone) }}"
                                placeholder="Your contact number"
                                required
                            >

                        </div>


                        {{-- REASON --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Reason / Additional Information
                            </label>

                            <textarea
                                name="reason"
                                class="form-control"
                                rows="4"
                                placeholder="Explain the blood requirement..."
                            >{{ old('reason') }}</textarea>

                        </div>


                        {{-- SUBMIT --}}
                        <button
                            type="submit"
                            class="btn btn-danger btn-lg w-100 rounded-pill fw-semibold"
                        >

                            🩸 Send Blood Request

                        </button>


                        <div class="text-center mt-3">

                            <small class="text-muted">

                                🔒 Your contact information will only be
                                shared with the donor when the request is accepted.

                            </small>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection