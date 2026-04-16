<section class="section">
    <div class="container form-page">
        <form class="panel wide-form" id="add-book-form" method="post" action="<?= htmlspecialchars($basePath) ?>/add-book">
            <p class="eyebrow">Don de livre</p>
            <h1>Ajouter un livre</h1>
            <?php if (!empty($flashError)): ?>
                <div class="panel" style="border-color:#c0392b;color:#8e2b23;"><?= htmlspecialchars($flashError) ?></div>
            <?php endif; ?>
            <?php if (!empty($flashSuccess)): ?>
                <div class="panel" style="border-color:#2d7a46;color:#1f5c33;"><?= htmlspecialchars($flashSuccess) ?></div>
            <?php endif; ?>
            <div class="form-grid">
                <div class="field field-span-2">
                    <label for="book-title">Titre</label>
                    <input id="book-title" name="title" type="text" required>
                </div>
                <div class="field">
                    <label for="book-subject">Matiere</label>
                    <input id="book-subject" name="subject" type="text" required>
                </div>
                <div class="field">
                    <label for="book-level">Niveau</label>
                    <select id="book-level" name="level" required>
                        <option value="Primaire">Primaire</option>
                        <option value="College">College</option>
                        <option value="Lycee">Lycee</option>
                    </select>
                </div>
                <div class="field">
                    <label for="book-condition">Etat</label>
                    <select id="book-condition" name="condition" required>
                        <option value="Neuf">Neuf</option>
                        <option value="Bon">Bon</option>
                        <option value="Usage">Usage</option>
                    </select>
                </div>
                <div class="field field-span-2">
                    <label for="book-description">Description</label>
                    <textarea id="book-description" name="description" rows="4"></textarea>
                </div>
            </div>
            <button class="button" type="submit">Enregistrer le livre</button>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    BookCycle.initAddBookPage();
});
</script>
