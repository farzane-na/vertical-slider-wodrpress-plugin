<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use ElementorPro\Plugin;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Responsive;
use Elementor\Group_Control_Box_Shado;
use Elementor\Utils;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Repeate;
use Elementor\Controls_Stack;
use Elementor\Base\Data_Control;
use Elementor\Frontend;
use Elementor\Editor;
use Elementor\Element_Bas;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Custom_Add_To_Cart extends \Elementor\Widget_Base {

    protected $_has_template_content = false;

    public function get_name() {
        return 'custom-add-to-cart';
    }

    public function get_title() {
        return esc_html__( 'Add To Cart', 'farzane-widget' );
    }

    public function get_icon() {
        return 'eicon-woo-cart';
    }

    public function get_keywords() {
        return [ 'woocommerce-elements', 'shop', 'store', 'categories', 'product' ];
    }

    public function has_widget_inner_wrapper(): bool {
        return ! Plugin::elementor()->experiments->is_feature_active( 'e_optimized_markup' );
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    /**
     * Get style dependencies.
     *
     * Retrieve the list of style dependencies the widget requires.
     *
     * @since 3.24.0
     * @access public
     *
     * @return array Widget style dependencies.
     */
    public function get_style_depends(): array {
        return [ 'widget-woocommerce-products', 'widget-woocommerce-categories' ];
    }
//    get taxonomy
   protected function register_controls(){
        $this->start_controls_section();
    }
    protected function render()
    {
        
    }
}