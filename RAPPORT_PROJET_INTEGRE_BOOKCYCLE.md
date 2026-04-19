# Rapport Du Projet Integre

## Page De Garde

**Universite de La Manouba**  
**Ecole Superieure d'Economie Numerique**  
**Licence 2 Big Data et Intelligence**  
**Annee universitaire 2025/2026**

**Projet integre commun aux modules :**
- AGL
- SGBD
- Programmation Web 2
- RPA

**Titre du projet :** `BookCycle Tunisia`  
**Theme :** Plateforme de don, d'echange et de reutilisation des livres scolaires en Tunisie

**Realise par :**
- A completer

**Encadre par :**
- A completer

---

## Remerciements

Nous adressons nos remerciements aux enseignants des modules **AGL**, **SGBD**, **Programmation Web 2** et **RPA** pour l'accompagnement pedagogique assure tout au long de ce projet.  
Nous remercions egalement l'Ecole Superieure d'Economie Numerique pour le cadre de travail mis a disposition.

---

## Resume

Le projet **BookCycle Tunisia** propose une plateforme web permettant de reutiliser les livres scolaires au lieu de les laisser inutilises apres chaque annee scolaire.  
L'application met en relation des proprietaires de livres et des utilisateurs interesses via un catalogue filtre, un systeme de demandes, des notifications et un espace d'administration.

La solution est developpee en **PHP** selon une architecture **MVC sans framework**, avec une base de donnees **Oracle XE** exploitee via **PDO_OCI** et completee par plusieurs objets **PL/SQL**.  
Le projet couvre a la fois la modelisation, le schema relationnel, les traitements metier, l'interface web et une reflexion de reingenierie des processus.

Les ameliorations recentes integrees dans cette version incluent :
- l'ajout de pages publiques `About`, `Contact` et `Privacy Policy`
- la transformation de la `Matiere` en liste controlee au lieu d'un champ libre
- l'ajout d'un filtre `Classe` dans le catalogue
- l'adaptation du filtre `Classe` au `Niveau` selectionne
- l'enrichissement du tableau de bord administrateur avec statistiques et moderation

**Mots cles :** `Oracle`, `PL/SQL`, `PHP`, `MVC`, `PDO`, `catalogue`, `livres scolaires`, `administration`

---

## Table Des Matieres

1. Introduction generale  
2. Presentation du projet  
3. Analyse et genie logiciel  
4. Partie SGBD  
5. Partie Programmation Web 2  
6. Tests et validation  
7. Difficultes, limites et ameliorations  
8. Conclusion generale  
9. Annexes

---

## 1. Introduction Generale

Dans le cadre du projet integre commun aux modules **AGL**, **SGBD**, **RPA** et **Programmation Web 2**, il nous a ete demande de concevoir une solution complete repondant a un besoin concret.  
Le sujet choisi traite la reutilisation des livres scolaires, un probleme a la fois social, economique et environnemental.

En Tunisie, de nombreuses familles supportent chaque annee des depenses importantes liees a l'achat des livres scolaires.  
Parallelement, beaucoup de livres restent encore utilisables apres usage.  
Le projet **BookCycle Tunisia** vise donc a faciliter la circulation de ces livres entre utilisateurs, dans une logique d'entraide et d'economie circulaire.

Ce rapport presente :
- le contexte et la problematique
- l'analyse fonctionnelle et technique
- le schema Oracle et les objets PL/SQL
- l'architecture et les fonctionnalites web implementees
- les tests de validation
- les pistes d'amelioration

---

## 2. Presentation Du Projet

### 2.1 Contexte

Le projet consiste a creer une plateforme web simple permettant :
- de publier des livres scolaires disponibles
- de consulter un catalogue filtre par niveau, classe et matiere
- d'envoyer une demande pour obtenir un livre
- de suivre les demandes et notifications
- d'administrer la plateforme via un espace dedie

### 2.2 Problematique

Le besoin de depart peut etre formule ainsi :

> Comment mettre en relation, de maniere simple et fiable, les proprietaires de livres scolaires et les personnes qui recherchent ces livres, tout en assurant un suivi des demandes, des echanges et des notifications ?

