# Partie SGBD

## Introduction

Dans le cadre du projet **BookCycle Tunisia**, la partie SGBD a pour objectif de concevoir et d'exploiter une base de donnees permettant de gerer les differents acteurs de la plateforme, les livres publies, les demandes de reservation, les echanges finalises ainsi que les notifications envoyees aux utilisateurs.  
Le schema de la base a ete etabli a partir du diagramme de classes defini dans la partie analyse et genie logiciel.  
Le langage SQL a ete utilise pour la creation et la manipulation des donnees, tandis que le langage **PL/SQL** a ete utilise pour les traitements plus avances comme les procedures, les fonctions, les curseurs et les triggers.

## 1. Schema relationnel de la base de donnees

Le schema relationnel retenu contient cinq tables principales.

### Table `USERS`

Cette table stocke les informations des utilisateurs de l'application.

- `id` : identifiant unique de l'utilisateur
- `name` : nom complet
- `email` : adresse mail unique
- `password` : mot de passe
- `phone` : numero de telephone
- `role` : role de l'utilisateur (`admin` ou `user`)
- `created_at` : date de creation du compte

### Table `BOOKS`

Cette table contient les livres publies sur la plateforme.

- `id` : identifiant unique du livre
- `title` : titre du livre
- `subject` : matiere
- `school_level` : niveau scolaire
- `condition_label` : etat du livre
- `description` : description libre
- `owner_id` : identifiant du proprietaire du livre
- `status` : etat du livre (`available`, `reserved`, `exchanged`)
- `is_active` : indique si le livre est encore visible
- `created_at` : date d'ajout
- `updated_at` : date de derniere modification

### Table `REQUESTS`

Cette table memorise les demandes faites par les utilisateurs pour obtenir un livre.

- `id` : identifiant de la demande
- `book_id` : identifiant du livre demande
- `requester_id` : identifiant de l'utilisateur qui fait la demande
- `status` : etat de la demande (`pending`, `accepted`, `rejected`)
- `meeting_note` : note de rendez-vous
- `request_date` : date de la demande

### Table `EXCHANGES`

Cette table conserve l'historique des echanges finalises.

- `id` : identifiant de l'echange
- `book_id` : identifiant du livre echange
- `owner_id` : identifiant du proprietaire initial
- `receiver_id` : identifiant du receveur
- `exchange_date` : date de l'echange
- `status` : statut de l'echange

### Table `NOTIFICATIONS`

Cette table stocke les notifications envoyees aux utilisateurs.

- `id` : identifiant de la notification
- `user_id` : identifiant de l'utilisateur concerne
- `message` : contenu de la notification
- `is_read` : etat de lecture
- `created_at` : date d'envoi

### Relations entre les tables

Les principales relations du schema sont les suivantes :

- `BOOKS.owner_id` reference `USERS.id`
- `REQUESTS.book_id` reference `BOOKS.id`
- `REQUESTS.requester_id` reference `USERS.id`
- `EXCHANGES.book_id` reference `BOOKS.id`
- `EXCHANGES.owner_id` reference `USERS.id`
- `EXCHANGES.receiver_id` reference `USERS.id`
- `NOTIFICATIONS.user_id` reference `USERS.id`

Ce schema permet de couvrir l'ensemble des besoins du projet : inscription, connexion, ajout de livres, recherche, demandes, suivi des echanges et consultation des notifications.

## 2. Creation des utilisateurs et gestion des privileges

La premiere etape a consiste a creer les utilisateurs de la base de donnees ainsi que les privileges necessaires.

Deux utilisateurs ont ete definis :

- `BOOKCYCLE_APP` : utilisateur principal qui possede les tables et les objets de la base
- `BOOKCYCLE_REPORT` : utilisateur secondaire destine a la consultation et au reporting

Les privileges accordes au compte principal lui permettent de :

- creer des tables
- creer des vues
- creer des procedures
- creer des fonctions
- creer des triggers
- creer des sequences si necessaire

Le compte de consultation dispose essentiellement du droit de connexion et de lecture sur les tables et la vue de synthese.

Cette organisation permet de separer les droits d'administration des droits de consultation, ce qui renforce la securite de la base.

