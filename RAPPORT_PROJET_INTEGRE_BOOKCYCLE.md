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
**Theme :** Plateforme de gestion, de don et de reutilisation des livres scolaires

**Realise par :**
- A completer

**Encadre par :**
- A completer

---

## Remerciements

Nous tenons a remercier l'ensemble des enseignants ayant assure l'encadrement pedagogique des modules du projet integre, ainsi que toute personne ayant contribue a la realisation de ce travail.  
Nous remercions egalement l'administration de l'Ecole Superieure d'Economie Numerique pour le cadre de formation mis a notre disposition.

---

## Resume

Le projet **BookCycle Tunisia** est une application de gestion de livres scolaires permettant aux utilisateurs de publier, consulter, demander et reutiliser des livres selon le niveau scolaire, la classe, la matiere et l'etat du livre.  
L'objectif principal est de reduire le cout des fournitures scolaires et de faciliter l'echange entre proprietaires de livres et utilisateurs interesses.

Le projet repose sur une base de donnees relationnelle Oracle, des traitements PL/SQL, une application Web developpee en PHP sans framework, et une organisation claire de type MVC.  
La solution proposee couvre l'inscription, l'authentification, la consultation publique du catalogue, la gestion des demandes, les notifications, les statistiques et l'administration.

**Mots cles :** `PHP`, `Oracle`, `PL/SQL`, `MVC`, `PDO`, `application web`, `base de donnees`, `livres scolaires`

---

## Table Des Matieres

1. Introduction generale  
2. Presentation du projet  
3. Analyse et genie logiciel  
4. Partie SGBD  
5. Partie Programmation Web 2  
6. Tests et validation  
7. Difficultes rencontrees et ameliorations  
8. Conclusion generale  
9. Annexes

---

## 1. Introduction Generale

Dans le cadre du projet integre commun aux modules **AGL**, **SGBD**, **RPA** et **Programmation Web 2**, il nous a ete demande de concevoir et realiser une solution informatique complete repondant a un besoin reel.  
Le projet retenu porte sur la reutilisation et la circulation des livres scolaires en Tunisie a travers une plateforme web simple, claire et orientee utilisateurs.

Le choix de ce sujet se justifie par plusieurs constats :
- le cout eleve des livres scolaires pour de nombreuses familles
- la disponibilite de nombreux livres deja utilises mais encore exploitables
- le besoin d'une plateforme de mise en relation entre offreurs et demandeurs
- l'interet pedagogique du sujet pour manipuler les notions de modelisation, de base de donnees et de developpement Web

L'objectif de ce rapport est de presenter les differentes etapes de realisation du projet, les choix techniques adoptes, les fonctionnalites developpees, la structure de la base de donnees et l'architecture generale de la solution.

---

## 2. Presentation Du Projet

### 2.1 Contexte

Le projet **BookCycle Tunisia** vise a fournir un espace numerique permettant aux utilisateurs :
- de consulter des livres scolaires disponibles
- de publier leurs propres livres
- d'envoyer des demandes pour obtenir un livre
- de suivre les demandes recues et envoyees
- de recevoir des notifications
- de beneficier d'un espace administrateur pour le suivi global de la plateforme

### 2.2 Problematique

De nombreux livres scolaires deviennent inutilises apres la fin d'une annee scolaire alors qu'ils restent en bon etat.  
En meme temps, d'autres eleves ou familles ont besoin de ces livres, parfois a un cout difficile a supporter.  
Il existe donc un besoin de centraliser la publication, la recherche et le suivi des demandes dans une solution simple d'utilisation.

### 2.3 Objectifs

Les objectifs principaux du projet sont :
- concevoir une application web de partage et de reutilisation de livres scolaires
- construire une base de donnees relationnelle coherente a partir du domaine etudie
- exploiter SQL et PL/SQL dans le cadre du module SGBD
- developper une application Web 2 connectee a la base de donnees
- appliquer les principes de la programmation orientee objet et du modele MVC
- produire des statistiques utiles pour les utilisateurs et les administrateurs

