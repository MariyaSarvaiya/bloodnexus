@extends('layouts.app')

@section('title', 'My Requests | BloodNexus')

@section('content')

<style>
    .requests-page {
        padding: 35px 0 60px;
    }

    .requests-hero {
        background: linear-gradient(135deg, #8b1020, #df263d, #ff5a6f);
        border-radius: 28px;
        padding: 35px;
        color: white;
        box-shadow: 0 18px 45px rgba(223, 38, 61, .20);
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }

    .requests-hero::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
        right: -60px;
        top: -70px;
    }

    .request-card {
        background: rgba(255,255,255,.96);
        border: 1px solid rgba(0,0,0,.05);
        border-radius: 24px;
        padding: 25px;
        margin-bottom: 18px;
        box-shadow: 0 12px 35px rgba(0,0,0,.07);
        transition: .25s ease;
    }

    .request-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 18px 42px rgba(0,0,0,.11);
    }

    .blood-badge {
        width: 62px;
        height: 62px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff0f2;
        font-size: 29px;
        flex-shrink: 0;
    }

    .request-title {
        font-size: 20px;
        font-weight: 800;
        color: #20242b;
    }

    .request-info {
        color: #687080;
        font-size: 14px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 15px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 800;
    }

    .status-pending {
        background: #fff4d6;
        color: #9a6900;
    }

    .status-accepted {
        background: #e6f8ed;
        color: #168447;
    }

    .status-completed {
        background: #e8f0ff;
        color: #2863c7;
    }

    .status-cancelled {
        background: #eeeeee;
        color: #666;
    }

    .status-rejected {
        background: #ffe8eb;
        color: #c82333;
    }

    .request-detail {
        background: #f8f9fc;
        border-radius: 16px;
        padding: 12px 15px;
        height: 100%;
    }

    .detail-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: #8a919e;
        font-weight: 700;
    }

    .detail-value {
        font-weight: 700;
        color: #272d38;
        margin-top: 2px;
    }

    .cancel-btn {
        border: 0;
        background: #fff0f2;
        color: #d9253f;
        font-weight: 700;
        border-radius: 50px;
        padding: 10px 18px;
        transition: .2s ease;
    }

    .cancel-btn:hover {
        background: #df263d;
        color: white;
        transform: translateY(-1px);
    }

    .create-btn {
        background: white;
        color: #d9233c;
        border: 0;
        border-radius: 50px;
        padding: 12px 22px;
        font-weight: 800;
        box-shadow: 0 8px 22px rgba(0,0,0,.12);
    }

    .create-btn:hover {
        color: #a90f25;
        transform: translateY(-1px);
    }

    .empty-card {
        background: white;
        border-radius: 28px;
        padding: 65px 25px;
        text-align: center;
        box-shadow: 0 12px 35px rgba(0,0,0,.07);
    }

    .empty-icon {
        width: 90px;
        height: 90px;
        border-radius: 28px;
        background: #fff0f2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
        margin: 0 auto 20px;
    }

    @media (max-width: 767px) {
        .requests-hero {
            padding: 25px;
        }

        .request-card {
            padding: 19px;
        }
    }
</style>


