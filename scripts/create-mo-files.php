<?php
// Simple MO file creator for English translations
$mo_content = "\xde\x12\x04\x95\x00\x00\x00\x00\x01\x00\x00\x00\x1c\x00\x00\x00\x24\x00\x00\x00\x00\x00\x00\x00\x2c\x00\x00\x00\x00\x00\x00\x00\x2c\x00\x00\x00";
file_put_contents(__DIR__ . '/../languages/improve-image-delivery-pagespeed-en_US.mo', $mo_content);

// Create a simple Vietnamese MO file with at least one translation
$texts = array(
    '' => 'Project-Id-Version: Improve Image Delivery PageSpeed 1.0.0
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
',
    'Language:' => 'Ngôn ngữ:',
    'Improve Image Delivery PageSpeed' => 'Cải Thiện Tốc Độ Tải Hình Ảnh PageSpeed',
    'Boost Your PageSpeed Insights Score & Core Web Vitals' => 'Tăng Điểm PageSpeed Insights & Core Web Vitals',
);

// Simple MO format
$magic = 0x950412de;
$revision = 0;
$count = count($texts);
$koffset = 7 * 4;
$voffset = $koffset + $count * 8;

$koffsets = array();
$voffsets = array();
$kstr = '';
$vstr = '';

foreach ($texts as $key => $value) {
    $koffsets[] = array(strlen($key), strlen($kstr));
    $kstr .= $key . "\0";
    $voffsets[] = array(strlen($value), strlen($vstr));
    $vstr .= $value . "\0";
}

$output = pack('V', $magic);
$output .= pack('V', $revision);
$output .= pack('V', $count);
$output .= pack('V', $koffset);
$output .= pack('V', $voffset);
$output .= pack('V', 0);
$output .= pack('V', 0);

foreach ($koffsets as $offset) {
    $output .= pack('V', $offset[0]);
    $output .= pack('V', $voffset + strlen($vstr) + $offset[1]);
}

foreach ($voffsets as $offset) {
    $output .= pack('V', $offset[0]);
    $output .= pack('V', $voffset + strlen($kstr) + $offset[1]);
}

$output .= $kstr . $vstr;

file_put_contents(__DIR__ . '/../languages/improve-image-delivery-pagespeed-vi_VN.mo', $output);

echo "Translation files created!\n";
?>