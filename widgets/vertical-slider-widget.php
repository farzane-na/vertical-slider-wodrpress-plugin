<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
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
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Editor;
use Elementor\Element_Bas;
use Elementor\Group_Control_Css_Filter;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class Vertical_slider_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'vertical_slider';
    }
    public function get_title() {
        return __( 'vertical slider', 'farzane-widget' );
    }
    public function get_icon() {
        return 'eicon-nested-carousel';
    }
    public function get_categories() {
        return [ 'basic' ];
    }
    protected function get_combined_product_terms_options() {
        $options = [];
        $product_cats = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ]);
        if (!is_wp_error($product_cats)) {
            foreach ($product_cats as $term) {
                $options["cat_{$term->term_id}"] = 'دسته: ' . $term->name;
            }
        }
        $product_tags = get_terms([
            'taxonomy' => 'product_tag',
            'hide_empty' => false,
        ]);
        if (!is_wp_error($product_tags)) {
            foreach ($product_tags as $term) {
                $options["tag_{$term->term_id}"] = 'برچسب: ' . $term->name;
            }
        }

        return $options;
    }
    protected function is_dynamic_content(): bool {
        return true;
    }
    public function has_widget_inner_wrapper(): bool {
        return false;
    }
    protected function register_controls() {
        $this->start_controls_section(
            'section_layout_controls',
            [
                'label' => esc_html__( 'card layout', 'farzane-widget' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'flex_direction',
            [
                'label' => esc_html__( 'direction', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'row'=>[
                        'title'=> __('row','farzane-widget'),
                        'icon'=>'eicon-arrow-left'
                    ],
                    'row-reverse' => [
                        'title'=>__('row reverse','farzane-widget'),
                        'icon'=>'eicon-arrow-right'
                    ],
                    'column' =>[
                        'title'=> __('column','farzane-widget'),
                        'icon'=>'eicon-arrow-down'
                    ],
                    'column-reverse' =>[
                        'title'=> __('column reverse','farzane-widget'),
                        'icon'=>'eicon-arrow-up'
                    ],
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .product-content' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'justify_content',
            [
                'label' => esc_html__( 'Justify Content', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' =>[
                        'title'=> __('flex start','farzane-widget'),
                        'icon'=>'eicon-justify-end-h'
                    ],
                    'center' =>[
                        'title'=> __('center','farzane-widget'),
                        'icon'=>'eicon-justify-center-h'
                    ],
                    'flex-end' => [
                        'title'=>__('flex end','farzane-widget'),
                        'icon'=>'eicon-justify-start-h'
                    ],
                    'space-between' =>[
                        'title'=> __('space between','farzane-widget'),
                        'icon'=>'eicon-justify-space-between-h'
                    ],
                    'space-around' => [
                        'title'=>__('space around','farzane-widget'),
                        'icon'=>'eicon-justify-space-around-h'
                    ],
                    'space-evenly' => [
                        'title'=>__('space evenly','farzane-widget'),
                        'icon'=>'eicon-justify-space-evenly-h'
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .product-content' => 'justify-content: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'align_items',
            [
                'label' => esc_html__( 'Align Items', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'stretch',
                'options' => [
                    'flex-start' =>[
                        'title'=>__( 'flex start','farzane-widget'),
                        'icon'=>'eicon-align-start-v'
                    ],
                    'center' => [
                        'title'=>__('center','farzane-widget'),
                        'icon'=>'eicon-align-center-v'
                    ],
                    'flex-end' =>[
                        'title'=> __('flex end','farzane-widget'),
                        'icon'=>'eicon-align-end-v'
                    ],
                    'stretch' => [
                        'title'=>__('stretch','farzane-widget'),
                        'icon'=>'eicon-align-stretch-v'
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .product-content' => 'align-items: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'flex_gap',
            [
                'label' => esc_html__( 'gap', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .product-content' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'category_name_section',
            [
                'label'=>__('Category Name','farzane-widget'),
                'tab'=>\Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'category_title_color',
            [
                'label' => __( 'Category Name Color', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .product-category-name' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'product_category_name_typography',
                'label' => __( 'Typography', 'farzane-widget' ),
                'selector' => '{{WRAPPER}} .product-category-name',
            ]
        );
        $this->add_responsive_control(
            'product_category_name_alignment',
            [
                'label' => __( 'Alignment', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'right' => [
                        'title' => __( 'right', 'farzane-widget' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'farzane-widget' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'left'=>[
                        'title'=>__('left','farzane-widget'),
                        'icon'=>'eicon-text-align-left'
                    ],
                ],
                'default' => 'right',
                'selectors' => [
                    '{{WRAPPER}} .product-category-name' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'product_category_name_spacing',
            [
                'label'      => esc_html__( 'Product Category Name Spacing', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .product-category-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_card_title_style',
            [
                'label' => __( 'content style', 'farzane-widget' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'card_title_color',
            [
                'label' => __( 'Title Color', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .product-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'card_title_typography',
                'label' => __( 'Typography', 'farzane-widget' ),
                'selector' => '{{WRAPPER}} .product-title',
            ]
        );
        $this->add_responsive_control(
            'card_title_alignment',
            [
                'label' => __( 'Alignment', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'right' => [
                        'title' => __( 'right', 'farzane-widget' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'farzane-widget' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'left'=>[
                        'title'=>__('left','farzane-widget'),
                        'icon'=>'eicon-text-align-left'
                    ],
                ],
                'default' => 'right',
                'selectors' => [
                    '{{WRAPPER}} .product-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'card_title_divider',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'card_caption_color',
            [
                'label'=> __('caption color','farzane-widget'),
                'type'=> \Elementor\Controls_Manager::COLOR,
                'default'=>"#333333",
                'selector'=>[
                    '{{WRAPPER}} .product-caption ' => 'color :{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'=>'card_caption_typography',
                'label'=>__('Typography', 'farzane-widget' ),
                'selector'=>'{{WRAPPER}} .product-caption'
            ]
        );
        $this->add_responsive_control(
            'card_caption_alignment',
            [
                'label'=>__('Alignment', 'farzane-widget'),
                'type'=> \Elementor\Controls_Manager::CHOOSE,
                'options'=>[
                    'right'=>[
                        'title'=>__('right','farzane-widget'),
                        'icon'=>'eicon-text-align-right'
                    ],
                    'center'=>[
                        'title'=>__('cenetr','farzane-widget'),
                        'icon'=>'eicon-text-align-center'
                    ],
                    'left'=>[
                        'title'=>__('left','farzane-widget'),
                        'icon'=>'eicon-text-align-left'
                    ],
                    'justify'=>[
                        'title'=>__('justify','farzane-widget'),
                        'icon'=>'eicon-text-align-justify'
                    ],
                ],
                'default' => 'right',
                'selectors' => [
                    '{{WRAPPER}} .product-caption' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'caption_height',
            [
                'label' => esc_html__( 'caption height', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
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
                    '{{WRAPPER}} .product-caption p' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'caption_line_clamp',
            [
                'label' => esc_html__( 'Line Clamp', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .product-caption p' => '-webkit-line-clamp: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'card_caption_divider',
            [
                'type'=>\Elementor\Controls_Manager::DIVIDER,
            ]
        );
        $this->add_control(
            'card_price_color',
            [
                'label'=>__('price color','farzane-widget'),
                'type'=>\Elementor\Controls_Manager::COLOR,
                'default'=>"#333333",
                'selector'=>[
                    '{{WRAPPER}} .product-price'=>'color:{{VALUE}}'
                ]
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'=>'card_price_typography',
                'label'=>__('typography','farzane-widget'),
                'selector'=> '{{WRAPPER}} .product-price'
            ]
        );
        $this->add_responsive_control(
            'card_price_alignment',
            [
                'label'=>__('Alignment', 'farzane-widget' ),
                'type'=>\Elementor\Controls_Manager::CHOOSE,
                'options'=>[
                    'right' => [
                        'title' => __( 'right', 'farzane-widget' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'farzane-widget' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'left'=>[
                        'title'=>__('left','farzane-widget'),
                        'icon'=>'eicon-text-align-left'
                    ],
                ],
                'default' => 'right',
                'selectors' => [
                    '{{WRAPPER}} .product-price' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_card_style',
            [
                'label'=>__('card style','farzane-widget'),
                'tab'=>\Elementor\Controls_Manager::TAB_STYLE,
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
                    '{{WRAPPER}} .product-content' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'card_background',
                'label' => __('card background','farzane-widget'),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .product-content',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'label' => esc_html__( 'Border', 'farzane-widget' ),
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .product-content',
            ]
        );
        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .product-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'card_padding',
            [
                'label' => esc_html__( 'card padding', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .product-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'label' => __('card shadow','farzane-widget'),
                'selector' => '{{WRAPPER}} .product-content',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_card_image',
            [
                'label'=>__('product image','verticcal-slider'),
                'tab'=>\Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'border_radius_image',
            [
                'label' => esc_html__( 'border radius image', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .product-image-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'image_css_filters',
                'label'    => __( 'CSS Filters', 'farzane-widget' ),
                'selector' => '{{WRAPPER}} .product-image',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_icon',
            [
                'label'=>__('icons','farzane-widget'),
                'tab'=>\Elementor\Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_responsive_control(
            'icon_width',
            [
                'label' => esc_html__( 'icon width', 'farzane-widget' ),
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
                    '{{WRAPPER}} .swiper-button-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'slider_icon_color',
            [
                'label' => __( 'icons color', 'farzane-widget' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }
    protected function render() {
        wp_enqueue_script('swiper-bundle-script');
        wp_enqueue_style('swiper-bundle-style');
        wp_enqueue_style('vertical-slider-style');
        wp_enqueue_script('vertical-slider-script');

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'ASC',
        ];
        $queried_object = get_queried_object();
        if (is_tax('product_cat') && isset($queried_object->term_id)) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $queried_object->term_id,
                ]
            ];
        }

        $query = new \WP_Query($args);

        if ($query->have_posts()) : ?>
            <h2 class="product-category-name"><?php echo $queried_object->name ;  ?></h2>
            <div class="swiper cafeSwiper">
                <div class="swiper-wrapper">
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="swiper-slide">
                            <div class="product-content">
                                <div class="detail-wrapper">
                                    <div class="product-image-wrapper">
                                        <img class="product-image" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" alt="<?php the_title(); ?>">
                                    </div>
                                    <h3 class="product-title"><?php the_title(); ?></h3>
                                </div>
                                <div class="content-wrapper">
                                    <div class="product-caption"><?php the_content(); ?></div>
                                    <div class="product-price">
                                        <p>
                                            <?php
                                            $product = wc_get_product(get_the_ID());
                                            echo $product ? $product->get_price_html() : '—';
                                            ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        <?php
        endif;
        wp_reset_postdata();
    }

}