<x-mail::message>
# Nouveau message de contact

**Nom :** {{ $contactMessage->nom }}
**Email :** {{ $contactMessage->email }}
**Sujet :** {{ $contactMessage->sujet }}

{{ $contactMessage->message }}

<x-mail::button :url="url('/admin/contact-messages')">
Voir dans l'administration
</x-mail::button>

Xendaro Fox
</x-mail::message>
