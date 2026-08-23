<x-layouts.public :title="__('app.legal.cookies_title')">
    <x-legal-page :title="__('app.legal.cookies_title')" :content="$siteIdentifier?->cookies" />
</x-layouts.public>