### 2.4 Public Cible

La plateforme vise principalement :
- les eleves
- les etudiants
- les parents
- les proprietaires de livres souhaitant en faire don ou les reutiliser
- l'administrateur de la plateforme

---

## 3. Analyse Et Genie Logiciel

### 3.1 Etude des acteurs

Les principaux acteurs identifies dans le systeme sont :

#### Visiteur

Le visiteur n'est pas authentifie. Il peut :
- consulter la page d'accueil
- rechercher des livres dans le catalogue
- voir les details d'un livre
- acceder aux pages de connexion et d'inscription

#### Utilisateur

L'utilisateur authentifie peut :
- creer un compte
- se connecter
- ajouter un livre
- consulter ses livres
- envoyer une demande de livre
- consulter ses demandes envoyees et recues
- lire ses notifications

#### Administrateur

L'administrateur possede tous les droits d'un utilisateur classique, avec des privileges supplementaires :
- consulter les statistiques globales
- voir tous les utilisateurs
- desactiver ou reactiver un utilisateur
- voir tous les livres
- masquer ou reactiver un livre
- consulter toutes les demandes
- filtrer les demandes par statut
- annuler une demande en cas de probleme
- envoyer des notifications globales ou individuelles

### 3.2 Besoins fonctionnels

Les principaux besoins fonctionnels retenus sont :
- inscription d'un utilisateur
- authentification
- gestion du profil utilisateur via session
- ajout d'un livre
- recherche et filtrage du catalogue
- affichage des details d'un livre
- envoi d'une demande de livre
- acceptation ou refus d'une demande
- notification des utilisateurs
- affichage des statistiques
- administration globale de la plateforme

### 3.3 Besoins non fonctionnels

Les besoins non fonctionnels du projet sont :
- interface simple et intuitive
- separation claire entre la logique et l'affichage
- securisation minimale des acces via session
- validation des entrees cote serveur
- code lisible et organise
- compatibilite avec Oracle XE pour la base de donnees

### 3.4 Diagrammes UML

Dans le cadre du module AGL, les diagrammes suivants doivent etre integres dans la version finale du rapport si vous les avez deja realises dans un autre document :
- diagramme de cas d'utilisation
- diagramme de classes
- eventuellement diagramme de sequences

**Zone a completer manuellement si necessaire :**
- Inserer le diagramme de cas d'utilisation
- Inserer le diagramme de classes
- Inserer les captures ou schemas UML du module AGL

### 3.5 Description textuelle du diagramme de classes

Le domaine du projet conduit a identifier les classes metier principales suivantes :
- `User`
- `Book`
- `BookRequest`
- `Notification`
- `Exchange`

Relations principales :
- un utilisateur peut publier plusieurs livres
- un livre appartient a un seul proprietaire
- un utilisateur peut envoyer plusieurs demandes
- une demande concerne un seul livre
- un utilisateur peut recevoir plusieurs notifications
- un echange finalise relie un proprietaire, un receveur et un livre

---

## 4. Partie SGBD

### 4.1 Choix du SGBD

Le systeme de gestion de base de donnees retenu est **Oracle XE**, conformement a la recommandation de l'enonce.  
L'editeur recommande pour l'execution des scripts est **Oracle SQL Developer**.

### 4.2 Schema relationnel

Le schema relationnel realise comprend les tables suivantes :
- `USERS`
- `BOOKS`
- `REQUESTS`
- `EXCHANGES`
- `NOTIFICATIONS`

Description resumee :

#### Table `USERS`

Cette table stocke les informations relatives aux utilisateurs :
- `id`
- `name`
- `email`
- `password`
- `phone`
- `role`
- `is_active`
- `created_at`

#### Table `BOOKS`

Cette table represente les livres publies :
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

