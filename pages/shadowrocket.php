<?php
// ShadowRocket Module Pricing — Style giống trang VIP
function getSecureModuleLink($filename) {
    $sr_secret = "LocketGold_ShadowRocket_Secret_2026!";
    $t = time();
    $hash = md5($filename . $t . $sr_secret);
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
    $domain = $_SERVER['HTTP_HOST'];
    $base_url = $protocol . "://" . $domain;
    return $base_url . "/download_module.php?file=" . urlencode($filename) . "&t=" . $t . "&hash=" . $hash;
}

// Giá từ admin settings
$sr_prices = [
    'vip' => (int) ($settings['price_sr_vip'] ?? 49000),
    'premium' => (int) ($settings['price_sr_premium'] ?? 49000),
    'ultimate' => (int) ($settings['price_sr_ultimate'] ?? 79000),
    'proxy_1m' => (int) ($settings['price_sr_proxy_1m'] ?? 20000),
    'proxy_1y' => (int) ($settings['price_sr_proxy_1y'] ?? 150000),
];

function srFmtK($v)
{
    return number_format($v / 1000, 0, '.', '.') . 'k';
}
$checkIcon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0; margin-top:2px;"><polyline points="20 6 9 17 4 12"></polyline></svg>';
?>

<div class="page-shell" style="display:flex; flex-direction:column; gap:20px;">
    <div class="text-center mb-lg" style="animation:cardIn 0.5s ease forwards;">
        <h1 class="page-title" style="font-size:36px; margin-bottom:12px;">Tải ShadowRocket - Locket Quốc Vũ</h1>
        <p class="hero-sub" style="margin-bottom:0; text-align:center;">Chọn gói module phù hợp để mở khóa Premium miễn phí. Hệ thống thanh toán tự động trong 3s.
        </p>
    </div>

    <div class="pricing-grid">
        <!-- 1. FREE -->
        <div class="price-card">
            <div class="price-name">Free — Locket Gold</div>
            <div class="price-amount" style="color:var(--green);">0đ</div>
            <div class="price-period">/ miễn phí mãi mãi</div>
            <div class="price-desc"
                style="text-align:left; font-size:13px; display:flex; flex-direction:column; gap:10px;">
                <span style="font-weight:700; color:var(--text-0); margin-bottom:4px;">Gói cơ bản:</span>
                <span style="display:flex; align-items:flex-start; gap:8px; color:var(--green);"><?= $checkIcon ?> <span
                        style="line-height:1.4; color:var(--text-2);">Mở khóa <strong>Locket Gold Premium</strong> vĩnh
                        viễn</span></span>
                <span style="display:flex; align-items:flex-start; gap:8px; color:var(--green);"><?= $checkIcon ?> <span
                        style="line-height:1.4; color:var(--text-2);">Không cần DNS, không cần Proxy</span></span>
                <span style="display:flex; align-items:flex-start; gap:8px; color:var(--green);"><?= $checkIcon ?> <span
                        style="line-height:1.4; color:var(--text-2);">Gọn nhẹ ~2KB, cài đặt siêu nhanh</span></span>
                <span style="display:flex; align-items:flex-start; gap:8px; color:var(--green);"><?= $checkIcon ?> <span
                        style="line-height:1.4; color:var(--text-2);">Hoạt động ngay không cần cấu hình
                        thêm</span></span>
            </div>
            <button onclick="copyToClipboard('<?= getSecureModuleLink('QuocVu_LocketFree.module') ?>', this)"
                class="btn btn-outline"
                style="border-radius:var(--radius-full); margin-top:auto; width:100%; display:flex; align-items:center; justify-content:center; gap:6px; border-color:var(--green); color:var(--green); background:transparent; cursor:pointer;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                <span>Sao chép link Module</span>
            </button>
        </div>

        <!-- 2. VIP (15s) -->
        <div class="price-card featured" style="display:flex; flex-direction:column;">
            <div class="price-name" style="font-size:20px;">VIP — Locket 15s</div>
            <div class="price-amount" style="color:var(--accent-bright);"><?= srFmtK($sr_prices['vip']) ?></div>
            <div class="price-period">/ vĩnh viễn (module)</div>
            <div class="price-desc"
                style="text-align:left; font-size:13px; display:flex; flex-direction:column; gap:10px;">
                <span style="font-weight:700; color:var(--text-0); margin-bottom:4px;">Gold + Video 15 giây:</span>
                <span
                    style="display:flex; align-items:flex-start; gap:8px; color:var(--accent-bright);"><?= $checkIcon ?>
                    <span style="line-height:1.4; color:var(--text-2);">Locket Gold Premium vĩnh viễn</span></span>
                <span
                    style="display:flex; align-items:flex-start; gap:8px; color:var(--accent-bright);"><?= $checkIcon ?>
                    <span style="line-height:1.4; color:var(--text-2);"><strong>Video 15 giây</strong> — nâng giới hạn
                        video Locket</span></span>
                <span
                    style="display:flex; align-items:flex-start; gap:8px; color:var(--accent-bright);"><?= $checkIcon ?>
                    <span style="line-height:1.4; color:var(--text-2);">DNS Anti-Revoke NextDNS tích hợp</span></span>
                <span style="display:flex; align-items:flex-start; gap:8px; color:#f59e0b;"><svg width="16" height="16"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        style="flex-shrink:0; margin-top:2px;">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                    </svg> <span style="line-height:1.4; color:var(--text-2);">Bắt buộc chọn <strong>Thuê Proxy
                            US</strong> bên dưới</span></span>
            </div>

            <div style="margin-top:auto; display:flex; flex-direction:column; gap:8px;">
                <?php if (strpos($current_role, 'sr_vip') !== false || $current_role === 'admin' || $current_role === 'agency'): ?>
                    <button onclick="copyToClipboard('<?= getSecureModuleLink('QuocVu_Locket15s.module') ?>', this)"
                        class="btn btn-primary"
                        style="border-radius:var(--radius-full); width:100%; display:flex; align-items:center; justify-content:center; gap:6px; border:none; cursor:pointer;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        <span>Sao chép link VIP Module</span>
                    </button>
                    <button onclick="downloadDNS('/LocketGold_Premium_DNS.mobileconfig', this)"
                        class="btn btn-outline" style="border-radius:var(--radius-full); width:100%; display:flex; align-items:center; justify-content:center; gap:6px; margin-top:4px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" y1="15" x2="12" y2="3" />
                        </svg>
                        <span>Tải Cấu hình DNS</span>
                    </button>
                <?php else: ?>
                    <div
                        style="font-size:12px; font-weight:700; color:var(--accent-bright); text-transform:uppercase; letter-spacing:0.5px; text-align:center;">
                        Combo Bắt Buộc</div>
                    <button
                        onclick="confirmCheckout('SR VIP + Proxy 1 Tháng', <?= $sr_prices['vip'] + $sr_prices['proxy_1m'] ?>)"
                        class="btn btn-outline" style="border-radius:var(--radius-full); width:100%;">
                        Chọn 1 Tháng — <b
                            style="color:var(--accent-bright);"><?= srFmtK($sr_prices['vip'] + $sr_prices['proxy_1m']) ?></b>
                    </button>
                    <button
                        onclick="confirmCheckout('SR VIP + Proxy 1 Năm', <?= $sr_prices['vip'] + $sr_prices['proxy_1y'] ?>)"
                        class="btn btn-primary" style="border-radius:var(--radius-full); width:100%;">
                        Chọn 1 Năm — <b style="color:white;"><?= srFmtK($sr_prices['vip'] + $sr_prices['proxy_1y']) ?></b>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- 3. PREMIUM -->
        <div class="price-card" style="display:flex; flex-direction:column;">
            <div class="price-name" style="font-size:20px;">Premium — 24 Apps</div>
            <div class="price-amount"><?= srFmtK($sr_prices['premium']) ?></div>
            <div class="price-period">/ vĩnh viễn (module)</div>
            <div class="price-desc"
                style="text-align:left; font-size:13px; display:flex; flex-direction:column; gap:10px;">
                <span style="font-weight:700; color:var(--text-0); margin-bottom:4px;">Gold + 24 Apps Premium:</span>
                <span style="display:flex; align-items:flex-start; gap:8px; color:var(--green);"><?= $checkIcon ?> <span
                        style="line-height:1.4; color:var(--text-2);">Locket Gold Premium vĩnh viễn</span></span>
                <span style="display:flex; align-items:flex-start; gap:8px; color:var(--green);"><?= $checkIcon ?> <span
                        style="line-height:1.4; color:var(--text-2);"><strong>YouTube Premium</strong> — không quảng
                        cáo, PiP, phát nền</span></span>
                <span style="display:flex; align-items:flex-start; gap:8px; color:var(--green);"><?= $checkIcon ?> <span
                        style="line-height:1.4; color:var(--text-2);"><strong>Spotify Premium</strong> — nghe nhạc không
                        quảng cáo</span></span>
                <span style="display:flex; align-items:flex-start; gap:8px; color:var(--green);"><?= $checkIcon ?> <span
                        style="line-height:1.4; color:var(--text-2);"><strong>24 Apps</strong> mở khóa Premium
                        (PicsArt, Emby, VSCO...)</span></span>
                <span style="display:flex; align-items:flex-start; gap:8px; color:var(--green);"><?= $checkIcon ?> <span
                        style="line-height:1.4; color:var(--text-2);">DNS Anti-Revoke + Sub-Store tích hợp</span></span>
                <span style="display:flex; align-items:flex-start; gap:8px; color:var(--green);"><?= $checkIcon ?> <span
                        style="line-height:1.4; color:var(--text-2);">Không cần Proxy — hoạt động trực
                        tiếp</span></span>
            </div>
            <?php if (strpos($current_role, 'sr_premium') !== false || $current_role === 'admin' || $current_role === 'agency'): ?>
                <button onclick="copyToClipboard('<?= getSecureModuleLink('QuocVu_Premium.module') ?>', this)"
                    class="btn btn-primary"
                    style="border-radius:var(--radius-full); margin-top:auto; width:100%; display:flex; align-items:center; justify-content:center; gap:6px; border:none; cursor:pointer;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                    <span>Sao chép link Premium Module</span>
                </button>
                <button onclick="downloadDNS('/LocketGold_Premium_DNS.mobileconfig', this)"
                    class="btn btn-outline" style="border-radius:var(--radius-full); width:100%; display:flex; align-items:center; justify-content:center; gap:6px; margin-top:4px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                    <span>Tải Cấu hình DNS</span>
                </button>
            <?php else: ?>
                <button onclick="confirmCheckout('SR Premium Module (24 Apps)', <?= $sr_prices['premium'] ?>)"
                    class="btn btn-outline" style="border-radius:var(--radius-full); margin-top:auto; width:100%;">Mua
                    Premium — <?= srFmtK($sr_prices['premium']) ?></button>
            <?php endif; ?>
        </div>

        <!-- 4. ULTIMATE -->
        <div class="price-card" style="display:flex; flex-direction:column;">
            <div class="price-name" style="font-size:20px;">Ultimate — Full</div>
            <div class="price-amount"><?= srFmtK($sr_prices['ultimate']) ?></div>
            <div class="price-period">/ vĩnh viễn (module)</div>
            <div class="price-desc"
                style="text-align:left; font-size:13px; display:flex; flex-direction:column; gap:10px;">
                <span style="font-weight:700; color:var(--text-0); margin-bottom:4px;">TẤT CẢ trong 1 module:</span>
                <span style="display:flex; align-items:flex-start; gap:8px; color:var(--green);"><?= $checkIcon ?> <span
                        style="line-height:1.4; color:var(--text-2);">Locket Gold Premium + <strong>Video 15
                            giây</strong></span></span>
                <span style="display:flex; align-items:flex-start; gap:8px; color:var(--green);"><?= $checkIcon ?> <span
                        style="line-height:1.4; color:var(--text-2);">YouTube + Spotify + <strong>24 Apps</strong>
                        Premium</span></span>
                <span style="display:flex; align-items:flex-start; gap:8px; color:var(--green);"><?= $checkIcon ?> <span
                        style="line-height:1.4; color:var(--text-2);">DNS Anti-Revoke + Sub-Store tích hợp</span></span>
                <span style="display:flex; align-items:flex-start; gap:8px; color:#f59e0b;"><svg width="16" height="16"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        style="flex-shrink:0; margin-top:2px;">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                    </svg> <span style="line-height:1.4; color:var(--text-2);">Bắt buộc chọn <strong>Thuê Proxy
                            US</strong> bên dưới</span></span>
            </div>

            <div style="margin-top:auto; display:flex; flex-direction:column; gap:8px;">
                <?php if (strpos($current_role, 'sr_ultimate') !== false || $current_role === 'admin' || $current_role === 'agency'): ?>
                    <button onclick="copyToClipboard('<?= getSecureModuleLink('QuocVu_Ultimate.module') ?>', this)"
                        class="btn btn-primary"
                        style="border-radius:var(--radius-full); width:100%; background:linear-gradient(135deg, #ec4899, #a855f7); border:none; display:flex; align-items:center; justify-content:center; gap:6px; cursor:pointer; color:white;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        <span>Sao chép link Ultimate Module</span>
                    </button>
                    <button onclick="downloadDNS('/LocketGold_Premium_DNS.mobileconfig', this)"
                        class="btn btn-outline" style="border-radius:var(--radius-full); width:100%; display:flex; align-items:center; justify-content:center; gap:6px; margin-top:4px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" y1="15" x2="12" y2="3" />
                        </svg>
                        <span>Tải Cấu hình DNS</span>
                    </button>
                <?php else: ?>
                    <div
                        style="font-size:12px; font-weight:700; color:#ec4899; text-transform:uppercase; letter-spacing:0.5px; text-align:center;">
                        Combo Bắt Buộc</div>
                    <button
                        onclick="confirmCheckout('SR Ultimate + Proxy 1 Tháng', <?= $sr_prices['ultimate'] + $sr_prices['proxy_1m'] ?>)"
                        class="btn btn-outline"
                        style="border-radius:var(--radius-full); width:100%; border-color:rgba(236,72,153,0.3);">
                        Chọn 1 Tháng — <b
                            style="color:#ec4899;"><?= srFmtK($sr_prices['ultimate'] + $sr_prices['proxy_1m']) ?></b>
                    </button>
                    <button
                        onclick="confirmCheckout('SR Ultimate + Proxy 1 Năm', <?= $sr_prices['ultimate'] + $sr_prices['proxy_1y'] ?>)"
                        class="btn btn-primary"
                        style="border-radius:var(--radius-full); width:100%; background:linear-gradient(135deg, #ec4899, #a855f7); border:none;">
                        Chọn 1 Năm — <b
                            style="color:white;"><?= srFmtK($sr_prices['ultimate'] + $sr_prices['proxy_1y']) ?></b>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bảng so sánh -->
    <div class="card card-wide" style="overflow-x:auto;">
        <h2 class="heading-sm mb-sm" style="text-align:center;">So Sánh Các Gói ShadowRocket Module</h2>
        <table style="width:100%; border-collapse:collapse; font-size:13px; margin-top:12px;">
            <thead>
                <tr style="border-bottom:2px solid var(--border);">
                    <th style="text-align:left; padding:10px 12px; color:var(--text-1);">Tính năng</th>
                    <th style="padding:10px 8px; color:#22c55e;">Free</th>
                    <th style="padding:10px 8px; color:var(--accent-bright);">VIP</th>
                    <th style="padding:10px 8px; color:var(--text-1);">Premium</th>
                    <th style="padding:10px 8px; color:#ec4899;">Ultimate</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rows = [
                    ['Locket Gold', true, true, true, true],
                    ['Video 15 giây', false, true, false, true],
                    ['DNS Anti-Revoke', false, true, true, true],
                    ['YouTube Premium', false, false, true, true],
                    ['Spotify Premium', false, false, true, true],
                    ['24 Apps Unlock', false, false, true, true],
                    ['Sub-Store', false, false, true, true],
                    ['Cần Proxy US', false, true, false, true],
                ];
                foreach ($rows as $r):
                    $label = $r[0];
                    $isProxy = ($label === 'Cần Proxy US');
                    ?>
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="text-align:left; padding:8px 12px; color:var(--text-1); font-weight:600;"><?= $label ?>
                        </td>
                        <?php for ($i = 1; $i <= 4; $i++): ?>
                            <td style="text-align:center; padding:8px;">
                                <?php if ($r[$i] && $isProxy): ?>
                                    <span style="color:#f59e0b; font-weight:700;">✓</span>
                                <?php elseif ($r[$i]): ?>
                                    <span style="color:#22c55e; font-weight:700;">✓</span>
                                <?php else: ?>
                                    <span style="color:rgba(255,255,255,0.15);">—</span>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
                <tr style="border-top:2px solid var(--border); font-weight:700;">
                    <td style="text-align:left; padding:10px 12px; color:var(--text-1);">Giá Module</td>
                    <td style="text-align:center; padding:10px 8px; color:#22c55e;">0đ</td>
                    <td style="text-align:center; padding:10px 8px; color:var(--accent-bright);">
                        <?= srFmtK($sr_prices['vip']) ?>
                    </td>
                    <td style="text-align:center; padding:10px 8px;"><?= srFmtK($sr_prices['premium']) ?></td>
                    <td style="text-align:center; padding:10px 8px; color:#ec4899;">
                        <?= srFmtK($sr_prices['ultimate']) ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Hướng dẫn -->
    <div class="card card-wide">
        <h2 class="heading-sm mb-sm">Hướng Dẫn Cài Đặt Locket Quốc Vũ - ShadowRocket</h2>
        <p style="font-size:13px; color:var(--text-2); margin-bottom:12px;">Chọn gói bạn đang dùng để xem hướng dẫn chi tiết:</p>
        
        <?php
        $needsProxy = strpos($current_role, 'sr_vip') !== false || strpos($current_role, 'sr_ultimate') !== false || strpos($current_role, 'sr_proxy') !== false || $current_role === 'admin' || $current_role === 'agency';
        if ($needsProxy):
            $stmt_p = $pdo->prepare("SELECT proxy_info FROM users WHERE username = ?");
            $stmt_p->execute([$current_user]);
            $user_proxy = trim($stmt_p->fetchColumn() ?: '');
        ?>
        <div style="background:var(--bg-2); border:1px solid var(--border); border-radius:8px; padding:12px; margin-bottom:16px;">
            <div style="font-size:14px; font-weight:700; color:#f59e0b; margin-bottom:6px; display:flex; align-items:center; gap:6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                Thông tin Proxy của bạn
            </div>
            <?php if (!empty($user_proxy)): ?>
                <div style="font-family:monospace; font-size:13px; color:var(--text-0); background:var(--bg-1); padding:10px; border-radius:4px; word-break:break-all; line-height:1.5;">
                    <?= nl2br(htmlspecialchars($user_proxy)) ?>
                </div>
            <?php else: ?>
                <div style="font-size:13px; color:var(--text-2);">
                    <i>Bạn chưa được cấp Proxy. Vui lòng liên hệ Admin để nhận Proxy.</i>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div style="display:flex; gap:8px; margin-bottom:16px; overflow-x:auto; padding-bottom:4px; border-bottom:1px solid var(--border);"
            id="srTabs">
            <button class="sr-tab-btn active" data-target="sr-guide-free"
                style="padding:6px 12px; font-size:13px; font-weight:600; border:none; background:transparent; color:var(--text-1); cursor:pointer; border-bottom:2px solid transparent; transition:0.2s; white-space:nowrap;">Free</button>
            <button class="sr-tab-btn" data-target="sr-guide-vip"
                style="padding:6px 12px; font-size:13px; font-weight:600; border:none; background:transparent; color:var(--text-1); cursor:pointer; border-bottom:2px solid transparent; transition:0.2s; white-space:nowrap;">VIP
                (15s)</button>
            <button class="sr-tab-btn" data-target="sr-guide-premium"
                style="padding:6px 12px; font-size:13px; font-weight:600; border:none; background:transparent; color:var(--text-1); cursor:pointer; border-bottom:2px solid transparent; transition:0.2s; white-space:nowrap;">Premium</button>
            <button class="sr-tab-btn" data-target="sr-guide-ultimate"
                style="padding:6px 12px; font-size:13px; font-weight:600; border:none; background:transparent; color:var(--text-1); cursor:pointer; border-bottom:2px solid transparent; transition:0.2s; white-space:nowrap;">Ultimate</button>
        </div>

        <style>
            .sr-tab-btn.active {
                color: var(--accent-bright) !important;
                border-bottom-color: var(--accent-bright) !important;
            }

            .sr-guide-pane {
                display: none;
                flex-direction: column;
                gap: 12px;
            }

            .sr-guide-pane.active {
                display: flex;
            }

            .sr-step-row {
                display: flex;
                gap: 12px;
                padding: 10px 0;
                border-bottom: 1px solid var(--border);
            }

            .sr-step-row:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .sr-step-num {
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #6366f1, #a855f7);
                color: #fff;
                font-size: 12px;
                font-weight: 800;
                border-radius: 8px;
                flex-shrink: 0;
            }

            .sr-step-title {
                font-size: 13px;
                font-weight: 700;
                color: var(--text-1);
                margin-bottom: 3px;
            }

            .sr-step-desc {
                font-size: 12px;
                color: var(--text-2);
                line-height: 1.5;
            }

            .sr-video-grid {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                justify-content: center;
                width: 100%;
            }
            .sr-video-item {
                flex: 0 1 calc(33.333% - 14px); /* 3 cols on PC */
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 8px;
                max-width: 280px;
                min-width: 140px;
            }
            @media (max-width: 768px) {
                .sr-video-item {
                    flex: 0 1 calc(50% - 10px); /* 2 cols on Mobile */
                }
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const tabs = document.querySelectorAll('.sr-tab-btn');
                const panes = document.querySelectorAll('.sr-guide-pane');
                tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        tabs.forEach(t => t.classList.remove('active'));
                        panes.forEach(p => p.classList.remove('active'));
                        tab.classList.add('active');
                        document.getElementById(tab.getAttribute('data-target')).classList.add('active');
                    });
                });
            });
        </script>

        <!-- Cột Các Bước (nay đã chiếm toàn bộ chiều rộng, ở trên) -->
        <div style="width: 100%; max-width: 800px; margin: 0 auto;">
            <!-- PANE FREE -->
            <div id="sr-guide-free" class="sr-guide-pane active">
                <?php
                $defaultFree = [
                    ['1', 'Tải app ShadowRocket', 'Tải từ các bên cung cấp, có thể mua trực tiếp tại AppStore ~79k hoặc nếu không có thì liên hệ Admin để thuê ID tải (10k).'],
                    ['2', 'Nhập Module & Bật Giải Mã', 'Nhấn nút "Sao chép" ở trên → Mở app ShadowRocket → Bấm dấu (+) ở góc phải trên cùng → Ứng dụng sẽ tự động dán link → Bấm Lưu. Tiếp theo chọn tab "Cấu hình" (Config) dưới cùng → Nhấn vào chữ "i" bên cạnh cấu hình "QuocVu_LocketFree.module" → Bật công tắc "HTTPS Giải mã" lên.'],
                    ['3', 'Tạo & Cài Chứng Chỉ', 'Cũng ở màn hình HTTPS Giải mã → Nhấn "Tạo chứng chỉ mới" → Nhấn dấu Tick (✓) góc trên phải. Sau đó nhấn "Cài đặt chứng chỉ" → Cho phép tải hồ sơ.'],
                    ['4', 'Tin cậy Chứng Chỉ (Quan Trọng)', 'Thoát ra màn hình chính iPhone → Vào "Cài đặt" → "Đã tải về hồ sơ" → Nhấn Cài đặt. Sau đó vào "Cài đặt chung" → "Giới thiệu" → Kéo xuống cùng chọn "Cài đặt tin cậy chứng chỉ" → Bật công tắc xanh cho ShadowRocket.'],
                    ['5', 'Bật & Trải nghiệm', 'Quay lại trang chủ ShadowRocket → Chọn cấu hình vừa cài (để có dấu tick cam) → Bật công tắc to nhất ở trên cùng → Mở Locket tận hưởng Gold.'],
                ];
                $stepsFree = !empty($settings['sr_steps_free']) ? json_decode($settings['sr_steps_free'], true) : $defaultFree;
                if (!is_array($stepsFree) || empty($stepsFree)) $stepsFree = $defaultFree;
                foreach ($stepsFree as $s): ?>
                    <div class="sr-step-row">
                        <div class="sr-step-num"><?= $s[0] ?></div>
                        <div>
                            <div class="sr-step-title"><?= $s[1] ?></div>
                            <div class="sr-step-desc"><?= $s[2] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- PANE VIP -->
            <div id="sr-guide-vip" class="sr-guide-pane">
                <?php
                $defaultVip = [
                    ['1', 'Tải app ShadowRocket', 'Cách 1: Tự mua trên App Store (~79K) để đảm bảo an toàn & update trọn đời.<br>Cách 2: Inbox Admin để được cấp tài khoản tải miễn phí.'],
                    ['2', 'Nhập Module & Bật Giải Mã', 'Nhấn "Sao chép link VIP Module" ở trên → Mở app ShadowRocket → Bấm dấu (+) ở góc phải trên cùng → Dán link → Lưu. Tiếp theo chọn tab "Cấu hình" → Nhấn vào chữ "i" bên cạnh cấu hình "QuocVu_Locket15s.module" → Bật "HTTPS Giải mã" → Nhấn "Tạo chứng chỉ mới" → "Cài đặt chứng chỉ".'],
                    ['3', 'Tin cậy Chứng Chỉ (Quan Trọng)', 'Vào "Cài đặt" iPhone → "Đã tải về hồ sơ" → Cài đặt. Sau đó vào "Cài đặt chung" → "Giới thiệu" → "Cài đặt tin cậy chứng chỉ" → Bật công tắc xanh cho ShadowRocket.'],
                    ['4', 'Nhận & Thêm Proxy (Để quay 15s)', 'Truy cập <strong>nhóm Zalo</strong> và nhắn tin cho Admin để nhận Proxy. Sau khi nhận: Về trang chủ ShadowRocket → Bấm dấu (+) góc phải trên → Mục "Loại" (Type) chọn <strong>SOCKS5</strong> → Nhập IP và Cổng (Port) → Dưới mục "Xác thực" điền Tên người dùng và Mật khẩu → Bấm Xong. Chạm vào proxy vừa tạo để có dấu tick cam.'],
                    ['5', 'Cài Đặt DNS (Bảo vệ chứng chỉ)', 'Nhấn nút "Tải Cấu hình DNS" ở bảng trên cùng → Cho phép tải về. Sau đó quay lại "Cài đặt" iPhone → "Đã tải về hồ sơ" → Chọn Cài đặt cấu hình Locket Gold DNS.'],
                    ['6', 'Bật & Trải nghiệm', 'Bật công tắc chính của ShadowRocket trên cùng → Mở Locket và bắt đầu quay video 15 giây!'],
                ];
                $stepsVip = !empty($settings['sr_steps_vip']) ? json_decode($settings['sr_steps_vip'], true) : $defaultVip;
                if (!is_array($stepsVip) || empty($stepsVip)) $stepsVip = $defaultVip;
                foreach ($stepsVip as $s): ?>
                    <div class="sr-step-row">
                        <div class="sr-step-num"><?= $s[0] ?></div>
                        <div>
                            <div class="sr-step-title"><?= $s[1] ?></div>
                            <div class="sr-step-desc"><?= $s[2] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- PANE PREMIUM -->
            <div id="sr-guide-premium" class="sr-guide-pane">
                <?php
                $defaultPremium = [
                    ['1', 'Tải app ShadowRocket', 'Cách 1: Tự mua trên App Store (~79K) để đảm bảo an toàn & update trọn đời.<br>Cách 2: Inbox Admin để được cấp tài khoản tải miễn phí.'],
                    ['2', 'Nhập Module & Bật Giải Mã', 'Nhấn "Sao chép link Premium Module" ở trên → Mở app ShadowRocket → Bấm dấu (+) ở góc phải trên cùng → Ứng dụng sẽ tự động dán link → Bấm Lưu. Tiếp theo chọn tab "Cấu hình" (Config) dưới cùng → Nhấn vào chữ "i" bên cạnh cấu hình "QuocVu_Premium.module" → Bật công tắc "HTTPS Giải mã" lên.'],
                    ['3', 'Tạo & Cài Chứng Chỉ', 'Cũng ở màn hình HTTPS Giải mã → Nhấn "Tạo chứng chỉ mới" → Nhấn dấu Tick (✓) góc trên phải. Sau đó nhấn "Cài đặt chứng chỉ" → Cho phép tải hồ sơ.'],
                    ['4', 'Tin cậy Chứng Chỉ (Quan Trọng)', 'Ra màn hình iPhone → Vào "Cài đặt" → "Đã tải về hồ sơ" → Cài đặt. Tiếp theo vào "Cài đặt chung" → "Giới thiệu" → "Cài đặt tin cậy chứng chỉ" → Bật công tắc xanh cho ShadowRocket.'],
                    ['5', 'Nhận & Thêm Proxy (Nếu có mua thêm)', 'Truy cập <strong>nhóm Zalo</strong> và nhắn tin cho Admin để nhận Proxy. Sau khi nhận: Về trang chủ ShadowRocket → Bấm dấu (+) góc phải trên → Mục "Loại" (Type) chọn <strong>SOCKS5</strong> → Nhập IP và Cổng (Port) → Dưới mục "Xác thực" điền Tên người dùng và Mật khẩu → Bấm Xong. Chạm vào proxy vừa tạo để có dấu tick cam.'],
                    ['6', 'Cài Đặt DNS (Bảo vệ chứng chỉ)', 'Nhấn nút "Tải Cấu hình DNS" ở bảng trên cùng → Cho phép tải về. Sau đó quay lại "Cài đặt" iPhone → "Đã tải về hồ sơ" → Chọn Cài đặt cấu hình Locket Gold DNS.'],
                    ['7', 'Bật & Trải nghiệm', 'Chỉ cần chọn đúng cấu hình Premium đã cài (có dấu tick cam) → Bật công tắc to nhất ở trang chủ ShadowRocket. Bạn có thể mở YouTube xem không quảng cáo, nghe Spotify Premium hoặc dùng 24 app khác ngay lập tức!'],
                ];
                $stepsPremium = !empty($settings['sr_steps_premium']) ? json_decode($settings['sr_steps_premium'], true) : $defaultPremium;
                if (!is_array($stepsPremium) || empty($stepsPremium)) $stepsPremium = $defaultPremium;
                foreach ($stepsPremium as $s): ?>
                    <div class="sr-step-row">
                        <div class="sr-step-num"><?= $s[0] ?></div>
                        <div>
                            <div class="sr-step-title"><?= $s[1] ?></div>
                            <div class="sr-step-desc"><?= $s[2] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- PANE ULTIMATE -->
            <div id="sr-guide-ultimate" class="sr-guide-pane">
                <?php
                $defaultUltimate = [
                    ['1', 'Tải app ShadowRocket', 'Cách 1: Tự mua trên App Store (~79K) để đảm bảo an toàn & update trọn đời.<br>Cách 2: Inbox Admin để được cấp tài khoản tải miễn phí.'],
                    ['2', 'Nhập Module & Bật Giải Mã', 'Nhấn "Sao chép link Ultimate Module" ở trên → Mở app ShadowRocket → Bấm dấu (+) ở góc phải trên cùng → Ứng dụng sẽ tự động dán link → Bấm Lưu. Tiếp theo chọn tab "Cấu hình" (Config) dưới cùng → Nhấn vào chữ "i" bên cạnh cấu hình "QuocVu_Ultimate.module" → Bật công tắc "HTTPS Giải mã" lên.'],
                    ['3', 'Tạo & Cài Chứng Chỉ', 'Cũng ở màn hình HTTPS Giải mã → Nhấn "Tạo chứng chỉ mới" → Nhấn dấu Tick (✓) góc trên phải. Sau đó nhấn "Cài đặt chứng chỉ" → Cho phép tải hồ sơ.'],
                    ['4', 'Tin cậy Chứng Chỉ (Bắt buộc)', 'Vào "Cài đặt" iPhone → "Đã tải về hồ sơ" → Cài đặt. Sau đó vào "Cài đặt chung" → "Giới thiệu" → "Cài đặt tin cậy chứng chỉ" → Bật xanh cho ShadowRocket.'],
                    ['5', 'Nhận & Thêm Proxy (Để quay 15s)', 'Truy cập <strong>nhóm Zalo</strong> và nhắn tin cho Admin để nhận Proxy. Sau khi nhận: Về trang chủ ShadowRocket → Bấm dấu (+) góc phải trên → Mục "Loại" (Type) chọn <strong>SOCKS5</strong> → Nhập IP và Cổng (Port) → Dưới mục "Xác thực" điền Tên người dùng và Mật khẩu → Bấm Xong. Chạm vào proxy vừa tạo để có dấu tick cam.'],
                    ['6', 'Cài Đặt DNS (Bảo vệ chứng chỉ)', 'Nhấn nút "Tải Cấu hình DNS" ở bảng trên cùng → Cho phép tải về. Sau đó quay lại "Cài đặt" iPhone → "Đã tải về hồ sơ" → Chọn Cài đặt cấu hình Locket Gold DNS.'],
                    ['7', 'Bật & Trải nghiệm Full', 'Bật công tắc chính của ShadowRocket. Giờ đây bạn đã có toàn bộ: Locket Video 15s, YouTube/Spotify không quảng cáo, và 24+ Premium App khác hoạt động trơn tru!'],
                ];
                $stepsUltimate = !empty($settings['sr_steps_ultimate']) ? json_decode($settings['sr_steps_ultimate'], true) : $defaultUltimate;
                if (!is_array($stepsUltimate) || empty($stepsUltimate)) $stepsUltimate = $defaultUltimate;
                foreach ($stepsUltimate as $s): ?>
                    <div class="sr-step-row">
                        <div class="sr-step-num"><?= $s[0] ?></div>
                        <div>
                            <div class="sr-step-title"><?= $s[1] ?></div>
                            <div class="sr-step-desc"><?= $s[2] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Video Hướng Dẫn (Chuyển xuống dưới, grid 3/2) -->
        <div style="width: 100%; max-width: 1000px; margin: 30px auto 0 auto; display: flex; flex-direction: column; gap: 20px; align-items: center;">
            <h2 style="font-size:16px; font-weight:700; color:var(--text-1); border-bottom:2px solid var(--border); padding-bottom:8px; margin:0; width:100%; text-align:center;">Video Hướng Dẫn Cài Đặt Locket Quốc Vũ Bằng ShadowRocket</h2>
            
            <div class="sr-video-grid">
                
                <!-- Video Cài Đặt Module -->
                <div class="sr-video-item">
                    <span style="font-size:12px; font-weight:600; color:var(--text-2);">Cài đặt Module</span>
                    <div style="background:var(--bg-2); border-radius:12px; overflow:hidden; border:1px solid var(--border); width: 100%; aspect-ratio: 9/16; position:relative; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                        <video controls style="width:100%; height:100%; object-fit:cover; display:block; position:relative; z-index:1;" preload="metadata" title="Video Hướng Dẫn Cài Đặt ShadowRocket - Locket Quốc Vũ" aria-label="Video Hướng Dẫn Cài Đặt ShadowRocket - Locket Quốc Vũ">
                            <source src="/uploads/huong-dan-shadowrocket.mp4" type="video/mp4">
                        </video>
                        <div style="position:absolute; text-align:center; padding:10px; color:var(--text-2); font-size:12px; z-index:0;">(Chưa có)</div>
                    </div>
                </div>

                <!-- Video Thêm Proxy -->
                <div class="sr-video-item">
                    <span style="font-size:12px; font-weight:600; color:var(--text-2);">Thêm Proxy</span>
                    <div style="background:var(--bg-2); border-radius:12px; overflow:hidden; border:1px solid var(--border); width: 100%; aspect-ratio: 9/16; position:relative; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                        <video controls style="width:100%; height:100%; object-fit:cover; display:block; position:relative; z-index:1;" preload="metadata" title="Hướng Dẫn Thêm Proxy Vào ShadowRocket" aria-label="Hướng Dẫn Thêm Proxy Vào ShadowRocket">
                            <source src="/uploads/huong-dan-proxy.mp4" type="video/mp4">
                        </video>
                        <div style="position:absolute; text-align:center; padding:10px; color:var(--text-2); font-size:12px; z-index:0;">(Chưa có)</div>
                    </div>
                </div>

                <!-- Video Nhận ID Apple tải Shadow -->
                <div class="sr-video-item">
                    <span style="font-size:12px; font-weight:600; color:var(--text-2);">Nhận ID Apple tải Shadow</span>
                    <div style="background:var(--bg-2); border-radius:12px; overflow:hidden; border:1px solid var(--border); width: 100%; aspect-ratio: 9/16; position:relative; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                        <video controls style="width:100%; height:100%; object-fit:cover; display:block; position:relative; z-index:1;" preload="metadata" title="Hướng Dẫn Nhận ID Apple Tải ShadowRocket Miễn Phí" aria-label="Hướng Dẫn Nhận ID Apple Tải ShadowRocket Miễn Phí">
                            <source src="/uploads/huong-dan-id-apple.mp4" type="video/mp4">
                        </video>
                        <div style="position:absolute; text-align:center; padding:10px; color:var(--text-2); font-size:12px; z-index:0;">(Chưa có)</div>
                    </div>
                </div>

            </div>
        </div>

    <!-- Lưu ý quan trọng -->
    <div class="card card-wide" style="background:rgba(245,158,11,0.05); border:1px solid rgba(245,158,11,0.2);">
        <h2 class="heading-sm mb-sm" style="color:#d97706;">Lưu Ý Quan Trọng Khi Sử Dụng ShadowRocket</h2>
        <ul style="font-size:13px; color:var(--text-1); line-height:1.6; padding-left:18px; margin-top:12px; display:flex; flex-direction:column; gap:8px;">
            <li><strong style="color:#ef4444;">Hạn chế của gói Free:</strong> Gói Free <strong>chỉ mở khoá Locket Gold cơ bản (3 giây)</strong>. Hoàn toàn <strong>KHÔNG</strong> hỗ trợ quay video 15s, <strong>KHÔNG</strong> có DNS Anti-Revoke bảo vệ chứng chỉ (dễ bị Apple thu hồi đột ngột gây mất Gold), và <strong>KHÔNG</strong> mở khoá các app Premium khác.</li>
            <li><strong style="color:#10b981;">Đặc quyền gói Trả Phí (VIP/Premium/Ultimate):</strong> Được tích hợp sẵn <strong>DNS Anti-Revoke siêu cấp</strong> bảo vệ cấu hình an toàn tuyệt đối. Gói Premium/Ultimate mang đến <strong>hệ sinh thái 24 App Premium</strong> trị giá hàng chục triệu đồng (YouTube Premium không quảng cáo, Spotify, PicsArt...). Gói VIP 15s và Ultimate còn có thêm máy chủ Proxy tốc độ cao để tải video 15s lên Locket cực mượt.</li>
            <li><strong style="color:var(--text-0);">Khi hết Proxy:</strong> Nếu Proxy US của bạn hết hạn (đối với gói VIP 15s hoặc Ultimate), tính năng quay video 15 giây sẽ ngừng hoạt động. Tuy nhiên, tính năng <strong>Locket Gold cơ bản (quay 3 giây)</strong> và các Premium App khác vẫn sẽ hoạt động bình thường vĩnh viễn.</li>
            <li><strong style="color:var(--text-0);">Tài khoản (Acc) áp dụng:</strong> Chức năng mở khoá Premium lưu trên thiết bị của bạn. Do đó, bạn có thể <strong>đăng nhập bất kỳ tài khoản nào</strong> (Locket, YouTube, Spotify...) trên máy đã cài ShadowRocket đều sẽ nhận được Premium, không giới hạn riêng một acc nào.</li>
            <li><strong style="color:var(--text-0);">Về gói 24 Apps:</strong> Bao gồm 24 ứng dụng phổ biến nhất hiện nay. Do nhà phát hành app thỉnh thoảng cập nhật bảo mật, <strong>một số ứng dụng có thể tạm thời không dùng được Premium</strong> ở các bản update mới. Vui lòng tắt tự động cập nhật và không cập nhật ứng dụng nếu đang dùng ổn định.</li>
        </ul>
    </div>
