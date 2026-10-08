@extends('layouts.app')

@section('title', 'Notifications | BloodNexus')

@section('content')

<style>
    .notification-page {
        padding: 45px 0 70px;
    }

    .notification-hero {
        background: linear-gradient(135deg, #df263d, #9f1239);
        color: white;
        border-radius: 28px;
        padding: 32px;
        margin-bottom: 25px;
        box-shadow: 0 18px 45px rgba(223, 38, 61, .20);
        position: relative;
        overflow: hidden;
    }

    .notification-hero::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
        right: -70px;
        top: -90px;
    }

    .notification-icon {
        width: 62px;
        height: 62px;
        border-radius: 20px;
        background: rgba(255,255,255,.16);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        flex-shrink: 0;
    }

    .notification-card {
        background: rgba(255,255,255,.95);
        border: 1px solid rgba(0,0,0,.05);
        border-radius: 22px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 10px 30px rgba(20,25,40,.06);
        transition: .25s ease;
    }

    .notification-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(20,25,40,.10);
    }

    .notification-card.unread {
        border-left: 5px solid #df263d;
        background: #fffafb;
    }

    .notification-card.read {
        opacity: .82;
    }

    .notification-badge {
        width: 48px;
        height: 48px;
        border-radius: 15px;
        background: #fff0f2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        flex-shrink: 0;
    }

    .notification-title {
        font-weight: 800;
        color: #172033;
        font-size: 16px;
    }

    .notification-message {
        color: #697386;
        line-height: 1.6;
        margin-top: 4px;
    }

    .notification-time {
        font-size: 12px;
        color: #9aa1ad;
    }

    .unread-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #df263d;
        display: inline-block;
        margin-right: 6px;
    }

    .empty-notifications {
        background: white;
        border-radius: 25px;
        padding: 60px 25px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(20,25,40,.06);
    }

    .empty-notifications-icon {
        width: 85px;
        height: 85px;
        border-radius: 25px;
        background: #fff0f2;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 38px;
    }

    .premium-btn {
        border: 0;
        border-radius: 13px;
        padding: 9px 15px;
        font-weight: 700;
        transition: .2s;
    }

    .premium-btn:hover {
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .notification-page {
            padding: 25px 0 50px;
        }

        .notification-hero {
            padding: 25px;
            border-radius: 22px;
        }

        .notification-card {
            padding: 16px;
        }
    }
</style>


<div class="container notification-page">

    {{-- HERO --}}
    <div class="notification-hero">

        <div class="d-flex align-items-center justify-content-between gap-3">

            <div class="d-flex align-items-center gap-3">

                <div class="notification-icon">
                    🔔
                </div>

                <div>

                    <h1 class="fw-bold mb-1">
                        Notifications
                    </h1>

                    <p class="mb-0 opacity-75">
                        Stay updated about your blood requests,
                        donors and important activity.
                    </p>

                </div>

            </div>

            @php
                $unreadCount = $notifications->where('is_read', false)->count();
            @endphp

            @if($unreadCount > 0)

                <div class="badge bg-light text-danger rounded-pill px-3 py-2">
                    {{ $unreadCount }} Unread
                </div>

            @endif

        </div>

    </div>


    {{-- FLASH SUCCESS --}}

    @if(session('success'))

        <div class="alert alert-success border-0 rounded-4 shadow-sm">
            ✅ {{ session('success') }}
        </div>

    @endif


    {{-- MARK ALL AS READ --}}

    @if($unreadCount > 0)

        <div class="d-flex justify-content-end mb-3">

            <form method="POST"
                  action="{{ route('notifications.read-all') }}">

                @csrf

                <button type="submit"
                        class="btn btn-outline-danger rounded-pill px-4 fw-semibold">

                    ✓ Mark all as read

                </button>

            </form>

        </div>

    @endif


    {{-- NOTIFICATIONS --}}

    @forelse($notifications as $notification)

        @php
            $isUnread = !$notification->is_read;
        @endphp

        <div class="notification-card {{ $isUnread ? 'unread' : 'read' }}">

            <div class="d-flex align-items-start gap-3">

                {{-- ICON --}}

                <div class="notification-badge">

                    @if(str_contains(strtolower($notification->message ?? ''), 'blood'))
                        🩸
                    @elseif(str_contains(strtolower($notification->message ?? ''), 'message'))
                        💬
                    @elseif(str_contains(strtolower($notification->message ?? ''), 'request'))
                        📋
                    @else
                        🔔
                    @endif

                </div>


                {{-- CONTENT --}}

                <div class="flex-grow-1">

                    <div class="d-flex justify-content-between gap-3">

                        <div class="notification-title">

                            @if($isUnread)
                                <span class="unread-dot"></span>
                            @endif

                            {{ $notification->title ?? 'Notification' }}

                        </div>

                        <div class="notification-time text-nowrap">

                            {{ $notification->created_at?->diffForHumans() }}

                        </div>

                    </div>


                    <div class="notification-message">

                        {{ $notification->message }}

                    </div>


                    {{-- ACTION --}}

                    @if($isUnread)

                        <form method="POST"
                              action="{{ route('notifications.read', $notification->id) }}"
                              class="mt-3">

                            @csrf

                            <button type="submit"
                                    class="btn btn-sm btn-outline-danger premium-btn">

                                ✓ Mark as read

                            </button>

                        </form>

                    @else

                        <div class="mt-3 small text-success fw-semibold">

                            ✓ Read

                        </div>

                    @endif

                </div>

            </div>

        </div>

    @empty

        <div class="empty-notifications">

            <div class="empty-notifications-icon">
                🔔
            </div>

            <h3 class="fw-bold">
                No notifications yet
            </h3>

            <p class="text-secondary mb-4">
                Important updates about your blood requests
                will appear here.
            </p>

            <a href="{{ route('dashboard') }}"
               class="btn btn-danger rounded-pill px-4 py-2 fw-bold">

                📊 Back to Dashboard

            </a>

        </div>

    @endforelse

</div>

@endsection