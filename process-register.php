<?php
session_start();
require_once 'config.php';

// التحقق من طريقة الطلب
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('طريقة الطلب غير مسموح بها');
}

// دالة تنظيف المدخلات
function sanitizeInput($input) {
    global $conn;
    return $conn->real_escape_string(trim($input));
}

// دالة التحقق من البريد الإلكتروني
function isEmailValid($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// دالة التحقق من قوة كلمة المرور
function isPasswordStrong($password) {
    // على الأقل 8 أحرف، حرف كبير، حرف صغير، رقم، حرف خاص
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password);
}

try {
    // استلام وتنظيف البيانات
    $fullname = sanitizeInput($_POST['fullname']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // التحقق من البيانات
    if (empty($fullname) || empty($email) || empty($phone) || empty($password)) {
        throw new Exception('جميع الحقول مطلوبة');
    }

    if (!isEmailValid($email)) {
        throw new Exception('البريد الإلكتروني غير صالح');
    }

    if ($password !== $confirmPassword) {
        throw new Exception('كلمات المرور غير متطابقة');
    }

    if (!isPasswordStrong($password)) {
        throw new Exception('كلمة المرور يجب أن تحتوي على 8 أحرف على الأقل، حرف كبير، حرف صغير، رقم، وحرف خاص');
    }

    // التحقق من وجود البريد الإلكتروني
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        throw new Exception('البريد الإلكتروني مستخدم بالفعل');
    }

    // تشفير كلمة المرور
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // إدخال المستخدم الجديد
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, phone, password, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $fullname, $email, $phone, $hashedPassword);
    
    if ($stmt->execute()) {
        // إنشاء جلسة للمستخدم
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['fullname'] = $fullname;
        
        // إرسال رسالة نجاح
        $_SESSION['success'] = "تم إنشاء حسابك بنجاح";
        header("Location: index.php");
        exit();
    } else {
        throw new Exception('حدث خطأ أثناء إنشاء الحساب');
    }

} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: register.php");
    exit();
}
?>