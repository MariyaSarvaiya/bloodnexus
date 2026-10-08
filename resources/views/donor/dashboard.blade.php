@extends('layouts.app')

@section('title', 'Donor Dashboard | BloodNexus')

@section('content')

@php
    $user = auth()->user();

    $donor = \App\Models\Donor::where(
        'user_id',
        $user->id
    )->first();

    $totalDonations = \App\Models\BloodRequest::where(
        'donor_id',
        optional($donor)->id
    )->where(
        'status',
        'completed'
    )->count();

    $urgentRequests = \App\Models\BloodRequest::where(
        'donor_id',
        optional($donor)->id
    )->whereIn(
        'status',
        ['pending', 'accepted']
    )->whereIn(
        'urgency',
        ['urgent', 'critical']
    )->count();

    $nearbyBloodRequests = \App\Models\BloodRequest::where(
        'donor_id',
        optional($donor)->id
    )->whereIn(
        'status',
        ['pending', 'accepted']
    )->latest()->get();

    $nearbyRequests = $nearbyBloodRequests->count();

    $completedRequests = $totalDonations;
@endphp


<style>

.donor-page {
    position: relative;
    overflow: hidden;
    background:
        radial-gradient(circle at 8% 5%, rgba(220,38,38,.12), transparent 24%),
        radial-gradient(circle at 92% 18%, rgba(124,58,237,.10), transparent 25%),
        linear-gradient(135deg,#f8fafc 0%,#fff 48%,#fff4f5 100%);
    min-height: calc(100vh - 75px);
    padding: 35px 0 70px;
}

.donor-page::before, .donor-page::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    filter: blur(2px);
}
.donor-page::before { width: 280px; height: 280px; right: -120px; top: 60px; background: rgba(220,38,38,.08); }
.donor-page::after { width: 220px; height: 220px; left: -110px; bottom: 100px; background: rgba(124,58,237,.07); }
.donor-hero::before {
    content:'🩸'; position:absolute; right:55px; bottom:-30px; font-size:145px; opacity:.10; transform:rotate(-15deg);
}
.donor-hero::after {
    content:'♥'; position:absolute; right:220px; top:-55px; font-size:150px; opacity:.07;
}
.donor-hero {
    position: relative;
    overflow: hidden;
    border-radius: 28px;
    padding: 35px;
    color: #fff;
    background:
        linear-gradient(
            135deg,
            #111827 0%,
            #7f1d1d 48%,
            #dc2626 100%
        );
    box-shadow: 0 20px 50px rgba(127,29,29,.22);
}

.donor-avatar {
    width: 72px;
    height: 72px;
    border-radius: 22px;
    background: rgba(255,255,255,.14);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 34px;
}

.stat-card,
.action-card,
.profile-card,
.section-card {
    background: #fff;
    border: 1px solid #edf0f4;
    border-radius: 22px;
    box-shadow: 0 12px 32px rgba(20,25,40,.06);
    backdrop-filter: blur(8px);
}

.stat-card {
    padding: 22px;
    height: 100%;
}

.stat-icon,
.action-icon,
.blood-group {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff1f2;
    color: #dc2626;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 15px;
    font-size: 22px;
}

.stat-number {
    font-size: 28px;
    font-weight: 800;
}

.section-card {
    overflow: hidden;
}

.section-header {
    padding: 21px 24px;
    border-bottom: 1px solid #edf0f4;
}

.request-card {
    padding: 20px;
    border-bottom: 1px solid #edf0f4;
}

.blood-group {
    width: 54px;
    height: 54px;
    border-radius: 17px;
    font-weight: 800;
}

.urgency-critical {
    background: #fee2e2;
    color: #b91c1c;
}

.urgency-urgent {
    background: #fef3c7;
    color: #92400e;
}

.urgency-normal {
    background: #dcfce7;
    color: #166534;
}

.request-meta {
    color: #6b7280;
    font-size: 13px;
}

.action-card {
    padding: 22px;
    height: 100%;
    transition: .25s;
}

.action-card:hover {
    transform: translateY(-4px);
}

.action-icon {
    width: 50px;
    height: 50px;
    border-radius: 16px;
    font-size: 23px;
    margin-bottom: 15px;
}

