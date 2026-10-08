<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin Panel'); ?> | BloodNexus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/bloodnexus-premium.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--bn-red:#dc2638;--bn-red-dark:#a9142a;--bn-ink:#182033;--bn-muted:#64748b;--bn-line:#e5eaf2;--bn-bg:#f5f7fb;--bn-card:#fff;--bn-glow:rgba(220,38,56,.22)}
        *{box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{margin:0;min-height:100vh;background:radial-gradient(circle at 8% 8%,rgba(220,38,56,.11),transparent 24%),radial-gradient(circle at 92% 18%,rgba(99,102,241,.08),transparent 22%),linear-gradient(180deg,#f8faff 0%,#f3f6fb 100%);color:var(--bn-ink);font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif;overflow-x:hidden}body:before{content:"";position:fixed;inset:0;pointer-events:none;background-image:linear-gradient(rgba(24,32,51,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(24,32,51,.025) 1px,transparent 1px);background-size:42px 42px;mask-image:linear-gradient(to bottom,rgba(0,0,0,.7),transparent 80%);z-index:-1}
        a{text-decoration:none}
        .admin-navbar{position:sticky;top:0;z-index:1050;background:rgba(255,255,255,.88);backdrop-filter:blur(24px) saturate(160%);border-bottom:1px solid rgba(226,232,240,.88);box-shadow:0 12px 35px rgba(15,23,42,.07)}.admin-navbar:before{content:"";position:absolute;left:0;right:0;top:0;height:2px;background:linear-gradient(90deg,transparent,#ff6b7a,#dc2638,#ff6b7a,transparent);background-size:220% 100%;animation:bnNavLine 5s linear infinite}
        .admin-brand{font-weight:950;color:var(--bn-ink)!important;font-size:20px;letter-spacing:-.7px;display:flex;align-items:center;gap:10px;transition:.25s ease}.admin-brand:hover{transform:translateY(-1px)}
        .admin-brand small{color:var(--bn-red)!important;letter-spacing:1px}
        .admin-brand-icon{width:43px;height:43px;border-radius:15px;background:linear-gradient(135deg,#fff0f2,#ffe2e6);border:1px solid #ffe0e5;display:grid;place-items:center;box-shadow:0 10px 24px rgba(220,38,56,.13);animation:bnAdminPulse 2.8s infinite;position:relative}.admin-brand-icon:after{content:"";position:absolute;inset:-5px;border:1px solid rgba(220,38,56,.15);border-radius:18px;animation:bnOrbit 2.4s ease-in-out infinite}
        .admin-brand-icon svg{width:28px}
        .admin-link{color:#526176!important;font-size:12px;font-weight:850;padding:10px 11px!important;border-radius:12px;display:flex;align-items:center;gap:7px;position:relative;overflow:hidden;isolation:isolate;transition:.25s ease}
        .admin-link:hover{color:var(--bn-red)!important;background:#fff4f5;transform:translateY(-1px)}
        .admin-link.active{color:var(--bn-red)!important;background:linear-gradient(135deg,#fff0f2,#fff7f8)}
        .admin-link.active:after{content:"";position:absolute;left:14px;right:14px;bottom:4px;height:2px;background:linear-gradient(90deg,#ff7a88,var(--bn-red));border-radius:5px;box-shadow:0 0 12px rgba(220,38,56,.45)}.admin-link i{transition:transform .25s ease}.admin-link:hover i,.admin-link.active i{transform:scale(1.12) translateY(-1px)}
        .bn-click-wave{position:absolute;border-radius:50%;background:rgba(220,38,56,.20);transform:scale(0);animation:bnRipple .62s linear;pointer-events:none;z-index:-1}
        @keyframes bnRipple{to{transform:scale(4);opacity:0}}
        .admin-user{display:flex;align-items:center;gap:8px;background:#fff;border:1px solid var(--bn-line);border-radius:15px;padding:5px 10px 5px 5px;box-shadow:0 6px 18px rgba(15,23,42,.05)}
        .admin-avatar{width:34px;height:34px;border-radius:11px;background:linear-gradient(135deg,var(--bn-red),#8f1830);color:#fff;display:grid;place-items:center;font-weight:900}
        .admin-user-name{font-size:12px;font-weight:800;color:#334155}
        .admin-logout{border:0;border-radius:12px;padding:10px 13px;background:linear-gradient(135deg,var(--bn-red),var(--bn-red-dark));color:#fff;font-size:12px;font-weight:850;box-shadow:0 9px 20px rgba(220,38,56,.18);position:relative;overflow:hidden}
        .admin-main{min-height:calc(100vh - 66px);position:relative}.admin-main:after{content:"";position:fixed;width:340px;height:340px;border-radius:50%;right:-190px;bottom:-190px;background:rgba(220,38,56,.06);filter:blur(10px);pointer-events:none}
        .admin-content{padding:34px 30px 60px;max-width:1650px;margin:auto;animation:pageIn .55s cubic-bezier(.2,.8,.2,1) both}.admin-content>*{animation:sectionIn .65s cubic-bezier(.2,.8,.2,1) both}.admin-content>*:nth-child(2){animation-delay:.04s}.admin-content>*:nth-child(3){animation-delay:.08s}.admin-content>*:nth-child(4){animation-delay:.12s}
        .page-title{font-size:29px;font-weight:950;color:#182235;letter-spacing:-1px;margin:0}
        .admin-page-subtitle{color:#718096;font-size:12px;font-weight:600;margin-top:6px}
        .admin-pagebar{display:flex;align-items:flex-end;justify-content:space-between;gap:18px;margin-bottom:18px;position:relative}.admin-livebar{display:flex;align-items:center;gap:9px;margin:0 0 18px;padding:8px 12px;width:max-content;max-width:100%;border:1px solid rgba(220,38,56,.12);border-radius:999px;background:rgba(255,255,255,.72);box-shadow:0 8px 24px rgba(15,23,42,.04);font-size:10px;color:#64748b;letter-spacing:.2px}.admin-livebar strong{color:#273247}.live-dot{width:7px;height:7px;border-radius:50%;background:#16a34a;box-shadow:0 0 0 4px rgba(22,163,74,.12);animation:livePulse 1.8s infinite}.live-divider{width:1px;height:13px;background:#e2e8f0}.live-time{font-variant-numeric:tabular-nums;font-weight:800;color:#dc2638}@keyframes livePulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(.75);opacity:.55}}
        .admin-pagebar>div{min-width:0}
        .admin-card,.stat-card,.insight,.table-card{background:var(--bn-card);border:1px solid var(--bn-line);box-shadow:0 12px 35px rgba(15,23,42,.055);color:var(--bn-ink)}
        .admin-card{border-radius:22px;overflow:hidden;position:relative}.admin-card:before{content:"";position:absolute;left:0;top:0;width:100%;height:1px;background:linear-gradient(90deg,transparent,rgba(220,38,56,.28),transparent);opacity:0;transition:.3s}.admin-card:hover:before{opacity:1}
        .admin-card-header{padding:17px 20px;border-bottom:1px solid var(--bn-line);background:linear-gradient(180deg,#ffffff,#fbfcfe);color:var(--bn-ink)}
        .admin-card-body{padding:18px 20px}
        .btn-admin{display:inline-flex;align-items:center;justify-content:center;gap:7px;border-radius:12px;padding:10px 14px;border:1px solid transparent;font-size:12px;font-weight:850;line-height:1.2;transition:transform .22s ease,box-shadow .22s ease,background .22s ease,border-color .22s ease;color:inherit;position:relative;overflow:hidden;isolation:isolate}
        .btn-admin:hover{transform:translateY(-2px);box-shadow:0 10px 24px rgba(15,23,42,.10);color:inherit}
        .btn-admin.btn-red{color:#fff!important}
        .btn-admin.btn-dark{color:#fff!important}
        .btn-admin.btn-light-admin:hover{background:#fff7f8;border-color:#ffd6dc;color:var(--bn-red)!important}
        .stat-card{border-radius:20px;padding:18px;min-height:118px;transition:.28s ease;position:relative;overflow:hidden}.stat-card:after{content:"";position:absolute;width:95px;height:95px;border-radius:50%;right:-48px;top:-48px;background:rgba(220,38,56,.055);transition:.3s}.stat-card:hover:after{transform:scale(1.35)}
        .stat-icon{width:38px;height:38px;border-radius:12px;display:grid;place-items:center;margin-bottom:12px}
        .security-panel .admin-card-body{padding-top:6px;padding-bottom:6px}
        .security-panel .admin-card-body>.d-flex:last-child{border-bottom:0!important}
        .donation-highlight{border-radius:20px;padding:22px 24px;box-shadow:0 16px 38px rgba(185,28,45,.16)}
        .donation-highlight .h3{color:#fff!important;font-size:25px}
        .donation-highlight .small{color:rgba(255,255,255,.82)!important}
        .admin-card-body .empty-state{padding:10px 0}
        .admin-pagebar .btn-admin{white-space:nowrap}
        .admin-card,.stat-card{transition:box-shadow .25s ease,border-color .25s ease,transform .25s ease}
        .admin-card:hover{border-color:#e1e6ee;box-shadow:0 16px 40px rgba(15,23,42,.07)}
        .admin-table th,.table th{color:#718096!important;background:#f8fafc!important}
        .admin-table td,.table td{color:#334155!important;border-color:#eef2f6!important}
        .table-hover>tbody>tr:hover>*{background:#fff8f9!important}
        .stat-card:hover{transform:translateY(-5px);border-color:#ffd4da;box-shadow:0 22px 45px rgba(220,38,56,.09)}
        .stat-number{color:#172033}.stat-label{color:#64748b}.stat-desc{color:#94a3b8}
        .btn{border-radius:12px}
        .btn-red{background:linear-gradient(135deg,var(--bn-red),var(--bn-red-dark));color:#fff}
        .btn-dark{background:#172033;color:#fff}
        .btn-light-admin{background:#fff;border:1px solid var(--bn-line);color:#334155}
        .filter-control,.form-select{background:#fff!important;color:#334155!important;border-color:#dfe5ee!important}
        .donation-highlight{background:linear-gradient(120deg,#a9152b,#ef233c 45%,#c51f36);border:1px solid rgba(255,255,255,.28);position:relative;overflow:hidden}
        .badge-soft{background:#fff0f2;color:#b91c2d}.status-pending{background:#fff7e6;color:#b7791f}.status-matched{background:#eff6ff;color:#2563eb}.status-accepted,.status-completed{background:#ecfdf5;color:#059669}.status-rejected,.status-cancelled{background:#fff1f2;color:#dc2638}.empty-state{color:#94a3b8}
        .admin-alert{border:0;border-radius:16px;box-shadow:0 10px 25px rgba(15,23,42,.06)}
        @keyframes pageIn{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}@keyframes sectionIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}@keyframes bnNavLine{0%{background-position:0 0}100%{background-position:220% 0}}@keyframes bnOrbit{0%,100%{transform:scale(.96);opacity:.45}50%{transform:scale(1.08);opacity:.9}}
        @keyframes bnAdminPulse{0%,100%{transform:translateY(0);box-shadow:0 10px 24px rgba(220,38,56,.13)}50%{transform:translateY(-2px);box-shadow:0 15px 28px rgba(220,38,56,.2)}}
        @media(max-width:1200px){.admin-link{font-size:11px;padding:9px 8px!important}.admin-brand{font-size:18px}}
        @media(max-width:991px){.admin-pagebar{align-items:flex-start;flex-direction:column}.admin-pagebar .btn-admin{width:100%}.admin-content{padding:18px}.admin-user-name{display:none}.admin-navbar .navbar-nav{padding:12px 0}.admin-link{margin:2px 0}.page-title{font-size:24px}}
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
<nav class="navbar navbar-expand-lg admin-navbar">
    <div class="container-fluid px-3 px-lg-4">
        <a class="admin-brand" href="<?php echo e(route('admin.dashboard')); ?>">
            <span class="admin-brand-icon"><svg viewBox="0 0 48 48" fill="none"><path d="M24 4S9 22 9 31a15 15 0 0 0 30 0C39 22 24 4 24 4Z" fill="url(#adminLogoGrad)"/><path d="M24 16v16M16 24h16" stroke="#fff" stroke-width="4" stroke-linecap="round"/><defs><linearGradient id="adminLogoGrad" x1="8" y1="4" x2="40" y2="40"><stop stop-color="#ff5267"/><stop offset="1" stop-color="#b9152d"/></linearGradient></defs></svg></span>
            BloodNexus <small style="font-size:9px;font-weight:900">ADMIN</small>
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav"><i class="bi bi-list fs-3"></i></button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav ms-lg-4 me-auto align-items-lg-center">
                <?php ($routeName=request()->route()?->getName()); ?>
                <li class="nav-item"><a class="nav-link admin-link <?php echo e($routeName==='admin.dashboard'?'active':''); ?>" href="<?php echo e(route('admin.dashboard')); ?>"><i class="bi bi-grid-1x2-fill"></i>Dashboard</a></li>
                <li class="nav-item"><a class="nav-link admin-link <?php echo e(in_array($routeName,['admin.requests','admin.critical'])?'active':''); ?>" href="<?php echo e(route('admin.requests')); ?>"><i class="bi bi-droplet-fill"></i>Blood Intelligence</a></li>
                <li class="nav-item"><a class="nav-link admin-link <?php echo e($routeName==='admin.donor.intelligence'?'active':''); ?>" href="<?php echo e(route('admin.donor.intelligence')); ?>"><i class="bi bi-heart-pulse-fill"></i>Donor Intelligence</a></li>
                <li class="nav-item"><a class="nav-link admin-link <?php echo e($routeName==='admin.medical.history'?'active':''); ?>" href="<?php echo e(route('admin.medical.history')); ?>"><i class="bi bi-clipboard2-pulse-fill"></i>Medical History</a></li>
                <li class="nav-item"><a class="nav-link admin-link <?php echo e(str_starts_with($routeName ?? '', 'admin.security')?'active':''); ?>" href="<?php echo e(route('admin.security')); ?>"><i class="bi bi-shield-lock-fill"></i>Security Center</a></li>
                <li class="nav-item"><a class="nav-link admin-link <?php echo e($routeName==='admin.messaging'?'active':''); ?>" href="<?php echo e(route('admin.messaging')); ?>"><i class="bi bi-chat-dots-fill"></i>Messaging</a></li>
                <li class="nav-item"><a class="nav-link admin-link <?php echo e($routeName==='admin.demand.report'?'active':''); ?>" href="<?php echo e(route('admin.demand.report')); ?>"><i class="bi bi-bar-chart-line-fill"></i>Blood Demand</a></li>
                <li class="nav-item"><a class="nav-link admin-link <?php echo e(in_array($routeName,['admin.reports','admin.donations','admin.analytics'])?'active':''); ?>" href="<?php echo e(route('admin.reports')); ?>"><i class="bi bi-file-earmark-bar-graph-fill"></i>Reports</a></li>
                <li class="nav-item"><a class="nav-link admin-link <?php echo e($routeName==='admin.ai'?'active':''); ?>" href="<?php echo e(route('admin.ai')); ?>"><i class="bi bi-stars"></i>Admin AI</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2 pt-2 pt-lg-0">
                <div class="admin-user"><div class="admin-avatar"><?php echo e(strtoupper(substr(session('fixed_admin_name') ?? auth()->user()->name ?? 'A',0,1))); ?></div><span class="admin-user-name"><?php echo e(session('fixed_admin_name') ?? auth()->user()->name ?? 'Admin'); ?></span></div>
                <form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?><button class="admin-logout bn-ripple" type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button></form>
            </div>
        </div>
    </div>
</nav>
<main class="admin-main"><section class="admin-content"><div class="admin-livebar"><span class="live-dot"></span><strong>BloodNexus Command Center</strong><span class="live-divider"></span><span>Live system</span><span class="live-time" id="bnLiveTime"></span></div>
    <?php if(session('success')): ?><div class="alert alert-success admin-alert"><?php echo e(session('success')); ?></div><?php endif; ?>
    <?php if(session('error')): ?><div class="alert alert-danger admin-alert"><?php echo e(session('error')); ?></div><?php endif; ?>
    <?php echo $__env->yieldContent('content'); ?>
</section></main>
<script>document.addEventListener("DOMContentLoaded",()=>{const e=document.getElementById("bnLiveTime");if(e){const tick=()=>e.textContent=new Date().toLocaleTimeString([], {hour:"2-digit",minute:"2-digit",second:"2-digit"});tick();setInterval(tick,1000)}});</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('click',function(e){
 const el=e.target.closest('.admin-link,.bn-ripple,.admin-logout');
 if(!el)return;
 const r=el.getBoundingClientRect(), wave=document.createElement('span');
 wave.className='bn-click-wave'; wave.style.width=wave.style.height=Math.max(r.width,r.height)+'px';
 wave.style.left=(e.clientX-r.left-r.width/2)+'px'; wave.style.top=(e.clientY-r.top-r.height/2)+'px';
 el.appendChild(wave); setTimeout(()=>wave.remove(),650);
});
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
<script src="<?php echo e(asset('js/bloodnexus-ui.js')); ?>"></script>
</body></html><?php /**PATH C:\xampp\htdocs\BloodNexus\resources\views/layouts/admin.blade.php ENDPATH**/ ?>