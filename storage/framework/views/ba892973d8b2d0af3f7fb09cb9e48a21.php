<?php $__env->startSection('title','Blood Demand Intelligence'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .demand-page{--red:#dc2638;--ink:#172033;--muted:#718096}
    .demand-command{position:relative;overflow:hidden;min-height:220px;border-radius:26px;padding:28px 32px;background:linear-gradient(125deg,#a9142a 0%,#dc2638 52%,#f14a5c 100%);color:#fff;box-shadow:0 24px 55px rgba(185,28,45,.20);display:flex;align-items:center;justify-content:space-between;gap:25px}.demand-command:before{content:"";position:absolute;width:420px;height:420px;border:1px solid rgba(255,255,255,.16);border-radius:50%;right:-110px;top:-160px;box-shadow:0 0 0 30px rgba(255,255,255,.035),0 0 0 70px rgba(255,255,255,.025)}.command-copy{position:relative;z-index:2;max-width:720px}.command-kicker{display:inline-flex;gap:7px;align-items:center;padding:7px 10px;border-radius:999px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);font-size:9px;font-weight:900;letter-spacing:1px}.command-copy h2{font-size:27px;font-weight:950;letter-spacing:-1px;margin:13px 0 7px}.command-copy p{font-size:12px;line-height:1.6;color:rgba(255,255,255,.80);max-width:630px;margin:0}.command-mini{display:flex;flex-wrap:wrap;gap:8px;margin-top:17px}.command-mini span{padding:8px 10px;border-radius:11px;background:rgba(255,255,255,.11);border:1px solid rgba(255,255,255,.14);font-size:10px;font-weight:800}.command-mini i{margin-right:5px}.pulse-visual{width:210px;height:180px;position:relative;display:grid;place-items:center;flex:none}.pulse-ring{position:absolute;border:1px solid rgba(255,255,255,.30);border-radius:50%;animation:pulseRing 3s ease-out infinite}.ring-one{width:110px;height:110px}.ring-two{width:165px;height:165px;animation-delay:1s}.pulse-core{width:74px;height:74px;border-radius:22px;background:rgba(255,255,255,.15);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.24);display:grid;place-items:center;align-content:center;gap:2px;box-shadow:0 15px 35px rgba(0,0,0,.12);z-index:2}.pulse-core i{font-size:25px}.pulse-core small{font-size:7px;font-weight:900;letter-spacing:1px}@keyframes pulseRing{0%{transform:scale(.82);opacity:.7}70%,100%{transform:scale(1.15);opacity:0}}

    .demand-top{background:linear-gradient(135deg,#ffffff 0%,#fff8f9 54%,#fff1f3 100%);border:1px solid #f1dfe3;border-radius:26px;padding:28px;box-shadow:0 18px 50px rgba(15,23,42,.06);position:relative;overflow:hidden}
    .demand-top:after{content:"";position:absolute;width:250px;height:250px;right:-80px;top:-110px;border-radius:50%;border:28px solid rgba(220,38,56,.055)}
    .intel-eyebrow{display:inline-flex;align-items:center;gap:7px;color:#b91c2d;background:#fff0f2;border:1px solid #ffdce1;padding:7px 11px;border-radius:999px;font-size:10px;font-weight:900;letter-spacing:1px}
    .demand-heading{font-size:30px;font-weight:950;letter-spacing:-1.1px;color:#172033;margin:14px 0 5px}
    .demand-sub{color:#718096;font-size:13px;max-width:650px}
    .period-card{background:#fff;border:1px solid #eef0f4;border-radius:18px;padding:7px;box-shadow:0 10px 25px rgba(15,23,42,.05)}
    .period-card .form-select{border:0!important;background:#f8fafc!important;font-size:12px;font-weight:750}
    .period-card .btn{font-size:12px;font-weight:850}
    .demand-download{border:1px solid #f0d3d8!important;background:#fff!important;color:#b91c2d!important;font-size:12px;font-weight:850}
    .intel-summary{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}
    .summary-pill{background:#fff;border:1px solid #edf0f4;border-radius:14px;padding:10px 13px;min-width:145px;box-shadow:0 7px 18px rgba(15,23,42,.04)}
    .summary-pill small{display:block;color:#94a3b8;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.7px}
    .summary-pill strong{display:block;color:#172033;font-size:18px;margin-top:3px}
    .summary-pill.critical{background:linear-gradient(135deg,#fff3f4,#fff);border-color:#ffd9df}
    .demand-stat{height:100%;background:#fff;border:1px solid #e8edf3;border-radius:20px;padding:18px;box-shadow:0 10px 28px rgba(15,23,42,.045);transition:.28s}
    .demand-stat:hover{transform:translateY(-4px);box-shadow:0 18px 36px rgba(15,23,42,.08);border-color:#ffd3da}
    .stat-icon{width:38px;height:38px;border-radius:13px;display:grid;place-items:center;background:#fff1f3;color:#d62539;font-size:17px}
    .stat-label{font-size:10px;text-transform:uppercase;letter-spacing:.7px;color:#94a3b8;font-weight:900;margin-top:12px}
    .stat-value{font-size:25px;font-weight:950;color:#172033;margin-top:3px;word-break:break-word}
    .intel-section{background:#fff;border:1px solid #e7ebf1;border-radius:22px;overflow:hidden;box-shadow:0 12px 32px rgba(15,23,42,.045);height:100%}
    .intel-section-head{padding:17px 19px;border-bottom:1px solid #edf0f4;display:flex;align-items:center;justify-content:space-between}
    .intel-section-title{font-size:15px;font-weight:900;color:#1d2939;margin:0}
    .intel-section-title i{color:#d62539;margin-right:7px}
    .intel-section-tag{font-size:10px;font-weight:850;color:#a12a38;background:#fff1f3;padding:6px 9px;border-radius:999px}
    .intel-table{margin:0}
    .intel-table th{font-size:10px;text-transform:uppercase;letter-spacing:.6px;color:#8a97a8!important;padding:12px 18px!important}
    .intel-table td{font-size:12px;padding:13px 18px!important;vertical-align:middle}
    .intel-table tr:last-child td{border-bottom:0}
    .blood-badge{display:inline-flex;align-items:center;justify-content:center;min-width:46px;padding:7px 10px;border-radius:10px;background:#fff0f2;color:#b91c2d;font-weight:950}
    .metric-number{font-weight:900;color:#253247}
    .reason-row{display:flex;align-items:center;gap:10px}
    .reason-dot{width:9px;height:9px;border-radius:50%;background:linear-gradient(135deg,#ff6b78,#c81e32);box-shadow:0 0 0 5px #fff4f5}
    .empty-intel{padding:34px 20px;text-align:center;color:#94a3b8;font-size:12px}
    .empty-intel i{display:block;font-size:26px;color:#e2a5ad;margin-bottom:8px}
    @media(max-width:767px){.demand-heading{font-size:25px}.demand-top{padding:21px}.intel-summary{display:grid;grid-template-columns:1fr 1fr}.summary-pill{min-width:0}.demand-command{padding:23px;min-height:0}.pulse-visual{display:none}}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="demand-page">
    <div class="demand-top mb-4">
        <div class="position-relative" style="z-index:1">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <div class="intel-eyebrow"><i class="bi bi-activity"></i> LIVE BLOOD INTELLIGENCE</div>
                    <h1 class="demand-heading">Blood Demand Intelligence</h1>
                    <div class="demand-sub">A dedicated analytics view for understanding where blood demand is rising — by blood group, city, area, hospital and medical need.</div>
                </div>
                <div class="d-flex gap-2 flex-wrap"><a class="btn btn-light border" target="_blank" href="<?php echo e(route('admin.demand.report.pdf.view',request()->query())); ?>"><i class="bi bi-eye me-1"></i> View PDF</a><a class="btn demand-download" href="<?php echo e(route('admin.demand.report.pdf',request()->query())); ?>"><i class="bi bi-file-earmark-pdf me-1"></i> Generate Demand PDF</a></div>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mt-4">
                <div class="intel-summary">
                    <div class="summary-pill"><small>Selected period</small><strong><?php echo e($days); ?> Day<?php echo e($days>1?'s':''); ?></strong></div>
                    <div class="summary-pill"><small>From → To</small><strong style="font-size:13px"><?php echo e($from->format('d M Y')); ?> → <?php echo e($to->format('d M Y')); ?></strong></div>
                    <div class="summary-pill critical"><small>Critical signals</small><strong><?php echo e($emergencyRequests); ?></strong></div>
                </div>
                <form class="period-card d-flex gap-2 flex-wrap" method="GET">
                    <select name="days" class="form-select">
                        <option value="1" <?php if($days===1): echo 'selected'; endif; ?>>Today</option>
                        <option value="7" <?php if($days===7): echo 'selected'; endif; ?>>Last 7 Days</option>
                        <option value="30" <?php if($days===30): echo 'selected'; endif; ?>>Last 30 Days</option>
                        <option value="90" <?php if($days===90): echo 'selected'; endif; ?>>Last 90 Days</option>
                        <option value="180" <?php if($days===180): echo 'selected'; endif; ?>>Last 180 Days</option>
                        <option value="365" <?php if($days===365): echo 'selected'; endif; ?>>Last 365 Days</option>
                    </select>
                    <select name="blood_group" class="form-select"><option value="">All Blood Groups</option><?php $__currentLoopData = ['O+','O-','A+','A-','B+','B-','AB+','AB-']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($g); ?>" <?php if(($bloodGroup ?? request('blood_group')) === $g): echo 'selected'; endif; ?>><?php echo e($g); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
                    <button class="btn btn-danger px-3">Search</button>
                </form>
            </div>
        </div>
    </div>

    <?php
        $stats = [
            ['Total Requests',$totalRequests,'bi-clipboard2-pulse'],
            ['Total Units',$totalUnits,'bi-droplet-half'],
            ['Critical / Emergency',$emergencyRequests,'bi-exclamation-triangle'],
            ['Top Blood',$topBlood?->blood_group ?? '—','bi-eyedropper'],
            ['Top City',$topCity?->city ?? '—','bi-buildings'],
            ['Top Area',$topArea?->area ?? '—','bi-geo-alt'],
            ['Top Hospital',$topHospital?->hospital ?? '—','bi-hospital'],
        ];
    ?>
    <?php if($bloodGroup): ?>
    <div class="demand-command mb-4" style="min-height:0">
        <div class="command-copy"><span class="command-kicker"><i class="bi bi-search"></i> BLOOD GROUP SEARCH</span><h2><?php echo e($bloodGroup); ?> demand vs donor availability</h2><p>This live view counts distinct Blood Need accounts with <?php echo e($bloodGroup); ?> requests and registered donors with the same blood group.</p></div>
        <div class="row g-2" style="min-width:320px"><div class="col-4"><div class="summary-pill"><small>Needers</small><strong><?php echo e($needersForGroup); ?></strong></div></div><div class="col-4"><div class="summary-pill"><small>Donors</small><strong><?php echo e($donorsForGroup); ?></strong></div></div><div class="col-4"><div class="summary-pill"><small>Available</small><strong><?php echo e($availableDonorsForGroup); ?></strong></div></div></div>
    </div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $x): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-6 col-md-4 col-xl">
            <div class="demand-stat">
                <div class="stat-icon"><i class="bi <?php echo e($x[2]); ?>"></i></div>
                <div class="stat-label"><?php echo e($x[0]); ?></div>
                <div class="stat-value"><?php echo e($x[1]); ?></div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="demand-command mb-4">
        <div class="command-copy">
            <span class="command-kicker"><i class="bi bi-broadcast-pin"></i> LIVE DEMAND PULSE</span>
            <h2>Where blood demand is moving right now.</h2>
            <p>Compare the strongest blood-group signals with city and hospital demand. This view turns raw requests into an admin-friendly operational picture.</p>
            <div class="command-mini"><span><i class="bi bi-droplet-fill"></i> <?php echo e($totalRequests); ?> requests</span><span><i class="bi bi-box-seam"></i> <?php echo e($totalUnits); ?> units</span><span><i class="bi bi-exclamation-triangle"></i> <?php echo e($emergencyRequests); ?> critical</span></div>
        </div>
        <div class="pulse-visual">
            <div class="pulse-ring ring-one"></div><div class="pulse-ring ring-two"></div><div class="pulse-core"><i class="bi bi-heart-pulse-fill"></i><small>DEMAND</small></div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="intel-section">
                <div class="intel-section-head"><h5 class="intel-section-title"><i class="bi bi-droplet-fill"></i>Blood Group Demand</h5><span class="intel-section-tag">GROUP ANALYSIS</span></div>
                <?php if($byBloodGroup->count()): ?>
                <div class="table-responsive"><table class="table intel-table table-hover"><thead><tr><th>Blood</th><th>Requests</th><th>Units</th></tr></thead><tbody>
                    <?php $__currentLoopData = $byBloodGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><td><span class="blood-badge"><?php echo e($r->blood_group); ?></span></td><td class="metric-number"><?php echo e($r->requests); ?></td><td class="metric-number"><?php echo e($r->units); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody></table></div>
                <?php else: ?> <div class="empty-intel"><i class="bi bi-bar-chart"></i>No blood-group demand data for this period.</div> <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="intel-section">
                <div class="intel-section-head"><h5 class="intel-section-title"><i class="bi bi-buildings"></i>City Demand</h5><span class="intel-section-tag">LOCATION</span></div>
                <?php if($byCity->count()): ?>
                <div class="table-responsive"><table class="table intel-table table-hover"><thead><tr><th>City</th><th>Requests</th><th>Units</th></tr></thead><tbody>
                    <?php $__currentLoopData = $byCity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><td><strong><?php echo e($r->city ?: 'Not specified'); ?></strong></td><td class="metric-number"><?php echo e($r->requests); ?></td><td class="metric-number"><?php echo e($r->units); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody></table></div>
                <?php else: ?> <div class="empty-intel"><i class="bi bi-geo"></i>No city demand data for this period.</div> <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="intel-section">
                <div class="intel-section-head"><h5 class="intel-section-title"><i class="bi bi-geo-alt-fill"></i>Area-wise Demand</h5><span class="intel-section-tag">LOCAL HOTSPOTS</span></div>
                <?php if($byArea->count()): ?>
                <div class="table-responsive"><table class="table intel-table table-hover"><thead><tr><th>City</th><th>Area</th><th>Req.</th><th>Units</th></tr></thead><tbody>
                    <?php $__currentLoopData = $byArea; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><td><?php echo e($r->city ?: '—'); ?></td><td><strong><?php echo e($r->area ?: 'Not specified'); ?></strong></td><td class="metric-number"><?php echo e($r->requests); ?></td><td class="metric-number"><?php echo e($r->units); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody></table></div>
                <?php else: ?> <div class="empty-intel"><i class="bi bi-pin-map"></i>No area-wise demand data for this period.</div> <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="intel-section">
                <div class="intel-section-head"><h5 class="intel-section-title"><i class="bi bi-hospital-fill"></i>Hospital-wise Demand</h5><span class="intel-section-tag">HOSPITAL</span></div>
                <?php if($byHospital->count()): ?>
                <div class="table-responsive"><table class="table intel-table table-hover"><thead><tr><th>Hospital</th><th>City</th><th>Req.</th><th>Units</th></tr></thead><tbody>
                    <?php $__currentLoopData = $byHospital; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><td><strong><?php echo e($r->hospital ?: 'Not specified'); ?></strong></td><td><?php echo e($r->city ?: '—'); ?></td><td class="metric-number"><?php echo e($r->requests); ?></td><td class="metric-number"><?php echo e($r->units); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody></table></div>
                <?php else: ?> <div class="empty-intel"><i class="bi bi-hospital"></i>No hospital demand data for this period.</div> <?php endif; ?>
            </div>
        </div>

        <div class="col-12">
            <div class="intel-section">
                <div class="intel-section-head"><h5 class="intel-section-title"><i class="bi bi-heart-pulse-fill"></i>Why is Blood Needed?</h5><span class="intel-section-tag">MEDICAL REASONS</span></div>
                <?php if($byReason->count()): ?>
                <div class="table-responsive"><table class="table intel-table table-hover"><thead><tr><th>Medical Need</th><th>Requests</th><th>Units</th></tr></thead><tbody>
                    <?php $__currentLoopData = $byReason; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><td><div class="reason-row"><span class="reason-dot"></span><strong><?php echo e(ucwords(str_replace('_',' ', $r->reason ?: 'Not specified'))); ?></strong></div></td><td class="metric-number"><?php echo e($r->requests); ?></td><td class="metric-number"><?php echo e($r->units); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody></table></div>
                <?php else: ?> <div class="empty-intel"><i class="bi bi-heart"></i>No medical-need data for this period.</div> <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\BloodNexus\resources\views/admin/analytics/demand-report.blade.php ENDPATH**/ ?>