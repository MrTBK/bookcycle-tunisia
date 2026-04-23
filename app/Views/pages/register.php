<!-- This page lets a new visitor create an account. -->
<section class="section">
    <div class="container form-page">
        <form class="panel auth-form" id="register-form" method="post" action="<?= htmlspecialchars($basePath) ?>/register">
            <p class="eyebrow">Nouveau compte</p>
            <h1>Inscription</h1>
            <!-- Flash messages explain success or failure after redirects. -->
            <?php if (!empty($flashError)): ?>
                <div class="panel" style="border-color:#c0392b;color:#8e2b23;"><?= htmlspecialchars($flashError) ?></div>
            <?php endif; ?>
            <?php if (!empty($flashSuccess)): ?>
                <div class="panel" style="border-color:#2d7a46;color:#1f5c33;"><?= htmlspecialchars($flashSuccess) ?></div>
            <?php endif; ?>
            <!-- The form collects the basic information needed to create an account. -->
            <div class="form-stack">
                <div class="field">
                    <label for="register-name">Nom complet</label>
                    <input id="register-name" name="name" type="text" required>
                </div>
                <div class="field">
                    <label for="register-email">Email</label>
                    <input id="register-email" name="email" type="email" required>
                </div>
                <div class="field">
                    <label for="register-phone">Telephone</label>
                    <input id="register-phone" name="phone" type="text" required>
                </div>
                <div class="field">
                    <label for="register-password">Mot de passe</label>
                    <input id="register-password" name="password" type="password" required>
                </div>
                <button class="button" type="submit">Creer le compte</button>
            </div>
            <p class="helper-text">Deja inscrit ? <a href="<?= htmlspecialchars($basePath) ?>/login">Se connecter</a></p>
        </form>
    </div>
</section>
