<?php
/**
 * Admin functionality class
 *
 * @package ImageOptimization
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Admin functionality class
 *
 * @since 1.0.0
 */
class Image_Optimization_Admin {

    /**
     * Instance
     *
     * @since 1.0.0
     * @var Image_Optimization_Admin|null
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @since 1.0.0
     * @return Image_Optimization_Admin
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
        
        // Load Vietnamese translations immediately for admin area
        add_action( 'admin_init', array( $this, 'ensure_vietnamese_language' ) );
    }

    /**
     * Initialize hooks
     *
     * @since 1.0.0
     */
    private function init_hooks() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        add_action( 'admin_notices', array( $this, 'admin_notices' ) );
        
        // AJAX handlers
        add_action( 'wp_ajax_image_optimization_scan', array( $this, 'ajax_scan' ) );
        add_action( 'wp_ajax_image_optimization_convert_batch', array( $this, 'ajax_convert_batch' ) );
        add_action( 'wp_ajax_image_optimization_revert_all', array( $this, 'ajax_revert_all' ) );
        add_action( 'wp_ajax_image_optimization_add_htaccess', array( $this, 'ajax_add_htaccess' ) );
        add_action( 'wp_ajax_image_optimization_remove_htaccess', array( $this, 'ajax_remove_htaccess' ) );
        add_action( 'wp_ajax_image_optimization_change_language', array( $this, 'ajax_change_language' ) );
        
        // Admin post handlers
        add_action( 'admin_post_image_optimization_save_settings', array( $this, 'save_settings' ) );
        add_action( 'admin_post_image_optimization_export_json', array( $this, 'export_json' ) );
        add_action( 'admin_post_image_optimization_export_csv', array( $this, 'export_csv' ) );
        
