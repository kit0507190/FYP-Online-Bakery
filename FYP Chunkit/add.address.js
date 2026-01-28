
document.addEventListener('DOMContentLoaded', function() {
    const addressForm = document.getElementById('addressForm');
    const areaSelect = document.getElementById('address_area');
    const postcodeInput = document.getElementById('address_postcode');
    const otherGroup = document.getElementById('other_area_group');
    const postcodeError = document.getElementById('postcode-error');
    
   
    const postcodeHint = document.getElementById('postcode-hint');
    const hintText = document.getElementById('hint-text');

    // --- 1. PostCode Conversion Table ---
    const postcodeMap = {
        "Bandar Melaka": ["75000", "75100", "75200", "75300"],
        "Ayer Keroh": ["75450"],
        "Bukit Beruang": ["75450"]
    };

    // --- 2. Dynamic suggestion logic ---
    function updatePostcodeHint() {
        const selectedArea = areaSelect.value;
        
        // Clear the previous state before each switch.
        postcodeHint.style.display = 'none';
        postcodeError.style.display = 'none';
        postcodeError.textContent = '';
        postcodeInput.style.borderColor = '';

        if (selectedArea !== 'other' && postcodeMap[selectedArea]) {
            // Display suggested postcodes for this area
            hintText.textContent = postcodeMap[selectedArea].join(', ');
            postcodeHint.style.display = 'block';
        }
        
        // Handle display switching for Other Area
        if (selectedArea === 'other') {
            otherGroup.style.display = 'block';
        } else {
            otherGroup.style.display = 'none';
        }
    }

   
    areaSelect.addEventListener('change', updatePostcodeHint);

   // --- 3. Form Submission Interception Logic ---
    addressForm.addEventListener('submit', function(event) {
        const selectedArea = areaSelect.value;
        const enteredPostcode = postcodeInput.value.trim();
        
        if (selectedArea !== 'other' && postcodeMap[selectedArea]) {
            if (!postcodeMap[selectedArea].includes(enteredPostcode)) {
                
                
                event.preventDefault();

                
                postcodeError.textContent = `Invalid Postcode! For ${selectedArea}, please use: ${postcodeMap[selectedArea].join(' or ')}.`;
                postcodeError.style.display = 'block';

                
                postcodeInput.style.borderColor = '#e74c3c';
                postcodeInput.focus();
            }
        }
    });

    
    if (areaSelect.value) {
        updatePostcodeHint();
    }
});