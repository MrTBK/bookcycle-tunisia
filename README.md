# BookCycle Tunisia

BookCycle Tunisia est une application web academique de don, d'echange et de reutilisation des livres scolaires en Tunisie.

Le projet combine :

- une application web en `PHP`
- une architecture `MVC`
- une base de donnees `Oracle XE`
- des scripts `SQL` et `PL/SQL`
- des livrables pour `AGL`, `SGBD`, `Programmation Web 2` et `RPA`

## Objectif

L'objectif de la plateforme est de permettre :

- la publication de livres scolaires reutilisables
- la consultation d'un catalogue public
- l'envoi et le suivi de demandes
- la reception de notifications
- l'administration de la plateforme

## Fonctionnalites Principales

### Visiteur

- consulter l'accueil
- consulter le catalogue
- filtrer les livres par niveau, classe et matiere
- lire les pages `About`, `Contact` et `Privacy Policy`
- acceder a l'inscription et a la connexion

### Utilisateur Connecte

- creer un compte
- se connecter
- ajouter un livre
- consulter ses livres
- envoyer une demande pour un livre
- consulter ses demandes envoyees et recues
- consulter ses notifications

### Administrateur

- consulter les statistiques globales
- gerer les utilisateurs
- moderer les livres
- annuler des demandes
- envoyer des notifications

## Stack Technique

- `PHP 7.4`
- `Oracle XE`
- `PDO_OCI`
- `HTML`
- `CSS`
- `JavaScript`
- architecture `MVC`

## Structure Du Projet

```text
bookcycle-tunisia/
  app/
    Config/
    Controllers/
    Core/
    Models/
    Views/
  public/
    assets/
  database/
  rapports/
  documents_aide/
  router.php
  start_oracle_app.bat
  README.md
```

## Pages Principales

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

## Base De Donnees

Le schema Oracle principal contient :

- `users`
- `books`
- `requests`
- `exchanges`
- `notifications`

Le projet utilise aussi :

- des sequences
- des triggers
- des procedures
- des fonctions
- une vue de reporting `v_book_overview`

## Lancement Local

### Methode Recommandee

Depuis la racine du projet :

```powershell
start_oracle_app.bat
```

### Methode Manuelle

```powershell
C:\php74\php.exe -S localhost:8000 router.php
```

Puis ouvrir :

```text
http://localhost:8000
```

## Configuration Oracle

Configuration par defaut attendue :

- DSN : `oci:dbname=//localhost:1521/XE;charset=AL32UTF8`
- user : `bookcycle_app`
- password : `BookCycle2026`

## Ordre Des Scripts Oracle

1. `database/01_users_privileges.sql`
2. `database/02_schema.sql`
3. `database/03_sample_data.sql`
4. `database/05_plsql_objects.sql`
5. `database/04_queries.sql`
6. `database/06_annex_objects.sql`

## Comptes De Demonstration

### Admin

- email : `admin@bookcycle.tn`
- mot de passe : `admin123`

### Utilisateur

- email : `ahmed@bookcycle.tn`
- mot de passe : `user123`

## Dossiers Utiles

- `rapports/` : versions propres des rapports finals
- `documents_aide/` : guides d'explication, diagrammes et documents de revision
- `database/` : scripts Oracle et documentation base de donnees

## Resume

BookCycle Tunisia est un projet integre complet qui relie analyse, base de donnees, developpement web et reflexion sur l'automatisation autour d'un cas concret : la reutilisation des livres scolaires.