Cette table memorise les demandes effectuees sur les livres :
- `id`
- `book_id`
- `requester_id`
- `status`
- `meeting_note`
- `request_date`

#### Table `EXCHANGES`

Cette table historise les echanges finalises :
- `id`
- `book_id`
- `owner_id`
- `receiver_id`
- `exchange_date`
- `status`

#### Table `NOTIFICATIONS`

Cette table stocke les notifications applicatives :
- `id`
- `user_id`
- `sender_name`
- `message`
- `is_read`
- `created_at`

### 4.3 Contraintes et integrite

Pour assurer la coherence des donnees, plusieurs contraintes ont ete mises en place :
- cles primaires sur toutes les tables
- cles etrangeres entre utilisateurs, livres, demandes, echanges et notifications
- contraintes `CHECK` sur les roles et les statuts
- contrainte `UNIQUE` sur l'email utilisateur

### 4.4 Creation des utilisateurs et privileges

Deux utilisateurs Oracle ont ete prevus :
- `BOOKCYCLE_APP` : proprietaire des objets applicatifs
- `BOOKCYCLE_REPORT` : utilisateur secondaire dedie a la consultation

Privileges principaux accordes a `BOOKCYCLE_APP` :
- `CREATE SESSION`
- `CREATE TABLE`
- `CREATE VIEW`
- `CREATE PROCEDURE`
- `CREATE TRIGGER`
- `CREATE SEQUENCE`

Privileges de `BOOKCYCLE_REPORT` :
- `CREATE SESSION`
- `SELECT` sur les tables et la vue de synthese

Le script correspondant est :
- [database/01_users_privileges.sql](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/database/01_users_privileges.sql)

### 4.5 Scripts SQL et PL/SQL realises

Les scripts fournis dans le projet sont :
- [database/01_users_privileges.sql](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/database/01_users_privileges.sql)
- [database/02_schema.sql](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/database/02_schema.sql)
- [database/03_sample_data.sql](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/database/03_sample_data.sql)
- [database/04_queries.sql](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/database/04_queries.sql)
- [database/05_plsql_objects.sql](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/database/05_plsql_objects.sql)
- [database/06_annex_objects.sql](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/database/06_annex_objects.sql)

### 4.6 Requetes d'interrogation et de modification

Conformement au travail demande, plusieurs types de requetes SQL ont ete prepares :
- selection simple
- projection
- selection avec condition
- recherche multicritere
- tri
- jointures
- aggregation avec `COUNT`
- `GROUP BY`
- `HAVING`
- sous-requetes
- `UPDATE`
- `DELETE`
- suppression logique

Exemples presentes dans le script :
- affichage des utilisateurs
- affichage des livres disponibles
- jointure livres et proprietaires
- statistiques par niveau scolaire
- mise a jour du statut d'un livre
- suppression des notifications lues

### 4.7 Objets PL/SQL

Les objets PL/SQL implementes sont :

#### Procedures
- `ADD_NOTIFICATION`
- `ACCEPT_REQUEST`

#### Fonctions
- `COUNT_BOOKS_BY_USER`
- `CALCULATE_MONEY_SAVED`

#### Triggers
- `TRG_USERS_PK`
- `TRG_BOOKS_PK`
- `TRG_REQUESTS_PK`
- `TRG_EXCHANGES_PK`
- `TRG_NOTIFICATIONS_PK`
- `TRG_BOOKS_UPDATED_AT`
- `TRG_PREVENT_SELF_REQUEST`
- `TRG_BOOK_EXCHANGE_LOG`

#### Curseurs et blocs PL/SQL
- exemple de curseur implicite avec `SQL%ROWCOUNT`
- exemple de curseur explicite avec `FETCH`
- utilisation de `DBMS_OUTPUT.PUT_LINE`

### 4.8 Elements Oracle specifiques conserves

