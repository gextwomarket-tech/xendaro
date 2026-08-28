<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Http;
use RuntimeException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

/**
 * Transport Mail Laravel/Symfony pour EmailJS (alternative a Resend, sans verification
 * de domaine/DNS requise - EmailJS envoie via son propre service connecte).
 *
 * EmailJS fonctionne par template (variables nommees), pas par HTML brut : le template
 * cote dashboard EmailJS doit contenir {{subject}} et {{html_body}} dans son corps
 * (mode "code editor") pour afficher tel quel le contenu genere par nos Mailables/
 * notifications Laravel existants, sans avoir a les reecrire.
 */
class EmailJsTransport extends AbstractTransport
{
    public function __construct(
        private readonly ?string $serviceId,
        private readonly ?string $templateId,
        private readonly ?string $publicKey,
        private readonly ?string $privateKey,
    ) {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        if (! $this->serviceId || ! $this->templateId || ! $this->publicKey || ! $this->privateKey) {
            throw new RuntimeException('EmailJsTransport: EMAILJS_SERVICE_ID / TEMPLATE_ID / PUBLIC_KEY / PRIVATE_KEY manquant(s) dans .env.');
        }

        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $to = $email->getTo()[0] ?? null;
        if (! $to) {
            throw new RuntimeException('EmailJsTransport: destinataire manquant.');
        }

        $response = Http::asJson()->post('https://api.emailjs.com/api/v1.0/email/send', [
            'service_id' => $this->serviceId,
            'template_id' => $this->templateId,
            'user_id' => $this->publicKey,
            'accessToken' => $this->privateKey,
            'template_params' => [
                'to_email' => $to->getAddress(),
                'to_name' => $to->getName() ?: $to->getAddress(),
                'subject' => (string) $email->getSubject(),
                'html_body' => $email->getHtmlBody() ?? $email->getTextBody() ?? '',
            ],
        ]);

        if ($response->failed()) {
            throw new RuntimeException('EmailJs a refuse l\'envoi (HTTP '.$response->status().'): '.$response->body());
        }
    }

    public function __toString(): string
    {
        return 'emailjs';
    }
}
