# Fiche Projet - BookCycle Tunisia

## Intitule

**BookCycle Tunisia** - Plateforme web de don, d'echange et de reutilisation des livres scolaires.

## Problematique

Chaque annee, de nombreux livres scolaires restent inutilises alors que d'autres familles ont besoin de ces ressources.  
Le projet vise a mettre en relation les proprietaires de livres et les demandeurs dans un systeme simple et administrable.

## Objectifs

- publier des livres scolaires
- consulter un catalogue public
- envoyer et suivre des demandes
- notifier les utilisateurs
- administrer la plateforme

## Modules Concernes

- AGL
- SGBD
- Programmation Web 2
- RPA

## Technologies

- PHP 7.4
- Oracle XE
- PDO_OCI
- HTML
- CSS
- JavaScript
- PL/SQL

## Fonctionnalites Principales

- inscription et connexion
- ajout de livre
- catalogue avec filtres
- gestion des demandes
- notifications
- tableau de bord utilisateur
- espace administrateur

## Architecture

Le projet suit une architecture MVC :

- `Controllers`
- `Models`
- `Views`
- `public/index.php`

## Base De Donnees

Tables principales :

- `users`
- `subjects`
- `school_classes`
- `class_subjects`
- `books`
- `requests`
- `exchanges`
- `notifications`

Les matieres, niveaux, classes et correspondances classe -> matieres sont desormais geres dans Oracle via des tables de reference plutot que dans des listes PHP codees en dur.

Objets Oracle importants :

- sequences
- triggers
- procedures
- fonctions
- vue `v_book_overview`

## Valeur Ajoutee

- reduction des couts scolaires
- reutilisation des livres
- centralisation des echanges
- projet academique complet et coherent

## Pistes D'Amelioration

- images pour les livres
- securite plus forte
- meilleur affichage mobile
- automatisation des relances et du reporting
