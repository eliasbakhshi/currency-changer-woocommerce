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

/*

    function changeCurrency() {
        add_filter('woocommerce_currency', 'set_role_currency');
        function set_role_currency($currency) {
            return 'SEK';
        }
    }
    add_action('init', 'changeCurrency');*/
    /*

    //-----------------------

    add_action( 'woocommerce_before_calculate_totals', 'add_custom_price' );

    function add_custom_price( $cart_object ) {
        $custom_price = 20010; // This will be your custome price
        var_dump($cart_object);
        foreach ( $cart_object->cart_contents as $key => $value ) {
            $value['data']->price = $custom_price;
            // for WooCommerce version 3+ use:
            // $value['data']->set_price($custom_price);
        }
    }*/


/*
    function add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
        // Has our option been selected?
        if( ! empty( $_POST['extended_warranty'] ) ) {
            $product = wc_get_product( $product_id );
            $price = $product->get_price();
            // Store the overall price for the product, including the cost of the warranty
            $cart_item_data['warranty_price'] = $price + "50000";
        }
        return $cart_item_data;
    }
    add_filter( 'woocommerce_add_cart_item_data', 'add_cart_item_data', 10, 3 );*/



}