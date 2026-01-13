document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.getElementById('profileForm');
    const saveButton = document.getElementById('saveButton');

    if (profileForm && saveButton) {
        profileForm.addEventListener('submit', function(event) {
            // 获取输入框的值
            const name = profileForm.elements['name'].value;
            const phone = profileForm.elements['phone'].value;

            // 1. 验证名字（只允许字母和空格）
            const namePattern = /^[a-zA-Z\s]+$/;
            if (!namePattern.test(name)) {
                alert("Please enter a valid name (letters only).");
                event.preventDefault(); // 停止提交
                return;
            }

            // 2. 验证电话（如果有填的话，必须是 10-11 位数字）
            if (phone.length > 0) {
                const phonePattern = /^[0-9]{10,11}$/;
                if (!phonePattern.test(phone)) {
                    alert("Phone number must be between 10 to 11 digits.");
                    event.preventDefault(); // 停止提交
                    return;
                }
            }

            // 如果上面都通过了，才显示加载状态并提交
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveButton.disabled = true;
        });
    }
});