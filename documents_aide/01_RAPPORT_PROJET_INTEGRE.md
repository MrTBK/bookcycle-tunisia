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
     2)   Problématique                                                       6
     3)   Objectifs du projet                                                 6
     4)   Public cible                                                        6
     5)   Valeur ajoutée                                                      7

II   Analyse et Génie Logiciel (AGL)                                          8
     Introduction                                                             8
     1)   Identification des Acteurs                                          8
     2)   Besoins Fonctionnels et Non Fonctionnels                            9
     3)   User Stories Principales                                           10
     4)   Product Backlog                                                    11
     5)   Définition of Done                                                 12
     6)   Architecture Logique                                               12

III  Base de Données Oracle (SGBD)                                           14
     Introduction                                                            14
     1)   Choix du SGBD                                                      14
     2)   Schéma Relationnel                                                 15
     3)   Contraintes d'Intégrité                                            16
     4)   Séquences et Triggers d'Auto-incrément                             16
     5)   Index de Performance                                               17
     6)   Vue de Reporting                                                   17
     7)   Objets PL/SQL                                                      18
     8)   Éléments Oracle Spécifiques                                        20

IV   Partie Programmation Web 2                                              21
     Introduction                                                            21
     1)   Technologies Utilisées                                             21
     2)   Architecture MVC Détaillée                                         22
     3)   Connexion à Oracle via PDO                                         23
     4)   Fonctionnement du Routeur                                          24
     5)   Gestion des Sessions et des Rôles                                  24
     6)   Validation des Données                                             24
     7)   Pages et Fonctionnalités                                           25
     8)   Sécurité Appliquée                                                 25

V    Réingénierie des Processus d'Affaires (RPA)                             27
     Introduction                                                            27
     1)   Vision Processus                                                   27
     2)   Processus Sélectionné pour le BPR                                  27
     3)   Scénarios d'Automatisation                                         29
     4)   KPI Définis                                                        30

VI   Tests et Validation                                                     31

VII  Difficultés, Limites et Améliorations                                   32

