# BookCycle Tunisia

BookCycle Tunisia est une application web de gestion, de don et de reutilisation des livres scolaires. Le projet repose sur une architecture MVC en PHP et une base de donnees Oracle, avec une partie SQL/PLSQL pour la modelisation, les traitements et le reporting.

## Objectif du projet

L'application aide les utilisateurs a :
- publier des livres scolaires qu'ils n'utilisent plus
- rechercher des livres par niveau, classe et matiere
- envoyer et suivre des demandes de recuperation
- recevoir des notifications
- administrer la plateforme via un espace dedie

Le but principal est de reduire le cout des livres scolaires et de favoriser leur reutilisation.

## Fonctionnalites principales

### Visiteur
- consulter la page d'accueil
- parcourir le catalogue
- filtrer les livres par niveau, classe et matiere
- consulter les pages `About`, `Contact` et `Privacy Policy`
- acceder a l'inscription et a la connexion

### Utilisateur connecte
- creer un compte et se connecter
- ajouter un livre
- consulter ses livres
- envoyer une demande sur un livre disponible
- consulter ses demandes envoyees et recues
- voir ses notifications
- suivre quelques statistiques personnelles

### Administrateur
- consulter les statistiques globales
- rechercher des utilisateurs
- activer ou desactiver un compte
- masquer ou reactiver un livre
- filtrer et annuler des demandes
- envoyer des notifications globales ou ciblees

## Stack technique

- `PHP 7.4`
- `Oracle XE`
- `PDO_OCI`
- `HTML / CSS / JavaScript`
- architecture `MVC` sans framework

## Structure du projet

```text
bookcycle-tunisia/
  app/
    Config/         configuration application et base Oracle
    Controllers/    logique HTTP des pages et API
    Core/           noyau MVC (router, auth, controller, db)
    Models/         acces aux donnees via PDO
    Views/          layouts et pages PHP
  database/         scripts SQL, PLSQL et documents SGBD
  public/           point d'entree web et assets
  routes/           declaration des routes web et api
  router.php        routeur du serveur PHP local
  start_oracle_app.bat
```

## Routes importantes

### Pages web
- `/`
- `/about`
- `/catalog`
- `/contact`
- `/privacy-policy`
- `/login`
- `/register`
- `/dashboard`
- `/add-book`
- `/admin`

### API
- `/api/me`
- `/api/register`
- `/api/login`
- `/api/logout`
- `/api/books`
- `/api/latest-books`
- `/api/my-books`
- `/api/stats`
- `/api/requests`
- `/api/my-requests`
- `/api/received-requests`
- `/api/accept-request`
- `/api/reject-request`
- `/api/admin-stats`

## Installation et lancement local

### Prerequis
- Oracle XE installe ou accessible
- Oracle Instant Client
- PHP 7.4 avec `pdo_oci` et `oci8`
- SQL Developer conseille pour executer les scripts Oracle

### Lancement recommande

Depuis la racine du projet :

```powershell
start_oracle_app.bat
```

Ce lanceur configure automatiquement le `PATH` pour Oracle Instant Client et PHP, puis demarre le serveur local.

### Lancement manuel

```powershell
C:\php74\php.exe -S localhost:8000 router.php
```

Ensuite ouvrir :

```text
http://localhost:8000
```

## Configuration Oracle

Le projet utilise par defaut une configuration de ce type dans `app/Config/config.php` :

- DSN : `oci:dbname=//localhost:1521/XE;charset=AL32UTF8`
- utilisateur : `bookcycle_app`
- mot de passe : `BookCycle2026`

Le detail de la configuration Oracle est explique dans :
- `database/README_ORACLE.md`

## Scripts base de donnees

Executer les scripts Oracle dans cet ordre :

1. `database/01_users_privileges.sql`
2. `database/02_schema.sql`
3. `database/03_sample_data.sql`
4. `database/05_plsql_objects.sql`
5. `database/04_queries.sql`
6. `database/06_annex_objects.sql`

## Objets Oracle importants

Le schema inclut :
- tables `users`, `books`, `requests`, `exchanges`, `notifications`
- sequences Oracle pour les cles primaires
- triggers d'auto-generation des ids
- vue `v_book_overview`
- procedures et fonctions PLSQL

Exemples d'objets metier :
- `add_notification`
- `accept_request`
- `count_books_by_user`
- `calculate_money_saved`
- `trg_prevent_self_request`
- `trg_book_exchange_log`

## Comptes de demonstration

### Administrateur
- email : `admin@bookcycle.tn`
- mot de passe : `admin123`

### Utilisateur simple
- email : `ahmed@bookcycle.tn`
- mot de passe : `user123`

## Logique applicative importante

- la `Matiere` est choisie depuis une liste controlee
- la `Classe` depend du `Niveau` selectionne
- un utilisateur ne peut pas demander son propre livre
- une demande `pending` en double est refusee
- l'acceptation d'une demande reserve le livre et rejette les autres demandes en attente

## Fichiers cles

- `public/index.php` : point d'entree unique
- `app/bootstrap.php` : session, helpers, autoload
- `app/Core/Router.php` : dispatch des routes
- `app/Core/Database.php` : connexion PDO Oracle
- `app/Controllers/PageController.php` : pages principales
- `app/Controllers/BookController.php` : logique livres et statistiques
- `app/Controllers/RequestController.php` : logique demandes
- `app/Controllers/AdminController.php` : moderation et administration

## Remarques

Ce projet est une base academique solide, mais il peut encore etre ameliore avec :
- une meilleure securisation des formulaires
- une ergonomie mobile plus poussee
- des images pour les livres
- plus de tests automatises
- un moteur de recommandation ou de priorisation des demandes
