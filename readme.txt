=== Improve Image Delivery PageSpeed ===
Contributors: hoquanghien, dpsmedia
Donate link: https://dps.media/donate/
Tags: pagespeed, core web vitals, webp, avif, image optimization, lcp, performance, speed
Requires at least: 5.0
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Boost PageSpeed Insights scores and Core Web Vitals by converting JPEG/PNG to WebP/AVIF. Reduces image download time and improves LCP performance.

== Description ==

🚀 **Improve Image Delivery PageSpeed** is specifically designed for website owners who want to optimize their PageSpeed Insights scores and improve Core Web Vitals performance. The plugin automatically converts your uploaded JPEG/PNG images into modern WebP/AVIF formats wherever supported, dramatically reducing download times and enhancing your Largest Contentful Paint (LCP) scores.

### 🎯 PageSpeed Optimization Focus

**Boost Your PageSpeed Insights Score:**
* **Improve LCP (Largest Contentful Paint)**: Reduce image file sizes by 25-50% to speed up largest image loading times
* **Better Core Web Vitals**: Smaller images mean faster download times and improved user experience
* **Mobile Performance**: Especially beneficial for mobile users with slower connections
* **SEO Benefits**: Better PageSpeed scores can improve your search engine rankings
* **User Experience**: Faster loading images lead to better engagement and lower bounce rates

**Perfect for:**
* Website owners wanting to improve PageSpeed Insights scores
* Sites targeting better Core Web Vitals performance
* E-commerce sites with many product images
* Photography and portfolio websites
* Any site where image loading speed matters

### 🌍 Multi-language Support

* **Vietnamese (Tiếng Việt)**: Full interface translation for Vietnamese users
* **English**: Complete English interface
* More languages coming soon

### 🚀 Key PageSpeed Features

* **Automatic Format Detection**: Intelligently converts to WebP or AVIF based on server capabilities
* **Smart Conversion**: Only converts images that will actually improve loading performance
* **LCP Optimization**: Specifically targets large images that affect Largest Contentful Paint scores
* **Real-time PageSpeed Impact**: See how much your optimization improves loading times
* **Core Web Vitals Ready**: Designed with Google's Core Web Vitals in mind
* **Smart Fallback Options**: 
  * Built-in URL replacement for sites not using LiteSpeed Cache
  * Automatic .htaccess rules generation and management
  * Browser-aware serving (WebP for modern browsers, originals for older ones)
* **Bulk Operations**: Convert multiple images at once from Media Library
* **Real-time Progress**: Watch optimization progress with live progress bars
* **Export Reports**: Export optimization reports in JSON or CSV format
* **Easy Reversal**: Remove all WebP files with one click if needed

### 🎯 PageSpeed Performance Benefits

* **25-50% Smaller Files**: WebP/AVIF images are significantly smaller than JPEG/PNG
* **Faster LCP Scores**: Improved Largest Contentful Paint times
* **Better Core Web Vitals**: Enhanced overall performance metrics
* **Reduced Bandwidth**: Save on hosting costs and CDN usage
* **Mobile Optimization**: Especially beneficial for mobile users
* **SEO Boost**: Faster sites rank better in search results

### 🛠️ How PageSpeed Optimization Works

1. **Smart Scanning**: Identifies images that will benefit most from optimization
2. **Format Selection**: Chooses WebP or AVIF based on browser and server support
3. **Quality Optimization**: Maintains visual quality while maximizing size reduction
4. **Performance Serving**: Delivers optimized images for better PageSpeed scores
5. **Core Web Vitals**: Specifically targets metrics that affect Google rankings

### 📊 PageSpeed-Focused Optimization

The plugin includes intelligent optimization specifically for PageSpeed Insights:

* **LCP-targeted conversion**: Prioritizes large images that affect Core Web Vitals
* **Smart file size thresholds**: Only converts images that will meaningfully improve loading
* **Format selection**: Automatically chooses WebP or AVIF for best performance
* **Mobile-first approach**: Optimizes for mobile PageSpeed scores

### 🔧 Technical Details

* **Requirements**: WordPress 5.0+, PHP 7.4+
* **Image Formats**: Supports JPEG and PNG input
* **Output Formats**: WebP (with AVIF support planned)
* **Libraries**: Works with both Imagick and GD
* **Integration**: Optimized for LiteSpeed Cache

### 🎨 User Experience

