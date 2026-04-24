<!-- ═══════ THANH TOÁN ═══════ -->
        <?php if ($page === 'payment'):
            if (!$current_user) {
                // Not logged in -> Redirect
                header("Location: /dang-nhap");
                exit;
            }

            $plan = $_GET['plan'] ?? 'Gói Dịch Vụ';
            $price = (int) ($_GET['price'] ?? 0);

            $checkout_target = $_GET['checkout'] ?? inferCheckoutTargetFromLegacyPlan($_GET['plan'] ?? '');
            $checkout = resolveCheckoutSelection($settings, $current_role, $checkout_target);
            if (!$checkout) {
                $_SESSION['toast_flash'] = $_SESSION['toast_flash'] ?? [];
                $_SESSION['toast_flash'][] = [
                    'type' => 'warning',
                    'message' => 'Gói thanh toán không hợp lệ hoặc không còn phù hợp với cấp tài khoản hiện tại.',
                    'duration' => 4200,
                ];
                header("Location: /dich-vu-vip");
                exit;
            }
            $plan = $checkout['plan_label'];
            $price = (int) $checkout['amount'];

            // Get Bank Settings
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM global_settings");
            $gs = [];
            foreach ($stmt->fetchAll() as $row) {
                $gs[$row['setting_key']] = $row['setting_value'];
            }
            $bank_code = $gs['bank_code'] ?? '';
            $bank_account = $gs['bank_account'] ?? '';
            $bank_owner = $gs['bank_owner'] ?? '';
            $addInfo = explode('@', trim($current_user))[0]; // Bỏ @gmail.com để nội dung VietQR sạch sẽ, không bị ngân hàng chặn
        
            // Generate VietQR URL
            $qr_url = "https://img.vietqr.io/image/{$bank_code}-{$bank_account}-compact2.png?amount=$price&addInfo=" . urlencode($addInfo) . "&accountName=" . urlencode($bank_owner);
            ?>
            <div class="page-shell"
                style="display:flex; flex-direction:column; align-items:center; gap:24px; max-width:600px; margin:0 auto;">
                <div class="text-center mb-md" style="animation:cardIn 0.5s ease forwards;">
                    <h1 class="page-title" style="font-size:32px; margin-bottom:12px;">Hóa Đơn Thanh Toán</h1>
                    <p class="hero-sub" style="margin-bottom:0;">Quét mã QR bằng ứng dụng ngân hàng để thanh toán tự động.
                    </p>
                </div>

                <div class="card card-wide"
                    style="width:100%; border:2px dashed var(--border-accent); box-shadow:0 20px 40px rgba(0,0,0,0.1); display:flex; flex-direction:column; align-items:center; padding:32px 24px; border-radius:24px; position:relative; overflow:hidden;">

                    <!-- Decor -->
                    <div
                        style="position:absolute; top:0; left:0; width:100%; height:6px; background:linear-gradient(90deg, #3b82f6, #10b981);">
                    </div>

                    <?php if (!$bank_code || !$bank_account): ?>
                        <div style="color:#ef4444; font-weight:bold; margin-bottom:20px; text-align:center;">
                            Admin chưa cài đặt thông tin Ngân hàng. Vui lòng liên hệ Admin!
                        </div>
                    <?php else: ?>
                        <div
                            style="background:#fff; padding:16px; border-radius:16px; margin-bottom:24px; box-shadow:0 8px 24px rgba(0,0,0,0.05);">
                            <img src="<?= htmlspecialchars($qr_url) ?>" alt="VietQR"
                                style="width:280px; height:auto; display:block; border-radius:8px;">
                        </div>

                        <div style="width:100%; background:var(--bg-0); padding:20px; border-radius:16px; margin-bottom:24px;">
                            <div style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:14px;">
                                <span style="color:var(--text-2);">Gói dịch vụ:</span>
                                <span style="font-weight:700; color:var(--text-0);"><?= htmlspecialchars($plan) ?></span>
                            </div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:14px;">
                                <span style="color:var(--text-2);">Tài khoản đăng nhập:</span>
                                <span
                                    style="font-weight:700; color:var(--text-0);"><?= htmlspecialchars($current_user) ?></span>
                            </div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:14px;">
                                <span style="color:var(--text-2);">Chủ tài khoản:</span>
                                <span
                                    style="font-weight:700; color:var(--text-0); text-transform:uppercase;"><?= htmlspecialchars($bank_owner) ?></span>
                            </div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:14px;">
                                <span style="color:var(--text-2);">Ngân hàng:</span>
                                <span
                                    style="font-weight:700; color:var(--text-0); text-transform:uppercase;"><?= htmlspecialchars($bank_code) ?></span>
                            </div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:14px;">
                                <span style="color:var(--text-2);">Số tài khoản:</span>
                                <span
                                    style="font-weight:700; color:var(--accent-bright); font-size:16px; letter-spacing:1px;"><?= htmlspecialchars($bank_account) ?></span>
                            </div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:14px;">
                                <span style="color:var(--text-2);">Nội dung (Bắt buộc):</span>
                                <span
                                    style="font-weight:700; color:#10b981; font-size:15px;"><?= htmlspecialchars($addInfo) ?></span>
                            </div>
                            <div style="height:1px; background:var(--border); margin:16px 0;"></div>
                            <div style="display:flex; justify-content:space-between; font-size:18px;">
                                <span style="color:var(--text-1); font-weight:600;">Tổng thanh toán:</span>
                                <span style="font-weight:900; color:#ef4444;"><?= number_format($price) ?>đ</span>
                            </div>
                        </div>

                        <!-- Upload Form -->
                        <div
                            style="width:100%; background:var(--bg-2); padding:20px; border-radius:16px; border:1px solid var(--border); margin-bottom:24px;">
                            <h3 style="font-size:15px; margin-bottom:10px; color:var(--text-0);">Xác nhận thanh toán</h3>
                            <p style="font-size:13px; color:var(--text-2); margin-bottom:15px;">Sau khi thanh toán thành công,
                                vui lòng tải lên hình ảnh chụp màn hình biên lai chuyển khoản để tự động kích hoạt gói.</p>
                            <form action="/thanh-toan" method="POST" enctype="multipart/form-data"
                                style="display:flex; flex-direction:column; gap:12px;">
                                <input type="hidden" name="action" value="upload_receipt">
                                <?= csrf_field() ?>
                                <input type="hidden" name="checkout_target"
                                    value="<?= htmlspecialchars($checkout['target_role']) ?>">
                                <input type="hidden" name="checkout_plan"
                                    value="<?= htmlspecialchars($checkout['plan_label']) ?>">
                                <input type="file" name="receipt_image" accept="image/*" required class="input"
                                    style="padding:10px; border:1px dashed var(--border-accent); background:var(--bg-0);">
                                <button type="submit" class="btn btn-primary"
                                    style="border-radius:var(--radius-full); justify-content:center; padding:16px; font-size:16px;">
                                    Tải lên hóa đơn thủ công</button>
                            </form>
                        </div>

                        <a href="/dich-vu-vip" class="btn btn-outline"
                            style="border-radius:var(--radius-full); justify-content:center; padding:16px; font-size:16px; color:var(--text-2); border-color:transparent;">Quay
                            lại Bảng giá</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>