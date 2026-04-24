<!-- ═══════ LIÊN HỆ ═══════ -->
        <?php if ($page === 'contact'): ?>
            <?php
            $contacts = [];
            try {
                $contacts = $pdo->query("SELECT * FROM contacts ORDER BY id ASC")->fetchAll();
            } catch (Exception $e) {
            }
            $contact_count = count($contacts);
            $primary_contact = $contacts[0] ?? null;
            foreach ($contacts as $item) {
                if (($item['type'] ?? '') === 'zalo') {
                    $primary_contact = $item;
                    break;
                }
            }
            ?>
            <style>
                .contact-grid {
                    display: grid;
                    grid-template-columns: 1.35fr 1fr;
                    gap: 24px;
                    width: 100%;
                    max-width: 100%;
                    margin: 0 auto;
                }

                .contact-hero-inner {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 40px;
                }

                .contact-hero-main {
                    flex: 1;
                    min-width: 0;
                }

                .contact-hero-actions {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 12px;
                }

                .contact-card {
                    background: var(--bg-1);
                    border: 1px solid var(--border);
                    border-radius: var(--radius-xl);
                    padding: 36px;
                    transition: var(--transition-smooth);
                }

                .contact-hero {
                    position: relative;
                    overflow: hidden;
                    background: radial-gradient(circle at top left, rgba(167, 139, 250, 0.1), transparent 40%),
                        radial-gradient(circle at bottom right, rgba(52, 211, 153, 0.05), transparent 40%),
                        linear-gradient(145deg, var(--bg-1), var(--bg-0));
                    border: 1px solid var(--border-accent);
                    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.15);
                }

                .contact-item {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 16px;
                    padding: 20px 24px;
                    border-radius: var(--radius-lg);
                    text-decoration: none;
                    background: rgba(255, 255, 255, 0.02);
                    border: 1px solid rgba(255, 255, 255, 0.05);
                    transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
                }

                .contact-item-main {
                    display: flex;
                    align-items: center;
                    gap: 18px;
                    min-width: 0;
                    flex: 1;
                }

                .contact-item-content {
                    min-width: 0;
                }

                .contact-item-title-row {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    margin-bottom: 6px;
                    flex-wrap: wrap;
                }

                .contact-item-url {
                    font-size: 14px;
                    color: var(--text-2);
                    overflow-wrap: anywhere;
                }

                .action-wrap {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    flex-shrink: 0;
                }

                .contact-side-stack {
                    display: flex;
                    flex-direction: column;
                    gap: 24px;
                }

                .contact-item:hover {
                    transform: translateY(-4px);
                    background: rgba(255, 255, 255, 0.05);
                    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
                }

                .contact-item.primary {
                    background: rgba(0, 104, 255, 0.06);
                    border-color: rgba(0, 104, 255, 0.2);
                }

                .contact-item.primary:hover {
                    background: rgba(0, 104, 255, 0.1);
                    border-color: rgba(0, 104, 255, 0.3);
                    box-shadow: 0 16px 32px rgba(0, 104, 255, 0.15);
                }

                .icon-box {
                    width: 58px;
                    height: 58px;
                    border-radius: 16px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: #fff;
                    flex-shrink: 0;
                    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
                }

                .badge-sm {
                    display: inline-flex;
                    align-items: center;
                    padding: 4px 10px;
                    border-radius: 8px;
                    font-size: 11px;
                    font-weight: 800;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    background: rgba(255, 255, 255, 0.06);
                    color: var(--text-1);
                }

                @media (max-width: 900px) {
                    .contact-grid {
                        grid-template-columns: 1fr;
                    }

                    .contact-hero-inner {
                        flex-direction: column;
                        align-items: flex-start;
                        gap: 22px;
                    }

                    .contact-hero-actions {
                        width: 100%;
                    }

                    .contact-hero-actions .btn {
                        flex: 1 1 220px;
                        justify-content: center;
                    }

                    .contact-hero-side {
                        display: none;
                    }
                }

                @media (max-width: 600px) {
                    .contact-grid {
                        gap: 16px;
                    }

                    .contact-item {
                        flex-direction: column;
                        align-items: flex-start;
                        padding: 16px;
                    }

                    .contact-item-main {
                        width: 100%;
                    }

                    .contact-item-url {
                        font-size: 13px;
                    }

                    .contact-item>.action-wrap {
                        width: 100%;
                        display: flex;
                        flex-direction: row;
                        justify-content: space-between;
                        align-items: center;
                        border-top: 1px solid rgba(255, 255, 255, 0.05);
                        padding-top: 16px;
                        margin-top: 8px;
                    }

                    .contact-card {
                        padding: 20px;
                    }

                    .contact-hero-actions .btn {
                        width: 100%;
                        flex: 1 1 100%;
                    }
                }
            </style>

            <div class="page-shell" style="animation: cardIn 0.6s ease forwards;">

                <!-- TOP HEADER SECTION -->
                <div class="contact-card contact-hero" style="margin-bottom:24px;">
                    <div class="contact-hero-inner">
                        <div class="contact-hero-main">
                            <div class="chip mb-sm" style="font-size:12px; padding:6px 14px; letter-spacing:0.5px;">
                                Trung
                                Tâm Trợ Giúp</div>
                            <h1
                                style="font-size: clamp(32px, 4vw, 44px); margin-bottom: 16px; line-height: 1.2; letter-spacing: -1px; font-weight: 800;">
                                Xử lý sự cố <span
                                    style="background:linear-gradient(135deg, var(--accent), var(--green)); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">siêu
                                    tốc</span>.
                            </h1>
                            <p class="desc"
                                style="font-size: 16px; max-width: 540px; margin-bottom: 24px; line-height: 1.6;">
                                Mọi hỗ trợ Kích hoạt, Báo lỗi, hay Nâng cấp VIP đều hoạt động 24/7.
                                Chọn đúng kênh liên lạc là cách để vấn đề của bạn được tiếp nhận nhanh nhất.
                            </p>
                            <div class="contact-hero-actions">
                                <?php if ($primary_contact): ?>
                                    <a href="<?= htmlspecialchars($primary_contact['link_url']) ?>" target="_blank"
                                        rel="noopener noreferrer" class="btn btn-primary"
                                        style="padding: 14px 24px; border-radius: 999px;">
                                        Nhắn tin cho Admin
                                    </a>
                                <?php endif; ?>
                                <a href="/huong-dan" class="btn btn-outline"
                                    style="padding: 14px 24px; border-radius: 999px;">
                                    Đọc trước Hướng dẫn
                                </a>
                            </div>
                        </div>

                        <div class="contact-hero-side" style="width:320px; flex-shrink:0;">
                            <div
                                style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; padding: 24px;">
                                <div style="display:flex; align-items:center; gap:10px; margin-bottom: 20px;">
                                    <div
                                        style="width:12px; height:12px; border-radius:50%; background:var(--green); box-shadow: 0 0 10px var(--green);">
                                    </div>
                                    <strong style="color:var(--text-0); font-size:15px;">Trạng Thái Hệ Thống</strong>
                                </div>
                                <div style="display:flex; flex-direction:column; gap:16px;">
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <span style="color:var(--text-2); font-size:14px;">Kênh hỗ trợ:</span>
                                        <b style="color:var(--text-0);"><?= $contact_count ?> kênh</b>
                                    </div>
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <span style="color:var(--text-2); font-size:14px;">Thời gian phản hồi:</span>
                                        <b style="color:var(--green);">~ 5 phút</b>
                                    </div>
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <span style="color:var(--text-2); font-size:14px;">Phiên hoạt động:</span>
                                        <b
                                            style="color:var(--accent-bright);"><?= $current_user ? getRoleLabel($current_role) : 'Tài khoản Khách' ?></b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MAIN GRID -->
                <div class="contact-grid">
                    <!-- LEFT COLUMN: Contact Channels -->
                    <div class="contact-card" style="padding-top:28px;">
                        <h2 style="font-size: 22px; margin-bottom: 8px;">Gặp lỗi? Hãy nhắn ngay</h2>
                        <p class="desc" style="font-size: 14.5px; margin-bottom:24px;">Ưu tiên chọn các kênh Zalo đối
                            với
                            vấn đề yêu cầu trực tiếp.</p>

                        <div style="display:flex; flex-direction:column; gap:14px;">
                            <?php if ($contact_count === 0): ?>
                                <div
                                    style="padding: 40px; text-align: center; border: 1px dashed var(--border); border-radius: var(--radius-lg); font-size:14px; color:var(--text-2);">
                                    Hệ thống chưa thiết lập kênh liên hệ.
                                </div>
                            <?php else: ?>
                                <?php foreach ($contacts as $c):
                                    $type = strtolower($c['type'] ?? 'other');
                                    $color = 'var(--text-0)';
                                    $badge = 'Hỗ trợ';
                                    $cta = 'Truy cập kênh';
                                    $is_primary = false;

                                    $icon_svg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--text-1)" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>';

                                    if ($type === 'zalo') {
                                        $color = '#0068ff';
                                        $badge = 'Ưu Tiên (Khuyên Dùng)';
                                        $cta = 'Nhắn Zalo';
                                        $is_primary = ($primary_contact && $primary_contact['id'] == $c['id']);
                                        $icon_svg = '<div style="font-weight:900; font-size:16px; padding:4px 8px; border-radius:8px; background:#0068ff; color:#fff;">Zalo</div>';
                                    } elseif ($type === 'facebook') {
                                        $color = '#1877F2';
                                        $badge = 'Facebook Hỗ Trợ';
                                        $cta = 'Mở Facebook';
                                        $icon_svg = '<svg width="28" height="28" viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>';
                                    } elseif ($type === 'telegram') {
                                        $color = '#229ED9';
                                        $badge = 'Cập Nhật Nhanh';
                                        $cta = 'Mở Telegram';
                                        $icon_svg = '<svg width="28" height="28" viewBox="0 0 24 24" fill="#229ED9"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>';
                                    }
                                    ?>
                                    <a href="<?= htmlspecialchars($c['link_url']) ?>" target="_blank" rel="noopener noreferrer"
                                        class="contact-item <?= $is_primary ? 'primary' : '' ?>">
                                        <div class="contact-item-main">
                                            <div class="icon-box" style="color:<?= $color ?>;">
                                                <?= $icon_svg ?>
                                            </div>
                                            <div class="contact-item-content">
                                                <div class="contact-item-title-row">
                                                    <strong
                                                        style="font-size: 17px; color: <?= $is_primary ? $color : 'var(--text-0)' ?>;">
                                                        <?= htmlspecialchars($c['platform_name']) ?>
                                                    </strong>
                                                    <span class="badge-sm"
                                                        style="<?= $is_primary ? 'background:rgba(0,104,255,0.12); color:#0068ff;' : '' ?>">
                                                        <?= $badge ?>
                                                    </span>
                                                </div>
                                                <div class="contact-item-url">
                                                    <?= htmlspecialchars($c['link_url']) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="action-wrap">
                                            <span
                                                style="font-size: 12px; font-weight:800; text-transform:uppercase; color:<?= $is_primary ? $color : 'var(--text-1)' ?>; margin-right:12px; white-space:nowrap;">
                                                <?= $cta ?>
                                            </span>
                                            <div
                                                style="width: 44px; height: 44px; border-radius: 50%; display:flex; align-items:center; justify-content:center; background: rgba(255,255,255,0.05); color:<?= $is_primary ? $color : 'var(--text-1)' ?>; flex-shrink:0;">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                                    <polyline points="12 5 19 12 12 19"></polyline>
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Guidelines & Info -->
                    <div class="contact-side-stack">
                        <div class="contact-card" style="padding: 28px;">
                            <div style="display:flex; align-items:center; gap:12px; margin-bottom: 18px;">
                                <div
                                    style="padding:8px; border-radius:10px; background:rgba(255,165,0,0.1); color:var(--orange);">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path
                                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                        </path>
                                        <line x1="12" y1="9" x2="12" y2="13"></line>
                                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                    </svg>
                                </div>
                                <h3 style="margin:0; font-size: 19px;">Mẹo được xử lý nhanh</h3>
                            </div>
                            <p class="desc" style="font-size: 14.5px; margin-bottom: 20px; line-height:1.6;">
                                Gửi sẵn các dữ liệu này ngay trong tin nhắn đầu tiên để đội ngũ bỏ qua bước xác minh.
                            </p>
                            <ul
                                style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:14px;">
                                <li
                                    style="display:flex; align-items:flex-start; gap:12px; font-size:14.5px; color:var(--text-1); line-height:1.5;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                        stroke-width="2" style="margin-top:2px; flex-shrink:0;">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    Cung cấp sẵn Link Locket hoặc Locket ID lúc nhờ Kích hoạt.
                                </li>
                                <li
                                    style="display:flex; align-items:flex-start; gap:12px; font-size:14.5px; color:var(--text-1); line-height:1.5;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                        stroke-width="2" style="margin-top:2px; flex-shrink:0;">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    Gửi email bạn đăng ký tại trang web này (nếu đang bị kẹt VIP).
                                </li>
                                <li
                                    style="display:flex; align-items:flex-start; gap:12px; font-size:14.5px; color:var(--text-1); line-height:1.5;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                        stroke-width="2" style="margin-top:2px; flex-shrink:0;">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    Chụp ảnh màn hình lỗi (cấu hình, kết nối, lỗi đăng nhập đỏ).
                                </li>
                            </ul>
                        </div>

                        <div class="contact-card"
                            style="padding: 28px; border: 1px solid rgba(167,139,250,0.3); background: rgba(167,139,250,0.03);">
                            <div style="display:flex; align-items:center; gap:12px; margin-bottom: 16px;">
                                <div
                                    style="padding:8px; border-radius:10px; background:rgba(167,139,250,0.1); color:var(--accent-bright);">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                    </svg>
                                </div>
                                <h3 style="margin:0; font-size: 19px; color:var(--accent-bright);">Cần Nâng Cấp VIP?
                                </h3>
                            </div>
                            <p class="desc" style="font-size: 14.5px; margin-bottom: 20px; line-height:1.6;">
                                Tiết kiệm thời gian tự hỏi đáp bằng cách tham khảo trước Bảng giá VIP và Quyền lợi ở mục
                                bên
                                dưới.
                            </p>
                            <a href="/dich-vu-vip"
                                style="display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; border-radius:12px; background:var(--accent-glow); color:#fff; text-decoration:none; font-weight:700; font-size:14.5px; box-shadow:0 8px 24px rgba(167,139,250,0.25);">
                                Xem Bảng Giá & Quyền Lợi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>