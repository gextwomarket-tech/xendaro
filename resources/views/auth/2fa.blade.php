<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Brocker — 2FA</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<style>
*{font-family:'Manrope',sans-serif;}
body{background:#EFE3CA;padding:20px;}
.container{max-width:440px;margin:0 auto;}
.card{background:#fff;border-radius:16px;padding:40px;box-shadow:0 4px 40px rgba(44,57,71,.1);}
.logo-icon{width:36px;height:36px;background:#2C3947;border-radius:8px;display:flex;align-items:center;justify-content:center;}
.input-field{width:100%;padding:12px 14px;border:1.5px solid #DED2B6;border-radius:8px;background:#fff;font-size:15px;color:#2C3947;outline:none;transition:border-color .2s;box-sizing:border-box;}
.input-field:focus{border-color:#547A95;}
.btn-primary{width:100%;padding:13px;background:#2C3947;color:#EFE3CA;border-radius:8px;font-weight:700;font-size:15px;border:none;cursor:pointer;transition:all .2s;}
.btn-primary:hover{background:#547A95;}
.alert{padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:14px;}
.alert-error{background:#fef2f2;border:1px solid #fecaca;color:#dc2626;}
@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
.fade-in{animation:fadeIn .4s ease;}
.code-input{letter-spacing:8px;font-size:24px;font-weight:700;text-align:center;}
</style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center">

<a href="{{ route('home') }}" class="flex items-center gap-2 mb-8">
  <div class="logo-icon"><svg width="22" height="22" viewBox="0 0 22 22" fill="none"><path d="M4 4h7c2.8 0 5 1.8 5 4.5S13.8 13 11 13H7v5H4V4z" fill="#EFE3CA"/><path d="M7 13h4.5c2.5 0 4.5 1.8 4.5 4H7v-4z" fill="#547A95"/></svg></div>
  <span style="font-size:20px;font-weight:800;color:#2C3947;letter-spacing:-.5px;">Brocker</span>
</a>

<div class="container">
  <div class="card fade-in">
    <div style="font-size:48px;margin-bottom:16px;text-align:center;">🔐</div>
    <h1 style="font-size:24px;font-weight:800;color:#2C3947;margin-bottom:6px;text-align:center;">Authentification 2FA</h1>
    <p style="font-size:14px;color:#74777c;margin-bottom:28px;text-align:center;line-height:1.7;">Entrez le code à 6 chiffres de votre authentificateur.</p>

    @if ($errors->any())
      <div class="alert alert-error">
        <ul style="margin: 0; padding-left: 20px;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('auth.verify-2fa.post') }}">
      @csrf
      <div style="margin-bottom:24px;">
        <input 
          type="text" 
          name="code"
          id="code"
          class="input-field code-input"
          placeholder="000000"
          maxlength="6"
          pattern="[0-9]{6}"
          inputmode="numeric"
          required
          autofocus
        />
      </div>
      <button type="submit" class="btn-primary">Vérifier</button>
    </form>

    <div style="text-align:center;margin-top:20px;">
      <a href="{{ route('auth.login') }}" style="font-size:13px;color:#547A95;font-weight:700;text-decoration:none;">← Retour à la connexion</a>
    </div>
  </div>
</div>

<script>
// Auto-format 6 digit input
document.getElementById('code')?.addEventListener('input', function(e) {
  this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
});
</script>

</body>
</html>
