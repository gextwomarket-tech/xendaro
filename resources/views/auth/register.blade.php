<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Moon Trade — Inscription</title>
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
      --green: #16a34a;
    }

    body {
      font-family: 'Manrope', sans-serif;
      background: #D8CDB5;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px 20px;
      background-image:
        radial-gradient(ellipse at 20% 50%, rgba(84, 122, 149, .15) 0%, transparent 60%),
        radial-gradient(ellipse at 80% 20%, rgba(201, 168, 76, .1) 0%, transparent 50%);
    }

    .outer-card {
      width: 100%;
      max-width: 1040px;
      background: #fff;
      border-radius: 24px;
      box-shadow: 0 20px 80px rgba(44, 57, 71, .18), 0 4px 16px rgba(44, 57, 71, .08);
      overflow: hidden;
      display: grid;
      grid-template-columns: 420px 1fr;
      min-height: auto;
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

    .left-panel {
      position: relative;
      overflow: hidden;
      background: var(--dark);
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 36px;
      min-height: 700px;
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

    .orb-1 { width: 280px; height: 280px; background: var(--accent); top: -80px; left: -80px; }
    .orb-2 { width: 200px; height: 200px; background: var(--gold); bottom: 60px; right: -60px; animation-delay: -3s; }
    .orb-3 { width: 150px; height: 150px; background: #547A95; top: 50%; right: 20px; animation-delay: -6s; }

    @keyframes drift {
      from { transform: translate(0, 0) scale(1); }
      to { transform: translate(20px, 30px) scale(1.1); }
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

    .step-indicator {
      display: flex;
      gap: 12px;
      margin-top: 32px;
      padding-top: 24px;
      border-top: 1px solid rgba(255, 255, 255, 0.15);
    }

    .step-dot {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 14px;
      color: #fff;
      border: 2px solid rgba(255, 255, 255, 0.3);
      transition: all 0.3s ease;
    }

    .step-dot.active {
      background: var(--gold);
      border-color: var(--gold-light);
      color: var(--dark);
    }

    .step-dot.completed {
      background: var(--green);
      border-color: var(--green);
    }

    .right-panel {
      padding: 48px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      overflow-y: auto;
      max-height: 700px;
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

    .form-step {
      display: none;
    }

    .form-step.active {
      display: block;
      animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
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
      box-shadow: 0 0 0 3px rgba(84, 122, 149, 0.1);
    }

    .form-control::placeholder {
      color: #9ca3af;
    }

    .two-col {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
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

    .btn-secondary {
      background: transparent;
      color: var(--accent);
      border: 1.5px solid var(--accent);
    }

    .btn-secondary:hover {
      background: rgba(84, 122, 149, 0.1);
    }

    .button-group {
      display: flex;
      gap: 12px;
      margin-top: 24px;
    }

    .button-group button {
      flex: 1;
    }

    .alert {
      padding: 12px 14px;
      border-radius: 10px;
      font-size: 13px;
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

    .login-link {
      text-align: center;
      margin-top: 20px;
      font-size: 13px;
      color: var(--text-muted);
    }

    .login-link a {
      color: var(--accent);
      text-decoration: none;
      font-weight: 700;
    }

    .login-link a:hover {
      color: var(--accent-light);
    }

    @keyframes slideIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
      .outer-card {
        grid-template-columns: 1fr;
        min-height: auto;
      }
      .left-panel {
        padding: 24px;
        min-height: 200px;
      }
      .right-panel {
        padding: 32px 24px;
        max-height: none;
      }
      .two-col {
        grid-template-columns: 1fr;
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

      <img src="{{ asset('auth.png') }}" alt="Authentication" style="width: calc(100% - 72px); height: 200px; object-fit: cover; border-radius: 24px; margin-bottom: 20px; display: block;" />

      <div class="left-content">
        <h1>Rejoignez-nous !</h1>
        <p>Créez un compte en quelques étapes et commencez à trader dès maintenant.</p>
        <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 20px;">
          <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; opacity: 0.9;">
            <div style="width: 24px; height: 24px; border-radius: 6px; background: rgba(201, 168, 76, .2); display: flex; align-items: center; justify-content: center; color: var(--gold); flex-shrink: 0;">🚀</div>
            <span>Inscription rapide en 2 étapes</span>
          </div>
          <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; opacity: 0.9;">
            <div style="width: 24px; height: 24px; border-radius: 6px; background: rgba(201, 168, 76, .2); display: flex; align-items: center; justify-content: center; color: var(--gold); flex-shrink: 0;">🔒</div>
            <span>Compte sécurisé</span>
          </div>
          <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; opacity: 0.9;">
            <div style="width: 24px; height: 24px; border-radius: 6px; background: rgba(201, 168, 76, .2); display: flex; align-items: center; justify-content: center; color: var(--gold); flex-shrink: 0;">🎁</div>
            <span>Bonus de bienvenue</span>
          </div>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator">
          <div class="step-dot active" id="step1-indicator">1</div>
          <div class="step-dot" id="step2-indicator">2</div>
        </div>
      </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right-panel">
      <div class="form-header">
        <h2>Créer un compte</h2>
        <p id="form-subtitle">Étape 1 : Informations personnelles</p>
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

      <form method="POST" action="{{ route('auth.register.post') }}" id="registerForm">
        @csrf

        <!-- ÉTAPE 1 : Infos Personnelles -->
        <div class="form-step active" id="step-1">
          <div class="two-col">
            <div class="form-group">
              <label for="first_name">Prénom *</label>
              <input 
                type="text" 
                id="first_name"
                name="first_name" 
                class="form-control @error('first_name') border-red-500 @enderror"
                placeholder="Jean"
                value="{{ old('first_name') }}"
                required
              />
            </div>

            <div class="form-group">
              <label for="last_name">Nom *</label>
              <input 
                type="text" 
                id="last_name"
                name="last_name" 
                class="form-control @error('last_name') border-red-500 @enderror"
                placeholder="Dupont"
                value="{{ old('last_name') }}"
                required
              />
            </div>
          </div>

          <div class="form-group">
            <label for="email">Email *</label>
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
            <label for="phone">Téléphone (optionnel)</label>
            <input 
              type="tel" 
              id="phone"
              name="phone" 
              class="form-control @error('phone') border-red-500 @enderror"
              placeholder="+33 6 12 34 56 78"
              value="{{ old('phone') }}"
            />
          </div>

          <div class="two-col">
            <div class="form-group">
              <label for="password">Mot de passe *</label>
              <input 
                type="password" 
                id="password"
                name="password" 
                class="form-control @error('password') border-red-500 @enderror"
                placeholder="••••••••"
                required
              />
            </div>

            <div class="form-group">
              <label for="password_confirmation">Confirmer *</label>
              <input 
                type="password" 
                id="password_confirmation"
                name="password_confirmation" 
                class="form-control @error('password_confirmation') border-red-500 @enderror"
                placeholder="••••••••"
                required
              />
            </div>
          </div>

          <div class="button-group">
            <button type="button" class="btn-primary" onclick="nextStep()">Continuer →</button>
          </div>
        </div>

        <!-- ÉTAPE 2 : Infos Financières -->
        <div class="form-step" id="step-2">
          <div class="form-group">
            <label for="estimated_income">Revenu annuel estimé (optionnel)</label>
            <select 
              id="estimated_income"
              name="estimated_income" 
              class="form-control"
            >
              <option value="">Sélectionnez un revenu</option>
              <option value="20000" {{ old('estimated_income') == '20000' ? 'selected' : '' }}>Moins de 20 000€</option>
              <option value="50000" {{ old('estimated_income') == '50000' ? 'selected' : '' }}>20 000€ - 50 000€</option>
              <option value="100000" {{ old('estimated_income') == '100000' ? 'selected' : '' }}>50 000€ - 100 000€</option>
              <option value="200000" {{ old('estimated_income') == '200000' ? 'selected' : '' }}>100 000€ - 200 000€</option>
              <option value="500000" {{ old('estimated_income') == '500000' ? 'selected' : '' }}>Plus de 200 000€</option>
            </select>
          </div>

          <div class="form-group">
            <label for="monthly_savings">Épargne mensuelle estimée (optionnel)</label>
            <select 
              id="monthly_savings"
              name="monthly_savings" 
              class="form-control"
            >
              <option value="">Sélectionnez un montant</option>
              <option value="500" {{ old('monthly_savings') == '500' ? 'selected' : '' }}>Moins de 500€</option>
              <option value="1000" {{ old('monthly_savings') == '1000' ? 'selected' : '' }}>500€ - 1 000€</option>
              <option value="2500" {{ old('monthly_savings') == '2500' ? 'selected' : '' }}>1 000€ - 2 500€</option>
              <option value="5000" {{ old('monthly_savings') == '5000' ? 'selected' : '' }}>2 500€ - 5 000€</option>
              <option value="10000" {{ old('monthly_savings') == '10000' ? 'selected' : '' }}>Plus de 5 000€</option>
            </select>
          </div>

          <div class="form-group">
            <label for="investment_amount">Capital à investir en trading (optionnel)</label>
            <select 
              id="investment_amount"
              name="investment_amount" 
              class="form-control"
            >
              <option value="">Sélectionnez un montant</option>
              <option value="1000" {{ old('investment_amount') == '1000' ? 'selected' : '' }}>1 000€ - 5 000€</option>
              <option value="5000" {{ old('investment_amount') == '5000' ? 'selected' : '' }}>5 000€ - 10 000€</option>
              <option value="10000" {{ old('investment_amount') == '10000' ? 'selected' : '' }}>10 000€ - 25 000€</option>
              <option value="25000" {{ old('investment_amount') == '25000' ? 'selected' : '' }}>25 000€ - 50 000€</option>
              <option value="50000" {{ old('investment_amount') == '50000' ? 'selected' : '' }}>Plus de 50 000€</option>
            </select>
          </div>

          <div class="form-group">
            <label for="expected_return">Gains mensuels espérés (optionnel)</label>
            <select 
              id="expected_return"
              name="expected_return" 
              class="form-control"
            >
              <option value="">Sélectionnez un pourcentage</option>
              <option value="5" {{ old('expected_return') == '5' ? 'selected' : '' }}>5% - 10% par mois</option>
              <option value="15" {{ old('expected_return') == '15' ? 'selected' : '' }}>10% - 15% par mois</option>
              <option value="20" {{ old('expected_return') == '20' ? 'selected' : '' }}>15% - 20% par mois</option>
              <option value="30" {{ old('expected_return') == '30' ? 'selected' : '' }}>20% - 30% par mois</option>
              <option value="50" {{ old('expected_return') == '50' ? 'selected' : '' }}>Plus de 30% par mois</option>
            </select>
          </div>

          <div class="button-group">
            <button type="button" class="btn-primary btn-secondary" onclick="previousStep()">← Retour</button>
            <button type="submit" class="btn-primary">Créer mon compte</button>
          </div>
        </div>
      </form>

      <div class="login-link">
        Déjà inscrit ? <a href="{{ route('auth.login') }}">Se connecter</a>
      </div>
    </div>
  </div>

  <script>
    let currentStep = 1;

    function nextStep() {
      // Validation de l'étape 1
      const firstName = document.getElementById('first_name').value.trim();
      const lastName = document.getElementById('last_name').value.trim();
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value;
      const passwordConfirm = document.getElementById('password_confirmation').value;

      if (!firstName) {
        alert('Veuillez entrer votre prénom');
        return;
      }
      if (!lastName) {
        alert('Veuillez entrer votre nom');
        return;
      }
      if (!email) {
        alert('Veuillez entrer votre email');
        return;
      }
      if (!password) {
        alert('Veuillez entrer un mot de passe');
        return;
      }
      if (password.length < 8) {
        alert('Le mot de passe doit contenir au moins 8 caractères');
        return;
      }
      if (password !== passwordConfirm) {
        alert('Les mots de passe ne correspondent pas');
        return;
      }

      currentStep = 2;
      showStep();
    }

    function previousStep() {
      currentStep = 1;
      showStep();
    }

    function showStep() {
      // Masquer tous les steps
      document.querySelectorAll('.form-step').forEach(step => {
        step.classList.remove('active');
      });

      // Afficher le step actuel
      document.getElementById('step-' + currentStep).classList.add('active');

      // Mettre à jour le sous-titre
      const subtitle = currentStep === 1 
        ? 'Étape 1 : Informations personnelles'
        : 'Étape 2 : Informations financières';
      document.getElementById('form-subtitle').textContent = subtitle;

      // Mettre à jour les indicateurs
      document.getElementById('step1-indicator').classList.toggle('active', currentStep === 1);
      document.getElementById('step2-indicator').classList.toggle('active', currentStep === 2);

      // Scroll to top
      document.querySelector('.right-panel').scrollTop = 0;
    }

    // Restore email si "remember me" était coché
    document.addEventListener('DOMContentLoaded', function() {
      const savedEmail = localStorage.getItem('moonTrade_email');
      const savedFirstName = localStorage.getItem('moonTrade_firstName');
      
      if (savedEmail) {
        document.getElementById('email').value = savedEmail;
      }
      if (savedFirstName) {
        document.getElementById('first_name').value = savedFirstName;
      }
    });

    // Save on form submission
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      const email = document.getElementById('email').value;
      const firstName = document.getElementById('first_name').value;
      
      if (email && firstName) {
        localStorage.setItem('moonTrade_email', email);
        localStorage.setItem('moonTrade_firstName', firstName);
        localStorage.setItem('moonTrade_registeredAt', new Date().toISOString());
      }
    });
  </script>
</body>

</html>