VIII Conclusion Générale                                                     33
```

---

&nbsp;

<div align="center">

## Chapitre I
### Présentation du cadre du projet

</div>

---

### Introduction

Au niveau de ce premier chapitre, nous tenons à décrire le cadre général dans lequel s'est déroulé notre projet de fin d'année.
Nous entamons ce chapitre par une description du contexte de notre projet. Puis, nous allons exposer la problématique, les objectifs assignés, le public cible et la valeur ajoutée de la plateforme.

### 1. Présentation du projet

Notre projet consiste à mettre en place une plateforme web de don, d'échange et de réutilisation des livres scolaires en Tunisie.

La plateforme **BookCycle Tunisia** est une application web académique conçue pour faciliter la réutilisation des livres scolaires en Tunisie. Elle met en relation des propriétaires de livres et des demandeurs, tout en offrant un espace d'administration complet.

Le projet repose sur une architecture **MVC en PHP 7.4**, une base de données **Oracle XE** enrichie d'objets **PL/SQL**, et une interface utilisateur construite avec HTML, CSS et JavaScript natif. Il constitue un projet intégré couvrant à la fois l'analyse fonctionnelle (AGL), la modélisation et l'administration des données (SGBD), le développement web côté serveur (Programmation Web 2), et une réflexion sur l'automatisation des processus métier (RPA).

**Mots-clés :** Oracle XE, PL/SQL, PHP 7.4, MVC, PDO_OCI, livres scolaires, catalogue, administration, Big Data, ESEN.

Ce projet intégré constitue l'aboutissement pratique de quatre modules enseignés en Licence 2 à l'ESEN :

| Module | Apport dans ce projet |
|---|---|
| **AGL** | Analyse des besoins, identification des acteurs, modélisation UML, backlog produit |
| **SGBD** | Conception du schéma Oracle, contraintes d'intégrité, requêtes SQL, objets PL/SQL |
| **Programmation Web 2** | Architecture MVC en PHP, formulaires, sessions, contrôle d'accès par rôle |
| **RPA** | Cartographie des processus, analyse As-Is / To-Be, scénarios d'automatisation |

### 2. Problématique

> *Comment concevoir et développer une plateforme web fiable permettant la mise en relation de propriétaires de livres scolaires et de demandeurs, avec un système de gestion des demandes, des notifications et une interface d'administration, en s'appuyant sur une architecture MVC PHP et une base de données Oracle ?*

### 3. Objectifs du Projet

Les objectifs principaux sont les suivants :

- **Publication** : permettre à tout utilisateur connecté de publier un livre scolaire disponible
- **Catalogue** : offrir un catalogue filtrable par niveau scolaire, classe et matière
- **Demandes** : gérer l'envoi, le suivi, l'acceptation et le rejet de demandes
- **Notifications** : informer les utilisateurs des événements importants sur la plateforme
- **Administration** : fournir un tableau de bord de modération et de statistiques

### 4. Public Cible

- Élèves et lycéens en recherche de manuels scolaires
- Parents souhaitant réduire les dépenses scolaires
- Étudiants et familles détenteurs de livres en bon état
- Administrateurs responsables de la plateforme

### 5. Valeur Ajoutée

| Dimension | Bénéfice |
|---|---|
| **Économique** | Réduction des dépenses scolaires par la réutilisation |
| **Environnemental** | Limitation du gaspillage de ressources imprimées |
| **Social** | Accessibilité des manuels pour les familles à revenus limités |
| **Académique** | Intégration cohérente de quatre modules en un projet concret |

---

&nbsp;

<div align="center">

## Chapitre II
### Analyse et Génie Logiciel (AGL)

</div>

---

### Introduction

Au niveau de ce chapitre, nous appliquons la démarche **Scrum** au projet BookCycle Tunisia. Nous identifions les acteurs, définissons les besoins fonctionnels et non fonctionnels, rédigeons les user stories, établissons le product backlog et présentons l'architecture logique retenue.

### 1. Identification des Acteurs

#### Visiteur (non authentifié)

Le visiteur accède librement à :

- La page d'accueil (`/`)
- Le catalogue public (`/catalog`) avec filtres par niveau, classe et matière
- Les pages institutionnelles : À propos (`/about`), Contact (`/contact`), Politique de confidentialité (`/privacy-policy`)
- Les formulaires d'inscription (`/register`) et de connexion (`/login`)

#### Utilisateur Connecté

En plus des droits du visiteur, l'utilisateur connecté peut :

- Accéder à son tableau de bord personnel (`/dashboard`)
- Publier un nouveau livre (`/add-book`)
- Modifier un livre publié (`/edit-book`) — état, prix estimé, description
- Consulter ses livres publiés
- Envoyer une demande pour un livre d'un autre utilisateur
- Consulter ses demandes envoyées et reçues
- Accepter ou refuser les demandes reçues
- Consulter ses notifications

#### Administrateur

En plus des droits de l'utilisateur, l'administrateur peut :

- Accéder au tableau de bord d'administration (`/admin`)
- Consulter les statistiques globales (utilisateurs, livres, échanges, économie estimée)
- Activer ou désactiver des comptes utilisateurs
- Supprimer définitivement un compte utilisateur (si sans livres actifs)
- Masquer ou restaurer des livres signalés
- Annuler des demandes en cours
- Envoyer des notifications ciblées ou globales

### 2. Besoins Fonctionnels et Non Fonctionnels

#### Besoins Fonctionnels

| Identifiant | Besoin | Priorité |
|---|---|---|
| BF-01 | Inscription et authentification | Haute |
| BF-02 | Gestion de session utilisateur | Haute |
| BF-03 | Publication d'un livre avec validation | Haute |
| BF-04 | Catalogue filtrable par niveau / classe / matière | Haute |
| BF-05 | Détail d'un livre | Haute |
| BF-06 | Envoi et traitement d'une demande | Haute |
| BF-07 | Système de notifications en temps différé | Moyenne |
| BF-08 | Tableau de bord utilisateur | Haute |
| BF-09 | Tableau de bord administrateur avec statistiques | Haute |
| BF-10 | Modération des utilisateurs et des livres | Haute |

#### Besoins Non Fonctionnels

| Catégorie | Exigence |
|---|---|
| **Architecture** | Séparation stricte MVC |
| **Sécurité** | Validation côté serveur, requêtes préparées PDO, contrôle des rôles |
| **Compatibilité** | Oracle XE avec PDO_OCI |
| **Maintenabilité** | Code modulaire, namespaces PHP |
| **Déployabilité** | Démarrage local en une commande |

### 3. User Stories Principales

```
US-01 : En tant que visiteur, je veux consulter le catalogue sans me connecter,
        afin de voir les livres disponibles avant de créer un compte.

