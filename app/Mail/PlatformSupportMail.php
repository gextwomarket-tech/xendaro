<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PlatformSupportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subject,
        public string $body,
        public ?string $recipientEmail = null,
    ) {
        $this->recipientEmail = $recipientEmail ?? config('app.support_email', 'support@moontrade.com');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('mail.from.address'),
            subject: $this->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.platform-support',
            with: [
                'body' => $this->body,
                'supportEmail' => $this->recipientEmail,
            ],
        );
    }
}
