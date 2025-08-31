# WordPress Image Optimization Plugin

[![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v2%2B-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Version](https://img.shields.io/badge/Version-1.0.0-orange.svg)](https://github.com/hienhoceo-dpsmedia/wordpress-image-optimization-plugin/releases)

> **🚀 Boost Your PageSpeed Insights Score & Core Web Vitals**  
> Automatically convert your JPEG/PNG images to modern WebP/AVIF formats to reduce download time, improve perceived page load performance, and enhance your Largest Contentful Paint (LCP) scores.

## 📊 Key Benefits

- **🎯 Improve LCP Score**: Reduce image file sizes by 25-50% to speed up largest image loading times
- **⚡ Faster Page Loads**: Smaller images mean faster download times and better user experience
- **📱 Mobile Performance**: Especially beneficial for mobile users with slower connections
- **🔧 No Server Overload**: Convert images on-demand without background processing that slows your site
- **📈 SEO Benefits**: Better PageSpeed scores can improve your search engine rankings

## 🌟 Features

### Core Functionality
- **Manual Image Optimization**: Full control over when and how images are optimized
- **WebP/AVIF Conversion**: Convert JPEG/PNG to modern formats with fallback support
- **Real-time Progress Tracking**: Monitor optimization progress with detailed reporting
- **Bulk Optimization**: Process multiple images from Media Library
- **Vietnamese Language Priority**: Vietnamese interface with English fallback

### Technical Features
- **No Background Processing**: Prevents server overload during optimization
- **Multiple Image Libraries**: Supports both Imagick and GD libraries
- **Smart Fallback**: Automatic fallback to original images for unsupported browsers
- **htaccess Management**: Automatic WebP serving rules configuration
- **Export Reports**: JSON/CSV export of optimization statistics

## 🚀 Quick Start - 3 Steps

### 1. 📊 Scan for Optimization Opportunities
Automatically finds all JPEG/PNG images that can be optimized to improve your PageSpeed score.

### 2. ⚡ Convert to Modern Formats
Converts images to WebP/AVIF formats for faster loading, smaller file sizes, and better LCP scores.

### 3. 🎯 Automatic Performance Setup
Sets up serving rules and provides LiteSpeed Cache recommendations.

## 📥 Installation

### From WordPress Admin (Recommended)
1. Download the plugin ZIP file from [Releases](https://github.com/hienhoceo-dpsmedia/wordpress-image-optimization-plugin/releases)
2. Go to WordPress Admin → Plugins → Add New
3. Click "Upload Plugin" and select the ZIP file
4. Activate the plugin
5. Navigate to "Image Optimization" in the admin menu

### Manual Installation
1. Download and extract the plugin files
2. Upload the `image-optimization` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Navigate to "Image Optimization" in the admin menu

## ⚙️ System Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **Image Library**: Imagick or GD extension
- **Server**: Write permissions for .htaccess (optional)

## 🔧 Configuration

### Basic Setup
1. Go to **Image Optimization** in your WordPress admin
2. Click **"Start Complete Optimization"** for one-click setup
3. Monitor progress in the real-time dashboard
4. Review optimization results and statistics

### Advanced Settings
- **Image Size Options**: Choose which image sizes to optimize
- **Quality Settings**: Configure WebP/AVIF quality levels
- **Serving Methods**: Select how optimized images are delivered
- **LiteSpeed Integration**: Enable for LiteSpeed Cache users

## 📈 Performance Impact

### Before Optimization
- Large JPEG/PNG files
- Slower page load times
- Poor PageSpeed Insights scores
- High bandwidth usage

### After Optimization
- 25-50% smaller file sizes
- Improved Core Web Vitals
- Better PageSpeed Insights scores
- Enhanced mobile performance

## 🛠️ Technical Architecture

### Plugin Structure
```
image-optimization/
├── admin/                      # Admin interface
│   ├── css/admin.css          # Admin styling
│   ├── js/admin.js            # Client-side logic
│   ├── views/dashboard.php    # Main dashboard
│   └── class-image-optimization-admin.php
├── includes/                   # Core functionality
│   ├── class-image-optimization-converter.php
│   ├── class-image-optimization-core.php
│   └── class-image-optimization-settings.php
├── languages/                  # Translation files
│   ├── improve-image-delivery-pagespeed-vi_VN.po
│   ├── improve-image-delivery-pagespeed-en_US.po
│   └── improve-image-delivery-pagespeed.pot
├── image-optimization.php      # Main plugin file
├── readme.txt                 # WordPress.org readme
└── uninstall.php              # Cleanup script
```

### Design Patterns
- **MVC Architecture**: Separation of views, controllers, and models
- **Strategy Pattern**: Multiple image conversion strategies
- **Observer Pattern**: WordPress hooks and filters integration

## 🌍 Language Support

- **Primary**: Vietnamese (vi_VN) - Default interface language
- **Fallback**: English (en_US) - Available for international users
- **Extensible**: Ready for additional language translations

## 🤝 Contributing

We welcome contributions! Please feel free to submit a Pull Request.

### Development Setup
1. Clone the repository
2. Set up a local WordPress development environment
3. Install the plugin in development mode
4. Make your changes and test thoroughly

### Coding Standards
- Follow WordPress Coding Standards
- Use proper PHPDoc documentation
- Ensure compatibility with PHP 7.4+
- Test with multiple WordPress versions

## 📝 Changelog

### Version 1.0.0 (2024-08-31)
- **Initial Release**: Complete PageSpeed Insights and Core Web Vitals optimization plugin
- **Vietnamese Language Support**: Primary language support with English fallback
- **Manual Image Optimization**: Full control over optimization process without server overload
- **WebP/AVIF Conversion**: Modern image format support with automatic fallback
- **Real-time Progress Tracking**: Live optimization progress monitoring
- **LiteSpeed Cache Integration**: Optimized for LiteSpeed Cache users
- **htaccess Management**: Automatic WebP serving rules configuration
- **Export Functionality**: JSON/CSV optimization reports

## 📧 Support & Contact

- **Developer**: HỒ QUANG HIỂN
- **Company**: DPS.MEDIA
- **Email**: hello@dps.media
- **Website**: [dps.media](https://dps.media)
- **Support**: [dps.media/support](https://dps.media/support/)

## 📄 License

This plugin is licensed under the GPL v2 or later.

```
Copyright (C) 2024 HỒ QUANG HIỂN

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## 🔗 Links

- **GitHub Repository**: [wordpress-image-optimization-plugin](https://github.com/hienhoceo-dpsmedia/wordpress-image-optimization-plugin)
- **WordPress.org**: [Coming Soon]
- **Documentation**: [GitHub Wiki](https://github.com/hienhoceo-dpsmedia/wordpress-image-optimization-plugin/wiki)
- **Issues**: [Report Bugs](https://github.com/hienhoceo-dpsmedia/wordpress-image-optimization-plugin/issues)

---

**💡 Perfect for website owners who want to optimize their PageSpeed Insights scores and improve Core Web Vitals performance with complete control over image conversion.**