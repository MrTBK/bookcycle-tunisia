<div align="center">

**Ecole Supérieure d'Economie Numérique**
**UNIVERSITÉ DE LA MANOUBA**

&nbsp;

&nbsp;

**Projet de Fin d'Année**

*Filière : Licence 2 — Big Data et Intelligence Artificielle*

&nbsp;

&nbsp;

Application web de don, d'échange et de réutilisation des livres scolaires
**\<\<BookCycle Tunisia\>\>**

&nbsp;

**Module : Programmation Web 2**

&nbsp;

&nbsp;

**Réalisé par :**

&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Mortadha Yakoubi

&nbsp;

**Présenté le :** 28/04/2026

&nbsp;

**Année Universitaire: 2025/2026**

</div>

---

&nbsp;

## Sommaire

```
I    Présentation du cadre du projet                                          5
     Introduction                                                             5
     1)   Présentation du projet                                              5
     2)   Objectifs                                                           6

II   Partie WEB                                                               7
     1)   Partie Visiteur                                                     8
          1.1  Conception UX/UI                                               8
          1.2  Page d'accueil                                                 8
          1.3  Catalogue des livres                                           9
          1.4  Inscription et Connexion                                       9
     2)   Partie Utilisateur                                                 10
          2.1  Tableau de bord                                               10
          2.2  Ajouter un livre                                              11
          2.3  Modifier un livre                                             12
          2.4  Envoyer une demande                                           12
          2.5  Historique des demandes envoyées                              13
          2.6  Demandes reçues et traitement                                 13
          2.7  Notifications                                                 14
     3)   Partie Administrateur                                              15
          3.1  Dashboard Administrateur                                      15
          3.2  Gestion des utilisateurs                                      16
          3.3  Modération des livres                                         16
          3.4  Gestion des demandes                                          17
          3.5  Envoi de notifications                                        17

III  Conclusion Générale                                                     18
```

---

&nbsp;

<div align="center">

## Chapitre I
### Présentation du cadre du projet

</div>

---

### Introduction

Au niveau de ce premier chapitre, nous tenons à décrire le cadre général dans lequel s'est déroulé notre projet de fin d'année. Nous allons présenter le projet BookCycle Tunisia, son contexte, et les objectifs qui lui ont été assignés.

### 1. Présentation du projet

Notre projet consiste à mettre en place une plateforme web de don, d'échange et de réutilisation des livres scolaires en Tunisie.

Chaque année, des familles tunisiennes dépensent des sommes importantes pour acquérir des manuels scolaires, alors que d'anciens exemplaires en bon état restent inutilisés chez d'autres familles. **BookCycle Tunisia** répond à ce constat en proposant une solution numérique simple : une plateforme qui permet aux propriétaires de livres de les mettre à disposition, et aux demandeurs de les solliciter directement.

Les utilisateurs pourront consulter le catalogue des livres disponibles, effectuer des demandes, et recevoir des notifications en temps réel. Des espaces de gestion sont proposés pour les utilisateurs et les administrateurs de la plateforme.

Ainsi, les principaux objectifs assignés au projet sont :

- Permettre la publication de livres scolaires en ligne avec leurs informations complètes
- Offrir un catalogue filtrable par niveau scolaire, classe et matière
- Gérer l'envoi, l'acceptation et le refus de demandes entre utilisateurs
- Notifier automatiquement les utilisateurs lors des événements importants
- Fournir un espace d'administration pour la modération de la plateforme

### 2. Objectifs

| Objectif | Détail |
|---|---|
| **Catalogue public** | Tout visiteur peut consulter les livres disponibles sans se connecter |
| **Gestion des livres** | Les utilisateurs connectés publient et modifient leurs livres |
| **Gestion des demandes** | Les utilisateurs envoient, acceptent ou refusent des demandes d'échange |
| **Notifications** | Les utilisateurs reçoivent des notifications lors des événements clés |
| **Administration** | L'administrateur supervise et modère l'ensemble de la plateforme |

---

&nbsp;

<div align="center">

## Chapitre II
### Partie WEB

</div>

---

### 1. Partie Visiteur

#### 1.1 Conception UX/UI