## 3. Creation des tables et des contraintes

Les tables ont ete creees avec des contraintes d'integrite afin d'assurer la coherence des donnees.

Parmi les contraintes mises en place, on trouve :

- des cles primaires pour identifier chaque enregistrement de facon unique
- des cles etrangeres pour garantir les liens entre les tables
- des contraintes `CHECK` pour limiter les valeurs possibles de certains attributs comme les statuts
- une contrainte `UNIQUE` sur l'email des utilisateurs

Des index ont egalement ete ajoutes sur certains champs frequemment utilises dans les recherches, comme le proprietaire du livre, la matiere, le niveau scolaire ou l'identifiant du demandeur.

Une vue de synthese a aussi ete creee afin de faciliter les requetes de consultation combinant les livres et leurs proprietaires.

## 4. Requetes SQL d'interrogation

Plusieurs types de requetes SQL ont ete prepares afin de repondre aux exigences du cours et du projet.

### Affichage simple

Les requetes simples permettent d'afficher toutes les donnees d'une table :

- afficher la liste des utilisateurs
- afficher la liste des livres
- afficher la liste des demandes

### Projection

Certaines requetes affichent uniquement quelques colonnes utiles, par exemple :

- le titre et la matiere des livres
- le nom et l'email des utilisateurs

### Selection avec condition

Les criteres de recherche permettent de filtrer les donnees, par exemple :

- afficher uniquement les livres disponibles
- afficher les livres d'un niveau scolaire donne
- afficher les demandes en attente

### Recherches avec plusieurs criteres

Il est egalement possible de combiner plusieurs conditions, par exemple :

- rechercher un livre de niveau lycee dans une matiere donnee
- rechercher les demandes d'un utilisateur sur une periode

### Jointures

Les jointures permettent d'interroger plusieurs tables en meme temps :

- afficher chaque livre avec le nom de son proprietaire
- afficher chaque demande avec le titre du livre et le nom du demandeur
- afficher l'historique des echanges avec les deux utilisateurs concernes

### Fonctions d'agregation

Des requetes statistiques ont ete realisees avec :

- `COUNT` pour compter le nombre de livres
- `GROUP BY` pour grouper les livres par niveau scolaire
- `HAVING` pour filtrer les groupes

Ces requetes sont utiles pour produire les statistiques demandees dans la partie Web du projet.

### Requetes imbriquees

Des sous-requetes ont ete utilisees, par exemple pour :

- afficher les utilisateurs ayant deja publie un livre
- retrouver les livres appartenant a une categorie particuliere de proprietaires

## 5. Requetes SQL de modification

La base de donnees prend aussi en charge les requetes de modification.

### Insertion

L'insertion est utilisee pour :

- ajouter un nouvel utilisateur
- ajouter un nouveau livre
- enregistrer une nouvelle demande
- inserer une notification

### Mise a jour

Les mises a jour permettent de :

- changer le statut d'un livre
- accepter ou rejeter une demande
- marquer une notification comme lue
- desactiver un livre sans le supprimer physiquement

### Suppression

La suppression physique a ete illustree par la suppression des notifications deja lues.  
Une suppression logique a egalement ete prevue grace au champ `is_active` dans la table `BOOKS`.

## 6. Procedures PL/SQL

Afin d'automatiser certaines operations, plusieurs procedures ont ete developpees.

### Procedure `ADD_NOTIFICATION`

Cette procedure permet d'ajouter automatiquement une notification pour un utilisateur donne.  
Elle prend en parametre l'identifiant de l'utilisateur et le message a enregistrer.

Son interet est d'eviter la repetition du meme code d'insertion dans plusieurs parties du projet.

### Procedure `ACCEPT_REQUEST`

Cette procedure permet de traiter l'acceptation d'une demande de livre.  
Lorsqu'elle est executee :

- la demande choisie passe a l'etat `accepted`
- les autres demandes en attente pour le meme livre passent a l'etat `rejected`
- le statut du livre passe a `reserved`
- une notification est envoyee au demandeur

Cette procedure centralise donc une logique metier importante dans la base de donnees.

