<?php
/**
 * Plugin Name: custom elementor widgets
 * Description: Custom plugins that we can add to our Elementor.
 * Version: 1.1.0
 * Requires PHP:      7.4
 * Author: Farzane Nazmabadi
 * Author URI:        https://farzanenazmabadi.ir/
 * Text Domain:       farzane-widget
 * Domain Path:       /languages
 * Requires Plugins:  elementor,woocommerce
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
function translate_plugin() {
    load_plugin_textdomain( 'farzane-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'translate_plugin' );
define('WELCOME_IMAGE_FARZANE',plugin_dir_url(__FILE__)."asset/images/coffee.webp");
require_once plugin_dir_path(__FILE__) . 'admin/admin-dashboard.php';
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
    );
    wp_register_style(
        'vertical-slider-style',
        plugin_dir_url(__FILE__) . 'asset/css/app.css',
        [],
        '1.0.0'
    );
    wp_register_script(
        'vertical-slider-script',
        plugin_dir_url(__FILE__) . 'asset/js/app.js',
        '1.0.0',
        true
    );
    wp_register_style(
        'custom-add-to-cart-style',
        plugin_dir_url(__FILE__).'asset/css/add-to-cart-style.css',
        [],
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'vertical_slider_enqueue_assets');

add_filter('woocommerce_add_cart_item_data', 'add_custom_data_to_cart_item', 10, 3);
function add_custom_data_to_cart_item($cart_item_data, $product_id, $variation_id) {
    if (isset($_POST['custom_price'])) {
        $custom_price = preg_replace('/[^\d.]/', '', $_POST['custom_price']);
        $cart_item_data['custom_price'] = floatval($custom_price);
    }


    if (!empty($_POST['device-type'])) {
        $cart_item_data['device_type'] = sanitize_text_field($_POST['device-type']);
    }

    return $cart_item_data;
}

add_action('woocommerce_before_calculate_totals', 'apply_custom_price_to_cart_item', 10, 1);
function apply_custom_price_to_cart_item($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        if (isset($cart_item['custom_price'])) {
            $cart_item['data']->set_price($cart_item['custom_price']);
        }
    }
}

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

add_action('woocommerce_checkout_create_order_line_item', 'save_device_type_to_order_items', 10, 4);
function save_device_type_to_order_items($item, $cart_item_key, $values, $order) {
    if (!empty($values['device_type'])) {
        $item->add_meta_data('نوع دستگاه قهوه‌ساز', sanitize_text_field($values['device_type']), true);
    }

    if (!empty($values['custom_price'])) {
        $item->add_meta_data('قیمت سفارشی', wc_price($values['custom_price']), true);
    }
}