<div class="container requests-page">

    {{-- HERO --}}
    <div class="requests-hero">

        <div class="d-flex flex-column flex-md-row justify-content-between
                    align-items-md-center gap-4 position-relative"
             style="z-index:2;">

            <div>

                <div class="small fw-bold opacity-75 mb-2">
                    🩸 BLOODNEXUS
                </div>

                <h1 class="fw-bold mb-2">
                    My Blood Requests
                </h1>

                <p class="mb-0 opacity-75">
                    Track your blood requests, status and emergency details
                    from one place.
                </p>

            </div>

            <a href="{{ route('blood.request') }}"
               class="create-btn text-decoration-none">

                <i class="bi bi-plus-circle-fill me-1"></i>
                New Request

            </a>

        </div>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))

        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- ERROR MESSAGE --}}
    @if(session('error'))

        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

        </div>

    @endif


    {{-- REQUESTS --}}
    @forelse($requests as $request)

        @php

            $status = strtolower($request->status ?? 'pending');

            $statusClass = match($status) {

                'accepted' => 'status-accepted',

                'completed' => 'status-completed',

                'cancelled' => 'status-cancelled',

                'rejected' => 'status-rejected',

                default => 'status-pending',

            };

            $statusIcon = match($status) {

                'accepted' => 'bi-check-circle-fill',

                'completed' => 'bi-patch-check-fill',

                'cancelled' => 'bi-x-circle-fill',

                'rejected' => 'bi-slash-circle-fill',

                default => 'bi-hourglass-split',

            };

        @endphp


        <div class="request-card">

            {{-- TOP --}}
            <div class="d-flex flex-column flex-md-row
                        justify-content-between gap-4">

                <div class="d-flex align-items-center gap-3">

                    <div class="blood-badge">
                        🩸
                    </div>

                    <div>

                        <div class="request-title">

                            {{ $request->blood_group }}

                            <span class="text-secondary">·</span>

                            {{ $request->patient_name }}

                        </div>

                        <div class="request-info mt-1">

                            <i class="bi bi-hospital me-1"></i>

                            {{ $request->hospital }}

                            <span class="mx-1">•</span>

                            <i class="bi bi-geo-alt me-1"></i>

                            {{ $request->city }}

                        </div>

                    </div>

                </div>


                <div class="text-md-end">

                    <span class="status-badge {{ $statusClass }}">

                        <i class="bi {{ $statusIcon }}"></i>

                        {{ ucfirst($status) }}

                    </span>

                </div>

            </div>


            {{-- DETAILS --}}
            <div class="row g-3 mt-3">

                <div class="col-6 col-md-3">

                    <div class="request-detail">

                        <div class="detail-label">
                            Blood Group
                        </div>

                        <div class="detail-value">
                            {{ $request->blood_group }}
                        </div>

                    </div>

                </div>


                <div class="col-6 col-md-3">

                    <div class="request-detail">

                        <div class="detail-label">
                            Units
                        </div>

                        <div class="detail-value">
                            {{ $request->units }}
                        </div>

                    </div>

                </div>


                <div class="col-6 col-md-3">

                    <div class="request-detail">

                        <div class="detail-label">
                            Urgency
                        </div>

                        <div class="detail-value">
                            {{ ucfirst($request->urgency) }}
                        </div>

                    </div>

                </div>


                <div class="col-6 col-md-3">

                    <div class="request-detail">

                        <div class="detail-label">
                            Contact
                        </div>

                        <div class="detail-value">

                            {{ $request->contact
                                ?? $request->contact_phone
                                ?? 'N/A' }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- MESSAGE --}}
            @if($request->message)

                <div class="mt-3 p-3 rounded-4"
                     style="background:#f8f9fc;">

                    <div class="detail-label mb-1">
                        Message
                    </div>

                    <div class="text-secondary">

                        <i class="bi bi-chat-left-text me-1"></i>

                        {{ $request->message }}

                    </div>

                </div>

            @endif


            {{-- ACTIONS --}}
            <div class="d-flex flex-column flex-sm-row
                        justify-content-between align-items-sm-center
                        gap-3 mt-4 pt-3"
                 style="border-top:1px solid #eeeeee;">

                <div class="small text-secondary">

                    <i class="bi bi-clock me-1"></i>

                    Request #{{ $request->id }}

                    @if($request->created_at)
                        · {{ $request->created_at->format('d M Y, h:i A') }}
                    @endif

                </div>


                {{-- ONLY PENDING REQUEST CAN BE CANCELLED --}}
                @if($status === 'pending')

                    <form method="POST"
                          action="{{ route(
                              'blood.requests.cancel',
                              $request->id
                          ) }}"
                          onsubmit="return confirm(
                              'Are you sure you want to cancel this blood request?'
                          );">

                        @csrf

                        @method('DELETE')

                        <button type="submit"
                                class="cancel-btn">

                            <i class="bi bi-x-circle me-1"></i>

                            Cancel Request

                        </button>

                    </form>

                @elseif($status === 'cancelled')

                    <span class="small text-secondary">

                        <i class="bi bi-info-circle me-1"></i>

                        This request has been cancelled.

                    </span>

                @else

                    <span class="small text-secondary">

                        <i class="bi bi-lock-fill me-1"></i>

                        Request cannot be cancelled now.

                    </span>

                @endif

            </div>

        </div>


    @empty

        {{-- EMPTY STATE --}}
        <div class="empty-card">

            <div class="empty-icon">
                📭
            </div>

            <h3 class="fw-bold">
                No Blood Requests Yet
            </h3>

            <p class="text-secondary mb-4">

                You haven't created any blood request yet.

                When you need blood, your request will appear here.

            </p>

            <a href="{{ route('blood.request') }}"
               class="btn btn-danger rounded-pill px-4 py-3 fw-bold">

                <i class="bi bi-droplet-fill me-1"></i>

                Create Blood Request

            </a>

        </div>

    @endforelse

</div>

@endsection