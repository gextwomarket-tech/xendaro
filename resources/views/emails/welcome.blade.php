<x-mail::message>
# Bienvenue, {{ $user->name }} !

Votre compte Xendaro Fox a bien été créé. Vous pouvez dès maintenant accéder à votre espace client pour configurer votre profil et commencer à trader.

<x-mail::button :url="route('client.dashboard')">
Accéder à mon espace client
</x-mail::button>

Si vous n'êtes pas à l'origine de cette inscription, vous pouvez ignorer cet email.

Xendaro Fox
</x-mail::message>
