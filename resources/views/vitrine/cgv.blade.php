<x-layouts.public :title="__('app.legal.cgv_title')">
    <x-legal-page :title="__('app.legal.cgv_title')" :content="$siteIdentifier?->cvg" />
</x-layouts.public>
