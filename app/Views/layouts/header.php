<?php

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Basic page information for the browser. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Build the page title from the current page title and the app name. -->
    <title><?= htmlspecialchars(($pageTitle ?? 'Application') . ' | ' . $appName) ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= htmlspecialchars($basePath) ?>/assets/favicon.svg">
    <link rel="stylesheet" href="<?= htmlspecialchars($basePath) ?>/assets/css/app.css">
</head>
<body>
    <!-- The header is the top part of the page that users see everywhere. -->
    <header class="site-header">
        <div class="container nav-bar">
            <!-- The brand sends the user back to the homepage. -->
            <a class="brand" href="<?= htmlspecialchars($basePath) ?>/">
                <span class="brand-mark">BC</span>
                <span><?= htmlspecialchars($appName) ?></span>
            </a>
            <!-- The navigation changes depending on whether the user is connected or not. -->
            <nav class="main-nav">
                <a href="<?= htmlspecialchars($basePath) ?>/">Accueil</a>
                <a href="<?= htmlspecialchars($basePath) ?>/catalog">Catalogue</a>
                <a href="<?= htmlspecialchars($basePath) ?>/contact">Contact</a>
                <?php if ($currentUser): ?>
                    <!-- Connected users see their dashboard and notifications. -->
                    <a href="<?= htmlspecialchars($basePath) ?>/dashboard">
                        Tableau de bord
                        <?php if (!empty($unreadNotificationsCount)): ?>
                            <span class="nav-count"><?= htmlspecialchars((string) $unreadNotificationsCount) ?></span>
                        <?php endif; ?>
                    </a>
                    <!-- This dropdown shows only the latest unread notifications in the navbar. -->
                    <details class="nav-dropdown">
                        <summary>
                            Notifications
                            <?php if (!empty($unreadNotificationsCount)): ?>
                                <span class="nav-count"><?= htmlspecialchars((string) $unreadNotificationsCount) ?></span>
                            <?php endif; ?>
                        </summary>
                        <div class="nav-dropdown-menu">
                            <div class="nav-dropdown-head">Notifications non lues</div>
                            <?php if (empty($navNotifications)): ?>
                                <p class="nav-empty">Aucune notification non lue.</p>
                            <?php else: ?>
                                <?php foreach ($navNotifications as $navNotification): ?>
                                    <!-- Clicking a notification opens it and marks it as read. -->
                                    <a class="nav-notification-item" href="<?= htmlspecialchars($basePath) ?>/notifications/read?id=<?= urlencode((string) $navNotification['id']) ?>&redirect=<?= urlencode('/dashboard#section-notifications') ?>">
                                        <div class="nav-notification-top">
                                            <span class="badge notification-sender">
                                                <?= htmlspecialchars((string) ($navNotification['sender_name'] ?? 'Systeme')) ?>
                                            </span>
                                            <span class="meta"><?= htmlspecialchars((string) ($navNotification['created_at'] ?? '')) ?></span>
                                        </div>
                                        <p><?= htmlspecialchars((string) ($navNotification['message'] ?? '')) ?></p>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <a class="button button-secondary button-small nav-dropdown-link" href="<?= htmlspecialchars($basePath) ?>/dashboard">Voir tout</a>
                        </div>
                    </details>
                    <?php if (($currentUser['role'] ?? '') === 'admin'): ?>
                        <!-- Only admins see the admin link. -->
                        <a href="<?= htmlspecialchars($basePath) ?>/admin">Admin</a>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Visitors who are not connected see login and registration links. -->
                    <a href="<?= htmlspecialchars($basePath) ?>/login">Connexion</a>
                    <a class="button button-small" href="<?= htmlspecialchars($basePath) ?>/register">Inscription</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <!-- The main tag will contain the page-specific content. -->
    <main>
