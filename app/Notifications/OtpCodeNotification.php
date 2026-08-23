<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification generique d'envoi de code OTP a 6 chiffres, reutilisee pour :
 * - la verification d'email a l'inscription (page id 29 "verify-email")
 * - l'authentification a deux facteurs (page id 30 "two-factor-auth")
 * Voir instructions_suggestions_tache: "reutiliser le meme mecanisme OTP email
 * que verify-email plutot qu'une lib TOTP complete, plus rapide a livrer sans bug".
 */
class OtpCodeNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $code,
        public string $context = 'verification', // 'verification' | 'two_factor'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->context === 'two_factor'
            ? __('app.auth.two_factor_title')
            : __('app.auth.verify_email_title');

        return (new MailMessage)
            ->subject($subject.' - Xendaro Fox')
            ->greeting(__('app.auth.welcome_title'))
            ->line($this->context === 'two_factor' ? __('app.auth.two_factor_text', ['email' => $notifiable->email]) : __('app.auth.verify_email_text', ['email' => $notifiable->email]))
            ->line(new \Illuminate\Support\HtmlString('<strong style="font-size:24px;letter-spacing:4px;">'.$this->code.'</strong>'))
            ->line(__('app.common.otp_validity'));
    }
}
