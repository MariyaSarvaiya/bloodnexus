@extends('layouts.app')
@section('title','BloodNexus AI | Smart Blood Assistant')
@section('content')
<style>
.ai-page{padding:48px 0 80px;background:radial-gradient(circle at 10% 5%,rgba(124,58,237,.08),transparent 30%),radial-gradient(circle at 90% 10%,rgba(220,38,56,.08),transparent 28%)}
.ai-hero{position:relative;overflow:hidden;border-radius:30px;padding:34px;background:linear-gradient(135deg,#111827,#312e81 55%,#7c3aed);color:#fff;box-shadow:0 25px 70px rgba(49,46,129,.24)}.ai-hero:before{content:'✦';position:absolute;right:50px;top:-55px;font-size:220px;opacity:.06}.ai-status{display:inline-flex;align-items:center;gap:7px;padding:7px 12px;border-radius:30px;background:rgba(255,255,255,.1);font-size:11px;font-weight:900}.ai-dot{width:8px;height:8px;border-radius:50%;background:#4ade80;box-shadow:0 0 15px #4ade80}.ai-title{font-size:clamp(32px,4vw,52px);font-weight:900;letter-spacing:-2px;margin:15px 0 8px}.ai-card{background:#fff;border:1px solid #e9edf3;border-radius:25px;box-shadow:0 14px 45px rgba(15,23,42,.07);overflow:hidden}.ai-head{padding:18px 22px;border-bottom:1px solid #edf0f4;display:flex;justify-content:space-between;align-items:center}.ai-avatar{width:46px;height:46px;border-radius:15px;background:linear-gradient(135deg,#ede9fe,#fce7f3);display:grid;place-items:center;font-size:23px;animation:aiPulse 2.4s ease-in-out infinite}.ai-body{height:500px;overflow-y:auto;padding:24px;background:#fafbff}.msg{display:flex;margin-bottom:15px}.msg.user{justify-content:flex-end}.bubble{max-width:82%;padding:13px 16px;border-radius:18px;line-height:1.65;font-size:13px;white-space:pre-line}.msg.bot .bubble{background:#fff;border:1px solid #edf0f4;border-bottom-left-radius:6px;color:#334155}.msg.user .bubble{background:linear-gradient(135deg,#7c3aed,#4f46e5);color:#fff;border-bottom-right-radius:6px}.ai-input{padding:17px;border-top:1px solid #edf0f4}.ai-input input{border-radius:15px;padding:13px 15px}.send{width:52px;border:0;border-radius:15px;background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff}.quick{padding:20px;border-radius:20px;background:#fff;border:1px solid #edf0f4;box-shadow:0 9px 28px rgba(15,23,42,.05);height:100%;transition:.2s}.quick:hover{transform:translateY(-4px);box-shadow:0 16px 35px rgba(15,23,42,.09)}.quick button{border:0;background:none;width:100%;text-align:left;font-weight:800;color:#334155}.quick-icon{width:43px;height:43px;border-radius:14px;background:#f3f0ff;color:#7c3aed;display:grid;place-items:center;margin-bottom:11px;font-size:20px}@keyframes aiPulse{50%{transform:scale(1.07);box-shadow:0 0 0 9px rgba(124,58,237,.08)}}
</style>
<div class="container ai-page">
    <div class="ai-hero mb-4">
        <span class="ai-status"><span class="ai-dot"></span> LIVE BLOOD INTELLIGENCE</span>
        <h1 class="ai-title">BloodNexus AI</h1>
        <p class="mb-0 opacity-75">A smart assistant connected to your BloodNexus workflow — donor matching, emergency demand, request status, eligibility and blood-group guidance.</p>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="ai-card">
                <div class="ai-head"><div class="d-flex align-items-center gap-3"><div class="ai-avatar">🤖</div><div><strong>BloodNexus Intelligence</strong><div class="small text-success">● Online · Live project data</div></div></div><span class="badge text-bg-light">{{ auth()->user()->role === 'donor' ? 'DONOR AI' : 'BLOOD NEED AI' }}</span></div>
                <div class="ai-body" id="aiChatBody"><div class="msg bot"><div class="bubble">👋 Hi <strong>{{ auth()->user()->name }}</strong>!\n\nI’m BloodNexus AI. {{ auth()->user()->role === 'donor' ? 'I can check your matching requests, critical demand, availability and donation cooldown.' : 'I can search live donors, summarize your requests and guide you through creating a blood request.' }}\n\nTry one of the smart prompts below or ask me anything about your BloodNexus workflow.</div></div></div>
                <div class="ai-input"><form id="aiChatForm" class="d-flex gap-2">@csrf<input id="aiMessage" class="form-control" placeholder="Ask BloodNexus AI... e.g. Show critical requests" autocomplete="off" required><button class="send" type="submit"><i class="bi bi-send-fill"></i></button></form></div>
            </div>
        </div>
        <div class="col-lg-4"><div class="row g-3">
            @if(auth()->user()->role === 'donor')
                <div class="col-6 col-lg-12"><div class="quick"><button data-ai="Show critical requests"><div class="quick-icon">🚨</div>Critical Requests</button></div></div>
                <div class="col-6 col-lg-12"><div class="quick"><button data-ai="Find matching requests"><div class="quick-icon">🎯</div>Find Matches</button></div></div>
                <div class="col-6 col-lg-12"><div class="quick"><button data-ai="Am I eligible to donate?"><div class="quick-icon">❤️</div>Eligibility</button></div></div>
                <div class="col-6 col-lg-12"><div class="quick"><button data-ai="Show my donation history"><div class="quick-icon">📊</div>Donation History</button></div></div>
            @else
                <div class="col-6 col-lg-12"><div class="quick"><button data-ai="Find AB+ donors"><div class="quick-icon">🔎</div>Find Donors</button></div></div>
                <div class="col-6 col-lg-12"><div class="quick"><button data-ai="Show my requests"><div class="quick-icon">📋</div>My Requests</button></div></div>
                <div class="col-6 col-lg-12"><div class="quick"><button data-ai="How do I request blood?"><div class="quick-icon">🩸</div>Request Blood</button></div></div>
                <div class="col-6 col-lg-12"><div class="quick"><button data-ai="Explain critical blood requests"><div class="quick-icon">🚨</div>Emergency Help</button></div></div>
            @endif
        </div></div>
    </div>
</div>
@endsection
@push('scripts')
<script>
(function(){
 const form=document.getElementById('aiChatForm'), input=document.getElementById('aiMessage'), body=document.getElementById('aiChatBody');
 function add(text,who){const wrap=document.createElement('div');wrap.className='msg '+who;const b=document.createElement('div');b.className='bubble';b.textContent=text;wrap.appendChild(b);body.appendChild(wrap);body.scrollTop=body.scrollHeight;}
 async function ask(text){add(text,'user');input.value='';const loading=document.createElement('div');loading.className='msg bot';loading.innerHTML='<div class="bubble">🤖 Thinking…</div>';body.appendChild(loading);body.scrollTop=body.scrollHeight;try{const r=await fetch('{{ route('ai.assistant.ask') }}',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({message:text})});const d=await r.json();loading.remove();add(d.reply||'I could not generate a response.','bot')}catch(e){loading.remove();add('⚠️ AI connection failed. Please try again.','bot')}}
 form.addEventListener('submit',e=>{e.preventDefault();if(input.value.trim())ask(input.value.trim())});document.querySelectorAll('[data-ai]').forEach(b=>b.addEventListener('click',()=>ask(b.dataset.ai)));
})();
</script>
@endpush
