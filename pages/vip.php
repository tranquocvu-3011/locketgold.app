<!-- ═══════ BẢNG GIÁ ═══════ -->
        <?php if ($page === 'vip'): ?>
            <div class="page-shell" style="display:flex; flex-direction:column; gap:20px;">
                <div class="text-center mb-lg" style="animation:cardIn 0.5s ease forwards;">
                    <h1 class="page-title" style="font-size:36px; margin-bottom:12px;">Bảng giá dịch vụ</h1>
                    <p class="hero-sub" style="margin-bottom:0;">Chọn gói phù hợp với nhu cầu. Thanh toán qua Zalo: <strong
                            style="color:var(--accent-bright)">0869.226.687</strong></p>
                </div>

                <?php
                // ═══ TÍNH GIÁ NÂNG CẤP THÔNG MINH ═══
                $vip_prices = [
                    'user' => 0,
                    'vip1' => (int) ($settings['price_vip1'] ?? 59000),
                    'vip2' => (int) ($settings['price_vip2'] ?? 79000),
                    'vip3' => (int) ($settings['price_vip3'] ?? 99000),
                    'vip4' => (int) ($settings['price_vip4'] ?? 149000),
                ];
                $vip_order = ['user', 'vip1', 'vip2', 'vip3', 'vip4'];
                $current_idx = array_search($current_role, $vip_order); // false nếu agency/admin
                $current_paid = ($current_idx !== false) ? ($vip_prices[$current_role] ?? 0) : 0;

                // Hàm tính chênh lệch giá
                function upgradeDiff($target_role, $current_paid, $vip_prices)
                {
                    $full = $vip_prices[$target_role] ?? 0;
                    return max(0, $full - $current_paid);
                }
                // Hàm build nút cho 1 gói VIP
                // $plan_role: vai trò của gói này (vip1/vip2/vip3/vip4)
                // $plan_name: tên hiển thị đầy đủ
                // $btn_class: 'btn-primary' hoặc 'btn-outline'
                // trả về HTML string
                function buildVipButton($plan_role, $plan_name, $btn_class, $current_role, $current_idx, $current_paid, $vip_prices, $vip_order, $settings)
                {
                    $full_price = $vip_prices[$plan_role] ?? 0;
                    $plan_idx = array_search($plan_role, $vip_order);

                    // GÓI HIỆN TẠI
                    if ($current_role === $plan_role) {
                        $color = $btn_class === 'btn-primary' ? 'var(--green)' : '';
                        $style_extra = $btn_class === 'btn-primary'
                            ? "background:{$color}; cursor:default; color:#fff; box-shadow:none;"
                            : "border-color:var(--green); color:var(--green); cursor:default;";
                        return '<button disabled class="btn ' . $btn_class . '" style="border-radius:var(--radius-full); margin-top:auto; width:100%; display:flex; align-items:center; justify-content:center; gap:6px; ' . $style_extra . '"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Gói hiện tại</button>';
                    }

                    // GÓI THẤP HƠN HIỆN TẠI (đã bao gồm)
                    if ($current_idx !== false && $plan_idx !== false && $plan_idx < $current_idx) {
                        return '<button disabled class="btn btn-outline" style="border-radius:var(--radius-full); margin-top:auto; width:100%; opacity:0.45; cursor:default; font-size:12px;">✓ Đã bao gồm trong gói của bạn</button>';
                    }

                    // GÓI CAO HƠN (nâng cấp)
                    if ($current_idx !== false && $plan_idx !== false && $plan_idx > $current_idx && $current_paid > 0) {
                        $diff = max(0, $full_price - $current_paid);
                        $diff_k = number_format($diff / 1000, 0, ',', '.');
                        $full_k = number_format($full_price / 1000, 0, ',', '.');
                        $from_label = strtoupper($current_role);
                        $to_label = strtoupper($plan_role);
                        $checkout_name = 'Nâng cấp ' . $from_label . ' → ' . $plan_name;
                        return '<button onclick="confirmCheckout(\'' . addslashes($checkout_name) . '\', ' . $diff . ', \'' . $plan_role . '\')" class="btn ' . $btn_class . '" style="border-radius:var(--radius-full); margin-top:auto; width:100%; position:relative;">'
                            . '<span style="font-size:10px; background:rgba(52,211,153,0.25); color:var(--green); padding:2px 7px; border-radius:6px; font-weight:700; margin-right:6px;">↑ NÂNG CẤP</span>'
                            . 'Chỉ <b style="font-size:16px; margin:0 3px;">+' . $diff_k . 'k</b></button>';
                    }

                    // MUA MỚI (user chưa có gói)
                    $plan_label_map = ['vip1' => 'VIP 1 Cá nhân', 'vip2' => 'VIP 2 Cặp Đôi', 'vip3' => 'VIP 3 Gia Đình', 'vip4' => 'VIP 4 Cao Cấp'];
                    $checkout_name = $plan_label_map[$plan_role] ?? $plan_name;
                    $full_k = number_format($full_price / 1000, 0, ',', '.');
                    return '<button onclick="confirmCheckout(\'' . addslashes($checkout_name) . '\', ' . $full_price . ')" class="btn ' . $btn_class . '" style="border-radius:var(--radius-full); margin-top:auto; width:100%;">Đăng ký ' . $plan_name . '</button>';
                }
                ?>

                <div class="pricing-grid">
                    <!-- VIP 1 -->
                    <div class="price-card">
                        <div class="price-name">VIP 1 — Cá nhân</div>
                        <div class="price-amount">
                            <?php if ($current_role !== 'vip1' && $current_idx !== false && $current_paid > 0 && array_search('vip1', $vip_order) > $current_idx): ?>
                                <?php $diff1 = upgradeDiff('vip1', $current_paid, $vip_prices); ?>
                                <span
                                    style="text-decoration:line-through; color:var(--text-2); font-size:16px; margin-right:6px;"><?= number_format(($settings['price_vip1'] ?? 59000) / 1000, 0, '.', '.') ?>k</span>
                                <span style="color:var(--green);">+<?= number_format($diff1 / 1000, 0, '.', '.') ?>k</span>
                            <?php elseif ($current_idx !== false && array_search('vip1', $vip_order) < $current_idx): ?>
                                <span style="font-size:18px; color:var(--green); font-weight:800;">✓ Đã có</span>
                            <?php else: ?>
                                <span
                                    style="text-decoration:line-through; color:var(--text-2); font-size:20px; margin-right:8px; font-weight:500;"><?= number_format(($settings['price_vip1_old'] ?? 399000) / 1000, 0, '.', '.') ?>k</span>
                                <?= number_format(($settings['price_vip1'] ?? 59000) / 1000, 0, '.', '.') ?>k
                            <?php endif; ?>
                        </div>
                        <div class="price-period">
                            <?= ($current_idx !== false && $current_paid > 0 && array_search('vip1', $vip_order) > $current_idx) ? '/ bù chênh lệch' : '/ vĩnh viễn' ?>
                        </div>
                        <div class="price-desc"
                            style="text-align:left; font-size:13px; display:flex; flex-direction:column; gap:10px;">
                            <span style="font-weight:700; color:var(--text-0); margin-bottom:4px;">Chuyên gia Locket Cơ
                                Bản:</span>
                            <span style="display:flex; align-items:flex-start; gap:8px;"><svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="flex-shrink:0; color:var(--green); margin-top:2px;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> <span style="line-height:1.4;">Kích hoạt thành công <strong>1 tài khoản</strong>
                                    Locket Gold theo ID do bạn chọn.</span></span>
                            <span style="display:flex; align-items:flex-start; gap:8px;"><svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="flex-shrink:0; color:var(--green); margin-top:2px;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> <span style="line-height:1.4;">Hưởng <strong>100% full đặc quyền Locket</strong>
                                    (Video dài, Ngôi sao, Đổi App Icon, Lưu trữ Infinite).</span></span>
                            <span style="display:flex; align-items:flex-start; gap:8px;"><svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="flex-shrink:0; color:var(--green); margin-top:2px;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> <span style="line-height:1.4;"><strong>KHÔNG CẦN CHIA SẺ PASSWORD</strong>, an toàn
                                    tuyệt đối 100%.</span></span>
                            <span style="display:flex; align-items:flex-start; gap:8px;"><svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="flex-shrink:0; color:var(--green); margin-top:2px;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> <span style="line-height:1.4;">Bảo hành duy trì kết nối ổn định <strong>(Tặng Lớp Bảo
                                        Vệ
                                        Premium)</strong> riêng cho máy.</span></span>
                        </div>
                        <?= buildVipButton('vip1', 'VIP 1', 'btn-outline', $current_role, $current_idx, $current_paid, $vip_prices, $vip_order, $settings) ?>
                    </div>
                    <!-- VIP 2 -->
                    <div class="price-card featured" style="display: flex; flex-direction: column;">
                        <div class="price-name" style="font-size:20px;">VIP 2 — Cặp Đôi</div>
                        <div class="price-amount" style="color:var(--accent-bright)">
                            <?php if ($current_role !== 'vip2' && $current_idx !== false && $current_paid > 0 && array_search('vip2', $vip_order) > $current_idx): ?>
                                <?php $diff2 = upgradeDiff('vip2', $current_paid, $vip_prices); ?>
                                <span
                                    style="text-decoration:line-through; color:var(--text-2); font-size:16px; margin-right:6px;"><?= number_format(($settings['price_vip2'] ?? 79000) / 1000, 0, '.', '.') ?>k</span>
                                <span
                                    style="color:var(--accent-bright);">+<?= number_format($diff2 / 1000, 0, '.', '.') ?>k</span>
                            <?php elseif ($current_idx !== false && array_search('vip2', $vip_order) < $current_idx): ?>
                                <span style="font-size:18px; color:var(--green); font-weight:800;">✓ Đã có</span>
                            <?php else: ?>
                                <span
                                    style="text-decoration:line-through; color:var(--text-2); font-size:20px; margin-right:8px; font-weight:500;"><?= number_format(($settings['price_vip2_old'] ?? 799000) / 1000, 0, '.', '.') ?>k</span>
                                <?= number_format(($settings['price_vip2'] ?? 79000) / 1000, 0, '.', '.') ?>k
                            <?php endif; ?>
                        </div>
                        <div class="price-period">
                            <?= ($current_idx !== false && $current_paid > 0 && array_search('vip2', $vip_order) > $current_idx) ? '/ bù chênh lệch' : '/ vĩnh viễn' ?>
                        </div>
                        <div class="price-desc"
                            style="text-align:left; font-size:13px; display:flex; flex-direction:column; gap:10px;">
                            <span style="font-weight:700; color:var(--text-0); margin-bottom:4px;">Siêu Tiết Kiệm Cho Cặp
                                Đôi:</span>
                            <span style="display:flex; align-items:flex-start; gap:8px;"><svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="flex-shrink:0; color:var(--accent-bright); margin-top:2px;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> <span style="line-height:1.4;">Kích hoạt lên tới <strong>2 tài khoản</strong> (Phù
                                    hợp cho Bạn & Gấu).</span></span>
                            <span style="display:flex; align-items:flex-start; gap:8px;"><svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="flex-shrink:0; color:var(--accent-bright); margin-top:2px;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> <span style="line-height:1.4;">Dùng chung không giới hạn, kích hoạt tự động theo
                                    <strong>Locket ID / Link Profile</strong> siêu nhạy.</span></span>
                            <span style="display:flex; align-items:flex-start; gap:8px;"><svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="flex-shrink:0; color:var(--accent-bright); margin-top:2px;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> <span style="line-height:1.4;">Quyền thay đổi Locket ID thoải mái trên lịch sử (Dùng
                                    lại hạn ngạch).</span></span>
                            <span style="display:flex; align-items:flex-start; gap:8px;"><svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="flex-shrink:0; color:var(--accent-bright); margin-top:2px;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> <span style="line-height:1.4;">Đội ngũ Support bảo hành 24/7 suốt vòng đời gói cước
                                    vĩnh viễn.</span></span>
                        </div>
                        <?= buildVipButton('vip2', 'VIP 2', 'btn-primary', $current_role, $current_idx, $current_paid, $vip_prices, $vip_order, $settings) ?>
                    </div>
                    <!-- VIP 3 -->
                    <div class="price-card" style="display: flex; flex-direction: column;">
                        <div class="price-name" style="font-size:20px;">VIP 3 — Gia Đình</div>
                        <div class="price-amount">
                            <?php if ($current_role !== 'vip3' && $current_idx !== false && $current_paid > 0 && array_search('vip3', $vip_order) > $current_idx): ?>
                                <?php $diff3 = upgradeDiff('vip3', $current_paid, $vip_prices); ?>
                                <span
                                    style="text-decoration:line-through; color:var(--text-2); font-size:16px; margin-right:6px;"><?= number_format(($settings['price_vip3'] ?? 99000) / 1000, 0, '.', '.') ?>k</span>
                                <span style="color:var(--text-0);">+<?= number_format($diff3 / 1000, 0, '.', '.') ?>k</span>
                            <?php elseif ($current_idx !== false && array_search('vip3', $vip_order) < $current_idx): ?>
                                <span style="font-size:18px; color:var(--green); font-weight:800;">✓ Đã có</span>
                            <?php else: ?>
                                <span
                                    style="text-decoration:line-through; color:var(--text-2); font-size:20px; margin-right:8px; font-weight:500;"><?= number_format(($settings['price_vip3_old'] ?? 1199000) / 1000, 0, '.', '.') ?>k</span>
                                <?= number_format(($settings['price_vip3'] ?? 99000) / 1000, 0, '.', '.') ?>k
                            <?php endif; ?>
                        </div>
                        <div class="price-period">
                            <?= ($current_idx !== false && $current_paid > 0 && array_search('vip3', $vip_order) > $current_idx) ? '/ bù chênh lệch' : '/ vĩnh viễn' ?>
                        </div>
                        <div class="price-desc"
                            style="text-align:left; font-size:13px; display:flex; flex-direction:column; gap:10px;">
                            <span style="font-weight:700; color:var(--text-0); margin-bottom:4px;">Gói Cao Cấp Nhất Hội
                                Nhóm:</span>
                            <span style="display:flex; align-items:flex-start; gap:8px;"><svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="flex-shrink:0; color:var(--green); margin-top:2px;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> <span style="line-height:1.4;">Miễn phí kích hoạt lên tới <strong>3 tài khoản độc
                                        lập</strong>, sử dụng bình thường không lo chung đụng.</span></span>
                            <span style="display:flex; align-items:flex-start; gap:8px;"><svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="flex-shrink:0; color:var(--green); margin-top:2px;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> <span style="line-height:1.4;">Nhận mọi quyền lợi VIP nhất: Video tẹt ga, Badge mạ
                                    Vàng, Giao diện Icon cực đỉnh.</span></span>
                            <span style="display:flex; align-items:flex-start; gap:8px;"><svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="flex-shrink:0; color:var(--green); margin-top:2px;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> <span style="line-height:1.4;">Duy trì kết nối ổn định trên 3 thiết bị iPhone khác
                                    nhau.</span></span>
                            <span style="display:flex; align-items:flex-start; gap:8px;"><svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="flex-shrink:0; color:var(--green); margin-top:2px;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> <span style="line-height:1.4;">Tự do quản lý lịch sử ID của bản thân cực thông
                                    minh.</span></span>
                        </div>
                        <?= buildVipButton('vip3', 'VIP 3', 'btn-outline', $current_role, $current_idx, $current_paid, $vip_prices, $vip_order, $settings) ?>
                    </div>

                    <!-- VIP 4 -->
                    <div class="price-card" style="display: flex; flex-direction: column;">
                        <div class="price-name" style="font-size:20px;">VIP 4 — Cao Cấp</div>
                        <div class="price-amount">
                            <?php if ($current_role !== 'vip4' && $current_idx !== false && $current_paid > 0 && array_search('vip4', $vip_order) > $current_idx): ?>
                                <?php $diff4 = upgradeDiff('vip4', $current_paid, $vip_prices); ?>
                                <span
                                    style="text-decoration:line-through; color:var(--text-2); font-size:16px; margin-right:6px;"><?= number_format(($settings['price_vip4'] ?? 149000) / 1000, 0, '.', '.') ?>k</span>
                                <span style="color:var(--text-0);">+<?= number_format($diff4 / 1000, 0, '.', '.') ?>k</span>
                            <?php else: ?>
                                <span
                                    style="text-decoration:line-through; color:var(--text-2); font-size:20px; margin-right:8px; font-weight:500;"><?= number_format(($settings['price_vip4_old'] ?? 1899000) / 1000, 0, '.', '.') ?>k</span>
                                <?= number_format(($settings['price_vip4'] ?? 149000) / 1000, 0, '.', '.') ?>k
                            <?php endif; ?>
                        </div>
                        <div class="price-period">
                            <?= ($current_idx !== false && $current_paid > 0 && array_search('vip4', $vip_order) > $current_idx) ? '/ bù chênh lệch' : '/ vĩnh viễn' ?>
                        </div>
                        <div class="price-desc"
                            style="text-align:left; font-size:13px; display:flex; flex-direction:column; gap:10px;">
                            <span style="font-weight:700; color:var(--text-0); margin-bottom:4px;">Gói Cao Cấp 10 ID:</span>
                            <span style="display:flex; align-items:flex-start; gap:8px;"><svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="flex-shrink:0; color:var(--green); margin-top:2px;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> <span style="line-height:1.4;">Kích hoạt thành công lên tới <strong>10 tài
                                        khoản</strong>
                                    độc lập.</span></span>
                            <span style="display:flex; align-items:flex-start; gap:8px;"><svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="flex-shrink:0; color:var(--green); margin-top:2px;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> <span style="line-height:1.4;">Thích hợp cho chia sẻ hội nhóm bạn bè siêu tiết
                                    kiệm.</span></span>
                            <span style="display:flex; align-items:flex-start; gap:8px;"><svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="flex-shrink:0; color:var(--green); margin-top:2px;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> <span style="line-height:1.4;">Nhận mọi quyền lợi VIP nhất: Video tẹt ga, Badge mạ
                                    Vàng.</span></span>
                            <span style="display:flex; align-items:flex-start; gap:8px;"><svg width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="flex-shrink:0; color:var(--green); margin-top:2px;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> <span style="line-height:1.4;">Đội ngũ Support bảo hành 24/7 trực tiếp suốt gói vĩnh
                                    viễn.</span></span>
                        </div>
                        <?= buildVipButton('vip4', 'VIP 4', 'btn-outline', $current_role, $current_idx, $current_paid, $vip_prices, $vip_order, $settings) ?>
                    </div>

                    <!-- ĐẠI LÝ -->
                    <div class="agency-card">
                        <div class="agency-info">
                            <div class="chip mb-sm" style="font-size:10px; letter-spacing:1.5px;">BẢO BỐI KHỞI NGHIỆP TỪ SỐ
                                0</div>
                            <h3 style="font-size:24px; font-weight:800; margin-bottom:10px;">Phân Quyền Đại Lý Locket</h3>
                            <p class="desc" style="line-height:1.6;">Nắm trong tay <strong>Server Apple API chính
                                    chủ</strong> của Locket Gold. Bạn tự do lấy ID của khách hàng để bơm Gold mà không bị
                                trừ
                                hạn ngạch. Thu lợi nhuận 100% từ tiền bán mà không mất 1 đồng vốn nhập hàng. Vốn thu hồi chỉ
                                sau chưa đầy 3 lần chốt sale.</p>
                        </div>
                        <div class="agency-price-box">
                            <div style="font-size:13px; font-weight:600; color:var(--text-2); margin-bottom:8px;">GIÁ GIA
                                NHẬP</div>
                            <div
                                style="font-size:52px; font-weight:900; color:var(--accent-bright); letter-spacing:-2px; line-height:1;">
                                <span
                                    style="text-decoration:line-through; color:var(--text-2); font-size:24px; margin-right:8px; font-weight:500; letter-spacing:0;"><?= number_format(($settings['price_agency_old'] ?? 2490000) / 1000, 0, ',', '.') ?>k</span>
                                <?= number_format(($settings['price_agency'] ?? 299000) / 1000, 0, ',', '.') ?>k
                            </div>
                            <div style="font-size:14px; color:var(--text-2); margin-bottom:20px;">/ Sử dụng Free Toàn
                                Bộ Vĩnh Viễn</div>
                            <?php if ($current_role === 'agency'): ?>
                                <button disabled class="btn btn-primary"
                                    style="border-radius:var(--radius-full); width:100%; background:var(--green); cursor:default; display:flex; align-items:center; justify-content:center; gap:6px; color:#fff; box-shadow:none;"><svg
                                        width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg> Đại lý hiện tại</button>
                            <?php else: ?>
                                <button
                                    onclick="confirmCheckout('Gói Đại Lý (Agency)', <?= $settings['price_agency'] ?? 299000 ?>)"
                                    class="btn btn-primary"
                                    style="border-radius:var(--radius-full); box-shadow: 0 4px 16px rgba(59,130,246,0.3); width:100%;">Hợp
                                    tác mở Agency</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- DNS download section -->
                <?php if ($is_vip_or_higher): ?>
                    <div class="card card-wide mt-md" style="text-align:center;">
                        <h3 class="heading-sm mb-sm">Tải DNS Profile chống thu hồi</h3>
                        <p class="desc mb-md">Cài đặt profile DNS trên iPhone để duy trì trạng thái Premium vĩnh viễn.</p>
                        <a href="/download-dns" class="btn btn-primary"
                            style="width:auto; padding:14px 36px; border-radius:var(--radius-full); display:inline-flex;">Tải
                            DNS Profile →</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>