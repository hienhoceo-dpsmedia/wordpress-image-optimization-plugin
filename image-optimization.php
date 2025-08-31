<?php
/**
 * Plugin Name: Improve Image Delivery PageSpeed
 * Plugin URI: https://dps.media/plugins/improve-image-delivery-pagespeed/
 * Description: Boost your PageSpeed Insights score and improve Core Web Vitals (LCP) by automatically converting JPEG/PNG images to modern WebP/AVIF formats. Reduces image download time and improves perceived page load performance.
 * Version: 1.0.9
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * Author: HỒ QUANG HIỂN
 * Author URI: https://dps.media/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: improve-image-delivery-pagespeed
 * Domain Path: /languages
 * Network: false
 *
 * @package ImageOptimization
 * @version 1.0.0
 * @author HỒ QUANG HIỂN - DPS.MEDIA JSC
 * @copyright 2024 DPS.MEDIA JSC
 * @license GPL-2.0-or-later
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'IMAGE_OPTIMIZATION_VERSION', '1.0.9' );
define( 'IMAGE_OPTIMIZATION_PLUGIN_FILE', __FILE__ );
define( 'IMAGE_OPTIMIZATION_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'IMAGE_OPTIMIZATION_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'IMAGE_OPTIMIZATION_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Main plugin class
 *
 * @since 1.0.0
 */
class Image_Optimization {

    /**
     * Plugin instance
     *
     * @since 1.0.0
     * @var Image_Optimization|null
     */
    private static $instance = null;

    /**
     * Get plugin instance
     *
     * @since 1.0.0
     * @return Image_Optimization
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    private function __construct() {
        $this->init_hooks();
        $this->load_dependencies();
    }

    /**
     * Initialize WordPress hooks
     *
     * @since 1.0.0
     */
    private function init_hooks() {
        register_activation_hook( IMAGE_OPTIMIZATION_PLUGIN_FILE, array( $this, 'activate' ) );
        register_deactivation_hook( IMAGE_OPTIMIZATION_PLUGIN_FILE, array( $this, 'deactivate' ) );

        add_action( 'init', array( $this, 'init' ) );
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
        
        // Set Vietnamese as preferred language for this plugin
        add_filter( 'plugin_locale', array( $this, 'set_plugin_locale' ), 10, 2 );
        
        // Add update notification system
        add_action( 'admin_init', array( $this, 'check_plugin_updates' ) );
        add_action( 'admin_notices', array( $this, 'show_update_notice' ) );
        add_action( 'wp_ajax_image_optimization_dismiss_update', array( $this, 'dismiss_update_notice' ) );
    }

    /**
     * Load plugin dependencies
     *
     * @since 1.0.0
     */
    private function load_dependencies() {
        require_once IMAGE_OPTIMIZATION_PLUGIN_DIR . 'includes/class-image-optimization-core.php';
        require_once IMAGE_OPTIMIZATION_PLUGIN_DIR . 'includes/class-image-optimization-converter.php';
        require_once IMAGE_OPTIMIZATION_PLUGIN_DIR . 'includes/class-image-optimization-settings.php';
        
        if ( is_admin() ) {
            require_once IMAGE_OPTIMIZATION_PLUGIN_DIR . 'admin/class-image-optimization-admin.php';
        }
    }

    /**
     * Initialize the plugin
     *
     * @since 1.0.0
     */
    public function init() {
        // Initialize core functionality
        Image_Optimization_Core::get_instance();
        Image_Optimization_Settings::get_instance();
        
        if ( is_admin() ) {
            Image_Optimization_Admin::get_instance();
        }
    }

    /**
     * Set plugin locale based on user preference or default to Vietnamese
     *
     * @since 1.0.0
     * @param string $locale The locale.
     * @param string $domain The text domain.
     * @return string
     */
    public function set_plugin_locale( $locale, $domain ) {
        if ( 'improve-image-delivery-pagespeed' === $domain ) {
            // Check user preference first
            $user_preference = get_user_meta( get_current_user_id(), 'image_optimization_language', true );
            if ( ! empty( $user_preference ) && in_array( $user_preference, array( 'vi_VN', 'en_US' ), true ) ) {
                // Load Vietnamese fallbacks if needed
                if ( $user_preference === 'vi_VN' ) {
                    add_action( 'admin_init', array( $this, 'load_vietnamese_fallbacks' ) );
                }
                return $user_preference;
            }
            
            // Check site option
            $site_preference = get_option( 'image_optimization_language', '' );
            if ( ! empty( $site_preference ) && in_array( $site_preference, array( 'vi_VN', 'en_US' ), true ) ) {
                if ( $site_preference === 'vi_VN' ) {
                    add_action( 'admin_init', array( $this, 'load_vietnamese_fallbacks' ) );
                }
                return $site_preference;
            }
            
            // Default to Vietnamese if current locale is English or empty
            if ( in_array( $locale, array( 'en_US', 'en', '' ), true ) ) {
                add_action( 'admin_init', array( $this, 'load_vietnamese_fallbacks' ) );
                return 'vi_VN';
            }
        }
        return $locale;
    }
    
