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

**Module : Systèmes de Gestion de Bases de Données (SGBD)**

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
I    Introduction                                                              5

II   Schéma Relationnel de BookCycle Tunisia                                   6
     1)   Tables de Référence Académique                                       7
          1.1  Table SUBJECTS — Matières scolaires                             7
          1.2  Table SCHOOL_CLASSES — Classes scolaires                        8
          1.3  Table CLASS_SUBJECTS — Correspondances classe/matières          8
     2)   Tables Métier                                                        9
          2.1  Table USERS — Comptes utilisateurs                              9
          2.2  Table BOOKS — Livres publiés                                   10
          2.3  Table REQUESTS — Demandes d'échange                            11
          2.4  Table EXCHANGES — Échanges finalisés                           11
          2.5  Table NOTIFICATIONS — Notifications système                    12

III  Relations et Contraintes d'Intégrité                                    13
     1)   Diagramme des relations                                             13
     2)   Clés étrangères                                                     13
     3)   Contraintes CHECK                                                   14

IV   Séquences et Triggers d'Auto-incrément                                  15

V    Index de Performance                                                     16

VI   Vue de Reporting                                                         17

VII  Requêtes SQL Illustratives                                               18
     1)   Sélection et Projection                                             18
     2)   Jointures                                                           18
     3)   Agrégations                                                         19
     4)   Sous-requêtes                                                       19
     5)   Modifications                                                       19

VIII Objets PL/SQL                                                           20
     1)   Procédure add_notification                                          20
     2)   Procédure accept_request                                            20
     3)   Fonction count_books_by_user                                        22
     4)   Fonction calculate_money_saved                                      22
     5)   Trigger trg_books_updated_at                                        22
     6)   Trigger trg_book_exchange_log                                       23
     7)   Trigger trg_notify_owner_on_request                                 23
     8)   Trigger trg_validate_user_email                                     24
     9)   Curseurs de démonstration                                           24

IX   Éléments Oracle Spécifiques                                             25

X    Scripts et Ordre d'Exécution                                            26

