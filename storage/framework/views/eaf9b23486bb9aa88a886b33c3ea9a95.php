<?php $__env->startSection('title', 'Messages | BloodNexus'); ?>

<?php $__env->startSection('content'); ?>

<style>

    .messages-page {
        min-height: calc(100vh - 75px);
        padding: 40px 0 80px;

        background:
            radial-gradient(
                circle at 0% 0%,
                rgba(220,38,38,.09),
                transparent 32%
            ),
            radial-gradient(
                circle at 100% 100%,
                rgba(124,58,237,.07),
                transparent 32%
            ),
            #f7f8fc;
    }


    .messages-hero {
        position: relative;
        overflow: hidden;

        border-radius: 30px;
        padding: 34px;

        color: #fff;

        background:
            linear-gradient(
                135deg,
                #111827,
                #7f1d1d 50%,
                #dc2626
            );

        box-shadow:
            0 25px 60px rgba(127,29,29,.20);
    }


    .messages-hero::before {
        content: "";

        position: absolute;

        width: 280px;
        height: 280px;

        right: -90px;
        top: -150px;

        border-radius: 50%;

        background:
            rgba(255,255,255,.09);
    }


    .hero-icon {
        width: 70px;
        height: 70px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 22px;

        background:
            rgba(255,255,255,.14);

        border:
            1px solid rgba(255,255,255,.18);

        font-size: 32px;

        backdrop-filter:
            blur(10px);
    }


    .messages-card {
        margin-top: 25px;

        padding: 10px;

        background:
            rgba(255,255,255,.94);

        border:
            1px solid #edf0f4;

        border-radius: 28px;

        box-shadow:
            0 18px 50px rgba(20,25,40,.07);
    }


    .conversation-row {

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;

        padding: 20px;

        margin: 8px 0;

        border-radius: 22px;

        border:
            1px solid transparent;

        transition:
            .25s ease;
    }


    .conversation-row:hover {

        transform:
            translateY(-3px);

        background:
            linear-gradient(
                135deg,
                #fff7f7,
                #fff
            );

        border-color:
            #fee2e2;

        box-shadow:
            0 12px 30px rgba(20,25,40,.07);
    }


    .user-avatar {

        width: 58px;
        height: 58px;

        min-width: 58px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 19px;

        color: #fff;

        font-size: 21px;
        font-weight: 900;

        background:
            linear-gradient(
                135deg,
                #dc2626,
                #f87171
            );

        box-shadow:
            0 10px 25px rgba(220,38,38,.20);
    }


    .user-name {

        color: #172033;

        font-size: 17px;

        font-weight: 900;
    }


    .user-role {

        display: inline-block;

        margin-top: 4px;

        padding: 4px 10px;

        border-radius: 999px;

        background: #fff1f2;

        color: #b91c1c;

        font-size: 10px;

        font-weight: 900;

        text-transform: uppercase;
    }


    .preview {

        max-width: 600px;

        margin-top: 8px;

        color: #6b7280;

        font-size: 14px;

        white-space: nowrap;

        overflow: hidden;

        text-overflow: ellipsis;
    }


    .request-context {

        display: inline-flex;

        align-items: center;

        gap: 6px;

        margin-top: 7px;

        padding: 5px 10px;

        border-radius: 999px;

        background: #f8fafc;

        color: #64748b;

        font-size: 11px;

        font-weight: 700;
    }


    .chat-btn {

        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: 7px;

        padding: 11px 18px;

        border-radius: 15px;

        background:
            linear-gradient(
                135deg,
                #111827,
                #374151
            );

        color: #fff;

        font-weight: 800;

        text-decoration: none;

        transition: .25s;
    }


    .chat-btn:hover {

        color: #fff;

        background:
            linear-gradient(
                135deg,
                #dc2626,
                #ef4444
            );

        transform:
            translateY(-2px);

        box-shadow:
            0 10px 25px rgba(220,38,38,.20);
    }


    .empty-state {

        padding:
            80px 25px;

        text-align: center;
    }


    .empty-icon {

        width: 95px;
        height: 95px;

        margin:
            0 auto 20px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 30px;

        background:
            linear-gradient(
                135deg,
                #fff1f2,
                #ffe4e6
            );

        color: #dc2626;

        font-size: 42px;
    }


    .start-info {

        margin-top: 20px;

        padding: 15px 18px;

        border-radius: 18px;

        background: #f8fafc;

        color: #64748b;

        font-size: 13px;
    }


    @media(max-width:768px) {

        .messages-page {
            padding:
                22px 0 55px;
        }


        .messages-hero {
            padding: 25px 22px;

            border-radius: 23px;
        }


        .conversation-row {

            flex-direction: column;

            align-items: flex-start;
        }


        .chat-btn {

            width: 100%;
        }


        .preview {
            max-width: 280px;
        }
    }

