@extends('layouts.app')

@section('title', 'Notifications')

@section('content')

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">
                🔔 Notifications
            </h1>

            <p class="text-muted mb-0">
                Stay updated with your blood requests and donor activities.
            </p>
        </div>

        @if($notifications->count() > 0)

            <form
                action="{{ route('notifications.read-all') }}"
                method="POST"
            >
                @csrf

                <button class="btn btn-outline-danger">
                    Mark All as Read
                </button>
            </form>

        @endif

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @forelse($notifications as $notification)

        <div class="card border-0 shadow-sm rounded-4 mb-3
            {{ !$notification->is_read ? 'border-start border-danger border-4' : '' }}">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between">

                    <div>

                        <h5 class="fw-bold">

                            @if($notification->type === 'blood_request')
                                🩸
                            @elseif($notification->type === 'donor_request')
                                ❤️
                            @elseif($notification->type === 'accepted')
                                ✅
                            @elseif($notification->type === 'rejected')
                                ❌
                            @elseif($notification->type === 'completed')
                                🎉
                            @elseif($notification->type === 'emergency')
                                🚨
                            @else
                                🔔
                            @endif

                            {{ $notification->title }}

                            @if(!$notification->is_read)

                                <span class="badge bg-danger">
                                    NEW
                                </span>

                            @endif

                        </h5>


                        <p class="text-muted mb-2">
                            {{ $notification->message }}
                        </p>


                        <small class="text-muted">
                            {{ $notification->created_at->diffForHumans() }}
                        </small>

                    </div>


                    <div>

                        @if(!$notification->is_read)

                            <form
                                action="{{ route('notifications.read', $notification) }}"
                                method="POST"
                                class="mb-2"
                            >

                                @csrf

                                <button
                                    class="btn btn-sm btn-outline-primary"
                                    title="Mark as read"
                                >
                                    ✓
                                </button>

                            </form>

                        @endif


                        <form
                            action="{{ route('notifications.delete', $notification) }}"
                            method="POST"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn btn-sm btn-outline-danger"
                                title="Delete"
                            >
                                🗑
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    @empty

        <div class="text-center py-5">

            <div style="font-size: 60px;">
                🔔
            </div>

            <h4 class="fw-bold mt-3">
                No Notifications
            </h4>

            <p class="text-muted">
                You're all caught up!
            </p>

        </div>

    @endforelse


    <div class="mt-4">
        {{ $notifications->links() }}
    </div>

</div>

@endsection