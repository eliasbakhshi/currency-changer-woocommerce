<?php
/* Close the direct access */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ----- Fetch info ----- */
function fetchInArray($url) {
    $url = strval($url);
    $curl = curl_init($url);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    /* Return it as array */
    return json_decode($response, true);
}

/* ----- Add script to WP ----- */
function krokedilScripts() {
    wp_enqueue_script('krokedil_currency', plugin_dir_url( __FILE__ ) . 'js/script.js', 'jquery', '1.0', true);
    wp_enqueue_script('krokedil_currency', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', 'jquery', '5.3.1');
}
add_action('wp_enqueue_scripts', 'krokedilScripts');


/* ----- Modify prices ----- */
function modifyPrices($currencyValue) {
    $currencyValue = wp_strip_all_tags($currencyValue);
    add_filter('woocommerce_product_get_price', function($price, $product) use ($currencyValue){
        return ((float)$product->get_regular_price() * (float)$currencyValue); }, 73, 2);
    add_filter('woocommerce_product_variation_get_price', function($price, $product) use ($currencyValue){
        return ((float)$product->get_regular_price() * (float)$currencyValue); }, 74, 2);
    add_filter('woocommerce_product_get_sale_price', function($price, $product) use ($currencyValue){
        return ((float)$product->get_regular_price() * (float)$currencyValue); }, 75, 2);
    add_filter('woocommerce_product_variation_get_sale_price', function($price, $product) use ($currencyValue){
        return ((float)$product->get_regular_price() * (float)$currencyValue); }, 76, 2);
    add_filter('woocommerce_variation_prices_price', function($price, $product) use ($currencyValue){
        return ((float)$product->get_regular_price() * (float)$currencyValue); }, 77, 2);
    add_filter('woocommerce_variation_prices_sale_price', function($price, $product) use ($currencyValue){
        return ((float)$product->get_regular_price() * (float)$currencyValue); }, 78, 2);
    add_filter('woocommerce_get_sale_price', function($price, $product) use ($currencyValue){
        return ((float)$product->get_regular_price() * (float)$currencyValue); }, 79, 2);
    add_filter('woocommerce_get_price', function($price, $product) use ($currencyValue){
        return ((float)$product->get_regular_price() * (float)$currencyValue); }, 80, 2);
    add_filter('woocommerce_product_variation_get_regular_price', function($price, $product) use ($currencyValue){
        return ((float)$product->get_regular_price() * (float)$currencyValue); }, 80, 2);
    add_filter('woocommerce_get_variation_prices_hash', function($price, $product) use ($currencyValue){
        return ((float)$product->get_regular_price() * (float)$currencyValue); }, 82, 2);
    add_filter('woocommerce_cart_product_price', function($price, $product) use ($currencyValue){
        return ((float)$product->get_regular_price() * (float)$currencyValue); }, 83, 2);
    add_filter('woocommerce_variation_prices_regular_price', function($price, $product) use ($currencyValue){
        return ((float)$product->get_regular_price() * (float)$currencyValue); }, 83, 2);

//
//    add_filter('woocommerce_get_regular_price', function($price, $product) use ($currencyValue){
//        return ((float)$product->get_regular_price() * (float)$currencyValue); }, 83, 2);
//    add_filter('woocommerce_product_get_regular_price', function($price, $product) use ($currencyValue){
//        return ((float)$product->get_regular_price() * (float)$currencyValue); }, 83, 2);


    // Fix this
    // Makes reapetition
    /*function filter_woocommerce_get_regular_price2( $price2, $product ) {
        echo $price2 . "ttt";
        return $price2;
    }
    */

//    add_filter( 'woocommerce_get_regular_price', 'filter_woocommerce_get_regular_price2', 71, 2 );
//    add_filter( 'woocommerce_product_get_regular_price', 'filter_woocommerce_get_regular_price2', 72, 2 );
}

