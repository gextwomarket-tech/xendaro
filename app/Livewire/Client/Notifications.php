<?php

namespace App\Livewire\Client;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Page id 39 "notifications" - centre de notifications (canal database Laravel Notifications).
 */
#[Layout('components.layouts.dashboard')]
class Notifications extends Component
{
    use WithPagination;

    public function markAsRead(string $id): void
    {
        Auth::user()->notifications()->where('id', $id)->first()?->markAsRead();
    }

    public function markAllAsRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->dispatch('toast', type: 'success', message: __('app.client.notifications.all_marked_read'));
    }

    public function render()
    {
        return view('livewire.client.notifications', [
            'notifications' => Auth::user()->notifications()->paginate(15),
        ]);
    }
}
