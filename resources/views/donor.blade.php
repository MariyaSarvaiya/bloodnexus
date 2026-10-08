@extends('layouts.app')

@section('title', 'Become Donor')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card border-0 shadow-lg rounded-4">

                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">

                        <div class="fs-1">🩸</div>

                        <h2 class="fw-bold text-danger">
                            Become a Blood Donor
                        </h2>

                        <p class="text-muted">
                            Register as a donor and help save lives.
                        </p>

                    </div>

                    {{-- Success Message --}}
                    @if(session('success'))

                        <div class="alert alert-success alert-dismissible fade show">

                            <i class="fa-solid fa-circle-check me-2"></i>

                            {{ session('success') }}

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert">
                            </button>

                        </div>

                    @endif


                    {{-- Validation Errors --}}
                    @if ($errors->any())

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    {{-- Donor Form --}}
                    <form action="{{ route('donor.store') }}" method="POST">

                        @csrf


                        {{-- Name --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control form-control-lg"
                                value="{{ old('name') }}"
                                placeholder="Enter your name"
                                required
                            >

                        </div>


                        {{-- Email --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control form-control-lg"
                                value="{{ old('email') }}"
                                placeholder="Enter your email"
                                required
                            >

                        </div>


                        {{-- Phone --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Phone
                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control form-control-lg"
                                value="{{ old('phone') }}"
                                placeholder="Enter mobile number"
                                required
                            >

                        </div>


                        {{-- Blood Group --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Blood Group
                            </label>

                            <select
                                name="blood_group"
                                class="form-select form-select-lg"
                                required
                            >

                                <option value="">Select Blood Group</option>

                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>

                            </select>

                        </div>


                        {{-- City --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                City
                            </label>

                            <input
                                type="text"
                                name="city"
                                class="form-control form-control-lg"
                                value="{{ old('city') }}"
                                placeholder="Enter your city"
                            >

                        </div>


                        {{-- Address --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Address
                            </label>

                            <textarea
                                name="address"
                                class="form-control"
                                rows="3"
                                placeholder="Enter your address"
                            >{{ old('address') }}</textarea>

                        </div>


                        {{-- Last Donation Date --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Last Donation Date
                            </label>

                            <input
                                type="date"
                                name="last_donation_date"
                                class="form-control form-control-lg"
                                value="{{ old('last_donation_date') }}"
                            >

                        </div>


                        {{-- Submit --}}
                        <button
                            type="submit"
                            class="btn btn-danger btn-lg w-100 rounded-pill"
                        >

                            🩸 Register as Donor

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection