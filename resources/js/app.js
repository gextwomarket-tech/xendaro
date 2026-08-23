import './bootstrap';
import './lang/index.js';

/*
 * Livewire v3 embarque et demarre deja sa propre instance d'Alpine.js en interne.
 * NE JAMAIS importer/demarrer un second paquet "alpinejs" ici: cela provoque
 * "Detected multiple instances of Alpine running" et casse l'hydratation des
 * composants Livewire dont la racine porte un x-data (ex: verify-email-form).
 * Si du JS custom a besoin d'Alpine, utiliser window.Alpine une fois l'evenement
 * "livewire:init" declenche (Livewire l'expose alors globalement).
 */
