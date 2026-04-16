# Partie Programmation Web 2

## Introduction

Dans le cadre du projet **BookCycle Tunisia**, la partie Programmation Web 2 consiste a developper une application Web permettant de gerer le don, la consultation et la demande de livres scolaires.  
L'objectif principal est de proposer une plateforme simple et pratique reliant les utilisateurs a une base de donnees afin de faciliter la reutilisation des livres et de reduire les couts scolaires.

L'application repose sur une architecture claire, avec une separation entre la logique metier, l'acces aux donnees et les interfaces utilisateur.

Cette partie est directement liee a la base de donnees du projet, puisque toutes les operations importantes de l'application reposent sur les tables `USERS`, `BOOKS`, `REQUESTS`, `EXCHANGES` et `NOTIFICATIONS`.

## 1. Objectif de l'application

L'application Web developpee permet de repondre a plusieurs besoins :

- permettre aux utilisateurs de creer un compte et de se connecter
- permettre aux internautes de consulter les livres disponibles sans authentification
- permettre aux utilisateurs connectes d'ajouter des livres
- permettre aux utilisateurs de demander un livre publie par un autre utilisateur
- permettre au proprietaire de consulter les demandes recues et d'accepter une demande
- permettre l'affichage de statistiques de synthese
- permettre a un administrateur de consulter un tableau de bord global

Ainsi, l'application ne se limite pas a un simple affichage de donnees, mais propose un ensemble complet de fonctionnalites reposant sur les operations CRUD et les relations entre plusieurs tables.

## 2. Architecture adoptee

Pour respecter les exigences du module, l'application a ete developpee selon une architecture **MVC**.

### Modele

La couche Modele regroupe les classes responsables de l'acces aux donnees.  
Chaque modele utilise `PDO` pour executer les requetes SQL sur la base de donnees.

Les principaux modeles du projet sont :

- `User`
- `Book`
- `BookRequest`
- `Notification`

Ces modeles permettent d'effectuer les operations suivantes :

- insertion d'un nouvel utilisateur
- recherche d'un utilisateur par email
- ajout d'un livre
- affichage des livres avec filtres
- creation d'une demande
- affichage des demandes envoyees et recues
- ajout de notifications

### Vue

La couche Vue contient les pages visibles par l'utilisateur.  
Les vues sont generees en `PHP`, ce qui permet de combiner contenu statique et contenu dynamique.

Les interfaces principales sont :

- page d'accueil
- page catalogue
- page de connexion
- page d'inscription
- tableau de bord utilisateur
- page d'ajout de livre
- tableau de bord administrateur

### Controleur

La couche Controleur contient la logique qui relie les vues et les modeles.

Les principaux controleurs sont :

- `PageController`
- `AuthController`
- `BookController`
- `RequestController`
- `AdminController`

Ces controleurs gerent :

- l'affichage des pages
- l'authentification
- les operations sur les livres
- les demandes de livres
- les statistiques administratives

## 3. Technologies utilisees

Les technologies utilisees dans l'application sont les suivantes :

- `PHP` : traitement serveur
- `PDO` : connexion a la base de donnees
- `SQL` : manipulation et interrogation des donnees
- `HTML` : structure des pages
- `CSS` : mise en forme de l'interface
- `JavaScript` : interactions dynamiques et appels AJAX

Le choix de ces technologies permet de rester conforme a l'enonce tout en assurant une bonne separation entre les differentes parties du projet.

## 4. Fonctionnalites realisees

### 4.1 Inscription et connexion

L'application permet aux utilisateurs de creer un compte grace a un formulaire d'inscription contenant :

- nom complet
- email
- telephone
- mot de passe

Une fois inscrit, l'utilisateur peut se connecter grace a son email et son mot de passe.  
La verification des identifiants est effectuee cote serveur.

Cette fonctionnalite distingue clairement au moins deux types d'acteurs :

- administrateur
- utilisateur standard

### 4.2 Consultation publique du catalogue

Un internaute non connecte peut consulter les livres disponibles a partir de la page catalogue.  
Cette page propose :

- l'affichage de la liste des livres
- la recherche par matiere
- le filtrage par niveau scolaire
- la consultation des details d'un livre

Cette fonctionnalite respecte l'exigence qui impose un acces public a l'affichage et a la recherche sans authentification.

### 4.3 Ajout de livres

Un utilisateur connecte peut ajouter un nouveau livre via un formulaire contenant :