Les limites des solutions informelles actuelles sont nombreuses :
- informations dispersees
- absence de suivi des demandes
- manque de moderation
- difficulte a trouver un livre adapte a une classe precise

### 2.3 Objectifs

Les objectifs du projet sont :
- modeliser le domaine de reutilisation des livres scolaires
- construire une base Oracle relationnelle coherente
- exploiter SQL et PL/SQL dans un cas reel
- developper une application web MVC connectee a Oracle
- fournir une interface claire pour visiteurs, utilisateurs et administrateur
- mettre a disposition des statistiques utiles pour le pilotage

### 2.4 Public Cible

La plateforme vise :
- les eleves
- les parents
- les etudiants
- les proprietaires de livres
- l'administrateur de la plateforme

### 2.5 Valeur Ajoutee

La valeur du projet repose sur :
- la reduction des depenses liees aux livres
- la promotion de la reutilisation
- la structuration d'un service simple d'utilisation
- la combinaison de plusieurs modules académiques dans une meme application

---

## 3. Analyse Et Genie Logiciel

### 3.1 Acteurs

#### Visiteur

Le visiteur non authentifie peut :
- consulter la page d'accueil
- voir le catalogue
- filtrer les livres par niveau, classe et matiere
- consulter les pages `About`, `Contact` et `Privacy Policy`
- acceder a l'inscription et a la connexion

#### Utilisateur

L'utilisateur authentifie peut :
- creer un compte
- se connecter
- ajouter un livre
- consulter ses livres
- envoyer une demande sur un livre
- accepter ou refuser des demandes recues s'il est proprietaire
- consulter ses notifications
- voir ses statistiques personnelles

#### Administrateur

L'administrateur peut en plus :
- consulter les statistiques globales
- rechercher un utilisateur
- activer ou desactiver un compte
- masquer ou reactiver un livre
- filtrer les demandes par statut
- annuler une demande
- envoyer des notifications a tous les utilisateurs ou a un utilisateur precis

### 3.2 Besoins Fonctionnels

Les besoins fonctionnels principaux sont :
- inscription et authentification
- gestion de session
- publication d'un livre
- generation automatique d'un titre de livre a partir de la matiere, de la classe et du niveau
- filtrage du catalogue
- affichage detaille d'un livre
- creation d'une demande
- prevention des demandes sur son propre livre
- prevention des doublons de demandes en attente
- acceptation ou refus d'une demande
- envoi de notifications
- suivi des livres, demandes et economies realisees
- administration et moderation

### 3.3 Besoins Non Fonctionnels

Les besoins non fonctionnels retenus sont :
- interface simple et claire
- organisation MVC
- validation cote serveur
- compatibilite Oracle XE
- code lisible et modulaire
- utilisation de PDO pour l'acces aux donnees
- execution locale simple via `start_oracle_app.bat`

### 3.4 Architecture Logique

L'application suit une architecture **MVC** :

- `app/Controllers` : controle des requetes HTTP
- `app/Models` : acces Oracle via PDO
- `app/Views` : rendu HTML
- `public/index.php` : front controller
- `routes/web.php` : routes pages
- `routes/api.php` : routes API

Cette architecture permet de separer :
- la logique metier
- l'acces aux donnees
- la presentation

### 3.5 Description Des Entites Metier

Les principales entites sont :
- `User`
- `Book`
- `BookRequest`
- `Notification`
- `Exchange`

Relations principales :
- un `User` peut publier plusieurs `Book`
- un `Book` appartient a un seul `User`
- un `User` peut creer plusieurs `BookRequest`
- un `BookRequest` concerne un seul `Book`
- un `User` recoit plusieurs `Notification`
- un `Exchange` historise un echange finalise

### 3.6 Scenarios Principaux

#### Scenario 1 : publication d'un livre
1. L'utilisateur se connecte.
2. Il ouvre la page `Ajouter un livre`.
3. Il choisit un niveau, une classe et une matiere dans des listes controlees.
4. L'application valide la coherence `niveau / classe`.
5. Le livre est enregistre avec le statut `available`.

