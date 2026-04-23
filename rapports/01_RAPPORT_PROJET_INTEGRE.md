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

Nous remercions les enseignants des modules AGL, SGBD, Programmation Web 2 et RPA pour leur accompagnement durant la realisation de ce projet.  
Nous remercions egalement l'Ecole Superieure d'Economie Numerique pour le cadre pedagogique mis a disposition.

---

## Resume

**BookCycle Tunisia** est une application web academique qui facilite la reutilisation des livres scolaires en Tunisie.  
La plateforme permet a des utilisateurs de publier des livres, consulter un catalogue, envoyer des demandes, suivre les notifications et administrer la plateforme.

Le projet repose sur une architecture **MVC en PHP**, une base de donnees **Oracle XE** et plusieurs objets **PL/SQL** destines a renforcer les traitements metier.  
Il couvre a la fois l'analyse fonctionnelle, la modelisation, le developpement web, l'administration des donnees et une reflexion sur l'amelioration des processus.

**Mots cles :** `Oracle`, `PL/SQL`, `PHP`, `MVC`, `livres scolaires`, `catalogue`, `administration`

---

## 1. Introduction Generale

Chaque annee, de nombreuses familles doivent acheter des livres scolaires alors que beaucoup d'anciens livres restent encore utilisables.  
Le projet **BookCycle Tunisia** repond a ce probleme en proposant une plateforme simple qui facilite la circulation des livres entre proprietaires et demandeurs.

Ce projet integre mobilise plusieurs competences :

- **AGL** pour l'analyse, les acteurs, les besoins et l'organisation du projet
- **SGBD** pour la conception du schema Oracle et des objets PL/SQL
- **Programmation Web 2** pour l'application MVC et l'interface utilisateur
- **RPA** pour la reflexion sur l'automatisation et l'amelioration des processus

---

## 2. Presentation Du Projet

### 2.1 Contexte

Le projet vise a mettre en relation :

- des utilisateurs qui possedent des livres encore exploitables
- des utilisateurs qui recherchent ces livres
- un administrateur charge de superviser la plateforme

### 2.2 Problematique

La problematique principale est la suivante :

> Comment mettre en relation des proprietaires de livres scolaires et des personnes qui recherchent ces livres dans un systeme simple, fiable et administrable ?

### 2.3 Objectifs

Les objectifs du projet sont :

- publier des livres scolaires reutilisables
- consulter un catalogue filtre par niveau, classe et matiere
- envoyer et suivre des demandes
- notifier les utilisateurs lors des actions importantes
- fournir un espace administrateur avec moderation et statistiques

### 2.4 Public Cible

La solution vise :

- les eleves
- les parents
- les proprietaires de livres
- les utilisateurs en recherche de livres
- l'administrateur de la plateforme

### 2.5 Valeur Ajoutee

Le projet apporte :

- une reduction potentielle des couts scolaires
- une meilleure reutilisation des ressources
- une centralisation des interactions autour des livres
- une mise en pratique coherente de plusieurs modules academiques

---

## 3. Analyse Et Genie Logiciel

### 3.1 Acteurs

#### Visiteur

Le visiteur peut :

- consulter l'accueil
- parcourir le catalogue
- filtrer les livres
- consulter les pages `About`, `Contact` et `Privacy Policy`
- acceder a l'inscription et a la connexion

#### Utilisateur

L'utilisateur connecte peut :

- creer un compte et se connecter
- ajouter un livre
- consulter ses livres
- envoyer une demande
- consulter ses demandes envoyees et recues
- consulter ses notifications

#### Administrateur

L'administrateur peut en plus :

- consulter les statistiques globales
- activer ou desactiver des comptes
- masquer ou restaurer des livres
- annuler des demandes
- envoyer des notifications

### 3.2 Besoins Fonctionnels

Les besoins fonctionnels principaux sont :

- inscription et authentification
- publication d'un livre
- consultation du catalogue
- filtrage par niveau, classe et matiere
- consultation detaillee d'un livre
- creation et traitement des demandes
- systeme de notifications
- administration et moderation

### 3.3 Besoins Non Fonctionnels

Les besoins non fonctionnels retenus sont :

- architecture MVC claire
- utilisation d'Oracle XE
- validation cote serveur
- code lisible et modulaire
- execution locale simple

### 3.4 Architecture Logique