Certains elements ont ete gardes volontairement car ils sont adaptes a Oracle :
- `EXECUTE IMMEDIATE`
- `sequence + trigger`
- `%TYPE`
- `%ROWTYPE`
- `SQL%ROWCOUNT`
- `DBMS_OUTPUT.PUT_LINE`
- `ALL_USERS`
- `USER_OBJECTS`

### 4.9 Annexe des objets BD

Pour l'annexe du rapport, le projet contient deja les requetes permettant d'afficher :
- les utilisateurs Oracle lies au projet
- les tables
- les vues
- les procedures
- les fonctions
- les triggers
- les index

Le script correspondant est :
- [database/06_annex_objects.sql](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/database/06_annex_objects.sql)

### 4.10 Conclusion de la partie SGBD

La partie SGBD a permis de construire une base relationnelle complete, exploitable par l'application Web et conforme aux attentes du module.  
Elle couvre la creation des schemas, la gestion des acces, les requetes SQL, les procedures, les fonctions, les triggers et les annexes demandees.

---

## 5. Partie Programmation Web 2

### 5.1 Technologies utilisees

L'application Web 2 a ete developpee avec :
- `PHP` sans framework
- `HTML`
- `CSS`
- `JavaScript` natif
- `PDO` pour la connexion a Oracle

### 5.2 Architecture adoptee

Pour respecter les bonnes pratiques du module, le projet suit une architecture **MVC**.

#### Modeles

Les modeles se trouvent dans `app/Models` :
- `User`
- `Book`
- `BookRequest`
- `Notification`

Ils sont responsables de l'acces a la base de donnees et de l'execution des requetes.

#### Controleurs

Les controleurs se trouvent dans `app/Controllers` :
- `PageController`
- `AuthController`
- `BookController`
- `RequestController`
- `AdminController`
- `NotificationController`

Ils assurent :
- l'affichage des pages
- l'authentification
- le traitement des formulaires
- l'administration
- la lecture des notifications

#### Vues

Les vues se trouvent dans `app/Views` et sont organisees entre :
- `layouts`
- `pages`

Les pages principales sont :
- accueil
- catalogue
- inscription
- connexion
- ajout de livre
- tableau de bord utilisateur
- administration

### 5.3 Structure du projet

La structure generale du projet est la suivante :

- `app/Controllers`
- `app/Models`
- `app/Views`
- `app/Core`
- `routes`
- `public`
- `database`

Le fichier `public/index.php` joue le role de point d'entree principal.  
Le fichier `router.php` permet d'utiliser le serveur PHP integre en environnement local.

### 5.4 Fonctionnalites realisees

#### 5.4.1 Page d'accueil

La page d'accueil presente :
- une introduction a la plateforme
- les statistiques principales
- les derniers livres ajoutes

#### 5.4.2 Inscription

La page d'inscription permet de creer un compte a partir des champs :
- nom complet
- email
- telephone
- mot de passe

Une verification est realisee cote serveur pour refuser les champs vides et les adresses email deja existantes.

#### 5.4.3 Connexion

La page de connexion permet l'authentification via :
- email
- mot de passe

La verification du mot de passe est effectuee avec `password_verify`.

#### 5.4.4 Catalogue public

Le catalogue est accessible sans authentification.  
Il permet :
- l'affichage des livres disponibles
- la recherche par matiere
- le filtrage par niveau
- l'affichage des details d'un livre

#### 5.4.5 Ajout d'un livre

Un utilisateur connecte peut publier un livre en renseignant :
- la matiere
- le niveau
- la classe
- l'etat
- le prix estime

Les donnees sont inserees dans la table `BOOKS`.

#### 5.4.6 Gestion des demandes

Un utilisateur connecte peut envoyer une demande sur un livre qui ne lui appartient pas.  
Le proprietaire peut ensuite :
- consulter les demandes recues
- accepter une demande
- refuser une demande

