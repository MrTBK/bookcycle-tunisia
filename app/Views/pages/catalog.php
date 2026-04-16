<section class="section">
    <div class="container layout-grid">
        <aside class="panel">
            <p class="eyebrow">Recherche</p>
            <h2>Filtres</h2>
            <form class="form-stack" method="get" action="<?= htmlspecialchars($basePath) ?>/catalog">
                <div class="field">
                    <label for="filter-level">Niveau</label>
                    <select id="filter-level" name="level">
                        <option value="">Tous</option>
                        <option value="Primaire" <?= (($_GET['level'] ?? '') === 'Primaire') ? 'selected' : '' ?>>Primaire</option>
                        <option value="College" <?= (($_GET['level'] ?? '') === 'College') ? 'selected' : '' ?>>College</option>
                        <option value="Lycee" <?= (($_GET['level'] ?? '') === 'Lycee') ? 'selected' : '' ?>>Lycee</option>
                    </select>
                </div>
                <div class="field">
                    <label for="filter-subject">Matiere</label>
                    <input id="filter-subject" name="subject" type="text" value="<?= htmlspecialchars((string) ($_GET['subject'] ?? '')) ?>" placeholder="Math, Physique, Arabe...">
                </div>
                <button class="button" type="submit" id="apply-filters">Appliquer</button>
            </form>
        </aside>

        <section>
            <div class="section-head">
                <div>
                    <p class="eyebrow">Resultats</p>
                    <h2>Livres disponibles</h2>
                </div>
                <span id="book-count"><?= count($catalogBooks ?? []) ?> livre(s) trouve(s)</span>
            </div>
            <div class="card-grid" id="book-grid">
                <?php foreach (($catalogBooks ?? []) as $book): ?>
                    <article class="book-card">
                        <span class="badge"><?= htmlspecialchars((string) ($book['level_label'] ?? $book['level'] ?? '')) ?></span>
                        <h3><?= htmlspecialchars((string) $book['title']) ?></h3>
                        <p class="meta"><?= htmlspecialchars((string) $book['subject']) ?></p>
                        <p class="meta">Proprietaire: <?= htmlspecialchars((string) ($book['owner_name'] ?? '')) ?></p>
                        <div class="hero-actions">
                            <span class="badge badge-alt"><?= htmlspecialchars((string) $book['condition_label']) ?></span>
                            <a class="button button-small" href="<?= htmlspecialchars($basePath) ?>/catalog?id=<?= urlencode((string) $book['id']) ?>">Details</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</section>

<?php if (!empty($selectedBook)): ?>
    <section class="section">
        <div class="container">
            <div class="panel">
                <p class="eyebrow">Fiche livre</p>
                <h2><?= htmlspecialchars((string) $selectedBook['title']) ?></h2>
                <p class="meta"><?= htmlspecialchars((string) $selectedBook['subject']) ?> | <?= htmlspecialchars((string) ($selectedBook['level_label'] ?? '')) ?></p>
                <p><?= htmlspecialchars((string) ($selectedBook['description'] ?? 'Aucune description fournie.')) ?></p>
                <p class="meta">Proprietaire: <?= htmlspecialchars((string) ($selectedBook['owner_name'] ?? '')) ?></p>
                <div class="hero-actions">
                    <span class="badge"><?= htmlspecialchars((string) $selectedBook['status']) ?></span>
                    <span class="badge badge-alt"><?= htmlspecialchars((string) $selectedBook['condition_label']) ?></span>
                </div>
                <div class="hero-actions">
                    <?php if (empty($currentUser)): ?>
                        <a class="button" href="<?= htmlspecialchars($basePath) ?>/login">Connectez-vous pour demander ce livre</a>
                    <?php elseif ((int) ($currentUser['id'] ?? 0) === (int) ($selectedBook['owner_id'] ?? 0)): ?>
                        <p class="muted">Ce livre vous appartient deja.</p>
                    <?php else: ?>
                        <form method="post" action="<?= htmlspecialchars($basePath) ?>/request-book">
                            <input type="hidden" name="bookId" value="<?= htmlspecialchars((string) $selectedBook['id']) ?>">
                            <button class="button" type="submit">Envoyer une demande</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
