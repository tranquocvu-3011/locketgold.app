<?php
if (isset($_GET['ajax_check_receipt'])) {
    header('Content-Type: application/json');
    if (!$current_user) {
        echo json_encode(['status' => 'error']);
        exit;
    }
    $stmt = $pdo->prepare("SELECT status, admin_note FROM receipts WHERE username = ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$current_user]);
    $receipt = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($receipt) {
        if ($receipt['status'] === 'pending' || $receipt['status'] === 'processing') {
            // Đảm bảo cron luôn chạy bằng HTTP trigger nếu exec bị disable trên Shared Hosting
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = $protocol . $_SERVER['HTTP_HOST'] . '/cron_ai_scanner.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 8); // Chờ tối đa 8 giây để lấy kết quả ngay lập tức
            curl_exec($ch);
            curl_close($ch);

            // Re-fetch status sau khi AI có thể đã quét xong
            $stmt->execute([$current_user]);
            $receipt = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        echo json_encode(['status' => $receipt['status'], 'note' => htmlspecialchars($receipt['admin_note'] ?? '', ENT_QUOTES, 'UTF-8')]);
    } else {
        echo json_encode(['status' => 'not_found']);
    }
    exit;
}

if ($action === 'confirm_payment_auto') {
    if (!$current_user) {
        $auth_msg = "Vui lòng đăng nhập!";
    } else {
        $stmt_pending = $pdo->prepare("SELECT COUNT(*) FROM receipts WHERE username = ? AND status IN ('pending', 'chờ duyệt')");
        $stmt_pending->execute([$current_user]);
        if ($stmt_pending->fetchColumn() > 0) {
            queueToast($toast_queue, "Bạn đang có yêu cầu chờ xử lý! Vui lòng chờ hoàn tất.", "warning");
            header("Location: /quan-ly-tai-khoan");
            exit;
        }

        $checkout_target = $_POST['checkout_target'] ?? inferCheckoutTargetFromLegacyPlan($_POST['checkout_plan'] ?? '');
        $checkout = resolveCheckoutSelection($settings, $current_role, $checkout_target);
        if (!$checkout) {
            queueToast($toast_queue, "Gói thanh toán không hợp lệ.", "error");
        } else {
            $auto_status = 'pending';
            try {
                $stmt = $pdo->prepare("INSERT INTO receipts (username, receipt_img, agency_owner, requested_plan, requested_role, requested_amount, checkout_target, status) VALUES (?, NULL, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $current_user,
                    $current_agency,
                    $checkout['plan_label'],
                    $checkout['target_role'],
                    (int) $checkout['amount'],
                    $checkout['target_role'],
                    $auto_status
                ]);

                // Trigger auto-scan
                $cronPath = __DIR__ . '/../cron_ai_scanner.php';
                if (file_exists($cronPath)) {
                    $cmd = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? "start /B php \"$cronPath\"" : "php \"$cronPath\"";
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        pclose(popen($cmd, "r"));
                    } else {
                        exec($cmd . " > /dev/null 2>&1 &");
                    }
                }
                queueToast($toast_queue, "Đã gửi xác nhận! Hệ thống ngân hàng đang đối soát tự động trong 1-2 phút...", "success");
                header("Location: /quan-ly-tai-khoan");
                exit;
            } catch (Exception $e) {
                queueToast($toast_queue, "Lỗi hệ thống khi lưu yêu cầu.", "error");
                header("Location: /quan-ly-tai-khoan");
                exit;
            }
        }
    }
}

