document.addEventListener('alpine:init', () => {});

document.addEventListener('DOMContentLoaded', () => {
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
