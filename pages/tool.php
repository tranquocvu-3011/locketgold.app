<!-- ═══════ KÍCH HOẠT ═══════ -->
        <?php if ($page === 'tool'): ?>
            <?php if (!$current_user) {
                echo "<script>window.location='/dang-nhap';</script>";
                exit;
            } ?>
            <?php
            $reactivate_uids = [];
            $used_quota_count = 0;
            try {
                $reactivate_stmt = $pdo->prepare("SELECT uid, MAX(created_at) AS last_used_at FROM activations WHERE injected_by = ? GROUP BY uid ORDER BY last_used_at DESC LIMIT 100");
                $reactivate_stmt->execute([$current_user]);
                $reactivate_uids = $reactivate_stmt->fetchAll(PDO::FETCH_COLUMN, 0) ?: [];

                $used_q_stmt = $pdo->prepare("SELECT COUNT(DISTINCT uid) FROM activations WHERE injected_by = ? AND (status LIKE '%Live%' OR status LIKE '%Demo%')");
                $used_q_stmt->execute([$current_user]);
                $used_quota_count = (int) $used_q_stmt->fetchColumn();
            } catch (Exception $e) {
                $reactivate_uids = [];
            }

            $num_limit = 1;
            if ($current_role === 'agency' || $current_role === 'admin') {
                $num_limit = 999999;
            } elseif (strpos($current_role, 'vip') !== false) {
                if ($current_role === 'vip4') {
                    $num_limit = 10;
                } else {
                    $n = intval(str_replace('vip', '', $current_role));
                    $num_limit = $n > 0 ? $n : 1;
                }
            }
            $is_quota_full = ($used_quota_count >= $num_limit);

            $is_trial_expired = ($current_role === 'user');
            $trial_expires_at_ts = 0;
            $trial_time_left_seconds = 0;
            ?>

            <?php if ($inject_status === 'success'): ?>
                <div class="card"
                    style="text-align:center; border-color:rgba(52,211,153,0.3); box-shadow:0 0 40px rgba(52,211,153,0.08); max-width:680px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"
                        style="width:56px;height:56px;margin:0 auto 20px;">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="8 12 11 15 16 9" />
                    </svg>
                    <h2 class="page-title light mb-sm">Kích hoạt thành công!</h2>
                    <p class="desc mb-md">Tài khoản Locket đã được đồng bộ trạng thái Gold Premium.</p>

                    <div style="background:rgba(239,68,68,0.08); border:1px dashed rgba(239,68,68,0.3); border-radius:10px; padding:16px; margin-bottom:20px; text-align:left; font-size:14px; color:var(--text-1); line-height:1.6;">
                        <strong style="color:var(--red); font-size:15px; display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            Thao tác bắt buộc sau khi chạy xong
                        </strong>
                        <ul style="margin:0; padding-left:20px; color:var(--text-2);">
                            <li style="margin-bottom:6px;">Bạn phải vuốt <strong>đóng hoàn toàn ứng dụng Locket ở màn hình đa nhiệm</strong>, sau đó mới mở lại app.</li>
                            <li style="margin-bottom:6px;">Nếu app đã lên Gold thành công, bạn hãy tiến hành cài DNS bảo vệ ở dưới.</li>
                            <li>Nếu app vẫn chưa lên Gold, vui lòng vào trang Hướng Dẫn và làm chuẩn theo mục <strong>"2. Khắc phục lỗi mất Gold"</strong>.</li>
                        </ul>
                    </div>
                    <?php if ($is_vip_or_higher): ?>
                        <div class="alert alert-info" style="text-align:left;">
                            <strong>Bước tiếp theo:</strong> Cài đặt DNS Profile chống thu hồi để duy trì trạng thái Premium suốt 1
                            năm.
                        </div>
                        <a href="/download-dns" class="btn btn-primary mb-sm">Tải DNS Profile bảo vệ</a>
                    <?php else: ?>
                        <div class="alert alert-success"
                            style="text-align:left; background:rgba(52,211,153,0.1); border-color:rgba(52,211,153,0.3); color:var(--text-1);">
                            <strong>Hoạt động hoàn tất:</strong> Quá trình kích hoạt đã xử lý xong. Hãy mở ứng dụng Locket của bạn
                            để kiểm tra trạng thái Gold Premium.
                        </div>
                    <?php endif; ?>
                    <a href="/cong-cu" class="btn btn-outline">Kích hoạt ID khác</a>
                </div>

            <?php else: ?>
                <!-- HEADER -->
                <div id="tool-header" class="page-shell mb-lg" style="text-align:center; animation:cardIn 0.5s ease forwards;">
                    <h1 class="page-title" style="font-size:36px; margin-bottom:12px;">Kích hoạt Locket Gold</h1>
                    <p class="hero-sub" style="margin-bottom:0;">Chọn phương thức kích hoạt phù hợp với nhu cầu của bạn.</p>
                </div>


                <!-- 2-COLUMN LAYOUT -->
                <div id="tool-grid"
                    style="display:grid; grid-template-columns:1fr 1fr; gap:24px; width:100%; max-width:var(--layout-max); animation:cardIn 0.6s 0.1s ease both;">

                    <!-- ══ CỘT TRÁI ══ -->
                    <div class="card"
                        style="position:relative; overflow:hidden; border-color:<?= ($is_trial_expired) ? 'rgba(239,68,68,0.4)' : 'rgba(52,211,153,0.25)' ?>;<?= ($is_trial_expired) ? ' background:linear-gradient(135deg,rgba(239,68,68,0.05),rgba(239,68,68,0.02));' : '' ?>"
                        id="inject-ui">
                        <!-- Badge -->
                        <div
                            style="position:absolute; top:16px; right:16px; background:<?= $is_trial_expired ? 'linear-gradient(135deg,#ef4444,#dc2626)' : ($is_vip_or_higher ? 'linear-gradient(135deg, var(--accent-dim), var(--accent))' : 'linear-gradient(135deg, var(--green), #10B981)') ?>; color:<?= $is_trial_expired ? '#fff' : ($is_vip_or_higher ? '#fff' : '#000') ?>; padding:5px 14px; border-radius:20px; font-size:11px; font-weight:800; letter-spacing:0.5px;">
                            <?= $is_trial_expired ? 'HẾT HẠN' : ($is_vip_or_higher ? strtoupper(getRoleLabel($current_role)) : 'MIỄN PHÍ') ?>
                        </div>

                        <!-- Icon -->
                        <div
                            style="display:flex; align-items:center; justify-content:center; width:52px; height:52px; border-radius:14px; background:rgba(52,211,153,0.1); margin-bottom:20px;">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                            </svg>
                        </div>

                        <?php if ($is_trial_expired): ?>
                            <h2 style="font-size:22px; font-weight:800; margin-bottom:6px; color:#ef4444;">⛔ Tính năng chưa mở khoá
                            </h2>
                            <p class="desc" style="margin-bottom:20px; font-size:14px; color:var(--text-2);">Vui lòng nâng cấp tài
                                khoản lên thành viên VIP để mở khoá tính năng Kích hoạt Locket.</p>
                            <div
                                style="padding:20px; border-radius:14px; border:1px dashed rgba(239,68,68,0.4); background:rgba(239,68,68,0.06); text-align:center; margin-bottom:20px;">
                                <div style="font-size:40px; margin-bottom:10px;">🔒</div>
                                <div style="font-weight:800; font-size:15px; color:var(--text-0); margin-bottom:6px;">Tính năng đã
                                    bị khoá</div>
                                <div style="font-size:13px; color:var(--text-2); line-height:1.5;">Vui lòng nâng cấp lên <b>VIP</b>
                                    để tiếp tục kích hoạt Locket Gold không giới hạn thời gian.</div>
                            </div>
                            <a href="/dich-vu-vip" class="btn btn-primary"
                                style="width:100%; text-align:center; text-decoration:none; display:block; background:linear-gradient(135deg,#ef4444,#f97316); border:none; font-size:15px; padding:14px;">
                                🚀 Nâng cấp VIP ngay
                            </a>
                        <?php else: ?>
                            <h2 style="font-size:22px; font-weight:800; margin-bottom:6px; color:var(--text-0);">Kích hoạt nhanh
                            </h2>
                            <p class="desc" style="margin-bottom:20px; font-size:14px;">Nhập Username hoặc Link Locket. Hệ thống tự
                                động đồng bộ Gold Premium ngay lập tức.</p>
                        <?php endif; ?>

                        <?php if ($inject_status === 'error'): ?>
                            <div class="alert alert-error" style="margin-bottom:16px;"><?= $inject_msg ?></div>
                        <?php endif; ?>

                        <!-- Stat bar -->
                        <div class="stat-bar" style="margin-bottom:20px;">
                            <div>
                                <div class="stat-label">Cấp bậc</div>
                                <div class="stat-value"><?= getRoleLabel($current_role) ?></div>
                            </div>
                            <div style="text-align:right;">
                                <div class="stat-label">Hạn ngạch</div>
                                <div class="stat-value"><?= getRoleQuota($current_role) ?></div>
                            </div>
                        </div>

                        <!-- Form -->
                        <form action="/cong-cu" method="POST" id="formTool">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="inject">
                            <input type="hidden" name="injectMode" value="normal">
                            <input type="hidden" name="inputType" value="id">
                            <div class="field">
                                <label
                                    style="font-size:13px; font-weight:600; margin-bottom:6px; display:block; color:var(--text-1);">Locket
                                    ID hoặc Link</label>
                                <?php if ($is_trial_expired): ?>
                                    <input type="text" disabled class="input input-mono state-lock-input"
                                        value="CHƯA NÂNG CẤP DỊCH VỤ">
                                    <p class="state-lock-note">Tài khoản của bạn chưa được nâng cấp. Vui lòng chọn một <b>Gói
                                            VIP</b> ở cột bên phải để tiếp tục sử dụng dịch vụ.</p>
                                <?php elseif ($is_quota_full): ?>
                                    <input type="text" disabled class="input input-mono state-lock-input"
                                        value="ĐÃ FULL HẠN NGẠCH - KHOÁ THÊM MỚI">
                                    <p class="state-lock-note">Hạn ngạch nâng cấp
                                        của bạn đã đạt giới hạn (<b><?= $used_quota_count ?>/<?= $num_limit ?></b> ID). Để ngăn chặn
                                        hành vi thay đổi Username/ID sang cho người khác mượn dùng, tính năng nhập mới đã bị khoá.
                                        Vui lòng sử dụng tính năng <b>Kích Hoạt Lại</b> đối với các ID đã đăng ký bên dưới.</p>
                                <?php else: ?>
                                    <input type="text" name="inputValue" id="iVal" class="input input-mono"
                                        placeholder="username hoặc https://locket.cam/username" required autocomplete="off"
                                        style="font-size:14px;">
                                <?php endif; ?>
                            </div>
                            <button type="button"
                                class="btn btn-primary <?= ($is_quota_full || $is_trial_expired) ? 'btn-disabled-theme' : '' ?>"
                                onclick="runSync('iVal', 'formTool')" <?= ($is_quota_full || $is_trial_expired) ? 'disabled style="margin-top:16px; width:100%;"' : 'style="margin-top:16px; width:100%; background:linear-gradient(135deg, var(--green), #10B981); border:none;"' ?>>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                    style="margin-right:8px; vertical-align:middle;">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                                </svg>
                                <?= $is_trial_expired ? 'Nâng cấp VIP ngay' : ($is_quota_full ? 'Đã full hạn ngạch (Khoá)' : ($is_vip_or_higher ? 'Kích hoạt ngay' : 'Kích hoạt miễn phí')) ?>
                            </button>
                        </form>

                        <!-- Tính năng miễn phí -->
                        <div style="margin-top:24px; padding-top:20px; border-top:1px solid var(--border);">
                            <div
                                style="font-size:12px; font-weight:700; color:var(--text-2); text-transform:uppercase; letter-spacing:1px; margin-bottom:12px;">
                                Bao gồm</div>
                            <div style="display:flex; flex-direction:column; gap:10px;">
                                <div style="display:flex; align-items:center; gap:10px; font-size:13.5px; color:var(--text-1);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                        stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                    Tự động kích hoạt Gold Premium
                                </div>
                                <div style="display:flex; align-items:center; gap:10px; font-size:13.5px; color:var(--text-1);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                        stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                    Không cần mật khẩu tài khoản
                                </div>
                                <div style="display:flex; align-items:center; gap:10px; font-size:13.5px; color:var(--text-1);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                        stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                    Xử lý trong 3–5 giây
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ══ CỘT PHẢI: TRẢ PHÍ ══ -->
                    <div class="card" style="position:relative; overflow:hidden; border-color:var(--border-accent);">
                        <!-- Badge -->
                        <div
                            style="position:absolute; top:16px; right:16px; background:linear-gradient(135deg, var(--accent-dim), var(--accent)); color:#fff; padding:5px 14px; border-radius:20px; font-size:11px; font-weight:800; letter-spacing:0.5px;">
                            PREMIUM
                        </div>

                        <!-- Glow effect -->
                        <div
                            style="position:absolute; top:-40px; right:-40px; width:120px; height:120px; background:radial-gradient(circle, rgba(167,139,250,0.15) 0%, transparent 70%); pointer-events:none;">
                        </div>

                        <!-- Icon -->
                        <div
                            style="display:flex; align-items:center; justify-content:center; width:52px; height:52px; border-radius:14px; background:rgba(167,139,250,0.1); margin-bottom:20px;">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--accent-bright)"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                        </div>

                        <h2 style="font-size:22px; font-weight:800; margin-bottom:6px; color:var(--text-0);">Dịch vụ VIP</h2>
                        <p class="desc" style="margin-bottom:24px; font-size:14px;">Nâng cấp tài khoản để mở khóa toàn bộ tính
                            năng cao cấp và bảo vệ vĩnh viễn.</p>

                        <!-- Các gói VIP -->
                        <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:24px;">
                            <?php
                            $packages = [
                                'vip1' => ['label' => 'VIP 1', 'desc' => '1 ID', 'is_hot' => false],
                                'vip2' => ['label' => 'VIP 2', 'desc' => '2 ID', 'is_hot' => true],
                                'vip3' => ['label' => 'VIP 3', 'desc' => '3 ID', 'is_hot' => false],
                                'vip4' => ['label' => 'VIP 4', 'desc' => '10 ID', 'is_hot' => false],
                                'agency' => ['label' => 'Đại Lý', 'desc' => 'Không giới hạn ID', 'is_hot' => false, 'is_pro' => true]
                            ];
                            foreach ($packages as $p_role => $p):
                                $is_current = ($current_role === $p_role);
                                $is_pro = $p['is_pro'] ?? false;

                                // Setup colors
                                $border_color = $is_current ? 'var(--accent-bright)' : ($p['is_hot'] || $is_pro ? 'var(--border-accent)' : 'var(--border)');
                                $bg_color = $is_current ? 'rgba(167,139,250,0.08)' : ($is_pro ? 'linear-gradient(135deg, rgba(167,139,250,0.08), rgba(52,211,153,0.05))' : 'var(--bg-2)');
                                $shadow = ($p['is_hot'] || $is_current) ? 'box-shadow:0 0 20px rgba(167,139,250,0.08);' : '';

                                $price_key = 'price_' . $p_role;
                                $price_old_key = 'price_' . $p_role . '_old';
                                $price = $settings[$price_key] ?? 0;
                                $price_old = $settings[$price_old_key] ?? 0;
                                ?>
                                <div <?= !$is_current ? 'onclick="window.location.href=\'/dich-vu-vip\'"' : '' ?>
                                    style="display:flex; align-items:center; justify-content:space-between; padding:14px 16px; background:<?= $bg_color ?>; border:1px solid <?= $border_color ?>; border-radius:12px; <?= $shadow ?> <?= !$is_current ? 'cursor:pointer; transition: all 0.2s;' : '' ?>">
                                    <div>
                                        <div style="font-weight:700; font-size:14px; color:var(--text-0);">
                                            <?= $p['label'] ?>
                                            <?php if ($p['is_hot']): ?>
                                                <span
                                                    style="font-size:10px; background:var(--accent-dim); color:#fff; padding:2px 8px; border-radius:10px; margin-left:6px; font-weight:600;">HOT</span>
                                            <?php endif; ?>
                                            <?php if ($is_pro): ?>
                                                <span
                                                    style="font-size:10px; background:var(--orange); color:#000; padding:2px 8px; border-radius:10px; margin-left:6px; font-weight:600;">PRO</span>
                                            <?php endif; ?>
                                        </div>
                                        <div style="font-size:12px; color:var(--text-2); margin-top:2px;">
                                            <?= $p['desc'] ?>
                                            <?php if ($is_current): ?>
                                                <span style="font-size:11px; color:#10b981; font-weight:700; margin-left:6px;">• Gói
                                                    hiện tại</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div style="text-align:right;">
                                        <?php if ($is_current): ?>
                                            <div
                                                style="font-weight:800; color:#10b981; font-size:14px; padding:4px 10px; background:rgba(16,185,129,0.1); border-radius:8px; display:inline-block;">
                                                Đang dùng</div>
                                        <?php else: ?>
                                            <div
                                                style="font-weight:800; color:<?= $is_pro ? 'var(--orange)' : 'var(--accent-bright)' ?>; font-size:16px;">
                                                <?= number_format($price / 1000, 0, ',', '.') ?>k
                                            </div>
                                            <div style="font-size:11px; color:var(--text-2); text-decoration:line-through;">
                                                <?= number_format($price_old / 1000, 0, ',', '.') ?>k
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Tính năng Premium -->
                        <div style="padding-top:20px; border-top:1px solid var(--border); margin-bottom:20px;">
                            <div
                                style="font-size:12px; font-weight:700; color:var(--text-2); text-transform:uppercase; letter-spacing:1px; margin-bottom:12px;">
                                Đặc quyền VIP</div>
                            <div style="display:flex; flex-direction:column; gap:10px;">
                                <div style="display:flex; align-items:center; gap:10px; font-size:13.5px; color:var(--text-1);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent-bright)"
                                        stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                    Tất cả tính năng Miễn phí
                                </div>
                                <div style="display:flex; align-items:center; gap:10px; font-size:13.5px; color:var(--text-1);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent-bright)"
                                        stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                    Chống thu hồi vĩnh viễn
                                </div>
                                <div style="display:flex; align-items:center; gap:10px; font-size:13.5px; color:var(--text-1);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent-bright)"
                                        stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                    Hỗ trợ kỹ thuật ưu tiên 24/7
                                </div>
                                <div style="display:flex; align-items:center; gap:10px; font-size:13.5px; color:var(--text-1);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent-bright)"
                                        stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                    Kích hoạt nhiều ID cùng lúc
                                </div>
                            </div>
                        </div>

                        <!-- CTA -->
                        <a href="/lien-he" class="btn btn-primary"
                            style="width:100%; text-align:center; text-decoration:none; display:block;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px; vertical-align:middle;">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                            </svg>
                            Liên hệ nâng cấp VIP
                        </a>
                    </div>
                </div>

                <div id="reactivate-box" class="card"
                    style="max-width:var(--layout-max); margin-top:20px; border:1px solid rgba(251,191,36,0.34); background:linear-gradient(135deg, rgba(251,191,36,0.08), rgba(167,139,250,0.05));">
                    <div style="display:flex; align-items:flex-start; gap:16px; flex-wrap:wrap;">
                        <div
                            style="width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:rgba(251,191,36,0.14); color:var(--orange); flex-shrink:0;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                <path d="M9 10h.01"></path>
                                <path d="M12 10h.01"></path>
                                <path d="M15 10h.01"></path>
                            </svg>
                        </div>
                        <div style="flex:1; min-width:260px;">
                            <h3 style="font-size:22px; font-weight:800; margin-bottom:8px;">Kích hoạt lại tài khoản bị lỗi Gold
                            </h3>
                            <p class="desc" style="margin-bottom:12px; line-height:1.6; font-size:14px;">
                                Dùng mục này khi tài khoản bị mất Gold sau khi thay đổi cài đặt hoặc đăng xuất ứng dụng.
                            </p>

                            <?php if ($current_role === 'user'): ?>
                                <div
                                    style="margin-top:14px; padding:16px; border-radius:12px; background:rgba(248,113,113,0.08); border:1px dashed rgba(248,113,113,0.3); text-align:center;">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"
                                        style="margin-bottom:8px;">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    <h4 style="color:var(--text-0); margin-bottom:6px; font-size:15px;">Tính năng khóa đối với Thành
                                        viên</h4>
                                    <p style="color:var(--text-2); font-size:13px; line-height:1.5; margin:0;">
                                        Tài khoản khởi tạo ngẫu nhiên không được hỗ trợ bảo hành khôi phục. Vui lòng nâng cấp
                                        VIP/Đại lý để sử dụng tính năng này không giới hạn.
                                    </p>
                                </div>
                            <?php else: ?>
                                <ol style="margin:0 0 14px 18px; color:var(--text-1); font-size:13.5px; line-height:1.6;">
                                    <li>Đổi DNS về <strong>"Tự động"</strong> trong Cài đặt iPhone.</li>
                                    <li>Chọn lại ID trong danh sách bên dưới và bấm kích hoạt lại.</li>
                                    <li>Vào ứng dụng Locket, ấn nút <strong>Khôi phục đơn hàng (Restore Purchases)</strong>.</li>
                                    <li>Chỉ khi lên Gold thành công mới đổi DNS sang <strong>"Locket Gold Premium"</strong>.</li>
                                </ol>

                                <form action="/cong-cu" method="POST" id="formReactivate">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="inject">
                                    <input type="hidden" name="injectMode" value="reactivate">
                                    <input type="hidden" name="inputType" value="id">
                                    <div class="field" style="margin-bottom:0;">
                                        <label
                                            style="font-size:13px; font-weight:600; margin-bottom:6px; display:block; color:var(--text-1);">Link
                                            hoặc Username Locket cần kích hoạt lại</label>
                                        <select name="inputValue" id="reVal" class="input input-mono" required>
                                            <option value="">Chọn ID</option>
                                            <?php foreach ($reactivate_uids as $uid): ?>
                                                <option value="<?= htmlspecialchars($uid) ?>"><?= htmlspecialchars($uid) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <?php if (empty($reactivate_uids)): ?>
                                        <div
                                            style="margin-top:10px; font-size:12.5px; color:#fca5a5; background:rgba(248,113,113,0.08); border:1px solid rgba(248,113,113,0.3); border-radius:8px; padding:10px 12px;">
                                            Tài khoản này chưa có lịch sử ID đã kích hoạt, nên chưa thể dùng mục kích hoạt lại.
                                        </div>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-primary" onclick="runSync('reVal', 'formReactivate')"
                                        <?= empty($reactivate_uids) ? 'disabled' : '' ?>
                                        style="margin-top:14px; width:100%; background:linear-gradient(135deg, #f59e0b, #f97316); border:none;">
                                        Kích hoạt lại Gold bị lỗi
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ══ SHADOW ROCKET MODULE ══ -->
                <div class="card" id="shadowrocket-box"
                    style="max-width:var(--layout-max); margin-top:20px; border:1px solid rgba(59,130,246,0.35); background:linear-gradient(135deg, rgba(59,130,246,0.07), rgba(167,139,250,0.05));">
                    <div style="display:flex; align-items:flex-start; gap:16px; flex-wrap:wrap;">
                        <div
                            style="width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:rgba(59,130,246,0.14); color:#3b82f6; flex-shrink:0;">
                            <!-- rocket icon -->
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2">
                                <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2l-.96-.96a2 2 0 0 0-2.08-.08z" />
                                <path
                                    d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z" />
                                <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0" />
                                <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5" />
                            </svg>
                        </div>
                        <div style="flex:1; min-width:260px;">
                            <h3 style="font-size:20px; font-weight:800; margin-bottom:6px; color:var(--text-0);">
                                Kích hoạt bằng Shadow Rocket
                            </h3>
                            <p class="desc" style="margin-bottom:14px; line-height:1.6; font-size:13.5px;">
                                Dùng Shadow Rocket trên iPhone để kết nối DNS tùy chỉnh. Sao chép link module bên dưới rồi dán
                                vào ứng dụng Shadow Rocket để cài đặt.
                            </p>

                            <!-- Link module + nút sao chép -->
                            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                                <div
                                    style="flex:1; min-width:200px; display:flex; align-items:center; gap:0; background:var(--bg-0); border:1.5px solid rgba(59,130,246,0.3); border-radius:12px; overflow:hidden;">
                                    <span
                                        style="padding:12px 14px; font-size:12px; color:var(--text-2); flex-shrink:0;">🔗</span>
                                    <input id="shadowrocket-link" type="text" readonly
                                        value="https://raw.githubusercontent.com/tranquocvu-3011/QuocVu_Scripts/main/QuocVu_Premium.module"
                                        style="flex:1; border:none; background:transparent; font-size:12px; font-family:monospace; color:var(--text-1); padding:12px 8px 12px 0; outline:none; min-width:0; cursor:text;"
                                        onclick="this.select();">
                                </div>
                                <button id="copy-sr-btn" type="button" onclick="copyShadowRocketLink()"
                                    style="padding:12px 20px; border-radius:12px; border:none; background:linear-gradient(135deg,#3b82f6,#6366f1); color:#fff; font-weight:700; font-size:13px; cursor:pointer; display:flex; align-items:center; gap:8px; white-space:nowrap; transition:all 0.2s;"
                                    onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(59,130,246,0.4)';"
                                    onmouseout="this.style.transform=''; this.style.boxShadow='';">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                    </svg>
                                    Sao chép
                                </button>
                            </div>

                            <!-- Hướng dẫn nhanh -->
                            <div
                                style="margin-top:14px; padding:12px 14px; background:rgba(59,130,246,0.07); border-radius:10px; border:1px dashed rgba(59,130,246,0.25);">
                                <p style="font-size:12.5px; color:var(--text-2); margin:0; line-height:1.7;">
                                    <strong style="color:var(--text-1);">Hướng dẫn nhanh:</strong>
                                    Mở Shadow Rocket → Config → Module → Dán link trên → Tải về &amp; Bật module.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PROCESSING UI -->
                <div class="card proc" id="proc-ui" style="max-width:680px;">
                    <div class="spinner"></div>
                    <h2 class="page-title light mb-md">Đang xử lý...</h2>
                    <div id="s0" class="proc-step">Kiểm tra tính hợp lệ của định dạng...</div>
                    <div id="s1" class="proc-step">Kết nối máy chủ RevenueCat...</div>
                    <div id="s2" class="proc-step">Gửi yêu cầu xác thực Receipt...</div>
                    <div id="s3" class="proc-step">Đồng bộ trạng thái Premium...</div>
                    <div id="s4" class="proc-step" style="color:var(--green); font-weight:700;">Hoàn tất! Đang chuyển hướng...
                    </div>
                </div>

                <!-- Responsive: mobile 1 cột -->
                <style>
                    @media (max-width: 768px) {
                        #tool-grid {
                            grid-template-columns: 1fr !important;
                        }
                    }
                </style>

                <script>
                    function runSync(inputId = 'iVal', formId = 'formTool') {
                        var inputEl = document.getElementById(inputId);
                        var inputVal = inputEl ? inputEl.value.trim() : '';
                        if (!inputVal) {
                            if (typeof window.showToast === 'function') {
                                window.showToast('Vui lòng nhập Link hoặc ID tài khoản Locket của bạn.', 'warning');
                            } else {
                                alert('Vui lòng nhập Link hoặc ID tài khoản Locket của bạn.');
                            }
                            return;
                        }
                        var grid = document.getElementById('tool-grid');
                        var header = document.getElementById('tool-header');
                        var reactivate = document.getElementById('reactivate-box');
                        var shadowrocket = document.getElementById('shadowrocket-box');
                        if (grid) grid.style.display = 'none';
                        if (header) header.style.display = 'none';
                        if (reactivate) reactivate.style.display = 'none';
                        if (shadowrocket) shadowrocket.style.display = 'none';
                        document.getElementById('proc-ui').classList.add('show');
                        const steps = ['s0', 's1', 's2', 's3', 's4'];
                        steps.forEach((id, i) => setTimeout(() => document.getElementById(id).classList.add('show'), 600 + i * 1000));
                        setTimeout(() => {
                            var targetForm = document.getElementById(formId);
                            if (targetForm) targetForm.submit();
                        }, 5600);
                    }

                    function copyShadowRocketLink() {
                        var input = document.getElementById('shadowrocket-link');
                        var btn = document.getElementById('copy-sr-btn');
                        if (!input) return;
                        navigator.clipboard.writeText(input.value).then(function () {
                            btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Đã sao chép!';
                            btn.style.background = 'linear-gradient(135deg,#10b981,#059669)';
                            setTimeout(function () {
                                btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg> Sao chép';
                                btn.style.background = 'linear-gradient(135deg,#3b82f6,#6366f1)';
                            }, 2000);
                        }).catch(function () {
                            input.select();
                            document.execCommand('copy');
                            if (typeof window.showToast === 'function') {
                                window.showToast('Đã sao chép link module!', 'success');
                            }
                        });
                    }
                </script>
            <?php endif; ?>
        <?php endif; ?>