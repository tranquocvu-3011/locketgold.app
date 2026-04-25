<?php
error_reporting(0);
ini_set('display_errors', '0');
ignore_user_abort(true);
set_time_limit(0);
define('IN_APP', true);
require_once __DIR__ . '/config/database.php';

// Fetch settings
$stmt = $pdo->query("SELECT setting_key, setting_value FROM global_settings");
$settings = [];
foreach ($stmt->fetchAll() as $row) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$thueapi_token = trim($settings['thueapi_token'] ?? '');

// Đồng bộ giao dịch ngân hàng từ ThueAPI.pro trước nếu có token
if ($thueapi_token) {
    // Sử dụng API V2 của thueapi.pro
    $api_url = "https://thueapi.pro/historyapimbbankv2/" . urlencode($thueapi_token);
    
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    // Bỏ qua lỗi SSL (nếu host chưa config)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        $data = json_decode($response, true);
        if (isset($data['status']) && $data['status'] === 'success' && isset($data['transactions'])) {
            $stmt_insert = $pdo->prepare("INSERT IGNORE INTO bank_transactions (transaction_id, amount, content, status) VALUES (?, ?, ?, 'pending')");
            foreach ($data['transactions'] as $tx) {
                if ($tx['type'] === 'IN') { // Chỉ lấy giao dịch tiền vào (credit)
                    $tx_id = (string)$tx['transactionID'];
                    $tx_amount = (int)$tx['amount'];
                    $tx_content = $tx['description'];
                    $stmt_insert->execute([$tx_id, $tx_amount, $tx_content]);
                }
            }
        }
    }
}



// Lấy 50 bill mới nhất đang pending hoặc chờ duyệt
$stmt = $pdo->prepare("SELECT * FROM receipts WHERE status IN ('pending', 'chờ duyệt') ORDER BY id DESC LIMIT 50");
$stmt->execute();
$receipts = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$receipts) {
    echo "No pending receipts.\n";
    exit;
}

// Function helper to get role expiry
function normalizeRoleValue($role)
{
    $role = strtolower(trim((string) $role));
    return $role === 'vip' ? 'vip1' : $role;
}

function getRoleExpiryAt($role)
{
    $role = normalizeRoleValue($role);
    return in_array($role, ['user', 'admin'], true) ? null : date('Y-m-d H:i:s', strtotime('+1 year'));
}

foreach ($receipts as $receipt) {
    $id = $receipt['id'];
    $username = $receipt['username'];
    $img_path = __DIR__ . $receipt['receipt_img'];
    $requested_amount = (int)$receipt['requested_amount'];
    $requested_role = $receipt['requested_role'];
    
    echo "Processing receipt ID: $id for user $username...\n";

    // Prevent duplicate processing
    $stmt_lock = $pdo->prepare("UPDATE receipts SET status = 'processing' WHERE id = ? AND status IN ('pending', 'chờ duyệt')");
    $stmt_lock->execute([$id]);
    if ($stmt_lock->rowCount() === 0) continue;



    $agency_owner = $receipt['agency_owner'] ?? null;
    $expected_account = $settings['bank_account'] ?? '';
    $expected_owner = $settings['bank_owner'] ?? '';
    
    if ($agency_owner) {
        $stmt_ag = $pdo->prepare("SELECT bank_account, bank_owner FROM agency_settings WHERE agency_username = ?");
        $stmt_ag->execute([$agency_owner]);
        if ($ag = $stmt_ag->fetch()) {
            $expected_account = $ag['bank_account'] ?? '';
            $expected_owner = $ag['bank_owner'] ?? '';
        }
    }

    // Cắt bỏ phần @gmail.com để chống lỗi mất ký tự đặc biệt của ngân hàng
    $base_username = explode('@', $username)[0];

    // 1. Kiểm tra nhanh qua bảng bank_transactions
    $stmt_bank = $pdo->prepare("SELECT id FROM bank_transactions WHERE amount >= ? AND LOWER(content) LIKE ? AND status = 'pending' LIMIT 1");
    $username_pattern = '%' . strtolower($base_username) . '%';
    $stmt_bank->execute([$requested_amount, $username_pattern]);
    $matched_transaction = $stmt_bank->fetch(PDO::FETCH_ASSOC);

    if ($matched_transaction) {
        // Khớp thành công với CSDL ngân hàng
        $pdo->prepare("UPDATE bank_transactions SET status = 'used' WHERE id = ?")->execute([$matched_transaction['id']]);
        $pdo->prepare("UPDATE receipts SET status = 'hoàn thành', admin_note = 'Auto-approved by Bank Webhook' WHERE id = ?")->execute([$id]);
        
        // Cập nhật role cho user
        $stmt_role = $pdo->prepare("SELECT role FROM users WHERE username = ?");
        $stmt_role->execute([$username]);
        $curr = $stmt_role->fetchColumn() ?: 'user';
        $new_roles = [];
        $is_sr_req = (strpos($requested_role, 'sr_') === 0);
        
        foreach (explode(',', $curr) as $r) {
            $r = trim($r);
            if ($is_sr_req) {
                // Nếu đang mua ShadowRocket -> giữ lại các quyền Locket Gold (không bắt đầu bằng sr_)
                if (strpos($r, 'sr_') !== 0 && $r !== '') $new_roles[] = $r;
            } else {
                // Nếu đang mua Locket Gold -> giữ lại các quyền ShadowRocket (bắt đầu bằng sr_)
                if (strpos($r, 'sr_') === 0) {
                    $new_roles[] = $r;
                }
            }
        }
        if ($requested_role !== 'user') $new_roles[] = $requested_role;
        if (empty($new_roles)) $new_roles[] = 'user';
        $final_role = implode(',', $new_roles);

        if ($is_sr_req) {
            $stmt_update = $pdo->prepare("UPDATE users SET role = ?, is_vip_notified = 0 WHERE username = ?");
            $stmt_update->execute([$final_role, $username]);
        } else {
            $stmt_update = $pdo->prepare("UPDATE users SET role = ?, role_expires_at = ?, is_vip_notified = 0 WHERE username = ?");
            $stmt_update->execute([$final_role, getRoleExpiryAt($requested_role), $username]);
        }
        
        echo "Receipt ID $id approved automatically via Bank Webhook.\n";
    } else {
        // Nếu không có trong bank_transactions, để trạng thái chờ để admin duyệt hoặc đợi lần quét sau
        $pdo->prepare("UPDATE receipts SET status = 'chờ duyệt', admin_note = ? WHERE id = ?")->execute(["Chưa tìm thấy giao dịch ngân hàng khớp. Vui lòng đợi hoặc duyệt tay.", $id]);
        echo "Receipt ID $id pending. No matching bank transaction found.\n";
    }
}
// ---------------------------------------------------------
// DỌN DẸP ẢNH CŨ (Tối ưu dung lượng ổ cứng Server)
// ---------------------------------------------------------
// Xóa file ảnh vật lý của các hóa đơn cũ hơn 7 ngày (giữ lại bản ghi dữ liệu)
try {
    $stmt_cleanup = $pdo->query("SELECT id, receipt_img FROM receipts WHERE created_at < NOW() - INTERVAL 7 DAY AND receipt_img IS NOT NULL AND receipt_img != '' LIMIT 100");
    while ($row = $stmt_cleanup->fetch()) {
        $file_path = __DIR__ . $row['receipt_img'];
        if (file_exists($file_path)) {
            @unlink($file_path);
        }
        $pdo->prepare("UPDATE receipts SET receipt_img = NULL WHERE id = ?")->execute([$row['id']]);
    }
} catch (Exception $e) {}

echo "Cronjob finished.\n";
