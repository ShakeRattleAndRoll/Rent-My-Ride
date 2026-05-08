function userSearch() {
    return {
        query: '',
        results: [],
        open: false,
        loading: false,
        goToUser(userId) {
            this.open = false;
            this.query = '';

            if (window.Livewire?.navigate) {
                window.Livewire.navigate(`/messages/${userId}`);
                return;
            }

            window.location.href = `/messages/${userId}`;
        },
        async search() {
            if (this.query.trim().length === 0) {
                this.results = [];
                this.open = false;
                return;
            }
            this.loading = true;
            this.open = true;
            try {
                const res = await fetch(`/messages/search-users?q=${encodeURIComponent(this.query)}`);
                this.results = await res.json();
            } catch (e) {
                this.results = [];
            }
            this.loading = false;
        }
    }
}

if (!window.rentRideMessageNavigateBound) {
    window.rentRideMessageNavigateBound = true;

    document.addEventListener('click', (event) => {
        const link = event.target.closest('[data-message-navigate]');

        if (!link || event.defaultPrevented || !window.Livewire?.navigate) return;

        event.preventDefault();
        window.Livewire.navigate(link.href);
    });
}
