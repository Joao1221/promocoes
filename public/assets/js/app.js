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

    const searchAutocompleteInputs = document.querySelectorAll('[data-search-autocomplete]');
    searchAutocompleteInputs.forEach((input) => {
        const listId = input.getAttribute('list');
        const suggestionsUrl = input.dataset.suggestionsUrl || '';
        const datalist = listId ? document.getElementById(listId) : null;
        const form = input.closest('form');

        if (!datalist || !suggestionsUrl || !form) {
            return;
        }

        let debounceTimer = null;
        let activeController = null;
        let submitting = false;

        const optionValues = () => Array.from(datalist.querySelectorAll('option')).map((option) => option.value.trim());
        const submitIfSuggestionSelected = () => {
            if (submitting) {
                return true;
            }

            const value = input.value.trim();
            if (value.length < 2) {
                return false;
            }

            if (!optionValues().includes(value)) {
                return false;
            }

            submitting = true;
            form.submit();
            return true;
        };

        const clearOptions = () => {
            datalist.innerHTML = '';
        };

        const renderOptions = (items) => {
            clearOptions();
            items.forEach((item) => {
                const option = document.createElement('option');
                option.value = item;
                datalist.appendChild(option);
            });
        };

        input.addEventListener('input', () => {
            if (submitIfSuggestionSelected()) {
                return;
            }

            const term = input.value.trim();

            if (debounceTimer !== null) {
                window.clearTimeout(debounceTimer);
            }

            if (activeController) {
                activeController.abort();
            }

            if (term.length < 2) {
                clearOptions();
                return;
            }

            debounceTimer = window.setTimeout(() => {
                activeController = new AbortController();
                const requestUrl = `${suggestionsUrl}?q=${encodeURIComponent(term)}`;

                fetch(requestUrl, {
                    signal: activeController.signal,
                    headers: { Accept: 'application/json' },
                })
                    .then((response) => (response.ok ? response.json() : { items: [] }))
                    .then((payload) => {
                        if (input.value.trim() !== term) {
                            return;
                        }

                        const items = Array.isArray(payload.items) ? payload.items : [];
                        renderOptions(items);
                    })
                    .catch((error) => {
                        if (error && error.name !== 'AbortError') {
                            clearOptions();
                        }
                    });
            }, 220);
        });

        input.addEventListener('change', () => {
            submitIfSuggestionSelected();
        });
    });

    const fileInputsWithLimit = document.querySelectorAll('input[type="file"][data-max-bytes]');
    fileInputsWithLimit.forEach((input) => {
        const maxBytes = Number(input.dataset.maxBytes || 0);
        const maxLabel = input.dataset.maxLabel || '2MB';
        const errorTarget = document.querySelector(`[data-file-error="${input.name}"]`);

        const hideError = () => {
            if (!errorTarget) {
                return;
            }

            errorTarget.textContent = '';
            errorTarget.classList.add('hidden');
        };

        const showError = (message) => {
            if (!errorTarget) {
                return;
            }

            errorTarget.textContent = message;
            errorTarget.classList.remove('hidden');
        };

        input.addEventListener('change', () => {
            hideError();

            if (maxBytes <= 0) {
                return;
            }

            const selectedFiles = Array.from(input.files || []);
            const invalidFile = selectedFiles.find((file) => file.size > maxBytes);

            if (!invalidFile) {
                return;
            }

            input.value = '';
            showError(`A imagem "${invalidFile.name}" excede ${maxLabel}. Escolha um arquivo menor.`);
        });
    });

    const chatbotRoot = document.querySelector('[data-help-chatbot]');
    if (chatbotRoot) {
        const endpoint = chatbotRoot.dataset.chatbotEndpoint || '';
        const toggleButton = chatbotRoot.querySelector('[data-chatbot-toggle]');
        const closeButton = chatbotRoot.querySelector('[data-chatbot-close]');
        const panel = chatbotRoot.querySelector('[data-chatbot-panel]');
        const messagesBox = chatbotRoot.querySelector('[data-chatbot-messages]');
        const optionsBox = chatbotRoot.querySelector('[data-chatbot-options]');
        const form = chatbotRoot.querySelector('[data-chatbot-form]');
        const input = chatbotRoot.querySelector('[data-chatbot-input]');
        const sendButton = chatbotRoot.querySelector('[data-chatbot-send]');

        if (endpoint && toggleButton && closeButton && panel && messagesBox && optionsBox && form && input && sendButton) {
            let welcomeLoaded = false;
            let loading = false;

            const scrollToBottom = () => {
                messagesBox.scrollTop = messagesBox.scrollHeight;
            };

            const setLoading = (isLoading) => {
                loading = isLoading;
                input.disabled = isLoading;
                sendButton.disabled = isLoading;
                if (!isLoading) {
                    input.focus();
                }
            };

            const appendMessage = (author, text) => {
                const item = document.createElement('div');
                item.className = author === 'user' ? 'chatbot-message chatbot-message-user' : 'chatbot-message chatbot-message-bot';

                const lines = String(text || '').split('\n');
                lines.forEach((line, index) => {
                    if (index > 0) {
                        item.appendChild(document.createElement('br'));
                    }
                    item.appendChild(document.createTextNode(line));
                });

                messagesBox.appendChild(item);
                scrollToBottom();
            };

            const renderOptions = (options) => {
                optionsBox.innerHTML = '';
                if (!Array.isArray(options) || options.length === 0) {
                    return;
                }

                options.slice(0, 5).forEach((label) => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'chatbot-option';
                    button.textContent = label;
                    button.addEventListener('click', () => {
                        requestReply(label, true);
                    });
                    optionsBox.appendChild(button);
                });
            };

            const requestReply = (message, showUserMessage) => {
                if (loading) {
                    return;
                }

                const cleanedMessage = String(message || '').trim();
                if (showUserMessage && cleanedMessage === '') {
                    return;
                }

                if (showUserMessage) {
                    appendMessage('user', cleanedMessage);
                }

                setLoading(true);

                const payload = new URLSearchParams();
                payload.set('message', cleanedMessage);

                fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        Accept: 'application/json',
                    },
                    body: payload.toString(),
                })
                    .then((response) => (response.ok ? response.json() : Promise.reject(new Error('chatbot_error'))))
                    .then((data) => {
                        const answer = String(data.answer || '').trim();
                        if (answer !== '') {
                            appendMessage('bot', answer);
                        } else {
                            appendMessage('bot', 'Nao consegui montar uma resposta agora. Tente novamente.');
                        }

                        renderOptions(data.options || []);
                    })
                    .catch(() => {
                        appendMessage('bot', 'Estou com instabilidade no momento. Tente novamente em instantes.');
                    })
                    .finally(() => {
                        setLoading(false);
                    });
            };

            const openPanel = () => {
                panel.classList.remove('hidden');
                toggleButton.setAttribute('aria-expanded', 'true');
                if (!welcomeLoaded) {
                    welcomeLoaded = true;
                    requestReply('', false);
                } else {
                    input.focus();
                }
            };

            const closePanel = () => {
                panel.classList.add('hidden');
                toggleButton.setAttribute('aria-expanded', 'false');
            };

            toggleButton.addEventListener('click', () => {
                if (panel.classList.contains('hidden')) {
                    openPanel();
                } else {
                    closePanel();
                }
            });

            closeButton.addEventListener('click', closePanel);

            form.addEventListener('submit', (event) => {
                event.preventDefault();
                if (loading) {
                    return;
                }

                const message = input.value.trim();
                if (message === '') {
                    input.focus();
                    return;
                }

                input.value = '';
                requestReply(message, true);
            });
        }
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
