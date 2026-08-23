<div class="space-y-6 max-w-3xl">
    <div class="flex items-center justify-between gap-4">
        <div>
            <a href="{{ route('client.support') }}" class="text-xs text-couleur-principale hover:underline">&larr; {{ __('app.client.support.back_to_tickets') }}</a>
            <h1 class="font-display text-2xl font-bold text-texte-principal mt-1">{{ $ticket->sujet }}</h1>
        </div>
        <x-status-badge :status="$ticket->statut" :map="[
            'ouvert' => ['label' => __('app.client.support.status_ouvert'), 'class' => 'bg-avertissement/10 text-avertissement'],
            'en_cours' => ['label' => __('app.client.support.status_en_cours'), 'class' => 'bg-info/10 text-info'],
            'ferme' => ['label' => __('app.client.support.status_ferme'), 'class' => 'bg-succes/10 text-succes'],
        ]" />
    </div>

    <div class="rounded-sm bg-fond-card border border-bordure-subtile p-5 space-y-4">
        @foreach($messages as $msg)
            <div class="flex {{ $msg->est_admin ? 'justify-start' : 'justify-end' }}">
                <div class="max-w-[80%] rounded-sm px-4 py-3 {{ $msg->est_admin ? 'bg-fond-surface border border-bordure-subtile' : 'bg-couleur-principale/10 border border-couleur-principale/20' }}">
                    <p class="text-xs font-medium text-texte-secondaire mb-1">{{ $msg->est_admin ? __('app.client.support.support_team') : __('app.client.support.you') }}</p>
                    <p class="text-sm text-texte-principal whitespace-pre-line">{{ $msg->message }}</p>
                    <p class="mt-1 text-[10px] text-texte-secondaire">{{ $msg->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <livewire:client.reply-ticket-form :ticket-id="$ticket->id" :key="'reply-'.$ticket->id" />
</div>
