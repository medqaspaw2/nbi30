<?php
session_start();
require_once 'functions.php';

// التحقق من تسجيل دخول المستخدم
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    
    // التحقق من الصور المرفوعة
    if (isset($_FILES['images'])) {
        // معالجة الصور وحفظها
    }
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة إعلان جديد - NBI30 Marketplace</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>إضافة إعلان جديد</h1>
        
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">عنوان الإعلان</label>
                <input type="text" id="title" name="title" required>
            </div>
            
            <div class="form-group">
                <label for="category">الفئة</label>
                <select id="category" name="category" required>
                    <option value="">اختر الفئة</option>
                    <option value="cars">سيارات</option>
                    <option value="real-estate">عقارات</option>
                    <option value="electronics">إلكترونيات</option>
                    <option value="phones">هواتف وأجهزة لوحية</option>
                    <option value="furniture">أثاث منزلي</option>
                    <option value="fashion">ملابس وإكسسوارات</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="price">السعر (درهم)</label>
                <input type="number" id="price" name="price" required>
            </div>
            
            <div class="form-group">
                <label for="description">وصف الإعلان</label>
                <textarea id="description" name="description" rows="5" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="location">المدينة</label>
                <select id="location" name="location" required>
                    <option value="">اختر المدينة</option>
                    <option value="casablanca">الدار البيضاء</option>
                    <option value="rabat">الرباط</option>
                    <option value="fes">فاس</option>
                    <option value="marrakech">مراكش</option>
                    <option value="tangier">طنجة</option>
                    <option value="agadir">أكادير</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="images">الصور (حد أقصى 5 صور)</label>
                <input type="file" id="images" name="images[]" multiple accept="image/*" required>
            </div>
            
            <button type="submit" class="btn btn-primary">نشر الإعلان</button>
        </form>
    </div>
</body>
</html>