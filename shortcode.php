<?php
function kCurrencyChanger($attrs, $content = null) {
    $attrs = shortcode_atts( array(
        'base'      => get_woocommerce_currency(),
        'symbols'    => ''
    ), $attrs, 'KCC' );

    $symbols = ( empty($attrs['symbols']) ) ? $content : $attrs['symbols'];
    $defaultCurrencyK = 'USD';
    if (empty($defaultCurrencyK)) { $defaultCurrencyK = get_woocommerce_currency(); };

    $url = 'https://api.exchangeratesapi.io/latest?base=' . $attrs['base'] . "&symbols=" . $attrs['base'] . "," . $content;
//    return $url;
    $mainCurrencies = fetchInArray($url);
    $defaultCurrency = [get_woocommerce_currency() => 1];
    /* Exit if we receive an error */
    if ($mainCurrencies['error']) { return $mainCurrencies['error']; }


    /* Get value and show in the dropbox ---------------------------------------------- */
    $defaultCurrencyValueK = $mainCurrencies['rates'][$defaultCurrencyK];
    $mainCurrencies['rates'] = $defaultCurrency + $mainCurrencies['rates'];
    print_r('<select id="krokedilCurrency">');
    foreach ($mainCurrencies['rates'] as $mainCurrency => $value) {
        ($mainCurrency === $defaultCurrencyK) ? $selected = 'selected="selected"' : $selected = '';
        $symbol = get_woocommerce_currency_symbol($mainCurrency);
        printf('<option value="%s" id="%s" symbol="' . $symbol . '" %s>%s</option>', $value, $mainCurrency, $selected, $mainCurrency);
    }
    print_r('</select>');


//    if (is_checkout()) {
        /* Set default currency ------------------------------------------------------------ */
        add_filter('woocommerce_currency', function ($currency) use ($defaultCurrencyK) {
            return $defaultCurrencyK;
        });
//    }



    /*add_action( 'woocommerce_review_order_before_order_total', 'custom_cart_total' );
    add_action( 'woocommerce_before_cart_totals', 'custom_cart_total' );
    function custom_cart_total() {
        global $woocommerce;

        if ( is_admin() && ! defined( 'DOING_AJAX' ) )
            return;
        $woocommerce->cart->total  = $woocommerce->cart->total*0.25;
        var_dump( $woocommerce->cart->total);


        WC()->cart->total *= 0.5;
//        var_dump( WC()->cart->total);
    }*/





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