document.addEventListener('alpine:init', () => {});

document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.querySelector('[data-mobile-menu-toggle]');
    const menuPanel = document.querySelector('[data-mobile-menu-panel]');
    const categoryMoreButton = document.querySelector('[data-category-menu-more]');
    const categoryMenuItems = document.querySelectorAll('[data-category-menu-item]');

    if (menuToggle && menuPanel) {
        menuToggle.addEventListener('click', () => {
            const isOpen = menuPanel.classList.contains('hidden');
            menuPanel.classList.toggle('hidden', !isOpen);
            menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    if (categoryMoreButton && categoryMenuItems.length > 0) {
        categoryMoreButton.addEventListener('click', () => {
            categoryMenuItems.forEach((item) => item.classList.remove('hidden'));
            categoryMoreButton.classList.add('hidden');
        });
    }

    const rotator = document.querySelector('[data-category-rotator]');
    if (!rotator) {
        return;
    }

    const items = Array.from(rotator.querySelectorAll('[data-category-group]'));
    if (items.length === 0) {
        return;
    }

    const groupIds = [...new Set(items.map((item) => Number(item.dataset.categoryGroup)))].sort((a, b) => a - b);
    if (groupIds.length <= 1) {
        return;
    }

    const intervalMs = Number(rotator.dataset.rotationMs) || 15000;
    let currentGroupIndex = 0;

    const renderGroup = (groupId) => {
        items.forEach((item) => {
            const isVisible = Number(item.dataset.categoryGroup) === groupId;
            item.classList.toggle('hidden', !isVisible);
        });
    };

    renderGroup(groupIds[currentGroupIndex]);

    window.setInterval(() => {
        currentGroupIndex = (currentGroupIndex + 1) % groupIds.length;
        renderGroup(groupIds[currentGroupIndex]);
    }, intervalMs);
});
