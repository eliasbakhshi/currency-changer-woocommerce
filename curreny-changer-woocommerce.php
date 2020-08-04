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
    include_once 'shortcode.php';
    include_once 'widget.php';


    // Makes reapetition
    /*function filter_woocommerce_get_regular_price2( $price2, $product ) {
        echo $price2 . "ttt";
        return $price2;
    }
    add_filter( 'woocommerce_get_regular_price', 'filter_woocommerce_get_regular_price2', 71, 2 );
    add_filter( 'woocommerce_product_get_regular_price', 'filter_woocommerce_get_regular_price2', 72, 2 );*/













    // This add just new price for the product and display it in the page
    /*add_filter('woocommerce_get_price', 'woocommerce_change_by_currency', 10, 2);
    function woocommerce_change_by_currency($price, $product) {
        global $post;
        $post_id = $post->ID;

        $product = WC_get_product($post_id);

        $price = ( $price + 100);

        return $price;
    }*/





/*//------------------------------------------------------ this words just in checkout table and not in invoices
    add_action( 'woocommerce_review_order_before_order_total', 'custom_cart_total' );
    add_action( 'woocommerce_before_cart_totals', 'custom_cart_total' );
    function custom_cart_total() {

        if ( is_admin() && ! defined( 'DOING_AJAX' ) )
            return;

        WC()->cart->total *= 0.5;
        var_dump( WC()->cart->total);
    }*/


// this is for cart payment because they use order->get_total to fetch cart grand total --------------
    /*add_filter( 'woocommerce_order_amount_total', 'custom_cart_total2' );
    function custom_cart_total2($order_total) {
        return $order_total *= 0.25;
    }*/










}
