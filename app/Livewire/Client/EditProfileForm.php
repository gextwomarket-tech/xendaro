<?php

namespace App\Livewire\Client;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Page id 32 "edit-profile" - popup embarquee dans le layout dashboard,
 * declenchee depuis la mini-card sidebar et le dropdown navbar.
 */
class EditProfileForm extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public ?string $phone = null;

    public $avatar = null;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
    }

    protected function rules(): array
    {
        $userId = Auth::id();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$userId],
            'phone' => ['nullable', 'string', 'max:30'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $user = Auth::user();

        if ($this->avatar) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $validated['avatar_path'] = $this->avatar->store('avatars', 'public');
        }

        unset($validated['avatar']);

        $user->update($validated);

        $this->dispatch('toast', type: 'success', message: __('app.client.profile.save_success'));
        $this->dispatch('close-modal', name: 'edit-profile');
    }

    public function render()
    {
        return view('livewire.client.edit-profile-form');
    }
}
