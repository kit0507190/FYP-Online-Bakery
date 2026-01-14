document.addEventListener('DOMContentLoaded', function() {
    const areaSelect = document.getElementById('address_area');
    const otherGroup = document.getElementById('other_area_group');
    const addressForm = document.getElementById('addressForm');

    // 1. 切换 "Other" 框的显示
    if (areaSelect) {
        areaSelect.addEventListener('change', function() {
            otherGroup.style.display = (this.value === 'other') ? 'block' : 'none';
        });
    }

    // 2. 邮编对照字典
    const postcodeDictionary = {
        "Bandar Melaka": ["75000", "75100", "75200", "75300"],
        "Ayer Keroh": ["75450"],
        "Bukit Beruang": ["75450"]
    };

    // 3. 提交验证
    addressForm.addEventListener('submit', function(event) {
        const area = areaSelect.value;
        const postcode = document.getElementById('address_postcode').value;
        const saveButton = document.getElementById('saveButton');

        // 验证邮编
        if (area !== 'other' && postcodeDictionary[area]) {
            if (!postcodeDictionary[area].includes(postcode)) {
                alert("The postcode " + postcode + " does not match " + area);
                event.preventDefault();
                return;
            }
        }

        // 按钮加载动画
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        saveButton.disabled = true;
    });
});