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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Custom_Add_To_Cart extends \Elementor\Widget_Base
{
    protected $_has_template_content = false;

    public function get_name()
    {
        return 'custom-add-to-cart';
    }

    public function get_title()
    {
        return esc_html__('Add To Cart', 'farzane-widget');
    }

    public function get_icon()
    {
        return 'eicon-woo-cart';
    }

    public function get_keywords()
    {
        return ['woocommerce-elements', 'shop', 'store', 'categories', 'product'];
    }

    public function has_widget_inner_wrapper(): bool
    {
        return !Plugin::elementor()->experiments->is_feature_active('e_optimized_markup');
    }

    public function get_categories()
    {
        return ['basic'];
    }

    public function get_style_depends(): array
    {
        return ['widget-woocommerce-products', 'widget-woocommerce-categories'];
    }

    public function get_script_depends()
    {
        return ['custom-add-to-cart-script'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            "content_section",
            [
                'label' => __('Content', 'farzane-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'choose_buy_option_text',
            [
                'label' => __('Choose Option Text', 'farzane-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );
        $this->add_control(
            'option_text_divider',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        $this->add_control(
            'choose_by_unit_option',
            [
                'label' => __('Choose By Unit text', 'farzane-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );
        $this->add_control(
            'unit_option_text_divider',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        $this->add_control(
            'custom_option',
            [
                'label' => __('Choose Arbitrarily', 'farzane-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );
        $this->add_control(
            'custom_option_text_divider',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'text_option_style',
            [
                'label' => __('Content Style', 'farzane-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'option_text_color',
            [
                'label' => __('Option Text Color', 'farzane-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .choose-option-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'option_text_typography',
                'label' => __('Typography', 'farzane-widget'),
                'selector' => '{{WRAPPER}} .choose-option-text',
            ]
        );
        $this->add_control(
            'divider1',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        $this->add_control(
            'unit_option_color',
            [
                'label' => __('Unit Option Color', 'farzane-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .unit-option-label' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'unit_option_typography',
                'label' => __('Typography', 'farzane-widget'),
                'selector' => '{{WRAPPER}} .unit-option-label',
            ]
        );
        $this->add_control(
            'divider2',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        $this->add_control(
            'custom_option_color',
            [
                'label' => __('Cutom Option Color', 'farzane-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .custom-option-label' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'option_typography',
                'label' => __('Typography', 'farzane-widget'),
                'selector' => '{{WRAPPER}} .custom-option-label',
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();
        global $product;
        $custom_product_id = $product ? $product->get_id() : 0;


        ?>
        <div class="add-cart-wrapper">
            <div class="option-column">
                <h5 class="choose-option-text"><?php echo esc_html($settings['choose_buy_option_text']); ?></h5>

                <div class="option-wrapper">
                    <input type="radio" name="buying-option" id="unit-option" class="buying-option-checkbox">
                    <label for="unit-option"
                           class="unit-option-label"><?php echo esc_html($settings['choose_by_unit_option']); ?></label>
                </div>
                <div class="option-wrapper">
                    <input type="radio" name="buying-option" id="custom-option" class="buying-option-checkbox">
                    <label for="custom-option"
                           class="custom-option-label"><?php echo esc_html($settings['custom_option']); ?></label>
                </div>
            </div>
            <div class="buying-detail-column">
                <div class="detail-option-box">

                </div>
                <div class="device-type-box">
                    <h6 class="device-type-title"><?php echo __("Choose type of your device", "farzane-widget") ?></h6>
                    <input type="text" class="device-type-input" name="device-type">
                </div>


            </div>
        </div>
        <script>
            const unitOptionRadio = document.querySelector("#unit-option")
            const customOptionRadio = document.querySelector("#custom-option")
            const detailOptionBox = document.querySelector(".detail-option-box")
            const chooseBuyingOptionRadio = document.querySelectorAll(".buying-option-checkbox")
            let RadioFlag="unit"
            const changeBuyingOption = (event) => {
                const getCheckBoxId = event.target.getAttribute("id")
                if (getCheckBoxId === "unit-option") {
                    detailOptionBox.innerHTML = ""
                    RadioFlag="unit"
                    detailOptionBox.innerHTML = `
                <form method="POST" action="?add-to-cart=<?php echo $custom_product_id ?> class="unit-option-container-form" >
                    <div class="product-variable-option-box">
                        <h6 class="product-variable-option-title"><?php echo __("choose variable","farzane-widget") ?></h6>
                        <?php
                    global $product;
                    if ( $product->is_type( 'variable' ) ) {
                        $available_variations = $product->get_available_variations();
                        $attributes = $product->get_variation_attributes();
                    ?>
                        <select name="product-variable" class="product-variable">
                            <?php foreach ( $available_variations as $variation ) : ?>
                                <option value="<?php echo esc_attr( $variation['variation_id'] ); ?>">
                                    <?php echo wc_get_formatted_variation( $variation, true, false, true ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                            <?php
                    }
                        ?>
                    </div>
                    <div class="product-quantity-box">
                        <h6 class="product-quantity-title"><?php echo __("quantity","farzane-widget") ?></h6>
                        <input type="number" name="product-quantity" class="product-quantity" value="1" min="1">
                    </div>
                    <div class="price-box">
                        <h6 class="price-title"><?php echo __("total price","farzane-widget") ?></h6>
                        <p class="price-text"></p>
                    </div>
                    <input type="submit" class="add-to-cart" value="<?php echo __("add to cart","farzane-widget") ?>">
                </form>
        `
                } else {
                    RadioFlag="custom"
                    detailOptionBox.innerHTML = ""
                    detailOptionBox.innerHTML = `
<form method="POST" action="?add-to-cart=<?php echo $custom_product_id ?>" class="custom-option-container-form">
    <input type="hidden" name="custom_price" class="custom-price-hidden">
    <div class="custom-price-box">
        <h6 class="custom-price-title"><?php echo __("custom price","farzane-widget") ?></h6>
        <input type="text" class="custom-price-input" name="custom-price-visible">
        <input type="hidden" name="custom_price" class="custom-price-hidden">
    </div>
    <div class="convert-price-to-weight-box">
        <h6 class="convert-price-to-weight-title"><?php echo __("weight of coffee","farzane-widget") ?></h6>
        <p class="convert-price-to-weight-text"></p>
    </div>
    <input type="submit" class="add-to-cart" value="<?php echo __("add to cart","farzane-widget") ?>">
</form>`
                }
                setTimeout(() => {
                    const visibleInput = document.querySelector('.custom-price-input');
                    const hiddenInput = document.querySelector('.custom-price-hidden');

                    visibleInput.addEventListener('input', () => {
                        hiddenInput.value = visibleInput.value;
                    });
                }, 50);

            }
            chooseBuyingOptionRadio.forEach((item) => {
                item.addEventListener("change", changeBuyingOption)
            })
            window.addEventListener("load", () => {
                unitOptionRadio.checked = true
                detailOptionBox.innerHTML = `
                <form class="unit-option-container-form" >
                    <div class="product-variable-option-box">
                        <h6 class="product-variable-option-title"><?php echo __("choose variable","farzane-widget") ?></h6>
                        <?php
                global $product;
                if ( $product->is_type( 'variable' ) ) {
                $available_variations = $product->get_available_variations();
                $attributes = $product->get_variation_attributes();
                ?>
                        <select name="product-variable" class="product-variable">
                            <?php foreach ( $available_variations as $variation ) : ?>
                                <option value="<?php echo esc_attr( $variation['variation_id'] ); ?>">
                                    <?php echo wc_get_formatted_variation( $variation, true, false, true ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                            <?php
                }
                ?>
                    </div>
                    <div class="product-quantity-box">
                        <h6 class="product-quantity-title"><?php echo __("quantity","farzane-widget") ?></h6>
                        <input type="number" name="product-quantity" class="product-quantity" value="1" min="1">
                    </div>
                    <div class="price-box">
                        <h6 class="price-title"><?php echo __("total price","farzane-widget") ?></h6>
                        <p class="price-text"></p>
                    </div>
                    <input type="submit" class="add-to-cart" value="<?php echo __("add to cart","farzane-widget") ?>">
                </form>
        `
            })
            addToCartForm.addEventListener("submit",submitAddingToCartForm)
        </script>
        <?php

    }
}