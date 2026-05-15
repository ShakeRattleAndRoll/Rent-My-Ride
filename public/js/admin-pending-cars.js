var rentRideAdminPendingCars = window.rentRideAdminPendingCars || (window.rentRideAdminPendingCars = {});

function initAdminPendingCars() {
    const root = document.querySelector('[data-admin-pending-cars]');

    if (rentRideAdminPendingCars.timer) {
        clearInterval(rentRideAdminPendingCars.timer);
        rentRideAdminPendingCars.timer = null;
    }

    if (!root) return;

    const refreshUrl = root.dataset.refreshUrl;
    let lastHtml = root.innerHTML;

    const refresh = async () => {
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

            document.querySelectorAll('[data-admin-pending-count]').forEach((element) => {
                element.textContent = Number(data.count || 0).toString();
            });

            document.querySelectorAll('[data-admin-pending-approvals-badge]').forEach((badge) => {
                window.setRentRideBadgeCount?.(badge, Number(data.count || 0));
            });
        } catch (error) {
            console.error('Unable to refresh pending car posts.', error);
        }
    };

    refresh();
    rentRideAdminPendingCars.timer = setInterval(refresh, 2000);
}

if (!rentRideAdminPendingCars.bound) {
    rentRideAdminPendingCars.bound = true;
    document.addEventListener('DOMContentLoaded', initAdminPendingCars);
    document.addEventListener('livewire:navigated', initAdminPendingCars);
    document.addEventListener('rentride:form-submitted', initAdminPendingCars);
}

initAdminPendingCars();
