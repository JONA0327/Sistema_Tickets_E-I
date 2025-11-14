document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-admin-users-index]');
    if (!root) {
        return;
    }

    const deleteButtons = root.querySelectorAll('[data-delete-user]');

    deleteButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const userId = button.dataset.userId;
            const userName = button.dataset.userName;

            if (!userId) {
                return;
            }

            const message = `¿Estás seguro de que quieres eliminar al usuario "${userName}"? Esta acción no se puede deshacer y eliminará todos sus tickets asociados.`;
            if (!window.confirm(message)) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${userId}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        });
    });

    const confirmForms = root.querySelectorAll('[data-confirm-remove-block]');
    confirmForms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            const shouldContinue = window.confirm('¿Deseas quitar este correo de la lista de no permitidos?');
            if (!shouldContinue) {
                event.preventDefault();
            }
        });
    });
});
