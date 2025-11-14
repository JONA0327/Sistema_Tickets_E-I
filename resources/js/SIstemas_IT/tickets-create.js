const rootSelector = '[data-ticket-create]';

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector(rootSelector);
    if (!root) {
        return;
    }

    const ticketType = root.dataset.ticketType;

    initMaintenanceCalendar(root);

    if (ticketType === 'software' || ticketType === 'hardware') {
        initImageUpload(root);
    }

    if (ticketType === 'hardware') {
        initHardwareDetails(root);
    }

    if (ticketType === 'software') {
        initSoftwareOtherProgram(root);
    }

    setupFormValidation(root, ticketType);
});

function initMaintenanceCalendar(root) {
    const schedulingContainer = root.querySelector('#maintenanceScheduling');
    if (!schedulingContainer) {
        return;
    }

    const availabilityUrl = schedulingContainer.dataset.availabilityUrl;
    const slotsUrl = schedulingContainer.dataset.slotsUrl;
    const calendarGrid = root.querySelector('#calendarGrid');
    const monthLabel = root.querySelector('#calendarMonthLabel');
    const prevButton = root.querySelector('#calendarPrev');
    const nextButton = root.querySelector('#calendarNext');
    const selectedDateLabel = root.querySelector('#selectedDateLabel');
    const selectedSlotLabel = root.querySelector('#selectedSlotLabel');
    const timeSlotsWrapper = root.querySelector('#timeSlotsWrapper');
    const timeSlotsList = root.querySelector('#timeSlotsList');
    const noSlotsMessage = root.querySelector('#noSlotsMessage');
    const hiddenSlotInput = root.querySelector('#maintenance_slot_id');
    const hiddenDateInput = root.querySelector('#maintenance_selected_date');

    if (!calendarGrid || !monthLabel || !prevButton || !nextButton || !selectedDateLabel || !timeSlotsWrapper || !timeSlotsList || !noSlotsMessage || !hiddenSlotInput || !hiddenDateInput) {
        return;
    }

    const statusClasses = {
        available: 'bg-green-100 text-green-700 border border-green-200 hover:bg-green-200',
        partial: 'bg-yellow-100 text-yellow-700 border border-yellow-200 hover:bg-yellow-200',
        full: 'bg-red-100 text-red-700 border border-red-200 cursor-not-allowed',
        empty: 'bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed',
        past: 'bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed',
    };

    let availabilityByDay = {};
    let selectedDate = hiddenDateInput.value || null;
    const initialDate = selectedDate ? new Date(`${selectedDate}T00:00:00`) : new Date();
    let currentDate = new Date(initialDate.getTime());

    prevButton.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        fetchAvailability();
    });

    nextButton.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        fetchAvailability();
    });

    fetchAvailability().then(() => {
        if (selectedDate) {
            selectDate(selectedDate, true);
        }
    });

    function fetchAvailability() {
        const monthParam = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}`;
        return fetch(`${availabilityUrl}?month=${monthParam}`)
            .then((response) => response.json())
            .then((data) => {
                availabilityByDay = {};
                (data.days || []).forEach((day) => {
                    availabilityByDay[day.date] = day;
                });
                renderCalendar();
            })
            .catch(() => {
                calendarGrid.innerHTML = '<p class="col-span-7 text-sm text-red-600">No se pudo cargar la disponibilidad.</p>';
            });
    }

    function renderCalendar() {
        calendarGrid.innerHTML = '';
        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const totalDays = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
        const startWeekDay = firstDay.getDay();

        monthLabel.textContent = firstDay
            .toLocaleDateString('es-MX', {
                month: 'long',
                year: 'numeric',
            })
            .replace(/^\w/u, (c) => c.toUpperCase());

        for (let i = 0; i < startWeekDay; i += 1) {
            const placeholder = document.createElement('div');
            placeholder.className = 'h-12';
            calendarGrid.appendChild(placeholder);
        }

        for (let day = 1; day <= totalDays; day += 1) {
            const isoDate = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const info = availabilityByDay[isoDate];
            const status = info ? info.status : 'empty';

            const button = document.createElement('button');
            button.type = 'button';
            button.dataset.date = isoDate;
            button.className = `h-12 rounded-xl flex flex-col items-center justify-center text-sm transition ${statusClasses[status] || statusClasses.empty}`;

            if (status === 'full' || status === 'empty' || status === 'past' || info?.is_past) {
                button.disabled = true;
            } else {
                button.classList.add('cursor-pointer');
                button.addEventListener('click', () => selectDate(isoDate, true));
            }

            if (selectedDate === isoDate) {
                button.classList.add('ring-2', 'ring-blue-500', 'ring-offset-2');
            }

            button.innerHTML = `
                <span class="font-semibold">${day}</span>
                <span class="text-[11px]">${info ? info.available : 0}/${info ? info.total_capacity : 0}</span>
            `;

            calendarGrid.appendChild(button);
        }
    }

    function selectDate(isoDate, shouldLoadSlots = true) {
        selectedDate = isoDate;
        hiddenDateInput.value = isoDate;
        renderCalendar();

        if (shouldLoadSlots) {
            loadSlots(isoDate);
        }
    }

    function loadSlots(isoDate) {
        timeSlotsList.innerHTML = '';
        noSlotsMessage.classList.add('hidden');
        noSlotsMessage.textContent = 'No hay horarios disponibles para la fecha seleccionada.';
        timeSlotsWrapper.classList.remove('hidden');
        selectedDateLabel.textContent = new Date(`${isoDate}T00:00:00`).toLocaleDateString('es-MX', {
            day: '2-digit',
            month: 'long',
            year: 'numeric',
        });
        selectedSlotLabel.textContent = '';

        fetch(`${slotsUrl}?date=${isoDate}`)
            .then((response) => response.json())
            .then((data) => {
                const slots = data.slots || [];

                if (slots.length === 0) {
                    noSlotsMessage.classList.remove('hidden');
                    hiddenSlotInput.value = '';
                    return;
                }

                let matchedSlot = false;

                slots.forEach((slot) => {
                    const isUnavailable = slot.status === 'full' || slot.status === 'past' || slot.available <= 0;
                    const label = document.createElement('label');
                    label.className = 'flex items-center justify-between gap-3 rounded-2xl border px-4 py-3 text-sm transition';

                    if (isUnavailable) {
                        label.classList.add('bg-gray-100', 'border-gray-200', 'cursor-not-allowed', 'opacity-70');
                    } else {
                        label.classList.add('cursor-pointer', 'border-blue-100', 'hover:border-blue-300', 'hover:bg-blue-50');
                    }

                    const radio = document.createElement('input');
                    radio.type = 'radio';
                    radio.name = 'maintenance_slot_choice';
                    radio.value = slot.id;
                    radio.className = 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300';
                    radio.disabled = isUnavailable;

                    if (!isUnavailable && hiddenSlotInput.value && String(hiddenSlotInput.value) === String(slot.id)) {
                        radio.checked = true;
                        selectedSlotLabel.textContent = `Horario seleccionado: ${slot.label}`;
                        matchedSlot = true;
                    }

                    if (!isUnavailable) {
                        radio.addEventListener('change', () => {
                            hiddenSlotInput.value = slot.id;
                            selectedSlotLabel.textContent = `Horario seleccionado: ${slot.label}`;
                        });
                    }

                    const infoContainer = document.createElement('div');
                    infoContainer.className = 'flex flex-col text-left';
                    let availabilityText = `${slot.available} lugar(es) disponible(s)`;

                    if (slot.status === 'past') {
                        availabilityText = 'Horario no disponible (pasado)';
                    } else if (slot.status === 'full') {
                        availabilityText = 'Sin lugares disponibles';
                    }

                    infoContainer.innerHTML = `
                        <span class="font-semibold text-slate-800">${slot.label}</span>
                        <span class="text-xs ${isUnavailable ? 'text-gray-400' : 'text-slate-500'}">${availabilityText}</span>
                    `;

                    label.appendChild(radio);
                    label.appendChild(infoContainer);

                    timeSlotsList.appendChild(label);
                });

                if (!matchedSlot) {
                    hiddenSlotInput.value = '';
                    selectedSlotLabel.textContent = '';
                }

                const hasAvailableSlot = slots.some((slot) => slot.status !== 'past' && slot.status !== 'full' && slot.available > 0);
                if (!hasAvailableSlot) {
                    noSlotsMessage.classList.remove('hidden');
                    noSlotsMessage.textContent = 'No hay horarios disponibles para la fecha seleccionada.';
                }
            })
            .catch(() => {
                noSlotsMessage.classList.remove('hidden');
                noSlotsMessage.textContent = 'No se pudieron cargar los horarios disponibles.';
            });
    }
}

function initImageUpload(root) {
    const imageInput = root.querySelector('#imageInput');
    const uploadButton = root.querySelector('#uploadButton');
    const imagePreview = root.querySelector('#imagePreview');
    const imageCount = root.querySelector('#imageCount');

    if (!imageInput || !uploadButton || !imagePreview || !imageCount) {
        return;
    }

    let selectedFiles = new DataTransfer();
    const maxFiles = 5;

    uploadButton.addEventListener('click', () => {
        imageInput.click();
    });

    imageInput.addEventListener('change', (event) => {
        const files = Array.from(event.target.files || []);

        files.forEach((file) => {
            if (selectedFiles.files.length < maxFiles && file.type.startsWith('image/')) {
                selectedFiles.items.add(file);
            }
        });

        updateImagePreview();
        imageInput.files = selectedFiles.files;
    });

    function updateImagePreview() {
        imagePreview.innerHTML = '';
        imageCount.textContent = `${selectedFiles.files.length}/${maxFiles} imágenes`;

        Array.from(selectedFiles.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (event) => {
                const imageContainer = document.createElement('div');
                imageContainer.className = 'group relative overflow-hidden rounded-2xl border border-blue-100 bg-white shadow';

                imageContainer.innerHTML = `
                    <img src="${event.target?.result || ''}" alt="Imagen ${index + 1}" class="h-24 w-full object-cover" />
                    <button type="button" class="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white opacity-0 transition hover:bg-red-600 group-hover:opacity-100" data-remove-index="${index}">×</button>
                    <div class="absolute inset-x-0 bottom-0 bg-black/50 px-2 py-1 text-[11px] text-white truncate">${file.name}</div>
                `;

                imagePreview.appendChild(imageContainer);
            };
            reader.readAsDataURL(file);
        });
    }

    imagePreview.addEventListener('click', (event) => {
        const button = event.target.closest('button[data-remove-index]');
        if (!button) {
            return;
        }

        event.preventDefault();
        const index = Number(button.dataset.removeIndex);
        const files = Array.from(selectedFiles.files);
        files.splice(index, 1);

        selectedFiles = new DataTransfer();
        files.forEach((file) => selectedFiles.items.add(file));

        imageInput.files = selectedFiles.files;
        updateImagePreview();
    });
}

function initHardwareDetails(root) {
    const equipmentSelect = root.querySelector('#tipo_equipo');
    const computerInfo = root.querySelector('#hardwareComputerInfo');
    const printerInfo = root.querySelector('#hardwarePrinterInfo');

    if (!equipmentSelect) {
        return;
    }

    const toggleHardwareDetails = () => {
        const value = equipmentSelect.value;

        if (computerInfo) {
            computerInfo.classList.toggle('hidden', value !== 'Computadora');
        }

        if (printerInfo) {
            printerInfo.classList.toggle('hidden', value !== 'Impresora');
        }
    };

    equipmentSelect.addEventListener('change', toggleHardwareDetails);
    toggleHardwareDetails();
}

function initSoftwareOtherProgram(root) {
    const select = root.querySelector('#nombre_programa');
    const otherProgramWrapper = root.querySelector('#otroPrograma');
    const otherProgramInput = root.querySelector('#otro_programa_nombre');

    if (!select || !otherProgramWrapper || !otherProgramInput) {
        return;
    }

    const toggleOtroPrograma = () => {
        if (select.value === 'Otro') {
            otherProgramWrapper.classList.remove('hidden');
            otherProgramInput.focus();
        } else {
            otherProgramWrapper.classList.add('hidden');
            otherProgramInput.value = '';
        }
    };

    select.addEventListener('change', toggleOtroPrograma);
    toggleOtroPrograma();
}

function setupFormValidation(root, ticketType) {
    const form = root.querySelector('form');
    if (!form) {
        return;
    }

    form.addEventListener('submit', (event) => {
        if (ticketType === 'mantenimiento') {
            const slotId = root.querySelector('#maintenance_slot_id');
            if (!slotId || !slotId.value) {
                alert('Por favor selecciona un horario de mantenimiento antes de crear el ticket.');
                event.preventDefault();
                return;
            }
        }

        if (ticketType === 'hardware') {
            const equipmentType = root.querySelector('#tipo_equipo');
            const descriptionField = root.querySelector('#descripcion_problema');

            if (equipmentType && !equipmentType.value) {
                alert('Selecciona el tipo de equipo que presenta la falla.');
                equipmentType.focus();
                event.preventDefault();
                return;
            }

            if (descriptionField && !descriptionField.value.trim()) {
                alert('Describe la falla del equipo para poder atender tu solicitud.');
                descriptionField.focus();
                event.preventDefault();
            }
        }
    });
}