XI   Conclusion                                                              27
```

---

&nbsp;

<div align="center">

## Chapitre I
### Introduction

</div>

---

La partie SGBD du projet **BookCycle Tunisia** a pour objectif de concevoir, implémenter et documenter la base de données Oracle qui supporte l'ensemble des fonctionnalités de la plateforme : gestion des utilisateurs, publication des livres scolaires, envoi et traitement des demandes d'échange, notifications et traçabilité des échanges.

Le projet exploite les capacités d'**Oracle Database Express Edition (XE)** à travers :

- **SQL DDL** : création des tables, contraintes, séquences, index et vue
- **SQL DML** : interrogation et modification des données (SELECT, INSERT, UPDATE, DELETE)
- **PL/SQL** : encapsulation de la logique métier en procédures, fonctions, triggers et curseurs

---

&nbsp;

<div align="center">

## Chapitre II
### Schéma Relationnel de BookCycle Tunisia

</div>

---

### Introduction

Au niveau de ce chapitre, nous présentons l'ensemble des tables qui composent la base de données de BookCycle Tunisia. Le schéma comprend **8 tables** réparties en deux catégories : tables de référence académique (données statiques) et tables métier (données applicatives).

### 1. Tables de Référence Académique

Ces trois tables stockent les données académiques tunisiennes (niveaux, classes, matières) utilisées pour alimenter les formulaires et valider les saisies. Elles remplacent les listes codées en dur dans le code PHP.

#### 1.1 Table `SUBJECTS` — Matières scolaires

Stocke la liste des matières scolaires disponibles sur la plateforme (Mathématiques, Arabe, Physique-Chimie, SVT, Informatique, etc.).

```sql
CREATE TABLE subjects (
    id         NUMBER PRIMARY KEY,
    name       VARCHAR2(120) NOT NULL,
    sort_order NUMBER DEFAULT 0 NOT NULL,
    is_active  NUMBER(1) DEFAULT 1 NOT NULL,
    CONSTRAINT uq_subjects_name   UNIQUE (name),
    CONSTRAINT chk_subjects_active CHECK (is_active IN (0, 1))
);
```

| Colonne | Type | Description |
|---|---|---|
| `id` | NUMBER | Identifiant unique (séquence + trigger) |
| `name` | VARCHAR2(120) | Nom de la matière |
| `sort_order` | NUMBER | Ordre d'affichage dans les formulaires |
| `is_active` | NUMBER(1) | 1 = matière visible, 0 = masquée |

#### 1.2 Table `SCHOOL_CLASSES` — Classes scolaires

Stocke les classes par niveau scolaire (6 classes Primaire, 3 Collège, 12 Lycée).

```sql
CREATE TABLE school_classes (
    id           NUMBER PRIMARY KEY,
    school_level VARCHAR2(40) NOT NULL,
    class_name   VARCHAR2(60) NOT NULL,
    sort_order   NUMBER DEFAULT 0 NOT NULL,
    is_active    NUMBER(1) DEFAULT 1 NOT NULL,
    CONSTRAINT uq_school_classes UNIQUE (school_level, class_name),
    CONSTRAINT chk_school_classes_level
        CHECK (school_level IN ('Primaire', 'College', 'Lycee')),
    CONSTRAINT chk_school_classes_active CHECK (is_active IN (0, 1))
);
```

| Colonne | Description |
|---|---|
| `school_level` | Niveau : `Primaire`, `College`, `Lycee` |
| `class_name` | Nom de la classe (ex : `9eme annee`, `bac math`) |

#### 1.3 Table `CLASS_SUBJECTS` — Correspondances classe ↔ matières

Définit quelles matières sont disponibles pour chaque classe. Par exemple, `bac info` propose Informatique, Programmation, Mathématiques, Physique-Chimie, Arabe, Français, Anglais, Philosophie.

```sql
CREATE TABLE class_subjects (
    id         NUMBER PRIMARY KEY,
    class_id   NUMBER NOT NULL REFERENCES school_classes(id),
    subject_id NUMBER NOT NULL REFERENCES subjects(id),
    sort_order NUMBER DEFAULT 0 NOT NULL,
    is_active  NUMBER(1) DEFAULT 1 NOT NULL,
    CONSTRAINT uq_class_subjects UNIQUE (class_id, subject_id)
);
```

### 2. Tables Métier

#### 2.1 Table `USERS` — Comptes utilisateurs

Stocke tous les comptes de la plateforme BookCycle Tunisia (visiteurs inscrits et administrateurs).

```sql
CREATE TABLE users (
    id         NUMBER PRIMARY KEY,
    name       VARCHAR2(120) NOT NULL,
    email      VARCHAR2(160) NOT NULL UNIQUE,
    password   VARCHAR2(255) NOT NULL,
    phone      VARCHAR2(30)  NOT NULL,
    role       VARCHAR2(20)  DEFAULT 'user' NOT NULL,
    is_active  NUMBER(1)     DEFAULT 1 NOT NULL,
    created_at DATE          DEFAULT SYSDATE NOT NULL,
    CONSTRAINT chk_users_role   CHECK (role IN ('admin', 'user')),
    CONSTRAINT chk_users_active CHECK (is_active IN (0, 1))
);
```

| Colonne | Description |
|---|---|
| `email` | Identifiant de connexion (unique) |
| `password` | Mot de passe hashé via `password_hash()` PHP |
| `role` | `user` (par défaut) ou `admin` |
| `is_active` | 1 = compte actif, 0 = compte bloqué par l'admin |

#### 2.2 Table `BOOKS` — Livres publiés

Stocke tous les livres scolaires publiés sur la plateforme BookCycle Tunisia.

```sql
CREATE TABLE books (
    id              NUMBER PRIMARY KEY,
    title           VARCHAR2(180) NOT NULL,
    subject         VARCHAR2(120) NOT NULL,
    class_name      VARCHAR2(60)  DEFAULT 'Non precise' NOT NULL,
    school_level    VARCHAR2(40)  NOT NULL,
    condition_label VARCHAR2(40)  NOT NULL,
    estimated_price NUMBER(10,2)  DEFAULT 0 NOT NULL,
    description     VARCHAR2(1000),
    owner_id        NUMBER NOT NULL REFERENCES users(id),
    status          VARCHAR2(20)  DEFAULT 'available' NOT NULL,
    is_active       NUMBER(1)     DEFAULT 1 NOT NULL,
    created_at      DATE          DEFAULT SYSDATE NOT NULL,
    updated_at      DATE          DEFAULT SYSDATE NOT NULL,
    CONSTRAINT chk_books_status CHECK (status IN ('available','reserved','exchanged')),
    CONSTRAINT chk_books_active CHECK (is_active IN (0, 1))
);
```

| Colonne | Description |
|---|---|
| `title` | Généré automatiquement : `Matière - Classe - Niveau` |
| `condition_label` | État du livre : Neuf / Bon / Usagé |
| `estimated_price` | Prix estimé en dinars tunisiens |
| `status` | `available` → `reserved` (demande acceptée) → `exchanged` |
| `is_active` | 0 = masqué par l'administrateur |

#### 2.3 Table `REQUESTS` — Demandes d'échange

Enregistre chaque demande envoyée par un utilisateur pour un livre disponible.

```sql
CREATE TABLE requests (
    id           NUMBER PRIMARY KEY,
    book_id      NUMBER NOT NULL REFERENCES books(id),
    requester_id NUMBER NOT NULL REFERENCES users(id),
    status       VARCHAR2(20) DEFAULT 'pending' NOT NULL,
    meeting_note VARCHAR2(1000),
    request_date DATE DEFAULT SYSDATE NOT NULL,
    CONSTRAINT chk_requests_status
        CHECK (status IN ('pending', 'accepted', 'rejected'))
);
```

| Colonne | Description |
|---|---|
| `status` | `pending` (en attente) → `accepted` ou `rejected` |
| `meeting_note` | Note de rendez-vous laissée par le propriétaire lors de l'acceptation |

#### 2.4 Table `EXCHANGES` — Échanges finalisés

Enregistre automatiquement (via trigger) chaque échange finalisé quand un livre passe au statut `exchanged`.

```sql
CREATE TABLE exchanges (
    id            NUMBER PRIMARY KEY,
    book_id       NUMBER NOT NULL REFERENCES books(id),
    owner_id      NUMBER NOT NULL REFERENCES users(id),
    receiver_id   NUMBER NOT NULL REFERENCES users(id),
    exchange_date DATE DEFAULT SYSDATE NOT NULL,
    status        VARCHAR2(20) DEFAULT 'completed' NOT NULL
);
```

#### 2.5 Table `NOTIFICATIONS` — Notifications système

Stocke toutes les notifications envoyées aux utilisateurs de BookCycle Tunisia (nouvelles demandes, demandes acceptées/refusées, messages admin).

```sql
CREATE TABLE notifications (
    id          NUMBER PRIMARY KEY,
    user_id     NUMBER NOT NULL REFERENCES users(id),
    sender_name VARCHAR2(120) DEFAULT 'Systeme' NOT NULL,
    message     VARCHAR2(1000) NOT NULL,
    is_read     NUMBER(1) DEFAULT 0 NOT NULL,
    created_at  DATE DEFAULT SYSDATE NOT NULL,
    CONSTRAINT chk_notifications_read CHECK (is_read IN (0, 1))
);
```

---

&nbsp;

<div align="center">

## Chapitre III
### Relations et Contraintes d'Intégrité

</div>

---

### 1. Diagramme des relations

```
subjects ◄──────── class_subjects ────────► school_classes
                                                    │
                                              books ◄──── users (owner_id)
                                                │              │
                                          requests ◄───────────┘ (requester_id)
                                                │
                                          exchanges ◄── users (owner_id, receiver_id)
                                                │
                                       notifications ◄── users (user_id)
