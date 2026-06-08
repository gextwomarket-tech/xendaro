<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Moon Trade — Connexion</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --dark: #2C3947;
      --cream: #EFE3CA;
      --accent: #547A95;
      --accent-light: #7BA3BD;
      --gold: #C9A84C;
      --gold-light: #E2C97E;
      --text-muted: #74777c;
    }

    body {
      font-family: 'Manrope', sans-serif;
      background: #D8CDB5;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      background-image:
        radial-gradient(ellipse at 20% 50%, rgba(84, 122, 149, .15) 0%, transparent 60%),
        radial-gradient(ellipse at 80% 20%, rgba(201, 168, 76, .1) 0%, transparent 50%);
    }

    /* ── OUTER CARD ── */
    .outer-card {
      width: 100%;
      max-width: 1040px;
      background: #fff;
      border-radius: 24px;
      box-shadow: 0 20px 80px rgba(44, 57, 71, .18), 0 4px 16px rgba(44, 57, 71, .08);
      overflow: hidden;
      display: grid;
      grid-template-columns: 1fr 1fr;
      min-height: 620px;
      animation: cardIn .6s cubic-bezier(.22, .68, 0, 1.2) both;
    }

    @keyframes cardIn {
      from {
        opacity: 0;
        transform: translateY(28px) scale(.97);
      }

      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    /* ── LEFT PANEL ── */
    .left-panel {
      position: relative;
      overflow: hidden;
      background: var(--dark);
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 36px;
    }

    .left-panel::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, #1a2533 0%, #2C3947 40%, #1e3a52 100%);
    }

    .orb {
      position: absolute;
      border-radius: 50%;
      filter: blur(60px);
      opacity: .35;
      animation: drift 8s ease-in-out infinite alternate;
    }

    .orb-1 {
      width: 280px;
      height: 280px;
      background: var(--accent);
      top: -80px;
      left: -80px;
      animation-delay: 0s;
    }

    .orb-2 {
      width: 200px;
      height: 200px;
      background: var(--gold);
      bottom: 60px;
      right: -60px;
      animation-delay: -3s;
    }

    .orb-3 {
      width: 150px;
      height: 150px;
      background: #547A95;
      top: 50%;
      right: 20px;
      animation-delay: -6s;
    }

    @keyframes drift {
      from {
        transform: translate(0, 0) scale(1);
      }

      to {
        transform: translate(20px, 30px) scale(1.1);
      }
    }

    .chart-svg {
      position: absolute;
      bottom: 180px;
      left: 0;
      right: 0;
      opacity: .18;
    }

    .chart-line-path {
      stroke-dasharray: 800;
      stroke-dashoffset: -800;
      stroke: var(--gold);
      stroke-width: 2;
      animation: drawLine 12s linear infinite;
    }

    @keyframes drawLine {
      0% {
        stroke-dashoffset: -800;
      }

      100% {
        stroke-dashoffset: 0;
      }
    }

    .left-content {
      position: relative;
      z-index: 10;
      color: #fff;
    }

    .left-content h1 {
      font-size: 38px;
      font-weight: 800;
      line-height: 1.2;
      margin-bottom: 16px;
      letter-spacing: -1px;
    }

    .left-content p {
      font-size: 16px;
      line-height: 1.7;
      opacity: .85;
      margin-bottom: 28px;
    }

    .feature-list {
      display: grid;
      gap: 12px;
    }

    .feature {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      opacity: .9;
    }

    .feature-icon {
      width: 24px;
      height: 24px;
      border-radius: 6px;
      background: rgba(201, 168, 76, .2);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--gold);
      flex-shrink: 0;
    }

    /* ── RIGHT PANEL ── */
    .right-panel {
      padding: 48px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .form-header {
      margin-bottom: 32px;
    }

    .form-header h2 {
      font-size: 28px;
      font-weight: 800;
      color: var(--dark);
      margin-bottom: 8px;
    }

    .form-header p {
      font-size: 15px;
      color: var(--text-muted);
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-size: 14px;
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 8px;
    }

    .form-control {
      width: 100%;
      padding: 12px 14px;
      border: 1.5px solid #DED2B6;
      border-radius: 8px;
      background: #fff;
      font-size: 15px;
      color: var(--dark);
      font-family: 'Manrope', sans-serif;
      outline: none;
      transition: border-color .2s;
      box-sizing: border-box;
    }

    .form-control:focus {
      border-color: var(--accent);
    }

    .form-control::placeholder {
      color: #9ca3af;
    }

    .btn-primary {
      width: 100%;
      padding: 13px;
      background: var(--dark);
      color: var(--cream);
      border-radius: 8px;
      font-weight: 700;
      font-size: 15px;
      border: none;
      cursor: pointer;
      transition: all .2s;
      font-family: 'Manrope', sans-serif;
    }

    .btn-primary:hover {
      background: var(--accent);
      transform: translateY(-1px);
      box-shadow: 0 8px 20px rgba(84, 122, 149, .25);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    .remember-forgot {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .remember-forgot a {
      color: var(--accent);
      text-decoration: none;
      font-weight: 600;
      transition: color .2s;
    }

    .remember-forgot a:hover {
      color: var(--accent-light);
    }

    .checkbox-wrapper {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .form-divider {
      text-align: center;
      margin: 24px 0;
      position: relative;
      font-size: 14px;
      color: var(--text-muted);
    }

    .form-divider::before,
    .form-divider::after {
      content: '';
      position: absolute;
      top: 50%;
      width: calc(50% - 30px);
      height: 1px;
      background: #e5e7eb;
    }

    .form-divider::before {
      left: 0;
    }

    .form-divider::after {
      right: 0;
    }

    .signup-link {
      text-align: center;
      margin-top: 24px;
      font-size: 14px;
      color: var(--text-muted);
    }

    .signup-link a {
      color: var(--accent);
      text-decoration: none;
      font-weight: 700;
      transition: color .2s;
    }

    .signup-link a:hover {
      color: var(--accent-light);
    }

    .alert {
      padding: 12px 14px;
      border-radius: 8px;
      font-size: 14px;
      margin-bottom: 16px;
      animation: slideIn .3s ease;
    }

    .alert-error {
      background: #fef2f2;
      border: 1px solid #fecaca;
      color: #dc2626;
    }

    .alert-success {
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      color: #16a34a;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 1024px) {
      body {
        padding: 16px;
      }

      .outer-card {
        max-width: 100%;
        min-height: auto;
      }
    }

    @media (max-width: 768px) {
      .outer-card {
        grid-template-columns: 1fr;
        min-height: auto;
        border-radius: 16px;
      }

      .left-panel {
        padding: 20px;
        min-height: auto;
        display: none;
      }

      .right-panel {
        padding: 24px 20px;
      }

      .left-content h1 {
        font-size: 28px;
      }

      .chart-svg {
        display: none;
      }

      .form-header h2 {
        font-size: 24px;
      }

      .form-header p {
        font-size: 14px;
      }

      .form-control {
        font-size: 16px;
        padding: 12px 12px;
      }

      .btn-primary {
        padding: 12px;
        font-size: 14px;
      }

      .left-content p {
        font-size: 14px;
      }
    }

    @media (max-width: 480px) {
      body {
        padding: 12px;
        min-height: 100dvh;
      }

      .outer-card {
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(44, 57, 71, .1), 0 2px 8px rgba(44, 57, 71, .05);
      }

      .right-panel {
        padding: 20px 16px;
      }

      .form-header {
        margin-bottom: 24px;
      }

      .form-header h2 {
        font-size: 22px;
        margin-bottom: 6px;
      }

      .form-header p {
        font-size: 13px;
      }

      .form-group {
        margin-bottom: 16px;
      }

      .form-group label {
        font-size: 13px;
        margin-bottom: 6px;
      }

      .form-control {
        font-size: 16px;
        padding: 11px 12px;
        border-radius: 6px;
      }

      .remember-forgot {
        margin-bottom: 16px;
        font-size: 13px;
      }

      .btn-primary {
        padding: 11px;
        font-size: 13px;
      }

      .form-divider {
        margin: 20px 0;
        font-size: 13px;
      }

      .signup-link {
        margin-top: 20px;
        font-size: 13px;
      }

      .alert {
        font-size: 13px;
        padding: 10px 12px;
        margin-bottom: 12px;
      }
    }
  </style>
</head>

<body>

  <div class="outer-card">
    <!-- LEFT PANEL -->
    <div class="left-panel">
      <div class="orb orb-1"></div>
      <div class="orb orb-2"></div>
      <div class="orb orb-3"></div>

      <svg class="chart-svg" viewBox="0 0 300 100" preserveAspectRatio="none">
        <path class="chart-line-path" d="M 0,80 Q 75,60 150,50 T 300,40" fill="none" />
      </svg>

      <img src="{{ asset('auth.png') }}" alt="Authentication" style="width: calc(100% - 72px); height: 200px; object-fit: cover; border-radius: 24px; margin-bottom: 20px; display: block;" />

      <div class="left-content">
        <h1>Bienvenue !</h1>
        <p>Accédez à votre plateforme de trading avancée et commencez à trader en quelques secondes.</p>
        <div class="feature-list">
          <div class="feature">
            <div class="feature-icon"><i class="fas fa-bolt"></i></div>
            <span>Exécution instantanée</span>
          </div>
          <div class="feature">
            <div class="feature-icon"><i class="fas fa-lock"></i></div>
            <span>Sécurité renforcée</span>
          </div>
          <div class="feature">
            <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
            <span>Outils avancés</span>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right-panel">
      <div class="form-header">
        <h2>Connexion</h2>
        <p>Connectez-vous à votre compte Brocker</p>
      </div>

      @if ($errors->any())
        <div class="alert alert-error">
          <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <form method="POST" action="{{ route('auth.login.post') }}" id="loginForm">
        @csrf

        <div class="form-group">
          <label for="email">Adresse email</label>
          <input 
            type="email" 
            id="email"
            name="email" 
            class="form-control @error('email') border-red-500 @enderror"
            placeholder="votre@email.com"
            value="{{ old('email') }}"
            required
          />
        </div>

        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input 
            type="password" 
            id="password"
            name="password" 
            class="form-control @error('password') border-red-500 @enderror"
            placeholder="••••••••"
            required
          />
        </div>

        <div class="remember-forgot">
          <div class="checkbox-wrapper">
            <input type="checkbox" id="remember" name="remember" />
            <label for="remember" style="margin: 0; font-weight: 500;">Se souvenir de moi</label>
          </div>
          <a href="{{ route('auth.forgot-password') }}">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn-primary">Connexion</button>
      </form>

      <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
          const email = document.getElementById('email').value;
          const remember = document.getElementById('remember').checked;
          
          if (email) {
            localStorage.setItem('moonTrade_email', email);
            localStorage.setItem('moonTrade_remember', remember);
            localStorage.setItem('moonTrade_lastLogin', new Date().toISOString());
          }
        });

        // Restore email if "remember me" was checked
        window.addEventListener('DOMContentLoaded', function() {
          const savedEmail = localStorage.getItem('moonTrade_email');
          const remember = localStorage.getItem('moonTrade_remember') === 'true';
          
          if (savedEmail && remember) {
            document.getElementById('email').value = savedEmail;
            document.getElementById('remember').checked = true;
          }
        });
      </script>

      <div class="form-divider">ou</div>

      <div class="signup-link">
        Pas encore de compte ? <a href="{{ route('auth.register') }}">Créer un compte</a>
      </div>
    </div>
  </div>

</body>

</html>
