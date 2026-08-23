@props(['title', 'content' => null, 'updatedAt' => null])
{{--
    Vue legale generique reutilisee par cgv, policies, cookies, risk-disclosure, aml-policy
    (voir xendaro-fox-plan.json page id 20 - "seul le champ source change").
    $content peut etre du texte brut (converti via nl2br) ou du HTML deja sanitize.
--}}
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal mb-2">{{ $title }}</h1>
    @if($updatedAt)
        <p class="text-sm text-texte-secondaire mb-8">{{ __('app.legal.last_update') }} {{ $updatedAt }}</p>
    @else
        <div class="mb-8"></div>
    @endif

    <div class="prose prose-invert max-w-none text-texte-secondaire leading-relaxed space-y-4">
        @php
            $text = $content ?? trim($slot);
        @endphp
        @if($text)
            @if(strip_tags($text) === $text)
                <p>{!! nl2br(e($text)) !!}</p>
            @else
                {!! $text !!}
            @endif
        @else
            <p>{{ __('app.legal.no_content') }}</p>
        @endif
    </div>
</section>
