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
    include_once 'initialization.php';
    include_once 'shortcode.php';
    include_once 'widget.php';
}
