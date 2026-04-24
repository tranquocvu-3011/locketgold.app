<?php
if (!defined('IN_APP')) die('Access denied');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!function_exists('pushFlashToast')) {
        function pushFlashToast($message, $type = 'info', $duration = 4200)
        {
            $text = trim((string) $message);
            if ($text === '') {
                return;
            }
            $_SESSION['toast_flash'] = $_SESSION['toast_flash'] ?? [];
            $_SESSION['toast_flash'][] = [
                'message' => $text,
                'type' => $type,
                'duration' => (int) $duration,
            ];
        }
    }

    // Bảo mật CSRF — Token-based + Referer check
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $host_without_port = explode(':', $host)[0];
    if (empty($referer) || parse_url($referer, PHP_URL_HOST) !== $host_without_port) {
        die("Bảo mật hệ thống: Yêu cầu không hợp lệ (Lỗi CSRF Referer).");
    }
    // CSRF Token validation
    $submitted_token = $_POST['csrf_token'] ?? '';
    $session_token = $_SESSION['csrf_token'] ?? '';
    if (empty($submitted_token) || empty($session_token) || !hash_equals($session_token, $submitted_token)) {
        die("Bảo mật hệ thống: Phiên làm việc hết hạn hoặc token không hợp lệ. Vui lòng tải lại trang và thử lại.");
    }
    // Chống Spam API / Lỗi Race Condition
    if (isset($_SESSION['last_action']) && time() - $_SESSION['last_action'] < 1 && ($action ?? '') !== 'login') {
        die("<script>alert('Thao tác quá nhanh để chống spam, vui lòng chậm lại!'); window.history.back();</script>");
    }
    $_SESSION['last_action'] = time();

    // --- ADMIN ACTIONS ---
    if ($current_role === 'admin') {
        if ($action === 'admin_add_user') {
            $u = strtolower(htmlspecialchars(trim($_POST['username'] ?? '')));
            $p = trim($_POST['password']);
            $r = normalizeRoleValue($_POST['role'] ?? 'user');
            if (!in_array($r, ['user', 'vip1', 'vip2', 'vip3', 'vip4', 'agency', 'admin'], true)) {
                $r = 'user';
            }
            $exp = getRoleExpiryAt($r);
            
            // Ép buộc admin cũng phải nhập đúng nguyên tắc Gmail
            if ($u && $p) {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?"); $stmt->execute([$u]);
                if (!$stmt->fetch()) {
                    $hash = password_hash($p, PASSWORD_BCRYPT);
                    $pdo->prepare("INSERT INTO users (username, password, role, role_expires_at) VALUES (?, ?, ?, ?)")->execute([$u, $hash, $r, $exp]);
                    pushFlashToast("Đã thêm tài khoản mới thành công.", 'success');
                }
            } else {
                $_SESSION['error_msg'] = "Lỗi: Vui lòng nhập đầy đủ thông tin tài khoản và mật khẩu";
            }
            header("Location: /admin?tab=users"); exit;
        }
        if ($action === 'admin_edit_user') {
            $id = intval($_POST['user_id']);
            $r = normalizeRoleValue($_POST['role'] ?? 'user');
            if (!in_array($r, ['user', 'vip1', 'vip2', 'vip3', 'vip4', 'agency', 'admin'], true)) {
                $r = 'user';
            }
            $p = trim($_POST['password']);
            $exp = getRoleExpiryAt($r);
            if ($id) {
                if ($p) {
                    $hash = password_hash($p, PASSWORD_BCRYPT);
                    $pdo->prepare("UPDATE users SET role = ?, password = ?, role_expires_at = ?, is_vip_notified = 0 WHERE id = ?")->execute([$r, $hash, $exp, $id]);
                } else {
                    $pdo->prepare("UPDATE users SET role = ?, role_expires_at = ?, is_vip_notified = 0 WHERE id = ?")->execute([$r, $exp, $id]);
                }
                pushFlashToast("Đã cập nhật tài khoản thành công.", 'success');
            }
            header("Location: /admin?tab=users"); exit;
        }
        if ($action === 'admin_del_user') {
            $id = intval($_POST['user_id']);
            if ($id) {
                $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
                pushFlashToast("Đã xóa tài khoản.", 'success');
            }
            header("Location: /admin?tab=users"); exit;
        }
        if ($action === 'admin_del_act') {
            $id = intval($_POST['act_id']);
            if ($id) {
                $pdo->prepare("DELETE FROM activations WHERE id = ?")->execute([$id]);
                pushFlashToast("Đã xóa bản ghi kích hoạt.", 'success');
            }
            header("Location: /admin?tab=activations"); exit;
        }
        if ($action === 'admin_add_contact') {
            $name = trim($_POST['c_name']);
            $url = trim($_POST['c_url']);
            $type = trim($_POST['c_type']);
            if ($name && $url) {
                $pdo->prepare("INSERT INTO contacts (platform_name, link_url, type) VALUES (?, ?, ?)")->execute([$name, $url, $type]);
                pushFlashToast("Đã thêm kênh liên hệ.", 'success');
            }
            header("Location: /admin?tab=contacts"); exit;
        }
        if ($action === 'admin_del_contact') {
            $id = intval($_POST['c_id']);
            if ($id) {
                $pdo->prepare("DELETE FROM contacts WHERE id = ?")->execute([$id]);
                pushFlashToast("Đã xóa kênh liên hệ.", 'success');
            }
            header("Location: /admin?tab=contacts"); exit;
        }
        if ($action === 'admin_save_notice') {
            $keys = ['notice_active', 'notice_title', 'notice_content', 'notice_link', 'notice_btn_text', 'notice_bg', 'notice_text', 'zalo_baohanh_url', 'zalo_hoidap_url'];
            $stmt = $pdo->prepare("REPLACE INTO global_settings (setting_key, setting_value) VALUES (?, ?)");
            foreach ($keys as $k) {
                $v = $_POST[$k] ?? '';
                $stmt->execute([$k, rtrim($v)]);
            }
            pushFlashToast("Đã lưu cài đặt thông báo.", 'success');
            header("Location: /admin?tab=settings"); exit;
        }
        
        if ($action === 'admin_save_receipt_pool') {
            $stmt = $pdo->prepare("REPLACE INTO global_settings (setting_key, setting_value) VALUES (?, ?)");
            $stmt->execute(['premium_receipts', trim($_POST['premium_receipts'] ?? '')]);
            pushFlashToast("Đã lưu danh sách Hồ chứa hóa đơn (Receipt Pool).", 'success');
            header("Location: /admin?tab=settings"); exit;
        }

        // Upload video hướng dẫn theo mục
        if (in_array($action, ['admin_upload_guide_video', 'admin_upload_guide_video_activation', 'admin_upload_guide_video_recovery'], true)) {
            $setting_key = 'guide_video_activation';
            if ($action === 'admin_upload_guide_video_recovery') {
                $setting_key = 'guide_video_recovery';
            }

            if (isset($_FILES['guide_video'])) {
                if ($_FILES['guide_video']['error'] !== UPLOAD_ERR_OK) {
                    $upload_errors = [
                        UPLOAD_ERR_INI_SIZE => 'Dung lượng file vượt quá giới hạn cấu hình server (post_max_size/upload_max_filesize).',
                        UPLOAD_ERR_FORM_SIZE => 'Dung lượng file quá lớn.',
                        UPLOAD_ERR_PARTIAL => 'File upload bị gián đoạn.',
                        UPLOAD_ERR_NO_FILE => 'Vui lòng chọn file video để upload.',
                        UPLOAD_ERR_NO_TMP_DIR => 'Lỗi máy chủ: Thiếu thư mục lưu tạm.',
                        UPLOAD_ERR_CANT_WRITE => 'Lỗi máy chủ: Không có quyền ghi file.',
                        UPLOAD_ERR_EXTENSION => 'Lỗi máy chủ: Bị chặn bởi cấu hình PHP.'
                    ];
                    $_SESSION['error_msg'] = $upload_errors[$_FILES['guide_video']['error']] ?? 'Lỗi hệ thống không xác định.';
                } else {
                    $file = $_FILES['guide_video'];
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $allowed = ['mp4', 'webm', 'mov', 'avi', 'mkv'];

                    if (!in_array($ext, $allowed)) {
                        $_SESSION['error_msg'] = "Định dạng không hợp lệ. Chỉ chấp nhận: " . implode(', ', $allowed);
                    } else {
                        $is_valid = true;
                        if (function_exists('finfo_open')) {
                            $finfo = finfo_open(FILEINFO_MIME_TYPE);
                            $mime = finfo_file($finfo, $file['tmp_name']);
                            finfo_close($finfo);
                            $allowed_mimes = ['video/mp4', 'video/webm', 'video/quicktime', 'video/x-msvideo', 'video/x-matroska', 'application/octet-stream'];
                            if (!in_array($mime, $allowed_mimes)) {
                                $is_valid = false;
                                $_SESSION['error_msg'] = "File không phải video hợp lệ (MIME: $mime).";
                            }
                        }

                        if ($is_valid) {
                            $upload_dir = __DIR__ . '/../uploads/';
                            if (!is_dir($upload_dir)) {
                                mkdir($upload_dir, 0755, true);
                            }

                            $old = $pdo->prepare("SELECT setting_value FROM global_settings WHERE setting_key = ?");
                            $old->execute([$setting_key]);
                            $old_path = $old->fetchColumn();
                            if ($old_path && file_exists(__DIR__ . '/../' . $old_path)) {
                                @unlink(__DIR__ . '/../' . $old_path);
                            }

                            $suffix = $setting_key === 'guide_video_recovery' ? 'recovery' : 'activation';
                            $filename = 'guide_' . $suffix . '_' . time() . '.' . $ext;
                            $dest = $upload_dir . $filename;

                            if (move_uploaded_file($file['tmp_name'], $dest)) {
                                $path = 'uploads/' . $filename;
                                $pdo->prepare("REPLACE INTO global_settings (setting_key, setting_value) VALUES (?, ?)")->execute([$setting_key, $path]);
                                if ($setting_key === 'guide_video_activation') {
                                    $pdo->prepare("REPLACE INTO global_settings (setting_key, setting_value) VALUES ('guide_video', ?)")->execute([$path]);
                                    pushFlashToast("Đã tải lên video cho mục Sử dụng & Kích hoạt.", 'success');
                                } else {
                                    pushFlashToast("Đã tải lên video cho mục Khắc phục mất Gold.", 'success');
                                }
                            } else {
                                $_SESSION['error_msg'] = "Lỗi khi ghi file vào thư mục uploads. Vui lòng kiểm tra phân quyền (CHMOD) của thư mục.";
                            }
                        }
                    }
                }
            } else {
                $_SESSION['error_msg'] = "Hệ thống không nhận được dữ liệu tải lên.";
            }
            header("Location: /admin?tab=settings"); exit;
        }

        // Xóa video hướng dẫn theo mục
        if (in_array($action, ['admin_delete_guide_video', 'admin_delete_guide_video_activation', 'admin_delete_guide_video_recovery'], true)) {
            $setting_key = 'guide_video_activation';
            if ($action === 'admin_delete_guide_video_recovery') {
                $setting_key = 'guide_video_recovery';
            }

            $old = $pdo->prepare("SELECT setting_value FROM global_settings WHERE setting_key = ?");
            $old->execute([$setting_key]);
            $old_path = $old->fetchColumn();
            if ($old_path && file_exists(__DIR__ . '/../' . $old_path)) {
                unlink(__DIR__ . '/../' . $old_path);
            }
            $pdo->prepare("DELETE FROM global_settings WHERE setting_key = ?")->execute([$setting_key]);

            if ($setting_key === 'guide_video_activation') {
                $pdo->exec("DELETE FROM global_settings WHERE setting_key = 'guide_video'");
                pushFlashToast("Đã xóa video mục Sử dụng & kích hoạt.", 'success');
            } else {
                pushFlashToast("Đã xóa video mục Khắc phục mất Gold.", 'success');
            }
            header("Location: /admin?tab=settings"); exit;
        }
    }
    
    // --- AGENCY ACTIONS ---
    if ($current_role === 'agency' && $action === 'agency_del_act') {
        $id = intval($_POST['act_id']);
        if ($id) {
            $pdo->prepare("DELETE FROM activations WHERE id = ? AND injected_by = ?")->execute([$id, $current_user]);
            pushFlashToast("Đã xóa bản ghi kích hoạt.", 'success');
        }
        header("Location: /lich-su"); exit;
    }
    // ----------------------


    if ($action === 'set_role' && $current_role === 'admin') {
        $target_user = $_POST['target_user'] ?? '';
        $new_role = normalizeRoleValue($_POST['new_role'] ?? 'user');
        if (!in_array($new_role, ['user', 'vip1', 'vip2', 'vip3', 'vip4', 'agency', 'admin'], true)) {
            $new_role = 'user';
        }
        if ($target_user) {
            $pdo->prepare("UPDATE users SET role = ?, role_expires_at = ?, is_vip_notified = 0 WHERE username = ?")->execute([$new_role, getRoleExpiryAt($new_role), $target_user]);
            header("Location: /lich-su");
            exit;
        }
    }

    if ($action === 'change_password' && $current_user) {
        $old_pass = trim($_POST['old_password'] ?? '');
        $new_pass = trim($_POST['new_password'] ?? '');
        $stmt = $pdo->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->execute([$current_user]);
        $hash = $stmt->fetchColumn();
        
        if (password_verify($old_pass, $hash)) {
            if (strlen($new_pass) < 6) {
                pushFlashToast("Mật khẩu mới phải có ít nhất 6 ký tự.", 'error');
            } else {
                $new_hash = password_hash($new_pass, PASSWORD_BCRYPT);
                $pdo->prepare("UPDATE users SET password = ? WHERE username = ?")->execute([$new_hash, $current_user]);
                pushFlashToast("Đổi mật khẩu thành công!", 'success');
            }
        } else {
            pushFlashToast("Mật khẩu cũ không chính xác.", 'error');
        }
        header("Location: /quan-ly-tai-khoan");
        exit;
    }

    if ($action === 'update_ref_code') {
        $new_ref = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', trim($_POST['new_ref_code'] ?? '')));
        if (strlen($new_ref) < 4 || strlen($new_ref) > 10) {
            pushFlashToast("Mã giới thiệu phải từ 4-10 ký tự chữ và số.", 'error');
        } else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE ref_code = ? AND username != ?");
            $stmt->execute([$new_ref, $current_user]);
            if ($stmt->fetch()) {
                pushFlashToast("Mã giới thiệu này đã có người sử dụng. Vui lòng chọn mã khác.", 'error');
            } else {
                $pdo->prepare("UPDATE users SET ref_code = ? WHERE username = ?")->execute([$new_ref, $current_user]);
                pushFlashToast("Cập nhật mã giới thiệu thành công!", 'success');
            }
        }
        header("Location: /quan-ly-tai-khoan");
        exit;
    }

    if ($action === 'register') {
        $username = trim($_POST['username'] ?? '');
        // Loại bỏ dấu tiếng Việt (chữ thường)
        $username = preg_replace('/[àáảãạăằắẳẵặâầấẩẫậ]/u', 'a', $username);
        $username = preg_replace('/[đ]/u', 'd', $username);
        $username = preg_replace('/[èéẻẽẹêềếểễệ]/u', 'e', $username);
        $username = preg_replace('/[ìíỉĩị]/u', 'i', $username);
        $username = preg_replace('/[òóỏõọôồốổỗộơờớởỡợ]/u', 'o', $username);
        $username = preg_replace('/[ùúủũụưừứửữự]/u', 'u', $username);
        $username = preg_replace('/[ỳýỷỹỵ]/u', 'y', $username);
        // Loại bỏ dấu tiếng Việt (chữ hoa)
        $username = preg_replace('/[ÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬ]/u', 'A', $username);
        $username = preg_replace('/[Đ]/u', 'D', $username);
        $username = preg_replace('/[ÈÉẺẼẸÊỀẾỂỄỆ]/u', 'E', $username);
        $username = preg_replace('/[ÌÍỈĨỊ]/u', 'I', $username);
        $username = preg_replace('/[ÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢ]/u', 'O', $username);
        $username = preg_replace('/[ÙÚỦŨỤƯỪỨỬỮỰ]/u', 'U', $username);
        $username = preg_replace('/[ỲÝỶỸỴ]/u', 'Y', $username);
        // Chỉ giữ lại a-z, A-Z, 0-9, dấu chấm, gạch dưới
        $username = preg_replace('/[^a-zA-Z0-9._]/', '', $username);
        $username = htmlspecialchars($username);
        $password = trim($_POST['password'] ?? '');
        $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
        $referred_by = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', trim($_POST['referred_by'] ?? '')));
        $register_ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $register_ip = explode(',', $register_ip)[0]; // Lấy IP gốc nếu qua proxy
        $register_ip = trim($register_ip);

        if (empty($username) || empty($password) || empty($phone)) {
            $auth_msg = "Vui lòng nhập đầy đủ thông tin (Kèm SĐT).";
        } elseif (strlen($username) < 3 || strlen($username) > 30) {
            $auth_msg = "Tài khoản đăng nhập phải từ 3-30 ký tự.";
        } elseif (!preg_match('/^[a-zA-Z0-9._]+$/', $username)) {
            $auth_msg = "Tài khoản đăng nhập chỉ được dùng chữ cái (a-z, A-Z), số (0-9), dấu chấm (.) và gạch dưới (_). Không dấu, không khoảng trắng.";
        } elseif (strlen($password) < 6) {
            $auth_msg = "Mật khẩu quá ngắn, vui lòng nhập ít nhất 6 ký tự.";
        } elseif (!preg_match('/^(0|\+?84)(3|5|7|8|9)[0-9]{8}$/', $phone)) {
            $auth_msg = "Số điện thoại không hợp lệ (Bắt buộc phải là dạng 09xx, 03xx, 08xx...)";
        } else {
            // Kiểm tra giới hạn 2 tài khoản / IP
            $ip_stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE register_ip = ?");
            $ip_stmt->execute([$register_ip]);
            $ip_count = (int)$ip_stmt->fetchColumn();
            
            if ($ip_count >= 2) {
                $auth_msg = "Mỗi địa chỉ IP chỉ được phép đăng ký tối đa 2 tài khoản. Vui lòng liên hệ Admin nếu cần hỗ trợ.";
            } else {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?"); $stmt->execute([$username]);
                if ($stmt->fetch()) $auth_msg = "Tài khoản đăng nhập này đã tồn tại trên hệ thống.";
                else {
                    if (!empty($referred_by)) {
                        $ref_check = $pdo->prepare("SELECT id FROM users WHERE ref_code = ?");
                        $ref_check->execute([$referred_by]);
                        if (!$ref_check->fetch()) {
                            $referred_by = NULL;
                        }
                    } else {
                        $referred_by = NULL;
                    }
                    
                    $ref_code = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6);
                    $check_ref = $pdo->prepare("SELECT id FROM users WHERE ref_code = ?");
                    $check_ref->execute([$ref_code]);
                    while($check_ref->fetch()) {
                        $ref_code = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6);
                        $check_ref->execute([$ref_code]);
                    }

                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $role = 'user'; // Mặc định 100% tài khoản đăng ký mới là Thành viên
                    if ($pdo->prepare("INSERT INTO users (username, password, role, phone, register_ip, ref_code, referred_by) VALUES (?, ?, ?, ?, ?, ?, ?)")->execute([$username, $hash, $role, $phone, $register_ip, $ref_code, $referred_by])) {
                        $auth_msg = "Tạo tài khoản thành công!"; $auth_msg_type = 'success'; $_SESSION['user'] = $username;
                        pushFlashToast("Đăng ký thành công. Chào mừng bạn đến với Locket Gold!", 'success', 4600);
                        echo "<script>setTimeout(()=>window.location='/trang-chu', 1200);</script>";
                    } else $auth_msg = "Lỗi hệ thống.";
                }
            }
        }
    }
    if ($action === 'login') {
        $username = trim($_POST['username']); 
        $password = trim($_POST['password']);
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?"); $stmt->execute([$username]);
        $user_data = $stmt->fetch();
        if ($user_data && password_verify($password, $user_data['password'])) { 
            session_regenerate_id(true); 
            $_SESSION['user'] = $user_data['username']; 
            pushFlashToast("Đăng nhập thành công. Chúc bạn thao tác thuận lợi!", 'success', 4200);
            header("Location: /trang-chu"); 
            exit; 
        } else {
            sleep(1);
            $auth_msg = "Sai tài khoản hoặc mật khẩu.";
        }
    }
    
    if ($action === 'admin_login') {
        $username = trim($_POST['username']); 
        $password = trim($_POST['password']);
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?"); $stmt->execute([$username]);
        $user_data = $stmt->fetch();
        if ($user_data && password_verify($password, $user_data['password'])) { 
            if (in_array(strtolower($user_data['role']), ['admin', 'agency'])) {
                session_regenerate_id(true); 
                $_SESSION['user'] = $user_data['username']; 
                pushFlashToast("Đăng nhập quản trị thành công!", 'success', 4200);
                header("Location: /admin"); 
                exit; 
            } else {
                sleep(1);
                pushFlashToast("Tài khoản không có quyền truy cập!", 'error');
            }
        } else {
            sleep(1);
            pushFlashToast("Sai tài khoản hoặc mật khẩu.", 'error');
        }
    }

    // ═══ INJECT HANDLER — Kích hoạt Gold ═══
    if ($action === 'inject' && $current_user) {
        $inputType = $_POST['inputType'] ?? 'id'; 
        $injectMode = $_POST['injectMode'] ?? 'normal';
        $inputValue = trim($_POST['inputValue'] ?? '');
        $role_limits = ['user'=>1,'vip'=>1,'vip1'=>1,'vip2'=>2,'vip3'=>3,'vip4'=>10,'agency'=>999999,'admin'=>999999];
        $my_limit = $role_limits[$current_role] ?? 0;
        
        if ($current_role === 'user') {
            $inject_status = 'error'; 
            $inject_msg = 'Chương trình dùng thử miễn phí đã kết thúc. Vui lòng nâng cấp tài khoản lên VIP để tiếp tục sử dụng dịch vụ.';
        } elseif (empty($inputValue)) {
            $inject_status = 'error';
            $inject_msg = 'Vui lòng nhập ID hoặc Link.';
        } else {
            $original_uid = trim($inputValue);
            $is_url = filter_var($original_uid, FILTER_VALIDATE_URL) || strpos($original_uid, 'locket.cam') !== false;
            
            $clean_uid = $original_uid;
            if ($is_url) {
                $parsed = parse_url($original_uid, PHP_URL_PATH);
                $clean_uid = $parsed ? basename($parsed) : $original_uid;
            }
            $clean_uid = str_replace(['@', '/u/', 'u/'], '', $clean_uid);
            $clean_uid = htmlspecialchars($clean_uid);

            // Pre-resolve UID từ cache (DB lưu injected_uid 28 ký tự, user nhập username)
            $resolved_uid = null;
            try {
                $rc = $pdo->prepare("SELECT uid_result FROM resolved_cache WHERE username = ? AND expires_at > NOW()");
                $rc->execute([$original_uid]);
                $resolved_uid = $rc->fetchColumn() ?: null;
            } catch(Exception $e) {}

            if ($injectMode === 'reactivate') {
                if ($current_role === 'user') {
                    $inject_status = 'error';
                    $inject_msg = 'Tài khoản thường không được hỗ trợ bảo hành. Vui lòng nâng cấp VIP.';
                } else {
                    $reStmt = $pdo->prepare("SELECT 1 FROM activations WHERE injected_by = ? AND (uid = ? OR uid = ?) LIMIT 1");
                    $reStmt->execute([$current_user, $original_uid, $resolved_uid ?? $clean_uid]);
                    if (!$reStmt->fetchColumn()) {
                        $inject_status = 'error';
                        $inject_msg = 'Chỉ được kích hoạt lại ID đã từng kích hoạt bằng chính tài khoản này.';
                    }
                }
            }

            // Quota: chỉ đếm slot đang ACTIVE Live
            $stmt = $pdo->prepare("SELECT DISTINCT uid FROM activations WHERE injected_by = ? AND status = 'Activated (Live)'");
            $stmt->execute([$current_user]);
            $used_uids = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $uid_already_used = in_array($original_uid, $used_uids) || ($resolved_uid && in_array($resolved_uid, $used_uids)) || in_array($clean_uid, $used_uids);
            
            if ($inject_status !== 'error' && !$uid_already_used && count($used_uids) >= $my_limit) {
                $inject_status = 'error';
                $inject_msg = "Hạn ngạch đã hết ($my_limit ID). Vui lòng nâng cấp gói VIP.";
            } elseif ($inject_status !== 'error') {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
                $ip = explode(',', $ip)[0];
                try { $pdo->exec("DELETE FROM rate_limits WHERE expires_at < NOW()"); } catch(Exception $e){}
                $stmt = $pdo->prepare("SELECT SUM(request_count) FROM rate_limits WHERE ip_address = ? OR username = ?");
                $stmt->execute([$ip, $current_user]);
                $count = (int)$stmt->fetchColumn();
                
                if ($count >= 5) {
                    $inject_status = 'error'; 
                    $inject_msg = 'Hệ thống đang chống Spam. Vui lòng chậm lại và thử sau 1 phút!';
                } else {
                    $pdo->prepare("INSERT INTO rate_limits (ip_address, username, request_count, expires_at) VALUES (?, ?, 1, DATE_ADD(NOW(), INTERVAL 1 MINUTE))")
                        ->execute([$ip, $current_user]);
                        
                        if ($current_role === 'user') {
                            $inject_status = 'error';
                            $inject_msg = 'Vui lòng nâng cấp VIP để tiếp tục!';
                        } else {
                            $final_status = 'Activated (Live)';
                            try {
                            $injected_uid = $clean_uid;
                            if (!(strlen($clean_uid) === 28 && preg_match('/^[A-Za-z0-9_-]{28}$/', $clean_uid))) {
                                if ($resolved_uid) {
                                    $injected_uid = $resolved_uid;
                                } else {
                                    $fetch_url = $original_uid;
                                    if (!$is_url) {
                                        $fetch_url = "https://locket.cam/" . urlencode($clean_uid);
                                    } elseif (!preg_match('/^https?:\/\//', $fetch_url)) {
                                        $fetch_url = 'https://' . $fetch_url;
                                    }

                                    $ch = curl_init($fetch_url);
                                    curl_setopt_array($ch, [
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_USERAGENT => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15',
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_TIMEOUT => 15,
                                        CURLOPT_SSL_VERIFYPEER => false,
                                    ]);
                                    $html = curl_exec($ch);
                                    $resolve_http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                    curl_close($ch);
                                    
                                    if ($resolve_http === 200 && (
                                        preg_match('/users%2F([A-Za-z0-9_-]{28})%2Fpublic/', $html, $matches) ||
                                        preg_match('/invites%2F([A-Za-z0-9_-]{28})/', $html, $matches) ||
                                        preg_match('/invite\/([A-Za-z0-9_-]{28})/', $html, $matches)
                                    )) {
                                        $injected_uid = htmlspecialchars($matches[1]);
                                        $pdo->prepare("INSERT INTO resolved_cache (username, uid_result, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 DAY))
                                            ON DUPLICATE KEY UPDATE uid_result = ?, expires_at = DATE_ADD(NOW(), INTERVAL 30 DAY)")
                                            ->execute([$original_uid, $injected_uid, $injected_uid]);
                                    } else {
                                        throw new Exception("Không tìm thấy UID từ Username/Link này. Tài khoản có thể chưa được tạo hoặc link không hợp lệ.");
                                    }
                                }
                            }
                            
                            // ── RECEIPT MAPPING 1:1 — Chống mất Gold ──
                            $selected_receipt = APPLE_RECEIPT_BASE64;
                            try {
                                $stmt_r = $pdo->query("SELECT setting_value FROM global_settings WHERE setting_key = 'premium_receipts'");
                                $r_val = $stmt_r->fetchColumn();
                                if ($r_val) {
                                    $r_arr = array_values(array_filter(array_map('trim', explode("\n", $r_val))));
                                    if (!empty($r_arr)) {
                                        // 1. Kiểm tra UID này đã được gán receipt chưa
                                        $stmt_check = $pdo->prepare("SELECT receipt_index FROM receipt_assignments WHERE assigned_uid = ? AND is_active = 1 LIMIT 1");
                                        $stmt_check->execute([$injected_uid]);
                                        $existing = $stmt_check->fetch();

                                        if ($existing && isset($r_arr[$existing['receipt_index']])) {
                                            // Dùng lại receipt đã gán trước đó (ổn định, không collision)
                                            $selected_receipt = $r_arr[$existing['receipt_index']];
                                            $pdo->prepare("UPDATE receipt_assignments SET last_used_at = NOW(), use_count = use_count + 1 WHERE assigned_uid = ?")
                                                ->execute([$injected_uid]);
                                        } else {
                                            // 2. Tìm receipt chưa gán cho ai (ưu tiên nhất)
                                            $assigned_indexes = [];
                                            $stmt_used = $pdo->query("SELECT DISTINCT receipt_index FROM receipt_assignments WHERE is_active = 1 AND assigned_uid IS NOT NULL");
                                            while ($row = $stmt_used->fetch()) {
                                                $assigned_indexes[] = (int)$row['receipt_index'];
                                            }

                                            $free_index = null;
                                            for ($i = 0; $i < count($r_arr); $i++) {
                                                if (!in_array($i, $assigned_indexes)) {
                                                    $free_index = $i;
                                                    break;
                                                }
                                            }

                                            if ($free_index !== null) {
                                                // Có receipt trống → gán cho UID này
                                                $chosen_index = $free_index;
                                            } else {
                                                // 3. Hết receipt trống → chọn receipt ÍT user nhất
                                                $stmt_least = $pdo->query("SELECT receipt_index, COUNT(*) as cnt FROM receipt_assignments WHERE is_active = 1 GROUP BY receipt_index ORDER BY cnt ASC LIMIT 1");
                                                $least = $stmt_least->fetch();
                                                $chosen_index = $least ? (int)$least['receipt_index'] : 0;
                                            }

                                            $selected_receipt = $r_arr[$chosen_index] ?? $r_arr[0];
                                            $receipt_hash = hash('sha256', $selected_receipt);

                                            // Xóa assignment cũ nếu có (cleanup)
                                            $pdo->prepare("DELETE FROM receipt_assignments WHERE assigned_uid = ?")->execute([$injected_uid]);

                                            // Gán receipt mới
                                            $pdo->prepare("INSERT INTO receipt_assignments (receipt_hash, receipt_index, assigned_uid, assigned_by, assigned_at, last_used_at, use_count) VALUES (?, ?, ?, ?, NOW(), NOW(), 1)")
                                                ->execute([$receipt_hash, $chosen_index, $injected_uid, $current_user]);
                                        }
                                    }
                                }
                            } catch (Exception $e) {}
                            // ─────────────────────────

                            $is_demo_mode = (RC_API_KEY === 'ĐIỀN_API_KEY_PUBLIC_CỦA_LOCKET_VÀO_ĐÂY'
                                || $selected_receipt === 'ĐIỀN_RECEIPT_BASE64_TỪ_CHARLES_PROXY_VÀO_ĐÂY');
                            if ($is_demo_mode) {
                                $api_result = 'demo';
                            } else {
                                $curl = curl_init();
                                curl_setopt_array($curl, [
                                    CURLOPT_URL            => "https://api.revenuecat.com/v1/receipts",
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_TIMEOUT        => 20,
                                    CURLOPT_CUSTOMREQUEST  => "POST",
                                    CURLOPT_POSTFIELDS     => json_encode([
                                        "app_user_id"  => $injected_uid,
                                        "fetch_token"  => $selected_receipt
                                    ]),
                                    CURLOPT_HTTPHEADER     => [
                                        "Authorization: Bearer " . RC_API_KEY,
                                        "Content-Type: application/json",
                                        "X-Platform: iOS"
                                    ],
                                    CURLOPT_SSL_VERIFYPEER => false,
                                ]);
                                $response = curl_exec($curl);
                                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                $curl_err = curl_error($curl);
                                curl_close($curl);
                                if ($httpcode == 200 || $httpcode == 201) {
                                    $api_result = 'success';
                                } else {
                                    $decoded = json_decode($response, true);
                                    $err_msg = $decoded['message'] ?? ($curl_err ? "cURL Error: " . $curl_err : "HTTP $httpcode");
                                    throw new Exception("Lỗi API kích hoạt: " . htmlspecialchars($err_msg));
                                }
                            }
                            
                            $cc = $pdo->prepare("SELECT id FROM activations WHERE (uid = ? OR uid = ?) AND injected_by = ? LIMIT 1"); 
                            $cc->execute([$original_uid, $injected_uid, $current_user]);
                            if($cc->fetch()) {
                                $pdo->prepare("UPDATE activations SET uid = ?, status = ?, job_status = 'completed', attempts = 1, error_log = NULL, updated_at = NOW() WHERE (uid = ? OR uid = ?) AND injected_by = ?")
                                    ->execute([$original_uid, $final_status, $original_uid, $injected_uid, $current_user]);
                            } else {
                                $pdo->prepare("INSERT INTO activations (uid, status, injected_by, job_status) VALUES (?, ?, ?, 'completed')")
                                    ->execute([$original_uid, $final_status, $current_user]);
                            }
                            
                            $inject_status = 'success';
                            $inject_msg = 'Kích hoạt Gold thành công! Tài khoản Locket đã được đồng bộ.';
                        } catch (Exception $ex) {
                            $inject_status = 'error';
                            $inject_msg = $ex->getMessage();
                            try {
                                $pdo->prepare("INSERT INTO activations (uid, status, injected_by, job_status, error_log) VALUES (?, 'FAILED (ERROR)', ?, 'failed', ?)")
                                    ->execute([$original_uid, $current_user, $ex->getMessage()]);
                            } catch(Exception $e2) {}
                        }
                    }
                }
            }
        }
    }
}

