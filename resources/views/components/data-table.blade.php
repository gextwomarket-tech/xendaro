@props(['headers' => []])
{{--
    Table paginee reutilisable (regle obligatoire: toute liste/tableau doit paginer, voir bonnes_pratiques_dev).
    Le composant Livewire parent gere la requete/pagination ($items->paginate(...)) et passe :items.
    Usage:
        <x-data-table :headers="['Instrument', 'Prix', 'Variation']">
            @foreach($items as $item) <tr>...</tr> @endforeach
            <x-slot:pagination>{{ $items->links() }}</x-slot:pagination>
        </x-data-table>
--}}
<div {{ $attributes->merge(['class' => 'rounded-sm border border-bordure-subtile bg-fond-card overflow-hidden']) }}>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            @if(count($headers))
                <thead class="bg-fond-surface text-texte-secondaire text-xs uppercase">
                    <tr>
                        @foreach($headers as $header)
                            <th class="px-4 py-3 font-medium whitespace-nowrap">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            <tbody class="divide-y divide-bordure-subtile text-texte-principal">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @isset($pagination)
        <div class="px-4 py-3 border-t border-bordure-subtile">
            {{ $pagination }}
        </div>
    @endisset
</div>
