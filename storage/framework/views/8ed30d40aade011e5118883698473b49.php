<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BloodNexus | Donation Certificate</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:radial-gradient(circle at 10% 0%,#fff0f2 0,transparent 30%),radial-gradient(circle at 90% 10%,#eef4ff 0,transparent 28%),#eef3f8;color:#12233d;font-family:Arial,Helvetica,sans-serif;padding:30px}
        .wrap{max-width:980px;margin:auto}.top{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}.brand{font-size:25px;font-weight:900;letter-spacing:-.5px}.brand small{display:block;font-size:10px;letter-spacing:1.5px;color:#718096;margin-top:3px}.brand span{color:#c91f32}.actions{display:flex;gap:10px}.btn{border:0;border-radius:999px;padding:11px 18px;text-decoration:none;font-weight:800;cursor:pointer}.back{background:#fff;color:#24364f;box-shadow:0 5px 18px #d9e0e8}.download{background:#c91f32;color:#fff;box-shadow:0 8px 22px rgba(201,31,50,.24)}
        .certificate{position:relative;background:#fff;min-height:650px;padding:48px 55px;border:3px solid #bd1d2e;box-shadow:0 24px 70px rgba(31,54,81,.16);overflow:hidden}.certificate:before{content:"";position:absolute;inset:10px;border:1px solid #df9ca4;pointer-events:none}.header{background:#122b49;color:#fff;padding:22px 25px;display:flex;justify-content:space-between;align-items:center}.header h1{margin:0;font-size:28px;letter-spacing:1px}.header small{display:block;color:#cbd8e7;margin-top:7px}.id{text-align:right;font-size:11px;color:#cbd8e7}.id strong{display:block;color:#fff;margin-top:5px}.title{text-align:center;margin:38px 0 24px}.title small{color:#c91f32;font-weight:800;letter-spacing:2px}.title h2{font-size:36px;margin:8px 0}.recipient{text-align:center}.recipient p{margin:0;color:#687587}.recipient h3{font-size:32px;margin:12px 0 8px}.line{width:45%;margin:0 auto;border-bottom:1px solid #ccd4df}.stats{display:grid;grid-template-columns:1fr 1fr;gap:15px;margin:28px 0}.stat{border:1px solid #e1e6ed;padding:17px;background:#f9fbfd}.label{font-size:11px;color:#788596;font-weight:800;letter-spacing:1px}.value{font-size:20px;font-weight:800;margin-top:7px}.details{display:grid;grid-template-columns:1fr 1fr;gap:30px;margin-top:20px}.detail h4{color:#c91f32;margin:0 0 14px;font-size:12px;letter-spacing:1px}.row{display:flex;justify-content:space-between;border-bottom:1px solid #edf0f4;padding:8px 0;gap:15px}.row span:first-child{color:#748093}.row span:last-child{font-weight:700;text-align:right}.verify{margin-top:28px;border:1px solid #cfe5d9;background:#f5fbf7;padding:15px;display:flex;align-items:center;justify-content:space-between}.verify strong{color:#167544}.seal{width:62px;height:62px;border:2px solid #c91f32;border-radius:50%;display:grid;place-items:center;color:#c91f32;font-weight:900;font-size:10px;text-align:center}.footer{text-align:center;margin-top:20px;color:#7b8796;font-size:11px}.footer b{color:#c91f32}@media(max-width:700px){body{padding:12px}.top,.header,.verify{flex-direction:column;gap:12px;align-items:flex-start}.actions{width:100%}.actions .btn{flex:1;text-align:center}.certificate{padding:25px 20px}.stats,.details{grid-template-columns:1fr}.title h2{font-size:28px}.recipient h3{font-size:25px}.id{text-align:left}}
    </style>
</head>
<body>
<div class="wrap">
    <div class="top">
        <div class="brand"><span>♥</span> BloodNexus<small>DONOR CERTIFICATE CENTER</small></div>
        <div class="actions">
            <a class="btn back" href="<?php echo e(route('donor.requests')); ?>">← Back</a>
            <a class="btn download" href="<?php echo e(route('donor.certificate.download', $bloodRequest->id)); ?>">⬇ Download PDF</a>
        </div>
    </div>

    <section class="certificate">
        <div class="header">
            <div><h1>BLOODNEXUS</h1><small>Blood Donation Network • Verified Donation Certificate</small></div>
            <div class="id">CERTIFICATE ID<strong>BNX-DON-<?php echo e(now()->format('Y')); ?>-<?php echo e(str_pad($bloodRequest->id, 6, '0', STR_PAD_LEFT)); ?></strong></div>
        </div>
        <div class="title"><small>RECOGNITION OF CONTRIBUTION</small><h2>DONATION CERTIFICATE</h2></div>
        <div class="recipient"><p>This certificate is proudly awarded to</p><h3><?php echo e($bloodRequest->donor?->name ?? 'BloodNexus Donor'); ?></h3><div class="line"></div></div>
        <div class="stats">
            <div class="stat"><div class="label">BLOOD GROUP</div><div class="value"><?php echo e($bloodRequest->donor?->blood_group ?? $bloodRequest->blood_group ?? 'N/A'); ?></div></div>
            <div class="stat"><div class="label">UNITS DONATED</div><div class="value"><?php echo e(max(1, (int)($bloodRequest->units_fulfilled ?: 1))); ?></div></div>
        </div>
        <div class="details">
            <div class="detail"><h4>DONATION DETAILS</h4><div class="row"><span>Date</span><span><?php echo e(optional($bloodRequest->donation_date)->format('d F Y') ?? optional($bloodRequest->completed_at)->format('d F Y') ?? optional($bloodRequest->updated_at)->format('d F Y')); ?></span></div><div class="row"><span>Time</span><span><?php echo e($bloodRequest->donation_time ? date('h:i A', strtotime($bloodRequest->donation_time)) : (optional($bloodRequest->completed_at)->format('h:i A') ?? '—')); ?></span></div><div class="row"><span>Request</span><span>#<?php echo e($bloodRequest->id); ?></span></div></div>
            <div class="detail"><h4>DONATION LOCATION</h4><div class="row"><span>Hospital</span><span><?php echo e($bloodRequest->hospital ?: 'BloodNexus Partner Hospital'); ?></span></div><div class="row"><span>Area / City</span><span><?php echo e($bloodRequest->area ? $bloodRequest->area.', ' : ''); ?><?php echo e($bloodRequest->city ?: 'N/A'); ?></span></div><div class="row"><span>Status</span><span style="color:#167544">VERIFIED • COMPLETED</span></div></div>
        </div>
        <div class="verify"><div><strong>✓ Certificate Verified</strong><br><small>This donation record has been completed and verified in the BloodNexus system.</small></div><div class="seal">VERIFIED<br>DONOR</div></div>
        <div class="footer">Every donation can become someone's second chance. <b>Thank you for donating blood.</b><br>BloodNexus • Keep this certificate as a record of your contribution.</div>
    </section>
</div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\BloodNexus\resources\views/donor-certificate.blade.php ENDPATH**/ ?>