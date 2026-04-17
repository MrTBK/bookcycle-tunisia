<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <p class="eyebrow">Espace membre</p>
                <h1>Tableau de bord</h1>
            </div>
            <div class="hero-actions">
                <a class="button button-secondary" href="<?= htmlspecialchars($basePath) ?>/add-book">Ajouter un livre</a>
                <form method="post" action="<?= htmlspecialchars($basePath) ?>/logout">
                    <button class="button button-danger" type="submit" id="logout-button">Deconnexion</button>
                </form>
            </div>
        </div>

        <?php if (!empty($flashError)): ?>
            <div class="panel" style="border-color:#c0392b;color:#8e2b23;"><?= htmlspecialchars($flashError) ?></div>
        <?php endif; ?>
        <?php if (!empty($flashSuccess)): ?>
            <div class="panel" style="border-color:#2d7a46;color:#1f5c33;"><?= htmlspecialchars($flashSuccess) ?></div>
        <?php endif; ?>

        <div class="stats-panel">
            <div class="stat-card">
                <span>Livres recus</span>
                <strong><?= htmlspecialchars((string) ($dashboardStats['booksReceived'] ?? 0)) ?></strong>
            </div>
            <div class="stat-card">
                <span>Livres donnes</span>
                <strong><?= htmlspecialchars((string) ($dashboardStats['booksGiven'] ?? 0)) ?></strong>
            </div>
            <div class="stat-card">
                <span>Argent economise</span>
                <strong><?= htmlspecialchars((string) ($dashboardStats['moneySaved'] ?? 0)) ?> DT</strong>
            </div>
            <div class="stat-card">
                <span>Argent fait economiser</span>
                <strong><?= htmlspecialchars((string) ($dashboardStats['moneySavedForOthers'] ?? 0)) ?> DT</strong>
            </div>
        </div>

        <section class="section">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Notifications</p>
                    <h2>Messages recus</h2>
                </div>
            </div>
            <div class="dashboard-section" id="section-notifications">
                <?php if (empty($notifications)): ?>
                    <div class="panel muted">Aucune notification pour le moment.</div>
                <?php else: ?>
                    <?php foreach ($notifications as $notification): ?>
                        <article class="dashboard-card">
                            <div class="notification-meta">
                                <div class="hero-actions">
                                    <span class="badge notification-sender">
                                        <?= htmlspecialchars((string) ($notification['sender_name'] ?? 'Systeme')) ?>
                                    </span>
                                    <span class="badge <?= ((int) ($notification['is_read'] ?? 0) === 0) ? '' : 'badge-alt' ?>">
                                        <?= ((int) ($notification['is_read'] ?? 0) === 0) ? 'Non lue' : 'Lue' ?>
                                    </span>
                                </div>
                                <span class="meta"><?= htmlspecialchars((string) ($notification['created_at'] ?? '')) ?></span>
                            </div>
                            <p><?= htmlspecialchars((string) ($notification['message'] ?? '')) ?></p>
                            <?php if ((int) ($notification['is_read'] ?? 0) === 0): ?>
                                <p>
                                    <a class="button button-secondary button-small" href="<?= htmlspecialchars($basePath) ?>/notifications/read?id=<?= urlencode((string) $notification['id']) ?>&redirect=<?= urlencode('/dashboard#section-notifications') ?>">
                                        Marquer comme lue
                                    </a>
                                </p>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="section">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Mes livres</p>
                    <h2>Livres ajoutes</h2>
                </div>
            </div>
            <div class="dashboard-section" id="section-my-books">
                <?php if (empty($myBooks)): ?>
                    <div class="panel muted">Vous n'avez ajoute aucun livre.</div>
                <?php else: ?>
                    <?php foreach ($myBooks as $book): ?>
                        <article class="dashboard-card">
                            <h3><?= htmlspecialchars((string) $book['subject']) ?></h3>
                            <p class="meta">Classe: <?= htmlspecialchars((string) ($book['class_label'] ?? '')) ?></p>
                            <p class="meta">Niveau: <?= htmlspecialchars((string) ($book['level_label'] ?? '')) ?></p>
                            <p class="meta">Prix estime: <?= htmlspecialchars((string) ($book['estimated_price'] ?? 0)) ?> DT</p>
                            <span class="badge"><?= htmlspecialchars((string) $book['status']) ?></span>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="section">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Demandes recues</p>
                    <h2>Demandes a traiter</h2>
                </div>
            </div>
            <div class="dashboard-section" id="section-received-requests">
                <?php if (empty($receivedRequests)): ?>
                    <div class="panel muted">Aucune demande recue.</div>
                <?php else: ?>
                    <?php foreach ($receivedRequests as $request): ?>
                        <article class="dashboard-card">
                            <h3><?= htmlspecialchars((string) $request['subject']) ?></h3>
                            <p class="meta">Classe: <?= htmlspecialchars((string) ($request['class_label'] ?? '')) ?></p>
                            <p class="meta">Niveau: <?= htmlspecialchars((string) ($request['level_label'] ?? '')) ?></p>
                            <p class="meta">Prix estime: <?= htmlspecialchars((string) ($request['estimated_price'] ?? 0)) ?> DT</p>
                            <p class="meta">Demandeur: <?= htmlspecialchars((string) $request['requester_name']) ?></p>
                            <p class="meta">Email: <?= htmlspecialchars((string) ($request['requester_email'] ?? '')) ?></p>
                            <p class="meta">Telephone: <?= htmlspecialchars((string) ($request['requester_phone'] ?? '')) ?></p>
                            <form method="post" action="<?= htmlspecialchars($basePath) ?>/accept-request?id=<?= urlencode((string) $request['id']) ?>">
                                <div class="field">
                                    <label for="note-<?= htmlspecialchars((string) $request['id']) ?>">Note de rendez-vous</label>
                                    <textarea id="note-<?= htmlspecialchars((string) $request['id']) ?>" name="meetingNote" rows="3" required></textarea>
                                </div>
                                <div class="hero-actions request-actions">
                                    <button class="button button-small" type="submit">Accepter</button>
                                    <button class="button button-small button-danger" type="submit" form="reject-request-<?= htmlspecialchars((string) $request['id']) ?>">Refuser</button>
                                </div>
                            </form>
                            <form id="reject-request-<?= htmlspecialchars((string) $request['id']) ?>" method="post" action="<?= htmlspecialchars($basePath) ?>/reject-request?id=<?= urlencode((string) $request['id']) ?>">
                            </form>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="section">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Demandes envoyees</p>
                    <h2>Suivi</h2>
                </div>
            </div>
            <div class="dashboard-section" id="section-sent-requests">
                <?php if (empty($sentRequests)): ?>
                    <div class="panel muted">Aucune demande envoyee.</div>
                <?php else: ?>
                    <?php foreach ($sentRequests as $request): ?>
                        <article class="dashboard-card">
                            <h3><?= htmlspecialchars((string) $request['subject']) ?></h3>
                            <p class="meta">Classe: <?= htmlspecialchars((string) ($request['class_label'] ?? '')) ?></p>
                            <p class="meta">Niveau: <?= htmlspecialchars((string) ($request['level_label'] ?? '')) ?></p>
                            <p class="meta">Prix estime: <?= htmlspecialchars((string) ($request['estimated_price'] ?? 0)) ?> DT</p>
                            <p class="meta">Proprietaire: <?= htmlspecialchars((string) $request['owner_name']) ?></p>
                            <p class="meta">Statut: <?= htmlspecialchars((string) $request['status']) ?></p>
                            <?php if (($request['status'] ?? '') === 'accepted'): ?>
                                <p class="meta">Email proprietaire: <?= htmlspecialchars((string) ($request['owner_email'] ?? '')) ?></p>
                                <p class="meta">Telephone proprietaire: <?= htmlspecialchars((string) ($request['owner_phone'] ?? '')) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($request['meeting_note'])): ?>
                                <p><?= htmlspecialchars((string) $request['meeting_note']) ?></p>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</section>