US-02 : En tant qu'utilisateur, je veux publier un livre scolaire avec son niveau,
        sa classe, sa matière et son état, afin de le mettre à disposition.

US-03 : En tant qu'utilisateur, je veux envoyer une demande pour un livre,
        afin de prendre contact avec le propriétaire.

US-04 : En tant que propriétaire, je veux accepter ou refuser une demande reçue,
        afin de gérer les échanges à partir de mon tableau de bord.

US-05 : En tant qu'administrateur, je veux voir les statistiques globales,
        afin de surveiller l'activité de la plateforme.
```

### 4. Product Backlog

| ID | User Story | Priorité | Statut |
|---|---|---|---|
| PB-01 | Consulter le catalogue public | Haute | Réalisé |
| PB-02 | Créer un compte utilisateur | Haute | Réalisé |
| PB-03 | Se connecter / se déconnecter | Haute | Réalisé |
| PB-04 | Ajouter un livre avec validation | Haute | Réalisé |
| PB-05 | Envoyer une demande | Haute | Réalisé |
| PB-06 | Accepter ou refuser une demande | Haute | Réalisé |
| PB-07 | Consulter ses notifications | Moyenne | Réalisé |
| PB-08 | Tableau de bord utilisateur | Haute | Réalisé |
| PB-09 | Tableau de bord administrateur | Haute | Réalisé |
| PB-10 | Modération des livres et utilisateurs | Haute | Réalisé |

### 5. Définition of Done

Une fonctionnalité est considérée comme terminée si :

1. La logique métier est implémentée dans le contrôleur et le modèle correspondants
2. La validation des données est assurée côté serveur
3. La vue associée est opérationnelle et accessible
4. Les messages d'erreur et de succès sont correctement affichés
5. La fonctionnalité a été testée manuellement avec des données réelles

### 6. Architecture Logique

Le projet suit le patron **MVC (Modèle-Vue-Contrôleur)** :

```
bookcycle-tunisia/
│
├── router.php                  ← Point d'entrée et routage HTTP
│
├── public/
│   ├── index.php               ← Bootstrap de l'application
│   └── assets/
│       ├── css/                ← Feuilles de style
│       └── js/app.js           ← Interactions légères côté client
│
└── app/
    ├── Config/
    │   └── config.php          ← Paramètres de connexion Oracle
    ├── Core/
    │   ├── Auth.php            ← Gestion des sessions et des rôles
    │   ├── Controller.php      ← Classe de base des contrôleurs
    │   └── Database.php        ← Singleton de connexion PDO/Oracle
    ├── Models/                 ← Accès aux données (requêtes SQL)
    ├── Controllers/            ← Logique métier et dispatching
    └── Views/
        ├── layouts/            ← En-tête et pied de page partagés
        └── pages/              ← Une vue par page
