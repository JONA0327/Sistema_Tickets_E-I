document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-admin-user-edit]');
    if (!root) {
        return;
    }

    const emailInput = root.querySelector('#email');
    if (!emailInput) {
        return;
    }

    emailInput.addEventListener('input', function onInput() {
        const value = emailInput.value.trim();
        emailInput.classList.remove('border-red-500', 'border-green-500');

        if (value) {
            if (!emailInput.validity.valid) {
                emailInput.classList.add('border-red-500');
            } else {
                emailInput.classList.add('border-green-500');
            }
        }
    });
});
