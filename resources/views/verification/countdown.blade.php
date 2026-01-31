<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Verification Countdown</title>



















































</html>  </body>    </script>      const timer = setInterval(updateCountdown, 1000);      updateCountdown();      }        countdownEl.textContent = `${String(hours).padStart(2,'0')}:${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;        const seconds = Math.floor((distance % (1000 * 60)) / 1000);        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));        }          return;          clearInterval(timer);          verifyBtn.href = '#';          verifyBtn.classList.add('disabled');          countdownEl.textContent = 'Expired';        if (distance <= 0) {        const distance = expiresAt.getTime() - now;        const now = new Date().getTime();      function updateCountdown() {      verifyBtn.href = verifyUrl || '#';

      const verifyBtn = document.getElementById('verifyBtn');      const countdownEl = document.getElementById('countdown');      const verifyUrl = @json($verificationUrl);      const expiresAt = new Date(@json($expiresAt));      // Expects ISO timestamp in expiresAt and verificationUrl provided by server    <script>    </div>      <p class="muted">If the link expires, request a new verification email from your account page.</p>      <p><a id="verifyBtn" class="btn" href="#">Open verification link</a></p>      <p>Time remaining: <span id="countdown" class="countdown">--:--:--</span></p>      <p class="muted">This page shows the remaining time before your verification link expires.</p>      <h2>Verify Your Email Address</h2>    <div class="card">  <body>  </head>    </style>      .muted { color:#666; font-size:14px; }      .btn { display:inline-block; padding:12px 28px; background:#28a745; color:#fff; border-radius:6px; text-decoration:none; }      .countdown { font-size:28px; color:#d63384; font-weight:700; }      .card { max-width:600px; margin:40px auto; background:#fff; padding:30px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.08); }      body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#f5f7fa; }    <style>