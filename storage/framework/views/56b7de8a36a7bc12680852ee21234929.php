<?php $__env->startSection('content'); ?>

<style>
    .blood-page {
        min-height: calc(100vh - 80px);
        padding: 50px 0 80px;
        background:
            radial-gradient(circle at top left, rgba(220, 38, 38, .12), transparent 35%),
            radial-gradient(circle at bottom right, rgba(37, 99, 235, .10), transparent 35%),
            #f8fafc;
    }

    .search-hero {
        border-radius: 28px;
        padding: 42px;
        color: white;
        background:
            linear-gradient(135deg, #991b1b, #dc2626 50%, #7f1d1d);
        box-shadow: 0 20px 50px rgba(127, 29, 29, .22);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .search-hero::after {
        content: "❤";
        position: absolute;
        right: 35px;
        top: 20px;
        font-size: 120px;
        opacity: .08;
    }

    .search-hero h1 {
        font-weight: 800;
        font-size: 38px;
        margin-bottom: 10px;
    }

    .search-box {
        background: white;
        border-radius: 22px;
        padding: 25px;
        box-shadow: 0 12px 35px rgba(15, 23, 42, .08);
        margin-bottom: 35px;
    }

    .form-control,
    .form-select {
        border-radius: 13px;
        padding: 13px 15px;
        border: 1px solid #e2e8f0;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, .10);
    }

    .search-btn {
        border: 0;
        border-radius: 13px;
        padding: 13px 25px;
        font-weight: 700;
        color: white;
        background: linear-gradient(135deg, #dc2626, #991b1b);
        transition: .25s;
    }

    .search-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(220, 38, 38, .25);
    }

    .donor-card {
        background: white;
        border-radius: 22px;
        padding: 25px;
        height: 100%;
        border: 1px solid #eef2f7;
        box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
        transition: .25s;
    }

    .donor-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(15, 23, 42, .12);
    }

    .blood-badge {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fee2e2;
        color: #b91c1c;
        font-weight: 800;
        font-size: 18px;
    }

    .available-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 11px;
        border-radius: 999px;
        background: #dcfce7;
        color: #15803d;
        font-size: 12px;
        font-weight: 700;
    }

    .request-btn {
        display: block;
        width: 100%;
        text-align: center;
        text-decoration: none;
        color: white;
        background: #0f172a;
        border-radius: 12px;
        padding: 12px;
        font-weight: 700;
        margin-top: 20px;
        transition: .25s;
    }

    .request-btn:hover {
        background: #dc2626;
        color: white;
    }

    .empty-box {
        text-align: center;
        background: white;
        border-radius: 22px;
        padding: 60px 25px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
    }
</style>