La plateforme BookCycle Tunisia adopte un design épuré inspiré de l'univers des livres. La palette de couleurs utilise des tons chauds (crème, vert forêt `#1f7a5b`) pour évoquer le papier et la nature. La typographie choisie est Georgia (serif) pour renforcer l'identité liée aux livres scolaires.

L'interface est accessible depuis n'importe quel navigateur moderne. La navigation est visible dans un en-tête fixe en haut de chaque page, avec les liens vers l'Accueil, le Catalogue et le Contact. Les visiteurs non connectés voient les boutons **Connexion** et **Inscription**.

#### 1.2 Page d'accueil

La page d'accueil (`/`) est la vitrine publique de la plateforme. Elle se compose de deux sections principales :

**Section Héro :**
La section principale présente BookCycle Tunisia avec un titre accrocheur et deux boutons d'action : **Parcourir les livres** (vers le catalogue) et **Ajouter un livre** (vers le formulaire de publication). Trois cartes statistiques en temps réel affichent :

- le nombre de livres actifs sur la plateforme
- le nombre d'échanges validés
- l'économie estimée générée pour les familles (en DT)

**Section Derniers livres :**
Cette section affiche les 4 livres les plus récemment publiés sur la plateforme. Chaque carte présente le niveau scolaire, la matière, la classe, l'état du livre, le prix estimé et le nom du propriétaire. Un bouton **Détails** permet d'accéder à la fiche complète du livre dans le catalogue.

#### 1.3 Catalogue des livres

La page catalogue (`/catalog`) est accessible à tous les visiteurs sans connexion. Elle propose :

**Filtres de recherche (panneau latéral gauche) :**
- **Niveau scolaire** : Primaire, Collège, Lycée — chargé depuis la base Oracle
- **Classe** : mise à jour automatiquement selon le niveau sélectionné
- **Matière** : mise à jour automatiquement selon la classe sélectionnée

Les listes de classes et de matières sont entièrement dynamiques : changer le niveau met à jour les classes, changer la classe met à jour les matières disponibles.

**Grille de résultats (panneau droit) :**
Chaque livre est présenté en carte avec : le niveau scolaire (badge), la matière, la classe, le prix estimé, l'état du livre et le nom du propriétaire. Un bouton **Détails** ouvre la fiche complète du livre.

**Fiche détaillée d'un livre :**
En cliquant sur **Détails**, la fiche du livre s'affiche avec toutes ses informations et un bouton **Envoyer une demande** pour les utilisateurs connectés. Les visiteurs non connectés voient un lien vers la page de connexion.

#### 1.4 Inscription et Connexion

**Page d'inscription (`/register`) :**
Le formulaire de création de compte demande :
- Nom complet
- Adresse email (unique sur la plateforme)
- Numéro de téléphone (exactement 8 chiffres — validé côté serveur)
- Mot de passe

Après inscription réussie, un message de confirmation s'affiche et le visiteur est redirigé vers la page de connexion.

**Page de connexion (`/login`) :**
Le formulaire demande l'email et le mot de passe. En cas d'erreur (identifiants incorrects ou compte désactivé), un message explicite s'affiche. Après connexion réussie, l'utilisateur est redirigé vers son tableau de bord.

---

### 2. Partie Utilisateur

#### 2.1 Tableau de bord

Le tableau de bord (`/dashboard`) est l'espace personnel de chaque utilisateur connecté. Il est organisé en quatre sections :

**Statistiques personnelles (cartes en haut) :**
- **Livres reçus** : nombre de livres obtenus via des demandes acceptées
- **Livres donnés** : nombre de livres cédés à d'autres utilisateurs
- **Argent économisé** : valeur estimée des livres reçus
- **Argent fait économiser** : valeur estimée des livres donnés

**Section Notifications :**
Affiche tous les messages reçus par l'utilisateur (nouvelles demandes, demandes acceptées/refusées, messages de l'administrateur). Chaque notification indique l'expéditeur, le message, la date et son état (lue / non lue). Un bouton **Marquer comme lue** est disponible pour les notifications non lues.

