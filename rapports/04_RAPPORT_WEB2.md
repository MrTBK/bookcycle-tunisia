# Rapport Programmation Web 2 - BookCycle Tunisia

## Introduction

La partie Programmation Web 2 du projet **BookCycle Tunisia** consiste a developper une application web permettant de consulter, publier et demander des livres scolaires.  
L'application est connectee a une base Oracle et reste basee sur les notions classiques vues en PHP : pages, formulaires, sessions, redirections et acces aux donnees avec `PDO`.

---

## 1. Objectif De L'Application

L'objectif de l'application est de :

- permettre aux visiteurs de consulter un catalogue public
- permettre aux utilisateurs de publier des livres
- permettre l'envoi et le suivi de demandes
- permettre la consultation de notifications
- permettre a un administrateur de superviser la plateforme

---

## 2. Architecture Adoptee

Le projet est organise de facon simple en trois parties :

- des fichiers qui gerent les traitements
- des fichiers qui lisent les donnees
- des fichiers qui affichent les pages

Cette organisation correspond a une presentation de type **MVC**, mais elle reste surtout une facon claire de ranger le code PHP du projet.

### Acces Aux Donnees

Les fichiers de donnees assurent l'acces a Oracle via `PDO`.

Exemples :

- `AcademicOption`
- `User`
- `Book`
- `BookRequest`
- `Notification`

### Pages Affichees

Les vues affichent les pages de l'application en PHP.

Pages principales :

- accueil
- catalogue
- connexion
- inscription
- tableau de bord
- ajout de livre
- administration

### Traitement Des Actions

Les fichiers de traitement relient le point d'entree web, les donnees et les pages affichees.

Principaux fichiers de traitement :

- `PageController`
- `AuthController`
- `BookController`
- `RequestController`
- `AdminController`
- `NotificationController`

---

## 3. Technologies Utilisees

Les technologies principales sont :

- `PHP 7.4`
- `HTML`
- `CSS`
- `JavaScript`
- `PDO_OCI`
- `Oracle XE`

Le serveur local peut etre lance via :

- `start_oracle_app.bat`

ou avec :

- `C:\php74\php.exe -S localhost:8000 router.php`

---

## 4. Pages Web Realisees

Les pages presentes dans le projet sont :

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

---

## 5. Fonctionnement Web

Le projet ne repose plus sur une API JSON separee.
Le fonctionnement web est base sur :

- le rendu serveur des pages en PHP
- des formulaires HTML classiques
- des redirections apres traitement
- un script JavaScript leger reserve a quelques interactions d'interface

Les listes de matieres, de niveaux et de classes sont maintenant chargees depuis Oracle a travers le modele `AcademicOption`.
Cette approche remplace les anciennes listes codees en dur et garde le code PHP aligne avec les donnees reelles de la base.

---

## 6. Fonctionnalites Principales

### 6.1 Inscription Et Connexion

L'application permet :

- la creation d'un compte
- la connexion
- la deconnexion
- le maintien de session

### 6.2 Catalogue Public

Le visiteur peut :

- consulter les livres actifs
- filtrer par niveau
- filtrer par classe
- filtrer par matiere
- ouvrir la fiche detail d'un livre

### 6.3 Ajout De Livre

L'utilisateur connecte peut ajouter un livre avec :

- un niveau
- une classe
- une matiere
- un etat
- une description
- un prix estime

### 6.4 Gestion Des Demandes

L'application permet :

- d'envoyer une demande
- de bloquer les demandes sur son propre livre
- d'eviter les doublons `pending`
- d'accepter une demande avec note de rendez-vous
- de refuser une demande

### 6.5 Tableau De Bord Utilisateur

Le tableau de bord affiche :

- les livres de l'utilisateur
- les demandes recues
- les demandes envoyees

### 6.6 Administration

L'espace administrateur affiche :

- le nombre total d'utilisateurs
- le nombre total de livres
- le nombre total d'echanges
- l'economie estimee
- une liste de livres recents

Il permet aussi :

- d'activer ou desactiver un utilisateur
- de masquer ou restaurer un livre
- d'annuler une demande
- d'envoyer des notifications

---

## 7. Validation Et Securite

Le projet met en place :

- des controles d'authentification
- une restriction des actions admin au role `admin`
- des validations cote serveur
- des requetes preparees PDO
- un echappement HTML dans les vues

Les regles metier importantes incluent :

- impossibilite de demander son propre livre
- prevention des demandes en double
- coherence entre niveau et classe
- verification des matieres autorisees depuis la table `subjects`

---

## 8. Points Forts Et Limites

### Points Forts

- organisation claire
- separation simple des responsabilites
- integration reelle avec Oracle
- front dynamique simple avec JavaScript
- tableau de bord admin utile pour la soutenance

### Limites

- pas d'upload d'image
- securite encore perfectible
- pas de tests automatises
- ergonomie mobile encore a renforcer

---

## 9. Alignement Avec Le Cours PHP

Le projet reutilise bien les notions principales vues dans le cours de PHP :

- syntaxe PHP integree dans les pages HTML
- formulaires avec `method="post"`
- recuperation des donnees avec `$_POST`
- conditions, tableaux, `foreach` et fonctions
- inclusion et chargement de fichiers
- sessions et redirections
- acces a la base avec `PDO`

Ces notions sont visibles dans des fichiers comme :

- `app/Views/pages/register.php`
- `app/bootstrap.php`
- `app/Core/Database.php`
- `app/Controllers/AuthController.php`

Le projet est simplement plus organise qu'un petit exercice procedural.
L'idee importante pour la soutenance est que les notions de base du cours ont bien ete reutilisees dans une application complete.

---

## Conclusion

La partie Web 2 de **BookCycle Tunisia** a permis de construire une application complete, reliee a Oracle, couvrant les besoins essentiels du projet.  
Elle valorise surtout les notions de PHP vues en cours, la gestion des roles, les operations CRUD et l'exploitation d'une base de donnees reelle.
