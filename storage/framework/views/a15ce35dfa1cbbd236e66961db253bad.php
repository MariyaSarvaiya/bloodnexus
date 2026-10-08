<?php $__env->startSection('title', 'BloodNexus | Save Lives'); ?>

<?php $__env->startSection('content'); ?>

<style>

    /* =========================================================
       PREMIUM HOME PAGE
    ========================================================= */

    .home-page {
        overflow: hidden;
    }


    /* =========================================================
       HERO
    ========================================================= */

    .hero-section {
        position: relative;
        padding: 95px 0 85px;

        background:
            radial-gradient(
                circle at 10% 10%,
                rgba(223,38,61,.13),
                transparent 32%
            ),
            radial-gradient(
                circle at 90% 20%,
                rgba(113,88,255,.10),
                transparent 30%
            ),
            linear-gradient(
                135deg,
                #fbfcff 0%,
                #f7f8fc 50%,
                #fff6f7 100%
            );
    }


    .hero-section::before {
        content: "";

        position: absolute;

        width: 380px;
        height: 380px;

        border-radius: 50%;

        background: rgba(223,38,61,.06);

        filter: blur(60px);

        top: -120px;
        left: -150px;

        pointer-events: none;
    }


    .hero-section::after {
        content: "";

        position: absolute;

        width: 350px;
        height: 350px;

        border-radius: 50%;

        background: rgba(91,76,255,.06);

        filter: blur(70px);

        bottom: -130px;
        right: -120px;

        pointer-events: none;
    }


    .hero-content {
        position: relative;
        z-index: 2;
    }


    .hero-badge {
        display: inline-flex;

        align-items: center;
        gap: 8px;

        padding: 8px 14px;

        border-radius: 30px;

        background: #fff0f3;

        border: 1px solid #ffdce2;

        color: #d8233b;

        font-size: 12px;

        font-weight: 800;

        letter-spacing: .5px;

        text-transform: uppercase;

        box-shadow:
            0 8px 20px rgba(223,38,61,.08);
    }


    .hero-title {
        margin-top: 24px;

        font-size: clamp(
            48px,
            6vw,
            76px
        );

        line-height: .98;

        font-weight: 900;

        letter-spacing: -3px;

        color: #171c29;

        max-width: 760px;
    }


    .hero-title .highlight {
        display: block;

        color: #df263d;
    }


    .hero-description {
        margin-top: 25px;

        max-width: 650px;

        font-size: 18px;

        line-height: 1.7;

        color: #697386;
    }


    .hero-actions {
        display: flex;

        flex-wrap: wrap;

        gap: 13px;

        margin-top: 32px;
    }


    .hero-primary-btn {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: 9px;

        padding: 14px 25px;

        border-radius: 14px;

        color: #fff !important;

        background:
            linear-gradient(
                135deg,
                #df263d,
                #b91f32
            );

        font-weight: 800;

        box-shadow:
            0 12px 28px rgba(223,38,61,.24);

        transition: .25s ease;
    }


    .hero-primary-btn:hover {
        transform: translateY(-3px);

        box-shadow:
            0 18px 35px rgba(223,38,61,.30);
    }


    .hero-secondary-btn {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: 9px;

        padding: 14px 25px;

        border-radius: 14px;

        background: #fff;

        color: #222936 !important;

        border: 1px solid #e4e8ef;

        font-weight: 800;

        box-shadow:
            0 8px 22px rgba(20,25,35,.06);

        transition: .25s ease;
    }


    .hero-secondary-btn:hover {
        transform: translateY(-3px);

        border-color: #f0bdc5;

        background: #fff7f8;
    }


    /* =========================================================
       HERO VISUAL
    ========================================================= */

    .hero-visual {
        position: relative;

        min-height: 480px;

        display: flex;

        align-items: center;

        justify-content: center;
    }


    .hero-orbit {
        position: absolute;

        width: 420px;
        height: 420px;

        border-radius: 50%;

        border: 1px solid rgba(223,38,61,.12);

        animation: rotateOrbit 18s linear infinite;
    }


    .hero-orbit::before {
        content: "";

        position: absolute;

        width: 12px;
        height: 12px;

        border-radius: 50%;

        background: #df263d;

        top: 45px;
        left: 60px;

        box-shadow:
            0 0 0 8px rgba(223,38,61,.08);
    }


    .hero-orbit-2 {
        width: 330px;
        height: 330px;

        border-color: rgba(223,38,61,.08);

        animation-duration: 13s;

        animation-direction: reverse;
    }


    @keyframes rotateOrbit {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }


    .blood-glass-card {
        position: relative;

        z-index: 3;

        width: 320px;

        padding: 35px;

        border-radius: 30px;

        background:
            rgba(255,255,255,.88);

        backdrop-filter: blur(18px);

        border: 1px solid rgba(255,255,255,.9);

        box-shadow:
            0 30px 70px rgba(27,35,50,.13);
    }


    .blood-icon-large {
        width: 78px;
        height: 78px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 24px;

        background:
            linear-gradient(
                145deg,
                #fff0f3,
                #ffdce2
            );

        font-size: 42px;

        box-shadow:
            0 15px 30px rgba(223,38,61,.13);

        margin-bottom: 22px;
    }


    .blood-glass-card h3 {
        font-size: 26px;

        font-weight: 850;

        margin-bottom: 10px;
    }


    .blood-glass-card p {
        color: #747d8d;

        line-height: 1.65;

        margin-bottom: 22px;
    }


    .availability-pill {
        display: flex;

        align-items: center;

        justify-content: space-between;

        padding: 13px 15px;

        background: #f8f9fc;

        border: 1px solid #edf0f4;

        border-radius: 14px;

        font-size: 13px;

        font-weight: 700;
    }


    .availability-dot {
        width: 9px;
        height: 9px;

        background: #19a463;

        border-radius: 50%;

        display: inline-block;

        margin-right: 6px;

        box-shadow:
            0 0 0 5px rgba(25,164,99,.10);
    }


    /* =========================================================
       TRUST STATS
    ========================================================= */

    .trust-section {
        padding: 0 0 70px;

        background:
            linear-gradient(
                180deg,
                transparent,
                #fff
            );
    }


    .trust-card {
        background: #fff;

        border: 1px solid #edf0f4;

        border-radius: 20px;

        padding: 25px;

        box-shadow:
            0 12px 30px rgba(25,32,45,.06);

        height: 100%;

        transition: .25s ease;
    }


    .trust-card:hover {
        transform: translateY(-4px);

        box-shadow:
            0 18px 38px rgba(25,32,45,.10);
    }


    .trust-icon {
        width: 45px;
        height: 45px;

        border-radius: 13px;

        display: flex;

        align-items: center;
        justify-content: center;

        background: #fff0f3;

        color: #df263d;

        font-size: 20px;

        margin-bottom: 16px;
    }


    .trust-number {
        font-size: 27px;

        font-weight: 900;

        color: #1c2230;
    }


    .trust-label {
        color: #7b8493;

        font-size: 13px;
    }


    /* =========================================================
       FEATURES
    ========================================================= */

    .features-section {
        padding: 100px 0;

        background: #fff;
    }


    .section-eyebrow {
        display: inline-flex;

        padding: 7px 12px;

        border-radius: 30px;

        background: #fff0f3;

        color: #df263d;

        font-size: 11px;

        font-weight: 850;

        text-transform: uppercase;

        letter-spacing: .7px;
    }


    .section-title {
        margin-top: 16px;

        font-size: clamp(
            34px,
            4vw,
            50px
        );

        font-weight: 900;

        letter-spacing: -1.8px;

        color: #181e2b;
    }


    .section-description {
        color: #788293;

        max-width: 650px;

        line-height: 1.7;

        font-size: 16px;
    }


    .feature-card {
        position: relative;

        height: 100%;

        padding: 30px;

        background: #fff;

        border: 1px solid #edf0f4;

        border-radius: 24px;

        box-shadow:
            0 12px 35px rgba(25,32,45,.06);

        transition:
            transform .28s ease,
            box-shadow .28s ease;
    }


    .feature-card:hover {
        transform: translateY(-7px);

        box-shadow:
            0 22px 45px rgba(25,32,45,.11);
    }


    .feature-icon {
        width: 58px;
        height: 58px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 17px;

        background:
            linear-gradient(
                145deg,
                #fff0f3,
                #ffe3e8
            );

        color: #df263d;

        font-size: 25px;

        margin-bottom: 22px;
    }


    .feature-card h4 {
        font-size: 21px;

        font-weight: 850;

        margin-bottom: 10px;
    }


    .feature-card p {
        color: #7a8392;

        line-height: 1.65;

        margin-bottom: 22px;
    }


    .feature-link {
        color: #df263d;

        font-size: 14px;

        font-weight: 800;
    }


    /* =========================================================
       AI FEATURE
    ========================================================= */

    .ai-section {
        padding: 100px 0;

        background:
            radial-gradient(
                circle at 80% 30%,
                rgba(223,38,61,.12),
                transparent 35%
            ),
            #171b24;

        color: #fff;

        position: relative;

        overflow: hidden;
    }


    .ai-section::before {
        content: "AI";

        position: absolute;

        right: -20px;
        bottom: -90px;

        font-size: 300px;

        font-weight: 950;

        color: rgba(255,255,255,.025);

        line-height: 1;
    }


    .ai-label {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        padding: 8px 13px;

        border-radius: 30px;

        background: rgba(255,255,255,.08);

        border: 1px solid rgba(255,255,255,.12);

        font-size: 11px;

        font-weight: 800;

        letter-spacing: .7px;

        color: #ffb8c1;
    }


    .ai-section h2 {
        margin-top: 18px;

        font-size: clamp(
            34px,
            4vw,
            52px
        );

        font-weight: 900;

        letter-spacing: -1.7px;
    }


    .ai-section p {
        color: #aeb5c2;

        line-height: 1.7;

        max-width: 600px;

        margin-top: 15px;
    }


    .ai-feature-box {
        padding: 30px;

        border-radius: 25px;

        background:
            rgba(255,255,255,.06);

        border: 1px solid rgba(255,255,255,.10);

        backdrop-filter: blur(15px);
    }


    .ai-feature-row {
        display: flex;

        align-items: center;

        gap: 15px;

        padding: 15px 0;

        border-bottom:
            1px solid rgba(255,255,255,.08);
    }


    .ai-feature-row:last-child {
        border-bottom: none;
    }


    .ai-feature-row-icon {
        width: 42px;
        height: 42px;

        border-radius: 13px;

        display: flex;

        align-items: center;
        justify-content: center;

        background: rgba(223,38,61,.18);

        color: #ff8e9d;
    }


    .ai-feature-row strong {
        display: block;

        font-size: 14px;
    }


    .ai-feature-row span {
        color: #8e97a8;

        font-size: 12px;
    }


    /* =========================================================
       HOW IT WORKS
    ========================================================= */

    .how-section {
        padding: 100px 0;

        background: #f7f8fc;
    }


    .step-card {
        position: relative;

        padding: 30px;

        background: #fff;

        border: 1px solid #edf0f4;

        border-radius: 22px;

        height: 100%;

        box-shadow:
            0 10px 28px rgba(25,32,45,.05);
    }


    .step-number {
        width: 45px;
        height: 45px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background: #df263d;

        color: #fff;

        font-weight: 900;

        margin-bottom: 20px;

        box-shadow:
            0 9px 20px rgba(223,38,61,.22);
    }


    .step-card h4 {
        font-weight: 850;

        font-size: 19px;
    }


    .step-card p {
        color: #7b8492;

        line-height: 1.65;

        margin-bottom: 0;
    }


    /* =========================================================
       FINAL CTA
    ========================================================= */

    .cta-section {
        padding: 100px 0;
        background: #fff;
    }


    .cta-box {
        position: relative;

        overflow: hidden;

        padding: 65px 50px;

        border-radius: 30px;

        background:
            linear-gradient(
                135deg,
                #df263d,
                #a7192e
            );

        box-shadow:
            0 25px 60px rgba(223,38,61,.22);

        color: #fff;
    }


    .cta-box::before {
        content: "";

        position: absolute;

        width: 280px;
        height: 280px;

        border-radius: 50%;

        background: rgba(255,255,255,.08);

        top: -120px;
        right: -60px;
    }


    .cta-box h2 {
        position: relative;

        z-index: 2;

        font-size: clamp(
            32px,
            4vw,
            48px
        );

        font-weight: 900;

        letter-spacing: -1.4px;
    }


    .cta-box p {
        position: relative;

        z-index: 2;

        color: rgba(255,255,255,.82);

        max-width: 620px;

        line-height: 1.7;
    }


    .cta-btn {
        position: relative;

        z-index: 2;

        display: inline-flex;

        align-items: center;

        gap: 8px;

        margin-top: 15px;

        padding: 13px 22px;

        border-radius: 13px;

        background: #fff;

        color: #c82038 !important;

        font-weight: 850;

        transition: .25s ease;
    }


    .cta-btn:hover {
        transform: translateY(-3px);

        box-shadow:
            0 12px 25px rgba(0,0,0,.18);
    }


    /* =========================================================
       FOOTER
    ========================================================= */

    .home-footer {
        padding: 40px 0;

        background: #11151c;

        color: #fff;
    }


    .footer-brand {
        color: #fff;

        font-size: 20px;

        font-weight: 850;
    }


    .footer-text {
        color: #858e9e;

        font-size: 13px;

        margin-top: 8px;
    }


    .footer-bottom {
        margin-top: 25px;

        padding-top: 22px;

        border-top:
            1px solid rgba(255,255,255,.08);

        color: #70798a;

        font-size: 12px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991px) {

        .hero-section {
            padding: 70px 0;
        }


        .hero-title {
            letter-spacing: -2px;
        }


        .hero-visual {
            min-height: 400px;

            margin-top: 30px;
        }


        .hero-orbit {
            width: 330px;
            height: 330px;
        }


        .hero-orbit-2 {
            width: 260px;
            height: 260px;
        }

    }


    @media (max-width: 576px) {

        .hero-title {
            font-size: 45px;
        }


        .hero-description {
            font-size: 16px;
        }


        .hero-actions {
            flex-direction: column;
        }


        .hero-primary-btn,
        .hero-secondary-btn {
            width: 100%;
        }


        .blood-glass-card {
            width: 290px;
        }


        .cta-box {
            padding: 45px 25px;
        }

    }


    .donor-home-panel{display:flex;align-items:center;gap:15px;padding:15px 17px;border-radius:20px;background:linear-gradient(135deg,rgba(255,255,255,.92),rgba(255,241,243,.96));border:1px solid #ffe0e5;box-shadow:0 12px 30px rgba(223,38,61,.09);animation:donorPanelIn .7s ease both;}
    .donor-home-icon{width:46px;height:46px;flex:0 0 46px;border-radius:15px;display:grid;place-items:center;background:linear-gradient(135deg,#dc2638,#7f1d1d);color:#fff;font-size:21px;box-shadow:0 8px 20px rgba(220,38,56,.24);animation:heartbeat 1.8s infinite;}
    .donor-home-eyebrow{font-size:9px;font-weight:900;letter-spacing:1.3px;color:#b91c1c;}
    .donor-home-title{font-size:13px;font-weight:850;color:#172033;margin-top:2px;}
    .donor-home-meta{display:flex;flex-wrap:wrap;gap:10px;margin-top:7px;font-size:10px;color:#64748b;font-weight:700;}
    .donor-home-meta span{display:inline-flex;align-items:center;gap:4px;}
    .donor-feature-card{background:linear-gradient(145deg,#fff,#fff6f7)!important;border-color:#ffe0e5!important;}
    .donor-feature-icon{background:linear-gradient(135deg,#fff0f2,#ffdce2)!important;color:#dc2638;}
    @keyframes heartbeat{0%,100%{transform:scale(1)}15%{transform:scale(1.08)}30%{transform:scale(1)}45%{transform:scale(1.06)}}
    @keyframes donorPanelIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}


    .bn-hero-noise{position:absolute;inset:0;opacity:.18;pointer-events:none;background-image:radial-gradient(#111827 0.7px,transparent .7px);background-size:18px 18px;mask-image:linear-gradient(to bottom,black,transparent 78%)}
    .bn-floating-blood{position:absolute;z-index:1;font-size:34px;filter:drop-shadow(0 12px 20px rgba(220,38,56,.18));animation:bnDrift 5s ease-in-out infinite}
    .bn-floating-blood.one{top:12%;right:8%}.bn-floating-blood.two{bottom:12%;left:7%;font-size:26px;animation-delay:1.2s}.bn-floating-blood.three{top:52%;right:3%;font-size:22px;animation-delay:2.1s}
    .bn-live-chip{display:inline-flex;align-items:center;gap:7px;padding:8px 11px;border-radius:999px;background:rgba(17,24,39,.92);color:#fff;font-size:10px;font-weight:900;box-shadow:0 10px 24px rgba(15,23,42,.14)}
    .bn-live-dot{width:7px;height:7px;border-radius:50%;background:#22c55e;box-shadow:0 0 0 5px rgba(34,197,94,.12);animation:bnPulse 1.7s infinite}
    @keyframes bnDrift{0%,100%{transform:translate3d(0,0,0) rotate(-4deg)}50%{transform:translate3d(0,-15px,0) rotate(5deg)}}
    @keyframes bnPulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(.7);opacity:.65}}
    .bn-security-strip{margin-top:18px;display:flex;gap:9px;flex-wrap:wrap}

</style>


<div class="home-page position-relative"><span class="bn-floating-drop" style="top:15%;left:7%;position:absolute;z-index:1"></span><span class="bn-floating-drop" style="top:38%;right:8%;animation-delay:1.4s;position:absolute;z-index:1"></span><span class="bn-floating-drop" style="bottom:18%;left:12%;animation-delay:2.4s;position:absolute;z-index:1"></span>


    

    <section class="hero-section">
        <div class="bn-hero-noise"></div>
        <span class="bn-floating-blood one">🩸</span><span class="bn-floating-blood two">🩸</span><span class="bn-floating-blood three">🩸</span>

        <div class="container">

            <div class="row align-items-center g-5">


                

                <div class="col-lg-7">

                    <div class="hero-content bn-reveal">

                        <div class="hero-badge">

                            <i class="bi bi-stars"></i>

                            Smart Blood Connection

                        </div>


                        <h1 class="hero-title">

                            Find the right blood.

                            <span class="highlight">
                                Faster when it matters.
                            </span>

                        </h1>


                        <p class="hero-description">

                            A smarter blood-bank platform that connects
                            people who need blood with available donors
                            through blood-group and city-based matching.

                        </p>


                        <div class="hero-actions">

                            <?php if(auth()->guard()->check()): ?>

                                <?php if(auth()->user()->role === 'donor'): ?>

                                    <a href="<?php echo e(route('donor.dashboard')); ?>" class="hero-primary-btn">
                                        <i class="bi bi-heart-pulse-fill"></i>
                                        Open Donor Center
                                    </a>

                                    <a href="<?php echo e(route('donor.requests')); ?>" class="hero-secondary-btn">
                                        <i class="bi bi-droplet-half"></i>
                                        Matching Requests
                                    </a>

                                <?php else: ?>

                                    <a href="<?php echo e(route('blood.request')); ?>" class="hero-primary-btn">
                                        <i class="bi bi-droplet-fill"></i>
                                        Need Blood
                                    </a>

                                    <a href="<?php echo e(route('blood.search')); ?>" class="hero-secondary-btn">
                                        <i class="bi bi-search"></i>
                                        Find Blood
                                    </a>

                                <?php endif; ?>

                            <?php else: ?>

                                <a
                                    href="<?php echo e(route('register')); ?>"
                                    class="hero-primary-btn"
                                >

                                    <i class="bi bi-person-plus-fill"></i>

                                    Create Account

                                </a>


                                <a
                                    href="<?php echo e(route('login')); ?>"
                                    class="hero-secondary-btn"
                                >

                                    <i class="bi bi-box-arrow-in-right"></i>

                                    Login

                                </a>

                            <?php endif; ?>

                        </div>

                        <?php if(auth()->guard()->check()): ?>
                            <?php if(auth()->user()->role === 'donor'): ?>
                                <div class="donor-home-panel mt-4">
                                    <div class="donor-home-icon">❤️</div>
                                    <div class="donor-home-copy">
                                        <div class="donor-home-eyebrow">DONOR IMPACT CENTER</div>
                                        <div class="donor-home-title">Your donation can become someone's turning point.</div>
                                        <div class="donor-home-meta">
                                            <span><i class="bi bi-droplet-fill"></i> <?php echo e(auth()->user()->blood_group ?? 'Blood group'); ?></span>
                                            <span><i class="bi bi-geo-alt-fill"></i> <?php echo e(auth()->user()->city ?? 'Your city'); ?></span>
                                            <span><i class="bi bi-shield-check"></i> Verified donor portal</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="bn-security-strip">
                            <span class="bn-live-chip"><span class="bn-live-dot"></span> Live matching</span>
                            <span class="bn-live-chip"><i class="bi bi-shield-check text-success"></i> Secure request tracking</span>
                            <span class="bn-live-chip"><i class="bi bi-stars text-warning"></i> AI assistance</span>
                        </div>

                        <div class="d-flex flex-wrap gap-4 mt-4">

                            <span class="small text-secondary">

                                <i class="bi bi-check-circle-fill text-danger me-1"></i>

                                Blood-group search

                            </span>


                            <span class="small text-secondary">

                                <i class="bi bi-check-circle-fill text-danger me-1"></i>

                                City filtering

                            </span>


                            <span class="small text-secondary">

                                <i class="bi bi-check-circle-fill text-danger me-1"></i>

                                Request tracking

                            </span>

                        </div>

                    </div>

                </div>


                

                <div class="col-lg-5">

                    <div class="hero-visual bn-reveal">

                        <div class="hero-orbit"></div>

                        <div class="hero-orbit hero-orbit-2"></div>


                        <div class="blood-glass-card">

                            <div class="blood-icon-large">

                                🩸

                            </div>


                            <h3>
                                Blood Need Portal
                            </h3>


                            <p>

                                Search blood, create requests and
                                track your request status from one
                                secure place.

                            </p>


                            <div class="availability-pill">

                                <span>

                                    <span class="availability-dot"></span>

                                    Blood Connect

                                </span>


                                <span class="text-success">

                                    Active

                                </span>

                            </div>

                        </div>

                    </div>

                </div>


            </div>

        </div>

    </section>


    

    <section class="trust-section">

        <div class="container">

            <div class="row g-3">


                <div class="col-6 col-lg-3">

                    <div class="trust-card">

                        <div class="trust-icon">

                            <i class="bi bi-droplet-fill"></i>

                        </div>

                        <div class="trust-number">
                            8+
                        </div>

                        <div class="trust-label">
                            Blood Groups Supported
                        </div>

                    </div>

                </div>


                <div class="col-6 col-lg-3">

                    <div class="trust-card">

                        <div class="trust-icon">

                            <i class="bi bi-search"></i>

                        </div>

                        <div class="trust-number">
                            Fast
                        </div>

                        <div class="trust-label">
                            Blood Search
                        </div>

                    </div>

                </div>


                <div class="col-6 col-lg-3">

                    <div class="trust-card">

                        <div class="trust-icon">

                            <i class="bi bi-clipboard2-check-fill"></i>

                        </div>

                        <div class="trust-number">
                            Live
                        </div>

                        <div class="trust-label">
                            Request Tracking
                        </div>

                    </div>

                </div>


                <div class="col-6 col-lg-3">

                    <div class="trust-card">

                        <div class="trust-icon">

                            <i class="bi bi-robot"></i>

                        </div>

                        <div class="trust-number">
                            AI
                        </div>

                        <div class="trust-label">
                            Smart Assistance
                        </div>

                    </div>

                </div>


            </div>

        </div>

    </section>


    

    <section class="features-section">

        <div class="container">


            <div class="text-center mb-5">

                <span class="section-eyebrow">
                    Everything in one place
                </span>


                <h2 class="section-title">
                    Your complete blood-connect platform.
                </h2>


                <p class="section-description mx-auto mt-3">

                    From finding blood to tracking requests,
                    communicating with donors and getting AI guidance,
                    everything is designed around one simple goal:
                    making blood access easier.

                </p>

            </div>


            <div class="row g-4">


                

                <div class="col-md-6 col-lg-4">

                    <div class="feature-card">

                        <div class="feature-icon">

                            <i class="bi bi-search"></i>

                        </div>


                        <h4>
                            Find Blood
                        </h4>


                        <p>

                            Search available blood donors by
                            blood group and city without wasting time.

                        </p>


                        <?php if(auth()->guard()->check()): ?>

                            <?php if(auth()->user()->role === 'donor'): ?>
                                <a href="<?php echo e(route('donor.requests')); ?>" class="feature-link">
                                    Find Matching Requests <i class="bi bi-arrow-right"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e(route('blood.search')); ?>" class="feature-link">
                                    Search Donors <i class="bi bi-arrow-right"></i>
                                </a>
                            <?php endif; ?>

                        <?php else: ?>

                            <a
                                href="<?php echo e(route('login')); ?>"
                                class="feature-link"
                            >

                                Login to Search

                                <i class="bi bi-arrow-right"></i>

                            </a>

                        <?php endif; ?>

                    </div>

                </div>


                

                <div class="col-md-6 col-lg-4">

                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->role === 'donor'): ?>
                            <div class="feature-card donor-feature-card">
                                <div class="feature-icon donor-feature-icon">
                                    <i class="bi bi-heart-pulse-fill"></i>
                                </div>
                                <h4>Donate With Purpose</h4>
                                <p>
                                    See requests matching your blood group and city, then respond when you are ready to help.
                                </p>
                                <a href="<?php echo e(route('donor.requests')); ?>" class="feature-link">
                                    View Matching Requests <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="feature-card">
                                <div class="feature-icon"><i class="bi bi-broadcast-pin"></i></div>
                                <h4>Need Blood</h4>
                                <p>Create normal, urgent or critical blood requests with hospital and patient details.</p>
                                <a href="<?php echo e(route('blood.request')); ?>" class="feature-link">Create Request <i class="bi bi-arrow-right"></i></a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="feature-card">
                            <div class="feature-icon"><i class="bi bi-broadcast-pin"></i></div>
                            <h4>Need Blood</h4>
                            <p>Create normal, urgent or critical blood requests with hospital and patient details.</p>
                            <a href="<?php echo e(route('register')); ?>" class="feature-link">Get Started <i class="bi bi-arrow-right"></i></a>
                        </div>
                    <?php endif; ?>

                </div>


                

                <div class="col-md-6 col-lg-4">

                    <div class="feature-card">

                        <div class="feature-icon">

                            <i class="bi bi-clipboard2-check-fill"></i>

                        </div>


                        <h4>
                            Track Requests
                        </h4>


                        <p>

                            Keep your blood requests organized
                            and monitor pending, accepted,
                            completed or cancelled status.

                        </p>


                        <?php if(auth()->guard()->check()): ?>

                            <?php if(auth()->user()->role === 'donor'): ?>
                                <a href="<?php echo e(route('donor.requests')); ?>" class="feature-link">
                                    Donation Requests <i class="bi bi-arrow-right"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e(route('blood.requests.mine')); ?>" class="feature-link">
                                    View Requests <i class="bi bi-arrow-right"></i>
                                </a>
                            <?php endif; ?>

                        <?php else: ?>

                            <a
                                href="<?php echo e(route('login')); ?>"
                                class="feature-link"
                            >

                                Login to Track

                                <i class="bi bi-arrow-right"></i>

                            </a>

                        <?php endif; ?>

                    </div>

                </div>


                

                <div class="col-md-6 col-lg-4">

                    <div class="feature-card">

                        <div class="feature-icon">

                            <i class="bi bi-chat-dots-fill"></i>

                        </div>


                        <h4>
                            Messages
                        </h4>


                        <p>

                            Communicate with other users and
                            donors through the built-in messaging
                            system.

                        </p>


                        <?php if(auth()->guard()->check()): ?>

                            <a
                                href="<?php echo e(route('messages.index')); ?>"
                                class="feature-link"
                            >

                                Open Messages

                                <i class="bi bi-arrow-right"></i>

                            </a>

                        <?php else: ?>

                            <a
                                href="<?php echo e(route('login')); ?>"
                                class="feature-link"
                            >

                                Login to Message

                                <i class="bi bi-arrow-right"></i>

                            </a>

                        <?php endif; ?>

                    </div>

                </div>


                

                <div class="col-md-6 col-lg-4">

                    <div class="feature-card">

                        <div class="feature-icon">

                            <i class="bi bi-bell-fill"></i>

                        </div>


                        <h4>
                            Notifications
                        </h4>


                        <p>

                            Stay updated about your blood requests,
                            status changes and important activity.

                        </p>


                        <?php if(auth()->guard()->check()): ?>

                            <a
                                href="<?php echo e(route('notifications.index')); ?>"
                                class="feature-link"
                            >

                                View Notifications

                                <i class="bi bi-arrow-right"></i>

                            </a>

                        <?php else: ?>

                            <a
                                href="<?php echo e(route('login')); ?>"
                                class="feature-link"
                            >

                                Login to View

                                <i class="bi bi-arrow-right"></i>

                            </a>

                        <?php endif; ?>

                    </div>

                </div>


                

                <div class="col-md-6 col-lg-4">

                    <div class="feature-card">

                        <div class="feature-icon">

                            <i class="bi bi-robot"></i>

                        </div>


                        <h4>
                            AI Assistant
                        </h4>


                        <p>

                            Get simple guidance about blood requests,
                            donors, messages and important platform
                            features.

                        </p>


                        <?php if(auth()->guard()->check()): ?>

                            <a
                                href="<?php echo e(route('ai.assistant')); ?>"
                                class="feature-link"
                            >

                                Talk to AI

                                <i class="bi bi-arrow-right"></i>

                            </a>

                        <?php else: ?>

                            <a
                                href="<?php echo e(route('login')); ?>"
                                class="feature-link"
                            >

                                Meet AI Assistant

                                <i class="bi bi-arrow-right"></i>

                            </a>

                        <?php endif; ?>

                    </div>

                </div>


            </div>

        </div>

    </section>


    

    <section class="ai-section">

        <div class="container">

            <div class="row align-items-center g-5">


                <div class="col-lg-7">

                    <span class="ai-label">

                        <i class="bi bi-stars"></i>

                        AI BLOOD ASSISTANT

                    </span>


                    <h2>

                        Help when you don't know
                        what to do next.

                    </h2>


                    <p>

                        The AI Assistant helps users understand
                        blood requests, finding donors, messages
                        and platform features through simple,
                        easy-to-understand guidance.

                    </p>


                    <?php if(auth()->guard()->check()): ?>

                        <a
                            href="<?php echo e(route('ai.assistant')); ?>"
                            class="hero-primary-btn mt-3"
                        >

                            <i class="bi bi-robot"></i>

                            Open AI Assistant

                        </a>

                    <?php else: ?>

                        <a
                            href="<?php echo e(route('login')); ?>"
                            class="hero-primary-btn mt-3"
                        >

                            <i class="bi bi-box-arrow-in-right"></i>

                            Login to Use AI

                        </a>

                    <?php endif; ?>

                </div>


                <div class="col-lg-5">

                    <div class="ai-feature-box">


                        <div class="ai-feature-row">

                            <div class="ai-feature-row-icon">

                                <i class="bi bi-droplet-fill"></i>

                            </div>

                            <div>

                                <strong>
                                    Blood Request Guidance
                                </strong>

                                <span>
                                    Understand how to create requests
                                </span>

                            </div>

                        </div>


                        <div class="ai-feature-row">

                            <div class="ai-feature-row-icon">

                                <i class="bi bi-search"></i>

                            </div>

                            <div>

                                <strong>
                                    Donor Search Help
                                </strong>

                                <span>
                                    Learn how blood search works
                                </span>

                            </div>

                        </div>


                        <div class="ai-feature-row">

                            <div class="ai-feature-row-icon">

                                <i class="bi bi-chat-dots"></i>

                            </div>

                            <div>

                                <strong>
                                    Messaging Guidance
                                </strong>

                                <span>
                                    Learn how to use conversations
                                </span>

                            </div>

                        </div>


                        <div class="ai-feature-row">

                            <div class="ai-feature-row-icon">

                                <i class="bi bi-bell"></i>

                            </div>

                            <div>

                                <strong>
                                    Notification Help
                                </strong>

                                <span>
                                    Understand request updates
                                </span>

                            </div>

                        </div>


                    </div>

                </div>


            </div>

        </div>

    </section>


    

    <section class="how-section">

        <div class="container">


            <div class="text-center mb-5">

                <span class="section-eyebrow">
                    Simple process
                </span>


                <h2 class="section-title">
                    Get connected in four steps.
                </h2>


                <p class="section-description mx-auto mt-3">

                    We keep the process simple so users can focus
                    on what matters most.

                </p>

            </div>


            <div class="row g-4">


                <div class="col-md-6 col-lg-3">

                    <div class="step-card">

                        <div class="step-number">
                            01
                        </div>


                        <h4>
                            Create Account
                        </h4>


                        <p>

                            Register as a blood seeker and
                            access your personal dashboard.

                        </p>

                    </div>

                </div>


                <div class="col-md-6 col-lg-3">

                    <div class="step-card">

                        <div class="step-number">
                            02
                        </div>


                        <h4>
                            Find Blood
                        </h4>


                        <p>

                            Search donors using blood group
                            and city.

                        </p>

                    </div>

                </div>


                <div class="col-md-6 col-lg-3">

                    <div class="step-card">

                        <div class="step-number">
                            03
                        </div>


                        <h4>
                            Create Request
                        </h4>


                        <p>

                            Enter patient, hospital, units
                            and urgency details.

                        </p>

                    </div>

                </div>


                <div class="col-md-6 col-lg-3">

                    <div class="step-card">

                        <div class="step-number">
                            04
                        </div>


                        <h4>
                            Track Everything
                        </h4>


                        <p>

                            Monitor request status and receive
                            updates through notifications.

                        </p>

                    </div>

                </div>


            </div>

        </div>

    </section>


    

    <section class="cta-section">

        <div class="container">

            <div class="cta-box">


                <h2>
                    Every blood request matters.
                </h2>


                <p>

                    Start using BloodNexus to search blood,
                    create requests and keep everything organized
                    from one premium dashboard.

                </p>


                <?php if(auth()->guard()->check()): ?>

                    <a
                        href="<?php echo e(route('dashboard')); ?>"
                        class="cta-btn"
                    >

                        Open Dashboard

                        <i class="bi bi-arrow-right"></i>

                    </a>

                <?php else: ?>

                    <a
                        href="<?php echo e(route('register')); ?>"
                        class="cta-btn"
                    >

                        Create Your Account

                        <i class="bi bi-arrow-right"></i>

                    </a>

                <?php endif; ?>


            </div>

        </div>

    </section>


</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\BloodNexus\resources\views/home.blade.php ENDPATH**/ ?>