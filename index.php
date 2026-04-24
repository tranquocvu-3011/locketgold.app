<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
error_reporting(0);
ini_set('display_errors', '0');
define('IN_APP', true);
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();
require_once __DIR__ . '/config/database.php';
if (!file_exists(__DIR__ . '/.installed')) { require_once __DIR__ . '/migrate.php'; file_put_contents(__DIR__ . '/.installed', time()); }
// Fetch all settings
$settings = [];
try {
    $r = $pdo->query("SELECT setting_key, setting_value FROM global_settings")->fetchAll(PDO::FETCH_KEY_PAIR);
    if ($r)
        $settings = $r;
} catch (\Throwable $e) {}
// --- Agency Sub-site / White-label Logic ---
$current_host = $_SERVER['HTTP_HOST'] ?? '';
$current_host_domain = explode(':', $current_host)[0];
$domain_agency = null;

try {
    if (!empty($current_host_domain)) {
        $stmtDomain = $pdo->prepare("SELECT agency_username FROM agency_settings WHERE domain_name = ? OR domain_name = ? LIMIT 1");
        $stmtDomain->execute([$current_host_domain, str_replace('www.', '', $current_host_domain)]);
        $domain_agency = $stmtDomain->fetchColumn();
    }
} catch (\Throwable $e) {}

if (isset($_GET['ref'])) {
    $ref = trim($_GET['ref']);
    setcookie('agency_ref', $ref, time() + 86400 * 30, '/');
    $_COOKIE['agency_ref'] = $ref;
}

$current_agency = $domain_agency ?: ($_COOKIE['agency_ref'] ?? null);
if ($current_agency) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM agency_settings WHERE agency_username = ?");
        $stmt->execute([$current_agency]);
        $agency_settings = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($agency_settings) {
            // Override global settings with agency settings if they exist
            $override_keys = ['site_name', 'bank_code', 'bank_account', 'bank_owner', 'price_vip1', 'price_vip2', 'price_vip3', 'price_vip4', 'price_agency'];
            foreach ($override_keys as $k) {
                if (!empty($agency_settings[$k])) {
                    $settings[$k] = $agency_settings[$k];
                }
            }
        }
    } catch (\Throwable $e) {}
}
// -------------------------------------------



$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$route = trim(basename($uri));
$pages = ['trang-chu' => 'home', 'huong-dan' => 'guide', 'dich-vu-vip' => 'vip', 'dang-nhap' => 'auth', 'cong-cu' => 'tool', 'lich-su' => 'history', 'admin' => 'admin', 'logout' => 'logout', 'download-dns' => 'download-dns', 'lien-he' => 'contact', 'kinh-nghiem' => 'blog', 'bai-viet' => 'article', 'thanh-toan' => 'payment', 'tao-web-con' => 'agency-setup', 'quan-ly-tai-khoan' => 'account'];
if ($route !== '' && $route !== 'index.php' && !isset($pages[$route])) {
    header("Location: /trang-chu");
    exit;
}
$page = ($route == '' || $route == 'index.php') ? 'home' : $pages[$route];

$action = $_POST['action'] ?? '';

$auth_msg = '';
$auth_msg_type = 'error';
$inject_status = 'idle';
$inject_msg = '';
$injected_uid = '';
$toast_queue = [];
$dns_file = 'LocketGold_Premium_DNS.mobileconfig';

// ═══════ CSRF TOKEN ═══════
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
}

function queueToast(&$queue, $message, $type = 'info', $duration = 4200)
{
    $text = trim((string) $message);
    if ($text === '') {
        return;
    }
    $allowed = ['success', 'error', 'info', 'warning'];
    if (!in_array($type, $allowed, true)) {
        $type = 'info';
    }
    $queue[] = [
        'type' => $type,
        'message' => $text,
        'duration' => max(2200, (int) $duration),
    ];
}

function normalizeRoleValue($role)
{
    $role = strtolower(trim((string) $role));
    return $role === 'vip' ? 'vip1' : $role;
}

function getRoleExpiryAt($role)
{
    $role = normalizeRoleValue($role);
    return in_array($role, ['user', 'admin'], true) ? null : date('Y-m-d H:i:s', strtotime('+1 year'));
}

function getVipPricingMap(array $settings)
{
    return [
        'user' => 0,
        'vip1' => (int) ($settings['price_vip1'] ?? 59000),
        'vip2' => (int) ($settings['price_vip2'] ?? 79000),
        'vip3' => (int) ($settings['price_vip3'] ?? 99000),
        'vip4' => (int) ($settings['price_vip4'] ?? 149000),
        'agency' => (int) ($settings['price_agency'] ?? 299000),
    ];
}

function inferCheckoutTargetFromLegacyPlan($plan)
{
    $plan = trim((string) $plan);
    if ($plan === '') {
        return null;
    }
    if (stripos($plan, 'agency') !== false || stripos($plan, 'đại lý') !== false) {
        return 'agency';
    }
    if (stripos($plan, 'VIP 4') !== false) {
        return 'vip4';
    }
    if (stripos($plan, 'VIP 3') !== false) {
        return 'vip3';
    }
    if (stripos($plan, 'VIP 2') !== false) {
        return 'vip2';
    }
    if (stripos($plan, 'VIP 1') !== false) {
        return 'vip1';
    }
    return null;
}

function resolveCheckoutSelection(array $settings, $currentRole, $requestedTarget)
{
    $currentRole = normalizeRoleValue($currentRole);
    $targetRole = normalizeRoleValue($requestedTarget);
    $prices = getVipPricingMap($settings);
    $roleOrder = ['user', 'vip1', 'vip2', 'vip3', 'vip4'];
    $planLabels = [
        'vip1' => 'VIP 1 Cá nhân',
        'vip2' => 'VIP 2 Cặp Đôi',
        'vip3' => 'VIP 3 Gia Đình',
        'vip4' => 'VIP 4 Cao Cấp',
        'agency' => 'Gói Đại Lý (Agency)',
    ];
    $shortLabels = [
        'vip1' => 'VIP 1',
        'vip2' => 'VIP 2',
        'vip3' => 'VIP 3',
        'vip4' => 'VIP 4',
        'agency' => 'Đại Lý',
    ];

    if (!isset($planLabels[$targetRole])) {
        return null;
    }

    if ($targetRole === 'agency') {
        if (in_array($currentRole, ['agency', 'admin'], true)) {
            return null;
        }
        return [
            'target_role' => 'agency',
            'target_label' => $shortLabels['agency'],
            'plan_label' => $planLabels['agency'],
            'amount' => (int) ($prices['agency'] ?? 0),
            'kind' => 'agency',
        ];
    }

    $currentIdx = array_search($currentRole, $roleOrder, true);
    $targetIdx = array_search($targetRole, $roleOrder, true);
    if ($currentIdx === false || $targetIdx === false || $targetIdx <= $currentIdx) {
        return null;
    }

    $currentPaid = (int) ($prices[$currentRole] ?? 0);
    return [
        'target_role' => $targetRole,
        'target_label' => $shortLabels[$targetRole],
        'plan_label' => $planLabels[$targetRole],
        'amount' => max(0, (int) ($prices[$targetRole] ?? 0) - $currentPaid),
        'kind' => $currentPaid > 0 ? 'upgrade' : 'new',
    ];
}

function validateImageUpload(array $file, array $allowedExts, array $allowedMimes)
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'Tệp tải lên không hợp lệ.'];
    }

    $tmpName = $file['tmp_name'] ?? '';
    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
        return ['ok' => false, 'error' => 'Không xác minh được tệp tải lên.'];
    }

    $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExts, true)) {
        return ['ok' => false, 'error' => 'Định dạng file không được hỗ trợ.'];
    }

    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = $finfo ? finfo_file($finfo, $tmpName) : '';
        if ($finfo) {
            finfo_close($finfo);
        }
        if ($mime && !in_array($mime, $allowedMimes, true)) {
            return ['ok' => false, 'error' => 'Nội dung file không đúng định dạng ảnh hợp lệ.'];
        }
    }

    return ['ok' => true, 'ext' => $ext];
}

