<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = cleanInput($_POST['username']);
    $email = cleanInput($_POST['email']);
    $phone = cleanInput($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $city = cleanInput($_POST['city']);
    
    try {
        // التحقق من عدم وجود البريد الإلكتروني مسبقاً
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $error = "البريد الإلكتروني مستخدم مسبقاً";
        } else {
            // إضافة المستخدم الجديد
            $stmt = $pdo->prepare("INSERT INTO users (username, email, phone, password, city) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $email, $phone, $password, $city]);
            
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            
            header("Location: index.php");
            exit();
        }
    } catch(PDOException $e) {
        $error = "حدث خطأ في التسجيل. الرجاء المحاولة مرة أخرى.";
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل حساب جديد - NBI30 Marketplace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="form-container">
        <form method="POST" action="register.php" class="registration-form">
            <img src="nbii3o.png" alt="Logo" class="logo">
            
            <?php if(isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="username">اسم المستخدم</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="phone">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            
            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="city">المدينة</label>
                <select id="city" name="city" required>
                    <option value="">اختر المدينة</option>
                    <option value="casablanca">الدار البيضاء</option>
                    <option value="rabat">الرباط</option>
                    <option value="fes">فاس</option>
                    <option value="marrakech">مراكش</option>
                    <option value="tangier">طنجة</option>
                    <option value="agadir">أكادير</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">تسجيل حساب جديد</button>
            
            <p class="login-link">لديك حساب بالفعل؟ <a href="login.php">تسجيل الدخول</a></p>
        </form>
    </div>
</body>
</html>