#### Scenario 2 : demande d'un livre
1. L'utilisateur consulte le catalogue.
2. Il filtre les resultats.
3. Il ouvre la fiche detail d'un livre.
4. Il envoie une demande.
5. Le proprietaire recoit une notification.

#### Scenario 3 : traitement d'une demande
1. Le proprietaire ouvre son tableau de bord.
2. Il consulte les demandes recues.
3. Il accepte ou refuse la demande.
4. Le statut de la demande et du livre est mis a jour.
5. Les notifications sont envoyees aux acteurs concernes.

---

## 4. Partie SGBD

### 4.1 Choix Du SGBD

Le SGBD choisi est **Oracle XE**, en coherence avec les objectifs du module **SGBD** et la volonte d'exploiter des objets Oracle specifiques :
- sequences
- triggers
- procedures stockees
- fonctions
- curseurs
- vues

### 4.2 Scripts Fournis

Les scripts Oracle du projet sont a executer dans l'ordre suivant :
1. `01_users_privileges.sql`
2. `02_schema.sql`
3. `03_sample_data.sql`
4. `05_plsql_objects.sql`
5. `04_queries.sql`
6. `06_annex_objects.sql`

### 4.3 Schema Relationnel

Le schema comprend cinq tables principales :

#### Table `USERS`
- `id`
- `name`
- `email`
- `password`
- `phone`
- `role`
- `is_active`
- `created_at`

#### Table `BOOKS`
- `id`
- `title`
- `subject`
- `class_name`
- `school_level`
- `condition_label`
- `estimated_price`
- `description`
- `owner_id`
- `status`
- `is_active`
- `created_at`
- `updated_at`

#### Table `REQUESTS`
- `id`
- `book_id`
- `requester_id`
- `status`
- `meeting_note`
- `request_date`

#### Table `EXCHANGES`
- `id`
- `book_id`
- `owner_id`
- `receiver_id`
- `exchange_date`
- `status`

#### Table `NOTIFICATIONS`
- `id`
- `user_id`
- `sender_name`
- `message`
- `is_read`
- `created_at`

### 4.4 Contraintes Et Integrite

Le schema integre :
- des cles primaires sur chaque table
- des cles etrangeres entre tables metier
- des contraintes `CHECK` sur les champs `role`, `status`, `is_active`, `is_read`
- une contrainte d'unicite sur `users.email`

Exemples de valeurs controlees :
- `users.role` : `admin` ou `user`
- `books.status` : `available`, `reserved`, `exchanged`
- `requests.status` : `pending`, `accepted`, `rejected`

### 4.5 Sequences, Triggers Et Vue

Pour rester compatible avec Oracle XE / 11g, les identifiants sont geres via :
- `seq_users`
- `seq_books`
- `seq_requests`
- `seq_exchanges`
- `seq_notifications`

Ces sequences sont utilisees par les triggers :
- `trg_users_pk`
- `trg_books_pk`
- `trg_requests_pk`
- `trg_exchanges_pk`
- `trg_notifications_pk`

Une vue de reporting est aussi definie :
- `v_book_overview`

Cette vue facilite les requetes multi-tables entre `books` et `users`.

### 4.6 Index

Des index ont ete ajoutes pour accelerer les recherches les plus courantes :
- `idx_books_owner`
- `idx_books_subject`
- `idx_books_level`
- `idx_requests_book`
- `idx_requests_requester`
- `idx_notifications_user`

### 4.7 Objets PL/SQL Realises

Le script `05_plsql_objects.sql` contient :

#### Procedures
- `add_notification(p_user_id, p_message)`
- `accept_request(p_request_id, p_meeting_note)`

#### Fonctions
- `count_books_by_user(p_user_id)`
- `calculate_money_saved()`

#### Triggers metier
- `trg_books_updated_at`
- `trg_prevent_self_request`
- `trg_book_exchange_log`

#### Blocs PL/SQL de demonstration
- exemple de `SQL%ROWCOUNT`
- exemple de curseur explicite
- exemple d'execution de procedure et fonction

