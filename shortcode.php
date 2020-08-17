<?php
/* Close the direct access */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function kCurrencyChanger($attrs, $content = null) {
    $attrs = shortcode_atts( array(
        'symbols'    => 'USD,SEK'
    ), $attrs, 'KCC' );

    $content = wp_strip_all_tags($content);
    $symbols = wp_strip_all_tags($attrs['symbols']);
    $adminCurrencies = ( empty($content) ) ? $symbols : $content;
    $adminCurrencies = explode(',', $adminCurrencies);
    $defaultCurrencyK = $_COOKIE['usersCurrency'];
    $mainCurrencies = get_transient('lastHour');

    /* Exit if we receive an error */
    if ($mainCurrencies['error']) {
        return $mainCurrencies['error'];
    } else {
        $CurrenciesToShow = [];
        foreach ( $adminCurrencies as $adminCurrency ) {
            foreach ( $mainCurrencies['rates'] as $mainCurrency => $mainCurrencyValue ) {
                if ( $adminCurrency === $mainCurrency ) {
                    $CurrenciesToShow[$mainCurrency] = $mainCurrencyValue;
                }
            }
        }
    }

    /* ---- Get value and show in the dropbox ---- */
    print_r('<select id="krokedilCurrencyShortcode" style="width: 100%; max-width: 200px;">');
    foreach ($CurrenciesToShow as $mainCurrency => $value) {
        ($mainCurrency === $defaultCurrencyK) ? $selected = 'selected="selected"' : $selected = '';
        $symbol = get_woocommerce_currency_symbol($mainCurrency);
        printf('<img src="coffee.png" width="10" height="10"><option value="%s" id="%s" symbol="' . $symbol . '" %s>'.__("%s").'</option>', $value, $mainCurrency, $selected, $mainCurrency);
    }
    print_r('</select>');

    if ($defaultCurrencyK) {
        $defaultCurrencyKSymbol = get_woocommerce_currency_symbol($defaultCurrencyK);
    } else {
        $defaultCurrencyK = get_woocommerce_currency();
        $defaultCurrencyKSymbol = get_woocommerce_currency_symbol($defaultCurrencyK);
    }

    /* Sent the current currency and currency symbol to the jquery file */
    printf("<script type='text/javascript'>
                var defaultCurrencyK = '%s';
                var defaultCurrencyKValue = '%s';
                var defaultCurrencyKSymbol = '%s';
                var numberDecimalsK = '%s';
                var DecimalSeparatorSymbolK = '%s';
                var ThousandSeparatorSymbolK = '%s';
            </script>", $defaultCurrencyK, $mainCurrencies['rates'][$defaultCurrencyK], $defaultCurrencyKSymbol,  wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator());
}

add_shortcode('KCC', 'kCurrencyChanger');