* **Clean Interface**: Modern, intuitive dashboard
* **Real-time Feedback**: Live progress indicators
* **Detailed Reporting**: Comprehensive optimization statistics
* **Media Library Integration**: Status columns and quick actions
* **Bulk Actions**: Process multiple images simultaneously

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/improve-image-delivery-pagespeed/` directory, or install through WordPress admin
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Navigate to 'Improve Image Delivery PageSpeed' in your admin menu
4. Configure your optimization settings
5. Click 'Start Complete Optimization' to begin converting your images

== Frequently Asked Questions ==

= What image formats are supported? =

The plugin converts JPEG and PNG images to WebP format. AVIF support is planned for future releases.

= Can I remove all WebP files if I don't want them anymore? =

Yes! The plugin includes a "Remove All WebP Files" button that will safely delete all WebP files created by the plugin while preserving your original images.

= What's the difference between this and automatic optimization plugins? =

Automatic plugins process images in the background, which can cause server load spikes and unexpected processing. This plugin gives you manual control - you decide when to optimize, preventing server overload and giving you transparency over the process.

= Do I need LiteSpeed Cache? =

No, but it's recommended for optimal performance. The plugin includes fallback options:

* **URL Replacement**: Automatically replaces image URLs in HTML with WebP versions
* **.htaccess Rules**: Automatically generates and manages server rules for WebP serving
* **Browser Detection**: Serves WebP to modern browsers, originals to older ones

These fallback options work without LiteSpeed Cache, but LiteSpeed Cache provides better performance and caching.

= How do the .htaccess rules work? =

The plugin can automatically add rules to your .htaccess file that:

* Detect if the browser supports WebP format
* Check if a WebP version exists for the requested image
* Automatically serve the WebP version to compatible browsers
* Fall back to original images for older browsers

You can preview, add, or remove these rules from the plugin dashboard.

= Will this break my website? =

No. Original images are preserved, and the plugin serves WebP versions only when browsers support them. Older browsers automatically receive the original images.

= How much space can I save? =

WebP images are typically 25-35% smaller than JPEG equivalents with the same visual quality. Results may vary depending on your images.

= Can I convert images in bulk? =

Yes. You can use the bulk actions in the Media Library or the batch conversion tools in the plugin dashboard.

= What happens to my original images? =

Original images are preserved. The plugin creates WebP versions alongside your originals, ensuring complete compatibility.

= Does this work with CDNs? =

The plugin works best with LiteSpeed Cache and LiteSpeed's CDN. For other CDNs, you may need additional configuration.

== Screenshots ==

1. **Main Dashboard** - Overview of optimization status and controls
2. **Settings Panel** - Configure optimization parameters
3. **Progress Tracking** - Real-time conversion progress
4. **Media Library Integration** - Status columns and bulk actions
5. **Analytics View** - Detailed optimization statistics

== Changelog ==

= 1.0.0 =
* Initial release
* Complete PageSpeed Insights and Core Web Vitals optimization focus
* Vietnamese language support as primary language with English fallback
* Automatic conversion of JPEG/PNG images to modern WebP/AVIF formats
* Smart server capability detection for ImageMagick and GD libraries
* Intelligent format selection (WebP only, AVIF only, or both formats)
* Real-time PageSpeed impact reporting and optimization analytics
* LCP (Largest Contentful Paint) targeting for better Core Web Vitals
* Multiple serving options: LiteSpeed Cache integration, .htaccess rules, URL replacement
* Comprehensive dashboard with PageSpeed optimization messaging
* Bulk image processing with real-time progress tracking
* Export functionality for optimization reports (JSON/CSV)
* Easy reversal with one-click WebP/AVIF file removal
* Mobile-first optimization approach for better mobile PageSpeed scores
* Professional WordPress.org compliance with proper internationalization
* Secure development with comprehensive input sanitization and validation

== Upgrade Notice ==

= 1.0.0 =
Initial release of Improve Image Delivery PageSpeed plugin. Designed specifically for website owners who want to boost their PageSpeed Insights scores and improve Core Web Vitals performance.

= 2.0.0 =
Major feature update with thumbnail support and improved analytics. Please backup before upgrading.

== Privacy Policy ==

This plugin does not collect, store, or transmit any personal data. All image processing happens locally on your server. No external services are used for image conversion.

== About DPS.MEDIA ==

**DPS.MEDIA JSC** specializes in providing comprehensive Digital Marketing solutions for SMEs, with hands-on experience since 2017 and serving over 5,400 customers.

**Company Information:**
* **Full Name**: CÔNG TY CỔ PHẦN DPS.MEDIA (DPS.MEDIA Joint Stock Company)
* **Address**: 56 Nguyễn Đình Chiểu, Tân Định Ward, Ho Chi Minh City, Vietnam
* **Email**: marketing@dps.media
* **Phone**: +84 961 545 445
* **Tax ID**: 0318700500
* **Website**: https://dps.media/

We are committed to providing high-quality WordPress solutions that help businesses optimize their digital presence and improve website performance.

== Support ==

For support, feature requests, or bug reports:
* **WordPress.org Support Forum**: Available after plugin approval
* **Email Support**: marketing@dps.media
* **Company Website**: https://dps.media/
* **Phone Support**: +84 961 545 445 (Vietnam timezone)

Our team is dedicated to providing excellent customer support and continuously improving our plugins based on user feedback.

== Credits ==

Developed by DPS.MEDIA with love for the WordPress community.