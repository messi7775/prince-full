document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('[data-sidebar]');
    const toggle = document.querySelector('[data-menu-toggle]');
    const overlay = document.querySelector('[data-menu-overlay]');

    const closeMenu = () => {
        sidebar?.classList.remove('open');
        overlay?.classList.remove('open');
    };

    toggle?.addEventListener('click', () => {
        sidebar?.classList.toggle('open');
        overlay?.classList.toggle('open');
    });

    overlay?.addEventListener('click', closeMenu);
});
