<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use ElementorPro\Plugin;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Background;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Custom_Add_To_Cart extends \Elementor\Widget_Base
{

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
            'label' => __('Choose Arbitrarily text', 'farzane-widget'),
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
        $this->add_control(
            'options_style_divider',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'options_label_typography',
            'label' => __('Options Typography', 'farzane-widget'),
            'selector' => '{{WRAPPER}} .options-label',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('submit_button_style', [
            'label' => __('button Style', 'farzane-widget'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'submit_button_background',
                'label' => __('Button Background', 'farzane-widget'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .submit-form-button',
            ]
        );
        $this->start_controls_tabs('submit_button_tabs');

        $this->start_controls_tab(
            'submit_button_tab_normal',
            [
                'label' => __('Normal', 'farzane-widget'),
            ]
        );
        $this->add_control(
            'submit_button_text_color',
            [
                'label' => __('Text Color', 'farzane-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .submit-form-button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'submit_button_tab_hover',
            [
                'label' => __('Hover', 'farzane-widget'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'submit_button_hover_background',
                'label' => __('Button Hover Background', 'farzane-widget'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .submit-form-button:hover',
            ]
        );
        $this->add_control(
            'submit_button_hover_text_color',
            [
                'label' => __('Text Hover Color', 'farzane-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .submit-form-button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab(); // End hover tab
        $this->end_controls_tabs();

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'submit_button_typography',
            'label' => __('Typography', 'farzane-widget'),
            'selector' => '{{WRAPPER}} .submit-form-button',
        ]);
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'label' => esc_html__('Border', 'farzane-widget'),
                'name' => 'submit_button_border',
                'selector' => '{{WRAPPER}} .submit-form-button',
            ]
        );
        $this->add_responsive_control(
            'submit_button_radius',
            [
                'label' => esc_html__('Border Radius', 'text-domain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .submit-form-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            "label_input_text",
            [
                "label" => __("input and label", "farzane-widget"),
                "tab" => Controls_Manager::TAB_STYLE
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'input_and_label_typography',
                'label' => __('Typography', 'farzane-widget'),
                'selector' => '{{WRAPPER}} .input-wrapper',
            ]
        );
        $this->add_responsive_control(
            'inputs_radius',
            [
                'label' => esc_html__('Border Radius', 'text-domain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .fwi' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        wp_enqueue_style('custom-add-to-cart-style');
        $settings = $this->get_settings_for_display();
        global $product;
        if (!$product || !$product->is_type('variable')) return;


        $variations_data = [];
        foreach ($product->get_available_variations() as $variation) {
            $variation_obj = wc_get_product($variation['variation_id']);
            $variations_data[] = [
                'id' => $variation['variation_id'],
                'price_html' => $variation_obj->get_price_html(),
            ];
        }



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
                    <input type="radio" name="buying-option" id="unit" class="buying-option-checkbox" checked>
                    <label for="unit"
                           class="options-label unit-option-label"><?php echo esc_html($settings['choose_by_unit_option']); ?></label>
                </div>
                <div class="option-wrapper">
                    <input type="radio" name="buying-option" id="custom" class="buying-option-checkbox">
                    <label for="custom"
                           class="options-label custom-option-label"><?php echo esc_html($settings['custom_option']); ?></label>
                </div>
            </div>
            <div class="buying-detail-column detail-option-box">
                <!--                <div class="detail-option-box"></div>-->
            </div>
        </div>
        <script>
            const productVariations = <?php echo json_encode($variations_data); ?>;
            const detailBox = document.querySelector(".detail-option-box");
            const radios = document.querySelectorAll(".buying-option-checkbox");
            const defaultVariationId = "<?php echo esc_js($default_variation_id); ?>";
            const productId = "<?php echo esc_js($custom_product_id); ?>";

            const renderForm = (type) => {
                let html = "";
                if (type === 'unit') {
                    html = `<form method="POST" action="?add-to-cart=<?php echo esc_attr($product->get_id()); ?>" class="unit-form">
                    <div class="input-wrapper">
                    <label for="variation-id-select"><?php echo __("variables", "farzane-widget") ?></label>
    <select class="fwi" name="variation_id" id="variation-id-select">
        <?php
                    if ($product->is_type('variable')) :
                    $default_attributes = $product->get_default_attributes();

                    foreach ($product->get_available_variations() as $variation_data) :
                    $variation = wc_get_product($variation_data['variation_id']);
                    $variation_attributes = $variation->get_attributes();

                    // بررسی برای نمایش ندادن متغیر پیش‌فرض
                    $is_default = true;
                    foreach ($default_attributes as $name => $value) {
                        if (!isset($variation_attributes[$name]) || $variation_attributes[$name] !== $value) {
                            $is_default = false;
                            break;
                        }
                    }

                    if ($is_default) continue;
                    $variation_label = wc_get_formatted_variation($variation, true, false, true);
                    ?>
                <option class="fwi" value="<?php echo esc_attr($variation->get_id()); ?>">
                    <?php  echo esc_html($variation_label); ?>
                </option>
            <?php
                    endforeach;
                    endif;
                    ?>
    </select>
    </div>
    <div class="input-wrapper">
    <label for="product-quantity" ><?php echo __("quantity", "farzane-widget") ?></label>
    <input id="product-quantity" class="fwi" type="number" name="quantity" value="1" min="1">
    </div>
    <div class="input-wrapper">
                    <label for="device-type" class="device-type-title"><?php echo __("type of your device", "farzane-widget") ?></label>
                    <input id="device-type" type="text" class="fwi device-type-input" name="device-type" required placeholder="<?php echo __("For Example : Moka Pot", "farzane-widget") ?>">
                </div>
    <input type="submit" class="submit-form-button" value="<?php echo __('Add to Cart', 'farzane-widget'); ?>">
</form>
`;
                } else {
                    html = `<form method="POST" action="?add-to-cart=${productId}&variation_id=${defaultVariationId}" class="custom-form">
                        <div class="input-wrapper">
                        <label for="custom-price-input" ><?php echo __("custom price", "farzane-widget") ?></label>
                        <input type="text" id="custom-price-input" class="fwi visible-price " name="custom_price" required placeholder="<?php echo __("For Example : 300000", "farzane-widget") ?>">
                        </div>
                    <div class="input-wrapper">
                    <label for="device-type" class="device-type-title"><?php echo __("type of your device", "farzane-widget") ?></label>
                    <input id="device-type" type="text" class="fwi device-type-input" name="device-type" required placeholder="<?php echo __("moka pot", "farzane-widget") ?>">
                </div>
                        <input class="submit-form-button" type="submit" value="<?php echo __('Add to Cart', 'farzane-widget'); ?>">
                    </form>`;
                }
                detailBox.innerHTML = html;
                const select = document.querySelector("#variation-id-select");
                const priceBox = document.createElement("div");
                priceBox.classList.add("variation-price-box");
                select.parentNode.appendChild(priceBox); // نمایش زیر select

                const updatePrice = () => {
                    const selectedId = parseInt(select.value);
                    const variation = productVariations.find(v => v.id === selectedId);
                    if (variation) {
                        priceBox.innerHTML = `<strong>${variation.price_html}</strong>`;
                    } else {
                        priceBox.innerHTML = "";
                    }
                };

                select.addEventListener("change", updatePrice);
                updatePrice();
            }

            radios.forEach(el => el.addEventListener('change', (e) => renderForm(e.target.id)));
            window.addEventListener("DOMContentLoaded", () => renderForm('unit'));
        </script>
        <?php
    }
}