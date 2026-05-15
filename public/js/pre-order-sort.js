function sortTable(mode) {
    const select = document.getElementById('sort-select');
    if (select && select.value !== mode) {
        select.value = mode;
    }

    const tbody = document.querySelector('tbody');
    if (!tbody) return;

    const rows = Array.from(tbody.querySelectorAll('tr[data-created]'));

    const numberValue = (row, key) => Number(row.dataset[key] || 0);
    const byFirstPending = (a, b) =>
        numberValue(a, 'created') - numberValue(b, 'created') ||
        numberValue(a, 'id') - numberValue(b, 'id');

    rows.sort((a, b) => {
        if (mode === 'fcfs') {
            return byFirstPending(a, b);
        } else if (mode === 'longest') {
            return numberValue(b, 'duration') - numberValue(a, 'duration') || byFirstPending(a, b);
        } else if (mode === 'shortest') {
            return numberValue(a, 'duration') - numberValue(b, 'duration') || byFirstPending(a, b);
        } else if (mode === 'nearest') {
            return numberValue(a, 'start') - numberValue(b, 'start') || byFirstPending(a, b);
        }

        return byFirstPending(a, b);
    });

    rows.forEach(row => tbody.appendChild(row));
}

function initPreOrderSort() {
    const select = document.getElementById('sort-select');
    sortTable(select?.value || 'fcfs');
}

document.addEventListener('DOMContentLoaded', initPreOrderSort);
document.addEventListener('livewire:navigated', initPreOrderSort);