</style>


<div class="messages-page">

<div class="container">


    

    <div class="messages-hero">

        <div
            class="d-flex align-items-center gap-3 position-relative"
            style="z-index:2;"
        >

            <div class="hero-icon">
                💬
            </div>

            <div>

                <div class="small fw-bold opacity-75 mb-1">
                    BLOODNEXUS • SECURE COMMUNICATION
                </div>

                <h1 class="fw-bold mb-1">
                    Messages ❤️
                </h1>

                <p class="mb-0 opacity-75">
                    Connect privately with your blood donor or blood seeker.
                </p>

            </div>

        </div>

    </div>


    

    <?php if(session('success')): ?>

        <div class="alert alert-success border-0 rounded-4 shadow-sm mt-4">
            ✅ <?php echo e(session('success')); ?>

        </div>

    <?php endif; ?>


    <?php if(session('error')): ?>

        <div class="alert alert-danger border-0 rounded-4 shadow-sm mt-4">
            ⚠️ <?php echo e(session('error')); ?>

        </div>

    <?php endif; ?>


    

    <?php if(($unreadCount ?? 0) > 0): ?>

        <div class="alert alert-danger border-0 rounded-4 shadow-sm mt-4">

            💌

            You have

            <strong>
                <?php echo e($unreadCount); ?>

            </strong>

            unread message(s).

        </div>

    <?php endif; ?>


    

    <div class="messages-card">

        <?php

            $conversations = $messages
                ->sortBy('created_at')
                ->groupBy(function ($message) {

                    return $message->sender_id == auth()->id()
                        ? $message->receiver_id
                        : $message->sender_id;

                });

        ?>


        <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <?php

                $lastMessage =
                    $conversation->sortBy('created_at')->last();

                $otherUser =
                    $lastMessage->sender_id == auth()->id()
                        ? $lastMessage->receiver
                        : $lastMessage->sender;

                $unread =
                    $conversation
                        ->where('receiver_id', auth()->id())
                        ->where('is_read', false)
                        ->count();

            ?>


            <?php if($otherUser): ?>

                <div class="conversation-row">


                    <div class="d-flex align-items-center gap-3">


                        <div class="user-avatar">

                            <?php echo e(strtoupper(
                                substr(
                                    $otherUser->name ?? '?',
                                    0,
                                    1
                                )
                            )); ?>


                        </div>


                        <div>

                            <div class="user-name">

                                <?php echo e($otherUser->name); ?>


                                <?php if($unread > 0): ?>

                                    <span
                                        class="badge bg-danger rounded-pill ms-2"
                                    >
                                        <?php echo e($unread); ?> new
                                    </span>

                                <?php endif; ?>

                            </div>


                            <span class="user-role">

                                <?php echo e(ucfirst($otherUser->role)); ?>


                            </span>


                            <div class="preview">

                                💬

                                <?php echo e($lastMessage->message); ?>


                            </div>


                            <?php if($lastMessage->bloodRequest): ?>

                                <div class="request-context">

                                    🩸

                                    Request #

                                    <?php echo e($lastMessage->bloodRequest->id); ?>


                                    ·

                                    <?php echo e($lastMessage->bloodRequest->blood_group); ?>


                                </div>

                            <?php endif; ?>

                        </div>

                    </div>


                    <a
                        href="<?php echo e(route(
                            'messages.conversation',
                            $otherUser->id
                        )); ?>"
                        class="chat-btn"
                    >

                        💬

                        Open Chat

                    </a>


                </div>

            <?php endif; ?>


        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <div class="empty-state">

                <div class="empty-icon">
                    💬
                </div>


                <h3 class="fw-bold">
                    No conversations yet
                </h3>


                <p class="text-secondary">

                    Once a donor accepts your blood request,
                    your private conversation will appear here.

                </p>


                <div class="start-info">

                    🩸

                    <strong>
                        User:
                    </strong>

                    Create a blood request.

                    <br>

                    ❤️

                    <strong>
                        Donor:
                    </strong>

                    Accept the request.

                    <br>

                    💬

                    Then both can communicate securely.

                </div>

            </div>

        <?php endif; ?>

    </div>

</div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\BloodNexus\resources\views/messages.blade.php ENDPATH**/ ?>