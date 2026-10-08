<?php $__env->startSection('title', 'Donor Dashboard | BloodNexus'); ?>

<?php $__env->startSection('content'); ?>

<style>

    body {
        background: #f5f7fb;
    }

    .donor-wrapper {
        min-height: calc(100vh - 75px);
        padding: 35px 0 80px;
        background:
            radial-gradient(
                circle at 0% 0%,
                rgba(220,38,38,.08),
                transparent 30%
            ),
            radial-gradient(
                circle at 100% 100%,
                rgba(220,38,38,.06),
                transparent 30%
            ),
            #f6f8fc;
    }


    /* HERO */

    .donor-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: 38px;
        color: #fff;
        background:
            linear-gradient(
                135deg,
                #111827 0%,
                #7f1d1d 48%,
                #dc2626 100%
            );
        box-shadow:
            0 25px 60px rgba(127,29,29,.25);
    }

    .donor-hero::before {
        content: "";
        position: absolute;
        width: 330px;
        height: 330px;
        right: -100px;
        top: -170px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
    }

    .donor-hero::after {
        content: "";
        position: absolute;
        width: 190px;
        height: 190px;
        left: 45%;
        bottom: -125px;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
    }


    .donor-avatar {
        width: 78px;
        height: 78px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.16);
        font-size: 38px;
        backdrop-filter: blur(12px);
        flex-shrink: 0;
    }


    .donor-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,.13);
        border: 1px solid rgba(255,255,255,.16);
        font-size: 13px;
        font-weight: 800;
    }

    .status-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #4ade80;
        box-shadow: 0 0 14px #4ade80;
    }


    /* CARDS */

    .premium-card {
        background: #fff;
        border: 1px solid #edf0f4;
        border-radius: 24px;
        box-shadow: 0 12px 35px rgba(20,25,40,.055);
    }


    .stat-card {
        height: 100%;
        padding: 23px;
        transition: .25s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 45px rgba(20,25,40,.10);
    }


    .stat-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 17px;
        background: #fff1f2;
        font-size: 24px;
    }


    .stat-value {
        margin-top: 17px;
        font-size: 30px;
        line-height: 1;
        font-weight: 900;
        color: #172033;
    }


    .stat-label {
        margin-top: 7px;
        color: #737b89;
        font-size: 14px;
    }


    /* MATCHING SECTION */

    .section-card {
        overflow: hidden;
    }

    .section-header {
        padding: 23px 25px;
        border-bottom: 1px solid #edf0f4;
    }


    .request-item {
        padding: 23px 25px;
        border-bottom: 1px solid #edf0f4;
        transition: .2s;
    }

    .request-item:last-child {
        border-bottom: 0;
    }

    .request-item:hover {
        background: #fffafa;
    }


    .blood-badge {
        width: 58px;
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        background: #fff1f2;
        color: #dc2626;
        font-size: 17px;
        font-weight: 900;
        flex-shrink: 0;
    }


    .urgency {
        display: inline-flex;
        padding: 6px 11px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 900;
    }

    .critical {
        background: #fee2e2;
        color: #b91c1c;
    }

    .urgent {
        background: #fef3c7;
        color: #92400e;
    }

    .normal {
        background: #dcfce7;
        color: #166534;
    }


    .request-meta {
        color: #6b7280;
        font-size: 13px;
        margin-top: 4px;
    }


    /* PROFILE */

    .profile-card {
        padding: 25px;
        background:
            linear-gradient(
                145deg,
                #fff,
                #fff7f7
            );
        border: 1px solid #fee2e2;
    }


    .profile-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 13px 0;
        border-bottom: 1px solid #f1f1f1;
    }

    .profile-row:last-child {
        border-bottom: 0;
    }


    .profile-label {
        color: #737b89;
        font-size: 13px;
    }

    .profile-value {
        font-weight: 800;
        text-align: right;
    }


    /* ACTIONS */

    .action-card {
        padding: 23px;
        height: 100%;
        transition: .25s;
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 45px rgba(20,25,40,.10);
    }


    .action-icon {
        width: 52px;
        height: 52px;
        border-radius: 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff1f2;
        font-size: 24px;
    }


    .premium-btn {
        border: 0;
        border-radius: 13px;
        font-weight: 800;
        padding: 10px 16px;
        transition: .2s;
    }

    .premium-btn:hover {
        transform: translateY(-2px);
    }


    .btn-help {
        background: linear-gradient(
            135deg,
            #16a34a,
            #15803d
        );
        color: #fff;
    }

    .btn-help:hover {
        color: #fff;
    }


    .btn-message {
        background: #fff1f2;
        color: #dc2626;
    }


    .ai-card {
        position: relative;
        overflow: hidden;
        border-radius: 26px;
        padding: 28px;
        color: #fff;
        background:
            linear-gradient(
                135deg,
                #312e81,
                #7c3aed,
                #db2777
            );
        box-shadow: 0 20px 50px rgba(124,58,237,.18);
    }


    .ai-card::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        right: -90px;
        top: -110px;
        border-radius: 50%;
        background: rgba(255,255,255,.10);
    }


    @media(max-width: 768px) {

        .donor-wrapper {
            padding: 20px 0 55px;
        }

        .donor-hero {
            padding: 27px 22px;
            border-radius: 23px;
        }

        .donor-avatar {
            width: 62px;
            height: 62px;
            font-size: 30px;
        }

        .donor-hero h1 {
            font-size: 27px;
        }

        .request-item {
            padding: 20px;
        }
    }

    .premium-card, .request-item { animation: donorRise .55s cubic-bezier(.2,.8,.2,1) both; }
    .request-item:nth-child(2) { animation-delay:.04s; }
    .request-item:nth-child(3) { animation-delay:.08s; }
    .request-item:nth-child(4) { animation-delay:.12s; }
    .request-item:hover { transform:translateX(4px); box-shadow:inset 4px 0 0 #dc2626; }
    .blood-badge { transition:transform .25s ease, box-shadow .25s ease; }
    .request-item:hover .blood-badge { transform:scale(1.08) rotate(-4deg); box-shadow:0 10px 24px rgba(220,38,38,.16); }
    @keyframes donorRise { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }
</style>


<div class="donor-wrapper">

<div class="container">


    

    <div class="donor-hero mb-4">

        <div class="row align-items-center position-relative"
             style="z-index:2;">

            <div class="col-lg-8">

                <div class="d-flex align-items-center gap-3">

                    <div class="donor-avatar">
                        🩸
                    </div>

                    <div>

                        <div class="donor-status mb-2">

                            <span class="status-dot"></span>

                            <?php echo e($donor->is_available
                                ? 'Donor Available'
                                : 'Currently Unavailable'); ?>


                        </div>

                        <h1 class="fw-bold mb-1">

                            Welcome,
                            <?php echo e($user->name); ?>

                            👋

                        </h1>

                        <p class="mb-0 opacity-75">

                            Your blood can become someone's
                            second chance at life.

                        </p>

                    </div>

                </div>

            </div>


            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">

                <a
                    href="<?php echo e(route('notifications.index')); ?>"
                    class="btn btn-light rounded-pill px-4 me-2"
                >
                    🔔 Notifications
                    <?php if($unreadNotifications > 0): ?>
                        <span class="badge bg-danger">
                            <?php echo e($unreadNotifications); ?>

                        </span>
                    <?php endif; ?>
                </a>


                <form
                    method="POST"
                    action="<?php echo e(route('donor.availability')); ?>"
                    class="d-inline"
                >

                    <?php echo csrf_field(); ?>

                    <button
                        type="submit"
                        class="btn btn-outline-light rounded-pill px-4 mt-2 mt-lg-0"
                    >
                        <?php echo e($donor->is_available
                            ? '🟢 Available'
                            : '⚪ Offline'); ?>

                    </button>

                </form>

            </div>

        </div>

    </div>


    

    <?php if(session('success')): ?>

        <div class="alert alert-success border-0 rounded-4 shadow-sm">
            ✅ <?php echo e(session('success')); ?>

        </div>

    <?php endif; ?>


    <?php if(session('error')): ?>

        <div class="alert alert-danger border-0 rounded-4 shadow-sm">
            ⚠️ <?php echo e(session('error')); ?>

        </div>

    <?php endif; ?>


    

    <div class="row g-3 mb-4">

        <div class="col-6 col-lg-3">

            <div class="premium-card stat-card">

                <div class="stat-icon">
                    🩸
                </div>

                <div class="stat-value">
                    <?php echo e($totalDonations); ?>

                </div>

                <div class="stat-label">
                    Total Donations
                </div>

            </div>

        </div>


        <div class="col-6 col-lg-3">

            <div class="premium-card stat-card">

                <div class="stat-icon">
                    🚨
                </div>

                <div class="stat-value">
                    <?php echo e($urgentRequests); ?>

                </div>

                <div class="stat-label">
                    Urgent Matches
                </div>

            </div>

        </div>


        <div class="col-6 col-lg-3">

            <div class="premium-card stat-card">

                <div class="stat-icon">
                    📍
                </div>

                <div class="stat-value">
                    <?php echo e($nearbyRequests); ?>

                </div>

                <div class="stat-label">
                    Donation History
                </div>

            </div>

        </div>


        <div class="col-6 col-lg-3">

            <div class="premium-card stat-card">

                <div class="stat-icon">
                    ❤️
                </div>

                <div class="stat-value">
                    <?php echo e($completedRequests); ?>

                </div>

                <div class="stat-label">
                    Completed Donations
                </div>

            </div>

        </div>

    </div>


    

<div class="premium-card mb-4"
     style="
        overflow:hidden;
        border:1px solid <?php echo e($canDonate ? '#bbf7d0' : '#fecaca'); ?>;
        background:
            linear-gradient(
                135deg,
                <?php echo e($canDonate ? '#f0fdf4' : '#fff7f7'); ?>,
                #ffffff
            );
     ">

    <div class="p-4">

        <div class="row align-items-center g-4">

            
            <div class="col-auto">

                <div
                    style="
                        width:68px;
                        height:68px;
                        border-radius:20px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:32px;
                        background:
                            <?php echo e($canDonate
                                ? 'linear-gradient(135deg,#dcfce7,#bbf7d0)'
                                : 'linear-gradient(135deg,#fee2e2,#fecaca)'); ?>;
                    "
                >
                    <?php echo e($canDonate ? '🟢' : '⏳'); ?>

                </div>

            </div>


            
            <div class="col">

                <div class="d-flex flex-wrap align-items-center gap-2">

                    <h4 class="fw-bold mb-0">
                        🩸 Donation Recovery Status
                    </h4>

                    <?php if($canDonate): ?>

                        <span
                            class="badge rounded-pill bg-success px-3 py-2"
                        >
                            ELIGIBLE
                        </span>

                    <?php else: ?>

                        <span
                            class="badge rounded-pill bg-danger px-3 py-2"
                        >
                            RECOVERY PERIOD
                        </span>

                    <?php endif; ?>

                </div>


                <?php if($canDonate): ?>

                    <p class="text-success fw-semibold mt-2 mb-1">

                        🟢 You are eligible to donate blood.

                    </p>

                    <p class="text-secondary small mb-0">

                        You have completed your recovery period
                        and can now help another blood seeker.

                    </p>

                <?php else: ?>

                    <p class="text-danger fw-semibold mt-2 mb-1">

                        ⏳ You are currently in the 3-month recovery period.

                    </p>

                    <p class="text-secondary small mb-0">

                        Please wait until your recovery period is completed
                        before donating blood again.

                    </p>

                <?php endif; ?>

            </div>


            
            <div class="col-lg-auto">

                <?php if($canDonate): ?>

                    <div
                        class="text-center px-4 py-3 rounded-4"
                        style="background:#dcfce7;"
                    >

                        <div
                            class="small text-success fw-bold"
                        >
                            STATUS
                        </div>

                        <div
                            class="fw-bold text-success mt-1"
                        >
                            Ready to Donate ❤️
                        </div>

                    </div>

                <?php else: ?>

                    <div
                        class="text-center px-4 py-3 rounded-4"
                        style="background:#fee2e2;"
                    >

                        <div
                            class="small text-danger fw-bold"
                        >
                            DAYS REMAINING
                        </div>

                        <div
                            class="fw-bold text-danger mt-1"
                            style="font-size:24px;"
                        >
                            <?php echo e($cooldownDays); ?>

                        </div>

                    </div>

                <?php endif; ?>

            </div>

        </div>


        
        <div
            class="mt-4 pt-3"
            style="border-top:1px solid #edf0f4;"
        >

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="small text-secondary">
                        🩸 Last Donation
                    </div>

                    <div class="fw-bold mt-1">

                        <?php if($donor->last_donation_date): ?>

                            <?php echo e(\Carbon\Carbon::parse(
                                $donor->last_donation_date
                            )->format('d M Y')); ?>


                        <?php else: ?>

                            No donation recorded

                        <?php endif; ?>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="small text-secondary">
                        📅 Next Eligible Date
                    </div>

                    <div class="fw-bold mt-1">

                        <?php if($nextDonationDate): ?>

                            <?php echo e(\Carbon\Carbon::parse(
                                $nextDonationDate
                            )->format('d M Y')); ?>


                        <?php else: ?>

                            Available after first donation

                        <?php endif; ?>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="small text-secondary">
                        🛡️ Donation Safety
                    </div>

                    <div class="fw-bold mt-1">

                        3 Month Recovery Protection

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


    

    <div class="row g-4">


        <div class="col-lg-8">

            <div class="premium-card section-card">

                <div class="section-header
                            d-flex
                            justify-content-between
                            align-items-center">

                    <div>

                        <h4 class="fw-bold mb-1">
                            🚨 Matching Blood Requests
                        </h4>

                        <div class="small text-secondary">

                            <?php echo e($bloodGroup ?: 'Blood group not set'); ?>

                            · Live network matching
                            <?php if($city): ?> · Priority: <?php echo e($city); ?> <?php endif; ?>

                        </div>

                    </div>


                    <span class="badge bg-danger rounded-pill px-3 py-2">

                        <?php echo e($nearbyBloodRequests->count()); ?>


                    </span>

                </div>


                <?php $__empty_1 = true; $__currentLoopData = $nearbyBloodRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <div class="request-item">

                        <div class="row align-items-center g-3">


                            <div class="col-auto">

                                <div class="blood-badge">
                                    <?php echo e($request->blood_group); ?>

                                </div>

                            </div>


                            <div class="col">

                                <div class="d-flex
                                            flex-wrap
                                            align-items-center
                                            gap-2">

                                    <h5 class="fw-bold mb-0">

                                        <?php echo e($request->patient_name); ?>


                                    </h5>


                                    <span
                                        class="urgency
                                        <?php echo e($request->urgency === 'critical'
                                            ? 'critical'
                                            : ($request->urgency === 'urgent'
                                                ? 'urgent'
                                                : 'normal')); ?>"
                                    >

                                        <?php echo e(strtoupper($request->urgency)); ?>


                                    </span>

                                </div>


                                <div class="request-meta">

                                    🏥 <?php echo e($request->hospital); ?>


                                </div>


                                <div class="request-meta">

                                    📍 <?php echo e($request->city); ?><?php if(!empty($request->area)): ?> · <?php echo e($request->area); ?><?php endif; ?>

                                    <?php if($request->units): ?>
                                        · 🩸 <?php echo e($request->units); ?> unit(s)
                                    <?php endif; ?>

                                </div>


                                <div class="request-meta">

                                    📞
                                    <?php echo e($request->contact_phone
                                        ?? $request->contact
                                        ?? 'Contact available after acceptance'); ?>


                                    ·

                                    <?php echo e($request->created_at->diffForHumans()); ?>


                                </div>


                                <?php if($request->reason): ?>

                                    <div class="small text-secondary mt-2">

                                        💬 <?php echo e($request->reason); ?>


                                    </div>

                                <?php endif; ?>

                            </div>


                            <div class="col-lg-auto">

                                <div class="d-flex flex-wrap gap-2">


                                    

                                    <form
                                        method="POST"
                                        action="<?php echo e(route(
                                                'donor.request.accept',
                                                $request->id
                                            )); ?>"
                                    >

                                        <?php echo csrf_field(); ?>

                                        <button
                                            type="submit"
                                            class="premium-btn btn-help"
                                            onclick="
                                                return confirm(
                                                    'Accept this blood request?'
                                                )
                                            "
                                        >

                                            ❤️ I Can Help

                                        </button>

                                    </form>


                                    

                                    <?php if($request->user_id): ?>

                                        <a
                                            href="<?php echo e(route(
                                                    'messages.conversation',
                                                    $request->user_id
                                                )); ?>"
                                            class="premium-btn btn-message text-decoration-none"
                                        >

                                            💬 Message

                                        </a>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <div class="text-center py-5 px-4">

                        <div style="font-size:60px;">
                            🩸
                        </div>

                        <h4 class="fw-bold mt-3">
                            No Donation Requests
                        </h4>

                        <p class="text-secondary mb-0">

                            There are currently no pending matching blood requests
                            available across the network.

                        </p>

                    </div>

                <?php endif; ?>

            </div>

        </div>


        
        <?php
            $savedMedicalHistory = is_string($donor->medical_history) ? json_decode($donor->medical_history, true) : [];
            if (!is_array($savedMedicalHistory)) $savedMedicalHistory = [];
            $medicalQuestions = [
                'chronic_condition' => 'Do you have any long-term / chronic medical condition?',
                'regular_medicines' => 'Do you currently take any regular medicines?',
                'allergies' => 'Do you have any known allergy?',
                'recent_surgery' => 'Have you had any surgery or medical procedure recently?',
                'fever_infection' => 'Do you currently have fever or an infection?',
                'blood_transfusion' => 'Have you received a blood transfusion recently?',
                'tattoo_piercing' => 'Have you had a tattoo or piercing recently?',
                'recent_donation' => 'Have you donated blood within the last 3 months?',
            ];
        ?>
        <div class="col-lg-8 mb-4">
            <div class="premium-card p-4" style="border:1px solid #e7ebf2;background:linear-gradient(145deg,#fff,#f8fbff);">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2">🩺 DONOR HEALTH PROFILE</span>
                        <h4 class="fw-bold mt-3 mb-1">Your Medical History</h4>
                        <p class="text-secondary small mb-0">You can update these Yes / No answers anytime.</p>
                    </div>
                    <i class="bi bi-heart-pulse-fill text-danger fs-2"></i>
                </div>
                <form method="POST" action="<?php echo e(route('donor.medical-history')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="row g-3">
                        <?php $__currentLoopData = $medicalQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6">
                                <div class="p-3 rounded-4 h-100" style="background:#fff;border:1px solid #e8edf4;">
                                    <div class="fw-semibold mb-2"><?php echo e($question); ?></div>
                                    <div class="d-flex gap-2">
                                        <input type="radio" class="btn-check" name="medical_history[<?php echo e($key); ?>]" id="dash_mh_<?php echo e($key); ?>_yes" value="yes" <?php echo e(($savedMedicalHistory[$key] ?? '') === 'yes' ? 'checked' : ''); ?> required>
                                        <label class="btn btn-outline-danger rounded-pill px-3" for="dash_mh_<?php echo e($key); ?>_yes">Yes</label>
                                        <input type="radio" class="btn-check" name="medical_history[<?php echo e($key); ?>]" id="dash_mh_<?php echo e($key); ?>_no" value="no" <?php echo e(($savedMedicalHistory[$key] ?? '') === 'no' ? 'checked' : ''); ?> required>
                                        <label class="btn btn-outline-success rounded-pill px-3" for="dash_mh_<?php echo e($key); ?>_no">No</label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3 gap-2 flex-wrap">
                        <small class="text-secondary"><i class="bi bi-check-circle text-success me-1"></i>Only Yes / No answers are stored.</small>
                        <button class="btn btn-danger rounded-pill px-4 fw-bold bn-ripple"><i class="bi bi-save2 me-1"></i>Update Medical History</button>
                    </div>
                </form>
            </div>
        </div>


        

        <div class="col-lg-4">

            <div class="premium-card profile-card">

                <div class="d-flex align-items-center gap-3 mb-4">

                    <div class="blood-badge">

                        <?php echo e($bloodGroup ?: '—'); ?>


                    </div>

                    <div>

                        <h5 class="fw-bold mb-1">
                            Donor Profile
                        </h5>

                        <div
                            class="small
                            <?php echo e($donor->is_available
                                ? 'text-success'
                                : 'text-secondary'); ?>"
                        >

                            ●

                            <?php echo e($donor->is_available
                                ? 'Available to Help'
                                : 'Currently Offline'); ?>


                        </div>

                    </div>

                </div>


                <div class="profile-row">

                    <span class="profile-label">
                        Name
                    </span>

                    <span class="profile-value">
                        <?php echo e($user->name); ?>

                    </span>

                </div>


                <div class="profile-row">

                    <span class="profile-label">
                        Blood Group
                    </span>

                    <span class="profile-value">
                        <?php echo e($bloodGroup ?: '—'); ?>

                    </span>

                </div>


                <div class="profile-row">

                    <span class="profile-label">
                        City
                    </span>

                    <span class="profile-value">
                        <?php echo e($city ?: '—'); ?>

                    </span>

                </div>


                <div class="profile-row">

                    <span class="profile-label">
                        Phone
                    </span>

                    <span class="profile-value">
                        <?php echo e($donor->phone
                            ?: ($user->phone ?? '—')); ?>

                    </span>

                </div>


                <div class="profile-row">

                    <span class="profile-label">
                        Email
                    </span>

                    <span class="profile-value">

                        <?php echo e($user->email); ?>


                    </span>

                </div>


                <div class="mt-4">

                    <a
                        href="<?php echo e(route('messages.index')); ?>"
                        class="btn btn-outline-danger w-100 rounded-pill fw-bold"
                    >
                        💬 Open Messages
                    </a>

                </div>


                <div class="mt-2">

                    <a
                        href="<?php echo e(route('notifications.index')); ?>"
                        class="btn btn-light border w-100 rounded-pill fw-bold"
                    >
                        🔔 Open Notifications
                    </a>

                </div>

            </div>

        </div>

    </div>


    

    <?php if($acceptedBloodRequests->count()): ?>

        <div class="premium-card mt-4 p-4">

            <div class="d-flex
                        justify-content-between
                        align-items-center
                        mb-3">

                <div>

                    <h4 class="fw-bold mb-1">
                        ❤️ Active Donation
                    </h4>

                    <p class="text-secondary mb-0">
                        Complete the donation after helping the requester.
                    </p>

                </div>

                <span class="badge bg-success rounded-pill px-3 py-2">
                    <?php echo e($acceptedBloodRequests->count()); ?>

                </span>

            </div>


            <?php $__currentLoopData = $acceptedBloodRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div
                    class="border rounded-4 p-3 mb-3"
                    style="background:#f8fff9;"
                >

                    <div class="row align-items-center g-3">

                        <div class="col">

                            <h5 class="fw-bold mb-1">
                                <?php echo e($request->patient_name); ?>

                            </h5>

                            <div class="small text-secondary">

                                🩸 <?php echo e($request->blood_group); ?>


                                ·

                                🏥 <?php echo e($request->hospital); ?>


                                ·

                                📍 <?php echo e($request->city); ?>


                            </div>

                        </div>


                        <div class="col-lg-auto">

                            <form
                                method="POST"
                                action="<?php echo e(route(
                                        'donor.request.complete',
                                        $request->id
                                    )); ?>"
                            >

                                <?php echo csrf_field(); ?>

                                <button
                                    type="submit"
                                    class="btn btn-success rounded-pill fw-bold px-4"
                                    onclick="
                                        return confirm(
                                            'Mark this donation as completed?'
                                        )
                                    "
                                >

                                    🎉 Donation Complete

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

    <?php endif; ?>


    

    <div class="mt-5">

        <h4 class="fw-bold mb-3">
            Donor Quick Actions
        </h4>


        <div class="row g-3">


            <div class="col-md-4">

                <a
                    href="<?php echo e(route('messages.index')); ?>"
                    class="text-decoration-none text-dark"
                >

                    <div class="premium-card action-card">

                        <div class="action-icon">
                            💬
                        </div>

                        <h5 class="fw-bold mt-3">
                            Messages
                        </h5>

                        <p class="text-secondary small mb-0">

                            Chat directly with blood seekers
                            about active requests.

                        </p>

                    </div>

                </a>

            </div>


            <div class="col-md-4">

                <a
                    href="<?php echo e(route('notifications.index')); ?>"
                    class="text-decoration-none text-dark"
                >

                    <div class="premium-card action-card">

                        <div class="action-icon">
                            🔔
                        </div>

                        <h5 class="fw-bold mt-3">
                            Notifications
                        </h5>

                        <p class="text-secondary small mb-0">

                            See new blood requests,
                            acceptances and updates.

                        </p>

                    </div>

                </a>

            </div>


            <div class="col-md-4">

                <a
                    href="<?php echo e(route('donor.requests')); ?>"
                    class="text-decoration-none text-dark"
                >

                    <div class="premium-card action-card">

                        <div class="action-icon">
                            📋
                        </div>

                        <h5 class="fw-bold mt-3">
                            My Donation History
                        </h5>

                        <p class="text-secondary small mb-0">

                            View your pending, accepted
                            and completed donations.

                        </p>

                    </div>

                </a>

            </div>

        </div>

    </div>


    

    <div class="ai-card mt-5">

        <div class="row align-items-center position-relative"
             style="z-index:2;">

            <div class="col-lg-8">

                <span class="badge bg-white text-dark rounded-pill px-3 py-2">
                    🤖 AI DONOR ASSISTANT
                </span>

                <h3 class="fw-bold mt-3">
                    Smart Blood Matching
                </h3>

                <p class="mb-0 opacity-75">

                    Matching is automatically prioritized using
                    blood group, location and urgency.

                </p>

            </div>


            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">

                <button
                    type="button"
                    class="btn btn-light rounded-pill fw-bold px-4"
                    onclick="
                        alert(
                            '🤖 Your requests are matched using blood group, city and urgency.'
                        )
                    "
                >

                    Analyze Matches

                </button>

            </div>

        </div>

    </div>


</div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\BloodNexus\resources\views/donor-dashboard.blade.php ENDPATH**/ ?>