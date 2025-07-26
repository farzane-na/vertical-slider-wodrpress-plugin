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
use Elementor\Group_Control_Css_Filter;

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
//    get taxonomy
    private function get_product_categories_options() {
        $terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ]);

        $options = [];

        foreach ( $terms as $term ) {
            $options[ $term->term_id ] = $term->name;
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
        $this->add_control(
            'product_exclude_categories',
            [
                'label'       => __( 'Exclude Category', 'farzane-widget' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_product_categories_options(),
                'default'     => [],
                'label_block' => true,
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
                'selector' => '{{WRAPPER}} .product-category-img',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'farzane-widget' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors'  => [
                    '{{WRAPPER}} .product-category-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
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
                    '{{WRAPPER}} .product-category-img' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'image_css_filters',
                'label'    => __( 'CSS Filters', 'farzane-widget' ),
                'selector' => '{{WRAPPER}} .product-category-img',
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
                    '{{WRAPPER}} .product-category-title' => 'color: {{VALUE}}',
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
                'selector' => '{{WRAPPER}} .product-category-title',
            ]
        );
        $this->add_responsive_control(
            'title_spacing',
            [
                'label'      => esc_html__( 'Title Spacing', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .product-category-title h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .product-category-title' => 'text-align: {{VALUE}}',
                ],
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
                    '{{WRAPPER}} .category-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_background',
                'label' => __('wrapepr background','farzane-widget'),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .category-wrapper',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wrapepr_shadow',
                'label' => __('wrapper shadow','farzane-widget'),
                'selector' => '{{WRAPPER}} .category-wrapper',
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
                    '{{WRAPPER}} .category-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }
    ///////////
    public function render() {
        $settings = $this->get_settings_for_display();
        $exclude_categories = $settings['product_exclude_categories'] ?? [];
        $args = [
            'taxonomy'   => 'product_cat',
            'hide_empty' => ( 'yes' === $settings['hide_empty'] ) ? true : false,
            'orderby'    => $settings['orderby'],
            'order'      => $settings['order'],
        ];
        if ( 'by_id' === $settings['source'] && ! empty( $settings['categories'] ) ) {
            $args['include'] = array_map( 'intval', $settings['categories'] );
        } elseif ( 'by_parent' === $settings['source'] ) {
            $args['parent'] = intval( $settings['parent'] );
        } elseif ( 'current_subcategories' === $settings['source'] ) {
            $args['parent'] = get_queried_object_id();
        }
        $categories = get_terms( $args );

        if ( is_wp_error( $categories ) || empty( $categories ) ) {
            echo '<p>' . __( 'No results found. ', 'farzane-widget' ) . '</p>';
            return;
        }
        echo '<div class="total-product-category-container">';
        foreach ( $categories as $category ) {
            if ( in_array( strval( $category->term_id ), $exclude_categories, true ) ) {
                continue;
            }

            $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
            $image_url = $thumbnail_id ? wp_get_attachment_url( $thumbnail_id ) : wc_placeholder_img_src();
            $link = get_term_link( $category );
            $title = esc_html( $category->name );
            echo '
            <div class="product-category-item">
                <div class="category-wrapper">
                     <a class="product-category-link" href="' . esc_url( $link ) . '">
                        <img class="product-category-img" src="' . esc_url( $image_url ) . '" alt="' . $title . '" />
                         <h4 class="product-category-title">' . $title . '</h4>
                     </a>
                </div>
            </div>';
        }

        echo '</div>';
    }
    ////////

    public function get_group_name() {
        return 'woocommerce';
    }
}
