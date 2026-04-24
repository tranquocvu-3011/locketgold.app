<?php
// Auto-migrate
// Cho phép chạy trực tiếp qua URL
if (!isset($pdo)) {
    define('IN_APP', true);
    require_once __DIR__ . '/config/database.php';
}
echo "<pre>Running migration...\n";
// $pdo->exec("ALTER TABLE activations ADD COLUMN edit_count INT DEFAULT 0");
// $pdo->exec("ALTER TABLE users ADD COLUMN role_expires_at DATETIME NULL");
// $pdo->exec("ALTER TABLE users ADD COLUMN register_ip VARCHAR(45) DEFAULT NULL");
try {
    $pdo->exec("ALTER TABLE users ADD COLUMN ref_code VARCHAR(10) UNIQUE DEFAULT NULL");
    $pdo->exec("ALTER TABLE users ADD COLUMN referred_by VARCHAR(10) DEFAULT NULL");
} catch (PDOException $e) {
}

// Tính năng thu hồi tự động (Auto-Revoke) ID Demo sau 24H + Kích hoạt Gold
// đã được tích hợp trực tiếp (inline) vào index.php, không cần chạy worker.php riêng.

// System Notice Board
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS global_settings (
        setting_key VARCHAR(50) PRIMARY KEY,
        setting_value TEXT
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(50) DEFAULT 'user',
        phone VARCHAR(20) DEFAULT NULL,
        register_ip VARCHAR(45) DEFAULT NULL,
        role_expires_at DATETIME NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS activations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        uid VARCHAR(255) NOT NULL,
        status VARCHAR(100) NOT NULL,
        injected_by VARCHAR(255) NOT NULL,
        edit_count INT DEFAULT 0,
        job_status VARCHAR(50) DEFAULT 'pending',
        attempts INT DEFAULT 0,
        error_log TEXT DEFAULT NULL,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Auto-migrate các cột mới
    try {
        $pdo->exec("ALTER TABLE activations ADD COLUMN job_status VARCHAR(50) DEFAULT 'pending'");
        $pdo->exec("ALTER TABLE activations ADD COLUMN attempts INT DEFAULT 0");
        $pdo->exec("ALTER TABLE activations ADD COLUMN error_log TEXT DEFAULT NULL");
        $pdo->exec("ALTER TABLE activations ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    } catch (Exception $e) { /* Cột đã tồn tại */
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS rate_limits (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ip_address VARCHAR(45) NOT NULL,
        username VARCHAR(255) NOT NULL,
        request_count INT DEFAULT 1,
        expires_at DATETIME NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS resolved_cache (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        uid_result VARCHAR(255) NOT NULL,
        expires_at DATETIME NOT NULL
    )");

    // Tối ưu hóa Database Indexes cho Production
    try {
        $pdo->exec("CREATE INDEX idx_users_role ON users(role)");
        $pdo->exec("CREATE INDEX idx_acts_uid ON activations(uid)");
        $pdo->exec("CREATE INDEX idx_rate_limits ON rate_limits(ip_address, username)");
        $pdo->exec("CREATE INDEX idx_resolved_cache ON resolved_cache(username)");
        $pdo->exec("CREATE INDEX idx_job_status ON activations(job_status)");
        $pdo->exec("CREATE INDEX idx_acts_injected_by ON activations(injected_by, created_at)");
    } catch (Exception $e) {
    }

    // Blog Schema & Seeder
    $pdo->exec("CREATE TABLE IF NOT EXISTS articles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slug VARCHAR(255) UNIQUE,
        title VARCHAR(255),
        thumbnail VARCHAR(255),
        excerpt TEXT,
        content LONGTEXT,
        meta_title VARCHAR(255),
        meta_desc VARCHAR(500),
        views INT DEFAULT 0,
        is_published TINYINT(1) DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    $bcnt = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
    if ($bcnt == 0) {
        require_once __DIR__ . '/seeder.php';
    }

    // Insert defaults if empty
    $pdo->exec("INSERT IGNORE INTO global_settings (setting_key, setting_value) VALUES 
        ('notice_active', '0'),
        ('notice_title', 'THÔNG BÁO QUAN TRỌNG'),
        ('notice_content', 'Chào mừng bạn đến với Locket Gold VIP. Vui lòng đọc kỹ hướng dẫn trước khi thao tác!'),
        ('notice_link', 'https://zalo.me/'),
        ('notice_text', '#ffffff'),
        ('price_vip1', '59000'), ('price_vip1_old', '399000'),
        ('price_vip2', '79000'), ('price_vip2_old', '799000'),
        ('price_vip3', '99000'), ('price_vip3_old', '1199000'),
        ('price_vip4', '149000'), ('price_vip4_old', '1899000'),
        ('price_agency', '299000'), ('price_agency_old', '2490000')
    ");

    // Agency Settings
    $pdo->exec("CREATE TABLE IF NOT EXISTS agency_settings (
        agency_username VARCHAR(255) PRIMARY KEY,
        site_name VARCHAR(255),
        domain_name VARCHAR(255),
        bank_code VARCHAR(50),
        bank_account VARCHAR(50),
        bank_owner VARCHAR(255),
        price_vip1 VARCHAR(50),
        price_vip2 VARCHAR(50),
        price_vip3 VARCHAR(50),
        price_vip4 VARCHAR(50),
        price_agency VARCHAR(50)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS receipts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255),
        receipt_img VARCHAR(255),
        status VARCHAR(50) DEFAULT 'chờ duyệt',
        requested_plan VARCHAR(255) DEFAULT NULL,
        requested_role VARCHAR(50) DEFAULT NULL,
        requested_amount INT DEFAULT NULL,
        checkout_target VARCHAR(50) DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        agency_owner VARCHAR(255) DEFAULT NULL
    )");

    try {
        $pdo->exec("ALTER TABLE agency_settings ADD COLUMN domain_name VARCHAR(255) DEFAULT NULL");
    } catch (Exception $e) {
    }

    try {
        $pdo->exec("ALTER TABLE receipts ADD COLUMN agency_owner VARCHAR(255) DEFAULT NULL");
    } catch (Exception $e) {
    }
    try {
        $pdo->exec("ALTER TABLE receipts ADD COLUMN admin_note TEXT DEFAULT NULL");
    } catch (Exception $e) {
    }
    try {
        $pdo->exec("ALTER TABLE receipts ADD COLUMN requested_plan VARCHAR(255) DEFAULT NULL");
    } catch (Exception $e) {
    }
    try {
        $pdo->exec("ALTER TABLE receipts ADD COLUMN requested_role VARCHAR(50) DEFAULT NULL");
    } catch (Exception $e) {
    }
    try {
        $pdo->exec("ALTER TABLE receipts ADD COLUMN requested_amount INT DEFAULT NULL");
    } catch (Exception $e) {
    }
    try {
        $pdo->exec("ALTER TABLE receipts ADD COLUMN checkout_target VARCHAR(50) DEFAULT NULL");
    } catch (Exception $e) {
    }
} catch (Exception $e) {
}
// Contacts table
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        platform_name VARCHAR(100),
        link_url VARCHAR(255),
        type VARCHAR(50) DEFAULT 'other'
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS feedbacks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        image_url VARCHAR(255),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
} catch (Exception $e) {
}

// Bank transactions table
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS bank_transactions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        transaction_id VARCHAR(100) UNIQUE NOT NULL,
        amount INT NOT NULL,
        content VARCHAR(255) NOT NULL,
        status VARCHAR(50) DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
} catch (Exception $e) {
}

// Receipt Mapping 1:1 — Chống mất Gold
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS receipt_assignments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        receipt_hash VARCHAR(64) NOT NULL,
        receipt_index INT NOT NULL DEFAULT 0,
        assigned_uid VARCHAR(255) DEFAULT NULL,
        assigned_by VARCHAR(255) DEFAULT NULL,
        assigned_at DATETIME DEFAULT NULL,
        last_used_at DATETIME DEFAULT NULL,
        use_count INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        UNIQUE KEY idx_uid (assigned_uid),
        KEY idx_hash (receipt_hash),
        KEY idx_active (is_active)
    )");
} catch (Exception $e) {
}

// Auto-migrate: is_vip_notified
try {
    $pdo->exec("ALTER TABLE users ADD COLUMN is_vip_notified TINYINT(1) DEFAULT 0");
} catch (Exception $e) {
}

echo "✅ Migration completed successfully!\n";
echo "Tables created/updated: users, activations, rate_limits, resolved_cache, articles, global_settings, agency_settings, receipts, contacts, feedbacks, bank_transactions, receipt_assignments\n";
echo "</pre>";
