<?php
class KCurrency extends WP_Widget {
    public function __construct(){
        $currenyArgs = array(
            'classname' => 'krokedil-currency',
            'description' => 'Krokedil Currency Changer'
        );
        parent::__construct( 'krokedil_currency', 'krokedil Currency Changer', $currenyArgs);
    }

    /* Back end of the widget */
    public function form($instance){
        $formCurrencies = fetchInArray('https://api.exchangeratesapi.io/latest');
        foreach ( $formCurrencies['rates'] as $rate => $value ) {
            printf('<span style="width: 20%%; display: inline-block;">
                            <input type="checkbox" name="' . $this->get_field_name($rate) . '" id="' . $this->get_field_id($rate) . '" %s>
                            <label for="' . $this->get_field_id($rate) . '">' . $rate . '</label>
                        </span>', checked($instance[$rate], 'on', false));
        }
    }

    /* Front end of the widget */
    public function widget($args, $instances) {
        $baseCurrencyK = get_woocommerce_currency();
        empty($defaultCurrencyK) ? $defaultCurrencyK = get_woocommerce_currency() : $defaultCurrencyK;
        $defaultCurrencyKSymbol = get_woocommerce_currency_symbol($baseCurrencyK);

        if ( !empty($instances) ) {
            $allowedCurrencies = array();
            foreach ($instances as $instance => $value) {
                $allowedCurrencies[] = $instance;
            }

            $allowedCurrenciesStr = implode(",", $allowedCurrencies);

            $url = 'https://api.exchangeratesapi.io/latest?base=' . get_woocommerce_currency() . "&symbols=" . $allowedCurrenciesStr;
            $mainCurrencies = fetchInArray($url);
            /* Exit if we receive an error */
            if ($mainCurrencies['error']) { return $mainCurrencies['error']; }

            /* Add the base to the array */
            $defaultCurrency = [get_woocommerce_currency() => 1];
            $mainCurrencies['rates'] = $defaultCurrency + $mainCurrencies['rates'];

            /* Show currencies to user */
            print_r('<select id="krokedilCurrency">');
            foreach ($mainCurrencies['rates'] as $mainCurrency => $value) {
                $symbol = get_woocommerce_currency_symbol($mainCurrency);
                printf('<option value="%s" id="%s" symbol="' . $symbol . '">%s</option>', $value, $mainCurrency, $mainCurrency);
            }
            print_r('</select>');
            /* Pass the info to the script */
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

        } else {
            printf('<p>There is no currency to choose.</p>');
        }
    }

    /* When user clicks the save button in the widgets page */
    public function update($new_instance, $old_instance) {
        $old_instance = $new_instance;
        return $old_instance;
    }
}
add_action('widgets_init22', function () {
    register_widget('KCurrency2');
});