    /**
     * Load Vietnamese text fallbacks when .mo file is not available
     */
    public function load_vietnamese_fallbacks() {
        // Define comprehensive Vietnamese translations as fallbacks
        $vietnamese_texts = array(
            // Language selector
            'Language:' => 'Ngôn ngữ:',
            
            // Main plugin name and headers
            'Improve Image Delivery PageSpeed' => 'Cải Thiện Tốc Độ Tải Hình Ảnh PageSpeed',
            'Boost Your PageSpeed Insights Score & Core Web Vitals' => 'Tăng Điểm PageSpeed Insights & Core Web Vitals',
            'Automatically convert your JPEG/PNG images to modern WebP/AVIF formats to reduce download time, improve perceived page load performance, and enhance your Largest Contentful Paint (LCP) scores for better Core Web Vitals.' => 'Tự động chuyển đổi hình ảnh JPEG/PNG sang định dạng WebP/AVIF hiện đại để giảm thời gian tải xuống, cải thiện hiệu suất tải trang cảm nhận và nâng cao điểm Largest Contentful Paint (LCP) cho Core Web Vitals tốt hơn.',
            'Note: This plugin defaults to Vietnamese. To permanently change language, update your WordPress admin language in Settings → General.' => 'Lưu ý: Plugin này mặc định là tiếng Việt. Để thay đổi ngôn ngữ vĩnh viễn, cập nhật ngôn ngữ quản trị WordPress trong Cài đặt → Chung.',
            
            // Benefits section
            'PageSpeed Optimization Benefits' => 'Lợi Ích Tối Ưu PageSpeed',
            'Improve LCP Score' => 'Cải Thiện Điểm LCP',
            'Reduce image file sizes by 25-50% to speed up largest image loading times.' => 'Giảm kích thước file hình ảnh 25-50% để tăng tốc thời gian tải hình ảnh lớn nhất.',
            'Faster Page Loads' => 'Tải Trang Nhanh Hơn',
            'Smaller images mean faster download times and better user experience.' => 'Hình ảnh nhỏ hơn có nghĩa là thời gian tải xuống nhanh hơn và trải nghiệm người dùng tốt hơn.',
            'Mobile Performance' => 'Hiệu Suất Mobile',
            'Especially beneficial for mobile users with slower connections.' => 'Đặc biệt có lợi cho người dùng di động với kết nối chậm hơn.',
            'No Server Overload' => 'Không Quá Tải Máy Chủ',
            'Convert images on-demand without background processing that slows your site.' => 'Chuyển đổi hình ảnh theo yêu cầu mà không có xử lý nền làm chậm trang web của bạn.',
            'SEO Benefits' => 'Lợi Ích SEO',
            'Better PageSpeed scores can improve your search engine rankings.' => 'Điểm PageSpeed tốt hơn có thể cải thiện thứ hạng công cụ tìm kiếm của bạn.',
            'Perfect for website owners who want to optimize their PageSpeed Insights scores and improve Core Web Vitals performance with complete control over image conversion.' => 'Hoàn hảo cho chủ sở hữu trang web muốn tối ưu hóa điểm PageSpeed Insights và cải thiện hiệu suất Core Web Vitals với quyền kiểm soát hoàn toàn việc chuyển đổi hình ảnh.',
            'Language Support' => 'Hỗ Trợ Ngôn Ngữ',
            'This plugin prioritizes Vietnamese language with English fallback.' => 'Plugin này ưu tiên tiếng Việt với tiếng Anh làm dự phòng.',
            
            // Quick steps section
            'Quick PageSpeed Optimization - 3 Steps' => 'Tối Ưu PageSpeed Nhanh - 3 Bước',
            'Follow these steps to boost your PageSpeed Insights score and improve Core Web Vitals:' => 'Thực hiện các bước này để tăng điểm PageSpeed Insights và cải thiện Core Web Vitals:',
            'Scan for Optimization Opportunities' => 'Quét Tìm Cơ Hội Tối Ưu',
            'Automatically finds all JPEG/PNG images that can be optimized to improve your PageSpeed score.' => 'Tự động tìm tất cả hình ảnh JPEG/PNG có thể được tối ưu hóa để cải thiện điểm PageSpeed của bạn.',
            'Convert to Modern Formats' => 'Chuyển Đổi Sang Định Dạng Hiện Đại',
            'Converts images to WebP/AVIF formats for faster loading, smaller file sizes, and better LCP scores.' => 'Chuyển đổi hình ảnh sang định dạng WebP/AVIF để tải nhanh hơn, kích thước file nhỏ hơn và điểm LCP tốt hơn.',
            'Automatic Performance Setup' => 'Thiết Lập Hiệu Suất Tự Động',
            'Automatically handles:' => 'Tự động xử lý:',
            'Adds .htaccess rules if needed' => 'Thêm quy tắc .htaccess nếu cần',
            'Shows LiteSpeed Cache settings' => 'Hiển thị cài đặt LiteSpeed Cache',
            'Recommends turning off conflicting optimizations' => 'Khuyến nghị tắt các tối ưu hóa xung đột',
            
            // Main action buttons
            'Start Complete Optimization' => 'Bắt Đầu Tối Ưu Hoàn Chỉnh',
            'This will scan images, convert to WebP/AVIF formats, add .htaccess rules (if needed), and show LiteSpeed Cache recommendations. Original images are preserved.' => 'Điều này sẽ quét hình ảnh, chuyển đổi sang định dạng WebP/AVIF, thêm quy tắc .htaccess (nếu cần) và hiển thị khuyến nghị LiteSpeed Cache. Hình ảnh gốc được bảo tồn.',
            'Truly One-Click:' => 'Thực Sự Một Cú Nhấp:',
            'No manual configuration needed - perfect for beginners!' => 'Không cần cấu hình thủ công - hoàn hảo cho người mới bắt đầu!',
            
            // Dashboard sections
            'Optimization Status Dashboard' => 'Bảng Điều Khiển Trạng Thái Tối Ưu',
            'View your current optimization progress and statistics' => 'Xem tiến trình tối ưu hóa hiện tại và thống kê của bạn',
            'Optimization Status' => 'Trạng Thái Tối Ưu',
            'Total Images' => 'Tổng Số Hình Ảnh',
            'Optimized' => 'Đã Tối Ưu',
            'Pending' => 'Đang Chờ',
            'Space Saved' => 'Dung Lượng Tiết Kiệm',
            'Storage Optimization' => 'Tối Ưu Lưu Trữ',
            'Last optimized:' => 'Lần tối ưu cuối:',
            'Format:' => 'Định dạng:',
            'Original images:' => 'Hình ảnh gốc:',
            'Thumbnail images:' => 'Hình ảnh thu nhỏ:',
            'Enabled' => 'Đã Bật',
            'Disabled' => 'Đã Tắt',
            'Image Thumbnail Sizes' => 'Kích Thước Hình Ảnh Thu Nhỏ',
            'Original' => 'Gốc',
            'Ready' => 'Sẵn Sàng',
            'No image sizes selected for optimization. Please enable at least one option in settings.' => 'Không có kích thước hình ảnh nào được chọn để tối ưu hóa. Vui lòng bật ít nhất một tùy chọn trong cài đặt.',
            
            // Settings section
            'Optimization Settings' => 'Cài Đặt Tối Ưu',
            'Configure which images to optimize and quality settings' => 'Cấu hình hình ảnh nào để tối ưu hóa và cài đặt chất lượng',
            'Image Size Options' => 'Tùy Chọn Kích Thước Hình Ảnh',
            'Choose which image sizes to convert. Original images are your uploaded files, thumbnails are automatically generated smaller versions.' => 'Chọn kích thước hình ảnh nào để chuyển đổi. Hình ảnh gốc là các file bạn tải lên, thu nhỏ là các phiên bản nhỏ hơn được tạo tự động.',
            'Optimize Original Images' => 'Tối Ưu Hình Ảnh Gốc',
            'Convert the original uploaded image files' => 'Chuyển đổi các file hình ảnh gốc đã tải lên',
            'Optimize Thumbnail Images' => 'Tối Ưu Hình Ảnh Thu Nhỏ',
            'Convert automatically generated thumbnail versions' => 'Chuyển đổi các phiên bản thu nhỏ được tạo tự động',
            
            // Advanced settings
            'Quality Settings' => 'Cài Đặt Chất Lượng',
            'Conversion Quality' => 'Chất Lượng Chuyển Đổi',
            'Higher quality means larger file sizes but better image appearance' => 'Chất lượng cao hơn có nghĩa là kích thước file lớn hơn nhưng hình ảnh đẹp hơn',
            'Minimum Size Filters' => 'Bộ Lọc Kích Thước Tối Thiểu',
            'Skip converting images smaller than these dimensions to avoid unnecessary processing' => 'Bỏ qua chuyển đổi hình ảnh nhỏ hơn các kích thước này để tránh xử lý không cần thiết',
            'Minimum Width (pixels)' => 'Chiều Rộng Tối Thiểu (pixel)',
            'Minimum Height (pixels)' => 'Chiều Cao Tối Thiểu (pixel)',
            'Minimum File Size (KB)' => 'Kích Thước File Tối Thiểu (KB)',
            'Force include small images' => 'Bắt buộc bao gồm hình ảnh nhỏ',
            'Ignore thresholds when scanning/converting' => 'Bỏ qua ngưỡng khi quét/chuyển đổi',
            
            // Output Format Selection - Missing from screenshot
            'Output Format Selection' => 'Lựa Chọn Định Dạng Đầu Ra',
            'Choose which next-generation image formats to convert your images to. The selection depends on your server capabilities and browser support.' => 'Chọn định dạng hình ảnh thế hệ mới nào để chuyển đổi hình ảnh của bạn. Lựa chọn phụ thuộc vào khả năng máy chủ và hỗ trợ trình duyệt.',
            'Server Conversion Capabilities' => 'Khả Năng Chuyển Đổi Máy Chủ',
            'ImageMagick Extension' => 'Tiện Ích ImageMagick',
            'GD Extension' => 'Tiện Ích GD',
            'Available' => 'Có Sẵn',
            'Not Available' => 'Không Có Sẵn',
            'WebP Support' => 'Hỗ Trợ WebP',
            'AVIF Support' => 'Hỗ Trợ AVIF',
            'AVIF Support (Not Available in GD)' => 'Hỗ Trợ AVIF (Không Có Sẵn trong GD)',
            'No Image Conversion Library Available!' => 'Không Có Thư Viện Chuyển Đổi Hình Ảnh Nào!',
            'Neither ImageMagick nor GD extension is available on your server. Please contact your hosting provider to install one of these extensions.' => 'Cả ImageMagick và tiện ích GD đều không có sẵn trên máy chủ của bạn. Vui lòng liên hệ nhà cung cấp hosting để cài đặt một trong những tiện ích này.',
            
            // Format options from screenshot
            'Output Format' => 'Định Dạng Đầu Ra',
            'Choose which modern image format(s) to convert your images to. AVIF provides better compression but requires ImageMagick with AVIF support.' => 'Chọn định dạng hình ảnh hiện đại nào để chuyển đổi hình ảnh của bạn. AVIF cung cấp nén tốt hơn nhưng yêu cầu ImageMagick có hỗ trợ AVIF.',
            'WebP Only' => 'Chỉ WebP',
            'Convert images to WebP format only. Best compatibility, supported by most modern browsers and servers.' => 'Chuyển đổi hình ảnh chỉ sang định dạng WebP. Tương thích tốt nhất, được hỗ trợ bởi hầu hết trình duyệt và máy chủ hiện đại.',
            'AVIF Only' => 'Chỉ AVIF',
            'Convert images to AVIF format only. Better compression than WebP but requires ImageMagick with AVIF support.' => 'Chuyển đổi hình ảnh chỉ sang định dạng AVIF. Nén tốt hơn WebP nhưng yêu cầu ImageMagick có hỗ trợ AVIF.',
            'Requires ImageMagick with AVIF support. Contact your hosting provider.' => 'Yêu cầu ImageMagick có hỗ trợ AVIF. Liên hệ nhà cung cấp hosting của bạn.',
            'Both WebP and AVIF' => 'Cả WebP và AVIF',
            'Fully Available' => 'Hoàn Toàn Có Sẵn',
            'WebP Only (AVIF not available)' => 'Chỉ WebP (AVIF không có sẵn)',
            'Convert to both formats for maximum compatibility and performance. Browsers will automatically choose the best supported format.' => 'Chuyển đổi sang cả hai định dạng để có tương thích và hiệu suất tối đa. Trình duyệt sẽ tự động chọn định dạng được hỗ trợ tốt nhất.',
            'Note: Only WebP will be generated since AVIF is not available on your server.' => 'Lưu ý: Chỉ WebP sẽ được tạo vì AVIF không có sẵn trên máy chủ của bạn.',
            
            // WebP/AVIF Serving Options - Missing from screenshot  
            'WebP/AVIF Serving Options' => 'Tùy Chọn Phục Vụ WebP/AVIF',
            'LiteSpeed Cache Detected:' => 'Đã Phát Hiện LiteSpeed Cache:',
            'You have multiple options to serve WebP/AVIF images. Choose the method that works best for your setup:' => 'Bạn có nhiều tùy chọn để phục vụ hình ảnh WebP/AVIF. Chọn phương thức phù hợp nhất với thiết lập của bạn:',
            'Option 1 (Recommended):' => 'Tùy Chọn 1 (Khuyến Nghị):',
            'Use LiteSpeed Cache WebP replacement feature' => 'Sử dụng tính năng thay thế WebP của LiteSpeed Cache',
            'Option 2:' => 'Tùy Chọn 2:',
            'Use .htaccess rules below for direct server-level serving' => 'Sử dụng quy tắc .htaccess bên dưới để phục vụ trực tiếp ở cấp máy chủ',
            'Option 3:' => 'Tùy Chọn 3:',
            'Use URL replacement below for PHP-level serving' => 'Sử dụng thay thế URL bên dưới để phục vụ ở cấp PHP',
            'No LiteSpeed Cache Detected:' => 'Không Phát Hiện LiteSpeed Cache:',
            'Use the options below to serve WebP/AVIF images to your visitors:' => 'Sử dụng các tùy chọn bên dưới để phục vụ hình ảnh WebP/AVIF cho khách truy cập của bạn:',
            
            // URL Replacement Method - Missing from screenshot
            'URL Replacement Method' => 'Phương Thức Thay Thế URL',
            'Enable URL Replacement' => 'Bật Thay Thế URL',
            'Replace image URLs with WebP/AVIF versions in your site\'s HTML' => 'Thay thế URL hình ảnh bằng phiên bản WebP/AVIF trong HTML của trang web',
            'This option replaces image URLs in HTML output with WebP/AVIF versions when available. For better performance, consider using .htaccess rules below.' => 'Tùy chọn này thay thế URL hình ảnh trong đầu ra HTML bằng phiên bản WebP/AVIF khi có sẵn. Để có hiệu suất tốt hơn, hãy xem xét sử dụng quy tắc .htaccess bên dưới.',
            'Note: .htaccess rules below provide better performance by serving WebP content while keeping original URLs and proper Content-Type headers.' => 'Lưu ý: Quy tắc .htaccess bên dưới cung cấp hiệu suất tốt hơn bằng cách phục vụ nội dung WebP trong khi giữ URL gốc và tiêu đề Content-Type phù hợp.',
            
            // .htaccess Rules Method - Missing from screenshot
            '.htaccess Rules Method' => 'Phương Thức Quy Tắc .htaccess',
            'Add .htaccess Rules' => 'Thêm Quy Tắc .htaccess',
            'Automatically detect and serve WebP/AVIF to supported browsers' => 'Tự động phát hiện và phục vụ WebP/AVIF cho trình duyệt được hỗ trợ',
            'This adds rules to your .htaccess file to automatically serve WebP/AVIF versions when available and supported by the visitor\'s browser.' => 'Điều này thêm quy tắc vào tập tin .htaccess của bạn để tự động phục vụ phiên bản WebP/AVIF khi có sẵn và được hỗ trợ bởi trình duyệt của khách truy cập.',
            'This method provides the best performance and is recommended for most users.' => 'Phương thức này cung cấp hiệu suất tốt nhất và được khuyến nghị cho hầu hết người dùng.',
            'Warning: Make sure to backup your .htaccess file before enabling this option.' => 'Cảnh báo: Đảm bảo sao lưu tập tin .htaccess của bạn trước khi bật tùy chọn này.',
            
            // WebP serving methods
            'WebP Serving Method' => 'Phương Thức Phục Vụ WebP',
            'Choose how WebP images are served to visitors' => 'Chọn cách phục vụ hình ảnh WebP cho khách truy cập',
            'URL Replacement' => 'Thay Thế URL',
            'Automatically replace image URLs with WebP versions when available' => 'Tự động thay thế URL hình ảnh bằng phiên bản WebP khi có sẵn',
            '.htaccess Rules' => 'Quy Tắc .htaccess',
            'Add server rules to automatically serve WebP to compatible browsers' => 'Thêm quy tắc máy chủ để tự động phục vụ WebP cho trình duyệt tương thích',
            'LiteSpeed Cache Integration' => 'Tích Hợp LiteSpeed Cache',
            'Use LiteSpeed Cache WebP replacement feature (recommended if available)' => 'Sử dụng tính năng thay thế WebP của LiteSpeed Cache (khuyên dùng nếu có)',
            
            // Optimization actions
            'Start Image Scan' => 'Bắt Đầu Quét Hình Ảnh',
            'Scan your media library to find images that can be optimized' => 'Quét thư viện media để tìm hình ảnh có thể được tối ưu hóa',
            'Convert Selected Images' => 'Chuyển Đổi Hình Ảnh Đã Chọn',
            'Convert scanned images to WebP/AVIF format' => 'Chuyển đổi hình ảnh đã quét sang định dạng WebP/AVIF',
            'Apply Server Configuration' => 'Áp Dụng Cấu Hình Máy Chủ',
            'Add .htaccess rules and configure serving methods' => 'Thêm quy tắc .htaccess và cấu hình phương thức phục vụ',
            
            // Status messages
            'Scanning in progress...' => 'Đang quét...',
            'Converting images...' => 'Đang chuyển đổi hình ảnh...',
            'Applying configuration...' => 'Đang áp dụng cấu hình...',
            'Optimization complete!' => 'Tối ưu hóa hoàn tất!',
            'No images found for optimization' => 'Không tìm thấy hình ảnh nào để tối ưu hóa',
            'Error during optimization' => 'Lỗi trong quá trình tối ưu hóa',
            
            // Format support messages
            'Format Support Detection' => 'Phát Hiện Hỗ Trợ Định Dạng',
            'WebP Support' => 'Hỗ Trợ WebP',
            'AVIF Support' => 'Hỗ Trợ AVIF',
            'Your server supports WebP conversion' => 'Máy chủ của bạn hỗ trợ chuyển đổi WebP',
            'Your server supports AVIF conversion' => 'Máy chủ của bạn hỗ trợ chuyển đổi AVIF',
            'WebP conversion not available on this server' => 'Chuyển đổi WebP không khả dụng trên máy chủ này',
            'AVIF conversion not available on this server' => 'Chuyển đổi AVIF không khả dụng trên máy chủ này',
            
            // Error messages
            'Server does not support %s format conversion.' => 'Máy chủ không hỗ trợ chuyển đổi định dạng %s.',
            'Neither Imagick nor GD format support is available on this server.' => 'Không có hỗ trợ định dạng Imagick hoặc GD trên máy chủ này.',
            'Image Optimization requires WordPress 5.0+ and PHP 7.4+' => 'Tối Ưu Hình Ảnh yêu cầu WordPress 5.0+ và PHP 7.4+',
            'Plugin Activation Error' => 'Lỗi Kích Hoạt Plugin',
            
            // Manual Control Center - Missing from screenshot
            'Manual Control Center' => 'Trung Tâm Điều Khiển Thủ Công',
            'Advanced manual controls for power users. Use these tools for detailed control over the optimization process.' => 'Các điều khiển thủ công nâng cao dành cho người dùng chuyên nghiệp. Sử dụng các công cụ này để kiểm soát chi tiết quá trình tối ưu hóa.',
            
            // Step-by-Step Process
            'Step-by-Step Process:' => 'Quy Trình Từng Bước:',
            'Scan' => 'Quét',
            'Scan Images' => 'Quét Hình Ảnh',
            'Convert' => 'Chuyển Đổi',
            'Convert Pending' => 'Chuyển Đổi Đang Chờ',
            'Manage' => 'Quản Lý',
            'Remove All WebP' => 'Xóa Tất Cả WebP',
            
            // Export Reports
            'Export Reports:' => 'Xuất Báo Cáo:',
            'Export JSON' => 'Xuất JSON',
            'Export CSV' => 'Xuất CSV',
            
            // Statistics labels
            'Total JPG/PNG:' => 'Tổng JPG/PNG:',
            'Already converted:' => 'Đã chuyển đổi:',
            'Pending:' => 'Đang chờ:',
            'Ignored:' => 'Bỏ qua:',
            
            // Quick Help Section - Missing from screenshot
            'Quick Help' => 'Trợ Giúp Nhanh',
            'New User?' => 'Người Dùng Mới?',
            'Just click the big "Start Complete Optimization" button above. It will handle everything automatically - scan, convert, add .htaccess rules, and show LiteSpeed Cache recommendations!' => 'Chỉ cần nhấp vào nút lớn "Bắt Đầu Tối Ưu Hoàn Chỉnh" ở trên. Nó sẽ xử lý mọi thứ tự động - quét, chuyển đổi, thêm quy tắc .htaccess và hiển thị khuyến nghị LiteSpeed Cache!',
            'What are WebP/AVIF?' => 'WebP/AVIF là gì?',
            'WebP and AVIF are modern image formats that reduce file sizes by 25-50% without losing quality. Faster loading = better SEO!' => 'WebP và AVIF là các định dạng hình ảnh hiện đại giảm kích thước file 25-50% mà không mất chất lượng. Tải nhanh hơn = SEO tốt hơn!',
            'Need Settings?' => 'Cần Cài Đặt?',
            'Default settings work great for most sites. Only change if you know what you\'re doing.' => 'Cài đặt mặc định hoạt động tốt cho hầu hết các trang web. Chỉ thay đổi nếu bạn biết mình đang làm gì.',
            'Is it Safe?' => 'Có An Toàn Không?',
            'Yes! Your original images are never deleted. WebP/AVIF copies are created alongside them.' => 'Có! Hình ảnh gốc của bạn không bao giờ bị xóa. Các bản sao WebP/AVIF được tạo cùng với chúng.',
            
            // Image Information Widget
            'Image Information' => 'Thông Tin Hình Ảnh',
            'Total Images:' => 'Tổng Số Hình Ảnh:',
            'Optimized:' => 'Đã Tối Ưu:',
            'Pending:' => 'Đang Chờ:',
            
            // Optimization Summary
            'Optimization Summary' => 'Tóm Tắt Tối Ưu Hóa',
            'images optimized' => 'hình ảnh đã tối ưu hóa',
            'Space saved:' => 'Dung lượng tiết kiệm:',
            'MB' => 'MB',
            
            // .htaccess section buttons
            'Remove .htaccess Rules' => 'Xóa Quy Tắc .htaccess',
            'Preview Rules' => 'Xem Trước Quy Tắc',
            
            // Admin interface - Fixed Save Settings
            'Save Settings' => 'Lưu Cài Đặt',
            'Reset to Defaults' => 'Đặt Lại Mặc Định',
            'Export Report' => 'Xuất Báo Cáo',
            'Clear All Optimized Images' => 'Xóa Tất Cả Hình Ảnh Đã Tối Ưu',
            'Settings saved successfully' => 'Cài đặt đã được lưu thành công',
            'Settings reset to default values' => 'Cài đặt đã được đặt lại về giá trị mặc định',
            
            // Additional missing strings from the interface
            'conversion quality' => 'chất lượng chuyển đổi',
            'Recommended: 80. Higher values = better quality but larger files. Lower values = smaller files but reduced quality.' => 'Khuyến nghị: 80. Giá trị cao hơn = chất lượng tốt hơn nhưng file lớn hơn. Giá trị thấp hơn = file nhỏ hơn nhưng chất lượng giảm.',
            '0–100, default 80' => '0–100, mặc định 80',
            'Min width (px)' => 'Chiều rộng tối thiểu (px)',
            'Min height (px)' => 'Chiều cao tối thiểu (px)',
            'Min file size (KB)' => 'Kích thước file tối thiểu (KB)',
        );
        
        // Add filter to override translations immediately
        add_filter( 'gettext', function( $translation, $text, $domain ) use ( $vietnamese_texts ) {
            if ( $domain === 'improve-image-delivery-pagespeed' && isset( $vietnamese_texts[$text] ) ) {
                return $vietnamese_texts[$text];
            }
            return $translation;
        }, 999, 3 ); // High priority
        
        add_filter( 'gettext_with_context', function( $translation, $text, $context, $domain ) use ( $vietnamese_texts ) {
            if ( $domain === 'improve-image-delivery-pagespeed' && isset( $vietnamese_texts[$text] ) ) {
                return $vietnamese_texts[$text];
            }
            return $translation;
        }, 999, 4 ); // High priority
    }

