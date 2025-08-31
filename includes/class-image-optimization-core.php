<?php
/**
 * Core functionality class
 *
 * @package ImageOptimization
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Prevent class redeclaration during plugin updates
if ( ! class_exists( 'Image_Optimization_Core' ) ) :

/**
 * Core functionality class
 *
 * @since 1.0.0
 */
class Image_Optimization_Core {

    /**
     * Instance
     *
     * @since 1.0.0
     * @var Image_Optimization_Core|null
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @since 1.0.0
     * @return Image_Optimization_Core
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
    }

    /**
     * Initialize hooks
     *
     * @since 1.0.0
     */
    private function init_hooks() {
        add_action( 'add_attachment', array( $this, 'handle_new_upload' ) );
        add_action( 'init', array( $this, 'init_fallback_replacement' ) );
    }

    /**
     * Handle new upload
     *
     * @since 1.0.0
     * @param int $attachment_id Attachment ID.
     */
    public function handle_new_upload( $attachment_id ) {
        $mime = get_post_mime_type( $attachment_id );
        if ( ! $this->is_convertible_mime( $mime ) ) {
            return;
        }
        
        // Default to WebP format
        $format = 'webp';
        
        // Convert all sizes for this attachment
        $converter = Image_Optimization_Converter::get_instance();
        $results = $converter->convert_all_sizes( $attachment_id, $format );
        
        /**
         * Fires after upload conversion
         *
         * @since 1.0.0
         * @param int   $attachment_id Attachment ID.
         * @param array $results       Conversion results.
         */
        do_action( 'image_optimization_upload_converted', $attachment_id, $results );
    }

    /**
     * Initialize fallback replacement
     *
     * @since 1.0.0
     */
    public function init_fallback_replacement() {
        $settings = Image_Optimization_Settings::get_instance();
        $options = $settings->get_settings();
        
        // Enable URL replacement if the user has chosen to use it
        if ( ! empty( $options['enable_fallback_replacement'] ) ) {
            add_filter( 'the_content', array( $this, 'replace_content_urls' ), 99 );
            add_filter( 'post_thumbnail_html', array( $this, 'replace_post_thumbnail' ), 99, 5 );
            add_filter( 'widget_text', array( $this, 'replace_content_urls' ), 99 );
            add_filter( 'wp_nav_menu', array( $this, 'replace_content_urls' ), 99 );
        }
    }

    /**
     * Check if mime type is convertible
     *
     * @since 1.0.0
     * @param string $mime MIME type.
     * @return bool
     */
    public function is_convertible_mime( $mime ) {
        return in_array( $mime, array( 'image/jpeg', 'image/png' ), true );
    }

    /**
     * Check if LiteSpeed Cache is active
     *
     * @since 1.0.0
     * @return bool
     */
    public function is_litespeed_active() {
        return is_plugin_active( 'litespeed-cache/litespeed-cache.php' );
    }

    /**
     * Check if browser supports format
     *
     * @since 1.0.0
     * @param string $format Image format.
     * @return bool
     */
    public function browser_supports_format( $format ) {
        if ( 'webp' === $format && isset( $_SERVER['HTTP_ACCEPT'] ) && false !== strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) ) {
            return true;
        }
        
        if ( 'avif' === $format && isset( $_SERVER['HTTP_ACCEPT'] ) && false !== strpos( $_SERVER['HTTP_ACCEPT'], 'image/avif' ) ) {
            return true;
        }
        
