<?php
/**
 * Plugin Name: Improve Image Delivery PageSpeed
 * Plugin URI: https://dps.media/plugins/improve-image-delivery-pagespeed/
 * Description: Boost your PageSpeed Insights score and improve Core Web Vitals (LCP) by automatically converting JPEG/PNG images to modern WebP/AVIF formats. Reduces image download time and improves perceived page load performance.
 * Version: 1.0.5
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
define( 'IMAGE_OPTIMIZATION_VERSION', '1.0.5' );
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
            
            // Language support messages
            'Language Support' => 'Hỗ Trợ Ngôn Ngữ',
            'This plugin prioritizes Vietnamese language with English fallback.' => 'Plugin này ưu tiên tiếng Việt với tiếng Anh làm dự phòng.',
            'Note: This plugin defaults to Vietnamese. To permanently change language, update your WordPress admin language in Settings → General.' => 'Lưu ý: Plugin này mặc định là tiếng Việt. Để thay đổi ngôn ngữ vĩnh viễn, cập nhật ngôn ngữ quản trị WordPress trong Cài đặt → Chung.',
            
            // Error messages
            'Server does not support %s format conversion.' => 'Máy chủ không hỗ trợ chuyển đổi định dạng %s.',
            'Neither Imagick nor GD format support is available on this server.' => 'Không có hỗ trợ định dạng Imagick hoặc GD trên máy chủ này.',
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
}

// Initialize the plugin
Image_Optimization::get_instance();