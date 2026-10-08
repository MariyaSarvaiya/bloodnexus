/* BloodNexus UI micro-interactions. No business logic. */
document.addEventListener('click',function(e){
  const el=e.target.closest('.bn-ripple,.bn-btn,.bn-submit,.admin-enter,.admin-link');
  if(!el || el.tagName==='BUTTON' && el.disabled) return;
  const r=el.getBoundingClientRect();
  const size=Math.max(r.width,r.height)*1.15;
  const wave=document.createElement('span');
  wave.className='bn-click-wave';
  wave.style.width=wave.style.height=size+'px';
  wave.style.left=(e.clientX-r.left-size/2)+'px';
  wave.style.top=(e.clientY-r.top-size/2)+'px';
  wave.style.position='absolute';
  wave.style.borderRadius='50%';
  wave.style.pointerEvents='none';
  wave.style.background='rgba(255,255,255,.38)';
  wave.style.transform='scale(0)';
  wave.style.animation='bnUiRipple .62s ease-out';
  el.appendChild(wave);
  setTimeout(()=>wave.remove(),700);
});

document.addEventListener('DOMContentLoaded',()=>{
  document.querySelectorAll('form').forEach(form=>form.addEventListener('submit',()=>{
    const btn=form.querySelector('button[type="submit"]');
    if(btn && !btn.dataset.noLoader && !btn.disabled){
      btn.classList.add('bn-submitting');
      setTimeout(()=>btn.classList.remove('bn-submitting'),2500);
    }
  }));
});
