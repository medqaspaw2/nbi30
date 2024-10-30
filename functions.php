<?php
// دالة لتحويل التاريخ إلى صيغة "منذ..."
function get_time_ago($timestamp) {
    $time_diff = time() - strtotime($timestamp);
    
    if ($time_diff < 60) {
        return 'منذ لحظات';
    }
    
    $condition = array(
        12 * 30 * 24 * 60 * 60 => 'سنة',
        30 * 24 * 60 * 60 => 'شهر',
        24 * 60 * 60 => 'يوم',
        60 * 60 => 'ساعة',
        60 => 'دقيقة',
    );
    
    foreach ($condition as $secs => $str) {
        $d = $time_diff / $secs;
        if ($d >= 1) {
            $r = round($d);
            return 'منذ ' . $r . ' ' . $str . ($r > 1 ? ($str == 'شهر' ? 'اً' : 'اً') : '');
        }
    }
}

// دالة للتحقق من صحة الصورة المرفوعة
function validate_image($file) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowed_types)) {
        return 'نوع الملف غير مدعوم. الرجاء رفع صور بصيغة JPG، PNG أو GIF.';
    }
    
    if ($file['size'] > $max_size) {
        return 'حجم الصورة كبير جداً. الحد الأقصى هو 5 ميجابايت.';
    }
    
    return true;
}

// دالة لإنشاء اسم فريد للملف
function generate_unique_filename($original_filename, $upload_path) {
    $info = pathinfo($original_filename);
    $ext = $info['extension'];
    $filename = $info['filename'];
    
    $i = 0;
    while (file_exists($upload_path . $filename . ($i ? '_' . $i : '') . '.' . $ext)) {
        $i++;
    }
    
    return $filename . ($i ? '_' . $i : '') . '.' . $ext;
}

// دالة لتنظيف النص من الأكواد الضارة
function clean_text($text) {
    return htmlspecialchars(strip_tags($text));
}

// دالة للتحقق من صحة البريد الإلكتروني
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// دالة للتحقق من صحة رقم الهاتف
function validate_phone($phone) {
    return preg_match('/^[0-9]{10}$/', $phone);
}

// دالة لإنشاء URL صديق للمحركات البحث
function create_slug($string) {
    $string = preg_replace('/[\s-]+/', '-', $string);
    $string = preg_replace('/[^\w\-]+/', '', $string);
    $string = strtolower(trim($string, '-'));
    return $string;
}

// دالة لتحويل السعر إلى صيغة مقروءة
function format_price($price) {
    return number_format($price, 0, '.', ',') . ' درهم';
}

// دالة للتحقق من الجلسة
function check_session() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
}

// دالة لإرسال بريد إلكتروني
function send_email($to, $subject, $message) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: NBI30 Marketplace <noreply@nbi30.com>' . "\r\n";
    
    return mail($to, $subject, $message, $headers);
}