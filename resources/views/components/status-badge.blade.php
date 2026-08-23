@props(['status', 'map' => []])
@php
    $defaultMap = [
        'en_attente' => ['label' => 'En attente', 'class' => 'bg-avertissement/10 text-avertissement'],
        'valide' => ['label' => 'Validé', 'class' => 'bg-succes/10 text-succes'],
        'refuse' => ['label' => 'Refusé', 'class' => 'bg-danger/10 text-danger'],
        'ouvert' => ['label' => 'Ouvert', 'class' => 'bg-info/10 text-info'],
        'cloture' => ['label' => 'Clôturé', 'class' => 'bg-texte-secondaire/10 text-texte-secondaire'],
    ];
    $map = array_merge($defaultMap, $map);
    $entry = $map[$status] ?? ['label' => $status, 'class' => 'bg-texte-secondaire/10 text-texte-secondaire'];
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ' . $entry['class']]) }}>
    {{ $entry['label'] }}
</span>
