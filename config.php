<?php
// config.php - قم بإنشاء هذا الملف في المجلد الرئيسي للمشروع

// إعدادات قاعدة البيانات
define('DB_HOST', 'localhost');     // عنوان استضافة قاعدة البيانات
define('DB_USER', 'root');         // اسم مستخدم قاعدة البيانات
define('DB_PASS', '');             // كلمة مرور قاعدة البيانات
define('DB_NAME', 'nbi30');        // اسم قاعدة البيانات

// إعدادات البريد الإلكتروني
define('SMTP_HOST', 'smtp.gmail.com');                  // سيرفر SMTP
define('SMTP_USER', 'your_email@gmail.com');           // بريدك الإلكتروني
define('SMTP_PASS', 'your_app_password');              // كلمة مرور التطبيق
define('SMTP_PORT', 587);                              // منفذ SMTP

// رابط موقعك
define('SITE_URL', 'http://localhost/nbi30');          // رابط موقعك المحلي

// إنشاء اتصال قاعدة البيانات
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>