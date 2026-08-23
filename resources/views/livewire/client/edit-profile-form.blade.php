<div>
    <h3 class="font-display text-lg font-semibold text-texte-principal">{{ __('app.client.profile.title') }}</h3>

    <form wire:submit="save" class="mt-5 space-y-4">
        <div>
            <label class="block text-sm font-medium text-texte-secondaire mb-2">{{ __('app.client.profile.avatar') }}</label>
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-couleur-principale/15 flex items-center justify-center text-lg font-semibold text-couleur-principale overflow-hidden shrink-0">
                    @if($avatar)
                        <img src="{{ $avatar->temporaryUrl() }}" class="w-full h-full object-cover">
                    @elseif(auth()->user()->avatar_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->avatar_path) }}" class="w-full h-full object-cover">
                    @else
                        {{ \Illuminate\Support\Str::of(auth()->user()->name)->substr(0,1)->upper() }}
                    @endif
                </div>
                <input type="file" wire:model="avatar" accept="image/*" class="text-sm text-texte-secondaire file:mr-3 file:rounded-sm file:border-0 file:bg-fond-surface file:text-texte-principal file:px-3 file:py-2 file:text-xs">
            </div>
            @error('avatar') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="edit-name" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.client.profile.name') }}</label>
            <input type="text" id="edit-name" wire:model="name"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="edit-email" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.client.profile.email') }}</label>
            <input type="email" id="edit-email" wire:model="email"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('email') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="edit-phone" class="block text-sm font-medium text-texte-secondaire mb-1">{{ __('app.client.profile.phone') }}</label>
            <input type="text" id="edit-phone" wire:model="phone"
                class="w-full rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-principal focus:outline-none focus:ring-1 focus:ring-couleur-principale">
            @error('phone') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" x-on:click="$dispatch('close-modal', { name: 'edit-profile' })" class="inline-flex items-center rounded-sm border border-bordure-subtile text-texte-secondaire hover:text-texte-principal text-sm font-medium px-4 py-2 transition">
                {{ __('app.common.cancel') }}
            </button>
            <button type="submit" wire:loading.attr="disabled" wire:target="save,avatar"
                class="inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal text-sm font-semibold px-4 py-2 hover:brightness-110 transition disabled:opacity-60">
                <span wire:loading.remove wire:target="save">{{ __('app.common.save') }}</span>
                <span wire:loading wire:target="save">{{ __('app.common.loading') }}</span>
            </button>
        </div>
    </form>
</div>
