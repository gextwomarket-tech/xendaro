<x-layouts.public :title="__('app.legal.policies_title')">
    <x-legal-page :title="__('app.legal.policies_title')" :content="$siteIdentifier?->policies" />
</x-layouts.public>
