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

function openRentModal(cartId, rentUnit, pricePerUnit) {
    const modal = document.getElementById('rentModal');
    const form = document.getElementById('rentForm');
    if (!modal || !form) return;

    form.action = `/cart/checkout/${cartId}`;

    document.getElementById('cart_id_hidden').value = cartId;
    document.getElementById('rent_unit_hidden').value = rentUnit;
    document.getElementById('price_per_unit_hidden').value = pricePerUnit;

    setMaxLimit(rentUnit);

    document.getElementById('unitLabel').textContent = rentUnit + 's';
    document.getElementById('unitLabelSub').textContent = rentUnit.toLowerCase() + 's';

    document.getElementById('days').value = '';
    document.getElementById('totalPrice').textContent = '\u20b10';
    document.getElementById('displayDays').innerHTML =
        `<i class="fa-regular fa-clock text-gray-500"></i> 0 ${rentUnit}s`;

    bindRentModalInput();
    modal.classList.remove('hidden');
}

function closeRentModal() {
    const modal = document.getElementById('rentModal');
    if (modal) modal.classList.add('hidden');
}

function updateRentTotal() {
    const daysInput = document.getElementById('days');
    if (!daysInput) return;

    const max = parseInt(daysInput.max);

    if (parseInt(daysInput.value) > max) {
        daysInput.value = max;
    }

    const count = parseInt(daysInput.value);
    const rentUnit = document.getElementById('rent_unit_hidden').value;
    const price = parseFloat(document.getElementById('price_per_unit_hidden').value);
    const display = document.getElementById('displayDays');

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
}

function bindRentModalInput() {
    const daysInput = document.getElementById('days');
    if (!daysInput || daysInput.dataset.rentModalBound === 'true') return;

    daysInput.dataset.rentModalBound = 'true';
    daysInput.addEventListener('input', updateRentTotal);

    daysInput.addEventListener('keydown', function (e) {
        const max = parseInt(this.max);
        const value = parseInt(this.value || 0);

        const allowed = ['Backspace', 'ArrowLeft', 'ArrowRight', 'Tab', 'Delete'];
        if (allowed.includes(e.key)) return;

        if (value >= max) {
            e.preventDefault();
        }
    });
}

document.addEventListener('DOMContentLoaded', bindRentModalInput);
document.addEventListener('livewire:navigated', bindRentModalInput);
