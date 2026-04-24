<!-- This page lets an existing user enter the app. -->
<section class="section">
    <div class="container form-page">
        <form class="panel auth-form" id="login-form" method="post" action="<?= htmlspecialchars($basePath) ?>/login">
            <p class="eyebrow">Authentification</p>
            <h1>Connexion</h1>
            <!-- Flash messages explain what happened on the previous action. -->
            <?php if (!empty($flashError)): ?>
                <div class="panel" style="border-color:#c0392b;color:#8e2b23;"><?= htmlspecialchars($flashError) ?></div>
            <?php endif; ?>
            <?php if (!empty($flashSuccess)): ?>
                <div class="panel" style="border-color:#2d7a46;color:#1f5c33;"><?= htmlspecialchars($flashSuccess) ?></div>
            <?php endif; ?>
            <!-- The form asks only for the two things needed to identify the user. -->
            <div class="form-stack">
                <div class="field">
                    <label for="login-email">Email</label>
                    <input id="login-email" name="email" type="email" required>
                </div>
                <div class="field">
                    <label for="login-password">Mot de passe</label>
                    <input id="login-password" name="password" type="password" required>
                </div>
                <button class="button" type="submit">Se connecter</button>
            </div>
            <p class="helper-text">Pas encore de compte ? <a href="<?= htmlspecialchars($basePath) ?>/register">Creer un compte</a></p>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Start any login-page JavaScript behavior from the global app script.
    BookCycle.initLoginPage();
});
</script>
