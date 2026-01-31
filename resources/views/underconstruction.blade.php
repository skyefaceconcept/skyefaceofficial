<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? config('app.name') }} - Coming Soon</title>
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
          radial-gradient(circle at 15% 40%, rgba(102, 126, 234, 0.08) 0%, transparent 40%),
          radial-gradient(circle at 85% 60%, rgba(102, 126, 234, 0.06) 0%, transparent 35%),
          radial-gradient(circle at 50% 100%, rgba(118, 75, 162, 0.04) 0%, transparent 60%);
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

      .construction-icon {
        font-size: 100px;
        margin-bottom: 30px;
        display: inline-block;
        animation: launch-float 3s ease-in-out infinite;
        filter: drop-shadow(0 8px 16px rgba(102, 126, 234, 0.15));
      }

      @keyframes launch-float {
        0%, 100% {
          transform: translateY(0px) rotate(0deg);
        }
        50% {
          transform: translateY(-25px) rotate(-5deg);
        }
      }

      @keyframes pulse-glow {
        0%, 100% {
          box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7);
        }
        50% {
          box-shadow: 0 0 0 10px rgba(102, 126, 234, 0);
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

      .launch-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, #e0e7ff 0%, #ddd6fe 100%);
        border: 2px solid #c7d2fe;
        color: #4c1d95;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.12);
        transition: all 0.3s ease;
      }

      .launch-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.16);
      }

      .launch-badge::before {
        content: '●';
        font-size: 10px;
        animation: blink 2s infinite;
      }

      @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
      }

      .countdown-title {
        font-size: 13px;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 800;
        margin-bottom: 30px;
      }

      .countdown {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 50px;
      }

      .countdown-item {
        flex: 1;
        text-align: center;
        padding: 28px 18px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.06) 0%, rgba(118, 75, 162, 0.04) 100%);
        border: 1px solid rgba(102, 126, 234, 0.2);
        border-radius: 12px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
      }

      .countdown-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, #667eea, transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
      }

      .countdown-item:hover {
        transform: translateY(-6px);
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.12) 0%, rgba(118, 75, 162, 0.08) 100%);
        border-color: rgba(102, 126, 234, 0.4);
        box-shadow: 0 12px 40px rgba(102, 126, 234, 0.15);
      }

      .countdown-item:hover::before {
        opacity: 1;
      }

      .countdown-number {
        font-size: 48px;
        font-weight: 800;
        color: #667eea;
        line-height: 1;
        margin-bottom: 12px;
        font-family: 'Poppins', sans-serif;
      }

      .countdown-label {
        font-size: 12px;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
      }

      .message {
        font-size: 15px;
        color: #cbd5e1;
        margin-top: 50px;
        padding: 24px 28px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.06) 100%);
        border: 1px solid rgba(102, 126, 234, 0.2);
        border-top: 3px solid #667eea;
        border-radius: 12px;
        line-height: 1.8;
        font-weight: 400;
      }

      .social-links {
        margin-top: 50px;
        display: flex;
        justify-content: center;
        gap: 18px;
      }

      .social-links a {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.08) 100%);
        border: 1.5px solid rgba(102, 126, 234, 0.25);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
        text-decoration: none;
        font-weight: 700;
        font-size: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
      }

      .social-links a::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: -1;
      }

      .social-links a:hover {
        color: white;
        border-color: transparent;
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.25);
      }

      .social-links a:hover::before {
        opacity: 1;
      }

      .footer-text {
        margin-top: 50px;
        padding-top: 30px;
        border-top: 1px solid rgba(102, 126, 234, 0.15);
        font-size: 13px;
        color: #9ca3af;
        font-weight: 400;
        text-align: center;
        line-height: 1.8;
      }

      .footer-text a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border-bottom: 1.5px solid rgba(102, 126, 234, 0.3);
      }

      .footer-text a:hover {
        color: #764ba2;
        border-bottom-color: #764ba2;
      }

      @media (max-width: 768px) {
        h1 {
          font-size: 44px;
        }

        h1 strong {
          font-size: 52px;
        }

        .container {
          padding: 60px 30px;
          max-width: 90%;
        }

        .subtitle {
          font-size: 16px;
          margin-bottom: 40px;
        }

        .countdown {
          grid-template-columns: repeat(2, 1fr);
          gap: 14px;
        }

        .countdown-item {
          padding: 22px 16px;
        }

        .countdown-number {
          font-size: 40px;
        }

        .countdown-label {
          font-size: 11px;
        }

        .social-links a {
          width: 48px;
          height: 48px;
          font-size: 18px;
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
          padding: 50px 20px;
          max-width: 95%;
        }

        .construction-icon {
          font-size: 60px;
        }

        .subtitle {
          font-size: 15px;
          margin-bottom: 35px;
        }

        .countdown {
          gap: 10px;
        }

        .countdown-item {
          padding: 16px 12px;
        }

        .countdown-number {
          font-size: 32px;
        }

        .countdown-label {
          font-size: 10px;
        }

        .message {
          font-size: 14px;
          padding: 18px 20px;
          margin-top: 30px;
        }

        .social-links {
          gap: 12px;
          margin-top: 30px;
        }

        .social-links a {
          width: 44px;
          height: 44px;
          font-size: 16px;
        }

        .footer-text {
          font-size: 12px;
          margin-top: 30px;
        }
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="construction-icon">🚀</div>

      <div class="launch-badge">Exciting Launch Coming</div>

      <h1>
        Coming Soon
        <strong>{{ $title ?? config('app.name') }}</strong>
      </h1>

      <p class="subtitle">
        {{ $message ?? 'We\'re crafting something exceptional. Get ready for a transformative digital experience that will redefine your expectations.' }}
      </p>

      <div class="countdown-title">Launching in</div>

      <div class="countdown">
        <div class="countdown-item">
          <div class="countdown-number" id="days">00</div>
          <div class="countdown-label">Days</div>
        </div>
        <div class="countdown-item">
          <div class="countdown-number" id="hours">00</div>
          <div class="countdown-label">Hours</div>
        </div>
        <div class="countdown-item">
          <div class="countdown-number" id="minutes">00</div>
          <div class="countdown-label">Minutes</div>
        </div>
        <div class="countdown-item">
          <div class="countdown-number" id="seconds">00</div>
          <div class="countdown-label">Seconds</div>
        </div>
      </div>

      <div class="message">
        <p>📧 Stay updated with our latest news and exclusive launch details</p>
      </div>

      <div class="social-links">
        <a href="https://facebook.com/skyefacedigital" target="_blank" rel="noopener noreferrer" title="Facebook" aria-label="Follow us on Facebook">f</a>
        <a href="#" title="Twitter" aria-label="Follow us on Twitter">𝕏</a>
        <a href="#" title="Instagram" aria-label="Follow us on Instagram">📷</a>
        <a href="#" title="LinkedIn" aria-label="Connect on LinkedIn">in</a>
      </div>

      <div class="footer-text">
        Questions? <a href="mailto:{{ config('app.support_email', 'support@example.com') }}">Contact us</a> or follow our journey
      </div>
    </div>

    <script>
      function updateCountdown() {
        // Target date: February 2, 2026 at 00:00:00
        const targetDate = new Date("2026-02-02T00:00:00").getTime();

        // Get current date and time
        const now = new Date().getTime();

        // Calculate time difference
        const difference = targetDate - now;

        // Calculate time units
        const days = Math.floor(difference / (1000 * 60 * 60 * 24));
        const hours = Math.floor(
          (difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
        );
        const minutes = Math.floor(
          (difference % (1000 * 60 * 60)) / (1000 * 60)
        );
        const seconds = Math.floor((difference % (1000 * 60)) / 1000);

        // Update the DOM
        document.getElementById("days").textContent = String(days).padStart(
          2,
          "0"
        );
        document.getElementById("hours").textContent = String(hours).padStart(
          2,
          "0"
        );
        document.getElementById("minutes").textContent = String(
          minutes
        ).padStart(2, "0");
        document.getElementById("seconds").textContent = String(
          seconds
        ).padStart(2, "0");

        // If countdown is finished
        if (difference < 0) {
          document.querySelector(".countdown-title").textContent =
            "🎉 We're Live!";
          document
            .querySelectorAll(".countdown-number")
            .forEach((el) => (el.textContent = "00"));
        }
      }

      // Update countdown immediately
      updateCountdown();

      // Update countdown every second
      setInterval(updateCountdown, 1000);
    </script>
  </body>
</html>
