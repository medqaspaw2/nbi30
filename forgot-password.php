<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email']);
    
    if (!validate_email($email)) {
        $error = "البريد الإلكتروني غير صالح";
    } else {
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)");
        
        if ($stmt->execute([$email, $token, $expiry])) {
            // Send reset email
            $reset_link = SITE_URL . "/reset-password.php?token=" . $token;
            // Add your email sending code here
            $success = "تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني";
        }
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استعادة كلمة المرور</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto my-10 max-w-md">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">استعادة كلمة المرور</h2>
            
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
                    <label class="block text-gray-700 mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" required
                           class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <button type="submit" 
                        class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300">
                    إرسال رابط إعادة التعيين
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="login.php" class="text-blue-500 hover:text-blue-700">العودة إلى تسجيل الدخول</a>
            </div>
        </div>
    </div>
</body>
</html>