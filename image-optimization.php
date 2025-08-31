<?php
/**
 * Plugin Name: Improve Image Delivery PageSpeed
 * Plugin URI: https://dps.media/plugins/improve-image-delivery-pagespeed/
 * Description: Boost your PageSpeed Insights score and improve Core Web Vitals (LCP) by automatically converting JPEG/PNG images to modern WebP/AVIF formats. Reduces image download time and improves perceived page load performance.
 * Version: 1.0.0
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
define( 'IMAGE_OPTIMIZATION_VERSION', '1.0.0' );
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
     * Set plugin locale to Vietnamese by default
     *
     * @since 1.0.0
     * @param string $locale The locale.
     * @param string $domain The text domain.
     * @return string
     */
    public function set_plugin_locale( $locale, $domain ) {
        if ( 'improve-image-delivery-pagespeed' === $domain ) {
            // Default to Vietnamese if current locale is English or empty
            if ( in_array( $locale, array( 'en_US', 'en', '' ), true ) ) {
                return 'vi_VN';
            }
        }
        return $locale;
    }

    /**
     * Load plugin textdomain for translations
     *
     * @since 1.0.0
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'improve-image-delivery-pagespeed',
            false,
            dirname( IMAGE_OPTIMIZATION_BASENAME ) . '/languages'
        );
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