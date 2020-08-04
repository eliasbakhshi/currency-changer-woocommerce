<?php
/* Close the direct access */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global  $woocommerce;

/* ----- Fetch info ----- */
function fetchInArray($url) {
    $url = strval($url);
    $curl = curl_init($url);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    /* Convert it to object */
    $object = json_decode($response);
    /* Return it as array */
    return json_decode(json_encode($object), true);
}

/* ----- Add script to WP ----- */
function krokedilScripts() {
    wp_enqueue_script('krokedil_currency', plugin_dir_url( __FILE__ ) . 'js/script.js', 'jquery', '1.0', true);
}
add_action('wp_enqueue_scripts', 'krokedilScripts');


/* ----- Modify prices ----- */
function modifyPrices($currencyValue) {
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
    add_filter('woocommerce_product_variation_get_regular_price', 'custom_dynamic_regular_price', 81, 2);
    add_filter('woocommerce_get_variation_prices_hash', function($price, $product) use ($currencyValue){
        return ((float)$product->get_regular_price() * (float)$currencyValue); }, 82, 2);
    add_filter('woocommerce_cart_product_price', function($price, $product) use ($currencyValue){
        return ((float)$product->get_regular_price() * (float)$currencyValue); }, 83, 2);
}


//$systemsCurrency = get_woocommerce_currency();
$systemsCurrency = get_option('woocommerce_currency');
/* ----- Change the currency before page loading ----- */
$usersCurrency = 'USD';
if ( !empty($usersCurrency)) {
    add_action('init', function () use ($usersCurrency) {
        add_filter('woocommerce_currency', function ($currency) use ($usersCurrency) {
            return $usersCurrency;
        });
    });
} else {
    add_action('init', function () use ($systemsCurrency) {
        add_filter('woocommerce_currency', function ($currency) use ($systemsCurrency) {
            return $systemsCurrency;
        });
    });
}
$url = sprintf( 'https://api.exchangeratesapi.io/latest?base=%s&symbols=%s', $systemsCurrency, $usersCurrency, );
$mainCurrencies = fetchInArray($url);
//var_dump($mainCurrencies['rates'][$usersCurrency]);
$displayCurrencyValue = ( !empty($mainCurrencies['rates'][$usersCurrency]) ) ? $mainCurrencies['rates'][$usersCurrency] : 1 ;
modifyPrices($displayCurrencyValue);


