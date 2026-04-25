<?php
if (!defined('IN_APP')) exit;

header('Content-Type: application/xml; charset=utf-8');
$site_domain = "https://" . $_SERVER['HTTP_HOST'];
$date = date('Y-m-d\TH:i:sP');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

$static_pages = [
    'trang-chu' => 1.0,
    'huong-dan' => 0.9,
    'dich-vu-vip' => 0.9,
    'lien-he' => 0.8,
    'kinh-nghiem' => 0.8,
    'shadowrocket' => 0.9,
    'dang-nhap' => 0.6,
];

foreach ($static_pages as $slug => $priority) {
    echo "  <url>\n";
    echo "    <loc>{$site_domain}/{$slug}</loc>\n";
    echo "    <lastmod>{$date}</lastmod>\n";
    echo "    <changefreq>daily</changefreq>\n";
    echo "    <priority>{$priority}</priority>\n";
    echo "  </url>\n";
}

try {
    $stmt = $pdo->query("SELECT slug, updated_at FROM articles WHERE status = 'published' ORDER BY created_at DESC LIMIT 1000");
    while ($row = $stmt->fetch()) {
        $mod_date = date('Y-m-d\TH:i:sP', strtotime($row['updated_at']));
        echo "  <url>\n";
        echo "    <loc>{$site_domain}/bai-viet?slug=" . htmlspecialchars($row['slug']) . "</loc>\n";
        echo "    <lastmod>{$mod_date}</lastmod>\n";
        echo "    <changefreq>weekly</changefreq>\n";
        echo "    <priority>0.7</priority>\n";
        echo "  </url>\n";
    }
} catch (Exception $e) {
    // Ignore db errors in sitemap
}

echo "</urlset>\n";
