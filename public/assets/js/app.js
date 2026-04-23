document.addEventListener('DOMContentLoaded', () => {
    const levelSelect = document.getElementById('filter-level');
    const classSelect = document.getElementById('filter-class');

    if (levelSelect && classSelect) {
        const options = Array.from(classSelect.querySelectorAll('option'));

        const syncClasses = () => {
            const selectedLevel = levelSelect.value;
            const currentValue = classSelect.value;

            options.forEach((option) => {
                if (!option.dataset.level) {
                    option.hidden = false;
                    return;
                }

                option.hidden = selectedLevel !== '' && option.dataset.level !== selectedLevel;
            });

            const visibleOptions = options.filter((option) => !option.hidden);
            const stillVisible = visibleOptions.some((option) => option.value === currentValue);

            if (!stillVisible && visibleOptions.length > 0) {
                classSelect.value = visibleOptions[0].value;
            }
        };

        levelSelect.addEventListener('change', syncClasses);
        syncClasses();
    }
});