```

---

&nbsp;

<div align="center">

## Chapitre III
### Base de Données Oracle (SGBD)

</div>

---

### Introduction

Au niveau de ce chapitre, nous présentons la conception et l'implémentation de la base de données **Oracle XE** utilisée par la plateforme BookCycle Tunisia. Nous détaillons le schéma relationnel, les contraintes d'intégrité, les objets PL/SQL ainsi que les éléments spécifiques à Oracle exploités dans ce projet.

### 1. Choix du SGBD

Le projet utilise **Oracle Database Express Edition (XE)**, conformément aux objectifs du module SGBD. Ce choix permet d'exploiter pleinement :

- Le langage **SQL** pour la définition et la manipulation des données
- Le langage **PL/SQL** pour les traitements métier avancés
- Les objets propriétaires Oracle : séquences, triggers, procédures, fonctions et vues

### 2. Schéma Relationnel

Le schéma comprend **8 tables** : quatre tables métier principales, trois tables de référence académique, et une table de traçabilité.

#### 2.1 Tables de Référence Académique

| Table | Rôle |
|---|---|
| `subjects` | Liste des matières scolaires disponibles |
| `school_classes` | Classes disponibles par niveau (Primaire / Collège / Lycée) |
| `class_subjects` | Association classes ↔ matières autorisées |

Ces tables sont lues par l'application PHP pour alimenter dynamiquement les formulaires et valider les données saisies. Elles remplacent les anciennes listes codées en dur dans le code.

#### 2.2 Tables Métier

| Table | Description |
|---|---|
| `users` | Comptes utilisateurs avec rôle (`admin` / `user`) et statut d'activation |
| `books` | Livres publiés avec niveau, classe, matière, état et statut de disponibilité |
| `requests` | Demandes envoyées par les utilisateurs pour les livres disponibles |
| `exchanges` | Enregistrements des échanges finalisés |
| `notifications` | Messages envoyés aux utilisateurs par le système ou l'administrateur |

### 3. Contraintes d'Intégrité

Le schéma intègre un ensemble complet de contraintes :

| Type | Exemples |
|---|---|
| **Clé primaire** | Toutes les tables |
| **Clé étrangère** | `books.owner_id → users.id`, `requests.book_id → books.id`, etc. |
| **UNIQUE** | `users.email`, `(school_classes.school_level, class_name)`, `(class_subjects.class_id, subject_id)` |
| **CHECK** | `users.role IN ('admin','user')`, `books.status IN ('available','reserved','exchanged')`, `requests.status IN ('pending','accepted','rejected')` |
| **NOT NULL** | Tous les champs obligatoires |

### 4. Séquences et Triggers d'Auto-incrément

Oracle XE ne supporte pas l'`IDENTITY` clause directement dans les anciennes versions. Les identifiants sont générés via des paires **séquence + trigger** :

```sql
CREATE SEQUENCE seq_books START WITH 1 INCREMENT BY 1 NOCACHE;

CREATE OR REPLACE TRIGGER trg_books_pk
BEFORE INSERT ON books
FOR EACH ROW
BEGIN
    IF :NEW.id IS NULL THEN
        SELECT seq_books.NEXTVAL INTO :NEW.id FROM dual;
    END IF;
END;
```

Ce pattern est appliqué uniformément sur toutes les tables : `users`, `subjects`, `school_classes`, `class_subjects`, `books`, `requests`, `exchanges`, `notifications`.

### 5. Index de Performance

Dix index sont définis pour optimiser les requêtes les plus fréquentes de l'application :

```
idx_subjects_active          → subjects(is_active, sort_order)
idx_school_classes_level     → school_classes(school_level, sort_order)
idx_class_subjects_class     → class_subjects(class_id, sort_order)
idx_books_owner              → books(owner_id)
idx_books_subject            → books(subject)
idx_books_level              → books(school_level)
idx_requests_book            → requests(book_id)
idx_requests_requester       → requests(requester_id)
idx_notifications_user       → notifications(user_id)
```

### 6. Vue de Reporting

```sql
CREATE OR REPLACE VIEW v_book_overview AS
SELECT
    b.id        AS book_id,
    b.title,
    b.subject,
    b.class_name,
    b.school_level,
    b.condition_label,
    b.estimated_price,
    b.status,
    u.name      AS owner_name,
    u.email     AS owner_email,
    b.created_at
FROM books b
JOIN users u ON u.id = b.owner_id;
```

Cette vue centralise les informations essentielles sur les livres et leurs propriétaires, utile pour les rapports et le tableau de bord administrateur.

### 7. Objets PL/SQL

#### 7.1 Procédure `add_notification`

Insère une notification pour un utilisateur donné. Utilisée à chaque événement important sur la plateforme (demande reçue, demande acceptée, etc.).

```sql
CREATE OR REPLACE PROCEDURE add_notification (
    p_user_id IN users.id%TYPE,
    p_message IN notifications.message%TYPE
) IS
BEGIN
    INSERT INTO notifications (user_id, message, is_read, created_at)
    VALUES (p_user_id, p_message, 0, SYSDATE);
END;
```

#### 7.2 Procédure `accept_request`

Accepte une demande de manière atomique : met à jour le statut de la demande, rejette les autres demandes en attente pour le même livre, change le statut du livre en `reserved`, et notifie le demandeur.

```sql
CREATE OR REPLACE PROCEDURE accept_request (
    p_request_id IN requests.id%TYPE,
    p_meeting_note IN requests.meeting_note%TYPE
) IS
    v_book_id       requests.book_id%TYPE;
    v_requester_id  requests.requester_id%TYPE;
    v_owner_id      books.owner_id%TYPE;
    v_title         books.title%TYPE;
