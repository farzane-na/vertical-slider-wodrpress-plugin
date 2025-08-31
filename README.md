# Custom Elementor Widgets Plugin

A professional WordPress plugin that adds three custom Elementor widgets to your site and integrates seamlessly with WooCommerce.

## 📋 Table of Contents

- [Description](#description)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Widgets](#widgets)
- [Configuration](#configuration)
- [Files](#files)
- [Development](#development)
- [Support](#support)
- [Changelog](#changelog)
- [License](#license)

## 🎯 Description

This plugin adds three custom and powerful widgets to Elementor, designed for e-commerce and business websites. The widgets include a vertical product slider, product category display, and custom add-to-cart button with advanced WooCommerce integration.

## ✨ Features

- **Vertical Product Slider**: Advanced vertical slider with directional controls and product display
- **Product Category Widget**: Beautiful display of product categories with customizable options
- **Custom Add to Cart**: Advanced button with custom pricing and metadata capabilities
- **Full WooCommerce Integration**: Custom pricing, cart metadata, and order management
- **RTL Support**: Compatible with right-to-left languages
- **Admin Dashboard**: Welcome widget in WordPress dashboard
- **Translation Ready**: Prepared for localization and translation

## 🔧 Requirements

- **WordPress**: Version 5.0 or higher
- **PHP**: Version 7.4 or higher
- **Elementor**: Version 3.0 or higher
- **WooCommerce**: Version 5.0 or higher

## 📥 Installation

### Method 1: Direct Installation

1. Upload plugin files to `/wp-content/plugins/custom-elementor-widget` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Ensure Elementor and WooCommerce are active

### Method 2: Via Git

```bash
cd wp-content/plugins/
git clone https://github.com/farzane-na/vertical-slider-wodrpress-plugin custom-elementor-widget
```

## 🚀 Usage

After activating the plugin, three new widgets will be available in Elementor:

1. **Vertical Slider** - Available in the "Basic" widgets section
2. **Product Categories Widget** - Available in the "Basic" widgets section
3. **Add To Cart** - Available in the "Basic" widgets section

## 🎨 Widgets

### 1. Vertical Slider

Advanced vertical slider widget with powerful features:

- **Direction Controls**: Left, right, up, down orientation
- **Product Display**: Based on categories or tags
- **Slider Controls**: Speed, loop, autoplay options
- **Responsive Design**: Compatible with all devices

### 2. Product Category Widget

Beautiful display of product categories:

- **Category Selection**: Manual selection of categories
- **Configurable Count**: Set number of displayed categories
- **Responsive Design**: Mobile and desktop compatible

### 3. Custom Add to Cart

Advanced button with custom capabilities:

- **Customizable Text**: Personalize button text content
- **Custom Pricing**: Set custom product prices
- **Device Type Selection**: Add custom metadata
- **Cart Integration**: Store information in cart and orders

## ⚙️ Configuration

### General Settings

The plugin configures automatically and requires no additional setup.

### WooCommerce Settings

- **Custom Pricing**: Displayed in cart and orders
- **Custom Metadata**: Device type and other custom information stored

### Language Settings

To change language:

1. Place translation files in the `/languages` folder
2. Use translation plugins like WPML or Polylang

## 📁 Files

```
custom-elementor-widget/
├── custom-elementor-widget.php      # Main plugin file
├── admin/
│   └── admin-dashboard.php          # Admin dashboard
├── widgets/
│   ├── vertical-slider-widget.php   # Vertical slider widget
│   ├── product-category-widget.php  # Product category widget
│   └── custom-add-to-cart.php      # Custom add to cart widget
├── asset/
│   ├── css/
│   │   ├── app.css                 # Custom styles
│   │   ├── add-to-cart-style.css   # Add to cart button styles
│   │   └── swiper-bundle.min.css   # Swiper library styles
│   ├── js/
│   │   ├── app.js                  # Custom scripts
│   │   └── swiper-bundle.min.js    # Swiper library
│   └── images/
│       └── coffee.webp             # Welcome image
└── languages/                       # Translation files
```

## 🛠️ Development

### Code Structure

- **Widget Classes**: Each widget has a separate class
- **Elementor Controls**: Uses standard Elementor APIs
- **WooCommerce Hooks**: Integration with cart and orders

### Adding New Widget

1. Create new widget file in the `widgets/` folder
2. Register widget class in `custom-elementor-widget.php`
3. Implement controls and functionality

### Customizing Styles

- CSS files in `asset/css/` folder
- JavaScript files in `asset/js/` folder
- Images in `asset/images/` folder

## 📞 Support

- **Author**: Farzane Nazmabadi
- **Website**: https://farzanenazmabadi.ir/
- **Text Domain**: farzane-widget
- **Version**: 1.1.1

## 📝 Changelog

### Version 1.1.0
- Added Vertical Slider widget
- Added Product Category widget
- Added Custom Add to Cart widget
- Full WooCommerce integration
- RTL support
- Admin dashboard widget


**Note**: This plugin is designed for production use and is compatible with the latest versions of WordPress, Elementor, and WooCommerce.
