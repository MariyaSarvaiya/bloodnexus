<?php $__env->startSection('title', 'Donation History | BloodNexus'); ?>

<?php $__env->startSection('content'); ?>

<style>
    .donor-requests-page{min-height:calc(100vh - 78px);padding:42px 0 80px;background:radial-gradient(circle at 10% 0%,rgba(220,38,56,.08),transparent 28%),radial-gradient(circle at 90% 10%,rgba(124,58,237,.07),transparent 24%),#f7f9fc}
    .requests-shell{max-width:1180px}
    .requests-hero{position:relative;overflow:hidden;padding:38px;border-radius:30px;color:#fff;background:linear-gradient(135deg,#0b1730 0%,#16365b 48%,#c91f32 140%);box-shadow:0 26px 70px rgba(15,35,60,.18)}
    .requests-hero:before{content:"";position:absolute;width:280px;height:280px;border-radius:50%;right:-100px;top:-145px;background:rgba(255,255,255,.10);filter:blur(2px)}
    .requests-hero:after{content:"🩸";position:absolute;right:42px;bottom:-28px;font-size:130px;opacity:.10;transform:rotate(-12deg)}
    .hero-copy{position:relative;z-index:2}
    .hero-chip{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.13);border:1px solid rgba(255,255,255,.2);padding:8px 13px;border-radius:999px;font-size:11px;font-weight:900;letter-spacing:.5px;backdrop-filter:blur(10px)}
    .hero-title{font-size:clamp(30px,4vw,46px);letter-spacing:-1.5px;line-height:1.05}
    .hero-sub{max-width:650px;color:rgba(255,255,255,.76);font-size:14px;line-height:1.7}
    .hero-back{position:relative;z-index:3;background:#fff!important;color:#16243b!important;border:0!important;font-weight:900;box-shadow:0 12px 28px rgba(0,0,0,.16);transition:.25s}.hero-back:hover{transform:translateY(-3px);box-shadow:0 17px 34px rgba(0,0,0,.2)}
    .request-box{position:relative;background:rgba(255,255,255,.96);border:1px solid #e8edf4;border-radius:24px;padding:22px;margin-bottom:15px;box-shadow:0 10px 30px rgba(24,39,75,.055);transition:transform .25s ease,box-shadow .25s ease,border-color .25s ease;overflow:hidden}
    .request-box:before{content:"";position:absolute;left:0;top:0;bottom:0;width:4px;background:linear-gradient(180deg,#dc2638,#ff8090);opacity:.65}.request-box:hover{transform:translateY(-3px);box-shadow:0 18px 44px rgba(24,39,75,.10);border-color:#dce4ef}
    .blood-circle{width:66px;height:66px;border-radius:21px;display:flex;align-items:center;justify-content:center;background:linear-gradient(145deg,#fff0f2,#ffe0e5);border:1px solid #ffd0d7;color:#c91f32;font-weight:950;font-size:16px;box-shadow:0 10px 24px rgba(220,38,56,.11)}
    .request-title{font-size:18px;letter-spacing:-.3px}.meta{color:#718096;font-size:12px;margin-top:7px}.meta strong{color:#27364d}.request-status{font-size:10px;font-weight:900;letter-spacing:.45px;padding:7px 10px;border-radius:999px}
    .request-actions{display:flex;flex-wrap:wrap;gap:8px}.request-actions .btn{min-height:42px;font-size:12px;font-weight:900;box-shadow:0 7px 16px rgba(15,23,42,.07);transition:.22s}.request-actions .btn:hover{transform:translateY(-2px)}
    .ai-match{display:inline-flex;align-items:center;gap:7px;margin-top:11px;padding:7px 10px;border-radius:10px;background:#faf5ff;border:1px solid #eadcff;color:#6d28d9;font-size:11px}
    .empty-card{text-align:center;padding:65px 25px}.empty-icon{width:82px;height:82px;margin:auto;border-radius:28px;display:grid;place-items:center;background:#fff0f2;font-size:42px;box-shadow:0 14px 32px rgba(220,38,56,.10)}
    .bn-certificate-btn{position:relative;overflow:hidden;background:linear-gradient(135deg,#fff,#fff5f6);border:1px solid #ffc9d0;color:#c91f32;font-weight:900;box-shadow:0 8px 18px rgba(220,38,56,.10);transition:.25s}.bn-certificate-btn:hover{color:#a91527;transform:translateY(-2px);box-shadow:0 13px 28px rgba(220,38,56,.17);border-color:#ffadb8}.bn-cert-icon{display:inline-block;margin-right:5px;animation:bnCertPulse 1.8s ease-in-out infinite}@keyframes bnCertPulse{0%,100%{transform:scale(1) rotate(0)}50%{transform:scale(1.16) rotate(8deg)}}
    @media(max-width:767px){.donor-requests-page{padding-top:24px}.requests-hero{padding:27px 23px;border-radius:24px}.requests-hero:after{right:-8px;font-size:100px}.request-box{padding:18px}.request-actions{margin-top:5px}.request-actions .btn{width:100%}.blood-circle{width:58px;height:58px;border-radius:18px}.hero-title{font-size:32px}}
</style>


<div class="donor-requests-page">

<div class="container">


    <div class="requests-hero mb-4 bn-reveal">

        <div class="d-flex
                    flex-wrap
                    justify-content-between
                    align-items-center">

            <div>

                <span class="badge bg-white text-danger rounded-pill px-3 py-2">
                    🩸 DONOR PORTAL
                </span>

                <h1 class="fw-bold mt-3 mb-1">
                    Donation History
                </h1>

                <p class="mb-0 opacity-75">
                    Manage blood requests assigned to you.
                </p>

            </div>


            <a
                href="<?php echo e(route('donor.dashboard')); ?>"
                class="btn hero-back rounded-pill px-4 mt-3 mt-lg-0"
            >
                ← Donor Dashboard
            </a>

        </div>

    </div>


    <?php if(session('success')): ?>

        <div class="alert alert-success border-0 rounded-4">
            ✅ <?php echo e(session('success')); ?>

        </div>

    <?php endif; ?>


    <?php if(session('error')): ?>

        <div class="alert alert-danger border-0 rounded-4">
            ⚠️ <?php echo e(session('error')); ?>

        </div>

    <?php endif; ?>


    <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

        <div class="request-box bn-reveal">

            <div class="row align-items-center g-4">


                <div class="col-auto">

                    <div class="blood-circle">
                        <?php echo e($request->blood_group); ?>

                    </div>

                </div>


                <div class="col">

                    <div class="d-flex
                                flex-wrap
                                align-items-center
                                gap-2">

                        <h4 class="request-title fw-bold mb-0">
                            <?php echo e($request->patient_name); ?>

                        </h4>


                        <?php if($request->status === 'pending'): ?>

                            <span class="badge bg-warning text-dark request-status">
                                Pending
                            </span>

                        <?php elseif($request->status === 'accepted'): ?>

                            <span class="badge bg-success request-status">
                                Accepted
                            </span>

                        <?php elseif($request->status === 'completed'): ?>

                            <span class="badge bg-primary request-status">
                                Completed
                            </span>

                        <?php else: ?>

                            <span class="badge bg-secondary request-status">
                                <?php echo e(ucfirst($request->status)); ?>

                            </span>

                        <?php endif; ?>

                    </div>


                    <div class="meta">
                        🩸 Blood Group:
                        <strong>
                            <?php echo e($request->blood_group); ?>

                        </strong>
                    </div>


                    <div class="meta">
                        🏥 <?php echo e($request->hospital); ?>

                    </div>


                    <div class="meta">
                        📍 <?php echo e($request->city); ?>

                    </div>


                    <div class="meta">
                        📞
                        <?php echo e($request->contact_phone
                            ?? $request->contact
                            ?? 'N/A'); ?>

                    </div>


                    <div class="meta">

                        🚨
                        <?php echo e(ucfirst($request->urgency)); ?>


                        ·

                        <?php echo e($request->created_at->diffForHumans()); ?>


                    </div>
                    <?php if($request->status === 'pending'): ?>
                        <div class="ai-match"><strong>🤖 AI Match <?php echo e($request->ai_match_score ?? 0); ?>%</strong><span><?php echo e($request->ai_match_reason ?? 'Compatible request'); ?></span></div>
                    <?php endif; ?>

                </div>


                <div class="col-lg-auto">


                    <?php if($request->status === 'pending'): ?>

                        <div class="request-actions">

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
                                    class="btn btn-success rounded-pill px-4 fw-bold"
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
                                    class="btn btn-outline-danger rounded-pill px-4"
                                >
                                    💬 Message
                                </a>

                            <?php endif; ?>

                        </div>


                    <?php elseif($request->status === 'accepted'): ?>

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
                                class="btn btn-primary rounded-pill px-4 fw-bold"
                            >
                                🎉 Complete Donation
                            </button>

                        </form>

                    <?php elseif($request->status === 'completed'): ?>

                        <div class="request-actions align-items-center">
                            <span class="badge bg-primary rounded-pill px-4 py-2">
                                ✅ Donation Completed
                            </span>
                            <a href="<?php echo e(route('donor.certificate', $request->id)); ?>" class="btn bn-certificate-btn rounded-pill px-3"><span class="bn-cert-icon">✦</span> View Certificate</a>
                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>


    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

        <div class="request-box empty-card bn-reveal">

            <div class="empty-icon">🩸</div>

            <h3 class="fw-bold mt-3">
                No Donation History
            </h3>

            <p class="text-secondary">
                Matching blood requests will appear here.
            </p>

            <a
                href="<?php echo e(route('donor.dashboard')); ?>"
                class="btn btn-danger rounded-pill px-4"
            >
                ← Back to Dashboard
            </a>

        </div>

    <?php endif; ?>


</div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\BloodNexus\resources\views/donor-requests.blade.php ENDPATH**/ ?>