### 4.8 Apport De PL/SQL

PL/SQL renforce le projet en apportant :
- des traitements metier proches des donnees
- une meilleure reutilisation de certaines operations
- un controle supplementaire d'integrite
- des exemples concrets de programmation procedurale sur Oracle

---

## 5. Partie Programmation Web 2

### 5.1 Technologies Utilisees

La partie web repose sur :
- `PHP 7.4`
- `HTML5`
- `CSS`
- `JavaScript`
- `PDO` avec `PDO_OCI`
- Oracle Instant Client

### 5.2 Lancement Du Projet

Le lancement local est prevu via :
- `start_oracle_app.bat`

Ou manuellement :
- `C:\php74\php.exe -S localhost:8000 router.php`

Le fichier `router.php` permet de servir correctement l'application en mode developpement.

### 5.3 Pages Web Realisees

#### Pages publiques
- `/` : accueil
- `/about` : presentation du projet
- `/catalog` : catalogue et fiche detail
- `/contact` : page de contact
- `/privacy-policy` : politique de confidentialite
- `/login` : connexion
- `/register` : inscription

#### Pages privees
- `/dashboard` : tableau de bord utilisateur
- `/add-book` : ajout d'un livre
- `/admin` : espace administrateur

### 5.4 API Disponible

Le projet expose aussi plusieurs routes API :

#### Authentification
- `GET /api/me`
- `POST /api/register`
- `POST /api/login`
- `POST /api/logout`

#### Livres
- `GET /api/books`
- `POST /api/books`
- `GET /api/latest-books`
- `GET /api/my-books`
- `GET /api/stats`

#### Demandes
- `POST /api/requests`
- `GET /api/my-requests`
- `GET /api/received-requests`
- `POST /api/accept-request`
- `POST /api/reject-request`

#### Administration
- `GET /api/admin-stats`

### 5.5 Fonctionnalites Implantees

#### Authentification
- creation de compte
- connexion
- deconnexion
- maintien de session

#### Catalogue
- affichage des livres actifs
- filtre par niveau
- filtre par classe
- filtre par matiere
- consultation detaillee d'un livre

#### Ajout de livre
- choix du niveau dans une liste
- choix de la classe dans une liste
- choix de la matiere dans une liste controlee
- validation de la coherence entre `niveau` et `classe`
- creation automatique du titre

#### Gestion des demandes
- envoi d'une demande
- refus de demande sur son propre livre
- blocage des doublons en attente
- acceptation avec note de rendez-vous obligatoire
- refus de demande
- notifications des utilisateurs concernes

#### Tableau de bord
- liste des livres de l'utilisateur
- demandes recues
- demandes envoyees
- notifications recentes
- statistiques personnelles

#### Administration
- statistiques globales
- livres par niveau
- matieres les plus demandees
- recherche d'utilisateurs
- activation et desactivation des comptes
- moderation des livres
- annulation de demandes
- envoi de notifications

### 5.6 Choix D'Interface

L'interface est volontairement simple et orientee usage.  
Les evolutions recentes ont renforce la coherence fonctionnelle :
- ajout des pages d'information publique
- filtrage plus guide dans le catalogue
- suppression des saisies libres inutiles pour la matiere
- meilleure structuration de l'administration

### 5.7 Securite Et Validation

La securite implementee reste academique mais fonctionnelle :
- verification d'authentification avant actions protegees
- restriction de l'administration au role `admin`
- validation des champs cote serveur
- echappement HTML dans les vues via `htmlspecialchars`
- usage de requetes preparees PDO

Des validations metier importantes sont aussi presentes :
- impossibilite de demander son propre livre
- impossibilite d'envoyer une deuxieme demande pending
- coherence entre `school_level` et `class_name`
- verification d'une matiere autorisee

---

## 6. Tests Et Validation

### 6.1 Tests Fonctionnels

