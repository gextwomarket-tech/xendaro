{{-- KYC Toast Notification — bas gauche, non-bloquant, avec CTA --}}
@php
    $user = auth()->user();
    $kycStatus = $user?->kyc_status;
    $showToast = $user && $kycStatus !== 'verified' && $kycStatus !== null;
@endphp

@if ($showToast)
<div
    id="kyc-toast"
    role="alert"
    aria-live="polite"
    style="
        position: fixed;
        bottom: 24px;
        left: 24px;
        z-index: 9999;
        width: 340px;
        max-width: calc(100vw - 48px);
        background: #0f172a;
        border: 1px solid {{ $kycStatus === 'pending' ? '#854d0e' : '#1e3a8a' }};
        border-left: 4px solid {{ $kycStatus === 'pending' ? '#eab308' : '#3b82f6' }};
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.4), 0 2px 8px rgba(0,0,0,0.2);
        padding: 16px 16px 14px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        opacity: 0;
        transform: translateY(16px);
        transition: opacity 0.35s ease, transform 0.35s ease;
        font-family: 'Manrope', sans-serif;
    "
>
    {{-- Header --}}
    <div style="display:flex; align-items:flex-start; gap:10px;">
        {{-- Icon --}}
        <div style="
            width:36px; height:36px; border-radius:8px; flex-shrink:0;
            background: {{ $kycStatus === 'pending' ? 'rgba(234,179,8,.15)' : 'rgba(59,130,246,.15)' }};
            display:flex; align-items:center; justify-content:center;
        ">
            <span class="material-symbols-outlined" style="font-size:20px; color:{{ $kycStatus === 'pending' ? '#eab308' : '#3b82f6' }}">
                {{ $kycStatus === 'pending' ? 'schedule' : 'verified_user' }}
            </span>
        </div>

        {{-- Text --}}
        <div style="flex:1; min-width:0;">
            <p style="font-size:13px; font-weight:700; color:#f1f5f9; margin:0 0 3px;">
                @if($kycStatus === 'pending')
                    Vérification KYC en cours
                @elseif($kycStatus === 'rejected')
                    KYC rejeté — action requise
                @else
                    Complétez votre KYC
                @endif
            </p>
            <p style="font-size:12px; color:#94a3b8; margin:0; line-height:1.5;">
                @if($kycStatus === 'pending')
                    Vos documents sont en cours d'examen (1–24 h). Accès complet maintenu.
                @elseif($kycStatus === 'rejected')
                    Votre vérification a été rejetée. Soumettez à nouveau vos documents.
                @else
                    Vérifiez votre identité pour débloquer toutes les fonctionnalités.
                @endif
            </p>
        </div>

        {{-- Close --}}
        <button
            onclick="closeKycToast()"
            aria-label="Fermer"
            style="background:none; border:none; cursor:pointer; padding:2px; flex-shrink:0; color:#64748b; line-height:1;"
        >
            <span class="material-symbols-outlined" style="font-size:18px;">close</span>
        </button>
    </div>

    {{-- CTA Buttons --}}
    <div style="display:flex; gap:8px;">
        @if($kycStatus === 'rejected')
            <a
                href="{{ route('kyc.show') }}"
                style="flex:1; text-align:center; padding:8px 12px; background:#ef4444; color:#fff; border-radius:8px; font-size:12px; font-weight:700; text-decoration:none; transition:background .2s;"
                onmouseover="this.style.background='#dc2626'"
                onmouseout="this.style.background='#ef4444'"
            >
                Relancer le KYC →
            </a>
        @else
            <a
                href="{{ route('kyc.show') }}"
                style="flex:1; text-align:center; padding:8px 12px; background:#3b82f6; color:#fff; border-radius:8px; font-size:12px; font-weight:700; text-decoration:none; transition:background .2s;"
                onmouseover="this.style.background='#2563eb'"
                onmouseout="this.style.background='#3b82f6'"
            >
                {{ $kycStatus === 'pending' ? 'Voir le statut →' : 'Vérifier maintenant →' }}
            </a>
        @endif
        <button
            onclick="closeKycToast()"
            style="padding:8px 14px; background:rgba(255,255,255,.06); color:#cbd5e1; border:1px solid rgba(255,255,255,.1); border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; transition:background .2s;"
            onmouseover="this.style.background='rgba(255,255,255,.12)'"
            onmouseout="this.style.background='rgba(255,255,255,.06)'"
        >
            Plus tard
        </button>
    </div>

    {{-- Progress bar auto-dismiss --}}
    <div style="height:2px; background:rgba(255,255,255,.06); border-radius:2px; overflow:hidden; margin-top:2px;">
        <div
            id="kyc-toast-bar"
            style="height:100%; width:100%; background:{{ $kycStatus === 'pending' ? '#eab308' : '#3b82f6' }}; border-radius:2px; transition:width linear;"
        ></div>
    </div>
</div>

<script>
(function() {
    const DISMISS_AFTER = 12000; // ms
    const toast   = document.getElementById('kyc-toast');
    const bar     = document.getElementById('kyc-toast-bar');
    let timer;

    function showKycToast() {
        if (!toast) return;
        // Entrée animée
        requestAnimationFrame(() => {
            toast.style.opacity  = '1';
            toast.style.transform = 'translateY(0)';
        });
        // Barre de progression
        bar.style.transitionDuration = DISMISS_AFTER + 'ms';
        bar.style.width = '0%';
        // Auto-dismiss
        timer = setTimeout(closeKycToast, DISMISS_AFTER);
    }

    window.closeKycToast = function() {
        if (!toast) return;
        clearTimeout(timer);
        toast.style.opacity   = '0';
        toast.style.transform = 'translateY(16px)';
        setTimeout(() => toast.remove(), 380);
        // Mémoriser en session (via cookie léger, 24h)
        document.cookie = 'kyc_toast_seen=1; path=/; max-age=86400';
    };

    // N'afficher qu'une fois par session (cookie)
    if (document.cookie.indexOf('kyc_toast_seen=1') === -1) {
        document.addEventListener('DOMContentLoaded', showKycToast);
    } else {
        toast && toast.remove();
    }

    // Fermer avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeKycToast();
    });
})();
</script>
@endif