// Chỉ admin hoặc agency mới được duyệt hóa đơn
if ($action === 'admin_set_receipt_role' && in_array($current_role, ['admin', 'agency'])) {
    $r_id = $_POST['receipt_id'] ?? 0;
    $new_role = normalizeRoleValue($_POST['role'] ?? 'user');
    if (!in_array($new_role, ['user', 'vip1', 'vip2', 'vip3', 'vip4', 'agency', 'admin', 'sr_vip', 'sr_premium', 'sr_ultimate', 'sr_proxy_1m', 'sr_proxy_1y', 'sr_proxy_combo_1m', 'sr_proxy_combo_1y'], true)) {
        $new_role = 'user';
    }
    $username = $_POST['username'] ?? '';

    if ($r_id && $username) {
        $receipt_stmt = $pdo->prepare("SELECT requested_role FROM receipts WHERE id = ? LIMIT 1");
        $receipt_stmt->execute([$r_id]);
        $requested_role = normalizeRoleValue($receipt_stmt->fetchColumn() ?: '');
        // Kiểm tra bảo mật cho đại lý
        if ($current_role === 'agency') {
            if ($new_role === 'admin' || $new_role === 'agency') {
                $new_role = 'user'; // Không cho phép đại lý tạo admin/agency khác
            }
            if ($requested_role !== '') {
                $new_role = $requested_role;
            }
            $check_stmt = $pdo->prepare("SELECT id FROM receipts WHERE id = ? AND agency_owner = ?");
            $check_stmt->execute([$r_id, $current_user]);
            if (!$check_stmt->fetch()) {
                queueToast($toast_queue, "Không có quyền thực hiện!", "error");
                $r_id = 0; // Ngăn chặn thực thi tiếp
            }
        }
        if ($r_id) {
            $proxy_info = $_POST['proxy_info'] ?? null;
            if ($proxy_info !== null) {
                $stmt_proxy = $pdo->prepare("UPDATE users SET proxy_info = ? WHERE username = ?");
                $stmt_proxy->execute([$proxy_info, $username]);
            }
            $is_sr_form = !empty($_POST['is_sr_form']);
            
            if ($is_sr_form || strpos($new_role, 'sr_') === 0) {
                $stmt_role = $pdo->prepare("SELECT role FROM users WHERE username = ?");
                $stmt_role->execute([$username]);
                $curr = $stmt_role->fetchColumn() ?: 'user';
                $new_roles = [];
                foreach (explode(',', $curr) as $r) {
                    $r = trim($r);
                    if (strpos($r, 'sr_') !== 0 && $r !== '') $new_roles[] = $r;
                }
                if ($new_role !== 'user') {
                    $new_roles[] = $new_role;
                }
                $final_role = implode(',', $new_roles);
                if (empty($final_role)) $final_role = 'user';
                $stmt = $pdo->prepare("UPDATE users SET role = ?, is_vip_notified = 0 WHERE username = ?");
                $stmt->execute([$final_role, $username]);
            } else {
                $stmt_role = $pdo->prepare("SELECT role FROM users WHERE username = ?");
                $stmt_role->execute([$username]);
                $curr = $stmt_role->fetchColumn() ?: 'user';
                $sr_roles = [];
                foreach (explode(',', $curr) as $r) {
                    $r = trim($r);
                    if (strpos($r, 'sr_') === 0) $sr_roles[] = $r;
                }
                
                $new_roles = [];
                if ($new_role !== 'user') $new_roles[] = $new_role;
                $new_roles = array_merge($new_roles, $sr_roles);
                
                $final_role = implode(',', $new_roles);
                if (empty($final_role)) $final_role = 'user';
                
                $stmt = $pdo->prepare("UPDATE users SET role = ?, role_expires_at = ?, is_vip_notified = 0 WHERE username = ?");
                $stmt->execute([$final_role, getRoleExpiryAt($new_role), $username]);
            }

            $stmt = $pdo->prepare("UPDATE receipts SET status = 'hoàn thành' WHERE id = ?");
            $stmt->execute([$r_id]);
            queueToast($toast_queue, "Đã cập nhật role và duyệt hóa đơn!", "success");
        }
    }
}

// Chỉ admin hoặc agency mới được xóa hóa đơn
if ($action === 'admin_del_receipt' && in_array($current_role, ['admin', 'agency'])) {
    $r_id = $_POST['receipt_id'] ?? 0;
    if ($r_id) {
        if ($current_role === 'agency') {
            $stmt = $pdo->prepare("DELETE FROM receipts WHERE id = ? AND agency_owner = ?");
            $stmt->execute([$r_id, $current_user]);
        } else {
            $stmt = $pdo->prepare("DELETE FROM receipts WHERE id = ?");
            $stmt->execute([$r_id]);
        }
        queueToast($toast_queue, "Đã xóa hóa đơn!", "success");
    }
}

