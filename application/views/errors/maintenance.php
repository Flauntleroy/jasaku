<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Maintenance | Jasa-Ku</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    :root {
      --bg-primary: #0a0e1a;
      --bg-secondary: #0f172a;
      --bg-card: #1a1f35;
      --text-primary: #ffffff;
      --text-secondary: #94a3b8;
      --accent: #fbbf24;
      --accent-glow: rgba(251, 191, 36, 0.2);
      --primary: #3b82f6;
      --primary-hover: #2563eb;
      --border: rgba(255, 255, 255, 0.1);
    }
    html, body {
      height: 100%;
    }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 50%, var(--bg-primary) 100%);
      color: var(--text-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      overflow: hidden;
      position: relative;
    }
    body::before {
      content: '';
      position: absolute;
      width: 800px;
      height: 800px;
      background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
      top: -400px;
      left: -400px;
      animation: glow 20s ease-in-out infinite;
    }
    body::after {
      content: '';
      position: absolute;
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, rgba(251, 191, 36, 0.1) 0%, transparent 70%);
      bottom: -300px;
      right: -300px;
      animation: glow 15s ease-in-out infinite reverse;
    }
    @keyframes glow {
      0%, 100% { transform: translate(0, 0) scale(1); }
      50% { transform: translate(50px, 50px) scale(1.1); }
    }
    .container {
      max-width: 900px;
      width: 100%;
      position: relative;
      z-index: 1;
    }
    .card {
      background: var(--bg-card);
      border-radius: 24px;
      padding: 48px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5),
                  0 0 0 1px rgba(255, 255, 255, 0.05) inset;
      backdrop-filter: blur(20px);
      border: 1px solid var(--border);
    }
    .header {
      text-align: center;
      margin-bottom: 48px;
    }
    .icon-wrapper {
      width: 300px;
      height: 300px;
      margin: 0 auto 24px;
      animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-15px); }
    }
    .title {
      font-size: 32px;
      font-weight: 800;
      letter-spacing: -0.02em;
      margin-bottom: 16px;
      background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .description {
      font-size: 16px;
      line-height: 1.6;
      color: var(--text-secondary);
      max-width: 600px;
      margin: 0 auto;
    }
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 18px;
      background: rgba(251, 191, 36, 0.15);
      border: 2px solid rgba(251, 191, 36, 0.3);
      border-radius: 100px;
      color: var(--accent);
      font-size: 14px;
      font-weight: 700;
      margin-bottom: 40px;
      box-shadow: 0 0 30px var(--accent-glow);
    }
    .badge svg {
      animation: pulse 2s ease-in-out infinite;
    }
    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.1); opacity: 0.8; }
    }
    .countdown {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 32px;
      flex-wrap: wrap;
    }
    .time-unit {
      text-align: center;
      min-width: 100px;
    }
    .time-box {
      background: linear-gradient(180deg, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0.02) 100%);
      border: 2px solid rgba(255,255,255,0.1);
      border-radius: 16px;
      padding: 20px;
      margin-bottom: 12px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }
    .time-box::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    }
    .time-value {
      font-size: 56px;
      font-weight: 900;
      font-family: 'SF Mono', 'Monaco', 'Courier New', monospace;
      line-height: 1;
      background: linear-gradient(180deg, #ffffff 0%, #e2e8f0 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      text-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    .time-label {
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: var(--text-secondary);
      font-weight: 600;
    }
    .separator {
      font-size: 48px;
      font-weight: 700;
      color: var(--accent);
      align-self: center;
      margin-top: -20px;
      text-shadow: 0 0 20px var(--accent-glow);
    }
    .end-time {
      text-align: center;
      font-size: 14px;
      color: var(--text-secondary);
      margin-bottom: 32px;
    }
    .actions {
      display: flex;
      justify-content: center;
      gap: 16px;
      align-items: center;
      flex-wrap: wrap;
    }
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 14px 28px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
      color: white;
      text-decoration: none;
      border-radius: 12px;
      font-weight: 700;
      font-size: 15px;
      border: 2px solid rgba(59, 130, 246, 0.3);
      box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    .btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s ease;
    }
    .btn:hover::before {
      left: 100%;
    }
    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 32px rgba(59, 130, 246, 0.5);
    }
    .btn:active {
      transform: translateY(0);
    }
    .note {
      color: var(--text-secondary);
      font-size: 14px;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .card {
        padding: 32px 24px;
      }
      .title {
        font-size: 24px;
      }
      .description {
        font-size: 14px;
      }
      .time-box {
        padding: 16px;
        min-width: 80px;
      }
      .time-value {
        font-size: 40px;
      }
      .separator {
        font-size: 32px;
        margin: 0 -8px;
      }
      .countdown {
        gap: 12px;
      }
      .icon-wrapper {
        width: 90px;
        height: 90px;
      }
    }
    @media (max-width: 480px) {
      .card {
        padding: 24px 16px;
      }
      .title {
        font-size: 20px;
      }
      .time-unit {
        min-width: 70px;
      }
      .time-box {
        padding: 12px;
      }
      .time-value {
        font-size: 32px;
      }
      .time-label {
        font-size: 10px;
      }
      .separator {
        display: none;
      }
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
      *,
      *::before,
      *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
      }
    }
  </style>
  <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>
