const unitToMs = {
    'Hour':  1000 * 60 * 60,
    'Day':   1000 * 60 * 60 * 24,
    'Week':  1000 * 60 * 60 * 24 * 7,
    'Month': 1000 * 60 * 60 * 24 * 30,
};

/* =========================
   LIMIT FUNCTION
========================= */
function setMaxLimit(rentUnit) {
    const input = document.getElementById('days');

    const limits = {
        Hour: 24,
        Day: 31,
        Week: 4,
        Month: 12
    };

    input.max = limits[rentUnit] || 30;
}

/* =========================
   RENT MODAL
========================= */
function openRentModal(cartId, rentUnit, pricePerUnit) {
    const modal = document.getElementById('rentModal');
    const form  = document.getElementById('rentForm');

    form.action = `/cart/checkout/${cartId}`;

    document.getElementById('cart_id_hidden').value = cartId;
    document.getElementById('rent_unit_hidden').value = rentUnit;
    document.getElementById('price_per_unit_hidden').value = pricePerUnit;

    setMaxLimit(rentUnit);

    document.getElementById('unitLabel').textContent = rentUnit + 's';
    document.getElementById('unitLabelSub').textContent = rentUnit.toLowerCase() + 's';

    document.getElementById('days').value = '';
    document.getElementById('totalPrice').textContent = '₱0';
    document.getElementById('displayDays').innerHTML =
        `<i class="fa-regular fa-clock text-gray-500"></i> 0 ${rentUnit}s`;

    modal.classList.remove('hidden');
}

function closeRentModal() {
    document.getElementById('rentModal').classList.add('hidden');
}

/* =========================
   INPUT CONTROL
========================= */
document.addEventListener('DOMContentLoaded', function () {
    const daysInput = document.getElementById('days');
    if (!daysInput) return;

    daysInput.addEventListener('input', function () {
        const max = parseInt(this.max);

        // clamp value
        if (parseInt(this.value) > max) {
            this.value = max;
        }

        const count = parseInt(this.value);
        const rentUnit = document.getElementById('rent_unit_hidden').value;
        const price = parseFloat(document.getElementById('price_per_unit_hidden').value);
        const display = document.getElementById('displayDays');

        if (!isNaN(count) && count > 0) {
            document.getElementById('totalPrice').textContent =
                '₱' + (count * price).toLocaleString('en-PH');

            display.innerHTML = `
                <i class="fa-regular fa-clock text-gray-500"></i>
                ${count} ${rentUnit}${count > 1 ? 's' : ''}
            `;
        } else {
            document.getElementById('totalPrice').textContent = '₱0';
            display.innerHTML = `
                <i class="fa-regular fa-clock text-gray-500"></i>
                0 ${rentUnit || 'Day'}s
            `;
        }
    });

    // block typing overflow
    daysInput.addEventListener('keydown', function (e) {
        const max = parseInt(this.max);
        const value = parseInt(this.value || 0);

        const allowed = ['Backspace','ArrowLeft','ArrowRight','Tab','Delete'];
        if (allowed.includes(e.key)) return;

        if (value >= max) {
            e.preventDefault();
        }
    });
});