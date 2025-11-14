document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-my-tickets]');
    if (!root) {
        return;
    }

    const cancelModal = document.getElementById('cancelModal');
    const cancelModalMessage = document.getElementById('cancelModalMessage');
    const cancelModalClose = document.getElementById('cancelModalClose');
    const cancelModalConfirm = document.getElementById('cancelModalConfirm');

    if (!cancelModal || !cancelModalMessage || !cancelModalClose || !cancelModalConfirm) {
        return;
    }

    const openCancelModal = () => {
        cancelModal.classList.remove('hidden');
        cancelModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    };

    const closeCancelModal = () => {
        cancelModal.classList.add('hidden');
        cancelModal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
        cancelModal.removeAttribute('data-ticket-id');
    };

    const confirmButtons = root.querySelectorAll('[data-cancel-ticket]');

    confirmButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const ticketId = button.dataset.ticketId;
            const folio = button.dataset.ticketFolio;

            if (!ticketId || !folio) {
                return;
            }

            cancelModalMessage.textContent = `¿Estás seguro de que quieres cancelar el ticket ${folio}? Esta acción no se puede deshacer.`;
            cancelModal.setAttribute('data-ticket-id', ticketId);
            openCancelModal();
        });
    });

    const submitCancelForm = () => {
        const ticketId = cancelModal.getAttribute('data-ticket-id');
        if (!ticketId) {
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/ticket/${ticketId}`;

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
    };

    cancelModalClose.addEventListener('click', closeCancelModal);
    cancelModalConfirm.addEventListener('click', () => {
        submitCancelForm();
        closeCancelModal();
    });

    cancelModal.addEventListener('click', (event) => {
        if (event.target === cancelModal) {
            closeCancelModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeCancelModal();
        }
    });
});
