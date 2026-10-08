<nav class="navbar navbar-expand-lg bn-navbar sticky-top">
    <div class="container-fluid px-lg-4">
        <a class="navbar-brand bn-brand" href="{{ route('home') }}">
            <span class="bn-brand-icon" aria-hidden="true">
                <svg viewBox="0 0 48 48" fill="none">
                    <path d="M24 4S9 22 9 31a15 15 0 0 0 30 0C39 22 24 4 24 4Z" fill="url(#bnLogoGrad)"/>
                    <path d="M24 15v18M15 24h18" stroke="#fff" stroke-width="4" stroke-linecap="round"/>
                    <defs><linearGradient id="bnLogoGrad" x1="8" y1="4" x2="40" y2="40" gradientUnits="userSpaceOnUse"><stop stop-color="#ff5267"/><stop offset="1" stop-color="#b9152d"/></linearGradient></defs>
                </svg>
            </span>
            <span>BloodNexus</span>
        </a>
        <button class="navbar-toggler bn-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#bnMainNav" aria-label="Toggle navigation"><i class="bi bi-list"></i></button>
        <div class="collapse navbar-collapse" id="bnMainNav">
            @php($bnRoute = request()->route()?->getName())
            @guest
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li><a class="bn-nav-link {{ $bnRoute === 'home' ? 'bn-nav-current' : '' }}" href="{{ route('home') }}"><i class="bi bi-house-heart-fill"></i> Home</a></li>
                    <li><a class="bn-nav-link {{ $bnRoute === 'login' ? 'bn-nav-current' : '' }}" href="{{ route('login') }}"><i class="bi bi-droplet-half"></i> Blood Availability</a></li>
                    <li><a class="bn-btn bn-btn-dark bn-ripple" href="{{ route('login') }}">Login</a></li>
                    <li><a class="bn-btn bn-btn-red bn-ripple" href="{{ route('register') }}">Register</a></li>
                </ul>
            @else
                @if(auth()->user()->role === 'user')
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                        <li><a class="bn-nav-link {{ $bnRoute === 'home' ? 'bn-nav-current' : '' }}" href="{{ route('home') }}"><i class="bi bi-house-heart-fill"></i> Home</a></li>
                        <li><a class="bn-nav-link" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a></li>
                        <li><a class="bn-nav-link" href="{{ route('blood.search') }}"><i class="bi bi-search-heart-fill"></i> Find Blood</a></li>
                        <li><a class="bn-nav-link bn-nav-danger" href="{{ route('blood.request') }}"><i class="bi bi-heart-pulse-fill"></i> Need Blood</a></li>
                        <li><a class="bn-nav-link" href="{{ route('blood.requests.mine') }}"><i class="bi bi-clipboard2-check-fill"></i> My Requests</a></li>
                        <li><a class="bn-nav-link" href="{{ route('messages.index') }}"><i class="bi bi-chat-dots-fill"></i> Messages</a></li>
                        <li><a class="bn-nav-link" href="{{ route('notifications.index') }}"><i class="bi bi-bell-fill"></i> Notifications</a></li>
                        <li><a class="bn-ai-nav bn-ripple" href="{{ route('ai.assistant') }}"><i class="bi bi-stars"></i> BloodNexus AI</a></li>
                        <li class="dropdown ms-lg-1">
                            <a class="bn-user-pill dropdown-toggle" href="#" data-bs-toggle="dropdown"><span>{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span>{{ \Illuminate\Support\Str::limit(auth()->user()->name,12) }}</a>
                            <ul class="dropdown-menu dropdown-menu-end bn-dropdown"><li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li><li><a class="dropdown-item" href="{{ route('ai.assistant') }}">BloodNexus AI</a></li><li><hr class="dropdown-divider"></li><li><form method="POST" action="{{ route('logout') }}">@csrf<button class="dropdown-item text-danger" type="submit">Logout</button></form></li></ul>
                        </li>
                    </ul>
                @elseif(auth()->user()->role === 'donor')
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                        <li><a class="bn-nav-link bn-donor-active" href="{{ route('home') }}"><i class="bi bi-house-heart-fill"></i> Donor Home</a></li>
                        <li><a class="bn-nav-link" href="{{ route('donor.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a></li>
                        <li><a class="bn-nav-link" href="{{ route('donor.requests') }}"><i class="bi bi-droplet-fill"></i> Donation History</a></li>
                        <li><a class="bn-nav-link" href="{{ route('messages.index') }}"><i class="bi bi-chat-dots-fill"></i> Messages</a></li>
                        <li><a class="bn-nav-link" href="{{ route('notifications.index') }}"><i class="bi bi-bell-fill"></i> Notifications</a></li>
                        <li><a class="bn-ai-nav bn-ripple" href="{{ route('ai.assistant') }}"><i class="bi bi-stars"></i> BloodNexus AI</a></li>
                        <li class="dropdown ms-lg-1">
                            <a class="bn-user-pill bn-donor-pill dropdown-toggle" href="#" data-bs-toggle="dropdown"><span>{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span>{{ \Illuminate\Support\Str::limit(auth()->user()->name,12) }}</a>
                            <ul class="dropdown-menu dropdown-menu-end bn-dropdown"><li class="px-3 py-2"><strong>{{ auth()->user()->name }}</strong><div class="small text-muted">Blood Donor</div></li><li><hr class="dropdown-divider"></li><li><a class="dropdown-item" href="{{ route('donor.dashboard') }}">Donor Dashboard</a></li><li><a class="dropdown-item" href="{{ route('donor.requests') }}">Donation History</a></li><li><a class="dropdown-item" href="{{ route('ai.assistant') }}">BloodNexus AI</a></li><li><hr class="dropdown-divider"></li><li><form method="POST" action="{{ route('logout') }}">@csrf<button class="dropdown-item text-danger" type="submit">Logout</button></form></li></ul>
                        </li>
                    </ul>
                @else
                    <ul class="navbar-nav ms-auto"><li><a class="bn-nav-link" href="{{ route('admin.dashboard') }}">Admin Command Center</a></li><li><form method="POST" action="{{ route('logout') }}">@csrf<button class="bn-btn bn-btn-dark bn-ripple" type="submit">Logout</button></form></li></ul>
                @endif
            @endguest
        </div>
    </div>
</nav>
<style>
.bn-navbar{min-height:78px;background:rgba(255,255,255,.94);backdrop-filter:blur(18px);border-bottom:1px solid #e8ecf2;box-shadow:0 10px 30px rgba(15,23,42,.07);z-index:1050}.bn-brand{display:flex;align-items:center;gap:10px;font-size:21px;font-weight:900;color:#b91c2d!important;letter-spacing:-.5px}.bn-brand-icon{width:43px;height:43px;display:grid;place-items:center;border-radius:14px;background:linear-gradient(135deg,#fff0f2,#ffe0e4);box-shadow:0 8px 24px rgba(220,38,56,.15);animation:bnHeart 2.6s ease-in-out infinite}.bn-brand-icon svg{width:30px;height:30px;display:block}.bn-nav-link{display:flex;align-items:center;gap:7px;padding:10px 11px;border-radius:11px;color:#334155!important;font-size:12px;font-weight:800;transition:.24s;position:relative;overflow:hidden;isolation:isolate}.bn-nav-link:hover,.bn-donor-active,.bn-nav-current{background:#fff1f3;color:#c81e32!important;transform:translateY(-1px)}.bn-nav-current:after,.bn-donor-active:after{content:'';position:absolute;left:14px;right:14px;bottom:3px;height:2px;border-radius:9px;background:linear-gradient(90deg,#ff7a88,#dc2638);box-shadow:0 0 9px rgba(220,38,56,.3)}.bn-nav-danger{color:#dc2638!important}.bn-ai-nav{display:flex;align-items:center;gap:7px;padding:10px 13px;border-radius:12px;color:#fff!important;background:linear-gradient(135deg,#7c3aed,#ec4899);font-size:12px;font-weight:900;box-shadow:0 9px 22px rgba(124,58,237,.23);transition:.22s}.bn-ai-nav:hover{color:#fff!important;transform:translateY(-2px);box-shadow:0 14px 28px rgba(124,58,237,.32)}.bn-btn{display:inline-flex;align-items:center;justify-content:center;border:0;border-radius:11px;padding:10px 15px;font-size:12px;font-weight:900;margin-left:5px}.bn-btn-red{background:linear-gradient(135deg,#dc2638,#b91c2d);color:#fff!important}.bn-btn-dark{background:#111827;color:#fff!important}.bn-user-pill{display:flex;align-items:center;gap:7px;padding:5px 10px 5px 5px;border:1px solid #e5e7eb;border-radius:30px;color:#1f2937!important;font-size:11px;font-weight:800}.bn-user-pill span{width:30px;height:30px;display:grid;place-items:center;border-radius:50%;background:linear-gradient(135deg,#dc2638,#7f1d1d);color:#fff}.bn-donor-pill span{background:linear-gradient(135deg,#991b1b,#dc2638)}.bn-dropdown{border:0;border-radius:16px;padding:8px;box-shadow:0 18px 50px rgba(15,23,42,.14)}.bn-dropdown .dropdown-item{border-radius:10px;padding:9px 11px;font-size:12px;font-weight:700}.bn-toggler{border:0;font-size:24px}.bn-toggler:focus{box-shadow:none}.bn-ripple{position:relative;overflow:hidden;isolation:isolate}.bn-click-wave{position:absolute;border-radius:50%;background:rgba(255,255,255,.38);transform:scale(0);animation:bnRipple .62s linear;pointer-events:none;z-index:-1}@keyframes bnRipple{to{transform:scale(4);opacity:0}}@keyframes bnHeart{0%,100%{transform:scale(1)}10%{transform:scale(1.07)}20%{transform:scale(1)}}@media(max-width:991px){.bn-nav-link,.bn-ai-nav,.bn-user-pill{margin-top:4px}.bn-btn{margin-top:6px}.bn-navbar{min-height:68px}}
</style>
