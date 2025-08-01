<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use ElementorPro\Plugin;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Custom_Add_To_Cart extends \Elementor\Widget_Base {

    public function get_name() {
        return 'custom-add-to-cart';
    }

    public function get_title() {
        return esc_html__('Add To Cart', 'farzane-widget');
    }

    public function get_icon() {
        return 'eicon-woo-cart';
    }

    public function get_keywords() {
        return ['woocommerce-elements', 'shop', 'store', 'categories', 'product'];
    }

    public function get_categories() {
        return ['basic'];
    }

    public function get_style_depends(): array {
        return ['widget-woocommerce-products', 'widget-woocommerce-categories'];
    }

    public function get_script_depends() {
        return ['custom-add-to-cart-script'];
    }

    protected function register_controls() {
        $this->start_controls_section('content_section', [
            'label' => __('Content', 'farzane-widget'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('choose_buy_option_text', [
            'label' => __('Choose Option Text', 'farzane-widget'),
            'type' => Controls_Manager::TEXT,
        ]);

        $this->add_control('choose_by_unit_option', [
            'label' => __('Choose By Unit text', 'farzane-widget'),
            'type' => Controls_Manager::TEXT,
        ]);

        $this->add_control('custom_option', [
            'label' => __('Choose Arbitrarily', 'farzane-widget'),
            'type' => Controls_Manager::TEXT,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('text_option_style', [
            'label' => __('Content Style', 'farzane-widget'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('option_text_color', [
            'label' => __('Option Text Color', 'farzane-widget'),
            'type' => Controls_Manager::COLOR,
            'default' => '#333333',
            'selectors' => ['{{WRAPPER}} .choose-option-text' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'option_text_typography',
            'label' => __('Typography', 'farzane-widget'),
            'selector' => '{{WRAPPER}} .choose-option-text',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        global $product;
        if (!$product || !$product->is_type('variable')) return;

        $custom_product_id = $product->get_id();
        $default_attributes = $product->get_default_attributes();
        $default_variation_id = 0;

        foreach ($product->get_available_variations() as $variation) {
            $match = true;
            foreach ($variation['attributes'] as $key => $value) {
                $attribute_name = str_replace('attribute_', '', $key);
                if (!isset($default_attributes[$attribute_name]) || $default_attributes[$attribute_name] !== $value) {
                    $match = false;
                    break;
                }
            }
            if ($match) {
                $default_variation_id = $variation['variation_id'];
                break;
            }
        }

        ?>
        <div class="add-cart-wrapper">
            <div class="option-column">
                <h5 class="choose-option-text"><?php echo esc_html($settings['choose_buy_option_text']); ?></h5>
                <div class="option-wrapper">
                    <input type="radio" name="buying-option" id="unit-option" class="buying-option-checkbox" checked>
                    <label for="unit-option" class="unit-option-label"><?php echo esc_html($settings['choose_by_unit_option']); ?></label>
                </div>
                <div class="option-wrapper">
                    <input type="radio" name="buying-option" id="custom-option" class="buying-option-checkbox">
                    <label for="custom-option" class="custom-option-label"><?php echo esc_html($settings['custom_option']); ?></label>
                </div>
            </div>
            <div class="buying-detail-column">
                <div class="detail-option-box"></div>
                <div class="device-type-box">
                    <h6 class="device-type-title"><?php echo __("Choose type of your device", "farzane-widget") ?></h6>
                    <input type="text" class="device-type-input" name="device-type">
                </div>
            </div>
        </div>
        <script>
            const detailBox = document.querySelector(".detail-option-box");
            const radios = document.querySelectorAll(".buying-option-checkbox");
            const defaultVariationId = "<?php echo esc_js($default_variation_id); ?>";
            const productId = "<?php echo esc_js($custom_product_id); ?>";

            const renderForm = (type) => {
                let html = "";
                if (type === 'unit') {
                    html = `<form method="POST" action="?add-to-cart=${productId}" class="unit-form">
                        <select name="product-variable">
                            <?php foreach ($product->get_available_variations() as $variation) : ?>
                                <option value="<?php echo esc_attr($variation['variation_id']); ?>">
                                    <?php echo wc_get_formatted_variation($variation, true, false, true); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="quantity" value="1" min="1">
                        <input type="submit" value="<?php echo __('Add to Cart', 'farzane-widget'); ?>">
                    </form>`;
                } else {
                    html = `<form method="POST" action="?add-to-cart=${productId}&variation_id=${defaultVariationId}" class="custom-form">

                        <input type="text" class="visible-price" name="custom_price">
                        <input type="submit" value="<?php echo __('Add to Cart', 'farzane-widget'); ?>">
                    </form>`;
                }
                detailBox.innerHTML = html;
            }

            radios.forEach(el => el.addEventListener('change', (e) => renderForm(e.target.id)));
            window.addEventListener("DOMContentLoaded", () => renderForm('unit'));
        </script>
        <?php
    }
}
