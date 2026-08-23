@props(['options' => [], 'placeholder' => null])
{{-- wire:model.live doit etre passe par le composant appelant via $attributes. $options = [value => label] --}}
<select {{ $attributes->merge(['class' => 'rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale']) }}>
    @if($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach($options as $value => $label)
        <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
</select>
