@props(['cols' => 3])
@php
    $colsClass = [
        2 => 'sm:grid-cols-2',
        3 => 'sm:grid-cols-2 lg:grid-cols-3',
        4 => 'sm:grid-cols-2 lg:grid-cols-4',
    ][$cols] ?? 'sm:grid-cols-2 lg:grid-cols-3';
@endphp
<div {{ $attributes->merge(['class' => "grid grid-cols-1 $colsClass gap-5"]) }}>
    {{ $slot }}
</div>
