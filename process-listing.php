// process-listing.php
<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'يجب تسجيل الدخول أولاً']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'طريقة طلب غير صحيحة']);
    exit;
}

try {
    // التحقق من البيانات المدخلة
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    $condition = filter_input(INPUT_POST, 'condition', FILTER_SANITIZE_STRING);
    
    // التحقق من وجود جميع البيانات المطلوبة
    if (!$title || !$category || !$price || !$description || !$location) {
        throw new Exception('جميع الحقول المطلوبة يجب ملؤها');
    }
    
    // التحقق من الصور
    if (!isset($_FILES['mainImage']) || $_FILES['mainImage']['error'] === UPLOAD_ERR_NO_FILE) {
        throw new Exception('الصورة الرئيسية مطلوبة');
    }

    // إنشاء مجلد للصور إذا لم يكن موجوداً
    $uploadDir = 'uploads/listings/' . date('Y/m/');
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // معالجة الصورة الرئيسية
    $mainImageName = processImage($_FILES['mainImage'], $uploadDir);
    
    // معالجة الصور الإضافية
    $additionalImages = [];
    if (isset($_FILES['additionalImages'])) {
        $files = reArrayFiles($_FILES['additionalImages']);
        foreach ($files as $file) {
            if ($file['error'] === UPLOAD_ERR_NO_FILE) continue;
            $additionalImages[] = processImage($file, $uploadDir);
        }
    }

    // إدخال البيانات في قاعدة البيانات
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "INSERT INTO listings (user_id, title, category, price, description, location, 
            condition_status, main_image, additional_images, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_SESSION['user_id'],
        $title,
        $category,
        $price,
        $description,
        $location,
        $condition,
        $mainImageName,
        json_encode($additionalImages)
    ]);

    echo json_encode(['success' => true, 'message' => 'تم نشر الإعلان بنجاح']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// دالة لمعالجة الصور
function processImage($file, $uploadDir) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('نوع الملف غير مدعوم');
    }
    
    if ($file['size'] > $maxSize) {
        throw new Exception('حجم الملف كبير جداً');
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = uniqid() . '.' . $extension;
    $destination = $uploadDir . $newName;
    
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception('فشل في رفع الملف');
    }
    
    return $destination;
}

// دالة لإعادة ترتيب مصفوفة الملفات المتعددة
function reArrayFiles($filePost) {
    $fileArray = [];
    $fileCount = count($filePost['name']);
    $fileKeys = array_keys($filePost);
    
    for ($i = 0; $i < $fileCount; $i++) {
        foreach ($fileKeys as $key) {
            $fileArray[$i][$key] = $filePost[$key][$i];
        }
    }
    
    return $fileArray;
}