.profile-card {
    background: linear-gradient(135deg,#fff,#fff7f7);
    border-color: #fee2e2;
    padding: 24px;
}

.profile-row {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding: 11px 0;
    border-bottom: 1px solid #f1f1f1;
}

.profile-row:last-child {
    border-bottom: 0;
}

.profile-label {
    color: #6b7280;
    font-size: 13px;
}

.profile-value {
    font-weight: 700;
    text-align: right;
}

</style>


<div class="container donor-page">

    {{-- HERO --}}

    <div class="donor-hero mb-4">

        <div class="d-flex align-items-center gap-3">

            <div class="donor-avatar">
                🩸
            </div>

            <div>

                <span class="badge bg-success rounded-pill mb-2">
                    ● Donor Account Active
                </span>

                <h1 class="fw-bold mb-1">
                    Welcome, {{ $user->name }} 👋
                </h1>

                <p class="mb-0 opacity-75">
                    Help save lives by responding to nearby blood requirements.
                </p>

            </div>

        </div>

    </div>


    {{-- SUCCESS --}}

    @if(session('success'))

        <div class="alert alert-success rounded-4 shadow-sm">
            ✅ {{ session('success') }}
        </div>

    @endif


    {{-- ERROR --}}

    @if(session('error'))

        <div class="alert alert-danger rounded-4 shadow-sm">
            ⚠️ {{ session('error') }}
        </div>

    @endif


    {{-- STATISTICS --}}

    <div class="row g-3 mb-4">

        <div class="col-6 col-lg-3">

            <div class="stat-card">

                <div class="stat-icon mb-3">
                    🩸
                </div>

                <div class="stat-number">
                    {{ $totalDonations }}
                </div>

                <div class="text-secondary small">
                    Total Donations
                </div>

            </div>

        </div>


        <div class="col-6 col-lg-3">

            <div class="stat-card">

                <div class="stat-icon mb-3">
                    🚨
                </div>

                <div class="stat-number">
                    {{ $urgentRequests }}
                </div>

                <div class="text-secondary small">
                    Urgent Requests
                </div>

            </div>

        </div>


        <div class="col-6 col-lg-3">

            <div class="stat-card">

                <div class="stat-icon mb-3">
                    📍
                </div>

                <div class="stat-number">
                    {{ $nearbyRequests }}
                </div>

                <div class="text-secondary small">
                    Active Requests
                </div>

            </div>

        </div>


        <div class="col-6 col-lg-3">

            <div class="stat-card">

                <div class="stat-icon mb-3">
                    ✅
                </div>

                <div class="stat-number">
                    {{ $completedRequests }}
                </div>

                <div class="text-secondary small">
                    Completed Donations
                </div>

            </div>

        </div>

    </div>


    {{-- MATCHING REQUESTS --}}

    <div class="section-card mb-4">

        <div class="section-header">

            <h4 class="fw-bold mb-1">
                Matching Blood Requests
            </h4>

            <div class="small text-secondary">
                Blood requests assigned to your donor profile.
            </div>

        </div>

        <div class="p-4">

            @if($nearbyBloodRequests->count())

                <div class="alert alert-danger rounded-4">

                    🎯
                    <strong>
                        {{ $nearbyBloodRequests->count() }}
                    </strong>

                    active blood request(s) are waiting for your response.

                </div>

                <a
                    href="{{ route('donor.requests') }}"
                    class="btn btn-danger rounded-pill px-4"
                >
                    📩 View Blood Requests
                </a>

            @else

                <div class="text-center py-4">

                    <div style="font-size:48px;">
                        🩸
                    </div>

                    <h5 class="fw-bold mt-3">
                        No active blood requests
                    </h5>

                    <p class="text-secondary mb-0">
                        New requests will appear here when a Blood Need user requests your blood.
                    </p>

                </div>

            @endif

        </div>

    </div>


    <div class="row g-4">

        {{-- REQUESTS --}}

        <div class="col-lg-8">

            <div class="section-card">

                <div class="section-header d-flex justify-content-between">

                    <div>

                        <h4 class="fw-bold mb-1">
                            Nearby Blood Requests
                        </h4>

                        <div class="small text-secondary">
                            Active requests assigned to you
                        </div>

                    </div>

                    <span class="badge rounded-pill text-bg-danger px-3 py-2">
                        {{ $nearbyBloodRequests->count() }}
                    </span>

                </div>


                @forelse($nearbyBloodRequests as $request)

                    <div class="request-card">

                        <div class="d-flex gap-3 align-items-center">

                            <div class="blood-group">
                                {{ $request->blood_group }}
                            </div>


                            <div class="flex-grow-1">

                                <div class="d-flex flex-wrap gap-2">

                                    <h5 class="fw-bold mb-0">
                                        {{ $request->patient_name }}
                                    </h5>

                                    <span class="badge rounded-pill
                                        @if($request->urgency === 'critical')
                                            urgency-critical
                                        @elseif($request->urgency === 'urgent')
                                            urgency-urgent
                                        @else
                                            urgency-normal
                                        @endif
                                    ">
                                        {{ ucfirst($request->urgency) }}
                                    </span>

                                </div>

                                <div class="request-meta mt-1">
                                    🏥 {{ $request->hospital }}
                                </div>

                                <div class="request-meta mt-1">
                                    📍 {{ $request->city }}
                                </div>

                                <div class="request-meta mt-1">
                                    🩸 {{ $request->blood_group }}
                                    ·
                                    📞 {{ $request->contact_phone }}
                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="text-center p-5">

                        <div style="font-size:48px;">
                            🩸
                        </div>

                        <h5 class="fw-bold mt-3">
                            No nearby requests
                        </h5>

                        <p class="text-secondary mb-0">
                            There are currently no active requests assigned to you.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- DONOR PROFILE --}}

        <div class="col-lg-4">

            <div class="profile-card">

                <div class="d-flex align-items-center gap-3 mb-3">

                    <div class="blood-group">
                        {{ $donor->blood_group ?? $user->blood_group ?? '—' }}
                    </div>

                    <div>

                        <h5 class="fw-bold mb-1">
                            Donor Profile
                        </h5>

                        @if($donor && $donor->is_available)

                            <div class="small text-success">
                                ● Available to Help
                            </div>

                        @else

                            <div class="small text-secondary">
                                ● Currently Unavailable
                            </div>

                        @endif

                    </div>

                </div>


                <div class="profile-row">

                    <span class="profile-label">
                        Name
                    </span>

                    <span class="profile-value">
                        {{ $user->name }}
                    </span>

                </div>


                <div class="profile-row">

                    <span class="profile-label">
                        Blood Group
                    </span>

                    <span class="profile-value">
                        {{ $donor->blood_group ?? $user->blood_group ?? '—' }}
                    </span>

                </div>


                <div class="profile-row">

                    <span class="profile-label">
                        City
                    </span>

                    <span class="profile-value">
                        {{ $donor->city ?? $user->city ?? '—' }}
                    </span>

                </div>


                <div class="profile-row">

                    <span class="profile-label">
                        Phone
                    </span>

                    <span class="profile-value">
                        {{ $donor->phone ?? $user->phone ?? '—' }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- QUICK ACTIONS --}}

    <div class="mt-5">

        <h4 class="fw-bold mb-3">
            Donor Quick Actions
        </h4>

        <div class="row g-3">

            {{-- BLOOD REQUESTS --}}

            <div class="col-md-4">

                <a
                    href="{{ route('donor.requests') }}"
                    class="text-decoration-none text-dark"
                >

                    <div class="action-card">

                        <div class="action-icon">
                            📩
                        </div>

                        <h5 class="fw-bold">
                            Blood Requests
                        </h5>

                        <p class="small text-secondary mb-0">
                            View and respond to blood requests sent to you.
                        </p>

                    </div>

                </a>

            </div>


            {{-- NOTIFICATIONS --}}

            <div class="col-md-4">

                <a
                    href="{{ route('notifications.index') }}"
                    class="text-decoration-none text-dark"
                >

                    <div class="action-card">

                        <div class="action-icon">
                            🔔
                        </div>

                        <h5 class="fw-bold">
                            Notifications
                        </h5>

                        <p class="small text-secondary mb-0">
                            View new blood requests and donation updates.
                        </p>

                    </div>

                </a>

            </div>


            {{-- MESSAGES --}}

            <div class="col-md-4">

                <a
                    href="{{ route('messages.index') }}"
                    class="text-decoration-none text-dark"
                >

                    <div class="action-card">

                        <div class="action-icon">
                            💬
                        </div>

                        <h5 class="fw-bold">
                            Messages
                        </h5>

                        <p class="small text-secondary mb-0">
                            Communicate with Blood Need users.
                        </p>

                    </div>

                </a>

            </div>

        </div>

    </div>

</div>

@endsection