<!-- ═══════ TẠO WEB CON ═══════ -->
        <?php if (false && $page === 'agency-setup'): // ĐÃ ĐÓNG BĂNG ?>
            <div style="max-width:1200px; width:100%; margin: 60px auto 100px; padding: 0 20px;">
                <!-- Hero Section -->
                <div style="text-align:center; margin-bottom:50px; position: relative;">
                    <div
                        style="position:absolute; top:-50px; left:50%; transform:translateX(-50%); width:150px; height:150px; background:var(--accent); filter:blur(100px); opacity:0.3; z-index:-1;">
                    </div>
                    <div
                        style="display:inline-flex; align-items:center; justify-content:center; padding:8px 16px; border-radius:20px; background:rgba(167, 139, 250, 0.1); border:1px solid rgba(167, 139, 250, 0.2); color:var(--accent-bright); font-size:14px; font-weight:600; margin-bottom:16px;">
                        🚀 Locket Gold Partners
                    </div>
                    <h1
                        style="font-size: clamp(32px, 5vw, 48px); font-weight: 800; line-height: 1.2; margin-bottom: 20px; letter-spacing: -1px; background: linear-gradient(135deg, #fff, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        Sở Hữu Nền Tảng Bán Hàng<br>Thương Hiệu Riêng
                    </h1>
                    <p style="color:var(--text-2); font-size:16px; line-height:1.6; max-width:600px; margin:0 auto;">
                        Trở thành đối tác chính thức và sở hữu ngay một website giống 100% trang chủ, với thương hiệu, giá
                        bán và thông tin thanh toán của riêng bạn.
                    </p>
                </div>

                <?php if ($current_role === 'agency' || $current_role === 'admin'): ?>
                    <!-- Active Agency Panel -->
                    <div class="card"
                        style="position:relative; overflow:hidden; border:1px solid var(--border-accent); box-shadow:0 10px 40px rgba(167,139,250,0.15); padding: 40px; margin-bottom: 40px;">
                        <div
                            style="position:absolute; top:0; right:0; width:300px; height:300px; background:radial-gradient(circle, rgba(167,139,250,0.1) 0%, transparent 70%); z-index:0; pointer-events:none;">
                        </div>

                        <div
                            style="position:relative; z-index:1; display: flex; flex-wrap: wrap; gap: 40px; align-items: stretch;">
                            <!-- LEFT COLUMN: Thông tin Đại lý -->
                            <div style="flex: 1 1 45%; min-width: 350px; display: flex; flex-direction: column;">
                                <div
                                    style="display:flex; align-items:flex-start; gap:20px; margin-bottom: 30px; flex-wrap:wrap;">
                                    <div
                                        style="width:64px; height:64px; border-radius:20px; background:linear-gradient(135deg, var(--accent), #7c3aed); display:flex; align-items:center; justify-content:center; color:#fff; box-shadow:0 8px 20px rgba(124, 58, 237, 0.3); flex-shrink:0;">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                        </svg>
                                    </div>
                                    <div style="flex:1; min-width:250px;">
                                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                                            <h3 style="font-size:24px; font-weight:700; margin:0; color:var(--text-0);">Website
                                                Đã Kích Hoạt</h3>
                                            <span
                                                style="padding:4px 10px; border-radius:100px; background:rgba(16, 185, 129, 0.1); color:#10b981; font-size:12px; font-weight:600; border:1px solid rgba(16, 185, 129, 0.2);">Đang
                                                hoạt động</span>
                                        </div>
                                        <p style="color:var(--text-2); font-size:15px; margin:0; line-height:1.5;">Gửi liên kết
                                            dưới đây cho khách hàng hoặc trỏ tên miền riêng để họ truy cập bằng thương hiệu của
                                            bạn.</p>
                                    </div>
                                </div>

                                <div
                                    style="background:rgba(0,0,0,0.2); padding:20px; border-radius:16px; border:1px solid rgba(255,255,255,0.05); margin-bottom: 30px;">
                                    <div
                                        style="font-size:13px; color:var(--text-2); font-weight:600; text-transform:uppercase; letter-spacing:1px; margin-bottom:10px;">
                                        Link Tiếp Thị Liên Kết</div>
                                    <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                                        <code
                                            style="color:var(--accent-bright); font-size:16px; font-family:monospace; padding:12px 16px; background:rgba(167,139,250,0.05); border-radius:8px; border:1px dashed rgba(167,139,250,0.3); flex:1; min-width:180px; word-break:break-all;">
                                                                                                                                    <?= htmlspecialchars($site_domain) ?>/?ref=<?= htmlspecialchars($current_user) ?>
                                                                                                                                </code>
                                        <button
                                            onclick="navigator.clipboard.writeText('<?= htmlspecialchars($site_domain) ?>/?ref=<?= htmlspecialchars($current_user) ?>'); Swal.fire({toast:true, position:'top-end', icon:'success', title:'Đã copy link', showConfirmButton:false, timer:1500, background:'var(--bg-1)', color:'var(--text-0)'})"
                                            class="btn btn-primary" style="padding:12px 20px; white-space:nowrap; height:auto;">
                                            Sao chép
                                        </button>
                                    </div>
                                </div>

                                <div style="display:flex; gap:16px; flex-wrap:wrap;">
                                    <a href="<?= htmlspecialchars($site_domain) ?>/?ref=<?= htmlspecialchars($current_user) ?>"
                                        target="_blank" class="btn btn-primary"
                                        style="flex:1; justify-content:center; min-width:150px; padding:14px; font-size:15px;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            style="margin-right:8px;">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                        </svg>
                                        Xem Web
                                    </a>
                                    <a href="/admin?tab=payments" class="btn btn-outline"
                                        style="flex:1; justify-content:center; min-width:150px; padding:14px; font-size:15px; border-color:var(--border-accent); color:var(--text-0);">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            style="margin-right:8px;">
                                            <circle cx="12" cy="12" r="3"></circle>
                                            <path
                                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                            </path>
                                        </svg>
                                        Sửa Giá
                                    </a>
                                </div>

                                <!-- Form cài đặt thương hiệu & ngân hàng & giá -->
                                <?php
                                $ag_info = [];
                                try {
                                    $st = $pdo->prepare("SELECT * FROM agency_settings WHERE agency_username = ?");
                                    $st->execute([$current_user]);
                                    $ag_info = $st->fetch(PDO::FETCH_ASSOC) ?: [];
                                } catch (Exception $e) {
                                }
                                ?>
                                <div
                                    style="background:var(--bg-1);border:1px solid var(--border);border-radius:20px;padding:28px;margin-top:28px;">
                                    <h3
                                        style="font-size:17px;font-weight:700;color:var(--text-0);margin-bottom:20px;display:flex;align-items:center;gap:8px;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="var(--accent-bright)" stroke-width="2">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                        </svg>
                                        Cài Đặt Website Con
                                    </h3>
                                    <form method="POST" style="display:flex;flex-direction:column;gap:14px;">
                                        <input type="hidden" name="action" value="agency_save_settings">
                                        <div class="field">
                                            <label
                                                style="font-size:13px;color:var(--text-2);font-weight:600;margin-bottom:6px;display:block;">Tên
                                                Thương Hiệu (hiển thị trên web con)</label>
                                            <input type="text" name="site_name" class="input" placeholder="VD: Locket Shop ABC"
                                                value="<?= htmlspecialchars($ag_info['site_name'] ?? '') ?>">
                                        </div>
                                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                            <div class="field">
                                                <label
                                                    style="font-size:13px;color:var(--text-2);font-weight:600;margin-bottom:6px;display:block;">Ngân
                                                    Hàng (Mã NH)</label>
                                                <input type="text" name="bank_code" class="input" placeholder="VD: MB, VCB, TCB"
                                                    value="<?= htmlspecialchars($ag_info['bank_code'] ?? '') ?>">
                                            </div>
                                            <div class="field">
                                                <label
                                                    style="font-size:13px;color:var(--text-2);font-weight:600;margin-bottom:6px;display:block;">Số
                                                    Tài Khoản</label>
                                                <input type="text" name="bank_account" class="input"
                                                    placeholder="VD: 1234567890"
                                                    value="<?= htmlspecialchars($ag_info['bank_account'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label
                                                style="font-size:13px;color:var(--text-2);font-weight:600;margin-bottom:6px;display:block;">Chủ
                                                Tài Khoản</label>
                                            <input type="text" name="bank_owner" class="input" placeholder="VD: NGUYEN VAN A"
                                                value="<?= htmlspecialchars($ag_info['bank_owner'] ?? '') ?>">
                                        </div>
                                        <div
                                            style="background:rgba(167,139,250,0.05);border:1px dashed var(--border-accent);border-radius:12px;padding:16px;">
                                            <div
                                                style="font-size:12px;color:var(--accent-bright);font-weight:700;margin-bottom:12px;text-transform:uppercase;letter-spacing:0.5px;">
                                                Bảng Giá Bán Lẻ (để trống = dùng giá gốc)</div>
                                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                                                <div>
                                                    <label
                                                        style="font-size:12px;color:var(--text-2);margin-bottom:4px;display:block;">Giá
                                                        VIP 1 (đ)</label>
                                                    <input type="number" name="price_vip1" class="input"
                                                        style="padding:10px 12px;"
                                                        placeholder="<?= $settings['price_vip1'] ?? '59000' ?>"
                                                        value="<?= htmlspecialchars($ag_info['price_vip1'] ?? '') ?>">
                                                </div>
                                                <div>
                                                    <label
                                                        style="font-size:12px;color:var(--text-2);margin-bottom:4px;display:block;">Giá
                                                        VIP 2 (đ)</label>
                                                    <input type="number" name="price_vip2" class="input"
                                                        style="padding:10px 12px;"
                                                        placeholder="<?= $settings['price_vip2'] ?? '79000' ?>"
                                                        value="<?= htmlspecialchars($ag_info['price_vip2'] ?? '') ?>">
                                                </div>
                                                <div>
                                                    <label
                                                        style="font-size:12px;color:var(--text-2);margin-bottom:4px;display:block;">Giá
                                                        VIP 3 (đ)</label>
                                                    <input type="number" name="price_vip3" class="input"
                                                        style="padding:10px 12px;"
                                                        placeholder="<?= $settings['price_vip3'] ?? '99000' ?>"
                                                        value="<?= htmlspecialchars($ag_info['price_vip3'] ?? '') ?>">
                                                </div>
                                                <div>
                                                    <label
                                                        style="font-size:12px;color:var(--text-2);margin-bottom:4px;display:block;">Giá
                                                        VIP 4 (đ)</label>
                                                    <input type="number" name="price_vip4" class="input"
                                                        style="padding:10px 12px;"
                                                        placeholder="<?= $settings['price_vip4'] ?? '149000' ?>"
                                                        value="<?= htmlspecialchars($ag_info['price_vip4'] ?? '') ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary"
                                            style="width:100%;padding:14px;font-size:15px;margin-top:4px;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" style="margin-right:8px;">
                                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                                <polyline points="17 21 17 13 7 13 7 21" />
                                                <polyline points="7 3 7 8 15 8" />
                                            </svg>
                                            Lưu Cài Đặt Website Con
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- RIGHT COLUMN: Thiết lập tên miền -->
                            <div style="flex: 1 1 45%; min-width: 350px; display: flex; flex-direction: column;">
                                <div
                                    style="background:var(--bg-1); border-radius:24px; padding:30px; border:1px solid var(--border-accent); box-shadow:0 10px 30px rgba(0,0,0,0.1); height:100%;">
                                    <?php
                                    $agency_domain = '';
                                    try {
                                        $stmt = $pdo->prepare("SELECT domain_name FROM agency_settings WHERE agency_username = ?");
                                        $stmt->execute([$current_user]);
                                        $agency_domain = $stmt->fetchColumn() ?: '';
                                    } catch (Exception $e) {
                                    }
                                    ?>
                                    <h3
                                        style="font-size:20px; font-weight:700; margin-bottom:20px; color:var(--text-0); display:flex; align-items:center; gap:8px;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            style="color:var(--accent-bright);">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="2" y1="12" x2="22" y2="12"></line>
                                            <path
                                                d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                            </path>
                                        </svg>
                                        Cài Đặt Tên Miền
                                    </h3>

                                    <form method="POST"
                                        style="display:flex; flex-direction:column; gap:12px; margin-bottom:24px;">
                                        <input type="hidden" name="action" value="agency_save_domain">
                                        <div style="display:flex; gap:10px;">
                                            <input type="text" name="domain_name" placeholder="VD: shopcuaban.com"
                                                value="<?= htmlspecialchars($agency_domain) ?>"
                                                style="flex:1; width:100%; padding:14px 16px; border-radius:12px; background:var(--bg-0); border:1px solid var(--border); color:var(--text-0); font-size:15px; outline:none;"
                                                required>
                                            <button type="submit" class="btn btn-primary"
                                                style="padding:14px 20px; font-size:15px; border-radius:12px;">Lưu</button>
                                        </div>
                                    </form>

                                    <!-- Domain Setup Guide (Vertical List) -->
                                    <div style="display:flex; flex-direction:column; gap:16px;">
                                        <!-- Buy Domain Guide -->
                                        <div
                                            style="background:var(--bg-0); border:1px solid var(--border); border-radius:16px; padding:20px; position:relative; overflow:hidden;">
                                            <div
                                                style="position:absolute; top:0; right:0; padding:4px 10px; background:rgba(59, 130, 246, 0.1); color:#3b82f6; font-size:11px; font-weight:700; border-bottom-left-radius:10px;">
                                                Bước 1</div>
                                            <h4
                                                style="font-size:15px; font-weight:700; margin-bottom:8px; color:var(--text-0);">
                                                Mua tên miền (nếu chưa có)</h4>
                                            <p
                                                style="color:var(--text-2); font-size:13px; line-height:1.5; margin-bottom:12px;">
                                                Truy cập nhà cung cấp để tìm và mua một tên miền với giá chỉ từ 49k.
                                            </p>
                                            <a href="https://tenten.vn/vi" target="_blank"
                                                style="display:inline-flex; align-items:center; gap:6px; color:#3b82f6; font-size:13px; font-weight:600; text-decoration:none;">
                                                Truy cập Tenten <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                                    <polyline points="12 5 19 12 12 19"></polyline>
                                                </svg>
                                            </a>
                                        </div>

                                        <!-- Bước 2: Đổi Nameserver -->
                                        <div
                                            style="background:var(--bg-0);border:1px solid var(--border);border-radius:16px;padding:18px;position:relative;overflow:hidden;">
                                            <div
                                                style="position:absolute;top:0;right:0;padding:4px 10px;background:rgba(16,185,129,0.1);color:#10b981;font-size:11px;font-weight:700;border-bottom-left-radius:10px;">
                                                Bước 2</div>
                                            <h4 style="font-size:14px;font-weight:700;margin-bottom:6px;color:var(--text-0);">
                                                Đổi Nameserver về Cloudflare</h4>
                                            <p style="color:var(--text-2);font-size:13px;line-height:1.5;margin-bottom:10px;">
                                                Tại nơi mua tên miền, đổi <strong>2 Nameserver</strong> thành:</p>
                                            <div
                                                style="background:var(--bg-1);border:1px dashed var(--border-accent);padding:12px;border-radius:8px;font-size:13px;">
                                                <div
                                                    style="display:flex;justify-content:space-between;margin-bottom:8px;border-bottom:1px solid var(--border);padding-bottom:8px;">
                                                    <span style="color:var(--text-2);">NS 1:</span>
                                                    <strong
                                                        style="color:var(--accent-bright);font-family:monospace;">harleigh.ns.cloudflare.com</strong>
                                                </div>
                                                <div style="display:flex;justify-content:space-between;">
                                                    <span style="color:var(--text-2);">NS 2:</span>
                                                    <strong
                                                        style="color:var(--accent-bright);font-family:monospace;">woz.ns.cloudflare.com</strong>
                                                </div>
                                            </div>
                                            <p style="color:var(--text-2);font-size:12px;margin-top:8px;font-style:italic;">⏱ NS
                                                có hiệu lực sau 5–30 phút, tối đa 24h.</p>
                                        </div>

                                        <!-- Bước 3: Tạo A Record -->
                                        <div
                                            style="background:var(--bg-0);border:1px solid var(--border);border-radius:16px;padding:18px;position:relative;overflow:hidden;">
                                            <div
                                                style="position:absolute;top:0;right:0;padding:4px 10px;background:rgba(245,158,11,0.1);color:#f59e0b;font-size:11px;font-weight:700;border-bottom-left-radius:10px;">
                                                Bước 3</div>
                                            <h4 style="font-size:14px;font-weight:700;margin-bottom:6px;color:var(--text-0);">
                                                Tạo A Record trên Cloudflare DNS</h4>
                                            <p style="color:var(--text-2);font-size:13px;line-height:1.5;margin-bottom:10px;">
                                                Vào <strong>DNS → Records → Add Record</strong> và điền:</p>
                                            <div
                                                style="background:var(--bg-1);border:1px dashed var(--border-accent);padding:12px;border-radius:8px;font-size:13px;">
                                                <div
                                                    style="display:flex;justify-content:space-between;margin-bottom:6px;padding-bottom:6px;border-bottom:1px solid var(--border);">
                                                    <span style="color:var(--text-2);">Type:</span><strong>A</strong>
                                                </div>
                                                <div
                                                    style="display:flex;justify-content:space-between;margin-bottom:6px;padding-bottom:6px;border-bottom:1px solid var(--border);">
                                                    <span style="color:var(--text-2);">Name:</span><strong>@ (root) &amp;
                                                        www</strong>
                                                </div>
                                                <div
                                                    style="display:flex;justify-content:space-between;margin-bottom:6px;padding-bottom:6px;border-bottom:1px solid var(--border);">
                                                    <span style="color:var(--text-2);">IPv4 Address:</span>
                                                    <strong
                                                        onclick="navigator.clipboard.writeText('103.153.64.28');Swal.fire({toast:true,position:'top-end',icon:'success',title:'Đã copy IP',showConfirmButton:false,timer:1500,background:'var(--bg-1)',color:'var(--text-0)'})"
                                                        style="color:var(--accent-bright);font-family:monospace;cursor:pointer;"
                                                        title="Click để copy">103.153.64.28 📋</strong>
                                                </div>
                                                <div style="display:flex;justify-content:space-between;">
                                                    <span style="color:var(--text-2);">Proxy status:</span>
                                                    <strong style="color:#f59e0b;">🟠 Proxied (Bật)</strong>
                                                </div>
                                            </div>
                                            <p style="color:var(--text-2);font-size:12px;margin-top:8px;font-style:italic;">*
                                                Tạo 2 record: một cho <code
                                                    style="background:var(--bg-1);padding:1px 4px;border-radius:3px;">@</code>
                                                và một cho <code
                                                    style="background:var(--bg-1);padding:1px 4px;border-radius:3px;">www</code>.
                                            </p>
                                        </div>

                                        <!-- Bước 4: Nhập domain vào hệ thống -->
                                        <div
                                            style="background:linear-gradient(135deg,rgba(167,139,250,0.08),rgba(124,58,237,0.04));border:1px solid var(--border-accent);border-radius:16px;padding:18px;">
                                            <div style="display:flex;gap:12px;align-items:flex-start;">
                                                <div
                                                    style="width:30px;height:30px;border-radius:8px;background:rgba(167,139,250,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--accent-bright);font-weight:800;font-size:14px;">
                                                    4</div>
                                                <div>
                                                    <h4
                                                        style="font-size:14px;font-weight:700;margin-bottom:5px;color:var(--text-0);">
                                                        Nhập tên miền vào ô phía trên ↑ và bấm Lưu</h4>
                                                    <p style="color:var(--text-2);font-size:13px;line-height:1.5;margin:0;">Hệ
                                                        thống tự động nhận diện và phục vụ khách dưới thương hiệu của bạn ngay
                                                        lập tức sau khi DNS được cập nhật.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Upgrade Banner -->
                    <div class="card"
                        style="position:relative; overflow:hidden; border:1px solid rgba(245, 158, 11, 0.3); background:linear-gradient(180deg, var(--bg-1) 0%, rgba(245, 158, 11, 0.05) 100%); padding: 50px 30px; text-align:center; margin-bottom: 40px; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
                        <div
                            style="position:absolute; top:-100px; left:50%; transform:translateX(-50%); width:300px; height:300px; background:radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%); z-index:0; pointer-events:none;">
                        </div>

                        <div style="position:relative; z-index:1; display:flex; flex-direction:column; align-items:center;">
                            <div
                                style="width:80px; height:80px; border-radius:24px; background:linear-gradient(135deg, #f59e0b, #d97706); display:flex; align-items:center; justify-content:center; color:#fff; box-shadow:0 12px 30px rgba(245, 158, 11, 0.3); margin-bottom:24px;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                    <path d="M2 17l10 5 10-5"></path>
                                    <path d="M2 12l10 5 10-5"></path>
                                </svg>
                            </div>
                            <h2 style="font-size:28px; font-weight:800; margin-bottom:16px; color:var(--text-0);">Bạn Cần Nâng
                                Cấp Tài Khoản</h2>
                            <p
                                style="color:var(--text-2); max-width:550px; margin:0 auto 32px; line-height:1.7; font-size:16px;">
                                Để sử dụng hệ thống Website Con tự động (White-label), tài khoản của bạn cần có đặc quyền
                                <strong>Đại Lý</strong>. Bạn sẽ có bảng giá vốn siêu rẻ và toàn quyền định giá bán lẻ cho khách
                                của mình.
                            </p>
                            <a href="/dich-vu-vip" class="btn btn-primary"
                                style="padding: 16px 32px; font-size:16px; border-radius:100px; background:linear-gradient(135deg, #f59e0b, #d97706); box-shadow:0 8px 25px rgba(245, 158, 11, 0.4); border:none; display:inline-flex; align-items:center; gap:10px; transition:all 0.3s;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 30px rgba(245, 158, 11, 0.5)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 25px rgba(245, 158, 11, 0.4)';">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                </svg>
                                Xem Bảng Giá Nâng Cấp
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Features Grid -->
                <div style="display:flex; align-items:center; gap:16px; margin: 60px 0 30px;">
                    <div style="flex:1; height:1px; background:var(--border);"></div>
                    <h2 style="font-size:20px; font-weight:700; color:var(--text-1); margin:0;">Đặc Quyền Của Đại Lý</h2>
                    <div style="flex:1; height:1px; background:var(--border);"></div>
                </div>

                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:24px;">
                    <!-- Feature 1 -->
                    <div class="card"
                        style="padding:30px; border:1px solid var(--border); transition:all 0.3s; background:var(--bg-1);"
                        onmouseover="this.style.borderColor='var(--accent)'; this.style.transform='translateY(-5px)';"
                        onmouseout="this.style.borderColor='var(--border)'; this.style.transform='translateY(0)';">
                        <div
                            style="width:56px; height:56px; border-radius:16px; background:rgba(167,139,250,0.1); color:var(--accent-bright); display:flex; align-items:center; justify-content:center; margin-bottom:20px;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <h3 style="font-size:18px; font-weight:700; margin-bottom:12px; color:var(--text-0);">Bảo Mật & Tách
                            Biệt</h3>
                        <p style="color:var(--text-2); font-size:15px; line-height:1.6; margin:0;">
                            Khách hàng sẽ chỉ nhìn thấy thương hiệu, tên miền và thông tin liên hệ của bạn. 100% không có
                            bất kỳ dấu vết nào của hệ thống mẹ.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="card"
                        style="padding:30px; border:1px solid var(--border); transition:all 0.3s; background:var(--bg-1);"
                        onmouseover="this.style.borderColor='var(--green)'; this.style.transform='translateY(-5px)';"
                        onmouseout="this.style.borderColor='var(--border)'; this.style.transform='translateY(0)';">
                        <div
                            style="width:56px; height:56px; border-radius:16px; background:rgba(16, 185, 129, 0.1); color:var(--green); display:flex; align-items:center; justify-content:center; margin-bottom:20px;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path>
                                <path d="M12 18V6"></path>
                            </svg>
                        </div>
                        <h3 style="font-size:18px; font-weight:700; margin-bottom:12px; color:var(--text-0);">Thanh Toán
                            Trực Tiếp</h3>
                        <p style="color:var(--text-2); font-size:15px; line-height:1.6; margin:0;">
                            Khách mua hàng và chuyển khoản thẳng về tài khoản ngân hàng của bạn. Bạn chủ động nhận tiền và
                            duyệt đơn ngay trên admin.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="card"
                        style="padding:30px; border:1px solid var(--border); transition:all 0.3s; background:var(--bg-1);"
                        onmouseover="this.style.borderColor='var(--orange)'; this.style.transform='translateY(-5px)';"
                        onmouseout="this.style.borderColor='var(--border)'; this.style.transform='translateY(0)';">
                        <div
                            style="width:56px; height:56px; border-radius:16px; background:rgba(245, 158, 11, 0.1); color:var(--orange); display:flex; align-items:center; justify-content:center; margin-bottom:20px;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                            </svg>
                        </div>
                        <h3 style="font-size:18px; font-weight:700; margin-bottom:12px; color:var(--text-0);">Tự Do Định Giá
                            Bán</h3>
                        <p style="color:var(--text-2); font-size:15px; line-height:1.6; margin:0;">
                            Nhập tài khoản với giá sỉ cực thấp và toàn quyền thiết lập giá bán lẻ (VIP 1, VIP 2...) hiển thị
                            trên website của bạn.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="card"
                        style="padding:30px; border:1px solid var(--border); transition:all 0.3s; background:var(--bg-1);"
                        onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-5px)';"
                        onmouseout="this.style.borderColor='var(--border)'; this.style.transform='translateY(0)';">
                        <div
                            style="width:56px; height:56px; border-radius:16px; background:rgba(59, 130, 246, 0.1); color:#3b82f6; display:flex; align-items:center; justify-content:center; margin-bottom:20px;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                </path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <h3 style="font-size:18px; font-weight:700; margin-bottom:12px; color:var(--text-0);">Quản Lý Tự
                            Động</h3>
                        <p style="color:var(--text-2); font-size:15px; line-height:1.6; margin:0;">
                            Trang quản trị (Admin) dành riêng cho bạn. Cấu hình giao diện, theo dõi hóa đơn và quản lý đơn
                            hàng một cách chuyên nghiệp.
                        </p>
                    </div>
                </div>

                <!-- How It Works Section -->
                <div style="margin: 80px 0 40px; text-align:center;">
                    <div
                        style="display:inline-flex; align-items:center; justify-content:center; padding:8px 16px; border-radius:20px; background:rgba(59, 130, 246, 0.1); border:1px solid rgba(59, 130, 246, 0.2); color:#3b82f6; font-size:14px; font-weight:600; margin-bottom:16px;">
                        Quy Trình Hoạt Động
                    </div>
                    <h2 style="font-size:32px; font-weight:800; color:var(--text-0); margin-bottom:16px;">Vận Hành Đơn Giản,
                        Lợi Nhuận Tối Đa</h2>
                    <p style="color:var(--text-2); font-size:16px; max-width:600px; margin:0 auto;">Hệ thống được thiết kế
                        để tự động hóa quy trình bán hàng, giúp bạn tập trung vào việc tìm kiếm khách hàng.</p>
                </div>

                <div
                    style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:24px; position:relative;">
                    <!-- Step 1 -->
                    <div
                        style="background:var(--bg-1); border:1px solid var(--border); border-radius:24px; padding:30px; text-align:center; position:relative;">
                        <div
                            style="width:48px; height:48px; border-radius:50%; background:linear-gradient(135deg, #3b82f6, #2563eb); color:#fff; display:flex; align-items:center; justify-content:center; font-size:20px; font-weight:800; margin:0 auto 20px; box-shadow:0 10px 20px rgba(59, 130, 246, 0.3);">
                            1</div>
                        <h3 style="font-size:18px; font-weight:700; margin-bottom:12px; color:var(--text-0);">Thiết Lập
                            Website</h3>
                        <p style="color:var(--text-2); font-size:14px; line-height:1.6; margin:0;">Nhập tên thương hiệu, cấu
                            hình thông tin ngân hàng và tùy chỉnh giá bán lẻ cho khách hàng của bạn.</p>
                    </div>
                    <!-- Step 2 -->
                    <div
                        style="background:var(--bg-1); border:1px solid var(--border); border-radius:24px; padding:30px; text-align:center; position:relative;">
                        <div
                            style="width:48px; height:48px; border-radius:50%; background:linear-gradient(135deg, #10b981, #059669); color:#fff; display:flex; align-items:center; justify-content:center; font-size:20px; font-weight:800; margin:0 auto 20px; box-shadow:0 10px 20px rgba(16, 185, 129, 0.3);">
                            2</div>
                        <h3 style="font-size:18px; font-weight:700; margin-bottom:12px; color:var(--text-0);">Khách Mua Hàng
                        </h3>
                        <p style="color:var(--text-2); font-size:14px; line-height:1.6; margin:0;">Khách truy cập web của
                            bạn, chọn gói dịch vụ và chuyển khoản trực tiếp vào tài khoản ngân hàng của bạn.</p>
                    </div>
                    <!-- Step 3 -->
                    <div
                        style="background:var(--bg-1); border:1px solid var(--border); border-radius:24px; padding:30px; text-align:center; position:relative;">
                        <div
                            style="width:48px; height:48px; border-radius:50%; background:linear-gradient(135deg, #f59e0b, #d97706); color:#fff; display:flex; align-items:center; justify-content:center; font-size:20px; font-weight:800; margin:0 auto 20px; box-shadow:0 10px 20px rgba(245, 158, 11, 0.3);">
                            3</div>
                        <h3 style="font-size:18px; font-weight:700; margin-bottom:12px; color:var(--text-0);">Xử Lý Tự Động
                        </h3>
                        <p style="color:var(--text-2); font-size:14px; line-height:1.6; margin:0;">Bạn vào Admin, nạp gói
                            giá sỉ (rẻ hơn) để kích hoạt đơn hàng. Hệ thống tự động báo cho khách.</p>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div style="margin: 80px 0 40px; text-align:center;">
                    <div
                        style="display:inline-flex; align-items:center; justify-content:center; padding:8px 16px; border-radius:20px; background:rgba(236, 72, 153, 0.1); border:1px solid rgba(236, 72, 153, 0.2); color:#ec4899; font-size:14px; font-weight:600; margin-bottom:16px;">
                        Câu Hỏi Thường Gặp
                    </div>
                    <h2 style="font-size:32px; font-weight:800; color:var(--text-0); margin-bottom:16px;">Giải Đáp Thắc Mắc
                    </h2>
                </div>

                <div style="display:flex; flex-direction:column; gap:16px; max-width:800px; margin:0 auto;">
                    <div style="background:var(--bg-1); border:1px solid var(--border); border-radius:16px; padding:24px;">
                        <h3 style="font-size:16px; font-weight:700; color:var(--text-0); margin-bottom:10px;">Tôi có cần
                            biết lập trình không?</h3>
                        <p style="color:var(--text-2); font-size:14px; line-height:1.6; margin:0;">Hoàn toàn KHÔNG. Mọi thứ
                            đã được xây dựng sẵn. Bạn chỉ cần nhập thông tin, giá bán và cấu hình ngân hàng là hệ thống tự
                            động sinh ra một website riêng cho bạn.</p>
                    </div>
                    <div style="background:var(--bg-1); border:1px solid var(--border); border-radius:16px; padding:24px;">
                        <h3 style="font-size:16px; font-weight:700; color:var(--text-0); margin-bottom:10px;">Làm sao khách
                            hàng không biết tôi là đại lý?</h3>
                        <p style="color:var(--text-2); font-size:14px; line-height:1.6; margin:0;">Hệ thống ứng dụng công
                            nghệ White-label. Nếu bạn dùng tên miền riêng (VD: locketgiatot.com), toàn bộ logo, tên gọi,
                            bảng giá và stk thanh toán đều là của bạn. Khách hàng sẽ nghĩ đây là website độc lập do bạn sở
                            hữu.</p>
                    </div>
                    <div style="background:var(--bg-1); border:1px solid var(--border); border-radius:16px; padding:24px;">
                        <h3 style="font-size:16px; font-weight:700; color:var(--text-0); margin-bottom:10px;">Lợi nhuận của
                            tôi được tính như thế nào?</h3>
                        <p style="color:var(--text-2); font-size:14px; line-height:1.6; margin:0;">Khi khách mua hàng trên
                            web của bạn (VD gói VIP 1 giá 100k), khách sẽ chuyển 100k vào tk ngân hàng của bạn. Bạn giữ tiền
                            đó. Sau đó bạn vào web gốc, nâng cấp gói Đại Lý (chỉ 50k) để gửi cho khách. Lợi nhuận của bạn là
                            50k.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>