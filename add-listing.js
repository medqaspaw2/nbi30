// add-listing.js
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addListingForm');
    const descriptionInput = document.getElementById('description');
    const charCount = document.querySelector('.char-count');
    const mainImageInput = document.getElementById('mainImage');
    const additionalImagesInput = document.getElementById('additionalImages');
    const mainImagePreview = document.getElementById('mainImagePreview');
    const additionalImagesPreview = document.getElementById('additionalImagesPreview');
    const loadingModal = document.getElementById('loadingModal');
    const successModal = document.getElementById('successModal');

    // تحديث عداد الأحرف
    if (descriptionInput && charCount) {
        descriptionInput.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = `${length}/1000`;
            
            if (length > 1000) {
                this.value = this.value.substring(0, 1000);
                charCount.textContent = "1000/1000";
            }
        });
    }

    // معاينة الصورة الرئيسية
    if (mainImageInput) {
        mainImageInput.addEventListener('change', function() {
            previewImage(this, mainImagePreview);
        });
    }

    // معاينة الصور الإضافية
    if (additionalImagesInput) {
        additionalImagesInput.addEventListener('change', function() {
            previewMultipleImages(this, additionalImagesPreview);
        });
    }

    // دالة معاينة صورة واحدة
    function previewImage(input, previewElement) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('preview-image');
                
                previewElement.innerHTML = '';
                previewElement.appendChild(img);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // دالة معاينة صور متعددة
    function previewMultipleImages(input, previewElement) {
        previewElement.innerHTML = '';
        
        if (input.files) {
            const filesAmount = Math.min(input.files.length, 5);
            
            for (let i = 0; i < filesAmount; i++) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('preview-image');
                    previewElement.appendChild(img);
                }
                
                reader.readAsDataURL(input.files[i]);
            }
        }
    }

    // معالجة تقديم النموذج
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // التحقق من صحة النموذج
            if (!validateForm()) {
                return;
            }

            loadingModal.style.display = 'flex';
            
            try {
                const formData = new FormData(this);
                const response = await fetch('process-listing.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                loadingModal.style.display = 'none';
                
                if (result.success) {
                    successModal.style.display = 'flex';
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 2000);
                } else {
                    alert(result.message || 'حدث خطأ أثناء نشر الإعلان');
                }
            } catch (error) {
                loadingModal.style.display = 'none';
                alert('حدث خطأ أثناء نشر الإعلان');
            }
        });
    }

    // التحقق من صحة النموذج
    function validateForm() {
        const title = document.getElementById('title').value.trim();
        const category = document.getElementById('category').value;
        const price = document.getElementById('price').value;
        const description = document.getElementById('description').value.trim();
        const location = document.getElementById('location').value;
        const mainImage = document.getElementById('mainImage').files;

        if (!title) {
            alert('يرجى إدخال عنوان الإعلان');
            return false;
        }
        
        if (!category) {
            alert('يرجى اختيار الفئة');
            return false;
        }
        
        if (!price || price <= 0) {
            alert('يرجى إدخال سعر صحيح');
            return false;
        }
        
        if (!description) {
            alert('يرجى إدخال وصف الإعلان');
            return false;
        }
        
        if (!location) {
            alert('يرجى اختيار الموقع');
            return false;
        }
        
        if (!mainImage || mainImage.length === 0) {
            alert('يرجى اختيار الصورة الرئيسية');
            return false;
        }

        return true;
    }
});
