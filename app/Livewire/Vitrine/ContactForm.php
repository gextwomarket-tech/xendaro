<?php

namespace App\Livewire\Vitrine;

use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

/**
 * Formulaire de contact public (xendaro-fox-plan.json, page id 18 "contact").
 * Validation standard + throttle anti-spam basique + toast de succes.
 */
class ContactForm extends Component
{
    public string $nom = '';

    public string $email = '';

    public string $sujet = '';

    public string $message = '';

    protected function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'sujet' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    public function send(): void
    {
        $this->validate();

        $throttleKey = 'contact-form:'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $this->addError('message', __('app.contact.throttled'));

            return;
        }

        RateLimiter::hit($throttleKey, 300);

        $contactMessage = ContactMessage::create([
            'nom' => $this->nom,
            'email' => $this->email,
            'sujet' => $this->sujet,
            'message' => $this->message,
            'est_traite' => false,
        ]);

        $adminEmail = \App\Services\SiteIdentifierService::current()->email_pro_1;

        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new ContactMessageMail($contactMessage));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $this->reset(['nom', 'email', 'sujet', 'message']);
        $this->dispatch('toast', type: 'success', message: __('app.contact.success'));
    }

    public function render()
    {
        return view('livewire.vitrine.contact-form');
    }
}