BEGIN
    SELECT r.book_id, r.requester_id, b.owner_id, b.title
    INTO v_book_id, v_requester_id, v_owner_id, v_title
    FROM requests r JOIN books b ON b.id = r.book_id
    WHERE r.id = p_request_id;

    UPDATE requests SET status = 'accepted', meeting_note = p_meeting_note
    WHERE id = p_request_id;

    UPDATE requests SET status = 'rejected'
    WHERE book_id = v_book_id AND id <> p_request_id AND status = 'pending';

    UPDATE books SET status = 'reserved', updated_at = SYSDATE WHERE id = v_book_id;

    add_notification(v_requester_id,
        'Votre demande pour le livre "' || v_title || '" a été acceptée.');
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        DBMS_OUTPUT.PUT_LINE('Demande introuvable.');
END;
```

#### 7.3 Fonction `count_books_by_user`

Retourne le nombre de livres actifs publiés par un utilisateur.

```sql
CREATE OR REPLACE FUNCTION count_books_by_user (
    p_user_id IN users.id%TYPE
) RETURN NUMBER IS
    v_total NUMBER;
BEGIN
    SELECT COUNT(*) INTO v_total
    FROM books WHERE owner_id = p_user_id AND is_active = 1;
    RETURN v_total;
END;
```

#### 7.4 Fonction `calculate_money_saved`

Calcule l'économie totale estimée sur la base des prix des livres échangés.

```sql
CREATE OR REPLACE FUNCTION calculate_money_saved RETURN NUMBER IS
    v_total NUMBER;
BEGIN
    SELECT NVL(SUM(b.estimated_price), 0) INTO v_total
    FROM exchanges e JOIN books b ON b.id = e.book_id;
    RETURN v_total;
END;
```

#### 7.5 Trigger `trg_books_updated_at`

Met automatiquement à jour le champ `updated_at` à chaque modification d'un livre.

#### 7.6 Trigger `trg_book_exchange_log`

Inscrit automatiquement un enregistrement dans la table `exchanges` lorsqu'un livre passe au statut `exchanged`.

### 8. Éléments Oracle Spécifiques Démontrés

| Élément | Utilisation dans le projet |
|---|---|
| `%TYPE` | Typage des variables PL/SQL d'après les colonnes Oracle |
| `%ROWTYPE` | Typage d'une variable curseur selon la structure du curseur |
| `SQL%ROWCOUNT` | Comptage des lignes affectées après un UPDATE |
| `DBMS_OUTPUT.PUT_LINE` | Affichage de messages de débogage dans SQL Developer |
| `EXECUTE IMMEDIATE` | Exécution de SQL dynamique si nécessaire |
| Curseur implicite | Utilisation de `SQL%ROWCOUNT` après UPDATE |
| Curseur explicite | Parcours des livres disponibles avec `OPEN / FETCH / CLOSE` |

---

&nbsp;

<div align="center">

## Chapitre IV
### Partie Programmation Web 2

</div>

---

### Introduction

Au niveau de ce chapitre, nous présentons la partie développement web du projet BookCycle Tunisia. Nous détaillons les technologies utilisées, l'architecture MVC, la connexion Oracle, la gestion des sessions, les pages réalisées et les mesures de sécurité appliquées.

### 1. Technologies Utilisées

| Technologie | Rôle |
|---|---|
| **PHP 7.4** | Logique serveur, MVC, sessions |
| **Oracle XE** | Persistance des données |
| **PDO_OCI** | Couche d'accès aux données via Oracle |
| **HTML5** | Structure des pages |
| **CSS3** | Mise en forme et responsive design |
| **JavaScript** | Interactions dynamiques légères (filtres, mise à jour d'options) |

### 2. Architecture MVC Détaillée

#### 2.1 Couche Modèle

Les modèles encapsulent toutes les interactions avec la base Oracle via PDO :

| Modèle | Responsabilité |
|---|---|
| `User` | Authentification, gestion des comptes, activation/désactivation |
| `Book` | CRUD livres, filtrage catalogue, statistiques |
| `BookRequest` | Création, acceptation, rejet des demandes |
| `Notification` | Lecture et marquage des notifications |
| `AcademicOption` | Lecture des tables de référence (matières, classes, niveaux) |

#### 2.2 Couche Contrôleur

| Contrôleur | Actions principales |
|---|---|
| `AuthController` | `showLogin`, `login`, `showRegister`, `register`, `logout` |
| `BookController` | `index` (catalogue), `store` (ajout), `mine` (mes livres), `stats` |
| `RequestController` | `store` (envoi demande), `accept`, `reject`, `cancel` |
| `NotificationController` | `index`, `markRead` |
| `AdminController` | Dashboard, gestion utilisateurs, modération livres |
| `PageController` | Pages statiques (accueil, à propos, contact, politique) |

#### 2.3 Couche Vue

Les vues utilisent une structure de layout partagée :

```
app/Views/
├── layouts/
│   ├── header.php      ← En-tête commun à toutes les pages
│   └── footer.php      ← Pied de page commun
└── pages/
    ├── home.php
    ├── catalog.php
    ├── login.php
    ├── register.php
    ├── dashboard.php
    ├── add-book.php
    ├── admin.php
    ├── about.php
    ├── contact.php
    └── privacy-policy.php
