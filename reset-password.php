<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

session_start();

if (!isset($_GET['token'])) {
    header('Location: login.php');
    exit;
}

$token = $_GET['token'];
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT * FROM password_resets WHERE token = ? AND expiry > NOW() AND used = 0");
$stmt->execute([$token]);
$reset = $stmt->fetch();

if (!$reset) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (strlen($password) < 8) {
        $error = "يجب أن تكون كلمة المرور 8 أحرف على الأقل";
    } elseif ($password !== $confirm_password) {
        $error = "كلمات المرور غير متطابقة";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $db->beginTransaction();
        try {
            // Update password
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->execute([$hash, $reset['email']]);
            
            // Mark reset token as used
            $stmt = $db->prepare("UPDATE password_resets SET used = 1 WHERE token = ?");
            $stmt->execute([$token]);
            
            $db->commit();
            $success = "تم تغيير كلمة المرور بنجاح";
            
            // Redirect after 3 seconds
            header("refresh:3;url=login.php");
        } catch (Exception $e) {
            $db->rollBack();
            $error = "حدث خطأ أثناء تحديث كلمة المرور";
        }
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة تعيين كلمة المرور</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto my-10 max-w-md">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">إعادة تعيين كلمة المرور</h2>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-r-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="bg-green-100 border-r-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-gray-700 mb-2">كلمة المرور الجديدة</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">تأكيد كلمة المرور</label>
                    <input type="password" name="confirm_password" required
                           class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <button type="submit" 
                        class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300">
                    تحديث كلمة المرور
                </button>
            </form>
        </div>
    </div>
</body>
</html>