```

### 2. Clés étrangères

| Table source | Colonne | Table cible | Colonne |
|---|---|---|---|
| `books` | `owner_id` | `users` | `id` |
| `requests` | `book_id` | `books` | `id` |
| `requests` | `requester_id` | `users` | `id` |
| `exchanges` | `book_id` | `books` | `id` |
| `exchanges` | `owner_id` | `users` | `id` |
| `exchanges` | `receiver_id` | `users` | `id` |
| `notifications` | `user_id` | `users` | `id` |
| `class_subjects` | `class_id` | `school_classes` | `id` |
| `class_subjects` | `subject_id` | `subjects` | `id` |

### 3. Contraintes CHECK

| Table | Colonne | Valeurs autorisées |
|---|---|---|
| `users` | `role` | `'admin'`, `'user'` |
| `users` | `is_active` | `0`, `1` |
| `school_classes` | `school_level` | `'Primaire'`, `'College'`, `'Lycee'` |
| `books` | `status` | `'available'`, `'reserved'`, `'exchanged'` |
| `books` | `is_active` | `0`, `1` |
| `requests` | `status` | `'pending'`, `'accepted'`, `'rejected'` |
| `notifications` | `is_read` | `0`, `1` |

---

&nbsp;

<div align="center">

## Chapitre IV
### Séquences et Triggers d'Auto-incrément

</div>

---

Oracle XE ne dispose pas d'`IDENTITY` natif. Les identifiants de BookCycle Tunisia sont générés via des paires **séquence + trigger BEFORE INSERT** sur toutes les tables.

**Exemple pour la table `books` :**

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
/
```

