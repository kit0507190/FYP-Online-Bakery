/**
 * editprofile.js - 仅保留提交状态控制
 */
document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.getElementById('profileForm');
    const saveButton = document.getElementById('saveButton');

    if (profileForm && saveButton) {
        profileForm.addEventListener('submit', function() {
            // 提交时改变按钮状态
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveButton.disabled = true;
        });
    }
});