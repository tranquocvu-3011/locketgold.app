<!-- ═══════ HƯỚNG DẪN ═══════ -->
<?php if ($page === 'guide'): ?>
    <?php
    $guide_video_activation = $settings['guide_video_activation'] ?? ($settings['guide_video'] ?? '');
    $guide_video_recovery = $settings['guide_video_recovery'] ?? '';
    ?>
    <div class="page-shell" style="animation:cardIn 0.6s ease forwards;">
        <!-- Header -->
        <div style="text-align:center; margin-bottom:36px;">
            <h1 class="page-title" style="font-size:36px; margin-bottom:12px;">Trung Tâm Hướng Dẫn Locket Gold</h1>
            <p style="color: var(--text-2); font-size: 15px; margin-bottom: 20px;">👇 Vui lòng chọn 1 trong 2 mục dưới đây
                để xem thông tin chi tiết</p>
            <div class="guide-section-chips initial-state">
                <button type="button" class="guide-chip" data-guide-tab="activation">1. Sử dụng và kích
                    hoạt</button>
                <button type="button" class="guide-chip" data-guide-tab="recovery">2. Khắc phục lỗi mất
                    Gold</button>
            </div>
        </div>

        <!-- 2 CỘT LAYOUT -->
        <div class="guide-layout">
            <!-- CỘT TRÁI: CÁC BƯỚC -->
            <div class="guide-steps">
                <div class="guide-tab-pane" data-guide-pane="activation">
                    <div id="guide-activation" class="guide-section-title">1. Hướng dẫn sử dụng và kích hoạt</div>

                    <!-- BƯỚC 1 -->
                    <div class="step"
                        style="background:var(--bg-1); padding:28px; border-radius:var(--radius-md); border:1px solid var(--border); box-shadow:0 4px 20px rgba(0,0,0,0.1); margin-bottom:24px;">
                        <div class="step-num"
                            style="background:var(--bg-surface); border:2px solid var(--text-2); color:var(--text-1);">
                            1
                        </div>
                        <div class="step-body">
                            <h3 style="font-size:18px; color:var(--text-1); margin-bottom:8px;">Mở khóa Tài Khoản
                                (Nâng cấp VIP)</h3>
                            <p style="margin-bottom:16px; font-size:14px; color:var(--text-2); line-height:1.5;">Để
                                kích hoạt trải nghiệm Locket Gold, bạn cần nâng cấp tài khoản của mình:</p>
                            <div style="display:flex; flex-direction:column; gap:10px;">
                                <div
                                    style="background:rgba(255,255,255,0.03); padding:12px 16px; border-radius:8px; border-left:3px solid var(--accent-bright); font-size:14px;">
                                    <strong style="color:var(--accent-bright); display:block; margin-bottom:4px;">Nâng
                                        cấp VIP Tự Động</strong>
                                    Truy cập vào mục <strong style="color:var(--text-1);">Bảng giá</strong>, chọn
                                    gói VIP mong muốn và tiến hành chuyển khoản kèm theo nội dung. Sau khi tải lên
                                    hóa đơn thành công, hệ thống hoặc đại lý sẽ xử lý và nâng cấp đặc quyền VIP cho
                                    bạn!
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BƯỚC 2 -->
                    <div class="step"
                        style="background:var(--bg-1); padding:28px; border-radius:var(--radius-md); border:1px solid rgba(167,139,250,0.3); box-shadow:0 4px 20px rgba(167,139,250,0.05); margin-bottom:24px; position:relative; overflow:hidden;">
                        <div
                            style="position:absolute; top:0; left:0; width:4px; height:100%; background:var(--accent-bright);">
                        </div>
                        <div class="step-num"
                            style="background:rgba(167,139,250,0.1); border:2px solid var(--accent-bright); color:var(--accent-bright);">
                            2</div>
                        <div class="step-body">
                            <h3 style="font-size:18px; color:var(--accent-bright); margin-bottom:8px;">Khai Báo Định
                                Danh & Đồng Bộ</h3>
                            <p style="margin-bottom:16px; font-size:14px; color:var(--text-2); line-height:1.5;">Hệ
                                thống cho phép kích hoạt bằng nhiều cách, siêu nhanh và chính xác.</p>
                            <div style="display:flex; flex-direction:column; gap:12px;">
                                <div
                                    style="background:rgba(167,139,250,0.05); border:1px dashed rgba(167,139,250,0.3); padding:14px; border-radius:8px;">
                                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="var(--accent-bright)" stroke-width="2">
                                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                                        </svg>
                                        <strong style="color:var(--accent-bright); font-size:14px;">Cách 1: Link
                                            Chia Sẻ
                                            (Khuyên dùng)</strong>
                                    </div>
                                    <span style="font-size:13px; color:var(--text-2); line-height:1.5;">Mở Locket >
                                        Profile > <strong>Chia sẻ Hồ sơ</strong> > <strong>Sao chép Liên
                                            kết</strong>.
                                        Dán vào Tool kích hoạt.</span>
                                </div>
                                <div
                                    style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); padding:14px; border-radius:8px;">
                                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-1)"
                                            stroke-width="2">
                                            <path
                                                d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                                            <line x1="7" y1="7" x2="7.01" y2="7" />
                                        </svg>
                                        <strong style="color:var(--text-1); font-size:14px;">Cách 2: Nhập thẳng
                                            Locket
                                            ID</strong>
                                    </div>
                                    <span style="font-size:13px; color:var(--text-2); line-height:1.5;">Link
                                        <code>/username</code> → nhập <code
                                            style="color:var(--accent-bright); font-weight:bold;">username</code>.</span>
                                </div>
                            </div>
                            <div
                                style="margin-top:14px; background:rgba(52,211,153,0.08); padding:12px; border-radius:8px; font-size:13px; color:var(--text-2); display:flex; gap:8px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                    stroke-width="2.5" style="margin-top:2px; flex-shrink:0;">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                <span style="line-height:1.5;">AI máy chủ tự động sàng lọc và xuất UID chuẩn xác
                                    nhất!</span>
                            </div>
                            <div
                                style="margin-top:12px; background:rgba(239,68,68,0.08); border:1px dashed rgba(239,68,68,0.3); padding:12px; border-radius:8px; font-size:13px; color:var(--red); display:flex; gap:8px; align-items:flex-start;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" style="margin-top:2px; flex-shrink:0;">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                <span style="line-height:1.5;"><strong>Lưu ý quan trọng:</strong> Sau bước này, bạn bắt buộc
                                    phải <strong>vuốt đóng hoàn toàn ứng dụng Locket ở màn hình đa nhiệm</strong> rồi mới mở
                                    lại để tài khoản được nhận cấu hình mới.</span>
                            </div>
                        </div>
                    </div>

                    <!-- BƯỚC 3 (VIP only) -->
                    <?php if ($is_vip_or_higher): ?>
                        <div class="step"
                            style="background:var(--bg-1); padding:28px; border-radius:var(--radius-md); border:1px solid rgba(52,211,153,0.4); box-shadow:0 4px 20px rgba(52,211,153,0.08); position:relative; overflow:hidden;">
                            <div style="position:absolute; top:0; left:0; width:4px; height:100%; background:var(--green);">
                            </div>
                            <div class="step-num"
                                style="background:rgba(52,211,153,0.1); border:2px solid var(--green); color:var(--green);">
                                3</div>
                            <div class="step-body">
                                <h3 style="font-size:18px; color:var(--green); margin-bottom:8px;">Cài đặt Profile Bảo
                                    Vệ
                                    Nhận Diện
                                    (BẮT BUỘC)</h3>
                                <p style="margin-bottom:16px; font-size:14px; color:var(--text-2); line-height:1.5;">Bạn
                                    <strong>phải kích hoạt Gold thành công trước</strong>, đợi hệ thống báo Hoàn Tất rồi
                                    mới
                                    cài Profile Bảo Vệ.
                                </p>

                                <div
                                    style="background:rgba(248,113,113,0.08); border:1px solid rgba(248,113,113,0.28); border-radius:10px; padding:14px 16px; margin-bottom:16px; font-size:13px; line-height:1.6; color:var(--red);">
                                    <strong style="color:var(--text-1);">Lưu ý:</strong> DNS chỉ dùng để <strong
                                        style="color:var(--text-1);">giữ trạng thái Cấp Quyền ổn định</strong>. Không
                                    cài DNS trước khi báo Hoàn tất.
                                </div>

                                <div
                                    style="background:var(--bg-surface); border:1px solid var(--border); border-radius:8px; padding:16px; margin-bottom:16px;">
                                    <h4
                                        style="font-size:13px; margin-bottom:12px; color:var(--text-1); text-transform:uppercase; letter-spacing:0.5px;">
                                        Trình Tự Trên iPhone:</h4>
                                    <div style="display:flex; flex-direction:column; gap:10px;">
                                        <div style="display:flex; align-items:flex-start; gap:12px;">
                                            <div
                                                style="background:var(--green); color:#000; width:22px; height:22px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:12px; flex-shrink:0; margin-top:2px;">
                                                1</div>
                                            <div style="font-size:13px; color:var(--text-2); line-height:1.5;">Ấn
                                                <strong>Tải DNS Profile bảo vệ</strong> sau khi kích hoạt. Chọn
                                                <strong>Cho
                                                    phép (Allow)</strong>.
                                            </div>
                                        </div>
                                        <div style="display:flex; align-items:flex-start; gap:12px;">
                                            <div
                                                style="background:var(--green); color:#000; width:22px; height:22px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:12px; flex-shrink:0; margin-top:2px;">
                                                2</div>
                                            <div style="font-size:13px; color:var(--text-2); line-height:1.5;">Thoát
                                                trình
                                                duyệt, mở app <strong>Cài đặt (Settings)</strong> gốc iPhone.</div>
                                        </div>
                                        <div style="display:flex; align-items:flex-start; gap:12px;">
                                            <div
                                                style="background:var(--green); color:#000; width:22px; height:22px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:12px; flex-shrink:0; margin-top:2px;">
                                                3</div>
                                            <div style="font-size:13px; color:var(--text-2); line-height:1.5;">Bấm
                                                <strong>"Đã tải về hồ sơ"</strong> ngay dưới tên Apple ID.
                                            </div>
                                        </div>
                                        <div style="display:flex; align-items:flex-start; gap:12px;">
                                            <div
                                                style="background:var(--green); color:#000; width:22px; height:22px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:12px; flex-shrink:0; margin-top:2px;">
                                                4</div>
                                            <div style="font-size:13px; color:var(--text-2); line-height:1.5;">Bấm
                                                <strong>Cài đặt (Install)</strong> > Nhập mật mã > <strong>Cài
                                                    đặt</strong>
                                                > <strong>Xong</strong>.
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <div
                                    style="background:rgba(248,113,113,0.08); border:1px solid rgba(248,113,113,0.3); padding:14px; border-radius:8px;">
                                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--red)"
                                            stroke-width="2">
                                            <path
                                                d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                                            <line x1="12" y1="9" x2="12" y2="13" />
                                            <line x1="12" y1="17" x2="12.01" y2="17" />
                                        </svg>
                                        <strong style="color:var(--red); font-size:14px; text-transform:uppercase;">Cảnh
                                            Báo
                                            Tước Quyền Bảo Hành</strong>
                                    </div>
                                    <p style="margin:0; font-size:13px; color:var(--red); line-height:1.5;">Nếu bỏ qua
                                        bước iPhone,
                                        Hệ thống sẽ <strong style="color:var(--accent-bright);">từ chối bảo
                                            hành</strong> đối với các trường hợp bị rớt tự nhiên!</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="guide-tab-pane" data-guide-pane="recovery">
                    <div id="guide-recovery" class="guide-section-title">2. Hướng dẫn khắc phục lỗi mất Gold</div>
                    <div class="step"
                        style="background:var(--bg-1); padding:28px; border-radius:var(--radius-md); border:1px solid rgba(251,191,36,0.32); box-shadow:0 4px 20px rgba(251,191,36,0.08); position:relative; overflow:hidden;">
                        <div style="position:absolute; top:0; left:0; width:4px; height:100%; background:var(--orange);">
                        </div>
                        <div class="step-num"
                            style="background:rgba(251,191,36,0.12); border:2px solid var(--orange); color:var(--orange);">
                            !</div>
                        <div class="step-body">
                            <h3 style="font-size:19px; color:var(--orange); margin-bottom:8px;">Quy trình khôi phục
                                khi tài khoản bị
                                mất Gold</h3>
                            <p style="margin-bottom:14px; font-size:14px; color:var(--text-2); line-height:1.6;">
                                Làm đúng thứ tự bên dưới để đảm bảo tài khoản lên lại Gold ổn định.
                            </p>

                            <div class="guide-recovery-list">
                                <?php if ($is_vip_or_higher): ?>
                                    <div class="guide-recovery-item">
                                        <div class="guide-recovery-num">1</div>
                                        <div>
                                            <strong>Tắt DNS tạm thời:</strong> Vào <strong>Cài đặt</strong> → <strong>Cài đặt
                                                chung</strong> →
                                            <strong>Quản lý VPN &amp; Thiết bị</strong> → <strong>DNS</strong> và chuyển tạm về
                                            <strong>"Tự động"</strong>.
                                        </div>
                                    </div>
                                    <div class="guide-recovery-item">
                                        <div class="guide-recovery-num">2</div>
                                        <div><strong>Làm mới cấu hình (Bắt buộc):</strong> Vào Website, mở tab Công cụ và tìm
                                            đến <strong>Kích hoạt lại tài khoản bị lỗi Gold</strong>, chọn
                                            đúng ID của bạn rồi bấm <strong>Kích hoạt lại</strong>.
                                            <span
                                                style="color:var(--red); font-size:12px; font-weight:bold; display:block; margin-top:4px;">(Tuyệt
                                                đối KHÔNG tự ý ấn Khôi phục trong app Locket nếu chưa làm bước này trên
                                                Web)</span>
                                        </div>
                                    </div>
                                    <div class="guide-recovery-item">
                                        <div class="guide-recovery-num">3</div>
                                        <div><strong>Đóng Đa Nhiệm:</strong> Bạn bắt buộc phải vuốt <strong>đóng hoàn toàn ứng
                                                dụng Locket</strong> ở màn hình đa nhiệm (App Switcher) để xóa bộ nhớ đệm cũ.
                                        </div>
                                    </div>
                                    <div class="guide-recovery-item">
                                        <div class="guide-recovery-num">4</div>
                                        <div style="width: 100%;"><strong>Nhận Gold:</strong> Mở lại ứng dụng Locket và tận
                                            hưởng quyền lợi.
                                            <div style="font-size:13px; color:var(--text-2); margin-top:4px;"><em>(Lưu ý: Nếu
                                                    Locket vẫn chưa hiện mác Gold, hãy <strong>đăng xuất ra rồi vào lại</strong>
                                                    tài khoản).</em></div>
                                            <div
                                                style="margin-top:10px; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); border-radius:8px; padding:12px; font-size:13px; color:var(--red); display:flex; align-items:flex-start; gap:8px;">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2.5"
                                                    style="flex-shrink:0; margin-top:2px;">
                                                    <path
                                                        d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                                                    <line x1="12" y1="9" x2="12" y2="13" />
                                                    <line x1="12" y1="17" x2="12.01" y2="17" />
                                                </svg>
                                                <div>
                                                    <strong>CẤM TUYỆT ĐỐI:</strong> Không được ấn nút <strong>Khôi phục đơn hàng
                                                        (Restore Purchases)</strong> trong app Locket. Việc ấn nút này sẽ làm
                                                    hỏng cấu hình và gây lỗi hệ thống!
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="guide-recovery-item">
                                        <div class="guide-recovery-num">5</div>
                                        <div><strong>Bật lại Bảo vệ:</strong> Sau khi hệ thống báo thành công và tài khoản đã
                                            lên lại mác Gold, hãy quay lại phần DNS ở
                                            bước 1 và đổi từ <strong>"Tự động"</strong> sang lại
                                            <strong>"LocketGold.app - Locket Gold Quốc Vũ"</strong> để giữ trạng thái an toàn.
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="guide-recovery-item">
                                        <div class="guide-recovery-num">1</div>
                                        <div>Do bạn đang dùng thẻ Thành Viên, tính năng tự động Khôi phục bị khóa. Vui
                                            lòng liên hệ Admin để được hỗ trợ cấp lại Gold.
                                        </div>
                                    </div>
                                    <div class="guide-recovery-item">
                                        <div class="guide-recovery-num">2</div>
                                        <div>Sau khi Admin báo thành công, hãy vào ứng dụng Locket, tìm và ấn nút
                                            <strong>Khôi phục đơn hàng (Restore
                                                Purchases)</strong>.
                                        </div>
                                    </div>
                                    <div class="guide-recovery-item">
                                        <div class="guide-recovery-num">3</div>
                                        <div>Chờ hệ thống báo thành công và kiểm tra lại trạng thái Gold trong ứng dụng.
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ($is_vip_or_higher): ?>
                                <div
                                    style="margin-top:14px; background:rgba(52,211,153,0.08); border:1px solid rgba(52,211,153,0.25); padding:12px 14px; border-radius:10px; font-size:13px; color:var(--text-1); line-height:1.6;">
                                    Mẹo: nếu chưa thành công ở bước 4, bạn chưa nên đổi DNS sang "LocketGold.app - Locket Gold
                                    Quốc Vũ".
                                    Hãy
                                    kích
                                    hoạt lại đến khi báo thành công rồi mới đổi.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>

            <!-- CỘT PHẢI: VIDEO -->
            <div class="guide-video-col">
                <div class="guide-video-pane" data-guide-video-pane="activation">
                    <div class="guide-video-wrapper">
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
                            <div
                                style="display:flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:10px; background:rgba(167,139,250,0.1);">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent-bright)"
                                    stroke-width="2">
                                    <polygon points="5 3 19 12 5 21 5 3" />
                                </svg>
                            </div>
                            <div>
                                <div style="font-size:15px; font-weight:700; color:var(--text-0);">Video mục 1: Sử
                                    dụng &amp; kích hoạt</div>
                                <div style="font-size:12px; color:var(--text-2);">Video thao tác dành cho phần kích
                                    hoạt</div>
                            </div>
                        </div>
                        <?php if ($guide_video_activation && file_exists($guide_video_activation)): ?>
                            <video controls playsinline
                                style="width:100%; border-radius:12px; background:#000; box-shadow:0 8px 32px rgba(0,0,0,0.4);">
                                <source src="/<?= htmlspecialchars($guide_video_activation) ?>" type="video/mp4">
                            </video>
                        <?php else: ?>
                            <div class="guide-video-empty">
                                <p style="color:var(--text-2); font-size:14px; margin:0;">Chưa có video cho mục Sử dụng
                                    &amp; kích hoạt.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="guide-video-pane" data-guide-video-pane="recovery">
                    <div class="guide-video-wrapper">
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
                            <div
                                style="display:flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:10px; background:rgba(251,191,36,0.16); color:var(--orange);">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polygon points="5 3 19 12 5 21 5 3" />
                                </svg>
                            </div>
                            <div>
                                <div style="font-size:15px; font-weight:700; color:var(--text-0);">Video mục 2: Khắc
                                    phục mất Gold</div>
                                <div style="font-size:12px; color:var(--text-2);">Video thao tác dành cho phần khắc
                                    phục</div>
                            </div>
                        </div>
                        <?php if ($guide_video_recovery && file_exists($guide_video_recovery)): ?>
                            <video controls playsinline
                                style="width:100%; border-radius:12px; background:#000; box-shadow:0 8px 32px rgba(0,0,0,0.4);">
                                <source src="/<?= htmlspecialchars($guide_video_recovery) ?>" type="video/mp4">
                            </video>
                        <?php else: ?>
                            <div class="guide-video-empty">
                                <p style="color:var(--text-2); font-size:14px; margin:0;">Chưa có video cho mục Khắc
                                    phục mất Gold.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .guide-section-chips {
            display: inline-flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .guide-section-chips.initial-state {
            margin-top: 30px;
            margin-bottom: 60px;
            gap: 20px;
        }

        .guide-section-chips.initial-state .guide-chip {
            padding: 16px 32px;
            font-size: 17px;
            border-width: 2px;
            border-color: rgba(167, 139, 250, 0.5);
            background: rgba(167, 139, 250, 0.08);
            box-shadow: 0 10px 30px rgba(167, 139, 250, 0.15);
            animation: pulse-guide-btn 2.5s infinite;
        }

        @keyframes pulse-guide-btn {
            0% { transform: scale(1); box-shadow: 0 10px 30px rgba(167, 139, 250, 0.15); }
            50% { transform: scale(1.05); box-shadow: 0 15px 40px rgba(167, 139, 250, 0.3); border-color: rgba(167, 139, 250, 0.8); }
            100% { transform: scale(1); box-shadow: 0 10px 30px rgba(167, 139, 250, 0.15); }
        }

        .guide-chip {
            appearance: none;
            border: 1px solid var(--border);
            font-family: inherit;
            cursor: pointer;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.03);
            color: var(--text-1);
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: 0.2s;
        }

        .guide-chip.is-active {
            border-color: var(--border-accent);
            color: var(--accent-bright);
            background: rgba(167, 139, 250, 0.16);
            box-shadow: 0 8px 22px rgba(167, 139, 250, 0.2);
        }

        .guide-chip:hover {
            border-color: var(--border-accent);
            color: var(--accent-bright);
            background: rgba(167, 139, 250, 0.08);
        }

        .guide-tab-pane {
            display: none;
        }

        .guide-tab-pane.is-active {
            display: block;
        }

        .guide-video-pane {
            display: none;
        }

        .guide-video-pane.is-active {
            display: block;
        }

        .guide-video-empty {
            border: 1px dashed var(--border);
            border-radius: var(--radius-md);
            padding: 32px 18px;
            text-align: center;
            background: rgba(255, 255, 255, 0.02);
        }

        .guide-section-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-0);
            margin-bottom: 14px;
            letter-spacing: -0.2px;
        }

        .guide-recovery-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .guide-recovery-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 10px;
            padding: 12px 14px;
            color: var(--text-1);
            font-size: 14px;
            line-height: 1.55;
        }

        .guide-recovery-num {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 800;
            color: #000;
            background: var(--orange);
            margin-top: 1px;
        }

        .guide-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 32px;
            align-items: start;
        }

        .guide-video-col {
            position: sticky;
            top: 100px;
        }

        .guide-video-wrapper {
            background: var(--bg-1);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px;
            box-shadow: var(--shadow-soft);
        }

        @media (max-width:900px) {
            .guide-section-chips {
                width: 100%;
            }

            .guide-layout {
                grid-template-columns: 1fr;
            }

            .guide-video-col {
                position: static;
                order: -1;
            }
        }
    </style>
    <script>
        (function () {
            const tabButtons = Array.from(document.querySelectorAll('[data-guide-tab]'));
            const panes = Array.from(document.querySelectorAll('[data-guide-pane]'));
            const videoPanes = Array.from(document.querySelectorAll('[data-guide-video-pane]'));
            if (!tabButtons.length || !panes.length) return;

            function setGuideTab(key) {
                const chipsContainer = document.querySelector('.guide-section-chips');
                if (chipsContainer) {
                    chipsContainer.classList.remove('initial-state');
                }
                
                tabButtons.forEach(btn => {
                    btn.classList.toggle('is-active', btn.dataset.guideTab === key);
                });
                panes.forEach(pane => {
                    pane.classList.toggle('is-active', pane.dataset.guidePane === key);
                });
                videoPanes.forEach(pane => {
                    pane.classList.toggle('is-active', pane.dataset.guideVideoPane === key);
                });
            }

            tabButtons.forEach(btn => {
                btn.addEventListener('click', () => setGuideTab(btn.dataset.guideTab));
            });
        })();
    </script>
<?php endif; ?>