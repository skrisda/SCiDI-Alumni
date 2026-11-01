const fontControls = document.querySelector('.font-controls');

if (fontControls) {
    const storageKey = 'scidi-font-scale';
    const sizes = [0.9, 1, 1.1, 1.2, 1.3];
    const defaultSize = 1;
    let selectedSize = defaultSize;

    try {
        const savedSize = Number(localStorage.getItem(storageKey));
        if (sizes.includes(savedSize)) {
            selectedSize = savedSize;
        }
    } catch {
        // Keep controls usable when browser storage is unavailable.
    }

    const applySize = () => {
        document.documentElement.style.setProperty('--font-scale', selectedSize);
        fontControls.querySelector('[data-font-status]').textContent = `${Math.round(selectedSize * 100)}%`;
        fontControls.querySelector('[data-font-action="decrease"]').disabled = selectedSize === sizes[0];
        fontControls.querySelector('[data-font-action="increase"]').disabled = selectedSize === sizes.at(-1);
    };

    fontControls.addEventListener('click', (event) => {
        const button = event.target.closest('button[data-font-action]');
        if (!button) {
            return;
        }

        const index = sizes.indexOf(selectedSize);
        const action = button.dataset.fontAction;
        selectedSize = action === 'reset' ? defaultSize : sizes[Math.max(0, Math.min(sizes.length - 1, index + (action === 'increase' ? 1 : -1)))];
        applySize();

        try {
            localStorage.setItem(storageKey, String(selectedSize));
        } catch {
            // The selected size still applies for the current page.
        }
    });

    applySize();
}

const prefixSelect = document.querySelector('[data-prefix-select]');
if (prefixSelect) {
    const otherField = document.getElementById('application-prefix-other-field');
    const otherInput = document.getElementById('application-prefix-other');
    const updatePrefixField = () => {
        const isOther = prefixSelect.value === 'other';
        otherField.hidden = !isOther;
        otherInput.disabled = !isOther;
        otherInput.required = isOther;
    };
    prefixSelect.addEventListener('change', updatePrefixField);
    updatePrefixField();
}
