<x-layouts.app>
  <x-slot name="title">Nous Contacter</x-slot>

  <div class="max-w-4xl mx-auto py-16">
    <h1 class="text-4xl font-bold mb-6 text-slate-900 dark:text-white">Nous Contacter</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
      <!-- Formulaire de contact -->
      <div>
        <form action="#" method="POST" class="space-y-4">
          @csrf
          
          <div>
            <label for="name" class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Nom Complet</label>
            <input type="text" id="name" name="name" required class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Email</label>
            <input type="email" id="email" name="email" required class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
          </div>

          <div>
            <label for="subject" class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Sujet</label>
            <input type="text" id="subject" name="subject" required class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
          </div>

          <div>
            <label for="message" class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Message</label>
            <textarea id="message" name="message" rows="5" required class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary resize-none"></textarea>
          </div>

          <button type="submit" class="w-full bg-primary text-white font-semibold py-2 px-4 rounded-lg hover:bg-primary-container transition-colors">
            Envoyer le Message
          </button>
        </form>
      </div>

      <!-- Informations de contact -->
      <div class="space-y-6">
        <div>
          <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Email</h3>
          <p class="text-slate-600 dark:text-slate-400">
            <a href="mailto:support@moontrade.com" class="text-primary hover:underline">support@moontrade.com</a>
          </p>
        </div>

        <div>
          <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Téléphone</h3>
          <p class="text-slate-600 dark:text-slate-400">
            <a href="tel:+33123456789" class="text-primary hover:underline">+33 (0) 1 23 45 67 89</a>
          </p>
        </div>

        <div>
          <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">WhatsApp</h3>
          <p class="text-slate-600 dark:text-slate-400">
            <a href="https://wa.me/33123456789" class="text-primary hover:underline">+33 6 12 34 56 78</a>
          </p>
        </div>

        <div>
          <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Heures d'Ouverture</h3>
          <p class="text-slate-600 dark:text-slate-400">
            Lundi - Vendredi: 09:00 - 18:00<br>
            Samedi - Dimanche: Fermé
          </p>
        </div>
      </div>
    </div>

    <div class="mt-12">
      <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary-container transition-colors">
        ← Retour à l'accueil
      </a>
    </div>
  </div>
</x-layouts.app>