        return false;
    }

    /**
     * Get preferred format based on user settings and browser support
     *
     * @since 1.0.0
     * @param string $setting User format setting ('webp', 'avif', or 'both').
     * @return string|false
     */
    public function get_preferred_format( $setting ) {
        $converter = Image_Optimization_Converter::get_instance();
        
        switch ( $setting ) {
            case 'avif':
                // Only AVIF requested
                if ( $this->browser_supports_format( 'avif' ) && $converter->server_supports_format( 'avif' ) ) {
                    return 'avif';
                }
                break;
                
            case 'both':
                // Prefer AVIF if supported, fallback to WebP
                if ( $this->browser_supports_format( 'avif' ) && $converter->server_supports_format( 'avif' ) ) {
                    return 'avif';
                }
                if ( $this->browser_supports_format( 'webp' ) && $converter->server_supports_format( 'webp' ) ) {
                    return 'webp';
                }
                break;
                
            case 'webp':
            default:
                // WebP requested or default
                if ( $this->browser_supports_format( 'webp' ) && $converter->server_supports_format( 'webp' ) ) {
                    return 'webp';
                }
                break;
        }
        
        return false; // No supported format available
    }

    /**
     * Get next-gen URL
     *
     * @since 1.0.0
     * @param string $url           Image URL.
     * @param int    $attachment_id Attachment ID.
     * @return string
     */
    public function get_next_gen_url( $url, $attachment_id = null ) {
        $settings = Image_Optimization_Settings::get_instance();
        $options = $settings->get_settings();
        
        // Check if URL replacement is enabled
        if ( empty( $options['enable_fallback_replacement'] ) ) {
            return $url;
        }
        
        // Get preferred format based on user settings and browser support
        $format = $this->get_preferred_format( $options['output_format'] );
        
        if ( ! $format ) {
            return $url; // No supported format available
        }
        
        // If no attachment ID provided, try to get it from URL
        if ( ! $attachment_id ) {
            $attachment_id = attachment_url_to_postid( $url );
        }
        
        if ( ! $attachment_id ) {
            return $url;
        }
        
        // Get the file path from URL
        $upload_dir = wp_upload_dir();
        $relative_path = str_replace( $upload_dir['baseurl'], '', $url );
        $file_path = $upload_dir['basedir'] . $relative_path;
        
        if ( ! file_exists( $file_path ) ) {
            return $url;
        }
        
        $converter = Image_Optimization_Converter::get_instance();
        $next_gen_path = $converter->get_target_path( $file_path, $format );
        
        if ( ! file_exists( $next_gen_path ) ) {
            return $url;
        }
        
        // Return the URL with the format extension
        return $url . '.' . $format;
    }

    /**
     * Replace content URLs
     *
     * @since 1.0.0
     * @param string $content Content.
     * @return string
     */
    public function replace_content_urls( $content ) {
        $settings = Image_Optimization_Settings::get_instance();
        $options = $settings->get_settings();
        
        // Only use URL replacement if enabled
        if ( empty( $options['enable_fallback_replacement'] ) ) {
            return $content;
        }
        
        // Get preferred format based on user settings and browser support
        $format = $this->get_preferred_format( $options['output_format'] );
        
        if ( ! $format ) {
            return $content; // No supported format available
        }
        
        // Replace image URLs in img tags
        $content = preg_replace_callback(
            '/<img([^>]+)src=([\'"])([^\'"]+)([\'"])([^>]*)>/i',
            array( $this, 'replace_img_tag_callback' ),
            $content
        );
        
        // Replace image URLs in srcset attributes
        $content = preg_replace_callback(
            '/srcset=([\'"])([^\'"]+)([\'"])/i',
            array( $this, 'replace_srcset_callback' ),
            $content
        );
        
        return $content;
    }

    /**
     * Replace img tag callback
     *
     * @since 1.0.0
     * @param array $matches Regex matches.
     * @return string
     */
    private function replace_img_tag_callback( $matches ) {
        $new_url = $this->get_next_gen_url( $matches[3] );
        if ( $new_url !== $matches[3] ) {
            return '<img' . $matches[1] . 'src=' . $matches[2] . $new_url . $matches[4] . $matches[5] . '>';
        }
        return $matches[0];
    }

    /**
     * Replace srcset callback
     *
     * @since 1.0.0
     * @param array $matches Regex matches.
     * @return string
     */
    private function replace_srcset_callback( $matches ) {
        $srcset = $matches[2];
        $sources = explode( ',', $srcset );
        $new_sources = array();
        
        foreach ( $sources as $source ) {
            $parts = trim( $source );
            $url_parts = explode( ' ', $parts );
            $url = $url_parts[0];
            $descriptor = isset( $url_parts[1] ) ? ' ' . $url_parts[1] : '';
            
            $new_url = $this->get_next_gen_url( $url );
            $new_sources[] = $new_url . $descriptor;
        }
        
        return 'srcset=' . $matches[1] . implode( ', ', $new_sources ) . $matches[3];
    }

    /**
     * Replace post thumbnail
     *
     * @since 1.0.0
     * @param string $html              The post thumbnail HTML.
     * @param int    $post_id           Post ID.
     * @param int    $post_thumbnail_id Post thumbnail ID.
     * @param string $size              Thumbnail size.
     * @param array  $attr              Query string or array of attributes.
     * @return string
     */
    public function replace_post_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
        $settings = Image_Optimization_Settings::get_instance();
        $options = $settings->get_settings();
        
        // Only use URL replacement if enabled
        if ( empty( $options['enable_fallback_replacement'] ) ) {
            return $html;
        }
        
        // Replace image URLs in img tags
        $html = preg_replace_callback(
            '/<img([^>]+)src=([\'"])([^\'"]+)([\'"])([^>]*)>/i',
            function( $matches ) use ( $post_thumbnail_id ) {
                $new_url = $this->get_next_gen_url( $matches[3], $post_thumbnail_id );
                if ( $new_url !== $matches[3] ) {
                    return '<img' . $matches[1] . 'src=' . $matches[2] . $new_url . $matches[4] . $matches[5] . '>';
                }
                return $matches[0];
            },
            $html
        );
        
        // Replace image URLs in srcset attributes
        $html = preg_replace_callback(
            '/srcset=([\'"])([^\'"]+)([\'"])/i',
            function( $matches ) use ( $post_thumbnail_id ) {
                $srcset = $matches[2];
                $sources = explode( ',', $srcset );
                $new_sources = array();
                
                foreach ( $sources as $source ) {
                    $parts = trim( $source );
                    $url_parts = explode( ' ', $parts );
                    $url = $url_parts[0];
                    $descriptor = isset( $url_parts[1] ) ? ' ' . $url_parts[1] : '';
                    
                    $new_url = $this->get_next_gen_url( $url, $post_thumbnail_id );
                    $new_sources[] = $new_url . $descriptor;
                }
                
                return 'srcset=' . $matches[1] . implode( ', ', $new_sources ) . $matches[3];
            },
            $html
        );
        
        return $html;
    }

    /**
     * Get recommended .htaccess rules for WebP serving
     *
     * @since 1.0.0
     * @return string
     */
    public function get_htaccess_rules() {
        return '
# BEGIN Image Optimization WebP Rules
# These rules serve WebP images while keeping original URLs
# Example: /image.jpg stays /image.jpg but serves WebP content
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Only handle real JPG/PNG files on disk
    RewriteCond %{REQUEST_FILENAME} -f
    
    # Browser supports WebP
    RewriteCond %{HTTP_ACCEPT} "image/webp" [NC]
    
    # Only for .jpg/.jpeg/.png requests
    RewriteCond %{REQUEST_URI} \.(jpe?g|png)$ [NC]
    
    # WebP version exists (e.g., file.jpg.webp)
    RewriteCond %{REQUEST_FILENAME}\.webp -f
    
    # Serve the WebP with correct MIME
    # [L] = Last rule (prevents other rules from overriding)
    # [T] = Set MIME type to image/webp (automatically sets Content-Type)
    # [E] = Set environment variable for Vary header
    RewriteRule ^(.+)\.(jpe?g|png)$ $1.$2.webp [L,T=image/webp,E=accept:1]
</IfModule>

<IfModule mod_headers.c>
    # Vary by Accept only when we redirected
    Header append Vary Accept env=REDIRECT_accept
</IfModule>

<IfModule mod_mime.c>
    # Register WebP MIME type
    AddType image/webp .webp
</IfModule>
# END Image Optimization WebP Rules';
    }

    /**
     * Check if .htaccess rules are present
     *
     * @since 1.0.0
     * @return array
     */
    public function check_htaccess_rules() {
        $htaccess_file = ABSPATH . '.htaccess';
        
        if ( ! file_exists( $htaccess_file ) ) {
            return array(
                'exists' => false,
                'message' => __( '.htaccess file not found', 'improve-image-delivery-pagespeed' ),
                'status' => 'error'
            );
        }
        
        if ( ! is_readable( $htaccess_file ) ) {
            return array(
                'exists' => true,
                'message' => __( '.htaccess file exists but is not readable', 'improve-image-delivery-pagespeed' ),
                'status' => 'warning'
            );
        }
        
        $htaccess_content = file_get_contents( $htaccess_file );
        
        if ( false === $htaccess_content ) {
            return array(
                'exists' => true,
                'message' => __( 'Could not read .htaccess file', 'improve-image-delivery-pagespeed' ),
                'status' => 'error'
            );
        }
        
        $has_webp_rules = strpos( $htaccess_content, '# BEGIN Image Optimization WebP Rules' ) !== false;
        
        if ( $has_webp_rules ) {
            // Check if rules have the [L] flag and proper structure
            $has_proper_structure = strpos( $htaccess_content, '[L,T=image/webp,E=accept:1]' ) !== false &&
                                  strpos( $htaccess_content, 'RewriteCond %{REQUEST_FILENAME} -f' ) !== false;
            
            if ( ! $has_proper_structure ) {
                return array(
                    'exists' => true,
                    'has_rules' => true,
                    'message' => __( 'WebP rules present but may be outdated. Consider updating.', 'improve-image-delivery-pagespeed' ),
                    'status' => 'warning'
                );
            }
            
            return array(
                'exists' => true,
                'has_rules' => true,
                'message' => __( 'WebP rules are present and properly configured in .htaccess', 'improve-image-delivery-pagespeed' ),
                'status' => 'success'
            );
        }
        
        return array(
            'exists' => true,
            'has_rules' => false,
            'message' => __( 'WebP rules are not present in .htaccess', 'improve-image-delivery-pagespeed' ),
            'status' => 'info'
        );
    }

    /**
     * Add .htaccess rules for WebP serving
     *
     * @since 1.0.0
     * @return array
     */
    public function add_htaccess_rules() {
        $htaccess_file = ABSPATH . '.htaccess';
        
        if ( ! is_writable( $htaccess_file ) && ! is_writable( ABSPATH ) ) {
            return array(
                'success' => false,
                'message' => __( '.htaccess file is not writable', 'improve-image-delivery-pagespeed' )
            );
        }
        
        $existing_content = '';
        if ( file_exists( $htaccess_file ) ) {
            $existing_content = file_get_contents( $htaccess_file );
            
            // Check if rules already exist
            if ( strpos( $existing_content, '# BEGIN Image Optimization WebP Rules' ) !== false ) {
                return array(
                    'success' => false,
                    'message' => __( 'WebP rules already exist in .htaccess', 'improve-image-delivery-pagespeed' )
                );
            }
        }
        
        $webp_rules = $this->get_htaccess_rules();
        $new_content = $webp_rules . "\n\n" . $existing_content;
        
        if ( file_put_contents( $htaccess_file, $new_content ) !== false ) {
            return array(
                'success' => true,
                'message' => __( 'WebP rules added to .htaccess successfully', 'improve-image-delivery-pagespeed' )
            );
        }
        
        return array(
            'success' => false,
            'message' => __( 'Failed to write to .htaccess file', 'improve-image-delivery-pagespeed' )
        );
    }

    /**
     * Remove .htaccess rules for WebP serving
     *
     * @since 1.0.0
     * @return array
     */
    public function remove_htaccess_rules() {
        $htaccess_file = ABSPATH . '.htaccess';
        
        if ( ! file_exists( $htaccess_file ) ) {
            return array(
                'success' => false,
                'message' => __( '.htaccess file not found', 'improve-image-delivery-pagespeed' )
            );
        }
        
        if ( ! is_writable( $htaccess_file ) ) {
            return array(
                'success' => false,
                'message' => __( '.htaccess file is not writable', 'improve-image-delivery-pagespeed' )
            );
        }
        
        $content = file_get_contents( $htaccess_file );
        
        if ( false === $content ) {
            return array(
                'success' => false,
                'message' => __( 'Could not read .htaccess file', 'improve-image-delivery-pagespeed' )
            );
        }
        
        // Remove the WebP rules block
        $pattern = '/\n?# BEGIN Image Optimization WebP Rules.*?# END Image Optimization WebP Rules\n?/s';
        $new_content = preg_replace( $pattern, '', $content );
        
        if ( $new_content === $content ) {
            return array(
                'success' => false,
                'message' => __( 'WebP rules not found in .htaccess', 'improve-image-delivery-pagespeed' )
            );
        }
        
        if ( file_put_contents( $htaccess_file, $new_content ) !== false ) {
            return array(
                'success' => true,
                'message' => __( 'WebP rules removed from .htaccess successfully', 'improve-image-delivery-pagespeed' )
            );
        }
        
        return array(
            'success' => false,
            'message' => __( 'Failed to write to .htaccess file', 'improve-image-delivery-pagespeed' )
        );
    }
}

endif; // End class_exists check