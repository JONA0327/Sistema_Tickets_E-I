document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-inventory-create]');
    if (!root) {
        return;
    }

    const imageInput = root.querySelector('#imagenes');
    const previewContainer = root.querySelector('#image_preview');
    const counterSpan = root.querySelector('#image_count');

    if (imageInput && previewContainer && counterSpan) {
        imageInput.addEventListener('change', (event) => {
            const files = Array.from(event.target.files || []);
            previewContainer.innerHTML = '';

            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            const invalidFiles = files.filter((file) => !validTypes.includes(file.type));

            if (invalidFiles.length > 0) {
                alert(`❌ Solo se permiten archivos JPG, JPEG y PNG.\n\nArchivos no válidos detectados:\n${invalidFiles.map((file) => file.name).join('\n')}`);
                imageInput.value = '';
                counterSpan.textContent = '';
                counterSpan.className = 'text-xs text-green-600 font-medium';
                return;
            }

            if (files.length > 0) {
                counterSpan.textContent = `📸 ${files.length} imagen${files.length > 1 ? 'es' : ''} seleccionada${files.length > 1 ? 's' : ''}`;
                counterSpan.className = 'text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded';
            } else {
                counterSpan.textContent = '';
                counterSpan.className = 'text-xs text-green-600 font-medium';
            }

            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (loadEvent) => {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'relative border rounded-lg overflow-hidden bg-green-50';
                        previewDiv.innerHTML = `
                            <img src="${loadEvent.target?.result || ''}" class="w-full h-20 object-cover" />
                            <div class="text-xs text-center p-1 bg-green-100">
                                <span class="font-medium text-green-700">#${index + 1}</span>
                            </div>
                            <div class="text-xs text-gray-600 truncate px-1" title="${file.name}">${file.name}</div>
                        `;
                        previewContainer.appendChild(previewDiv);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    }

    const form = root.querySelector('form');
    if (!form) {
        return;
    }

    const submitBtn = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', (event) => {
        const files = Array.from(imageInput?.files || []);

        if (files.length > 5) {
            const shouldContinue = window.confirm(`Estás a punto de subir ${files.length} imágenes. Esto puede tardar un momento. ¿Continuar?`);
            if (!shouldContinue) {
                event.preventDefault();
                return;
            }
        }

        if (submitBtn && files.length > 0) {
            submitBtn.innerHTML = `
                <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Procesando...
            `;
            submitBtn.disabled = true;
        }
    });
});
