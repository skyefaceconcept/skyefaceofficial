<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo e($title ?? config('app.name')); ?> - Scheduled Maintenance</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 50%, #0f1729 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 20px;
      position: relative;
      overflow-x: hidden;
      color: #1a202c;
    }

    /* Sophisticated animated background */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background:
        radial-gradient(circle at 15% 40%, rgba(239, 68, 68, 0.06) 0%, transparent 40%),
        radial-gradient(circle at 85% 60%, rgba(239, 68, 68, 0.04) 0%, transparent 35%),
        radial-gradient(circle at 50% 100%, rgba(59, 130, 246, 0.03) 0%, transparent 60%);
      pointer-events: none;
      z-index: 0;
    }

    /* Floating particles effect */
    body::after {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image:
        radial-gradient(2px 2px at 20% 30%, rgba(255,255,255,0.02), rgba(255,255,255,0)),
        radial-gradient(2px 2px at 60% 70%, rgba(255,255,255,0.02), rgba(255,255,255,0)),
        radial-gradient(1px 1px at 50% 50%, rgba(255,255,255,0.02), rgba(255,255,255,0)),
        radial-gradient(1px 1px at 80% 10%, rgba(255,255,255,0.02), rgba(255,255,255,0)),
        radial-gradient(2px 2px at 90% 60%, rgba(255,255,255,0.02), rgba(255,255,255,0));
      background-repeat: repeat;
      background-size: 200% 200%, 150% 150%, 250% 250%, 200% 200%, 150% 150%;
      pointer-events: none;
      z-index: 0;
      animation: particles 20s ease-in-out infinite;
    }

    @keyframes particles {
      0%, 100% {
        background-position: 0 0, 0 0, 0 0, 0 0, 0 0;
      }
      50% {
        background-position: 100px 100px, -50px 50px, 150px -50px, 100px -100px, -100px 50px;
      }
    }

    .container {
      text-align: center;
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      padding: 100px 60px;
      border-radius: 32px;
      box-shadow:
        0 30px 100px rgba(0, 0, 0, 0.35),
        0 0 1px rgba(255, 255, 255, 0.5) inset;
      max-width: 800px;
      width: 100%;
      position: relative;
      z-index: 2;
      border: 1px solid rgba(255, 255, 255, 0.6);
    }

    .maintenance-icon {
      font-size: 100px;
      margin-bottom: 30px;
      display: inline-block;
      animation: spin-maintenance 3s ease-in-out infinite;
      filter: drop-shadow(0 8px 16px rgba(239, 68, 68, 0.15));
    }

    @keyframes spin-maintenance {
      0%, 100% {
        transform: rotate(0deg) scale(1);
      }
      50% {
        transform: rotate(15deg) scale(1.05);
      }
    }

    h1 {
      font-family: "Poppins", sans-serif;
      font-size: 60px;
      font-weight: 800;
      color: #0a0e27;
      margin-bottom: 12px;
      letter-spacing: -1.5px;
      line-height: 1.1;
    }

    h1 strong {
      background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      display: block;
      margin-top: 10px;
      font-size: 68px;
      font-weight: 800;
    }

    .subtitle {
      font-size: 18px;
      color: #475569;
      margin-bottom: 50px;
      line-height: 1.85;
      font-weight: 400;
      max-width: 580px;
      margin-left: auto;
      margin-right: auto;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 20px;
      background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
      border: 2px solid #fca5a5;
      color: #7f1d1d;
      border-radius: 50px;
      font-size: 13px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.2px;
      margin-bottom: 30px;
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.12);
      transition: all 0.3s ease;
    }

    .status-badge:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(239, 68, 68, 0.16);
    }

    .status-badge::before {
      content: '●';
      font-size: 10px;
      animation: blink 2s infinite;
    }

    @keyframes blink {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.4; }
    }

    .message-box {
      background: linear-gradient(135deg, rgba(254, 242, 242, 0.5) 0%, rgba(241, 245, 249, 0.5) 100%);
      border: 2px solid rgba(254, 202, 202, 0.5);
      padding: 40px 35px;
      border-radius: 16px;
      margin: 50px 0;
      position: relative;
      overflow: hidden;
    }

    .message-box::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      height: 4px;
      width: 100%;
      background: linear-gradient(90deg, #dc2626 0%, #991b1b 100%);
    }

    .message-box p {
      font-size: 16px;
      color: #374151;
      font-weight: 500;
      margin: 0;
      position: relative;
      z-index: 1;
    }

    .progress-section {
      margin: 50px 0;
    }

    .progress-title {
      font-size: 13px;
      color: #6b7280;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .progress-bar {
      width: 100%;
      height: 6px;
      background: #e5e7eb;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .progress-fill {
      height: 100%;
      width: 65%;
      background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%);
      border-radius: 10px;
      animation: progress-animation 2s ease-in-out infinite;
    }

    @keyframes progress-animation {
      0%, 100% { width: 65%; }
      50% { width: 75%; }
    }

    .info-section {
      margin-top: 50px;
      padding-top: 50px;
      border-top: 1px solid #e5e7eb;
    }

    .info-section h3 {
      font-size: 12px;
      color: #9ca3af;
      text-transform: uppercase;
      letter-spacing: 2px;
      font-weight: 800;
      margin-bottom: 18px;
    }

    .contact-link {
      font-size: 15px;
      color: #4b5563;
      font-weight: 400;
    }

    .contact-link a {
      color: #dc2626;
      text-decoration: none;
      font-weight: 700;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      padding-bottom: 2px;
      border-bottom: 2px solid transparent;
    }

    .contact-link a:hover {
      color: #991b1b;
      border-bottom-color: #991b1b;
    }

    .status-indicators {
      display: flex;
      justify-content: center;
      gap: 40px;
      margin-top: 50px;
      flex-wrap: wrap;
    }

    .status-item {
      text-align: center;
      padding: 24px;
      border-radius: 14px;
      background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
      flex: 1;
      min-width: 140px;
      max-width: 160px;
      border: 1px solid #e5e7eb;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .status-item:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
      border-color: #d1d5db;
    }

    .status-item-icon {
      font-size: 36px;
      margin-bottom: 12px;
      display: block;
    }

    .status-item-label {
      font-size: 12px;
      color: #6b7280;
      text-transform: uppercase;
      letter-spacing: 1.2px;
      font-weight: 700;
    }

    .logo-section {
      margin-bottom: 25px;
    }

    .logo-section img {
      height: 48px;
      max-width: 180px;
      opacity: 0.95;
      transition: opacity 0.3s ease;
    }

    .logo-section img:hover {
      opacity: 1;
    }

    @media (max-width: 768px) {
      h1 {
        font-size: 44px;
      }

      h1 strong {
        font-size: 52px;
      }

      .container {
        padding: 70px 35px;
      }

      .subtitle {
        font-size: 16px;
      }

      .status-indicators {
        gap: 20px;
      }

      .status-item {
        min-width: 120px;
      }

      .message-box {
        padding: 28px 24px;
      }

      .maintenance-icon {
        font-size: 80px;
      }
    }

    @media (max-width: 480px) {
      h1 {
        font-size: 32px;
      }

      h1 strong {
        font-size: 40px;
      }

      .container {
        padding: 50px 24px;
      }

      .maintenance-icon {
        font-size: 70px;
        margin-bottom: 20px;
      }

      .subtitle {
        font-size: 15px;
        margin-bottom: 35px;
      }

      .status-indicators {
        gap: 15px;
        flex-direction: column;
      }

      .status-item {
        width: 100%;
        max-width: none;
      }

      .status-item-icon {
        font-size: 32px;
      }

      .progress-bar {
        height: 5px;
      }

      .message-box {
        padding: 20px 18px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo-section">
      <img src="/assets/branding/logo.png" alt="Logo" onerror="this.style.display='none'">
    </div>

    <div class="maintenance-icon">⚙️</div>

    <div class="status-badge">Scheduled Maintenance in Progress</div>

    <h1>
      We'll Be Back Shortly
      <strong><?php echo e($title ?? config('app.name')); ?></strong>
    </h1>

    <p class="subtitle">
      <?php echo e($message ?? 'We\'re performing critical system upgrades to enhance performance, security, and your overall experience. We apologize for the temporary inconvenience.'); ?>

    </p>

    <div class="progress-section">
      <div class="progress-title">Maintenance Progress</div>
      <div class="progress-bar">
        <div class="progress-fill"></div>
      </div>
    </div>

    <div class="message-box">
      <p>✨ We're committed to delivering you an even better experience. Your patience is greatly appreciated!</p>
    </div>

    <div class="status-indicators">
      <div class="status-item">
        <span class="status-item-icon">🔄</span>
        <div class="status-item-label">System Upgrade</div>
      </div>
      <div class="status-item">
        <span class="status-item-icon">⚡</span>
        <div class="status-item-label">Performance Boost</div>
      </div>
      <div class="status-item">
        <span class="status-item-icon">🔒</span>
        <div class="status-item-label">Enhanced Security</div>
      </div>
    </div>

    <div class="info-section">
      <h3>Questions or Urgent Need?</h3>
      <div class="contact-link">
        Contact our support team at <a href="mailto:<?php echo e(config('app.support_email', 'support@example.com')); ?>"><?php echo e(config('app.support_email', 'support@example.com')); ?></a> or call <strong>+234 806 212 1410</strong>
        — <a href="#" id="admin-login-link" style="color:#991b1b; font-weight:700; text-decoration:underline; margin-left:8px;">Admin Login</a>
      </div>
    </div>

    <!-- Admin login modal (SuperAdmin only during maintenance) -->
    <style>
      .sf-modal-backdrop { position: fixed; inset: 0; background: rgba(2,6,23,0.6); display: none; align-items: center; justify-content: center; z-index: 9999; }
      .sf-modal { background: white; border-radius: 12px; padding: 28px; max-width: 420px; width: 95%; box-shadow: 0 30px 80px rgba(2,6,23,0.55); }
      .sf-modal h3 { margin-bottom: 12px; font-family: 'Poppins', sans-serif; }
      .sf-modal .field { margin-bottom: 12px; }
      .sf-modal input { width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e6e9f2; }
      .sf-modal .actions { display:flex; gap:10px; justify-content:flex-end; margin-top:12px; }
      .sf-btn { background: linear-gradient(135deg,#dc2626,#991b1b); color:white; padding:10px 14px; border-radius:8px; border:none; cursor:pointer; }
      .sf-btn.ghost { background:transparent; color:#475569; border:1px solid #e6e9f2; }
      .sf-error { color: #b91c1c; font-size: 13px; margin-bottom:8px; }
    </style>

    <div id="sf-admin-login" class="sf-modal-backdrop" role="dialog" aria-modal="true" aria-hidden="true">
      <div class="sf-modal" role="document">
        <h3>Admin Login</h3>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors && $errors->any()): ?>
          <div class="sf-error"><?php echo e($errors->first()); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>">
          <?php echo csrf_field(); ?>

          <div class="field">
            <label for="login" style="display:block; font-size:13px; margin-bottom:6px;">Email or Username</label>
            <input id="login" name="username" type="text" autofocus value="<?php echo e(old('username')); ?>" autocomplete="username" />
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="sf-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>

          <div class="field">
            <label for="password" style="display:block; font-size:13px; margin-bottom:6px;">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" />
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="sf-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>

          <div class="actions">
            <button type="button" class="sf-btn ghost" id="sf-modal-close">Cancel</button>
            <button type="submit" class="sf-btn">Sign in</button>
          </div>
        </form>
      </div>
    </div>

    <script>
      (function(){
        const link = document.getElementById('admin-login-link');
        const modal = document.getElementById('sf-admin-login');
        const close = document.getElementById('sf-modal-close');

        function openModal(){ modal.style.display = 'flex'; modal.setAttribute('aria-hidden','false'); const first = modal.querySelector('input'); if(first) first.focus(); }
        function closeModal(){ modal.style.display = 'none'; modal.setAttribute('aria-hidden','true'); }

        if(link){ link.addEventListener('click', function(e){ e.preventDefault(); openModal(); }); }
        if(close){ close.addEventListener('click', closeModal); }
        modal.addEventListener('click', function(e){ if(e.target === modal) closeModal(); });

        <?php if(session()->has('errors') && session('errors')->any()): ?>
          openModal();
        <?php endif; ?>

      })();
    </script>
  </div>
</body>
</html>
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/maintenance.blade.php ENDPATH**/ ?>