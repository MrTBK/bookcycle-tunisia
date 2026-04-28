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

**Rapport Intégré — AGL · SGBD · Programmation Web 2 · RPA**

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
     1)   Présentation du projet BookCycle Tunisia                            5
     2)   Problématique                                                       6
     3)   Objectifs                                                           6
     4)   Public cible                                                        6
     5)   Valeur ajoutée                                                      7

II   Partie WEB                                                               8
     Introduction                                                             8
     1)   Partie Visiteur                                                     8
          1.1  Page d'accueil                                                 8
          1.2  Catalogue des livres                                           9
          1.3  Inscription et Connexion                                       9
     2)   Partie Utilisateur                                                 10
          2.1  Tableau de bord                                               10
          2.2  Ajouter un livre                                              11
          2.3  Modifier un livre                                             11
          2.4  Envoyer une demande                                           12
          2.5  Traitement des demandes reçues                                12
          2.6  Notifications                                                 13
     3)   Partie Administrateur                                              13
          3.1  Dashboard Administrateur                                      13
          3.2  Gestion des utilisateurs                                      14
          3.3  Modération des livres                                         14
          3.4  Gestion des demandes                                          14
          3.5  Envoi de notifications                                        15

III  Analyse et Génie Logiciel (AGL)                                         16
     Introduction                                                            16
     1)   Acteurs de BookCycle Tunisia                                       16
     2)   Besoins Fonctionnels                                               17
     3)   User Stories                                                       18
     4)   Product Backlog                                                    19
     5)   Architecture Logique                                               20

IV   Base de Données Oracle (SGBD)                                           21
     Introduction                                                            21
     1)   Schéma relationnel                                                 21
     2)   Contraintes d'intégrité                                            23
     3)   Séquences et Triggers d'auto-incrément                             23
     4)   Vue de reporting                                                   24
     5)   Objets PL/SQL                                                      24

V    Réingénierie des Processus d'Affaires (RPA)                             27
     Introduction                                                            27
     1)   Cartographie des processus                                         27
     2)   Processus sélectionné — État As-Is                                 28
     3)   Solution cible — État To-Be                                        29
     4)   KPI de pilotage                                                    30