```

### 3. Connexion à Oracle via PDO

La connexion est gérée par le singleton `Database` :

```php
self::$connection = new PDO($db['dsn'], $db['user'], $db['password'], [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_CASE               => PDO::CASE_LOWER,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
```

La configuration Oracle utilisée :

- **DSN** : `oci:dbname=//localhost:1521/XE;charset=AL32UTF8`
- **Utilisateur** : `bookcycle_app`
- **Mot de passe** : `BookCycle2026`

### 4. Fonctionnement du Routeur

Le fichier `router.php` constitue le front controller. Il intercepte toutes les requêtes HTTP et les redirige vers le contrôleur et l'action appropriés selon l'URL demandée.

Le script `public/index.php` initialise la session, charge l'autoloader, et délègue le traitement au routeur.

### 5. Gestion des Sessions et des Rôles

La classe `Auth` gère l'état d'authentification via les sessions PHP :

```php
Auth::login($user);   // Stocke id, name, email, role en session
Auth::check();        // true si connecté
Auth::isAdmin();      // true si role === 'admin'
Auth::id();           // Retourne l'ID utilisateur courant
Auth::logout();       // Détruit la session et régénère l'ID
```

### 6. Validation des Données

La validation dans `BookController::store()` illustre la cohérence entre les tables de référence Oracle et la logique PHP :

```php
if (!$this->isValidClassForLevel($payload['level'], $payload['class_name'])) {
    $this->respondError('La classe ne correspond pas au niveau.', '/add-book', 422);
    return;
}

if (!$this->isValidSubject($payload['level'], $payload['class_name'], $payload['subject'])) {
    $this->respondError('La matière ne correspond pas à la classe.', '/add-book', 422);
    return;
}
```

### 7. Pages et Fonctionnalités

| URL | Accès | Description |
|---|---|---|
| `/` | Tous | Page d'accueil avec présentation et livres récents |
| `/catalog` | Tous | Catalogue filtrable par niveau, classe, matière |
| `/about` | Tous | Présentation du projet |
| `/contact` | Tous | Formulaire de contact |
| `/privacy-policy` | Tous | Politique de confidentialité |
| `/login` | Non connecté | Formulaire de connexion |
| `/register` | Non connecté | Formulaire d'inscription (téléphone 8 chiffres obligatoire) |
| `/dashboard` | Connecté | Livres publiés, demandes envoyées/reçues, notifications |
| `/add-book` | Connecté | Formulaire d'ajout de livre |
| `/edit-book` | Connecté (propriétaire) | Formulaire de modification d'un livre |
| `/admin` | Admin | Tableau de bord d'administration |

### 8. Sécurité Appliquée

| Mesure | Implémentation |
|---|---|
| **Requêtes préparées** | PDO avec paramètres liés — protection contre les injections SQL |
| **Échappement HTML** | `htmlspecialchars()` dans les vues — protection contre le XSS |
| **Contrôle des rôles** | Vérification de `Auth::isAdmin()` avant chaque action admin |
| **Contrôle d'authentification** | Vérification de `Auth::check()` pour les actions protégées |
| **Règles métier** | Impossibilité de demander son propre livre, prévention des doublons |
| **Régénération de session** | `session_regenerate_id(true)` à la déconnexion |

---

&nbsp;

<div align="center">

## Chapitre V
### Réingénierie des Processus d'Affaires (RPA)

</div>

---

### Introduction

Au niveau de ce chapitre, nous analysons les processus métier de la plateforme BookCycle Tunisia selon la démarche **BPR (Business Process Reengineering)**. Nous évaluons l'état actuel (As-Is), sélectionnons un processus à fort potentiel de transformation, et proposons une solution cible (To-Be) intégrant l'automatisation.

### 1. Vision Processus

La plateforme **BookCycle Tunisia** peut être analysée comme un service numérique structuré autour de cinq processus principaux :

1. **Publication d'un livre** — le propriétaire met un livre en ligne
2. **Recherche et filtrage** — le visiteur ou utilisateur explore le catalogue
3. **Envoi d'une demande** — l'utilisateur sollicite un livre
4. **Traitement d'une demande** — le propriétaire répond à la demande
5. **Administration et suivi** — l'administrateur supervise la plateforme

### 2. Processus Sélectionné pour le BPR

Le processus sélectionné est : **le traitement d'une demande de livre**.

Ce choix est justifié car il est le plus critique pour l'expérience utilisateur, le plus sujet aux délais et aux oublis, et le mieux adapté à une automatisation.

#### 2.1 État Actuel (As-Is)

```
Demandeur                    Propriétaire               Système
    │                             │                          │
    ├── Envoie une demande ───────►                          │
    │                             │                          │
    │                     ◄─── Consulte son dashboard ──────┤
    │                             │                          │
    │                     ◄─── Lit les demandes reçues       │
    │                             │                          │
    │                     ◄─── Décide d'accepter ou refuser  │
    │                             │                          │
    ◄─── Notification de statut ──┼──────────────────────────┤
    │                             │                          │
```

**Durée totale As-Is :** 24h à 7 jours — dépendance 100% manuelle côté propriétaire.

#### 2.2 Limites du Processus Actuel

- Dépendance totale à l'action manuelle du propriétaire
- Délai de réponse imprévisible et non maîtrisé
- Risque d'oubli en cas d'absence ou de forte activité
- Aucune priorisation automatique des demandes anciennes
- Absence de clôture automatique des demandes redondantes

#### 2.3 État Cible (To-Be)

```
Demandeur                    Propriétaire               Système Automatisé
    │                             │                          │
    ├── Envoie une demande ───────►────────────────────►Notification immédiate
    │                             │                          │
    │                             │    ◄──── Relance auto si pending > 48h ──
    │                             │                          │
    │                     ◄─── Répond (accept/reject)        │
    │                             │                          │
    ◄─── Notification statut ─────┼──────────────────────────┤
    │                    [Autres demandes du livre → rejected automatiquement]
```

**Durée totale To-Be :** < 12 heures — **gain estimé > 75%** sur le délai de traitement.

### 3. Scénarios d'Automatisation

#### 3.1 Scénario 1 — Relance Automatique

- Détecter les demandes `pending` depuis plus de 48 heures
- Envoyer une notification de relance au propriétaire
- Remonter ces demandes en tête du tableau de bord

**Bénéfice :** Réduction du délai de réponse moyen, meilleure expérience demandeur.

#### 3.2 Scénario 2 — Clôture Automatique

- Lorsqu'une demande est acceptée, rejeter automatiquement les autres demandes du même livre
- Notifier les autres demandeurs du rejet
- Ce scénario est déjà partiellement implémenté dans la procédure `accept_request`

**Bénéfice :** Cohérence automatique des données, moins de travail manuel pour le propriétaire.

#### 3.3 Scénario 3 — Reporting Automatique

- Calculer et afficher des KPI de suivi en temps réel pour l'administrateur
- Alerter l'administrateur sur les livres inactifs depuis plus de 30 jours
- Générer des rapports périodiques de l'activité de la plateforme

**Bénéfice :** Meilleure visibilité sur l'activité, décisions d'administration plus éclairées.

### 4. KPI Définis

| Indicateur | Description |
|---|---|
| Nombre de livres actifs | Livres disponibles avec `is_active = 1` |
| Nombre total de demandes | Toutes demandes confondues |
| Taux d'acceptation | Demandes acceptées / total des demandes |
| Délai moyen de traitement | Temps moyen entre envoi et réponse |
| Nombre total d'échanges | Échanges finalisés dans la table `exchanges` |
| Économie estimée | Somme des `estimated_price` des livres échangés (en DT) |

---

&nbsp;

<div align="center">

## Tests et Validation

</div>

---

### Tests Fonctionnels Manuels

| Scénario de test | Résultat |
|---|---|
| Inscription avec email valide | Succès |
| Inscription avec email déjà utilisé | Erreur correctement affichée |
| Connexion avec bons identifiants | Succès, redirection vers `/dashboard` |
| Connexion avec mauvais mot de passe | Erreur affichée, pas de connexion |
| Ajout d'un livre avec tous les champs | Succès, livre visible dans le catalogue |
| Ajout d'un livre avec classe incohérente | Erreur de validation serveur |
| Filtrage du catalogue par niveau | Résultats corrects |
| Envoi d'une demande sur son propre livre | Bloqué par la règle métier |
| Envoi d'une demande en double (`pending`) | Bloqué par la règle métier |
| Acceptation d'une demande | Statut mis à jour, autres demandes rejetées, notification envoyée |
| Accès à `/admin` sans rôle admin | Redirection vers `/login` |
| Déconnexion | Session détruite, redirection vers l'accueil |

### Validation Technique

- Exécution des 5 scripts Oracle dans l'ordre correct
- Vérification de la connexion PDO Oracle sans erreur
- Démarrage de l'application via `start_oracle_app.bat`
- Accès aux 10 pages principales via le navigateur
- Vérification des objets PL/SQL dans SQL Developer

---

&nbsp;

<div align="center">

## Difficultés, Limites et Améliorations

</div>

---

### Difficultés Rencontrées

| Difficulté | Solution apportée |
|---|---|
| Configuration d'Oracle Instant Client | Utilisation de PDO_OCI avec le chemin explicite vers les librairies |
| Compatibilité PHP 7.4 et Oracle XE | Tests et ajustements des paramètres PDO |
| Gestion des séquences Oracle (pas d'auto-increment natif) | Pattern séquence + trigger sur toutes les tables |
| Synchronisation PHP ↔ PL/SQL | Tests manuels croisés entre SQL Developer et le navigateur |

### Limites Actuelles

| Limite | Impact |
|---|---|
| Pas d'upload d'image pour les livres | Interface moins riche visuellement |
| Pas de token CSRF | Vulnérabilité potentielle aux attaques CSRF |
| Pas de tests automatisés | Validation entièrement manuelle |
| Affichage mobile non optimisé | Expérience dégradée sur smartphone |
| Automatisation RPA partielle | Scénarios définis mais non encore déployés |

### Améliorations Proposées

| Amélioration | Priorité |
|---|---|
| Ajout d'un token CSRF sur tous les formulaires | Haute |
| Upload et affichage d'images de couverture | Haute |
| Enrichissement du profil utilisateur (biographie, ville) | Moyenne |
| Tests automatisés (PHPUnit) | Haute |
| Optimisation du responsive design | Moyenne |
| Déploiement sur un serveur distant | Basse |
| Automatisation complète des relances RPA | Moyenne |
| Tableaux de bord analytiques plus détaillés | Basse |

---

&nbsp;

<div align="center">

## Conclusion Générale

</div>

---

**BookCycle Tunisia** est un projet intégré abouti qui démontre la capacité à concevoir, modéliser et développer une application web complète en mobilisant des compétences complémentaires.

Sur le plan technique, le projet livre une architecture MVC claire en PHP 7.4, une base Oracle solide avec des objets PL/SQL bien conçus, et une interface fonctionnelle couvrant tous les besoins identifiés. Sur le plan méthodologique, il illustre comment l'analyse des besoins (AGL), la conception des données (SGBD), le développement web (Web 2) et la réflexion sur les processus (RPA) se complètent naturellement autour d'un cas d'usage concret.

La plateforme constitue une réponse pertinente à la problématique de départ et une base solide pour des extensions futures, notamment l'automatisation des relances et le déploiement en ligne.

---

*Rapport réalisé dans le cadre du Projet Intégré — Licence 2 Big Data et Intelligence Artificielle — ESEN — Université de la Manouba — 2025/2026*
