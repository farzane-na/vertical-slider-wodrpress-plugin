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
if ( ! defined( 'ABSPATH' ) ) {
  exit; 
}
class Vertical_slider_Widget extends \Elementor\Widget_Base {
  public function get_name() {
      return 'vertical_slider';
  }
  public function get_title() {
      return __( 'vertical slider', 'vertical-slider' );
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
        'label' => esc_html__( 'card layout', 'vertical-slider' ),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
    ]
);
$this->add_control(
    'flex_direction',
    [
        'label' => esc_html__( 'direction', 'vertical-slider' ),
        'type' => \Elementor\Controls_Manager::CHOOSE,
        'options' => [
            'row'=>[
                'title'=> __('row','vertical-slider'),
                'icon'=>'eicon-arrow-left'
            ],
            'row-reverse' => [
                'title'=>__('row reverse','vertical-slider'),
                'icon'=>'eicon-arrow-right'
            ],
            'column' =>[
                'title'=> __('column','vertical-slider'),
                'icon'=>'eicon-arrow-down'
            ],
            'column-reverse' =>[
                'title'=> __('column reverse','vertical-slider'),
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
        'label' => esc_html__( 'Justify Content', 'vertical-slider' ),
        'type' => \Elementor\Controls_Manager::CHOOSE,
        'options' => [
            'flex-start' =>[
                'title'=> __('flex start','vertical-slider'),
                'icon'=>'eicon-justify-end-h'
            ],
            'center' =>[
                'title'=> __('center','vertical-slider'),
                'icon'=>'eicon-justify-center-h'
            ],
            'flex-end' => [
                'title'=>__('flex end','vertical-slider'),
                'icon'=>'eicon-justify-start-h'
            ],
            'space-between' =>[
                'title'=> __('space between','vertical-slider'),
                'icon'=>'eicon-justify-space-between-h'
            ],
            'space-around' => [
                'title'=>__('space around','vertical-slider'),
                'icon'=>'eicon-justify-space-around-h'
            ],
            'space-evenly' => [
                'title'=>__('space evenly','vertical-slider'),
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
        'label' => esc_html__( 'Align Items', 'vertical-slider' ),
        'type' => \Elementor\Controls_Manager::CHOOSE,
        'default' => 'stretch',
        'options' => [
            'flex-start' =>[
                'title'=>__( 'flex start','vertical-slider'),
                'icon'=>'eicon-align-start-v'
            ],
            'center' => [
                'title'=>__('center','vertical-slider'),
                'icon'=>'eicon-align-center-v'
            ],
            'flex-end' =>[
                'title'=> __('flex end','vertical-slider'),
                'icon'=>'eicon-align-end-v'
            ],
            'stretch' => [
                'title'=>__('stretch','vertical-slider'),
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
        'label' => esc_html__( 'gap', 'vertical-slider' ),
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
    'section_query',
    [
        'label' => __( 'Query', 'vertical-slider' ),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
    ]
);
$this->add_control(
    'post_type',
    [
        'label' => __( 'Post Type', 'vertical-slider' ),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'product',
        'options' => [
            'post'    => __( 'Posts', 'vertical-slider' ),
            'product' => __( 'Products', 'vertical-slider' ),
            'page'    => __( 'Pages', 'vertical-slider' ),
        ]
    ]
);
$this->add_control(
    'select_terms',
    [
        'label' => __( 'Filter by Term', 'vertical-slider' ),
        'type' => \Elementor\Controls_Manager::SELECT2,
        'multiple' => true,
        'options' => $this->get_combined_product_terms_options(),
        'condition' => [
            'post_type' => 'product'
        ]
    ]
);
$this->end_controls_section(); // ⬅️ پایان بخش Query
$this->start_controls_section(
    'section_card_title_style',
    [
        'label' => __( 'content style', 'vertical-slider' ),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
    ]
);
$this->add_control(
    'card_title_color',
    [
        'label' => __( 'Title Color', 'vertical-slider' ),
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
        'label' => __( 'Typography', 'vertical-slider' ),
        'selector' => '{{WRAPPER}} .product-title',
    ]
);
$this->add_responsive_control(
    'card_title_alignment',
    [
        'label' => __( 'Alignment', 'vertical-slider' ),
        'type' => \Elementor\Controls_Manager::CHOOSE,
        'options' => [
            'right' => [
                'title' => __( 'right', 'vertical-slider' ),
                'icon' => 'eicon-text-align-right',
            ],
            'center' => [
                'title' => __( 'Center', 'vertical-slider' ),
                'icon' => 'eicon-text-align-center',
            ],
            'left'=>[
                'title'=>__('left','vertical-slider'),
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
        'label'=> __('caption color','vertical-slider'),
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
        'label'=>__('Typography', 'vertical-slider' ),
        'selector'=>'{{WRAPPER}} .product-caption'
    ]
);
$this->add_responsive_control(
    'cart_caption_alignment',
    [
        'label'=>__('Alignment', 'vertical-slider'),
        'type'=> \Elementor\Controls_Manager::CHOOSE,
        'options'=>[
            'right'=>[
                'title'=>__('right','vertical-slider'),
                'icon'=>'eicon-text-align-right'
            ],
            'center'=>[
                'title'=>__('cenetr','vertical-slider'),
                'icon'=>'eicon-text-align-center'
            ],
            'left'=>[
                'title'=>__('left','vertical-slider'),
                'icon'=>'eicon-text-align-left'
            ],
        ],
        'default' => 'right',
        'selectors' => [
            '{{WRAPPER}} .product-caption' => 'text-align: {{VALUE}};',
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
        'label'=>__('price color','vertical-slider'),
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
        'label'=>__('typography','vertical-slider'),
        'selector'=> '{{WRAPPER}} .product-price'
    ]
);
$this->add_responsive_control(
    'card_price_alignment',
    [
        'label'=>__('Alignment', 'vertical-slider' ),
        'type'=>\Elementor\Controls_Manager::CHOOSE,
        'options'=>[
             'right' => [
                'title' => __( 'right', 'vertical-slider' ),
                'icon' => 'eicon-text-align-right',
            ],
            'center' => [
                'title' => __( 'Center', 'vertical-slider' ),
                'icon' => 'eicon-text-align-center',
            ],
            'left'=>[
                'title'=>__('left','vertical-slider'),
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
        'label'=>__('card style','vertical-slider'),
        'tab'=>\Elementor\Controls_Manager::TAB_STYLE,
    ]
);
$this->add_group_control(
    \Elementor\Group_Control_Background::get_type(),
    [
        'name' => 'card_background',
        'label' => __('cart background','vertical-slider'),
        'types' => [ 'classic', 'gradient' ],
        'selector' => '{{WRAPPER}} .product-content',
    ]
);
$this->add_group_control(
    \Elementor\Group_Control_Border::get_type(),
    [
        'label' => esc_html__( 'Border', 'vertical-slider' ),
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
$this->add_group_control(
    \Elementor\Group_Control_Box_Shadow::get_type(),
    [
        'name' => 'card_shadow',
        'label' => __('cart shadow','vertical-slider'),
        'selector' => '{{WRAPPER}} .product-content',
    ]
);
$this->add_responsive_control(
    'cart_padding',
    [
        'label' => esc_html__( 'cart padding', 'vertical-slider' ),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', 'em', '%' ],
        'selectors' => [
            '{{WRAPPER}} .product-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
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
        'label' => esc_html__( 'border radius image', 'vertical-slider' ),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%' ],
        'selectors' => [
            '{{WRAPPER}} .product-image-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
    ]
);
$this->end_controls_section();
$this->start_controls_section(
    'section_icon',
    [
        'label'=>__('icons','vertical-slider'),
        'tab'=>\Elementor\Controls_Manager::TAB_STYLE
    ]
);
$this->add_responsive_control(
    'icon_width',
    [
        'label' => esc_html__( 'عرض کارت', 'text-domain' ),
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
        'label' => __( 'icons color', 'vertical-slider' ),
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
    $settings = $this->get_settings_for_display();
    $post_type = $settings['post_type'] ?? 'post';
    $category = $settings['category'] ?? '';
    $args = [
        'post_type' => $post_type,
        'posts_per_page' => -1,
    ];
    if (!empty($category)) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $category,
            ],
        ];
    }
    $query = new \WP_Query($args);
    // var_dump($query);
    if ($query->have_posts()) : ?>
        <div class="swiper cafeSwiper">
            <div class="swiper-wrapper">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <div class="swiper-slide">
                        <div class="product-content">
                            <div class="product-image-wrapper">
                                <img class="product-image" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" alt="<?php the_title(); ?>">
                            </div>
                            <h2 class="product-title">
                                <?php
                                 the_title(); 
                                 ?>
                            </h2>
                            <div class="product-caption"><?php the_content(); ?></div>
                            <div class="product-price">
                                <p>
                                    <?php
                                    $price = get_post_meta(get_the_ID(), '_regular_price', true);
                                    echo $price ? esc_html($price) . ' تومان' : '—';
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="swiper-pagination"></div>
                <svg class="swiper-button-icon swiper-button-next" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19.9201 8.9502L13.4001 15.4702C12.6301 16.2402 11.3701 16.2402 10.6001 15.4702L4.08008 8.9502" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <svg class="swiper-button-icon swiper-button-prev" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19.9201 15.0501L13.4001 8.53014C12.6301 7.76014 11.3701 7.76014 10.6001 8.53014L4.08008 15.0501" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
        </div>
    <?php
    endif;
    wp_reset_postdata();
  }
}
