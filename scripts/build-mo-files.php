<?php
// Create a working Vietnamese .mo file

// Translations array
$translations = array(
    'Language:' => 'Ngôn ngữ:',
    'Improve Image Delivery PageSpeed' => 'Cải Thiện Tốc Độ Tải Hình Ảnh PageSpeed',
    'Boost Your PageSpeed Insights Score & Core Web Vitals' => 'Tăng Điểm PageSpeed Insights & Core Web Vitals',
    'Automatically convert your JPEG/PNG images to modern WebP/AVIF formats to reduce download time, improve perceived page load performance, and enhance your Largest Contentful Paint (LCP) scores for better Core Web Vitals.' => 'Tự động chuyển đổi hình ảnh JPEG/PNG sang định dạng WebP/AVIF hiện đại để giảm thời gian tải xuống, cải thiện hiệu suất tải trang cảm nhận và nâng cao điểm Largest Contentful Paint (LCP) cho Core Web Vitals tốt hơn.',
    'PageSpeed Optimization Benefits' => 'Lợi Ích Tối Ưu PageSpeed',
    'Improve LCP Score' => 'Cải Thiện Điểm LCP',
    'Faster Page Loads' => 'Tải Trang Nhanh Hơn',
    'Mobile Performance' => 'Hiệu Suất Mobile',
    'No Server Overload' => 'Không Quá Tải Máy Chủ',
    'SEO Benefits' => 'Lợi Ích SEO',
    'Start Complete Optimization' => 'Bắt Đầu Tối Ưu Hoàn Chỉnh',
    'Quick PageSpeed Optimization - 3 Steps' => 'Tối Ưu PageSpeed Nhanh - 3 Bước',
    'Scan for Optimization Opportunities' => 'Quét Tìm Cơ Hội Tối Ưu',
    'Convert to Modern Formats' => 'Chuyển Đổi Sang Định Dạng Hiện Đại',
    'Automatic Performance Setup' => 'Thiết Lập Hiệu Suất Tự Động',
    'Optimization Status Dashboard' => 'Bảng Điều Khiển Trạng Thái Tối Ưu',
    'Total Images' => 'Tổng Số Hình Ảnh',
    'Optimized' => 'Đã Tối Ưu',
    'Pending' => 'Đang Chờ',
    'Space Saved' => 'Dung Lượng Tiết Kiệm',
);

// Create MO file content
function create_mo_file($translations, $filename) {
    $magic = 0x950412de;
    $revision = 0;
    $count = count($translations);
    $koffset = 7 * 4;
    $voffset = $koffset + $count * 8;
    
    $koffsets = array();
    $voffsets = array();
    $kstr = '';
    $vstr = '';
    
    foreach ($translations as $key => $value) {
        $koffsets[] = array(strlen($key), strlen($kstr));
        $kstr .= $key . "\0";
        $voffsets[] = array(strlen($value), strlen($vstr));
        $vstr .= $value . "\0";
    }
    
    $keystart = $voffset + strlen($vstr);
    $valuestart = $keystart + strlen($kstr);
    
    $output = '';
    $output .= pack('V', $magic);
    $output .= pack('V', $revision);
    $output .= pack('V', $count);
    $output .= pack('V', $koffset);
    $output .= pack('V', $voffset);
    $output .= pack('V', 0);
    $output .= pack('V', 0);
    
    foreach ($koffsets as $offset) {
        $output .= pack('V', $offset[0]);
        $output .= pack('V', $keystart + $offset[1]);
    }
    
    foreach ($voffsets as $offset) {
        $output .= pack('V', $offset[0]);
        $output .= pack('V', $valuestart + $offset[1]);
    }
    
    $output .= $vstr . $kstr;
    
    return file_put_contents($filename, $output);
}

// Create the Vietnamese .mo file
$result = create_mo_file($translations, __DIR__ . '/../languages/improve-image-delivery-pagespeed-vi_VN.mo');

if ($result) {
    echo "Vietnamese .mo file created successfully!\n";
} else {
    echo "Failed to create .mo file.\n";
}

// Create a simple English .mo file (mostly empty since English is the source language)
$en_translations = array(
    'Language:' => 'Language:',
);

$result = create_mo_file($en_translations, __DIR__ . '/../languages/improve-image-delivery-pagespeed-en_US.mo');

if ($result) {
    echo "English .mo file created successfully!\n";
} else {
    echo "Failed to create English .mo file.\n";
}
?>