VI   Conclusion Générale                                                     31
```

---

&nbsp;

<div align="center">

## Chapitre I
### Présentation du cadre du projet

</div>

---

### Introduction

Au niveau de ce premier chapitre, nous tenons à décrire le cadre général dans lequel s'est déroulé notre projet de fin d'année. Nous entamons ce chapitre par une description du contexte de notre projet, puis nous exposons la problématique, les objectifs assignés, le public cible et la valeur ajoutée de la plateforme BookCycle Tunisia.

### 1. Présentation du projet

Notre projet consiste à mettre en place une plateforme web de don, d'échange et de réutilisation des livres scolaires en Tunisie.

Chaque année, des familles tunisiennes dépensent des sommes importantes pour acquérir des manuels scolaires, alors que d'anciens exemplaires en bon état restent inutilisés. **BookCycle Tunisia** répond à ce constat en proposant une solution numérique simple : une plateforme web qui met en relation les propriétaires de livres scolaires et les demandeurs, avec un espace d'administration complet pour superviser la plateforme.

Le projet repose sur une architecture **MVC en PHP 7.4**, une base de données **Oracle XE** enrichie d'objets **PL/SQL**, et une interface utilisateur construite avec HTML, CSS et JavaScript natif. Il constitue un projet intégré couvrant quatre modules : AGL, SGBD, Programmation Web 2 et RPA.

| Module | Apport dans BookCycle Tunisia |
|---|---|
| **AGL** | Analyse des besoins, acteurs, user stories, backlog produit, architecture |
| **SGBD** | Schéma Oracle, contraintes, séquences, PL/SQL, index, vues |
| **Programmation Web 2** | Application MVC PHP, sessions, formulaires, sécurité, PDO_OCI |
| **RPA** | Cartographie des processus, analyse As-Is / To-Be, scénarios d'automatisation |

### 2. Problématique

> *Comment concevoir et développer une plateforme web fiable permettant la mise en relation de propriétaires de livres scolaires et de demandeurs en Tunisie, avec un système de gestion des demandes, des notifications automatiques et une interface d'administration, en s'appuyant sur une architecture MVC PHP et une base de données Oracle ?*

### 3. Objectifs

- Permettre la **publication** de livres scolaires avec informations complètes (niveau, classe, matière, état, prix)
- Offrir un **catalogue filtrable** dynamiquement par niveau, classe et matière depuis Oracle
- Gérer l'**envoi, l'acceptation et le refus** de demandes d'échange entre utilisateurs
- **Notifier automatiquement** les utilisateurs lors des événements importants
- Fournir un espace d'**administration** avec statistiques, modération et gestion

### 4. Public cible

- Élèves et lycéens tunisiens en recherche de manuels scolaires
- Parents souhaitant réduire leurs dépenses scolaires
- Familles détentrices de livres en bon état souhaitant les céder
- Administrateurs responsables de la plateforme

### 5. Valeur ajoutée

| Dimension | Bénéfice pour BookCycle Tunisia |
|---|---|
| **Économique** | Réduction des dépenses scolaires des familles tunisiennes |
| **Environnemental** | Réutilisation des manuels, moins de gaspillage |
| **Social** | Accès aux livres pour les familles à revenus limités |
| **Académique** | Projet intégré cohérent couvrant 4 modules en L2 BDIA |

---

&nbsp;

<div align="center">

## Chapitre II
### Partie WEB

</div>

---

### Introduction

Au niveau de ce chapitre, nous présentons les interfaces et fonctionnalités de la plateforme **BookCycle Tunisia**, organisées par type d'utilisateur : le visiteur non connecté, l'utilisateur connecté et l'administrateur.

### 1. Partie Visiteur

#### 1.1 Page d'accueil

La page d'accueil (`/`) est la vitrine publique de BookCycle Tunisia. Elle présente la plateforme avec une section héro et deux boutons d'action : **Parcourir les livres** et **Ajouter un livre**. Trois cartes statistiques affichent en temps réel le nombre de livres actifs, le nombre d'échanges validés et l'économie totale estimée (en DT).

La section **Derniers livres** affiche les 4 livres les plus récemment publiés, avec le niveau scolaire, la matière, la classe, l'état et le prix de chaque livre. Un bouton **Détails** renvoie vers la fiche du livre dans le catalogue.

#### 1.2 Catalogue des livres

Le catalogue (`/catalog`) est accessible sans connexion. Un panneau latéral propose trois filtres dynamiques :
- **Niveau** : Primaire, Collège, Lycée — chargé depuis Oracle
- **Classe** : mise à jour automatique selon le niveau
- **Matière** : mise à jour automatique selon la classe

La grille de résultats affiche les livres correspondants. La fiche d'un livre (accessible via **Détails**) montre toutes ses informations et un bouton **Envoyer une demande** pour les utilisateurs connectés.

#### 1.3 Inscription et Connexion

**Inscription (`/register`) :** formulaire avec nom complet, email (unique), téléphone (8 chiffres obligatoires, validé côté serveur) et mot de passe. Après inscription, redirection vers la connexion.

**Connexion (`/login`) :** formulaire email + mot de passe. Un compte désactivé par l'admin ne peut pas se connecter. Après connexion réussie, redirection vers le tableau de bord.

---

### 2. Partie Utilisateur

#### 2.1 Tableau de bord

Le tableau de bord (`/dashboard`) est l'espace personnel de chaque utilisateur connecté, organisé en quatre sections :

**Statistiques personnelles :** livres reçus, livres donnés, argent économisé, argent fait économiser (en DT).

**Notifications :** historique complet des messages reçus (nouvelles demandes, acceptations, refus, messages admin) avec statut lu / non lu.

**Mes livres :** liste des livres publiés avec matière, classe, niveau, état, prix, description, statut (disponible / réservé / échangé) et bouton **Modifier**.

**Demandes reçues / envoyées :** traitement des demandes sur ses propres livres et suivi des demandes envoyées à d'autres propriétaires.

#### 2.2 Ajouter un livre

Le formulaire `/add-book` permet de publier un livre avec : niveau scolaire, classe (dynamique), matière (dynamique), état (Neuf / Bon / Usagé), prix estimé et description optionnelle. Le titre est généré automatiquement (`Matière - Classe - Niveau`). La validation serveur vérifie la cohérence niveau/classe/matière via Oracle.

#### 2.3 Modifier un livre

Le formulaire `/edit-book` permet de modifier l'état, le prix et la description d'un livre. Le niveau, la classe et la matière sont affichés en lecture seule (non modifiables) pour préserver la cohérence avec les demandes existantes. L'accès est refusé si le livre n'appartient pas à l'utilisateur connecté.

#### 2.4 Envoyer une demande

Depuis la fiche d'un livre dans le catalogue, l'utilisateur connecté peut envoyer une demande. Le système vérifie qu'il ne demande pas son propre livre et qu'il n'a pas déjà une demande en attente pour ce livre. Une notification est envoyée automatiquement au propriétaire.

#### 2.5 Traitement des demandes reçues

Dans la section **Demandes reçues** du tableau de bord, le propriétaire voit les demandes en attente avec les coordonnées du demandeur. Il peut :
- **Accepter** : la demande passe à `accepted`, toutes les autres demandes pour ce livre sont automatiquement rejetées, le livre passe à `reserved`, et les deux parties reçoivent une notification avec leurs coordonnées respectives.
- **Refuser** : la demande passe à `rejected` et le demandeur reçoit une notification.

#### 2.6 Notifications

Les notifications s'affichent dans le menu déroulant de la navbar (5 dernières non lues) et dans la section **Notifications** du tableau de bord (historique complet). Chaque notification peut être marquée comme lue.

---

### 3. Partie Administrateur

#### 3.1 Dashboard Administrateur

Le tableau de bord admin (`/admin`) affiche 4 cartes de statistiques globales (utilisateurs, livres, échanges, livres inactifs), la répartition des livres par niveau, les matières les plus demandées (top 5) et l'économie totale estimée.

#### 3.2 Gestion des utilisateurs

Liste complète des utilisateurs avec recherche par nom/email. Pour chaque compte :
- **Désactiver / Réactiver** : suppression logique — le compte est bloqué sans supprimer les données
- **Supprimer** : suppression physique définitive — bloquée si l'utilisateur a des livres actifs

#### 3.3 Modération des livres

Liste de tous les livres (actifs et masqués) avec actions :
- **Masquer** : le livre disparaît du catalogue (`is_active = 0`)
- **Restaurer** : le livre redevient visible (`is_active = 1`)

#### 3.4 Gestion des demandes

Liste filtrée des demandes (par statut) avec bouton **Annuler** pour forcer le statut `rejected`. Si la demande annulée était `accepted`, le livre repasse automatiquement en `available`.

#### 3.5 Envoi de notifications

Formulaire permettant d'envoyer un message à un utilisateur ciblé ou à tous les utilisateurs actifs en une seule opération.

---

&nbsp;

<div align="center">

## Chapitre III
### Analyse et Génie Logiciel (AGL)

</div>

---

### Introduction

Au niveau de ce chapitre, nous appliquons la démarche Scrum au développement de BookCycle Tunisia et décrivons l'architecture logique de l'application.

### 1. Acteurs de BookCycle Tunisia

| Acteur | Droits |
|---|---|
| **Visiteur** | Accueil, catalogue, filtres, fiche livre, inscription, connexion |
| **Utilisateur** | + Dashboard, ajouter/modifier livre, envoyer/traiter demandes, notifications |
| **Administrateur** | + Statistiques globales, gestion comptes, modération livres, gestion demandes, envoi notifications |

### 2. Besoins Fonctionnels

| ID | Besoin | Priorité |
|---|---|---|
| BF-01 | Inscription avec email unique et téléphone 8 chiffres | Haute |
| BF-02 | Connexion / déconnexion sécurisée | Haute |
| BF-03 | Catalogue filtrable dynamiquement par niveau/classe/matière | Haute |
| BF-04 | Publication d'un livre avec validation Oracle | Haute |
| BF-05 | Modification d'un livre (état, prix, description) | Haute |
| BF-06 | Envoi d'une demande avec contrôles métier | Haute |
| BF-07 | Acceptation / refus d'une demande | Haute |
| BF-08 | Tableau de bord utilisateur | Haute |
| BF-09 | Notifications automatiques | Moyenne |
| BF-10 | Tableau de bord admin + statistiques | Haute |
| BF-11 | Gestion des comptes et modération | Haute |

### 3. User Stories

```
US-01 : En tant que visiteur, je veux consulter le catalogue sans me connecter,
        afin de voir les livres disponibles avant de créer un compte.

