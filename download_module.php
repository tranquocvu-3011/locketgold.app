<?php

// Bảo mật tránh lỗi hiển thị
error_reporting(0);

$sr_secret = "LocketGold_ShadowRocket_Secret_2026!"; // Khóa bảo mật ký token

$file = $_GET['file'] ?? '';
$t = (int)($_GET['t'] ?? 0);
$hash = $_GET['hash'] ?? '';

if (empty($file) || empty($t) || empty($hash)) {
    die("Lỗi: Link không hợp lệ hoặc thiếu tham số.");
}

// Giới hạn thời gian link sống (ví dụ: 10 phút = 600s)
if (time() - $t > 600) {
    die("Lỗi: Link cài đặt đã hết hạn (chỉ có hiệu lực trong 10 phút). Vui lòng quay lại web và bấm tải lại.");
}

// Kiểm tra chữ ký (signature)
$expected_hash = md5($file . $t . $sr_secret);
if (!hash_equals($expected_hash, $hash)) {
    die("Lỗi: Chữ ký bảo mật không hợp lệ. Link đã bị giả mạo.");
}

// Giới hạn các file được phép tải (tránh Path Traversal)
$allowed_files = [
    'QuocVu_LocketFree.module',
    'QuocVu_Locket15s.module',
    'QuocVu_Premium.module',
    'QuocVu_Ultimate.module'
];

if (!in_array($file, $allowed_files)) {
    die("Lỗi: Không tìm thấy module được yêu cầu.");
}

// Đường dẫn file thực tế trên server
$filepath = __DIR__ . '/QuocVu_Scripts/' . $file;

if (!file_exists($filepath)) {
    die("Lỗi: File module không tồn tại trên hệ thống máy chủ.");
}

// Phục vụ file text/plain cho Shadowrocket
header('Content-Type: text/plain; charset=utf-8');
header('Content-Disposition: inline; filename="' . $file . '"');
header('Cache-Control: no-cache, no-store, must-revalidate');

readfile($filepath);
exit;
