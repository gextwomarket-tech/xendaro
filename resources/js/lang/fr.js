/*
 * Objet de langue FR pour tout texte pilote par JS (placeholder, alertes, tooltips dynamiques).
 * Regle du projet: aucun texte JS ne doit etre code en dur, toujours passer par window.i18n.
 */
export default {
    placeholders: {
        email: 'Adresse email',
        password: 'Mot de passe',
        search: 'Rechercher un instrument...',
        amount: 'Montant',
        volume: 'Volume (lot)',
        stop_loss: 'Stop Loss',
        take_profit: 'Take Profit',
    },
    alerts: {
        insufficient_balance: 'Solde insuffisant pour cette opération.',
        trade_opened: 'Position ouverte avec succès.',
        trade_closed: 'Position clôturée avec succès.',
        copied_to_clipboard: 'Copié dans le presse-papiers.',
        generic_error: 'Une erreur est survenue, veuillez réessayer.',
    },
    tooltips: {
        buy: 'Acheter au prix du marché',
        sell: 'Vendre au prix du marché',
        toggle_demo_real: 'Basculer entre compte démo et compte réel',
    },
};
