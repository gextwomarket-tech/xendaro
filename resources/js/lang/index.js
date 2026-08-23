import fr from './fr';
import en from './en';

const dictionaries = { fr, en };

const currentLocale = document.documentElement.lang?.split('-')[0] || 'fr';

window.i18n = dictionaries[currentLocale] || dictionaries.fr;
