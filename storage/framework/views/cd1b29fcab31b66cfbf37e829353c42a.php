<?php $__env->startSection('title', 'Dashboard | BloodNexus'); ?>

<?php $__env->startSection('content'); ?>

<style>
    /* =========================================================
       PREMIUM USER DASHBOARD
    ========================================================= */

    .dashboard-page {
        position: relative;
        overflow: hidden;
        padding: 42px 0 70px;
        background:
            radial-gradient(circle at 5% 0%, rgba(223,38,61,.08), transparent 28%),
            radial-gradient(circle at 95% 10%, rgba(79,70,229,.08), transparent 30%),
            #f7f8fc;
        min-height: calc(100vh - 75px);
    }

    .dashboard-page::before, .dashboard-page::after {
        content: ''; position:absolute; border-radius:50%; pointer-events:none; filter:blur(3px);
    }
    .dashboard-page::before { width:360px;height:360px;left:-180px;top:140px;background:rgba(220,38,38,.07); }
    .dashboard-page::after { width:300px;height:300px;right:-140px;bottom:120px;background:rgba(79,70,229,.07); }

    /* =========================================================
       HERO
    ========================================================= */

    .dashboard-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: 38px;
        color: white;
        background:
            linear-gradient(
                135deg,
                #111827 0%,
                #25263b 45%,
                #df263d 100%
            );
        box-shadow:
            0 25px 60px rgba(17,24,39,.20);
    }

    .dashboard-hero::before {
        content: "";
        position: absolute;
        width: 330px;
        height: 330px;
        right: -100px;
        top: -170px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
    }

    .dashboard-hero::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        left: 48%;
        bottom: -170px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 15px;
        border-radius: 30px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.15);
        font-size: 13px;
        font-weight: 700;
        backdrop-filter: blur(12px);
    }

    .hero-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #4ade80;
        box-shadow: 0 0 12px #4ade80;
    }

    .hero-title {
        font-size: clamp(30px, 4vw, 48px);
        font-weight: 850;
        letter-spacing: -1.5px;
        margin-top: 18px;
        margin-bottom: 10px;
    }

    .hero-subtitle {
        color: rgba(255,255,255,.75);
        max-width: 650px;
        font-size: 15px;
        line-height: 1.7;
    }

    .hero-actions {
        margin-top: 25px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .hero-primary-btn {
        border: 0;
        padding: 12px 22px;
        border-radius: 15px;
        background: white;
        color: #df263d;
        font-weight: 800;
        text-decoration: none;
        transition: .25s ease;
    }

    .hero-primary-btn:hover {
        transform: translateY(-3px);
        color: #b91c32;
        box-shadow: 0 12px 25px rgba(0,0,0,.18);
    }

    .hero-secondary-btn {
        padding: 11px 21px;
        border-radius: 15px;
        border: 1px solid rgba(255,255,255,.22);
        background: rgba(255,255,255,.08);
        color: white;
        font-weight: 700;
        text-decoration: none;
        backdrop-filter: blur(10px);
        transition: .25s ease;
    }

    .hero-secondary-btn:hover {
        color: white;
        background: rgba(255,255,255,.16);
        transform: translateY(-3px);
    }

    .hero-blood-icon {
        position: absolute;
        z-index: 1;
        right: 55px;
        bottom: 25px;
        font-size: 115px;
        opacity: .13;
        transform: rotate(-12deg);
        filter: drop-shadow(0 15px 20px rgba(0,0,0,.15));
    }

    /* =========================================================
       STAT CARDS
    ========================================================= */

    .stat-card {
        position: relative;
        overflow: hidden;
        height: 100%;
        padding: 24px;
        border-radius: 22px;
        background: rgba(255,255,255,.92);
        border: 1px solid rgba(20,25,40,.06);
        box-shadow: 0 12px 30px rgba(20,25,40,.06);
        transition: .28s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(20,25,40,.10);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 18px;
    }

    .stat-red {
        background: #fff0f2;
        color: #df263d;
    }

    .stat-orange {
        background: #fff7ed;
        color: #ea580c;
    }

    .stat-green {
        background: #ecfdf5;
        color: #059669;
    }

    .stat-blue {
        background: #eff6ff;
        color: #2563eb;
    }

    .stat-label {
        color: #7b8493;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 850;
        color: #172033;
        margin-top: 4px;
    }

    .stat-card::after {
        content: "";
        position: absolute;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        right: -55px;
        bottom: -55px;
        background: rgba(223,38,61,.04);
    }

    /* =========================================================
       SECTION TITLE
    ========================================================= */

    .section-title {
        font-size: 22px;
        font-weight: 850;
        color: #172033;
    }

    .section-subtitle {
        color: #89919e;
        font-size: 14px;
    }

    /* =========================================================
       QUICK ACCESS
    ========================================================= */

    .quick-card {
        display: block;
        height: 100%;
        padding: 23px;
        border-radius: 22px;
        background: white;
        border: 1px solid rgba(20,25,40,.06);
        box-shadow: 0 10px 28px rgba(20,25,40,.05);
        text-decoration: none;
        color: #172033;
        transition: .28s ease;
    }

    .quick-card:hover {
        color: #172033;
        transform: translateY(-6px);
        box-shadow: 0 20px 42px rgba(20,25,40,.10);
    }

    .quick-icon {
        width: 52px;
        height: 52px;
        border-radius: 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        margin-bottom: 18px;
    }

    .quick-red {
        background: #fff0f2;
    }

    .quick-blue {
        background: #eff6ff;
    }

    .quick-purple {
        background: #f5f3ff;
    }

    .quick-green {
        background: #ecfdf5;
    }

    .quick-title {
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .quick-description {
        color: #8a92a0;
        font-size: 13px;
        line-height: 1.5;
    }

    .quick-arrow {
        margin-top: 15px;
        color: #9ca3af;
        transition: .2s;
    }

    .quick-card:hover .quick-arrow {
        color: #df263d;
        transform: translateX(5px);
    }

    /* =========================================================
       RECENT REQUESTS
    ========================================================= */

    .requests-card {
        background: white;
        border-radius: 25px;
        border: 1px solid rgba(20,25,40,.06);
        box-shadow: 0 12px 30px rgba(20,25,40,.06);
        overflow: hidden;
    }

    .requests-header {
        padding: 23px 25px;
        border-bottom: 1px solid #edf0f4;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .request-row {
        padding: 20px 25px;
        border-bottom: 1px solid #f0f2f5;
        transition: .2s ease;
    }

    .request-row:last-child {
        border-bottom: 0;
    }

    .request-row:hover {
        background: #fafbfc;
    }

    .request-blood {
        width: 48px;
        height: 48px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff0f2;
        color: #df263d;
        font-weight: 850;
        font-size: 15px;
    }

    .request-name {
        font-weight: 800;
        color: #172033;
    }

    .request-meta {
        color: #89919e;
        font-size: 13px;
        margin-top: 4px;
    }

    .status-badge {
        padding: 8px 13px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 800;
    }

    .status-pending {
        background: #fff7ed;
        color: #c2410c;
    }

    .status-accepted {
        background: #ecfdf5;
        color: #047857;
    }

    .status-completed {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .status-cancelled {
        background: #f3f4f6;
        color: #4b5563;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 55px 25px;
        text-align: center;
    }

    .empty-icon {
        width: 75px;
        height: 75px;
        border-radius: 25px;
        margin: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff0f2;
        font-size: 34px;
    }

    .create-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 15px;
        padding: 11px 20px;
        border-radius: 14px;
        background: #df263d;
        color: white;
        font-weight: 800;
        text-decoration: none;
        transition: .25s;
    }

    .create-btn:hover {
        color: white;
        transform: translateY(-2px);
        background: #c91f35;
        box-shadow: 0 10px 25px rgba(223,38,61,.22);
    }

    /* =========================================================
       PREMIUM INFO CARD
    ========================================================= */

    .info-card {
        height: 100%;
        border-radius: 25px;
        padding: 27px;
        color: white;
        background:
            linear-gradient(135deg, #1e293b, #334155);
        box-shadow: 0 15px 35px rgba(15,23,42,.15);
        position: relative;
        overflow: hidden;
    }

    .info-card::after {
        content: "🩸";
        position: absolute;
        right: -15px;
        bottom: -30px;
        font-size: 120px;
        opacity: .08;
        transform: rotate(-15deg);
    }

    .info-icon {
        width: 50px;
        height: 50px;
        border-radius: 16px;
        background: rgba(255,255,255,.10);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 20px;
    }

    .info-card h4 {
        font-weight: 850;
    }

    .info-card p {
        color: rgba(255,255,255,.68);
        font-size: 14px;
        line-height: 1.7;
    }

    .info-link {
        display: inline-flex;
        margin-top: 10px;
        color: white;
        font-weight: 800;
        text-decoration: none;
        gap: 8px;
    }

    .info-link:hover {
        color: #fecdd3;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 768px) {

        .dashboard-page {
            padding: 25px 0 50px;
        }

        .dashboard-hero {
            padding: 27px 22px;
            border-radius: 23px;
        }

        .hero-title {
            font-size: 32px;
        }

        .hero-blood-icon {
            display: none;
        }

        .requests-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .request-row {
            padding: 18px;
        }

        .request-row .status-area {
            margin-top: 10px;
        }
    }
</style>


<div class="dashboard-page">

    <div class="container">

        

        <div class="dashboard-hero mb-4">

            <div class="hero-content">

                <div class="hero-badge">

                    <span class="hero-dot"></span>

                    BloodNexus • User Dashboard

                </div>

                <h1 class="hero-title">

                    Welcome back,
                    <?php echo e(auth()->user()->name ?? 'User'); ?> 👋

                </h1>

                <p class="hero-subtitle mb-0">

                    Manage your blood requests, find donors,
                    connect with people and get intelligent
                    assistance from one premium dashboard.

                </p>

                <div class="hero-actions">

                    <a
                        href="<?php echo e(route('blood.request')); ?>"
                        class="hero-primary-btn"
                    >

                        <i class="bi bi-plus-circle-fill me-1"></i>

                        Need Blood

                    </a>

                    <a
                        href="<?php echo e(route('blood.search')); ?>"
                        class="hero-secondary-btn"
                    >

                        <i class="bi bi-search me-1"></i>

                        Find Blood

                    </a>

                </div>

            </div>

            <div class="hero-blood-icon">
                🩸
            </div>

        </div>


        

        <div class="row g-3 mb-5">

            <div class="col-6 col-xl-3">

                <div class="stat-card">

                    <div class="stat-icon stat-red">
                        <i class="bi bi-clipboard2-pulse-fill"></i>
                    </div>

                    <div class="stat-label">
                        Total Requests
                    </div>

                    <div class="stat-number">
                        <?php echo e($totalRequests); ?>

                    </div>

                </div>

            </div>


            <div class="col-6 col-xl-3">

                <div class="stat-card">

                    <div class="stat-icon stat-orange">
                        <i class="bi bi-hourglass-split"></i>
                    </div>

                    <div class="stat-label">
                        Pending
                    </div>

                    <div class="stat-number text-warning">
                        <?php echo e($pendingRequests); ?>

                    </div>

                </div>

            </div>


            <div class="col-6 col-xl-3">

                <div class="stat-card">

                    <div class="stat-icon stat-green">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                    <div class="stat-label">
                        Accepted
                    </div>

                    <div class="stat-number text-success">
                        <?php echo e($acceptedRequests); ?>

                    </div>

                </div>

            </div>


            <div class="col-6 col-xl-3">

                <div class="stat-card">

                    <div class="stat-icon stat-blue">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>

                    <div class="stat-label">
                        Completed
                    </div>

                    <div class="stat-number text-primary">
                        <?php echo e($completedRequests); ?>

                    </div>

                </div>

            </div>

        </div>


        

        <div class="mb-5">

            <div class="mb-3">

                <div class="section-title">
                    Quick Access
                </div>

                <div class="section-subtitle">
                    Everything you need, just one click away.
                </div>

            </div>


            <div class="row g-3">


                

                <div class="col-12 col-md-6 col-xl-3">

                    <a
                        href="<?php echo e(route('blood.request')); ?>"
                        class="quick-card"
                    >

                        <div class="quick-icon quick-red">
                            🚨
                        </div>

                        <div class="quick-title">
                            Need Blood
                        </div>

                        <div class="quick-description">
                            Create a new blood request with
                            patient and hospital details.
                        </div>

                        <div class="quick-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </div>

                    </a>

                </div>


                

                <div class="col-12 col-md-6 col-xl-3">

                    <a
                        href="<?php echo e(route('blood.requests.mine')); ?>"
                        class="quick-card"
                    >

                        <div class="quick-icon quick-blue">
                            📋
                        </div>

                        <div class="quick-title">
                            My Requests
                        </div>

                        <div class="quick-description">
                            Track your submitted blood requests
                            and their current status.
                        </div>

                        <div class="quick-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </div>

                    </a>

                </div>


                

                <div class="col-12 col-md-6 col-xl-3">

                    <a
                        href="<?php echo e(route('messages.index')); ?>"
                        class="quick-card"
                    >

                        <div class="quick-icon quick-purple">
                            💬
                        </div>

                        <div class="quick-title">
                            Messages
                        </div>

                        <div class="quick-description">
                            Communicate with donors and other
                            users through secure messaging.
                        </div>

                        <div class="quick-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </div>

                    </a>

                </div>


                

                <div class="col-12 col-md-6 col-xl-3">

                    <a
                        href="<?php echo e(route('ai.assistant')); ?>"
                        class="quick-card"
                    >

                        <div class="quick-icon quick-green">
                            🤖
                        </div>

                        <div class="quick-title">
                            AI Assistant
                        </div>

                        <div class="quick-description">
                            Get smart guidance about blood
                            requests, donors and workflows.
                        </div>

                        <div class="quick-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </div>

                    </a>

                </div>

            </div>

        </div>


        

        <div class="row g-4">


            

            <div class="col-lg-8">

                <div class="requests-card">

                    <div class="requests-header">

                        <div>

                            <div class="section-title">
                                Recent Requests
                            </div>

                            <div class="section-subtitle">
                                Your latest blood requests
                            </div>

                        </div>


                        <a
                            href="<?php echo e(route('blood.requests.mine')); ?>"
                            class="btn btn-sm btn-outline-danger rounded-pill px-3"
                        >

                            View All

                            <i class="bi bi-arrow-right ms-1"></i>

                        </a>

                    </div>


                    <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <div class="request-row">

                            <div
                                class="d-flex justify-content-between align-items-center gap-3"
                            >

                                <div class="d-flex align-items-center gap-3">

                                    <div class="request-blood">

                                        <?php echo e($request->blood_group); ?>


                                    </div>

                                    <div>

                                        <div class="request-name">

                                            <?php echo e($request->patient_name); ?>


                                        </div>

                                        <div class="request-meta">

                                            <i class="bi bi-hospital me-1"></i>

                                            <?php echo e($request->hospital); ?>


                                            <span class="mx-1">•</span>

                                            <i class="bi bi-geo-alt me-1"></i>

                                            <?php echo e($request->city); ?>


                                        </div>

                                    </div>

                                </div>


                                <div class="status-area">

                                    <?php if($request->status === 'accepted'): ?>

                                        <span class="status-badge status-accepted">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Accepted
                                        </span>

                                    <?php elseif($request->status === 'completed'): ?>

                                        <span class="status-badge status-completed">
                                            <i class="bi bi-patch-check me-1"></i>
                                            Completed
                                        </span>

                                    <?php elseif($request->status === 'cancelled'): ?>

                                        <span class="status-badge status-cancelled">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Cancelled
                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge status-pending">
                                            <i class="bi bi-clock me-1"></i>
                                            Pending
                                        </span>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <div class="empty-state">

                            <div class="empty-icon">
                                🩸
                            </div>

                            <h4 class="fw-bold mt-3 mb-2">
                                No blood requests yet
                            </h4>

                            <p class="text-secondary mb-0">
                                Your submitted requests will
                                appear here.
                            </p>

                            <a
                                href="<?php echo e(route('blood.request')); ?>"
                                class="create-btn"
                            >

                                <i class="bi bi-plus-circle-fill"></i>

                                Create Blood Request

                            </a>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


            

            <div class="col-lg-4">

                <div class="info-card">

                    <div class="info-icon">
                        🤖
                    </div>

                    <h4>
                        Smart Blood Assistant
                    </h4>

                    <p>

                        Need help understanding blood groups,
                        requests, donors or notifications?

                        Your AI Assistant is ready to guide
                        you through the platform.

                    </p>

                    <a
                        href="<?php echo e(route('ai.assistant')); ?>"
                        class="info-link"
                    >

                        Open AI Assistant

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

        </div>


        

        <div class="mt-4">

            <div class="requests-card p-4">

                <div class="row align-items-center g-3">

                    <div class="col-md-8">

                        <div class="d-flex align-items-center gap-3">

                            <div
                                class="quick-icon quick-red mb-0 flex-shrink-0"
                            >
                                ❤️
                            </div>

                            <div>

                                <div class="fw-bold fs-5">
                                    Every request can save a life.
                                </div>

                                <div class="small text-secondary">
                                    Keep your blood request information
                                    updated and stay connected.
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-4 text-md-end">

                        <a
                            href="<?php echo e(route('notifications.index')); ?>"
                            class="btn btn-dark rounded-pill px-4 fw-bold"
                        >

                            <i class="bi bi-bell-fill me-1"></i>

                            Notifications

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\BloodNexus\resources\views/dashboard.blade.php ENDPATH**/ ?>