Lors de l'acceptation :
- la demande choisie devient `accepted`
- les autres demandes deviennent `rejected`
- le livre devient `reserved`
- une notification est envoyee

#### 5.4.7 Tableau de bord utilisateur

Le tableau de bord utilisateur affiche :
- les statistiques personnelles
- les livres publies
- les demandes recues
- les demandes envoyees
- les notifications

Les notifications affichent :
- l'expediteur
- le message
- la date
- l'etat lue/non lue

#### 5.4.8 Notifications

Le systeme de notifications permet :
- l'ajout de notifications lors de certaines actions metier
- l'affichage des notifications dans le tableau de bord
- l'affichage du nombre de notifications non lues dans la barre de navigation
- un menu deroulant dans la barre de navigation
- le marquage individuel comme lue lors du clic sur une notification

#### 5.4.9 Administration

La partie administrateur propose des fonctionnalites plus avancees que celles d'un utilisateur standard.

L'administrateur peut :
- consulter les statistiques globales
- voir tous les utilisateurs
- desactiver ou reactiver un utilisateur
- voir tous les livres
- masquer un livre inapproprie
- reactiver un livre masque
- voir qui a poste chaque livre
- consulter toutes les demandes du systeme
- filtrer les demandes par statut
- annuler une demande en cas de probleme
- envoyer une notification a un seul utilisateur
- envoyer une notification globale a tous les utilisateurs actifs

### 5.5 Connexion avec la base de donnees

L'application utilise `PDO` pour interagir avec Oracle via la classe :
- `app/Core/Database.php`

Le projet a ete configure pour fonctionner avec :
- Oracle XE
- Instant Client
- `pdo_oci`
- `oci8`

Le lancement local prevu est documente dans :
- [README.md](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/README.md)
- [database/README_ORACLE.md](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/database/README_ORACLE.md)

### 5.6 Programmation orientee objet

L'application suit une logique orientee objet simple :
- les responsabilites sont separees
- les modeles encapsulent les traitements lies aux donnees
- les controleurs gerent la logique applicative
- les vues affichent les informations

Cette organisation rend le code plus :
- lisible
- maintenable
- reutilisable

### 5.7 Captures d'ecran a inserer

Pour la version finale du rapport, il est recommande d'ajouter les captures suivantes :
- page d'accueil
- page de connexion
- page d'inscription
- catalogue
- formulaire d'ajout de livre
- tableau de bord utilisateur
- espace administrateur
- notifications dans la barre de navigation

**Zone a completer manuellement :**
- Inserer capture de la page d'accueil
- Inserer capture du catalogue
- Inserer capture de l'inscription
- Inserer capture du tableau de bord
- Inserer capture de l'administration

### 5.8 Conclusion de la partie Web 2

La partie Web 2 a permis de realiser une application fonctionnelle, structurée et directement reliee a la base de donnees.  
Elle respecte les exigences principales du module a travers l'utilisation de PHP, du modele MVC, des formulaires, des traitements serveur et de l'acces aux donnees via PDO.

---

## 6. Tests Et Validation

### 6.1 Tests fonctionnels realises

Les tests fonctionnels principaux portent sur :
- l'inscription d'un utilisateur
- la connexion
- la consultation du catalogue
- l'ajout d'un livre
- l'envoi d'une demande
- l'acceptation et le refus d'une demande
- l'affichage des notifications
- les actions administratives

### 6.2 Jeux de donnees

Des donnees de demonstration ont ete ajoutees dans :
- [database/03_sample_data.sql](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/database/03_sample_data.sql)

Comptes utiles :
- administrateur : `admin@bookcycle.tn / admin123`
- utilisateur de test : `ahmed@bookcycle.tn / user123`

### 6.3 Verification syntaxique

Au cours du developpement, une verification syntaxique des fichiers PHP a ete effectuee avec `php -l`, ce qui a permis de confirmer l'absence d'erreurs de syntaxe dans les fichiers modifies.