US-02 : En tant qu'utilisateur, je veux publier un livre avec niveau, classe,
        matière et état, afin de le mettre à disposition d'autres familles.

US-03 : En tant qu'utilisateur, je veux envoyer une demande pour un livre,
        afin de prendre contact avec son propriétaire.

US-04 : En tant que propriétaire, je veux accepter ou refuser une demande
        avec une note de rendez-vous, afin de finaliser l'échange.

US-05 : En tant qu'administrateur, je veux voir les statistiques globales,
        afin de surveiller l'activité de BookCycle Tunisia.
```

### 4. Product Backlog

| ID | Fonctionnalité | Priorité | Effort (JD) | Statut |
|---|---|---|---|---|
| PB-01 | Catalogue public consultable | Haute | 1 | Done |
| PB-02 | Inscription / Connexion | Haute | 1.5 | Done |
| PB-03 | Ajout d'un livre avec validation Oracle | Haute | 2 | Done |
| PB-04 | Envoi et traitement d'une demande | Haute | 3 | Done |
| PB-05 | Tableau de bord utilisateur | Haute | 1.5 | Done |
| PB-06 | Tableau de bord administrateur | Haute | 2 | Done |
| PB-07 | Gestion des comptes (admin) | Haute | 1.5 | Done |
| PB-08 | Système de notifications | Moyenne | 1 | Done |
| PB-09 | Filtres dynamiques classe → matières | Haute | 1.5 | Done |
| PB-10 | Modification d'un livre (/edit-book) | Haute | 1 | Done |
| PB-11 | Suppression permanente utilisateur | Haute | 0.5 | Done |
| PB-12 | Upload d'image de couverture | Basse | 2 | To Do |
| PB-13 | Token CSRF | Moyenne | 0.5 | To Do |

### 5. Architecture Logique

```
router.php (Front Controller)
    └── public/index.php
        ├── Controllers/
        │   ├── AuthController    → register, login, logout
        │   ├── BookController    → store, update, stats
        │   ├── RequestController → store, accept, reject
        │   ├── AdminController   → toggleUser, deleteBook, notify, cancelRequest
        │   ├── PageController    → home, catalog, dashboard, admin, addBook, editBook
        │   └── NotificationController → read
        ├── Models/
        │   ├── User, Book, BookRequest, Notification, AcademicOption
        └── Views/
            ├── layouts/ (header.php, footer.php)
            └── pages/   (home, catalog, dashboard, admin, add-book, edit-book, login, register…)
