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

    include_once 'custom-functions.php';



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
            if (!empty($instances)) {
                $allowedCurrencies = array();
                foreach ($instances as $instance => $value) {
                    $allowedCurrencies[] = $instance;
                }

                /* Add the base as well */
                $allowedCurrencies[] = get_woocommerce_currency();
                $allowedCurrenciesStr = implode(",", $allowedCurrencies);

                $url = 'https://api.exchangeratesapi.io/latest?base=' . get_woocommerce_currency() . "&symbols=" . get_woocommerce_currency() . "," . $allowedCurrenciesStr;
                $mainCurrencies = fetchInArray($url);

                print_r('<select id="krokedilCurrency">');
                foreach ($mainCurrencies['rates'] as $mainCurrency => $value) {
                    $symbol = get_woocommerce_currency_symbol($mainCurrency);
                    printf('<option value="%s" id="%s" symbol="' . $symbol . '">%s</option>', $value, $mainCurrency, $mainCurrency);
                }
                print_r('</select>');

                /* Sent the current currency and currency symbol to the jquery file */
                printf("<script type='text/javascript'>
                    var baseCurrency = '%s';
                    var baseCurrencySymbol = '%s';
                    var currencyAllSymbols = %s;
                    </script>", get_woocommerce_currency(), get_woocommerce_currency_symbol(), json_encode(get_woocommerce_currency_symbols()));





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
