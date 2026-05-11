var rentRideNavbar = window.rentRideNavbar || (window.rentRideNavbar = {});

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if (sidebar && overlay) {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
}

function toggleGarageDropdown() {
    const dropdown = document.getElementById('garageDropdown');
    const arrow = document.getElementById('garageArrow');
    if (!dropdown || !arrow) return;

    dropdown.classList.toggle('hidden');
    dropdown.classList.toggle('flex');
    arrow.classList.toggle('rotate-180');
}

function setBadgeCount(badge, count) {
    if (!badge) return;

    const hasCount = count > 0;
    badge.textContent = count > 99 ? '99+' : String(count);
    badge.classList.toggle('hidden', !hasCount);
    badge.classList.toggle('flex', hasCount);
}

function openDeleteNotifModal(actionUrl, deleteAll = false) {
    const form = document.getElementById('delete-notification-form');
    const modal = document.getElementById('delete-notification-modal');
    const title = document.getElementById('delete-notification-title');
    const message = document.getElementById('delete-notification-message');
    const submit = document.getElementById('delete-notification-submit');

    if (!form || !modal) return;

    form.action = actionUrl;
    form.dataset.deleteAll = deleteAll ? '1' : '0';

    if (title) {
        title.textContent = deleteAll ? 'Delete All Notifications' : 'Delete Notification';
    }

    if (message) {
        message.textContent = deleteAll
            ? 'All notifications will be removed from your list.'
            : 'This notification will be removed from your list.';
    }

    if (submit) {
        submit.textContent = deleteAll ? 'Delete All' : 'Delete';
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteNotifModal() {
    const modal = document.getElementById('delete-notification-modal');

    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

window.openDeleteNotifModal = openDeleteNotifModal;
window.closeDeleteNotifModal = closeDeleteNotifModal;

async function refreshRentRideNotifications() {
    const unreadBadges = document.querySelectorAll('[data-unread-messages-badge]');
    const contactBadges = document.querySelectorAll('[data-contact-unread-badge]');
    const pendingOrderBadges = document.querySelectorAll('[data-pending-orders-badge]');
    const carPendingOrderBadges = document.querySelectorAll('[data-car-pending-orders-badge]');
    const notificationBadges = document.querySelectorAll('[data-unread-notifications-badge]');

    if (unreadBadges.length === 0 && contactBadges.length === 0 && pendingOrderBadges.length === 0 && carPendingOrderBadges.length === 0 && notificationBadges.length === 0) return;

    if (unreadBadges.length > 0 || contactBadges.length > 0) {
        try {
            const response = await fetch('/messages/notifications', {
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) return;

            const data = await response.json();
            unreadBadges.forEach((badge) => setBadgeCount(badge, Number(data.total_unread_messages || 0)));

            contactBadges.forEach((badge) => setBadgeCount(badge, 0));
            (data.contacts || []).forEach((contact) => {
                const badge = document.querySelector(`[data-contact-unread-badge="${contact.id}"]`);
                setBadgeCount(badge, Number(contact.unread_count || 0));
            });
        } catch (error) {
            console.error('Unable to refresh message notifications.', error);
        }
    }

    if (pendingOrderBadges.length > 0 || carPendingOrderBadges.length > 0) {
        try {
            const response = await fetch('/rentals/notifications', {
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) return;

            const data = await response.json();
            pendingOrderBadges.forEach((badge) => setBadgeCount(badge, Number(data.total_pending_orders || 0)));

            carPendingOrderBadges.forEach((badge) => setBadgeCount(badge, 0));
            (data.cars || []).forEach((car) => {
                const badges = document.querySelectorAll(`[data-car-pending-orders-badge="${car.id}"]`);
                badges.forEach((badge) => setBadgeCount(badge, Number(car.pending_orders_count || 0)));
            });
        } catch (error) {
            console.error('Unable to refresh rental notifications.', error);
        }
    }

    if (notificationBadges.length > 0) {
        try {
            const response = await fetch('/notifications/count', {
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) return;

            const data = await response.json();
            notificationBadges.forEach((badge) => setBadgeCount(badge, Number(data.unread_notifications || 0)));
        } catch (error) {
            console.error('Unable to refresh notifications.', error);
        }
    }
}

function initRentRideNavbar() {
    if (rentRideNavbar.notificationTimer) {
        clearInterval(rentRideNavbar.notificationTimer);
    }

    refreshRentRideNotifications();
    rentRideNavbar.notificationTimer = setInterval(refreshRentRideNotifications, 2000);
}

window.refreshRentRideNotifications = refreshRentRideNotifications;

if (!rentRideNavbar.bound) {
    rentRideNavbar.bound = true;

    document.addEventListener('click', (event) => {
        const link = event.target.closest('[data-nav-navigate]');

        if (!link || event.defaultPrevented || !window.Livewire?.navigate) return;

        event.preventDefault();

        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (sidebar && overlay && !sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        window.Livewire.navigate(link.href);
    });

    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('[data-livewire-form]');

        if (!form || event.defaultPrevented || !window.Livewire?.navigate) return;

        event.preventDefault();

        const message = form.dataset.confirm;

        if (message && !window.confirm(message)) {
            return;
        }

        const submitButton = event.submitter || form.querySelector('button[type="submit"]');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const preserveScroll = form.hasAttribute('data-preserve-scroll');
        const scrollPosition = {
            x: window.scrollX,
            y: window.scrollY,
        };

        const navigateAfterSubmit = (url) => {
            const targetUrl = url || window.location.href;

            if (!preserveScroll) {
                window.Livewire.navigate(targetUrl, { scroll: false });
                return;
            }

            const restoreScroll = () => {
                window.scrollTo(scrollPosition.x, scrollPosition.y);
            };

            document.addEventListener('livewire:navigated', restoreScroll, { once: true });
            window.Livewire.navigate(targetUrl, { scroll: false });
            requestAnimationFrame(() => requestAnimationFrame(restoreScroll));
        };

        if (submitButton) {
            submitButton.disabled = true;
        }

        try {
            const response = await fetch(form.action, {
                method: form.method || 'POST',
                headers: {
                    Accept: 'application/json',
                    ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                },
                body: new FormData(form),
            });

            if (form.id === 'delete-notification-form') {
                closeDeleteNotifModal();
            }

            if (response.redirected) {
                navigateAfterSubmit(response.url);
                return;
            }

            const contentType = response.headers.get('content-type') || '';

            if (contentType.includes('application/json')) {
                const data = await response.json();
                navigateAfterSubmit(data.redirect);
                return;
            }

            navigateAfterSubmit(window.location.href);
        } catch (error) {
            form.submit();
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
            }
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeDeleteNotifModal();
        }
    });

    document.addEventListener('DOMContentLoaded', initRentRideNavbar);
    document.addEventListener('livewire:navigated', initRentRideNavbar);
}

initRentRideNavbar();