    /**
     * Load plugin textdomain for translations
     *
     * @since 1.0.0
     */
    public function load_textdomain() {
        // Get the preferred language for this plugin
        $user_preference = get_user_meta( get_current_user_id(), 'image_optimization_language', true );
        $site_preference = get_option( 'image_optimization_language', '' );
        $preferred_locale = ! empty( $user_preference ) ? $user_preference : $site_preference;
        
        // If no preference, default to Vietnamese for this plugin
        if ( empty( $preferred_locale ) ) {
            $preferred_locale = 'vi_VN';
        }
        
        // Load Vietnamese fallbacks immediately if Vietnamese is preferred
        if ( $preferred_locale === 'vi_VN' ) {
            $this->load_vietnamese_fallbacks();
        }
        
        // Try to load the text domain
        $loaded = load_plugin_textdomain(
            'improve-image-delivery-pagespeed',
            false,
            dirname( IMAGE_OPTIMIZATION_BASENAME ) . '/languages'
        );
        
        // If loading failed and we want Vietnamese, ensure fallbacks are loaded
        if ( ! $loaded && $preferred_locale === 'vi_VN' ) {
            $this->load_vietnamese_fallbacks();
        }
    }

    /**
     * Plugin activation
     *
     * @since 1.0.0
     */
    public function activate() {
        // Check minimum requirements
        if ( ! $this->check_requirements() ) {
            deactivate_plugins( IMAGE_OPTIMIZATION_BASENAME );
            wp_die(
                esc_html__( 'Image Optimization requires WordPress 5.0+ and PHP 7.4+', 'improve-image-delivery-pagespeed' ),
                esc_html__( 'Plugin Activation Error', 'improve-image-delivery-pagespeed' ),
                array( 'back_link' => true )
            );
        }

        // Set default options
        $default_options = array(
            'quality'                => 80,
            'min_w'                  => 150,
            'min_h'                  => 150,
            'min_kb'                 => 10,
            'force_all'              => 0,
            'auto_request_cron'      => 0,
            'optimize_original'      => 1,
            'optimize_thumbnails'    => 1,
            'remove_original_backups'=> 0,
            'optimize_losslessly'    => 0,
            'preserve_exif'          => 0,
            'enable_fallback_replacement' => 0,
        );

        add_option( 'image_optimization_settings', $default_options );
        add_option( 'image_optimization_analytics', array(
            'total_images' => 0,
            'optimized_images' => 0,
            'pending_images' => 0,
            'space_saved' => 0,
            'last_optimized' => 'Never'
        ));

        // Add version option
        add_option( 'image_optimization_version', IMAGE_OPTIMIZATION_VERSION );

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     *
     * @since 1.0.0
     */
    public function deactivate() {
        // Clean up transients
        delete_transient( 'image_optimization_pending_ids' );
        delete_transient( 'image_optimization_last_scan_summary' );
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Check minimum requirements
     *
     * @since 1.0.0
     * @return bool
     */
    private function check_requirements() {
        global $wp_version;
        
        return (
            version_compare( $wp_version, '5.0', '>=' ) &&
            version_compare( PHP_VERSION, '7.4', '>=' )
        );
    }
    
    /**
     * Check for plugin updates and store update information
     *
     * @since 1.0.8
     */
    public function check_plugin_updates() {
        // Only check on plugin admin pages
        if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
            return;
        }
        
        $current_screen = get_current_screen();
        if ( ! $current_screen || strpos( $current_screen->id, 'image-optimization' ) === false ) {
            return;
        }
        
        // Check for updates once per day
        $last_check = get_transient( 'image_optimization_update_check' );
        if ( $last_check ) {
            return;
        }
        
        // Set transient for 24 hours
        set_transient( 'image_optimization_update_check', time(), DAY_IN_SECONDS );
        
        // Simulate update check - in real implementation, check against GitHub API
        $current_version = IMAGE_OPTIMIZATION_VERSION;
        $latest_version = $this->get_latest_version_from_github();
        
        if ( version_compare( $latest_version, $current_version, '>' ) ) {
            set_transient( 'image_optimization_update_available', array(
                'current_version' => $current_version,
                'latest_version' => $latest_version,
                'download_url' => 'https://github.com/hienhoceo-dpsmedia/wordpress-image-optimization-plugin/releases/latest'
            ), WEEK_IN_SECONDS );
        }
    }
    
    /**
     * Get latest version from GitHub releases
     *
     * @since 1.0.8
     * @return string
     */
    private function get_latest_version_from_github() {
        $api_url = 'https://api.github.com/repos/hienhoceo-dpsmedia/wordpress-image-optimization-plugin/releases/latest';
        
        $response = wp_remote_get( $api_url, array(
            'timeout' => 10,
            'headers' => array(
                'User-Agent' => 'WordPress-Image-Optimization-Plugin/' . IMAGE_OPTIMIZATION_VERSION
            )
        ) );
        
        if ( is_wp_error( $response ) ) {
            return IMAGE_OPTIMIZATION_VERSION; // Return current version if API fails
        }
        
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );
        
        if ( isset( $data['tag_name'] ) ) {
            return ltrim( $data['tag_name'], 'v' ); // Remove 'v' prefix from tag
        }
        
        return IMAGE_OPTIMIZATION_VERSION;
    }
    
