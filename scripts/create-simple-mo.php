<?php
// Create a working .mo file for Vietnamese translations
// This is a simple script to generate a binary .mo file

$translations = array(
    'Language:' => 'Ngôn ngữ:',
    'Improve Image Delivery PageSpeed' => 'Cải Thiện Tốc Độ Tải Hình Ảnh PageSpeed',
    'Boost Your PageSpeed Insights Score & Core Web Vitals' => 'Tăng Điểm PageSpeed Insights & Core Web Vitals',
    'Start Complete Optimization' => 'Bắt Đầu Tối Ưu Hoàn Chỉnh',
    'Total Images' => 'Tổng Số Hình Ảnh',
    'Optimized' => 'Đã Tối Ưu',
    'Pending' => 'Đang Chờ',
    'Space Saved' => 'Dung Lượng Tiết Kiệm',
);

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
    echo "Vietnamese .mo file created successfully with " . count($translations) . " translations!\n";
} else {
    echo "Failed to create .mo file.\n";
}
?>