<?php
/**
* Plugin Name: vertical slider
* Description: A slider that is displayed vertically and is suitable for mobile devices.
* Version: 1.0
* Requires PHP:      7.4
* Author: farzane nazmabadi
* Author URI:        https://farzanenazmabadi.ir/
* Text Domain:       vertical-slider
* Domain Path:       /languages
* Requires Plugins:  elementor
*/
if ( ! defined( 'ABSPATH' ) ) {
   exit;
}
// Register the widget.
function register_vertical_slider_widget( $widgets_manager ) {
   require_once __DIR__ . '/widgets/vertical-slider-widget.php';
   $widgets_manager->register( new \Vertical_Slider_Widget() );
}
add_action( 'elementor/widgets/register', 'register_vertical_slider_widget' );

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
        'vertical-slider-style',
        plugin_dir_url(__FILE__) . 'asset/css/app.css',
        [],
        '1.0.0'
    );
    wp_register_script(
        'vertical-slider-script',
        plugin_dir_url(__FILE__) . 'asset/js/app.js',
        ['jquery'],
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'vertical_slider_enqueue_assets');