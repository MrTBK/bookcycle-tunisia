<!-- This page is the public catalogue.
     On the left, the user chooses filters.
     On the right, the user sees matching books. -->
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
                        <?php foreach (($levelOptions ?? []) as $levelOption): ?>
                            <option value="<?= htmlspecialchars($levelOption) ?>" <?= (($_GET['level'] ?? '') === $levelOption) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($levelOption) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="filter-subject">Matiere</label>
                    <select id="filter-subject" name="subject">
                        <option value="">Toutes</option>
                        <?php foreach (($subjectOptions ?? []) as $subjectOption): ?>
                            <option value="<?= htmlspecialchars($subjectOption) ?>" <?= (($_GET['subject'] ?? '') === $subjectOption) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($subjectOption) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="filter-class">Classe</label>
                    <select id="filter-class" name="class_name">
                        <option value="">Toutes</option>
                        <?php foreach (($classOptions ?? []) as $groupLevel => $classes): ?>
                            <optgroup label="<?= htmlspecialchars($groupLevel) ?>">
                                <?php foreach ($classes as $className): ?>
                                    <option value="<?= htmlspecialchars($className) ?>" data-level="<?= htmlspecialchars($groupLevel) ?>" <?= (($_GET['class_name'] ?? '') === $className) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($className) ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
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
                <div class="hero-actions">
                    <span id="book-count"><?= count($catalogBooks ?? []) ?> livre(s) trouve(s)</span>
                    <a class="button button-secondary button-small" href="<?= htmlspecialchars($basePath) ?>/add-book">Donner un livre</a>
                </div>
            </div>
            <div class="card-grid" id="book-grid">
                <?php foreach (($catalogBooks ?? []) as $book): ?>
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
        </section>
    </div>
</section>

<?php if (!empty($selectedBook)): ?>
    <section class="section">
        <div class="container">
            <div class="panel">
                <p class="eyebrow">Fiche livre</p>
                <h2><?= htmlspecialchars((string) $selectedBook['subject']) ?></h2>
                <p class="meta">Niveau: <?= htmlspecialchars((string) ($selectedBook['level_label'] ?? '')) ?></p>
                <p class="meta">Classe: <?= htmlspecialchars((string) ($selectedBook['class_label'] ?? '')) ?></p>
                <p class="meta">Prix estime: <?= htmlspecialchars((string) ($selectedBook['estimated_price'] ?? 0)) ?> DT</p>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const levelSelect = document.getElementById('filter-level');
    const classSelect = document.getElementById('filter-class');
    const subjectSelect = document.getElementById('filter-subject');
    const subjectMap = <?= json_encode($subjectOptionsByClass ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    const allSubjects = <?= json_encode($allSubjectOptions ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

    if (!levelSelect || !classSelect || !subjectSelect) {
        return;
    }

    const allOptions = Array.from(classSelect.querySelectorAll('option'));
    const subjectPlaceholder = '<option value=\"\">Toutes</option>';

    const subjectsForSelection = () => {
        const selectedOption = classSelect.options[classSelect.selectedIndex] || null;
        const inferredLevel = selectedOption && selectedOption.dataset.level ? selectedOption.dataset.level : '';
        const selectedLevel = levelSelect.value || inferredLevel;
        const selectedClass = classSelect.value;

        if (selectedLevel && selectedClass && subjectMap[selectedLevel] && subjectMap[selectedLevel][selectedClass]) {
            return subjectMap[selectedLevel][selectedClass];
        }

        if (selectedLevel && subjectMap[selectedLevel]) {
            const mergedSubjects = [];

            Object.values(subjectMap[selectedLevel]).forEach((classSubjects) => {
                classSubjects.forEach((subject) => {
                    if (!mergedSubjects.includes(subject)) {
                        mergedSubjects.push(subject);
                    }
                });
            });

            if (mergedSubjects.length > 0) {
                return mergedSubjects;
            }
        }

        return allSubjects;
    };

    const syncClasses = () => {
        const selectedLevel = levelSelect.value;
        const currentValue = classSelect.value;

        allOptions.forEach((option) => {
            if (!option.dataset.level) {
                option.hidden = false;
                return;
            }

            option.hidden = selectedLevel !== '' && option.dataset.level !== selectedLevel;
        });

        const visibleOptions = allOptions.filter((option) => !option.hidden);
        const stillVisible = visibleOptions.some((option) => option.value === currentValue);

        if (!stillVisible && visibleOptions.length > 0) {
            classSelect.value = visibleOptions[0].value;
        }
    };

    const syncSubjects = () => {
        const currentValue = subjectSelect.value;
        const availableSubjects = subjectsForSelection();

        subjectSelect.innerHTML = subjectPlaceholder;

        availableSubjects.forEach((subject) => {
            const option = document.createElement('option');
            option.value = subject;
            option.textContent = subject;
            subjectSelect.appendChild(option);
        });

        if (availableSubjects.includes(currentValue)) {
            subjectSelect.value = currentValue;
        }
    };

    levelSelect.addEventListener('change', function () {
        syncClasses();
        syncSubjects();
    });
    classSelect.addEventListener('change', syncSubjects);
    syncClasses();
    syncSubjects();
});
</script>
