<?php
/**
 * Uninstall script for Image Optimization plugin
 *
 * This file is executed when the plugin is deleted through WordPress admin.
 * It cleans up all plugin data, options, and transients.
 *
 * @package ImageOptimization
 * @since 2.1.0
 */

// Prevent direct access
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

/**
 * Clean up plugin data on uninstall
 *
 * @since 2.1.0
 */
function image_optimization_uninstall_cleanup() {
    global $wpdb;

    // Delete plugin options
    delete_option( 'image_optimization_settings' );
    delete_option( 'image_optimization_analytics' );
    delete_option( 'image_optimization_version' );

    // Delete transients
    delete_transient( 'image_optimization_pending_ids' );
    delete_transient( 'image_optimization_last_scan_summary' );

    // Clean up any remaining transients with our prefix
    $wpdb->query( $wpdb->prepare(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
        '_transient_image_optimization_%'
    ) );

    $wpdb->query( $wpdb->prepare(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
        '_transient_timeout_image_optimization_%'
    ) );

    // For multisite, clean up site options
    if ( is_multisite() ) {
        delete_site_option( 'image_optimization_settings' );
        delete_site_option( 'image_optimization_analytics' );
        delete_site_option( 'image_optimization_version' );
    }

    // Optional: Remove WebP files (uncomment if you want to delete converted files)
    /*
    $upload_dir = wp_upload_dir();
    if ( $upload_dir['basedir'] ) {
        image_optimization_remove_webp_files( $upload_dir['basedir'] );
    }
    */
}

/**
 * Recursively remove WebP files from uploads directory
 *
 * @since 2.1.0
 * @param string $dir Directory path.
 */
function image_optimization_remove_webp_files( $dir ) {
    if ( ! is_dir( $dir ) ) {
        return;
    }

    $files = scandir( $dir );
    if ( false === $files ) {
        return;
    }

    foreach ( $files as $file ) {
        if ( '.' === $file || '..' === $file ) {
            continue;
        }

        $file_path = $dir . DIRECTORY_SEPARATOR . $file;

        if ( is_dir( $file_path ) ) {
            // Recursively process subdirectories
            image_optimization_remove_webp_files( $file_path );
        } elseif ( is_file( $file_path ) ) {
            // Check if it's a WebP file created by our plugin
            if ( preg_match( '/\.(jpg|jpeg|png)\.webp$/i', $file ) ) {
                // Verify the original file exists before deleting WebP
                $original_file = preg_replace( '/\.webp$/i', '', $file_path );
                if ( file_exists( $original_file ) ) {
                    @unlink( $file_path );
                }
            }
        }
    }
}

// Execute cleanup
image_optimization_uninstall_cleanup();