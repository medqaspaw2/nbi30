<?php
session_start();
require_once 'functions.php';

$product_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$product_id) {
    header('Location: index.php');
    exit();
}

// استرجاع بيانات المنتج من قاعدة البيانات
// $product = get_product_by_id($product_id);
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['title']); ?> - NBI30 Marketplace</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="product-details">
            <div class="product-images">
                <div class="main-image">
                    <img src="<?php echo htmlspecialchars($product['main_image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                </div>
                <div class="thumbnail-images">
                    <?php foreach ($product['images'] as $image): ?>
                        <img src="<?php echo htmlspecialchars($image); ?>" alt="صورة المنتج">
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="product-info">
                <h1><?php echo htmlspecialchars($product['title']); ?></h1>
                <div class="price"><?php echo number_format($product['price']); ?> درهم</div>
                
                <div class="location-date">
                    <span class="location"><?php echo htmlspecialchars($product['location']); ?></span>
                    <span class="date">نشر <?php echo get_time_ago($product['created_at']); ?></span>
                </div>
                
                <div class="description">
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </div>
                
                <div class="seller-info">
                    <h2>معلومات البائع</h2>
                    <div class="seller-name"><?php echo htmlspecialchars($product['seller_name']); ?></div>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="seller-contact">
                            <a href="tel:<?php echo htmlspecialchars($product['phone']); ?>" class="btn btn-primary">اتصال</a>
                            <button class="btn btn-secondary" id="showMessageForm">مراسلة</button>
                        </div>
                    <?php else: ?>
                        <p>الرجاء <a href="login.php">تسجيل الدخول</a> لمشاهدة معلومات الاتصال</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="similar-products">
            <h2>إعلانات مشابهة</h2>
            <div class="products-grid">
                <!-- عرض المنتجات المشابهة -->
            </div>
        </div>
    </div>
</body>
</html>