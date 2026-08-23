<div class="space-y-6">
    <div class="flex items-center justify-between gap-4">
        <h1 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.client.notifications.title') }}</h1>
        <button type="button" wire:click="markAllAsRead" class="text-sm text-couleur-principale hover:underline">
            {{ __('app.client.notifications.mark_all_read') }}
        </button>
    </div>

    <div class="space-y-2">
        @forelse($notifications as $notification)
            <div class="rounded-sm bg-fond-card border border-bordure-subtile p-4 flex items-start justify-between gap-4 {{ $notification->read_at ? 'opacity-60' : '' }}">
                <div class="min-w-0">
                    <p class="text-sm text-texte-principal">{{ $notification->data['message'] ?? $notification->data['title'] ?? __('app.client.notifications.title') }}</p>
                    <p class="mt-1 text-xs text-texte-secondaire">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
                @if(!$notification->read_at)
                    <button type="button" wire:click="markAsRead('{{ $notification->id }}')" class="shrink-0 text-xs text-couleur-principale hover:underline">
                        {{ __('app.client.notifications.mark_read') }}
                    </button>
                @endif
            </div>
        @empty
            <p class="text-center text-texte-secondaire text-sm py-10">{{ __('app.client.notifications.no_notifications') }}</p>
        @endforelse
    </div>

    <div>{{ $notifications->links() }}</div>
</div>
