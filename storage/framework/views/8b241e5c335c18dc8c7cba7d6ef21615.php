<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'BloodNexus'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <?php echo $__env->yieldPushContent('styles'); ?>
    <style>
        :root{--bn-red:#dc2638;--bn-dark:#111827;--bn-soft:#fff5f6;--bn-purple:#7c3aed}
        *{box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{margin:0;font-family:Inter,system-ui,sans-serif;background:#f6f8fc;color:#172033}
        a{text-decoration:none}
        .bn-page{min-height:calc(100vh - 78px)}
        .bn-float-ai{position:fixed;right:24px;bottom:24px;z-index:1080;width:62px;height:62px;border-radius:22px;display:grid;place-items:center;color:#fff;background:linear-gradient(135deg,#7c3aed,#ec4899);box-shadow:0 16px 40px rgba(124,58,237,.32);animation:bnFloat 3s ease-in-out infinite;transition:.25s}
        .bn-float-ai:hover{color:#fff;transform:translateY(-5px) scale(1.04);box-shadow:0 20px 50px rgba(124,58,237,.42)}
        .bn-float-ai i{font-size:25px}.bn-float-label{position:absolute;right:70px;white-space:nowrap;background:#111827;color:#fff;padding:8px 12px;border-radius:10px;font-size:11px;font-weight:800;opacity:0;transform:translateX(8px);transition:.2s;pointer-events:none}.bn-float-ai:hover .bn-float-label{opacity:1;transform:none}
        @keyframes bnFloat{0%,100%{box-shadow:0 16px 40px rgba(124,58,237,.30)}50%{box-shadow:0 20px 55px rgba(236,72,153,.38)}}

        .bn-reveal{opacity:0;transform:translateY(22px);transition:opacity .7s cubic-bezier(.2,.7,.2,1),transform .7s cubic-bezier(.2,.7,.2,1)}.bn-reveal.is-visible{opacity:1;transform:none}
        .bn-stagger>*{opacity:0;transform:translateY(18px);animation:bnStaggerIn .65s cubic-bezier(.2,.7,.2,1) forwards}.bn-stagger>*:nth-child(1){animation-delay:.05s}.bn-stagger>*:nth-child(2){animation-delay:.11s}.bn-stagger>*:nth-child(3){animation-delay:.17s}.bn-stagger>*:nth-child(4){animation-delay:.23s}.bn-stagger>*:nth-child(5){animation-delay:.29s}.bn-stagger>*:nth-child(6){animation-delay:.35s}@keyframes bnStaggerIn{to{opacity:1;transform:none}}
        .bn-ripple{position:relative;overflow:hidden;isolation:isolate}.bn-ripple::after{content:"";position:absolute;left:50%;top:50%;width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.35);transform:translate(-50%,-50%) scale(0);opacity:0;pointer-events:none}.bn-ripple:active::after{animation:bnRipple .55s ease-out}@keyframes bnRipple{0%{transform:translate(-50%,-50%) scale(0);opacity:.8}100%{transform:translate(-50%,-50%) scale(34);opacity:0}}
        .bn-brand-icon{position:relative;overflow:visible}.bn-brand-icon::after{content:"";position:absolute;inset:-5px;border-radius:18px;border:1px solid rgba(220,38,56,.16);animation:bnLogoRing 2.8s ease-out infinite}@keyframes bnLogoRing{0%{transform:scale(.88);opacity:.75}100%{transform:scale(1.35);opacity:0}}
        .bn-nav-link{position:relative;overflow:hidden}.bn-nav-link::after{content:"";position:absolute;left:12px;right:12px;bottom:5px;height:2px;border-radius:99px;background:linear-gradient(90deg,#dc2638,#f97383);transform:scaleX(0);transition:transform .25s ease}.bn-nav-link:hover::after,.bn-nav-link.bn-nav-current::after{transform:scaleX(1)}.bn-nav-link i{transition:transform .25s ease}.bn-nav-link:hover i{transform:translateY(-2px) scale(1.08)}
        .bn-btn-red,.bn-btn-dark,.bn-ai-nav{position:relative;overflow:hidden}.bn-btn-red::before,.bn-btn-dark::before,.bn-ai-nav::before{content:"";position:absolute;top:0;left:-120%;width:70%;height:100%;background:linear-gradient(100deg,transparent,rgba(255,255,255,.28),transparent);transform:skewX(-18deg);transition:left .55s ease}.bn-btn-red:hover::before,.bn-btn-dark:hover::before,.bn-ai-nav:hover::before{left:140%}
        .bn-user-pill{transition:.25s ease}.bn-user-pill:hover{transform:translateY(-2px);box-shadow:0 10px 22px rgba(15,23,42,.09)}
        .bn-form-shell{position:relative;overflow:hidden;border:1px solid rgba(255,255,255,.9)!important;box-shadow:0 30px 80px rgba(15,23,42,.11),0 0 0 1px rgba(220,38,56,.035)}.bn-form-shell::before{content:"";position:absolute;width:230px;height:230px;border-radius:50%;background:rgba(220,38,56,.08);filter:blur(55px);top:-110px;right:-90px;pointer-events:none}.bn-form-shell::after{content:"";position:absolute;width:220px;height:220px;border-radius:50%;background:rgba(124,58,237,.06);filter:blur(65px);bottom:-120px;left:-100px;pointer-events:none}.bn-form-content{position:relative;z-index:2}.bn-form-icon{width:74px;height:74px;border-radius:24px;display:grid;place-items:center;background:linear-gradient(145deg,#fff0f2,#ffe0e5);box-shadow:0 15px 35px rgba(220,38,56,.15);animation:bnIconFloat 3s ease-in-out infinite}.bn-form-icon svg{width:44px;height:44px}@keyframes bnIconFloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
        .bn-input{border:1px solid #dfe5ec!important;border-radius:15px!important;min-height:52px;transition:border-color .22s,box-shadow .22s,transform .22s;background:rgba(255,255,255,.9)}.bn-input:focus{border-color:#dc2638!important;box-shadow:0 0 0 4px rgba(220,38,56,.09)!important;transform:translateY(-1px)}.bn-submit{min-height:54px;border:0!important;border-radius:16px!important;background:linear-gradient(135deg,#e32940,#b91c2d)!important;box-shadow:0 14px 32px rgba(220,38,56,.24);font-weight:900;position:relative;overflow:hidden;transition:.25s}.bn-submit:hover{transform:translateY(-2px);box-shadow:0 18px 40px rgba(220,38,56,.3)}
        .bn-portal-chip{display:inline-flex;align-items:center;gap:7px;padding:7px 11px;border-radius:999px;background:#fff1f3;color:#c81e32;font-size:11px;font-weight:900}.bn-security-alert{border:1px solid #ffd6dc!important;background:linear-gradient(135deg,#fff7f8,#fff)!important;animation:bnAlertIn .45s ease both}@keyframes bnAlertIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:none}}
        .bn-floating-drop{position:absolute;width:18px;height:25px;border-radius:55% 55% 60% 60%;background:linear-gradient(145deg,#ff5267,#b9152d);transform:rotate(45deg);opacity:.13;filter:drop-shadow(0 8px 12px rgba(220,38,56,.2));animation:bnDropFloat 7s ease-in-out infinite;pointer-events:none}.bn-floating-drop::after{content:"";position:absolute;width:5px;height:5px;border-radius:50%;background:#fff;top:5px;left:5px;opacity:.55}@keyframes bnDropFloat{0%,100%{transform:translate3d(0,0,0) rotate(45deg)}50%{transform:translate3d(12px,-22px,0) rotate(52deg)}}

        @media(max-width:991px){.bn-float-ai{right:16px;bottom:16px}.bn-float-label{display:none}}
    </style>
</head>
<body>
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="bn-page">
        <?php if(session('success')): ?>
            <div class="container pt-3"><div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert"><i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="container pt-3"><div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo e(session('error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php if(auth()->check() && in_array(auth()->user()->role, ['user','donor'], true)): ?>
        <a href="<?php echo e(route('ai.assistant')); ?>" class="bn-float-ai" aria-label="Open BloodNexus AI">
            <span class="bn-float-label">Open BloodNexus AI</span>
            <i class="bi bi-stars"></i>
        </a>
    <?php endif; ?>

    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
document.addEventListener('click',function(e){
 const el=e.target.closest('.bn-ripple,.bn-nav-link');
 if(!el)return;
 const r=el.getBoundingClientRect(), wave=document.createElement('span');
 wave.className='bn-click-wave'; wave.style.width=wave.style.height=Math.max(r.width,r.height)+'px';
 wave.style.left=(e.clientX-r.left-r.width/2)+'px'; wave.style.top=(e.clientY-r.top-r.height/2)+'px';
 el.appendChild(wave); setTimeout(()=>wave.remove(),650);
});
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
    <script>document.addEventListener('DOMContentLoaded',()=>{const o=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('is-visible')}),{threshold:.12});document.querySelectorAll('.bn-reveal').forEach(e=>o.observe(e));});</script>
<script src="<?php echo e(asset('js/bloodnexus-ui.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\BloodNexus\resources\views/layouts/app.blade.php ENDPATH**/ ?>