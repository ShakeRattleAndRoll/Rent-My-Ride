var rentRideRentals = window.rentRideRentals || (window.rentRideRentals = {});

function initRentRideRentals() {
    const refreshRoot = document.querySelector('[data-rentals-live-refresh]');
    const rows = refreshRoot?.querySelectorAll('[data-rental-row]') || [];

    if (rentRideRentals.statusTimer) {
        clearInterval(rentRideRentals.statusTimer);
        rentRideRentals.statusTimer = null;
    }

    const openRequestedRentalDetails = () => {
        const rentalId = new URLSearchParams(window.location.search).get('rental');
        if (!rentalId) return;

        const modal = document.getElementById(`details-modal-${rentalId}`);
        if (modal) {
            modal.classList.remove('hidden');
            rentRideRentals.openedRentalId = rentalId;

            const url = new URL(window.location.href);
            url.searchParams.delete('rental');
            window.history.replaceState({}, '', url);
        }
    };

    openRequestedRentalDetails();

    if (!refreshRoot) return;

    if (rows.length === 0) return;

    const refreshRentalStatuses = async () => {
        const currentRows = refreshRoot.querySelectorAll('[data-rental-row]');
        if (currentRows.length === 0) return;

        try {
            const response = await fetch('/rentals/my-statuses', {
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) return;

            const data = await response.json();
            const activeIds = new Set();

            (data.rentals || []).forEach((rental) => {
                activeIds.add(String(rental.id));

                const row = refreshRoot.querySelector(`[data-rental-row="${rental.id}"]`);
                if (!row || row.dataset.rentalStatus === rental.status_key) return;

                row.dataset.rentalStatus = rental.status_key;
                row.innerHTML = rental.html;
            });

            currentRows.forEach((row) => {
                if (!activeIds.has(row.dataset.rentalRow)) {
                    row.remove();
                }
            });

            if (!rentRideRentals.openedRentalId) {
                openRequestedRentalDetails();
            }
        } catch (error) {
            console.error('Unable to refresh rental statuses.', error);
        }
    };

    refreshRentalStatuses();
    rentRideRentals.statusTimer = setInterval(refreshRentalStatuses, 2000);
}

if (!rentRideRentals.bound) {
    rentRideRentals.bound = true;
    document.addEventListener('DOMContentLoaded', initRentRideRentals);
    document.addEventListener('livewire:navigated', initRentRideRentals);
}

initRentRideRentals();
