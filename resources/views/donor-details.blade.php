@extends('layouts.app')

@section('title', 'Donor Details')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <div class="bg-danger text-white p-4 text-center">

                    <div class="fs-1">
                        🩸
                    </div>

                    <h2 class="fw-bold mb-1">
                        {{ $donor->name }}
                    </h2>

                    <p class="mb-0">
                        Available Blood Donor
                    </p>

                </div>


                <div class="card-body p-4 p-md-5">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="p-3 bg-light rounded-4">

                                <small class="text-muted">
                                    Blood Group
                                </small>

                                <h4 class="fw-bold text-danger mb-0">
                                    {{ $donor->blood_group }}
                                </h4>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="p-3 bg-light rounded-4">

                                <small class="text-muted">
                                    City
                                </small>

                                <h5 class="fw-bold mb-0">
                                    📍 {{ $donor->city ?? 'Not provided' }}
                                </h5>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="p-3 bg-light rounded-4">

                                <small class="text-muted">
                                    Phone
                                </small>

                                <h5 class="fw-bold mb-0">
                                    📞 {{ $donor->phone }}
                                </h5>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="p-3 bg-light rounded-4">

                                <small class="text-muted">
                                    Email
                                </small>

                                <h6 class="fw-bold mb-0">
                                    ✉️ {{ $donor->email }}
                                </h6>

                            </div>

                        </div>

                    </div>


                    <hr class="my-4">


                    <div class="alert alert-success border-0 rounded-4">

                        <strong>● Available for Donation</strong>

                        <br>

                        <small>
                            This donor is currently marked as available.
                        </small>

                    </div>


                    <div class="d-flex gap-2 flex-wrap">

                        <a href="tel:{{ $donor->phone }}"
                           class="btn btn-danger rounded-pill px-4">

                            📞 Contact Donor

                        </a>


                        <a href="{{ route('blood.request') }}"
                           class="btn btn-outline-danger rounded-pill px-4">

                            🚨 Request Blood

                        </a>


                        <a href="{{ route('blood.search') }}"
                           class="btn btn-outline-secondary rounded-pill px-4">

                            ← Back to Search

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection