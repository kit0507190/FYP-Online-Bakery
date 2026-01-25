/**
 * add.address.js - 增强动态提示版
 */
document.addEventListener('DOMContentLoaded', function() {
    const addressForm = document.getElementById('addressForm');
    const areaSelect = document.getElementById('address_area');
    const postcodeInput = document.getElementById('address_postcode');
    const otherGroup = document.getElementById('other_area_group');
    const postcodeError = document.getElementById('postcode-error');
    
    // 新增提示相关的 DOM
    const postcodeHint = document.getElementById('postcode-hint');
    const hintText = document.getElementById('hint-text');

    // --- 1. 邮编对照数据表 ---
    const postcodeMap = {
        "Bandar Melaka": ["75000", "75100", "75200", "75300"],
        "Ayer Keroh": ["75450"],
        "Bukit Beruang": ["75450"]
    };

    // --- 2. 动态提示逻辑 (新功能) ---
    function updatePostcodeHint() {
        const selectedArea = areaSelect.value;
        
        // 每次切换时先清空之前的状态
        postcodeHint.style.display = 'none';
        postcodeError.style.display = 'none';
        postcodeError.textContent = '';
        postcodeInput.style.borderColor = '';

        if (selectedArea !== 'other' && postcodeMap[selectedArea]) {
            // 显示该地区的建议邮编
            hintText.textContent = postcodeMap[selectedArea].join(', ');
            postcodeHint.style.display = 'block';
        }
        
        // 处理 Other Area 的显示切换
        if (selectedArea === 'other') {
            otherGroup.style.display = 'block';
        } else {
            otherGroup.style.display = 'none';
        }
    }

    // 监听地区选择框的变化
    areaSelect.addEventListener('change', updatePostcodeHint);

    // --- 3. 表单提交拦截逻辑 ---
    addressForm.addEventListener('submit', function(event) {
        const selectedArea = areaSelect.value;
        const enteredPostcode = postcodeInput.value.trim();
        
        if (selectedArea !== 'other' && postcodeMap[selectedArea]) {
            if (!postcodeMap[selectedArea].includes(enteredPostcode)) {
                
                // 拦截提交
                event.preventDefault();

                // 显示红字错误
                postcodeError.textContent = `Invalid Postcode! For ${selectedArea}, please use: ${postcodeMap[selectedArea].join(' or ')}.`;
                postcodeError.style.display = 'block';

                // 视觉加强
                postcodeInput.style.borderColor = '#e74c3c';
                postcodeInput.focus();
            }
        }
    });

    // 初始化执行一次（用于编辑页面回显时显示提示）
    if (areaSelect.value) {
        updatePostcodeHint();
    }
});