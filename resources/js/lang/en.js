/*
 * EN language object for any JS-driven text (placeholders, JS alerts, dynamic tooltips).
 * Project rule: never hardcode JS text, always go through window.i18n.
 */
export default {
    placeholders: {
        email: 'Email address',
        password: 'Password',
        search: 'Search an instrument...',
        amount: 'Amount',
        volume: 'Volume (lot)',
        stop_loss: 'Stop Loss',
        take_profit: 'Take Profit',
    },
    alerts: {
        insufficient_balance: 'Insufficient balance for this operation.',
        trade_opened: 'Position opened successfully.',
        trade_closed: 'Position closed successfully.',
        copied_to_clipboard: 'Copied to clipboard.',
        generic_error: 'Something went wrong, please try again.',
    },
    tooltips: {
        buy: 'Buy at market price',
        sell: 'Sell at market price',
        toggle_demo_real: 'Switch between demo and real account',
    },
};
