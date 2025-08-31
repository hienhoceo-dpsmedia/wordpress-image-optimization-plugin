<?php
/**
 * Settings management class
 *
 * @package ImageOptimization
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Settings management class
 *
 * @since 1.0.0
 */
class Image_Optimization_Settings {

    /**
     * Instance
     *
     * @since 1.0.0
     * @var Image_Optimization_Settings|null
     */
    private static $instance = null;

    /**
     * Settings option name
     *
     * @since 1.0.0
     * @var string
     */
    private $option_name = 'image_optimization_settings';

    /**
     * Analytics option name
     *
     * @since 1.0.0
     * @var string
     */
    private $analytics_option = 'image_optimization_analytics';

    /**
     * Get instance
     *
     * @since 1.0.0
     * @return Image_Optimization_Settings
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
        // Constructor can be empty for now
    }

    /**
     * Get default settings
     *
     * @since 1.0.0
     * @return array
     */
    public function get_defaults() {
        return array(
            'quality'                => 80,   // 0..100
            'min_w'                  => 150,  // px
            'min_h'                  => 150,  // px
            'min_kb'                 => 10,   // KB
            'force_all'              => 0,    // ignore thresholds
            'auto_request_cron'      => 0,    // OFF by default
            'optimize_original'      => 1,    // ON by default
            'optimize_thumbnails'    => 1,    // ON by default - NEW OPTION
            'remove_original_backups'=> 0,    // OFF by default
            'optimize_losslessly'    => 0,    // OFF by default
            'preserve_exif'          => 0,    // OFF by default
            'enable_fallback_replacement' => 0, // Enable fallback URL replacement if not using LiteSpeed
            'output_format'          => 'webp', // Output format: webp, avif, or both
        );
    }

    /**
     * Get settings
     *
     * @since 1.0.0
     * @return array
     */
    public function get_settings() {
        $options = get_option( $this->option_name, array() );
        return wp_parse_args( $options, $this->get_defaults() );
    }

    /**
     * Update settings
     *
     * @since 1.0.0
     * @param array $settings Settings array.
     * @return bool
     */
    public function update_settings( $settings ) {
        $sanitized = $this->sanitize_settings( $settings );
        return update_option( $this->option_name, $sanitized );
    }

    /**
     * Sanitize settings
     *
     * @since 1.0.0
     * @param array $settings Raw settings.
     * @return array
     */
    public function sanitize_settings( $settings ) {
        $defaults = $this->get_defaults();
        $sanitized = array();

        // Sanitize numeric values
        $sanitized['quality'] = isset( $settings['quality'] ) 
            ? max( 0, min( 100, (int) $settings['quality'] ) ) 
            : $defaults['quality'];

        $sanitized['min_w'] = isset( $settings['min_w'] ) 
            ? max( 0, (int) $settings['min_w'] ) 
            : $defaults['min_w'];

        $sanitized['min_h'] = isset( $settings['min_h'] ) 
            ? max( 0, (int) $settings['min_h'] ) 
            : $defaults['min_h'];

        $sanitized['min_kb'] = isset( $settings['min_kb'] ) 
            ? max( 0, (int) $settings['min_kb'] ) 
            : $defaults['min_kb'];

        // Sanitize boolean values
        $boolean_fields = array(
            'force_all',
            'auto_request_cron',
            'optimize_original',
            'optimize_thumbnails',
            'remove_original_backups',
            'optimize_losslessly',
            'preserve_exif',
            'enable_fallback_replacement',
        );

        foreach ( $boolean_fields as $field ) {
            $sanitized[ $field ] = ! empty( $settings[ $field ] ) ? 1 : 0;
        }

        // Sanitize output format
        $valid_formats = array( 'webp', 'avif', 'both' );
        $sanitized['output_format'] = isset( $settings['output_format'] ) && in_array( $settings['output_format'], $valid_formats, true )
            ? $settings['output_format']
            : $defaults['output_format'];

        return $sanitized;
    }

    /**
     * Get analytics
     *
     * @since 1.0.0
     * @return array
     */
    public function get_analytics() {
        return get_option( $this->analytics_option, array(
            'total_images' => 0,
            'optimized_images' => 0,
            'pending_images' => 0,
            'space_saved' => 0,
            'last_optimized' => 'Never',
        ) );
    }

    /**
     * Update analytics
     *
     * @since 1.0.0
     * @param array $analytics Analytics data.
     * @return bool
     */
    public function update_analytics( $analytics ) {
        return update_option( $this->analytics_option, $analytics );
    }

    /**
     * Check user capability
     *
     * @since 1.0.0
     * @return bool
     */
    public function user_can_manage() {
        return current_user_can( 'manage_options' );
    }

    /**
     * Get nonce action
     *
     * @since 1.0.0
     * @param string $action Action name.
     * @return string
     */
    public function get_nonce_action( $action ) {
        return 'image_optimization_' . $action . '_nonce';
    }

    /**
     * Verify nonce
     *
     * @since 1.0.0
     * @param string $action Action name.
     * @param string $nonce  Nonce value.
     * @return bool
     */
    public function verify_nonce( $action, $nonce = null ) {
        if ( null === $nonce && isset( $_POST['_wpnonce'] ) ) {
            $nonce = sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) );
        }
        
        return wp_verify_nonce( $nonce, $this->get_nonce_action( $action ) );
    }
}