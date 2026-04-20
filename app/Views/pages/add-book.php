<!-- This page lets a connected user add one new book to the platform. -->
<section class="section">
    <div class="container form-page">
        <form class="panel wide-form" id="add-book-form" method="post" action="<?= htmlspecialchars($basePath) ?>/add-book">
            <p class="eyebrow">Don de livre</p>
            <h1>Ajouter un livre</h1>
            <!-- Flash messages appear after redirects when something succeeds or fails. -->
            <?php if (!empty($flashError)): ?>
                <div class="panel" style="border-color:#c0392b;color:#8e2b23;"><?= htmlspecialchars($flashError) ?></div>
            <?php endif; ?>
            <?php if (!empty($flashSuccess)): ?>
                <div class="panel" style="border-color:#2d7a46;color:#1f5c33;"><?= htmlspecialchars($flashSuccess) ?></div>
            <?php endif; ?>
            <!-- The form uses guided dropdowns so the user chooses valid values. -->
            <div class="form-grid">
                <div class="field">
                    <label for="book-subject">Matiere</label>
                    <select id="book-subject" name="subject" required>
                        <option value="">Choisir une matiere</option>
                        <?php foreach (($subjectOptions ?? []) as $subjectOption): ?>
                            <option value="<?= htmlspecialchars($subjectOption) ?>">
                                <?= htmlspecialchars($subjectOption) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
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
                    <label for="book-class">Classe</label>
                    <select id="book-class" name="class_name" required>
                        <?php foreach (($classOptions ?? []) as $groupLevel => $classes): ?>
                            <optgroup label="<?= htmlspecialchars($groupLevel) ?>">
                                <?php foreach ($classes as $className): ?>
                                    <option value="<?= htmlspecialchars($className) ?>" data-level="<?= htmlspecialchars($groupLevel) ?>">
                                        <?= htmlspecialchars($className) ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
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
                <div class="field">
                    <label for="book-price">Prix estime (DT)</label>
                    <input id="book-price" name="estimated_price" type="number" min="1" step="0.01" required>
                </div>
            </div>
            <button class="button" type="submit">Enregistrer le livre</button>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Start the general add-book page behavior from app.js.
    BookCycle.initAddBookPage();

    const levelSelect = document.getElementById('book-level');
    const classSelect = document.getElementById('book-class');

    if (!levelSelect || !classSelect) {
        return;
    }

    // Keep all class options in memory so we can hide/show them when the level changes.
    const allOptions = Array.from(classSelect.querySelectorAll('option'));

    const syncClasses = () => {
        // Read the chosen level and current class selection.
        const selectedLevel = levelSelect.value;
        const currentValue = classSelect.value;

        // Hide classes that do not belong to the selected level.
        allOptions.forEach((option) => {
            option.hidden = option.dataset.level !== selectedLevel;
        });

        // If the currently selected class no longer fits, move to the first visible one.
        const visibleOptions = allOptions.filter((option) => option.dataset.level === selectedLevel);
        const stillVisible = visibleOptions.some((option) => option.value === currentValue);

        if (!stillVisible && visibleOptions.length > 0) {
            classSelect.value = visibleOptions[0].value;
        }
    };

    levelSelect.addEventListener('change', syncClasses);
    syncClasses();
});
</script>
