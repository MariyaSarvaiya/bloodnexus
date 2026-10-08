@extends('layouts.app')

@section('title', 'Chat | BloodNexus')

@section('content')

<style>

    body {
        background: #f5f7fb;
    }

    .chat-page {
        min-height: calc(100vh - 75px);
        padding: 30px 0 60px;
        background:
            radial-gradient(
                circle at 0% 0%,
                rgba(220,38,38,.08),
                transparent 30%
            ),
            radial-gradient(
                circle at 100% 100%,
                rgba(124,58,237,.06),
                transparent 30%
            ),
            #f6f8fc;
    }


    /* MAIN CHAT */

    .chat-container {
        max-width: 1050px;
        margin: auto;
        background: #fff;
        border-radius: 28px;
        overflow: hidden;
        border: 1px solid #edf0f4;
        box-shadow: 0 25px 70px rgba(20,25,40,.10);
    }


    /* HEADER */

    .chat-header {
        position: relative;
        overflow: hidden;
        padding: 22px 25px;
        color: #fff;

        background:
            linear-gradient(
                135deg,
                #111827,
                #7f1d1d 55%,
                #dc2626
            );
    }


    .chat-header::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        right: -100px;
        top: -130px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
    }


    .chat-back {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        color: #fff;
        text-decoration: none;

        background: rgba(255,255,255,.13);
        border: 1px solid rgba(255,255,255,.15);

        transition: .2s;
    }


    .chat-back:hover {
        color: #fff;
        background: rgba(255,255,255,.22);
        transform: translateX(-2px);
    }


    .chat-avatar {
        width: 52px;
        height: 52px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 17px;

        background:
            linear-gradient(
                135deg,
                #ef4444,
                #991b1b
            );

        color: #fff;

        font-size: 20px;
        font-weight: 900;

        box-shadow:
            0 8px 20px rgba(0,0,0,.20);
    }


    .chat-user-name {
        font-weight: 900;
        font-size: 17px;
    }


    .chat-user-role {
        display: inline-flex;
        align-items: center;
        gap: 5px;

        margin-top: 2px;

        font-size: 11px;
        font-weight: 800;

        padding: 4px 9px;

        border-radius: 999px;

        background: rgba(255,255,255,.13);
    }


    /* BLOOD CONNECTION */

    .connection-card {
        margin: 15px 20px 0;

        padding: 13px 16px;

        border-radius: 17px;

        background:
            linear-gradient(
                135deg,
                #fff7f7,
                #fff
            );

        border: 1px solid #fee2e2;
    }


    .connection-title {
        font-size: 12px;
        font-weight: 900;
        color: #dc2626;
    }


    .connection-text {
        font-size: 13px;
        color: #6b7280;
    }


    /* CHAT BODY */

    .chat-body {
        height: 520px;
        overflow-y: auto;

        padding: 28px;

        background:
            radial-gradient(
                circle at 20% 20%,
                rgba(220,38,38,.025),
                transparent 25%
            ),
            #fafbfc;
    }


    .chat-date {
        text-align: center;
        margin-bottom: 20px;
    }


    .chat-date span {
        display: inline-block;

        padding: 6px 12px;

        border-radius: 999px;

        background: #eef1f5;

        color: #737b89;

        font-size: 11px;
        font-weight: 700;
    }


    /* MESSAGE */

    .message-row {
        display: flex;
        margin-bottom: 13px;
    }


    .message-row.mine {
        justify-content: flex-end;
    }


    .message-row.theirs {
        justify-content: flex-start;
    }


    .message-bubble {
        max-width: 70%;

        padding: 12px 15px;

        border-radius: 19px;

        position: relative;

        font-size: 14px;

        line-height: 1.5;
    }


    .message-row.mine .message-bubble {

        color: #fff;

        background:
            linear-gradient(
                135deg,
                #dc2626,
                #b91c1c
            );

        border-bottom-right-radius: 5px;

        box-shadow:
            0 8px 18px rgba(220,38,38,.15);
    }


    .message-row.theirs .message-bubble {

        color: #273044;

        background: #fff;

        border: 1px solid #edf0f4;

        border-bottom-left-radius: 5px;

        box-shadow:
            0 6px 18px rgba(20,25,40,.05);
    }


    .message-text {
        white-space: pre-wrap;
        word-break: break-word;
    }


    .message-time {
        display: block;

        margin-top: 5px;

        font-size: 10px;

        opacity: .65;

        text-align: right;
    }


    .message-status {
        font-size: 10px;
        margin-left: 3px;
    }


    /* EMPTY */

    .chat-empty {
        height: 100%;

        display: flex;
        align-items: center;
        justify-content: center;

        text-align: center;
    }


    .chat-empty-icon {
        width: 80px;
        height: 80px;

        margin: auto;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 25px;

        background: #fff1f2;

        font-size: 38px;
    }


    /* COMPOSER */

    .chat-composer {

        padding: 18px 20px;

        border-top: 1px solid #edf0f4;

        background: #fff;
    }


    .message-form {
        display: flex;
        align-items: flex-end;
        gap: 10px;
    }


    .message-input {

        flex: 1;

        resize: none;

        min-height: 48px;
        max-height: 120px;

        padding: 13px 17px;

        border-radius: 17px;

        border: 1px solid #e5e7eb;

        outline: none;

        font-size: 14px;

        background: #f8fafc;

        transition: .2s;
    }


    .message-input:focus {

        background: #fff;

        border-color: #fca5a5;

        box-shadow:
            0 0 0 4px rgba(220,38,38,.07);
    }


    .send-button {

        width: 50px;
        height: 50px;

        border: 0;

        border-radius: 16px;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #fff;

        font-size: 19px;

        background:
            linear-gradient(
                135deg,
                #dc2626,
                #991b1b
            );

        box-shadow:
            0 10px 22px rgba(220,38,38,.22);

        transition: .2s;
    }


    .send-button:hover {

        transform: translateY(-2px);

        box-shadow:
            0 14px 28px rgba(220,38,38,.28);
    }


    .send-button:active {
        transform: scale(.96);
    }


    /* MOBILE */

    @media(max-width: 768px) {

        .chat-page {
            padding: 15px 0 35px;
        }

        .chat-container {
            border-radius: 20px;
        }

        .chat-body {
            height: 58vh;
            padding: 20px 15px;
        }

        .message-bubble {
            max-width: 85%;
        }

        .chat-header {
            padding: 17px;
        }

        .connection-card {
            margin-left: 12px;
            margin-right: 12px;
        }

    }