// ─── ACTION: Lưu Thương Hiệu & SEO (Logo, Favicon, Banner, Meta) ─────────────
if ($action === 'admin_save_branding' && $current_role === 'admin') {
    if (!is_dir('uploads'))
        mkdir('uploads', 0775, true);

    // Lưu text settings
    $branding_keys = ['site_name', 'meta_desc', 'meta_keywords', 'seo_redirect_enabled', 'seo_redirect_keyword'];
    foreach ($branding_keys as $key) {
        $val = trim($_POST[$key] ?? '');
        $stmt = $pdo->prepare("INSERT INTO global_settings (setting_key, setting_value) VALUES (?,?) ON DUPLICATE KEY UPDATE setting_value=?");
        $stmt->execute([$key, $val, $val]);
    }

    // Upload Logo
    if (!empty($_FILES['logo_file']['name'])) {
        if ($_FILES['logo_file']['error'] === 0) {
            $logoUpload = validateImageUpload(
                $_FILES['logo_file'],
                ['png', 'jpg', 'jpeg', 'gif', 'webp'],
                ['image/png', 'image/jpeg', 'image/gif', 'image/webp']
            );
            if (!$logoUpload['ok']) {
                queueToast($toast_queue, "Logo: " . $logoUpload['error'], "error");
            } else {
                $ext = strtolower(pathinfo($_FILES['logo_file']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
                    move_uploaded_file($_FILES['logo_file']['tmp_name'], __DIR__ . '/../logo.' . $ext);
                    $logo_path = '/logo.' . $ext;
                    $stmt = $pdo->prepare("INSERT INTO global_settings (setting_key, setting_value) VALUES ('logo_path',?) ON DUPLICATE KEY UPDATE setting_value=?");
                    $stmt->execute([$logo_path, $logo_path]);
                }
            }
        } else {
            queueToast($toast_queue, "Lỗi tải Logo (Code: " . $_FILES['logo_file']['error'] . "). Có thể do ảnh quá lớn (vượt quá giới hạn PHP).", "error");
        }
    }

    // Upload Favicon
    if (!empty($_FILES['favicon_file']['name'])) {
        if ($_FILES['favicon_file']['error'] === 0) {
            $faviconUpload = validateImageUpload(
                $_FILES['favicon_file'],
                ['png', 'jpg', 'jpeg', 'gif', 'webp', 'ico'],
                ['image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/x-icon', 'image/vnd.microsoft.icon', 'application/octet-stream']
            );
            if (!$faviconUpload['ok']) {
                queueToast($toast_queue, "Favicon: " . $faviconUpload['error'], "error");
            } else {
                $ext = strtolower(pathinfo($_FILES['favicon_file']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp', 'ico'])) {
                    $favicon_name = 'favicon.' . $ext;
                    move_uploaded_file($_FILES['favicon_file']['tmp_name'], __DIR__ . '/../' . $favicon_name);
                    $favicon_path = '/' . $favicon_name;
                    $stmt = $pdo->prepare("INSERT INTO global_settings (setting_key, setting_value) VALUES ('favicon_path',?) ON DUPLICATE KEY UPDATE setting_value=?");
                    $stmt->execute([$favicon_path, $favicon_path]);
                }
            }
        } else {
            queueToast($toast_queue, "Lỗi tải Favicon (Code: " . $_FILES['favicon_file']['error'] . ").", "error");
        }
    }

    // Upload Banner (OG Image)
    if (!empty($_FILES['banner_file']['name'])) {
        if ($_FILES['banner_file']['error'] === 0) {
            $bannerUpload = validateImageUpload(
                $_FILES['banner_file'],
                ['png', 'jpg', 'jpeg', 'gif', 'webp'],
                ['image/png', 'image/jpeg', 'image/gif', 'image/webp']
            );
            if (!$bannerUpload['ok']) {
                queueToast($toast_queue, "Banner: " . $bannerUpload['error'], "error");
            } else {
                $ext = strtolower(pathinfo($_FILES['banner_file']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
                    move_uploaded_file($_FILES['banner_file']['tmp_name'], __DIR__ . '/../banner.' . $ext);
                    $banner_path = '/banner.' . $ext;
                    $stmt = $pdo->prepare("INSERT INTO global_settings (setting_key, setting_value) VALUES ('banner_path',?) ON DUPLICATE KEY UPDATE setting_value=?");
                    $stmt->execute([$banner_path, $banner_path]);
                }
            }
        } else {
            queueToast($toast_queue, "Lỗi tải Banner (Code: " . $_FILES['banner_file']['error'] . "). Dung lượng ảnh tải lên vượt quá giới hạn hệ thống.", "error");
        }
    }

    queueToast($toast_queue, "✅ Đã lưu cài đặt thương hiệu & SEO thành công!", "success");
}

// ─── ACTION: Quản lý bài viết (Articles) ─────────────
if ($action === 'admin_add_article' && $current_role === 'admin') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    if (!$slug) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    }
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $is_published = isset($_POST['is_published']) ? 1 : 0;

    // Thumbnail upload
    $thumbnail = '';
    if (!empty($_FILES['thumbnail']['name']) && $_FILES['thumbnail']['error'] === 0) {
        if (!is_dir('uploads'))
            mkdir('uploads', 0775, true);
        $thumbUpload = validateImageUpload(
            $_FILES['thumbnail'],
            ['png', 'jpg', 'jpeg', 'gif', 'webp'],
            ['image/png', 'image/jpeg', 'image/gif', 'image/webp']
        );
        if (!$thumbUpload['ok']) {
            queueToast($toast_queue, "Thumbnail: " . $thumbUpload['error'], "error");
        } else {
            $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
            $thumb_name = 'uploads/art_' . time() . '.' . $ext;
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
                move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumb_name);
                $thumbnail = '/' . $thumb_name;
            }
        }
    }

    $stmt = $pdo->prepare("INSERT INTO articles (slug, title, thumbnail, excerpt, content, is_published) VALUES (?, ?, ?, ?, ?, ?)");
    try {
        $stmt->execute([$slug, $title, $thumbnail, $excerpt, $content, $is_published]);
        queueToast($toast_queue, "Đã thêm bài viết thành công!", "success");
    } catch (Exception $e) {
        queueToast($toast_queue, "Lỗi: " . $e->getMessage(), "error");
    }
}

