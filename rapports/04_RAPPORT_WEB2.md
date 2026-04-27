# Rapport Programmation Web 2 — BookCycle Tunisia

**Université de La Manouba — ESEN — L2 Big Data et Intelligence Artificielle**
**Année universitaire 2025/2026 — Projet Intégré**

**Site hébergé :** `https://bookcycle-tunisia.page.gd`

---

## Introduction

La partie Programmation Web 2 consiste à développer une application web complète en PHP permettant de consulter, publier, modifier, supprimer et demander des livres scolaires. L'application est connectée à une base de données via **PDO**, organisée selon une architecture **MVC**, et développée en **POO** (Programmation Orientée Objet).

---

## 1. Technologies Utilisées

| Composant | Local (soutenance) | En ligne |
|---|---|---|
| Langage | PHP 7.4 | PHP 7.4 |
| SGBD | Oracle XE | MySQL |
| Connexion BD | PDO_OCI | PDO_MYSQL |
| Frontend | HTML5, CSS3, JavaScript | Identique |
| Architecture | MVC | MVC |
| URL | http://localhost:8000 | https://bookcycle-tunisia.page.gd |

---

## 2. Architecture MVC

L'application suit le patron de conception **Modèle-Vue-Contrôleur (MVC)**.

```
Requête HTTP
     │
     ▼
public/index.php ──── Routeur + Front Controller
     │
     ▼
  Controller ──────── Logique métier, validation
     │
     ├──► Model ────── Requêtes PDO → Base de données
     │        └──────── Retourne les données
     │
     └──► View ─────── Affichage HTML avec les données
              │
              ▼
         Réponse HTML → Navigateur
```

### 2.1 Rôle De Chaque Couche

| Couche | Fichiers | Rôle |
|---|---|---|
| **Routeur** | `public/index.php` | Lit l'URL et la méthode HTTP, dispatche vers le bon contrôleur |
| **Contrôleurs** | `app/Controllers/*.php` | Valide les données, appelle les modèles, passe les résultats aux vues |
| **Modèles** | `app/Models/*.php` | Contient uniquement les requêtes SQL via PDO |
| **Vues** | `app/Views/pages/*.php` | Affiche le HTML avec `htmlspecialchars()` pour la sécurité |
| **Core** | `app/Core/*.php` | Classes partagées (Database, Auth, Controller de base) |

---

## 3. Programmation Orientée Objet (POO)

L'application exploite pleinement les principes de la POO :

### 3.1 Encapsulation

```php
// Dans Database.php — attribut privé, accessible uniquement via connection()
class Database {
    private static $connection = null;  // encapsulé, non accessible de l'extérieur
    public static function connection() { ... }  // seule méthode d'accès publique
}

// Dans Book.php — attribut privé $db
class Book {
    private $db;  // la connexion PDO est encapsulée dans chaque modèle
    public function all($filters = []) { ... }
}
```

### 3.2 Héritage (Généralisation)

```php
// Controller.php — classe de base abstraite
class Controller {
    protected function render($view, $data = []) { ... }
    protected function json($payload, $code = 200) { ... }
}

// BookController.php — hérite de Controller
class BookController extends Controller {
    public function store() { ... }
    public function update() { ... }
}

// AdminController.php — hérite de Controller
class AdminController extends Controller {
    public function stats() { ... }
    public function permanentDeleteUser() { ... }
}
```

**Généralisation UML :** `Controller` est la super-classe généralisée par `BookController`, `AuthController`, `AdminController`, `PageController`, `RequestController`, `NotificationController`.

### 3.3 Patron Singleton (Design Pattern)

```php
// Database.php — une seule instance PDO partagée par toute l'application
class Database {
    private static $connection = null;
    public static function connection() {
        if (self::$connection instanceof PDO) {
            return self::$connection;  // réutilise l'instance existante
        }
        self::$connection = new PDO(...);  // crée une nouvelle instance si besoin
        return self::$connection;
    }
}
```

### 3.4 Classes Et Responsabilités