</head>
<body>
  <div class="container">
    <div class="card">
      <div class="header">
        <div class="icon-wrapper">
          <dotlottie-wc 
            src="https://lottie.host/470cdbcf-1a4f-4634-8c2a-075be510990b/32kQlgYr13.lottie" 
            style="width: 300px; height: 300px"
            autoplay 
            loop>
          </dotlottie-wc>
        </div>
        <h1 class="title">Situs Sedang Dalam Perawatan</h1>
        <p class="description">
          Kami sedang melakukan pemeliharaan untuk meningkatkan performa dan keamanan sistem. 
          Terima kasih atas kesabaran Anda.
        </p>
      </div>

      <div style="text-align: center;">
        <div class="badge">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.5"/>
            <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Maintenance Mode Aktif
        </div>
      </div>

      <div class="countdown">
        <div class="time-unit">
          <div class="time-box">
            <div class="time-value" id="hours">00</div>
          </div>
          <div class="time-label">Jam</div>
        </div>
        
        <div class="separator">:</div>
        
        <div class="time-unit">
          <div class="time-box">
            <div class="time-value" id="minutes">00</div>
          </div>
          <div class="time-label">Menit</div>
        </div>
        
        <div class="separator">:</div>
        
        <div class="time-unit">
          <div class="time-box">
            <div class="time-value" id="seconds">00</div>
          </div>
          <div class="time-label">Detik</div>
        </div>
      </div>

      <div class="end-time" id="end-time">
        Berakhir pada: <strong id="end-date"></strong>
      </div>

      <!-- <div class="actions">
        <a href="/" class="btn">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M21 12a9 9 0 11-2.636-6.364M12 3v9l4 2" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Muat Ulang Halaman
        </a>
        <span class="note">Jika masalah berlanjut, hubungi admin</span>
      </div> -->
    </div>
  </div>

  <script>
    // Set countdown end time (2 hours from now)
    const endTime = new Date(Date.now() + 2 * 60 * 60 * 1000);
    
    // Display end time
    const options = { 
      day: '2-digit', 
      month: 'long', 
      year: 'numeric', 
      hour: '2-digit', 
      minute: '2-digit' 
    };
    document.getElementById('end-date').textContent = endTime.toLocaleString('id-ID', options);

    // Countdown function
    function updateCountdown() {
      const now = new Date().getTime();
      const distance = endTime - now;

      if (distance < 0) {
        document.getElementById('hours').textContent = '00';
        document.getElementById('minutes').textContent = '00';
        document.getElementById('seconds').textContent = '00';
        setTimeout(() => window.location.reload(), 1000);
        return;
      }

      const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((distance % (1000 * 60)) / 1000);

      document.getElementById('hours').textContent = String(hours).padStart(2, '0');
      document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
      document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
    }

    // Update every second
    updateCountdown();
    setInterval(updateCountdown, 1000);
  </script>
</body>
</html>