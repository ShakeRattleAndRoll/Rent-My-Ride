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

window.setRentRideBadgeCount = setBadgeCount;

function restoreRentRideSubmitScroll() {
    let saved = null;

    try {
        saved = JSON.parse(sessionStorage.getItem('rentRideScrollAfterSubmit') || 'null');
    } catch (error) {
        saved = null;
    }

    if (!saved) return;

    if (saved.url === window.location.href) {
        requestAnimationFrame(() => window.scrollTo(saved.x || 0, saved.y || 0));
    }

    sessionStorage.removeItem('rentRideScrollAfterSubmit');
}

function updateAutoAcceptCard(form, enabled) {
    const panel = form.closest('[data-auto-accept-panel]') || form.closest('details');
    const title = panel?.querySelector('[data-auto-accept-title]');
    const status = panel?.querySelector('[data-auto-accept-status]');
    const toggleLabel = form.querySelector('div span');

    [title, toggleLabel].forEach((element) => {
        if (!element) return;

        element.classList.toggle('text-lime-400', enabled);
        element.classList.toggle('text-gray-500', !enabled);
    });

    if (status) {
        status.textContent = enabled ? 'On' : 'Off';
        status.classList.toggle('text-lime-300', enabled);
        status.classList.toggle('text-gray-500', !enabled);
    }
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

function openAuthRequiredModal() {
    const modal = document.getElementById('auth-required-modal');

    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeAuthRequiredModal() {
    const modal = document.getElementById('auth-required-modal');

    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

window.openDeleteNotifModal = openDeleteNotifModal;
window.closeDeleteNotifModal = closeDeleteNotifModal;
window.openAuthRequiredModal = openAuthRequiredModal;
window.closeAuthRequiredModal = closeAuthRequiredModal;

async function refreshRentRideNotifications() {
    const unreadBadges = document.querySelectorAll('[data-unread-messages-badge]');
    const contactBadges = document.querySelectorAll('[data-contact-unread-badge]');
    const pendingOrderBadges = document.querySelectorAll('[data-pending-orders-badge]');
    const carPendingOrderBadges = document.querySelectorAll('[data-car-pending-orders-badge]');
    const notificationBadges = document.querySelectorAll('[data-unread-notifications-badge]');
    const adminApprovalBadges = document.querySelectorAll('[data-admin-pending-approvals-badge]');

    if (unreadBadges.length === 0 && contactBadges.length === 0 && pendingOrderBadges.length === 0 && carPendingOrderBadges.length === 0 && notificationBadges.length === 0 && adminApprovalBadges.length === 0) return;

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

    if (adminApprovalBadges.length > 0) {
        try {
            const response = await fetch('/admin/cars/pending/items', {
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) return;

            const data = await response.json();
            adminApprovalBadges.forEach((badge) => setBadgeCount(badge, Number(data.count || 0)));
        } catch (error) {
            console.error('Unable to refresh admin approval notifications.', error);
        }
    }
}

function initRentRideNavbar() {
    if (rentRideNavbar.notificationTimer) {
        clearInterval(rentRideNavbar.notificationTimer);
    }

    restoreRentRideSubmitScroll();
    refreshRentRideNotifications();
    rentRideNavbar.notificationTimer = setInterval(refreshRentRideNotifications, 2000);
}

window.refreshRentRideNotifications = refreshRentRideNotifications;

if (!rentRideNavbar.bound) {
    rentRideNavbar.bound = true;

    document.addEventListener('click', (event) => {
        const authRequiredTarget = event.target.closest('[data-auth-required]');

        if (authRequiredTarget) {
            event.preventDefault();
            event.stopPropagation();
            openAuthRequiredModal();
            return;
        }

        if (event.target.closest('[data-auth-modal-close]') || event.target.id === 'auth-required-modal') {
            event.preventDefault();
            closeAuthRequiredModal();
            return;
        }

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

        if (!form || event.defaultPrevented) return;

        if (form.hasAttribute('data-auth-required')) {
            event.preventDefault();
            openAuthRequiredModal();
            return;
        }

        if (!window.Livewire?.navigate) return;

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

            if (preserveScroll) {
                try {
                    sessionStorage.setItem('rentRideScrollAfterSubmit', JSON.stringify({
                        url: targetUrl,
                        x: scrollPosition.x,
                        y: scrollPosition.y,
                    }));
                } catch (error) {
                    // Ignore storage failures; the redirect still needs to happen.
                }
            }

            window.location.replace(targetUrl);
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

            const contentType = response.headers.get('content-type') || '';

            if (contentType.includes('application/json')) {
                const data = await response.json();
                document.dispatchEvent(new CustomEvent('rentride:form-submitted', { detail: { form, data } }));

                if (form.hasAttribute('data-stay-on-submit')) {
                    if (form.hasAttribute('data-auto-accept-form')) {
                        updateAutoAcceptCard(form, Boolean(data.auto_accept));
                    }

                    window.refreshRentRideNotifications?.();
                    return;
                }

                navigateAfterSubmit(data.redirect);
                return;
            }

            if (!response.ok) {
                form.submit();
                return;
            }

            if (response.redirected) {
                document.dispatchEvent(new CustomEvent('rentride:form-submitted', { detail: { form } }));
                navigateAfterSubmit(response.url);
                return;
            }

            document.dispatchEvent(new CustomEvent('rentride:form-submitted', { detail: { form } }));
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
            closeAuthRequiredModal();
        }
    });

    document.addEventListener('DOMContentLoaded', initRentRideNavbar);
    document.addEventListener('livewire:navigated', initRentRideNavbar);
}

initRentRideNavbar();
