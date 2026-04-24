<!-- ═══════ SINGLE ARTICLE ═══════ -->
        <?php if ($page === 'article'):
            $slug = $_GET['slug'] ?? '';
            $article = null;
            try {
                $st = $pdo->prepare("SELECT * FROM articles WHERE slug = ? AND is_published = 1");
                $st->execute([$slug]);
                $article = $st->fetch();
                if ($article) {
                    $pdo->prepare("UPDATE articles SET views = views + 1 WHERE id = ?")->execute([$article['id']]);
                }
            } catch (Exception $e) {
            }
            if ($article):
                ?>
                <style>
                    .article-wrap {
                        max-width: var(--layout-max);
                        margin: 0 auto;
                        width: 100%;
                    }

                    .article-inner {
                        display: grid;
                        grid-template-columns: 360px 1fr;
                        gap: 48px;
                        align-items: start;
                        margin-bottom: 60px;
                    }

                    .article-left {
                        position: sticky;
                        top: 100px;
                    }

                    .article-banner {
                        width: 100%;
                        border-radius: 20px;
                        box-shadow: var(--shadow-sm);
                        display: block;
                        border: 1px solid var(--border);
                        object-fit: cover;
                    }

                    .article-right {
                        background: var(--bg-1);
                        border: 1px solid var(--border);
                        border-radius: 24px;
                        box-shadow: var(--shadow-sm);
                        padding: 48px;
                    }

                    .article-hero {
                        margin-bottom: 24px;
                        border-bottom: 1px dashed var(--border);
                        padding-bottom: 24px;
                    }

                    .article-hero h1 {
                        font-size: 26px;
                        font-weight: 800;
                        color: var(--text-0);
                        line-height: 1.35;
                        margin-bottom: 16px;
                    }

                    .article-meta {
                        display: flex;
                        flex-wrap: wrap;
                        align-items: center;
                        gap: 16px;
                        color: var(--text-2);
                        font-size: 13px;
                    }

                    .article-content {
                        font-size: 16px;
                        color: var(--text-1);
                        line-height: 1.8;
                    }

                    .article-content img {
                        display: none !important;
                    }

                    .article-content h2,
                    .article-content h3 {
                        color: var(--text-0);
                        margin-top: 32px;
                        margin-bottom: 16px;
                        font-weight: 700;
                        line-height: 1.4;
                    }

                    .article-content h2 {
                        font-size: 22px;
                    }

                    .article-content h3 {
                        font-size: 18px;
                    }

                    .article-content p {
                        margin-bottom: 18px;
                        text-align: justify;
                        text-justify: inter-word;
                    }

                    @media (max-width: 900px) {
                        .article-inner {
                            grid-template-columns: 1fr;
                            gap: 24px;
                        }

                        .article-left {
                            position: relative;
                            top: 0;
                            max-width: 320px;
                            margin: 0 auto;
                        }

                        .article-right {
                            padding: 24px 20px;
                        }

                        .article-hero h1 {
                            font-size: 22px;
                        }
                    }
                </style>

                <div class="article-wrap">
                    <a href="/kinh-nghiem" class="back-link"
                        style="display:inline-flex; align-items:center; gap:8px; color:var(--text-2); text-decoration:none; font-size:14px; margin-bottom:24px; font-weight:600;"><svg
                            width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 18l-6-6 6-6" />
                        </svg>Quay lại &amp; Xem tất cả bài viết</a>
                    <div class="article-inner">
                        <div class="article-left">
                            <?php if ($article['thumbnail']): ?>
                                <img src="<?= htmlspecialchars($article['thumbnail']) ?>"
                                    alt="<?= htmlspecialchars($article['title']) ?>" class="article-banner">
                            <?php endif; ?>
                        </div>
                        <div class="article-right card card-wide">
                            <div class="article-hero">
                                <h1><?= htmlspecialchars($article['title']) ?></h1>
                                <div class="article-meta"><span>Locket Gold
                                        Admin</span><span>•</span><span><?= date('d/m/Y', strtotime($article['created_at'])) ?></span><span>•</span><span><?= number_format($article['views'] + 1) ?>
                                        lượt xem</span></div>
                            </div>
                            <div class="article-content"><?= $article['content'] ?></div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div style="text-align:center; padding: 100px 20px;">
                    <h1 style="font-size:24px; margin-bottom:16px;">Bài viết không tồn tại</h1><a href="/kinh-nghiem"
                        class="btn btn-primary">Về trang Kinh nghiệm</a>
                </div>
            <?php endif; endif; ?>