L'application suit une architecture **MVC** :

- `app/Controllers` : gestion des requetes
- `app/Models` : acces aux donnees Oracle
- `app/Views` : rendu des pages
- `public/index.php` : point d'entree et repartition directe vers les controleurs
- `public/assets/js/app.js` : petites interactions cote client sans dependre d'une API JSON

---

## 4. Partie SGBD

### 4.1 Choix Du SGBD

Le projet utilise **Oracle XE** afin de respecter les objectifs du module SGBD et d'exploiter :

- des sequences
- des triggers
- des vues
- des procedures et fonctions PL/SQL

### 4.2 Tables Principales

Le schema contient cinq tables principales :

- `users`
- `books`
- `requests`
- `exchanges`
- `notifications`

### 4.3 Contraintes Et Integrite

Le schema integre :

- des cles primaires
- des cles etrangeres
- des contraintes `CHECK`
- une contrainte `UNIQUE` sur `users.email`
- des index pour les recherches frequentes

### 4.4 Objets PL/SQL

Les objets PL/SQL principaux sont :

- `add_notification`
- `accept_request`
- `count_books_by_user`
- `calculate_money_saved`
- `trg_books_updated_at`
- `trg_prevent_self_request`
- `trg_book_exchange_log`

---

## 5. Partie Programmation Web 2

### 5.1 Technologies Utilisees

La partie web repose sur :

- `PHP 7.4`
- `HTML`
- `CSS`
- `JavaScript`
- `PDO_OCI`
- `Oracle XE`

### 5.2 Pages Web Realisees

Les principales pages presentes dans l'application sont :

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

### 5.3 Fonctionnement Web

L'application fonctionne desormais sans couche API separee.
Les ecrans sont rendus cote serveur en PHP et les actions principales passent par :

- des pages HTML classiques
- des formulaires `GET` et `POST`
- des redirections avec messages flash
- un point d'entree unique `public/index.php`

### 5.4 Fonctionnalites Principales

Le projet permet :

- l'inscription et la connexion
- l'ajout d'un livre
- le filtrage du catalogue
- l'envoi d'une demande
- l'acceptation ou le refus d'une demande
- l'affichage des notifications
- l'acces a un tableau de bord utilisateur
- l'acces a un tableau de bord administrateur

---

## 6. Partie RPA

La partie RPA concerne surtout l'amelioration du traitement des demandes.  
Le processus le plus critique est celui de la reponse du proprietaire lorsqu'un livre est demande.

Les pistes d'automatisation proposees sont :

- relancer les demandes `pending` anciennes
- prioriser les demandes
- notifier automatiquement les utilisateurs concernes
- preparer des indicateurs de suivi pour l'administration

---

## 7. Tests Et Validation

Les tests manuels les plus importants ont porte sur :

- inscription
- connexion
- ajout d'un livre
- affichage du catalogue
- filtres du catalogue
- envoi d'une demande
- acceptation et refus d'une demande
- affichage des statistiques admin

La validation technique comprend :

- l'execution des scripts Oracle
- la verification de la connexion PDO Oracle
- le lancement local via `start_oracle_app.bat`
- le controle des pages et formulaires principaux

---

## 8. Difficultes Et Limites

Les difficultes principales ont ete :

- la configuration d'Oracle Instant Client
- l'utilisation de `PDO_OCI`
- la compatibilite Oracle XE
- la synchronisation entre logique PHP et logique PL/SQL

Les limites actuelles sont :

- absence d'images pour les livres
- securite encore perfectible
- ergonomie mobile ameliorable
- automatisation RPA encore partielle

---

## 9. Ameliorations Proposees

Les ameliorations possibles sont :

- ajout de CSRF token
- ajout d'images de livres
- enrichissement du profil utilisateur
- tableaux de bord plus detailles
- automatisation plus poussee du reporting et des relances

---

## 10. Conclusion Generale

**BookCycle Tunisia** est un projet integre coherent qui relie analyse, base de donnees, developpement web et reflexion sur l'automatisation.  
La solution obtenue est fonctionnelle, claire et suffisamment riche pour illustrer les objectifs des modules concernes.

Le projet montre qu'une architecture simple en PHP, associee a Oracle et a des objets PL/SQL bien choisis, permet de construire une plateforme utile et pedagogiquement solide.