        // Media library integration
        add_filter( 'bulk_actions-upload', array( $this, 'add_bulk_actions' ) );
        add_filter( 'handle_bulk_actions-upload', array( $this, 'handle_bulk_actions' ), 10, 3 );
        add_filter( 'manage_media_columns', array( $this, 'add_media_columns' ) );
        add_action( 'manage_media_custom_column', array( $this, 'display_media_columns' ), 10, 2 );
        add_filter( 'media_row_actions', array( $this, 'add_media_row_actions' ), 10, 2 );
        add_action( 'admin_post_image_optimization_convert_single', array( $this, 'convert_single' ) );
    }

    /**
     * Add admin menu
     *
     * @since 1.0.0
     */
    public function add_admin_menu() {
        add_menu_page(
            __( 'Improve Image Delivery PageSpeed', 'improve-image-delivery-pagespeed' ),
            __( 'Improve Image Delivery PageSpeed', 'improve-image-delivery-pagespeed' ),
            'manage_options',
            'image-optimization-dashboard',
            array( $this, 'dashboard_page' ),
            'dashicons-images-alt2',
            30
        );
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @since 1.0.0
     * @param string $hook Current admin page hook.
     */
    public function enqueue_admin_scripts( $hook ) {
        if ( 'toplevel_page_image-optimization-dashboard' !== $hook ) {
            return;
        }

        wp_enqueue_script(
            'image-optimization-admin',
            IMAGE_OPTIMIZATION_PLUGIN_URL . 'admin/js/admin.js',
            array( 'jquery' ),
            IMAGE_OPTIMIZATION_VERSION,
            true
        );

        wp_localize_script( 'image-optimization-admin', 'imageOptimization', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'hasLiteSpeed' => Image_Optimization_Core::get_instance()->is_litespeed_active(),
            'nonces'  => array(
                'scan'    => wp_create_nonce( 'image_optimization_scan_nonce' ),
                'convert' => wp_create_nonce( 'image_optimization_convert_nonce' ),
                'export'  => wp_create_nonce( 'image_optimization_export_nonce' ),
                'revert'  => wp_create_nonce( 'image_optimization_revert_nonce' ),
                'htaccess' => wp_create_nonce( 'image_optimization_htaccess_nonce' ),
            ),
            'strings' => array(
                'scanFailed'    => __( 'Scan failed', 'improve-image-delivery-pagespeed' ),
                'convertFailed' => __( 'Convert failed', 'improve-image-delivery-pagespeed' ),
                'scanError'     => __( 'Scan error', 'improve-image-delivery-pagespeed' ),
                'batchError'    => __( 'Batch error', 'improve-image-delivery-pagespeed' ),
                'done'          => __( 'Done', 'improve-image-delivery-pagespeed' ),
                'optimizationCompleted' => __( 'Optimization completed successfully!', 'improve-image-delivery-pagespeed' ),
                'noImagesNeedOptimization' => __( 'No images need optimization.', 'improve-image-delivery-pagespeed' ),
                'revertConfirm' => __( 'Are you sure you want to remove ALL WebP files? This action cannot be undone.', 'improve-image-delivery-pagespeed' ),
                'revertFailed'  => __( 'Revert failed', 'improve-image-delivery-pagespeed' ),
                'revertCompleted' => __( 'All WebP files have been removed successfully!', 'improve-image-delivery-pagespeed' ),
                'htaccessAdded' => __( '.htaccess rules added successfully!', 'improve-image-delivery-pagespeed' ),
                'htaccessRemoved' => __( '.htaccess rules removed successfully!', 'improve-image-delivery-pagespeed' ),
                'htaccessFailed' => __( 'Failed to modify .htaccess file', 'improve-image-delivery-pagespeed' ),
            ),
        ) );

        wp_enqueue_style(
            'image-optimization-admin',
            IMAGE_OPTIMIZATION_PLUGIN_URL . 'admin/css/admin.css',
            array(),
            IMAGE_OPTIMIZATION_VERSION
        );
    }

    /**
     * Display admin notices
     *
     * @since 1.0.0
     */
    public function admin_notices() {
        $settings = Image_Optimization_Settings::get_instance();
        
        if ( ! $settings->user_can_manage() ) {
            return;
        }

        $converter = Image_Optimization_Converter::get_instance();
        if ( ! $converter->get_support_engine() ) {
            echo '<div class="notice notice-error"><p>';
            echo '<strong>' . esc_html__( 'Image Optimization:', 'improve-image-delivery-pagespeed' ) . '</strong> ';
            echo esc_html__( 'WebP is not supported by Imagick or GD on this server. Conversion will be skipped.', 'improve-image-delivery-pagespeed' );
            echo '</p></div>';
        }

        // Display bulk conversion results
        if ( isset( $_GET['bulk_converted'] ) ) {
            $converted = (int) $_GET['bulk_converted'];
            $errors = isset( $_GET['bulk_errors'] ) ? (int) $_GET['bulk_errors'] : 0;

            if ( $converted > 0 ) {
                echo '<div class="notice notice-success is-dismissible"><p>';
                /* translators: %d: number of images converted */
                echo sprintf( __( 'Successfully optimized %d image(s)', 'improve-image-delivery-pagespeed' ), $converted );
                if ( $errors > 0 ) {
                    /* translators: %d: number of errors */
                    echo sprintf( __( ' with %d error(s)', 'improve-image-delivery-pagespeed' ), $errors );
                }
                echo '.</p></div>';
            } elseif ( $errors > 0 ) {
                echo '<div class="notice notice-error is-dismissible"><p>';
                /* translators: %d: number of errors */
                echo sprintf( __( 'Optimization failed with %d error(s).', 'improve-image-delivery-pagespeed' ), $errors );
                echo '</p></div>';
            }
        }

        // Display single conversion results
        if ( isset( $_GET['converted'] ) && isset( $_GET['errors'] ) && isset( $_GET['skipped'] ) ) {
            $converted = (int) $_GET['converted'];
            $errors = (int) $_GET['errors'];
            $skipped = (int) $_GET['skipped'];

            if ( $converted > 0 ) {
                echo '<div class="notice notice-success is-dismissible"><p>';
                /* translators: %d: number of files converted */
                echo sprintf( __( 'Successfully converted %d file(s) to WebP', 'improve-image-delivery-pagespeed' ), $converted );
                if ( $skipped > 0 ) {
                    /* translators: %d: number of files skipped */
                    echo sprintf( __( ', skipped %d file(s)', 'improve-image-delivery-pagespeed' ), $skipped );
                }
                if ( $errors > 0 ) {
                    /* translators: %d: number of errors */
                    echo sprintf( __( ', %d error(s)', 'improve-image-delivery-pagespeed' ), $errors );
                }
                echo '.</p></div>';
            } elseif ( $errors > 0 ) {
                echo '<div class="notice notice-error is-dismissible"><p>';
                /* translators: %d: number of errors */
                echo sprintf( __( 'Conversion failed with %d error(s).', 'improve-image-delivery-pagespeed' ), $errors );
                echo '</p></div>';
            } elseif ( $skipped > 0 ) {
                echo '<div class="notice notice-info is-dismissible"><p>';
                /* translators: %d: number of files skipped */
                echo sprintf( __( 'All %d file(s) were skipped (already converted or below thresholds).', 'improve-image-delivery-pagespeed' ), $skipped );
                echo '</p></div>';
            }
        }
    }

    /**
     * Dashboard page
     *
     * @since 1.0.0
     */
    public function dashboard_page() {
        $settings = Image_Optimization_Settings::get_instance();
        
        if ( ! $settings->user_can_manage() ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'improve-image-delivery-pagespeed' ) );
        }

        // Handle form submission
        if ( isset( $_POST['save_settings'] ) && $settings->verify_nonce( 'save', $_POST['_wpnonce'] ?? '' ) ) {
            $new_settings = $settings->sanitize_settings( $_POST );
            $settings->update_settings( $new_settings );
            echo '<div class="notice notice-success"><p>' . esc_html__( 'Settings saved successfully!', 'improve-image-delivery-pagespeed' ) . '</p></div>';
        }

        $options = $settings->get_settings();
        $analytics = $settings->get_analytics();
        $core = Image_Optimization_Core::get_instance();
        $is_litespeed_active = $core->is_litespeed_active();
        $image_sizes = get_intermediate_image_sizes();

        include IMAGE_OPTIMIZATION_PLUGIN_DIR . 'admin/views/dashboard.php';
    }

    /**
     * AJAX scan handler
     *
     * @since 1.0.0
     */
    public function ajax_scan() {
        $settings = Image_Optimization_Settings::get_instance();
        
        if ( ! $settings->user_can_manage() ) {
            wp_send_json_error( __( 'Forbidden', 'improve-image-delivery-pagespeed' ) );
        }

        if ( ! $settings->verify_nonce( 'scan', $_POST['_wpnonce'] ?? '' ) ) {
            wp_send_json_error( __( 'Invalid nonce', 'improve-image-delivery-pagespeed' ) );
        }

        $stats = $this->scan_build_pending();
        wp_send_json_success( $stats );
    }

    /**
     * AJAX convert batch handler
     *
     * @since 1.0.0
     */
    public function ajax_convert_batch() {
        $settings = Image_Optimization_Settings::get_instance();
        
        if ( ! $settings->user_can_manage() ) {
            wp_send_json_error( __( 'Forbidden', 'improve-image-delivery-pagespeed' ) );
        }

        if ( ! $settings->verify_nonce( 'convert', $_POST['_wpnonce'] ?? '' ) ) {
            wp_send_json_error( __( 'Invalid nonce', 'improve-image-delivery-pagespeed' ) );
        }

        $pending = get_transient( 'image_optimization_pending_ids' );
        if ( ! is_array( $pending ) ) {
            $pending = array();
        }

        $batch_size = 10; // Reduced because we're processing multiple files per attachment
        $batch = array_splice( $pending, 0, $batch_size );
        $converted_now = 0;
        $errors = 0;

        $converter = Image_Optimization_Converter::get_instance();
        $format = 'webp';

        foreach ( $batch as $id ) {
            $paths = $converter->get_all_image_paths( $id );

            if ( empty( $paths ) ) {
                $errors++;
                continue;
            }

            $results = $converter->convert_all_sizes( $id, $format );

            if ( $results['converted'] > 0 ) {
                $converted_now++;
                /**
                 * Fires after attachment conversion
                 *
                 * @since 1.0.0
                 * @param int   $id    Attachment ID.
                 * @param array $paths Image paths.
                 */
                do_action( 'image_optimization_converted', $id, $paths );
            }
        }

        $stats = $this->scan_build_pending();
        set_transient( 'image_optimization_pending_ids', $pending, HOUR_IN_SECONDS );

        wp_send_json_success( array(
            'converted_batch'    => $converted_now,
            'errors'             => $errors,
            'converted_total'    => $stats['converted'],
            'pending_total'      => $stats['pending'],
            'ignored_total'      => $stats['ignored']['total'],
            'ignored_breakdown'  => array(
                'too_small_dimensions' => $stats['ignored']['too_small_dimensions'],
                'too_small_filesize'   => $stats['ignored']['too_small_filesize'],
                'unreadable'           => $stats['ignored']['unreadable'],
            ),
            'total'              => $stats['total'],
        ) );
    }

    /**
     * AJAX revert all handler
     *
     * @since 1.0.0
     */
    public function ajax_revert_all() {
        $settings = Image_Optimization_Settings::get_instance();
        
        if ( ! $settings->user_can_manage() ) {
            wp_send_json_error( __( 'Forbidden', 'improve-image-delivery-pagespeed' ) );
        }

        if ( ! $settings->verify_nonce( 'revert', $_POST['_wpnonce'] ?? '' ) ) {
            wp_send_json_error( __( 'Invalid nonce', 'improve-image-delivery-pagespeed' ) );
        }

        $converter = Image_Optimization_Converter::get_instance();
        $results = $converter->remove_all_webp_files( 'webp' );
        
        // Update analytics
        $analytics = $settings->get_analytics();
        $analytics['optimized_images'] = 0;
        $analytics['space_saved'] = 0;
        $analytics['last_optimized'] = 'Never';
        $settings->update_analytics( $analytics );
        
        wp_send_json_success( $results );
    }

    /**
     * AJAX add htaccess handler
     *
     * @since 1.0.0
     */
    public function ajax_add_htaccess() {
        $settings = Image_Optimization_Settings::get_instance();
        
        if ( ! $settings->user_can_manage() ) {
            wp_send_json_error( __( 'Forbidden', 'improve-image-delivery-pagespeed' ) );
        }

        if ( ! $settings->verify_nonce( 'htaccess', $_POST['_wpnonce'] ?? '' ) ) {
            wp_send_json_error( __( 'Invalid nonce', 'improve-image-delivery-pagespeed' ) );
        }

        $core = Image_Optimization_Core::get_instance();
        $result = $core->add_htaccess_rules();
        
        if ( $result['success'] ) {
            wp_send_json_success( $result );
        } else {
            wp_send_json_error( $result['message'] );
        }
    }

    /**
     * AJAX remove htaccess handler
     *
     * @since 1.0.0
     */
    public function ajax_remove_htaccess() {
        $settings = Image_Optimization_Settings::get_instance();
        
        if ( ! $settings->user_can_manage() ) {
            wp_send_json_error( __( 'Forbidden', 'improve-image-delivery-pagespeed' ) );
        }

        if ( ! $settings->verify_nonce( 'htaccess', $_POST['_wpnonce'] ?? '' ) ) {
            wp_send_json_error( __( 'Invalid nonce', 'improve-image-delivery-pagespeed' ) );
        }

        $core = Image_Optimization_Core::get_instance();
        $result = $core->remove_htaccess_rules();
        
        if ( $result['success'] ) {
            wp_send_json_success( $result );
        } else {
            wp_send_json_error( $result['message'] );
        }
    }

    /**
     * Scan and build pending list
     *
     * @since 1.0.0
     * @return array
     */
    private function scan_build_pending() {
        $query = new WP_Query( array(
            'post_type'      => 'attachment',
            'post_status'    => 'inherit',
            'post_mime_type' => array( 'image/jpeg', 'image/png' ),
            'fields'         => 'ids',
            'posts_per_page' => -1,
            'no_found_rows'  => true,
        ) );

        $ids = $query->posts;
        $total_attachments = count( $ids );
        $converted_attachments = 0;
        $pending = array();
        $ignored_breakdown = array(
            'too_small_dimensions' => 0,
            'too_small_filesize'   => 0,
            'unreadable'           => 0,
        );

        $settings = Image_Optimization_Settings::get_instance();
        $options = $settings->get_settings();
        $converter = Image_Optimization_Converter::get_instance();
        $force = ! empty( $options['force_all'] );
        $format = 'webp';

        // Calculate space saved and total files processed
        $space_saved = 0;
        $total_files_processed = 0;
        $converted_files = 0;

        foreach ( $ids as $id ) {
            $paths = $converter->get_all_image_paths( $id );

            if ( empty( $paths ) ) {
                $ignored_breakdown['unreadable']++;
                continue;
            }

            $attachment_has_converted = false;
            $attachment_has_pending = false;

            foreach ( $paths as $size_key => $path ) {
                $total_files_processed++;

                if ( ! file_exists( $path ) ) {
                    $ignored_breakdown['unreadable']++;
                    continue;
                }

                $webp_path = $converter->get_target_path( $path, $format );

                if ( file_exists( $webp_path ) ) {
                    $attachment_has_converted = true;
                    $converted_files++;

                    // Calculate space saved
                    $original_size = filesize( $path );
                    $webp_size = filesize( $webp_path );
                    if ( $original_size && $webp_size ) {
                        $space_saved += ( $original_size - $webp_size ) / 1024 / 1024; // in MB
                    }
                    continue;
                }

                if ( ! $force ) {
                    $check = $converter->check_thresholds( $path, $options );
                    if ( ! $check['ok'] ) {
                        $reason = $check['reason'];
                        if ( isset( $ignored_breakdown[ $reason ] ) ) {
                            $ignored_breakdown[ $reason ]++;
                        } else {
                            $ignored_breakdown['unreadable']++;
                        }
                        continue;
                    }
                }

                $attachment_has_pending = true;
            }

            if ( $attachment_has_pending ) {
                $pending[] = $id;
            } elseif ( $attachment_has_converted ) {
                $converted_attachments++;
            }
        }

        set_transient( 'image_optimization_pending_ids', $pending, HOUR_IN_SECONDS );

        $summary = array(
            'total'     => $total_attachments,
            'converted' => $converted_attachments,
            'pending'   => count( $pending ),
            'ignored'   => array_merge( array( 'total' => array_sum( $ignored_breakdown ) ), $ignored_breakdown ),
            'total_files' => $total_files_processed,
            'converted_files' => $converted_files,
        );

        set_transient( 'image_optimization_last_scan_summary', $summary, HOUR_IN_SECONDS );

        // Update analytics
        $analytics = $settings->get_analytics();
        $analytics['total_images'] = $total_attachments;
        $analytics['optimized_images'] = $converted_attachments;
        $analytics['pending_images'] = count( $pending );
        $analytics['space_saved'] = round( $space_saved, 2 );
        $analytics['last_optimized'] = current_time( 'mysql' );
        $analytics['total_files'] = $total_files_processed;
        $analytics['converted_files'] = $converted_files;
        $settings->update_analytics( $analytics );

        return $summary;
    }

    /**
     * Save settings
     *
     * @since 1.0.0
     */
    public function save_settings() {
        $settings = Image_Optimization_Settings::get_instance();
        
        if ( ! $settings->user_can_manage() ) {
            wp_die( esc_html__( 'Forbidden', 'improve-image-delivery-pagespeed' ) );
        }

        if ( ! $settings->verify_nonce( 'save' ) ) {
            wp_die( esc_html__( 'Invalid nonce', 'improve-image-delivery-pagespeed' ) );
        }

        $new_settings = $settings->sanitize_settings( $_POST );
        $settings->update_settings( $new_settings );

        wp_safe_redirect( wp_get_referer() );
        exit;
    }

    /**
     * Export JSON
     *
     * @since 1.0.0
     */
    public function export_json() {
        $settings = Image_Optimization_Settings::get_instance();
        
        if ( ! $settings->user_can_manage() ) {
            wp_die( esc_html__( 'Forbidden', 'improve-image-delivery-pagespeed' ) );
        }

        if ( ! $settings->verify_nonce( 'export' ) ) {
            wp_die( esc_html__( 'Invalid nonce', 'improve-image-delivery-pagespeed' ) );
        }

        $pending = get_transient( 'image_optimization_pending_ids' );
        if ( ! is_array( $pending ) ) {
            $this->scan_build_pending();
            $pending = get_transient( 'image_optimization_pending_ids' );
        }

        $converter = Image_Optimization_Converter::get_instance();
        $out = array();

        foreach ( $pending as $id ) {
            $paths = $converter->get_all_image_paths( $id );
            $url = wp_get_attachment_url( $id );

            foreach ( $paths as $size_key => $path ) {
                if ( $path ) {
                    $out[] = array(
                        'id'   => $id,
                        'size' => $size_key,
                        'path' => $path,
                        'url'  => $url,
                    );
                }
            }
        }

        nocache_headers();
        header( 'Content-Type: application/json; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=pending-webp.json' );
        echo wp_json_encode( $out );
        exit;
    }

    /**
     * Export CSV
     *
     * @since 1.0.0
     */
    public function export_csv() {
        $settings = Image_Optimization_Settings::get_instance();
        
        if ( ! $settings->user_can_manage() ) {
            wp_die( esc_html__( 'Forbidden', 'improve-image-delivery-pagespeed' ) );
        }

        if ( ! $settings->verify_nonce( 'export' ) ) {
            wp_die( esc_html__( 'Invalid nonce', 'improve-image-delivery-pagespeed' ) );
        }

        $pending = get_transient( 'image_optimization_pending_ids' );
        if ( ! is_array( $pending ) ) {
            $this->scan_build_pending();
            $pending = get_transient( 'image_optimization_pending_ids' );
        }

        nocache_headers();
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=pending-webp.csv' );

        $out = fopen( 'php://output', 'w' );
        fputcsv( $out, array( 'id', 'size', 'path', 'url' ) );

        $converter = Image_Optimization_Converter::get_instance();

        foreach ( $pending as $id ) {
            $paths = $converter->get_all_image_paths( $id );
            $url = wp_get_attachment_url( $id );

            foreach ( $paths as $size_key => $path ) {
                if ( $path ) {
                    fputcsv( $out, array( $id, $size_key, $path, $url ) );
                }
            }
        }

        fclose( $out );
        exit;
    }

    /**
     * Add bulk actions to media library
     *
     * @since 1.0.0
     * @param array $bulk_actions Bulk actions array.
     * @return array
     */
    public function add_bulk_actions( $bulk_actions ) {
        $bulk_actions['image_optimization_convert'] = __( 'Convert to WebP', 'improve-image-delivery-pagespeed' );
        return $bulk_actions;
    }

    /**
     * Handle bulk actions
     *
     * @since 1.0.0
     * @param string $redirect_to Redirect URL.
     * @param string $doaction    Action name.
     * @param array  $post_ids    Post IDs.
     * @return string
     */
    public function handle_bulk_actions( $redirect_to, $doaction, $post_ids ) {
        if ( 'image_optimization_convert' !== $doaction ) {
            return $redirect_to;
        }

        $settings = Image_Optimization_Settings::get_instance();
        if ( ! $settings->user_can_manage() ) {
            return $redirect_to;
        }

        $converter = Image_Optimization_Converter::get_instance();
        $core = Image_Optimization_Core::get_instance();
        $format = 'webp';
        $converted_count = 0;
        $error_count = 0;

        foreach ( $post_ids as $id ) {
            $mime = get_post_mime_type( $id );
            if ( ! $core->is_convertible_mime( $mime ) ) {
                continue;
            }

            $results = $converter->convert_all_sizes( $id, $format );

            if ( $results['converted'] > 0 ) {
                $converted_count++;
            }

            $error_count += $results['errors'];
        }

        $redirect_to = add_query_arg( array(
            'bulk_converted' => $converted_count,
            'bulk_errors' => $error_count,
        ), $redirect_to );

        return $redirect_to;
    }

    /**
     * Add media columns
     *
     * @since 1.0.0
     * @param array $columns Columns array.
     * @return array
     */
    public function add_media_columns( $columns ) {
        $columns['webp_status'] = __( 'WebP Status', 'improve-image-delivery-pagespeed' );
        return $columns;
    }

    /**
     * Display media columns
     *
     * @since 1.0.0
     * @param string $column_name  Column name.
     * @param int    $attachment_id Attachment ID.
     */
    public function display_media_columns( $column_name, $attachment_id ) {
        if ( 'webp_status' !== $column_name ) {
            return;
        }

        $core = Image_Optimization_Core::get_instance();
        $mime = get_post_mime_type( $attachment_id );
        
        if ( ! $core->is_convertible_mime( $mime ) ) {
            echo '<span style="color: #999;">' . esc_html__( 'N/A', 'improve-image-delivery-pagespeed' ) . '</span>';
            return;
        }

        $converter = Image_Optimization_Converter::get_instance();
        $paths = $converter->get_all_image_paths( $attachment_id );
        $format = 'webp';
        $total_sizes = count( $paths );
        $converted_sizes = 0;

        foreach ( $paths as $path ) {
            $webp_path = $converter->get_target_path( $path, $format );
            if ( file_exists( $webp_path ) ) {
                $converted_sizes++;
            }
        }

        if ( 0 === $converted_sizes ) {
            echo '<span style="color: #d63638;">' . esc_html__( 'Not optimized', 'improve-image-delivery-pagespeed' ) . '</span>';
        } elseif ( $converted_sizes === $total_sizes ) {
            /* translators: %1$d: converted sizes, %2$d: total sizes */
            echo '<span style="color: #00a32a;">' . sprintf( __( 'Fully optimized (%1$d/%2$d)', 'improve-image-delivery-pagespeed' ), $converted_sizes, $total_sizes ) . '</span>';
        } else {
            /* translators: %1$d: converted sizes, %2$d: total sizes */
            echo '<span style="color: #dba617;">' . sprintf( __( 'Partially optimized (%1$d/%2$d)', 'improve-image-delivery-pagespeed' ), $converted_sizes, $total_sizes ) . '</span>';
        }
    }

    /**
     * Add media row actions
     *
     * @since 1.0.0
     * @param array   $actions Actions array.
     * @param WP_Post $post    Post object.
     * @return array
     */
    public function add_media_row_actions( $actions, $post ) {
        $settings = Image_Optimization_Settings::get_instance();
        if ( ! $settings->user_can_manage() ) {
            return $actions;
        }

        $core = Image_Optimization_Core::get_instance();
        $mime = get_post_mime_type( $post->ID );
        
        if ( ! $core->is_convertible_mime( $mime ) ) {
            return $actions;
        }

        $converter = Image_Optimization_Converter::get_instance();
        $paths = $converter->get_all_image_paths( $post->ID );
        $format = 'webp';
        $needs_conversion = false;

        foreach ( $paths as $path ) {
            $webp_path = $converter->get_target_path( $path, $format );
            if ( ! file_exists( $webp_path ) ) {
                $needs_conversion = true;
                break;
            }
        }

        if ( $needs_conversion ) {
            $convert_url = wp_nonce_url(
                admin_url( 'admin-post.php?action=image_optimization_convert_single&attachment_id=' . $post->ID ),
                $settings->get_nonce_action( 'convert_single' )
            );

            $actions['convert_webp'] = '<a href="' . esc_url( $convert_url ) . '">' . esc_html__( 'Convert to WebP', 'improve-image-delivery-pagespeed' ) . '</a>';
        }

        return $actions;
    }

    /**
     * Convert single attachment
     *
     * @since 1.0.0
     */
    public function convert_single() {
        $settings = Image_Optimization_Settings::get_instance();
        
        if ( ! $settings->user_can_manage() ) {
            wp_die( esc_html__( 'Forbidden', 'improve-image-delivery-pagespeed' ) );
        }

        if ( ! $settings->verify_nonce( 'convert_single', $_GET['_wpnonce'] ?? '' ) ) {
            wp_die( esc_html__( 'Invalid nonce', 'improve-image-delivery-pagespeed' ) );
        }

        $attachment_id = isset( $_GET['attachment_id'] ) ? (int) $_GET['attachment_id'] : 0;

        if ( ! $attachment_id ) {
            wp_die( esc_html__( 'Invalid attachment ID.', 'improve-image-delivery-pagespeed' ) );
        }

        $core = Image_Optimization_Core::get_instance();
        $mime = get_post_mime_type( $attachment_id );
        
        if ( ! $core->is_convertible_mime( $mime ) ) {
            wp_die( esc_html__( 'This file type cannot be converted.', 'improve-image-delivery-pagespeed' ) );
        }

        $converter = Image_Optimization_Converter::get_instance();
        $results = $converter->convert_all_sizes( $attachment_id, 'webp' );

        $redirect_url = add_query_arg( array(
            'converted' => $results['converted'],
            'errors'    => $results['errors'],
            'skipped'   => $results['skipped'],
        ), wp_get_referer() );

        wp_safe_redirect( $redirect_url );
        exit;
    }

    /**
     * AJAX handler for changing plugin language
     *
     * @since 1.0.0
     */
    public function ajax_change_language() {
        check_ajax_referer( 'image_optimization_scan_nonce', '_wpnonce' );
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Insufficient permissions', 'improve-image-delivery-pagespeed' ) );
        }
        
        $locale = sanitize_text_field( $_POST['locale'] ?? '' );
        
        if ( ! in_array( $locale, array( 'vi_VN', 'en_US' ), true ) ) {
            wp_send_json_error( __( 'Invalid language selection', 'improve-image-delivery-pagespeed' ) );
        }
        
        // Store the user's language preference for this plugin
        update_user_meta( get_current_user_id(), 'image_optimization_language', $locale );
        
        // Store it as a site option as well for consistency
        update_option( 'image_optimization_language', $locale );
        
        // Temporarily override the locale for this request
        add_filter( 'locale', function( $current_locale ) use ( $locale ) {
            return $locale;
        }, 999 );
        
        // Reload text domain with new locale
        unload_textdomain( 'improve-image-delivery-pagespeed' );
        
        // Load the correct translations
        if ( $locale === 'vi_VN' ) {
            // Load Vietnamese translations or use fallbacks
            $main_plugin = Image_Optimization::get_instance();
            $main_plugin->load_vietnamese_fallbacks();
        }
        
        wp_send_json_success( array(
            'message' => __( 'Language changed successfully', 'improve-image-delivery-pagespeed' ),
            'locale' => $locale
        ) );
    }
    
    /**
     * Ensure Vietnamese language is loaded for the plugin
     */
    public function ensure_vietnamese_language() {
        // Check if we're on the plugin's admin page
        $screen = get_current_screen();
        if ( ! $screen || $screen->id !== 'toplevel_page_image-optimization-dashboard' ) {
            return;
        }
        
        // Get user preference
        $user_preference = get_user_meta( get_current_user_id(), 'image_optimization_language', true );
        $site_preference = get_option( 'image_optimization_language', '' );
        $preferred_locale = ! empty( $user_preference ) ? $user_preference : $site_preference;
        
        // Default to Vietnamese if no preference set
        if ( empty( $preferred_locale ) ) {
            $preferred_locale = 'vi_VN';
            // Store the default preference
            update_option( 'image_optimization_language', 'vi_VN' );
        }
        
        // Load Vietnamese fallbacks if Vietnamese is preferred
        if ( $preferred_locale === 'vi_VN' ) {
            $main_plugin = Image_Optimization::get_instance();
            $main_plugin->load_vietnamese_fallbacks();
        }
    }
}