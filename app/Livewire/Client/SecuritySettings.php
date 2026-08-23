<?php

namespace App\Livewire\Client;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Page id 33 "security-settings" - changement de mot de passe, toggle 2FA, sessions actives.
 */
#[Layout('components.layouts.dashboard')]
class SecuritySettings extends Component
{
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public bool $two_factor_enabled = false;

    public function mount(): void
    {
        $this->two_factor_enabled = (bool) Auth::user()->two_factor_enabled;
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (! Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', __('app.client.security.current_password_invalid'));

            return;
        }

        $user->update(['password' => Hash::make($this->password)]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->dispatch('toast', type: 'success', message: __('app.client.security.password_updated'));
    }

    public function toggleTwoFactor(): void
    {
        $this->two_factor_enabled = ! $this->two_factor_enabled;

        Auth::user()->update(['two_factor_enabled' => $this->two_factor_enabled]);

        $this->dispatch('toast', type: 'success', message: $this->two_factor_enabled
            ? __('app.client.security.two_factor_enabled')
            : __('app.client.security.two_factor_disabled'));
    }

    public function terminateSession(string $sessionId): void
    {
        DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', Auth::id())
            ->where('id', '!=', session()->getId())
            ->delete();

        $this->dispatch('toast', type: 'success', message: __('app.client.security.session_terminated'));
    }

    public function render()
    {
        $sessions = DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderByDesc('last_activity')
            ->get();

        return view('livewire.client.security-settings', [
            'sessions' => $sessions,
            'currentSessionId' => session()->getId(),
        ]);
    }
}
