<?php
session_start();
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// استرجاع بيانات المستخدم
// $user = get_user_by_id($_SESSION['user_id']);
// $listings = get_user_listings($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي - NBI30 Marketplace</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="profile-header">
            <h1>الملف الشخصي</h1>
            <a href="add-listing.php" class="btn btn-primary">إضافة إعلان جديد</a>
        </div>
        
        <div class="profile-content">
            <div class="user-info">
                <h2>المعلومات الشخصية</h2>
                <form method="POST" action="update-profile.php">
                    <div class="form-group">
                        <label for="name">الاسم</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">رقم الهاتف</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">المدينة</label>
                        <select id="location" name="location" required>
                            <option value="casablanca" <?php echo $user['location'] == 'casablanca' ? 'selected' : ''; ?>>الدار البيضاء</option>
                            <option value="rabat" <?php echo $user['location'] == 'rabat' ? 'selected' : ''; ?>>الرباط</option>
                            <option value="fes" <?php echo $user['location'] == 'fes' ? 'selected' : ''; ?>>فاس</option>
                            <option value="marrakech" <?php echo $user['location'] == 'marrakech' ? 'selected' : ''; ?>>مراكش</option>
                            <option value="tangier" <?php echo $user['location'] == 'tangier' ? 'selected' : ''; ?>>طنجة</option>
                            <option value="agadir" <?php echo $user['location'] == 'agadir' ? 'selected' : ''; ?>>أكادير</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </form>
            </div>
            
            <div class="user-listings">
                <h2>إعلاناتي</h2>
                <div class="listings-grid">
                    <?php foreach ($listings as $listing): ?>
                        <div class="listing-card">
                            <img src="<?php echo htmlspecialchars($listing['main_image']); ?>" alt="<?php echo htmlspecialchars($listing['title']); ?>">
                            <div class="listing-info">
                                <h3><?php echo htmlspecialchars($listing['title']); ?></h3>
                                <div class="price"><?php echo number_format($listing['price']); ?> درهم</div>
                                <div class="listing-actions">
                                   <a href="edit-listing.php?id=<?php echo $listing['id']; ?>" class="btn btn-secondary">تعديل</a>
                                    <button class="btn btn-danger delete-listing" data-id="<?php echo $listing['id']; ?>">حذف</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="messages">
                <h2>الرسائل</h2>
                <div class="messages-list">
                    <?php foreach ($messages as $message): ?>
                        <div class="message-card">
                            <div class="message-header">
                                <span class="sender"><?php echo htmlspecialchars($message['sender_name']); ?></span>
                                <span class="date"><?php echo get_time_ago($message['created_at']); ?></span>
                            </div>
                            <div class="message-content">
                                <?php echo nl2br(htmlspecialchars($message['content'])); ?>
                            </div>
                            <div class="message-actions">
                                <a href="messages.php?conversation=<?php echo $message['conversation_id']; ?>" class="btn btn-primary">الرد</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.querySelectorAll('.delete-listing').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('هل أنت متأكد من حذف هذا الإعلان؟')) {
                const listingId = this.dataset.id;
                // إرسال طلب حذف الإعلان
                fetch(`delete-listing.php?id=${listingId}`, {
                    method: 'POST'
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.listing-card').remove();
                    }
                });
            }
        });
    });
    </script>
</body>
</html>