if ($action === 'admin_edit_article' && $current_role === 'admin') {
    $id = $_POST['article_id'] ?? 0;
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $is_published = isset($_POST['is_published']) ? 1 : 0;

    $update_sql = "UPDATE articles SET slug=?, title=?, excerpt=?, content=?, is_published=? WHERE id=?";
    $params = [$slug, $title, $excerpt, $content, $is_published, $id];

    if (!empty($_FILES['thumbnail']['name']) && $_FILES['thumbnail']['error'] === 0) {
        if (!is_dir('uploads'))
            mkdir('uploads', 0775, true);
        $thumbUpload = validateImageUpload(
            $_FILES['thumbnail'],
            ['png', 'jpg', 'jpeg', 'gif', 'webp'],
            ['image/png', 'image/jpeg', 'image/gif', 'image/webp']
        );
        if (!$thumbUpload['ok']) {
            queueToast($toast_queue, "Thumbnail: " . $thumbUpload['error'], "error");
        } else {
            $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
            $thumb_name = 'uploads/art_' . time() . '.' . $ext;
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
                move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumb_name);
                $update_sql = "UPDATE articles SET slug=?, title=?, thumbnail=?, excerpt=?, content=?, is_published=? WHERE id=?";
                $params = [$slug, $title, '/' . $thumb_name, $excerpt, $content, $is_published, $id];
            }
        }
    }

    $stmt = $pdo->prepare($update_sql);
    try {
        $stmt->execute($params);
        queueToast($toast_queue, "Đã cập nhật bài viết!", "success");
    } catch (Exception $e) {
        queueToast($toast_queue, "Lỗi: " . $e->getMessage(), "error");
    }
}

