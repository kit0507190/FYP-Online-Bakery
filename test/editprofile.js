document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.getElementById('profileForm');
    const saveButton = document.getElementById('saveButton');

    if (profileForm && saveButton) {
        profileForm.addEventListener('submit', function(event) {
            const name = profileForm.elements['name'].value;
            const phone = profileForm.elements['phone'].value;

            // 1. 验证名字
            const namePattern = /^[a-zA-Z\s]+$/;
            if (!namePattern.test(name)) {
                alert("Please enter a valid name (letters and spaces only).");
                event.preventDefault();
                return;
            }

            // 2. 验证马来西亚电话号码
            if (phone.length > 0) {
                // 检查是否以 01 开头，且长度是 10 或 11
                if (!phone.startsWith('01') || (phone.length < 10 || phone.length > 11)) {
                    alert("Invalid phone number! It must start with '01' and be 10-11 digits long.");
                    event.preventDefault();
                    return;
                }
            }

            // 通过验证，显示加载状态
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveButton.disabled = true;
        });
    }
});