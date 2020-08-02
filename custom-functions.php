<?php
/* Close the direct access */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* Check if WooCommerce is active */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

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

    /* Add script to WP */
    function krokedilScripts() {
        wp_enqueue_script('krokedil_currency', plugin_dir_url( __FILE__ ) . 'js/script.js', 'jquery', '1.0', true);
    }
    add_action('wp_enqueue_scripts', 'krokedilScripts');


}