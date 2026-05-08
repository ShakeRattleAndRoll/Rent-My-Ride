var rentRideRentals = window.rentRideRentals || (window.rentRideRentals = {});

function initRentRideRentals() {
    const rows = document.querySelectorAll('[data-rental-row]');

    if (rentRideRentals.statusTimer) {
        clearInterval(rentRideRentals.statusTimer);
        rentRideRentals.statusTimer = null;
    }

    if (rows.length === 0) return;

    const refreshRentalStatuses = async () => {
        const currentRows = document.querySelectorAll('[data-rental-row]');
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

                const row = document.querySelector(`[data-rental-row="${rental.id}"]`);
                if (!row || row.dataset.rentalStatus === rental.status_key) return;

                row.dataset.rentalStatus = rental.status_key;
                row.innerHTML = rental.html;
            });

            currentRows.forEach((row) => {
                if (!activeIds.has(row.dataset.rentalRow)) {
                    row.remove();
                }
            });
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
