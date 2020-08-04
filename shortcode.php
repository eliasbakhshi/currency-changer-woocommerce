<?php
function kCurrencyChanger($attrs, $content = null) {
    $attrs = shortcode_atts( array(
//        'base'      => get_woocommerce_currency(), // Delete the base later and symbol if you want
        'symbols'    => ''
    ), $attrs, 'KCC' );

    $currencies = ( empty($attrs['symbols']) ) ? $content : $attrs['symbols'];
    if (empty($defaultCurrencyK)) { $defaultCurrencyK = get_woocommerce_currency(); };
    $systemsCurrency = 'SEK';
    $url = 'https://api.exchangeratesapi.io/latest?base=' . $systemsCurrency . "&symbols=" . $systemsCurrency . "," . $currencies;
    $mainCurrencies = fetchInArray($url);

    /* Exit if we receive an error */
    if ($mainCurrencies['error']) { return $mainCurrencies['error']; }

    /* ---- Get value and show in the dropbox ---- */
    print_r('<select id="krokedilCurrency">');
    foreach ($mainCurrencies['rates'] as $mainCurrency => $value) {
        ($mainCurrency === $defaultCurrencyK) ? $selected = 'selected="selected"' : $selected = '';
        $symbol = get_woocommerce_currency_symbol($mainCurrency);
        printf('<option value="%s" id="%s" symbol="' . $symbol . '" %s>%s</option>', $value, $mainCurrency, $selected, $mainCurrency);
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
                    var currencyAllSymbols = '%s';
                    var numberDecimalsK = '%s';
                    var DecimalSeparatorSymbolK = '%s';
                    var ThousandSeparatorSymbolK = '%s';
                    </script>", $defaultCurrencyK, $mainCurrencies['rates'][$defaultCurrencyK], $defaultCurrencyKSymbol, get_woocommerce_currency_symbols(), wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator());

}

add_shortcode('KCC', 'kCurrencyChanger');