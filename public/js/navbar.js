document.addEventListener('livewire:navigated', () => {
    console.log('Page swapped instantly!');
});

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
    dropdown.classList.toggle('hidden');
    dropdown.classList.toggle('flex');
    arrow.classList.toggle('rotate-180');
}