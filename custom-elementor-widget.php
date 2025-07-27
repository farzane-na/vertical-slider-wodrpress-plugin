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