| Classe | Type | Attributs clés | Méthodes principales |
|---|---|---|---|
| `Database` | Core | `$connection` (static) | `connection()` |
| `Auth` | Core | — | `user()`, `id()`, `check()`, `isAdmin()`, `login()`, `logout()` |
| `Controller` | Base (héritage) | — | `render()`, `json()` |
| `Book` | Modèle | `$db` | `create()`, `all()`, `find()`, `update()`, `mine()`, `deactivate()` |
| `User` | Modèle | `$db` | `create()`, `findByEmail()`, `setActive()`, `delete()`, `hasActiveBooks()` |
| `BookRequest` | Modèle | `$db` | `create()`, `accept()`, `reject()`, `mine()`, `received()` |
| `Notification` | Modèle | `$db` | `create()`, `createForAll()`, `latestForUser()`, `markAsRead()` |
| `AcademicOption` | Modèle | `$db` | `levels()`, `classesByLevel()`, `subjects()`, `hasSubjectForClass()` |
| `BookController` | Contrôleur | — | `store()`, `update()`, `index()`, `stats()` |
| `AdminController` | Contrôleur | — | `stats()`, `toggleUser()`, `permanentDeleteUser()`, `notify()` |
| `PageController` | Contrôleur | — | `home()`, `catalog()`, `dashboard()`, `editBook()`, `admin()` |
| `AuthController` | Contrôleur | — | `login()`, `register()`, `logout()` |

---

## 4. Connexion PDO À La Base De Données

```php
// Connexion PDO avec gestion d'erreur (try/catch — cours 05)
try {
    self::$connection = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_CASE               => PDO::CASE_LOWER,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Erreur de connexion : ' . $e->getMessage());
}
```

**Fermeture de connexion :**
```php
// Fermer le curseur après utilisation (cours 05 - closeCursor)
$statement->closeCursor();
// PHP ferme automatiquement la connexion en fin de script
// Fermeture explicite possible : $pdo = null;
```

---

## 5. Les 4 Opérations CRUD

### 5.1 Affichage — SELECT + fetch / fetchAll

```php
// Récupérer tous les livres actifs avec jointure propriétaire
$stmt = $this->db->prepare(
    'SELECT b.title, b.subject, b.estimated_price,
            u.name AS owner_name
     FROM books b
     INNER JOIN users u ON u.id = b.owner_id
     WHERE b.is_active = 1
     ORDER BY b.created_at DESC'
);
$stmt->execute();
$books = $stmt->fetchAll();  // tableau associatif de toutes les lignes
$stmt->closeCursor();
```

### 5.2 Ajout — INSERT via formulaire POST

```php
// Formulaire HTML (cours 04)
// <form method="post" action="/add-book">
//   <input name="subject"> <input name="estimated_price"> ...
// </form>

// Traitement PHP sécurisé (requête préparée)
$stmt = $this->db->prepare(
    'INSERT INTO books (title, subject, class_name, school_level,
                        condition_label, estimated_price, description,
                        owner_id, status, is_active)
     VALUES (:title, :subject, :class_name, :school_level,
             :book_condition, :estimated_price, :description,
             :owner_id, :status, :is_active)'
);
$stmt->execute([
    'title'          => $data['title'],
    'subject'        => $data['subject'],
    'class_name'     => $data['class_name'],
    'school_level'   => $data['level'],
    'book_condition' => $data['condition'],
    'estimated_price'=> (float) $data['estimated_price'],
    'description'    => $data['description'] ?? null,
    'owner_id'       => (int) $data['owner_id'],
    'status'         => 'available',
    'is_active'      => 1,
]);
```

### 5.3 Modification — UPDATE via formulaire POST

```php
// Formulaire HTML (/edit-book?id=X)
// <form method="post" action="/edit-book">
//   <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
//   <select name="condition"> ... </select>
//   <input name="estimated_price" type="number"> ...
// </form>

// Traitement PHP — seul le propriétaire peut modifier
$stmt = $this->db->prepare(
    'UPDATE books
     SET condition_label   = :condition_label,
         estimated_price   = :estimated_price,
         description       = :description,
         updated_at        = SYSDATE
     WHERE id       = :id
       AND owner_id = :owner_id'  // sécurité : vérifier le propriétaire
);
$stmt->execute([
    'condition_label' => $data['condition'],
    'estimated_price' => (float) $data['estimated_price'],
    'description'     => $data['description'] ?? null,
    'id'              => (int) $bookId,
    'owner_id'        => (int) $ownerId,
]);
$stmt->closeCursor();
```

