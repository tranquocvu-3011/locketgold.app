<?php
error_reporting(0);
ini_set('display_errors', '0');
require_once __DIR__ . '/config/database.php';

// Endpoint nhận Webhook biến động số dư (MBBank / SePay / Casso)
// Token bảo mật có thể đổi trong global_settings hoặc config tĩnh
$stmt_token = $pdo->query("SELECT setting_value FROM global_settings WHERE setting_key = 'webhook_token'");
$db_token = $stmt_token ? $stmt_token->fetchColumn() : false;
if (!$db_token) {
    $db_token = 'locket_gold_webhook_secret_2026';
    try { $pdo->exec("INSERT IGNORE INTO global_settings (setting_key, setting_value) VALUES ('webhook_token', '$db_token')"); } catch (Exception $e) {}
}
$secret_token = $db_token;
$received_token = $_GET['token'] ?? '';

// Kiểm tra token xác thực
if ($received_token !== $secret_token) {
    http_response_code(401);
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized']));
}

$raw_data = file_get_contents('php://input');
$data = json_decode($raw_data, true);

if (!$data) {
    http_response_code(400);
    die(json_encode(['status' => 'error', 'message' => 'Invalid JSON']));
}

// Hỗ trợ nhiều định dạng Webhook (Casso, SePay, MBBank direct)
$transaction_id = $data['transaction_id'] ?? $data['id'] ?? $data['refNo'] ?? $data['tid'] ?? '';
$amount = $data['amount'] ?? $data['in'] ?? 0;
$content = $data['content'] ?? $data['description'] ?? $data['msg'] ?? '';

if (empty($transaction_id) || empty($amount) || empty($content)) {
    http_response_code(400);
    die(json_encode(['status' => 'error', 'message' => 'Missing required fields']));
}

try {
    // Lưu vào bảng bank_transactions (nếu đã có thì bỏ qua để chống duplicate)
    $stmt = $pdo->prepare("INSERT IGNORE INTO bank_transactions (transaction_id, amount, content, status) VALUES (?, ?, ?, 'pending')");
    $stmt->execute([$transaction_id, $amount, $content]);
    
    if ($stmt->rowCount() > 0) {
        // Có giao dịch mới -> Kích hoạt bot quét đối soát
        if (function_exists('exec')) {
            $cmd = "php " . escapeshellarg(__DIR__ . '/cron_ai_scanner.php');
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                pclose(popen("start /B " . $cmd, "r"));
            } else {
                exec($cmd . " > /dev/null 2>&1 &");
            }
        }
    }
    
    echo json_encode(['status' => 'success', 'message' => 'Transaction recorded', 'tid' => $transaction_id]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
