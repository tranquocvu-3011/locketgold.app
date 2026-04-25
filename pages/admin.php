<!-- ═══════ QUẢN TRỊ ADMIN ═══════ -->
<?php if ($page === 'admin'): ?>
    <?php
    if ($current_user && $current_role !== 'admin' && $current_role !== 'agency') {
        header("Location: /trang-chu");
        exit;
    }
    if ($current_role !== 'admin' && $current_role !== 'agency') { ?>
        <div class="card" style="max-width:400px; margin: 100px auto; border-top: 2px solid var(--accent);">
            <div style="text-align:center; margin-bottom:24px;">
                <h2 class="page-title light">Quản Trị Hệ Thống</h2>
                <p class="desc">Đăng nhập tài khoản cấp Quản trị hoặc Đại lý.</p>
            </div>
            <form action="/admin" method="POST">
                <input type="hidden" name="action" value="admin_login">
                <?= csrf_field() ?>
                <div class="field"><label>Tài khoản</label><input type="text" name="username" class="input"
                        placeholder="Nhập tài khoản" required></div>
                <div class="field"><label>Mật khẩu</label><input type="password" name="password" class="input"
                        placeholder="Mật khẩu" required></div>
                <button type="submit" class="btn btn-primary mt-sm"
                    style="width:100%; border-radius:var(--radius-full); padding:14px; font-size:16px;">Đăng Nhập Quản
                    Trị</button>
            </form>
        </div>
    <?php } else { ?>
        <style>
            .admin-full-page {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .admin-wrap {
                width: 100%;
                max-width: 100%;
                min-height: 100vh;
                margin: 0;
                display: flex;
                align-items: stretch;
                background: var(--bg-0);
            }

            .admin-sidebar {
                width: 280px;
                background: var(--bg-1);
                border-right: 1px solid var(--border);
                padding: 24px;
                flex-shrink: 0;
                display: flex;
                flex-direction: column;
            }

            .admin-content {
                flex: 1;
                display: flex;
                flex-direction: column;
                padding: 32px 40px;
                min-height: 100vh;
                overflow-y: auto;
                gap: 24px;
            }

            .admin-menu-link {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 16px;
                border-radius: 12px;
                color: var(--text-2);
                text-decoration: none;
                font-weight: 600;
                font-size: 14px;
                transition: 0.2s;
                margin-bottom: 6px;
            }

            .admin-menu-link:hover {
                background: rgba(255, 255, 255, 0.04);
                color: var(--text-0);
            }

            .admin-menu-link.active {
                background: rgba(167, 139, 250, 0.1);
                color: var(--accent-bright);
                border: 1px solid rgba(167, 139, 250, 0.2);
            }

            .admin-menu-link svg {
                width: 18px;
                height: 18px;
            }

            .admin-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
                flex-wrap: wrap;
                gap: 12px;
            }

            .admin-panel {
                background: var(--bg-1);
                border: 1px solid var(--border);
                border-radius: var(--radius-lg);
                padding: 30px;
                box-shadow: var(--shadow-soft);
            }

            .admin-stat-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 16px;
                margin-bottom: 24px;
            }

            .admin-stat {
                background: var(--bg-2);
                border: 1px solid var(--border);
                border-radius: 16px;
                padding: 20px;
            }

            .admin-stat-title {
                font-size: 13px;
                color: var(--text-2);
                font-weight: 600;
                text-transform: uppercase;
                margin-bottom: 8px;
                letter-spacing: 0.5px;
            }

            .admin-stat-val {
                font-size: 32px;
                font-weight: 800;
                color: var(--text-0);
            }

            .action-btn {
                background: none;
                border: none;
                cursor: pointer;
                color: var(--text-2);
                padding: 6px;
                border-radius: 6px;
                transition: 0.2s;
            }

            .action-btn:hover {
                background: var(--bg-3);
                color: var(--text-0);
            }

            .action-btn.del:hover {
                background: rgba(248, 113, 113, 0.1);
                color: var(--red);
            }

            .admin-sidebar-home {
                display: flex;
            }

            /* ════ ADMIN MOBILE RESPONSIVE ════ */
            @media (max-width: 900px) {

                /* Toàn bộ wrap không được tràn */
                .admin-full-page {
                    max-width: 100vw !important;
                    overflow-x: hidden !important;
                }

                .admin-wrap {
                    flex-direction: column !important;
                    align-items: stretch !important;
                    width: 100% !important;
                    max-width: 100vw !important;
                    min-height: 100dvh !important;
                    gap: 0 !important;
                }

                /* ── SIDEBAR ĐỔI THÀNH THANH TAB NGANG ── */
                .admin-sidebar {
                    width: 100% !important;
                    max-width: 100vw !important;
                    position: sticky !important;
                    top: 0 !important;
                    z-index: 100 !important;
                    flex-direction: row !important;
                    padding: 8px 12px !important;
                    gap: 6px !important;
                    overflow-x: auto !important;
                    overflow-y: hidden !important;
                    border-right: none !important;
                    border-bottom: 1px solid var(--border) !important;
                    border-radius: 0 !important;
                    scroll-snap-type: x mandatory !important;
                    -webkit-overflow-scrolling: touch !important;
                    scrollbar-width: none !important;
                    background: var(--bg-1) !important;
                    /* Safe area cho iPhone */
                    padding-left: max(12px, env(safe-area-inset-left)) !important;
                    padding-right: max(12px, env(safe-area-inset-right)) !important;
                }

                .admin-sidebar::-webkit-scrollbar {
                    display: none !important;
                }

                .admin-sidebar-title {
                    display: none !important;
                }

                .admin-menu-link {
                    flex-shrink: 0 !important;
                    flex: 0 0 auto !important;
                    margin-bottom: 0 !important;
                    padding: 9px 14px !important;
                    font-size: 13px !important;
                    white-space: nowrap !important;
                    scroll-snap-align: start !important;
                    border-radius: 10px !important;
                    gap: 6px !important;
                }

                .admin-menu-link svg {
                    width: 15px !important;
                    height: 15px !important;
                }

                /* ── CONTENT FULL WIDTH ── */
                .admin-content {
                    width: 100% !important;
                    max-width: 100vw !important;
                    padding: 16px 12px 80px !important;
                    min-height: auto !important;
                    height: auto !important;
                    overflow-y: visible !important;
                    box-sizing: border-box !important;
                    /* Safe area bottom cho iPhone */
                    padding-bottom: calc(80px + env(safe-area-inset-bottom)) !important;
                }

                /* ── PANELS ── */
                .admin-panel {
                    width: 100% !important;
                    max-width: 100% !important;
                    padding: 16px 14px !important;
                    border-radius: var(--radius-md) !important;
                    box-sizing: border-box !important;
                    overflow-x: hidden !important;
                }

                .admin-header {
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 10px !important;
                    margin-bottom: 14px !important;
                }

                .admin-header .heading {
                    font-size: 17px !important;
                }

                /* ── STATS GRID 2 CỘT ── */
                .admin-stat-grid {
                    grid-template-columns: 1fr 1fr !important;
                    gap: 10px !important;
                }

                .admin-stat {
                    padding: 14px !important;
                }

                .admin-stat-val {
                    font-size: 22px !important;
                }

                /* ── FORM GRID 1 CỘT ── */
                .admin-panel [style*="grid-template-columns:1fr 1fr"],
                .admin-panel [style*="grid-template-columns: 1fr 1fr"] {
                    grid-template-columns: 1fr !important;
                }

                /* ── OVERFLOW BẢNG ── */
                .admin-panel [style*="overflow-x:auto"],
                .admin-panel [style*="overflow-x: auto"] {
                    max-width: 100% !important;
                    overflow-x: auto !important;
                }
            }

            /* ── ULTRA SMALL ≤ 480px ── */
            @media (max-width: 480px) {
                .admin-sidebar {
                    padding: 6px 8px !important;
                    padding-left: max(8px, env(safe-area-inset-left)) !important;
                    padding-right: max(8px, env(safe-area-inset-right)) !important;
                }

                .admin-menu-link {
                    padding: 8px 10px !important;
                    font-size: 12px !important;
                    gap: 5px !important;
                }

                .admin-menu-link svg {
                    width: 14px !important;
                    height: 14px !important;
                }

                .admin-content {
                    padding: 12px 10px !important;
                    padding-bottom: calc(72px + env(safe-area-inset-bottom)) !important;
                }

                .admin-panel {
                    padding: 14px 12px !important;
                }

                .admin-stat-grid {
                    grid-template-columns: 1fr 1fr !important;
                    gap: 8px !important;
                }

                .admin-stat {
                    padding: 12px !important;
                }

                .admin-stat-title {
                    font-size: 11px !important;
                }

                .admin-stat-val {
                    font-size: 20px !important;
                }

                .admin-header .heading {
                    font-size: 16px !important;
                }

                .admin-panel .tbl tr {
                    padding: 12px !important;
                }

                .admin-panel .tbl td {
                    min-height: 38px !important;
                }

                .admin-panel .tbl td[data-label="ID"] {
                    font-size: 18px !important;
                }

                .admin-panel .tbl td[data-label="Tài khoản"] {
                    font-size: 15px !important;
                }

                .admin-panel .tbl td.td-actions .action-btn {
                    width: 42px !important;
                    height: 42px !important;
                }
            }
        </style>

        <?php
        if ($current_role === 'agency') {
            $tab = $_GET['tab'] ?? 'receipts';
            if (!in_array($tab, ['receipts', 'payments']))
                $tab = 'receipts';
        } else {
            $tab = $_GET['tab'] ?? 'dashboard';
            if (!in_array($tab, ['dashboard', 'users', 'activations', 'settings', 'contacts', 'payments', 'receipts', 'sr_receipts', 'branding', 'articles', 'feedbacks', 'settings_shadowrocket']))
                $tab = 'dashboard';

            // Core Stats
            $cnt_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
            $cnt_acts = $pdo->query("SELECT COUNT(*) FROM activations")->fetchColumn();
            $cnt_vips = $pdo->query("SELECT COUNT(*) FROM users WHERE role LIKE '%vip%'")->fetchColumn();
            $cnt_agency = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'agency'")->fetchColumn();

            // Advanced Stats
            $total_revenue = $pdo->query("SELECT SUM(requested_amount) FROM receipts WHERE status = 'hoàn thành'")->fetchColumn() ?: 0;
            $today_revenue = $pdo->query("SELECT SUM(requested_amount) FROM receipts WHERE status = 'hoàn thành' AND DATE(created_at) = CURDATE()")->fetchColumn() ?: 0;
            $pending_receipts = $pdo->query("SELECT COUNT(*) FROM receipts WHERE status IN ('pending', 'chờ duyệt')")->fetchColumn() ?: 0;
            $today_acts = $pdo->query("SELECT COUNT(*) FROM activations WHERE DATE(created_at) = CURDATE()")->fetchColumn() ?: 0;

            // Chart 1: Doanh thu Tuần này (Thứ 2 đến Chủ nhật)
            $chart_week_labels = [];
            $chart_week_data = [];
            $monday = date('Y-m-d', strtotime('monday this week'));
            for ($i = 0; $i < 7; $i++) {
                $date = date('Y-m-d', strtotime("$monday +$i days"));
                $chart_week_labels[] = date('d/m', strtotime($date));
                $rev = $pdo->query("SELECT SUM(requested_amount) FROM receipts WHERE status = 'hoàn thành' AND DATE(created_at) = '$date'")->fetchColumn() ?: 0;
                $chart_week_data[] = $rev;
            }
            $chart_week_labels_json = json_encode($chart_week_labels);
            $chart_week_data_json = json_encode($chart_week_data);

            // Chart 2: Doanh thu Tháng này (Mùng 1 đến cuối tháng)
            $chart_month_labels = [];
            $chart_month_data = [];
            $num_days = date('t');
            $current_month = date('Y-m');
            $monthly_revs_stmt = $pdo->query("SELECT DAY(created_at) as d, SUM(requested_amount) as total FROM receipts WHERE status = 'hoàn thành' AND DATE_FORMAT(created_at, '%Y-%m') = '$current_month' GROUP BY DAY(created_at)");
            $monthly_revs = [];
            while ($row = $monthly_revs_stmt->fetch()) {
                $monthly_revs[$row['d']] = $row['total'];
            }
            for ($i = 1; $i <= $num_days; $i++) {
                $chart_month_labels[] = $i;
                $chart_month_data[] = $monthly_revs[$i] ?? 0;
            }
            $chart_month_labels_json = json_encode($chart_month_labels);
            $chart_month_data_json = json_encode($chart_month_data);
        }
        ?>
        <div class="admin-wrap">
            <aside class="admin-sidebar">
                <div class="admin-sidebar-title"
                    style="font-size:12px; font-weight:800; color:var(--text-2); letter-spacing:1px; margin-bottom:16px;">
                    ADMIN PANEL</div>
                
                <!-- Nút Về Trang Chủ (Desktop sidebar) -->
                <a href="/trang-chu" class="admin-menu-link admin-sidebar-home"
                    style="margin-bottom:16px; border-bottom:1px solid var(--border); padding-bottom:16px; color:var(--accent-bright);">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg> Về Trang Chủ
                </a>
                <?php if ($current_role === 'admin'): ?>
                    <a href="?tab=dashboard" class="admin-menu-link <?= $tab == 'dashboard' ? 'active' : '' ?>"><svg
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="9" />
                            <rect x="14" y="3" width="7" height="5" />
                            <rect x="14" y="12" width="7" height="9" />
                            <rect x="3" y="16" width="7" height="5" />
                        </svg> Tổng quan</a>
                <?php endif; ?>
                <a href="?tab=receipts" class="admin-menu-link <?= $tab == 'receipts' ? 'active' : '' ?>"><svg
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg> Lịch sử nạp tiền</a>
                <a href="?tab=sr_receipts" class="admin-menu-link <?= $tab == 'sr_receipts' ? 'active' : '' ?>"><svg
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg> Hóa đơn ShadowRocket</a>
                <a href="?tab=settings_shadowrocket"
                    class="admin-menu-link <?= $tab == 'settings_shadowrocket' ? 'active' : '' ?>"><svg viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                    </svg> Cấu hình ShadowRocket</a>
                <a href="?tab=payments" class="admin-menu-link <?= $tab == 'payments' ? 'active' : '' ?>"><svg
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="5" width="20" height="14" rx="2" />
                        <line x1="2" y1="10" x2="22" y2="10" />
                    </svg> <?= $current_role === 'agency' ? 'Cấu hình Website' : 'Cấu hình Thanh toán' ?></a>

                <?php if ($current_role === 'admin'): ?>
                    <a href="?tab=users" class="admin-menu-link <?= $tab == 'users' ? 'active' : '' ?>"><svg viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg> Quản lý tài khoản</a>
                    <a href="?tab=activations" class="admin-menu-link <?= $tab == 'activations' ? 'active' : '' ?>"><svg
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                        </svg> Lịch sử kích hoạt</a>
                    <a href="?tab=settings" class="admin-menu-link <?= $tab == 'settings' ? 'active' : '' ?>"><svg
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path
                                d="M12.22 2h-.44a2 2 0 0 0-2 2v1.18a8.97 8.97 0 0 0-2.18 1.25l-.85-.85a2 2 0 0 0-2.83 0l-.3.3a2 2 0 0 0 0 2.83l.85.85a8.97 8.97 0 0 0-1.25 2.18H2a2 2 0 0 0-2 2v.44a2 2 0 0 0 2 2h1.18a8.97 8.97 0 0 0 1.25 2.18l-.85.85a2 2 0 0 0 0 2.83l.3.3a2 2 0 0 0 2.83 0l.85-.85a8.97 8.97 0 0 0 2.18 1.25V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-1.18a8.97 8.97 0 0 0 2.18-1.25l.85.85a2 2 0 0 0 2.83 0l.3-.3a2 2 0 0 0 0-2.83l-.85-.85a8.97 8.97 0 0 0 1.25-2.18H22a2 2 0 0 0 2-2v-.44a2 2 0 0 0-2-2h-1.18a8.97 8.97 0 0 0-1.25-2.18l.85-.85a2 2 0 0 0 0-2.83l-.3-.3a2 2 0 0 0-2.83 0l-.85.85a8.97 8.97 0 0 0-2.18-1.25V4a2 2 0 0 0-2-2z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg> Cài đặt hệ thống</a>
                    <a href="?tab=contacts" class="admin-menu-link <?= $tab == 'contacts' ? 'active' : '' ?>"><svg
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg> Liên hệ & MXH</a>
                    <a href="?tab=branding" class="admin-menu-link <?= $tab == 'branding' ? 'active' : '' ?>"><svg
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg> Thương Hiệu & SEO</a>
                    <a href="?tab=articles" class="admin-menu-link <?= $tab == 'articles' ? 'active' : '' ?>"><svg
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                            <polyline points="10 9 9 9 8 9" />
                        </svg> Quản lý Bài Viết</a>
                    <a href="?tab=feedbacks" class="admin-menu-link <?= $tab == 'feedbacks' ? 'active' : '' ?>"><svg
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg> Quản lý Đánh Giá</a>
                <?php endif; ?>

            </aside>

            <section class="admin-content">
                <?php if ($tab == 'receipts'): ?>
                    <div class="admin-panel">
                        <div class="admin-header">
                            <h2 class="heading" style="margin:0;">Lịch sử nạp tiền</h2>
                        </div>
                        <div class="divider"></div>
                        <div style="overflow-x:auto;">
                            <table class="tbl" style="table-layout: fixed; width: 100%; text-align: center;">
                                <thead>
                                    <tr>
                                        <th style="width:5%; text-align:center;">STT</th>
                                        <th style="width:15%; text-align:center;">Tài khoản</th>
                                        <th style="width:15%; text-align:center;">Trạng thái</th>
                                        <th style="width:15%; text-align:center;">Ngày gửi</th>
                                        <th style="width:25%; text-align:center;">Cấp quyền (Set Role)</th>
                                        <th style="width:10%; text-align:center;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    try {
                                        if ($current_role === 'admin') {
                                            $stmt = $pdo->query("SELECT * FROM receipts WHERE requested_role NOT LIKE 'sr_%' OR requested_role IS NULL ORDER BY created_at DESC");
                                            $all_receipts = $stmt->fetchAll();
                                        } else {
                                            $stmt = $pdo->prepare("SELECT * FROM receipts WHERE agency_owner = ? AND (requested_role NOT LIKE 'sr_%' OR requested_role IS NULL) ORDER BY created_at DESC");
                                            $stmt->execute([$current_user]);
                                            $all_receipts = $stmt->fetchAll();
                                        }
                                        if (empty($all_receipts)) {
                                            echo "<tr><td colspan='6' style='text-align:center;'>Chưa có hóa đơn nào</td></tr>";
                                        } else {
                                            $stt = count($all_receipts);
                                            foreach ($all_receipts as $r) {
                                                $r_id = (int) $r['id'];
                                                $r_user = htmlspecialchars($r['username'], ENT_QUOTES, 'UTF-8');
                                                $r_img = htmlspecialchars((string)($r['receipt_img'] ?? ''), ENT_QUOTES, 'UTF-8');
                                                $r_status = htmlspecialchars($r['status'], ENT_QUOTES, 'UTF-8');
                                                $r_date = date('d/m/Y H:i', strtotime($r['created_at']));

                                                // Get current role of user
                                                $stmt_u = $pdo->prepare("SELECT role FROM users WHERE username = ?");
                                                $stmt_u->execute([$r_user]);
                                                $u_role = normalizeRoleValue($stmt_u->fetchColumn() ?: 'user');
                                                $requested_role = normalizeRoleValue($r['requested_role'] ?? '');
                                                $requested_plan = htmlspecialchars((string) ($r['requested_plan'] ?? ''), ENT_QUOTES, 'UTF-8');
                                                $requested_amount = (int) ($r['requested_amount'] ?? 0);
                                                $selected_role = $requested_role !== '' ? $requested_role : $u_role;

                                                $badge_class = ($r_status == 'hoàn thành') ? 'badge-success' : 'badge-warning';

                                                echo "<tr style='text-align:center;'>";
                                                echo "<td data-label='STT' style='vertical-align:middle;'>#{$stt}</td>";
                                                $stt--;
                                                echo "<td data-label='Tài khoản' style='vertical-align:middle;'><strong>{$r_user}</strong><br><span style='font-size:12px;color:var(--text-2);'>Current: " . htmlspecialchars(getRoleLabel($u_role), ENT_QUOTES, 'UTF-8') . "</span>" . ($requested_plan !== '' ? "<br><span style='font-size:12px;color:var(--accent-bright);'>Yêu cầu: {$requested_plan}" . ($requested_amount > 0 ? " (" . number_format($requested_amount) . "đ)" : "") . "</span>" : "") . "</td>";
                                                echo "<td data-label='Trạng thái' style='vertical-align:middle;'><span class='badge {$badge_class}'>{$r_status}</span></td>";
                                                echo "<td data-label='Ngày gửi' style='vertical-align:middle;'>{$r_date}</td>";
                                                echo "<td data-label='Cấp quyền & Proxy' style='vertical-align:middle;'>
                                                            <form method='POST' style='display:flex; flex-wrap:wrap; align-items:center; justify-content:center; gap:6px; margin:0;'>
                                                                <input type='hidden' name='action' value='admin_set_receipt_role'>
                                                                <input type='hidden' name='receipt_id' value='{$r_id}'>
                                                                <input type='hidden' name='username' value='{$r_user}'>
                                                                <select name='role' class='sel' style='padding:4px 8px; font-size:12px; height:32px; max-width:100%;'>
                                                                    <option value='user'>User</option>
                                                                    " . (strpos($selected_role, 'sr_') === 0 ? "<option value='{$selected_role}' selected>ShadowRocket ({$selected_role})</option>" : "") . "
                                                                    <option value='vip1' " . ($selected_role == 'vip1' ? 'selected' : '') . ">VIP 1</option>
                                                                    <option value='vip2' " . ($selected_role == 'vip2' ? 'selected' : '') . ">VIP 2</option>
                                                                    <option value='vip3' " . ($selected_role == 'vip3' ? 'selected' : '') . ">VIP 3</option>
                                                                     <option value='vip4' " . ($selected_role == 'vip4' ? 'selected' : '') . ">VIP 4</option>
                                                                    " . ($current_role !== 'agency' ? "<option value='agency' " . ($u_role == 'agency' ? 'selected' : '') . ">Đại lý</option>" : "") . "
                                                                </select>
                                                                <button type='submit' class='btn btn-primary' style='padding:4px 12px; font-size:12px; height:32px; margin:0;'>Duyệt</button>
                                                            </form>
                                                          </td>";
                                                echo "<td data-label='Thao tác' style='vertical-align:middle;'>
                                                            <form method='POST' style='margin:0; display:flex; justify-content:center;' onsubmit=\"return confirm('Xóa hóa đơn này?');\">
                                                                <input type='hidden' name='action' value='admin_del_receipt'>
                                                                <input type='hidden' name='receipt_id' value='{$r_id}'>
                                                                <button type='submit' class='btn btn-outline' style='color:#ef4444; border-color:#ef4444; padding:0 12px; height:32px; font-size:12px;'>Xóa</button>
                                                            </form>
                                                          </td>";
                                                echo "</tr>";
                                            }
                                        }
                                    } catch (Exception $e) {
                                        echo "<tr><td colspan='7'>Lỗi CSDL: " . $e->getMessage() . "</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($tab == 'payments'):
                    $gs = [];
                    if ($current_role === 'agency') {
                        $stmt = $pdo->prepare("SELECT * FROM agency_settings WHERE agency_username = ?");
                        $stmt->execute([$current_user]);
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($row)
                            $gs = $row;
                    } else {
                        $stmt = $pdo->query("SELECT setting_key, setting_value FROM global_settings");
                        foreach ($stmt->fetchAll() as $row) {
                            $gs[$row['setting_key']] = $row['setting_value'];
                        }
                    }
                    ?>
                    <div class="admin-panel">
                        <div class="admin-header">
                            <h2 class="heading" style="margin:0;">Cấu hình Thanh toán (VietQR)</h2>
                        </div>
                        <div class="divider"></div>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="admin_save_payment_settings">
                            <?php if ($current_role === 'agency'): ?>
                                <div class="field" style="margin-bottom:15px;">
                                    <label>Tên Thương Hiệu (Website con)</label>
                                    <input type="text" name="site_name" class="input"
                                        value="<?= htmlspecialchars($gs['site_name'] ?? '') ?>" placeholder="Tên shop của bạn..."
                                        required>
                                </div>
                                <div class="field" style="margin-bottom:15px;">
                                    <label>Tên Miền (Trỏ CNAME/A về server này)</label>
                                    <input type="text" name="domain_name" class="input"
                                        value="<?= htmlspecialchars($gs['domain_name'] ?? '') ?>"
                                        placeholder="VD: locket.domaincuaban.com">
                                </div>
                            <?php endif; ?>
                            <div class="field" style="margin-bottom:15px;">
                                <label>Mã Ngân Hàng (VD: mbbank, vietcombank)</label>
                                <input type="text" name="bank_code" class="input"
                                    value="<?= htmlspecialchars($gs['bank_code'] ?? '') ?>" placeholder="mbbank" required>
                            </div>
                            <div class="field" style="margin-bottom:15px;">
                                <label>Số Tài Khoản</label>
                                <input type="text" name="bank_account" class="input"
                                    value="<?= htmlspecialchars($gs['bank_account'] ?? '') ?>" placeholder="0123456789" required>
                            </div>
                            <div class="field" style="margin-bottom:20px;">
                                <label>Tên Chủ Tài Khoản (Không dấu)</label>
                                <input type="text" name="bank_owner" class="input"
                                    value="<?= htmlspecialchars($gs['bank_owner'] ?? '') ?>" placeholder="NGUYEN VAN A" required>
                            </div>

                            <div class="field"
                                style="margin-bottom:20px; background:rgba(59, 130, 246, 0.05); padding:16px; border:1px dashed #3b82f6; border-radius:12px;">
                                <label style="color:#3b82f6; font-weight:700;">ThueAPI.Pro Token (Auto Bank Webhook)</label>
                                <p style="font-size:13px; color:var(--text-2); margin-bottom:10px;">Nhập token lấy từ ThueAPI.Pro để
                                    hệ thống tự động kiểm tra lịch sử giao dịch. Để trống nếu không dùng.</p>
                                <input type="password" name="thueapi_token" class="input"
                                    value="<?= htmlspecialchars($gs['thueapi_token'] ?? '') ?>" placeholder="Token...">
                            </div>
                            <button type="submit" class="btn btn-primary" style="margin-bottom:30px;">Lưu Cấu Hình</button>

                            <div class="admin-header">
                                <h2 class="heading" style="margin:0;">Cấu hình Giá Bán (VND)</h2>
                            </div>
                            <div class="divider"></div>

                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                                <div class="field">
                                    <label>VIP 1 - Giá Khuyến Mãi</label>
                                    <input type="number" name="price_vip1" class="input"
                                        value="<?= htmlspecialchars($gs['price_vip1'] ?? '59000') ?>">
                                </div>
                                <div class="field">
                                    <label>VIP 1 - Giá Gốc</label>
                                    <input type="number" name="price_vip1_old" class="input"
                                        value="<?= htmlspecialchars($gs['price_vip1_old'] ?? '399000') ?>">
                                </div>

                                <div class="field">
                                    <label>VIP 2 - Giá Khuyến Mãi</label>
                                    <input type="number" name="price_vip2" class="input"
                                        value="<?= htmlspecialchars($gs['price_vip2'] ?? '79000') ?>">
                                </div>
                                <div class="field">
                                    <label>VIP 2 - Giá Gốc</label>
                                    <input type="number" name="price_vip2_old" class="input"
                                        value="<?= htmlspecialchars($gs['price_vip2_old'] ?? '799000') ?>">
                                </div>

                                <div class="field">
                                    <label>VIP 3 - Giá Khuyến Mãi</label>
                                    <input type="number" name="price_vip3" class="input"
                                        value="<?= htmlspecialchars($gs['price_vip3'] ?? '99000') ?>">
                                </div>
                                <div class="field">
                                    <label>VIP 3 - Giá Gốc</label>
                                    <input type="number" name="price_vip3_old" class="input"
                                        value="<?= htmlspecialchars($gs['price_vip3_old'] ?? '1199000') ?>">
                                </div>

                                <div class="field">
                                    <label>VIP 4 - Giá Khuyến Mãi</label>
                                    <input type="number" name="price_vip4" class="input"
                                        value="<?= htmlspecialchars($gs['price_vip4'] ?? '149000') ?>">
                                </div>
                                <div class="field">
                                    <label>VIP 4 - Giá Gốc</label>
                                    <input type="number" name="price_vip4_old" class="input"
                                        value="<?= htmlspecialchars($gs['price_vip4_old'] ?? '1899000') ?>">
                                </div>

                                <div class="field">
                                    <label>Đại Lý - Giá Khuyến Mãi</label>
                                    <input type="number" name="price_agency" class="input"
                                        value="<?= htmlspecialchars($gs['price_agency'] ?? '299000') ?>">
                                </div>
                                <div class="field">
                                    <label>Đại Lý - Giá Gốc</label>
                                    <input type="number" name="price_agency_old" class="input"
                                        value="<?= htmlspecialchars($gs['price_agency_old'] ?? '2490000') ?>">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Lưu Cấu Hình</button>

                        </form>
                    </div>
                <?php elseif ($tab == 'sr_receipts' && $current_role === 'admin'): ?>
                    <div class="admin-panel" style="margin-bottom: 20px;">
                        <div class="admin-header">
                            <h2 class="heading" style="margin:0;">Hóa đơn ShadowRocket chờ duyệt</h2>
                        </div>
                        <div class="divider"></div>
                        <div style="overflow-x:auto;">
                            <table class="tbl" style="table-layout: fixed; width: 100%; text-align: center;">
                                <thead>
                                    <tr>
                                        <th style="width:5%; text-align:center;">STT</th>
                                        <th style="width:15%; text-align:center;">Tài khoản</th>
                                        <th style="width:15%; text-align:center;">Trạng thái</th>
                                        <th style="width:15%; text-align:center;">Ngày gửi</th>
                                        <th style="width:25%; text-align:center;">Cấp quyền & Proxy</th>
                                        <th style="width:10%; text-align:center;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    try {
                                        $stmt = $pdo->query("SELECT * FROM receipts WHERE requested_role LIKE 'sr_%' ORDER BY created_at DESC");
                                        $all_receipts = $stmt->fetchAll();

                                        if (empty($all_receipts)) {
                                            echo "<tr><td colspan='6' style='text-align:center;'>Chưa có hóa đơn ShadowRocket nào</td></tr>";
                                        } else {
                                            $stt = count($all_receipts);
                                            foreach ($all_receipts as $r) {
                                                $r_id = $r['id'];
                                                $r_user = htmlspecialchars($r['username'], ENT_QUOTES, 'UTF-8');
                                                $r_img = htmlspecialchars((string)($r['receipt_img'] ?? ''), ENT_QUOTES, 'UTF-8');
                                                $r_status = $r['status'];
                                                $r_date = date('d/m/Y H:i', strtotime($r['created_at']));

                                                // Get current role of user
                                                $stmt_u = $pdo->prepare("SELECT role, proxy_info FROM users WHERE username = ?");
                                                $stmt_u->execute([$r_user]);
                                                $u_data = $stmt_u->fetch(PDO::FETCH_ASSOC);
                                                $u_role = normalizeRoleValue($u_data['role'] ?? 'user');
                                                $u_proxy = $u_data['proxy_info'] ?? '';
                                                $requested_role = normalizeRoleValue($r['requested_role'] ?? '');
                                                $requested_plan = htmlspecialchars((string) ($r['requested_plan'] ?? ''), ENT_QUOTES, 'UTF-8');
                                                $requested_amount = (int) ($r['requested_amount'] ?? 0);
                                                $selected_role = $requested_role !== '' ? $requested_role : $u_role;

                                                $badge_class = ($r_status == 'hoàn thành') ? 'badge-success' : 'badge-warning';

                                                echo "<tr style='text-align:center;'>";
                                                echo "<td data-label='STT' style='vertical-align:middle;'>#{$stt}</td>";
                                                $stt--;
                                                echo "<td data-label='Tài khoản' style='vertical-align:middle;'><strong>{$r_user}</strong><br><span style='font-size:12px;color:var(--text-2);'>Current: " . htmlspecialchars(getRoleLabel($u_role), ENT_QUOTES, 'UTF-8') . "</span>" . ($requested_plan !== '' ? "<br><span style='font-size:12px;color:var(--accent-bright);'>Yêu cầu: {$requested_plan}" . ($requested_amount > 0 ? " (" . number_format($requested_amount) . "đ)" : "") . "</span>" : "") . "</td>";
                                                echo "<td data-label='Trạng thái' style='vertical-align:middle;'><span class='badge {$badge_class}'>{$r_status}</span></td>";
                                                echo "<td data-label='Ngày gửi' style='vertical-align:middle;'>{$r_date}</td>";
                                                echo "<td data-label='Cấp quyền & Proxy' style='vertical-align:middle;'>
                                                            <form method='POST' style='display:flex; flex-wrap:wrap; align-items:center; justify-content:center; gap:6px; margin:0;'>
                                                                <input type='hidden' name='action' value='admin_set_receipt_role'>
                                                                <input type='hidden' name='is_sr_form' value='1'>
                                                                <input type='hidden' name='receipt_id' value='{$r_id}'>
                                                                <input type='hidden' name='username' value='{$r_user}'>
                                                                <input type='text' name='proxy_info' value='" . htmlspecialchars($u_proxy, ENT_QUOTES, 'UTF-8') . "' placeholder='Nhập Proxy' class='input' style='padding:4px 8px; font-size:12px; height:32px; width:80px; max-width:100%;'>
                                                                <select name='role' class='sel' style='padding:4px 8px; font-size:12px; height:32px; max-width:100%;'>
                                                                    <option value='user'>User (Hủy SR)</option>
                                                                    <option value='sr_vip' " . ($selected_role == 'sr_vip' ? 'selected' : '') . ">ShadowRocket: VIP 15s</option>
                                                                    <option value='sr_premium' " . ($selected_role == 'sr_premium' ? 'selected' : '') . ">ShadowRocket: Premium</option>
                                                                    <option value='sr_ultimate' " . ($selected_role == 'sr_ultimate' ? 'selected' : '') . ">ShadowRocket: Ultimate</option>
                                                                </select>
                                                                <button type='submit' class='btn btn-primary' style='padding:4px 12px; font-size:12px; height:32px; margin:0;'>Duyệt</button>
                                                            </form>
                                                          </td>";
                                                echo "<td data-label='Thao tác' style='vertical-align:middle;'>
                                                            <form method='POST' style='margin:0; display:flex; justify-content:center;' onsubmit=\"return confirm('Xóa hóa đơn này?');\">
                                                                <input type='hidden' name='action' value='admin_del_receipt'>
                                                                <input type='hidden' name='receipt_id' value='{$r_id}'>
                                                                <button type='submit' class='btn btn-outline' style='color:#ef4444; border-color:#ef4444; padding:0 12px; height:32px; font-size:12px;'>Xóa</button>
                                                            </form>
                                                          </td>";
                                                echo "</tr>";
                                            }
                                        }
                                    } catch (Exception $e) {
                                        echo "<tr><td colspan='7' style='text-align:center;'>Lỗi: {$e->getMessage()}</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php elseif ($tab == 'settings_shadowrocket' && $current_role === 'admin'): ?>
                    <div class="admin-panel">
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="admin_save_payment_settings">
                            <div class="admin-header">
                                <h2 class="heading" style="margin:0;">Giá ShadowRocket Module (VND)</h2>
                            </div>
                            <div class="divider"></div>
                            <p style="font-size:13px; color:var(--text-2); margin-bottom:15px;">Cấu hình giá bán cho các gói module
                                ShadowRocket trên trang <code>/shadowrocket</code>. Gói Free = 0đ (không cần cấu hình).</p>
                            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:15px;">
                                <div class="field">
                                    <label>VIP (15s)</label>
                                    <input type="number" name="price_sr_vip" class="input"
                                        value="<?= htmlspecialchars($gs['price_sr_vip'] ?? '49000') ?>" placeholder="49000">
                                    <small style="color:var(--text-2); font-size:11px;">Gold + Video 15s + DNS</small>
                                </div>
                                <div class="field">
                                    <label>Premium</label>
                                    <input type="number" name="price_sr_premium" class="input"
                                        value="<?= htmlspecialchars($gs['price_sr_premium'] ?? '49000') ?>" placeholder="49000">
                                    <small style="color:var(--text-2); font-size:11px;">Gold + 24 Apps</small>
                                </div>
                                <div class="field">
                                    <label>Ultimate</label>
                                    <input type="number" name="price_sr_ultimate" class="input"
                                        value="<?= htmlspecialchars($gs['price_sr_ultimate'] ?? '79000') ?>" placeholder="79000">
                                    <small style="color:var(--text-2); font-size:11px;">Gold + 15s + 24 Apps + DNS + Proxy
                                        US</small>
                                </div>
                            </div>
                            <div style="margin-top:18px;">
                                <label style="font-weight:700; color:var(--text-1); margin-bottom:10px; display:block;">Giá Thuê
                                    Proxy US (cho gói 15s / Ultimate)</label>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                                    <div class="field">
                                        <label>Proxy 1 Tháng</label>
                                        <input type="number" name="price_sr_proxy_1m" class="input"
                                            value="<?= htmlspecialchars($gs['price_sr_proxy_1m'] ?? '20000') ?>"
                                            placeholder="20000">
                                    </div>
                                    <div class="field">
                                        <label>Proxy 1 Năm</label>
                                        <input type="number" name="price_sr_proxy_1y" class="input"
                                            value="<?= htmlspecialchars($gs['price_sr_proxy_1y'] ?? '150000') ?>"
                                            placeholder="150000">
                                    </div>
                                </div>
                            </div>
                            <div class="field" style="margin-top:20px;">
                                <label style="font-weight:700; color:var(--text-1); margin-bottom:10px; display:block;">Video Hướng
                                    Dẫn Cài Đặt Module (MP4)</label>
                                <input type="file" name="sr_video" class="input" accept="video/mp4"
                                    style="padding:10px; background:var(--bg-2);">
                                <small style="color:var(--text-2); font-size:11px;">Tải lên video hướng dẫn cài đặt module (.mp4).
                                    File tải lên sẽ tự động ghi đè file cũ.</small>
                            </div>
                            <div class="field" style="margin-top:15px;">
                                <label style="font-weight:700; color:var(--text-1); margin-bottom:10px; display:block;">Video Hướng
                                    Dẫn Thêm Proxy (MP4)</label>
                                <input type="file" name="sr_proxy_video" class="input" accept="video/mp4"
                                    style="padding:10px; background:var(--bg-2);">
                                <small style="color:var(--text-2); font-size:11px;">Tải lên video hướng dẫn thêm Proxy dành cho gói
                                    VIP/Ultimate (.mp4). File tải lên sẽ tự động ghi đè file cũ.</small>
                            </div>
                            <div class="field" style="margin-top:15px;">
                                <label style="font-weight:700; color:var(--text-1); margin-bottom:10px; display:block;">Video Hướng
                                    Dẫn Nhận ID Apple Tải Shadow (MP4)</label>
                                <input type="file" name="sr_id_apple_video" class="input" accept="video/mp4"
                                    style="padding:10px; background:var(--bg-2);">
                                <small style="color:var(--text-2); font-size:11px;">Tải lên video hướng dẫn nhận ID Apple tải Shadow
                                    (.mp4). File tải lên sẽ tự động ghi đè file cũ.</small>
                            </div>
                            <div class="field" style="margin-top:15px;">
                                <label style="font-weight:700; color:var(--text-1); margin-bottom:10px; display:block;">File Cấu
                                    Hình DNS (.mobileconfig)</label>
                                <input type="file" name="sr_dns_file" class="input" accept=".mobileconfig"
                                    style="padding:10px; background:var(--bg-2);">
                                <small style="color:var(--text-2); font-size:11px;">Tải lên file cấu hình DNS bảo vệ chứng chỉ
                                    (.mobileconfig). Khách hàng sẽ nhấn nút "Tải Cấu hình DNS" để lấy file này.</small>
                            </div>
                            <!-- SHADOWROCKET STEPS BUILDER -->
                            <div
                                style="margin-top:30px; background:var(--bg-2); padding:20px; border-radius:12px; border:1px solid var(--border);">
                                <h3
                                    style="font-size:16px; font-weight:700; color:var(--text-1); margin-top:0; border-bottom:1px solid var(--border); padding-bottom:10px; margin-bottom:20px;">
                                    Quản lý Hướng Dẫn Cài Đặt Các Gói</h3>

                                <!-- Mảng lưu trữ ban đầu -->
                                <?php
                                $defaultFree = [
                                    ['1', 'Tải app ShadowRocket', 'Tải từ các bên cung cấp, có thể mua trực tiếp tại AppStore ~79k hoặc nếu không có thì liên hệ Admin để thuê ID tải (10k).'],
                                    ['2', 'Nhập Module & Bật Giải Mã', 'Nhấn nút "Sao chép" ở trên → Mở app ShadowRocket → Bấm dấu (+) ở góc phải trên cùng → Ứng dụng sẽ tự động dán link → Bấm Lưu. Tiếp theo chọn tab "Cấu hình" (Config) dưới cùng → Nhấn vào chữ "i" bên cạnh cấu hình "QuocVu_LocketFree.module" → Bật công tắc "HTTPS Giải mã" lên.'],
                                    ['3', 'Tạo & Cài Chứng Chỉ', 'Cũng ở màn hình HTTPS Giải mã → Nhấn "Tạo chứng chỉ mới" → Nhấn dấu Tick (✓) góc trên phải. Sau đó nhấn "Cài đặt chứng chỉ" → Cho phép tải hồ sơ.'],
                                    ['4', 'Tin cậy Chứng Chỉ (Quan Trọng)', 'Thoát ra màn hình chính iPhone → Vào "Cài đặt" → "Đã tải về hồ sơ" → Nhấn Cài đặt. Sau đó vào "Cài đặt chung" → "Giới thiệu" → Kéo xuống cùng chọn "Cài đặt tin cậy chứng chỉ" → Bật công tắc xanh cho ShadowRocket.'],
                                    ['5', 'Bật & Trải nghiệm', 'Quay lại trang chủ ShadowRocket → Chọn cấu hình vừa cài (để có dấu tick cam) → Bật công tắc to nhất ở trên cùng → Mở Locket tận hưởng Gold.']
                                ];
                                $defaultVip = [
                                    ['1', 'Tải app ShadowRocket', 'Cách 1: Tự mua trên App Store (~79K) để đảm bảo an toàn & update trọn đời.<br>Cách 2: Inbox Admin để được cấp tài khoản tải miễn phí.'],
                                    ['2', 'Nhập Module & Bật Giải Mã', 'Nhấn "Sao chép link VIP Module" ở trên → Mở app ShadowRocket → Bấm dấu (+) ở góc phải trên cùng → Dán link → Lưu. Tiếp theo chọn tab "Cấu hình" → Nhấn vào chữ "i" bên cạnh cấu hình "QuocVu_Locket15s.module" → Bật "HTTPS Giải mã" → Nhấn "Tạo chứng chỉ mới" → "Cài đặt chứng chỉ".'],
                                    ['3', 'Tin cậy Chứng Chỉ (Quan Trọng)', 'Vào "Cài đặt" iPhone → "Đã tải về hồ sơ" → Cài đặt. Sau đó vào "Cài đặt chung" → "Giới thiệu" → "Cài đặt tin cậy chứng chỉ" → Bật công tắc xanh cho ShadowRocket.'],
                                    ['4', 'Nhận & Thêm Proxy (Để quay 15s)', 'Truy cập <strong>nhóm Zalo</strong> và nhắn tin cho Admin để nhận Proxy. Sau khi nhận: Về trang chủ ShadowRocket → Bấm dấu (+) góc phải trên → Mục "Loại" (Type) chọn <strong>SOCKS5</strong> → Nhập IP và Cổng (Port) → Dưới mục "Xác thực" điền Tên người dùng và Mật khẩu → Bấm Xong. Chạm vào proxy vừa tạo để có dấu tick cam.'],
                                    ['5', 'Cài Đặt DNS (Bảo vệ chứng chỉ)', 'Nhấn nút "Tải Cấu hình DNS" ở bảng trên cùng → Cho phép tải về. Sau đó quay lại "Cài đặt" iPhone → "Đã tải về hồ sơ" → Chọn Cài đặt cấu hình Locket Gold DNS.'],
                                    ['6', 'Bật & Trải nghiệm', 'Bật công tắc chính của ShadowRocket trên cùng → Mở Locket và bắt đầu quay video 15 giây!']
                                ];
                                $defaultPremium = [
                                    ['1', 'Tải app ShadowRocket', 'Cách 1: Tự mua trên App Store (~79K) để đảm bảo an toàn & update trọn đời.<br>Cách 2: Inbox Admin để được cấp tài khoản tải miễn phí.'],
                                    ['2', 'Nhập Module & Bật Giải Mã', 'Nhấn "Sao chép link Premium Module" ở trên → Mở app ShadowRocket → Bấm dấu (+) ở góc phải trên cùng → Ứng dụng sẽ tự động dán link → Bấm Lưu. Tiếp theo chọn tab "Cấu hình" (Config) dưới cùng → Nhấn vào chữ "i" bên cạnh cấu hình "QuocVu_Premium.module" → Bật công tắc "HTTPS Giải mã" lên.'],
                                    ['3', 'Tạo & Cài Chứng Chỉ', 'Cũng ở màn hình HTTPS Giải mã → Nhấn "Tạo chứng chỉ mới" → Nhấn dấu Tick (✓) góc trên phải. Sau đó nhấn "Cài đặt chứng chỉ" → Cho phép tải hồ sơ.'],
                                    ['4', 'Tin cậy Chứng Chỉ (Quan Trọng)', 'Ra màn hình iPhone → Vào "Cài đặt" → "Đã tải về hồ sơ" → Cài đặt. Tiếp theo vào "Cài đặt chung" → "Giới thiệu" → "Cài đặt tin cậy chứng chỉ" → Bật công tắc xanh cho ShadowRocket.'],
                                    ['5', 'Nhận & Thêm Proxy (Nếu có mua thêm)', 'Truy cập <strong>nhóm Zalo</strong> và nhắn tin cho Admin để nhận Proxy. Sau khi nhận: Về trang chủ ShadowRocket → Bấm dấu (+) góc phải trên → Mục "Loại" (Type) chọn <strong>SOCKS5</strong> → Nhập IP và Cổng (Port) → Dưới mục "Xác thực" điền Tên người dùng và Mật khẩu → Bấm Xong. Chạm vào proxy vừa tạo để có dấu tick cam.'],
                                    ['6', 'Cài Đặt DNS (Bảo vệ chứng chỉ)', 'Nhấn nút "Tải Cấu hình DNS" ở bảng trên cùng → Cho phép tải về. Sau đó quay lại "Cài đặt" iPhone → "Đã tải về hồ sơ" → Chọn Cài đặt cấu hình Locket Gold DNS.'],
                                    ['7', 'Bật & Trải nghiệm', 'Chỉ cần chọn đúng cấu hình Premium đã cài (có dấu tick cam) → Bật công tắc to nhất ở trang chủ ShadowRocket. Bạn có thể mở YouTube xem không quảng cáo, nghe Spotify Premium hoặc dùng 24 app khác ngay lập tức!']
                                ];
                                $defaultUltimate = [
                                    ['1', 'Tải app ShadowRocket', 'Cách 1: Tự mua trên App Store (~79K) để đảm bảo an toàn & update trọn đời.<br>Cách 2: Inbox Admin để được cấp tài khoản tải miễn phí.'],
                                    ['2', 'Nhập Module & Bật Giải Mã', 'Nhấn "Sao chép link Ultimate Module" ở trên → Mở app ShadowRocket → Bấm dấu (+) ở góc phải trên cùng → Ứng dụng sẽ tự động dán link → Bấm Lưu. Tiếp theo chọn tab "Cấu hình" (Config) dưới cùng → Nhấn vào chữ "i" bên cạnh cấu hình "QuocVu_Ultimate.module" → Bật công tắc "HTTPS Giải mã" lên.'],
                                    ['3', 'Tạo & Cài Chứng Chỉ', 'Cũng ở màn hình HTTPS Giải mã → Nhấn "Tạo chứng chỉ mới" → Nhấn dấu Tick (✓) góc trên phải. Sau đó nhấn "Cài đặt chứng chỉ" → Cho phép tải hồ sơ.'],
                                    ['4', 'Tin cậy Chứng Chỉ (Bắt buộc)', 'Vào "Cài đặt" iPhone → "Đã tải về hồ sơ" → Cài đặt. Sau đó vào "Cài đặt chung" → "Giới thiệu" → "Cài đặt tin cậy chứng chỉ" → Bật xanh cho ShadowRocket.'],
                                    ['5', 'Nhận & Thêm Proxy (Để quay 15s)', 'Truy cập <strong>nhóm Zalo</strong> và nhắn tin cho Admin để nhận Proxy. Sau khi nhận: Về trang chủ ShadowRocket → Bấm dấu (+) góc phải trên → Mục "Loại" (Type) chọn <strong>SOCKS5</strong> → Nhập IP và Cổng (Port) → Dưới mục "Xác thực" điền Tên người dùng và Mật khẩu → Bấm Xong. Chạm vào proxy vừa tạo để có dấu tick cam.'],
                                    ['6', 'Cài Đặt DNS (Bảo vệ chứng chỉ)', 'Nhấn nút "Tải Cấu hình DNS" ở bảng trên cùng → Cho phép tải về. Sau đó quay lại "Cài đặt" iPhone → "Đã tải về hồ sơ" → Chọn Cài đặt cấu hình Locket Gold DNS.'],
                                    ['7', 'Bật & Trải nghiệm Full', 'Bật công tắc chính của ShadowRocket. Giờ đây bạn đã có toàn bộ: Locket Video 15s, YouTube/Spotify không quảng cáo, và 24+ Premium App khác hoạt động trơn tru!']
                                ];

                                $valFree = !empty($gs['sr_steps_free']) ? $gs['sr_steps_free'] : json_encode($defaultFree);
                                $valVip = !empty($gs['sr_steps_vip']) ? $gs['sr_steps_vip'] : json_encode($defaultVip);
                                $valPremium = !empty($gs['sr_steps_premium']) ? $gs['sr_steps_premium'] : json_encode($defaultPremium);
                                $valUltimate = !empty($gs['sr_steps_ultimate']) ? $gs['sr_steps_ultimate'] : json_encode($defaultUltimate);
                                ?>
                                <input type="hidden" name="sr_steps_free" id="val_sr_steps_free"
                                    value="<?= htmlspecialchars($valFree) ?>">
                                <input type="hidden" name="sr_steps_vip" id="val_sr_steps_vip"
                                    value="<?= htmlspecialchars($valVip) ?>">
                                <input type="hidden" name="sr_steps_premium" id="val_sr_steps_premium"
                                    value="<?= htmlspecialchars($valPremium) ?>">
                                <input type="hidden" name="sr_steps_ultimate" id="val_sr_steps_ultimate"
                                    value="<?= htmlspecialchars($valUltimate) ?>">

                                <!-- UI Tabs cho các gói -->
                                <div style="display:flex; gap:10px; margin-bottom:15px; flex-wrap:wrap;">
                                    <button type="button" class="btn btn-outline" style="flex:1" onclick="switchSrTab('free')">Gói
                                        Free</button>
                                    <button type="button" class="btn btn-outline" style="flex:1" onclick="switchSrTab('vip')">Gói
                                        VIP</button>
                                    <button type="button" class="btn btn-outline" style="flex:1"
                                        onclick="switchSrTab('premium')">Gói Premium</button>
                                    <button type="button" class="btn btn-outline" style="flex:1"
                                        onclick="switchSrTab('ultimate')">Gói Ultimate</button>
                                </div>

                                <div id="sr_step_editor_container">
                                    <div style="text-align:center; padding:20px; color:var(--text-2); font-size:13px;">Chọn một gói
                                        ở trên để chỉnh sửa các bước cài đặt.</div>
                                </div>
                            </div>

                            <script>
                                let currentEditingPackage = null;
                                let currentSteps = [];

                                function switchSrTab(pkg) {
                                    document.getElementById('sr_step_editor_container').innerHTML = '';
                                    currentEditingPackage = pkg;
                                    try {
                                        currentSteps = JSON.parse(document.getElementById('val_sr_steps_' + pkg).value);
                                    } catch (e) {
                                        currentSteps = [];
                                    }
                                    renderSrSteps();
                                }

                                function renderSrSteps() {
                                    if (!currentEditingPackage) return;
                                    let html = '<div style="margin-bottom:15px; display:flex; justify-content:space-between; align-items:center;">';
                                    html += '<h4 style="margin:0; font-weight:700; color:var(--text-1); text-transform:uppercase;">Chỉnh sửa gói: ' + currentEditingPackage + '</h4>';
                                    html += '<button type="button" class="btn btn-primary" style="padding:6px 12px; font-size:12px;" onclick="addSrStep()">+ Thêm Bước Mới</button>';
                                    html += '</div>';

                                    if (currentSteps.length === 0) {
                                        html += '<div style="color:var(--text-2); font-size:13px; text-align:center; padding:15px; border:1px dashed var(--border); border-radius:8px;">Chưa có bước nào. Hãy thêm bước mới.</div>';
                                    } else {
                                        html += '<div style="display:flex; flex-direction:column; gap:10px;">';
                                        currentSteps.forEach((step, index) => {
                                            html += `
                                                <div style="background:var(--bg-1); border:1px solid var(--border); padding:15px; border-radius:8px; display:flex; flex-direction:column; gap:10px; position:relative;">
                                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                                        <span style="font-weight:700; color:var(--text-2); font-size:12px;">Bước ${index + 1}</span>
                                                        <div style="display:flex; gap:6px;">
                                                            <button type="button" class="btn btn-outline" style="padding:4px 8px; font-size:11px;" onclick="moveSrStep(${index}, -1)">▲</button>
                                                            <button type="button" class="btn btn-outline" style="padding:4px 8px; font-size:11px;" onclick="moveSrStep(${index}, 1)">▼</button>
                                                            <button type="button" class="btn btn-danger" style="padding:4px 8px; font-size:11px; background:#ef4444; border:none; color:white;" onclick="removeSrStep(${index})">Xóa</button>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label style="font-size:11px; color:var(--text-2);">Tiêu đề bước</label>
                                                        <input type="text" class="input" style="padding:8px;" value="${escapeHtml(step[1])}" oninput="updateSrStep(${index}, 1, this.value)">
                                                    </div>
                                                    <div>
                                                        <label style="font-size:11px; color:var(--text-2);">Nội dung chi tiết (HTML)</label>
                                                        <textarea class="input" style="padding:8px; min-height:60px; resize:vertical;" oninput="updateSrStep(${index}, 2, this.value)">${escapeHtml(step[2])}</textarea>
                                                    </div>
                                                </div>`;
                                        });
                                        html += '</div>';
                                    }
                                    document.getElementById('sr_step_editor_container').innerHTML = html;
                                    saveSrStepsToInput();
                                }

                                function addSrStep() {
                                    currentSteps.push(['', 'Tiêu đề mới', 'Nội dung chi tiết']);
                                    recalculateSrStepNumbers();
                                    renderSrSteps();
                                }

                                function removeSrStep(index) {
                                    if (confirm("Bạn có chắc muốn xóa bước này?")) {
                                        currentSteps.splice(index, 1);
                                        recalculateSrStepNumbers();
                                        renderSrSteps();
                                    }
                                }

                                function moveSrStep(index, dir) {
                                    if (index + dir < 0 || index + dir >= currentSteps.length) return;
                                    let temp = currentSteps[index];
                                    currentSteps[index] = currentSteps[index + dir];
                                    currentSteps[index + dir] = temp;
                                    recalculateSrStepNumbers();
                                    renderSrSteps();
                                }

                                function updateSrStep(index, field, val) {
                                    currentSteps[index][field] = val;
                                    saveSrStepsToInput();
                                }

                                function recalculateSrStepNumbers() {
                                    currentSteps.forEach((s, idx) => {
                                        s[0] = (idx + 1).toString();
                                    });
                                }

                                function saveSrStepsToInput() {
                                    document.getElementById('val_sr_steps_' + currentEditingPackage).value = JSON.stringify(currentSteps);
                                }

                                function escapeHtml(unsafe) {
                                    return (unsafe || '').replace(/&/g, "&amp;")
                                        .replace(/</g, "&lt;")
                                        .replace(/>/g, "&gt;")
                                        .replace(/"/g, "&quot;")
                                        .replace(/'/g, "&#039;");
                                }
                            </script>

                            <button type="submit" class="btn btn-primary"
                                style="margin-top:20px; font-size:16px; padding:12px 24px; width:100%;">Lưu Cấu Hình
                                ShadowRocket</button>
                        </form>
                    </div>
                <?php endif; ?>
                <?php if ($tab == 'dashboard'): ?>
                    <div class="admin-panel">
                        <h2 class="heading mb-lg">Tổng quan hệ thống</h2>

                        <h3
                            style="margin-bottom: 16px; font-size: 14px; color: var(--text-2); text-transform: uppercase; letter-spacing: 1px;">
                            Kinh doanh & Tài chính</h3>
                        <div class="admin-stat-grid mb-lg">
                            <div class="admin-stat" style="border-top: 3px solid var(--accent-bright);">
                                <div class="admin-stat-title">Doanh thu hôm nay</div>
                                <div class="admin-stat-val" style="color:var(--accent-bright);">
                                    <?= number_format($today_revenue) ?>đ
                                </div>
                            </div>
                            <div class="admin-stat" style="border-top: 3px solid var(--green);">
                                <div class="admin-stat-title">Tổng doanh thu</div>
                                <div class="admin-stat-val" style="color:var(--green);">
                                    <?= number_format($total_revenue) ?>đ
                                </div>
                            </div>
                            <div class="admin-stat" style="border-top: 3px solid var(--orange);">
                                <div class="admin-stat-title">Nạp chờ duyệt</div>
                                <div class="admin-stat-val" style="color:var(--orange);">
                                    <?= number_format($pending_receipts) ?>
                                </div>
                            </div>
                            <div class="admin-stat" style="border-top: 3px solid var(--text-0);">
                                <div class="admin-stat-title">Kích hoạt hôm nay</div>
                                <div class="admin-stat-val"><?= number_format($today_acts) ?></div>
                            </div>
                        </div>

                        <h3
                            style="margin-bottom: 16px; font-size: 14px; color: var(--text-2); text-transform: uppercase; letter-spacing: 1px;">
                            Biểu đồ Doanh thu (Tuần này - Từ Thứ 2)</h3>
                        <div
                            style="background:var(--bg-0); padding:20px; border-radius:12px; border:1px solid var(--border); margin-bottom: 24px; position: relative; height: 300px;">
                            <canvas id="revenueWeekChart"></canvas>
                        </div>

                        <h3
                            style="margin-bottom: 16px; font-size: 14px; color: var(--text-2); text-transform: uppercase; letter-spacing: 1px;">
                            Biểu đồ Doanh thu (Tháng này)</h3>
                        <div
                            style="background:var(--bg-0); padding:20px; border-radius:12px; border:1px solid var(--border); margin-bottom: 24px; position: relative; height: 300px;">
                            <canvas id="revenueMonthChart"></canvas>
                        </div>
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                            const commonOptions = {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: function (context) {
                                                let value = context.raw || 0;
                                                return value.toLocaleString('vi-VN') + ' VNĐ';
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function (value) {
                                                if (value >= 1000000) return (value / 1000000) + 'M';
                                                if (value >= 1000) return (value / 1000) + 'K';
                                                return value;
                                            }
                                        }
                                    }
                                }
                            };

                            // Biểu đồ Tuần (Line Chart)
                            new Chart(document.getElementById('revenueWeekChart').getContext('2d'), {
                                type: 'line',
                                data: {
                                    labels: <?= $chart_week_labels_json ?>,
                                    datasets: [{
                                        label: 'Doanh thu',
                                        data: <?= $chart_week_data_json ?>,
                                        borderColor: '#10b981',
                                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                        borderWidth: 3, fill: true, tension: 0.4,
                                        pointBackgroundColor: '#10b981', pointRadius: 4,
                                    }]
                                },
                                options: commonOptions
                            });

                            // Biểu đồ Tháng (Bar Chart)
                            new Chart(document.getElementById('revenueMonthChart').getContext('2d'), {
                                type: 'bar',
                                data: {
                                    labels: <?= $chart_month_labels_json ?>,
                                    datasets: [{
                                        label: 'Doanh thu',
                                        data: <?= $chart_month_data_json ?>,
                                        backgroundColor: '#3b82f6',
                                        borderRadius: 4,
                                    }]
                                },
                                options: commonOptions
                            });
                        </script>

                        <h3
                            style="margin-bottom: 16px; font-size: 14px; color: var(--text-2); text-transform: uppercase; letter-spacing: 1px;">
                            Thống kê tổng</h3>
                        <div class="admin-stat-grid">
                            <div class="admin-stat">
                                <div class="admin-stat-title">Tổng tài khoản</div>
                                <div class="admin-stat-val"><?= number_format($cnt_users) ?></div>
                            </div>
                            <div class="admin-stat">
                                <div class="admin-stat-title">Tổng lượt kích hoạt</div>
                                <div class="admin-stat-val"><?= number_format($cnt_acts) ?></div>
                            </div>
                            <div class="admin-stat">
                                <div class="admin-stat-title">Thành viên VIP</div>
                                <div class="admin-stat-val" style="color:var(--accent-bright);">
                                    <?= number_format($cnt_vips) ?>
                                </div>
                            </div>
                            <div class="admin-stat">
                                <div class="admin-stat-title">Đối tác Đại lý</div>
                                <div class="admin-stat-val" style="color:var(--orange);"><?= number_format($cnt_agency) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($tab == 'settings'): ?>
                    <div class="admin-panel">
                        <h2 class="heading mb-md">Cài Đặt Cửa Sổ Thông Báo (Popup)</h2>
                        <form method="POST" style="display:flex; flex-direction:column; gap:16px; max-width:600px;">
                            <input type="hidden" name="action" value="admin_save_notice">
                            <div class="field">
                                <label>Trạng thái hiển thị</label>
                                <select name="notice_active" class="input">
                                    <option value="1" <?= ($settings['notice_active'] ?? '0') == '1' ? 'selected' : '' ?>>ĐANG
                                        BẬT (Hiển thị cho mọi người)</option>
                                    <option value="0" <?= ($settings['notice_active'] ?? '0') == '0' ? 'selected' : '' ?>>TẮT
                                    </option>
                                </select>
                            </div>
                            <div class="field">
                                <label>Tiêu đề thông báo</label>
                                <input type="text" name="notice_title" class="input"
                                    value="<?= htmlspecialchars($settings['notice_title'] ?? '') ?>" required>
                            </div>
                            <div class="field">
                                <label>Nội dung thông báo (hỗ trợ xuống dòng)</label>
                                <textarea name="notice_content" class="input" style="height:100px; padding:12px;"
                                    required><?= htmlspecialchars($settings['notice_content'] ?? '') ?></textarea>
                            </div>
                            <div class="field">
                                <label>Link chèn vào nút (Tùy chọn)</label>
                                <input type="text" name="notice_link" class="input"
                                    value="<?= htmlspecialchars($settings['notice_link'] ?? '') ?>">
                            </div>
                            <div class="field">
                                <label>Tên chữ trên nút (Vd: Tham gia ngay)</label>
                                <input type="text" name="notice_btn_text" class="input"
                                    value="<?= htmlspecialchars($settings['notice_btn_text'] ?? '') ?>">
                            </div>
                            <div style="display:flex; gap:20px;">
                                <div class="field" style="flex:1;">
                                    <label>Màu nền nút chặn</label>
                                    <input type="color" name="notice_bg" class="input" style="padding:4px; height:48px;"
                                        value="<?= htmlspecialchars($settings['notice_bg'] ?? '#A78BFA') ?>">
                                </div>
                                <div class="field" style="flex:1;">
                                    <label>Màu chữ nút</label>
                                    <input type="color" name="notice_text" class="input" style="padding:4px; height:48px;"
                                        value="<?= htmlspecialchars($settings['notice_text'] ?? '#ffffff') ?>">
                                </div>
                            </div>
                            <div style="display:flex; gap:20px; flex-wrap:wrap; margin-top:20px;">
                                <div class="field" style="flex:1; min-width:200px;">
                                    <label>Nhóm Zalo Bảo Hành (URL)</label>
                                    <input type="text" name="zalo_baohanh_url" class="input" style="padding:14px;"
                                        value="<?= htmlspecialchars($settings['zalo_baohanh_url'] ?? '') ?>"
                                        placeholder="https://zalo.me/g/..." />
                                </div>
                                <div class="field" style="flex:1; min-width:200px;">
                                    <label>Nhóm Zalo Hỏi Đáp (URL)</label>
                                    <input type="text" name="zalo_hoidap_url" class="input" style="padding:14px;"
                                        value="<?= htmlspecialchars($settings['zalo_hoidap_url'] ?? '') ?>"
                                        placeholder="https://zalo.me/..." />
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" style="margin-top:24px;">Lưu Cấu Hình</button>
                        </form>
                    </div>

                    <!-- Receipt Pool Dashboard -->
                    <div class="admin-panel" style="margin-top:24px;">
                        <h2 class="heading mb-md">Hồ Chứa Hóa Đơn (Receipt Pool) <span
                                style="background:var(--green); color:#fff; font-size:11px; padding:2px 6px; border-radius:4px; margin-left:8px;">SMART
                                MAPPING</span></h2>
                        <p class="desc mb-md">Hệ thống <strong>Receipt Mapping 1:1</strong> — mỗi UID được gán cố định 1 receipt,
                            chống mất Gold do collision. Nhập danh sách các mã <code>APPLE_RECEIPT_BASE64</code> (mỗi dòng 1 mã).
                        </p>

                        <?php
                        // Thống kê receipt pool
                        $receipt_pool_raw = $settings['premium_receipts'] ?? '';
                        $receipt_pool_arr = array_values(array_filter(array_map('trim', explode("\n", $receipt_pool_raw))));
                        $total_receipts = count($receipt_pool_arr);
                        $is_single_receipt_mode = ($total_receipts <= 1);

                        $assigned_count = 0;
                        $receipt_assignments_list = [];
                        try {
                            $assigned_count = (int) $pdo->query("SELECT COUNT(*) FROM receipt_assignments WHERE is_active = 1 AND assigned_uid IS NOT NULL")->fetchColumn();
                            $receipt_assignments_list = $pdo->query("SELECT ra.*, a.injected_by as web_user FROM receipt_assignments ra LEFT JOIN activations a ON a.uid = ra.assigned_uid ORDER BY ra.last_used_at DESC LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);
                        } catch (Exception $e) {
                        }

                        if ($is_single_receipt_mode) {
                            $total_active = 0;
                            try {
                                $total_active = (int) $pdo->query("SELECT COUNT(DISTINCT uid) FROM activations WHERE status = 'Activated (Live)'")->fetchColumn();
                            } catch (Exception $e) {
                            }
                            $health_color = '#f59e0b';
                            $health_text = 'DNS bảo vệ';
                            $mode_label = '1 RECEIPT';
                            $mode_color = '#f59e0b';
                        } else {
                            $free_receipts = max(0, $total_receipts - $assigned_count);
                            $health_color = $free_receipts > 5 ? '#10b981' : ($free_receipts > 0 ? '#f59e0b' : '#ef4444');
                            $health_text = $free_receipts > 5 ? 'Tốt' : ($free_receipts > 0 ? 'Sắp hết' : 'Hết receipt trống!');
                            $mode_label = 'MULTI RECEIPT';
                            $mode_color = '#10b981';
                        }
                        ?>

                        <?php if ($is_single_receipt_mode): ?>
                            <div
                                style="background:linear-gradient(135deg, rgba(251,191,36,0.1), rgba(249,115,22,0.05)); border:2px solid rgba(251,191,36,0.4); border-radius:12px; padding:16px; margin-bottom:20px;">
                                <div style="font-size:15px; font-weight:800; color:#f59e0b; margin-bottom:8px;">⚡ Chế độ 1 Receipt — Bảo
                                    vệ bằng DNS</div>
                                <div style="font-size:13px; color:var(--text-1); line-height:1.7;">
                                    Bạn đang dùng <strong>1 receipt duy nhất</strong> cho tất cả khách hàng. Cách hệ thống hoạt động:
                                    <ul style="margin:8px 0 0 18px; padding:0;">
                                        <li>Mỗi lần kích hoạt, Gold <strong>chuyển từ khách cũ sang khách mới</strong> trên server
                                            RevenueCat.</li>
                                        <li>Tất cả khách cũ vẫn giữ Gold nhờ <strong>DNS chặn</strong> app kiểm tra server.</li>
                                        <li>Cron Auto-Recovery chỉ re-activate <strong>UID gần nhất</strong> (tránh ping-pong transfer).
                                        </li>
                                        <li>Muốn an toàn hơn? <strong>Thêm nhiều receipt</strong> vào pool → hệ thống tự chuyển sang chế
                                            độ Mapping 1:1.</li>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Stats Cards -->
                        <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:12px; margin-bottom:20px;">
                            <div
                                style="background:var(--bg-2); border:1px solid var(--border); border-radius:12px; padding:16px; text-align:center;">
                                <div style="font-size:28px; font-weight:800; color:var(--accent-bright);">
                                    <?= $total_receipts ?: '1 (.env)' ?></div>
                                <div style="font-size:12px; color:var(--text-2); margin-top:4px;">Tổng Receipt</div>
                            </div>
                            <div
                                style="background:var(--bg-2); border:1px solid var(--border); border-radius:12px; padding:16px; text-align:center;">
                                <?php if ($is_single_receipt_mode): ?>
                                    <div style="font-size:28px; font-weight:800; color:#3b82f6;"><?= $total_active ?? 0 ?></div>
                                    <div style="font-size:12px; color:var(--text-2); margin-top:4px;">UID đang Active</div>
                                <?php else: ?>
                                    <div style="font-size:28px; font-weight:800; color:#3b82f6;"><?= $assigned_count ?></div>
                                    <div style="font-size:12px; color:var(--text-2); margin-top:4px;">Đã gán</div>
                                <?php endif; ?>
                            </div>
                            <div
                                style="background:var(--bg-2); border:1px solid var(--border); border-radius:12px; padding:16px; text-align:center;">
                                <?php if ($is_single_receipt_mode): ?>
                                    <div style="font-size:16px; font-weight:800; color:#f59e0b; margin-top:6px;">🛡️ DNS</div>
                                    <div style="font-size:12px; color:var(--text-2); margin-top:4px;">Phương thức bảo vệ</div>
                                <?php else: ?>
                                    <div style="font-size:28px; font-weight:800; color:<?= $health_color ?>;"><?= $free_receipts ?>
                                    </div>
                                    <div style="font-size:12px; color:var(--text-2); margin-top:4px;">Còn trống</div>
                                <?php endif; ?>
                            </div>
                            <div
                                style="background:var(--bg-2); border:1px solid var(--border); border-radius:12px; padding:16px; text-align:center;">
                                <div style="font-size:16px; font-weight:800; color:<?= $health_color ?>; margin-top:6px;">●
                                    <?= $health_text ?></div>
                                <div style="font-size:12px; color:var(--text-2); margin-top:4px;">Trạng thái</div>
                            </div>
                        </div>

                        <?php if (!$is_single_receipt_mode && $free_receipts <= 2 && $total_receipts > 0): ?>
                            <div
                                style="background:rgba(239,68,68,0.08); border:1px dashed rgba(239,68,68,0.4); border-radius:10px; padding:14px; margin-bottom:16px; font-size:13px; color:var(--text-1); line-height:1.5;">
                                ⚠️ <strong style="color:#ef4444;">Cảnh báo:</strong> Pool sắp/đã hết receipt trống. Nếu có khách mới
                                kích hoạt, receipt sẽ phải chia sẻ và có nguy cơ collision. Hãy thêm receipt mới vào pool!
                            </div>
                        <?php endif; ?>

                        <!-- Textarea nhập receipt -->
                        <form method="POST" style="display:flex; flex-direction:column; gap:16px; margin-bottom:24px;">
                            <input type="hidden" name="action" value="admin_save_receipt_pool">
                            <div class="field">
                                <label>Danh sách Receipt (mỗi dòng 1 mã)</label>
                                <textarea name="premium_receipts" class="input"
                                    style="height:160px; padding:12px; font-family:monospace; white-space:pre; font-size:11px;"
                                    placeholder="VD:&#10;MIIV...abc1&#10;MIIV...xyz2"><?= htmlspecialchars($settings['premium_receipts'] ?? '') ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Lưu Danh Sách Hóa Đơn</button>
                        </form>

                        <!-- Bảng Receipt Assignments -->
                        <?php if (!empty($receipt_assignments_list)): ?>
                            <div style="margin-top:8px;">
                                <h3 style="font-size:15px; font-weight:700; margin-bottom:12px; color:var(--text-0);">📋 Mapping Receipt
                                    → UID (<?= count($receipt_assignments_list) ?> gần nhất)</h3>
                                <div style="overflow-x:auto; border:1px solid var(--border); border-radius:12px;">
                                    <table class="admin-table" style="width:100%; font-size:12px;">
                                        <thead>
                                            <tr>
                                                <th style="padding:10px 12px; white-space:nowrap;">Receipt #</th>
                                                <th style="padding:10px 12px;">Locket UID</th>
                                                <th style="padding:10px 12px;">User Web</th>
                                                <th style="padding:10px 12px; white-space:nowrap;">Lần dùng</th>
                                                <th style="padding:10px 12px; white-space:nowrap;">Lần cuối</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($receipt_assignments_list as $ra): ?>
                                                <tr>
                                                    <td style="padding:8px 12px; font-weight:700; color:var(--accent-bright);">
                                                        #<?= $ra['receipt_index'] ?></td>
                                                    <td
                                                        style="padding:8px 12px; font-family:monospace; font-size:11px; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                                        <?= htmlspecialchars($ra['assigned_uid'] ?? '—') ?></td>
                                                    <td style="padding:8px 12px;">
                                                        <?= htmlspecialchars($ra['assigned_by'] ?? $ra['web_user'] ?? '—') ?></td>
                                                    <td style="padding:8px 12px; text-align:center;"><?= $ra['use_count'] ?></td>
                                                    <td style="padding:8px 12px; font-size:11px; color:var(--text-2); white-space:nowrap;">
                                                        <?= $ra['last_used_at'] ? date('d/m H:i', strtotime($ra['last_used_at'])) : '—' ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Video Hướng Dẫn -->
                    <div class="admin-panel" style="margin-top:24px;">
                        <h2 class="heading mb-md">Video Hướng Dẫn Theo Từng Mục</h2>
                        <p class="desc mb-md">Mỗi tab hướng dẫn dùng 1 video riêng: mục Sử dụng &amp; kích hoạt và mục
                            Khắc phục lỗi mất Gold.</p>

                        <?php
                        $guide_video_activation = $settings['guide_video_activation'] ?? ($settings['guide_video'] ?? '');
                        $guide_video_recovery = $settings['guide_video_recovery'] ?? '';
                        ?>

                        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:16px;">
                            <div
                                style="background:var(--bg-2); border:1px solid var(--border); border-radius:var(--radius-md); padding:16px;">
                                <h3 style="margin:0 0 10px; font-size:15px; color:var(--text-0);">Video mục 1: Sử dụng &amp;
                                    kích hoạt</h3>
                                <?php if ($guide_video_activation && file_exists($guide_video_activation)): ?>
                                    <video controls
                                        style="width:100%; max-height:220px; border-radius:8px; background:#000; margin-bottom:10px;">
                                        <source src="/<?= htmlspecialchars($guide_video_activation) ?>" type="video/mp4">
                                    </video>
                                    <div style="font-size:12px; color:var(--text-2); margin-bottom:10px;">📁
                                        <?= htmlspecialchars($guide_video_activation) ?> —
                                        <?= round(filesize($guide_video_activation) / 1048576, 1) ?> MB
                                    </div>
                                    <form method="POST" style="margin-bottom:10px;" onsubmit="return confirm('Xóa video mục 1?');">
                                        <input type="hidden" name="action" value="admin_delete_guide_video_activation">
                                        <button type="submit" class="action-btn del" style="padding:6px 12px; font-size:12px;">Xóa video
                                            mục 1</button>
                                    </form>
                                <?php endif; ?>
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="action" value="admin_upload_guide_video_activation">
                                    <div class="field" style="margin-bottom:10px;">
                                        <label>Chọn file video mục 1</label>
                                        <input type="file" name="guide_video" accept="video/*" class="input" style="padding:12px;"
                                            required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" style="width:100%;">Upload mục 1</button>
                                </form>
                            </div>

                            <div
                                style="background:var(--bg-2); border:1px solid var(--border); border-radius:var(--radius-md); padding:16px;">
                                <h3 style="margin:0 0 10px; font-size:15px; color:var(--text-0);">Video mục 2: Khắc phục mất
                                    Gold</h3>
                                <?php if ($guide_video_recovery && file_exists($guide_video_recovery)): ?>
                                    <video controls
                                        style="width:100%; max-height:220px; border-radius:8px; background:#000; margin-bottom:10px;">
                                        <source src="/<?= htmlspecialchars($guide_video_recovery) ?>" type="video/mp4">
                                    </video>
                                    <div style="font-size:12px; color:var(--text-2); margin-bottom:10px;">📁
                                        <?= htmlspecialchars($guide_video_recovery) ?> —
                                        <?= round(filesize($guide_video_recovery) / 1048576, 1) ?> MB
                                    </div>
                                    <form method="POST" style="margin-bottom:10px;" onsubmit="return confirm('Xóa video mục 2?');">
                                        <input type="hidden" name="action" value="admin_delete_guide_video_recovery">
                                        <button type="submit" class="action-btn del" style="padding:6px 12px; font-size:12px;">Xóa video
                                            mục 2</button>
                                    </form>
                                <?php endif; ?>
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="action" value="admin_upload_guide_video_recovery">
                                    <div class="field" style="margin-bottom:10px;">
                                        <label>Chọn file video mục 2</label>
                                        <input type="file" name="guide_video" accept="video/*" class="input" style="padding:12px;"
                                            required>
                                    </div>
                                    <button type="submit" class="btn btn-primary"
                                        style="width:100%; background:linear-gradient(135deg,#f59e0b,#f97316); border:none;">Upload
                                        mục 2</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($tab == 'articles'): ?>
                    <div class="admin-panel">
                        <div class="admin-header">
                            <h2 class="heading" style="margin:0;">Quản lý Bài Viết (Blog)</h2>
                            <button class="btn btn-primary btn-sm" onclick="showModal('modal-add-article')">+ Viết bài
                                mới</button>
                        </div>
                        <div class="divider"></div>
                        <div style="overflow-x:auto;">
                            <table class="tbl">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Ảnh</th>
                                        <th>Tiêu đề / Slug</th>
                                        <th>Lượt xem</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $page_no = max(1, intval($_GET['p'] ?? 1));
                                    $limit = 20;
                                    $offset = ($page_no - 1) * $limit;
                                    $total_articles = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
                                    $total_pages = ceil($total_articles / $limit);

                                    $stmt = $pdo->query("SELECT * FROM articles ORDER BY id DESC LIMIT $limit OFFSET $offset");
                                    foreach ($stmt->fetchAll() as $art):
                                        ?>
                                        <tr>
                                            <td>#<?= $art['id'] ?></td>
                                            <td>
                                                <?php if ($art['thumbnail']): ?>
                                                    <img src="<?= htmlspecialchars($art['thumbnail']) ?>"
                                                        style="width:50px; height:35px; object-fit:cover; border-radius:4px;">
                                                <?php else: ?>
                                                    <span style="color:var(--text-2); font-size:11px;">Trống</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div
                                                    style="font-weight:600; color:var(--text-1); max-width:250px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                    <?= htmlspecialchars($art['title']) ?>
                                                </div>
                                                <div style="font-size:12px; color:var(--text-2); margin-top:4px;">
                                                    /bai-viet?slug=<?= htmlspecialchars($art['slug']) ?>
                                                </div>
                                            </td>
                                            <td><?= number_format($art['views']) ?></td>
                                            <td>
                                                <?php if ($art['is_published']): ?>
                                                    <span
                                                        style="background:rgba(34,197,94,0.1); color:#22c55e; padding:4px 8px; border-radius:12px; font-size:11px; font-weight:600;">Đã
                                                        xuất bản</span>
                                                <?php else: ?>
                                                    <span
                                                        style="background:rgba(239,68,68,0.1); color:#ef4444; padding:4px 8px; border-radius:12px; font-size:11px; font-weight:600;">Bản
                                                        nháp</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div style="font-size:13px; color:var(--text-1);">
                                                    <?= date('d/m/Y', strtotime($art['created_at'])) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div style="display:flex; gap:8px;">
                                                    <a href="/bai-viet?slug=<?= htmlspecialchars($art['slug']) ?>" target="_blank"
                                                        class="action-btn"
                                                        style="background:rgba(59,130,246,0.1); color:#3b82f6;">Xem</a>
                                                    <button
                                                        onclick='editArticle(<?= htmlspecialchars(json_encode($art), ENT_QUOTES, "UTF-8") ?>)'
                                                        class="action-btn">Sửa</button>
                                                    <form method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không? Hành động này không thể hoàn tác!')">
                                                        <input type="hidden" name="action" value="admin_delete_article">
                                                        <input type="hidden" name="article_id" value="<?= $art['id'] ?>">
                                                        <button type="submit" class="action-btn del">Xóa</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($total_pages > 1): ?>
                            <div style="display:flex; justify-content:center; gap:8px; margin-top:20px;">
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <a href="?tab=articles&p=<?= $i ?>" class="btn <?= $i == $page_no ? 'btn-primary' : 'btn-outline' ?>"
                                        style="padding:6px 12px; min-width:unset;"><?= $i ?></a>
                                <?php endfor; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Modal Add Article -->
                    <div id="modal-add-article" class="admin-modal">
                        <div class="modal-box" style="max-width:800px; max-height:90vh; overflow-y:auto;">
                            <h3 class="heading-sm mb-md">✍️ Viết bài mới</h3>
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="admin_add_article">
                                <div class="field">
                                    <label>Tiêu đề bài viết <span style="color:var(--error);">*</span></label>
                                    <input type="text" name="title" class="input" required placeholder="Nhập tiêu đề...">
                                </div>
                                <div class="field">
                                    <label>Đường dẫn (Slug) <small style="color:var(--text-2);">(Tự tạo nếu để
                                            trống)</small></label>
                                    <input type="text" name="slug" class="input" placeholder="VD: huong-dan-dung-locket">
                                </div>
                                <div class="field">
                                    <label>Mô tả ngắn (Excerpt)</label>
                                    <textarea name="excerpt" class="input" style="height:80px; padding:12px;"
                                        placeholder="Tóm tắt bài viết..."></textarea>
                                </div>
                                <div class="field">
                                    <label>Nội dung chi tiết (Hỗ trợ HTML) <span style="color:var(--error);">*</span></label>
                                    <textarea name="content" class="input"
                                        style="height:300px; padding:12px; font-family:monospace;" required
                                        placeholder="<p>Nhập nội dung bài viết...</p>"></textarea>
                                </div>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                                    <div class="field">
                                        <label>Ảnh đại diện (Thumbnail)</label>
                                        <input type="file" name="thumbnail" class="input" accept="image/*" style="padding:10px;">
                                    </div>
                                    <div class="field">
                                        <label>Trạng thái hiển thị</label>
                                        <label
                                            style="display:flex; align-items:center; gap:8px; background:var(--bg-1); padding:12px; border-radius:12px; border:1px solid var(--border); cursor:pointer;">
                                            <input type="checkbox" name="is_published" value="1" checked
                                                style="width:20px; height:20px; accent-color:var(--accent-bright);">
                                            <span style="font-size:14px; color:var(--text-1); font-weight:500;">Công khai
                                                (Xuất bản)</span>
                                        </label>
                                    </div>
                                </div>
                                <div style="display:flex; gap:12px; margin-top:24px;">
                                    <button type="button" class="btn btn-outline" onclick="closeModal('modal-add-article')">Hủy
                                        bỏ</button>
                                    <button type="submit" class="btn btn-primary">Đăng bài viết</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Edit Article -->
                    <div id="modal-edit-article" class="admin-modal">
                        <div class="modal-box" style="max-width:800px; max-height:90vh; overflow-y:auto;">
                            <h3 class="heading-sm mb-md">✏️ Cập nhật bài viết</h3>
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="admin_edit_article">
                                <input type="hidden" name="article_id" id="ea_id">
                                <div class="field">
                                    <label>Tiêu đề bài viết <span style="color:var(--error);">*</span></label>
                                    <input type="text" name="title" id="ea_title" class="input" required>
                                </div>
                                <div class="field">
                                    <label>Đường dẫn (Slug)</label>
                                    <input type="text" name="slug" id="ea_slug" class="input" required>
                                </div>
                                <div class="field">
                                    <label>Mô tả ngắn (Excerpt)</label>
                                    <textarea name="excerpt" id="ea_excerpt" class="input"
                                        style="height:80px; padding:12px;"></textarea>
                                </div>
                                <div class="field">
                                    <label>Nội dung chi tiết (HTML) <span style="color:var(--error);">*</span></label>
                                    <textarea name="content" id="ea_content" class="input"
                                        style="height:300px; padding:12px; font-family:monospace;" required></textarea>
                                </div>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                                    <div class="field">
                                        <label>Đổi ảnh đại diện (để trống nếu giữ nguyên)</label>
                                        <input type="file" name="thumbnail" class="input" accept="image/*" style="padding:10px;">
                                    </div>
                                    <div class="field">
                                        <label>Trạng thái hiển thị</label>
                                        <label
                                            style="display:flex; align-items:center; gap:8px; background:var(--bg-1); padding:12px; border-radius:12px; border:1px solid var(--border); cursor:pointer;">
                                            <input type="checkbox" name="is_published" id="ea_is_published" value="1"
                                                style="width:20px; height:20px; accent-color:var(--accent-bright);">
                                            <span style="font-size:14px; color:var(--text-1); font-weight:500;">Công khai
                                                (Xuất bản)</span>
                                        </label>
                                    </div>
                                </div>
                                <div style="display:flex; gap:12px; margin-top:24px;">
                                    <button type="button" class="btn btn-outline" onclick="closeModal('modal-edit-article')">Hủy
                                        bỏ</button>
                                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        function editArticle(art) {
                            document.getElementById('ea_id').value = art.id;
                            document.getElementById('ea_title').value = art.title;
                            document.getElementById('ea_slug').value = art.slug;
                            document.getElementById('ea_excerpt').value = art.excerpt;
                            document.getElementById('ea_content').value = art.content;
                            document.getElementById('ea_is_published').checked = art.is_published == 1;
                            showModal('modal-edit-article');
                        }
                    </script>
                <?php endif; ?>

                <?php if ($tab == 'feedbacks'): ?>
                    <div class="admin-panel">
                        <div class="admin-header">
                            <h2 class="heading" style="margin:0;">📸 Quản Lý Ảnh Khách Hàng Đánh Giá</h2>
                        </div>
                        <div class="divider"></div>
                        <form method="POST" enctype="multipart/form-data"
                            style="margin-bottom: 24px; padding: 16px; background: var(--bg-1); border-radius: 12px; border: 1px solid var(--border);">
                            <input type="hidden" name="action" value="admin_upload_feedback">
                            <div class="field" style="margin-bottom: 12px;">
                                <label>Tải lên ảnh mới (Tỉ lệ màn hình điện thoại 9:16)</label>
                                <input type="file" name="feedback_image" accept="image/*" class="input" style="padding: 12px;"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Tải Lên</button>
                        </form>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 16px;">
                            <?php
                            $fb_stmt = $pdo->query("SELECT * FROM feedbacks ORDER BY id DESC");
                            foreach ($fb_stmt->fetchAll() as $fb):
                                ?>
                                <div
                                    style="position: relative; border-radius: 12px; overflow: hidden; border: 1px solid var(--border); aspect-ratio: 9/16;">
                                    <img src="<?= htmlspecialchars($fb['image_url']) ?>"
                                        style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                    <form method="POST" onsubmit="return confirm('Xóa ảnh này?');"
                                        style="position: absolute; top: 8px; right: 8px; margin: 0;">
                                        <input type="hidden" name="action" value="admin_delete_feedback">
                                        <input type="hidden" name="feedback_id" value="<?= $fb['id'] ?>">
                                        <button type="submit"
                                            style="background: rgba(239, 68, 68, 0.9); color: white; border: none; border-radius: 50%; width: 28px; height: 28px; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($tab == 'branding'): ?>
                    <div class="admin-panel">
                        <div class="admin-header">
                            <h2 class="heading" style="margin:0;">🎨 Thương Hiệu & SEO Trang Web</h2>
                        </div>
                        <div class="divider"></div>

                        <?php
                        $cur_logo = $settings['logo_path'] ?? '/logo.png';
                        $cur_fav = $settings['favicon_path'] ?? $cur_logo;
                        $cur_banner = $settings['banner_path'] ?? '/banner.png';
                        $cur_name = $settings['site_name'] ?? '';
                        $cur_desc = $settings['meta_desc'] ?? '';
                        $cur_kw = $settings['meta_keywords'] ?? '';
                        $seo_redirect_enabled = $settings['seo_redirect_enabled'] ?? '1';
                        $seo_redirect_keyword = $settings['seo_redirect_keyword'] ?? 'Locket Quốc Vũ';
                        ?>

                        <form method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:28px;">
                            <input type="hidden" name="action" value="admin_save_branding">

                            <!-- ── Tên & SEO ── -->
                            <div style="background:var(--bg-2);border:1px solid var(--border);border-radius:16px;padding:24px;">
                                <h3
                                    style="font-size:15px;font-weight:700;color:var(--accent-bright);margin:0 0 18px;display:flex;align-items:center;gap:8px;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                    Tên & Mô tả SEO
                                </h3>
                                <div style="display:flex;flex-direction:column;gap:14px;">
                                    <div class="field">
                                        <label>Tên Thương Hiệu / Tên Web <small style="color:var(--text-2);">(Hiển thị trên
                                                logo, tab, OG)</small></label>
                                        <input type="text" name="site_name" class="input" value="<?= htmlspecialchars($cur_name) ?>"
                                            placeholder="VD: Locket Gold VN">
                                    </div>
                                    <div class="field">
                                        <label>Meta Description <small style="color:var(--text-2);">(Mô tả hiển thị trên
                                                Google — tối ưu 120-160 ký tự)</small></label>
                                        <textarea name="meta_desc" class="input" style="height:80px;padding:12px;"
                                            placeholder="Mô tả ngắn về dịch vụ của bạn..."><?= htmlspecialchars($cur_desc) ?></textarea>
                                        <small style="color:var(--text-2);font-size:11px;">Hiện tại: <span
                                                id="desc-count"><?= mb_strlen($cur_desc) ?></span> ký tự</small>
                                    </div>
                                    <div class="field">
                                        <label>Meta Keywords <small style="color:var(--text-2);">(Phân cách bằng dấu
                                                phẩy)</small></label>
                                        <input type="text" name="meta_keywords" class="input"
                                            value="<?= htmlspecialchars($cur_kw) ?>"
                                            placeholder="locket gold, locket vip, locketgold...">
                                    </div>
                                    <hr style="border:0; border-top:1px solid var(--border); margin: 10px 0;">
                                    <div class="field">
                                        <label>SEO Traffic Redirect <small style="color:var(--text-2);">(Bắt buộc tìm kiếm Google trước khi dùng module)</small></label>
                                        <select name="seo_redirect_enabled" class="sel" style="padding:10px; border-radius:8px;">
                                            <option value="1" <?= $seo_redirect_enabled === '1' ? 'selected' : '' ?>>Bật (Yêu cầu tìm kiếm)</option>
                                            <option value="0" <?= $seo_redirect_enabled === '0' ? 'selected' : '' ?>>Tắt (Không yêu cầu)</option>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label>Từ khóa tìm kiếm <small style="color:var(--text-2);">(Hiển thị trong hộp thoại yêu cầu)</small></label>
                                        <input type="text" name="seo_redirect_keyword" class="input"
                                            value="<?= htmlspecialchars($seo_redirect_keyword) ?>"
                                            placeholder="VD: Locket Quốc Vũ">
                                    </div>
                                </div>
                            </div>

                            <!-- ── Logo ── -->
                            <div style="background:var(--bg-2);border:1px solid var(--border);border-radius:16px;padding:24px;">
                                <h3
                                    style="font-size:15px;font-weight:700;color:var(--accent-bright);margin:0 0 18px;display:flex;align-items:center;gap:8px;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" />
                                    </svg>
                                    Logo Website
                                </h3>
                                <div style="display:grid;grid-template-columns:auto 1fr;gap:20px;align-items:center;">
                                    <div
                                        style="width:80px;height:80px;border-radius:16px;overflow:hidden;border:2px solid var(--border);background:var(--bg-1);display:flex;align-items:center;justify-content:center;">
                                        <img src="<?= htmlspecialchars($cur_logo) ?>?t=<?= time() ?>" alt="Logo hiện tại"
                                            style="width:100%;height:100%;object-fit:contain;" onerror="this.style.display='none'">
                                    </div>
                                    <div>
                                        <div style="font-size:12px;color:var(--text-2);margin-bottom:8px;">Ảnh hiện tại:
                                            <code style="color:var(--accent-bright);"><?= htmlspecialchars($cur_logo) ?></code>
                                        </div>
                                        <div class="field" style="margin:0;">
                                            <label style="font-size:13px;">Thay logo mới <small style="color:var(--text-2);">(PNG,
                                                    JPG, SVG, WebP — nên dùng
                                                    512×512px)</small></label>
                                            <input type="file" name="logo_file" accept="image/*" class="input"
                                                style="padding:10px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ── Favicon ── -->
                            <div style="background:var(--bg-2);border:1px solid var(--border);border-radius:16px;padding:24px;">
                                <h3
                                    style="font-size:15px;font-weight:700;color:var(--accent-bright);margin:0 0 18px;display:flex;align-items:center;gap:8px;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                    </svg>
                                    Favicon (Icon Tab Trình Duyệt)
                                </h3>
                                <div style="display:grid;grid-template-columns:auto 1fr;gap:20px;align-items:center;">
                                    <div
                                        style="width:48px;height:48px;border-radius:8px;overflow:hidden;border:2px solid var(--border);background:var(--bg-1);display:flex;align-items:center;justify-content:center;">
                                        <img src="<?= htmlspecialchars($cur_fav) ?>?t=<?= time() ?>" alt="Favicon"
                                            style="width:100%;height:100%;object-fit:contain;" onerror="this.style.display='none'">
                                    </div>
                                    <div>
                                        <div style="font-size:12px;color:var(--text-2);margin-bottom:8px;">Ảnh hiện tại:
                                            <code style="color:var(--accent-bright);"><?= htmlspecialchars($cur_fav) ?></code>
                                        </div>
                                        <div class="field" style="margin:0;">
                                            <label style="font-size:13px;">Thay favicon mới <small
                                                    style="color:var(--text-2);">(PNG, ICO — tốt nhất 32×32px hoặc
                                                    64×64px)</small></label>
                                            <input type="file" name="favicon_file" accept="image/*,.ico" class="input"
                                                style="padding:10px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ── Banner / OG Image ── -->
                            <div style="background:var(--bg-2);border:1px solid var(--border);border-radius:16px;padding:24px;">
                                <h3
                                    style="font-size:15px;font-weight:700;color:var(--accent-bright);margin:0 0 18px;display:flex;align-items:center;gap:8px;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <rect x="2" y="3" width="20" height="14" rx="2" />
                                        <line x1="8" y1="21" x2="16" y2="21" />
                                        <line x1="12" y1="17" x2="12" y2="21" />
                                    </svg>
                                    Banner / Ảnh OG (Chia sẻ Zalo, Facebook)
                                </h3>
                                <div
                                    style="margin-bottom:14px;border-radius:12px;overflow:hidden;border:2px solid var(--border);max-height:200px;">
                                    <img src="<?= htmlspecialchars($cur_banner) ?>?t=<?= time() ?>" alt="Banner hiện tại"
                                        style="width:100%;height:200px;object-fit:cover;"
                                        onerror="this.src='data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'200\'><rect width=\'400\' height=\'200\' fill=\'%23374151\'/><text x=\'200\' y=\'100\' text-anchor=\'middle\' fill=\'%23fff\' font-size=\'16\'>Chưa có banner</text></svg>'">
                                </div>
                                <div style="font-size:12px;color:var(--text-2);margin-bottom:10px;">Ảnh hiện tại: <code
                                        style="color:var(--accent-bright);"><?= htmlspecialchars($cur_banner) ?></code> —
                                    <?= file_exists(ltrim($cur_banner, '/')) ? round(filesize(ltrim($cur_banner, '/')) / 1024) . ' KB' : 'Chưa có' ?>
                                </div>
                                <div class="field" style="margin:0;">
                                    <label style="font-size:13px;">Thay banner mới <small style="color:var(--text-2);">(PNG,
                                            JPG, WebP — tốt nhất 1200×630px để OG chuẩn)</small></label>
                                    <input type="file" name="banner_file" accept="image/*" class="input" style="padding:10px;">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary" style="width:100%;padding:16px;font-size:16px;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    style="margin-right:8px;">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                    <polyline points="17 21 17 13 7 13 7 21" />
                                    <polyline points="7 3 7 8 15 8" />
                                </svg>
                                Lưu Tất Cả Cài Đặt Thương Hiệu & SEO
                            </button>
                        </form>

                        <script>
                            // Đếm ký tự meta desc
                            document.querySelector('[name=meta_desc]').addEventListener('input', function () {
                                document.getElementById('desc-count').textContent = this.value.length;
                                document.getElementById('desc-count').style.color = this.value.length > 160 ? '#ef4444' : 'var(--accent-bright)';
                            });
                        </script>
                    </div>
                <?php endif; ?>

                <?php if ($tab == 'contacts'): ?>
                    <div class="admin-panel">
                        <div class="admin-header">
                            <h2 class="heading" style="margin:0;">Liên hệ (<span
                                    style="color:var(--accent-bright)"><?= $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn() ?></span>)
                            </h2>
                            <button class="btn btn-primary btn-sm" onclick="showModal('modal-add-contact')">+ Thêm liên
                                hệ</button>
                        </div>
                        <div class="divider"></div>
                        <div style="overflow-x:auto;">
                            <table class="tbl">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Loại</th>
                                        <th>Tên hiển thị</th>
                                        <th>Liên kết (URL)</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $contactsList = $pdo->query("SELECT * FROM contacts ORDER BY order_index ASC, id ASC")->fetchAll();
                                    foreach ($contactsList as $index => $c): ?>
                                        <tr>
                                            <td data-label="STT" style="color:var(--text-2); font-weight:bold;">#<?= $index + 1 ?></td>
                                            <td data-label="Loại"><span class="tag tag-gray"
                                                    style="text-transform:uppercase;"><?= htmlspecialchars($c['type'] ?? 'other') ?></span>
                                            </td>
                                            <td data-label="Tên hiển thị" style="font-weight:700; color:var(--text-0);">
                                                <?= htmlspecialchars($c['platform_name']) ?>
                                            </td>
                                            <td data-label="Liên kết (URL)"
                                                style="font-family:monospace; font-size:12px; color:var(--accent-bright);"><a
                                                    href="<?= htmlspecialchars($c['link_url']) ?>" target="_blank"
                                                    style="color:inherit; text-decoration:none;"><?= htmlspecialchars($c['link_url']) ?></a>
                                            </td>
                                            <td class="td-actions" data-label="Thao tác" style="white-space: nowrap;">
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="action" value="admin_move_contact">
                                                    <input type="hidden" name="direction" value="up">
                                                    <input type="hidden" name="c_id" value="<?= $c['id'] ?>">
                                                    <button type="submit" class="action-btn" title="Lên trên" <?= $index === 0 ? 'disabled style="opacity:0.3;"' : '' ?>><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"></polyline></svg></button>
                                                </form>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="action" value="admin_move_contact">
                                                    <input type="hidden" name="direction" value="down">
                                                    <input type="hidden" name="c_id" value="<?= $c['id'] ?>">
                                                    <button type="submit" class="action-btn" title="Xuống dưới" <?= $index === count($contactsList) - 1 ? 'disabled style="opacity:0.3;"' : '' ?>><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
                                                </form>
                                                <button type="button" class="action-btn edit" title="Sửa liên hệ" onclick="openEditContactModal(<?= $c['id'] ?>, '<?= htmlspecialchars(addslashes($c['platform_name'])) ?>', '<?= htmlspecialchars(addslashes($c['link_url'])) ?>', '<?= htmlspecialchars($c['type'] ?? 'other') ?>')">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </button>
                                                <form method="POST" style="display:inline;"
                                                    onsubmit="return confirm('Chắc chắn xóa liên hệ này?');">
                                                    <input type="hidden" name="action" value="admin_del_contact">
                                                    <input type="hidden" name="c_id" value="<?= $c['id'] ?>">
                                                    <button type="submit" class="action-btn del"><svg width="18" height="18"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polyline points="3 6 5 6 21 6" />
                                                            <path
                                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                                            <line x1="10" y1="11" x2="10" y2="17" />
                                                            <line x1="14" y1="11" x2="14" y2="17" />
                                                        </svg></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal Sửa Liên Hệ -->
                    <div id="modal-edit-contact" class="admin-modal">
                        <div class="modal-content" style="max-width:500px;">
                            <div class="modal-header">
                                <h3>Sửa thông tin Liên hệ</h3>
                                <button class="btn-close" onclick="closeModal('modal-edit-contact')"><svg width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST">
                                    <input type="hidden" name="action" value="admin_edit_contact">
                                    <input type="hidden" name="c_id" id="edit_c_id" value="">
                                    <div class="form-grid">
                                        <div class="field">
                                            <label>Tên hiển thị</label>
                                            <input type="text" name="c_name" id="edit_c_name" class="input" required>
                                        </div>
                                        <div class="field">
                                            <label>Đường link (URL)</label>
                                            <input type="url" name="c_url" id="edit_c_url" class="input" required>
                                        </div>
                                        <div class="field">
                                            <label>Biểu tượng (Icon)</label>
                                            <select name="c_type" id="edit_c_type" class="input" style="cursor:pointer;">
                                                <option value="zalo">Zalo</option>
                                                <option value="facebook">Facebook</option>
                                                <option value="telegram">Telegram</option>
                                                <option value="youtube">YouTube</option>
                                                <option value="instagram">Instagram</option>
                                                <option value="tiktok">TikTok</option>
                                                <option value="threads">Threads</option>
                                                <option value="other">Khác</option>
                                            </select>
                                        </div>
                                        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                                            <button type="button" class="btn btn-outline btn-sm"
                                                onclick="closeModal('modal-edit-contact')">Hủy</button>
                                            <button type="submit" class="btn btn-primary btn-sm">Lưu thay đổi</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <script>
                        function openEditContactModal(id, name, url, type) {
                            document.getElementById('edit_c_id').value = id;
                            document.getElementById('edit_c_name').value = name;
                            document.getElementById('edit_c_url').value = url;
                            document.getElementById('edit_c_type').value = type;
                            showModal('modal-edit-contact');
                        }
                    </script>

                    <!-- Modal Thêm Liên Hệ -->
                    <div id="modal-add-contact" class="admin-modal">
                        <div class="modal-box">
                            <h3 class="heading-sm mb-md">Thêm Liên Hệ Mới</h3>
                            <form method="POST">
                                <input type="hidden" name="action" value="admin_add_contact">
                                <div class="field">
                                    <label>Tên hiển thị (Vd: Nhóm Zalo VIP)</label>
                                    <input type="text" name="c_name" class="input" required>
                                </div>
                                <div class="field">
                                    <label>Đường link (URL)</label>
                                    <input type="url" name="c_url" class="input" required>
                                </div>
                                <div class="field">
                                    <label>Biểu tượng (Icon)</label>
                                    <select name="c_type" class="input" style="cursor:pointer;">
                                        <option value="zalo">Zalo</option>
                                        <option value="facebook">Facebook</option>
                                        <option value="telegram">Telegram</option>
                                        <option value="youtube">YouTube</option>
                                        <option value="instagram">Instagram</option>
                                        <option value="tiktok">TikTok</option>
                                        <option value="threads">Threads</option>
                                        <option value="other">Khác</option>
                                    </select>
                                </div>
                                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                                    <button type="button" class="btn btn-outline btn-sm"
                                        onclick="closeModal('modal-add-contact')">Hủy</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Lưu liên hệ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($tab == 'users'): ?>
                    <div class="admin-panel">
                        <div class="admin-header">
                            <h2 class="heading" style="margin:0;">Tài khoản (<span
                                    style="color:var(--accent-bright)"><?= $cnt_users ?></span>)</h2>
                            <button class="btn btn-primary btn-sm" onclick="showModal('modal-add-user')">+ Thêm mới</button>
                        </div>
                        <div class="divider"></div>
                        <div style="overflow-x:auto;">
                            <table class="tbl">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tài khoản</th>
                                        <th>SĐT Zalo</th>
                                        <th>IP Đăng ký</th>
                                        <th>Thời gian ĐK</th>
                                        <th>Phân quyền</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $user_index = $cnt_users; ?>
                                    <?php foreach ($pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll() as $u): ?>
                                        <tr>
                                            <td data-label="STT" style="color:var(--text-2)">#<?= $user_index-- ?></td>
                                            <td data-label="Tài khoản" style="font-weight:700;">
                                                <?= htmlspecialchars($u['username']) ?>
                                            </td>
                                            <td data-label="SĐT Zalo" style="color:var(--green); font-weight:600;">
                                                <?= htmlspecialchars($u['phone'] ?? 'N/A') ?>
                                            </td>
                                            <td data-label="IP Đăng ký" title="<?= htmlspecialchars($u['register_ip'] ?? '—') ?>"
                                                style="color:var(--text-2); font-size:12px; font-family:monospace; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                <?= htmlspecialchars($u['register_ip'] ?? '—') ?>
                                            </td>
                                            <td data-label="Thời gian ĐK"
                                                style="color:var(--text-2); font-size:13px; white-space: nowrap;">
                                                <?= htmlspecialchars($u['created_at'] ?? '—') ?>
                                            </td>
                                            <td data-label="Phân quyền">
                                                <?php $r = $u['role'];
                                                $tc = $r == 'admin' ? 'tag-red' : ($r == 'agency' ? 'tag-orange' : (strpos($r, 'vip') !== false ? 'tag-purple' : 'tag-gray'));
                                                echo '<span class="tag ' . $tc . '">' . getRoleLabel($r) . '</span>'; ?>
                                            </td>
                                            <td class="td-actions" data-label="Thao tác" style="white-space:nowrap;">
                                                <?php if ($u['username'] !== $current_user): ?>
                                                    <button class="action-btn" style="color:#10b981; background:rgba(16,185,129,0.1);"
                                                        title="Nạp tiền / Cấp VIP"
                                                        onclick="addFund('<?= htmlspecialchars($u['username']) ?>')"><svg
                                                            viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                                            stroke-width="2">
                                                            <line x1="12" y1="5" x2="12" y2="19" />
                                                            <line x1="5" y1="12" x2="19" y2="12" />
                                                        </svg></button>
                                                    <button class="action-btn" title="Chỉnh sửa thông tin"
                                                        onclick="editUser(<?= $u['id'] ?>, '<?= htmlspecialchars($u['username']) ?>', '<?= $u['role'] ?>', '<?= htmlspecialchars($u['proxy_info'] ?? '', ENT_QUOTES, 'UTF-8') ?>')"><svg
                                                            width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                        </svg></button>
                                                    <form method="POST" style="display:inline;"
                                                        onsubmit="return confirm('Chắc chắn xóa tài khoản này?');">
                                                        <input type="hidden" name="action" value="admin_del_user"><input type="hidden"
                                                            name="user_id" value="<?= $u['id'] ?>">
                                                        <button type="submit" class="action-btn del"><svg width="18" height="18"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <polyline points="3 6 5 6 21 6" />
                                                                <path
                                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                                                <line x1="10" y1="11" x2="10" y2="17" />
                                                                <line x1="14" y1="11" x2="14" y2="17" />
                                                            </svg></button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal Add User -->
                    <div id="modal-add-user" class="admin-modal">
                        <div class="modal-box">
                            <h3 class="heading-sm mb-md">Thêm tài khoản</h3>
                            <form method="POST">
                                <input type="hidden" name="action" value="admin_add_user">
                                <div class="field"><label>Tên đăng nhập</label><input type="text" name="username" class="input"
                                        required></div>
                                <div class="field"><label>Mật khẩu</label><input type="text" name="password" class="input" required>
                                </div>
                                <div class="field"><label>Phân quyền Locket</label>
                                    <select name="role" class="sel"
                                        style="width:100%; padding:14px; font-size:15px; border-radius:var(--radius-sm); margin-bottom:12px;">
                                        <option value="user">Thành viên (0 ID)</option>
                                        <option value="vip1">VIP 1 (1 ID)</option>
                                        <option value="vip2">VIP 2 (2 ID)</option>
                                        <option value="vip3">VIP 3 (3 ID)</option>
                                        <option value="vip4">VIP 4 (10 ID)</option>
                                        <option value="agency">Đại lý</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="field"><label>Phân quyền ShadowRocket</label>
                                    <select name="sr_role" class="sel"
                                        style="width:100%; padding:14px; font-size:15px; border-radius:var(--radius-sm);">
                                        <option value="">Không có</option>
                                        <option value="sr_vip">ShadowRocket: VIP 15s</option>
                                        <option value="sr_premium">ShadowRocket: Premium</option>
                                        <option value="sr_ultimate">ShadowRocket: Ultimate</option>
                                    </select>
                                </div>
                                <div style="display:flex; gap:12px; margin-top:24px;">
                                    <button type="button" class="btn btn-outline"
                                        onclick="closeModal('modal-add-user')">Hủy</button>
                                    <button type="submit" class="btn btn-primary">Tạo tài khoản</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Edit User -->
                    <div id="modal-edit-user" class="admin-modal">
                        <div class="modal-box">
                            <h3 class="heading-sm mb-md">Sửa tài khoản <span id="e_uname" style="color:var(--accent-bright)"></span>
                            </h3>
                            <form method="POST">
                                <input type="hidden" name="action" value="admin_edit_user">
                                <input type="hidden" name="user_id" id="e_id">
                                <div class="field"><label>Đổi mật khẩu (Để trống nếu ko đổi)</label><input type="text"
                                        name="password" class="input" placeholder="Mật khẩu mới..."></div>
                                <div class="field"><label>Thông tin Proxy (Cho SR VIP/Ultimate)</label><input type="text"
                                        name="proxy_info" id="e_proxy" class="input" placeholder="Nhập proxy..."></div>
                                <div class="field"><label>Phân quyền Locket</label>
                                    <select name="role" id="e_role" class="sel"
                                        style="width:100%; padding:14px; font-size:15px; border-radius:var(--radius-sm); margin-bottom:12px;">
                                        <option value="user">Thành viên (0 ID)</option>
                                        <option value="vip1">VIP 1 (1 ID)</option>
                                        <option value="vip2">VIP 2 (2 ID)</option>
                                        <option value="vip3">VIP 3 (3 ID)</option>
                                        <option value="vip4">VIP 4 (10 ID)</option>
                                        <option value="agency">Đại lý</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="field"><label>Phân quyền ShadowRocket</label>
                                    <select name="sr_role" id="e_sr_role" class="sel"
                                        style="width:100%; padding:14px; font-size:15px; border-radius:var(--radius-sm);">
                                        <option value="">Không có</option>
                                        <option value="sr_vip">ShadowRocket: VIP 15s</option>
                                        <option value="sr_premium">ShadowRocket: Premium</option>
                                        <option value="sr_ultimate">ShadowRocket: Ultimate</option>
                                    </select>
                                </div>
                                <div style="display:flex; gap:12px; margin-top:24px;">
                                    <button type="button" class="btn btn-outline"
                                        onclick="closeModal('modal-edit-user')">Hủy</button>
                                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <script>
                        function editUser(id, un, role, proxy = '') { 
                            document.getElementById('e_id').value = id; 
                            document.getElementById('e_uname').innerText = un; 
                            document.getElementById('e_proxy').value = proxy; 
                            
                            let roles = role.split(',').map(r => r.trim());
                            let baseRole = 'user';
                            let srRole = '';
                            roles.forEach(r => {
                                if (r.startsWith('sr_')) srRole = r;
                                else if (r) baseRole = r;
                            });
                            
                            document.getElementById('e_role').value = baseRole; 
                            document.getElementById('e_sr_role').value = srRole; 
                            showModal('modal-edit-user'); 
                        }
                        function addFund(un) { document.getElementById('f_uname_input').value = un; document.getElementById('f_uname_display').innerText = un; showModal('modal-add-fund'); }
                    </script>

                    <!-- Modal Add Fund -->
                    <div id="modal-add-fund" class="admin-modal">
                        <div class="modal-box">
                            <h3 class="heading-sm mb-md">Nạp tiền / Cấp VIP cho <span id="f_uname_display"
                                    style="color:var(--green)"></span></h3>
                            <p class="desc mb-md" style="font-size:13px;">Hành động này sẽ tạo một bản ghi hóa đơn "hoàn
                                thành" trong hệ thống và trực tiếp nâng cấp tài khoản.</p>
                            <form method="POST">
                                <input type="hidden" name="action" value="admin_add_fund">
                                <input type="hidden" name="username" id="f_uname_input">

                                <div class="field">
                                    <label>Gói muốn cấp</label>
                                    <select name="role" class="sel"
                                        style="width:100%; padding:14px; font-size:15px; border-radius:var(--radius-sm);">
                                        <option value="vip1">VIP 1 (1 ID)</option>
                                        <option value="vip2">VIP 2 (2 ID)</option>
                                        <option value="vip3">VIP 3 (3 ID)</option>
                                        <option value="vip4">VIP 4 (10 ID)</option>
                                        <option value="agency">Đại lý</option>
                                        <option value="user">Hạ cấp về User (0 ID)</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label>Số tiền / Ghi chú (Chỉ lưu vết)</label>
                                    <input type="text" name="note" class="input" placeholder="VD: Nạp 59k qua Momo..."
                                        value="Admin cấp trực tiếp">
                                </div>
                                <div style="display:flex; gap:12px; margin-top:24px;">
                                    <button type="button" class="btn btn-outline"
                                        onclick="closeModal('modal-add-fund')">Hủy</button>
                                    <button type="submit" class="btn btn-primary"
                                        style="background:#10b981; border-color:#10b981;">Xác nhận</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($tab == 'activations'): ?>
                    <div class="admin-panel">
                        <h2 class="heading mb-sm">Lịch sử kích hoạt (<span
                                style="color:var(--accent-bright)"><?= $cnt_acts ?></span>)</h2>
                        <p class="desc mb-md">Quản lý toàn bộ UID đã đồng bộ thành công hoặc lỗi.</p>
                        <div class="divider"></div>
                        <?php
                        $rows = [];
                        $can_manage_activation_logs = in_array($current_role, ['admin', 'agency'], true);
                        try {
                            if ($current_role === 'agency') {
                                $stmt = $pdo->prepare("SELECT * FROM activations WHERE injected_by = ? ORDER BY created_at DESC LIMIT 200");
                                $stmt->execute([$current_user]);
                            } else {
                                $stmt = $pdo->query("SELECT * FROM activations ORDER BY created_at DESC LIMIT 200");
                            }
                            $rows = $stmt->fetchAll();
                        } catch (Exception $e) {
                            $rows = [];
                        }
                        ?>
                        <div style="overflow-x:auto;">
                            <table class="tbl">
                                <thead>
                                    <tr>
                                        <th>Locket ID</th>
                                        <th>Trạng thái</th>
                                        <th>Người thực hiện</th>
                                        <th>Thời gian</th>
                                        <?php if ($can_manage_activation_logs): ?>
                                            <th>Thao tác</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($rows)): ?>
                                        <tr>
                                            <td colspan="<?= $can_manage_activation_logs ? '5' : '4' ?>"
                                                style="text-align:center; padding:40px; color:var(--text-2);">Chưa có
                                                lịch sử kích hoạt.</td>
                                        </tr>
                                    <?php else:
                                        foreach ($rows as $r): ?>
                                            <tr>
                                                <td data-label="Locket ID"
                                                    style="font-family:monospace; font-size:13px; color:var(--accent-bright); word-break:break-all;">
                                                    <?= htmlspecialchars($r['uid']) ?>
                                                </td>
                                                <td data-label="Trạng thái">
                                                    <?php
                                                    $is_failed = (($r['job_status'] ?? '') === 'failed' || strtolower($r['status'] ?? '') === 'failed');
                                                    $st = strtolower($r['status']);
                                                    $ok = !$is_failed && (strpos($st, 'live') !== false || strpos($st, 'demo') !== false || strpos($st, 'success') !== false);
                                                    $disp_status = $is_failed ? 'FAILED (ERROR)' : $r['status'];
                                                    echo '<span class="tag ' . ($ok ? 'tag-green' : 'tag-red') . '">' . htmlspecialchars($disp_status) . '</span>';
                                                    if ($is_failed && !empty($r['error_log'])) {
                                                        echo '<div style="font-size:11px; color:#ef4444; margin-top:4px; line-height: 1.3;">Lỗi: ' . htmlspecialchars($r['error_log']) . '</div>';
                                                    }
                                                    ?>
                                                </td>
                                                <td data-label="Người thực hiện" style="font-weight:600; font-size:13px;">
                                                    <?= htmlspecialchars($r['injected_by'] ?? '—') ?>
                                                </td>
                                                <td data-label="Thời gian"
                                                    style="color:var(--text-2); font-size:13px; font-family:monospace;">
                                                    <?= $r['created_at'] ?>
                                                </td>
                                                <?php if ($can_manage_activation_logs): ?>
                                                    <td class="td-actions" data-label="Thao tác">
                                                        <form method="POST" style="display:inline;"
                                                            onsubmit="return confirm('Chắc chắn xóa log ID này?');">
                                                            <input type="hidden" name="action"
                                                                value="<?= $current_role === 'admin' ? 'admin_del_act' : 'agency_del_act' ?>">
                                                            <input type="hidden" name="act_id" value="<?= $r['id'] ?>">
                                                            <button type="submit" class="action-btn del" title="Xóa bản ghi kích hoạt">
                                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2">
                                                                    <polyline points="3 6 5 6 21 6" />
                                                                    <path
                                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                                                    <line x1="10" y1="11" x2="10" y2="17" />
                                                                    <line x1="14" y1="11" x2="14" y2="17" />
                                                                </svg></button>
                                                        </form>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
        <?php endif; ?>
        </section>
        </div>
        <script>
            function showModal(id) { document.getElementById(id).classList.add('show'); }
            function closeModal(id) { document.getElementById(id).classList.remove('show'); }
        </script>
    <?php } ?>
<?php endif; ?>