| Séquence | Table |
|---|---|
| `seq_users` | `users` |
| `seq_subjects` | `subjects` |
| `seq_school_classes` | `school_classes` |
| `seq_class_subjects` | `class_subjects` |
| `seq_books` | `books` |
| `seq_requests` | `requests` |
| `seq_exchanges` | `exchanges` |
| `seq_notifications` | `notifications` |

---

&nbsp;

<div align="center">

## Chapitre V
### Index de Performance

</div>

---

Dix index accélèrent les requêtes les plus fréquentes de BookCycle Tunisia.

```sql
CREATE INDEX idx_subjects_active        ON subjects(is_active, sort_order);
CREATE INDEX idx_school_classes_level   ON school_classes(school_level, sort_order);
CREATE INDEX idx_class_subjects_class   ON class_subjects(class_id, sort_order);
CREATE INDEX idx_class_subjects_subject ON class_subjects(subject_id, sort_order);
CREATE INDEX idx_books_owner            ON books(owner_id);
CREATE INDEX idx_books_subject          ON books(subject);
CREATE INDEX idx_books_level            ON books(school_level);
CREATE INDEX idx_requests_book          ON requests(book_id);
CREATE INDEX idx_requests_requester     ON requests(requester_id);
CREATE INDEX idx_notifications_user     ON notifications(user_id);
```

| Index | Requête optimisée |
|---|---|
| `idx_books_level` | Filtrage du catalogue par niveau scolaire |
| `idx_books_subject` | Filtrage du catalogue par matière |
| `idx_books_owner` | Affichage des livres d'un utilisateur |
| `idx_requests_book` | Demandes liées à un livre |
| `idx_notifications_user` | Notifications d'un utilisateur |

---

&nbsp;

<div align="center">

## Chapitre VI
### Vue de Reporting

</div>

---

La vue `v_book_overview` centralise les informations des livres et de leurs propriétaires pour les rapports et le tableau de bord admin.

```sql
CREATE OR REPLACE VIEW v_book_overview AS
SELECT
    b.id              AS book_id,
    b.title,
    b.subject,
    b.class_name,
    b.school_level,
    b.condition_label,
    b.estimated_price,
    b.status,
    u.name            AS owner_name,
    u.email           AS owner_email,
    b.created_at
FROM books b
JOIN users u ON u.id = b.owner_id;
```

---

&nbsp;

<div align="center">

## Chapitre VII
### Requêtes SQL Illustratives

</div>

---

### 1. Sélection et Projection

```sql
-- Livres disponibles sur BookCycle Tunisia
SELECT * FROM books WHERE status = 'available' AND is_active = 1;

-- Livres de Lycée en Physique-Chimie
SELECT * FROM books
WHERE school_level = 'Lycee' AND subject LIKE '%Phys%';
```

### 2. Jointures

```sql
-- Livres avec le nom du propriétaire
SELECT b.title, b.subject, u.name AS owner_name
FROM books b JOIN users u ON u.id = b.owner_id;

-- Demandes avec livre et demandeur
SELECT r.id, b.title, u.name AS requester_name, r.status
FROM requests r
JOIN books b ON b.id = r.book_id
JOIN users u ON u.id = r.requester_id;
```

### 3. Agrégations

```sql
-- Livres par niveau scolaire
SELECT school_level, COUNT(*) AS total_books
FROM books GROUP BY school_level;

-- Économie totale estimée par BookCycle Tunisia
SELECT COUNT(*) AS total_exchanges,
       NVL(SUM(b.estimated_price), 0) AS money_saved_dt
FROM exchanges e JOIN books b ON b.id = e.book_id;
```

### 4. Sous-requêtes

```sql
-- Utilisateurs ayant publié au moins un livre
SELECT name, email FROM users
WHERE id IN (SELECT owner_id FROM books);
```

### 5. Modifications

```sql
-- Réserver un livre après acceptation d'une demande
UPDATE books SET status = 'reserved', updated_at = SYSDATE WHERE id = :id;

-- Masquer un livre (modération admin)
UPDATE books SET is_active = 0, updated_at = SYSDATE WHERE id = :id;
```

---

&nbsp;

<div align="center">

## Chapitre VIII
### Objets PL/SQL

</div>

---

### 1. Procédure `add_notification`