$current_user = $_SESSION['user'] ?? null;
$current_role = 'user';
$base_theme = 'user';
if ($current_user) {
    $stmt = $pdo->prepare("SELECT role, role_expires_at, is_vip_notified FROM users WHERE username = ?");
    $stmt->execute([$current_user]);
    $uinfo = $stmt->fetch();
    if ($uinfo) {
        $current_role = normalizeRoleValue($uinfo['role']);
        if ($uinfo['role_expires_at'] && strtotime($uinfo['role_expires_at']) < time() && $current_role !== 'admin') {
            $current_role = 'user'; // Tự hủy, giáng cấp về Thành viên
            $pdo->prepare("UPDATE users SET role = 'user', role_expires_at = NULL WHERE username = ?")->execute([$current_user]);
            $_SESSION['error_msg'] = "Gói dịch vụ của bạn đã hết hạn. Vui lòng nâng cấp để tiếp tục.";
            header("Location: /trang-chu");
            exit;
        }

        if ($current_role !== 'user' && isset($uinfo['is_vip_notified']) && $uinfo['is_vip_notified'] == 0) {
            $show_vip_upgrade_modal = true;
            $pdo->prepare("UPDATE users SET is_vip_notified = 1 WHERE username = ?")->execute([$current_user]);
        }
    }
}
if (strpos($current_role, 'vip') !== false)
    $base_theme = 'vip';
elseif ($current_role === 'agency')
    $base_theme = 'agency';
elseif ($current_role === 'admin')
    $base_theme = 'admin';
$is_vip_or_higher = in_array($current_role, ['vip1', 'vip2', 'vip3', 'vip4', 'agency', 'admin'], true);

// ═══════ CSRF VALIDATION cho index.php action handlers ═══════
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($action)) {
    $submitted_csrf = $_POST['csrf_token'] ?? '';
    if (empty($submitted_csrf) || empty($csrf_token) || !hash_equals($csrf_token, $submitted_csrf)) {
        // Skip — actions.php sẽ validate lần nữa cho các action khác
        // Nhưng nếu action xử lý ở index.php, cần block
        $index_actions = [
            'upload_receipt',
            'admin_set_receipt_role',
            'admin_del_receipt',
            'admin_save_branding',
            'admin_add_article',
            'admin_edit_article',
            'admin_delete_article',
            'delete_activation',
            'admin_add_fund',
            'admin_save_payment_settings',
            'agency_save_domain',
            'agency_save_settings',
            'admin_upload_feedback',
            'admin_delete_feedback'
        ];
        if (in_array($action, $index_actions)) {
            die("Bảo mật hệ thống: Token CSRF không hợp lệ. Vui lòng tải lại trang và thử lại.");
        }
    }
}

require_once __DIR__ . '/controllers/IndexActions.php';
require_once __DIR__ . '/includes/actions.php';

$had_session_toasts = !empty($_SESSION['toast_flash']) && is_array($_SESSION['toast_flash']);

if (!empty($_SESSION['toast_flash']) && is_array($_SESSION['toast_flash'])) {
    foreach ($_SESSION['toast_flash'] as $toast) {
        queueToast(
            $toast_queue,
            $toast['message'] ?? '',
            $toast['type'] ?? 'info',
            $toast['duration'] ?? 4200
        );
    }
    unset($_SESSION['toast_flash']);
}

if (!empty($_SESSION['error_msg'])) {
    queueToast($toast_queue, $_SESSION['error_msg'], 'warning', 5200);
    unset($_SESSION['error_msg']);
}

if ($auth_msg !== '' && !($auth_msg_type === 'success' && $had_session_toasts)) {
    queueToast($toast_queue, $auth_msg, $auth_msg_type === 'success' ? 'success' : 'error', 4200);
}

