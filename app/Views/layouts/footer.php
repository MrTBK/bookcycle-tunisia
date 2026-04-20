    </main>
    <!-- The footer is the bottom part shared by all pages. -->
    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <h3>BookCycle Tunisia</h3>
                <p>Plateforme d'echange et de reutilisation des livres scolaires.</p>
            </div>
            <div>
                <p>Mettre en relation les proprietaires de livres et les utilisateurs qui en ont besoin.</p>
                <p>
                    <a href="<?= htmlspecialchars($basePath) ?>/about">A propos</a> |
                    <a href="<?= htmlspecialchars($basePath) ?>/contact">Contact</a> |
                    <a href="<?= htmlspecialchars($basePath) ?>/privacy-policy">Politique de confidentialite</a>
                </p>
            </div>
        </div>
    </footer>
    <!-- Expose the app base path to JavaScript so scripts can build safe URLs. -->
    <script>window.APP_BASE_PATH = <?= json_encode($basePath) ?>;</script>
    <script src="<?= htmlspecialchars($basePath) ?>/assets/js/app.js"></script>
</body>
</html>
