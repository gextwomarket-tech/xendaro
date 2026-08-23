{{--
    Accordeon reutilisable (Alpine.js) pour toute liste de type question/reponse ou details repliables.
    Usage:
        <x-accordion>
            <x-accordion-item title="Question ?">Reponse...</x-accordion-item>
        </x-accordion>
--}}
<div {{ $attributes->merge(['class' => 'divide-y divide-bordure-subtile rounded-sm border border-bordure-subtile bg-fond-card']) }}>
    {{ $slot }}
</div>
