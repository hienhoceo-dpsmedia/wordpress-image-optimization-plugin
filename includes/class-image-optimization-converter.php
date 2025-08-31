<?php
/**
 * Image converter class
 *
 * @package ImageOptimization
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Image converter class
 *
 * @since 1.0.0
 */
class Image_Optimization_Converter {

    /**
     * Instance
     *
     * @since 1.0.0
     * @var Image_Optimization_Converter|null
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @since 1.0.0
     * @return Image_Optimization_Converter
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
     * Get support engine
     *
     * @since 1.0.0
     * @return string|false
     */
    public function get_support_engine() {
        $capabilities = $this->get_server_capabilities();
        
        if ( $capabilities['imagick']['webp'] || $capabilities['imagick']['avif'] ) {
            return 'imagick';
        }
        
        if ( $capabilities['gd']['webp'] ) {
            return 'gd';
        }
        
        return false;
    }

    /**
     * Get detailed server capabilities for format conversion
     *
     * @since 1.0.0
     * @return array
     */
    public function get_server_capabilities() {
        $capabilities = array(
            'imagick' => array(
                'available' => false,
                'webp' => false,
                'avif' => false,
            ),
            'gd' => array(
                'available' => false,
                'webp' => false,
            ),
        );
        
        // Check Imagick capabilities
        if ( class_exists( 'Imagick' ) ) {
            $capabilities['imagick']['available'] = true;
            
            try {
                $im = new Imagick();
                $formats = array_map( 'strtoupper', $im->queryFormats() );
                $capabilities['imagick']['webp'] = in_array( 'WEBP', $formats, true );
                $capabilities['imagick']['avif'] = in_array( 'AVIF', $formats, true );
            } catch ( Exception $e ) {
                $capabilities['imagick']['available'] = false;
            }
        }
        
        // Check GD capabilities
        if ( function_exists( 'gd_info' ) ) {
            $capabilities['gd']['available'] = true;
            $gd = gd_info();
            $capabilities['gd']['webp'] = ! empty( $gd['WebP Support'] );
            // GD doesn't support AVIF yet
        }
        
        return $capabilities;
    }

    /**
     * Check if server supports specific format
     *
     * @since 1.0.0
     * @param string $format Format to check (webp, avif).
     * @return bool
     */
    public function server_supports_format( $format ) {
        $capabilities = $this->get_server_capabilities();
        
        switch ( strtolower( $format ) ) {
            case 'webp':
                return $capabilities['imagick']['webp'] || $capabilities['gd']['webp'];
            case 'avif':
                return $capabilities['imagick']['avif'];
            default:
                return false;
        }
    }

    /**
     * Get original path for attachment
     *
     * @since 1.0.0
     * @param int $attachment_id Attachment ID.
     * @return string|false
     */
    public function get_original_path( $attachment_id ) {
        $path = get_attached_file( $attachment_id );
        return ( $path && file_exists( $path ) ) ? $path : false;
    }

    /**
     * Get target path for converted image
     *
     * @since 1.0.0
     * @param string $original_path Original file path.
     * @param string $format        Target format.
     * @return string
     */
    public function get_target_path( $original_path, $format = 'webp' ) {
        return $original_path . '.' . $format;
    }

    /**
     * Get all image paths for attachment
     *
     * @since 1.0.0
     * @param int $attachment_id Attachment ID.
     * @return array
     */
    public function get_all_image_paths( $attachment_id ) {
        $paths = array();
        $settings = Image_Optimization_Settings::get_instance();
        $options = $settings->get_settings();
        
        // Original image
        if ( ! empty( $options['optimize_original'] ) ) {
            $original_path = $this->get_original_path( $attachment_id );
            if ( $original_path ) {
                $paths['original'] = $original_path;
            }
        }
        
        // Thumbnail sizes
        if ( ! empty( $options['optimize_thumbnails'] ) ) {
            $metadata = wp_get_attachment_metadata( $attachment_id );
            if ( ! empty( $metadata['sizes'] ) && is_array( $metadata['sizes'] ) ) {
                $base_dir = dirname( get_attached_file( $attachment_id ) );
                
                foreach ( $metadata['sizes'] as $size_name => $size_data ) {
                    if ( ! empty( $size_data['file'] ) ) {
                        $thumb_path = $base_dir . '/' . $size_data['file'];
                        if ( file_exists( $thumb_path ) ) {
                            $paths[ 'thumb_' . $size_name ] = $thumb_path;
                        }
                    }
                }
            }
        }
        
        return $paths;
    }

