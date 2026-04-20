<!-- This page is the admin control panel. -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <p class="eyebrow">Administration</p>
                <h1>Gestion de la plateforme</h1>
            </div>
            <div class="hero-actions">
                <!-- Quick way for the admin to leave the session. -->
                <form method="post" action="<?= htmlspecialchars($basePath) ?>/logout">
                    <button class="button button-danger" type="submit">Deconnexion</button>
                </form>
            </div>
        </div>

        <!-- Flash messages show the result of the admin's last action. -->
        <?php if (!empty($flashError)): ?>
            <div class="panel" style="border-color:#c0392b;color:#8e2b23;"><?= htmlspecialchars($flashError) ?></div>
        <?php endif; ?>
        <?php if (!empty($flashSuccess)): ?>
            <div class="panel" style="border-color:#2d7a46;color:#1f5c33;"><?= htmlspecialchars($flashSuccess) ?></div>
        <?php endif; ?>

        <!-- Top summary cards with platform-wide numbers. -->
        <div class="stats-panel admin-stats">
            <div class="stat-card">
                <span>Total utilisateurs</span>
                <strong><?= htmlspecialchars((string) ($adminStats['totalUsers'] ?? 0)) ?></strong>
            </div>
            <div class="stat-card">
                <span>Total livres</span>
                <strong><?= htmlspecialchars((string) ($adminStats['totalBooks'] ?? 0)) ?></strong>
            </div>
            <div class="stat-card">
                <span>Total echanges</span>
                <strong><?= htmlspecialchars((string) ($adminStats['totalExchanges'] ?? 0)) ?></strong>
            </div>
            <div class="stat-card">
                <span>Livres inactifs</span>
                <strong><?= htmlspecialchars((string) ($adminStats['inactiveBooks'] ?? 0)) ?></strong>
            </div>
        </div>

        <!-- Smaller cards with extra admin insights. -->
        <div class="card-grid" style="margin-bottom: 2rem;">
            <article class="dashboard-card">
                <h3>Livres par niveau</h3>
                <p class="meta">Primaire: <?= htmlspecialchars((string) ($adminStats['booksByLevel']['Primaire'] ?? 0)) ?></p>
                <p class="meta">College: <?= htmlspecialchars((string) ($adminStats['booksByLevel']['College'] ?? 0)) ?></p>
                <p class="meta">Lycee: <?= htmlspecialchars((string) ($adminStats['booksByLevel']['Lycee'] ?? 0)) ?></p>
            </article>
            <article class="dashboard-card">
                <h3>Matières les plus demandees</h3>
                <?php if (empty($adminRequestedSubjects)): ?>
                    <p class="muted">Aucune statistique disponible.</p>
                <?php else: ?>
                    <?php foreach ($adminRequestedSubjects as $subjectStat): ?>
                        <p class="meta">
                            <?= htmlspecialchars((string) ($subjectStat['subject'] ?? '')) ?> :
                            <?= htmlspecialchars((string) ($subjectStat['total_requests'] ?? 0)) ?> demande(s)
                        </p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </article>
            <article class="dashboard-card">
                <h3>Etat des comptes</h3>
                <p class="meta">Utilisateurs actifs: <?= htmlspecialchars((string) (($adminStats['totalUsers'] ?? 0) - ($adminStats['inactiveUsers'] ?? 0))) ?></p>
                <p class="meta">Utilisateurs inactifs: <?= htmlspecialchars((string) ($adminStats['inactiveUsers'] ?? 0)) ?></p>
                <p class="meta">Economie totale: <?= htmlspecialchars((string) ($adminStats['moneySaved'] ?? 0)) ?> DT</p>
            </article>
        </div>

        <section class="panel" style="margin-bottom: 2rem;">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Notifications</p>
                    <h2>Envoyer un message</h2>
                </div>
            </div>

            <!-- Admin can send a message to everyone or to one chosen user. -->
            <form class="form-stack" method="post" action="<?= htmlspecialchars($basePath) ?>/admin/notify">
                <div class="form-grid">
                    <div class="field">
                        <label for="notify-user">Destinataire</label>
                        <select id="notify-user" name="user_id">
                            <option value="0">Tous les utilisateurs actifs</option>
                            <?php foreach (($notifyUsers ?? []) as $user): ?>
                                <option value="<?= htmlspecialchars((string) $user['id']) ?>">
                                    <?= htmlspecialchars((string) $user['name']) ?> - <?= htmlspecialchars((string) $user['email']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field field-span-2">
                        <label for="notify-message">Message</label>
                        <textarea id="notify-message" name="message" rows="3" required></textarea>
                    </div>
                </div>
                <button class="button" type="submit">Envoyer la notification</button>
            </form>
        </section>

        <section class="panel" style="margin-bottom: 2rem;">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Utilisateurs</p>
                    <h2>Activer ou desactiver</h2>
                </div>
            </div>

            <!-- This search helps the admin find users faster. -->
            <form class="form-stack" method="get" action="<?= htmlspecialchars($basePath) ?>/admin" style="margin-bottom: 1rem;">
                <div class="field">
                    <label for="user-search">Recherche</label>
                    <input id="user-search" type="text" name="user_search" value="<?= htmlspecialchars((string) ($_GET['user_search'] ?? '')) ?>" placeholder="Nom ou email">
                </div>
                <button class="button button-secondary button-small" type="submit">Rechercher</button>
            </form>

            <!-- This table shows users and lets the admin activate or deactivate them. -->
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Telephone</th>
                            <th>Role</th>
                            <th>Etat</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($adminUsers)): ?>
                            <tr><td colspan="6">Aucun utilisateur trouve.</td></tr>
                        <?php else: ?>
                            <?php foreach ($adminUsers as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) $user['name']) ?></td>
                                    <td><?= htmlspecialchars((string) $user['email']) ?></td>
                                    <td><?= htmlspecialchars((string) ($user['phone'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) $user['role']) ?></td>
                                    <td><?= ((int) ($user['is_active'] ?? 1) === 1) ? 'Actif' : 'Inactif' ?></td>
                                    <td>
                                        <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/toggle-user?id=<?= urlencode((string) $user['id']) ?>">
                                            <?php if ((int) ($user['is_active'] ?? 1) === 1): ?>
                                                <button class="button button-small button-danger" type="submit">Desactiver</button>
                                            <?php else: ?>
                                                <button class="button button-small" type="submit">Reactiver</button>
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel" style="margin-bottom: 2rem;">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Livres</p>
                    <h2>Livres postes et moderation</h2>
                </div>
            </div>

            <!-- This table helps the admin moderate posted books. -->
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Matiere</th>
                            <th>Proprietaire</th>
                            <th>Niveau</th>
                            <th>Etat</th>
                            <th>Statut</th>
                            <th>Visibilite</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($adminBooks)): ?>
                            <tr><td colspan="7">Aucun livre trouve.</td></tr>
                        <?php else: ?>
                            <?php foreach ($adminBooks as $book): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) $book['subject']) ?></td>
                                    <td><?= htmlspecialchars((string) ($book['owner_name'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($book['class_label'] ?? '')) ?> - <?= htmlspecialchars((string) ($book['level_label'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($book['condition_label'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($book['status'] ?? '')) ?></td>
                                    <td><?= ((int) ($book['is_active'] ?? 0) === 1) ? 'Visible' : 'Masque' ?></td>
                                    <td>
                                        <?php if ((int) ($book['is_active'] ?? 0) === 1): ?>
                                            <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/delete-book?id=<?= urlencode((string) $book['id']) ?>">
                                                <button class="button button-small button-danger" type="submit">Supprimer</button>
                                            </form>
                                        <?php else: ?>
                                            <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/restore-book?id=<?= urlencode((string) $book['id']) ?>">
                                                <button class="button button-small button-secondary" type="submit">Reactiver</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Demandes</p>
                    <h2>Controle des demandes</h2>
                </div>
            </div>

            <!-- This filter helps the admin view only pending, accepted, or rejected requests. -->
            <form class="form-stack" method="get" action="<?= htmlspecialchars($basePath) ?>/admin" style="margin-bottom: 1rem;">
                <div class="field">
                    <label for="request-status">Filtrer par statut</label>
                    <select id="request-status" name="request_status">
                        <option value="">Tous</option>
                        <option value="pending" <?= (($_GET['request_status'] ?? '') === 'pending') ? 'selected' : '' ?>>pending</option>
                        <option value="accepted" <?= (($_GET['request_status'] ?? '') === 'accepted') ? 'selected' : '' ?>>accepted</option>
                        <option value="rejected" <?= (($_GET['request_status'] ?? '') === 'rejected') ? 'selected' : '' ?>>rejected</option>
                    </select>
                </div>
                <?php if (!empty($_GET['user_search'])): ?>
                    <input type="hidden" name="user_search" value="<?= htmlspecialchars((string) $_GET['user_search']) ?>">
                <?php endif; ?>
                <button class="button button-secondary button-small" type="submit">Filtrer</button>
            </form>

            <!-- This table shows all requests and lets the admin cancel them if needed. -->
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Livre</th>
                            <th>Proprietaire</th>
                            <th>Demandeur</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Action admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($adminRequests)): ?>
                            <tr><td colspan="6">Aucune demande trouvee.</td></tr>
                        <?php else: ?>
                            <?php foreach ($adminRequests as $request): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) ($request['subject'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($request['owner_name'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($request['requester_name'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($request['status'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($request['request_date'] ?? '')) ?></td>
                                    <td>
                                        <?php if (($request['status'] ?? '') !== 'rejected'): ?>
                                            <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/cancel-request?id=<?= urlencode((string) $request['id']) ?>">
                                                <button class="button button-small button-danger" type="submit">Annuler</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="muted">Deja annulee</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</section>
