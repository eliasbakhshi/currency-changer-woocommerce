<?php
/* Close the direct access */
if ( ! defined( 'ABSPATH' ) ) { exit; }

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
        $formCurrencies = get_transient('lastHour');
        foreach ( $formCurrencies['rates'] as $rate => $value ) {
            printf('<span class="currency" style="width: 33%%; display: inline-block;">
                            <input type="checkbox" name="' . $this->get_field_name($rate) . '" id="' . $this->get_field_id($rate) . '" %s>
                            <label for="' . $this->get_field_id($rate) . '">' . $rate . '</label>
                        </span>', checked($instance[$rate], 'on', false));
        }
    }

    /* Front end of the widget */
    public function widget($args, $instances) {
        $baseCurrencyK = get_woocommerce_currency();
        $defaultCurrencyK = $_COOKIE['usersCurrency'];
        $defaultCurrencyKSymbol = get_woocommerce_currency_symbol($baseCurrencyK);
        $mainCurrencies = get_transient('lastHour');

        if ( !empty($instances) ) {
            $allowedCurrencies = array();
            foreach ($instances as $instance => $value) {
                $allowedCurrencies[] = $instance;
            }

            /* Exit if we receive an error */
            if ($mainCurrencies['error']) { return $mainCurrencies['error']; }

            $CurrenciesToShow = [];
            foreach ( $allowedCurrencies as $allowedCurrency ) {
                foreach ( $mainCurrencies['rates'] as $mainCurrency => $mainCurrencyValue ) {
                    if ( $allowedCurrency === $mainCurrency ) {
                        $CurrenciesToShow[$mainCurrency] = $mainCurrencyValue;
                    }
                }
            }

            /* Show currencies to user */
            print_r('<select id="krokedilCurrencyWidget" style="width: 100%">');
            foreach ($CurrenciesToShow as $mainCurrency => $value) {
                ($mainCurrency === $defaultCurrencyK) ? $selected = 'selected="selected"' : $selected = '';
                $symbol = get_woocommerce_currency_symbol($mainCurrency);
                printf('<option value="%s" id="%s" symbol="' . $symbol . '" %s>'.__("%s").'</option>', $value, $mainCurrency, $selected, $mainCurrency);
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
                    var numberDecimalsK = '%s';
                    var DecimalSeparatorSymbolK = '%s';
                    var ThousandSeparatorSymbolK = '%s';
                    </script>", $defaultCurrencyK, $mainCurrencies['rates'][$defaultCurrencyK], $defaultCurrencyKSymbol, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator());

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
add_action('widgets_init', function () {
    register_widget('KCurrency');
});