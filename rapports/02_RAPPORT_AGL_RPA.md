# Rapport AGL Et RPA

## Introduction

Ce document presente la partie **AGL** et la partie **RPA** du projet **BookCycle Tunisia**.  
Il decrit les acteurs, les besoins, l'organisation du projet, les processus metier et les pistes d'automatisation retenues.

---

## 1. Partie AGL

### 1.1 Demarche De Genie Logiciel

Le projet a suivi une logique de travail progressive :

- analyse du besoin
- identification des acteurs
- modelisation du domaine
- conception de la base Oracle
- developpement de l'application MVC
- tests et corrections

### 1.2 Acteurs

#### Visiteur

Le visiteur peut :

- consulter l'accueil
- parcourir le catalogue
- filtrer les livres
- consulter les pages d'information
- s'inscrire et se connecter

#### Utilisateur

L'utilisateur peut :

- publier un livre
- envoyer une demande
- consulter ses livres
- consulter ses demandes
- consulter ses notifications

#### Administrateur

L'administrateur peut :

- consulter les statistiques globales
- gerer les utilisateurs
- moderer les livres
- annuler des demandes
- envoyer des notifications

### 1.3 Besoins Fonctionnels

Les besoins fonctionnels principaux sont :

- inscription et connexion
- gestion des sessions
- publication de livres
- recherche de livres avec filtres
- creation et traitement de demandes
- notifications
- administration de la plateforme

### 1.4 Besoins Non Fonctionnels

Les besoins non fonctionnels retenus sont :

- simplicite d'utilisation
- lisibilite du code
- separation MVC
- validation cote serveur
- compatibilite Oracle XE

### 1.5 User Stories

Exemples de user stories du projet :

- En tant que visiteur, je veux consulter le catalogue sans me connecter.
- En tant qu'utilisateur, je veux publier un livre scolaire.
- En tant qu'utilisateur, je veux envoyer une demande pour un livre.
- En tant que proprietaire, je veux accepter ou refuser une demande.
- En tant qu'administrateur, je veux surveiller l'activite globale.

### 1.6 Product Backlog Simplifie

| ID | User story | Priorite |
|---|---|---|
| PB1 | Consulter le catalogue | Haute |
| PB2 | Creer un compte | Haute |
| PB3 | Se connecter | Haute |
| PB4 | Ajouter un livre | Haute |
| PB5 | Envoyer une demande | Haute |
| PB6 | Accepter ou refuser une demande | Haute |
| PB7 | Voir ses notifications | Moyenne |
| PB8 | Consulter les statistiques admin | Haute |
| PB9 | Gerer les utilisateurs | Haute |
| PB10 | Moderer les livres | Haute |

### 1.7 Architecture Logique

Le projet s'appuie sur une architecture MVC :

- les **controleurs** recoivent les requetes
- les **modeles** manipulent les donnees
- les **vues** affichent les pages
- le **front controller** relie les URL aux actions

### 1.8 Definition Of Done

Une fonctionnalite est consideree comme terminee si :

- la logique metier est implemente
- la page ou l'action fonctionne
- l'interface est accessible
- les donnees sont validees
- la fonctionnalite a ete testee manuellement

---

## 2. Partie RPA

### 2.1 Vision Processus

Le projet peut etre vu comme un service numerique organise autour de plusieurs processus :

- publication d'un livre
- recherche et filtrage
- envoi d'une demande
- traitement d'une demande
- administration et suivi

### 2.2 Cartographie Des Processus

#### Processus coeur

- publier un livre
- rechercher un livre
- envoyer une demande
- traiter une demande
- finaliser un echange

#### Processus support

- gestion des comptes
- authentification
- notifications
- maintenance Oracle et application

#### Processus de management

- administration
- moderation
- suivi des statistiques

### 2.3 Processus Choisi Pour Le BPR

Le processus choisi pour l'etude RPA est :

- **le traitement d'une demande de livre**

Ce choix est pertinent car il influence directement :

- la satisfaction des utilisateurs
- le delai de reponse
- la qualite du suivi
- la charge du proprietaire

### 2.4 Etat As-Is

Dans l'etat actuel :

1. un demandeur envoie une demande
2. le proprietaire consulte son tableau de bord
3. il lit les demandes recues
4. il decide d'accepter ou de refuser
5. la plateforme met a jour le statut et notifie les acteurs

### 2.5 Limites Du Processus Actuel

Les limites principales sont :

- dependance a l'action manuelle du proprietaire
- delai de reponse variable
- risque d'oubli
- absence de priorisation automatique

### 2.6 Vision To-Be

Le processus cible ajoute une assistance automatisee :

- relance automatique des demandes en attente
- mise en avant des demandes prioritaires
- notifications automatiques apres changement d'etat
- indicateurs de pilotage pour l'administration

### 2.7 Scenarios D'Automatisation

#### Scenario 1 : relance automatique

- detecter les demandes `pending` anciennes
- notifier le proprietaire
- remonter ces demandes dans le dashboard

#### Scenario 2 : cloture automatique

- lorsqu'une demande est acceptee, rejeter les autres demandes du meme livre
- notifier les demandeurs concernes

#### Scenario 3 : reporting automatique

- calculer des KPI utiles
- suivre les livres peu demandes
- suivre les delais de traitement

### 2.8 KPI Utiles

Les indicateurs les plus interessants sont :

- nombre total de livres actifs
- nombre total de demandes
- taux de demandes acceptees
- delai moyen de traitement
- nombre total d'echanges
- economie estimee

---

## 3. Conclusion

La partie AGL montre que le projet repose sur des besoins clairement identifies, une architecture adaptee et une organisation de travail coherent.  
La partie RPA montre qu'il existe une vraie opportunite d'ameliorer l'efficacite du traitement des demandes sans changer la finalite de la plateforme.

L'ensemble confirme que **BookCycle Tunisia** est a la fois un projet applicatif fonctionnel et un bon support d'analyse methodologique.
