<?php $__env->startSection('title','Donor Registration | BloodNexus'); ?>
<?php $__env->startSection('content'); ?>
<style>
.bn-register-page{min-height:calc(100vh - 78px);padding:42px 0 70px;background:radial-gradient(circle at 8% 12%,rgba(220,38,56,.10),transparent 27%),radial-gradient(circle at 92% 82%,rgba(99,102,241,.08),transparent 28%),linear-gradient(135deg,#fbfcff,#f7f8fc 55%,#fff7f8)}
.bn-register-grid{display:grid;grid-template-columns:.82fr 1.18fr;gap:28px;align-items:stretch}.bn-register-side{position:relative;overflow:hidden;border-radius:30px;padding:38px;background:linear-gradient(145deg,#171d2d,#262d40);color:#fff;box-shadow:0 24px 65px rgba(17,24,39,.18)}.bn-register-side:before{content:'';position:absolute;width:260px;height:260px;border:1px solid rgba(255,255,255,.10);border-radius:50%;right:-100px;top:-80px}.bn-register-side:after{content:'';position:absolute;width:180px;height:180px;border:1px dashed rgba(255,255,255,.10);border-radius:50%;left:-75px;bottom:-55px}.bn-register-side h1{font-size:clamp(36px,4vw,54px);font-weight:900;letter-spacing:-2.5px;line-height:1.02;margin:20px 0 16px}.bn-register-side h1 span{color:#ff5267}.bn-register-side p{color:#cbd5e1;line-height:1.7}.bn-feature{display:flex;gap:12px;align-items:flex-start;margin-top:18px}.bn-feature i{color:#ff5267;font-size:19px}.bn-blood-orb{position:absolute;right:34px;bottom:30px;width:125px;height:125px;border-radius:50%;border:1px solid rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-size:52px;box-shadow:0 0 0 18px rgba(255,255,255,.025),0 0 0 38px rgba(255,255,255,.018);animation:bnFloat 3.5s ease-in-out infinite}.bn-register-card{background:#fff;animation:bnRegisterCardIn .8s cubic-bezier(.18,.8,.2,1) both;border:1px solid #edf0f5;border-radius:30px;padding:34px;box-shadow:0 24px 65px rgba(17,24,39,.10)}.bn-register-head{display:flex;gap:15px;align-items:center;margin-bottom:24px}.bn-register-icon{width:62px;height:62px;border-radius:20px;background:#fff0f2;display:flex;align-items:center;justify-content:center;font-size:28px;box-shadow:0 12px 26px rgba(220,38,56,.10);animation:bnFloat 3s ease-in-out infinite}.bn-register-head h2{font-weight:900;letter-spacing:-1.2px;margin:0}.bn-register-head p{margin:4px 0 0;color:#64748b}.bn-step{display:flex;align-items:center;gap:10px;background:#f8fafc;border:1px solid #e8edf4;border-radius:18px;padding:12px 15px;margin-bottom:22px}.bn-step .num{width:29px;height:29px;border-radius:50%;background:#dc2638;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:13px}.bn-input{border-radius:15px!important;min-height:50px;border-color:#dfe4ec!important;box-shadow:none!important;transition:.22s}.bn-input:focus{border-color:#dc2638!important;box-shadow:0 0 0 4px rgba(220,38,56,.08)!important;transform:translateY(-1px)}.bn-submit{min-height:54px;border-radius:16px;font-weight:900;box-shadow:0 13px 28px rgba(220,38,56,.20);position:relative;overflow:hidden}.bn-submit:after{content:'';position:absolute;inset:-80% -30%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.25),transparent);transform:translateX(-80%) rotate(8deg);transition:.7s}.bn-submit:hover:after{transform:translateX(80%) rotate(8deg)}.bn-trust{background:#fff8f9;border:1px solid #ffe0e5;border-radius:17px;padding:13px 15px;color:#64748b}.bn-trust strong{color:#b9152d}.bn-link{color:#dc2638;text-decoration:none;font-weight:800}.bn-link:hover{text-decoration:underline}@keyframes bnFloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}} @keyframes bnRegisterCardIn{from{opacity:0;transform:translateY(26px) scale(.985)}to{opacity:1;transform:translateY(0) scale(1)}}@media(max-width:992px){.bn-register-grid{grid-template-columns:1fr}.bn-register-side{display:none}}@media(max-width:576px){.bn-register-page{padding:22px 0 45px}.bn-register-card{padding:22px;border-radius:23px}}
</style>
<div class="bn-register-page"><div class="container"><div class="bn-register-grid">
 <div class="bn-register-side bn-reveal"><span class="bn-portal-chip"><i class="bi bi-heart-pulse-fill"></i> DONOR PORTAL</span><h1>Give blood.<br><span>Give hope.</span></h1><p>Join BloodNexus as a donor and receive compatible blood requests across cities and areas.</p><div class="bn-feature"><i class="bi bi-person-check-fill"></i><div><strong>Quick account setup</strong><br><small class="text-white-50">Create your donor profile and get started.</small></div></div><div class="bn-feature"><i class="bi bi-geo-alt-fill"></i><div><strong>City + area profile</strong><br><small class="text-white-50">Your location helps organize matching and requests.</small></div></div><div class="bn-feature"><i class="bi bi-shield-check"></i><div><strong>Secure donor account</strong><br><small class="text-white-50">Only verified accounts can access donor features.</small></div></div><div class="bn-blood-orb">❤️</div></div>
 <div class="bn-register-card bn-reveal"><div class="bn-register-head"><div class="bn-register-icon">🩸</div><div><h2>Become a Blood Donor</h2><p>Create your secure donor profile.</p></div></div>
 <?php if($errors->any()): ?><div class="alert alert-danger rounded-4 border-0"><strong>Please fix the following:</strong><ul class="mb-0 mt-2"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div><?php endif; ?>
 <div class="bn-step"><span class="num">1</span><div><strong>Donor details</strong><div class="small text-secondary">Complete your details and your donor account will be ready to use.</div></div></div>
 <form method="POST" action="<?php echo e(route('register.donor.submit')); ?>" class="bn-stagger"><?php echo csrf_field(); ?>
  <div class="row g-3">
   <div class="col-md-6"><label class="form-label fw-bold">Full Name</label><input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-control bn-input" placeholder="Enter your full name" required></div>
   <div class="col-md-6"><label class="form-label fw-bold">Email Address</label><input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control bn-input" placeholder="you@gmail.com" required><div class="small text-secondary mt-1"><i class="bi bi-envelope me-1"></i>Use an email address you can access.</div></div>
   <div class="col-md-6"><label class="form-label fw-bold">Phone Number</label><input type="text" name="phone" value="<?php echo e(old('phone')); ?>" class="form-control bn-input" placeholder="Enter phone number" required></div>
   <div class="col-md-6"><label class="form-label fw-bold">Blood Group</label><select name="blood_group" class="form-select bn-input" required><option value="">Select Blood Group</option><?php $__currentLoopData = ['A+','A-','B+','B-','AB+','AB-','O+','O-']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($group); ?>" <?php if(old('blood_group')===$group): echo 'selected'; endif; ?>><?php echo e($group); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
   <div class="col-md-6"><label class="form-label fw-bold">City</label><input type="text" name="city" value="<?php echo e(old('city')); ?>" class="form-control bn-input" placeholder="Ahmedabad" required></div>
   <div class="col-md-6"><label class="form-label fw-bold">Area / Locality</label><input type="text" name="area" value="<?php echo e(old('area')); ?>" class="form-control bn-input" placeholder="Navrangpura, Maninagar" required></div>
   <div class="col-12">
    <div class="p-4 rounded-4" style="background:linear-gradient(145deg,#fff8f9,#f8fbff);border:1px solid #edf0f5;">
     <div class="d-flex align-items-center gap-3 mb-3">
      <div style="width:48px;height:48px;border-radius:15px;background:#fff0f2;display:flex;align-items:center;justify-content:center;font-size:22px;">🩺</div>
      <div><label class="form-label fw-bold mb-0">Medical History</label><div class="small text-secondary">Please answer every question with Yes or No.</div></div>
     </div>
     <?php
      $medicalQuestions = [
       'chronic_condition' => 'Do you have any long-term / chronic medical condition?',
       'regular_medicines' => 'Do you currently take any regular medicines?',
       'allergies' => 'Do you have any known allergy?',
       'recent_surgery' => 'Have you had any surgery or medical procedure recently?',
       'fever_infection' => 'Do you currently have fever or an infection?',
       'blood_transfusion' => 'Have you received a blood transfusion recently?',
       'tattoo_piercing' => 'Have you had a tattoo or piercing recently?',
       'recent_donation' => 'Have you donated blood within the last 3 months?',
      ];
     ?>
     <div class="row g-3">
      <?php $__currentLoopData = $medicalQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
       <div class="col-md-6">
        <div class="p-3 rounded-4 h-100" style="background:#fff;border:1px solid #e8edf4;">
         <div class="fw-semibold mb-2"><?php echo e($question); ?></div>
         <div class="d-flex gap-2">
          <input type="radio" class="btn-check" name="medical_history[<?php echo e($key); ?>]" id="mh_<?php echo e($key); ?>_yes" value="yes" <?php echo e(old('medical_history.'.$key)==='yes' ? 'checked' : ''); ?> required>
          <label class="btn btn-outline-danger rounded-pill px-4" for="mh_<?php echo e($key); ?>_yes">Yes</label>
          <input type="radio" class="btn-check" name="medical_history[<?php echo e($key); ?>]" id="mh_<?php echo e($key); ?>_no" value="no" <?php echo e(old('medical_history.'.$key)==='no' ? 'checked' : ''); ?> required>
          <label class="btn btn-outline-success rounded-pill px-4" for="mh_<?php echo e($key); ?>_no">No</label>
         </div>
        </div>
       </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
     </div>
     <div class="small text-secondary mt-3"><i class="bi bi-shield-check text-success me-1"></i>Medical history is for the Donor profile only.</div>
    </div>
   </div></div>
   <div class="col-md-6"><label class="form-label fw-bold">Password</label><div class="position-relative"><input id="bnDonorPass" type="password" name="password" class="form-control bn-input pe-5" placeholder="Create a password" required><button type="button" class="btn position-absolute top-50 end-0 translate-middle-y border-0 text-secondary" onclick="bnTogglePassword('bnDonorPass',this)"><i class="bi bi-eye"></i></button></div></div>
   <div class="col-md-6"><label class="form-label fw-bold">Confirm Password</label><input type="password" name="password_confirmation" class="form-control bn-input" placeholder="Confirm your password" required></div>
  </div>
  <div class="bn-trust mt-4"><i class="bi bi-shield-check text-danger me-1"></i><strong>Account setup is complete after registration.</strong> You can login immediately using your email and password.</div>
  <button type="submit" class="btn btn-danger w-100 bn-submit bn-ripple mt-4"><i class="bi bi-heart-pulse-fill me-2"></i>Create Donor Account</button>
 </form>
 <div class="text-center mt-4 pt-3 border-top text-secondary small">Already have an account? <a href="<?php echo e(route('login')); ?>" class="bn-link">Login here</a> <span class="mx-1">·</span> Need blood? <a href="<?php echo e(route('register.user')); ?>" class="bn-link">Create Blood Need Account</a></div>
 </div></div></div></div>
<script>function bnTogglePassword(id,btn){const input=document.getElementById(id),icon=btn.querySelector('i');if(input.type==='password'){input.type='text';icon.className='bi bi-eye-slash'}else{input.type='password';icon.className='bi bi-eye'}}</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\BloodNexus\resources\views/register-donor.blade.php ENDPATH**/ ?>