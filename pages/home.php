<!-- ═══════ TRANG CHỦ ═══════ -->
<?php if ($page === 'home'): ?>
    <div class="page-shell" style="text-align:center; animation: cardIn 0.6s ease forwards;">


        <style>
            .hero-grid {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 60px;
                margin-top: 10px;
                margin-bottom: 60px;
                flex-wrap: wrap;
                text-align: left;
            }

            .hero-text-col {
                flex: 1.25;
                min-width: 340px;
            }

            .hero-image-col {
                flex: 0.75;
                min-width: 300px;
                display: flex;
                justify-content: flex-end;
                align-items: center;
            }

            .hero-text-col .hero-title,
            .hero-text-col .hero-sub {
                text-align: left !important;
            }

            .hero-title .gradient {
                background: linear-gradient(270deg, #A78BFA, #F472B6, #8B5CF6, #3B82F6, #A78BFA);
                background-size: 400% 400%;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                animation: gradientSweep 6s ease infinite;
                display: inline-block;
            }

            @keyframes gradientSweep {
                0% {
                    background-position: 0% 50%;
                }

                50% {
                    background-position: 100% 50%;
                }

                100% {
                    background-position: 0% 50%;
                }
            }

            .hero-image-col img {
                width: 100%;
                max-width: 480px;
                height: auto;
                display: block;
                /* Tạo hiệu ứng đổ bóng cho ảnh (nếu là PNG trong suốt sẽ rất đẹp) */
                filter: drop-shadow(0 20px 40px rgba(167, 139, 250, 0.25));
                /* Hiệu ứng di chuyển nhẹ */
                animation: floatBanner 5s ease-in-out infinite;
                transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }

            @keyframes floatBanner {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-16px);
                }
            }

            .hero-image-col img:hover {
                transform: translateY(-24px) scale(1.03) rotate(1deg);
                filter: drop-shadow(0 30px 60px rgba(167, 139, 250, 0.45));
            }

            @media (max-width: 900px) {
                .hero-grid {
                    flex-direction: column;
                    /* Đổi từ column-reverse thành column để chữ lên trên */
                    gap: 32px;
                    margin-top: 5px;
                    margin-bottom: 40px;
                }

                .hero-image-col {
                    justify-content: center;
                }

                .hero-image-col img {
                    max-width: 340px;
                    /* Tăng cường hiệu ứng di chuyển tại chỗ trên mobile */
                    animation: floatMobile 5s ease-in-out infinite;
                }

                @keyframes floatMobile {

                    0%,
                    100% {
                        transform: translateY(0);
                    }

                    50% {
                        transform: translateY(-12px);
                    }
                }

                .hero-text-col .hero-title,
                .hero-text-col .hero-sub {
                    text-align: center !important;
                }
            }
        </style>

        <div class="hero-grid">
            <div class="hero-text-col">
                <h1 class="hero-title" style="margin-bottom:20px; line-height:1.15; letter-spacing:-1px;">
                    Hệ Thống Nâng Cấp<br><span class="gradient">LocketGold Chính Chủ</span></h1>
                <div
                    style="background: linear-gradient(to right, rgba(167, 139, 250, 0.1), transparent); border-left: 4px solid var(--accent); padding: 18px 20px; border-radius: 0 12px 12px 0; margin-bottom: 28px; color: var(--text-0); font-size: 15px; line-height: 1.6; text-align: justify; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
                    Độc quyền tại <strong><a href="https://<?= $_SERVER['HTTP_HOST'] ?>"
                            style="color: var(--accent-bright); text-decoration: none; font-weight: 700;"><?= $_SERVER['HTTP_HOST'] ?></a></strong>
                    — Nền tảng tự động nâng cấp Locket Gold thông qua Username / Link kết bạn. Quy trình được mã hóa, tuyệt
                    đối an toàn và bảo vệ quyền riêng tư của khách hàng với <strong>cam kết 100% quy tắc 3 KHÔNG:</strong>
                </div>

                <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 32px;">
                    <!-- 1 -->
                    <div style="display: flex; align-items: flex-start; gap: 14px;">
                        <div
                            style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 50%; background: rgba(248,113,113,0.1); color: var(--red); flex-shrink: 0; margin-top: 2px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </div>
                        <div style="font-size: 15px; color: var(--text-0); line-height: 1.5;">
                            <strong style="color:var(--red); font-weight:700;">KHÔNG</strong> yêu cầu cài đặt App trung gian
                            hay phần mềm thứ ba.
                        </div>
                    </div>
                    <!-- 2 -->
                    <div style="display: flex; align-items: flex-start; gap: 14px;">
                        <div
                            style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 50%; background: rgba(248,113,113,0.1); color: var(--red); flex-shrink: 0; margin-top: 2px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </div>
                        <div style="font-size: 15px; color: var(--text-0); line-height: 1.5;">
                            <strong style="color:var(--red); font-weight:700;">KHÔNG</strong> yêu cầu đăng nhập iCloud hay
                            can thiệp vào thiết bị.
                        </div>
                    </div>
                    <!-- 3 -->
                    <div style="display: flex; align-items: flex-start; gap: 14px;">
                        <div
                            style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 50%; background: rgba(248,113,113,0.1); color: var(--red); flex-shrink: 0; margin-top: 2px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </div>
                        <div style="font-size: 15px; color: var(--text-0); line-height: 1.5;">
                            <strong style="color:var(--red); font-weight:700;">KHÔNG</strong> cần cung cấp tài khoản hay mật
                            khẩu Locket của bạn.
                        </div>
                    </div>
                </div>

                <div
                    style="display: inline-flex; align-items: flex-start; gap: 10px; font-size: 14px; font-weight: 500; color: var(--red); background: rgba(239, 68, 68, 0.08); padding: 12px 18px; border-radius: 12px; border: 1px dashed rgba(239, 68, 68, 0.3);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top:2px;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div>
                        <strong style="display:block; margin-bottom:4px; font-size: 15px;">Lưu ý quan trọng: Chỉ hỗ trợ hệ
                            sinh thái Apple (iPhone/iPad)</strong>
                        <span style="color:var(--text-2); line-height: 1.5; display:block;">Hệ thống cấp quyền trực tiếp qua
                            máy chủ gốc của Apple, đảm bảo an toàn tuyệt đối và hoàn toàn KHÔNG hỗ trợ thiết bị
                            Android.</span>
                    </div>
                </div>
            </div>
            <div class="hero-image-col">
                <img src="<?= htmlspecialchars($banner_path) ?>" alt="LocketGold App - Hệ Thống Locket Gold Số 1">
            </div>
        </div>

        <!-- Tiêu đề 8 Grid Đặc Quyền Locket Gold -->
        <div style="text-align:center; margin-top: 50px; margin-bottom: 32px;">
            <h2
                style="font-size: 26px; font-weight: 900; background: linear-gradient(135deg, #C084FC, #F472B6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 12px; letter-spacing: -0.5px; text-transform: uppercase;">
                Các Tính Năng Độc Quyền</h2>
            <p style="color: var(--text-2); font-size: 15px; max-width: 600px; margin: 0 auto; line-height: 1.6;">
                Nâng cấp trải nghiệm Locket của bạn lên một tầm cao mới với bộ công cụ mạnh mẽ và xịn xò nhất hiện
                nay.</p>
        </div>

        <?php
        $features = [
            [
                'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>',
                'color' => 'var(--accent-bright)',
                'title' => 'Mở khóa Locket Gold',
                'desc' => 'Kích hoạt vĩnh viễn mác Gold Premium cao cấp trên trang cá nhân.'
            ],
            [
                'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>',
                'color' => 'var(--red)',
                'title' => 'Không quảng cáo',
                'desc' => 'Trải nghiệm mượt mà, sạch bóng không một cọng quảng cáo khó chịu.'
            ],
            [
                'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>',
                'color' => 'var(--green)',
                'title' => 'Upload ảnh từ thư viện',
                'desc' => 'Lấy ảnh trực tiếp từ camera roll để gửi nhanh cho bạn bè thay vì chụp mới.'
            ],
            [
                'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>',
                'color' => '#F59E0B',
                'title' => 'Quay video Lockets 3s',
                'desc' => 'Ghi lại các đoạn clip chuyển động 3 giây sống động để lên bảng tin.'
            ],
            [
                'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>',
                'color' => '#3B82F6',
                'title' => 'Xem người đã xem Lockets',
                'desc' => 'Xem chính xác danh sách những ai đã mở và xem bài đăng của bạn.'
            ],
            [
                'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="4" ry="4"></rect><path d="M9 12h6"></path><path d="M12 9v6"></path></svg>',
                'color' => '#EC4899',
                'title' => 'Thay đổi icon Locket',
                'desc' => 'Làm mới màn hình chính với bộ icon ứng dụng Locket viền vàng Gold quyền lực.'
            ],
            [
                'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path></svg>',
                'color' => '#8B5CF6',
                'title' => 'Thay đổi theme Locket',
                'desc' => 'Cá nhân hóa màu sắc giao diện bên trong ứng dụng hoàn toàn theo sở thích.'
            ],
            [
                'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>',
                'color' => '#14B8A6',
                'title' => 'Mở khóa giới hạn bạn bè',
                'desc' => 'Xoá bỏ những rào cản, thêm vô số bạn bè xung quanh không bị giới hạn.'
            ]
        ];

        $features_row2 = array_merge(array_slice($features, 4), array_slice($features, 0, 4));

        $renderCard = function ($f) {
            return '
                    <div class="feature-card">
                        <div style="color:' . $f['color'] . '; margin-bottom:14px;">
                            ' . $f['icon'] . '
                        </div>
                        <h3 style="font-size:16px; font-weight:700; margin-bottom:8px; color:var(--text-0);">' . $f['title'] . '</h3>
                        <p style="font-size:13px; color:var(--text-2); line-height: 1.6; margin:0;">' . $f['desc'] . '</p>
                    </div>';
        };
        ?>

        <style>
            /* Marquee Layout Rules */
            .marquee-wrapper {
                display: flex;
                flex-direction: column;
                gap: 16px;
                margin-bottom: 20px;
                overflow: hidden;
                width: 100vw;
                margin-left: calc(-50vw + 50%);
                position: relative;
                padding: 10px 0;
            }

            /* Blur mask 2 bên để mượt */
            .marquee-wrapper::before,
            .marquee-wrapper::after {
                content: '';
                position: absolute;
                top: 0;
                bottom: 0;
                width: 120px;
                z-index: 2;
                pointer-events: none;
            }

            .marquee-wrapper::before {
                left: 0;
                background: linear-gradient(to right, var(--bg-0) 0%, transparent 100%);
            }

            .marquee-wrapper::after {
                right: 0;
                background: linear-gradient(to left, var(--bg-0) 0%, transparent 100%);
            }

            .marquee-track {
                display: flex;
                width: max-content;
            }

            .marquee-track.left {
                animation: scroll-left 40s linear infinite;
            }

            .marquee-track.right {
                animation: scroll-right 40s linear infinite;
            }

            .marquee-track:hover {
                animation-play-state: paused;
            }

            @keyframes scroll-left {
                0% {
                    transform: translateX(0);
                }

                100% {
                    transform: translateX(-50%);
                }
            }

            @keyframes scroll-right {
                0% {
                    transform: translateX(-50%);
                }

                100% {
                    transform: translateX(0);
                }
            }

            .marquee-wrapper .feature-card {
                background: linear-gradient(145deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.01) 100%);
                border: 1px solid rgba(255, 255, 255, 0.05);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                position: relative;
                overflow: hidden;
                z-index: 1;
                width: 280px;
                flex-shrink: 0;
                margin-right: 16px;
                padding: 24px;
                border-radius: var(--radius-md);
                text-align: left;
                animation: none !important;
            }

            .marquee-wrapper .feature-card::before {
                content: \'\';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.03), transparent 60%);
                pointer-events: none;
                z-index: -1;
            }

            .marquee-wrapper .feature-card:hover {
                transform: translateY(-8px);
                border-color: rgba(192, 132, 252, 0.5);
                box-shadow: 0 20px 40px rgba(192, 132, 252, 0.15), inset 0 0 0 1px rgba(192, 132, 252, 0.2);
                z-index: 10;
            }

            .marquee-wrapper .feature-card svg {
                filter: drop-shadow(0 0 6px currentColor);
                transition: all 0.3s ease;
            }

            .marquee-wrapper .feature-card:hover svg {
                filter: drop-shadow(0 0 12px currentColor);
                transform: scale(1.1) rotate(-3deg);
            }

            @media (max-width: 900px) {
                .marquee-wrapper .feature-card {
                    width: 240px;
                    padding: 18px;
                    margin-right: 12px;
                }
            }
        </style>

        <!-- 2 Marquee Tracks -->
        <div class="marquee-wrapper">
            <!-- Hàng trên: chạy sang trái -->
            <div class="marquee-track left">
                <?php
                foreach ($features as $f)
                    echo $renderCard($f);
                foreach ($features as $f)
                    echo $renderCard($f);
                ?>
            </div>

            <!-- Hàng dưới: chạy sang phải -->
            <div class="marquee-track right">
                <?php
                foreach ($features_row2 as $f)
                    echo $renderCard($f);
                foreach ($features_row2 as $f)
                    echo $renderCard($f);
                ?>
            </div>
        </div>

        <!-- Bộ Sưu Tập Ảnh Tính Năng -->
        <div style="text-align:center; margin-top: 40px; margin-bottom: 24px;">
            <h2 style="font-size: 22px; font-weight: 800; color: var(--text-0);">Trải Nghiệm Ưu Việt</h2>
            <p style="color: var(--text-2); font-size: 14px; margin-top: 8px;">Hình ảnh thực tế về các tính năng độc
                quyền trên ứng dụng</p>
        </div>

        <style>
            .feature-gallery {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 16px;
                margin-bottom: 40px;
            }

            .gallery-img-wrap {
                border-radius: var(--radius-lg);
                overflow: hidden;
                box-shadow: var(--shadow-soft);
                border: 1px solid var(--border);
                transition: var(--transition-smooth);
                background: var(--bg-1);
            }

            .gallery-img-wrap {
                border-radius: var(--radius-lg);
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
                border: 1px solid rgba(255, 255, 255, 0.05);
                transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                background: var(--bg-1);
                position: relative;
            }

            .gallery-img-wrap::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, rgba(167, 139, 250, 0.2), transparent);
                opacity: 0;
                transition: opacity 0.5s ease;
                pointer-events: none;
            }

            .gallery-img-wrap:hover {
                border-color: rgba(167, 139, 250, 0.5);
                transform: translateY(-10px) scale(1.02);
                box-shadow: 0 20px 50px rgba(167, 139, 250, 0.25), 0 0 20px rgba(244, 114, 182, 0.15);
                z-index: 2;
            }

            .gallery-img-wrap:hover::after {
                opacity: 1;
            }

            .gallery-img-wrap img {
                width: 100%;
                height: auto;
                display: block;
                object-fit: cover;
                transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }

            /* Trái nghiêng qua trái */
            .gallery-img-wrap:nth-child(3n+1):hover img {
                transform: scale(1.1) rotate(-3deg);
            }

            /* Giữa không nghiêng */
            .gallery-img-wrap:nth-child(3n+2):hover img {
                transform: scale(1.1) rotate(0deg);
            }

            /* Phải nghiêng qua phải */
            .gallery-img-wrap:nth-child(3n+3):hover img {
                transform: scale(1.1) rotate(3deg);
            }

            @media (max-width: 768px) {
                .feature-gallery {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 12px;
                }

                .gallery-img-wrap {
                    border-radius: 12px;
                }

                /* Cột lẻ (Trái) nghiêng trái, Cột chẵn (Phải) nghiêng phải trên màn hình 2 cột */
                .gallery-img-wrap:nth-child(odd):hover img {
                    transform: scale(1.1) rotate(-3deg);
                }

                .gallery-img-wrap:nth-child(even):hover img {
                    transform: scale(1.1) rotate(3deg);
                }
            }
        </style>

        <div class="feature-gallery">
            <div class="gallery-img-wrap"><img src="/uploads/locketgold.jpg" alt="Locket Gold" loading="lazy"></div>
            <div class="gallery-img-wrap"><img src="/uploads/cac_tinh_nang_glod.jpg" alt="Các Tính Năng Gold"
                    loading="lazy"></div>
            <div class="gallery-img-wrap"><img src="/uploads/khong_gioi_han_ban_be.jpg" alt="Không giới hạn bạn bè"
                    loading="lazy"></div>
            <div class="gallery-img-wrap"><img src="/uploads/quay_video.jpg" alt="Quay Video trên Locket" loading="lazy">
            </div>
            <div class="gallery-img-wrap"><img src="/uploads/icon_app.jpg" alt="Đổi Icon Ứng Dụng" loading="lazy">
            </div>
            <div class="gallery-img-wrap"><img src="/uploads/theme.jpg" alt="Đổi Theme Giao Diện" loading="lazy">
            </div>
        </div>


        <!-- Lưu ý nổi bật đỏ dưới cùng -->
        <div
            style="background: transparent; border: 1px dashed var(--accent); border-radius: var(--radius-md); padding: 18px; margin-bottom: 40px; text-align: center;">
            <div style="display:inline-flex; align-items:center; justify-content:center; gap:8px; margin-bottom:8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                <h4
                    style="color: var(--accent); font-size: 15px; font-weight: 700; margin: 0; letter-spacing: 0.5px; text-transform:uppercase;">
                    Cam kết quyền riêng tư</h4>
            </div>
            <p style="color: var(--text-2); font-size: 14.5px; margin: 0; line-height: 1.6;">
                Tài khoản của bạn sẽ được mở khóa toàn bộ các đặc quyền Premium. Xin lưu ý: Để đảm bảo quyền riêng
                tư tuyệt đối, hệ thống sẽ hoàn toàn không hiển thị Huy hiệu Vàng trên trang cá nhân của bạn cũng như
                với người dùng khác.
            </p>
        </div>

        <div style="display:flex; gap:16px; justify-content:center; flex-wrap:wrap;">
            <?php if ($current_user): ?>
                <a href="/cong-cu" class="btn btn-primary"
                    style="width:auto; padding:16px 36px; border-radius:var(--radius-full); font-size:16px; font-weight:700; box-shadow: 0 8px 32px var(--accent-glow); display:flex; align-items:center; gap:10px; transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position:relative; overflow:hidden;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                    </svg>
                    Tới Bảng Kích Hoạt Miễn Phí
                </a>
            <?php else: ?>
                <a href="/dang-nhap" class="btn btn-primary"
                    style="width:auto; padding:16px 36px; border-radius:var(--radius-full); font-size:16px; font-weight:700; box-shadow: 0 8px 32px var(--accent-glow); display:flex; align-items:center; gap:10px; transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position:relative; overflow:hidden;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5c-1.1 0-2 .9-2 2v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    Tạo Tài Khoản Miễn Phí
                </a>
            <?php endif; ?>
            <a href="/dich-vu-vip" class="btn btn-outline"
                style="width:auto; padding:16px 36px; border-radius:var(--radius-full); font-size:16px; font-weight:600; display:flex; align-items:center; gap:8px; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); color:var(--text-1); backdrop-filter:blur(10px); transition:all 0.3s;">
                Xem Bảng Giá VIP
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" style="margin-left:4px;">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>

        <div
            style="margin-top:24px; color:var(--text-2); font-size:13.5px; display:flex; align-items:center; justify-content:center; gap:8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <span>Hơn <strong style="color:var(--text-1);">109+</strong> người dùng đã nâng cấp trực tuyến hôm
                nay.</span>
        </div>

        <!-- 3 BƯỚC HOẠT ĐỘNG (CRO OPTIMIZED) -->
        <div style="margin-top: 80px; margin-bottom: 40px; text-align: center;">
            <h2 class="page-title" style="font-size: 26px; margin-bottom: 12px;">Sở Hữu Locket Gold Chỉ Với 3 Bước
            </h2>
            <p style="color: var(--text-2); font-size: 15px; max-width: 600px; margin: 0 auto;">Quá trình nâng cấp
                diễn ra hoàn toàn tự động trong chưa đầy 1 phút. Không rườm rà, không chờ đợi.</p>
        </div>

        <style>
            /* Nút CTA Animation */
            .btn-primary {
                position: relative;
                overflow: hidden;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
            }

            .btn-primary::after {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 50%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
                transform: skewX(-20deg);
                animation: sweepShimmer 3s infinite;
            }

            @keyframes sweepShimmer {
                0% {
                    left: -100%;
                }

                20% {
                    left: 200%;
                }

                100% {
                    left: 200%;
                }
            }

            .btn-primary:hover {
                transform: translateY(-5px) scale(1.05) !important;
                box-shadow: 0 15px 35px var(--accent-glow) !important;
            }

            /* 3 Bước Animation */
            .step-card {
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 24px;
                padding: 32px 24px;
                text-align: center;
                position: relative;
                backdrop-filter: blur(10px);
                transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                overflow: hidden;
                z-index: 1;
            }

            .step-card::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: conic-gradient(transparent, rgba(167, 139, 250, 0.5), transparent 30%);
                animation: rotateBeam 4s linear infinite;
                opacity: 0;
                transition: opacity 0.4s ease;
                z-index: -2;
            }

            .step-card::after {
                content: '';
                position: absolute;
                inset: 1px;
                background: var(--bg-1);
                border-radius: 23px;
                z-index: -1;
            }

            .step-card:hover::before {
                opacity: 1;
            }

            .step-card:hover {
                transform: translateY(-10px) scale(1.02);
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2), 0 0 30px rgba(167, 139, 250, 0.2);
                border-color: rgba(167, 139, 250, 0.4);
            }

            .step-number {
                width: 64px;
                height: 64px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                font-weight: 900;
                margin: 0 auto 20px;
                position: relative;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }

            .step-card:hover .step-number {
                transform: scale(1.2) rotate(10deg);
            }

            @keyframes rotateBeam {
                100% {
                    transform: rotate(1turn);
                }
            }
        </style>

        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 60px;">
            <!-- Bước 1 -->
            <div class="step-card">
                <div class="step-number"
                    style="background: rgba(167, 139, 250, 0.15); color: var(--accent-bright); box-shadow: 0 0 20px rgba(167, 139, 250, 0.2);">
                    1</div>
                <h3 style="font-size: 18px; font-weight: 800; color: var(--text-0); margin-bottom: 10px;">Đăng Ký Tài Khoản
                </h3>
                <p style="font-size: 14.5px; color: var(--text-2); line-height: 1.6; margin:0;">Tạo tài khoản miễn phí trên
                    hệ thống để bắt đầu quá trình đồng bộ dữ liệu.</p>
            </div>
            <!-- Bước 2 -->
            <div class="step-card">
                <div class="step-number"
                    style="background: rgba(244, 114, 182, 0.15); color: #F472B6; box-shadow: 0 0 20px rgba(244, 114, 182, 0.2);">
                    2</div>
                <h3 style="font-size: 18px; font-weight: 800; color: var(--text-0); margin-bottom: 10px;">Nhập ID Locket
                </h3>
                <p style="font-size: 14.5px; color: var(--text-2); line-height: 1.6; margin:0;">Cung cấp link trang cá nhân
                    Locket. Hệ thống sẽ tự động tìm nạp thông tin hoàn toàn ẩn danh.</p>
            </div>
            <!-- Bước 3 -->
            <div class="step-card">
                <div class="step-number"
                    style="background: rgba(52, 211, 153, 0.15); color: var(--green); box-shadow: 0 0 20px rgba(52, 211, 153, 0.2);">
                    3</div>
                <h3 style="font-size: 18px; font-weight: 800; color: var(--text-0); margin-bottom: 10px;">Tận Hưởng Premium
                </h3>
                <p style="font-size: 14.5px; color: var(--text-2); line-height: 1.6; margin:0;">Cài đặt Profile Bảo Vệ Nhận
                    Diện. Mở app và sử dụng ngay toàn bộ đặc quyền Gold vĩnh viễn.</p>
            </div>
        </div>

        <style>
            @keyframes pulseGlow {
                0% {
                    box-shadow: 0 0 0 0 rgba(167, 139, 250, 0.4);
                }

                70% {
                    box-shadow: 0 0 0 15px rgba(167, 139, 250, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(167, 139, 250, 0);
                }
            }

            .feature-card {
                background: var(--bg-1);
                border: 1px solid var(--border);
                padding: 24px;
                border-radius: var(--radius-md);
                box-shadow: var(--shadow-soft);
                transition: var(--transition-smooth);
                position: relative;
                overflow: hidden;
            }

            .feature-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 2px;
                background: linear-gradient(90deg, transparent, var(--accent-glow), transparent);
                opacity: 0;
                transition: opacity 0.3s;
            }

            .feature-card:hover {
                border-color: rgba(167, 139, 250, 0.15);
                transform: translateY(-4px);
                box-shadow: var(--shadow-card-hover), 0 0 20px rgba(167, 139, 250, 0.05);
            }

            .feature-card:hover::before {
                opacity: 1;
            }

            .feature-card svg {
                transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .feature-card:hover svg {
                transform: scale(1.15) rotate(5deg);
            }

            .feature-card:nth-child(1) {
                animation: cardIn 0.5s 0.1s ease both;
            }

            .feature-card:nth-child(2) {
                animation: cardIn 0.5s 0.2s ease both;
            }

            .feature-card:nth-child(3) {
                animation: cardIn 0.5s 0.3s ease both;
            }

            .feature-card:nth-child(4) {
                animation: cardIn 0.5s 0.4s ease both;
            }

            .comparison-table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
                margin-top: 32px;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
                border: 1px solid var(--border);
            }

            .comparison-table th,
            .comparison-table td {
                padding: 16px 20px;
                border-bottom: 1px solid var(--border);
            }

            .comparison-table th {
                background: var(--bg-2);
                font-size: 15px;
                font-weight: 700;
                color: var(--text-0);
            }

            .comparison-table td {
                font-size: 14.5px;
                color: var(--text-1);
                background: var(--bg-1);
                transition: all 0.3s ease;
            }

            .comparison-table tr:hover td {
                background: rgba(167, 139, 250, 0.1) !important;
                color: var(--text-0) !important;
            }

            .comparison-table tr:last-child td {
                border-bottom: none;
            }

            .check-icon {
                color: var(--green);
            }

            .x-icon {
                color: var(--text-2);
                opacity: 0.5;
            }

            .faq-item {
                background: var(--bg-1);
                border: 1px solid var(--border);
                border-radius: 12px;
                padding: 20px;
                margin-bottom: 16px;
                text-align: left;
                transition: var(--transition-smooth);
            }

            .faq-item:hover {
                border-color: rgba(167, 139, 250, 0.2);
                background: rgba(167, 139, 250, 0.02);
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            }

            .faq-item h4 {
                font-size: 16px;
                font-weight: 700;
                color: var(--text-0);
                margin-bottom: 8px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .faq-item p {
                font-size: 14.5px;
                color: var(--text-2);
                line-height: 1.6;
                margin: 0;
                text-align: justify;
            }
        </style>

        <!-- Bảng Đặc Quyền VIP -->
        <div style="margin-top: 80px; text-align: left;">
            <h2 class="page-title" style="margin-bottom: 16px;">Đặc Quyền Của Gói Locket Gold VIP</h2>
            <p
                style="text-align:justify; color:var(--text-2); margin-bottom: 32px; font-size:15px; max-width: 600px; margin-left: auto; margin-right: auto;">
                Trở thành thành viên VIP để mở khóa toàn bộ sức mạnh của Locket Gold, không giới hạn tính năng và trải
                nghiệm trọn vẹn nhất.</p>

            <div style="overflow-x: auto;">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th style="width: 70%">Tính Năng & Quyền Lợi</th>
                            <th
                                style="text-align:center; width: 30%; background:rgba(167,139,250,0.1); color:var(--accent-bright);">
                                Locket Gold VIP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Trải nghiệm toàn bộ tính năng Gold</strong> (Video, Icon, Đổi logo...)</td>
                            <td style="text-align:center; background:rgba(167,139,250,0.02);"><svg width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    class="check-icon">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg></td>
                        </tr>
                        <tr>
                            <td><strong>Thời hạn duy trì Premium</strong></td>
                            <td
                                style="text-align:center; font-weight:700; color:var(--accent-bright); background:rgba(167,139,250,0.02);">
                                Vĩnh viễn</td>
                        </tr>
                        <tr>
                            <td><strong>Cấu hình duy trì sự ổn định Premium (Anti-Revoke)</strong></td>
                            <td style="text-align:center; background:rgba(167,139,250,0.02);"><svg width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    class="check-icon">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg></td>
                        </tr>
                        <tr>
                            <td><strong>Hỗ trợ chuyển đổi thiết bị (Đổi sang iPhone/iPad khác)</strong></td>
                            <td style="text-align:center; background:rgba(167,139,250,0.02);"><svg width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    class="check-icon">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> Miễn phí đổi máy</td>
                        </tr>
                        <tr>
                            <td><strong>Hỗ trợ kỹ thuật ưu tiên</strong></td>
                            <td style="text-align:center; background:rgba(167,139,250,0.02);"><svg width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    class="check-icon">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg> Zalo/Telegram 24/7</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bộ Sưu Tập Đánh Giá (Testimonials) -->
        <div style="margin-top: 80px; text-align: center;">
            <h2 class="page-title" style="margin-bottom: 12px;">Khách Hàng Đánh Giá Locket Gold</h2>
            <p
                style="color: var(--text-2); font-size: 15px; margin-bottom: 32px; max-width: 600px; margin-left: auto; margin-right: auto;">
                Hàng ngàn người dùng tin tưởng vì tính năng bảo mật tuyệt đối, hoàn toàn tự động chỉ qua ID.</p>

            <style>
                .reviews-grid {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap: 16px;
                    text-align: left;
                }

                @media (max-width: 900px) {
                    .reviews-grid {
                        grid-template-columns: repeat(2, 1fr);
                        gap: 12px;
                    }
                }

                .review-card {
                    background: var(--bg-1);
                    border: 1px solid var(--border);
                    border-radius: 12px;
                    padding: 16px;
                    box-shadow: var(--shadow-soft);
                    transition: var(--transition-smooth);
                    position: relative;
                    overflow: hidden;
                }

                .review-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 3px;
                    background: linear-gradient(90deg, var(--accent), var(--accent-bright));
                    opacity: 0;
                    transition: opacity 0.3s;
                }

                .review-card:hover {
                    transform: translateY(-4px);
                    border-color: rgba(167, 139, 250, 0.25);
                    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.1);
                }

                .review-card:hover::before {
                    opacity: 1;
                }

                .review-stars {
                    color: #F59E0B;
                    margin-bottom: 10px;
                    display: flex;
                    gap: 2px;
                }

                .review-stars svg {
                    width: 14px;
                    height: 14px;
                }

                .review-text {
                    font-size: 13.5px;
                    color: var(--text-1);
                    line-height: 1.5;
                    margin-bottom: 16px;
                    font-style: italic;
                    position: relative;
                }

                .review-text::after {
                    content: '"';
                    position: absolute;
                    bottom: -16px;
                    right: 0;
                    font-size: 32px;
                    color: var(--border);
                    font-family: serif;
                    opacity: 0.5;
                    line-height: 1;
                }

                .review-author {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    border-top: 1px solid var(--border);
                    padding-top: 12px;
                }

                .review-avatar {
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, var(--accent), var(--accent-bright));
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #fff;
                    font-weight: 700;
                    font-size: 13px;
                    box-shadow: 0 2px 8px rgba(167, 139, 250, 0.3);
                }

                .review-info h5 {
                    margin: 0 0 2px 0;
                    font-size: 13px;
                    color: var(--text-0);
                    font-weight: 700;
                }

                .review-info span {
                    font-size: 11px;
                    color: var(--text-2);
                    display: flex;
                    align-items: center;
                    gap: 4px;
                }
            </style>

            <svg width="0" height="0" style="position:absolute;">
                <defs>
                    <linearGradient id="halfStarGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="50%" style="stop-color:currentColor; stop-opacity:1" />
                        <stop offset="50%" style="stop-color:transparent; stop-opacity:1" />
                    </linearGradient>
                </defs>
            </svg>

            <div class="reviews-grid">
                <!-- Review 1 -->
                <div class="review-card">
                    <div class="review-stars">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                    </div>
                    <div class="review-text">"Mới trải nghiệm hôm qua, ban đầu hơi lo lắng nhưng bên này chỉ yêu cầu
                        nhập Locket ID là hệ thống tự động xử lý, rất chuyên nghiệp và an toàn!"</div>
                    <div class="review-author">
                        <div class="review-avatar" style="background: linear-gradient(135deg, #F43F5E, #E11D48);">M
                        </div>
                        <div class="review-info">
                            <h5>Thảo My</h5>
                            <span><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                    stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg> VIP 1</span>
                        </div>
                    </div>
                </div>

                <!-- Review 2 -->
                <div class="review-card">
                    <div class="review-stars">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="url(#halfStarGrad)" stroke="currentColor" stroke-width="1">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                    </div>
                    <div class="review-text">"Dịch vụ uy tín, điểm cộng lớn là không yêu cầu mật khẩu bảo mật. Nhập
                        username là xong, ứng dụng hoạt động rất mượt mà."</div>
                    <div class="review-author">
                        <div class="review-avatar" style="background: linear-gradient(135deg, #10B981, #34D399);">K
                        </div>
                        <div class="review-info">
                            <h5>Hoàng Kim</h5>
                            <span><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                    stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg> Đại Lý</span>
                        </div>
                    </div>
                </div>

                <!-- Review 3 -->
                <div class="review-card">
                    <div class="review-stars">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                    </div>
                    <div class="review-text">"Hệ thống tự động cực kỳ nhanh, giao diện Premium rất đẹp. Tôi ưng nhất
                        khoản bảo mật, không hề yêu cầu thông tin iCloud cá nhân."</div>
                    <div class="review-author">
                        <div class="review-avatar" style="background: linear-gradient(135deg, #0EA5E9, #38BDF8);">B
                        </div>
                        <div class="review-info">
                            <h5>Đức Bảo</h5>
                            <span><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                    stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg> VIP 2</span>
                        </div>
                    </div>
                </div>

                <!-- Review 4 -->
                <div class="review-card">
                    <div class="review-stars">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                    </div>
                    <div class="review-text">"Quy trình chuẩn mực, tránh xa các bên đòi hỏi thông tin nhạy cảm. Hệ
                        thống tự động nâng cấp nhanh chóng, tôi rất an tâm."</div>
                    <div class="review-author">
                        <div class="review-avatar" style="background: linear-gradient(135deg, #F59E0B, #F97316);">Đ
                        </div>
                        <div class="review-info">
                            <h5>Tiến Đạt</h5>
                            <span><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                    stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg> VIP 1</span>
                        </div>
                    </div>
                </div>

                <!-- Review 5 -->
                <div class="review-card">
                    <div class="review-stars">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                    </div>
                    <div class="review-text">"Trải nghiệm bản quyền mượt mà hơn hẳn, không hề gặp tình trạng lỗi ứng
                        dụng. Chỉ thao tác một chạm là hoàn tất đăng ký VIP."</div>
                    <div class="review-author">
                        <div class="review-avatar" style="background: linear-gradient(135deg, #8B5CF6, #C084FC);">H
                        </div>
                        <div class="review-info">
                            <h5>Ngọc Hân</h5>
                            <span><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                    stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg> VIP 3</span>
                        </div>
                    </div>
                </div>

                <!-- Review 6 -->
                <div class="review-card">
                    <div class="review-stars">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="url(#halfStarGrad)" stroke="currentColor" stroke-width="1">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                    </div>
                    <div class="review-text">"Cảm ơn đội ngũ Locket Gold rất nhiều, tài khoản đã được nâng cấp.
                        Không cần mật khẩu điện thoại nên tôi hoàn toàn yên tâm sử dụng."</div>
                    <div class="review-author">
                        <div class="review-avatar" style="background: linear-gradient(135deg, #14B8A6, #2DD4BF);">TM
                        </div>
                        <div class="review-info">
                            <h5>Thanh Mai</h5>
                            <span><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                    stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg> VIP 2</span>
                        </div>
                    </div>
                </div>

                <!-- Review 7 -->
                <div class="review-card">
                    <div class="review-stars">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                    </div>
                    <div class="review-text">"Chi phí vô cùng hợp lý so với các tính năng nhận được. Hệ thống hoạt
                        động hoàn toàn độc lập, tăng tính bảo mật cho thiết bị người dùng."</div>
                    <div class="review-author">
                        <div class="review-avatar" style="background: linear-gradient(135deg, #F43F5E, #E11D48);">K
                        </div>
                        <div class="review-info">
                            <h5>Khang Nguyễn</h5>
                            <span><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                    stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg> VIP 1</span>
                        </div>
                    </div>
                </div>

                <!-- Review 8 -->
                <div class="review-card">
                    <div class="review-stars">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                    </div>
                    <div class="review-text">"Quy trình đơn giản đến mức ai cũng có thể tự thực hiện được. Chỉ cần
                        dán Username Locket vào là xong. Thực sự thuận tiện!"</div>
                    <div class="review-author">
                        <div class="review-avatar" style="background: linear-gradient(135deg, #F59E0B, #F97316);">T
                        </div>
                        <div class="review-info">
                            <h5>Bích Trâm</h5>
                            <span><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                    stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg> Đại Lý</span>
                        </div>
                    </div>
                </div>

                <!-- Review 9 -->
                <div class="review-card">
                    <div class="review-stars">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                    </div>
                    <div class="review-text">"Nâng cấp cho bạn bè cực kỳ dễ dàng. Hệ thống xử lý thông minh mà không
                        yêu cầu cung cấp bất kỳ thông tin nào nhạy cảm. Tuyệt vời!"</div>
                    <div class="review-author">
                        <div class="review-avatar" style="background: linear-gradient(135deg, #3B82F6, #2563EB);">S
                        </div>
                        <div class="review-info">
                            <h5>Hữu Sang</h5>
                            <span><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                    stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg> VIP 2</span>
                        </div>
                    </div>
                </div>

                <!-- Review 10 -->
                <div class="review-card">
                    <div class="review-stars">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                    </div>
                    <div class="review-text">"Đáng đồng tiền bát gạo, quan trọng nhất là quyền riêng tư thông tin
                        được bảo vệ tuyệt đối. Chỉ việc nhập ID là nâng cấp thành công."</div>
                    <div class="review-author">
                        <div class="review-avatar" style="background: linear-gradient(135deg, #10B981, #34D399);">T
                        </div>
                        <div class="review-info">
                            <h5>Minh Thư</h5>
                            <span><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                    stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg> VIP 1</span>
                        </div>
                    </div>
                </div>

                <!-- Review 11 -->
                <div class="review-card">
                    <div class="review-stars">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                    </div>
                    <div class="review-text">"Mình đánh giá 5 sao vì trải nghiệm ứng dụng vô cùng ổn định. Dịch vụ
                        độc lập 100%, không cần kết nối với tài khoản thiết bị cá nhân."</div>
                    <div class="review-author">
                        <div class="review-avatar" style="background: linear-gradient(135deg, #EC4899, #F472B6);">Q
                        </div>
                        <div class="review-info">
                            <h5>Lê Quyên</h5>
                            <span><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                    stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg> VIP 1</span>
                        </div>
                    </div>
                </div>

                <!-- Review 12 -->
                <div class="review-card">
                    <div class="review-stars">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="url(#halfStarGrad)" stroke="currentColor" stroke-width="1">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                    </div>
                    <div class="review-text">"Ứng dụng hoàn toàn sạch bóng quảng cáo, trải nghiệm cao cấp. Việc nâng
                        cấp chỉ thông qua ID thực sự quá đỗi tiện lợi và khoa học."</div>
                    <div class="review-author">
                        <div class="review-avatar" style="background: linear-gradient(135deg, #6366F1, #818CF8);">K
                        </div>
                        <div class="review-info">
                            <h5>Anh Khoa</h5>
                            <span><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--green)"
                                    stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg> VIP 3</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-top: 40px;">
            <style>
                .feedback-grid {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    gap: 16px;
                    margin-top: 32px;
                }

                .feedback-item {
                    flex: 0 0 calc(20% - 12.8px);
                    max-width: calc(20% - 12.8px);
                    aspect-ratio: 9/16;
                    border-radius: 12px;
                    overflow: hidden;
                    border: 1px solid var(--border);
                    box-shadow: var(--shadow-soft);
                }

                @media (max-width: 900px) {
                    .feedback-grid {
                        gap: 12px;
                    }

                    .feedback-item {
                        flex: 0 0 calc(50% - 6px);
                        max-width: calc(50% - 6px);
                    }
                }

                .feedback-item img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    cursor: pointer;
                    transition: transform 0.3s;
                }

                .feedback-item img:hover {
                    transform: scale(1.05);
                }

                #feedback-lightbox {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.85);
                    z-index: 999999;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                }

                #feedback-lightbox img {
                    max-width: 100%;
                    max-height: 90vh;
                    border-radius: 12px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
                }

                #feedback-lightbox .close-btn {
                    position: absolute;
                    top: 20px;
                    right: 20px;
                    color: white;
                    font-size: 32px;
                    cursor: pointer;
                    background: rgba(0, 0, 0, 0.5);
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border: 1px solid rgba(255, 255, 255, 0.2);
                }
            </style>
            <?php
            $fb_home = $pdo->query("SELECT * FROM feedbacks ORDER BY id DESC")->fetchAll();
            if (count($fb_home) > 0):
                ?>
                <div class="feedback-grid">
                    <?php foreach ($fb_home as $fb): ?>
                        <div class="feedback-item">
                            <img src="<?= htmlspecialchars($fb['image_url']) ?>" alt="Khách hàng feedback Locket Gold"
                                loading="lazy" onclick="openFeedbackLightbox(this.src)">
                        </div>
                    <?php endforeach; ?>
                </div>

                <div id="feedback-lightbox" onclick="closeFeedbackLightbox()">
                    <div class="close-btn">&times;</div>
                    <img id="feedback-lightbox-img" src="" alt="Feedback Detail" onclick="event.stopPropagation()">
                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const lightbox = document.getElementById('feedback-lightbox');
                        if (lightbox) {
                            document.body.appendChild(lightbox);
                        }
                    });

                    function openFeedbackLightbox(src) {
                        document.getElementById('feedback-lightbox-img').src = src;
                        document.getElementById('feedback-lightbox').style.display = 'flex';
                        document.body.style.overflow = 'hidden';
                    }
                    function closeFeedbackLightbox() {
                        document.getElementById('feedback-lightbox').style.display = 'none';
                        document.body.style.overflow = '';
                    }
                </script>
            <?php endif; ?>
        </div>

        <!-- FAQ Mới -->
        <div style="margin-top: 80px; text-align: left;">
            <h2 class="page-title" style="margin-bottom: 32px;">Câu Hỏi Thường Gặp (FAQ)</h2>

            <div class="faq-item">
                <h4><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg> Dịch vụ hỗ trợ trên nền tảng nào?</h4>
                <p>Hệ thống hiện tại dành riêng cho người dùng sử dụng hệ điều hành iOS (iPhone & iPad). Do cấu trúc
                    hệ thống của các nền tảng khác biệt, chúng tôi tập trung tối ưu hóa 100% trải nghiệm cho các
                    thiết bị Apple.</p>
            </div>

            <div class="faq-item">
                <h4><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg> Điều gì xảy ra khi tôi thay đổi thiết bị?</h4>
                <p>Trong quá trình duy trì trạng thái VIP, thông tin Locket ID của bạn được đồng bộ hoá. Khi đăng
                    nhập vào ứng dụng trên thiết bị mới, hệ thống sẽ tự động khôi phục toàn bộ các trải nghiệm
                    Premium cao cấp cho bạn.</p>
            </div>

            <div class="faq-item">
                <h4><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg> Tôi có cần chia sẻ mật khẩu cá nhân không?</h4>
                <p><strong>Hoàn toàn không.</strong> Quy trình đồng bộ hoá trạng thái chỉ thông qua định danh Locket
                    ID/Username công khai của tài khoản. Dữ liệu cá nhân nhạy cảm hay thông tin định danh Apple ID
                    của bạn sẽ không bao giờ được yêu cầu cung cấp.</p>
            </div>
        </div>
    </div>
<?php endif; ?>