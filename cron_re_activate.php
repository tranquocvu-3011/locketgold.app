<?php
/**
 * CRON AUTO-RECOVERY — Tự động kích hoạt lại Gold cho khách
 * 
 * Chạy mỗi 10-15 phút qua crontab:
 *   crontab: 0,10,20,30,40,50 * * * * php /path/to/cron_re_activate.php
 * 
 * CHẾ ĐỘ THÔNG MINH:
 * - Nếu pool có nhiều receipt (>1): Re-activate tất cả UID (mỗi UID có receipt riêng, không collision)
 * - Nếu pool chỉ có 1 receipt: CHỈ re-activate UID được kích hoạt GẦN NHẤT (tránh ping-pong transfer)
 */

error_reporting(0);
ini_set('display_errors', '0');
ignore_user_abort(true);
set_time_limit(0);

// Bảo vệ: chỉ cho phép chạy qua CLI hoặc có secret key
$is_cli = (php_sapi_name() === 'cli');
$secret_ok = (isset($_GET['key']) && $_GET['key'] === 'locketgold_cron_2026');
if (!$is_cli && !$secret_ok) {
    http_response_code(403);
    die('Access denied.');
}

define('IN_APP', true);
require_once __DIR__ . '/config/database.php';

// Fetch Receipt Pool
$stmt_r = $pdo->query("SELECT setting_value FROM global_settings WHERE setting_key = 'premium_receipts'");
$r_val = $stmt_r->fetchColumn();

$r_arr = [];
if ($r_val) {
    $r_arr = array_values(array_filter(array_map('trim', explode("\n", $r_val))));
}

// Fallback: dùng receipt từ .env nếu pool rỗng
if (empty($r_arr)) {
    if (APPLE_RECEIPT_BASE64 && APPLE_RECEIPT_BASE64 !== 'ĐIỀN_RECEIPT_BASE64_TỪ_CHARLES_PROXY_VÀO_ĐÂY') {
        $r_arr = [APPLE_RECEIPT_BASE64];
    } else {
        echo "[SKIP] No receipt configured.\n";
        exit;
    }
}

$api_key = RC_API_KEY;
if ($api_key === 'ĐIỀN_API_KEY_PUBLIC_CỦA_LOCKET_VÀO_ĐÂY') {
    echo "[SKIP] API key not configured.\n";
    exit;
}

$pool_size = count($r_arr);
$is_single_receipt = ($pool_size <= 1);

echo "=== Cron Re-Activate ===\n";
echo "Pool size: $pool_size receipt(s)\n";
echo "Mode: " . ($is_single_receipt ? "SINGLE RECEIPT (conservative)" : "MULTI RECEIPT (full scan)") . "\n\n";

if ($is_single_receipt) {
    // ═══════════════════════════════════════════════
    // CHẾ ĐỘ 1 RECEIPT: Chỉ re-activate UID cuối cùng
    // Vì 1 receipt chỉ có thể "thuộc về" 1 UID trên RC
    // → Re-activate nhiều UID sẽ gây ping-pong transfer
    // → Chỉ đảm bảo UID mới nhất luôn có Gold trên server
    // ═══════════════════════════════════════════════
    
    $stmt = $pdo->query("
        SELECT uid, injected_by, updated_at
        FROM activations 
        WHERE status = 'Activated (Live)' AND job_status = 'completed'
        ORDER BY updated_at DESC 
        LIMIT 1
    ");
    $last = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$last) {
        echo "[DONE] No active UIDs.\n";
        exit;
    }
    
    $uid = $last['uid'];
    $receipt = $r_arr[0];
    
    echo "Re-activating latest UID: $uid\n";
    
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL            => "https://api.revenuecat.com/v1/receipts",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_CUSTOMREQUEST  => "POST",
        CURLOPT_POSTFIELDS     => json_encode([
            "app_user_id"  => $uid,
            "fetch_token"  => $receipt
        ]),
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer " . $api_key,
            "Content-Type: application/json",
            "X-Platform: iOS"
        ],
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    if ($httpcode == 200 || $httpcode == 201) {
        echo "SUCCESS: UID $uid re-activated.\n";
        // Cập nhật receipt_assignments nếu có
        try {
            $pdo->prepare("UPDATE receipt_assignments SET last_used_at = NOW(), use_count = use_count + 1 WHERE assigned_uid = ?")
                ->execute([$uid]);
        } catch (Exception $e) {}
    } else {
        echo "FAILED: UID $uid (HTTP $httpcode).\n";
    }

} else {
    // ═══════════════════════════════════════════════
    // CHẾ ĐỘ NHIỀU RECEIPT: Re-activate tất cả (an toàn vì mỗi UID có receipt riêng)
    // ═══════════════════════════════════════════════
    
    $stmt = $pdo->prepare("
        SELECT 
            a.uid,
            ra.receipt_index,
            ra.last_used_at
        FROM activations a
        INNER JOIN receipt_assignments ra ON ra.assigned_uid = a.uid AND ra.is_active = 1
        WHERE a.status = 'Activated (Live)'
          AND a.job_status = 'completed'
          AND a.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY a.uid
        ORDER BY ra.last_used_at ASC
        LIMIT 25
    ");
    $stmt->execute();
    $uids = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($uids)) {
        echo "[DONE] No UIDs to re-activate.\n";
        exit;
    }
    
    $success = 0;
    $failed = 0;
    $skipped = 0;
    
    foreach ($uids as $row) {
        $uid = $row['uid'];
        $receipt_index = (int)$row['receipt_index'];
        
        if (!isset($r_arr[$receipt_index])) {
            echo "SKIP: UID $uid — Receipt #$receipt_index not in pool.\n";
            $skipped++;
            continue;
        }
        
        $receipt = $r_arr[$receipt_index];
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => "https://api.revenuecat.com/v1/receipts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => json_encode([
                "app_user_id"  => $uid,
                "fetch_token"  => $receipt
            ]),
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer " . $api_key,
                "Content-Type: application/json",
                "X-Platform: iOS"
            ],
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        if ($httpcode == 200 || $httpcode == 201) {
            $success++;
            $pdo->prepare("UPDATE receipt_assignments SET last_used_at = NOW(), use_count = use_count + 1 WHERE assigned_uid = ?")
                ->execute([$uid]);
            echo "OK: UID $uid (Receipt #$receipt_index)\n";
        } else {
            $failed++;
            echo "FAIL: UID $uid (HTTP $httpcode)\n";
        }
        
        // Rate limit: 1.5s giữa mỗi request
        usleep(1500000);
    }
    
    echo "\n=== Summary ===\n";
    echo "Total: " . count($uids) . " | OK: $success | Fail: $failed | Skip: $skipped\n";
}

echo "Finished at " . date('Y-m-d H:i:s') . "\n";
