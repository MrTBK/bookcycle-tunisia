const BookCycle = (() => {
    const basePath = window.APP_BASE_PATH || '';
    const path = (value) => `${basePath}${value}`;

    const apiUrl = (action, params = {}) => {
        const query = new URLSearchParams(params).toString();
        return query ? path(`/api/${action}?${query}`) : path(`/api/${action}`);
    };

    const fetchJson = async (url, options = {}) => {
        const response = await fetch(url, options);
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Une erreur est survenue.');
        }

        return data;
    };

    const bookLevel = (book) => book.level_label || book.level || '';
    const bookClass = (book) => book.class_label || book.class_name || '';

    const createBookCard = (book, showButton = true) => `
        <article class="book-card">
            <span class="badge">${bookLevel(book)}</span>
            <h3>${book.subject}</h3>
            <p class="meta">Classe: ${bookClass(book)}</p>
            <p class="meta">Proprietaire: ${book.owner_name}</p>
            <div class="hero-actions">
                <span class="badge badge-alt">${book.condition_label}</span>
                ${showButton ? `<button class="button button-small" type="button" data-book-id="${book.id}">Details</button>` : ''}
            </div>
        </article>
    `;

    const renderEmptyState = (element, message) => {
        element.innerHTML = `<div class="panel muted">${message}</div>`;
    };

    const showAlert = (message) => window.alert(message);

    const bindModal = () => {
        const modal = document.getElementById('book-modal');
        const closeModal = document.getElementById('close-modal');

        if (!modal || !closeModal || modal.dataset.bound === '1') {
            return;
        }

        closeModal.addEventListener('click', () => modal.classList.add('hidden'));
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });

        modal.dataset.bound = '1';
    };

    const showBookDetails = async (bookId) => {
        const books = await fetchJson(apiUrl('books', { id: bookId }));
        const book = books[0];
        if (!book) {
            showAlert('Livre introuvable.');
            return;
        }

        const auth = await fetchJson(apiUrl('me'));
        const modal = document.getElementById('book-modal');
        const content = document.getElementById('modal-content');

        let actions = `<a class="button" href="${path('/login')}">Connectez-vous pour demander ce livre</a>`;
        if (auth.loggedIn && auth.user.id !== Number(book.owner_id)) {
            actions = `<button class="button" type="button" id="request-book-button" data-book-id="${book.id}">Envoyer une demande</button>`;
        }
        if (auth.loggedIn && auth.user.id === Number(book.owner_id)) {
            actions = '<p class="muted">Ce livre vous appartient deja.</p>';
        }

        content.innerHTML = `
            <p class="eyebrow">Fiche livre</p>
            <h2>${book.subject}</h2>
            <p class="meta">Niveau: ${bookLevel(book)}</p>
            <p class="meta">Classe: ${bookClass(book)}</p>
            <p class="meta">Proprietaire: ${book.owner_name}</p>
            <div class="hero-actions">
                <span class="badge">${book.status}</span>
                <span class="badge badge-alt">${book.condition_label}</span>
            </div>
            <div class="hero-actions">${actions}</div>
        `;

        modal.classList.remove('hidden');

        const requestButton = document.getElementById('request-book-button');
        if (requestButton) {
            requestButton.addEventListener('click', async () => {
                try {
                    await fetchJson(apiUrl('requests'), {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ bookId }),
                    });
                    showAlert('Demande envoyee avec succes.');
                    modal.classList.add('hidden');
                } catch (error) {
                    showAlert(error.message);
                }
            });
        }
    };

    const bindBookCards = (container) => {
        container.querySelectorAll('[data-book-id]').forEach((button) => {
            button.addEventListener('click', () => showBookDetails(button.dataset.bookId));
        });
    };

    const loadStats = async () => {
        const stats = await fetchJson(apiUrl('stats'));
        const books = document.getElementById('stat-books');
        const exchanges = document.getElementById('stat-exchanges');
        const money = document.getElementById('stat-money');

        if (books) books.textContent = stats.totalBooks;
        if (exchanges) exchanges.textContent = stats.totalExchanges;
        if (money) money.textContent = `${stats.moneySaved} DT`;
    };

    const initHomePage = async () => {
        bindModal();
        await loadStats();
        const books = await fetchJson(apiUrl('latest-books'));
        const container = document.getElementById('featured-books');

        if (!books.length) {
            renderEmptyState(container, 'Aucun livre disponible pour le moment.');
            return;
        }

        container.innerHTML = books.map((book) => createBookCard(book)).join('');
        bindBookCards(container);
    };

    const initCatalogPage = async () => {
        const grid = document.getElementById('book-grid');
        const count = document.getElementById('book-count');
        const filterButton = document.getElementById('apply-filters');
        bindModal();

        const loadBooks = async () => {
            const level = document.getElementById('filter-level').value;
            const subject = document.getElementById('filter-subject').value;
            const books = await fetchJson(apiUrl('books', { level, subject }));

            count.textContent = `${books.length} livre(s) trouve(s)`;
            if (!books.length) {
                renderEmptyState(grid, 'Aucun livre ne correspond aux criteres.');
                return;
            }

            grid.innerHTML = books.map((book) => createBookCard(book)).join('');
            bindBookCards(grid);
        };

        filterButton.addEventListener('click', loadBooks);

        await loadBooks();

        const urlParams = new URLSearchParams(window.location.search);
        const bookId = urlParams.get('id');
        if (bookId) {
            await showBookDetails(bookId);
        }
    };

    const initLoginPage = () => {
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const data = Object.fromEntries(new FormData(form).entries());

            try {
                await fetchJson(apiUrl('login'), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data),
                });
                window.location.href = path('/dashboard');
            } catch (error) {
                showAlert(error.message);
            }
        });
    };

    const initRegisterPage = () => {
        const form = document.getElementById('register-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const data = Object.fromEntries(new FormData(form).entries());

            try {
                await fetchJson(apiUrl('register'), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data),
                });
                showAlert('Inscription reussie. Vous pouvez maintenant vous connecter.');
                window.location.href = path('/login');
            } catch (error) {
                showAlert(error.message);
            }
        });
    };

    const initAddBookPage = () => {
        const form = document.getElementById('add-book-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const data = Object.fromEntries(new FormData(form).entries());

            try {
                await fetchJson(apiUrl('books'), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data),
                });
                showAlert('Livre ajoute avec succes.');
                window.location.href = path('/dashboard');
            } catch (error) {
                showAlert(error.message);
            }
        });
    };

    const renderDashboardCard = (html) => `<article class="dashboard-card">${html}</article>`;

    const switchDashboardSection = (targetSection) => {
        document.querySelectorAll('.dashboard-section').forEach((section) => section.classList.add('hidden'));
        document.querySelectorAll('.tab-button').forEach((button) => {
            button.classList.toggle('is-active', button.dataset.section === targetSection);
        });
        document.getElementById(`section-${targetSection}`).classList.remove('hidden');
    };

    const initDashboardPage = async () => {
        const me = await fetchJson(apiUrl('me'));
        if (!me.loggedIn) {
            window.location.href = path('/login');
            return;
        }

        const logoutButton = document.getElementById('logout-button');
        if (logoutButton) {
            logoutButton.addEventListener('click', async (event) => {
                event.preventDefault();
                await fetchJson(apiUrl('logout'), { method: 'POST' });
                window.location.href = path('/');
            });
        }

        const loadMyBooks = async () => {
            const section = document.getElementById('section-my-books');
            const books = await fetchJson(apiUrl('my-books'));
            if (!books.length) {
                renderEmptyState(section, 'Vous n\\'avez ajoute aucun livre.');
                return;
            }

            section.innerHTML = books.map((book) => renderDashboardCard(`
                <h3>${book.subject}</h3>
                <p class="meta">Classe: ${bookClass(book)}</p>
                <p class="meta">Niveau: ${bookLevel(book)}</p>
                <span class="badge">${book.status}</span>
            `)).join('');
        };

        const loadReceivedRequests = async () => {
            const section = document.getElementById('section-received-requests');
            const requests = await fetchJson(apiUrl('received-requests'));
            if (!requests.length) {
                renderEmptyState(section, 'Aucune demande recue.');
                return;
            }

            section.innerHTML = requests.map((request) => renderDashboardCard(`
                <h3>${request.subject}</h3>
                <p class="meta">Classe: ${bookClass(request)}</p>
                <p class="meta">Niveau: ${bookLevel(request)}</p>
                <p class="meta">Demandeur: ${request.requester_name}</p>
                <div class="field">
                    <label for="note-${request.id}">Note de rendez-vous</label>
                    <textarea id="note-${request.id}" rows="3"></textarea>
                </div>
                <button class="button button-small" type="button" data-accept-id="${request.id}" data-book-title="${request.title}">Accepter</button>
            `)).join('');

            section.querySelectorAll('[data-accept-id]').forEach((button) => {
                button.addEventListener('click', async () => {
                    const note = document.getElementById(`note-${button.dataset.acceptId}`).value.trim();
                    if (!note) {
                        showAlert('Ajoute une note de rendez-vous avant de valider.');
                        return;
                    }

                    try {
                        await fetchJson(apiUrl('accept-request', { id: button.dataset.acceptId }), {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ meetingNote: note }),
                        });
                        showAlert(`Demande acceptee pour "${button.dataset.bookTitle}".`);
                        await loadReceivedRequests();
                        await loadMyBooks();
                    } catch (error) {
                        showAlert(error.message);
                    }
                });
            });
        };

        const loadSentRequests = async () => {
            const section = document.getElementById('section-sent-requests');
            const requests = await fetchJson(apiUrl('my-requests'));
            if (!requests.length) {
                renderEmptyState(section, 'Aucune demande envoyee.');
                return;
            }

            section.innerHTML = requests.map((request) => renderDashboardCard(`
                <h3>${request.subject}</h3>
                <p class="meta">Classe: ${bookClass(request)}</p>
                <p class="meta">Niveau: ${bookLevel(request)}</p>
                <p class="meta">Proprietaire: ${request.owner_name}</p>
                <p class="meta">Statut: ${request.status}</p>
                ${request.meeting_note ? `<p>${request.meeting_note}</p>` : ''}
            `)).join('');
        };

        document.querySelectorAll('.tab-button').forEach((button) => {
            button.addEventListener('click', async () => {
                const section = button.dataset.section;
                switchDashboardSection(section);
                if (section === 'my-books') await loadMyBooks();
                if (section === 'received-requests') await loadReceivedRequests();
                if (section === 'sent-requests') await loadSentRequests();
            });
        });

        switchDashboardSection('my-books');
        await loadMyBooks();
    };

    const initAdminPage = async () => {
        const me = await fetchJson(apiUrl('me'));
        if (!me.loggedIn || me.user.role !== 'admin') {
            window.location.href = path('/dashboard');
            return;
        }

        const [stats, adminStats, books] = await Promise.all([
            fetchJson(apiUrl('stats')),
            fetchJson(apiUrl('admin-stats')),
            fetchJson(apiUrl('books', { status: 'all' })),
        ]);

        document.getElementById('admin-users').textContent = adminStats.totalUsers;
        document.getElementById('admin-books').textContent = stats.totalBooks;
        document.getElementById('admin-exchanges').textContent = stats.totalExchanges;
        document.getElementById('admin-money').textContent = `${stats.moneySaved} DT`;

        const table = document.getElementById('admin-books-table');
        if (!books.length) {
            table.innerHTML = '<tr><td colspan="5">Aucun livre disponible.</td></tr>';
            return;
        }

        table.innerHTML = books.slice(0, 10).map((book) => `
            <tr>
                <td>${book.subject}</td>
                <td>${book.owner_name}</td>
                <td>${bookClass(book)} - ${bookLevel(book)}</td>
                <td>${book.condition_label}</td>
                <td>${book.status}</td>
            </tr>
        `).join('');
    };

    return {
        initHomePage,
        initCatalogPage,
        initLoginPage,
        initRegisterPage,
        initDashboardPage,
        initAddBookPage,
        initAdminPage,
    };
})();