Insère une notification pour un utilisateur de BookCycle Tunisia. Appelée automatiquement lors des événements : nouvelle demande, demande acceptée/refusée, message admin.

```sql
CREATE OR REPLACE PROCEDURE add_notification (
    p_user_id IN users.id%TYPE,
    p_message IN notifications.message%TYPE
) IS
BEGIN
    INSERT INTO notifications (user_id, message, is_read, created_at)
    VALUES (p_user_id, p_message, 0, SYSDATE);
END;
/
```

### 2. Procédure `accept_request`

Accepte une demande de manière **atomique** : met à jour la demande acceptée, rejette toutes les autres demandes en attente pour le même livre, passe le livre en statut `reserved`, et notifie le demandeur.

```sql
CREATE OR REPLACE PROCEDURE accept_request (
    p_request_id  IN requests.id%TYPE,
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

    UPDATE books SET status = 'reserved', updated_at = SYSDATE
    WHERE id = v_book_id;

    add_notification(v_requester_id,
        'Votre demande pour le livre "' || v_title || '" a ete acceptee.');
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        DBMS_OUTPUT.PUT_LINE('Demande introuvable.');
END;
/
```

### 3. Fonction `count_books_by_user`

Retourne le nombre de livres actifs publiés par un utilisateur de BookCycle Tunisia.

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
/
```

### 4. Fonction `calculate_money_saved`

Calcule l'économie totale estimée générée par tous les échanges finalisés sur BookCycle Tunisia.

```sql
CREATE OR REPLACE FUNCTION calculate_money_saved RETURN NUMBER IS
    v_total NUMBER;
BEGIN
    SELECT NVL(SUM(b.estimated_price), 0) INTO v_total
    FROM exchanges e JOIN books b ON b.id = e.book_id;
    RETURN v_total;
END;
/
```

### 5. Trigger `trg_books_updated_at`

Met à jour automatiquement `updated_at` à chaque modification d'un livre, sans intervention du code PHP.

```sql
CREATE OR REPLACE TRIGGER trg_books_updated_at
BEFORE UPDATE ON books
FOR EACH ROW
BEGIN
    :NEW.updated_at := SYSDATE;
END;
/
```

### 6. Trigger `trg_book_exchange_log`

Inscrit automatiquement un enregistrement dans `exchanges` quand un livre passe au statut `exchanged`.

```sql
CREATE OR REPLACE TRIGGER trg_book_exchange_log
AFTER UPDATE OF status ON books
FOR EACH ROW
WHEN (NEW.status = 'exchanged' AND OLD.status <> 'exchanged')
DECLARE
    v_receiver_id requests.requester_id%TYPE;
BEGIN
    SELECT requester_id INTO v_receiver_id
    FROM requests WHERE book_id = :NEW.id AND status = 'accepted' AND ROWNUM = 1;

    INSERT INTO exchanges (book_id, owner_id, receiver_id, exchange_date, status)
    VALUES (:NEW.id, :NEW.owner_id, v_receiver_id, SYSDATE, 'completed');
EXCEPTION
    WHEN NO_DATA_FOUND THEN NULL;
END;
/
```

### 7. Trigger `trg_notify_owner_on_request`

Notifie automatiquement le propriétaire d'un livre dès qu'une nouvelle demande est insérée.

```sql
CREATE OR REPLACE TRIGGER trg_notify_owner_on_request
AFTER INSERT ON requests
FOR EACH ROW
DECLARE
    v_owner_id books.owner_id%TYPE;
    v_title    books.title%TYPE;
BEGIN
    SELECT owner_id, title INTO v_owner_id, v_title
    FROM books WHERE id = :NEW.book_id;

    INSERT INTO notifications (user_id, sender_name, message, is_read, created_at)
    VALUES (v_owner_id, 'Systeme',
        'Un utilisateur a demande votre livre "' || v_title || '".',
        0, SYSDATE);
EXCEPTION
    WHEN NO_DATA_FOUND THEN NULL;
END;
/
```

### 8. Trigger `trg_validate_user_email`

Bloque l'insertion d'un utilisateur si son email ne contient pas le caractère `@`.

```sql
CREATE OR REPLACE TRIGGER trg_validate_user_email
BEFORE INSERT ON users
FOR EACH ROW
BEGIN
    IF INSTR(:NEW.email, '@') = 0 THEN
        RAISE_APPLICATION_ERROR(-20001,
            'Email invalide : le caractere @ est obligatoire.');
    END IF;