### 6.4 Soutenance technique

Lors de la soutenance technique, il sera pertinent de montrer :
- l'execution des scripts Oracle
- la creation de la base
- l'insertion des donnees d'exemple
- l'affichage des requetes SQL
- l'execution des procedures et fonctions PL/SQL
- le fonctionnement de l'application Web
- la gestion des roles utilisateur et administrateur

---

## 7. Difficultes Rencontrees Et Ameliorations

### 7.1 Difficultes rencontrees

Parmi les principales difficultes rencontrees :
- la configuration de la connexion PHP avec Oracle
- la gestion des sessions et des redirections
- la coordination entre logique metier, vues et base de donnees
- la mise en place des notifications
- la gestion des droits specifiques de l'administrateur

### 7.2 Ameliorations possibles

Des ameliorations futures peuvent etre envisagees :
- ajout d'une gestion plus complete du profil utilisateur
- ajout d'un systeme de recherche avancee
- ajout d'un historique des actions administratives
- ajout de pagination dans le catalogue
- ajout de pièces jointes ou images pour les livres
- ajout d'une messagerie directe entre utilisateurs
- ajout d'une vraie gestion de rapports ou signalements

### 7.3 Partie RPA

Le sujet global cite egalement le module RPA.  
Dans la version actuelle du depot, aucune implementation RPA autonome n'apparait explicitement dans les fichiers disponibles.  
Si une partie RPA a ete realisee en dehors de ce depot, il faudra l'ajouter a la version finale du rapport.

**Zone a completer manuellement si necessaire :**
- objectif de la partie RPA
- outil utilise
- scenario automatise
- captures d'execution

---

## 8. Conclusion Generale

Le projet **BookCycle Tunisia** a permis de concevoir une solution complete combinant analyse, base de donnees et developpement Web autour d'un besoin concret.  
Le systeme mis en place facilite la reutilisation des livres scolaires et propose une plateforme claire reposant sur :
- une base de donnees Oracle structuree
- des scripts SQL et PL/SQL conformes aux attentes du module SGBD
- une application Web developpee en PHP suivant une architecture MVC
- une interface utilisateur simple avec un espace administrateur

Le projet constitue ainsi une application integree coherente, pedagogiquement riche, et presentable lors de la soutenance technique.

---

## 9. Annexes

### 9.1 Fichiers principaux du projet

- [README.md](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/README.md)
- [app/Controllers](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/app/Controllers)
- [app/Models](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/app/Models)
- [app/Views](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/app/Views)
- [routes/web.php](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/routes/web.php)
- [routes/api.php](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/routes/api.php)

### 9.2 Scripts base de donnees

- [database/01_users_privileges.sql](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/database/01_users_privileges.sql)
- [database/02_schema.sql](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/database/02_schema.sql)
- [database/03_sample_data.sql](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/database/03_sample_data.sql)
- [database/04_queries.sql](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/database/04_queries.sql)
- [database/05_plsql_objects.sql](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/database/05_plsql_objects.sql)
- [database/06_annex_objects.sql](C:/Users/Mega-Pc/Desktop/bookcycle-tunisia/database/06_annex_objects.sql)

### 9.3 Ordre d'execution Oracle

Depuis Oracle SQL Developer :

1. Se connecter avec `SYSTEM`
2. Executer `01_users_privileges.sql`
3. Se connecter avec `BOOKCYCLE_APP`
4. Executer `02_schema.sql`
5. Executer `03_sample_data.sql`
6. Executer `05_plsql_objects.sql`
7. Executer `04_queries.sql`
8. Executer `06_annex_objects.sql`

### 9.4 Notes finales de personnalisation

Avant la remise finale du rapport, il est recommande de personnaliser les elements suivants :
- noms des etudiants
- nom de l'encadrant
- captures d'ecran
- diagrammes UML
- toute section RPA realisee en dehors du depot
