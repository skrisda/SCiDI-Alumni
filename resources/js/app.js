const fontControls = document.querySelector('.font-controls');

if (fontControls) {
    const storageKey = 'scidi-font-scale';
    const sizes = [0.9, 1, 1.1, 1.2, 1.3];
    let selectedSize = 1;

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
        selectedSize = action === 'reset' ? 1 : sizes[Math.max(0, Math.min(sizes.length - 1, index + (action === 'increase' ? 1 : -1)))];
        applySize();

        try {
            localStorage.setItem(storageKey, String(selectedSize));
        } catch {
            // The selected size still applies for the current page.
        }
    });

    applySize();
}
