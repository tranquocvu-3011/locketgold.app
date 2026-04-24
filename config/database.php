<?php
if (!defined('IN_APP')) die('Access denied');

// ═══════════════════════════════════════════
// LOAD .ENV — Đọc cấu hình từ file .env
// ═══════════════════════════════════════════
$env_file = __DIR__ . '/../.env';
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = array_map('trim', explode('=', $line, 2));
        if (!getenv($key)) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

// ═══════════════════════════════════════════
// DATABASE CONNECTION
// ═══════════════════════════════════════════
$host    = getenv('DB_HOST') ?: '127.0.0.1';
$db      = getenv('DB_NAME') ?: 'locket_system';
$user    = getenv('DB_USER') ?: 'root';
$pass    = getenv('DB_PASS') ?: '';
$charset = getenv('DB_CHARSET') ?: 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $pdo->exec("SET time_zone = '+07:00'");
} catch (\PDOException $e) {
    die("DB Error");
}

// ═══════════════════════════════════════════
// CẤU HÌNH API KÍCH HOẠT LOCKET GOLD (REVENUECAT)
// ═══════════════════════════════════════════
// Đọc từ .env — Không hardcode API Key trong source code!
define('RC_API_KEY', getenv('RC_API_KEY') ?: 'ĐIỀN_API_KEY_PUBLIC_CỦA_LOCKET_VÀO_ĐÂY');
define('RC_SECRET_KEY', getenv('RC_SECRET_KEY') ?: '');
define('APPLE_RECEIPT_BASE64', getenv('APPLE_RECEIPT_BASE64') ?: 'ĐIỀN_RECEIPT_BASE64_TỪ_CHARLES_PROXY_VÀO_ĐÂY');
