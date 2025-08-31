<?php
/**
 * Update Vietnamese fallback translations in the main plugin file
 * This script updates the load_vietnamese_fallbacks() method with all missing translations
 */

$plugin_file = dirname(__DIR__) . '/image-optimization.php';
$plugin_content = file_get_contents($plugin_file);

// Define the new comprehensive Vietnamese fallback translations
$new_vietnamese_fallbacks = "    /**
     * Load Vietnamese text fallbacks when .mo file is not available
     */
    public function load_vietnamese_fallbacks() {
        // Define comprehensive Vietnamese translations as fallbacks
        \$vietnamese_texts = array(
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
            
            // Conversion settings
            'Conversion Settings' => 'Cài Đặt Chuyển Đổi',
            'Control quality and minimum size thresholds for conversion. Higher quality = larger files but better image quality.' => 'Kiểm soát chất lượng và ngưỡng kích thước tối thiểu để chuyển đổi. Chất lượng cao hơn = file lớn hơn nhưng chất lượng hình ảnh tốt hơn.',
            'Conversion quality' => 'Chất lượng chuyển đổi',
            'Recommended: 80. Higher values = better quality but larger files. Lower values = smaller files but reduced quality.' => 'Khuyến nghị: 80. Giá trị cao hơn = chất lượng tốt hơn nhưng file lớn hơn. Giá trị thấp hơn = file nhỏ hơn nhưng chất lượng giảm.',
            '0–100, default 80' => '0–100, mặc định 80',
            'Min width (px)' => 'Chiều rộng tối thiểu (px)',
            'Min height (px)' => 'Chiều cao tối thiểu (px)',
            'Min file size (KB)' => 'Kích thước file tối thiểu (KB)',
            'Force include small images' => 'Bắt buộc bao gồm hình ảnh nhỏ',
            'Ignore thresholds when scanning/converting' => 'Bỏ qua ngưỡng khi quét/chuyển đổi',
            
            // Server capabilities
            'Server Conversion Capabilities' => 'Khả Năng Chuyển Đổi Máy Chủ',
            'ImageMagick Extension' => 'Tiện Ích ImageMagick',
            'Available' => 'Có Sẵn',
            'Not Available' => 'Không Có Sẵn',
            'WebP Support' => 'Hỗ Trợ WebP',
            'AVIF Support' => 'Hỗ Trợ AVIF',
            'GD Extension' => 'Tiện Ích GD',
            'AVIF Support (Not Available in GD)' => 'Hỗ Trợ AVIF (Không Có Sẵn trong GD)',
            'No Image Conversion Library Available!' => 'Không Có Thư Viện Chuyển Đổi Hình Ảnh Nào!',
            'Neither ImageMagick nor GD extension is available on your server. Please contact your hosting provider to install one of these extensions.' => 'Cả ImageMagick và tiện ích GD đều không có sẵn trên máy chủ của bạn. Vui lòng liên hệ nhà cung cấp hosting để cài đặt một trong những tiện ích này.',
            
            // Format options
            'Output Format Selection' => 'Lựa Chọn Định Dạng Đầu Ra',
            'Choose which next-generation image formats to convert your images to. The selection depends on your server capabilities and browser support.' => 'Chọn định dạng hình ảnh thế hệ mới nào để chuyển đổi hình ảnh của bạn. Lựa chọn phụ thuộc vào khả năng máy chủ và hỗ trợ trình duyệt.',
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
            
            // Tools and actions
            'Step-by-Step Process:' => 'Quy Trình Từng Bước:',
            'Scan' => 'Quét',
            'Scan Images' => 'Quét Hình Ảnh',
            'Convert' => 'Chuyển Đổi',
            'Convert Pending' => 'Chuyển Đổi Đang Chờ',
            'Manage' => 'Quản Lý',
            'Remove All WebP' => 'Xóa Tất Cả WebP',
            
            // Help section
            'New User?' => 'Người Dùng Mới?',
            'Just click the big \"Start Complete Optimization\" button above. It will handle everything automatically - scan, convert, add .htaccess rules, and show LiteSpeed Cache recommendations!' => 'Chỉ cần nhấp vào nút \"Bắt Đầu Tối Ưu Hoàn Chỉnh\" lớn ở trên. Nó sẽ xử lý mọi thứ tự động - quét, chuyển đổi, thêm quy tắc .htaccess và hiển thị khuyến nghị LiteSpeed Cache!',
            'What are WebP/AVIF?' => 'WebP/AVIF là gì?',
            'WebP and AVIF are modern image formats that reduce file sizes by 25-50% without losing quality. Faster loading = better SEO!' => 'WebP và AVIF là định dạng hình ảnh hiện đại giảm kích thước file 25-50% mà không mất chất lượng. Tải nhanh hơn = SEO tốt hơn!',
            'Need Settings?' => 'Cần Cài Đặt?',
            'Default settings work great for most sites. Only change if you know what you're doing.' => 'Cài đặt mặc định hoạt động tốt cho hầu hết các trang web. Chỉ thay đổi nếu bạn biết mình đang làm gì.',
            'Is it Safe?' => 'Có An Toàn Không?',
            'Yes! Your original images are never deleted. WebP/AVIF copies are created alongside them.' => 'Có! Hình ảnh gốc của bạn không bao giờ bị xóa. Bản sao WebP/AVIF được tạo cùng với chúng.',
            
            // Footer and branding
            'Developed by' => 'Được Phát Triển Bởi',
            'from' => 'từ',
            'Comprehensive Digital Marketing solutions for SMEs since 2017 | 5,400+ customers served' => 'Giải pháp Digital Marketing toàn diện cho doanh nghiệp vừa và nhỏ từ năm 2017 | Đã phục vụ 5.400+ khách hàng',
            'Rate Plugin' => 'Đánh Giá Plugin',
            'Support Development' => 'Hỗ Trợ Phát Triển',
            'Get Support' => 'Nhận Hỗ Trợ',
            'Optimization completed successfully! Consider rating this plugin to help other users discover it.' => 'Tối ưu hóa hoàn thành thành công! Hãy xem xét đánh giá plugin này để giúp người dùng khác khám phá nó.',
            'Rate Now →' => 'Đánh Giá Ngay →',
            
            // Error messages and additional strings
            'Server does not support %s format conversion.' => 'Máy chủ không hỗ trợ chuyển đổi định dạng %s.',
            'Neither Imagick nor GD format support is available on this server.' => 'Không có hỗ trợ định dạng Imagick hoặc GD trên máy chủ này.',
            'Invalid language selection' => 'Lựa chọn ngôn ngữ không hợp lệ',
            'Language changed successfully' => 'Đã thay đổi ngôn ngữ thành công',
            ', skipped %d file(s)' => ', đã bỏ qua %d file',
            ', %d error(s)' => ', %d lỗi',
            'Conversion failed with %d error(s).' => 'Chuyển đổi thất bại với %d lỗi.',
            'All %d file(s) were skipped (already converted or below thresholds).' => 'Tất cả %d file đã bị bỏ qua (đã chuyển đổi hoặc dưới ngưỡng).',
            'Save Settings' => 'Lưu Cài Đặt',
            'Settings saved successfully.' => 'Đã lưu cài đặt thành công.',
        );
        
        // Add filter to override translations immediately
        add_filter( 'gettext', function( \$translation, \$text, \$domain ) use ( \$vietnamese_texts ) {
            if ( \$domain === 'improve-image-delivery-pagespeed' && isset( \$vietnamese_texts[\$text] ) ) {
                return \$vietnamese_texts[\$text];
            }
            return \$translation;
        }, 999, 3 ); // High priority
        
        add_filter( 'gettext_with_context', function( \$translation, \$text, \$context, \$domain ) use ( \$vietnamese_texts ) {
            if ( \$domain === 'improve-image-delivery-pagespeed' && isset( \$vietnamese_texts[\$text] ) ) {
                return \$vietnamese_texts[\$text];
            }
            return \$translation;
        }, 999, 4 ); // High priority
    }";

// Find the existing load_vietnamese_fallbacks method and replace it
$pattern = '/\/\*\*\s*\*\s*Load Vietnamese text fallbacks when \.mo file is not available\s*\*\/\s*public function load_vietnamese_fallbacks\(\)\s*{.*?^\s*}/ms';

if (preg_match($pattern, $plugin_content)) {
    $updated_content = preg_replace($pattern, $new_vietnamese_fallbacks, $plugin_content);
    file_put_contents($plugin_file, $updated_content);
    echo "✅ Vietnamese fallback translations updated successfully!\n";
    echo "🎯 The plugin now has complete Vietnamese language support.\n";
} else {
    echo "❌ Could not find the load_vietnamese_fallbacks method to update.\n";
}
?>