- titre
- matiere
- niveau scolaire
- etat du livre
- description

Lors de la validation, les informations sont enregistrees dans la table `BOOKS`.

### 4.4 Gestion des demandes

Lorsqu'un utilisateur trouve un livre interessant, il peut envoyer une demande au proprietaire.  
Cette demande est enregistree dans la table `REQUESTS`.

Le proprietaire du livre peut ensuite :

- consulter les demandes recues
- saisir une note de rendez-vous
- accepter une demande

Lorsqu'une demande est acceptee :

- le statut de la demande passe a `accepted`
- les autres demandes deviennent `rejected`
- le livre passe a l'etat `reserved`
- une notification est envoyee au demandeur

### 4.5 Tableau de bord utilisateur

Chaque utilisateur connecte dispose d'un espace personnel lui permettant de consulter :

- ses livres publies
- les demandes recues sur ses livres
- les demandes qu'il a envoyees

Cette interface rend la navigation plus simple et facilite le suivi des operations.

### 4.6 Tableau de bord administrateur

Un compte administrateur peut acceder a un espace d'administration qui affiche :

- le nombre total d'utilisateurs
- le nombre total de livres
- le nombre d'echanges
- l'economie estimee
- une liste recente des livres enregistres

Cette fonctionnalite repond a l'exigence de generation de statistiques et de rapports de synthese.

## 5. Exploitation de la base de donnees

L'application Web repose directement sur la base de donnees du projet.  
Les operations suivantes ont ete integrees dans l'application :

- affichage des donnees
- ajout de donnees
- recherche avec un ou plusieurs criteres
- modification des donnees
- suppression logique

Les operations ont ete realisees aussi bien sur :

- une seule table, comme l'ajout d'un utilisateur ou d'un livre
- plusieurs tables, comme l'affichage d'un livre avec son proprietaire ou d'une demande avec le demandeur

L'utilisation de `PDO` permet d'executer ces requetes de facon securisee et structuree.

## 6. Programmation orientee objet

La qualite de la programmation etant un critere d'evaluation important, le projet a ete organise selon les principes de la programmation orientee objet.

Les avantages de cette approche sont les suivants :

- meilleure lisibilite du code
- separation des responsabilites
- reutilisation plus facile des methodes
- maintenance plus simple

Par exemple :

- la gestion des utilisateurs est centralisee dans la classe `User`
- la gestion des livres est centralisee dans la classe `Book`
- la gestion des demandes est centralisee dans la classe `BookRequest`

Cette organisation evite la repetition du code et rend l'application plus evolutive.

## 7. Interfaces graphiques et navigation

Un soin particulier a ete apporte a l'interface afin de rendre l'utilisation de l'application simple et intuitive.

L'application propose une navigation claire entre :

- l'accueil
- le catalogue
- l'inscription
- la connexion
- le tableau de bord
- l'administration

Le style graphique a ete uniformise afin d'assurer une bonne coherence visuelle.  
Les formulaires sont simples, les boutons sont visibles, et les informations importantes sont mises en avant.

Dans le rapport final, il conviendra d'ajouter :

- des captures d'ecran de chaque interface
- une breve description de l'objectif de chaque page
- les parametres des comptes utilises pour les tests

## 8. Statistiques et rapports de synthese

L'application produit des indicateurs utiles a l'utilisateur et a l'administrateur.

Parmi les statistiques affichees, on trouve :

- le nombre total de livres disponibles
- le nombre total d'echanges realises
- l'economie estimee en dinars
- le nombre total d'utilisateurs

Ces statistiques sont obtenues a partir de requetes SQL d'agregation et sont affichees dans les tableaux de bord.

## 9. Cohérence generale de la solution

La solution propose une organisation claire de l'application, une base de donnees exploitee sur plusieurs tables, une separation entre les roles utilisateurs et un ensemble de fonctionnalites couvrant l'affichage, la recherche, l'ajout, le suivi des demandes et les statistiques.

## Conclusion

La partie Programmation Web 2 a permis de developper une application fonctionnelle, claire et structuree autour du projet BookCycle Tunisia.  
Grace a une organisation claire du code et a l'integration directe avec la base de donnees, il a ete possible de construire une solution Web complete reliee aux traitements du projet.  
Cette partie valorise a la fois les competences techniques en developpement Web et l'integration des traitements metier definis dans la partie base de donnees.
