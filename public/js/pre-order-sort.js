function sortTable(mode) {
    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.classList.remove('bg-yellow-400', 'text-black');
        btn.classList.add('bg-[#1a1a1a]', 'text-gray-400', 'border', 'border-gray-700');
    });

    const active = document.getElementById('btn-' + mode);
    active.classList.add('bg-yellow-400', 'text-black');
    active.classList.remove('bg-[#1a1a1a]', 'text-gray-400', 'border', 'border-gray-700');

    const tbody = document.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr[data-created]'));

    rows.sort((a, b) => {
        if (mode === 'fcfs') {
            return parseInt(a.dataset.created) - parseInt(b.dataset.created);
        } else if (mode === 'longest') {
            return parseInt(b.dataset.days) - parseInt(a.dataset.days);
        } else if (mode === 'shortest') {
            return parseInt(a.dataset.days) - parseInt(b.dataset.days);
        }
    });

    rows.forEach(row => tbody.appendChild(row));
}

document.addEventListener('DOMContentLoaded', () => sortTable('fcfs'));