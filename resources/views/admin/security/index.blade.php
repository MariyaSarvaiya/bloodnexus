@extends('layouts.admin')
@section('title','Security Center')
@section('content')
<style>
.security-shell{animation:secIn .45s ease both}.security-hero{position:relative;overflow:hidden;border:1px solid #263244;border-radius:24px;padding:28px;background:linear-gradient(135deg,#08111f,#111d2f 60%,#18263a);color:#fff;box-shadow:0 18px 45px rgba(15,23,42,.16);margin-bottom:22px}.security-hero:before{content:"";position:absolute;right:-90px;top:-150px;width:330px;height:330px;border-radius:50%;background:radial-gradient(circle,rgba(220,38,56,.26),transparent 67%)}.security-kicker{font-size:10px;letter-spacing:1.8px;text-transform:uppercase;font-weight:900;color:#fb7185}.security-title{font-size:28px;font-weight:900;letter-spacing:-.8px}.security-live{display:inline-flex;align-items:center;gap:7px;padding:8px 12px;border:1px solid rgba(255,255,255,.12);border-radius:999px;background:rgba(255,255,255,.06);font-size:9px;font-weight:900;letter-spacing:.8px}.live-dot{width:7px;height:7px;border-radius:50%;background:#22c55e;animation:pulse .1s infinite}.security-stat,.stat-card{transition:.22s}.security-stat:hover,.stat-card:hover{transform:translateY(-3px);box-shadow:0 14px 32px rgba(15,23,42,.09)}.security-card,.admin-card{box-shadow:0 8px 26px rgba(15,23,42,.045);transition:.22s}.security-card:hover,.admin-card:hover{box-shadow:0 14px 34px rgba(15,23,42,.075)}.admin-table tbody tr,.security-table tbody tr{transition:.18s}.admin-table tbody tr:hover,.security-table tbody tr:hover{background:#fafbfc}.locked-row{background:linear-gradient(90deg,#fff7f7,transparent)}.risk-bar{display:inline-block;width:70px;height:6px;border-radius:99px;background:#edf1f5;overflow:hidden;vertical-align:middle}.risk-fill{height:100%;border-radius:99px;background:linear-gradient(90deg,#22c55e,#f59e0b,#dc2638)}@keyframes secIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}@keyframes pulse{0%,100%{box-shadow:0 0 0 0 rgba(34,197,94,.2)}50%{box-shadow:0 0 0 6px rgba(34,197,94,0)}}
</style>
<div class="admin-pagebar"><div><h1 class="page-title">Security Command Center</h1><div class="admin-page-subtitle">Account protection, suspicious activity and audit telemetry.</div></div><div class="d-flex gap-2"><a class="btn-admin btn-light-admin" href="{{ route('admin.security.appeals') }}"><i class="bi bi-inbox me-1"></i>Appeals <span class="badge text-bg-danger">{{ $stats['appeals'] }}</span></a><span class="badge rounded-pill text-bg-dark px-3 py-2"><i class="bi bi-shield-check me-1"></i>LIVE</span></div></div>
<div class="row g-3 mb-4">
@foreach([['🚨 Brute Force Alerts',$stats['bruteforce'],'#ffe4e6','#be123c','bi-shield-exclamation'],['Blocked Accounts',$stats['blocked'],'#fee2e2','#b91c1c','bi-person-lock'],['Pending Appeals',$stats['appeals'],'#fef3c7','#a16207','bi-person-check-fill'],['Events Today',$stats['today'],'#dbeafe','#1d4ed8','bi-activity']] as $x)
<div class="col-6 col-xl-3"><div class="stat-card"><div class="stat-icon" style="background:{{$x[2]}};color:{{$x[3]}}"><i class="bi {{$x[4]}}"></i></div><div class="stat-label">{{$x[0]}}</div><div class="stat-number">{{$x[1]}}</div><div class="stat-desc">Live security status</div></div></div>
@endforeach
</div>
<div class="admin-card mb-4"><div class="admin-card-body"><form class="row g-2 align-items-end" method="GET"><div class="col-md-3"><label class="form-label small fw-bold">Severity</label><select name="severity" class="form-select filter-control"><option value="">All</option>@foreach(['critical','high','medium','info'] as $v)<option value="{{$v}}" @selected(request('severity')===$v)>{{ucfirst($v)}}</option>@endforeach</select></div><div class="col-md-3"><label class="form-label small fw-bold">Event</label><input name="event_type" value="{{request('event_type')}}" class="form-control filter-control" placeholder="failed_login"></div><div class="col-md-4"><label class="form-label small fw-bold">Search</label><input name="search" value="{{request('search')}}" class="form-control filter-control" placeholder="Incident, IP, user..."></div><div class="col-md-2"><button class="btn-admin btn-dark w-100 py-2"><i class="bi bi-search me-1"></i>Filter</button></div></form></div></div>
<div class="admin-card mb-4 border border-danger-subtle">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <div><strong class="text-danger">🚨 Brute Force & Security Alerts</strong><div class="admin-page-subtitle">High-risk repeated failed logins detected from the same account/IP within a rolling 10-minute window.</div></div>
        <span class="badge-soft status-rejected">{{ $stats['bruteforce'] }} HIGH RISK</span>
    </div>
    <div class="table-wrap"><table class="table admin-table mb-0"><thead><tr><th>Risk</th><th>Account</th><th>Failed Attempts</th><th>IP Address</th><th>Time Window</th><th>Action</th></tr></thead><tbody>
    @forelse($bruteForceAlerts as $alert)
        <tr class="locked-row">
            <td><span class="badge-soft status-rejected">🚨 HIGH RISK</span></td>
            <td>@if($alert->user)<strong>{{ $alert->user->email }}</strong><div class="text-muted" style="font-size:9px">{{ $alert->user->name }} · {{ strtoupper($alert->user->role) }}</div>@else<strong>Unknown / guest email</strong><div class="text-muted" style="font-size:9px">No matching account</div>@endif</td>
            <td><strong class="text-danger">{{ $alert->attempts }}</strong></td>
            <td><code>{{ $alert->ip_address ?? 'Unknown' }}</code></td>
            <td>{{ $alert->first_at?->format('h:i:s A') }} → {{ $alert->last_at?->format('h:i:s A') }}</td>
            <td>
                @if($alert->user)
                    <div class="d-flex flex-wrap gap-1">
                        <a href="#audit-log" class="btn-admin btn-light-admin">View Activity</a>
                        @if(!$alert->user->isSecurityBlocked())
                        <form method="POST" action="{{ route('admin.security.user.block', $alert->user) }}" onsubmit="return confirm('Block this account due to high-risk brute-force activity?')">@csrf<input type="hidden" name="reason" value="High-risk repeated failed login activity detected from the same account/IP."><button class="btn-admin btn-red">Block Account</button></form>
                        @else <span class="badge-soft status-rejected">BLOCKED</span> @endif
                    </div>
                @else <span class="text-muted" style="font-size:10px">No account available to block</span>@endif
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="empty-state">No high-risk brute-force pattern detected in the latest 10 minutes.</td></tr>
    @endforelse
    </tbody></table></div>
</div>

<div class="admin-card mb-4">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <div><strong>🚫 Permanently Blocked Accounts</strong><div class="admin-page-subtitle">Complete block list with the reason, time and appeal status.</div></div>
        <span class="badge-soft status-rejected">{{ $blockedUsers->count() }} BLOCKED</span>
    </div>
    <div class="table-wrap"><table class="table admin-table mb-0"><thead><tr><th>Account</th><th>Role</th><th>Block Reason</th><th>Blocked At</th><th>Appeal</th><th>Action</th></tr></thead><tbody>
    @forelse($blockedUsers as $u)
        @php($appeal = \App\Models\AccountAppeal::whereRaw('LOWER(email) = ?', [strtolower($u->email)])->latest()->first())
        <tr class="locked-row">
            <td><strong>{{ $u->name }}</strong><div class="text-muted" style="font-size:9px">{{ $u->email }}</div></td>
            <td>{{ strtoupper($u->role) }}</td>
            <td style="max-width:280px">{{ $u->security_block_reason ?: 'Security policy violation / suspicious login activity.' }}</td>
            <td>{{ $u->security_blocked_at?->format('d M Y, h:i A') ?? '—' }}</td>
            <td>@if($appeal)<span class="badge-soft {{ $appeal->status==='approved'?'status-completed':($appeal->status==='rejected'?'status-rejected':'status-pending') }}">{{ strtoupper($appeal->status) }}</span><div class="text-muted" style="font-size:9px">One-time appeal used</div>@else<span class="text-muted" style="font-size:10px">No appeal submitted</span>@endif</td>
            <td><form method="POST" action="{{ route('admin.security.user.unlock', $u) }}" onsubmit="return confirm('Unblock and restore this account?')">@csrf<button class="btn-admin btn-light-admin">Unblock</button></form></td>
        </tr>
    @empty<tr><td colspan="6" class="empty-state">No permanently blocked accounts.</td></tr>@endforelse
    </tbody></table></div>
</div>

<div class="admin-card mb-4"><div class="admin-card-header d-flex justify-content-between"><div><strong>🔒 Temporarily Locked Accounts</strong><div class="admin-page-subtitle">Accounts blocked or restricted after repeated incorrect passwords or suspicious login activity.</div></div><span class="badge-soft status-rejected">{{$stats['locked']}} locked</span></div><div class="table-wrap"><table class="table admin-table mb-0"><thead><tr><th>User</th><th>Role</th><th>Failed Attempts</th><th>Locked Until</th><th>Reason</th><th>Action</th></tr></thead><tbody>@php($lockedUsers=\App\Models\User::whereNotNull('locked_until')->where('locked_until','>',now())->latest('locked_until')->get())@forelse($lockedUsers as $u)<tr><td><strong>{{$u->name}}</strong><div class="text-muted" style="font-size:9px">{{$u->email}}</div></td><td>{{strtoupper($u->role)}}</td><td>{{$u->failed_login_attempts}}</td><td>{{$u->locked_until?->format('d M Y, h:i A')}}</td><td>3 incorrect password attempts</td><td><form method="POST" action="{{route('admin.security.user.unlock', $u)}}">@csrf<button class="btn-admin btn-red">Unlock</button></form></td></tr>@empty<tr><td colspan="6" class="empty-state">No accounts are currently locked.</td></tr>@endforelse</tbody></table></div></div>

<div class="admin-card mb-4">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <div><strong>📈 Frequent Login Monitor</strong><div class="admin-page-subtitle">Users with 5+ successful logins today. Send a warning before security escalation.</div></div>
        <span class="badge-soft status-pending">{{ $frequentUsers->count() }} monitored</span>
    </div>
    <div class="table-wrap">
        <table class="table admin-table mb-0">
            <thead><tr><th>User</th><th>Role</th><th>Today's Logins</th><th>Last Warning</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($frequentUsers as $u)
                <tr class="{{ $u->successful_logins_today >= 8 ? 'locked-row' : '' }}">
                    <td><strong>{{ $u->name }}</strong><div class="text-muted" style="font-size:9px">{{ $u->email }}</div></td>
                    <td>{{ strtoupper($u->role) }}</td>
                    <td><span class="badge-soft {{ $u->successful_logins_today >= 8 ? 'status-rejected' : 'status-pending' }}">{{ $u->successful_logins_today }} / 8</span></td>
                    <td>{{ $u->last_login_warning_at?->format('d M Y, h:i A') ?? 'Not sent' }}</td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <form method="POST" action="{{ route('admin.security.user.warning', $u) }}" class="d-flex gap-1">
                                @csrf
                                <input type="text" name="message" value="Your account has shown unusually frequent login activity today. Please avoid repeated sign-ins and keep your account secure. Further excessive activity may result in a permanent security block." class="form-control form-control-sm" style="min-width:300px" required>
                                <button class="btn-admin btn-light-admin">⚠️ Warn</button>
                            </form>
                            <form method="POST" action="{{ route('admin.security.user.block', $u) }}" onsubmit="return confirm('Permanently block this account?')">
                                @csrf
                                <input type="hidden" name="reason" value="Excessive login activity / repeated sign-in attempts detected by administrator.">
                                <button class="btn-admin btn-red">🔴 Block</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty-state">No unusually frequent login activity today.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="audit-log" class="admin-card"><div class="admin-card-header d-flex justify-content-between"><div><strong>🕵️ Audit Log</strong><div class="admin-page-subtitle">No private chat content is exposed here—only security and activity metadata.</div></div><span class="badge-soft status-accepted">{{$stats['total']}} events</span></div><div class="table-wrap"><table class="table admin-table mb-0"><thead><tr><th>Incident</th><th>Severity</th><th>Event</th><th>User</th><th>IP</th><th>Risk</th><th>Time</th><th></th></tr></thead><tbody>
@forelse($logs as $log)<tr><td><strong class="text-danger">{{$log->incident_id}}</strong><div class="text-muted" style="font-size:9px;max-width:260px">{{$log->message}}</div></td><td><span class="badge-soft {{$log->severity==='critical'?'status-rejected':($log->severity==='high'?'status-pending':($log->severity==='medium'?'status-matched':'status-accepted'))}}">{{strtoupper($log->severity)}}</span></td><td><code>{{$log->event_type}}</code></td><td>{{$log->user?->name??'Guest/System'}}<div class="text-muted" style="font-size:9px">#{{$log->user_id??'-'}}</div></td><td>{{$log->ip_address??'-'}}</td><td><strong>{{$log->risk_score}}/100</strong></td><td>{{$log->created_at?->format('d M Y, h:i A')}}</td><td>@if($log->resolved_at)<span class="badge-soft status-completed">RESOLVED</span>@else<form method="POST" action="{{route('admin.security.resolve',$log)}}">@csrf<button class="btn-admin btn-light-admin">Resolve</button></form>@endif</td></tr>@empty<tr><td colspan="8" class="empty-state">No security events found.</td></tr>@endforelse
</tbody></table></div>@if($logs->hasPages())<div class="p-3">{{$logs->links()}}</div>@endif</div>
@endsection
