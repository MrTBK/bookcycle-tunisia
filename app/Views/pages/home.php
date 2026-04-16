<section class="hero">
    <div class="container hero-grid">
        <div>
            <p class="eyebrow">Projet Web 2 sans framework</p>
            <h1>Gerer le don et la reutilisation des livres scolaires en Tunisie.</h1>
            <p class="lead">
                Cette version du projet utilise une architecture simple en PHP MVC avec PDO, des pages HTML servies en PHP
                et un JavaScript natif pour l'interactivite.
            </p>
            <div class="hero-actions">
                <a class="button" href="<?= htmlspecialchars($basePath) ?>/catalog">Parcourir les livres</a>
                <a class="button button-secondary" href="<?= htmlspecialchars($basePath) ?>/add-book">Ajouter un livre</a>
            </div>
        </div>
        <div class="stats-panel">
            <div class="stat-card">
                <span>Livres actifs</span>
                <strong id="stat-books">0</strong>
            </div>
            <div class="stat-card">
                <span>Echanges valides</span>
                <strong id="stat-exchanges">0</strong>
            </div>
            <div class="stat-card">
                <span>Economie estimee</span>
                <strong id="stat-money">0 DT</strong>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <p class="eyebrow">Catalogue</p>
                <h2>Derniers livres ajoutes</h2>
            </div>
            <a href="<?= htmlspecialchars($basePath) ?>/catalog">Voir tout</a>
        </div>
        <div class="card-grid" id="featured-books">
            <?php foreach (($featuredBooks ?? []) as $book): ?>
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
    </div>
</section>
