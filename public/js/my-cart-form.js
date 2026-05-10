function setMaxLimit(rentUnit) {
    const input = document.getElementById('days');
    if (!input) return;

    const limits = {
        Hour: 24,
        Day: 31,
        Week: 4,
        Month: 12
    };

    input.max = limits[rentUnit] || 30;
}

function setMinStartDate() {
    const input = document.getElementById('startDate');
    if (!input) return;

    const now = new Date();
    const offset = now.getTimezoneOffset() * 60000;
    const localISO = new Date(now - offset).toISOString().slice(0, 16);
    input.min = localISO;

    if (!input.value) {
        input.value = localISO;
    }
}

function formatDisplayDate(date) {
    return date.toLocaleString('en-PH', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
}

function computeEndDate(startDate, count, rentUnit) {
    const end = new Date(startDate);

    switch (rentUnit) {
        case 'Hour':  end.setHours(end.getHours() + count);       break;
        case 'Day':   end.setDate(end.getDate() + count);          break;
        case 'Week':  end.setDate(end.getDate() + count * 7);      break;
        case 'Month': end.setMonth(end.getMonth() + count);        break;
        default:      end.setDate(end.getDate() + count);
    }

    return end;
}

function toLocalDatetimeString(date) {
    const pad = (n) => String(n).padStart(2, '0');
    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}` +
           `T${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
}

function updateRentTotal() {
    const daysInput = document.getElementById('days');
    if (!daysInput) return;

    const max = parseInt(daysInput.max);
    if (parseInt(daysInput.value) > max) daysInput.value = max;

    const count    = parseInt(daysInput.value);
    const rentUnit = document.getElementById('rent_unit_hidden').value;
    const price    = parseFloat(document.getElementById('price_per_unit_hidden').value);
    const display  = document.getElementById('displayDays');

    if (!isNaN(count) && count > 0) {
        document.getElementById('totalPrice').textContent =
            '\u20b1' + (count * price).toLocaleString('en-PH');

        display.innerHTML = `
            <i class="fa-regular fa-clock text-gray-500"></i>
            ${count} ${rentUnit}${count > 1 ? 's' : ''}
        `;
    } else {
        document.getElementById('totalPrice').textContent = '\u20b10';
        display.innerHTML = `
            <i class="fa-regular fa-clock text-gray-500"></i>
            0 ${rentUnit || 'Day'}s
        `;
    }

    updateDateRangeDisplay();
}

function updateDateRangeDisplay() {
    const startInput = document.getElementById('startDate');
    const daysInput  = document.getElementById('days');

    if (!startInput || !daysInput) return;

    const count    = parseInt(daysInput.value);
    const rentUnit = document.getElementById('rent_unit_hidden').value;
    const endEl    = document.getElementById('displayEnd');

    if (!startInput.value || isNaN(count) || count < 1) {
        if (endEl) endEl.textContent = '—';
        clearDateHiddenFields();
        return;
    }

    const start = new Date(startInput.value);
    const end   = computeEndDate(start, count, rentUnit);

    if (endEl) endEl.textContent = formatDisplayDate(end);

    // Save as local datetime strings (no UTC shift)
    document.getElementById('start_date_hidden').value = toLocalDatetimeString(start);
    document.getElementById('end_date_hidden').value   = toLocalDatetimeString(end);
}

function clearDateHiddenFields() {
    const s = document.getElementById('start_date_hidden');
    const e = document.getElementById('end_date_hidden');
    if (s) s.value = '';
    if (e) e.value = '';
}

function openRentModal(cartId, rentUnit, pricePerUnit) {
    const modal = document.getElementById('rentModal');
    const form  = document.getElementById('rentForm');
    if (!modal || !form) return;

    form.action = `/cart/checkout/${cartId}`;

    document.getElementById('cart_id_hidden').value        = cartId;
    document.getElementById('rent_unit_hidden').value      = rentUnit;
    document.getElementById('price_per_unit_hidden').value = pricePerUnit;

    setMaxLimit(rentUnit);
    setMinStartDate();

    document.getElementById('unitLabel').textContent    = rentUnit + 's';
    document.getElementById('unitLabelSub').textContent = rentUnit.toLowerCase() + 's';

    // Reset fields
    document.getElementById('days').value             = '';
    document.getElementById('totalPrice').textContent = '\u20b10';
    document.getElementById('displayDays').innerHTML  =
        `<i class="fa-regular fa-clock text-gray-500"></i> 0 ${rentUnit}s`;

    const endEl = document.getElementById('displayEnd');
    if (endEl) endEl.textContent = '—';
    clearDateHiddenFields();

    bindRentModalInput();
    modal.classList.remove('hidden');
}

function closeRentModal() {
    const modal = document.getElementById('rentModal');
    if (modal) modal.classList.add('hidden');
}

function bindRentModalInput() {
    const daysInput  = document.getElementById('days');
    const startInput = document.getElementById('startDate');

    // Bind duration input
    if (daysInput && daysInput.dataset.rentModalBound !== 'true') {
        daysInput.dataset.rentModalBound = 'true';

        daysInput.addEventListener('input', updateRentTotal);

        daysInput.addEventListener('keydown', function (e) {
            const max     = parseInt(this.max);
            const value   = parseInt(this.value || 0);
            const allowed = ['Backspace', 'ArrowLeft', 'ArrowRight', 'Tab', 'Delete'];
            if (allowed.includes(e.key)) return;
            if (value >= max) e.preventDefault();
        });
    }

    // Bind start date input
    if (startInput && startInput.dataset.rentModalBound !== 'true') {
        startInput.dataset.rentModalBound = 'true';
        startInput.addEventListener('change', updateDateRangeDisplay);
    }
}

document.addEventListener('DOMContentLoaded', bindRentModalInput);
document.addEventListener('livewire:navigated', bindRentModalInput);