```

---

&nbsp;

<div align="center">

## Chapitre IV
### Base de Données Oracle (SGBD)

</div>

---

### Introduction

Au niveau de ce chapitre, nous présentons le schéma Oracle de BookCycle Tunisia, ses contraintes d'intégrité, ses objets PL/SQL et les éléments Oracle spécifiques exploités.

### 1. Schéma relationnel

Le schéma comprend **8 tables** : 3 tables de référence académique et 5 tables métier.

#### Tables de référence académique

| Table | Contenu |
|---|---|
| `subjects` | 19 matières (Arabe, Maths, Français, Physique-Chimie, SVT, Informatique, etc.) |
| `school_classes` | 25 classes sur 3 niveaux (6 Primaire, 3 Collège, 16 Lycée) |
| `class_subjects` | Correspondances classe ↔ matières autorisées (ex. : `bac info` → 8 matières) |

#### Tables métier

| Table | Description |
|---|---|
| `users` | Comptes BookCycle Tunisia avec rôle (`user` / `admin`) et statut |
| `books` | Livres publiés avec niveau, classe, matière, état, prix, propriétaire |
| `requests` | Demandes d'échange avec statut (`pending` → `accepted` / `rejected`) |
| `exchanges` | Échanges finalisés (enregistrés automatiquement par trigger) |
| `notifications` | Notifications système et admin pour chaque utilisateur |

### 2. Contraintes d'intégrité

| Type | Exemples BookCycle Tunisia |
|---|---|
| **Clé primaire** | Toutes les tables |
| **Clé étrangère** | `books.owner_id → users.id`, `requests.book_id → books.id` |
| **UNIQUE** | `users.email`, `(school_classes.school_level, class_name)` |
| **CHECK** | `users.role IN ('admin','user')`, `books.status IN ('available','reserved','exchanged')` |

### 3. Séquences et Triggers d'auto-incrément

8 paires séquence + trigger BEFORE INSERT assurent la génération automatique des IDs sur toutes les tables (Oracle XE sans IDENTITY natif).

```sql
CREATE SEQUENCE seq_books START WITH 1 INCREMENT BY 1 NOCACHE;
CREATE OR REPLACE TRIGGER trg_books_pk
BEFORE INSERT ON books FOR EACH ROW
BEGIN
    IF :NEW.id IS NULL THEN
        SELECT seq_books.NEXTVAL INTO :NEW.id FROM dual;
    END IF;
END;
```

### 4. Vue de reporting

```sql
CREATE OR REPLACE VIEW v_book_overview AS
SELECT b.id, b.title, b.subject, b.class_name, b.school_level,
       b.condition_label, b.estimated_price, b.status,
       u.name AS owner_name, u.email AS owner_email, b.created_at
