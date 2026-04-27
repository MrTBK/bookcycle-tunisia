# Fiche Projet - BookCycle Tunisia

## Intitule

**BookCycle Tunisia** - Plateforme web de don, d'echange et de reutilisation des livres scolaires en Tunisie.

## Adresse Web

**`https://bookcycle-tunisia.page.gd`**

Comptes de demonstration :
- Administrateur : `admin@bookcycle.tn` / `admin123`
- Utilisateur : `ahmed@bookcycle.tn` / `user123`

## Problematique

Chaque annee, de nombreux livres scolaires restent inutilises alors que d'autres familles ont besoin de ces ressources.
Le projet vise a mettre en relation les proprietaires de livres et les demandeurs dans un systeme simple, administrable et heberge en ligne.

## Objectifs

- publier, **modifier** et gerer des livres scolaires
- consulter un catalogue public avec filtres
- envoyer et suivre des demandes
- notifier les utilisateurs
- administrer la plateforme (y compris **suppression physique** d'utilisateurs)

## Modules Concernes

- AGL (analyse, acteurs, backlog, architecture)
- SGBD (Oracle XE, PL/SQL, triggers, procedures, fonctions, curseurs)
- Programmation Web 2 (PHP MVC, PDO, CRUD complet)
- RPA (automatisation des processus metier)

## Technologies

| Composant | Local | En ligne |
|---|---|---|
| Langage | PHP 7.4 | PHP 7.4 |
| SGBD | Oracle XE | MySQL |
| Connexion | PDO_OCI | PDO_MYSQL |
| Frontend | HTML, CSS, JavaScript | Identique |

## Fonctionnalites Principales

| Fonctionnalite | Acces | Operation CRUD |
|---|---|---|
| Inscription / connexion | Public | INSERT / SELECT |
| Catalogue avec filtres | Public | SELECT multi-criteres |
| Ajouter un livre | Connecte | INSERT |
| **Modifier un livre** | Proprietaire | **UPDATE** |
| Envoyer une demande | Connecte | INSERT |
| Accepter / refuser une demande | Proprietaire | UPDATE |
| Notifications | Connecte | SELECT |
| Statistiques admin | Admin | SELECT / COUNT / SUM |
| Activer / desactiver utilisateur | Admin | UPDATE |
| **Supprimer un utilisateur** | Admin | **DELETE** |
| Masquer / restaurer un livre | Admin | UPDATE |

## Architecture MVC

```
app/
  Controllers/  <- logique metier
  Models/       <- acces PDO aux donnees
  Views/        <- rendu HTML/PHP
  Core/         <- Database, Auth, Controller
public/
  index.php     <- front controller + routage
  assets/       <- CSS, JS
database/
  01_users_privileges.sql
  02_schema.sql
  03_sample_data.sql
  04_queries.sql
  05_plsql_objects.sql
  06_triggers.sql
```

## Base De Donnees Oracle

8 tables : `users`, `subjects`, `school_classes`, `class_subjects`, `books`, `requests`, `exchanges`, `notifications`

Objets PL/SQL :
- 2 procedures : `add_notification`, `accept_request`
- 2 fonctions : `count_books_by_user`, `calculate_money_saved`
- 5 triggers metier (dont 1 statement-level)
- curseurs implicite et explicite
- 1 vue : `v_book_overview`
- 8 sequences + 8 triggers PK + 10 index

## Valeur Ajoutee

- reduction des couts scolaires pour les familles
- reutilisation des livres (ODD 12 : consommation responsable)
- projet academique integre et coherent
- site deploye en ligne et demonstrable
