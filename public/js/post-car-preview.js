(() => {
    const fields = {
        image:        document.querySelector('#car_image'),
        brand:        document.querySelector('[data-preview-source="brand"]'),
        model:        document.querySelector('[data-preview-source="model"]'),
        date:         document.querySelector('[data-preview-source="date"]'),
        price:        document.querySelector('[data-preview-source="price"]'),
        unit:         document.querySelector('[data-preview-source="unit"]'),
        fuel:         document.querySelector('[data-preview-source="fuel"]'),
        transmission: document.querySelector('[data-preview-source="transmission"]'),
    };

    const preview = {
        image:        document.querySelector('#car-image-preview'),
        brand:        document.querySelector('#preview-brand'),
        model:        document.querySelector('#preview-model'),
        date:         document.querySelector('#preview-date'),
        price:        document.querySelector('#preview-price'),
        unit:         document.querySelector('#preview-unit'),
        fuel:         document.querySelector('#preview-fuel'),
        transmission: document.querySelector('#preview-transmission'),
    };

    const formatDate = (value) => {
        if (!value) return 'Date owned';
        const date = new Date(`${value}T00:00:00`);
        return Number.isNaN(date.getTime())
            ? 'Date owned'
            : date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    };

    const refreshPreview = () => {
        preview.brand.textContent        = fields.brand?.value || 'Car Brand';
        preview.model.textContent        = fields.model?.value || 'Model';
        preview.date.textContent         = formatDate(fields.date?.value);
        preview.price.textContent        = fields.price?.value
            ? `PHP ${Number(fields.price.value).toLocaleString()}`
            : 'PHP --';
        preview.unit.textContent         = fields.unit?.value         || 'Day';
        preview.fuel.textContent         = fields.fuel?.value         || 'Gasoline';
        preview.transmission.textContent = fields.transmission?.value || 'Automatic';
    };

    Object.entries(fields).forEach(([key, field]) => {
        if (key === 'image') return;
        field?.addEventListener('input',  refreshPreview);
        field?.addEventListener('change', refreshPreview);
    });

    fields.image?.addEventListener('change', () => {
        const file = fields.image.files?.[0];
        if (!file || !preview.image) return;

        const reader = new FileReader();
        reader.onload = () => {
            preview.image.innerHTML = `<img src="${reader.result}" alt="" class="h-full w-full object-cover">`;
        };
        reader.readAsDataURL(file);
    });

    refreshPreview();
})();