    /**
     * Check thresholds
     *
     * @since 1.0.0
     * @param string $path     File path.
     * @param array  $settings Settings array.
     * @return array
     */
    public function check_thresholds( $path, $settings = null ) {
        if ( null === $settings ) {
            $settings_instance = Image_Optimization_Settings::get_instance();
            $settings = $settings_instance->get_settings();
        }
        
        $out = array(
            'ok'      => true,
            'reason'  => null,
            'details' => array(),
        );
        
        $info = @getimagesize( $path );
        if ( ! $info ) {
            return array(
                'ok'      => false,
                'reason'  => 'unreadable',
                'details' => array(),
            );
        }
        
        $w = (int) $info[0];
        $h = (int) $info[1];
        $kb = @filesize( $path );
        $kb = $kb ? (int) ceil( $kb / 1024 ) : 0;
        
        $out['details'] = compact( 'w', 'h', 'kb' );
        
        if ( $w < (int) $settings['min_w'] || $h < (int) $settings['min_h'] ) {
            return array(
                'ok'      => false,
                'reason'  => 'too_small_dimensions',
                'details' => $out['details'],
            );
        }
        
        if ( $kb < (int) $settings['min_kb'] ) {
            return array(
                'ok'      => false,
                'reason'  => 'too_small_filesize',
                'details' => $out['details'],
            );
        }
        
        return $out;
    }

    /**
     * Convert file
     *
     * @since 1.0.0
     * @param string $src_path Source path.
     * @param string $dst_path Destination path.
     * @param int    $quality  Quality setting.
     * @param string $format   Target format.
     * @return bool|WP_Error
     */
    public function convert_file( $src_path, $dst_path, $quality = 80, $format = 'webp' ) {
        // Check if server supports the requested format
        if ( ! $this->server_supports_format( $format ) ) {
            return new WP_Error( 'format-unsupported', sprintf( __( 'Server does not support %s format conversion.', 'improve-image-delivery-pagespeed' ), strtoupper( $format ) ) );
        }
        
        $engine = $this->get_support_engine();
        if ( ! $engine ) {
            return new WP_Error( 'no-format-support', __( 'Neither Imagick nor GD format support is available on this server.', 'improve-image-delivery-pagespeed' ) );
        }
        
        if ( file_exists( $dst_path ) ) {
            return true; // Already exists
        }
        
        $info = @getimagesize( $src_path );
        $core = Image_Optimization_Core::get_instance();
        
        if ( ! $info || ! $core->is_convertible_mime( $info['mime'] ?? '' ) ) {
            return new WP_Error( 'unsupported', __( 'Source is not a JPEG or PNG.', 'improve-image-delivery-pagespeed' ) );
        }
        
        if ( 'imagick' === $engine ) {
            return $this->convert_with_imagick( $src_path, $dst_path, $quality, $format, $info );
        }
        
        // GD (only supports WebP)
        if ( 'webp' !== $format ) {
            return new WP_Error( 'gd-format-limited', sprintf( __( 'GD library only supports WebP format. %s is not supported.', 'improve-image-delivery-pagespeed' ), strtoupper( $format ) ) );
        }
        
        return $this->convert_with_gd( $src_path, $dst_path, $quality, $format, $info );
    }

    /**
     * Convert with Imagick
     *
     * @since 1.0.0
     * @param string $src_path Source path.
     * @param string $dst_path Destination path.
     * @param int    $quality  Quality.
     * @param string $format   Format.
     * @param array  $info     Image info.
     * @return bool|WP_Error
     */
    private function convert_with_imagick( $src_path, $dst_path, $quality, $format, $info ) {
        try {
            $im = new Imagick( $src_path );
            
            if ( 'image/png' === $info['mime'] && $im->getImageAlphaChannel() ) {
                $im->setImageBackgroundColor( new ImagickPixel( 'transparent' ) );
                $im = $im->mergeImageLayers( Imagick::LAYERMETHOD_MERGE );
            }
            
            $im->setImageFormat( $format );
            $im->setImageCompressionQuality( (int) apply_filters( 'image_optimization_quality', $quality ) );
            
            $ok = $im->writeImage( $dst_path );
            $im->clear();
            $im->destroy();
            
            return $ok ? true : new WP_Error( 'write-failed', sprintf( __( 'Failed to write %s (Imagick).', 'improve-image-delivery-pagespeed' ), strtoupper( $format ) ) );
        } catch ( Exception $e ) {
            return new WP_Error( 'imagick-error', $e->getMessage() );
        }
    }

