<!-- Page de modification d'un livre existant.
     Les champs niveau, classe et matiere sont affiches en lecture seule
     car les changer apres qu'une demande a ete envoyee causerait des incoherences.
     Seuls l'etat, le prix et la description peuvent etre modifies. -->
<section class="section">
    <div class="container form-page">
        <form class="panel wide-form" method="post" action="<?= htmlspecialchars($basePath) ?>/edit-book">

            <!-- Champ cache pour transmettre l'identifiant du livre au controleur. -->
            <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['id'] ?? '') ?>">

            <p class="eyebrow">Modifier le livre</p>
            <h1>Modifier les informations</h1>

            <!-- Afficher les messages d'erreur ou de succes si presents en session flash. -->
            <?php if (!empty($flashError)): ?>
                <div class="panel" style="border-color:#c0392b;color:#8e2b23;">
                    <?= htmlspecialchars($flashError) ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($flashSuccess)): ?>
                <div class="panel" style="border-color:#2d7a46;color:#1f5c33;">
                    <?= htmlspecialchars($flashSuccess) ?>
                </div>
            <?php endif; ?>

            <div class="form-grid">

                <!-- Niveau scolaire - lecture seule, non modifiable. -->
                <div class="field">
                    <label>Niveau</label>
                    <input type="text"
                           value="<?= htmlspecialchars($book['level_label'] ?? '') ?>"
                           disabled>
                </div>

                <!-- Classe - lecture seule, non modifiable. -->
                <div class="field">
                    <label>Classe</label>
                    <input type="text"
                           value="<?= htmlspecialchars($book['class_label'] ?? '') ?>"
                           disabled>
                </div>

                <!-- Matiere - lecture seule, non modifiable. -->
                <div class="field">
                    <label>Matiere</label>
                    <input type="text"
                           value="<?= htmlspecialchars($book['subject'] ?? '') ?>"
                           disabled>
                </div>

                <!-- Etat du livre - modifiable, pre-selectionne avec la valeur actuelle. -->
                <div class="field">
                    <label for="edit-condition">Etat</label>
                    <select id="edit-condition" name="condition" required>
                        <?php foreach (['Neuf', 'Bon', 'Usage'] as $cond): ?>
                            <option value="<?= htmlspecialchars($cond) ?>"
                                <?= ($book['condition_label'] ?? '') === $cond ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cond) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Prix estime - modifiable, pre-rempli avec la valeur actuelle. -->
                <div class="field">
                    <label for="edit-price">Prix estime (DT)</label>
                    <input id="edit-price"
                           name="estimated_price"
                           type="number"
                           min="1"
                           step="0.01"
                           value="<?= htmlspecialchars($book['estimated_price'] ?? '0') ?>"
                           required>
                </div>

            </div>

            <!-- Description - modifiable, pre-remplie avec la valeur actuelle. -->
            <div class="field" style="margin-top:1rem;">
                <label for="edit-description">Description (optionnelle)</label>
                <textarea id="edit-description"
                          name="description"
                          rows="3"
                          style="width:100%;padding:.5rem;border:1px solid #ccc;border-radius:4px;"
                ><?= htmlspecialchars($book['description'] ?? '') ?></textarea>
            </div>

            <!-- Boutons d'action : enregistrer ou annuler. -->
            <div style="display:flex;gap:1rem;margin-top:1.5rem;">
                <button class="button" type="submit">Enregistrer les modifications</button>
                <a href="<?= htmlspecialchars($basePath) ?>/dashboard"
                   class="button"
                   style="background:#6c757d;">
                    Annuler
                </a>
            </div>

        </form>
    </div>
</section>
