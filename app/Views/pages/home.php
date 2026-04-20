<!-- This first section is the homepage hero:
     it explains the app quickly and shows the main call-to-action buttons. -->
<section class="hero">
    <div class="container hero-grid">
        <div>
            <p class="eyebrow">Plateforme collaborative</p>
            <h1>Gerer le don et la reutilisation des livres scolaires en Tunisie.</h1>
            <p class="lead">
                Une experience simple pour publier, consulter et demander des livres scolaires selon le niveau, la classe,
                la matiere et l'etat.
            </p>
            <div class="hero-actions">
                <a class="button" href="<?= htmlspecialchars($basePath) ?>/catalog">Parcourir les livres</a>
                <a class="button button-secondary" href="<?= htmlspecialchars($basePath) ?>/add-book">Ajouter un livre</a>
            </div>
        </div>
        <!-- These number cards give a quick summary of the platform. -->
        <div class="stats-panel">
            <div class="stat-card">
                <span>Livres actifs</span>
                <strong id="stat-books"><?= htmlspecialchars((string) ($homeStats['totalBooks'] ?? 0)) ?></strong>
            </div>
            <div class="stat-card">
                <span>Echanges valides</span>
                <strong id="stat-exchanges"><?= htmlspecialchars((string) ($homeStats['totalExchanges'] ?? 0)) ?></strong>
            </div>
            <div class="stat-card">
                <span>Economie estimee</span>
                <strong id="stat-money"><?= htmlspecialchars((string) ($homeStats['moneySaved'] ?? 0)) ?> DT</strong>
            </div>
        </div>
    </div>
</section>

<!-- This section shows the newest books added to the platform. -->
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
                <!-- Each card shows one featured book. -->
                <article class="book-card">
                    <span class="badge"><?= htmlspecialchars((string) ($book['level_label'] ?? $book['level'] ?? '')) ?></span>
                    <h3><?= htmlspecialchars((string) $book['subject']) ?></h3>
                    <p class="meta">Classe: <?= htmlspecialchars((string) ($book['class_label'] ?? '')) ?></p>
                    <p class="meta">Prix estime: <?= htmlspecialchars((string) ($book['estimated_price'] ?? 0)) ?> DT</p>
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