<div class="blood-page">

    <div class="container">

        
        <div class="search-hero">

            <h1>Find Blood Near You 🩸</h1>

            <p class="mb-0">
                Search available donors by blood group and city.
                Connect with the right donor when you need blood.
            </p>
            <div class="mt-3 small">
                Active request: <strong>#<?php echo e($bloodRequest->id); ?></strong> ·
                <?php echo e($bloodRequest->blood_group); ?> · <?php echo e($bloodRequest->hospital); ?> · <?php echo e($bloodRequest->city); ?>

            </div>

        </div>


        
        <div class="search-box">

            <form
                method="GET"
                action="<?php echo e(route('blood.search')); ?>"
            >

                <div class="row g-3 align-items-end">

                    <div class="col-md-5">
                        <label class="form-label fw-bold">Blood Group Needed</label>
                        <div class="form-control bg-light fw-bold">🩸 <?php echo e($bloodRequest->blood_group); ?> — compatible donors only</div>
                        <input type="hidden" name="request_id" value="<?php echo e($bloodRequest->id); ?>">
                    </div>


                    <div class="col-md-5">

                        <label class="form-label fw-bold">
                            City
                        </label>

                        <input
                            type="text"
                            name="city"
                            class="form-control"
                            value="<?php echo e($city); ?>"
                            placeholder="Enter your city"
                        >

                    </div>


                    <div class="col-md-2">

                        <button
                            type="submit"
                            class="search-btn w-100"
                        >
                            🔎 Search
                        </button>

                    </div>

                </div>

            </form>

        </div>


        

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold mb-1">
                    Available Donors
                </h3>

                <p class="text-muted mb-0">
                    <?php echo e($donors->count()); ?>

                    donor(s) currently available
                </p>

            </div>

            <a
                href="<?php echo e(route('blood.request')); ?>"
                class="btn btn-danger rounded-pill px-4"
            >
                Need Blood Now?
            </a>

        </div>


        

        <?php if($donors->count() > 0): ?>

            <div class="row g-4">

                <?php $__currentLoopData = $donors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <div class="col-lg-4 col-md-6">

                        <div class="donor-card">

                            <div class="d-flex justify-content-between align-items-start">

                                <div class="d-flex gap-3 align-items-center">

                                    <div class="blood-badge">
                                        <?php echo e($donor->blood_group); ?>

                                    </div>

                                    <div>

                                        <h5 class="fw-bold mb-1">
                                            <?php echo e($donor->name); ?>

                                        </h5>

                                        <small class="text-muted">
                                            📍 <?php echo e($donor->city); ?>

                                        </small>

                                    </div>

                                </div>

                                <span class="available-badge">
                                    ● Available
                                </span>

                            </div>


                            <hr class="my-4">


                            <div class="small text-muted">

                                <div class="mb-2">
                                    🩸 Blood Group:
                                    <strong class="text-dark">
                                        <?php echo e($donor->blood_group); ?>

                                    </strong>
                                </div>

                                <div class="mb-2">
                                    📍 City:
                                    <strong class="text-dark">
                                        <?php echo e($donor->city); ?>

                                    </strong>
                                </div>

                                <?php if(!empty($donor->phone)): ?>
                                    <div class="mb-2">📞 Contact available</div>
                                <?php endif; ?>

                                <?php
                                    $history = is_string($donor->medical_history) ? json_decode($donor->medical_history, true) : ($donor->medical_history ?? []);
                                    $historyLabels = [
                                        'chronic_condition' => 'Chronic condition',
                                        'regular_medicines' => 'Regular medicines',
                                        'allergies' => 'Known allergy',
                                        'recent_surgery' => 'Recent surgery/procedure',
                                        'fever_infection' => 'Current fever/infection',
                                        'blood_transfusion' => 'Recent blood transfusion',
                                        'tattoo_piercing' => 'Recent tattoo/piercing',
                                        'recent_donation' => 'Blood donation within last 3 months',
                                    ];
                                ?>
                                <div class="mt-3 p-3 rounded-4" style="background:#f8fafc;border:1px solid #e8edf4;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong class="text-dark">🩺 Medical History</strong>
                                        <a href="<?php echo e(route('donor.profile', $donor)); ?>" class="small fw-bold text-danger">View full</a>
                                    </div>
                                    <?php if(!empty($history)): ?>
                                        <div class="row g-2">
                                            <?php $__currentLoopData = $historyLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-12 col-xl-6 small">
                                                    <span class="text-secondary"><?php echo e($label); ?>:</span>
                                                    <strong class="<?php echo e(($history[$key] ?? null) === 'yes' ? 'text-danger' : 'text-success'); ?>">
                                                        <?php echo e(strtoupper($history[$key] ?? 'N/A')); ?>

                                                    </strong>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="small text-secondary">No medical history provided.</div>
                                    <?php endif; ?>
                                    <div class="small text-muted mt-2">Self-reported by donor; final donation eligibility is decided by blood-bank medical screening.</div>
                                </div>

                            </div>


                            <form method="POST" action="<?php echo e(route('blood.search.request', $donor)); ?>" class="mt-3">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="request_id" value="<?php echo e($bloodRequest->id); ?>">
                                <button type="submit" class="request-btn border-0">
                                    🎯 Send Request To This Donor
                                </button>
                            </form>

                        </div>

                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>

        <?php else: ?>

            <div class="empty-box">

                <div style="font-size:55px;">
                    🩸
                </div>

                <h3 class="fw-bold mt-3">
                    No Donor Found
                </h3>

                <p class="text-muted">
                    Try another blood group or city.
                </p>

                <a
                    href="<?php echo e(route('blood.search')); ?>"
                    class="btn btn-dark rounded-pill px-4"
                >
                    Clear Search
                </a>

            </div>

        <?php endif; ?>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\BloodNexus\resources\views/blood-search.blade.php ENDPATH**/ ?>