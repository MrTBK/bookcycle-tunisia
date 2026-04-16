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
                            <h3><?= htmlspecialchars((string) $book['title']) ?></h3>
                            <p class="meta"><?= htmlspecialchars((string) $book['subject']) ?> | <?= htmlspecialchars((string) ($book['level_label'] ?? '')) ?></p>
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
                            <h3><?= htmlspecialchars((string) $request['title']) ?></h3>
                            <p class="meta">Demandeur: <?= htmlspecialchars((string) $request['requester_name']) ?></p>
                            <form method="post" action="<?= htmlspecialchars($basePath) ?>/accept-request?id=<?= urlencode((string) $request['id']) ?>">
                                <div class="field">
                                    <label for="note-<?= htmlspecialchars((string) $request['id']) ?>">Note de rendez-vous</label>
                                    <textarea id="note-<?= htmlspecialchars((string) $request['id']) ?>" name="meetingNote" rows="3" required></textarea>
                                </div>
                                <button class="button button-small" type="submit">Accepter</button>
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
                            <h3><?= htmlspecialchars((string) $request['title']) ?></h3>
                            <p class="meta">Proprietaire: <?= htmlspecialchars((string) $request['owner_name']) ?></p>
                            <p class="meta">Statut: <?= htmlspecialchars((string) $request['status']) ?></p>
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
