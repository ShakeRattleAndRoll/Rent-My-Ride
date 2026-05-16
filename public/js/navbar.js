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

const rentRideFlashKey = 'rentRidePendingFlashes';

function rentRideFlashClasses(type) {
    if (type === 'error') {
        return {
            box: 'bg-red-600 text-white border-red-500',
            close: 'text-white/40 hover:text-white',
            icon: '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>',
        };
    }

    if (type === 'status') {
        return {
            box: 'bg-blue-500 text-white border-blue-400',
            close: 'text-white/40 hover:text-white',
            icon: '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"></path>',
        };
    }

    return {
        box: 'bg-lime-500 text-black border-lime-400',
        close: 'text-black/40 hover:text-black',
        icon: '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>',
    };
}

function ensureRentRideFlashContainer() {
    let container = document.getElementById('rentride-client-flashes');

    if (container) return container;

    container = document.createElement('div');
    container.id = 'rentride-client-flashes';
    container.className = 'fixed-center-x fixed top-5 z-[140] w-full max-w-md px-4 pointer-events-none';
    document.body.appendChild(container);

    return container;
}

function showRentRideFlash(type, message) {
    if (!message) return;

    const container = ensureRentRideFlashContainer();
    const classes = rentRideFlashClasses(type);
    const alert = document.createElement('div');

    alert.className = `pointer-events-auto ${classes.box} px-6 py-4 rounded-2xl shadow-2xl flex items-center justify-between border mb-3 transition duration-300`;
    alert.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                ${classes.icon}
            </svg>
            <span class="font-['Montserrat'] font-semibold uppercase text-[11px] tracking-widest leading-none pt-[1px]"></span>
        </div>
        <button type="button" class="${classes.close} transition-colors ml-4">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
            </svg>
        </button>
    `;

    alert.querySelector('span').textContent = message;
    alert.querySelector('button').addEventListener('click', () => alert.remove());
    container.appendChild(alert);

    window.setTimeout(() => {
        alert.classList.add('opacity-0', '-translate-y-4');
        window.setTimeout(() => alert.remove(), 300);
    }, 3000);
}

function storeRentRideFlash(type, message) {
    if (!message) return;

    let flashes = [];

    try {
        flashes = JSON.parse(sessionStorage.getItem(rentRideFlashKey) || '[]');
    } catch (error) {
        flashes = [];
    }

    flashes.push({ type, message });
    sessionStorage.setItem(rentRideFlashKey, JSON.stringify(flashes));
}

function storeRentRideFlashesFromHtml(html) {
    if (!html) return;

    const doc = new DOMParser().parseFromString(html, 'text/html');

    doc.querySelectorAll('[data-rentride-flash]').forEach((flash) => {
        storeRentRideFlash(
            flash.dataset.flashType || 'success',
            flash.dataset.flashMessage || flash.textContent.trim()
        );
    });
}

function showStoredRentRideFlashes() {
    let flashes = [];

    try {
        flashes = JSON.parse(sessionStorage.getItem(rentRideFlashKey) || '[]');
    } catch (error) {
        flashes = [];
    }

    sessionStorage.removeItem(rentRideFlashKey);
    flashes.forEach((flash) => showRentRideFlash(flash.type, flash.message));
}

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

        element.classList.remove('text-lime-400', 'text-white', 'text-gray-500');
        element.classList.add(enabled ? 'text-lime-400' : 'text-white');
    });

    if (status) {
        status.textContent = enabled ? 'On' : 'Off';
        status.classList.remove('text-lime-300', 'text-gray-500');
        status.classList.add(enabled ? 'text-lime-300' : 'text-gray-500');
    }
}

function updateAvailabilityCard(form, enabled) {
    const panel = form.closest('[data-availability-panel]');
    const card = form.closest('.group') || form.closest('[data-listing-card]');
    const title = panel?.querySelector('[data-availability-title]');
    const status = panel?.querySelector('[data-availability-status]');
    const badge = card?.querySelector('[data-availability-badge]');

    if (title) {
        title.classList.remove('text-lime-400', 'text-white', 'text-gray-500');
        title.classList.add(enabled ? 'text-lime-400' : 'text-white');
    }

    if (status) {
        status.textContent = enabled ? 'On' : 'Off';
        status.classList.remove('text-lime-300', 'text-gray-500');
        status.classList.add(enabled ? 'text-lime-300' : 'text-gray-500');
    }

    if (badge) {
        badge.textContent = enabled ? 'Visible' : 'Hidden';
        badge.classList.remove('bg-lime-400', 'bg-red-500', 'text-black', 'text-white');
        badge.classList.add(enabled ? 'bg-lime-400' : 'bg-red-500', enabled ? 'text-black' : 'text-white');
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
    showStoredRentRideFlashes();
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

        if (form.hasAttribute('data-livewire-html')) {
            return;
        }

        event.preventDefault();

        const message = form.dataset.confirm;

        if (message && !window.confirm(message)) {
            return;
        }

        const submitButton = event.submitter || form.querySelector('button[type="submit"]');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const preserveScroll = form.hasAttribute('data-preserve-scroll');
        const replaceOnSubmit = form.hasAttribute('data-replace-on-submit');
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

            if (replaceOnSubmit) {
                window.location.replace(targetUrl);
                return;
            }

            window.Livewire.navigate(targetUrl, { scroll: false });
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

                    if (form.hasAttribute('data-availability-form')) {
                        updateAvailabilityCard(form, Boolean(data.is_available));
                    }

                    if (data.flash?.message) {
                        showRentRideFlash(data.flash.type || 'success', data.flash.message);
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
                if (contentType.includes('text/html')) {
                    storeRentRideFlashesFromHtml(await response.text());
                }

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
