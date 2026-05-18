var rentRideMyListings = window.rentRideMyListings || (window.rentRideMyListings = {});

function initRentRideMyListings() {
    const root = document.querySelector('[data-owner-listings]');

    if (rentRideMyListings.timer) {
        clearInterval(rentRideMyListings.timer);
        rentRideMyListings.timer = null;
    }

    if (!root) return;

    const refreshUrl = root.dataset.refreshUrl;
    let lastHtml = root.innerHTML;

    const refresh = async () => {
        if (document.querySelector('.fixed.inset-0:not(.hidden)')) return;

        try {
            const response = await fetch(refreshUrl, {
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) return;

            const data = await response.json();

            if (data.html && data.html !== lastHtml) {
                root.innerHTML = data.html;
                lastHtml = data.html;
            }
        } catch (error) {
            console.error('Unable to refresh listings.', error);
        }
    };

    refresh();
    rentRideMyListings.timer = setInterval(refresh, 2000);
}

if (!rentRideMyListings.bound) {
    rentRideMyListings.bound = true;
    document.addEventListener('DOMContentLoaded', initRentRideMyListings);
    document.addEventListener('livewire:navigated', initRentRideMyListings);
    document.addEventListener('rentride:form-submitted', initRentRideMyListings);
}

initRentRideMyListings();
