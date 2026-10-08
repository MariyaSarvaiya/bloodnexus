@extends('layouts.app')

@section('title', 'Join BloodNexus | Register')

@section('content')
<style>
    .bn-register-page {
        position: relative;
        min-height: calc(100vh - 78px);
        overflow: hidden;
        padding: 46px 0 55px;
        background:
            radial-gradient(circle at 0% 25%, rgba(255, 83, 108, .15), transparent 27%),
            radial-gradient(circle at 100% 70%, rgba(255, 196, 205, .28), transparent 30%),
            linear-gradient(135deg, #fff 0%, #fbfcff 55%, #fff6f8 100%);
    }

    .bn-register-page::before,
    .bn-register-page::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
    }

    .bn-register-page::before {
        width: 420px;
        height: 420px;
        left: -270px;
        top: 220px;
        border: 1px solid rgba(220, 38, 56, .10);
        box-shadow: 0 0 0 70px rgba(220, 38, 56, .025), 0 0 0 140px rgba(220, 38, 56, .018);
    }

    .bn-register-page::after {
        width: 340px;
        height: 340px;
        right: -220px;
        bottom: 20px;
        border: 1px solid rgba(220, 38, 56, .08);
        box-shadow: 0 0 0 65px rgba(220, 38, 56, .025);
    }

    .bn-register-container { position: relative; z-index: 2; }

    .bn-register-heading { text-align: center; max-width: 820px; margin: 0 auto 30px; }

    .bn-join-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 999px;
        color: #c51f34;
        background: rgba(255,255,255,.85);
        border: 1px solid #ffd7dd;
        box-shadow: 0 8px 25px rgba(220,38,56,.10);
        font-size: 11px;
        font-weight: 900;
        letter-spacing: .7px;
        animation: bnFadeUp .65s ease both;
    }

    .bn-register-heading h1 {
        margin: 15px 0 8px;
        color: #111827;
        font-size: clamp(34px, 4vw, 52px);
        line-height: 1.05;
        letter-spacing: -1.8px;
        font-weight: 900;
        animation: bnFadeUp .7s .08s ease both;
    }

    .bn-register-heading h1 span {
        color: #df263d;
        position: relative;
    }

    .bn-register-heading p {
        color: #64748b;
        font-size: 15px;
        margin: 0;
        animation: bnFadeUp .7s .16s ease both;
    }

    .bn-ecg {
        width: 150px;
        height: 25px;
        margin: 14px auto 0;
        overflow: visible;
    }

    .bn-ecg path {
        fill: none;
        stroke: #df263d;
        stroke-width: 3;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-dasharray: 260;
        stroke-dashoffset: 260;
        animation: bnEcg 2.2s linear infinite;
    }

    .bn-drop-art {
        position: absolute;
        left: 18px;
        top: 150px;
        width: 190px;
        opacity: .92;
        filter: drop-shadow(0 22px 30px rgba(220,38,56,.20));
        animation: bnDropFloat 4.2s ease-in-out infinite;
    }

    .bn-heart-art {
        position: absolute;
        right: 28px;
        top: 210px;
        width: 110px;
        opacity: .22;
        animation: bnHeartFloat 5s ease-in-out infinite;
    }

    .bn-floating-drop { position:absolute; width:18px; opacity:.72; animation: bnFall 6s linear infinite; }
    .bn-floating-drop.one { left:21%; top:8%; animation-delay:.3s; }
    .bn-floating-drop.two { right:23%; top:15%; width:14px; animation-delay:1.8s; }
    .bn-floating-drop.three { right:8%; top:44%; width:22px; animation-delay:3.1s; }
    .bn-floating-drop.four { left:8%; top:62%; width:13px; animation-delay:4s; }

    .bn-portals { max-width: 980px; margin: 0 auto; }

    .bn-portal-card {
        position: relative;
        height: 100%;
        padding: 30px;
        border-radius: 27px;
        background: rgba(255,255,255,.88);
        border: 1px solid #edf0f5;
        box-shadow: 0 18px 55px rgba(15,23,42,.08);
        overflow: hidden;
        transition: transform .35s cubic-bezier(.2,.8,.2,1), box-shadow .35s ease, border-color .35s ease;
        animation: bnFadeUp .8s .2s ease both;
    }

    .bn-portal-card.donor { animation-delay: .32s; }

    .bn-portal-card::before {
        content: "";
        position:absolute;
        width:180px;
        height:180px;
        border-radius:50%;
        right:-80px;
        top:-90px;
        background: rgba(220,38,56,.05);
        transition: transform .5s ease;
    }

    .bn-portal-card.donor::before { background: rgba(16,185,129,.06); }
    .bn-portal-card:hover { transform: translateY(-9px); box-shadow: 0 28px 70px rgba(15,23,42,.14); border-color: #ffd5db; }
    .bn-portal-card.donor:hover { border-color: #bdeedb; }
    .bn-portal-card:hover::before { transform: scale(1.35); }

    .bn-card-top { display:flex; gap:18px; align-items:flex-start; position:relative; z-index:1; }

    .bn-card-icon {
        width:76px;
        height:76px;
        flex:0 0 76px;
        border-radius:23px;
        display:grid;
        place-items:center;
        background:linear-gradient(145deg,#fff0f2,#ffe0e5);
        box-shadow: inset 0 0 0 1px rgba(220,38,56,.06), 0 12px 28px rgba(220,38,56,.10);
    }

    .bn-card-icon svg { width:43px; height:43px; }
    .bn-portal-card.donor .bn-card-icon { background:linear-gradient(145deg,#effcf6,#dff7eb); box-shadow:inset 0 0 0 1px rgba(16,185,129,.06),0 12px 28px rgba(16,185,129,.09); }

    .bn-card-badge {
        display:inline-flex;
        padding:6px 11px;
        border-radius:999px;
        color:#fff;
        background:#df263d;
        font-size:10px;
        font-weight:900;
        letter-spacing:.5px;
        margin-bottom:8px;
    }

    .bn-portal-card.donor .bn-card-badge { background:#15945f; }

    .bn-portal-card h2 { margin:0; color:#111827; font-size:25px; font-weight:900; letter-spacing:-.7px; }
    .bn-portal-card p { color:#64748b; line-height:1.7; font-size:13px; margin:11px 0 18px; }

    .bn-feature-box {
        position:relative;
        z-index:1;
        padding:14px 16px;
        border-radius:18px;
        background:#fff7f8;
        border:1px solid #ffe4e8;
        margin-bottom:18px;
    }

    .bn-portal-card.donor .bn-feature-box { background:#f3fcf7; border-color:#d8f3e6; }

    .bn-feature { display:flex; align-items:center; gap:10px; color:#475569; font-size:12px; font-weight:700; padding:5px 0; }
    .bn-feature i { color:#df263d; font-size:15px; }
    .bn-portal-card.donor .bn-feature i { color:#15945f; }

    .bn-portal-btn {
        position:relative;
        z-index:1;
        width:100%;
        min-height:53px;
        border-radius:16px;
        display:flex;
        align-items:center;
        justify-content:center;
        gap:10px;
        color:#fff!important;
        font-size:14px;
        font-weight:900;
        background:linear-gradient(135deg,#ed243d,#bd1830);
        box-shadow:0 13px 28px rgba(220,38,56,.22);
        transition:.28s ease;
    }

    .bn-portal-card.donor .bn-portal-btn { background:linear-gradient(135deg,#18a768,#07804c); box-shadow:0 13px 28px rgba(16,185,129,.20); }
    .bn-portal-btn:hover { transform:translateY(-2px); color:#fff!important; box-shadow:0 17px 35px rgba(220,38,56,.30); }
    .bn-portal-card.donor .bn-portal-btn:hover { box-shadow:0 17px 35px rgba(16,185,129,.28); }

    .bn-safe-line { text-align:center; color:#64748b; font-size:11px; font-weight:700; margin-top:15px; }
    .bn-safe-line strong { color:#334155; }
    .bn-safe-dot { margin:0 6px; color:#df263d; }

    .bn-trust-bar {
        max-width: 1120px;
        margin: 30px auto 0;
        padding: 17px 18px;
        border:1px solid #edf0f5;
        border-radius:22px;
        background:rgba(255,255,255,.92);
        box-shadow:0 15px 45px rgba(15,23,42,.07);
        display:grid;
        grid-template-columns:repeat(5,1fr);
        animation:bnFadeUp .8s .45s ease both;
    }

    .bn-trust-item { display:flex; align-items:center; justify-content:center; gap:11px; padding:6px 14px; border-right:1px solid #edf0f5; }
    .bn-trust-item:last-child { border-right:0; }
    .bn-trust-icon { width:40px; height:40px; border-radius:13px; display:grid; place-items:center; color:#df263d; background:#fff0f2; font-size:19px; }
    .bn-trust-item strong { display:block; color:#1f2937; font-size:12px; }
    .bn-trust-item small { display:block; color:#94a3b8; font-size:10px; margin-top:2px; }

    .bn-register-footer { text-align:center; color:#94a3b8; font-size:11px; margin-top:20px; }
    .bn-register-footer i { color:#df263d; }

    @keyframes bnFadeUp { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:none} }
    @keyframes bnDropFloat { 0%,100%{transform:translateY(0) rotate(-2deg)} 50%{transform:translateY(-13px) rotate(2deg)} }
    @keyframes bnHeartFloat { 0%,100%{transform:translateY(0) rotate(4deg)} 50%{transform:translateY(-18px) rotate(-4deg)} }
    @keyframes bnFall { 0%{transform:translateY(-10px);opacity:0} 15%{opacity:.75} 70%{opacity:.55} 100%{transform:translateY(75px);opacity:0} }
    @keyframes bnEcg { 0%{stroke-dashoffset:260} 55%{stroke-dashoffset:0} 100%{stroke-dashoffset:-260} }

    @media (max-width: 991px) {
        .bn-drop-art { left:-30px; width:145px; opacity:.35; }
        .bn-heart-art { right:-25px; width:90px; opacity:.16; }
        .bn-trust-bar { grid-template-columns:repeat(3,1fr); }
        .bn-trust-item:nth-child(3) { border-right:0; }
    }

    @media (max-width: 767px) {
        .bn-register-page { padding:30px 12px 45px; }
        .bn-register-heading h1 { font-size:34px; }
        .bn-drop-art,.bn-heart-art { display:none; }
        .bn-portal-card { padding:24px; }
        .bn-card-top { gap:13px; }
        .bn-card-icon { width:62px; height:62px; flex-basis:62px; border-radius:19px; }
        .bn-card-icon svg { width:35px; height:35px; }
        .bn-portal-card h2 { font-size:22px; }
        .bn-trust-bar { grid-template-columns:1fr 1fr; }
        .bn-trust-item { border-right:0; border-bottom:1px solid #edf0f5; justify-content:flex-start; }
        .bn-trust-item:last-child { border-bottom:0; }
    }

    @media (prefers-reduced-motion: reduce) {
        .bn-register-page *, .bn-register-page *::before, .bn-register-page *::after { animation:none!important; transition:none!important; }
    }
</style>

<div class="bn-register-page">
    <div class="bn-register-container container">

        {{-- Decorative blood drop --}}
        <svg class="bn-drop-art" viewBox="0 0 220 260" aria-hidden="true">
            <defs>
                <linearGradient id="bnDropGradient" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0" stop-color="#ff5368"/>
                    <stop offset=".48" stop-color="#ed1f3a"/>
                    <stop offset="1" stop-color="#a80f25"/>
                </linearGradient>
                <filter id="bnDropGlow"><feGaussianBlur stdDeviation="7"/></filter>
            </defs>
            <ellipse cx="110" cy="235" rx="65" ry="10" fill="#df263d" opacity=".13" filter="url(#bnDropGlow)"/>
            <path d="M110 15C110 15 39 101 39 151c0 45 31 79 71 79s71-34 71-79c0-50-71-136-71-136z" fill="url(#bnDropGradient)"/>
            <path d="M83 64C66 89 56 112 56 132c0 18 8 29 19 36" fill="none" stroke="#fff" stroke-width="10" stroke-linecap="round" opacity=".38"/>
            <path d="M110 116v55M82.5 143.5h55" stroke="#fff" stroke-width="13" stroke-linecap="round"/>
        </svg>

        <svg class="bn-heart-art" viewBox="0 0 120 120" aria-hidden="true">
            <path d="M60 101S12 72 12 39C12 18 38 9 52 27c4 5 8 10 8 10s4-5 8-10C82 9 108 18 108 39c0 33-48 62-48 62z" fill="#df263d"/>
            <path d="M23 43c4-12 15-18 25-16" fill="none" stroke="#fff" stroke-width="6" stroke-linecap="round" opacity=".6"/>
        </svg>

        <svg class="bn-floating-drop one" viewBox="0 0 30 40" aria-hidden="true"><path d="M15 2S5 15 5 23a10 10 0 0 0 20 0C25 15 15 2 15 2z" fill="#ef4357"/></svg>
        <svg class="bn-floating-drop two" viewBox="0 0 30 40" aria-hidden="true"><path d="M15 2S5 15 5 23a10 10 0 0 0 20 0C25 15 15 2 15 2z" fill="#ff7281"/></svg>
        <svg class="bn-floating-drop three" viewBox="0 0 30 40" aria-hidden="true"><path d="M15 2S5 15 5 23a10 10 0 0 0 20 0C25 15 15 2 15 2z" fill="#ef4357"/></svg>
        <svg class="bn-floating-drop four" viewBox="0 0 30 40" aria-hidden="true"><path d="M15 2S5 15 5 23a10 10 0 0 0 20 0C25 15 15 2 15 2z" fill="#ff7281"/></svg>

        <div class="bn-register-heading">
            <span class="bn-join-pill"><i class="bi bi-heart-pulse-fill"></i> JOIN BLOODNEXUS</span>
            <h1>How can we <span>help</span> you today?</h1>
            <p>Choose the portal that matches your purpose and make a difference.</p>
            <svg class="bn-ecg" viewBox="0 0 150 25" aria-hidden="true">
                <path d="M2 13h35l7-8 8 16 8-17 8 9h80"/>
            </svg>
        </div>

        <div class="bn-portals row g-4 justify-content-center">

            <div class="col-lg-6">
                <div class="bn-portal-card">
                    <div class="bn-card-top">
                        <div class="bn-card-icon">
                            <svg viewBox="0 0 48 48" fill="none" aria-hidden="true">
                                <path d="M24 5S11 21 11 29a13 13 0 0 0 26 0C37 21 24 5 24 5Z" stroke="#df263d" stroke-width="3"/>
                                <path d="M17 29c0-4 2-7 5-10" stroke="#df263d" stroke-width="3" stroke-linecap="round" opacity=".5"/>
                            </svg>
                        </div>
                        <div>
                            <span class="bn-card-badge">BLOOD SEEKER</span>
                            <h2>I Need Blood</h2>
                            <p>Search compatible donors, create blood requests and manage your blood requirements from your personal dashboard.</p>
                        </div>
                    </div>

                    <div class="bn-feature-box">
                        <div class="bn-feature"><i class="bi bi-search-heart-fill"></i><span>Search compatible donors</span></div>
                        <div class="bn-feature"><i class="bi bi-clipboard2-pulse-fill"></i><span>Request blood quickly</span></div>
                        <div class="bn-feature"><i class="bi bi-clock-history"></i><span>Track request status in real-time</span></div>
                    </div>

                    <a href="{{ route('register.user') }}" class="bn-portal-btn bn-ripple">
                        Register as Blood Seeker <i class="bi bi-arrow-right"></i>
                    </a>
                    <div class="bn-safe-line"><strong><i class="bi bi-shield-check"></i> Safe</strong><span class="bn-safe-dot">•</span> Secure <span class="bn-safe-dot">•</span> Confidential</div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="bn-portal-card donor">
                    <div class="bn-card-top">
                        <div class="bn-card-icon">
                            <svg viewBox="0 0 48 48" fill="none" aria-hidden="true">
                                <path d="M24 40S7 30 7 18C7 10 17 6 24 14c7-8 17-4 17 4 0 12-17 22-17 22Z" stroke="#15945f" stroke-width="3" stroke-linejoin="round"/>
                                <path d="M17 24l5 5 10-11" stroke="#15945f" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div>
                            <span class="bn-card-badge">DONOR</span>
                            <h2>I Want to Donate</h2>
                            <p>Register as a blood donor and help patients who need blood in your city and nearby areas. Be a hero!</p>
                        </div>
                    </div>

                    <div class="bn-feature-box">
                        <div class="bn-feature"><i class="bi bi-heart-fill"></i><span>Help save lives</span></div>
                        <div class="bn-feature"><i class="bi bi-award-fill"></i><span>Get donation certificate</span></div>
                        <div class="bn-feature"><i class="bi bi-people-fill"></i><span>Join a trusted donor community</span></div>
                    </div>

                    <a href="{{ route('register.donor') }}" class="bn-portal-btn bn-ripple">
                        Register as Donor <i class="bi bi-arrow-right"></i>
                    </a>
                    <div class="bn-safe-line"><strong><i class="bi bi-shield-check"></i> Safe</strong><span class="bn-safe-dot">•</span> Secure <span class="bn-safe-dot">•</span> Confidential</div>
                </div>
            </div>
        </div>

        <div class="bn-trust-bar">
            <div class="bn-trust-item"><div class="bn-trust-icon"><i class="bi bi-shield-lock-fill"></i></div><div><strong>100% Secure</strong><small>Your data is protected</small></div></div>
            <div class="bn-trust-item"><div class="bn-trust-icon"><i class="bi bi-people-fill"></i></div><div><strong>10K+ Donors</strong><small>Across multiple cities</small></div></div>
            <div class="bn-trust-item"><div class="bn-trust-icon"><i class="bi bi-droplet-fill"></i></div><div><strong>Lives Saved</strong><small>Together we help</small></div></div>
            <div class="bn-trust-item"><div class="bn-trust-icon"><i class="bi bi-headset"></i></div><div><strong>24/7 Support</strong><small>We're here to help</small></div></div>
            <div class="bn-trust-item"><div class="bn-trust-icon"><i class="bi bi-patch-check-fill"></i></div><div><strong>Trusted Platform</strong><small>Verified &amp; reliable</small></div></div>
        </div>

        <div class="bn-register-footer">
            <i class="bi bi-shield-check"></i> Your account information is protected
            <span class="mx-2">•</span>
            <i class="bi bi-heart-fill"></i> Together, we help save lives
        </div>
    </div>
</div>
@endsection
