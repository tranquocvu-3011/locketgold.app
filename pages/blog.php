<!-- ═══════ KINH NGHIỆM (BLOG & ARTICLE) ═══════ -->
        <?php if ($page === 'blog'): ?>
            <style>
                .blog-header {
                    text-align: center;
                    margin-bottom: 40px;
                }

                .blog-grid {
                    display: grid;
                    grid-template-columns: repeat(5, 1fr);
                    gap: 20px;
                }

                .blog-card {
                    background: var(--bg-1);
                    border: 1px solid var(--border);
                    border-radius: var(--radius-lg);
                    overflow: hidden;
                    transition: var(--transition-smooth);
                    text-decoration: none;
                    display: flex;
                    flex-direction: column;
                }

                .blog-card:hover {
                    transform: translateY(-4px);
                    border-color: var(--accent);
                    box-shadow: var(--shadow-card-hover);
                }

                .blog-thumb {
                    width: 100%;
                    height: 160px;
                    object-fit: cover;
                }

                .blog-info {
                    padding: 16px;
                    display: flex;
                    flex-direction: column;
                    flex: 1;
                }

                .blog-title {
                    font-size: 15px;
                    font-weight: 700;
                    color: var(--text-0);
                    margin-bottom: 8px;
                    line-height: 1.4;
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }

                .blog-excerpt {
                    font-size: 13px;
                    color: var(--text-2);
                    line-height: 1.5;
                    display: -webkit-box;
                    -webkit-line-clamp: 3;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                    margin-bottom: auto;
                }

                .blog-date {
                    font-size: 11px;
                    color: var(--text-2);
                    margin-top: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }

                @media (max-width: 1024px) {
                    .blog-grid {
                        grid-template-columns: repeat(3, 1fr);
                    }
                }

                @media (max-width: 768px) {
                    .blog-grid {
                        grid-template-columns: repeat(2, 1fr);
                        gap: 12px;
                    }

                    .blog-header {
                        margin-bottom: 24px;
                    }

                    .blog-thumb {
                        height: 120px;
                    }

                    .blog-info {
                        padding: 12px;
                    }

                    .blog-title {
                        font-size: 14px;
                    }

                    .blog-excerpt {
                        font-size: 12px;
                        -webkit-line-clamp: 2;
                    }
                }

                .page-link:hover {
                    background: rgba(255, 255, 255, 0.15) !important;
                    transform: translateY(-2px);
                }
            </style>

            <div class="blog-header">
                <div class="chip mb-sm" style="font-size:12px; padding:6px 14px; letter-spacing:0.5px;">Góc Chia Sẻ
                </div>
                <h1 class="page-title light">Kinh Nghiệm & Thủ Thuật</h1>
                <p style="color:var(--text-1); font-size:14.5px; max-width:600px; margin: 0 auto; line-height:1.6;">
                    Tuyển tập các bài viết hữu ích về siêu ứng dụng Locket, cách sử dụng các bộ lọc độc quyền, tính năng
                    VIP
                    đặc quyền và hướng dẫn chụp ảnh lấy ngay siêu mượt.
                </p>
            </div>

            <div class="blog-grid">
                <?php
                try {
                    $page_num = max(1, intval($_GET['p'] ?? 1));
                    $limit = 20;
                    $offset = ($page_num - 1) * $limit;

                    $total_articles = $pdo->query("SELECT COUNT(*) FROM articles WHERE is_published = 1")->fetchColumn();
                    $total_pages = ceil($total_articles / $limit);

                    $query = "SELECT * FROM articles WHERE is_published = 1 ORDER BY id DESC LIMIT $limit OFFSET $offset";
                    $arts = $pdo->query($query)->fetchAll();

                    foreach ($arts as $a):
                        ?>
                        <a href="/bai-viet?slug=<?= urlencode($a['slug']) ?>" class="blog-card">
                            <img src="<?= htmlspecialchars($a['thumbnail'] ?: '/banner.png') ?>"
                                alt="<?= htmlspecialchars($a['title']) ?>" class="blog-thumb" loading="lazy">
                            <div class="blog-info">
                                <div class="blog-title"><?= htmlspecialchars($a['title']) ?></div>
                                <div class="blog-excerpt"><?= htmlspecialchars($a['excerpt']) ?></div>
                                <div class="blog-date"><?= date('d/m/Y', strtotime($a['created_at'])) ?></div>
                            </div>
                        </a>
                    <?php endforeach;
                } catch (Exception $e) {
                } ?>
            </div>

            <div class="pagination"
                style="display:flex; justify-content:center; gap:8px; margin-top:40px; padding-bottom:40px; flex-wrap:wrap;">
                <?php
                if (isset($total_pages) && $total_pages > 1) {
                    $base_btn_style = 'padding:10px 16px; border-radius:12px; background:var(--bg-1); border: 1px solid var(--border); color:var(--text-1); text-decoration:none; display:inline-flex; align-items:center; transition:0.3s; font-weight:600; font-size:14px;';

                    if ($page_num > 1) {
                        echo '<a href="/kinh-nghiem?p=' . ($page_num - 1) . '" class="page-link" style="' . $base_btn_style . '">&laquo; Qua</a>';
                    }

                    $start = max(1, $page_num - 2);
                    $end = min($total_pages, $page_num + 2);

                    if ($start > 1) {
                        echo '<a href="/kinh-nghiem?p=1" class="page-link" style="' . $base_btn_style . '">1</a>';
                        if ($start > 2) {
                            echo '<span style="color:var(--text-2); padding:10px 8px;">...</span>';
                        }
                    }

                    for ($i = $start; $i <= $end; $i++) {
                        $active_style = ($i == $page_num) ? 'background:var(--primary); color:#fff; border-color:var(--primary); box-shadow:0 8px 20px rgba(var(--primary-rgb),0.3);' : '';
                        $class = ($i == $page_num) ? '' : 'page-link';
                        echo '<a href="/kinh-nghiem?p=' . $i . '" class="' . $class . '" style="' . $base_btn_style . ' ' . $active_style . '">' . $i . '</a>';
                    }

                    if ($end < $total_pages) {
                        if ($end < $total_pages - 1) {
                            echo '<span style="color:var(--text-2); padding:10px 8px;">...</span>';
                        }
                        echo '<a href="/kinh-nghiem?p=' . $total_pages . '" class="page-link" style="' . $base_btn_style . '">' . $total_pages . '</a>';
                    }

                    if ($page_num < $total_pages) {
                        echo '<a href="/kinh-nghiem?p=' . ($page_num + 1) . '" class="page-link" style="' . $base_btn_style . '">Lại &raquo;</a>';
                    }
                }
                ?>
            </div>
        <?php endif; ?>