    /**
     * Show update notice to administrators
     *
     * @since 1.0.8
     */
    public function show_update_notice() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        
        $update_info = get_transient( 'image_optimization_update_available' );
        if ( ! $update_info ) {
            return;
        }
        
        // Only show on plugin pages
        $current_screen = get_current_screen();
        if ( ! $current_screen || strpos( $current_screen->id, 'image-optimization' ) === false ) {
            return;
        }
        
        $current_version = $update_info['current_version'];
        $latest_version = $update_info['latest_version'];
        $download_url = $update_info['download_url'];
        
        $is_vietnamese = ( get_user_meta( get_current_user_id(), 'image_optimization_language', true ) === 'vi_VN' ||
                          get_option( 'image_optimization_language', '' ) === 'vi_VN' ||
                          in_array( get_locale(), array( 'vi_VN', 'vi', '' ), true ) );
        
        if ( $is_vietnamese ) {
            $title = '🎉 Cập Nhật Mới Có Sẵn!';
            $message = sprintf(
                'Phiên bản mới của plugin Tối Ưu Hình Ảnh PageSpeed đã có sẵn! Phiên bản hiện tại: <strong>%s</strong> → Phiên bản mới: <strong>%s</strong>',
                $current_version,
                $latest_version
            );
            $download_text = 'Tải Về Phiên Bản Mới';
            $dismiss_text = 'Ẩn Thông Báo';
        } else {
            $title = '🎉 Update Available!';
            $message = sprintf(
                'A new version of Image Optimization PageSpeed plugin is available! Current version: <strong>%s</strong> → New version: <strong>%s</strong>',
                $current_version,
                $latest_version
            );
            $download_text = 'Download New Version';
            $dismiss_text = 'Dismiss Notice';
        }
        
