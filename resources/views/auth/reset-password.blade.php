<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Brocker — Réinitialiser le mot de passe</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<style>
*{font-family:'Manrope',sans-serif;}
body{background:#EFE3CA;padding:20px;}
.container{max-width:440px;margin:0 auto;}
.input-field{width:100%;padding:12px 14px;border:1.5px solid #DED2B6;border-radius:8px;background:#fff;font-size:15px;color:#2C3947;outline:none;transition:border-color .2s;box-sizing:border-box;}
.input-field:focus{border-color:#547A95;}
.btn-primary{width:100%;padding:13px;background:#2C3947;color:#EFE3CA;border-radius:8px;font-weight:700;font-size:15px;border:none;cursor:pointer;transition:all .2s;}
.btn-primary:hover{background:#547A95;}
.card{background:#fff;border-radius:16px;padding:40px;box-shadow:0 4px 40px rgba(44,57,71,.1);}
.logo-icon{width:36px;height:36px;background:#2C3947;border-radius:8px;display:flex;align-items:center;justify-content:center;}
.pw-strength-bar{height:4px;border-radius:2px;transition:all .4s;margin-top:6px;background:#DED2B6;}
.alert{padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:14px;}
.alert-error{background:#fef2f2;border:1px solid #fecaca;color:#dc2626;}
@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
.fade-in{animation:fadeIn .4s ease;}
</style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center">

<a href="{{ route('home') }}" class="flex items-center gap-2 mb-8">
  <div class="logo-icon"><svg width="22" height="22" viewBox="0 0 22 22" fill="none"><path d="M4 4h7c2.8 0 5 1.8 5 4.5S13.8 13 11 13H7v5H4V4z" fill="#EFE3CA"/><path d="M7 13h4.5c2.5 0 4.5 1.8 4.5 4H7v-4z" fill="#547A95"/></svg></div>
  <span style="font-size:20px;font-weight:800;color:#2C3947;letter-spacing:-.5px;">Brocker</span>
</a>

<div class="container">
  <div class="card fade-in">
    <div style="font-size:40px;margin-bottom:16px;">🔑</div>
    <h1 style="font-size:24px;font-weight:800;color:#2C3947;margin-bottom:6px;">Nouveau mot de passe</h1>
    <p style="font-size:14px;color:#74777c;margin-bottom:28px;">Choisissez un nouveau mot de passe sécurisé.</p>

    @if ($errors->any())
      <div class="alert alert-error">
        <ul style="margin: 0; padding-left: 20px;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('auth.reset-password.post') }}">
      @csrf

      <input type="hidden" name="email" value="{{ $email ?? '' }}" />
      <input type="hidden" name="code" value="{{ $code ?? '' }}" />

      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:13px;font-weight:600;color:#2C3947;margin-bottom:6px;">Nouveau mot de passe</label>
        <div style="position:relative;">
          <input 
            type="password" 
            name="password"
            id="password"
            class="input-field"
            placeholder="••••••••"
            style="padding-right:44px;"
            required
          />
          <button type="button" onclick="togglePassword('password')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#74777c;">👁</button>
        </div>
        <div id="pw-strength-bar" class="pw-strength-bar" style="width:0%;"></div>
      </div>

      <div style="margin-bottom:20px;">
        <label style="display:block;font-size:13px;font-weight:600;color:#2C3947;margin-bottom:6px;">Confirmer le mot de passe</label>
        <input 
          type="password" 
          name="password_confirmation"
          id="password_confirmation"
          class="input-field"
          placeholder="••••••••"
          required
        />
      </div>

      <button type="submit" class="btn-primary">Réinitialiser le mot de passe</button>
    </form>

    <div style="text-align:center;margin-top:20px;">
      <a href="{{ route('auth.login') }}" style="font-size:14px;color:#547A95;font-weight:700;text-decoration:none;">← Retour à la connexion</a>
    </div>
  </div>
</div>

<script>
function togglePassword(id) {
  const input = document.getElementById(id);
  input.type = input.type === 'password' ? 'text' : 'password';
}

// Simple password strength indicator
document.getElementById('password')?.addEventListener('input', function() {
  const strength = this.value.length;
  const bar = document.getElementById('pw-strength-bar');
  const percent = Math.min(strength * 10, 100);
  bar.style.width = percent + '%';
  bar.style.background = percent < 30 ? '#ef4444' : percent < 60 ? '#f59e0b' : '#16a34a';
});
</script>

</body>
</html>