END;
/
```

### 9. Curseurs de démonstration

**Curseur implicite (`SQL%ROWCOUNT`) :**
```sql
BEGIN
    UPDATE notifications SET is_read = 1
    WHERE user_id = 2 AND is_read = 0;
    DBMS_OUTPUT.PUT_LINE('Notifications mises a jour : ' || SQL%ROWCOUNT);
    ROLLBACK;
END;
/
```

**Curseur explicite — livres disponibles :**
```sql
DECLARE
    CURSOR c_available_books IS
        SELECT id, title, subject FROM books
        WHERE status = 'available' ORDER BY created_at DESC;
    v_book c_available_books%ROWTYPE;
BEGIN
    OPEN c_available_books;
    LOOP
        FETCH c_available_books INTO v_book;
        EXIT WHEN c_available_books%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE('Livre #' || v_book.id || ' : ' || v_book.title);
    END LOOP;
    CLOSE c_available_books;
END;
/
```

---

&nbsp;

<div align="center">

## Chapitre IX
### Éléments Oracle Spécifiques

</div>

---

| Élément | Utilisation dans BookCycle Tunisia |
|---|---|
| `%TYPE` | Typage des variables PL/SQL d'après les colonnes (ex. : `users.id%TYPE`) |
| `%ROWTYPE` | Typage d'une variable curseur selon la structure du curseur |
| `SQL%ROWCOUNT` | Comptage des lignes affectées après un UPDATE |
| `DBMS_OUTPUT.PUT_LINE` | Messages de débogage dans SQL Developer |
| `NVL` | `NVL(SUM(estimated_price), 0)` pour éviter les NULL |
| `SYSDATE` | Horodatage des créations et modifications |
| `ROWNUM` | Limitation à 1 résultat dans les triggers |
| `RAISE_APPLICATION_ERROR` | Erreur personnalisée pour validation email |
| `EXECUTE IMMEDIATE` | Suppression conditionnelle des utilisateurs Oracle |
| Séquence + trigger | Pattern d'auto-incrément sur les 8 tables |

---

&nbsp;

<div align="center">

## Chapitre X
### Scripts et Ordre d'Exécution

</div>

---

| Fichier | Description |
|---|---|
| `01_users_privileges.sql` | Création de `bookcycle_app` et `bookcycle_report` avec leurs droits Oracle |
| `02_schema.sql` | Création des 8 tables, contraintes, 8 séquences, 8 triggers PK, 10 index, vue |
| `03_sample_data.sql` | Données de démonstration : 4 utilisateurs, 19 matières, 25 classes, livres, demandes |
| `04_queries.sql` | Requêtes SQL illustratives pour TP/soutenance |
| `05_plsql_objects.sql` | Procédures, fonctions et blocs PL/SQL de démonstration |
| `06_triggers.sql` | Triggers métier : updated_at, exchange log, notify owner, validate email, audit |

**Ordre d'exécution :**
```
1. 01_users_privileges.sql   (connecté SYSTEM)
2. 02_schema.sql             (connecté bookcycle_app)
3. 03_sample_data.sql        (connecté bookcycle_app)
4. 05_plsql_objects.sql      (connecté bookcycle_app)
5. 06_triggers.sql           (connecté bookcycle_app)
6. 04_queries.sql            (soutenance / démonstration)
```

**Comptes de démonstration :**
| Email | Mot de passe | Rôle |
|---|---|---|
| `admin@bookcycle.tn` | `admin123` | Administrateur |
| `ahmed@bookcycle.tn` | `user123` | Utilisateur |

---

&nbsp;

<div align="center">

## Conclusion

</div>

---

La base de données Oracle de **BookCycle Tunisia** est robuste, normalisée et complète. Elle supporte l'ensemble des fonctionnalités de la plateforme grâce à :

- Un schéma de 8 tables avec contraintes d'intégrité complètes
- Des tables de référence académique dynamiques (matières, classes, niveaux) éliminant les listes codées en dur
- Des objets PL/SQL encapsulant la logique métier critique (acceptation atomique des demandes, traçabilité automatique des échanges, notifications automatiques)
- Une démonstration de l'ensemble des concepts Oracle : séquences, triggers BEFORE/AFTER/statement-level, procédures, fonctions, vues, curseurs implicites et explicites, `%TYPE`, `%ROWTYPE`, `SQL%ROWCOUNT`

---

*Rapport réalisé dans le cadre du Projet Intégré — Licence 2 Big Data et Intelligence Artificielle — ESEN — Université de la Manouba — 2025/2026*
