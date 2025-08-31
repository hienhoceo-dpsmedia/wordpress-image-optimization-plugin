<?php
/**
 * Dashboard view template
 *
 * @package ImageOptimization
 * @since 2.1.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$settings = Image_Optimization_Settings::get_instance();
$scan_nonce = wp_create_nonce( $settings->get_nonce_action( 'scan' ) );
$convert_nonce = wp_create_nonce( $settings->get_nonce_action( 'convert' ) );
$save_nonce = wp_create_nonce( $settings->get_nonce_action( 'save' ) );
$export_nonce = wp_create_nonce( $settings->get_nonce_action( 'export' ) );
?>
<div class="wrap">
    <h1><?php esc_html_e( 'Improve Image Delivery PageSpeed', 'improve-image-delivery-pagespeed' ); ?></h1>
    
    <!-- Language Selector -->
    <div class="image-optimization-language-selector" style="float: right; margin: -40px 0 20px 0;">
        <label for="plugin-language-selector" style="font-size: 13px; color: #666;">
            🌍 <?php esc_html_e( 'Language:', 'improve-image-delivery-pagespeed' ); ?>
        </label>
        <select id="plugin-language-selector" style="margin-left: 8px; font-size: 13px;">
            <option value="vi_VN" <?php selected( get_locale(), 'vi_VN' ); ?>>🇻🇳 Tiếng Việt</option>
            <option value="en_US" <?php selected( get_locale(), 'en_US' ); ?>>🇺🇸 English</option>
        </select>
        <p style="font-size: 12px; color: #888; margin: 4px 0 0; text-align: right;">
            <?php esc_html_e( 'Note: This plugin defaults to Vietnamese. To permanently change language, update your WordPress admin language in Settings → General.', 'improve-image-delivery-pagespeed' ); ?>
        </p>
    </div>
    <div style="clear: both;"></div>
    
    <div class="image-optimization-info-banner">
        <h2>🚀 <?php esc_html_e( 'Boost Your PageSpeed Insights Score & Core Web Vitals', 'improve-image-delivery-pagespeed' ); ?></h2>
        <p><?php esc_html_e( 'Automatically convert your JPEG/PNG images to modern WebP/AVIF formats to reduce download time, improve perceived page load performance, and enhance your Largest Contentful Paint (LCP) scores for better Core Web Vitals.', 'improve-image-delivery-pagespeed' ); ?></p>
        
        <h3>📊 <?php esc_html_e( 'PageSpeed Optimization Benefits', 'improve-image-delivery-pagespeed' ); ?></h3>
        <ul style="list-style: disc; padding-left: 20px; margin: 10px 0;">
            <li><strong>🎯 <?php esc_html_e( 'Improve LCP Score', 'improve-image-delivery-pagespeed' ); ?></strong> – <?php esc_html_e( 'Reduce image file sizes by 25-50% to speed up largest image loading times.', 'improve-image-delivery-pagespeed' ); ?></li>
            <li><strong>⚡ <?php esc_html_e( 'Faster Page Loads', 'improve-image-delivery-pagespeed' ); ?></strong> – <?php esc_html_e( 'Smaller images mean faster download times and better user experience.', 'improve-image-delivery-pagespeed' ); ?></li>
            <li><strong>📱 <?php esc_html_e( 'Mobile Performance', 'improve-image-delivery-pagespeed' ); ?></strong> – <?php esc_html_e( 'Especially beneficial for mobile users with slower connections.', 'improve-image-delivery-pagespeed' ); ?></li>
            <li><strong>🔧 <?php esc_html_e( 'No Server Overload', 'improve-image-delivery-pagespeed' ); ?></strong> – <?php esc_html_e( 'Convert images on-demand without background processing that slows your site.', 'improve-image-delivery-pagespeed' ); ?></li>
            <li><strong>📈 <?php esc_html_e( 'SEO Benefits', 'improve-image-delivery-pagespeed' ); ?></strong> – <?php esc_html_e( 'Better PageSpeed scores can improve your search engine rankings.', 'improve-image-delivery-pagespeed' ); ?></li>
        </ul>
        
        <p><em>💡 <?php esc_html_e( 'Perfect for website owners who want to optimize their PageSpeed Insights scores and improve Core Web Vitals performance with complete control over image conversion.', 'improve-image-delivery-pagespeed' ); ?></em></p>
        
        <!-- Language Information -->
        <div style="margin-top: 16px; padding: 12px; background: #e8f4fd; border-radius: 6px; font-size: 13px; color: #004085;">
            🌍 <strong><?php esc_html_e( 'Language Support', 'improve-image-delivery-pagespeed' ); ?>:</strong> <?php esc_html_e( 'This plugin prioritizes Vietnamese language with English fallback.', 'improve-image-delivery-pagespeed' ); ?>
        </div>
    </div>
    
    <!-- Getting Started Section -->
    <div class="image-optimization-getting-started" style="background: #f9f9f9; border: 1px solid #ddd; border-radius: 5px; padding: 20px; margin: 20px 0;">
        <h2>🚀 <?php esc_html_e( 'Quick PageSpeed Optimization - 3 Steps', 'improve-image-delivery-pagespeed' ); ?></h2>
        <p><?php esc_html_e( 'Follow these steps to boost your PageSpeed Insights score and improve Core Web Vitals:', 'improve-image-delivery-pagespeed' ); ?></p>
        
        <div class="image-optimization-steps" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin: 32px 0;">
            <div class="step-card" style="background: white; padding: 24px; border-radius: var(--border-radius); box-shadow: var(--shadow-sm); transition: var(--transition); position: relative; overflow: hidden;">
                <div class="step-number" style="background: #0073aa; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 18px; margin-bottom: 16px; box-shadow: var(--shadow-sm);">1</div>
                <h3>📊 <?php esc_html_e( 'Scan for Optimization Opportunities', 'improve-image-delivery-pagespeed' ); ?></h3>
                <p><?php esc_html_e( 'Automatically finds all JPEG/PNG images that can be optimized to improve your PageSpeed score.', 'improve-image-delivery-pagespeed' ); ?></p>
            </div>
            
            <div class="step-card" style="background: white; padding: 24px; border-radius: var(--border-radius); box-shadow: var(--shadow-sm); transition: var(--transition); position: relative; overflow: hidden;">
                <div class="step-number" style="background: #00a32a; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 18px; margin-bottom: 16px; box-shadow: var(--shadow-sm);">2</div>
                <h3>⚡ <?php esc_html_e( 'Convert to Modern Formats', 'improve-image-delivery-pagespeed' ); ?></h3>
                <p><?php esc_html_e( 'Converts images to WebP/AVIF formats for faster loading, smaller file sizes, and better LCP scores.', 'improve-image-delivery-pagespeed' ); ?></p>
            </div>
            
            <div class="step-card" style="background: white; padding: 24px; border-radius: var(--border-radius); box-shadow: var(--shadow-sm); transition: var(--transition); position: relative; overflow: hidden;">
                <div class="step-number" style="background: #d63638; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 18px; margin-bottom: 16px; box-shadow: var(--shadow-sm);">3</div>
                <h3>🎯 <?php esc_html_e( 'Automatic Performance Setup', 'improve-image-delivery-pagespeed' ); ?></h3>
                <div style="font-size: 14px;">
                    <p><strong><?php esc_html_e( 'Automatically handles:', 'improve-image-delivery-pagespeed' ); ?></strong></p>
                    <ul style="margin: 10px 0; padding-left: 20px; font-size: 13px;">
                        <li><?php esc_html_e( 'Adds .htaccess rules if needed', 'improve-image-delivery-pagespeed' ); ?></li>
                        <li><?php esc_html_e( 'Shows LiteSpeed Cache settings', 'improve-image-delivery-pagespeed' ); ?></li>
                        <li><?php esc_html_e( 'Recommends turning off conflicting optimizations', 'improve-image-delivery-pagespeed' ); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="image-optimization-start-action" style="text-align: center; margin-top: 32px; padding: 24px; background: linear-gradient(135deg, var(--bg-light) 0%, #ffffff 100%); border-radius: var(--border-radius); border: 1px solid var(--border-light);">
            <button id="image-optimization-scan-main" class="button button-primary button-hero" style="font-size: 18px; padding: 16px 32px;">
                🚀 <?php esc_html_e( 'Start Complete Optimization', 'improve-image-delivery-pagespeed' ); ?>
            </button>
            
            <!-- Progress Bar - Visible immediately when button is clicked -->
            <div id="image-optimization-progress" class="image-optimization-progress-wrap" style="display: none; margin: 20px auto 0; max-width: 400px;">
                <div class="image-optimization-progress-bar">
                    <div id="image-optimization-bar" class="image-optimization-progress"></div>
                </div>
                <p id="image-optimization-progress-text" style="margin: 8px 0 0; font-size: 14px; color: #666; font-weight: 500;">0% - Preparing...</p>
            </div>
            
            <p style="margin-top: 12px; color: #666; font-size: 14px; font-weight: 500;">
                <?php esc_html_e( 'This will scan images, convert to WebP/AVIF formats, add .htaccess rules (if needed), and show LiteSpeed Cache recommendations. Original images are preserved.', 'improve-image-delivery-pagespeed' ); ?>
            </p>
            <div style="margin-top: 16px; padding: 12px; background: #e8f4fd; border-radius: 6px; font-size: 13px; color: #004085;">
                ✨ <strong><?php esc_html_e( 'Truly One-Click:', 'improve-image-delivery-pagespeed' ); ?></strong> <?php esc_html_e( 'No manual configuration needed - perfect for beginners!', 'improve-image-delivery-pagespeed' ); ?>
            </div>
        </div>
    </div>
    
    <div class="image-optimization-dashboard-container">
        <div class="image-optimization-main-content">
            <div class="image-optimization-card">
                <h2>
                    📈 <?php esc_html_e( 'Optimization Status Dashboard', 'improve-image-delivery-pagespeed' ); ?>
                    <span class="tooltip">
                        ℹ️
                        <span class="tooltiptext"><?php esc_html_e( 'View your current optimization progress and statistics', 'improve-image-delivery-pagespeed' ); ?></span>
                    </span>
                </h2>
                
                <div class="image-optimization-status-section">
                    <h3><?php esc_html_e( 'Optimization Status', 'improve-image-delivery-pagespeed' ); ?></h3>
                    <div class="image-optimization-status-grid">
                        <div class="image-optimization-status-item">
                            <div class="image-optimization-status-number"><?php echo esc_html( $analytics['total_images'] ); ?></div>
                            <div class="image-optimization-status-label"><?php esc_html_e( 'Total Images', 'improve-image-delivery-pagespeed' ); ?></div>
                        </div>
                        <div class="image-optimization-status-item">
                            <div class="image-optimization-status-number"><?php echo esc_html( $analytics['optimized_images'] ); ?></div>
                            <div class="image-optimization-status-label"><?php esc_html_e( 'Optimized', 'improve-image-delivery-pagespeed' ); ?></div>
                        </div>
                        <div class="image-optimization-status-item">
                            <div class="image-optimization-status-number"><?php echo esc_html( $analytics['pending_images'] ); ?></div>
                            <div class="image-optimization-status-label"><?php esc_html_e( 'Pending', 'improve-image-delivery-pagespeed' ); ?></div>
                        </div>
                        <div class="image-optimization-status-item">
                            <div class="image-optimization-status-number"><?php echo esc_html( $analytics['space_saved'] ); ?> MB</div>
                            <div class="image-optimization-status-label"><?php esc_html_e( 'Space Saved', 'improve-image-delivery-pagespeed' ); ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="image-optimization-storage-section">
                    <h3><?php esc_html_e( 'Storage Optimization', 'improve-image-delivery-pagespeed' ); ?></h3>
                    <div class="image-optimization-storage-info">
                        <p><?php esc_html_e( 'Last optimized:', 'improve-image-delivery-pagespeed' ); ?> <strong><?php echo esc_html( $analytics['last_optimized'] ); ?></strong></p>
                        <p><?php esc_html_e( 'Format:', 'improve-image-delivery-pagespeed' ); ?> <strong>WebP/AVIF</strong></p>
                        <p><?php esc_html_e( 'Original images:', 'improve-image-delivery-pagespeed' ); ?> <strong><?php echo $options['optimize_original'] ? esc_html__( 'Enabled', 'improve-image-delivery-pagespeed' ) : esc_html__( 'Disabled', 'improve-image-delivery-pagespeed' ); ?></strong></p>
                        <p><?php esc_html_e( 'Thumbnail images:', 'improve-image-delivery-pagespeed' ); ?> <strong><?php echo $options['optimize_thumbnails'] ? esc_html__( 'Enabled', 'improve-image-delivery-pagespeed' ) : esc_html__( 'Disabled', 'improve-image-delivery-pagespeed' ); ?></strong></p>
                    </div>
                </div>
                
                <div class="image-optimization-sizes-section">
                    <h3><?php esc_html_e( 'Image Thumbnail Sizes', 'improve-image-delivery-pagespeed' ); ?></h3>
                    <div class="image-optimization-sizes-grid">
                        <?php if ( $options['optimize_original'] ) : ?>
                            <div class="image-optimization-size-item">
                                <div class="image-optimization-size-name"><?php esc_html_e( 'Original', 'improve-image-delivery-pagespeed' ); ?></div>
                                <div class="image-optimization-size-status"><?php esc_html_e( 'Ready', 'improve-image-delivery-pagespeed' ); ?></div>
                            </div>
                        <?php endif; ?>
                        <?php if ( $options['optimize_thumbnails'] ) : ?>
                            <?php foreach ( $image_sizes as $size ) : ?>
                                <div class="image-optimization-size-item">
                                    <div class="image-optimization-size-name"><?php echo esc_html( $size ); ?></div>
                                    <div class="image-optimization-size-status"><?php esc_html_e( 'Ready', 'improve-image-delivery-pagespeed' ); ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <?php if ( ! $options['optimize_original'] && ! $options['optimize_thumbnails'] ) : ?>
                        <p><em><?php esc_html_e( 'No image sizes selected for optimization. Please enable at least one option in settings.', 'improve-image-delivery-pagespeed' ); ?></em></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="image-optimization-card">
                <h2>
                    ⚙️ <?php esc_html_e( 'Optimization Settings', 'improve-image-delivery-pagespeed' ); ?>
                    <span class="tooltip">
                        ℹ️
                        <span class="tooltiptext"><?php esc_html_e( 'Configure which images to optimize and quality settings', 'improve-image-delivery-pagespeed' ); ?></span>
                    </span>
                </h2>
                
                <form method="post" action="">
                    <?php wp_nonce_field( $settings->get_nonce_action( 'save' ) ); ?>
                    <input type="hidden" name="save_settings" value="1">
                    
                    <h3>
                        📁 <?php esc_html_e( 'Image Size Options', 'improve-image-delivery-pagespeed' ); ?>
                        <span class="tooltip">
                            ℹ️
                            <span class="tooltiptext"><?php esc_html_e( 'Choose which image sizes to convert. Original images are your uploaded files, thumbnails are automatically generated smaller versions.', 'improve-image-delivery-pagespeed' ); ?></span>
                        </span>
                    </h3>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Optimize Original Images', 'improve-image-delivery-pagespeed' ); ?></th>
                            <td>
                                <label>
                                    <input name="optimize_original" type="checkbox" value="1" <?php checked( ! empty( $options['optimize_original'] ) ); ?> />
                                    <?php esc_html_e( 'Convert the original uploaded image files', 'improve-image-delivery-pagespeed' ); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Optimize Thumbnail Images', 'improve-image-delivery-pagespeed' ); ?></th>
                            <td>
                                <label>
                                    <input name="optimize_thumbnails" type="checkbox" value="1" <?php checked( ! empty( $options['optimize_thumbnails'] ) ); ?> />
                                    <?php
                                    $thumbnail_text = sprintf(
                                        /* translators: %1$d: number of sizes, %2$s: size names */
                                        esc_html__( 'Convert all thumbnail size variants (%1$d sizes: %2$s)', 'improve-image-delivery-pagespeed' ),
                                        count( $image_sizes ),
                                        implode( ', ', array_slice( $image_sizes, 0, 3 ) ) . ( count( $image_sizes ) > 3 ? '...' : '' )
                                    );
                                    echo $thumbnail_text;
                                    ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                    
                    <h3>
                        🎨 <?php esc_html_e( 'Conversion Settings', 'improve-image-delivery-pagespeed' ); ?>
                        <span class="tooltip">
                            ℹ️
                            <span class="tooltiptext"><?php esc_html_e( 'Control quality and minimum size thresholds for conversion. Higher quality = larger files but better image quality.', 'improve-image-delivery-pagespeed' ); ?></span>
                        </span>
                    </h3>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <?php esc_html_e( 'Conversion quality', 'improve-image-delivery-pagespeed' ); ?>
                                <span class="tooltip">
                                    ℹ️
                                    <span class="tooltiptext"><?php esc_html_e( 'Recommended: 80. Higher values = better quality but larger files. Lower values = smaller files but reduced quality.', 'improve-image-delivery-pagespeed' ); ?></span>
                                </span>
                            </th>
                            <td>
                                <input name="quality" type="number" min="0" max="100" value="<?php echo esc_attr( $options['quality'] ); ?>" />
                                <p class="description"><?php esc_html_e( '0–100, default 80', 'improve-image-delivery-pagespeed' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Min width (px)', 'improve-image-delivery-pagespeed' ); ?></th>
                            <td><input name="min_w" type="number" min="0" value="<?php echo esc_attr( $options['min_w'] ); ?>" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Min height (px)', 'improve-image-delivery-pagespeed' ); ?></th>
                            <td><input name="min_h" type="number" min="0" value="<?php echo esc_attr( $options['min_h'] ); ?>" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Min file size (KB)', 'improve-image-delivery-pagespeed' ); ?></th>
                            <td><input name="min_kb" type="number" min="0" value="<?php echo esc_attr( $options['min_kb'] ); ?>" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Force include small images', 'improve-image-delivery-pagespeed' ); ?></th>
                            <td>
                                <label>
                                    <input name="force_all" type="checkbox" value="1" <?php checked( ! empty( $options['force_all'] ) ); ?> />
                                    <?php esc_html_e( 'Ignore thresholds when scanning/converting', 'improve-image-delivery-pagespeed' ); ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                    
                    <h3>
                        🔧 <?php esc_html_e( 'Output Format Selection', 'improve-image-delivery-pagespeed' ); ?>
                        <span class="tooltip">
                            ℹ️
                            <span class="tooltiptext"><?php esc_html_e( 'Choose which next-generation image formats to convert your images to. The selection depends on your server capabilities and browser support.', 'improve-image-delivery-pagespeed' ); ?></span>
                        </span>
                    </h3>
                    
                    <?php
                    // Get server capabilities
                    $converter = Image_Optimization_Converter::get_instance();
                    $capabilities = $converter->get_server_capabilities();
                    ?>
                    
                    <!-- Server Capabilities Display -->
                    <div class="server-capabilities" style="background: #f9f9f9; border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin: 15px 0;">
                        <h4 style="margin-top: 0;"><?php esc_html_e( 'Server Conversion Capabilities', 'improve-image-delivery-pagespeed' ); ?></h4>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                            
                            <!-- Imagick Status -->
                            <div class="capability-item">
                                <h5><?php esc_html_e( 'ImageMagick Extension', 'improve-image-delivery-pagespeed' ); ?></h5>
                                <p>
                                    <span class="status-indicator status-<?php echo $capabilities['imagick']['available'] ? 'success' : 'error'; ?>">
                                        <?php echo $capabilities['imagick']['available'] ? '✅' : '❌'; ?>
                                    </span>
                                    <?php echo $capabilities['imagick']['available'] ? esc_html__( 'Available', 'improve-image-delivery-pagespeed' ) : esc_html__( 'Not Available', 'improve-image-delivery-pagespeed' ); ?>
                                </p>
                                <?php if ( $capabilities['imagick']['available'] ) : ?>
                                    <ul style="margin: 5px 0; padding-left: 20px; font-size: 13px;">
                                        <li>
                                            <span class="status-indicator status-<?php echo $capabilities['imagick']['webp'] ? 'success' : 'error'; ?>">
                                                <?php echo $capabilities['imagick']['webp'] ? '✅' : '❌'; ?>
                                            </span>
                                            <?php esc_html_e( 'WebP Support', 'improve-image-delivery-pagespeed' ); ?>
                                        </li>
                                        <li>
                                            <span class="status-indicator status-<?php echo $capabilities['imagick']['avif'] ? 'success' : 'error'; ?>">
                                                <?php echo $capabilities['imagick']['avif'] ? '✅' : '❌'; ?>
                                            </span>
                                            <?php esc_html_e( 'AVIF Support', 'improve-image-delivery-pagespeed' ); ?>
                                        </li>
                                    </ul>
                                <?php endif; ?>
                            </div>
                            
                            <!-- GD Status -->
                            <div class="capability-item">
                                <h5><?php esc_html_e( 'GD Extension', 'improve-image-delivery-pagespeed' ); ?></h5>
                                <p>
                                    <span class="status-indicator status-<?php echo $capabilities['gd']['available'] ? 'success' : 'error'; ?>">
                                        <?php echo $capabilities['gd']['available'] ? '✅' : '❌'; ?>
                                    </span>
                                    <?php echo $capabilities['gd']['available'] ? esc_html__( 'Available', 'improve-image-delivery-pagespeed' ) : esc_html__( 'Not Available', 'improve-image-delivery-pagespeed' ); ?>
                                </p>
                                <?php if ( $capabilities['gd']['available'] ) : ?>
                                    <ul style="margin: 5px 0; padding-left: 20px; font-size: 13px;">
                                        <li>
                                            <span class="status-indicator status-<?php echo $capabilities['gd']['webp'] ? 'success' : 'error'; ?>">
                                                <?php echo $capabilities['gd']['webp'] ? '✅' : '❌'; ?>
                                            </span>
                                            <?php esc_html_e( 'WebP Support', 'improve-image-delivery-pagespeed' ); ?>
                                        </li>
                                        <li>
                                            <span class="status-indicator status-error">
                                                ❌
                                            </span>
                                            <?php esc_html_e( 'AVIF Support (Not Available in GD)', 'improve-image-delivery-pagespeed' ); ?>
                                        </li>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ( ! $capabilities['imagick']['available'] && ! $capabilities['gd']['available'] ) : ?>
                            <div class="notice notice-error" style="margin: 10px 0;">
                                <p><strong><?php esc_html_e( 'No Image Conversion Library Available!', 'improve-image-delivery-pagespeed' ); ?></strong></p>
                                <p><?php esc_html_e( 'Neither ImageMagick nor GD extension is available on your server. Please contact your hosting provider to install one of these extensions.', 'improve-image-delivery-pagespeed' ); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Format Selection -->
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <?php esc_html_e( 'Output Format', 'improve-image-delivery-pagespeed' ); ?>
                                <span class="tooltip">
                                    ℹ️
                                    <span class="tooltiptext"><?php esc_html_e( 'Choose which modern image format(s) to convert your images to. AVIF provides better compression but requires ImageMagick with AVIF support.', 'improve-image-delivery-pagespeed' ); ?></span>
                                </span>
                            </th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text"><?php esc_html_e( 'Output Format', 'improve-image-delivery-pagespeed' ); ?></legend>
                                    
                                    <!-- WebP Option -->
                                    <label style="display: block; margin-bottom: 10px;">
                                        <input name="output_format" type="radio" value="webp" <?php checked( $options['output_format'], 'webp' ); ?>
                                               <?php echo ( ! $capabilities['imagick']['webp'] && ! $capabilities['gd']['webp'] ) ? 'disabled' : ''; ?> />
                                        <strong><?php esc_html_e( 'WebP Only', 'improve-image-delivery-pagespeed' ); ?></strong>
                                        <?php if ( $capabilities['imagick']['webp'] || $capabilities['gd']['webp'] ) : ?>
                                            <span style="color: #46b450;">✅ <?php esc_html_e( 'Available', 'improve-image-delivery-pagespeed' ); ?></span>
                                        <?php else : ?>
                                            <span style="color: #dc3232;">❌ <?php esc_html_e( 'Not Available', 'improve-image-delivery-pagespeed' ); ?></span>
                                        <?php endif; ?>
                                        <br><span class="description"><?php esc_html_e( 'Convert images to WebP format only. Best compatibility, supported by most modern browsers and servers.', 'improve-image-delivery-pagespeed' ); ?></span>
                                    </label>
                                    
                                    <!-- AVIF Option -->
                                    <label style="display: block; margin-bottom: 10px;">
                                        <input name="output_format" type="radio" value="avif" <?php checked( $options['output_format'], 'avif' ); ?>
                                               <?php echo ! $capabilities['imagick']['avif'] ? 'disabled' : ''; ?> />
                                        <strong><?php esc_html_e( 'AVIF Only', 'improve-image-delivery-pagespeed' ); ?></strong>
                                        <?php if ( $capabilities['imagick']['avif'] ) : ?>
                                            <span style="color: #46b450;">✅ <?php esc_html_e( 'Available', 'improve-image-delivery-pagespeed' ); ?></span>
                                        <?php else : ?>
                                            <span style="color: #dc3232;">❌ <?php esc_html_e( 'Not Available', 'improve-image-delivery-pagespeed' ); ?></span>
                                        <?php endif; ?>
                                        <br><span class="description"><?php esc_html_e( 'Convert images to AVIF format only. Better compression than WebP but requires ImageMagick with AVIF support.', 'improve-image-delivery-pagespeed' ); ?></span>
                                        <?php if ( ! $capabilities['imagick']['avif'] ) : ?>
                                            <br><small style="color: #dc3232;"><?php esc_html_e( 'Requires ImageMagick with AVIF support. Contact your hosting provider.', 'improve-image-delivery-pagespeed' ); ?></small>
                                        <?php endif; ?>
                                    </label>
                                    
                                    <!-- Both Option -->
                                    <label style="display: block; margin-bottom: 10px;">
                                        <input name="output_format" type="radio" value="both" <?php checked( $options['output_format'], 'both' ); ?>
                                               <?php echo ( ! $capabilities['imagick']['webp'] && ! $capabilities['gd']['webp'] ) ? 'disabled' : ''; ?> />
                                        <strong><?php esc_html_e( 'Both WebP and AVIF', 'improve-image-delivery-pagespeed' ); ?></strong>
                                        <?php if ( ( $capabilities['imagick']['webp'] || $capabilities['gd']['webp'] ) && $capabilities['imagick']['avif'] ) : ?>
                                            <span style="color: #46b450;">✅ <?php esc_html_e( 'Fully Available', 'improve-image-delivery-pagespeed' ); ?></span>
                                        <?php elseif ( $capabilities['imagick']['webp'] || $capabilities['gd']['webp'] ) : ?>
                                            <span style="color: #ffb900;">⚠️ <?php esc_html_e( 'WebP Only (AVIF not available)', 'improve-image-delivery-pagespeed' ); ?></span>
                                        <?php else : ?>
                                            <span style="color: #dc3232;">❌ <?php esc_html_e( 'Not Available', 'improve-image-delivery-pagespeed' ); ?></span>
                                        <?php endif; ?>
                                        <br><span class="description"><?php esc_html_e( 'Convert to both formats for maximum compatibility and performance. Browsers will automatically choose the best supported format.', 'improve-image-delivery-pagespeed' ); ?></span>
                                        <?php if ( ! $capabilities['imagick']['avif'] && ( $capabilities['imagick']['webp'] || $capabilities['gd']['webp'] ) ) : ?>
                                            <br><small style="color: #ffb900;"><?php esc_html_e( 'Note: Only WebP will be generated since AVIF is not available on your server.', 'improve-image-delivery-pagespeed' ); ?></small>
                                        <?php endif; ?>
                                    </label>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                    
                    <h3><?php esc_html_e( 'WebP/AVIF Serving Options', 'improve-image-delivery-pagespeed' ); ?></h3>
                    <?php if ( $is_litespeed_active ) : ?>
                        <div class="notice notice-info" style="margin: 15px 0; padding: 12px;">
                            <p><strong><?php esc_html_e( 'LiteSpeed Cache Detected:', 'improve-image-delivery-pagespeed' ); ?></strong> <?php esc_html_e( 'You have multiple options to serve WebP/AVIF images. Choose the method that works best for your setup:', 'improve-image-delivery-pagespeed' ); ?></p>
                            <ul style="margin: 10px 0; padding-left: 20px;">
                                <li><strong><?php esc_html_e( 'Option 1 (Recommended):', 'improve-image-delivery-pagespeed' ); ?></strong> <?php esc_html_e( 'Use LiteSpeed Cache WebP replacement feature', 'improve-image-delivery-pagespeed' ); ?></li>
                                <li><strong><?php esc_html_e( 'Option 2:', 'improve-image-delivery-pagespeed' ); ?></strong> <?php esc_html_e( 'Use .htaccess rules below for direct server-level serving', 'improve-image-delivery-pagespeed' ); ?></li>
                                <li><strong><?php esc_html_e( 'Option 3:', 'improve-image-delivery-pagespeed' ); ?></strong> <?php esc_html_e( 'Use URL replacement below for PHP-level serving', 'improve-image-delivery-pagespeed' ); ?></li>
                            </ul>
                        </div>
                    <?php else : ?>
                        <div class="notice notice-warning" style="margin: 15px 0; padding: 12px;">
                            <p><strong><?php esc_html_e( 'No LiteSpeed Cache Detected:', 'improve-image-delivery-pagespeed' ); ?></strong> <?php esc_html_e( 'Use the options below to serve WebP/AVIF images to your visitors:', 'improve-image-delivery-pagespeed' ); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <h4><?php esc_html_e( 'URL Replacement Method', 'improve-image-delivery-pagespeed' ); ?></h4>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Enable URL Replacement', 'improve-image-delivery-pagespeed' ); ?></th>
                            <td>
                                <label>
                                    <input name="enable_fallback_replacement" type="checkbox" value="1" <?php checked( ! empty( $options['enable_fallback_replacement'] ) ); ?> />
                                    <?php esc_html_e( 'Replace image URLs with WebP/AVIF versions in your site\'s HTML', 'improve-image-delivery-pagespeed' ); ?>
                                </label>
                                <p class="description">
                                    <?php if ( $is_litespeed_active ) : ?>
                                        <?php esc_html_e( 'This method works alongside LiteSpeed Cache. You can use this instead of or in addition to LiteSpeed\'s WebP replacement feature.', 'improve-image-delivery-pagespeed' ); ?><br>
                                        <strong><?php esc_html_e( 'Note:', 'improve-image-delivery-pagespeed' ); ?></strong> <?php esc_html_e( 'If you prefer LiteSpeed\'s method, leave this unchecked and configure LiteSpeed Cache WebP replacement instead.', 'improve-image-delivery-pagespeed' ); ?>
                                    <?php else : ?>
                                        <?php esc_html_e( 'This option replaces image URLs in HTML output with WebP/AVIF versions when available. For better performance, consider using .htaccess rules below.', 'improve-image-delivery-pagespeed' ); ?><br>
                                        <strong><?php esc_html_e( 'Note:', 'improve-image-delivery-pagespeed' ); ?></strong> <?php esc_html_e( '.htaccess rules below provide better performance by serving WebP content while keeping original URLs and proper Content-Type headers.', 'improve-image-delivery-pagespeed' ); ?>
                                    <?php endif; ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    
                    <h4><?php esc_html_e( '.htaccess Rules Method', 'improve-image-delivery-pagespeed' ); ?></h4>
                    <?php
                    $core = Image_Optimization_Core::get_instance();
                    $htaccess_status = $core->check_htaccess_rules();
                    ?>
                    <div class="image-optimization-htaccess-section">
                        <div class="htaccess-status htaccess-<?php echo esc_attr( $htaccess_status['status'] ); ?>">
                            <strong><?php esc_html_e( 'Status:', 'improve-image-delivery-pagespeed' ); ?></strong> <?php echo esc_html( $htaccess_status['message'] ); ?>
                        </div>
                        
                        <?php if ( $is_litespeed_active ) : ?>
                            <div class="notice notice-info" style="margin: 10px 0; padding: 8px;">
                                <p><strong><?php esc_html_e( 'LiteSpeed Cache Users:', 'improve-image-delivery-pagespeed' ); ?></strong> <?php esc_html_e( 'These .htaccess rules work independently of LiteSpeed Cache. You can use them for direct server-level WebP serving or stick with LiteSpeed\'s WebP replacement feature.', 'improve-image-delivery-pagespeed' ); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="htaccess-actions" style="margin: 15px 0;">
                            <?php if ( empty( $htaccess_status['has_rules'] ) ) : ?>
                                <button type="button" id="add-htaccess-rules" class="button button-secondary">
                                    <?php esc_html_e( 'Add .htaccess Rules', 'improve-image-delivery-pagespeed' ); ?>
                                </button>
                            <?php else : ?>
                                <button type="button" id="remove-htaccess-rules" class="button button-secondary">
                                    <?php esc_html_e( 'Remove .htaccess Rules', 'improve-image-delivery-pagespeed' ); ?>
                                </button>
                            <?php endif; ?>
                            <button type="button" id="preview-htaccess-rules" class="button">
                                <?php esc_html_e( 'Preview Rules', 'improve-image-delivery-pagespeed' ); ?>
                            </button>
                        </div>
                        
                        <div id="htaccess-preview" style="display: none; margin-top: 15px;">
                            <h5><?php esc_html_e( 'Recommended .htaccess Rules:', 'improve-image-delivery-pagespeed' ); ?></h5>
                            <textarea readonly style="width: 100%; height: 200px; font-family: monospace; font-size: 12px;"><?php echo esc_textarea( $core->get_htaccess_rules() ); ?></textarea>
                            <p class="description">
                                <?php esc_html_e( 'These improved rules safely serve WebP images to browsers that support them while keeping original URLs. Key features: only processes real files on disk, case-insensitive matching, and uses proper MIME type handling without forcing headers. The [L] flag ensures these rules take precedence.', 'improve-image-delivery-pagespeed' ); ?>
                            </p>
                        </div>
                    </div>
                    
                    <p class="submit">
                        <button type="submit" class="button button-primary"><?php esc_html_e( 'Save Settings', 'improve-image-delivery-pagespeed' ); ?></button>
                    </p>
                </form>
            </div>
            
            <div class="image-optimization-card">
                <h2>
                    🛠️ <?php esc_html_e( 'Manual Control Center', 'improve-image-delivery-pagespeed' ); ?>
                    <span class="tooltip">
                        ℹ️
                        <span class="tooltiptext"><?php esc_html_e( 'Advanced controls for experienced users who want step-by-step control over the optimization process', 'improve-image-delivery-pagespeed' ); ?></span>
                    </span>
                </h2>
                <p><?php esc_html_e( 'Advanced manual controls for power users. Use these tools for detailed control over the optimization process.', 'improve-image-delivery-pagespeed' ); ?></p>
                
                <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 15px 0;">
                    <h4 style="margin-top: 0;"><?php esc_html_e( 'Step-by-Step Process:', 'improve-image-delivery-pagespeed' ); ?></h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                        <div>
                            <strong>1. <?php esc_html_e( 'Scan', 'improve-image-delivery-pagespeed' ); ?></strong><br>
                            <button id="image-optimization-scan-tools" class="button"><?php esc_html_e( 'Scan Images', 'improve-image-delivery-pagespeed' ); ?></button>
                        </div>
                        <div>
                            <strong>2. <?php esc_html_e( 'Convert', 'improve-image-delivery-pagespeed' ); ?></strong><br>
                            <button id="image-optimization-convert" class="button button-primary" disabled><?php esc_html_e( 'Convert Pending', 'improve-image-delivery-pagespeed' ); ?></button>
                        </div>
                        <div>
                            <strong>3. <?php esc_html_e( 'Manage', 'improve-image-delivery-pagespeed' ); ?></strong><br>
                            <button id="image-optimization-revert-all" class="button button-secondary"><?php esc_html_e( 'Remove All WebP', 'improve-image-delivery-pagespeed' ); ?></button>
                        </div>
                    </div>
                </div>
                
                <div style="border-top: 1px solid #ddd; padding-top: 15px; margin-top: 15px;">
                    <h4><?php esc_html_e( 'Export Reports:', 'improve-image-delivery-pagespeed' ); ?></h4>
                    <p>
                        <button id="image-optimization-export-json" class="button" disabled><?php esc_html_e( 'Export JSON', 'improve-image-delivery-pagespeed' ); ?></button>
                        <button id="image-optimization-export-csv" class="button" disabled><?php esc_html_e( 'Export CSV', 'improve-image-delivery-pagespeed' ); ?></button>
                    </p>
                </div>
                
                <div id="image-optimization-status" style="margin-top:10px;">
                    <p><strong><?php esc_html_e( 'Total JPG/PNG:', 'improve-image-delivery-pagespeed' ); ?></strong> <span id="total">–</span></p>
                    <p><strong><?php esc_html_e( 'Already converted:', 'improve-image-delivery-pagespeed' ); ?></strong> <span id="converted">–</span></p>
                    <p><strong><?php esc_html_e( 'Pending:', 'improve-image-delivery-pagespeed' ); ?></strong> <span id="pending">–</span></p>
                    <p><strong><?php esc_html_e( 'Ignored:', 'improve-image-delivery-pagespeed' ); ?></strong> <span id="ignored">–</span></p>
                    <div id="ignored-reasons" style="margin-top:6px; display:none;">
                        <em><?php esc_html_e( 'Ignored reasons:', 'improve-image-delivery-pagespeed' ); ?></em>
                        <ul style="list-style:disc; padding-left:18px;">
                            <li><?php esc_html_e( 'Too small dimensions:', 'improve-image-delivery-pagespeed' ); ?> <span id="ign-dim">0</span></li>
                            <li><?php esc_html_e( 'Too small file size:', 'improve-image-delivery-pagespeed' ); ?> <span id="ign-size">0</span></li>
                            <li><?php esc_html_e( 'Unreadable/missing path:', 'improve-image-delivery-pagespeed' ); ?> <span id="ign-unr">0</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="image-optimization-sidebar">
            <!-- Quick Help Card for Newbies -->
            <div class="image-optimization-card" style="background: linear-gradient(135deg, #e8f4fd 0%, #ffffff 100%); border-left: 4px solid var(--info-color);">
                <h3>🎆 <?php esc_html_e( 'Quick Help', 'improve-image-delivery-pagespeed' ); ?></h3>
                <div class="help-content">
                    <div class="help-item">
                        <strong>🚀 <?php esc_html_e( 'New User?', 'improve-image-delivery-pagespeed' ); ?></strong>
                        <p><?php esc_html_e( 'Just click the big "Start Complete Optimization" button above. It will handle everything automatically - scan, convert, add .htaccess rules, and show LiteSpeed Cache recommendations!', 'improve-image-delivery-pagespeed' ); ?></p>
                    </div>
                    <div class="help-item">
                        <strong>🤔 <?php esc_html_e( 'What are WebP/AVIF?', 'improve-image-delivery-pagespeed' ); ?></strong>
                        <p><?php esc_html_e( 'WebP and AVIF are modern image formats that reduce file sizes by 25-50% without losing quality. Faster loading = better SEO!', 'improve-image-delivery-pagespeed' ); ?></p>
                    </div>
                    <div class="help-item">
                        <strong>⚙️ <?php esc_html_e( 'Need Settings?', 'improve-image-delivery-pagespeed' ); ?></strong>
                        <p><?php esc_html_e( 'Default settings work great for most sites. Only change if you know what you\'re doing.', 'improve-image-delivery-pagespeed' ); ?></p>
                    </div>
                    <div class="help-item">
                        <strong>🔒 <?php esc_html_e( 'Is it Safe?', 'improve-image-delivery-pagespeed' ); ?></strong>
                        <p><?php esc_html_e( 'Yes! Your original images are never deleted. WebP/AVIF copies are created alongside them.', 'improve-image-delivery-pagespeed' ); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="image-optimization-card">
                <h3><?php esc_html_e( 'Image Information', 'improve-image-delivery-pagespeed' ); ?></h3>
                <div class="image-optimization-info-content">
                    <p><strong><?php esc_html_e( 'Total Images:', 'improve-image-delivery-pagespeed' ); ?></strong> <?php echo esc_html( $analytics['total_images'] ); ?></p>
                    <p><strong><?php esc_html_e( 'Optimized:', 'improve-image-delivery-pagespeed' ); ?></strong> <?php echo esc_html( $analytics['optimized_images'] ); ?></p>
                    <p><strong><?php esc_html_e( 'Pending:', 'improve-image-delivery-pagespeed' ); ?></strong> <?php echo esc_html( $analytics['pending_images'] ); ?></p>
                </div>
            </div>
            
            <div class="image-optimization-card">
                <h3><?php esc_html_e( 'Optimization Summary', 'improve-image-delivery-pagespeed' ); ?></h3>
                <div class="image-optimization-summary-content">
                    <div class="image-optimization-progress-bar">
                        <div class="image-optimization-progress" style="width: <?php echo esc_attr( $analytics['total_images'] > 0 ? ( $analytics['optimized_images'] / $analytics['total_images'] ) * 100 : 0 ); ?>%"></div>
                    </div>
                    <p><?php echo esc_html( $analytics['optimized_images'] ); ?> <?php esc_html_e( 'of', 'improve-image-delivery-pagespeed' ); ?> <?php echo esc_html( $analytics['total_images'] ); ?> <?php esc_html_e( 'images optimized', 'improve-image-delivery-pagespeed' ); ?></p>
                    <p><?php esc_html_e( 'Space saved:', 'improve-image-delivery-pagespeed' ); ?> <?php echo esc_html( $analytics['space_saved'] ); ?> MB</p>
                </div>
            </div>
            
            <div class="image-optimization-card">
                <?php if ( $is_litespeed_active ) : ?>
                    <h3><?php esc_html_e( 'WebP/AVIF Serving Methods', 'improve-image-delivery-pagespeed' ); ?></h3>
                    <div class="image-optimization-tools-content">
                        <p><?php esc_html_e( 'LiteSpeed Cache detected! You have multiple options to serve WebP/AVIF images:', 'improve-image-delivery-pagespeed' ); ?></p>
                        <div style="background: #f9f9f9; padding: 12px; border-radius: 5px; margin: 10px 0;">
                            <p><strong><?php esc_html_e( 'Option 1 - LiteSpeed Method (Recommended):', 'improve-image-delivery-pagespeed' ); ?></strong></p>
                            <ol style="margin: 8px 0; padding-left: 20px;">
                                <li><?php esc_html_e( 'Go to LiteSpeed Cache → Settings → Image Optimization', 'improve-image-delivery-pagespeed' ); ?></li>
                                <li><?php esc_html_e( 'Enable Image WebP Replacement', 'improve-image-delivery-pagespeed' ); ?></li>
                            </ol>
                            <p>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=litespeed-img_optm#settings' ) ); ?>" class="button button-secondary">
                                    <?php esc_html_e( 'Open LiteSpeed Cache Settings', 'improve-image-delivery-pagespeed' ); ?>
                                </a>
                            </p>
                        </div>
                        <p><strong><?php esc_html_e( 'Alternative Options:', 'improve-image-delivery-pagespeed' ); ?></strong></p>
                        <ul style="margin: 8px 0; padding-left: 20px; font-size: 14px;">
                            <li><?php esc_html_e( 'Option 2: Use .htaccess rules (configured above)', 'improve-image-delivery-pagespeed' ); ?></li>
                            <li><?php esc_html_e( 'Option 3: Use URL replacement (configured above)', 'improve-image-delivery-pagespeed' ); ?></li>
                        </ul>
                        <div class="notice notice-info" style="margin: 10px 0; padding: 8px;">
                            <p style="margin: 0;"><strong><?php esc_html_e( 'Important:', 'improve-image-delivery-pagespeed' ); ?></strong> <?php esc_html_e( 'Choose only one method to avoid conflicts. Most users prefer LiteSpeed\'s WebP replacement for better performance.', 'improve-image-delivery-pagespeed' ); ?></p>
                        </div>
                    </div>
                <?php else : ?>
                    <h3><?php esc_html_e( 'WebP/AVIF Serving Options', 'improve-image-delivery-pagespeed' ); ?></h3>
                    <div class="image-optimization-tools-content">
                        <p><?php esc_html_e( 'Use the WebP/AVIF serving options configured above:', 'improve-image-delivery-pagespeed' ); ?></p>
                        <ul style="margin: 10px 0; padding-left: 20px;">
                            <li><strong><?php esc_html_e( 'Option 1 (Recommended):', 'improve-image-delivery-pagespeed' ); ?></strong> <?php esc_html_e( '.htaccess rules for server-level serving', 'improve-image-delivery-pagespeed' ); ?></li>
                            <li><strong><?php esc_html_e( 'Option 2:', 'improve-image-delivery-pagespeed' ); ?></strong> <?php esc_html_e( 'URL replacement for PHP-level serving', 'improve-image-delivery-pagespeed' ); ?></li>
                        </ul>
                        <div class="notice notice-warning">
                            <p><strong><?php esc_html_e( 'No LiteSpeed Cache Detected:', 'improve-image-delivery-pagespeed' ); ?></strong> <?php esc_html_e( 'Consider installing LiteSpeed Cache for better performance and caching.', 'improve-image-delivery-pagespeed' ); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Plugin Footer with Author Benefits -->
<div class="image-optimization-footer">
    <div class="footer-content">
        <div class="footer-branding">
            <p style="margin: 0;"><strong><?php esc_html_e( 'Improve Image Delivery PageSpeed', 'improve-image-delivery-pagespeed' ); ?></strong> - <?php esc_html_e( 'Developed by', 'improve-image-delivery-pagespeed' ); ?> <strong>HỒ QUANG HIỂN</strong> <?php esc_html_e( 'from', 'improve-image-delivery-pagespeed' ); ?> <a href="https://dps.media/" target="_blank" style="color: var(--primary-color); text-decoration: none; font-weight: 600;"><strong>DPS.MEDIA JSC</strong></a></p>
            <p style="margin: 5px 0 0 0; font-size: 13px; color: #666;"><?php esc_html_e( 'Comprehensive Digital Marketing solutions for SMEs since 2017 | 5,400+ customers served', 'improve-image-delivery-pagespeed' ); ?> | <a href="mailto:marketing@dps.media" style="color: var(--primary-color);">marketing@dps.media</a></p>
        </div>
        <div class="footer-actions">
            <a href="https://wordpress.org/plugins/image-optimization/#reviews" target="_blank" class="button rate-plugin">
                ⭐ <?php esc_html_e( 'Rate Plugin', 'improve-image-delivery-pagespeed' ); ?>
            </a>
            <a href="https://paypal.me/hoquanghien" target="_blank" class="button donate-btn">
                💖 <?php esc_html_e( 'Support Development', 'improve-image-delivery-pagespeed' ); ?>
            </a>
            <a href="https://dps.media/contact" target="_blank" class="button button-secondary">
                💬 <?php esc_html_e( 'Get Support', 'improve-image-delivery-pagespeed' ); ?>
            </a>
        </div>
    </div>
    
    <!-- Success Message for Completed Optimization -->
    <div id="optimization-success" class="success-message" style="display: none; margin-top: 16px;">
        <?php esc_html_e( 'Optimization completed successfully! Consider rating this plugin to help other users discover it.', 'improve-image-delivery-pagespeed' ); ?>
        <a href="https://wordpress.org/plugins/image-optimization/#reviews" target="_blank" style="margin-left: 8px; color: var(--primary-color); font-weight: 600;"><?php esc_html_e( 'Rate Now →', 'improve-image-delivery-pagespeed' ); ?></a>
    </div>
</div>