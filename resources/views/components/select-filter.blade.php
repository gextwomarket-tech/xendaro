@props(['options' => [], 'placeholder' => null, 'selected' => null])
{{--
    wire:model.live doit etre passe par le composant appelant via $attributes pour un usage Livewire.
    Pour un filtre GET classique (formulaire non-Livewire), passer :selected="$valeurCourante" pour conserver
    la selection apres rechargement de page.
    $options = [value => label]
--}}
<select {{ $attributes->merge(['class' => 'rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale']) }}>
    @if($placeholder)
        <option value="" @selected($selected === null || $selected === '')>{{ $placeholder }}</option>
    @endif
    @foreach($options as $value => $label)
        <option value="{{ $value }}" @selected((string) $selected === (string) $value)>{{ $label }}</option>
    @endforeach
</select>
