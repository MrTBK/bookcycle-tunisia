# Guide D'Explication Du Code - BookCycle Tunisia

Ce document explique les choix techniques et les parties importantes du code
pour preparer la soutenance.

---

## 1. Connexion PDO (Database.php)

```php
// Patron Singleton : une seule connexion partagee par toute l'application.
self::$connection = new PDO($dsn, $user, $password, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_CASE               => PDO::CASE_LOWER,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
```

**Methodes PDO utilisees dans le projet :**
- `prepare($sql)` : preparer une requete avec des parametres (securite SQL)
- `execute($params)` : executer la requete en remplacant les `:param`
- `fetch()` : recuperer une seule ligne
- `fetchAll()` : recuperer toutes les lignes
- `fetchColumn()` : recuperer la premiere colonne (COUNT, SUM...)
- `closeCursor()` : liberer les ressources du curseur apres lecture
- `query($sql)` : requete simple sans parametre
- `exec($sql)` : modification directe (INSERT/UPDATE/DELETE)

---

## 2. Requetes Preparees (securite contre SQL injection)

```php
// VULNERABLE (ne jamais faire) :
$sql = "SELECT * FROM users WHERE email = '$email'";

// SECURISE (requis par le cours) :
$stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();
$stmt->closeCursor();
```

---

## 3. Architecture MVC

```
Navigateur → index.php (routeur) → Controller → Model → Vue
                                      ↓
                                   Database (PDO)
```

- **index.php** : lit l'URL et la methode HTTP, choisit le controleur et l'action
- **Controller** : valide les donnees, appelle le modele, passe les resultats a la vue
- **Model** : contient uniquement les requetes SQL
- **Vue** : affiche le HTML avec les donnees

---

## 4. Les 4 Operations CRUD

### CREATE (INSERT) - Ajouter un livre
```php
$stmt = $this->db->prepare(
    'INSERT INTO books (title, subject, ...) VALUES (:title, :subject, ...)'
);
$stmt->execute(['title' => $data['title'], ...]);
```

### READ (SELECT) - Lire des livres
```php
$stmt = $this->db->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll();
```

### UPDATE - Modifier un livre
```php
$stmt = $this->db->prepare(
    'UPDATE books SET condition_label = :condition, estimated_price = :price,
     updated_at = SYSDATE WHERE id = :id AND owner_id = :owner_id'
);
$stmt->execute([...]);
$stmt->closeCursor();
```

### DELETE - Supprimer un utilisateur (admin uniquement)
```php
$this->db->prepare('DELETE FROM notifications WHERE user_id = :id')->execute(['id' => $id]);
$this->db->prepare('DELETE FROM requests WHERE requester_id = :id')->execute(['id' => $id]);
$this->db->prepare('DELETE FROM users WHERE id = :id')->execute(['id' => $id]);
```

---

## 5. Sessions Et Authentification

```php
$_SESSION['user'] = ['id' => $user['id'], 'name' => $user['name'], 'role' => $user['role']];
if (!Auth::check()) { header('Location: /login'); exit; }
if (!Auth::isAdmin()) { header('Location: /dashboard'); exit; }
```

---

## 6. Formulaires GET et POST

```php
$level = $_GET['level'] ?? null;  // filtres catalogue dans l'URL
$title = $_POST['title'] ?? '';   // formulaire d'ajout de livre
```

---

## 7. Recherche Multi-Criteres

```php
$sql = 'SELECT ... FROM books b WHERE b.is_active = 1';
if (!empty($filters['level']))  { $sql .= ' AND b.school_level = :level'; }
if (!empty($filters['subject'])) { $sql .= ' AND b.subject LIKE :subject'; }
$stmt = $this->db->prepare($sql);
$stmt->execute($params);
```

---

## 8. Objets PL/SQL Importants

### Procedure `accept_request`
Accepte une demande, rejette les autres, met a jour le livre, notifie le demandeur.

### Trigger `trg_notify_owner_on_request`
AFTER INSERT sur `requests`. Cree automatiquement une notification pour le proprietaire.

### Trigger `trg_books_audit_statement`
**Trigger de niveau instruction** (sans FOR EACH ROW). Se declenche une fois par
ordre SQL, peu importe le nombre de lignes. Illustre la difference avec les triggers
de niveau ligne (FOR EACH ROW).

### Curseur explicite
```sql
DECLARE
    CURSOR c_books IS SELECT id, title FROM books WHERE status = 'available';
    v_book c_books%ROWTYPE;
BEGIN
    OPEN c_books;
    LOOP
        FETCH c_books INTO v_book;
        EXIT WHEN c_books%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE(v_book.title);
    END LOOP;
    CLOSE c_books;
END;
```

---

## 9. Points Cles Pour La Soutenance

1. **PDO** : prepare/execute + fetch/fetchAll + closeCursor
2. **MVC** : separation claire des responsabilites
3. **CRUD complet** : SELECT, INSERT, UPDATE, DELETE
4. **Requetes preparees** : securite SQL injection
5. **Sessions** : maintien de la connexion entre pages
6. **Triggers** : ligne vs instruction, BEFORE vs AFTER
7. **Curseurs** : implicite (SQL%ROWCOUNT) vs explicite (OPEN/FETCH/CLOSE)
8. **URL du site** : `https://bookcycle-tunisia.page.gd`
