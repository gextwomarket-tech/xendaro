{{--
    Bandeau de consentement cookies (xendaro-fox-plan.json, page id 22 "cookies" > popup_modal).
    Persistance via localStorage (choix minimaliste MVP: Accepter / Refuser).
    Injecte une seule fois via x-layouts.public.
--}}
<div
    x-data="{
        show: false,
        init() {
            this.show = !localStorage.getItem('xendaro_cookie_consent');
        },
        choose(value) {
            localStorage.setItem('xendaro_cookie_consent', value);
            document.cookie = 'xendaro_cookie_consent=' + value + ';path=/;max-age=31536000';
            this.show = false;
        },
    }"
    x-show="show"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    class="fixed bottom-0 inset-x-0 z-50 border-t border-bordure-subtile bg-fond-card/95 backdrop-blur"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center gap-4">
        <p class="text-sm text-texte-secondaire flex-1">
            {{ __('app.legal.cookies_banner_text') }}
            <a href="{{ url('/cookies') }}" class="text-couleur-principale hover:underline">{{ __('app.legal.cookies_title') }}</a>
        </p>
        <div class="flex items-center gap-3 shrink-0">
            <button type="button" x-on:click="choose('declined')" class="rounded-sm border border-bordure-subtile text-texte-secondaire hover:text-texte-principal text-sm font-medium px-4 py-2 transition">
                {{ __('app.legal.cookies_decline') }}
            </button>
            <button type="button" x-on:click="choose('accepted')" class="rounded-sm bg-couleur-principale text-fond-principal text-sm font-semibold px-4 py-2 hover:brightness-110 transition">
                {{ __('app.legal.cookies_accept') }}
            </button>
        </div>
    </div>
</div>