if ($page === 'download-dns') {
    if ($is_vip_or_higher) {
        if (file_exists($dns_file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/x-apple-aspen-config');
            header('Content-Disposition: attachment; filename="' . basename($dns_file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Content-Length: ' . filesize($dns_file));
            readfile($dns_file);
            exit;
        } else
            die("File không tồn tại.");
    } else {
        $_SESSION['toast_flash'] = $_SESSION['toast_flash'] ?? [];
        $_SESSION['toast_flash'][] = [
            'type' => 'warning',
            'message' => 'Bạn cần nâng cấp VIP để tải Profile DNS.',
            'duration' => 4200,
        ];
        header("Location: /trang-chu?success=receipt");
        exit;
    }
}
if ($page === 'logout') {
    session_destroy();
    header("Location: /trang-chu");
    exit;
}

// ═══════ DYNAMIC SITEMAP GENERATOR ═══════
if ($route === 'sitemap.xml') {
    header('Content-Type: application/xml; charset=UTF-8');
    $domain = 'https://' . $_SERVER['HTTP_HOST'];
    $today = date('Y-m-d');

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    // Trang tĩnh
    $static_pages = [
        ['loc' => '/trang-chu', 'freq' => 'weekly', 'priority' => '1.0'],
        ['loc' => '/huong-dan', 'freq' => 'monthly', 'priority' => '0.8'],
        ['loc' => '/dich-vu-vip', 'freq' => 'weekly', 'priority' => '0.9'],
        ['loc' => '/lien-he', 'freq' => 'monthly', 'priority' => '0.6'],
        ['loc' => '/kinh-nghiem', 'freq' => 'daily', 'priority' => '0.8'],
    ];
    foreach ($static_pages as $sp) {
        $xml .= "  <url>\n";
        $xml .= "    <loc>{$domain}{$sp['loc']}</loc>\n";
        $xml .= "    <lastmod>{$today}</lastmod>\n";
        $xml .= "    <changefreq>{$sp['freq']}</changefreq>\n";
        $xml .= "    <priority>{$sp['priority']}</priority>\n";
        $xml .= "  </url>\n";
    }

    // Bài viết từ database
    try {
        $arts = $pdo->query("SELECT slug, updated_at FROM articles WHERE is_published = 1 ORDER BY updated_at DESC")->fetchAll();
        foreach ($arts as $a) {
            $lastmod = date('Y-m-d', strtotime($a['updated_at']));
            $slug_enc = htmlspecialchars(urlencode($a['slug']));
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$domain}/bai-viet?slug={$slug_enc}</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.6</priority>\n";
            $xml .= "  </url>\n";
        }
    } catch (\Throwable $e) {}
    $xml .= '</urlset>';
    echo $xml;
    exit;
}

// Role label helper
function getRoleLabel($role)
{
    $labels = [
        'user' => 'Thành viên',
        'vip1' => 'VIP 1',
        'vip2' => 'VIP 2',
        'vip3' => 'VIP 3',
        'vip4' => 'VIP 4',
        'agency' => 'Đại lý',
        'admin' => 'Admin'
    ];
    return $labels[$role] ?? strtoupper($role);
}
function getRoleQuota($role)
{
    $q = [
        'user' => 'Chưa kích hoạt',
        'vip1' => '1 ID',
        'vip2' => '2 ID',
        'vip3' => '3 ID',
        'vip4' => '10 ID',
        'agency' => 'Không giới hạn',
        'admin' => 'Không giới hạn'
    ];
    return $q[$role] ?? '0 ID';
}


// ═══════ DYNAMIC SEO ENGINE ═══════
$site_name = !empty($settings['site_name']) ? $settings['site_name'] : "Locket Gold";
$site_domain = "https://" . $_SERVER['HTTP_HOST'];
$seo = [
    "home" => [
        "title" => "{$site_name} | Hệ Thống Tải & Nâng Cấp App Locket Gold #1",
        "desc" => "Nền tảng {$site_name} tự động nâng cấp Locket Gold Premium cực nhanh. Trải nghiệm app {$site_name} tiện lợi, locket không cần icloud, bảo mật 100%.",
        "keywords" => "locketgold, locket gold, {$site_name} app, app {$site_name}, tai {$site_name}, lóc két gold, locket premium, locket không cần icloud",
    ],
    "guide" => [
        "title" => "Hướng Dẫn Kích Hoạt Lóc Két Gold Chi Tiết - {$site_name}",
        "desc" => "Tự tay cài đặt và kích hoạt Locket Gold Premium cực kỳ dễ dàng. Hướng dẫn chi tiết cách cài cấu hình bảo mật locket không cần icloud hay các thao tác phức tạp.",
        "keywords" => "hướng dẫn {$site_name}, lóc két gold, cài đặt {$site_name}, locket không cần icloud, locket gold không cần shadownrocket, lỗi locket gold",
    ],
    "vip" => [
        "title" => "Bảng Giá Locket Gold VIP Cao Cấp — {$site_name}",
        "desc" => "Trải nghiệm hệ thống {$site_name} phiên bản VIP siêu tốc độ. Đăng ký {$site_name} chính hãng vĩnh viễn, dùng locket gold không cần shadownrocket, bảo hành 1 đổi 1.",
        "keywords" => "mua locket vip {$site_name}, locketgold, lóc két gold, mua locket gold vĩnh viễn, locket gold không cần shadownrocket, thuê dns locket",
    ],
    "contact" => [
        "title" => "Liên Hệ Hỗ Trợ Kỹ Thuật Locket — {$site_name}",
        "desc" => "Tư vấn khắc phục lỗi ứng dụng Locket. Đội ngũ chuyên viên hỗ trợ {$site_name} luôn sẵn sàng 24/7. Hỗ trợ locket không cần icloud dành cho thiết bị mới.",
        "keywords" => "liên hệ {$site_name}, hỗ trợ lóc két gold, locketgold, sửa lỗi locket gold, locket không cần icloud, locket việt nam",
    ],
    "auth" => [
        "title" => "Đăng Nhập Hệ Thống {$site_name}",
        "desc" => "Đăng nhập vào hệ thống {$site_name} để thao tác quản lý gói locketgold của bạn. Cam kết bảo mật, locket không cần icloud.",
        "keywords" => "đăng nhập {$site_name}, tài khoản locket vip, locketgold, lóc két gold",
    ],
    "tool" => [
        "title" => "Công Cụ Inject Locket Premium — {$site_name}",
        "desc" => "Công cụ độc quyền Inject lóc két gold cực nhanh. Quét và cấp chứng chỉ trực tiếp, locket gold không cần shadownrocket. An toàn 100%.",
        "keywords" => "kích hoạt lóc két gold, tool {$site_name}, locketgold, locket gold không cần shadownrocket, inject locket premium",
    ],
    "blog" => [
        "title" => "Danh Mục Mẹo Vặt & Thủ Thuật {$site_name}",
        "desc" => "Tổng hợp các mẹo xài locketgold thông minh, hướng dẫn dùng locket gold không cần shadownrocket, và chia sẻ lóc két gold từ {$site_name}.",
        "keywords" => "mẹo {$site_name}, thủ thuật locketgold, lóc két gold, locket không cần icloud, locket gold không cần shadownrocket, tin tức locket",
    ],
    "agency-setup" => [
        "title" => "Tạo Website Con Đại Lý Bán Hàng — {$site_name}",
        "desc" => "Trở thành Đại lý chính thức của {$site_name}, sở hữu ngay một website con giống 100% trang chủ và bắt đầu kinh doanh tự động.",
        "keywords" => "đại lý {$site_name}, tạo web con, thiết kế website, đại lý locket gold, kiếm tiền online",
    ],
    "account" => [
        "title" => "Quản Lý Tài Khoản — {$site_name}",
        "desc" => "Quản lý thông tin tài khoản, đổi mật khẩu và xem các quyền lợi hội viên của bạn trên hệ thống {$site_name}.",
        "keywords" => "quản lý tài khoản, đổi mật khẩu, locket gold",
    ],
];
$current_seo = $seo[$page] ?? $seo["home"];
$page_title = $current_seo["title"];
// Override desc & keywords từ global_settings nếu admin đã cài
$page_desc = !empty($settings['meta_desc']) ? $settings['meta_desc'] : $current_seo["desc"];
$page_keywords = !empty($settings['meta_keywords']) ? $settings['meta_keywords'] : $current_seo["keywords"];
$logo_path_base = !empty($settings['logo_path']) ? $settings['logo_path'] : '/logo.png';
$favicon_path_base = !empty($settings['favicon_path']) ? $settings['favicon_path'] : $logo_path_base;
$banner_path_base = !empty($settings['banner_path']) ? $settings['banner_path'] : '/banner.png';

$logo_path = $logo_path_base . '?v=' . (@filemtime(__DIR__ . $logo_path_base) ?: 1);
$favicon_path = $favicon_path_base . '?v=' . (@filemtime(__DIR__ . $favicon_path_base) ?: 1);
$banner_path = $banner_path_base . '?v=' . (@filemtime(__DIR__ . $banner_path_base) ?: 1);

$article_meta_thumb = $site_domain . $banner_path;
$page_type = "website";
$published_time = "";
$modified_time = date("c", filemtime(__FILE__));

$canonical_paths = ["home" => "/trang-chu", "guide" => "/huong-dan", "vip" => "/dich-vu-vip", "contact" => "/lien-he", "auth" => "/dang-nhap", "tool" => "/cong-cu", "blog" => "/kinh-nghiem", "agency-setup" => "/tao-web-con", "account" => "/quan-ly-tai-khoan"];
$canonical_url = $site_domain . ($canonical_paths[$page] ?? "/trang-chu");

if ($page === "article") {
    $page_type = "article";
    $slug = $_GET["slug"] ?? "";
    try {
        $st_seo = $pdo->prepare("SELECT * FROM articles WHERE slug = ?");
        $st_seo->execute([$slug]);
        $art_seo = $st_seo->fetch();
        if ($art_seo) {
            $page_title = $art_seo["meta_title"] ?: ($art_seo["title"] . " - " . $site_name);
            $page_desc = $art_seo["meta_desc"] ?: mb_substr(strip_tags($art_seo["excerpt"]), 0, 160);
            $page_keywords = "locket gold, " . str_replace(" ", ", ", mb_substr($art_seo["title"], 0, 50));
            $canonical_url = $site_domain . "/bai-viet?slug=" . urlencode($art_seo["slug"]);
            if (!empty($art_seo["thumbnail"]))
                $article_meta_thumb = $art_seo["thumbnail"];
            $published_time = date("c", strtotime($art_seo["created_at"]));
            $modified_time = date("c", strtotime($art_seo["updated_at"]));
        } else {
            header("Location: /trang-chu");
            exit;
        }
    } catch (\Throwable $e) {}
}

$robots_content = in_array($page, ["admin", "history", "tool"]) ? "noindex, nofollow" : "index, follow";
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($page_keywords) ?>">
    <meta name="author" content="<?= htmlspecialchars($site_name) ?>">
    <meta name="application-name" content="Locket Gold">
    <meta name="apple-mobile-web-app-title" content="Locket Gold">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="robots" content="<?= htmlspecialchars($robots_content) ?>">
    <meta name="theme-color" content="#A78BFA">
    <link rel="canonical" href="<?= htmlspecialchars($canonical_url) ?>">
    <link rel="icon" type="image/png" href="<?= htmlspecialchars($favicon_path) ?>">
    <link rel="shortcut icon" href="<?= htmlspecialchars($favicon_path) ?>">
    <link rel="apple-touch-icon" href="<?= htmlspecialchars($logo_path) ?>">
    <link rel="apple-touch-startup-image" href="<?= htmlspecialchars($banner_path) ?>">

    <!-- Open Graph (Facebook/Zalo/LinkedIn) -->
    <meta property="og:type" content="<?= $page_type ?>">
    <meta property="og:site_name" content="<?= htmlspecialchars($site_name) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_desc) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonical_url) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($article_meta_thumb) ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="<?= htmlspecialchars($page_title) ?>">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:updated_time" content="<?= $modified_time ?>">
    <?php if ($page_type === "article" && $published_time): ?>
        <meta property="article:published_time" content="<?= $published_time ?>">
        <meta property="article:modified_time" content="<?= $modified_time ?>">
        <meta property="article:author" content="<?= htmlspecialchars($site_name) ?>">
        <meta property="article:section" content="Technology">
    <?php endif; ?>

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@LocketGold">
    <meta name="twitter:creator" content="@LocketQuocVu">
    <meta name="twitter:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($page_desc) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($article_meta_thumb) ?>">

    <!-- Google Search Thumbnail -->
    <meta name="thumbnail" content="<?= htmlspecialchars($article_meta_thumb) ?>">

    <!-- Structured Data (Schema.org) MASTER -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "WebSite",
          "@id": "<?= htmlspecialchars($site_domain) ?>/#website",
          "url": "<?= htmlspecialchars($site_domain) ?>",
          "name": "<?= htmlspecialchars($site_name) ?>",
          "description": "Nền tảng kích hoạt Locket Gold cao cấp tại Việt Nam.",
          "publisher": {"@id": "<?= htmlspecialchars($site_domain) ?>/#organization"},
          "inLanguage": "vi-VN",
          "potentialAction": {
            "@type": "SearchAction",
            "target": "<?= htmlspecialchars($site_domain) ?>/kinh-nghiem?q={search_term_string}",
            "query-input": "required name=search_term_string"
          }
        },
        {
          "@type": "Organization",
          "@id": "<?= htmlspecialchars($site_domain) ?>/#organization",
          "name": "<?= htmlspecialchars($site_name) ?>",
          "url": "<?= htmlspecialchars($site_domain) ?>",
          "logo": {
            "@type": "ImageObject",
            "url": "<?= htmlspecialchars($site_domain) ?>/logo.png",
            "width": 512,
            "height": 512
          }
        },
        {
          "@type": "BreadcrumbList",
          "@id": "<?= htmlspecialchars($canonical_url) ?>/#breadcrumb",
          "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "Trang Chủ", "item": "<?= htmlspecialchars($site_domain) ?>/"}
            <?php if ($page !== "home" && $page !== "404"): ?>
                                                            ,{"@type": "ListItem", "position": 2, "name": "<?= htmlspecialchars($page === "article" ? "Bài Viết" : ($seo[$page]["title"] ?? "Trang")) ?>", "item": "<?= htmlspecialchars($canonical_url) ?>"}
            <?php endif; ?>
          ]
        }
        <?php if ($page === "article" && isset($art_seo)): ?>,
                                                        {
                                                          "@type": "Article",
                                                          "@id": "<?= htmlspecialchars($canonical_url) ?>/#article",
                                                          "isPartOf": {"@id": "<?= htmlspecialchars($site_domain) ?>/#website"},
                                                          "mainEntityOfPage": {"@id": "<?= htmlspecialchars($canonical_url) ?>"},
                                                          "headline": "<?= htmlspecialchars($page_title) ?>",
                                                          "description": "<?= htmlspecialchars($page_desc) ?>",
                                                          "image": "<?= htmlspecialchars($article_meta_thumb) ?>",
                                                          "datePublished": "<?= $published_time ?>",
                                                          "dateModified": "<?= $modified_time ?>",
                                                          "author": {"@type": "Person", "name": "<?= htmlspecialchars($site_name) ?>"},
                                                          "publisher": {"@id": "<?= htmlspecialchars($site_domain) ?>/#organization"}
                                                        }
        <?php endif; ?>
        <?php if ($page === "tool"): ?>,
                                                        {
                                                          "@type": "SoftwareApplication",
                                                          "name": "Locket Gold Premium",
                                                          "operatingSystem": "iOS, Android",
                                                          "applicationCategory": "UtilitiesApplication",
                                                          "description": "Ứng dụng tiện ích hỗ trợ phân giải và tiêm cấu hình Locket Gold Premium.",
                                                          "aggregateRating": {
                                                            "@type": "AggregateRating",
                                                            "ratingValue": "4.9",
                                                            "ratingCount": "8250"
                                                          },
                                                          "offers": {
                                                            "@type": "Offer",
                                                            "price": "0",
                                                            "priceCurrency": "VND"
                                                          }
                                                        }
        <?php endif; ?>
        <?php if ($page === "home"): ?>,
                                                        {
                                                          "@type": "FAQPage",
                                                          "mainEntity": [
                                                            {"@type": "Question", "name": "Locket Gold có an toàn không?", "acceptedAnswer": {"@type": "Answer", "text": "Hoàn toàn an toàn. Hệ thống sử dụng chứng chỉ Apple chính thức, không can thiệp ID Apple của bạn."}},
                                                            {"@type": "Question", "name": "Kích hoạt Locket Gold mất bao lâu?", "acceptedAnswer": {"@type": "Answer", "text": "Hệ thống tự động xử lý và đồng bộ trạng thái Premium ngay lập tức dưới nền (khoảng 3-5s)."}}
                                                          ]
                                                        }
        <?php endif; ?>
        <?php if ($page === "vip"): ?>,
                                                        {
                                                          "@type": "Product",
                                                          "name": "Locket Gold VIP Service",
                                                          "description": "Dịch vụ nâng cấp Locket Gold Premium chính hãng, bảo hiểm trọn đời.",
                                                          "brand": {"@type": "Brand", "name": "<?= htmlspecialchars($site_name) ?>"},
                                                          "offers": {"@type": "AggregateOffer", "priceCurrency": "VND", "lowPrice": "50000", "highPrice": "500000", "offerCount": "4"}
                                                        }
        <?php endif; ?>
      ]
    }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Inter:wght@400;500;600;700;800;900&family=Manrope:wght@500;600;700;800&family=Sora:wght@600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css?v=<?= filemtime(__DIR__ . '/assets/style.css') ?>">
    <style>
        .user-dropdown {
            position: relative;
            display: inline-block;
            margin-left: 8px;
        }

        .user-dropdown-content {
            display: block;
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            background-color: var(--bg-1);
            min-width: 240px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18);
            border-radius: 14px;
            z-index: 9999;
            border: 1px solid var(--border);
            overflow: hidden;
            opacity: 0;
            transform: translateY(-8px) scale(0.97);
            pointer-events: none;
            transition: opacity 0.18s ease, transform 0.18s ease;
            transform-origin: top right;
        }

        .user-dropdown.open .user-dropdown-content {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        /* arrow rotate khi open */
        .user-dropdown.open .user-dropdown-btn .chevron-icon {
            transform: rotate(180deg);
        }

        .user-dropdown-btn .chevron-icon {
            transition: transform 0.2s ease;
        }

        .user-dropdown-content a {
            color: var(--text-1);
            padding: 12px 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 500;
            transition: 0.2s;
            border-bottom: 1px solid var(--border);
        }

        .user-dropdown-content a:last-child {
            border-bottom: none;
        }

        .user-dropdown-content a:hover {
            background-color: var(--bg-2);
            color: var(--accent-bright);
        }

        .user-dropdown-content a svg {
            color: var(--text-2);
            transition: 0.2s;
        }

        .user-dropdown-content a:hover svg {
            color: var(--accent-bright);
        }

        .user-dropdown-content a.logout-link:hover {
            color: var(--red);
            background: rgba(239, 68, 68, 0.05);
        }

        .user-dropdown-content a.logout-link:hover svg {
            color: var(--red);
        }

        .user-dropdown-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            border: 1px solid var(--border);
            padding: 6px 14px;
            border-radius: 20px;
            color: var(--text-1);
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: 0.2s;
            height: 38px;
        }

        .user-dropdown-btn:hover {
            background: var(--bg-2);
            border-color: var(--accent);
        }

        /* ═ State Lock (tool page) ═ */
        .state-lock-input {
            background: rgba(255, 255, 255, 0.05) !important;
            color: #888 !important;
            cursor: not-allowed !important;
            border: 1px dashed rgba(248, 113, 113, 0.5) !important;
        }

        .state-lock-note {
            color: #fca5a5;
            font-size: 13px;
            margin-top: 8px;
            line-height: 1.5;
        }

        .btn-disabled-theme {
            opacity: 0.5;
            background: linear-gradient(135deg, #4b5563, #374151) !important;
            border: none !important;
            cursor: not-allowed !important;
        }

        /* ═ Receipt Badges ═ */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .badge-warning {
            background: rgba(251, 191, 36, 0.15);
            color: #f59e0b;
        }
    </style>
    <script>
        // Scroll position restore
        document.addEventListener("DOMContentLoaded", function () {
            if (sessionStorage.getItem("scrollPos")) {
                window.scrollTo(0, sessionStorage.getItem("scrollPos"));
                sessionStorage.removeItem("scrollPos");
            }
        });
        window.onbeforeunload = function () {
            sessionStorage.setItem("scrollPos", window.scrollY);
        };

        // Dropdown toggle - works on desktop AND mobile touch
        function toggleUserDropdown(event) {
            event.stopPropagation();
            var dd = document.getElementById('userDropdown');
            if (!dd) return;
            var isOpen = dd.classList.toggle('open');
            var btn = document.getElementById('userDropdownBtn');
            if (btn) btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }
        document.addEventListener('click', function (event) {
            var dd = document.getElementById('userDropdown');
            if (dd && !dd.contains(event.target)) {
                dd.classList.remove('open');
                var btn = document.getElementById('userDropdownBtn');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }
        });
    </script>
</head>

<body class="theme-<?= $base_theme ?>" data-mode="light">
    <script>if (localStorage.getItem('locket_theme') === 'dark') document.body.removeAttribute('data-mode');</script>

    <?php if ($page !== 'admin'): ?>
        <!-- NAVBAR -->
        <nav class="nav">
            <div class="nav-inner">
                <a href="/trang-chu" class="logo">
                    <img src="<?= htmlspecialchars($logo_path) ?>" alt="Locket Gold"
                        style="width:42px; height:42px; border-radius:12px; object-fit:contain; box-shadow:0 8px 24px rgba(167,139,250,0.24);">
                    <div class="logo-text"><span
                            class="brand-main"><?= htmlspecialchars($site_name) ?></span><?php if (empty($settings['site_name'])): ?><span
                                class="brand-sub">Quốc Vũ</span><?php endif; ?>
                    </div>
                </a>
                <div class="nav-menu">
                    <a href="/trang-chu" class="nav-link <?= $page == 'home' ? 'on' : '' ?>">Trang chủ</a>
                    <a href="/cong-cu" class="nav-link <?= $page == 'tool' ? 'on' : '' ?>">Kích hoạt</a>
                    <a href="/huong-dan" class="nav-link <?= $page == 'guide' ? 'on' : '' ?>">Hướng dẫn</a>
                    <a href="/kinh-nghiem" class="nav-link <?= $page == 'blog' ? 'on' : '' ?>">Góc chia sẻ</a>
                    <a href="/dich-vu-vip" class="nav-link <?= $page == 'vip' ? 'on' : '' ?>">Bảng giá</a>
                    <a href="/lien-he" class="nav-link <?= $page == 'contact' ? 'on' : '' ?>">Liên hệ</a>
                </div>
                <div class="nav-right" style="display:flex; align-items:center;">
                    <button class="btn-nav" style="border:none; cursor:pointer;" onclick="toggleTheme()" title="Sáng/Tối">
                        <svg id="theme-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                    </button>
                    <?php if ($current_user): ?>
                        <div class="user-dropdown hide-m" id="userDropdown">
                            <button class="user-dropdown-btn" id="userDropdownBtn" onclick="toggleUserDropdown(event)"
                                aria-haspopup="true" aria-expanded="false">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span
                                    style="max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars(explode('@', $current_user)[0]) ?></span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>
                            <div class="user-dropdown-content">
                                <div style="padding:12px 16px; border-bottom:1px solid var(--border); margin-bottom:4px;">
                                    <div style="font-size:12px; color:var(--text-2);">Đang đăng nhập</div>
                                    <div style="font-size:14px; font-weight:700; color:var(--text-0); word-break:break-all;">
                                        <?= htmlspecialchars($current_user) ?>
                                    </div>
                                    <div class="chip <?= $current_role === 'user' ? 'chip-neutral' : '' ?>"
                                        style="margin-top:8px; display:inline-flex;">
                                        <?= getRoleLabel($current_role) ?>
                                    </div>
                                </div>
                                <?php if ($current_role === 'admin'): ?>
                                    <a href="/admin"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                        </svg> Quản trị hệ thống</a>
                                <?php else: ?>
                                    <a href="/lich-su"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg> Quản lý hóa đơn</a>
                                <?php endif; ?>
                                <a href="/quan-ly-tai-khoan"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg> Quản lý tài khoản</a>
                                <?php /* 
                                                                 <a href="/tao-web-con"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                         <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                                         <line x1="3" y1="9" x2="21" y2="9"></line>
                                                                         <line x1="9" y1="21" x2="9" y2="9"></line>
                                                                     </svg> Tạo web con</a>
                                                                 */ ?>
                                <a href="/logout" class="logout-link"><svg width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg> Đăng xuất</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/dang-nhap" class="btn-nav hide-m"
                            style="border-color:var(--accent); color:var(--accent-bright)">Đăng nhập</a>
                    <?php endif; ?>
                    <button class="hamburger-btn" onclick="toggleSidebar()" aria-label="Menu">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Mobile Sidebar -->
        <div class="mobile-sidebar-overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>
        <div class="mobile-sidebar" id="mobileSidebar">
            <div class="sidebar-header">
                <a href="/trang-chu" class="logo" style="gap:8px;">
                    <img src="<?= htmlspecialchars($logo_path) ?>" alt="Locket Gold"
                        style="width:36px; height:36px; border-radius:10px; object-fit:contain;">
                    <div class="logo-text" style="font-size:18px;"><span
                            class="brand-main"><?= htmlspecialchars($site_name) ?></span><?php if (empty($settings['site_name'])): ?><span
                                class="brand-sub" style="font-size:16px;">Quốc Vũ</span><?php endif; ?></div>
                </a>
                <button class="btn-close-sidebar" onclick="toggleSidebar()" aria-label="Đóng">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="sidebar-content">
                <a href="/trang-chu" class="sidebar-link <?= $page == 'home' ? 'active' : '' ?>">Trang chủ</a>
                <a href="/cong-cu" class="sidebar-link <?= $page == 'tool' ? 'active' : '' ?>">Kích hoạt</a>
                <a href="/huong-dan" class="sidebar-link <?= $page == 'guide' ? 'active' : '' ?>">Hướng dẫn</a>
                <a href="/kinh-nghiem" class="sidebar-link <?= $page == 'blog' ? 'active' : '' ?>">Góc chia sẻ</a>
                <a href="/dich-vu-vip" class="sidebar-link <?= $page == 'vip' ? 'active' : '' ?>">Bảng giá</a>
                <a href="/lien-he" class="sidebar-link <?= $page == 'contact' ? 'active' : '' ?>">Liên hệ</a>

                <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--glass-border);">
                    <?php if ($current_user): ?>
                        <?php if ($current_role === 'admin'): ?>
                            <a href="/admin" class="sidebar-link <?= $page == 'admin' ? 'active' : '' ?>">Quản trị hệ thống</a>
                        <?php else: ?>
                            <a href="/lich-su" class="sidebar-link <?= $page == 'history' ? 'active' : '' ?>">Quản lý</a>
                        <?php endif; ?>
                        <a href="/quan-ly-tai-khoan" class="sidebar-link <?= $page == 'account' ? 'active' : '' ?>">Tài khoản &
                            Đổi MK</a>
                        <?php /* <a href="/tao-web-con" class="sidebar-link <?= $page == 'agency-setup' ? 'active' : '' ?>">Tạo web con</a> */ ?>
                        <a href="/logout" class="sidebar-link" style="color:var(--error); margin-top: 8px;">Đăng xuất</a>
                    <?php else: ?>
                        <a href="/dang-nhap" class="btn btn-primary"
                            style="width: 100%; text-align: center; justify-content: center;">Đăng nhập</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <nav class="bottom-nav">
            <a href="/trang-chu" class="bottom-nav-item <?= $page == 'home' ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span>Trang chủ</span>
            </a>
            <a href="/cong-cu" class="bottom-nav-item <?= $page == 'tool' ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
                <span>Kích hoạt</span>
            </a>
            <a href="/huong-dan" class="bottom-nav-item <?= $page == 'guide' ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <span>HD</span>
            </a>
            <a href="/dich-vu-vip" class="bottom-nav-item <?= $page == 'vip' ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                </svg>
                <span>Bảng giá</span>
            </a>
            <?php if ($current_user): ?>
                <a href="<?= $current_role === 'admin' ? '/admin' : '/lich-su' ?>"
                    class="bottom-nav-item <?= in_array($page, ['admin', 'history']) ? 'active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Tài khoản</span>
                </a>
            <?php else: ?>
                <a href="/dang-nhap" class="bottom-nav-item <?= $page == 'auth' ? 'active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    <span>Đăng nhập</span>
                </a>
            <?php endif; ?>
        </nav>
    <?php endif; // End check not admin ?>

    <main
        class="wrap <?= in_array($page, ['home', 'auth']) ? 'center-y' : '' ?> <?= $page === 'admin' ? 'admin-full-page' : '' ?>">

        
        <?php
        $valid_pages = ['admin', 'home', 'auth', 'tool', 'payment', 'vip', 'guide', 'blog', 'article', 'contact', 'history', 'agency_setup', 'account'];
        if (in_array($page, $valid_pages)) {
            require __DIR__ . '/pages/' . $page . '.php';
        }
        ?>