## 7. Fonctions PL/SQL

Deux fonctions ont ete mises en place.

### Fonction `COUNT_BOOKS_BY_USER`

Cette fonction retourne le nombre de livres actifs publies par un utilisateur donne.

Elle est utile pour :

- afficher des statistiques sur le tableau de bord
- verifier l'activite des utilisateurs

### Fonction `CALCULATE_MONEY_SAVED`

Cette fonction calcule l'economie estimee grace aux echanges realises.  
Dans notre projet, chaque echange est estime a `25 DT`.

Elle permet de produire une statistique de synthese simple a afficher dans l'application.

## 8. Curseurs en PL/SQL

Le cours demande l'utilisation de curseurs implicites et explicites.

### Curseur implicite

Le curseur implicite a ete illustre par une instruction `UPDATE` sur la table `NOTIFICATIONS`, suivie de l'utilisation de `SQL%ROWCOUNT` pour connaitre le nombre de lignes modifiees.

Cette approche est utile lorsque l'on veut connaitre directement le resultat d'une instruction SQL sans declarer explicitement de curseur.

### Curseur explicite

Un curseur explicite a ete cree pour parcourir la liste des livres disponibles.  
Le traitement effectue successivement :

- l'ouverture du curseur
- la lecture de chaque ligne avec `FETCH`
- l'arret a la fin des resultats
- la fermeture du curseur

Ce type de curseur est utile lorsqu'on veut traiter ligne par ligne un ensemble d'enregistrements.

## 9. Triggers

Les triggers permettent d'executer automatiquement du code PL/SQL lorsqu'un evenement se produit sur une table.

### Trigger `TRG_BOOKS_UPDATED_AT`

Ce trigger met automatiquement a jour la date de modification d'un livre a chaque `UPDATE`.

### Trigger `TRG_PREVENT_SELF_REQUEST`

Ce trigger empeche un utilisateur de faire une demande sur son propre livre.  
Ainsi, une regle de gestion est directement imposee au niveau de la base.

### Trigger `TRG_BOOK_EXCHANGE_LOG`

Ce trigger cree automatiquement un enregistrement dans la table `EXCHANGES` lorsqu'un livre passe a l'etat `exchanged`, a condition qu'une demande acceptee existe deja.

Ce mecanisme permet d'automatiser l'historisation des echanges.

## 10. Annexe des objets de la base

Dans l'annexe du rapport, il est possible d'afficher :

- les utilisateurs de la base
- les tables
- les vues
- les procedures
- les fonctions
- les triggers
- les index

Pour cela, des requetes sur les vues systeme ont ete preparees afin de consulter facilement les objets crees dans le schema.

## 11. Elements Oracle specifiques conserves

Certains elements presents dans les scripts sont propres a Oracle et ont ete conserves car ils sont utiles pour la soutenance technique et pour respecter le travail demande.

- `EXECUTE IMMEDIATE` : permet d'executer une instruction SQL construite sous forme de texte, ici pour supprimer un utilisateur avant recreation.
- `sequence + trigger` : dans Oracle XE / 11g, cette combinaison remplace l'auto-increment.
- `%TYPE` : reutilise directement le type d'une colonne dans les procedures et fonctions.
- `%ROWTYPE` : cree une variable ayant la meme structure qu'une ligne retournee.
- `SQL%ROWCOUNT` : indique le nombre de lignes affectees par une requete SQL.
- `DBMS_OUTPUT.PUT_LINE` : affiche des messages dans la console de SQL Developer.
- `ALL_USERS` et `USER_OBJECTS` : vues systeme Oracle utilisees pour afficher les utilisateurs et les objets de l'annexe.

## Conclusion

La partie SGBD du projet a permis de mettre en place une base de donnees complete et cohere nte pour la gestion de la plateforme BookCycle Tunisia.  
Le schema relationnel assure la structuration des informations, les requetes SQL permettent l'exploitation des donnees, et les objets PL/SQL ajoutent une couche d'automatisation et de securisation des traitements.  
Cette partie constitue une base solide pour le developpement de l'application Web et pour la demonstration technique lors de la soutenance.