</div>

<?php
$seo_redirect_enabled = $settings['seo_redirect_enabled'] ?? '1';
$seo_redirect_keyword = $settings['seo_redirect_keyword'] ?? 'Locket Quốc Vũ';
$seo_search_url = 'https://www.google.com/search?q=' . urlencode($seo_redirect_keyword);
?>
<script>
    const isFromGoogle = <?= isset($_SESSION['from_google']) ? 'true' : 'false' ?>;
    const isAdminOrAgency = <?= in_array($current_role, ['admin', 'agency']) ? 'true' : 'false' ?>;
    const isSeoRedirectEnabled = <?= $seo_redirect_enabled === '1' ? 'true' : 'false' ?>;
    const seoKeyword = <?= json_encode($seo_redirect_keyword) ?>;
    const seoSearchUrl = <?= json_encode($seo_search_url) ?>;

    function checkGoogleReferer() {
        if (isSeoRedirectEnabled && !isFromGoogle && !isAdminOrAgency) {
            Swal.fire({
                title: 'Yêu Cầu Truy Cập',
                html: `
                    <div style="text-align:left; font-size:13px; line-height:1.4;">
                        <p style="margin-bottom:8px; color:var(--text-1);">Để sử dụng tính năng này, bạn vui lòng làm theo các bước sau để ủng hộ Website:</p>
                        <div style="background:var(--bg-2); padding:12px; border-radius:8px; border:1px solid var(--border); margin-bottom:10px;">
                            <p style="margin-bottom:6px;"><b>Bước 1:</b> Mở một Tab mới và vào trang chủ <b>Google.com</b></p>
                            <p style="margin-bottom:6px;"><b>Bước 2:</b> Tìm kiếm từ khóa: <b style="color:#ec4899; user-select:all; background:rgba(236,72,153,0.1); padding:2px 6px; border-radius:4px;">${seoKeyword}</b></p>
                            <p style="margin-bottom:6px;"><b>Bước 3:</b> Tìm và bấm vào link có trang web <b style="color:#10b981;"><?= $_SERVER['HTTP_HOST'] ?></b></p>
                            <p style="margin-bottom:0;"><b>Bước 4:</b> Sau khi vào web, truy cập lại vào trang <b>ShadowRocket</b> để nhận kết quả ngay!</p>
                        </div>
                        <p style="font-size:11px; color:var(--text-2); font-style:italic; margin-bottom:0;">Hệ thống sẽ tự động mở khóa tính năng sau khi bạn thực hiện đủ 4 bước.</p>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Mở tab mới đến Google',
                showCancelButton: true,
                cancelButtonText: 'Đóng',
                confirmButtonColor: '#3b82f6',
                background: 'var(--bg-1)',
                color: 'var(--text-0)'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open(seoSearchUrl, '_blank');
                }
            });
            return false;
        }
        return true;
    }

    function copyToClipboard(text, btn) {
        if (!checkGoogleReferer()) return;

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => {
                const span = btn.querySelector('span');
                const oldText = span.innerText;
                span.innerText = 'Đã sao chép!';
                setTimeout(() => { span.innerText = oldText; }, 2000);
            }).catch(err => {
                fallbackCopyTextToClipboard(text, btn);
            });
        } else {
            fallbackCopyTextToClipboard(text, btn);
        }
    }

    function downloadDNS(url, btn) {
        if (!checkGoogleReferer()) return;
        
        const a = document.createElement('a');
        a.href = url;
        a.download = '';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        
        if (btn) {
            const span = btn.querySelector('span');
            if (span) {
                const oldText = span.innerText;
                span.innerText = 'Đã tải xuống!';
                setTimeout(() => { span.innerText = oldText; }, 2000);
            }
        }
    }

    function fallbackCopyTextToClipboard(text, btn) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        textArea.style.top = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            const span = btn.querySelector('span');
            const oldText = span.innerText;
            span.innerText = 'Đã sao chép!';
            setTimeout(() => { span.innerText = oldText; }, 2000);
        } catch (err) {
            alert('Trình duyệt không hỗ trợ sao chép tự động. Vui lòng copy thủ công: ' + text);
        }
        document.body.removeChild(textArea);
    }
</script>