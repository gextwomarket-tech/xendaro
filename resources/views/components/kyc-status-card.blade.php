<!-- KYC Status Card - Improved Design -->
@php
    $user = auth()->user();
    $kycStatus = $user?->kyc_status;
    $kycLevel = $user?->kyc_level ?? 0;
    
    $statusConfig = [
        'verified' => [
            'color' => 'green',
            'icon' => 'verified_user',
            'text' => 'Vérification Complétée',
            'message' => 'Votre identité a été vérifiée avec succès',
            'badge' => 'Vérifié',
        ],
        'pending' => [
            'color' => 'amber',
            'icon' => 'pending_actions',
            'text' => 'Vérification en Attente',
            'message' => 'Votre demande de vérification est en cours d\'examen',
            'badge' => 'En attente',
        ],
        'rejected' => [
            'color' => 'red',
            'icon' => 'cancel',
            'text' => 'Vérification Rejetée',
            'message' => 'Veuillez soumettre à nouveau vos documents',
            'badge' => 'Rejeté',
        ],
        'unverified' => [
            'color' => 'slate',
            'icon' => 'person_add',
            'text' => 'Vérification Non Initiée',
            'message' => 'Complétez votre vérification KYC pour accéder à tous les services',
            'badge' => 'Non vérifié',
        ],
    ];
    
    $config = $statusConfig[$kycStatus] ?? $statusConfig['unverified'];
    $colorClasses = [
        'green' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800',
        'amber' => 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800',
        'red' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800',
        'slate' => 'bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700',
    ];
    $textClasses = [
        'green' => 'text-green-700 dark:text-green-300',
        'amber' => 'text-amber-700 dark:text-amber-300',
        'red' => 'text-red-700 dark:text-red-300',
        'slate' => 'text-slate-700 dark:text-slate-300',
    ];
    $iconClasses = [
        'green' => 'text-green-600 dark:text-green-400',
        'amber' => 'text-amber-600 dark:text-amber-400',
        'red' => 'text-red-600 dark:text-red-400',
        'slate' => 'text-slate-600 dark:text-slate-400',
    ];
@endphp

<div class="{{$colorClasses[$config['color']]}} border rounded-lg p-6 transition-all hover:shadow-md">
    <!-- Header -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-white dark:bg-slate-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl {{$iconClasses[$config['color']]}}">{{ $config['icon'] }}</span>
            </div>
            <div>
                <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ $config['text'] }}</h3>
                <p class="text-sm {{$textClasses[$config['color']]}}">{{ $config['message'] }}</p>
            </div>
        </div>
        
        <!-- Badge -->
        <span class="px-3 py-1 text-xs font-semibold rounded-full whitespace-nowrap
            @if ($config['color'] === 'green')
                bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200
            @elseif ($config['color'] === 'amber')
                bg-amber-200 dark:bg-amber-800 text-amber-800 dark:text-amber-200
            @elseif ($config['color'] === 'red')
                bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200
            @else
                bg-slate-300 dark:bg-slate-600 text-slate-800 dark:text-slate-200
            @endif
        ">
            {{ $config['badge'] }}
        </span>
    </div>

    <!-- Progress Indicator -->
    @if ($kycStatus !== 'verified')
        <div class="mb-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-400">Progrès KYC</span>
                <span class="text-xs font-semibold {{$textClasses[$config['color']]}}">
                    @if ($kycStatus === 'pending') 75% @elseif ($kycStatus === 'rejected') 25% @else 0% @endif
                </span>
            </div>
            <div class="w-full bg-gray-300 dark:bg-slate-700 rounded-full h-2">
                <div class="h-2 rounded-full transition-all duration-300
                    @if ($config['color'] === 'green')
                        bg-green-500
                    @elseif ($config['color'] === 'amber')
                        bg-amber-500
                    @elseif ($config['color'] === 'red')
                        bg-red-500
                    @else
                        bg-slate-500
                    @endif
                " style="width: @if ($kycStatus === 'pending') 75% @elseif ($kycStatus === 'rejected') 25% @else 0% @endif"></div>
            </div>
        </div>
    @endif

    <!-- KYC Level Info -->
    @if ($kycStatus === 'verified')
        <div class="bg-white dark:bg-slate-700 rounded p-3 mb-4">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-lg text-green-600 dark:text-green-400">check_circle</span>
                <div>
                    <p class="text-sm font-medium text-slate-900 dark:text-white">Niveau KYC: <span class="font-bold">Niveau {{ $kycLevel }}</span></p>
                    <p class="text-xs text-slate-600 dark:text-slate-400">Limite de transaction: Illimitée</p>
                </div>
            </div>
        </div>
    @endif

    <!-- CTA -->
    <div class="flex gap-3">
        @if ($kycStatus !== 'verified')
            <a href="{{ route('kyc.show') }}" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg font-medium transition-all
                @if ($config['color'] === 'green')
                    bg-green-600 hover:bg-green-700 text-white
                @elseif ($config['color'] === 'amber')
                    bg-amber-600 hover:bg-amber-700 text-white
                @elseif ($config['color'] === 'red')
                    bg-red-600 hover:bg-red-700 text-white
                @else
                    bg-slate-600 hover:bg-slate-700 text-white
                @endif
            ">
                <span class="material-symbols-outlined text-[18px]">
                    @if ($kycStatus === 'rejected') refresh @else arrow_forward @endif
                </span>
                @if ($kycStatus === 'rejected') Soumettre à Nouveau @elseif ($kycStatus === 'pending') Voir le Statut @else Commencer @endif
            </a>
        @else
            <div class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">
                <span class="material-symbols-outlined text-[18px]">verified</span>
                Accès Complet
            </div>
        @endif
        
        <button type="button" onclick="toggleKycDetails()" class="px-4 py-2 rounded-lg font-medium border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 transition-all">
            <span class="material-symbols-outlined text-[18px]">info</span>
        </button>
    </div>

    <!-- Details Section (Hidden by default) -->
    <div id="kycDetails" class="hidden mt-4 pt-4 border-t border-current border-opacity-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                <span class="text-slate-700 dark:text-slate-300">Pièce d'identité validée</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] opacity-30">check_circle</span>
                <span class="text-slate-500 dark:text-slate-500">Adresse de résidence</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] opacity-30">check_circle</span>
                <span class="text-slate-500 dark:text-slate-500">Vérification bancaire</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] opacity-30">check_circle</span>
                <span class="text-slate-500 dark:text-slate-500">Biométrie faciale</span>
            </div>
        </div>
    </div>
</div>

<script>
function toggleKycDetails() {
    const details = document.getElementById('kycDetails');
    details.classList.toggle('hidden');
}
</script>