**Section Mes livres :**
Liste de tous les livres publiés par l'utilisateur avec leurs informations complètes (matière, classe, niveau, état, prix, description, statut : disponible / réservé / échangé). Un bouton **Modifier** permet d'accéder au formulaire de modification de chaque livre.

**Section Demandes reçues :**
Affiche les demandes en attente sur les livres de l'utilisateur. Pour chaque demande, le nom du demandeur, son email et son téléphone sont visibles. Des boutons **Accepter** et **Refuser** permettent de traiter chaque demande.

**Section Demandes envoyées :**
Affiche le suivi de toutes les demandes envoyées par l'utilisateur. Pour chaque demande, le statut (en attente / acceptée / refusée) est indiqué. Si la demande est acceptée, les coordonnées du propriétaire (email et téléphone) s'affichent pour organiser l'échange.

#### 2.2 Ajouter un livre

La page d'ajout (`/add-book`) permet à tout utilisateur connecté de publier un livre scolaire. Le formulaire contient :

- **Niveau scolaire** : liste déroulante chargée depuis la base Oracle (Primaire, Collège, Lycée)
- **Classe** : mise à jour automatique selon le niveau (ex. : 7ème, 8ème, 9ème pour le Collège)
- **Matière** : mise à jour automatique selon la classe sélectionnée
- **État du livre** : Neuf / Bon / Usagé
- **Prix estimé** : valeur en dinars tunisiens (DT)
- **Description** : champ optionnel pour ajouter des précisions

Le titre du livre est généré automatiquement par le système : `Matière - Classe - Niveau` (exemple : `Mathématiques - 9ème annee - College`).

La validation serveur vérifie que la classe correspond bien au niveau sélectionné et que la matière est autorisée pour cette classe, en interrogeant directement la base Oracle.

#### 2.3 Modifier un livre

La page de modification (`/edit-book`) permet à un utilisateur de mettre à jour les informations d'un livre qu'il a publié. Les champs modifiables sont :

- **État** : Neuf / Bon / Usagé (liste déroulante pré-sélectionnée avec la valeur actuelle)
- **Prix estimé** : champ numérique pré-rempli
- **Description** : textarea pré-rempli

Les champs **Niveau**, **Classe** et **Matière** sont affichés en lecture seule (non modifiables) pour préserver la cohérence avec les demandes déjà envoyées sur ce livre.

L'accès est refusé si le livre n'appartient pas à l'utilisateur connecté — il est alors redirigé vers son tableau de bord.

#### 2.4 Envoyer une demande

Depuis la fiche d'un livre dans le catalogue, un utilisateur connecté peut envoyer une demande en cliquant sur le bouton **Envoyer une demande**. Le système vérifie automatiquement :

- que l'utilisateur ne demande pas son propre livre
- qu'il n'a pas déjà une demande en attente pour ce même livre

Si la demande est envoyée avec succès, le propriétaire du livre reçoit une notification automatique indiquant qu'une nouvelle demande a été effectuée sur son livre.

#### 2.5 Historique des demandes envoyées

Dans la section **Demandes envoyées** du tableau de bord, l'utilisateur voit toutes les demandes qu'il a soumises avec :

- La matière, la classe et le niveau du livre concerné
- Le nom du propriétaire
- Le statut de la demande : **en attente**, **acceptée** ou **refusée**
- Si acceptée : l'email et le téléphone du propriétaire pour organiser l'échange
- La note de rendez-vous laissée par le propriétaire

#### 2.6 Demandes reçues et traitement

Dans la section **Demandes reçues** du tableau de bord, le propriétaire voit les demandes en attente sur ses livres. Pour chaque demande :

- Le nom, l'email et le téléphone du demandeur sont affichés
- Un champ **Note de rendez-vous** (obligatoire) permet d'ajouter les détails de l'échange
- Le bouton **Accepter** accepte la demande, rejette automatiquement toutes les autres demandes pour le même livre, et notifie les deux parties
- Le bouton **Refuser** rejette uniquement cette demande et notifie le demandeur

#### 2.7 Notifications

Les notifications s'affichent dans deux endroits :

**Navbar (en-tête) :**
Un badge rouge indique le nombre de notifications non lues. En cliquant sur **Notifications**, un menu déroulant affiche les 5 dernières notifications non lues avec l'expéditeur, le message et la date.