Les tests manuels principaux realises portent sur :
- inscription d'un nouvel utilisateur
- connexion avec compte utilisateur et compte admin
- ajout d'un livre
- affichage du catalogue
- filtre par niveau, matiere et classe
- demande d'un livre
- acceptation d'une demande avec note de rendez-vous
- refus d'une demande
- consultation des notifications
- actions d'administration

### 6.2 Cas De Test Representatifs

| Cas de test | Entree | Resultat attendu |
|---|---|---|
| Inscription | nom, email, telephone, mot de passe | compte cree |
| Connexion | email + mot de passe valides | ouverture du dashboard |
| Ajout livre | niveau + classe coherente + matiere valide | livre ajoute |
| Ajout livre invalide | classe ne correspondant pas au niveau | message d'erreur |
| Demande sur son propre livre | proprietaire = demandeur | action refusee |
| Demande duplicate | demande pending existante | action refusee |
| Acceptation | note de rendez-vous fournie | demande acceptee, livre reserve |
| Admin toggle user | action admin | utilisateur active ou desactive |

### 6.3 Validation Technique

La validation technique comprend :
- execution des scripts Oracle
- verification de la connexion Oracle via PDO
- execution locale par `start_oracle_app.bat`
- verification des routes pages et API
- controle visuel du rendu des vues principales

---

## 7. Difficultes, Limites Et Ameliorations

### 7.1 Difficultes Rencontrees

Les principales difficultes du projet ont ete :
- configuration de `PDO_OCI` et d'Oracle Instant Client
- adaptation a Oracle XE et a ses contraintes
- gestion des objets Oracle comme les sequences et triggers
- synchronisation entre logique metier PHP et traitements PL/SQL
- construction d'une interface simple mais complete

### 7.2 Limites Actuelles

Cette version comporte encore certaines limites :
- absence d'upload d'images pour les livres
- securisation perfectible des formulaires
- ergonomie mobile encore ameliorable
- pas de moteur de recommandation avance
- pas de workflow automatise complet cote RPA

### 7.3 Ameliorations Proposees

Les ameliorations envisageables sont :
- ajout de CSRF token sur tous les formulaires
- ajout d'un profil utilisateur editable
- gestion d'images de livres
- passage a un systeme de notifications plus riche
- tableaux de bord plus detailles
- moteur de recommandation des livres
- automatisation partielle du tri des demandes

---

## 8. Conclusion Generale

Le projet **BookCycle Tunisia** a permis de concretiser un cas d'usage realiste autour de la reutilisation des livres scolaires, en mobilisant les competences des modules **SGBD**, **Programmation Web 2**, **AGL** et **RPA**.

Sur le plan technique, le projet aboutit a une application web fonctionnelle connectee a une base **Oracle XE**, structuree selon une architecture **MVC**, et enrichie par des objets **PL/SQL** pertinents.  
Sur le plan fonctionnel, la plateforme couvre les besoins essentiels de consultation, publication, demande, notification et administration.

Cette version du rapport reflete l'etat reel du projet Oracle tel qu'il est implemente actuellement, avec les ameliorations recentes du catalogue, des pages publiques et des controles de validation.

---

## 9. Annexes

### 9.1 Arborescence Simplifiee

```text
bookcycle-tunisia/
  app/
    Config/
    Controllers/
    Core/
    Models/
    Views/
  database/
  public/
    assets/
  routes/
  router.php
  start_oracle_app.bat
```

### 9.2 Fichiers Importants

- `app/Config/config.php`
- `app/Core/Database.php`
- `app/Controllers/PageController.php`
- `app/Controllers/BookController.php`
- `app/Controllers/RequestController.php`
- `app/Controllers/AdminController.php`
- `app/Models/Book.php`
- `database/02_schema.sql`
- `database/05_plsql_objects.sql`

### 9.3 Comptes De Demonstration

- administrateur : `admin@bookcycle.tn` / `admin123`
- utilisateur : `ahmed@bookcycle.tn` / `user123`

### 9.4 Scripts Oracle

- `01_users_privileges.sql`
- `02_schema.sql`
- `03_sample_data.sql`
- `04_queries.sql`
- `05_plsql_objects.sql`
- `06_annex_objects.sql`