</style>


<div class="chat-page">

    <div class="container">

        <div class="chat-container">


            {{-- =====================================================
                 HEADER
            ====================================================== --}}

            <div class="chat-header">

                <div
                    class="d-flex align-items-center gap-3 position-relative"
                    style="z-index:2;"
                >

                    <a
                        href="{{ route('messages.index') }}"
                        class="chat-back"
                    >
                        <i class="bi bi-arrow-left"></i>
                    </a>


                    <div class="chat-avatar">

                        {{ strtoupper(
                            substr(
                                $otherUser->name,
                                0,
                                1
                            )
                        ) }}

                    </div>


                    <div>

                        <div class="chat-user-name">

                            {{ $otherUser->name }}

                        </div>


                        <div class="chat-user-role">

                            @if($otherUser->role === 'donor')

                                🩸 Donor

                            @else

                                ❤️ Blood Seeker

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 BLOOD REQUEST CONNECTION
            ====================================================== --}}

            @if($bloodRequest)

                <div class="connection-card">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            style="
                                width:42px;
                                height:42px;
                                border-radius:14px;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                background:#fee2e2;
                                font-size:20px;
                            "
                        >
                            🩸
                        </div>


                        <div class="flex-grow-1">

                            <div class="connection-title">

                                CONNECTED BLOOD REQUEST

                            </div>

                            <div class="connection-text">

                                {{ $bloodRequest->blood_group }}

                                ·

                                {{ $bloodRequest->hospital }}

                                ·

                                {{ ucfirst(
                                    $bloodRequest->status
                                ) }}

                            </div>

                        </div>


                        <span class="badge bg-success rounded-pill">

                            Connected

                        </span>

                    </div>

                </div>

            @endif


            {{-- =====================================================
                 SUCCESS / ERROR
            ====================================================== --}}

            @if(session('success'))

                <div class="alert alert-success border-0 rounded-0 mb-0">

                    <i class="bi bi-check-circle-fill me-2"></i>

                    {{ session('success') }}

                </div>

            @endif


            @if(session('error'))

                <div class="alert alert-danger border-0 rounded-0 mb-0">

                    <i class="bi bi-exclamation-triangle-fill me-2"></i>

                    {{ session('error') }}

                </div>

            @endif


            {{-- =====================================================
                 CHAT BODY
            ====================================================== --}}

            <div
                class="chat-body"
                id="chatBody"
            >

                @if($messages->count())

                    @php
                        $lastDate = null;
                    @endphp


                    @foreach($messages as $message)

                        @php

                            $messageDate =
                                $message->created_at
                                    ->format('d M Y');

                        @endphp


                        @if($lastDate !== $messageDate)

                            <div class="chat-date">

                                <span>

                                    {{ $messageDate }}

                                </span>

                            </div>

                            @php
                                $lastDate = $messageDate;
                            @endphp

                        @endif


                        <div
                            class="message-row
                            {{
                                $message->sender_id == auth()->id()
                                    ? 'mine'
                                    : 'theirs'
                            }}"
                        >

                            <div class="message-bubble">

                                <div class="message-text">

                                    {{ $message->message }}

                                </div>


                                <span class="message-time">

                                    {{ $message->created_at->format('h:i A') }}


                                    @if(
                                        $message->sender_id == auth()->id()
                                    )

                                        <span class="message-status">

                                            @if($message->is_read)

                                                <i class="bi bi-check2-all text-info"></i>

                                            @else

                                                <i class="bi bi-check2"></i>

                                            @endif

                                        </span>

                                    @endif

                                </span>

                            </div>

                        </div>

                    @endforeach

                @else

                    <div class="chat-empty">

                        <div>

                            <div class="chat-empty-icon">

                                💬

                            </div>


                            <h4 class="fw-bold mt-3">

                                Start the conversation

                            </h4>


                            <p class="text-secondary mb-0">

                                Send a message to
                                {{ $otherUser->name }}.

                            </p>

                        </div>

                    </div>

                @endif

            </div>


            {{-- =====================================================
                 MESSAGE COMPOSER
            ====================================================== --}}

            <div class="chat-composer">

                @if($bloodRequest)

                    <form
                        method="POST"
                        action="{{ route('messages.store') }}"
                        class="message-form"
                    >

                        @csrf


                        <input
                            type="hidden"
                            name="receiver_id"
                            value="{{ $otherUser->id }}"
                        >


                        <input
                            type="hidden"
                            name="blood_request_id"
                            value="{{ $bloodRequest->id }}"
                        >


                        <textarea
                            name="message"
                            class="message-input"
                            placeholder="Type your message..."
                            rows="1"
                            maxlength="2000"
                            required
                            oninput="
                                this.style.height='auto';
                                this.style.height=this.scrollHeight+'px';
                            "
                        ></textarea>


                        <button
                            type="submit"
                            class="send-button"
                            title="Send Message"
                        >

                            <i class="bi bi-send-fill"></i>

                        </button>

                    </form>


                    <div class="small text-secondary mt-2">

                        🔒 This conversation is connected to your blood request.

                    </div>

                @else

                    <div class="text-center text-secondary py-2">

                        🔒 Messaging is unavailable because there is
                        no active blood request connection.

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>


<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const chatBody =
                document.getElementById('chatBody');

            if (chatBody) {

                chatBody.scrollTop =
                    chatBody.scrollHeight;

            }

        }
    );

</script>

@endsection