### 5.4 Suppression — DELETE physique (admin)

```php
// Suppression physique réelle (DELETE FROM — cours 05)
// Respecte les clés étrangères en supprimant d'abord les dépendances

// Étape 1 : supprimer les notifications
$stmt = $this->db->prepare('DELETE FROM notifications WHERE user_id = :id');
$stmt->execute(['id' => (int) $userId]);
$stmt->closeCursor();

// Étape 2 : supprimer les demandes envoyées
$stmt = $this->db->prepare('DELETE FROM requests WHERE requester_id = :id');
$stmt->execute(['id' => (int) $userId]);
$stmt->closeCursor();

// Étape 3 : supprimer l'utilisateur
$stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
$stmt->execute(['id' => (int) $userId]);
$stmt->closeCursor();
```

---

## 6. Recherche Multi-Critères

Le catalogue supporte 4 filtres combinables (niveau, classe, matière, statut) :

```php
// Construction dynamique selon les filtres actifs (GET)
$sql = 'SELECT b.id, b.title, b.subject, u.name AS owner_name
        FROM books b
        INNER JOIN users u ON u.id = b.owner_id
        WHERE b.is_active = 1';
$params = [];

if (!empty($filters['level'])) {
    $sql .= ' AND b.school_level = :school_level_filter';
    $params['school_level_filter'] = $filters['level'];
}
if (!empty($filters['class_name'])) {
    $sql .= ' AND b.class_name = :class_name_filter';
    $params['class_name_filter'] = $filters['class_name'];
}
if (!empty($filters['subject'])) {
    $sql .= ' AND b.subject LIKE :subject';
    $params['subject'] = '%' . $filters['subject'] . '%';
}

$stmt = $this->db->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();
```

---

## 7. Données Multi-Tables (Jointures)

L'application accède régulièrement à des données de **plusieurs tables simultanément** :

```php
// INNER JOIN : livres avec informations propriétaire
SELECT b.title, b.subject, u.name AS owner_name, u.email
FROM books b
INNER JOIN users u ON u.id = b.owner_id
WHERE b.is_active = 1;

// LEFT JOIN : livres avec nombre de demandes (même sans demandes)
SELECT b.title, COUNT(r.id) AS nb_demandes
FROM books b
LEFT JOIN requests r ON r.book_id = b.id
WHERE b.is_active = 1
GROUP BY b.title;

// JOIN triple : demandes + livres + demandeurs
SELECT r.id, b.title, u.name AS requester_name, r.status
FROM requests r
JOIN books b ON b.id = r.book_id
JOIN users u ON u.id = r.requester_id;
```

---

## 8. Formulaires GET Et POST

```php
// GET — filtres du catalogue (données dans l'URL, partageables)
// URL : /catalog?level=Lycée&class_name=Terminale&subject=Maths
$level      = $_GET['level']      ?? null;
$class_name = $_GET['class_name'] ?? null;
$subject    = $_GET['subject']    ?? null;

// POST — soumission de formulaires (données invisibles dans l'URL)
// <form method="post" action="/add-book">
$title            = $_POST['title']            ?? '';
$estimated_price  = $_POST['estimated_price']  ?? 0;
$condition        = $_POST['condition']        ?? '';
```

---

## 9. Sessions Et Authentification

```php
// Connexion : stocker les infos en session
$_SESSION['user'] = [
    'id'    => $user['id'],
    'name'  => $user['name'],
    'email' => $user['email'],
    'role'  => $user['role'],
];

// Vérifier si connecté
if (!Auth::check()) {
    header('Location: /login');
    exit;
}

// Vérifier si administrateur
if (!Auth::isAdmin()) {
    header('Location: /dashboard');
    exit;
}
```

**Flash messages :** messages temporaires stockés en session, affichés une fois puis supprimés automatiquement.
```php
$_SESSION['flash_success'] = 'Livre modifié avec succès.';
// Côté vue : lus et supprimés par pullFlash()
```

---

## 10. Pages Réalisées

