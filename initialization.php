<?php
/* Close the direct access */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$systemsCurrency = get_option('woocommerce_currency');

// get the user's currency
if ( isset($_COOKIE['usersCurrency']) ) {
    $usersCurrency = wp_strip_all_tags($_COOKIE['usersCurrency']);
} else {
    $usersCurrency = $systemsCurrency;
    setcookie('usersCurrency', $systemsCurrency, time() + (86400 * 365), '/' );
}

/* ----- Change the currency before page loading ----- */
if ( !is_admin() ) {
    if ( !empty($usersCurrency)) {
        add_action('init', function () use ($usersCurrency) {
            add_filter('woocommerce_currency', function ($currency) use ($usersCurrency) {
                return $usersCurrency;
            });
        });
    } else {
        add_action('init', function () use ($systemsCurrency) {
            add_filter('woocommerce_currency', function ($currency) use ($systemsCurrency) {
                return $systemsCurrency;
            });
        });
    }
}


/* Fetch currencies for max the last hour */
if ( get_transient('lastHour') === false ) {
    $url = esc_url(sprintf('https://api.exchangeratesapi.io/latest?base=%s', $systemsCurrency));
    $mainCurrencies = fetchInArray(esc_url($url));
    set_transient('lastHour', $mainCurrencies, 3600);
}

$mainCurrencies = get_transient('lastHour');
/* ---- Get the value of selected currency to work with ---- */
$displayCurrencyValue = ( !empty($mainCurrencies['rates'][$usersCurrency]) ) ? $mainCurrencies['rates'][$usersCurrency] : 1;
/* ---- Modify prices depend on currency value ----*/
modifyPrices($displayCurrencyValue);
