<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $code) {}

    public function build(): self
    {
        return $this->subject('Votre code de réinitialisation')
                    ->html("
                        <h2>Code de réinitialisation</h2>
                        <p>Votre code est : <strong style='font-size:24px'>{$this->code}</strong></p>
                        <p>Ce code expire dans <strong>15 minutes</strong>.</p>
                    ");
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password Code Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