**Section Notifications du tableau de bord :**
Affiche l'historique complet des notifications avec leur statut (lue / non lue). Cliquer sur **Marquer comme lue** marque la notification et redirige vers le tableau de bord.

---

### 3. Partie Administrateur

#### 3.1 Dashboard Administrateur

Le tableau de bord administrateur (`/admin`) est accessible uniquement aux comptes avec le rôle `admin`. Il s'ouvre sur quatre cartes de statistiques globales :

- **Total utilisateurs** : nombre total de comptes inscrits sur la plateforme
- **Total livres** : nombre de livres actifs et visibles
- **Total échanges** : nombre d'échanges validés (demandes acceptées)
- **Livres inactifs** : nombre de livres masqués par modération

Trois cartes complémentaires affichent :
- La répartition des livres par niveau scolaire (Primaire / Collège / Lycée)
- Les matières les plus demandées (top 5 par nombre de demandes reçues)
- L'état des comptes (actifs / inactifs) et l'économie totale estimée générée

#### 3.2 Gestion des utilisateurs

Le panneau **Utilisateurs** affiche la liste complète des comptes avec : nom, email, téléphone, rôle, statut (Actif / Inactif). Un champ de recherche permet de filtrer par nom ou email.

Pour chaque utilisateur, deux actions sont disponibles :

- **Désactiver / Réactiver** : suppression logique — le compte est bloqué mais les données sont conservées. L'utilisateur ne peut plus se connecter.
- **Supprimer** : suppression physique définitive (DELETE en base). Bloquée si l'utilisateur possède encore des livres actifs. Un message de confirmation apparaît avant l'action.

Un administrateur ne peut pas désactiver ni supprimer son propre compte.

#### 3.3 Modération des livres

Le panneau **Livres** affiche tous les livres publiés sur la plateforme (actifs et masqués) avec : matière, propriétaire, niveau/classe, état, statut de disponibilité, visibilité.

Pour chaque livre :
- **Masquer** : passe le livre en `is_active = 0` — il disparaît du catalogue public mais reste en base.
- **Restaurer** : remet le livre en `is_active = 1` — il redevient visible dans le catalogue.

#### 3.4 Gestion des demandes

Le panneau **Demandes** affiche toutes les demandes de la plateforme avec un filtre par statut : toutes / en attente / acceptées / refusées. Pour chaque demande, le livre, le propriétaire, le demandeur, le statut et la date sont affichés.

L'administrateur peut **Annuler** n'importe quelle demande non encore refusée. Si la demande était acceptée, le livre est automatiquement remis en statut **disponible**.

#### 3.5 Envoi de notifications

Le panneau **Notifications** permet à l'administrateur d'envoyer un message directement depuis l'interface :

- **Tous les utilisateurs actifs** : en sélectionnant « Tous les utilisateurs actifs » dans la liste déroulante, la notification est envoyée à l'ensemble des comptes actifs en une seule opération.
- **Un utilisateur ciblé** : en choisissant un utilisateur spécifique dans la liste, la notification lui est envoyée uniquement.

---

&nbsp;

<div align="center">

## Conclusion Générale

</div>

---

**BookCycle Tunisia** est une application web complète qui répond à un besoin réel : faciliter la réutilisation des livres scolaires en Tunisie. La plateforme couvre l'ensemble du parcours utilisateur — de la consultation anonyme du catalogue jusqu'à la finalisation d'un échange — avec une interface claire organisée autour de trois espaces distincts : le visiteur, l'utilisateur connecté, et l'administrateur.

Sur le plan technique, l'application repose sur une architecture MVC PHP 7.4 connectée à Oracle XE via PDO_OCI, avec une validation croisée niveau/classe/matière directement depuis les tables de référence Oracle. Les filtres dynamiques, la gestion des sessions, les notifications automatiques et l'espace d'administration complet en font un projet intégré abouti.

---

*Rapport réalisé dans le cadre du Projet Intégré — Licence 2 Big Data et Intelligence Artificielle — ESEN — Université de la Manouba — 2025/2026*
