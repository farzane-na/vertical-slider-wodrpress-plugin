<?php
//namespace ElementorPro\Modules\Woocommerce\Widgets;
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

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Product_Category_Widget extends \Elementor\Widget_Base {

    protected $_has_template_content = false;

    public function get_name() {
        return 'wc-categories-custom';
    }

    public function get_title() {
        return esc_html__( 'Product Categories widget', 'farzane-widget' );
    }

    public function get_icon() {
        return 'eicon-products-archive';
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
    private function get_product_categories() {
        $terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ]);

        $options = [];

        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->term_id] = $term->name;
            }
        }

        return $options;
    }


    protected function register_controls() {
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__( 'Layout', 'farzane-widget' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

//		$this->add_columns_responsive_control();

        $this->add_control(
            'number',
            [
                'label' => esc_html__( 'Categories Count', 'farzane-widget' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_filter',
            [
                'label' => esc_html__( 'Query', 'farzane-widget' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'source',
            [
                'label' => esc_html__( 'Source', 'farzane-widget' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Show All', 'farzane-widget' ),
                    'by_id' => esc_html__( 'Manual Selection', 'farzane-widget' ),
                    'by_parent' => esc_html__( 'By Parent', 'farzane-widget' ),
                    'current_subcategories' => esc_html__( 'Current Subcategories', 'farzane-widget' ),
                ],
                'label_block' => true,
            ]
        );

        $categories = get_terms( 'product_cat' );

        $options = [];
        foreach ( $categories as $category ) {
            $options[ $category->term_id ] = $category->name;
        }

        $this->add_control(
            'categories',
            [
                'label' => esc_html__( 'Categories', 'farzane-widget' ),
                'type' => Controls_Manager::SELECT2,
                'options' => $options,
                'default' => [],
                'label_block' => true,
                'multiple' => true,
                'condition' => [
                    'source' => 'by_id',
                ],
            ]
        );

        $parent_options = [ '0' => esc_html__( 'Only Top Level', 'farzane-widget' ) ] + $options;
        $this->add_control(
            'parent',
            [
                'label' => esc_html__( 'Parent', 'farzane-widget' ),
                'type' => Controls_Manager::SELECT,
                'default' => '0',
                'options' => $parent_options,
                'condition' => [
                    'source' => 'by_parent',
                ],
            ]
        );

        $this->add_control(
            'hide_empty',
            [
                'label' => esc_html__( 'Hide Empty', 'farzane-widget' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => 'Hide',
                'label_off' => 'Show',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => esc_html__( 'Order By', 'farzane-widget' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'name',
                'options' => [
                    'name' => esc_html__( 'Name', 'farzane-widget' ),
                    'slug' => esc_html__( 'Slug', 'farzane-widget' ),
                    'description' => esc_html__( 'Description', 'farzane-widget' ),
                    'count' => esc_html__( 'Count', 'farzane-widget' ),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__( 'Order', 'farzane-widget' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => [
                    'asc' => esc_html__( 'ASC', 'farzane-widget' ),
                    'desc' => esc_html__( 'DESC', 'farzane-widget' ),
                ],
            ]
        );
        $this->add_control(
            'excluded_categories',
            [
                'label' => esc_html__('Execute Category', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_product_categories(),
                'label_block' => true,
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_products_style',
            [
                'label' => esc_html__( 'Products', 'farzane-widget' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wc_style_warning',
            [
                'type' => Controls_Manager::ALERT,
                'alert_type' => 'info',
                'content' => esc_html__( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'farzane-widget' ),
            ]
        );

        $this->add_control(
            'products_class',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => 'wc-products',
                'prefix_class' => 'farzane-widgetducts-grid elementor-',
            ]
        );

        $this->add_control(
            'column_gap',
            [
                'label'     => esc_html__( 'Columns Gap', 'farzane-widget' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'default'   => [
                    'size' => 20,
                ],
                'range'     => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-wc-products  ul.products' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'row_gap',
            [
                'label'     => esc_html__( 'Rows Gap', 'farzane-widget' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'default'   => [
                    'size' => 40,
                ],
                'range'     => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-wc-products  ul.products' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'     => esc_html__( 'Alignment', 'farzane-widget' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'farzane-widget' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'farzane-widget' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'farzane-widget' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'prefix_class' => 'farzane-widgetduct-loop-item--align-',
                'selectors' => [
                    '{{WRAPPER}} .product' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'heading_image_style',
            [
                'label'     => esc_html__( 'Image', 'farzane-widget' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'image_border',
                'selector' => '{{WRAPPER}} a > img',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'farzane-widget' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors'  => [
                    '{{WRAPPER}} a > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_spacing',
            [
                'label'      => esc_html__( 'Spacing', 'farzane-widget' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'selectors'  => [
                    '{{WRAPPER}} a > img' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'heading_title_style',
            [
                'label'     => esc_html__( 'Title', 'farzane-widget' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Color', 'farzane-widget' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce .woocommerce-loop-category__title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'global'    => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .woocommerce .woocommerce-loop-category__title',
            ]
        );

        $this->add_control(
            'heading_count_style',
            [
                'label'     => esc_html__( 'Count', 'farzane-widget' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'count_color',
            [
                'label'     => esc_html__( 'Color', 'farzane-widget' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-loop-category__title .count' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'count_typography',
                'global'    => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .woocommerce-loop-category__title .count',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            "control_wrapepr",
            [
                'label' => esc_html__( 'wrapper style', 'farzane-widget' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'wrapepr_padding',
            [
                'label' => esc_html__( 'wrapper padding', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wrapper-category-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_background',
                'label' => __('wrapepr background','farzane-widget'),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wrapper-category-box',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wrapepr_shadow',
                'label' => __('wrapper shadow','farzane-widget'),
                'selector' => '{{WRAPPER}} .wrapper-category-box',
            ]
        );
        $this->add_responsive_control(
            'wrapper_width',
            [
                'label' => esc_html__( 'wrapper width', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wrapper-category-box' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    private function get_shortcode() {
        $settings = $this->get_settings();

        $attributes = [
            'number' => $settings['number'],
            'columns' => $settings['columns'],
            'hide_empty' => ( 'yes' === $settings['hide_empty'] ) ? 1 : 0,
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
        ];

        if ( 'by_id' === $settings['source'] ) {
            $attributes['ids'] = implode( ',', $settings['categories'] );
        } elseif ( 'by_parent' === $settings['source'] ) {
            $attributes['parent'] = $settings['parent'];
        } elseif ( 'current_subcategories' === $settings['source'] ) {
            $attributes['parent'] = get_queried_object_id();
        }
        if ( ! empty( $settings['product_categories'] ) ) {
            $attributes['ids'] = implode( ',', $settings['product_categories'] );
        } elseif ( ! empty( $settings['excluded_categories'] ) ) {
            $attributes['exclude'] = implode( ',', $settings['excluded_categories'] );
        }

        $this->add_render_attribute( 'shortcode', $attributes );

        $shortcode = sprintf( '[product_categories %s]', $this->get_render_attribute_string( 'shortcode' ) );

        return $shortcode;
    }

    public function render() {
        $product_categories_html = do_shortcode( $this->get_shortcode() );

        if ( $product_categories_html ) {
            $product_categories_html = str_replace( '<ul class="products', '<ul class="products elementor-grid', $product_categories_html );

            // PHPCS - Doesn't need to be escaped since it's a WooCommerce template, and 3rd party plugins might hook into it.
            echo $product_categories_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    public function render_plain_content() {
        echo wp_kses_post( $this->get_shortcode() );
    }

    public function get_group_name() {
        return 'woocommerce';
    }
}
