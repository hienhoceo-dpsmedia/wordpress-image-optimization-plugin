# Changelog

All notable changes to the Improve Image Delivery PageSpeed plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2025-08-31

### 🔧 Critical Fixes
- **Fixed plugin activation fatal errors during updates** - Resolved "Plugin could not be activated because it triggered a fatal error" issue
- **Enhanced plugin update compatibility** - No longer requires manual removal of old versions before installing new ones
- **Improved class loading with existence checks** - Prevents class redeclaration errors during WordPress plugin updates

### 🚀 Performance Optimizations  
- **Safer plugin initialization** - Uses WordPress `plugins_loaded` hook for better compatibility
- **Enhanced dependency loading** - Better error handling and file existence validation
- **Optimized memory usage** - More efficient Vietnamese translation loading system
- **Reduced agent lag and performance issues** - Streamlined codebase for better IDE performance

### 🛠️ Technical Improvements
- Added comprehensive class existence checks to all core classes
- Implemented safer plugin initialization patterns
- Enhanced error handling for missing dependency files
- Improved plugin update workflow compatibility
- Better WordPress standards compliance

### 📋 Developer Notes
- This version resolves the critical update compatibility issues reported by users
- All class files now include proper existence checks to prevent conflicts
- Plugin initialization is now deferred to ensure WordPress is fully loaded
- Enhanced error messages for better debugging experience

### ⚡ Compatibility
- WordPress: 5.0+
- PHP: 7.4+
- Tested with WordPress 6.6
- Compatible with all major hosting environments

---

**This is a critical stability release that fixes plugin update issues. Users experiencing activation errors should upgrade immediately.**

## [1.0.0] - 2024-08-31

### Added
- **Initial Release**: Complete PageSpeed Insights and Core Web Vitals optimization plugin
- **Vietnamese Language Support**: Primary language support with English fallback
- **Modern Image Format Conversion**: Automatic JPEG/PNG to WebP/AVIF conversion
- **Smart Server Detection**: Comprehensive capability detection for ImageMagick and GD libraries
- **Intelligent Format Selection**: User choice between WebP only, AVIF only, or both formats
- **PageSpeed Optimization Focus**: Specifically designed to improve PageSpeed Insights scores
- **LCP Targeting**: Optimizes Largest Contentful Paint (LCP) for better Core Web Vitals
- **Multiple Serving Methods**: 
  - LiteSpeed Cache integration for optimal performance
  - .htaccess rules for server-level image serving
  - URL replacement for PHP-level serving
- **Real-time Analytics**: Comprehensive optimization progress and statistics dashboard
- **Bulk Processing**: Convert multiple images with real-time progress tracking
- **Export Functionality**: Generate optimization reports in JSON/CSV formats
- **Reversible Optimization**: One-click removal of all WebP/AVIF files
- **Mobile-First Approach**: Optimized for mobile PageSpeed scores
- **Professional WordPress.org Compliance**: 
  - Proper internationalization (i18n) support
  - Comprehensive security with input sanitization and validation
  - Object-oriented architecture with proper class structure
  - Secure AJAX handling with nonce verification
  - Proper capability checks and user permissions

### Features
- **PageSpeed-Focused UI**: Dashboard designed around PageSpeed optimization goals
- **Server Capability Reporting**: Real-time display of WebP/AVIF support status
- **Browser-Aware Serving**: Automatically serves best format based on browser support
- **Quality Control**: Configurable compression settings with smart defaults
- **Size Thresholds**: Intelligent filtering to optimize only beneficial images
- **Media Library Integration**: Status columns and bulk actions in WordPress Media Library
- **Progress Feedback**: Visual progress indicators for all operations
- **Error Handling**: Comprehensive error reporting and user guidance
- **Performance Optimization**: Designed to avoid server overload with manual control

### Technical Specifications
- **WordPress Compatibility**: 5.0+ (tested up to 6.6)
- **PHP Requirements**: 7.4+
- **Image Libraries**: Support for both ImageMagick and GD
- **Input Formats**: JPEG and PNG images
- **Output Formats**: WebP and AVIF (where supported)
- **Security**: GPL v2+ licensed with comprehensive security measures

---

## About This Plugin

**Developed by**: HỒ QUANG HIỂN - DPS.MEDIA JSC  
**Company**: CÔNG TY CỔ PHẦN DPS.MEDIA (DPS.MEDIA Joint Stock Company)  
**Since**: 2017  
**Customers Served**: 5,400+  
**Specialization**: Comprehensive Digital Marketing solutions for SMEs  

**Contact Information**:  
📧 Email: marketing@dps.media  
📞 Phone: +84 961 545 445  
🏢 Address: 56 Nguyễn Đình Chiểu, Tân Định Ward, Ho Chi Minh City, Vietnam  
🌐 Website: https://dps.media/  
🏛️ Tax ID: 0318700500