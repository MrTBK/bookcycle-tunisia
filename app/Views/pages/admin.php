<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <p class="eyebrow">Administration</p>
                <h1>Statistiques</h1>
            </div>
        </div>

        <div class="stats-panel admin-stats">
            <div class="stat-card">
                <span>Utilisateurs</span>
                <strong id="admin-users"><?= htmlspecialchars((string) ($adminStats['totalUsers'] ?? 0)) ?></strong>
            </div>
            <div class="stat-card">
                <span>Livres</span>
                <strong id="admin-books"><?= htmlspecialchars((string) ($adminStats['totalBooks'] ?? 0)) ?></strong>
            </div>
            <div class="stat-card">
                <span>Echanges</span>
                <strong id="admin-exchanges"><?= htmlspecialchars((string) ($adminStats['totalExchanges'] ?? 0)) ?></strong>
            </div>
            <div class="stat-card">
                <span>Economie</span>
                <strong id="admin-money"><?= htmlspecialchars((string) ($adminStats['moneySaved'] ?? 0)) ?> DT</strong>
            </div>
        </div>

        <section class="panel">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Suivi</p>
                    <h2>Livres recents</h2>
                </div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Proprietaire</th>
                            <th>Niveau</th>
                            <th>Etat</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody id="admin-books-table">
                        <?php if (empty($adminBooks)): ?>
                            <tr><td colspan="5">Aucun livre disponible.</td></tr>
                        <?php else: ?>
                            <?php foreach ($adminBooks as $book): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) $book['title']) ?></td>
                                    <td><?= htmlspecialchars((string) ($book['owner_name'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($book['level_label'] ?? $book['level'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) $book['condition_label']) ?></td>
                                    <td><?= htmlspecialchars((string) $book['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</section>
