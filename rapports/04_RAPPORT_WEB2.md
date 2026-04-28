# RAPPORT PROGRAMMATION WEB 2
## BookCycle Tunisia

---

**Université de la Manouba — ESEN**
**Licence 2 — Big Data et Intelligence Artificielle**
**Année universitaire 2025 / 2026**

**Réalisé par :** Mortadha Yakoubi
**Module :** Programmation Web 2

---

## Table des Matières

1. [Introduction](#introduction)
2. [Objectif de l'Application](#objectif)
3. [Technologies Utilisées](#technologies)
4. [Architecture MVC Détaillée](#architecture-mvc)
5. [Connexion Oracle via PDO](#connexion-oracle)
6. [Routeur et Front Controller](#routeur)
7. [Gestion des Sessions et des Rôles](#sessions-et-rôles)
8. [Pages Web Réalisées](#pages-web)
9. [Fonctionnalités Principales](#fonctionnalités)
10. [Validation et Sécurité](#validation-et-sécurité)
11. [Points Forts et Limites](#points-forts-et-limites)
12. [Conclusion](#conclusion)

---

## 1. Introduction

La partie Programmation Web 2 du projet **BookCycle Tunisia** consiste à développer une application web complète en PHP, organisée selon le patron d'architecture **MVC**, connectée à une base de données **Oracle XE** via **PDO_OCI**, et accessible via un navigateur web standard.

L'application permet à des visiteurs de consulter un catalogue public de livres scolaires, et à des utilisateurs authentifiés de publier des livres, d'envoyer des demandes et de gérer leurs échanges. Un espace administrateur permet la modération et le suivi de la plateforme.

Ce rapport présente les choix techniques, l'architecture de l'application, les fonctionnalités implémentées et les mesures de sécurité appliquées.

---

## 2. Objectif de l'Application

L'application **BookCycle Tunisia** doit permettre :

| Objectif | Détail |
|---|---|
| **Catalogue public** | Tout visiteur peut consulter et filtrer les livres disponibles sans se connecter |
| **Gestion des livres** | Les utilisateurs connectés peuvent publier leurs livres scolaires |
| **Gestion des demandes** | Les utilisateurs peuvent envoyer, accepter ou refuser des demandes d'échange |
| **Notifications** | Les utilisateurs reçoivent des notifications lors des événements importants |
| **Administration** | L'administrateur supervise, modère et consulte les statistiques de la plateforme |

---

## 3. Technologies Utilisées

| Technologie | Version | Rôle dans le projet |
|---|---|---|
| **PHP** | 7.4 | Logique serveur, contrôleurs, modèles, gestion de session |
| **Oracle XE** | Express Edition | Base de données relationnelle |
| **PDO_OCI** | — | Couche d'abstraction PHP pour Oracle |
| **HTML5** | — | Structure des pages web |
| **CSS3** | — | Mise en forme, responsive design |
| **JavaScript** | Natif (ES6) | Interactions légères côté client (filtres dynamiques) |

### Lancement de l'Application

```powershell
# Méthode recommandée (depuis la racine du projet)
start_oracle_app.bat

# Méthode manuelle
C:\php74\php.exe -S localhost:8000 router.php
```

Puis accéder à : `http://localhost:8000`

---

## 4. Architecture MVC Détaillée

Le projet suit strictement le patron **MVC (Modèle-Vue-Contrôleur)**, qui sépare les responsabilités en trois couches indépendantes.

### 4.1 Structure des Fichiers

```
bookcycle-tunisia/
│
├── router.php                     ← Front Controller : routage des requêtes HTTP
│
├── public/
│   ├── index.php                  ← Bootstrap : session, autoloader, dispatch
│   └── assets/
│       ├── css/                   ← Feuilles de style globales
│       └── js/
│           └── app.js             ← Interactions JavaScript légères
│
└── app/
    ├── Config/
    │   └── config.php             ← Paramètres de connexion Oracle
    │
    ├── Core/
    │   ├── Auth.php               ← Gestion sessions et contrôle des rôles
    │   ├── Controller.php         ← Classe de base des contrôleurs
    │   └── Database.php           ← Singleton de connexion PDO/Oracle
    │
    ├── Models/
    │   ├── AcademicOption.php     ← Lecture des tables de référence
    │   ├── Book.php               ← CRUD livres et filtrage catalogue
    │   ├── BookRequest.php        ← Gestion des demandes
    │   ├── Notification.php       ← Lecture et marquage des notifications
    │   └── User.php               ← Authentification et gestion des comptes
    │
    ├── Controllers/
    │   ├── AdminController.php    ← Tableau de bord admin, modération
    │   ├── AuthController.php     ← Connexion, inscription, déconnexion
    │   ├── BookController.php     ← Catalogue, ajout de livre, statistiques
    │   ├── NotificationController.php ← Lecture des notifications
    │   ├── PageController.php     ← Pages statiques (accueil, about, contact)
    │   └── RequestController.php  ← Envoi, acceptation, rejet de demandes
    │
    └── Views/
        ├── layouts/
        │   ├── header.php         ← En-tête commun (navigation, session)
        │   └── footer.php         ← Pied de page commun
        └── pages/
            ├── home.php
            ├── catalog.php
            ├── login.php
            ├── register.php
            ├── dashboard.php
            ├── add-book.php
            ├── edit-book.php
            ├── admin.php
            ├── about.php
            ├── contact.php
            └── privacy-policy.php
```

### 4.2 Couche Modèle

Les modèles encapsulent toutes les interactions avec Oracle via PDO. Chaque modèle correspond à une ou plusieurs tables de la base de données.

| Modèle | Méthodes principales | Tables concernées |
|---|---|---|
| `User` | `findByEmail()`, `create()`, `activate()`, `deactivate()` | `users` |
| `Book` | `all()`, `mine()`, `create()`, `hide()`, `countActive()` | `books` |
| `BookRequest` | `store()`, `accept()`, `reject()`, `received()`, `sent()` | `requests` |
| `Notification` | `forUser()`, `markRead()` | `notifications` |
| `AcademicOption` | `getLevels()`, `getClassesForLevel()`, `getSubjectsForClass()`, `hasClassForLevel()`, `hasSubjectForClass()` | `subjects`, `school_classes`, `class_subjects` |

**Exemple — Modèle `AcademicOption` :** Ce modèle est particulièrement important car il alimente dynamiquement les formulaires d'ajout de livre depuis Oracle, et valide la cohérence niveau/classe/matière.

### 4.3 Couche Contrôleur

Les contrôleurs reçoivent les requêtes HTTP, appellent les méthodes des modèles, et transmettent les données aux vues.

#### `BookController` — Extrait représentatif

```php
public function store()
{
    // Vérification de l'authentification
    if (!Auth::check()) {
        $this->respondError('Authentification requise.', '/login', 401);
        return;
    }

    $payload = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    // Validation des champs obligatoires
    foreach (['subject', 'level', 'class_name', 'condition', 'estimated_price'] as $field) {
        if (empty($payload[$field])) {
            $this->respondError('Champs obligatoires manquants.', '/add-book', 422);
            return;
        }
    }

    // Validation de la cohérence niveau ↔ classe (via Oracle)
    if (!$this->isValidClassForLevel($payload['level'], $payload['class_name'])) {
        $this->respondError('Classe invalide pour ce niveau.', '/add-book', 422);
        return;
    }

    // Validation de la cohérence classe ↔ matière (via Oracle)
    if (!$this->isValidSubject($payload['level'], $payload['class_name'], $payload['subject'])) {
        $this->respondError('Matière invalide pour cette classe.', '/add-book', 422);
        return;
    }

    // Création du livre
    $this->books->create(array_merge($payload, [
        'title'    => $this->buildBookTitle($payload),
        'owner_id' => Auth::id(),
    ]));

    $_SESSION['flash_success'] = 'Livre ajouté avec succès.';
    $this->redirect('/dashboard');
}
```

### 4.4 Couche Vue

Les vues sont des fichiers PHP purs qui reçoivent des variables depuis le contrôleur et génèrent le HTML. Elles s'appuient sur un système de layout partagé (`header.php` + `footer.php`) pour éviter la duplication.

**Exemple de rendu d'une vue avec données Oracle :**

```php
// Dans le contrôleur
$books = $this->books->all($filters);
require __DIR__ . '/../Views/pages/catalog.php';

// Dans la vue (catalog.php)
foreach ($books as $book) {
    echo '<div class="book-card">';
    echo '<h3>' . htmlspecialchars($book['title']) . '</h3>';
    echo '<p>' . htmlspecialchars($book['subject']) . ' — ' . htmlspecialchars($book['class_name']) . '</p>';
    echo '</div>';
}
```

L'utilisation systématique de `htmlspecialchars()` dans les vues prévient les attaques XSS.

---

## 5. Connexion Oracle via PDO

La connexion est gérée par le singleton `Database` dans `app/Core/Database.php` :

```php
public static function connection()
{
    if (self::$connection instanceof PDO) {
        return self::$connection;
    }

    $config = require dirname(__DIR__) . '/Config/config.php';
    $db = $config['db'];

    self::$connection = new PDO($db['dsn'], $db['user'], $db['password'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_CASE               => PDO::CASE_LOWER,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return self::$connection;
}
```

**Options PDO utilisées :**

| Option | Valeur | Effet |
|---|---|---|
| `ATTR_ERRMODE` | `ERRMODE_EXCEPTION` | Les erreurs SQL lèvent une exception PHP |
| `ATTR_CASE` | `CASE_LOWER` | Les noms de colonnes Oracle sont retournés en minuscules |
| `ATTR_DEFAULT_FETCH_MODE` | `FETCH_ASSOC` | Les résultats sont retournés sous forme de tableaux associatifs |

Le pattern **singleton** garantit qu'une seule connexion PDO est ouverte par requête HTTP.

---

## 6. Routeur et Front Controller

Le fichier `router.php` constitue le **point d'entrée unique** de l'application. Il intercepte toutes les requêtes HTTP et les redirige vers le contrôleur et l'action appropriés selon l'URL demandée.

**Exemples de routes définies :**

| Méthode | URL | Contrôleur | Action |
|---|---|---|---|
| GET | `/` | `PageController` | `home` |
| GET | `/catalog` | `PageController` | `catalog` |
| GET | `/login` | `PageController` | `login` |
| POST | `/login` | `AuthController` | `login` |
| GET | `/register` | `PageController` | `register` |
| POST | `/register` | `AuthController` | `register` |
| GET | `/dashboard` | `PageController` | `dashboard` |
| GET | `/add-book` | `PageController` | `addBook` |
| POST | `/add-book` | `BookController` | `store` |
| GET | `/edit-book` | `PageController` | `editBook` |
| POST | `/edit-book` | `BookController` | `update` |
| POST | `/request-book` | `RequestController` | `store` |
| POST | `/accept-request` | `RequestController` | `accept` |
| POST | `/reject-request` | `RequestController` | `reject` |
| GET | `/notifications/read` | `NotificationController` | `read` |
| GET | `/admin` | `PageController` | `admin` |
| POST | `/admin/toggle-user` | `AdminController` | `toggleUser` |
| POST | `/admin/delete-user` | `AdminController` | `permanentDeleteUser` |
| POST | `/admin/delete-book` | `AdminController` | `deleteBook` |
| POST | `/admin/restore-book` | `AdminController` | `restoreBook` |
| POST | `/admin/cancel-request` | `AdminController` | `cancelRequest` |
| POST | `/admin/notify` | `AdminController` | `notify` |
| POST | `/logout` | `AuthController` | `logout` |

---

## 7. Gestion des Sessions et des Rôles

La classe `Auth` dans `app/Core/Auth.php` centralise toute la gestion des sessions :

```php
// Connexion — stocke les informations essentielles en session
Auth::login($user);
// Stocke : id, name, email, role

// Contrôles d'accès
Auth::check();     // true si l'utilisateur est connecté
Auth::isAdmin();   // true si le rôle est 'admin'
Auth::id();        // Retourne l'ID de l'utilisateur courant
Auth::user();      // Retourne le tableau complet des données de session

// Déconnexion sécurisée
Auth::logout();    // Détruit $_SESSION['user'] et régénère l'ID de session
```

**Protection des routes :** Chaque contrôleur vérifie les droits avant toute action sensible :

```php
// Protection d'une action utilisateur
if (!Auth::check()) {
    $this->redirect('/login');
    return;
}

// Protection d'une action admin
if (!Auth::isAdmin()) {
    http_response_code(403);
    exit('Accès refusé.');
}
```

---

## 8. Pages Web Réalisées

| URL | Accès | Description | Fonctionnalités |
|---|---|---|---|
| `/` | Tous | Page d'accueil | Présentation, livres récents, statistiques |
| `/catalog` | Tous | Catalogue public | Liste des livres, filtres niveau/classe/matière, détail d'un livre |
| `/login` | Non connecté | Connexion | Formulaire email + mot de passe, message d'erreur |
| `/register` | Non connecté | Inscription | Formulaire complet, validation email unique, téléphone 8 chiffres |
| `/dashboard` | Connecté | Tableau de bord | Mes livres (avec bouton Modifier), demandes reçues, demandes envoyées, notifications |
| `/add-book` | Connecté | Ajout de livre | Formulaire avec filtres dynamiques Oracle, validation complète |
| `/edit-book` | Connecté (propriétaire) | Modification de livre | Modifier l'état, le prix estimé et la description d'un livre publié |
| `/admin` | Admin | Administration | Statistiques, gestion utilisateurs, suppression permanente, modération livres, notifications |
| `/about` | Tous | À propos | Présentation du projet et de ses objectifs |
| `/contact` | Tous | Contact | Informations de contact |
| `/privacy-policy` | Tous | Politique de confidentialité | Politique d'utilisation de la plateforme |

---

## 9. Fonctionnalités Principales

### 9.1 Inscription et Connexion

```
Inscription :
  ✔ Validation email (format et unicité dans Oracle)
  ✔ Validation téléphone (exactement 8 chiffres, espaces ignorés)
  ✔ Validation mot de passe (longueur minimale)
  ✔ Hash du mot de passe avant insertion (password_hash)
  ✔ Redirection vers /login après inscription réussie

Connexion :
  ✔ Vérification email + mot de passe (password_verify)
  ✔ Contrôle du statut is_active du compte
  ✔ Stockage des données essentielles en session (id, name, email, role)
  ✔ Redirection vers /dashboard
```

### 9.2 Catalogue Public avec Filtres Dynamiques

Le catalogue charge les options de filtrage **directement depuis Oracle** via le modèle `AcademicOption` :

```
Filtres disponibles :
  ✔ Niveau scolaire   → chargé depuis school_classes.school_level
  ✔ Classe            → chargé selon le niveau sélectionné
  ✔ Matière           → chargé selon la classe sélectionnée (via class_subjects)
```

La mise à jour des listes de classes et de matières selon les sélections est gérée par `app.js` en JavaScript natif, via des requêtes sur les données pré-chargées depuis Oracle.

### 9.3 Ajout d'un Livre

```
Champs du formulaire :
  - Niveau scolaire (liste Oracle)
  - Classe          (liste Oracle, dépend du niveau)
  - Matière         (liste Oracle, dépend de la classe)
  - État du livre   (Très bon état / Bon état / État correct / Abîmé)
  - Description     (optionnelle)
  - Prix estimé     (en DT)

Validations côté serveur :
  ✔ Tous les champs obligatoires sont présents
  ✔ La classe correspond au niveau (vérification Oracle)
  ✔ La matière est autorisée pour la classe (vérification Oracle)
  ✔ Le titre est généré automatiquement : "Matière - Classe - Niveau"
```

### 9.4 Gestion des Demandes

```
Envoi d'une demande :
  ✔ Réservé aux utilisateurs connectés
  ✔ Impossible de demander son propre livre (règle métier)
  ✔ Impossible d'avoir deux demandes pending pour le même livre (anti-doublon)

Traitement d'une demande (propriétaire) :
  ✔ Acceptation avec note de rendez-vous obligatoire
  ✔ Appel de la procédure Oracle accept_request :
     - La demande passe à 'accepted'
     - Les autres demandes du livre passent à 'rejected'
     - Le livre passe à 'reserved'
     - Le demandeur reçoit une notification
  ✔ Refus simple avec mise à jour du statut

Administration :
  ✔ L'administrateur peut annuler n'importe quelle demande
```

### 9.5 Tableau de Bord Utilisateur

Le tableau de bord (`/dashboard`) centralise en une seule page :

- **Mes livres publiés** : liste avec état, prix estimé, description, statut et bouton **Modifier** vers `/edit-book`
- **Demandes reçues** : demandes en attente sur ses livres, avec boutons Accepter / Refuser
- **Demandes envoyées** : suivi des demandes envoyées à d'autres propriétaires
- **Notifications** : historique des messages reçus avec marquage « lu »

### 9.6 Modification d'un Livre (`/edit-book`)

Un utilisateur peut modifier un livre qu'il a publié, tant que ce livre lui appartient :

```
Champs modifiables :
  ✔ État du livre (Neuf / Bon / Usagé)
  ✔ Prix estimé (en DT)
  ✔ Description (optionnelle)

Champs en lecture seule (non modifiables) :
  ✗ Niveau scolaire
  ✗ Classe
  ✗ Matière

Sécurité :
  ✔ Vérification que le livre appartient à l'utilisateur connecté
  ✔ Redirection vers /dashboard si livre introuvable ou accès refusé
```

### 9.7 Espace Administrateur

Le tableau de bord administrateur (`/admin`) affiche :

**Statistiques globales :**
- Nombre total d'utilisateurs inscrits
- Nombre total de livres actifs
- Nombre total d'échanges finalisés
- Économie totale estimée (calculée via `calculate_money_saved()`)

**Actions de modération :**
- Activer / désactiver un compte utilisateur (suppression logique)
- **Supprimer définitivement** un utilisateur (DELETE physique sur `users`, `requests`, `notifications`) — bloqué si l'utilisateur a encore des livres actifs
- Masquer / restaurer un livre (suppression logique : `is_active = 0`)
- Annuler une demande en cours
- Envoyer une notification ciblée ou globale (à tous les utilisateurs actifs)

---

## 10. Validation et Sécurité

### 10.1 Protection contre les Injections SQL

Toutes les interactions avec Oracle passent par des **requêtes préparées PDO** avec des paramètres liés :

```php
// Exemple sécurisé — jamais de concaténation directe
$stmt = Database::connection()->prepare(
    'SELECT * FROM books WHERE owner_id = :owner_id AND is_active = 1'
);
$stmt->execute([':owner_id' => $userId]);
```

### 10.2 Protection contre le XSS

Toutes les variables affichées dans les vues sont échappées avec `htmlspecialchars()` :

```php
echo htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8');
```

### 10.3 Contrôle d'Accès par Rôle

```
Niveau 1 — Actions publiques     : aucune vérification requise
Niveau 2 — Actions utilisateur   : Auth::check() vérifié avant chaque action
Niveau 3 — Actions administrateur: Auth::isAdmin() vérifié, HTTP 403 sinon
```

### 10.4 Règles Métier de Sécurité

| Règle | Implémentation |
|---|---|
| Pas de demande sur son propre livre | Vérification `book.owner_id !== Auth::id()` |
| Pas de doublon de demande | Vérification `pending` existant avant insertion |
| Cohérence niveau/classe | Requête sur `school_classes` avant validation |
| Cohérence classe/matière | Requête sur `class_subjects` avant validation |
| Compte inactif bloqué | Vérification `users.is_active = 1` à la connexion |

### 10.5 Sécurité des Sessions

```php
Auth::logout();
// Détruit $_SESSION['user']
// Appelle session_regenerate_id(true) pour prévenir la fixation de session
```

### 10.6 Résumé des Mesures de Sécurité

| Menace | Contre-mesure appliquée |
|---|---|
| Injection SQL | Requêtes préparées PDO avec paramètres liés |
| XSS (Cross-Site Scripting) | `htmlspecialchars()` systématique dans les vues |
| Accès non autorisé | Contrôle `Auth::check()` et `Auth::isAdmin()` |
| Fixation de session | `session_regenerate_id(true)` à la déconnexion |
| Escalade de privilèges | Vérification du rôle `admin` côté serveur, jamais côté client |
| Données incohérentes | Validation croisée niveau/classe/matière via Oracle |

---

## 11. Captures d'Écran des Interfaces

> Les captures d'écran suivantes illustrent les principales interfaces de l'application.
> Pour les visualiser, lancer l'application via `start_oracle_app.bat` puis accéder à `http://localhost:8000`.

### 11.1 Page d'Accueil (`/`)

**Description :** Page d'accueil publique affichant la présentation de la plateforme et les derniers livres publiés.

- En-tête avec navigation : Logo, liens Catalogue, Connexion, Inscription
- Section hero avec titre et bouton d'appel à l'action
- Section "Derniers livres" avec les 4 livres les plus récents
- Pied de page avec liens utiles

**Comptes de démonstration :**
- Administrateur : `admin@bookcycle.tn` / `admin123`
- Utilisateur : `ahmed@bookcycle.tn` / `user123`

### 11.2 Page Catalogue (`/catalog`)

**Description :** Catalogue public filtrable, accessible sans authentification.

- Barre de filtres : Niveau scolaire → Classe → Matière (chargement dynamique via Oracle)
- Grille de livres avec : titre, matière, classe, niveau, état, prix estimé
- Bouton "Demander" visible uniquement pour les utilisateurs connectés
- Message "Aucun livre trouvé" si les filtres ne correspondent à aucun résultat

### 11.3 Page Connexion (`/login`)

**Description :** Formulaire d'authentification.

- Champs : Email, Mot de passe
- Message d'erreur si identifiants incorrects (sans révéler si c'est l'email ou le mot de passe)
- Lien vers la page d'inscription
- Redirection vers `/dashboard` après connexion réussie

### 11.4 Page Inscription (`/register`)

**Description :** Formulaire de création de compte.

- Champs : Nom complet, Email, Téléphone, Mot de passe
- Validation téléphone : exactement **8 chiffres** (HTML5 `pattern="[0-9]{8}"` + vérification serveur `preg_match`)
- Validation email : format valide et unicité vérifiée en base Oracle
- Hash du mot de passe avec `password_hash()` avant insertion
- Redirection vers `/login` après inscription réussie

### 11.5 Page Tableau de Bord (`/dashboard`)

**Description :** Espace personnel de l'utilisateur connecté, organisé en sections.

- **Mes livres** : liste des livres publiés avec état, prix, description, statut (Disponible / Réservé / Échangé) et bouton **Modifier**
- **Demandes reçues** : demandes en attente sur ses livres, avec boutons Accepter / Refuser et champ note de rendez-vous
- **Demandes envoyées** : suivi des demandes envoyées avec statut, coordonnées du propriétaire si accepté
- **Notifications** : liste des notifications avec lien « Marquer comme lue »

### 11.6 Page Modification de Livre (`/edit-book`)

**Description :** Formulaire de modification d'un livre appartenant à l'utilisateur connecté.

- Champs niveau, classe et matière affichés en **lecture seule** (non modifiables pour préserver la cohérence avec les demandes existantes)
- Champ **État** (liste déroulante : Neuf / Bon / Usagé), pré-sélectionné avec la valeur actuelle
- Champ **Prix estimé** pré-rempli avec la valeur actuelle
- Champ **Description** optionnel
- Boutons : Enregistrer les modifications / Annuler (retour vers `/dashboard`)
- Accès refusé si le livre n'appartient pas à l'utilisateur connecté

### 11.8 Page Ajout de Livre (`/add-book`)

**Description :** Formulaire de publication d'un livre scolaire.

- Champ Niveau scolaire (liste déroulante chargée depuis Oracle)
- Champ Classe (mise à jour automatique selon le niveau via JavaScript)
- Champ Matière (mise à jour automatique selon la classe via JavaScript)
- Champ État du livre (Très bon état / Bon état / État correct / Abîmé)
- Champ Description (optionnel, textarea)
- Champ Prix estimé (numérique, en DT)
- Messages de validation côté serveur si les champs sont incomplets ou incohérents

### 11.9 Page Administration (`/admin`)

**Description :** Tableau de bord administrateur, accessible uniquement avec le rôle `admin`.

**Section Statistiques globales :**
- Nombre total d'utilisateurs inscrits
- Nombre total de livres actifs
- Nombre total d'échanges finalisés
- Économie totale estimée (en DT, calculée via `calculate_money_saved()`)

**Section Gestion des utilisateurs :**
- Liste des utilisateurs avec email, rôle, statut (actif / inactif)
- Boutons Activer / Désactiver par utilisateur

**Section Modération des livres :**
- Liste des livres récents avec titre, propriétaire, statut
- Boutons Masquer / Restaurer par livre

**Section Notifications :**
- Formulaire pour envoyer une notification ciblée à un utilisateur
- Champs : destinataire (ID ou email), message

---

## 12. Points Forts et Limites

### 12.1 Points Forts

| Point fort | Détail |
|---|---|
| **Architecture claire** | Séparation stricte MVC, code modulaire et lisible |
| **Intégration Oracle réelle** | Requêtes SQL exécutées sur une vraie base Oracle XE |
| **Tables de référence dynamiques** | Les matières et classes viennent d'Oracle, pas de listes PHP codées en dur |
| **Validation cohérente** | La logique de validation côté PHP s'appuie sur les contraintes Oracle |
| **Tableau de bord admin complet** | Statistiques, modération et notifications en un seul espace |
| **Procédure PL/SQL intégrée** | `accept_request` appelée depuis PHP pour garantir l'atomicité |

### 12.2 Limites Actuelles

| Limite | Impact | Solution envisagée |
|---|---|---|
| Pas d'upload d'image | Interface moins riche, livres sans couverture visuelle | Upload vers `/storage/` |
| Pas de token CSRF | Risque d'attaque CSRF sur les formulaires POST | Ajout d'un token dans chaque formulaire |
| Pas de tests automatisés | Validation uniquement manuelle | Intégration de PHPUnit |
| Responsive mobile limité | Ergonomie dégradée sur smartphones | Refonte CSS avec media queries |
| Pas d'envoi d'email | Inscription sans confirmation par email | Intégration d'un service SMTP |
| Hébergement local uniquement | L'application tourne sur `localhost:8000`, pas accessible en ligne | Déploiement sur serveur distant avec Oracle ou migration vers MySQL |

---

## 12. Conclusion

La partie Programmation Web 2 de **BookCycle Tunisia** livre une application web complète, structurée et fonctionnelle. Elle démontre la maîtrise des concepts fondamentaux du développement web côté serveur :

- Le patron **MVC** est appliqué rigoureusement, assurant une séparation claire entre les données, la logique et la présentation
- La connexion à **Oracle via PDO_OCI** est encapsulée de façon propre dans un singleton, et toutes les requêtes utilisent des paramètres liés
- La gestion des **sessions et des rôles** est centralisée dans la classe `Auth` et appliquée de façon cohérente sur toutes les routes protégées
- La **validation des données** combine des vérifications PHP et des contraintes Oracle pour garantir l'intégrité des informations saisies
- L'ensemble des **10 pages principales** est opérationnel et couvre tous les besoins fonctionnels identifiés en phase d'analyse

Le projet constitue une base solide et extensible, notamment pour l'ajout de la gestion des images, des tests automatisés et du renforcement de la sécurité.

---

*Rapport réalisé dans le cadre du Projet Intégré — Licence 2 Big Data et Intelligence Artificielle — ESEN — Université de la Manouba — 2025/2026*
