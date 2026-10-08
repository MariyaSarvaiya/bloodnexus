<?php $__env->startSection('title','Medical History'); ?>
<?php $__env->startSection('content'); ?>
<div class="admin-pagebar">
    <div>
        <h1 class="page-title">Donor Medical History</h1>
        <div class="admin-page-subtitle">Live medical-history answers submitted by registered donors.</div>
    </div>
</div>

<div class="admin-card mb-4">
    <div class="admin-card-body">
        <form class="row g-2" method="GET">
            <div class="col-md-9">
                <input class="form-control filter-control" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search donor name, email, phone, city or area">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn-admin btn-dark flex-fill">Search</button>
                <a class="btn-admin btn-light-admin flex-fill text-center" href="<?php echo e(route('admin.medical.history')); ?>">Reset</a>
            </div>
        </form>
    </div>
</div>

<?php
$medicalQuestions = [
    'chronic_condition' => 'Long-term / chronic medical condition',
    'regular_medicines' => 'Regular medicines',
    'allergies' => 'Known allergy',
    'recent_surgery' => 'Recent surgery / medical procedure',
    'fever_infection' => 'Current fever / infection',
    'blood_transfusion' => 'Recent blood transfusion',
    'tattoo_piercing' => 'Recent tattoo / piercing',
    'recent_donation' => 'Blood donation within last 3 months',
];
?>

<div class="row g-4">
<?php $__empty_1 = true; $__currentLoopData = $donors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
        $history = is_string($donor->medical_history) ? json_decode($donor->medical_history, true) : $donor->medical_history;
        $history = is_array($history) ? $history : [];
    ?>
    <div class="col-12">
        <div class="admin-card" style="overflow:hidden">
            <div class="admin-card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h3 class="h5 fw-bold mb-0"><?php echo e($donor->name); ?></h3>
                            <span class="badge bg-danger-subtle text-danger"><?php echo e($donor->blood_group); ?></span>
                            <span class="badge-soft <?php echo e($donor->isAvailable() ? 'status-completed' : 'status-cancelled'); ?>"><?php echo e($donor->isAvailable() ? 'AVAILABLE' : 'UNAVAILABLE'); ?></span>
                        </div>
                        <div class="small text-secondary mt-1">
                            <?php echo e($donor->email); ?> · <?php echo e($donor->phone); ?> · <?php echo e($donor->city); ?><?php echo e($donor->area ? ' · '.$donor->area : ''); ?>

                        </div>
                    </div>
                    <div class="text-end">
                        <div class="small text-secondary">Medical History</div>
                        <strong><?php echo e(count($history)); ?>/<?php echo e(count($medicalQuestions)); ?> answers saved</strong>
                    </div>
                </div>

                <?php if($history): ?>
                    <div class="row g-2">
                        <?php $__currentLoopData = $medicalQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 col-xl-3">
                                <div class="p-3 rounded-4 h-100" style="background:#f8fafc;border:1px solid #e8edf4">
                                    <div class="small text-secondary mb-2"><?php echo e($label); ?></div>
                                    <?php if(($history[$key] ?? null) === 'yes'): ?>
                                        <span class="badge-soft status-cancelled"><i class="bi bi-check-circle-fill me-1"></i>YES</span>
                                    <?php elseif(($history[$key] ?? null) === 'no'): ?>
                                        <span class="badge-soft status-completed"><i class="bi bi-x-circle-fill me-1"></i>NO</span>
                                    <?php else: ?>
                                        <span class="badge-soft"><i class="bi bi-dash-circle me-1"></i>NOT ANSWERED</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="p-4 rounded-4" style="background:#f8fafc;border:1px solid #e8edf4;color:#64748b">
                        <i class="bi bi-info-circle me-1"></i>No medical history has been submitted by this donor yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12"><div class="admin-card"><div class="admin-card-body empty-state text-center py-5">No donor medical-history records found.</div></div></div>
<?php endif; ?>
</div>

<div class="mt-4"><?php echo e($donors->onEachSide(1)->links('pagination::bootstrap-5')); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\BloodNexus\resources\views/admin/medical-history/index.blade.php ENDPATH**/ ?>