if ($action === 'admin_delete_article' && $current_role === 'admin') {
    $id = $_POST['article_id'] ?? 0;
    $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    queueToast($toast_queue, "Đã xóa bài viết!", "success");
}

if ($action === 'admin_upload_feedback' && $current_role === 'admin') {
    if (!empty($_FILES['feedback_image']['name']) && $_FILES['feedback_image']['error'] === 0) {
        if (!is_dir('uploads/feedbacks')) {
            mkdir('uploads/feedbacks', 0775, true);
        }
        $fbUpload = validateImageUpload(
            $_FILES['feedback_image'],
            ['png', 'jpg', 'jpeg', 'gif', 'webp'],
            ['image/png', 'image/jpeg', 'image/gif', 'image/webp']
        );
        if (!$fbUpload['ok']) {
            queueToast($toast_queue, "Feedback: " . $fbUpload['error'], "error");
        } else {
            $ext = strtolower(pathinfo($_FILES['feedback_image']['name'], PATHINFO_EXTENSION));
            $fb_name = 'uploads/feedbacks/fb_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
            if (move_uploaded_file($_FILES['feedback_image']['tmp_name'], $fb_name)) {
                $stmt = $pdo->prepare("INSERT INTO feedbacks (image_url) VALUES (?)");
                $stmt->execute(['/' . $fb_name]);
                queueToast($toast_queue, "Đã tải lên ảnh đánh giá thành công!", "success");
            } else {
                queueToast($toast_queue, "Lỗi khi lưu file!", "error");
            }
        }
    } else {
        queueToast($toast_queue, "Vui lòng chọn ảnh hợp lệ!", "error");
    }
}

if ($action === 'admin_delete_feedback' && $current_role === 'admin') {
    $id = $_POST['feedback_id'] ?? 0;
    // Tùy chọn: Xóa file vật lý
    $stmt = $pdo->prepare("SELECT image_url FROM feedbacks WHERE id = ?");
    $stmt->execute([$id]);
    $fb = $stmt->fetch();
    if ($fb && !empty($fb['image_url'])) {
        $filePath = __DIR__ . $fb['image_url'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM feedbacks WHERE id = ?");
    $stmt->execute([$id]);
    queueToast($toast_queue, "Đã xóa ảnh đánh giá!", "success");
}

// Xóa bản ghi kích hoạt — CHỈ admin và agency
if ($action === 'delete_activation' && in_array($current_role, ['admin', 'agency'])) {
    $act_id = (int) ($_POST['activation_id'] ?? 0);
    if ($act_id > 0) {
        if ($current_role === 'admin') {
            $stmt = $pdo->prepare("DELETE FROM activations WHERE id = ?");
            $stmt->execute([$act_id]);
        } else {
            // agency chỉ xóa bản ghi của chính họ inject
            $stmt = $pdo->prepare("DELETE FROM activations WHERE id = ? AND injected_by = ?");
            $stmt->execute([$act_id, $current_user]);
        }
        queueToast($toast_queue, "Đã xóa ID khỏi lịch sử!", "success");
        header('Location: /lich-su');
        exit;
    }
}
if ($action === 'admin_add_fund' && $current_role === 'admin') {
    $uname = trim($_POST['username'] ?? '');
    $role = normalizeRoleValue($_POST['role'] ?? 'user');
    if (!in_array($role, ['user', 'vip1', 'vip2', 'vip3', 'vip4', 'agency', 'admin', 'sr_vip', 'sr_premium', 'sr_ultimate', 'sr_proxy_1m', 'sr_proxy_1y', 'sr_proxy_combo_1m', 'sr_proxy_combo_1y'], true)) {
        $role = 'user';
    }
    $note = trim($_POST['note'] ?? 'Admin cấp trực tiếp');

    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$uname]);
    if ($stmt->rowCount() > 0) {
        $stmt2 = $pdo->prepare("INSERT INTO receipts (username, receipt_img, status, created_at) VALUES (?, ?, ?, NOW())");
        $stmt2->execute([$uname, 'https://cdn-icons-png.flaticon.com/512/561/561136.png', 'hoàn thành']);

        $pdo->prepare("UPDATE receipts SET requested_plan = ?, requested_role = ?, requested_amount = NULL, checkout_target = ? WHERE id = LAST_INSERT_ID()")
            ->execute([$note, $role, $role]);

        $stmt3 = $pdo->prepare("UPDATE users SET role = ?, role_expires_at = ?, is_vip_notified = 0 WHERE username = ?");
        $stmt3->execute([$role, getRoleExpiryAt($role), $uname]);

        queueToast($toast_queue, "Đã nạp tiền & cấp quyền {$role} cho tài khoản {$uname}!", "success");
    } else {
        queueToast($toast_queue, "Tài khoản không tồn tại!", "error");
    }
}