    /**
     * Convert with GD
     *
     * @since 1.0.0
     * @param string $src_path Source path.
     * @param string $dst_path Destination path.
     * @param int    $quality  Quality.
     * @param string $format   Format.
     * @param array  $info     Image info.
     * @return bool|WP_Error
     */
    private function convert_with_gd( $src_path, $dst_path, $quality, $format, $info ) {
        if ( 'webp' !== $format ) {
            return new WP_Error( 'gd-unsupported', __( 'GD only supports WebP format.', 'improve-image-delivery-pagespeed' ) );
        }
        
        $q = (int) apply_filters( 'image_optimization_quality', $quality );
        
        if ( 'image/jpeg' === $info['mime'] ) {
            $img = @imagecreatefromjpeg( $src_path );
        } else {
            $img = @imagecreatefrompng( $src_path );
            if ( $img ) {
                imagepalettetotruecolor( $img );
                imagealphablending( $img, true );
                imagesavealpha( $img, true );
            }
        }
        
        if ( ! $img ) {
            return new WP_Error( 'gd-open', __( 'Failed to open source image (GD).', 'improve-image-delivery-pagespeed' ) );
        }
        
        $ok = @imagewebp( $img, $dst_path, $q );
        imagedestroy( $img );
        
        return $ok ? true : new WP_Error( 'gd-write', __( 'Failed to write WebP (GD).', 'improve-image-delivery-pagespeed' ) );
    }

    /**
     * Convert all sizes for attachment
     *
     * @since 1.0.0
     * @param int    $attachment_id Attachment ID.
     * @param string $format        Target format (can be 'webp', 'avif', or 'both').
     * @return array
     */
    public function convert_all_sizes( $attachment_id, $format = null ) {
        $settings_instance = Image_Optimization_Settings::get_instance();
        $settings = $settings_instance->get_settings();
        $paths = $this->get_all_image_paths( $attachment_id );
        
        // Use settings format if not specified
        if ( null === $format ) {
            $format = $settings['output_format'];
        }
        
        // Determine which formats to convert to
        $formats_to_convert = $this->get_formats_to_convert( $format );
        
        $results = array(
            'converted' => 0,
            'skipped'   => 0,
            'errors'    => 0,
            'details'   => array(),
        );
        
        foreach ( $paths as $size_key => $src_path ) {
            // Check thresholds unless force_all is enabled
            if ( empty( $settings['force_all'] ) ) {
                $chk = $this->check_thresholds( $src_path, $settings );
                if ( ! $chk['ok'] ) {
                    $results['skipped']++;
                    $results['details'][ $size_key ] = 'skipped: ' . $chk['reason'];
                    continue;
                }
            }
            
            // Convert to each requested format
            foreach ( $formats_to_convert as $target_format ) {
                // Check if server supports this format
                if ( ! $this->server_supports_format( $target_format ) ) {
                    $results['errors']++;
                    $results['details'][ $size_key . '_' . $target_format ] = 'error: server does not support ' . strtoupper( $target_format );
                    continue;
                }
                
                $dst_path = $this->get_target_path( $src_path, $target_format );
                
                // Skip if already exists
                if ( file_exists( $dst_path ) ) {
                    $results['skipped']++;
                    $results['details'][ $size_key . '_' . $target_format ] = 'already exists';
                    continue;
                }
                
                $convert_result = $this->convert_file( $src_path, $dst_path, $settings['quality'], $target_format );
                
                if ( true === $convert_result ) {
                    $results['converted']++;
                    $results['details'][ $size_key . '_' . $target_format ] = 'converted';
                } else {
                    $results['errors']++;
                    $error_msg = is_wp_error( $convert_result ) ? $convert_result->get_error_message() : __( 'unknown error', 'improve-image-delivery-pagespeed' );
                    $results['details'][ $size_key . '_' . $target_format ] = 'error: ' . $error_msg;
                }
            }
        }
        
        return $results;
    }

