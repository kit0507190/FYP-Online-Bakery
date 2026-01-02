/**
 * editprofile.js - 处理个人资料编辑页面的交互逻辑
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. 获取需要的页面元素 ---
    const profileForm = document.getElementById('profileForm');
    const saveButton = document.getElementById('saveButton');
    const areaSelect = document.getElementById('address_area');
    const otherAreaGroup = document.getElementById('other_area_group');

    // --- 2. 处理“其他区域 (Other Area)”的显示/隐藏 ---
    /**
     * 当 Area 选择框发生变化时触发
     */
    function toggleOtherArea() {
        if (areaSelect && otherAreaGroup) {
            // 如果用户选择了 'other'，显示输入框；否则隐藏
            if (areaSelect.value === 'other') {
                otherAreaGroup.style.display = 'block';
            } else {
                otherAreaGroup.style.display = 'none';
            }
        }
    }

    // 绑定选择框的变化事件
    if (areaSelect) {
        areaSelect.addEventListener('change', toggleOtherArea);
        // 页面初始化时执行一次，确保如果原本就是 'other'，输入框会被显示
        toggleOtherArea();
    }

    // --- 3. 处理表单提交时的按钮状态 ---
    /**
     * 提交表单时，让按钮变成 Loading 状态并禁用，防止重复点击
     */
    if (profileForm && saveButton) {
        profileForm.addEventListener('submit', function() {
            // 改变按钮内容，加入旋转图标
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            // 禁用按钮
            saveButton.disabled = true;
        });
    }

});