if ($action === 'admin_save_payment_settings' && in_array($current_role, ['admin', 'agency'])) {
    $keys = [
        'bank_code',
        'bank_account',
        'bank_owner',
        'price_vip1',
        'price_vip1_old',
        'price_vip2',
        'price_vip2_old',
        'price_vip3',
        'price_vip3_old',
        'price_vip4',
        'price_vip4_old',
        'price_agency',
        'price_agency_old',
        'site_name',
        'thueapi_token',
        'price_sr_vip',
        'price_sr_premium',
        'price_sr_ultimate',
        'price_sr_proxy_1m',
        'price_sr_proxy_1y',
        'sr_steps_free',
        'sr_steps_vip',
        'sr_steps_premium',
        'sr_steps_ultimate'
    ];

    if ($current_role === 'agency') {
        $site_name = $_POST['site_name'] ?? '';
        $domain_name = $_POST['domain_name'] ?? '';
        $bank_code = $_POST['bank_code'] ?? '';
        $bank_account = $_POST['bank_account'] ?? '';
        $bank_owner = $_POST['bank_owner'] ?? '';
        $p_v1 = $_POST['price_vip1'] ?? '';
        $p_v2 = $_POST['price_vip2'] ?? '';
        $p_v3 = $_POST['price_vip3'] ?? '';
        $p_v4 = $_POST['price_vip4'] ?? '';
        $p_a = $_POST['price_agency'] ?? '';

        $stmt = $pdo->prepare("INSERT INTO agency_settings (agency_username, site_name, domain_name, bank_code, bank_account, bank_owner, price_vip1, price_vip2, price_vip3, price_vip4, price_agency) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE site_name=?, domain_name=?, bank_code=?, bank_account=?, bank_owner=?, price_vip1=?, price_vip2=?, price_vip3=?, price_vip4=?, price_agency=?");
        $stmt->execute([$current_user, $site_name, $domain_name, $bank_code, $bank_account, $bank_owner, $p_v1, $p_v2, $p_v3, $p_v4, $p_a, $site_name, $domain_name, $bank_code, $bank_account, $bank_owner, $p_v1, $p_v2, $p_v3, $p_v4, $p_a]);
        queueToast($toast_queue, "Đã lưu cấu hình Website Đại Lý!", "success");
    } else {
        foreach ($keys as $k) {
            if (isset($_POST[$k])) {
                $stmt = $pdo->prepare("INSERT INTO global_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                $stmt->execute([$k, $_POST[$k], $_POST[$k]]);
            }
        }
        
        if (isset($_FILES['sr_video']) && $_FILES['sr_video']['error'] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['sr_video']['tmp_name'];
            $upload_dir = __DIR__ . '/../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $dest = $upload_dir . 'huong-dan-shadowrocket.mp4';
            move_uploaded_file($tmp_name, $dest);
        }

        if (isset($_FILES['sr_proxy_video']) && $_FILES['sr_proxy_video']['error'] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['sr_proxy_video']['tmp_name'];
            $upload_dir = __DIR__ . '/../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $dest = $upload_dir . 'huong-dan-proxy.mp4';
            move_uploaded_file($tmp_name, $dest);
        }

        if (isset($_FILES['sr_id_apple_video']) && $_FILES['sr_id_apple_video']['error'] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['sr_id_apple_video']['tmp_name'];
            $upload_dir = __DIR__ . '/../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $dest = $upload_dir . 'huong-dan-id-apple.mp4';
            move_uploaded_file($tmp_name, $dest);
        }

        if (isset($_FILES['sr_dns_file']) && $_FILES['sr_dns_file']['error'] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['sr_dns_file']['tmp_name'];
            // DNS file is at the root directory
            $dest = __DIR__ . '/../LocketGold_Premium_DNS.mobileconfig';
            move_uploaded_file($tmp_name, $dest);
        }

        queueToast($toast_queue, "Đã lưu cấu hình và video hướng dẫn!", "success");
    }
}

if ($action === 'agency_save_domain') {
    // Fix: cho phép cả admin và agency lưu domain
    if ($current_role === 'agency' || $current_role === 'admin') {
        $domain_name = trim($_POST['domain_name'] ?? '');
        // Xóa http/https nếu người dùng nhập nhầm
        $domain_name = preg_replace('#^https?://#', '', $domain_name);
        $domain_name = rtrim($domain_name, '/');
        $stmt = $pdo->prepare("INSERT INTO agency_settings (agency_username, domain_name) VALUES (?, ?) ON DUPLICATE KEY UPDATE domain_name = ?");
        $stmt->execute([$current_user, $domain_name, $domain_name]);
        queueToast($toast_queue, "✅ Đã cập nhật tên miền: $domain_name", "success");
    }
}

// Action lưu toàn bộ thông tin agency (site name, bank, giá)
if ($action === 'agency_save_settings') {
    if ($current_role === 'agency' || $current_role === 'admin') {
        $site_name = trim($_POST['site_name'] ?? '');
        $bank_code = trim($_POST['bank_code'] ?? '');
        $bank_account = trim($_POST['bank_account'] ?? '');
        $bank_owner = trim($_POST['bank_owner'] ?? '');
        $price_vip1 = intval($_POST['price_vip1'] ?? 0);
        $price_vip2 = intval($_POST['price_vip2'] ?? 0);
        $price_vip3 = intval($_POST['price_vip3'] ?? 0);
        $price_vip4 = intval($_POST['price_vip4'] ?? 0);
        $price_agency = intval($_POST['price_agency'] ?? 0);

        $stmt = $pdo->prepare("INSERT INTO agency_settings 
            (agency_username, site_name, bank_code, bank_account, bank_owner, price_vip1, price_vip2, price_vip3, price_vip4, price_agency)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                site_name = VALUES(site_name),
                bank_code = VALUES(bank_code),
                bank_account = VALUES(bank_account),
                bank_owner = VALUES(bank_owner),
                price_vip1 = IF(VALUES(price_vip1) > 0, VALUES(price_vip1), price_vip1),
                price_vip2 = IF(VALUES(price_vip2) > 0, VALUES(price_vip2), price_vip2),
                price_vip3 = IF(VALUES(price_vip3) > 0, VALUES(price_vip3), price_vip3),
                price_vip4 = IF(VALUES(price_vip4) > 0, VALUES(price_vip4), price_vip4),
                price_agency = IF(VALUES(price_agency) > 0, VALUES(price_agency), price_agency)
        ");
        $stmt->execute([
            $current_user,
            $site_name,
            $bank_code,
            $bank_account,
            $bank_owner,
            $price_vip1 ?: null,
            $price_vip2 ?: null,
            $price_vip3 ?: null,
            $price_vip4 ?: null,
            $price_agency ?: null
        ]);
        queueToast($toast_queue, "✅ Đã lưu cài đặt website con!", "success");
    }
}



