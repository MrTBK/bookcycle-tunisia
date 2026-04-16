# BookCycle Tunisia

Application Web 2 conforme au cahier des charges du projet integre:

- `PHP` sans framework
- `PDO` pour l'acces a la base Oracle
- `POO` et organisation `MVC`
- `HTML`, `CSS`, `JavaScript` natif
- `Oracle / PL-SQL` pour la partie base de donnees

## Structure

- `app/Controllers`: logique des pages et API
- `app/Models`: acces aux donnees avec PDO
- `app/Views`: vues PHP
- `public/`: point d'entree du site et assets
- `database/`: schemas SQL et exemples de requetes

## Lancement local

Important: ne pas utiliser la commande `php -S ...` seule si `php` pointe encore vers une autre installation.

1. Depuis la racine du projet, lancer:
   `start_oracle_app.bat`
2. Ou en ligne de commande:
   `C:\php74\php.exe -S localhost:8000 router.php`
3. Ouvrir:
   `http://localhost:8000`

Le lanceur ajoute automatiquement:
- `C:\oracle\instantclient_19_28`
- `C:\php74`

au `PATH` pour que PDO Oracle fonctionne.
Il ouvre aussi automatiquement la page de connexion dans le navigateur.

## Base de donnees Oracle

- scripts PL/SQL: `database/`
- guide rapide: `database/README_ORACLE.md`
- script principal du schema: `database/02_schema.sql`
- objets PL/SQL: `database/05_plsql_objects.sql`

## Compte administrateur de demonstration

- Email: `admin@bookcycle.tn`
- Mot de passe: `admin123`

## Structure MVC

- `public/index.php`: front controller unique
- `routes/web.php`: routes des pages
- `routes/api.php`: routes API
- `app/Controllers`: controleurs
- `app/Models`: modeles PDO
- `app/Views`: vues
