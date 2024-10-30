<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// نضع هذا الكود مرة واحدة فقط لإضافة المستخدم الافتراضي
// بعد تشغيل الصفحة مرة واحدة، قم بحذف أو تعليق هذا الكود
try {
    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $checkStmt->execute(['email' => 'med.qaspaw@gmail.com']);
    
    if (!$checkStmt->fetch()) {
        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, password, created_at) 
            VALUES (:username, :email, :password, CURRENT_TIMESTAMP)
        ");
        
        $stmt->execute([
            'username' => 'محمد',
            'email' => 'med.qaspaw@gmail.com',
            'password' => password_hash('Jadbouta01', PASSWORD_DEFAULT)
        ]);
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
}
// انتهى كود إضافة المستخدم الافتراضي

// التحقق من وجود جلسة مسجلة
if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'الرجاء إدخال البريد الإلكتروني وكلمة المرور';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $updateStmt = $pdo->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = :id");
                $updateStmt->execute(['id' => $user['id']]);
                
                $_SESSION['user_id'] = $user['id'];
                
                header('Location: profile.php');
                exit();
            } else {
                $error = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
            }
        } catch (PDOException $e) {
            $error = 'حدث خطأ في النظام. الرجاء المحاولة مرة أخرى لاحقاً.';
            error_log($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - NBI30 Marketplace</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-primary {
            background: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .test-info {
            background: #e3f2fd;
            color: #0d47a1;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-footer {
            margin-top: 20px;
            text-align: center;
        }
        .form-footer a {
            color: #007bff;
            text-decoration: none;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
        .forgot-password {
            text-align: left;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="text-align: center; margin-bottom: 20px;">تسجيل الدخول</h1>
        
        <div class="test-info">
            <strong>بيانات تجريبية:</strong><br>
            البريد الإلكتروني: med.qaspaw@gmail.com<br>
            كلمة المرور: Jadbouta01
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" novalidate>
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($email ?? ''); ?>" 
                       placeholder="med.qaspaw@gmail.com">
            </div>
            
            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" 
                       placeholder="Jadbouta01">
            </div>
            
            <div class="form-group forgot-password">
                <a href="forgot-password.php">نسيت كلمة المرور؟</a>
            </div>
            
            <button type="submit" class="btn btn-primary">تسجيل الدخول</button>
        </form>
        
        <div class="form-footer">
            <p>ليس لديك حساب؟ <a href="register.php">إنشاء حساب جديد</a></p>
        </div>
    </div>
</body>
</html>