        echo '<div class="notice notice-info is-dismissible" id="image-optimization-update-notice">';
        echo '<h3>' . esc_html( $title ) . '</h3>';
        echo '<p>' . wp_kses_post( $message ) . '</p>';
        echo '<p>';
        echo '<a href="' . esc_url( $download_url ) . '" class="button button-primary" target="_blank">' . esc_html( $download_text ) . '</a> ';
        echo '<a href="#" class="button" onclick="imageOptimizationDismissUpdate(); return false;">' . esc_html( $dismiss_text ) . '</a>';
        echo '</p>';
        echo '</div>';
        
        // Add JavaScript for dismiss functionality
        echo '<script>
        function imageOptimizationDismissUpdate() {
            fetch(ajaxurl, {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "action=image_optimization_dismiss_update&nonce=' . wp_create_nonce( 'image_optimization_dismiss_update' ) . '"
            }).then(() => {
                document.getElementById("image-optimization-update-notice").style.display = "none";
            });
        }
        </script>';
    }
    
    /**
     * Dismiss update notice
     *
     * @since 1.0.8
     */
    public function dismiss_update_notice() {
        if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $_POST['nonce'], 'image_optimization_dismiss_update' ) ) {
            wp_die( 'Unauthorized' );
        }
        
        delete_transient( 'image_optimization_update_available' );
        wp_die(); // Proper AJAX response
    }
}

// Initialize the plugin
Image_Optimization::get_instance();