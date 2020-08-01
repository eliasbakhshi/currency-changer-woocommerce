<?php
function kCurrencyChanger($attrs, $content = null) {
    $attrs = shortcode_atts( array(
        'base'      => get_woocommerce_currency(),
        'symbols'    => ''
    ), $attrs, 'KCC' );
    $symbols = ( empty($attrs['symbols']) ) ? $content : $attrs['symbols'];
    $defaultCurrencyK = $_SESSION['usersCurrency'];
    if (empty($defaultCurrencyK)) { $defaultCurrencyK = get_woocommerce_currency(); };

    $url = 'https://api.exchangeratesapi.io/latest?base=' . $attrs['base'] . "&symbols=" . $attrs['base'] . "," . $content;
//    return $url;
    $mainCurrencies = fetchInArray($url);
    $defaultCurrency = [get_woocommerce_currency() => 1];
    /* Exit if we receive an error */
    if ($mainCurrencies['error']) { return $mainCurrencies['error']; }

    $mainCurrencies['rates'] = $defaultCurrency + $mainCurrencies['rates'];
    print_r('<select id="krokedilCurrency">');
    foreach ($mainCurrencies['rates'] as $mainCurrency => $value) {
        ($mainCurrency === $defaultCurrencyK) ? $selected = 'selected' : $selected = '';
        $symbol = get_woocommerce_currency_symbol($mainCurrency);
        printf('<option value="%s" id="%s" symbol="' . $symbol . '" selected="%s">%s</option>', $value, $mainCurrency, $selected, $mainCurrency);
    }
    print_r('</select>');
//    echo $defaultCurrencyK;





    //--------------
//    if (is_checkout()) {
//           add_action( 'woocommerce_before_calculate_totals', 'add_custom_price' );
//
//            function add_custom_price( $cart_object ) {
//                var_dump($cart_object->cart_contents);
//                $custom_price = 2; // This will be your custome price
//                foreach ( $cart_object->cart_contents as $key => $value ) {
//                    $value['data']->price = $custom_price;
//                    $value['data']->set_price($custom_price);
//                     for WooCommerce version 3+ use:
//
//                }
//            }
//        }



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