// تهيئة النموذج
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('register-form');
    form.addEventListener('submit', handleSubmit);
});

// معالجة تقديم النموذج
async function handleSubmit(e) {
    e.preventDefault();
    
    if (validateForm()) {
        const formData = new FormData(e.target);
        try {
            const response = await fetch('process-register.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showVerificationModal();
            } else {
                showError(data.message);
            }
        } catch (error) {
            showError('حدث خطأ أثناء إنشاء الحساب. يرجى المحاولة مرة أخرى.');
        }
    }
}
document.querySelectorAll('.modal-close').forEach(button => {
    button.addEventListener('click', () => {
        document.querySelector('.modal').classList.remove('active');
    });
});

// لفتح النافذة المنبثقة
function openModal() {
    document.querySelector('.modal').classList.add('active');
}
// التحقق من صحة النموذج
function validateForm() {
    const password = document.querySelector('input[name="password"]').value;
    const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const phone = document.querySelector('input[name="phone"]').value;
    const terms = document.querySelector('input[name="terms"]').checked;

    // التحقق من الموافقة على الشروط
    if (!terms) {
        showError('يجب الموافقة على شروط الاستخدام وسياسة الخصوصية');
        return false;
    }

    // التحقق من تطابق كلمات المرور
    if (password !== confirmPassword) {
        showError('كلمات المرور غير متطابقة');
        return false;
    }

    // التحقق من قوة كلمة المرور
    if (password.length < 8) {
        showError('يجب أن تتكون كلمة المرور من 8 أحرف على الأقل');
        return false;
    }

    // التحقق من صحة البريد الإلكتروني
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showError('البريد الإلكتروني غير صالح');
        return false;
    }

    // التحقق من رقم الهاتف
    const phoneRegex = /^[0-9]{10,}$/;
    if (!phoneRegex.test(phone)) {
        showError('رقم الهاتف غير صالح');
        return false;
    }

    return true;
}

// إظهار رسالة خطأ
function showError(message) {
    const alert = document.createElement('div');
    alert.className = '














