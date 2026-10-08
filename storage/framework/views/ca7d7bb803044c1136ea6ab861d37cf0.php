<?php $__env->startSection('title','Security Center'); ?>
<?php $__env->startSection('content'); ?>
<style>
.security-shell{animation:secIn .45s ease both}.security-hero{position:relative;overflow:hidden;border:1px solid #263244;border-radius:24px;padding:28px;background:linear-gradient(135deg,#08111f,#111d2f 60%,#18263a);color:#fff;box-shadow:0 18px 45px rgba(15,23,42,.16);margin-bottom:22px}.security-hero:before{content:"";position:absolute;right:-90px;top:-150px;width:330px;height:330px;border-radius:50%;background:radial-gradient(circle,rgba(220,38,56,.26),transparent 67%)}.security-kicker{font-size:10px;letter-spacing:1.8px;text-transform:uppercase;font-weight:900;color:#fb7185}.security-title{font-size:28px;font-weight:900;letter-spacing:-.8px}.security-live{display:inline-flex;align-items:center;gap:7px;padding:8px 12px;border:1px solid rgba(255,255,255,.12);border-radius:999px;background:rgba(255,255,255,.06);font-size:9px;font-weight:900;letter-spacing:.8px}.live-dot{width:7px;height:7px;border-radius:50%;background:#22c55e;animation:pulse .1s infinite}.security-stat,.stat-card{transition:.22s}.security-stat:hover,.stat-card:hover{transform:translateY(-3px);box-shadow:0 14px 32px rgba(15,23,42,.09)}.security-card,.admin-card{box-shadow:0 8px 26px rgba(15,23,42,.045);transition:.22s}.security-card:hover,.admin-card:hover{box-shadow:0 14px 34px rgba(15,23,42,.075)}.admin-table tbody tr,.security-table tbody tr{transition:.18s}.admin-table tbody tr:hover,.security-table tbody tr:hover{background:#fafbfc}.locked-row{background:linear-gradient(90deg,#fff7f7,transparent)}.risk-bar{display:inline-block;width:70px;height:6px;border-radius:99px;background:#edf1f5;overflow:hidden;vertical-align:middle}.risk-fill{height:100%;border-radius:99px;background:linear-gradient(90deg,#22c55e,#f59e0b,#dc2638)}@keyframes secIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}@keyframes pulse{0%,100%{box-shadow:0 0 0 0 rgba(34,197,94,.2)}50%{box-shadow:0 0 0 6px rgba(34,197,94,0)}}
</style>
<div class="admin-pagebar"><div><h1 class="page-title">Security Command Center</h1><div class="admin-page-subtitle">Account protection, suspicious activity and audit telemetry.</div></div><div class="d-flex gap-2"><a class="btn-admin btn-light-admin" href="<?php echo e(route('admin.security.appeals')); ?>"><i class="bi bi-inbox me-1"></i>Appeals <span class="badge text-bg-danger"><?php echo e($stats['appeals']); ?></span></a><span class="badge rounded-pill text-bg-dark px-3 py-2"><i class="bi bi-shield-check me-1"></i>LIVE</span></div></div>
<div class="row g-3 mb-4">
<?php $__currentLoopData = [['🚨 Brute Force Alerts',$stats['bruteforce'],'#ffe4e6','#be123c','bi-shield-exclamation'],['Blocked Accounts',$stats['blocked'],'#fee2e2','#b91c1c','bi-person-lock'],['Pending Appeals',$stats['appeals'],'#fef3c7','#a16207','bi-person-check-fill'],['Events Today',$stats['today'],'#dbeafe','#1d4ed8','bi-activity']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $x): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="col-6 col-xl-3"><div class="stat-card"><div class="stat-icon" style="background:<?php echo e($x[2]); ?>;color:<?php echo e($x[3]); ?>"><i class="bi <?php echo e($x[4]); ?>"></i></div><div class="stat-label"><?php echo e($x[0]); ?></div><div class="stat-number"><?php echo e($x[1]); ?></div><div class="stat-desc">Live security status</div></div></div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<div class="admin-card mb-4"><div class="admin-card-body"><form class="row g-2 align-items-end" method="GET"><div class="col-md-3"><label class="form-label small fw-bold">Severity</label><select name="severity" class="form-select filter-control"><option value="">All</option><?php $__currentLoopData = ['critical','high','medium','info']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($v); ?>" <?php if(request('severity')===$v): echo 'selected'; endif; ?>><?php echo e(ucfirst($v)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div><div class="col-md-3"><label class="form-label small fw-bold">Event</label><input name="event_type" value="<?php echo e(request('event_type')); ?>" class="form-control filter-control" placeholder="failed_login"></div><div class="col-md-4"><label class="form-label small fw-bold">Search</label><input name="search" value="<?php echo e(request('search')); ?>" class="form-control filter-control" placeholder="Incident, IP, user..."></div><div class="col-md-2"><button class="btn-admin btn-dark w-100 py-2"><i class="bi bi-search me-1"></i>Filter</button></div></form></div></div>
<div class="admin-card mb-4 border border-danger-subtle">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <div><strong class="text-danger">🚨 Brute Force & Security Alerts</strong><div class="admin-page-subtitle">High-risk repeated failed logins detected from the same account/IP within a rolling 10-minute window.</div></div>
        <span class="badge-soft status-rejected"><?php echo e($stats['bruteforce']); ?> HIGH RISK</span>
    </div>
    <div class="table-wrap"><table class="table admin-table mb-0"><thead><tr><th>Risk</th><th>Account</th><th>Failed Attempts</th><th>IP Address</th><th>Time Window</th><th>Action</th></tr></thead><tbody>
    <?php $__empty_1 = true; $__currentLoopData = $bruteForceAlerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr class="locked-row">
            <td><span class="badge-soft status-rejected">🚨 HIGH RISK</span></td>
            <td><?php if($alert->user): ?><strong><?php echo e($alert->user->email); ?></strong><div class="text-muted" style="font-size:9px"><?php echo e($alert->user->name); ?> · <?php echo e(strtoupper($alert->user->role)); ?></div><?php else: ?><strong>Unknown / guest email</strong><div class="text-muted" style="font-size:9px">No matching account</div><?php endif; ?></td>
            <td><strong class="text-danger"><?php echo e($alert->attempts); ?></strong></td>
            <td><code><?php echo e($alert->ip_address ?? 'Unknown'); ?></code></td>
            <td><?php echo e($alert->first_at?->format('h:i:s A')); ?> → <?php echo e($alert->last_at?->format('h:i:s A')); ?></td>
            <td>
                <?php if($alert->user): ?>
                    <div class="d-flex flex-wrap gap-1">
                        <a href="#audit-log" class="btn-admin btn-light-admin">View Activity</a>
                        <?php if(!$alert->user->isSecurityBlocked()): ?>
                        <form method="POST" action="<?php echo e(route('admin.security.user.block', $alert->user)); ?>" onsubmit="return confirm('Block this account due to high-risk brute-force activity?')"><?php echo csrf_field(); ?><input type="hidden" name="reason" value="High-risk repeated failed login activity detected from the same account/IP."><button class="btn-admin btn-red">Block Account</button></form>
                        <?php else: ?> <span class="badge-soft status-rejected">BLOCKED</span> <?php endif; ?>
                    </div>
                <?php else: ?> <span class="text-muted" style="font-size:10px">No account available to block</span><?php endif; ?>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="6" class="empty-state">No high-risk brute-force pattern detected in the latest 10 minutes.</td></tr>
    <?php endif; ?>
    </tbody></table></div>
</div>

<div class="admin-card mb-4">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <div><strong>🚫 Permanently Blocked Accounts</strong><div class="admin-page-subtitle">Complete block list with the reason, time and appeal status.</div></div>
        <span class="badge-soft status-rejected"><?php echo e($blockedUsers->count()); ?> BLOCKED</span>
    </div>
    <div class="table-wrap"><table class="table admin-table mb-0"><thead><tr><th>Account</th><th>Role</th><th>Block Reason</th><th>Blocked At</th><th>Appeal</th><th>Action</th></tr></thead><tbody>
    <?php $__empty_1 = true; $__currentLoopData = $blockedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php ($appeal = \App\Models\AccountAppeal::whereRaw('LOWER(email) = ?', [strtolower($u->email)])->latest()->first()); ?>
        <tr class="locked-row">
            <td><strong><?php echo e($u->name); ?></strong><div class="text-muted" style="font-size:9px"><?php echo e($u->email); ?></div></td>
            <td><?php echo e(strtoupper($u->role)); ?></td>
            <td style="max-width:280px"><?php echo e($u->security_block_reason ?: 'Security policy violation / suspicious login activity.'); ?></td>
            <td><?php echo e($u->security_blocked_at?->format('d M Y, h:i A') ?? '—'); ?></td>
            <td><?php if($appeal): ?><span class="badge-soft <?php echo e($appeal->status==='approved'?'status-completed':($appeal->status==='rejected'?'status-rejected':'status-pending')); ?>"><?php echo e(strtoupper($appeal->status)); ?></span><div class="text-muted" style="font-size:9px">One-time appeal used</div><?php else: ?><span class="text-muted" style="font-size:10px">No appeal submitted</span><?php endif; ?></td>
            <td><form method="POST" action="<?php echo e(route('admin.security.user.unlock', $u)); ?>" onsubmit="return confirm('Unblock and restore this account?')"><?php echo csrf_field(); ?><button class="btn-admin btn-light-admin">Unblock</button></form></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="6" class="empty-state">No permanently blocked accounts.</td></tr><?php endif; ?>
    </tbody></table></div>
</div>

<div class="admin-card mb-4"><div class="admin-card-header d-flex justify-content-between"><div><strong>🔒 Temporarily Locked Accounts</strong><div class="admin-page-subtitle">Accounts blocked or restricted after repeated incorrect passwords or suspicious login activity.</div></div><span class="badge-soft status-rejected"><?php echo e($stats['locked']); ?> locked</span></div><div class="table-wrap"><table class="table admin-table mb-0"><thead><tr><th>User</th><th>Role</th><th>Failed Attempts</th><th>Locked Until</th><th>Reason</th><th>Action</th></tr></thead><tbody><?php ($lockedUsers=\App\Models\User::whereNotNull('locked_until')->where('locked_until','>',now())->latest('locked_until')->get()); ?><?php $__empty_1 = true; $__currentLoopData = $lockedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><strong><?php echo e($u->name); ?></strong><div class="text-muted" style="font-size:9px"><?php echo e($u->email); ?></div></td><td><?php echo e(strtoupper($u->role)); ?></td><td><?php echo e($u->failed_login_attempts); ?></td><td><?php echo e($u->locked_until?->format('d M Y, h:i A')); ?></td><td>3 incorrect password attempts</td><td><form method="POST" action="<?php echo e(route('admin.security.user.unlock', $u)); ?>"><?php echo csrf_field(); ?><button class="btn-admin btn-red">Unlock</button></form></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="6" class="empty-state">No accounts are currently locked.</td></tr><?php endif; ?></tbody></table></div></div>

<div class="admin-card mb-4">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <div><strong>📈 Frequent Login Monitor</strong><div class="admin-page-subtitle">Users with 5+ successful logins today. Send a warning before security escalation.</div></div>
        <span class="badge-soft status-pending"><?php echo e($frequentUsers->count()); ?> monitored</span>
    </div>
    <div class="table-wrap">
        <table class="table admin-table mb-0">
            <thead><tr><th>User</th><th>Role</th><th>Today's Logins</th><th>Last Warning</th><th>Action</th></tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $frequentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="<?php echo e($u->successful_logins_today >= 8 ? 'locked-row' : ''); ?>">
                    <td><strong><?php echo e($u->name); ?></strong><div class="text-muted" style="font-size:9px"><?php echo e($u->email); ?></div></td>
                    <td><?php echo e(strtoupper($u->role)); ?></td>
                    <td><span class="badge-soft <?php echo e($u->successful_logins_today >= 8 ? 'status-rejected' : 'status-pending'); ?>"><?php echo e($u->successful_logins_today); ?> / 8</span></td>
                    <td><?php echo e($u->last_login_warning_at?->format('d M Y, h:i A') ?? 'Not sent'); ?></td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <form method="POST" action="<?php echo e(route('admin.security.user.warning', $u)); ?>" class="d-flex gap-1">
                                <?php echo csrf_field(); ?>
                                <input type="text" name="message" value="Your account has shown unusually frequent login activity today. Please avoid repeated sign-ins and keep your account secure. Further excessive activity may result in a permanent security block." class="form-control form-control-sm" style="min-width:300px" required>
                                <button class="btn-admin btn-light-admin">⚠️ Warn</button>
                            </form>
                            <form method="POST" action="<?php echo e(route('admin.security.user.block', $u)); ?>" onsubmit="return confirm('Permanently block this account?')">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="reason" value="Excessive login activity / repeated sign-in attempts detected by administrator.">
                                <button class="btn-admin btn-red">🔴 Block</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="empty-state">No unusually frequent login activity today.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="audit-log" class="admin-card"><div class="admin-card-header d-flex justify-content-between"><div><strong>🕵️ Audit Log</strong><div class="admin-page-subtitle">No private chat content is exposed here—only security and activity metadata.</div></div><span class="badge-soft status-accepted"><?php echo e($stats['total']); ?> events</span></div><div class="table-wrap"><table class="table admin-table mb-0"><thead><tr><th>Incident</th><th>Severity</th><th>Event</th><th>User</th><th>IP</th><th>Risk</th><th>Time</th><th></th></tr></thead><tbody>
<?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><strong class="text-danger"><?php echo e($log->incident_id); ?></strong><div class="text-muted" style="font-size:9px;max-width:260px"><?php echo e($log->message); ?></div></td><td><span class="badge-soft <?php echo e($log->severity==='critical'?'status-rejected':($log->severity==='high'?'status-pending':($log->severity==='medium'?'status-matched':'status-accepted'))); ?>"><?php echo e(strtoupper($log->severity)); ?></span></td><td><code><?php echo e($log->event_type); ?></code></td><td><?php echo e($log->user?->name??'Guest/System'); ?><div class="text-muted" style="font-size:9px">#<?php echo e($log->user_id??'-'); ?></div></td><td><?php echo e($log->ip_address??'-'); ?></td><td><strong><?php echo e($log->risk_score); ?>/100</strong></td><td><?php echo e($log->created_at?->format('d M Y, h:i A')); ?></td><td><?php if($log->resolved_at): ?><span class="badge-soft status-completed">RESOLVED</span><?php else: ?><form method="POST" action="<?php echo e(route('admin.security.resolve',$log)); ?>"><?php echo csrf_field(); ?><button class="btn-admin btn-light-admin">Resolve</button></form><?php endif; ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="8" class="empty-state">No security events found.</td></tr><?php endif; ?>
</tbody></table></div><?php if($logs->hasPages()): ?><div class="p-3"><?php echo e($logs->links()); ?></div><?php endif; ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\BloodNexus\resources\views/admin/security/index.blade.php ENDPATH**/ ?>