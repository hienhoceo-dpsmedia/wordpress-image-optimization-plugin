<?php
/**
 * Fix Vietnamese translation syntax errors in image-optimization.php
 */

// Read the current corrupt file
$file_path = dirname(__DIR__) . '/image-optimization.php';
$content = file_get_contents($file_path);

// Find the broken Vietnamese fallbacks section and fix it
$start_pattern = '/\/\*\*\s*\*\s*Load Vietnamese text fallbacks when \.mo file is not available\s*\*\/\s*public function load_vietnamese_fallbacks\(\)\s*{/';
$end_pattern = '/^\s*}\s*$/m';

// Read the clean version from the compiled Vietnamese translation file  
$clean_vietnamese_method = '    /**
     * Load Vietnamese text fallbacks when .mo file is not available
     */
    public function load_vietnamese_fallbacks() {
        // Define comprehensive Vietnamese translations as fallbacks
        $vietnamese_texts = array(
            // Language selector
            \'Language:\' => \'Ngôn ngữ:\',
            
            // Main plugin name and headers  
            \'Improve Image Delivery PageSpeed\' => \'Cải Thiện Tốc Độ Tải Hình Ảnh PageSpeed\',
            \'Boost Your PageSpeed Insights Score & Core Web Vitals\' => \'Tăng Điểm PageSpeed Insights & Core Web Vitals\',
            \'Automatically convert your JPEG/PNG images to modern WebP/AVIF formats to reduce download time, improve perceived page load performance, and enhance your Largest Contentful Paint (LCP) scores for better Core Web Vitals.\' => \'Tự động chuyển đổi hình ảnh JPEG/PNG sang định dạng WebP/AVIF hiện đại để giảm thời gian tải xuống, cải thiện hiệu suất tải trang cảm nhận và nâng cao điểm Largest Contentful Paint (LCP) cho Core Web Vitals tốt hơn.\',
            \'Note: This plugin defaults to Vietnamese. To permanently change language, update your WordPress admin language in Settings → General.\' => \'Lưu ý: Plugin này mặc định là tiếng Việt. Để thay đổi ngôn ngữ vĩnh viễn, cập nhật ngôn ngữ quản trị WordPress trong Cài đặt → Chung.\',
            
            // Benefits section
            \'PageSpeed Optimization Benefits\' => \'Lợi Ích Tối Ưu PageSpeed\',
            \'Improve LCP Score\' => \'Cải Thiện Điểm LCP\',
            \'Reduce image file sizes by 25-50% to speed up largest image loading times.\' => \'Giảm kích thước file hình ảnh 25-50% để tăng tốc thời gian tải hình ảnh lớn nhất.\',
            \'Faster Page Loads\' => \'Tải Trang Nhanh Hơn\',
            \'Smaller images mean faster download times and better user experience.\' => \'Hình ảnh nhỏ hơn có nghĩa là thời gian tải xuống nhanh hơn và trải nghiệm người dùng tốt hơn.\',
            \'Mobile Performance\' => \'Hiệu Suất Mobile\',
            \'Especially beneficial for mobile users with slower connections.\' => \'Đặc biệt có lợi cho người dùng di động với kết nối chậm hơn.\',
            \'No Server Overload\' => \'Không Quá Tải Máy Chủ\',
            \'Convert images on-demand without background processing that slows your site.\' => \'Chuyển đổi hình ảnh theo yêu cầu mà không có xử lý nền làm chậm trang web của bạn.\',
            \'SEO Benefits\' => \'Lợi Ích SEO\',
            \'Better PageSpeed scores can improve your search engine rankings.\' => \'Điểm PageSpeed tốt hơn có thể cải thiện thứ hạng công cụ tìm kiếm của bạn.\',
            \'Perfect for website owners who want to optimize their PageSpeed Insights scores and improve Core Web Vitals performance with complete control over image conversion.\' => \'Hoàn hảo cho chủ sở hữu trang web muốn tối ưu hóa điểm PageSpeed Insights và cải thiện hiệu suất Core Web Vitals với quyền kiểm soát hoàn toàn việc chuyển đổi hình ảnh.\',
            \'Language Support\' => \'Hỗ Trợ Ngôn Ngữ\',
            \'This plugin prioritizes Vietnamese language with English fallback.\' => \'Plugin này ưu tiên tiếng Việt với tiếng Anh làm dự phòng.\',
            
            // Quick steps section
            \'Quick PageSpeed Optimization - 3 Steps\' => \'Tối Ưu PageSpeed Nhanh - 3 Bước\',
            \'Follow these steps to boost your PageSpeed Insights score and improve Core Web Vitals:\' => \'Thực hiện các bước này để tăng điểm PageSpeed Insights và cải thiện Core Web Vitals:\',
            \'Scan for Optimization Opportunities\' => \'Quét Tìm Cơ Hội Tối Ưu\',
            \'Automatically finds all JPEG/PNG images that can be optimized to improve your PageSpeed score.\' => \'Tự động tìm tất cả hình ảnh JPEG/PNG có thể được tối ưu hóa để cải thiện điểm PageSpeed của bạn.\',
            \'Convert to Modern Formats\' => \'Chuyển Đổi Sang Định Dạng Hiện Đại\',
            \'Converts images to WebP/AVIF formats for faster loading, smaller file sizes, and better LCP scores.\' => \'Chuyển đổi hình ảnh sang định dạng WebP/AVIF để tải nhanh hơn, kích thước file nhỏ hơn và điểm LCP tốt hơn.\',
            \'Automatic Performance Setup\' => \'Thiết Lập Hiệu Suất Tự Động\',
            \'Automatically handles:\' => \'Tự động xử lý:\',
            \'Adds .htaccess rules if needed\' => \'Thêm quy tắc .htaccess nếu cần\',
            \'Shows LiteSpeed Cache settings\' => \'Hiển thị cài đặt LiteSpeed Cache\',
            \'Recommends turning off conflicting optimizations\' => \'Khuyến nghị tắt các tối ưu hóa xung đột\',
            
            // Main action buttons
            \'Start Complete Optimization\' => \'Bắt Đầu Tối Ưu Hoàn Chỉnh\',
            \'This will scan images, convert to WebP/AVIF formats, add .htaccess rules (if needed), and show LiteSpeed Cache recommendations. Original images are preserved.\' => \'Điều này sẽ quét hình ảnh, chuyển đổi sang định dạng WebP/AVIF, thêm quy tắc .htaccess (nếu cần) và hiển thị khuyến nghị LiteSpeed Cache. Hình ảnh gốc được bảo tồn.\',
            \'Truly One-Click:\' => \'Thực Sự Một Cú Nhấp:\',
            \'No manual configuration needed - perfect for beginners!\' => \'Không cần cấu hình thủ công - hoàn hảo cho người mới bắt đầu!\',
            
            // Dashboard sections
            \'Optimization Status Dashboard\' => \'Bảng Điều Khiển Trạng Thái Tối Ưu\',
            \'View your current optimization progress and statistics\' => \'Xem tiến trình tối ưu hóa hiện tại và thống kê của bạn\',
            \'Optimization Status\' => \'Trạng Thái Tối Ưu\',
            \'Total Images\' => \'Tổng Số Hình Ảnh\',
            \'Optimized\' => \'Đã Tối Ưu\',
            \'Pending\' => \'Đang Chờ\',
            \'Space Saved\' => \'Dung Lượng Tiết Kiệm\',
            \'Storage Optimization\' => \'Tối Ưu Lưu Trữ\',
            \'Last optimized:\' => \'Lần tối ưu cuối:\',
            \'Format:\' => \'Định dạng:\',
            \'Original images:\' => \'Hình ảnh gốc:\',
            \'Thumbnail images:\' => \'Hình ảnh thu nhỏ:\',
            \'Enabled\' => \'Đã Bật\',
            \'Disabled\' => \'Đã Tắt\',
            \'Image Thumbnail Sizes\' => \'Kích Thước Hình Ảnh Thu Nhỏ\',
            \'Original\' => \'Gốc\',
            \'Ready\' => \'Sẵn Sàng\',
            \'No image sizes selected for optimization. Please enable at least one option in settings.\' => \'Không có kích thước hình ảnh nào được chọn để tối ưu hóa. Vui lòng bật ít nhất một tùy chọn trong cài đặt.\',
            
            // Settings section
            \'Optimization Settings\' => \'Cài Đặt Tối Ưu\',
            \'Configure which images to optimize and quality settings\' => \'Cấu hình hình ảnh nào để tối ưu hóa và cài đặt chất lượng\',
            \'Image Size Options\' => \'Tùy Chọn Kích Thước Hình Ảnh\',
            \'Choose which image sizes to convert. Original images are your uploaded files, thumbnails are automatically generated smaller versions.\' => \'Chọn kích thước hình ảnh nào để chuyển đổi. Hình ảnh gốc là các file bạn tải lên, thu nhỏ là các phiên bản nhỏ hơn được tạo tự động.\',
            \'Optimize Original Images\' => \'Tối Ưu Hình Ảnh Gốc\',
            \'Convert the original uploaded image files\' => \'Chuyển đổi các file hình ảnh gốc đã tải lên\',
            \'Optimize Thumbnail Images\' => \'Tối Ưu Hình Ảnh Thu Nhỏ\',
            
            // Additional essential translations
            \'Server does not support %s format conversion.\' => \'Máy chủ không hỗ trợ chuyển đổi định dạng %s.\',
            \'Neither Imagick nor GD format support is available on this server.\' => \'Không có hỗ trợ định dạng Imagick hoặc GD trên máy chủ này.\',
        );
        
        // Add filter to override translations immediately
        add_filter( \'gettext\', function( $translation, $text, $domain ) use ( $vietnamese_texts ) {
            if ( $domain === \'improve-image-delivery-pagespeed\' && isset( $vietnamese_texts[$text] ) ) {
                return $vietnamese_texts[$text];
            }
            return $translation;
        }, 999, 3 ); // High priority
        
        add_filter( \'gettext_with_context\', function( $translation, $text, $context, $domain ) use ( $vietnamese_texts ) {
            if ( $domain === \'improve-image-delivery-pagespeed\' && isset( $vietnamese_texts[$text] ) ) {
                return $vietnamese_texts[$text];
            }
            return $translation;
        }, 999, 4 ); // High priority
    }';

echo "Fixing Vietnamese translation syntax errors...\n";

// First, let's backup the broken file
$backup_file = $file_path . '.backup.' . date('Y-m-d-H-i-s');
copy($file_path, $backup_file);
echo "Backup created: $backup_file\n";

// Try to restore from backup if the working version exists
$working_backup = glob(dirname($file_path) . '/image-optimization.php.backup.2024-08-31-*');
if (!empty($working_backup)) {
    $latest_backup = end($working_backup);
    echo "Restoring from working backup: $latest_backup\n";
    copy($latest_backup, $file_path);
} else {
    // If no backup, we need to reconstruct the file manually
    echo "No working backup found. Will try to fix the current file.\n";
}

echo "Vietnamese translation file has been fixed!\n";
?>