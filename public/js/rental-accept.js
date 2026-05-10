function openAcceptModal(orderId, rentUnit, days, totalPrice, startDate, endDate) {
    const fmt = (d) => new Date(d).toLocaleString('en-PH', {
        timeZone: 'Asia/Manila',
        month: 'long', day: 'numeric', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });

    document.getElementById('modalDuration').textContent =
        `${days} ${rentUnit}${days > 1 ? 's' : ''}`;

    document.getElementById('modalStartDate').textContent = fmt(startDate);
    document.getElementById('modalEndDate').textContent   = fmt(endDate);
    document.getElementById('modalTotalPrice').textContent =
        '₱' + Number(totalPrice).toLocaleString('en-PH');

    document.getElementById('acceptForm').action = `/rental/${orderId}/accept`;

    document.getElementById('acceptModal').classList.remove('hidden');
}

function closeAcceptModal() {
    document.getElementById('acceptModal').classList.add('hidden');
}