<?php

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(($pageTitle ?? 'Application') . ' | ' . $appName) ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($basePath) ?>/assets/css/app.css">
</head>
<body>
    <header class="site-header">
        <div class="container nav-bar">
            <a class="brand" href="<?= htmlspecialchars($basePath) ?>/">
                <span class="brand-mark">BC</span>
                <span><?= htmlspecialchars($appName) ?></span>
            </a>
            <nav class="main-nav">
                <a href="<?= htmlspecialchars($basePath) ?>/">Accueil</a>
                <a href="<?= htmlspecialchars($basePath) ?>/catalog">Catalogue</a>
                <?php if ($currentUser): ?>
                    <a href="<?= htmlspecialchars($basePath) ?>/dashboard">Tableau de bord</a>
                    <?php if (($currentUser['role'] ?? '') === 'admin'): ?>
                        <a href="<?= htmlspecialchars($basePath) ?>/admin">Admin</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($basePath) ?>/login">Connexion</a>
                    <a class="button button-small" href="<?= htmlspecialchars($basePath) ?>/register">Inscription</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main>
