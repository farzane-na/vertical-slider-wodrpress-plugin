<?php
/**
* Plugin Name: custom elementor widgets
* Description: Custom plugins that we can add to our Elementor.
* Version: 1.0
* Requires PHP:      7.4
* Author: farzane nazmabadi
* Author URI:        https://farzanenazmabadi.ir/
* Text Domain:       farzane-widget
* Domain Path:       /languages
* Requires Plugins:  elementor
*/
if ( ! defined( 'ABSPATH' ) ) {
   exit;
}
// Register the widget.
add_action( 'elementor/init', 'init_custom_elementor_widgets' );
function init_custom_elementor_widgets() {
    require_once __DIR__ . '/widgets/vertical-slider-widget.php';
    require_once __DIR__ . '/widgets/product-category-widget.php';
    require_once __DIR__ . '/widgets/custom-add-to-cart.php';

    add_action( 'elementor/widgets/widgets_registered', 'register_custom_widget_elementor' );
}
function register_custom_widget_elementor( $widgets_manager ) {
   $widgets_manager->register( new \Vertical_Slider_Widget() );
   $widgets_manager->register( new \Product_Category_Widget() );
   $widgets_manager->register(new \Custom_Add_To_Cart());
}

function vertical_slider_enqueue_assets() {
   
    wp_register_style(
        'swiper-bundle-style',
        plugin_dir_url(__FILE__) . 'asset/css/swiper-bundle.min.css',
        [],
        '1.0.0'
    );

    
     wp_register_script(
        'swiper-bundle-script',
        plugin_dir_url(__FILE__) . 'asset/js/swiper-bundle.min.js',
        [],
        '1.0.0',
        true
    ); wp_register_style(
        'farzane-widget-style',
        plugin_dir_url(__FILE__) . 'asset/css/app.css',
        [],
        '1.0.0'
    );
    wp_register_script(
        'farzane-widget-script',
        plugin_dir_url(__FILE__) . 'asset/js/app.js',
        ['jquery'],
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'vertical_slider_enqueue_assets');
add_action( 'elementor/frontend/after_register_scripts', function() {
    wp_register_script(
        'custom-add-to-cart-script',
        plugin_dir_url( __FILE__ ) . 'asset/js/add-to-cart/add-to-cart.js',
        '1.0.0',
        true
    );
});

//add_action('woocommerce_before_calculate_totals', 'set_custom_price_for_custom_product', 10, 1);
//function set_custom_price_for_custom_product($cart) {
//    if (is_admin() && !defined('DOING_AJAX')) return;
//
//    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
//        if (isset($cart_item['custom_price'])) {
//            // قیمت رو بروزرسانی کن
//            $cart_item['data']->set_price($cart_item['custom_price']);
//        }
//    }
//}
//add_filter('woocommerce_add_cart_item_data', 'add_custom_price_to_cart_item', 10, 3);
//function add_custom_price_to_cart_item($cart_item_data, $product_id, $variation_id) {
//    if (isset($_POST['custom_price'])) {
//        $cart_item_data['custom_price'] = (float) $_POST['custom_price'];
//    }
//    return $cart_item_data;
//}
//add_filter('woocommerce_get_item_data', 'display_custom_price_in_cart', 10, 2);
//function display_custom_price_in_cart($item_data, $cart_item) {
//    if (isset($cart_item['custom_price'])) {
//        $item_data[] = array(
//            'name' => 'قیمت سفارشی',
//            'value' => wc_price($cart_item['custom_price']),
//        );
//    }
//    return $item_data;
//}
//add_filter('woocommerce_add_cart_item_data', 'save_device_type_to_cart_item', 10, 2);
//function save_device_type_to_cart_item($cart_item_data, $product_id) {
//    if (!empty($_POST['device-type'])) {
//        $cart_item_data['device_type'] = sanitize_text_field($_POST['device-type']);
//    }
//    return $cart_item_data;
//}

////////////////////////////////////
// قیمت سفارشی برای محصولات سفارشی
add_filter('woocommerce_add_cart_item_data', 'add_custom_data_to_cart_item', 10, 3);
function add_custom_data_to_cart_item($cart_item_data, $product_id, $variation_id) {
    if (isset($_POST['custom_price'])) {
        $cart_item_data['custom_price'] = (float) $_POST['custom_price'];
    }

    if (!empty($_POST['device-type'])) {
        $cart_item_data['device_type'] = sanitize_text_field($_POST['device-type']);
    }

    return $cart_item_data;
}

// اعمال قیمت سفارشی در سبد خرید
add_action('woocommerce_before_calculate_totals', 'apply_custom_price_to_cart_item', 10, 1);
function apply_custom_price_to_cart_item($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        if (isset($cart_item['custom_price'])) {
            $cart_item['data']->set_price($cart_item['custom_price']);
        }
    }
}

// نمایش اطلاعات سفارشی در سبد خرید
add_filter('woocommerce_get_item_data', 'display_custom_cart_item_data', 10, 2);
function display_custom_cart_item_data($item_data, $cart_item) {
    if (isset($cart_item['custom_price'])) {
        $item_data[] = array(
            'name' => 'قیمت سفارشی',
            'value' => wc_price($cart_item['custom_price']),
        );
    }

    if (!empty($cart_item['device_type'])) {
        $item_data[] = array(
            'name' => 'نوع دستگاه قهوه‌ساز',
            'value' => sanitize_text_field($cart_item['device_type']),
        );
    }

    return $item_data;
}
add_filter('woocommerce_add_cart_item_data', 'save_device_type_to_cart_item', 10, 2);
function save_device_type_to_cart_item($cart_item_data, $product_id) {
    if (!empty($_POST['device-type'])) {
        $cart_item_data['device_type'] = sanitize_text_field($_POST['device-type']);
    }
    return $cart_item_data;
}
// ذخیره نوع دستگاه در آیتم سفارش
add_action('woocommerce_add_order_item_meta', 'save_device_type_to_order_items', 10, 3);
function save_device_type_to_order_items($item_id, $values, $cart_item_key) {
    if (!empty($values['device_type'])) {
        wc_add_order_item_meta($item_id, 'نوع دستگاه قهوه‌ساز', sanitize_text_field($values['device_type']));
    }
}
