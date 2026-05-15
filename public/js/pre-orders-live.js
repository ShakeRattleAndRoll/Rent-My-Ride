var rentRidePreOrdersLive = window.rentRidePreOrdersLive || (window.rentRidePreOrdersLive = {});

function initPreOrdersLive() {
    const root = document.querySelector('[data-pre-orders-list]');

    if (rentRidePreOrdersLive.timer) {
        clearInterval(rentRidePreOrdersLive.timer);
        rentRidePreOrdersLive.timer = null;
    }

    if (!root) return;

    const refreshUrl = root.dataset.refreshUrl;
    let lastHtml = root.innerHTML;

    const refresh = async () => {
        const acceptModal = document.getElementById('acceptModal');
        if (acceptModal && !acceptModal.classList.contains('hidden')) {
            return;
        }

        try {
            const response = await fetch(refreshUrl, {
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) return;

            const data = await response.json();

            if (data.html && data.html !== lastHtml) {
                root.innerHTML = data.html;
                lastHtml = data.html;
                window.sortTable?.(document.getElementById('sort-select')?.value || 'fcfs');
            }
        } catch (error) {
            console.error('Unable to refresh pre-orders.', error);
        }
    };

    refresh();
    rentRidePreOrdersLive.timer = setInterval(refresh, 2000);
}

if (!rentRidePreOrdersLive.bound) {
    rentRidePreOrdersLive.bound = true;
    document.addEventListener('DOMContentLoaded', initPreOrdersLive);
    document.addEventListener('livewire:navigated', initPreOrdersLive);
    document.addEventListener('rentride:form-submitted', initPreOrdersLive);
}

initPreOrdersLive();
