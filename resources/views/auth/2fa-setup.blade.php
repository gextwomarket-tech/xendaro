<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Brocker — Configurer 2FA</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<style>
*{font-family:'Manrope',sans-serif;}
body{background:#EFE3CA;padding:20px;}
.container{max-width:560px;margin:0 auto;}
.card{background:#fff;border-radius:16px;padding:40px;box-shadow:0 4px 40px rgba(44,57,71,.1);}
.card h2{font-size:22px;font-weight:800;color:#2C3947;margin-bottom:12px;}
.card p{font-size:14px;color:#74777c;margin-bottom:24px;line-height:1.7;}
.input-field{width:100%;padding:12px 14px;border:1.5px solid #DED2B6;border-radius:8px;background:#fff;font-size:15px;color:#2C3947;outline:none;transition:border-color .2s;box-sizing:border-box;}
.input-field:focus{border-color:#547A95;}
.btn-primary{width:100%;padding:13px;background:#2C3947;color:#EFE3CA;border-radius:8px;font-weight:700;font-size:15px;border:none;cursor:pointer;transition:all .2s;margin-top:16px;}
.btn-primary:hover{background:#547A95;}
.qr-container{text-align:center;padding:24px;background:#f9f7f4;border-radius:12px;margin:24px 0;}
.qr-image{max-width:280px;margin:0 auto;}
.secret-box{background:#f9f7f4;border:1px solid #E2D9C8;border-radius:8px;padding:16px;font-family:monospace;font-size:14px;word-break:break-all;margin:16px 0;}
.recovery-codes{background:#f9f7f4;border:1px solid #E2D9C8;border-radius:8px;padding:16px;margin:16px 0;}
.recovery-codes code{display:block;margin:8px 0;font-size:13px;font-weight:600;color:#2C3947;}
.alert{padding:12px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;}
.alert-warning{background:#fef3c7;border:1px solid #fde047;color:#78350f;}
</style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center">

<div class="container">
  <div class="card">
    <h2>Configurer l'authentification à deux facteurs</h2>
    <p>Sécurisez votre compte avec une authentification à deux facteurs (2FA).</p>

    <div class="alert alert-warning">
      ⚠️ Gardez vos codes de récupération en lieu sûr. Vous en aurez besoin si vous perdez accès à votre authenticateur.
    </div>

    <!-- Step 1: QR Code -->
    <div>
      <h3 style="font-size:16px;font-weight:700;color:#2C3947;margin-bottom:12px;">1. Scannez le code QR</h3>
      <p style="font-size:13px;color:#74777c;margin-bottom:16px;">Utilisez une application d'authentification comme Google Authenticator, Authy ou Microsoft Authenticator.</p>
      
      <div class="qr-container">
        <img src="{{ $qr_uri }}" alt="QR Code" class="qr-image" />
      </div>

      <p style="font-size:13px;color:#74777c;margin-bottom:12px;">Ou entrez cette clé manuellement :</p>
      <div class="secret-box">{{ $secret }}</div>
    </div>

    <!-- Step 2: Recovery Codes -->
    <div style="margin-top:32px;">
      <h3 style="font-size:16px;font-weight:700;color:#2C3947;margin-bottom:12px;">2. Codes de récupération</h3>
      <p style="font-size:13px;color:#74777c;margin-bottom:16px;">Sauvegardez ces codes dans un endroit sûr. Vous pouvez les utiliser pour accéder à votre compte si vous perdez votre authenticateur.</p>
      
      <div class="recovery-codes">
        @foreach ($recovery_codes as $code)
          <code>{{ $code }}</code>
        @endforeach
      </div>

      <div style="text-align:center;">
        <button type="button" onclick="copyRecoveryCodes()" style="padding:8px 16px;background:#f0f0f0;border:1px solid #d0d0d0;border-radius:6px;cursor:pointer;font-size:13px;font-weight:600;">Copier les codes</button>
      </div>
    </div>

    <!-- Step 3: Confirm -->
    <div style="margin-top:32px;">
      <h3 style="font-size:16px;font-weight:700;color:#2C3947;margin-bottom:12px;">3. Confirmation</h3>
      <p style="font-size:13px;color:#74777c;margin-bottom:16px;">Entrez le code à 6 chiffres de votre authenticateur pour confirmer.</p>

      <form method="POST" action="{{ route('auth.2fa.confirm.post') }}">
        @csrf
        <div style="margin-bottom:16px;">
          <label style="display:block;font-size:13px;font-weight:600;color:#2C3947;margin-bottom:6px;">Code d'authentification</label>
          <input 
            type="text" 
            name="code"
            class="input-field"
            placeholder="000000"
            maxlength="6"
            pattern="[0-9]{6}"
            inputmode="numeric"
            required
          />
        </div>
        <button type="submit" class="btn-primary">Activer le 2FA</button>
      </form>
    </div>
  </div>
</div>

<script>
function copyRecoveryCodes() {
  const codes = document.querySelectorAll('.recovery-codes code');
  const text = Array.from(codes).map(c => c.textContent).join('\n');
  navigator.clipboard.writeText(text).then(() => {
    alert('Codes copiés !');
  });
}

// Auto-format code input
document.addEventListener('DOMContentLoaded', function() {
  const input = document.querySelector('input[name="code"]');
  if (input) {
    input.addEventListener('input', function(e) {
      this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
    });
  }
});
</script>

</body>
</html>
