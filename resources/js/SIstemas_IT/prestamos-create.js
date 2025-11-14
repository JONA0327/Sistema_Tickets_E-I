document.addEventListener('DOMContentLoaded', () => {
    const inventorySelect = document.getElementById('inventario_id');
    const quantityInput = document.getElementById('cantidad_prestada');

    if (!inventorySelect || !quantityInput) {
        return;
    }

    inventorySelect.addEventListener('change', () => {
        const selectedOption = inventorySelect.options[inventorySelect.selectedIndex];

        if (selectedOption && selectedOption.dataset.disponible) {
            const disponible = selectedOption.dataset.disponible;
            quantityInput.max = disponible;
            quantityInput.placeholder = `Máximo: ${disponible}`;

            const helpText = quantityInput.parentNode?.querySelector('.text-xs');
            if (helpText) {
                helpText.textContent = `💡 Disponibles: ${disponible} unidades`;
            }
        } else {
            quantityInput.max = '';
            quantityInput.placeholder = '1';
        }
    });
});
