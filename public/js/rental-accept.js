const unitToMs = {
    'Hour':  1000 * 60 * 60,
    'Day':   1000 * 60 * 60 * 24,
    'Week':  1000 * 60 * 60 * 24 * 7,
    'Month': 1000 * 60 * 60 * 24 * 30,
};

function openAcceptModal(orderId, rentUnit, days, totalPrice) {
    const now = new Date();
    const ms = unitToMs[rentUnit] || unitToMs['Day'];
    const endDate = new Date(now.getTime() + days * ms);

    const fmt = (d) => d.toLocaleString('en-PH', {
        month: 'long', day: 'numeric', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });

    document.getElementById('modalDuration').textContent =
        `${days} ${rentUnit}${days > 1 ? 's' : ''}`;

    document.getElementById('modalStartDate').textContent = fmt(now);
    document.getElementById('modalEndDate').textContent = fmt(endDate);
    document.getElementById('modalTotalPrice').textContent =
        '₱' + Number(totalPrice).toLocaleString('en-PH');

    document.getElementById('acceptForm').action = `/rental/${orderId}/accept`;

    document.getElementById('acceptModal').classList.remove('hidden');
}

function closeAcceptModal() {
    document.getElementById('acceptModal').classList.add('hidden');
}