</main>

    <div id="toast-stack" class="toast-stack" aria-live="polite" aria-atomic="true"></div>

    <script>
        window.__initialToasts = <?= json_encode($toast_queue, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    </script>

    <?php if (($settings['notice_active'] ?? '0') === '1' && $page === 'home'): ?>
        <div id="global-notice-modal" class="admin-modal" style="z-index:99999;">
            <div class="modal-box"
                style="width:100%; max-width:520px; padding:48px 36px; border-radius:24px; background:linear-gradient(145deg, var(--bg-1), var(--bg-0)); border:1px solid var(--border-accent); box-shadow: 0 20px 60px rgba(0,0,0,0.6); text-align:center; position:relative; overflow:hidden;">
                <div
                    style="position:absolute; top:-20px; left:-20px; right:-20px; height:120px; background:linear-gradient(135deg, <?= htmlspecialchars($settings['notice_bg'] ?? '#A78BFA') ?>, transparent); opacity:0.15; z-index:0; filter:blur(20px);">
                </div>
                <button onclick="closeNotice()"
                    style="position:absolute; top:20px; right:20px; background:rgba(255,255,255,0.06); border-radius:50%; width:36px; height:36px; display:flex; align-items:center; justify-content:center; border:1px solid rgba(255,255,255,0.1); color:var(--text-2); cursor:pointer; z-index:10; transition:all 0.2s;"><svg
                        width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg></button>
                <div style="position:relative; z-index:1;">
                    <div
                        style="display:inline-flex; align-items:center; justify-content:center; width:56px; height:56px; border-radius:16px; background:<?= htmlspecialchars($settings['notice_bg'] ?? '#A78BFA') ?>20; color:<?= htmlspecialchars($settings['notice_bg'] ?? '#A78BFA') ?>; margin-bottom:20px;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                            </path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>
                    <h2
                        style="font-size:24px; font-weight:800; margin-bottom:16px; letter-spacing:-0.5px; background:linear-gradient(90deg, var(--gradient-text-start), <?= htmlspecialchars($settings['notice_bg'] ?? '#A78BFA') ?>); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        <?= htmlspecialchars($settings['notice_title'] ?? '') ?>
                    </h2>
                    <div style="font-size:16px; line-height:1.7; color:var(--text-2); margin-bottom:32px; padding: 0 10px;">
                        <?= nl2br(htmlspecialchars($settings['notice_content'] ?? '')) ?>
                    </div>

                    <div style="display:flex; gap:12px;">
                        <?php if (!empty($settings['notice_link'])): ?>
                            <a href="<?= htmlspecialchars($settings['notice_link']) ?>" target="_blank" onclick="closeNotice()"
                                style="flex:1; display:flex; align-items:center; justify-content:center; padding:14px; border-radius:14px; font-weight:700; font-size:15px; border:none; cursor:pointer; background:<?= htmlspecialchars($settings['notice_bg'] ?? 'var(--accent)') ?>; color:<?= htmlspecialchars($settings['notice_text'] ?? '#ffffff') ?>; box-shadow:0 8px 24px rgba(0,0,0,0.25); transition:transform 0.2s; text-decoration:none;">Đã
                                hiểu</a>
                        <?php else: ?>
                            <button onclick="closeNotice()"
                                style="flex:1; display:flex; align-items:center; justify-content:center; padding:14px; border-radius:14px; font-weight:700; font-size:15px; border:none; cursor:pointer; background:<?= htmlspecialchars($settings['notice_bg'] ?? 'var(--accent)') ?>; color:<?= htmlspecialchars($settings['notice_text'] ?? '#ffffff') ?>; box-shadow:0 8px 24px rgba(0,0,0,0.25); transition:transform 0.2s;">Đã
                                hiểu</button>
                        <?php endif; ?>

                        <button onclick="closeNotice24h()"
                            style="flex:1; display:flex; align-items:center; justify-content:center; padding:14px; border-radius:14px; font-weight:600; font-size:15px; border:1px solid rgba(248,113,113,0.3); cursor:pointer; background:rgba(239,68,68,0.1); color:var(--red); transition:transform 0.2s;">Đóng
                            24h</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function closeNotice() {
                document.getElementById('global-notice-modal').classList.remove('show');
            }
            function closeNotice24h() {
                closeNotice();
                localStorage.setItem('notice_hidden_until', Date.now() + 24 * 60 * 60 * 1000);
            }
            window.addEventListener('DOMContentLoaded', () => {
                let hideUntil = localStorage.getItem('notice_hidden_until');
                if (!hideUntil || Date.now() > hideUntil) {
                    setTimeout(() => {
                        document.getElementById('global-notice-modal').classList.add('show');
                    }, 600);
                }
            });
        </script>
    <?php endif; ?>

    <footer class="footer">
        <div style="margin-bottom:8px;">© <?= date('Y') ?> <strong
                style="color:var(--text-1);"><?= htmlspecialchars($site_name) ?></strong> —
            <?= htmlspecialchars($_SERVER['HTTP_HOST']) ?>.
            Mọi quyền được bảo lưu.
        </div>
        <div>Liên hệ hỗ trợ kỹ thuật: <a
                href="<?= htmlspecialchars($settings['zalo_admin_url'] ?? 'https://zalo.me/0869226687') ?>"
                target="_blank">Admin System</a></div>
    </footer>

    <script>
        const themeBtn = document.getElementById('theme-icon');
        function updateThemeIcon(mode) {
            if (!themeBtn) return;
            if (mode === 'light') themeBtn.innerHTML = '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>';
            else themeBtn.innerHTML = '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>';
        }
        function toggleTheme() {
            let isLight = document.body.getAttribute('data-mode') === 'light';
            if (isLight) {
                document.body.removeAttribute('data-mode');
                localStorage.setItem('locket_theme', 'dark');
                updateThemeIcon('dark');
            } else {
                document.body.setAttribute('data-mode', 'light');
                localStorage.setItem('locket_theme', 'light');
                updateThemeIcon('light');
            }
        }
        if (localStorage.getItem('locket_theme') === 'dark') updateThemeIcon('dark');
        else updateThemeIcon('light');
    </script>

    <script>
        (function () {
            const stack = document.getElementById('toast-stack');
            if (!stack) return;

            const iconMap = {
                success: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>',
                error: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
                warning: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
                info: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>'
            };

            const titleMap = {
                success: 'Thành công',
                error: 'Lỗi',
                warning: 'Lưu ý',
                info: 'Thông báo'
            };

            function removeToast(toast) {
                if (!toast || !toast.parentNode) return;
                toast.classList.add('is-leaving');
                setTimeout(() => {
                    if (toast.parentNode) toast.parentNode.removeChild(toast);
                }, 210);
            }

            window.showToast = function (message, type = 'info', duration = 4200) {
                const safeType = ['success', 'error', 'warning', 'info'].includes(type) ? type : 'info';
                const text = String(message || '').trim();
                if (!text) return;

                const toast = document.createElement('div');
                toast.className = `toast-item ${safeType}`;
                toast.style.setProperty('--toast-duration', `${Math.max(2200, Number(duration) || 4200)}ms`);
                toast.innerHTML = `
                    <span class="toast-icon">${iconMap[safeType]}</span>
                    <div class="toast-content">
                        <div class="toast-title">${titleMap[safeType]}</div>
                        <div class="toast-message"></div>
                    </div>
                    <button type="button" class="toast-close" aria-label="Đóng thông báo">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                `;
                toast.querySelector('.toast-message').textContent = text;

                const closeBtn = toast.querySelector('.toast-close');
                closeBtn.addEventListener('click', () => removeToast(toast));

                stack.appendChild(toast);
                setTimeout(() => removeToast(toast), Math.max(2200, Number(duration) || 4200));
            };

            const initial = Array.isArray(window.__initialToasts) ? window.__initialToasts : [];
            if (initial.length) {
                requestAnimationFrame(() => {
                    initial.forEach((item, idx) => {
                        setTimeout(() => {
                            window.showToast(item.message, item.type, item.duration);
                        }, idx * 140);
                    });
                });
            }
        })();
    </script>

    <script>
        // Prevent F12, Right Click, Inspect Element
        document.addEventListener('contextmenu', e => e.preventDefault());
        document.onkeydown = function (e) {
            if (e.keyCode == 123) return false;
            if (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 67 || e.keyCode === 74)) return false; // I, C, J
            if (e.ctrlKey && e.keyCode === 85) return false; // U
        };
    </script>

    <!-- ═══════ QUẢN LÝ WIDGETS NỔI ═══════ -->
    <style>
        .floating-widget {
            position: fixed;
            bottom: 80px;
            /* Tránh thanh điều hướng app bottom */
            z-index: 999;
        }

        @media (min-width: 768px) {
            .floating-widget {
                bottom: 40px;
            }
        }

        /* Back to top */
        #backToTop {
            left: 20px;
            width: 45px;
            height: 45px;
            background: var(--bg-1);
            border: 1px solid var(--border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-2);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            pointer-events: none;
            transform: translateY(15px);
        }

        #backToTop.show {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        #backToTop:hover {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
            box-shadow: 0 4px 20px var(--accent-glow);
        }

        /* Zalo Khách Hàng */
        .floating-zalo {
            right: 20px;
        }

        .zalo-trigger {
            width: 52px;
            height: 52px;
            background: #0068ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(0, 104, 255, 0.4);
            cursor: pointer;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 2;
            color: #fff;
            font-weight: 800;
            font-size: 15px;
            letter-spacing: 0.5px;
        }

        .zalo-trigger:hover {
            transform: scale(1.08);
        }

        .zalo-trigger.active {
            transform: scale(0.95);
        }

        .zalo-popup {
            position: absolute;
            bottom: 64px;
            right: 0;
            background: var(--bg-1);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
            padding: 8px;
            width: max-content;
            display: flex;
            flex-direction: column;
            gap: 4px;
            opacity: 0;
            pointer-events: none;
            transform: translateY(15px) scale(0.95);
            transform-origin: bottom right;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1;
        }

        .zalo-popup.show {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0) scale(1);
        }

        .zalo-popup-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            color: var(--text-0);
            text-decoration: none;
            font-size: 14.5px;
            font-weight: 600;
            border-radius: var(--radius-sm);
            transition: var(--transition-smooth);
        }

        .zalo-popup-item:hover {
            background: rgba(0, 104, 255, 0.08);
            color: #0068ff;
        }

        body[data-mode="light"] #backToTop,
        body[data-mode="light"] .zalo-popup {
            background: #ffffff;
            border-color: rgba(0, 0, 0, 0.08);
        }
    </style>

    <div id="backToTop" class="floating-widget" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
        title="Lên đầu trang">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </div>

    <div class="floating-widget floating-zalo">
        <div id="zaloPopup" class="zalo-popup">
            <a href="https://www.facebook.com/groups/1488436032791952/" target="_blank" class="zalo-popup-item"
                style="color:#1877f2;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                </svg>
                Cộng đồng Facebook
            </a>
            <a href="<?= htmlspecialchars($settings['zalo_baohanh_url'] ?? 'https://zalo.me/') ?>" target="_blank"
                class="zalo-popup-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0068ff" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    <polyline points="9 12 11 14 15 10"></polyline>
                </svg>
                Nhóm Zalo Bảo Hành
            </a>
            <a href="<?= htmlspecialchars($settings['zalo_hoidap_url'] ?? 'https://zalo.me/') ?>" target="_blank"
                class="zalo-popup-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0068ff" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                    </path>
                </svg>
                Zalo Hỏi Đáp
            </a>
        </div>
        <div class="zalo-trigger" id="zaloTrigger" onclick="toggleZaloPopup(event)" title="Liên hệ Zalo">
            Zalo
        </div>
    </div>

    <script>
        // Xử lý mũi tên lên đầu trang & Navbar cuộn
        window.addEventListener('scroll', () => {
            const btt = document.getElementById('backToTop');
            if (window.scrollY > 400) {
                btt.classList.add('show');
            } else {
                btt.classList.remove('show');
            }

            // Thu gọn Navbar khi lướt xuống
            const nav = document.querySelector('.nav');
            if (nav) {
                if (window.scrollY > 20) {
                    nav.classList.add('nav-scrolled');
                } else {
                    nav.classList.remove('nav-scrolled');
                }
            }
        });

        // Xử lý Popup Zalo
        function toggleZaloPopup(e) {
            e.stopPropagation();
            const popup = document.getElementById('zaloPopup');
            const trigger = document.getElementById('zaloTrigger');
            popup.classList.toggle('show');
            if (popup.classList.contains('show')) {
                trigger.classList.add('active');
            } else {
                trigger.classList.remove('active');
            }
        }

        // Đóng Popup Zalo khi click ra ngoài
        document.addEventListener('click', (e) => {
            const popup = document.getElementById('zaloPopup');
            const triggerEl = e.target.closest('.floating-zalo');
            if (!triggerEl && popup.classList.contains('show')) {
                popup.classList.remove('show');
                document.getElementById('zaloTrigger').classList.remove('active');
            }
        });
    </script>

    <!-- CSRF Auto-Inject: Tự động thêm CSRF token vào mọi form POST -->
    <script>
        document.querySelectorAll('form[method="POST"], form[method="post"], form:not([method])').forEach(function (f) {
            if (f.method && f.method.toLowerCase() === 'post' || !f.method) {
                if (!f.querySelector('input[name="csrf_token"]')) {
                    var i = document.createElement('input');
                    i.type = 'hidden'; i.name = 'csrf_token'; i.value = '<?= htmlspecialchars($csrf_token) ?>';
                    f.appendChild(i);
                }
            }
        });

        // Toggle mobile sidebar
        function toggleSidebar() {
            document.getElementById('mobileSidebar').classList.toggle('active');
            document.getElementById('mobileOverlay').classList.toggle('active');
            document.body.style.overflow = document.getElementById('mobileSidebar').classList.contains('active') ? 'hidden' : '';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmCheckout(planName, price, checkoutTarget = '') {
            Swal.fire({
                title: 'Xác nhận Đăng ký',
                html: `<p style="margin-bottom:15px; font-size:16px;">Bạn đang chọn mua gói <b>${planName}</b> với giá <b style="color:#ef4444;">${price.toLocaleString('vi-VN')}đ</b>.</p>
               <p style="font-size:14px; color:var(--text-2); margin-bottom:10px;">Bạn có chắc chắn muốn tiếp tục tới trang thanh toán không?</p>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Tiếp tục Thanh Toán',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#4b5563',
                background: 'var(--bg-1)',
                color: 'var(--text-0)',
            }).then((result) => {
                if (result.isConfirmed) {
                    const inferredTarget = checkoutTarget || (() => {
                        const normalized = String(planName || '').toLowerCase();
                        if (normalized.includes('agency') || normalized.includes('đại lý')) return 'agency';
                        if (normalized.includes('vip 4')) return 'vip4';
                        if (normalized.includes('vip 3')) return 'vip3';
                        if (normalized.includes('vip 2')) return 'vip2';
                        if (normalized.includes('vip 1')) return 'vip1';
                        return '';
                    })();
                    if (inferredTarget) {
                        window.location.href = `/thanh-toan?checkout=${encodeURIComponent(inferredTarget)}`;
                    } else {
                        window.location.href = `/thanh-toan?plan=${encodeURIComponent(planName)}&price=${price}`;
                    }
                }
            });
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success') && urlParams.get('success') === 'receipt') {
                let pollInterval;
                Swal.fire({
                    title: 'Đang phân tích hóa đơn...',
                    html: '<p style="margin-bottom:15px; font-size:15px; line-height:1.5;">Hệ thống Siêu AI đang kiểm tra tính hợp lệ của biên lai.</p><p style="font-size:14px; color:var(--text-2);">Vui lòng giữ nguyên màn hình, quá trình này mất khoảng 5-15 giây.</p>',
                    imageUrl: 'https://i.imgur.com/llF5iyg.gif',
                    imageWidth: 80,
                    imageHeight: 80,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    background: 'var(--bg-1)',
                    color: 'var(--text-0)',
                    didOpen: () => {
                        pollInterval = setInterval(() => {
                            fetch('?ajax_check_receipt=1')
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'hoàn thành') {
                                        clearInterval(pollInterval);
                                        Swal.fire({
                                            title: 'Nâng cấp thành công!',
                                            html: '<p style="margin-bottom:15px; font-size:15px;">Tuyệt vời! AI đã xác nhận hóa đơn hợp lệ.</p><p style="font-size:14px; color:var(--text-2);">Tài khoản của bạn đã được nâng cấp lên VIP.</p>',
                                            icon: 'success',
                                            confirmButtonText: 'Bắt đầu sử dụng',
                                            confirmButtonColor: '#10b981',
                                            background: 'var(--bg-1)',
                                            color: 'var(--text-0)',
                                        }).then(() => {
                                            window.location.href = '/trang-chu';
                                        });
                                    } else if (data.status === 'chờ duyệt') {
                                        clearInterval(pollInterval);
                                        let note = data.note ? `<br><br><span style="color:#ef4444; font-size:13px;">Chi tiết: ${data.note}</span>` : '';
                                        Swal.fire({
                                            title: 'Cần xác nhận thủ công!',
                                            html: '<p style="margin-bottom:15px; font-size:15px;">AI không thể tự động duyệt hóa đơn này.</p><p style="font-size:14px; color:var(--text-2);">Vui lòng liên hệ Admin qua Zalo để báo cáo và được duyệt thủ công.</p>' + note,
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Liên hệ Zalo Admin',
                                            cancelButtonText: 'Đóng',
                                            confirmButtonColor: '#0068ff',
                                            cancelButtonColor: '#4b5563',
                                            background: 'var(--bg-1)',
                                            color: 'var(--text-0)',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.open('https://zalo.me/0869226687', '_blank');
                                            }
                                            window.history.replaceState(null, '', '/trang-chu');
                                        });
                                    } else if (data.status === 'error_not_found') {
                                        clearInterval(pollInterval);
                                        Swal.fire('Lỗi', 'Không tìm thấy file hóa đơn!', 'error').then(() => window.location.href = '/trang-chu');
                                    }
                                    // If status is 'pending', just keep polling
                                })
                                .catch(err => console.error(err));
                        }, 2000); // Check every 2 seconds
                    },
                    willClose: () => {
                        clearInterval(pollInterval);
                    }
                });
            }
        });
    </script>

    <?php if ($page === 'home'): ?>
        <div id="salesPopup" class="sales-popup hide">
            <div class="sales-popup-img">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </div>
            <div class="sales-popup-content">
                <div class="sales-title"><span id="salesName"></span> vừa đăng ký gói</div>
                <div class="sales-desc" id="salesPlan"></div>
                <div class="sales-time" id="salesTime"></div>
            </div>
            <div class="sales-close" onclick="closeSalesPopup()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </div>
        </div>
        <style>
            .sales-popup {
                position: fixed;
                bottom: 20px;
                left: 20px;
                background: var(--bg-1);
                border: 1px solid var(--border);
                border-radius: 10px;
                padding: 8px 12px;
                display: flex;
                align-items: center;
                gap: 10px;
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
                z-index: 999;
                max-width: 280px;
                transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                transform: translateY(0);
                opacity: 1;
            }

            .sales-popup.hide {
                transform: translateY(150px);
                opacity: 0;
                pointer-events: none;
            }

            .sales-popup-img {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--accent), var(--green));
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .sales-popup-img svg {
                width: 16px;
                height: 16px;
            }

            .sales-popup-content {
                flex: 1;
                min-width: 0;
            }

            .sales-title {
                font-size: 12px;
                color: var(--text-2);
                margin-bottom: 2px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .sales-title span {
                font-weight: 700;
                color: var(--text-0);
            }

            .sales-desc {
                font-size: 13px;
                font-weight: 700;
                color: var(--accent-bright);
                margin-bottom: 2px;
            }

            .sales-time {
                font-size: 10px;
                color: var(--text-2);
            }

            .sales-close {
                position: absolute;
                top: 4px;
                right: 4px;
                cursor: pointer;
                color: var(--text-2);
                padding: 4px;
            }

            .sales-close svg {
                width: 12px;
                height: 12px;
            }

            .sales-close:hover {
                color: var(--red);
            }

            @media(max-width: 768px) {
                .sales-popup {
                    bottom: calc(85px + env(safe-area-inset-bottom));
                    /* Nằm trên thanh menu dưới cùng */
                    left: 15px;
                    /* Ép về góc trái */
                    transform: translateY(0);
                    width: auto;
                    max-width: calc(100vw - 30px);
                }

                .sales-popup.hide {
                    transform: translateY(150px);
                }
            }
        </style>
        <script>
            const firstNames = ["Nguyễn", "Trần", "Lê", "Phạm", "Hoàng", "Huỳnh", "Phan", "Vũ", "Võ", "Đặng", "Bùi", "Đỗ", "Hồ", "Ngô", "Dương", "Lý", "Bạch", "Thái", "Lương", "Chu", "Đào"];
            const middleNames = ["Văn", "Thị", "Ngọc", "Hữu", "Thanh", "Minh", "Thu", "Xuân", "Hải", "Tuấn", "Đức", "Hoài", "Quang", "Đình", "Nhật", "Hoàng", "Phương"];
            const lastNames = ["Anh", "Bình", "Cường", "Dũng", "Dương", "Đạt", "Hải", "Hiếu", "Hòa", "Huy", "Khang", "Khoa", "Lâm", "Long", "Nam", "Nghĩa", "Phát", "Phong", "Phú", "Quân", "Quốc", "Sơn", "Tài", "Tâm", "Thắng", "Thành", "Thiện", "Thịnh", "Tiến", "Toàn", "Trí", "Trọng", "Tuấn", "Tùng", "Vinh", "Vũ", "Hà", "Hương", "Hồng", "Lan", "Linh", "Mai", "Nga", "Ngọc", "Nhi", "Nhung", "Oanh", "Phương", "Quyên", "Thảo", "Thư", "Thủy", "Trang", "Trâm", "Trinh", "Tuyết", "Uyên", "Vân", "Yến"];
            const plans = ["Gói VIP 1 (1 ID)", "Gói VIP 2 (2 ID)", "Gói VIP 3 (3 ID)", "Gói VIP 4 (10 ID)", "Gói Đại Lý (∞ ID)"];

            function randomItem(arr) { return arr[Math.floor(Math.random() * arr.length)]; }
            function generateName() {
                const type = Math.random();
                if (type < 0.2) return randomItem(firstNames) + ' ' + randomItem(lastNames);
                if (type < 0.8) return randomItem(firstNames) + ' ' + randomItem(middleNames) + ' ' + randomItem(lastNames);
                return randomItem(firstNames) + ' ' + randomItem(middleNames) + ' ' + randomItem(middleNames) + ' ' + randomItem(lastNames);
            }
            function generateTime() {
                const type = Math.random();
                if (type < 0.2) return "Vài giây trước";
                if (type < 0.8) return Math.floor(Math.random() * 59 + 1) + " phút trước";
                return Math.floor(Math.random() * 2 + 1) + " giờ trước";
            }

            let salesPopupTimer;
            let nextPopupTimeout;

            function closeSalesPopup() {
                document.getElementById('salesPopup').classList.add('hide');
                clearTimeout(salesPopupTimer);
            }

            function showRandomSales() {
                document.getElementById('salesName').textContent = generateName();
                document.getElementById('salesPlan').textContent = randomItem(plans);
                document.getElementById('salesTime').textContent = generateTime();

                const popup = document.getElementById('salesPopup');
                popup.classList.remove('hide');

                salesPopupTimer = setTimeout(() => {
                    popup.classList.add('hide');
                }, 5000); // Đã đổi thành 5s

                const nextTime = Math.floor(Math.random() * (120000 - 60000 + 1) + 60000);
                nextPopupTimeout = setTimeout(showRandomSales, nextTime);
            }

            setTimeout(showRandomSales, 3000);
        </script>
    <?php endif; ?>
    <?php if (!empty($show_vip_upgrade_modal)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: '🎉 Nâng Cấp Thành Công!',
                    html: `Tài khoản của bạn đã được nâng cấp lên <b>VIP</b>!<br><br>Chúc bạn sử dụng dịch vụ vui vẻ.<br>Vui lòng đọc kĩ hướng dẫn và xem kĩ video tại trang <a href="/huong-dan" style="color:#3b82f6;text-decoration:underline;">Hướng dẫn</a> để sử dụng dịch vụ một cách tốt nhất.`,
                    icon: 'success',
                    confirmButtonText: 'Đã hiểu & Đóng',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    background: 'var(--bg-1)',
                    color: 'var(--text-0)',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/huong-dan';
                    }
                });
            });
        </script>
    <?php endif; ?>
</body>

</html>

