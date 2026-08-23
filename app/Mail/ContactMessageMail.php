<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Notification interne envoyee a l'equipe suite a un envoi du formulaire de contact
 * (xendaro-fox-plan.json, page id 18 "contact" > form > ContactForm).
 *
 * Envoi synchrone volontaire (pas de ShouldQueue) : aucun worker de queue n'est garanti
 * actif pour ce MVP (QUEUE_CONNECTION=database sans "queue:work" en tache de fond), un mail
 * en queue non traite resterait invisible. A reconsiderer en passant en production avec un
 * worker supervise.
 */
class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Xendaro Fox] Nouveau message de contact : '.$this->contactMessage->sujet,
            replyTo: [$this->contactMessage->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-message',
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