| Page | URL | Méthode | Accès | Fonctionnalité |
|---|---|---|---|---|
| Accueil | `/` | GET | Public | Livres récents + statistiques |
| Catalogue | `/catalog` | GET | Public | Liste + filtres multi-critères |
| Connexion | `/login` | GET+POST | Public | Authentification |
| Inscription | `/register` | GET+POST | Public | Création de compte |
| À propos | `/about` | GET | Public | Présentation |
| Contact | `/contact` | GET | Public | Informations contact |
| Confidentialité | `/privacy-policy` | GET | Public | Politique |
| Tableau de bord | `/dashboard` | GET | Connecté | Livres + demandes + notifications |
| Ajouter un livre | `/add-book` | GET+POST | Connecté | Formulaire d'ajout (INSERT) |
| **Modifier un livre** | `/edit-book?id=X` | GET+POST | Propriétaire | Formulaire de modification (UPDATE) |
| Administration | `/admin` | GET | Admin | Statistiques + gestion |
| **Supprimer user** | `/admin/delete-user` | POST | Admin | Suppression physique (DELETE) |

---

## 11. Validation Et Sécurité

| Mesure | Implémentation |
|---|---|
| **Injection SQL** | Toutes les requêtes utilisent `prepare()` + `execute()` avec paramètres liés |
| **XSS** | Toutes les sorties HTML utilisent `htmlspecialchars()` |
| **Authentification** | Vérification `Auth::check()` avant toute action privée |
| **Autorisation** | Vérification `Auth::isAdmin()` pour les actions admin |
| **Propriété** | `WHERE id = ? AND owner_id = ?` pour les modifications |
| **Hash mot de passe** | `password_hash($pwd, PASSWORD_DEFAULT)` à l'inscription |
| **Session sécurisée** | `session_regenerate_id()` à la déconnexion |
| **Validation métier** | Cohérence niveau/classe/matière vérifiée avant INSERT |

---

## 12. Rapports Et Statistiques

L'application génère des rapports automatiques :

| Rapport | Requête SQL | Affiché sur |
|---|---|---|
| Nombre total d'utilisateurs | `SELECT COUNT(*) FROM users` | Admin dashboard |
| Nombre total de livres actifs | `SELECT COUNT(*) FROM books WHERE is_active=1` | Admin + accueil |
| Nombre d'échanges | `SELECT COUNT(*) FROM requests WHERE status='accepted'` | Admin + accueil |
| Économie totale estimée | `SELECT NVL(SUM(b.estimated_price),0) FROM exchanges e JOIN books b...` | Admin + accueil |
| Livres par niveau | `SELECT school_level, COUNT(*) ... GROUP BY school_level` | Admin |
| Matières les + demandées | `SELECT b.subject, COUNT(r.id) ... GROUP BY b.subject ORDER BY...` | Admin |

---

## 13. Hebergement En Ligne

L'application est accessible à l'adresse : **`https://bookcycle-tunisia.page.gd`**

| Compte | Email | Mot de passe | Rôle |
|---|---|---|---|
| Administrateur | `admin@bookcycle.tn` | `admin123` | Accès complet |
| Utilisateur | `ahmed@bookcycle.tn` | `user123` | Accès standard |

---

## 14. Points Forts Et Limites

### Points Forts
- Architecture MVC claire avec séparation stricte des responsabilités
- POO complète : encapsulation, héritage, singleton
- CRUD complet : SELECT (afficher), INSERT (ajouter), UPDATE (modifier), DELETE (supprimer)
- Recherche multi-critères avec jointures multi-tables
- `closeCursor()` utilisé systématiquement
- Requêtes préparées sur toutes les entrées utilisateur
- Déployé en ligne et démontrable

### Limites
- Pas d'upload d'image pour les livres
- Ergonomie mobile perfectible
- Pas de pagination sur les grandes listes

---

## Conclusion

La partie Web 2 de **BookCycle Tunisia** implémente toutes les fonctionnalités demandées : PDO, POO et MVC avec CRUD complet (affichage, ajout, modification, suppression), recherche multi-critères, gestion de sessions, multi-acteurs, et rapports statistiques.

L'application est accessible en ligne à `https://bookcycle-tunisia.page.gd` avec des comptes de démonstration opérationnels, et démontrable localement avec Oracle XE.
