document.addEventListener('alpine:init', () => {});

document.addEventListener('DOMContentLoaded', () => {
    const galleryInput = document.querySelector('[data-product-gallery-input]');
    const galleryPreview = document.querySelector('[data-product-gallery-preview]');
    const galleryList = document.querySelector('[data-product-gallery-list]');
    const galleryEmpty = document.querySelector('[data-product-gallery-empty]');

    if (galleryInput && galleryPreview && galleryList && galleryEmpty) {
        const maxFiles = Number(galleryInput.dataset.maxFiles || 4);
        let selectedFiles = [];
        let previewUrls = [];

        const syncInputFiles = () => {
            if (typeof DataTransfer === 'undefined') {
                return;
            }

            const transfer = new DataTransfer();
            selectedFiles.forEach((file) => transfer.items.add(file));
            galleryInput.files = transfer.files;
        };

        const renderSelectedFiles = () => {
            previewUrls.forEach((url) => URL.revokeObjectURL(url));
            previewUrls = [];
            galleryPreview.innerHTML = '';
            galleryList.innerHTML = '';

            const hasFiles = selectedFiles.length > 0;
            galleryEmpty.classList.toggle('hidden', hasFiles);
            galleryPreview.classList.toggle('hidden', !hasFiles);
            galleryPreview.classList.toggle('grid', hasFiles);
            galleryList.classList.toggle('hidden', !hasFiles);

            selectedFiles.forEach((file, index) => {
                const objectUrl = URL.createObjectURL(file);
                previewUrls.push(objectUrl);

                const previewCard = document.createElement('div');
                previewCard.className = 'rounded-2xl border border-slate-200 bg-white p-2';
                previewCard.innerHTML = `<img src="${objectUrl}" alt="${file.name}" class="h-28 w-full rounded-xl object-contain">`;
                galleryPreview.appendChild(previewCard);

                const fileRow = document.createElement('div');
                fileRow.className = 'rounded-xl bg-white px-3 py-2 text-slate-700 ring-1 ring-slate-200';
                fileRow.textContent = `${index + 1}. ${file.name}`;
                galleryList.appendChild(fileRow);
            });
        };

        galleryInput.addEventListener('change', () => {
            const incomingFiles = Array.from(galleryInput.files || []);
            if (incomingFiles.length === 0) {
                return;
            }

            selectedFiles = [...selectedFiles, ...incomingFiles].slice(0, maxFiles);
            syncInputFiles();
            renderSelectedFiles();
        });
    }

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
