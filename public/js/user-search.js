function userSearch() {
    return {
        query: '',
        results: [],
        open: false,
        loading: false,
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