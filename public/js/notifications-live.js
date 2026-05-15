var rentRideNotificationsLive = window.rentRideNotificationsLive || (window.rentRideNotificationsLive = {});

function initNotificationsLive() {
    const root = document.querySelector('[data-notifications-list]');

    if (rentRideNotificationsLive.timer) {
        clearInterval(rentRideNotificationsLive.timer);
        rentRideNotificationsLive.timer = null;
    }

    if (!root) return;

    const refreshUrl = root.dataset.refreshUrl;
    let lastHtml = root.innerHTML;

    const refresh = async () => {
        const deleteModal = document.getElementById('delete-notification-modal');

        if (deleteModal && !deleteModal.classList.contains('hidden')) {
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
            }

            document.querySelectorAll('[data-unread-notifications-badge]').forEach((badge) => {
                window.setRentRideBadgeCount?.(badge, Number(data.unread_notifications || 0));
            });
        } catch (error) {
            console.error('Unable to refresh notifications list.', error);
        }
    };

    refresh();
    rentRideNotificationsLive.timer = setInterval(refresh, 2000);
}

if (!rentRideNotificationsLive.bound) {
    rentRideNotificationsLive.bound = true;
    document.addEventListener('DOMContentLoaded', initNotificationsLive);
    document.addEventListener('livewire:navigated', initNotificationsLive);
    document.addEventListener('rentride:form-submitted', initNotificationsLive);
}

initNotificationsLive();
