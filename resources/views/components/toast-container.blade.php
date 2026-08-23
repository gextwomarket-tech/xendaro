{{--
    Conteneur global de toasts (voir Instructions_de_base.design_ui_ux.toasts).
    Ecoute l'evenement navigateur "toast" : window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: '...' } }))
    ou depuis Livewire: $dispatch('toast', type: 'success', message: '...')
--}}
<div
    x-data="{
        toasts: [],
        add(toast) {
            const id = Date.now() + Math.random();
            this.toasts.push({ id, type: toast.type ?? 'info', message: toast.message });
            setTimeout(() => this.remove(id), 4000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    x-on:toast.window="add($event.detail)"
    class="fixed top-4 right-4 z-[100] flex flex-col gap-2 w-80 max-w-[90vw]"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="rounded-sm bg-fond-card border-l-4 shadow-lg px-4 py-3 text-sm text-texte-principal"
            :class="{
                'border-succes': toast.type === 'success',
                'border-danger': toast.type === 'error',
                'border-avertissement': toast.type === 'warning',
                'border-info': toast.type === 'info',
            }"
        >
            <div class="flex items-start justify-between gap-3">
                <span x-text="toast.message"></span>
                <button type="button" class="text-texte-secondaire hover:text-texte-principal" x-on:click="remove(toast.id)">&times;</button>
            </div>
        </div>
    </template>
</div>