FROM books b JOIN users u ON u.id = b.owner_id;
```

### 5. Objets PL/SQL

#### Procédure `add_notification`
Insère une notification pour un utilisateur (appelée à chaque événement : nouvelle demande, acceptation, refus, message admin).

#### Procédure `accept_request`
Accepte atomiquement une demande : met à jour la demande, rejette les autres pour le même livre, passe le livre en `reserved`, notifie le demandeur.

#### Fonction `count_books_by_user`
Retourne le nombre de livres actifs publiés par un utilisateur.

#### Fonction `calculate_money_saved`
Calcule l'économie totale estimée générée par les échanges finalisés sur BookCycle Tunisia.

#### Trigger `trg_books_updated_at`
Met à jour automatiquement `updated_at` à chaque modification d'un livre.

#### Trigger `trg_book_exchange_log`
Enregistre automatiquement un échange dans `exchanges` quand un livre passe au statut `exchanged`.

#### Trigger `trg_notify_owner_on_request`
Notifie automatiquement le propriétaire d'un livre dès qu'une nouvelle demande est insérée.

#### Trigger `trg_validate_user_email`
Bloque l'insertion si l'email ne contient pas `@` (`RAISE_APPLICATION_ERROR`).

---

&nbsp;

<div align="center">

## Chapitre V
### Réingénierie des Processus d'Affaires (RPA)

</div>

---

### Introduction

Au niveau de ce chapitre, nous analysons les processus métier de BookCycle Tunisia et proposons une solution d'automatisation pour améliorer le traitement des demandes.

### 1. Cartographie des processus

| Processus | Acteurs | SLA actuel |
|---|---|---|
| Publication d'un livre | Utilisateur | 3-5 min |
| Recherche et filtrage | Tous | < 1 sec |
| Envoi d'une demande | Utilisateur | Immédiat |
| **Traitement d'une demande** | **Utilisateur** | **24h à 7 jours** |
| Finalisation d'un échange | Système | Manuel |

### 2. Processus sélectionné — État As-Is

**Processus : traitement d'une demande de livre sur BookCycle Tunisia**

Ce processus est sélectionné car son SLA est inacceptable (24h à 7 jours) et il constitue le cœur de valeur de la plateforme.

```
 Demandeur          Propriétaire        Système
     │                    │                │
     ├── Envoie demande ──►│                │
     │         [Le propriétaire doit se connecter manuellement]
     │                    │◄─── Consulte dashboard ──┤
     │              [Décision manuelle, sans délai]
     │                    ├── Accepte / refuse ──────►│
     │◄── Notification ───┼──────────────────────────┤
```

**Limites :** dépendance 100% manuelle, pas de rappel automatique, pas de priorisation.

### 3. Solution cible — État To-Be

```
 Demandeur    Propriétaire    Système     Moteur IA    Moteur RPA
     ├── Envoie ────►├──────────►│             │              │
     │               │◄─ Notif. immédiate ─────┤              │
     │               │           │── Score ───►│              │
     │               │     [Si pending > 48h] ◄──── Relance ──┤
     │               ├── Répond ─►│                           │
     │◄── Notif. ────┤           │──── Clôture auto autres ───►
```

**Gain estimé :** > 75% de réduction du délai (objectif < 12 heures).

### 4. KPI de pilotage

| KPI | Cible |
|---|---|
| Délai moyen de traitement | < 12 heures |
| Taux de réponse des propriétaires | > 90% |
| Taux d'abandon des demandes | < 10% |
| Taux d'acceptation | > 60% |
| Économie totale estimée | Croissance continue (en DT) |

---

&nbsp;

<div align="center">

## Conclusion Générale

</div>

---

**BookCycle Tunisia** est un projet intégré abouti qui démontre la capacité à concevoir, modéliser et développer une application web complète répondant à un besoin réel : la réutilisation des livres scolaires en Tunisie.

La plateforme couvre l'ensemble du parcours utilisateur — de la consultation anonyme du catalogue jusqu'à la finalisation d'un échange — avec une interface claire organisée autour de trois espaces : le visiteur, l'utilisateur connecté et l'administrateur.

Sur le plan technique, le projet livre une architecture MVC PHP 7.4 connectée à Oracle XE, avec une validation croisée niveau/classe/matière directement depuis les tables de référence Oracle, des objets PL/SQL encapsulant la logique métier critique, et un espace d'administration complet. Sur le plan méthodologique, il démontre comment AGL (Scrum), SGBD (Oracle), Web 2 (PHP MVC) et RPA (analyse processus) se complètent naturellement autour d'un cas d'usage concret.

---

*Rapport réalisé dans le cadre du Projet Intégré — Licence 2 Big Data et Intelligence Artificielle — ESEN — Université de la Manouba — 2025/2026*
