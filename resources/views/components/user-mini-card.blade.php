@props(['user', 'showEditButton' => false, 'size' => 'md'])
@php
    $avatarSize = $size === 'sm' ? 'w-9 h-9' : 'w-10 h-10';
    $initial = \Illuminate\Support\Str::of($user->name ?? '?')->substr(0, 1)->upper();
@endphp
{{--
    Composant reutilisable: avatar rond + point de statut vert + nom + email.
    Reutilise a l'identique par la sidebar dashboard (id 31) et la sidebar droite
    de la page Trade (id 37, cf. reference_dashboard_samify) - ne pas dupliquer.
    showEditButton=true ajoute le bouton "Modifier le profil" (ouvre la modale edit-profile).
--}}
<div {{ $attributes->merge(['class' => '']) }}>
    <div class="flex items-center gap-3 min-w-0">
        <div class="relative shrink-0">
            <div class="{{ $avatarSize }} rounded-full bg-couleur-principale/15 flex items-center justify-center text-sm font-semibold text-couleur-principale overflow-hidden">
                @if(!empty($user->avatar_path))
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($user->avatar_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    {{ $initial }}
                @endif
            </div>
            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-succes rounded-full border-2 border-fond-surface"></span>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-medium text-texte-principal truncate">{{ $user->name ?? '' }}</p>
            <p class="text-xs text-texte-secondaire truncate">{{ $user->email ?? '' }}</p>
        </div>
    </div>

    {{ $slot ?? '' }}

    @if($showEditButton)
        <button
            type="button"
            x-on:click="$dispatch('open-modal', { name: 'edit-profile' })"
            class="mt-3 w-full text-xs font-medium rounded-sm border border-bordure-subtile text-texte-secondaire hover:text-texte-principal hover:border-couleur-principale/50 py-2 transition"
        >
            {{ __('app.client.edit_profile_button') }}
        </button>
    @endif
</div>