    /**
     * Remove all WebP files for attachment
     *
     * @since 1.0.0
     * @param int    $attachment_id Attachment ID.
     * @param string $format        Target format (can be 'webp', 'avif', or 'both').
     * @return array
     */
    public function remove_all_sizes( $attachment_id, $format = null ) {
        $settings_instance = Image_Optimization_Settings::get_instance();
        $settings = $settings_instance->get_settings();
        $paths = $this->get_all_image_paths( $attachment_id );
        
        // Use settings format if not specified, but default to both for removal
        if ( null === $format ) {
            $format = 'both'; // Remove all formats when not specified
        }
        
        // Determine which formats to remove
        $formats_to_remove = $this->get_formats_to_convert( $format );
        
        $results = array(
            'removed' => 0,
            'errors'  => 0,
            'details' => array(),
        );
        
        foreach ( $paths as $size_key => $src_path ) {
            foreach ( $formats_to_remove as $target_format ) {
                $dst_path = $this->get_target_path( $src_path, $target_format );
                
                if ( ! file_exists( $dst_path ) ) {
                    $results['details'][ $size_key . '_' . $target_format ] = 'not found';
                    continue;
                }
                
                if ( @unlink( $dst_path ) ) {
                    $results['removed']++;
                    $results['details'][ $size_key . '_' . $target_format ] = 'removed';
                } else {
                    $results['errors']++;
                    $results['details'][ $size_key . '_' . $target_format ] = 'error: failed to delete';
                }
            }
        }
        
        return $results;
    }

    /**
     * Get formats to convert based on user selection
     *
     * @since 1.0.0
     * @param string $format_setting Format setting ('webp', 'avif', or 'both').
     * @return array
     */
    private function get_formats_to_convert( $format_setting ) {
        switch ( $format_setting ) {
            case 'avif':
                return array( 'avif' );
            case 'both':
                return array( 'webp', 'avif' );
            case 'webp':
            default:
                return array( 'webp' );
        }
    }

    /**
     * Scan and remove all WebP files in uploads directory
     *
     * @since 1.0.0
     * @param string $format Target format.
     * @return array
     */
    public function remove_all_webp_files( $format = 'webp' ) {
        $upload_dir = wp_upload_dir();
        if ( ! $upload_dir['basedir'] ) {
            return array(
                'removed' => 0,
                'errors'  => 1,
                'message' => __( 'Could not access uploads directory', 'improve-image-delivery-pagespeed' ),
            );
        }
        
        $removed_count = 0;
        $error_count = 0;
        
        $this->remove_webp_files_recursive( $upload_dir['basedir'], $format, $removed_count, $error_count );
        
        return array(
            'removed' => $removed_count,
            'errors'  => $error_count,
            'message' => sprintf(
                /* translators: %1$d: removed files, %2$d: errors */
                __( 'Removed %1$d WebP files with %2$d errors', 'improve-image-delivery-pagespeed' ),
                $removed_count,
                $error_count
            ),
        );
    }

    /**
     * Recursively remove WebP files
     *
     * @since 1.0.0
     * @param string $dir           Directory path.
     * @param string $format        Format to remove.
     * @param int    $removed_count Reference to removed count.
     * @param int    $error_count   Reference to error count.
     */
    private function remove_webp_files_recursive( $dir, $format, &$removed_count, &$error_count ) {
        if ( ! is_dir( $dir ) ) {
            return;
        }
        
        $files = scandir( $dir );
        if ( false === $files ) {
            $error_count++;
            return;
        }
        
        foreach ( $files as $file ) {
            if ( '.' === $file || '..' === $file ) {
                continue;
            }
            
            $file_path = $dir . DIRECTORY_SEPARATOR . $file;
            
            if ( is_dir( $file_path ) ) {
                // Recursively process subdirectories
                $this->remove_webp_files_recursive( $file_path, $format, $removed_count, $error_count );
            } elseif ( is_file( $file_path ) ) {
                // Check if it's a WebP file created by our plugin
                $pattern = '/\.(jpg|jpeg|png)\.' . preg_quote( $format, '/' ) . '$/i';
                if ( preg_match( $pattern, $file ) ) {
                    // Verify the original file exists before deleting WebP
                    $original_file = preg_replace( '/\.' . preg_quote( $format, '/' ) . '$/i', '', $file_path );
                    if ( file_exists( $original_file ) ) {
                        if ( @unlink( $file_path ) ) {
                            $removed_count++;
                        } else {
                            $error_count++;
                        }
                    }
                }
            }
        }
    }
}