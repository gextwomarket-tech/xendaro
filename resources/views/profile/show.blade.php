<x-layouts.dashboard>
  <x-slot name="title">Mon Profil</x-slot>
  <x-slot name="subtitle">Gérez vos informations personnelles, sécurité et préférences</x-slot>

  @push('styles')
  <style>
    .section-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:28px; margin-bottom:20px; }
    .section-title { font-size:15px; font-weight:700; color:#0f172a; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .form-label { display:block; font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.04em; margin-bottom:6px; }
    .form-input { width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; color:#0f172a; outline:none; transition:border-color .2s; background:#fff; }
    .form-input:focus { border-color:#547A95; box-shadow:0 0 0 3px rgba(84,122,149,.1); }
    .form-input:disabled { background:#f8fafc; color:#94a3b8; }
    .btn-primary { padding:9px 22px; background:#0f172a; color:#fff; border-radius:8px; font-size:13px; font-weight:600; border:none; cursor:pointer; transition:background .15s; }
    .btn-primary:hover { background:#1e293b; }
    .btn-outline { padding:9px 22px; border:1px solid #e2e8f0; color:#64748b; border-radius:8px; font-size:13px; font-weight:600; background:#fff; cursor:pointer; transition:all .15s; }
    .btn-outline:hover { border-color:#0f172a; color:#0f172a; }
    .btn-danger { padding:9px 22px; background:#dc2626; color:#fff; border-radius:8px; font-size:13px; font-weight:600; border:none; cursor:pointer; }
    .badge-kyc { display:inline-flex; align-items:center; gap:6px; padding:4px 14px; border-radius:99px; font-size:12px; font-weight:700; }
    .kyc-pending  { background:#fef9c3; color:#ca8a04; }
    .kyc-verified { background:#dcfce7; color:#16a34a; }
    .kyc-rejected { background:#fee2e2; color:#dc2626; }
    .strength-bar { height:6px; border-radius:4px; background:#e2e8f0; overflow:hidden; margin-top:6px; }
    .strength-fill { height:100%; border-radius:4px; transition:width .3s, background .3s; }
    .avatar-wrap { position:relative; display:inline-block; }
    .avatar-wrap:hover .avatar-overlay { opacity:1; }
    .avatar-overlay { position:absolute; inset:0; border-radius:50%; background:rgba(0,0,0,.45); display:flex; align-items:center; justify-content:center; opacity:0; transition:opacity .2s; cursor:pointer; color:#fff; font-size:22px; }
    /* danger zone */
    .danger-zone { border:1px solid #fecaca; border-radius:14px; padding:24px; background:#fff5f5; }
  </style>
  @endpush

  @if(session('success'))
    <div id="successToast" class="fixed top-6 right-6 z-50 bg-green-600 text-white px-5 py-3 rounded-xl font-semibold text-sm shadow-lg flex items-center gap-2">
      ✓ {{ session('success') }}
    </div>
    <script>setTimeout(()=>document.getElementById('successToast')?.remove(), 4000);</script>
  @endif

  <div class="max-w-4xl mx-auto space-y-5">

    {{-- ══ AVATAR + INFOS HEADER ══ --}}
    <div class="section-card flex flex-col sm:flex-row items-start sm:items-center gap-6">
      <div class="avatar-wrap">
        @if($user->avatar)
          <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
               class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md">
        @else
          <div class="w-20 h-20 rounded-full bg-gradient-to-br from-slate-700 to-slate-500 flex items-center justify-center text-white font-bold text-2xl shadow-md">
            {{ strtoupper(substr($user->first_name ?? $user->name ?? 'U', 0, 1)) }}
          </div>
        @endif
        <label class="avatar-overlay" for="avatarInput">
          <span class="material-symbols-outlined text-[20px]">photo_camera</span>
        </label>
      </div>
      <form action="{{ route('profile.avatar.post') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
        @csrf
        <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden"
               onchange="document.getElementById('avatarForm').submit()">
      </form>
      <div>
        <h2 class="text-xl font-bold text-slate-900">{{ $user->first_name ?? '' }} {{ $user->last_name ?? $user->name }}</h2>
        <p class="text-slate-500 text-sm">{{ $user->email }}</p>
        <p class="text-xs text-slate-400 mt-1">Membre depuis {{ $user->created_at?->format('d F Y') }}</p>
        <div class="mt-2">
          <span class="badge-kyc {{ $user->kyc_status === 'verified' ? 'kyc-verified' : ($user->kyc_status === 'rejected' ? 'kyc-rejected' : 'kyc-pending') }}">
            <span class="material-symbols-outlined text-[14px]">{{ $user->kyc_status === 'verified' ? 'verified' : 'pending' }}</span>
            KYC {{ ucfirst($user->kyc_status ?? 'pending') }}
          </span>
        </div>
      </div>
    </div>

    {{-- ══ INFORMATIONS PERSONNELLES ══ --}}
    <div class="section-card">
      <div class="section-title"><span class="material-symbols-outlined text-[20px]">person</span> Informations personnelles</div>
      <form action="{{ route('profile.update') }}" method="POST" id="infoForm">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
          <div>
            <label class="form-label">Prénom</label>
            <input name="first_name" value="{{ old('first_name', $user->first_name) }}" class="form-input" id="inp_first_name" disabled>
          </div>
          <div>
            <label class="form-label">Nom</label>
            <input name="last_name" value="{{ old('last_name', $user->last_name ?? $user->name) }}" class="form-input" id="inp_last_name" disabled>
          </div>
          <div>
            <label class="form-label">Email</label>
            <input type="email" value="{{ $user->email }}" class="form-input" disabled>
          </div>
          <div>
            <label class="form-label">Téléphone</label>
            <input name="phone" value="{{ old('phone', $user->phone) }}" class="form-input" id="inp_phone" disabled>
          </div>
          <div>
            <label class="form-label">Date de naissance</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}" class="form-input" id="inp_dob" disabled>
          </div>
          <div>
            <label class="form-label">Pays</label>
            <input name="country" value="{{ old('country', $user->country) }}" class="form-input" id="inp_country" disabled>
          </div>
        </div>
        @if($errors->any())
          <div class="text-sm text-red-600 mb-4">{{ $errors->first() }}</div>
        @endif
        <div class="flex gap-3" id="infoActions">
          <button type="button" onclick="toggleEdit()" class="btn-primary" id="editBtn">✏️ Modifier</button>
          <button type="submit" class="btn-primary hidden" id="saveBtn">💾 Enregistrer</button>
          <button type="button" onclick="cancelEdit()" class="btn-outline hidden" id="cancelBtn">Annuler</button>
        </div>
      </form>
    </div>

    {{-- ══ KYC ══ --}}
    <div class="section-card">
      <div class="section-title"><span class="material-symbols-outlined text-[20px]">verified_user</span> Vérification KYC</div>
      @php $kyc = $user->kyc_status ?? 'pending'; @endphp
      <div class="flex items-center gap-4 mb-4">
        <span class="badge-kyc {{ $kyc === 'verified' ? 'kyc-verified' : ($kyc === 'rejected' ? 'kyc-rejected' : 'kyc-pending') }} text-base px-5 py-2">
          {{ $kyc === 'verified' ? '✓ Vérifié' : ($kyc === 'rejected' ? '✗ Rejeté' : '⏳ En attente') }}
        </span>
        @if($kyc !== 'verified')
          <a href="{{ route('kyc.show') }}" class="btn-primary text-sm">
            {{ $kyc === 'rejected' ? 'Re-soumettre' : 'Compléter le KYC' }}
          </a>
        @endif
      </div>
      {{-- Barre de progression KYC --}}
      <div class="flex items-center gap-2 text-sm">
        @foreach(['Basique','Standard','Avancé'] as $i=>$lvl)
          <div class="flex items-center gap-1">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
              {{ ($user->kyc_level ?? 0) >= $i+1 ? 'bg-green-500 text-white' : 'bg-slate-200 text-slate-400' }}">
              {{ $i+1 }}
            </div>
            <span class="{{ ($user->kyc_level ?? 0) >= $i+1 ? 'text-green-600 font-semibold' : 'text-slate-400' }}">{{ $lvl }}</span>
          </div>
          @if($i < 2) <div class="flex-1 h-px bg-slate-200 mx-1"></div> @endif
        @endforeach
      </div>
    </div>

    {{-- ══ SÉCURITÉ ══ --}}
    <div class="section-card">
      <div class="section-title"><span class="material-symbols-outlined text-[20px]">lock</span> Sécurité</div>

      {{-- Changement de mot de passe --}}
      <h4 class="font-semibold text-sm text-slate-700 mb-3">Changer le mot de passe</h4>
      <form action="{{ route('profile.password.put') }}" method="POST" id="pwdForm">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
          <div>
            <label class="form-label">Mot de passe actuel</label>
            <input type="password" name="current_password" class="form-input" required autocomplete="current-password">
          </div>
          <div>
            <label class="form-label">Nouveau</label>
            <input type="password" name="password" id="newPwd" class="form-input" required autocomplete="new-password"
                   oninput="updateStrength(this.value)">
            <div class="strength-bar"><div class="strength-fill" id="strengthBar" style="width:0;background:#dc2626"></div></div>
            <div class="text-xs text-slate-400 mt-1" id="strengthLabel"></div>
          </div>
          <div>
            <label class="form-label">Confirmation</label>
            <input type="password" name="password_confirmation" class="form-input" required autocomplete="new-password">
          </div>
        </div>
        <button class="btn-primary text-sm">Changer le mot de passe</button>
      </form>

      {{-- 2FA --}}
      <div class="mt-6 pt-6 border-t border-slate-100">
        <div class="flex items-center justify-between">
          <div>
            <h4 class="font-semibold text-sm text-slate-700">Authentification à deux facteurs (2FA)</h4>
            <p class="text-xs text-slate-400 mt-1">Protégez votre compte avec un code TOTP.</p>
          </div>
          @if($user->two_factor_enabled)
            <form action="{{ route('auth.2fa.disable') }}" method="POST">
              @csrf @method('DELETE')
              <button class="btn-outline text-sm border-red-200 text-red-600">Désactiver</button>
            </form>
          @else
            <a href="{{ route('auth.2fa.setup') }}" class="btn-primary text-sm">Activer 2FA</a>
          @endif
        </div>
        <div class="mt-2">
          <span class="{{ $user->two_factor_enabled ? 'text-green-600' : 'text-slate-400' }} text-xs font-semibold">
            {{ $user->two_factor_enabled ? '✓ Activé' : '✗ Désactivé' }}
          </span>
        </div>
      </div>
    </div>

    {{-- ══ PRÉFÉRENCES ══ --}}
    <div class="section-card">
      <div class="section-title"><span class="material-symbols-outlined text-[20px]">settings</span> Préférences</div>
      <form action="{{ route('profile.update') }}" method="POST">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="form-label">Devise préférée</label>
            <select name="preferred_currency" class="form-input">
              @foreach(['USD','EUR','GBP','BTC','USDT'] as $cur)
                <option value="{{ $cur }}" {{ ($user->preferred_currency ?? 'USD') === $cur ? 'selected' : '' }}>{{ $cur }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <button class="btn-primary text-sm">Sauvegarder préférences</button>
      </form>
    </div>

    {{-- ══ DANGER ZONE ══ --}}
    <div class="danger-zone">
      <div class="section-title text-red-700"><span class="material-symbols-outlined text-[20px]">warning</span> Zone de danger</div>
      <p class="text-sm text-red-600 mb-4">La suppression de votre compte est irréversible. Toutes vos données seront perdues.</p>
      <button onclick="document.getElementById('deleteModal').classList.remove('hidden')" class="btn-danger text-sm">
        Supprimer mon compte
      </button>
    </div>
  </div>

  {{-- Modal suppression --}}
  <div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-8 w-full max-w-md shadow-2xl">
      <h3 class="font-bold text-xl text-slate-900 mb-2">Supprimer mon compte</h3>
      <p class="text-sm text-slate-500 mb-4">Tapez <strong>SUPPRIMER</strong> et entrez votre mot de passe pour confirmer.</p>
      <form action="{{ route('profile.account.delete') }}" method="POST" onsubmit="return validateDelete()">
        @csrf @method('DELETE')
        <div class="space-y-3 mb-5">
          <input id="deleteConfirm" type="text" placeholder="SUPPRIMER" class="form-input" autocomplete="off">
          <input type="password" name="password" placeholder="Mot de passe" class="form-input" required>
        </div>
        <div class="flex gap-3">
          <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" class="btn-outline flex-1">Annuler</button>
          <button type="submit" class="btn-danger flex-1">Supprimer définitivement</button>
        </div>
      </form>
    </div>
  </div>

  @push('scripts')
  <script>
    // ── Mode édition inline ──────────────────────────────────────
    const fields = ['inp_first_name','inp_last_name','inp_phone','inp_dob','inp_country'];
    function toggleEdit() {
      fields.forEach(id => document.getElementById(id)?.removeAttribute('disabled'));
      document.getElementById('editBtn').classList.add('hidden');
      document.getElementById('saveBtn').classList.remove('hidden');
      document.getElementById('cancelBtn').classList.remove('hidden');
    }
    function cancelEdit() {
      document.getElementById('infoForm').reset();
      fields.forEach(id => document.getElementById(id)?.setAttribute('disabled', ''));
      document.getElementById('editBtn').classList.remove('hidden');
      document.getElementById('saveBtn').classList.add('hidden');
      document.getElementById('cancelBtn').classList.add('hidden');
    }

    // ── Force du mot de passe ────────────────────────────────────
    function updateStrength(val) {
      let score = 0;
      if (val.length >= 8) score++;
      if (/[A-Z]/.test(val)) score++;
      if (/[0-9]/.test(val)) score++;
      if (/[^A-Za-z0-9]/.test(val)) score++;
      const bar   = document.getElementById('strengthBar');
      const label = document.getElementById('strengthLabel');
      const configs = [
        { w:'25%', bg:'#dc2626', lbl:'Très faible' },
        { w:'50%', bg:'#ea580c', lbl:'Faible' },
        { w:'75%', bg:'#ca8a04', lbl:'Moyen' },
        { w:'100%', bg:'#16a34a', lbl:'Fort' },
      ];
      const c = configs[score - 1] || { w:'0', bg:'#dc2626', lbl:'' };
      bar.style.width = c.w; bar.style.background = c.bg;
      label.textContent = c.lbl;
      label.style.color = c.bg;
    }

    // ── Validation suppression ───────────────────────────────────
    function validateDelete() {
      if (document.getElementById('deleteConfirm').value !== 'SUPPRIMER') {
        alert('Veuillez taper exactement "SUPPRIMER" pour confirmer.');
        return false;
      }
      return true;
    }
  </script>
  @endpush

</x-layouts.dashboard>
