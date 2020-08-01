<?php

/**
 * Plugin Name: Krokedil Currency Changer
 * Plugin URI: http://woocommerce.com/products/woocommerce-extension/
 * Description: This plugin lets you to change the currency that is used in WooCommerce and change the prices and calculations in WooCommerce on the product page, cart page and checkout page.
 * Version: 1.0.0
 * Author: Elias Bakhshi
 * Author URI: https://www.linkedin.com/in/eliasbakhshi/
 * Developer: Elias Bakhshi
 * Developer URI: https://www.linkedin.com/in/eliasbakhshi/
 * Text Domain: krokedilcurrency
 *
 * WC requires at least: 3.0
 * WC tested up to: 4.3.1
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

/* Close the direct access */
if (!defined('ABSPATH')) {
    exit;
}

/* Check if WooCommerce is active */
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    session_start();
    $_SESSION['usersCurrency'] = 'DKK';


    include_once 'custom-functions.php';
    include_once 'shortcode.php';



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
        public function widget($args, $instances)
        {
            $defaultCurrencyK = $_SESSION['usersCurrency'];
            $baseCurrencyK = get_woocommerce_currency();
            empty($defaultCurrencyK) ? $defaultCurrencyK = get_woocommerce_currency() : $defaultCurrencyK;
            $defaultCurrencyKSymbol = get_woocommerce_currency_symbol($baseCurrencyK);
//            echo "old" . $defaultCurrencyK;
            /* Change the currency before page loading */
            add_action('init', function () use ($defaultCurrencyK) {
                add_filter('woocommerce_currency', function ($currency) use ($defaultCurrencyK) {
                    return $defaultCurrencyK;
                    echo $defaultCurrencyK;
                });
            });

//            echo "new" . get_woocommerce_currency();





         /*   add_action( 'woocommerce_before_calculate_totals', 'add_custom_price' );

            function add_custom_price( $cart_object ) {
                var_dump($cart_object->cart_contents);
                $custom_price = 5550000; // This will be your custome price
                foreach ( $cart_object->cart_contents as $key => $value ) {
                    $value['data']->price = $custom_price;
//                    $value['data']->set_price($custom_price);
                    // for WooCommerce version 3+ use:

                }
            }*/





            if ( !empty($instances) ) {
                $allowedCurrencies = array();
                foreach ($instances as $instance => $value) {
                    $allowedCurrencies[] = $instance;
                }

                /* Add the base as well */
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
    add_action('widgets_init', function () {
        register_widget('KCurrency');
    });


}
