<!-- ═══════ QUẢN LÝ TÀI KHOẢN ═══════ -->
        <?php if ($page === 'account'): ?>
            <?php if (!$current_user): ?>
                <script>window.location = '/dang-nhap';</script>
            <?php else: ?>
                <?php
                // Fetch additional info
                $stmt_u = $pdo->prepare("SELECT phone, created_at, ref_code, referred_by FROM users WHERE username = ?");
                $stmt_u->execute([$current_user]);
                $full_uinfo = $stmt_u->fetch() ?: [];

                $stmt_act = $pdo->prepare("SELECT COUNT(*) FROM activations WHERE injected_by = ?");
                $stmt_act->execute([$current_user]);
                $total_act = $stmt_act->fetchColumn();

                $role_limits = ['user' => 1, 'vip' => 1, 'vip1' => 1, 'vip2' => 2, 'vip3' => 3, 'vip4' => 10, 'agency' => 999999, 'admin' => 999999];
                $my_limit = $role_limits[$current_role] ?? 0;
                $limit_text = ($my_limit > 1000) ? 'Không giới hạn' : $my_limit;
                ?>
                <div class="wrap" style="max-width:1000px; margin: 40px auto 100px;">
                    <div style="text-align:center; margin-bottom:50px;">
                        <div
                            style="width:100px; height:100px; border-radius:50%; background:linear-gradient(135deg, var(--accent), var(--green)); color:white; display:flex; align-items:center; justify-content:center; font-size:40px; font-weight:800; margin:0 auto 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 4px solid var(--bg-1);">
                            <?= strtoupper(substr($current_user, 0, 1)) ?>
                        </div>
                        <h1 class="page-title" style="font-size: 32px; margin-bottom: 12px;">Quản Lý Tài Khoản</h1>
                        <div class="chip <?= $current_role === 'user' ? 'chip-neutral' : '' ?>"
                            style="display:inline-flex; padding: 6px 16px; font-size: 14px;">
                            <?= getRoleLabel($current_role) ?>
                        </div>
                    </div>

                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 380px), 1fr)); gap: 32px;">
                        <!-- CỘT TRÁI -->
                        <div style="display: flex; flex-direction: column; gap: 32px;">
                            <div class="card" style="padding: 32px;">
                                <h3 class="heading-sm mb-md" style="display:flex; align-items:center; gap:10px;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    Thông Tin Cá Nhân
                                </h3>
                                <div style="display:flex; flex-direction:column; gap:20px;">
                                    <div
                                        style="display:flex; justify-content:space-between; align-items:center; padding-bottom:16px; border-bottom:1px solid var(--border);">
                                        <span style="color:var(--text-2);">Tên đăng nhập (Email)</span>
                                        <span
                                            style="font-weight:600; color:var(--text-0); word-break: break-all; text-align: right; margin-left: 10px;"><?= htmlspecialchars($current_user) ?></span>
                                    </div>
                                    <?php if (!empty($full_uinfo['phone'])): ?>
                                        <div
                                            style="display:flex; justify-content:space-between; align-items:center; padding-bottom:16px; border-bottom:1px solid var(--border);">
                                            <span style="color:var(--text-2);">Số điện thoại</span>
                                            <span
                                                style="font-weight:600; color:var(--text-0);"><?= htmlspecialchars($full_uinfo['phone']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div
                                        style="display:flex; justify-content:space-between; align-items:center; padding-bottom:16px; border-bottom:1px solid var(--border);">
                                        <span style="color:var(--text-2);">Ngày tham gia</span>
                                        <span
                                            style="font-weight:600; color:var(--text-0);"><?= !empty($full_uinfo['created_at']) ? date('d/m/Y', strtotime($full_uinfo['created_at'])) : 'Không xác định' ?></span>
                                    </div>
                                    <div
                                        style="display:flex; justify-content:space-between; align-items:center; padding-bottom:16px; border-bottom:1px solid var(--border);">
                                        <span style="color:var(--text-2);">Lượt kích hoạt (Đã dùng)</span>
                                        <span style="font-weight:600; color:var(--accent-bright);"><b
                                                style="font-size:18px;"><?= $total_act ?></b> / <?= $limit_text ?></span>
                                    </div>
                                    <div
                                        style="display:flex; justify-content:space-between; align-items:center; padding-bottom:16px; border-bottom:1px solid var(--border);">
                                        <span style="color:var(--text-2);">Cấp bậc hiện tại</span>
                                        <span
                                            style="font-weight:600; color:var(--accent-bright);"><?= strip_tags(getRoleLabel($current_role)) ?></span>
                                    </div>
                                    <?php if (isset($uinfo['role_expires_at']) && $uinfo['role_expires_at']): ?>
                                        <div
                                            style="display:flex; justify-content:space-between; align-items:center; padding-bottom:16px; border-bottom:1px solid var(--border);">
                                            <span style="color:var(--text-2);">Hạn dùng Hội Viên</span>
                                            <span
                                                style="font-weight:600; color:var(--green);"><?= date('d/m/Y H:i', strtotime($uinfo['role_expires_at'])) ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($current_role === 'agency'): ?>
                                        <div
                                            style="display:flex; justify-content:space-between; align-items:center; padding-bottom:16px; border-bottom:1px solid var(--border);">
                                            <span style="color:var(--text-2);">Mã giới thiệu (Ref)</span>
                                            <span
                                                style="font-weight:600; color:var(--orange); background: rgba(249, 115, 22, 0.1); padding: 4px 10px; border-radius: 8px;"><?= htmlspecialchars($current_user) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- CARD MÃ GIỚI THIỆU -->
                            <div class="card" style="padding: 32px;">
                                <h3 class="heading-sm mb-md" style="display:flex; align-items:center; gap:10px;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                    Mã Giới Thiệu Của Bạn
                                </h3>
                                <p style="color:var(--text-2); font-size:14px; margin-bottom: 20px; line-height: 1.6;">
                                    Sử dụng mã giới thiệu này để mời bạn bè tham gia. Bạn có thể thay đổi mã giới thiệu (4 - 10
                                    ký tự).
                                </p>
                                <form action="/quan-ly-tai-khoan" method="POST">
                                    <input type="hidden" name="action" value="update_ref_code">
                                    <?= csrf_field() ?>
                                    <div style="display:flex; gap:10px;">
                                        <input type="text" name="new_ref_code" class="input"
                                            value="<?= htmlspecialchars($full_uinfo['ref_code'] ?? '') ?>"
                                            placeholder="Ví dụ: LOCKET99" minlength="4" maxlength="10" required
                                            style="flex:1; min-width:0; width:100%; padding: 14px; background: var(--bg-0); font-weight: 700; color: var(--accent-bright); font-size: 16px; text-transform: uppercase;">
                                        <button type="submit" class="btn btn-primary"
                                            style="width:auto !important; flex:0 0 auto; padding: 14px 20px; border-radius: 12px; font-weight: 600;">Cập
                                            nhật</button>
                                    </div>
                                </form>
                                <?php if (!empty($full_uinfo['referred_by'])): ?>
                                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px dashed var(--border);">
                                        <span style="color:var(--text-2); font-size:13px;">Bạn được giới thiệu bởi mã:</span>
                                        <span
                                            style="font-weight:700; color:var(--text-0); margin-left: 5px;"><?= htmlspecialchars($full_uinfo['referred_by']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- CỘT PHẢI -->
                        <div style="display: flex; flex-direction: column; gap: 32px;">
                            <div class="card" style="padding: 32px;">
                                <h3 class="heading-sm mb-md" style="display:flex; align-items:center; gap:10px;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    Bảo Mật Tài Khoản
                                </h3>
                                <p style="color:var(--text-2); font-size:14px; margin-bottom: 24px; line-height: 1.6;">
                                    Đổi mật khẩu định kỳ giúp tài khoản của bạn an toàn hơn. Tuyệt đối không chia sẻ tài khoản
                                    cho người khác.
                                </p>
                                <form action="/quan-ly-tai-khoan" method="POST">
                                    <input type="hidden" name="action" value="change_password">
                                    <?= csrf_field() ?>
                                    <div class="field" style="margin-bottom: 20px;">
                                        <label style="font-size:14px; font-weight:600; color:var(--text-1);">Mật khẩu hiện
                                            tại</label>
                                        <input type="password" name="old_password" class="input"
                                            placeholder="Nhập mật khẩu cũ để xác minh" required
                                            style="padding: 14px; background: var(--bg-0);">
                                    </div>
                                    <div class="field" style="margin-bottom: 24px;">
                                        <label style="font-size:14px; font-weight:600; color:var(--text-1);">Mật khẩu
                                            mới</label>
                                        <input type="password" name="new_password" class="input"
                                            placeholder="Mật khẩu mới (ít nhất 6 ký tự)" required minlength="6"
                                            style="padding: 14px; background: var(--bg-0);">
                                    </div>
                                    <button type="submit" class="btn btn-primary"
                                        style="width: 100%; padding: 14px; font-size: 16px; border-radius: 12px; justify-content: center; font-weight: 700;">Lưu
                                        Mật Khẩu Mới</button>
                                </form>
                            </div>

                            <?php
                            // ─── Tính toán giá nâng cấp (chênh lệch) ───
                            $price_map = [
                                'user' => 0,
                                'vip1' => (int) ($settings['price_vip1'] ?? 59000),
                                'vip2' => (int) ($settings['price_vip2'] ?? 79000),
                                'vip3' => (int) ($settings['price_vip3'] ?? 99000),
                                'vip4' => (int) ($settings['price_vip4'] ?? 149000),
                            ];
                            $label_map = ['vip1' => 'VIP 1 (1 ID)', 'vip2' => 'VIP 2 (2 ID)', 'vip3' => 'VIP 3 (3 ID)', 'vip4' => 'VIP 4 (10 ID)', 'agency' => 'Đại Lý (∞ ID)'];
                            $upgrade_targets = [];
                            $current_price_paid = $price_map[$current_role] ?? 0;
                            $role_order = ['user', 'vip1', 'vip2', 'vip3', 'vip4'];
                            $current_idx = array_search($current_role, $role_order);
                            if ($current_idx !== false) {
                                for ($i = $current_idx + 1; $i < count($role_order); $i++) {
                                    $tgt = $role_order[$i];
                                    $tgt_price = $price_map[$tgt] ?? 0;
                                    $diff = max(0, $tgt_price - $current_price_paid);
                                    $upgrade_targets[] = ['role' => $tgt, 'label' => $label_map[$tgt], 'full_price' => $tgt_price, 'diff' => $diff];
                                }
                            }
                            // Agency luôn hiển thị
                            $price_agency = (int) ($settings['price_agency'] ?? 299000);
                            $upgrade_targets[] = ['role' => 'agency', 'label' => 'Đại Lý (∞ ID)', 'full_price' => $price_agency, 'diff' => $price_agency];
                            ?>

                            <?php if (in_array($current_role, ['agency', 'admin'])): ?>
                                <!-- Agency/Admin: Hiển thị trạng thái premium tối cao -->
                                <div class="card"
                                    style="padding: 24px; background: linear-gradient(135deg, rgba(16,185,129,0.1), rgba(59,130,246,0.05)); border: 1px solid rgba(16, 185, 129, 0.3);">
                                    <div style="display:flex; align-items:center; gap: 16px;">
                                        <div
                                            style="width: 48px; height: 48px; border-radius: 12px; background: var(--green); color: white; display:flex; align-items:center; justify-content:center;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 style="font-size: 16px; font-weight: 700; color:var(--text-0); margin-bottom: 4px;">
                                                Tài khoản <?= getRoleLabel($current_role) ?></h4>
                                            <p style="font-size: 13px; color:var(--text-2); margin:0;">Bạn đang tận hưởng đặc quyền
                                                cao cấp nhất ✨</p>
                                        </div>
                                    </div>
                                </div>

                            <?php else: ?>
                                <!-- User/VIP: Hiển thị bảng nâng cấp với giá chênh lệch -->
                                <div class="card"
                                    style="padding: 28px; border: 1px solid <?= $current_role === 'user' ? 'rgba(239,68,68,0.35)' : 'var(--border-accent)' ?>; background: linear-gradient(135deg, <?= $current_role === 'user' ? 'rgba(239,68,68,0.06),rgba(251,191,36,0.04)' : 'rgba(167,139,250,0.06),rgba(52,211,153,0.03)' ?>);">
                                    <h3
                                        style="font-size:16px; font-weight:800; margin-bottom:4px; color:var(--text-0); display:flex; align-items:center; gap:8px;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.2">
                                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                                            <polyline points="17 6 23 6 23 12" />
                                        </svg>
                                        <?= $current_role === 'user' ? 'Nâng cấp tài khoản' : 'Nâng cấp lên gói cao hơn' ?>
                                    </h3>
                                    <?php if ($current_role !== 'user'): ?>
                                        <p style="font-size:12px; color:var(--text-2); margin-bottom:16px; line-height:1.5;">Bạn đã trả
                                            <b><?= number_format($current_price_paid / 1000, 0, '.', '.') ?>k</b> cho gói
                                            <?= getRoleLabel($current_role) ?>. Chỉ cần thanh toán phần <b>chênh lệch</b> để lên gói
                                            mới.
                                        </p>
                                    <?php else: ?>
                                        <p style="font-size:12px; color:#ef4444; margin-bottom:16px; line-height:1.5;">⛔ Bạn cần nâng
                                            cấp lên gói VIP để mở khóa dịch vụ Locket Gold.</p>
                                    <?php endif; ?>

                                    <div style="display:flex; flex-direction:column; gap:10px;">
                                        <?php foreach ($upgrade_targets as $idx => $t): ?>
                                            <?php
                                            $is_agency_opt = ($t['role'] === 'agency');
                                            $is_highlight = ($idx === 0); // Gói đầu tiên highlight
                                            $border_color = $is_agency_opt ? 'rgba(245,158,11,0.5)' : ($is_highlight ? 'var(--border-accent)' : 'var(--border)');
                                            $bg_color = $is_agency_opt ? 'rgba(245,158,11,0.08)' : ($is_highlight ? 'rgba(167,139,250,0.08)' : 'var(--bg-2)');
                                            $diff_display = $t['diff'] > 0 ? '+' . number_format($t['diff'] / 1000, 0, '.', '.') . 'k' : 'Miễn phí';
                                            $checkout_plan = 'Nâng cấp ' . $t['label'];
                                            ?>
                                            <div
                                                style="display:flex; align-items:center; justify-content:space-between; padding:12px 14px; background:<?= $bg_color ?>; border:1px solid <?= $border_color ?>; border-radius:12px; gap:10px;">
                                                <div style="flex:1; min-width:0;">
                                                    <div style="font-weight:700; font-size:13px; color:var(--text-0);">
                                                        <?php if ($is_highlight && !$is_agency_opt): ?><span
                                                                style="font-size:10px; background:var(--accent-dim); color:#fff; padding:2px 6px; border-radius:6px; margin-right:5px; font-weight:600;">ĐỀ
                                                                XUẤT</span><?php endif; ?>
                                                        <?php if ($is_agency_opt): ?><span
                                                                style="font-size:10px; background:rgba(245,158,11,0.3); color:#f59e0b; padding:2px 6px; border-radius:6px; margin-right:5px; font-weight:700;">PRO</span><?php endif; ?>
                                                        <?= $t['label'] ?>
                                                    </div>
                                                    <?php if ($current_role !== 'user' && !$is_agency_opt): ?>
                                                        <div style="font-size:11px; color:var(--text-2); margin-top:2px;">Giá gốc
                                                            <?= number_format($t['full_price'] / 1000, 0, '.', '.') ?>k — chênh lệch: <b
                                                                style="color:<?= $is_highlight ? 'var(--accent-bright)' : 'var(--text-1)'; ?>"><?= $diff_display ?></b>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
                                                    <div
                                                        style="font-weight:900; font-size:16px; color:<?= $is_agency_opt ? '#f59e0b' : 'var(--accent-bright)' ?>; white-space:nowrap;">
                                                        <?= $diff_display ?>
                                                    </div>
                                                    <a href="/thanh-toan?plan=<?= urlencode($checkout_plan) ?>&price=<?= $t['diff'] ?>"
                                                        style="padding:7px 14px; background:<?= $is_agency_opt ? 'linear-gradient(135deg,#f59e0b,#d97706)' : ($is_highlight ? 'linear-gradient(135deg,var(--accent-dim),var(--accent))' : 'var(--bg-0)') ?>; color:<?= ($is_agency_opt || $is_highlight) ? '#fff' : 'var(--text-1)' ?>; border:1px solid <?= ($is_agency_opt || $is_highlight) ? 'transparent' : 'var(--border)' ?>; border-radius:8px; font-weight:700; font-size:12px; text-decoration:none; white-space:nowrap;">Mua
                                                        ngay</a>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <div
                                        style="margin-top:14px; padding-top:12px; border-top:1px dashed var(--border); font-size:11.5px; color:var(--text-2); line-height:1.6;">
                                        📋 Sau khi chuyển khoản, gửi hóa đơn để được duyệt.
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- LỊCH SỬ NÂNG CẤP & HÓA ĐƠN -->
                            <div class="card" style="padding: 32px;">
                                <h3 class="heading-sm mb-md" style="display:flex; align-items:center; gap:10px;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    Lịch Sử Nâng Cấp
                                </h3>

                                <?php
                                $stmt_receipts = $pdo->prepare("SELECT * FROM receipts WHERE username = ? ORDER BY created_at DESC LIMIT 10");
                                $stmt_receipts->execute([$current_user]);
                                $my_receipts = $stmt_receipts->fetchAll(PDO::FETCH_ASSOC);
                                ?>

                                <?php if (empty($my_receipts)): ?>
                                    <p
                                        style="color:var(--text-2); font-size:14px; text-align:center; padding: 20px 0; background:var(--bg-2); border-radius:12px;">
                                        Bạn chưa có hóa đơn giao dịch nào.</p>
                                <?php else: ?>
                                    <div style="display:flex; flex-direction:column; gap:16px;">
                                        <?php foreach ($my_receipts as $r): ?>
                                            <?php
                                            $r_status = $r['status'];
                                            $st_color = 'var(--text-2)';
                                            $st_label = 'Không xác định';
                                            if ($r_status === 'hoàn thành') {
                                                $st_color = 'var(--green)';
                                                $st_label = 'Thành công';
                                            } elseif ($r_status === 'pending' || $r_status === 'chờ duyệt') {
                                                $st_color = '#f59e0b';
                                                $st_label = 'Đang xử lý';
                                            } elseif (strpos($r_status, 'error') !== false || strpos($r_status, 'từ chối') !== false) {
                                                $st_color = '#ef4444';
                                                $st_label = 'Bị từ chối';
                                            }
                                            ?>
                                            <div
                                                style="display:flex; align-items:center; gap:16px; padding: 16px; background:var(--bg-0); border:1px solid var(--border); border-radius:12px;">
                                                <?php if (empty($r['receipt_img'])): ?>
                                                    <div style="flex-shrink:0; width:45px; height:60px; border-radius:6px; background:rgba(16,185,129,0.1); border:1px dashed #10b981; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#10b981;">
                                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                                        </svg>
                                                        <span style="font-size:9px; font-weight:700; margin-top:4px;">AUTO</span>
                                                    </div>
                                                <?php else: ?>
                                                    <div onclick="Swal.fire({imageUrl: '<?= htmlspecialchars($r['receipt_img']) ?>', showConfirmButton: false, showCloseButton: true, background: 'transparent', backdrop: 'rgba(0,0,0,0.8)'})"
                                                        style="flex-shrink:0; cursor:pointer; width:45px; height:60px; border-radius:6px; background-image:url('<?= htmlspecialchars($r['receipt_img']) ?>'); background-size:cover; background-position:center; border:1px solid var(--border-accent); box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: transform 0.2s;"
                                                        onmouseover="this.style.transform='scale(1.05)'"
                                                        onmouseout="this.style.transform='scale(1)'" title="Bấm để phóng to ảnh"></div>
                                                <?php endif; ?>
                                                <div style="flex:1; min-width:0;">
                                                    <div
                                                        style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                                                        <span style="font-weight:700; color:var(--text-0); font-size:14px;">Gói:
                                                            <?= htmlspecialchars($r['requested_plan'] ?? 'Nâng cấp VIP') ?></span>
                                                        <span
                                                            style="font-weight:700; color:var(--accent-bright); font-size:14px;"><?= number_format((int) $r['requested_amount']) ?>đ</span>
                                                    </div>
                                                    <div
                                                        style="display:flex; justify-content:space-between; align-items:center; font-size:12px;">
                                                        <span
                                                            style="color:var(--text-2);"><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></span>
                                                        <span
                                                            style="font-weight:600; color:<?= $st_color ?>; background:<?= $st_color ?>20; padding:2px 8px; border-radius:4px;"><?= $st_label ?></span>
                                                    </div>
                                                    <?php if (!empty($r['admin_note'])): ?>
                                                        <div
                                                            style="margin-top:8px; font-size:11px; color:#ef4444; background:rgba(239,68,68,0.1); padding:6px; border-radius:4px;">
                                                            Lý do: <?= htmlspecialchars($r['admin_note']) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>