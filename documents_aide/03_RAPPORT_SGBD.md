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

II   Choix du SGBD                                                            6
     1)   Oracle Database Express Edition (XE)                                6
     2)   Configuration de Connexion                                          6

III  Schéma Relationnel                                                       7
     1)   Tables de Référence Académique                                      7
          1.1  Table SUBJECTS — Matières Scolaires                            7
          1.2  Table SCHOOL_CLASSES — Classes Scolaires                       8
          1.3  Table CLASS_SUBJECTS — Correspondances Classe/Matières         8
     2)   Tables Métier Principales                                           9
          2.1  Table USERS — Utilisateurs                                     9
          2.2  Table BOOKS — Livres                                          10
          2.3  Table REQUESTS — Demandes                                     11
          2.4  Table EXCHANGES — Échanges Finalisés                          11
          2.5  Table NOTIFICATIONS — Notifications                           12

IV   Relations et Contraintes d'Intégrité                                   13
     1)   Diagramme des Relations                                            13
     2)   Récapitulatif des Clés Étrangères                                  13
     3)   Valeurs Contrôlées par Contraintes CHECK                           14

V    Séquences et Triggers d'Auto-incrément                                 15

VI   Index de Performance                                                    16

VII  Vue de Reporting                                                        17

VIII Requêtes SQL Illustratives                                              18
     1)   Sélection et Projection                                            18
     2)   Jointures                                                          18
     3)   Agrégations                                                        19
     4)   Sous-requêtes                                                      19
     5)   Modifications                                                      19

IX   Objets PL/SQL                                                          20
     1)   Procédure add_notification                                         20
     2)   Procédure accept_request                                           20
     3)   Fonction count_books_by_user                                       22
     4)   Fonction calculate_money_saved                                     22
     5)   Trigger trg_books_updated_at                                       23
     6)   Trigger trg_book_exchange_log                                      23
     7)   Curseurs et Blocs PL/SQL de Démonstration                         24

X    Éléments Oracle Spécifiques                                            25

XI   Scripts Fournis et Ordre d'Exécution                                   26

XII  Conclusion                                                             27
```

---

&nbsp;

<div align="center">

## Chapitre I
### Introduction

</div>

---

La partie SGBD du projet **BookCycle Tunisia** a pour objectif de concevoir, implémenter et documenter une base de données Oracle capable de gérer l'ensemble des entités de la plateforme : utilisateurs, livres scolaires, demandes, échanges et notifications.

Le projet exploite pleinement les capacités d'**Oracle XE** :

- **SQL DDL** pour la définition du schéma (tables, contraintes, index, vues)
- **SQL DML** pour la manipulation des données (SELECT, INSERT, UPDATE, DELETE)
- **PL/SQL** pour l'encapsulation de la logique métier (procédures, fonctions, triggers, curseurs)

Ce rapport présente chaque composant de la base de données avec son rôle, sa structure et les extraits SQL correspondants.

---

&nbsp;

<div align="center">

## Chapitre II
### Choix du SGBD

</div>

---

### 1. Oracle Database Express Edition (XE)

Le SGBD retenu est **Oracle XE**, conformément aux objectifs du module SGBD. Ce choix est justifié par :

| Critère | Justification |
|---|---|
| **Robustesse** | Oracle est un SGBD professionnel de référence, largement utilisé en entreprise |
| **Richesse PL/SQL** | Support complet des procédures stockées, fonctions, triggers, curseurs |
| **Contraintes d'intégrité** | Gestion avancée des clés étrangères, contraintes CHECK, UNIQUE |
| **Séquences** | Mécanisme propre Oracle pour la génération d'identifiants |
| **Compatibilité pédagogique** | Aligné avec le contenu du module SGBD enseigné à l'ESEN |

### 2. Configuration de Connexion

L'application PHP se connecte à Oracle via **PDO_OCI** avec les paramètres suivants :

```
DSN         : oci:dbname=//localhost:1521/XE;charset=AL32UTF8
Utilisateur : bookcycle_app
Mot de passe: BookCycle2026
```

Un utilisateur de reporting à droits limités (`bookcycle_report`) est également créé pour les accès en lecture seule.

---

&nbsp;

<div align="center">

## Chapitre III
### Schéma Relationnel

</div>

---

### Introduction

Au niveau de ce chapitre, nous présentons l'ensemble des tables qui composent le schéma de la base de données. Le schéma comprend **8 tables** réparties en trois catégories : tables de référence académique, tables métier principales, et table de traçabilité.

### 1. Tables de Référence Académique

Ces tables centralisent les données de référence pour les niveaux scolaires, les classes et les matières. Elles remplacent les listes codées en dur dans le code PHP, garantissant la cohérence des données à la source.

#### 1.1 Table `SUBJECTS` — Matières Scolaires

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
| `name` | VARCHAR2(120) | Nom de la matière (ex. : Mathématiques, Physique) |
| `sort_order` | NUMBER | Ordre d'affichage dans les formulaires |
| `is_active` | NUMBER(1) | 1 = matière visible, 0 = masquée |

#### 1.2 Table `SCHOOL_CLASSES` — Classes Scolaires

```sql
CREATE TABLE school_classes (
    id           NUMBER PRIMARY KEY,
    school_level VARCHAR2(40) NOT NULL,
    class_name   VARCHAR2(60) NOT NULL,
    sort_order   NUMBER DEFAULT 0 NOT NULL,
    is_active    NUMBER(1) DEFAULT 1 NOT NULL,
    CONSTRAINT uq_school_classes      UNIQUE (school_level, class_name),
    CONSTRAINT chk_school_classes_level
        CHECK (school_level IN ('Primaire', 'College', 'Lycee')),
    CONSTRAINT chk_school_classes_active CHECK (is_active IN (0, 1))
);
```

| Colonne | Type | Description |
|---|---|---|
| `id` | NUMBER | Identifiant unique |
| `school_level` | VARCHAR2(40) | Niveau scolaire : `Primaire`, `College`, `Lycee` |
| `class_name` | VARCHAR2(60) | Nom de la classe (ex. : 6ème, 1ère Année Lycée) |
| `sort_order` | NUMBER | Ordre d'affichage |
| `is_active` | NUMBER(1) | Statut d'activation |

#### 1.3 Table `CLASS_SUBJECTS` — Correspondances Classe ↔ Matières

```sql
CREATE TABLE class_subjects (
    id         NUMBER PRIMARY KEY,
    class_id   NUMBER NOT NULL,
    subject_id NUMBER NOT NULL,
    sort_order NUMBER DEFAULT 0 NOT NULL,
    is_active  NUMBER(1) DEFAULT 1 NOT NULL,
    CONSTRAINT fk_class_subjects_class
        FOREIGN KEY (class_id) REFERENCES school_classes(id),
    CONSTRAINT fk_class_subjects_subject
        FOREIGN KEY (subject_id) REFERENCES subjects(id),
    CONSTRAINT uq_class_subjects UNIQUE (class_id, subject_id),
    CONSTRAINT chk_class_subjects_active CHECK (is_active IN (0, 1))
);
```

Cette table d'association définit quelles matières sont disponibles pour chaque classe. Elle permet au formulaire d'ajout de livre de charger dynamiquement les matières autorisées selon la classe sélectionnée.

### 2. Tables Métier Principales

#### 2.1 Table `USERS` — Utilisateurs

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
| `id` | Identifiant unique |
| `name` | Nom complet de l'utilisateur |
| `email` | Adresse email unique (identifiant de connexion) |
| `password` | Mot de passe hashé |
| `phone` | Numéro de téléphone |
| `role` | `user` (par défaut) ou `admin` |
| `is_active` | 1 = compte actif, 0 = compte désactivé |
| `created_at` | Date de création du compte |

#### 2.2 Table `BOOKS` — Livres

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
    owner_id        NUMBER NOT NULL,
    status          VARCHAR2(20)  DEFAULT 'available' NOT NULL,
    is_active       NUMBER(1)     DEFAULT 1 NOT NULL,
    created_at      DATE          DEFAULT SYSDATE NOT NULL,
    updated_at      DATE          DEFAULT SYSDATE NOT NULL,
    CONSTRAINT fk_books_owner  FOREIGN KEY (owner_id) REFERENCES users(id),
    CONSTRAINT chk_books_status CHECK (status IN ('available','reserved','exchanged')),
    CONSTRAINT chk_books_active CHECK (is_active IN (0, 1))
);
```

| Colonne | Description |
|---|---|
| `title` | Titre généré automatiquement (matière + classe + niveau) |
| `subject` | Matière scolaire |
| `class_name` | Classe cible |
| `school_level` | Niveau scolaire |
| `condition_label` | État du livre (ex. : Très bon état, Bon état) |
| `estimated_price` | Prix estimé du livre en dinars tunisiens |
| `description` | Description libre optionnelle |
| `owner_id` | Référence vers l'utilisateur propriétaire |
| `status` | `available` / `reserved` / `exchanged` |
| `is_active` | Suppression logique (0 = masqué par admin) |
| `updated_at` | Mis à jour automatiquement par trigger |

#### 2.3 Table `REQUESTS` — Demandes

```sql
CREATE TABLE requests (
    id           NUMBER PRIMARY KEY,
    book_id      NUMBER NOT NULL,
    requester_id NUMBER NOT NULL,
    status       VARCHAR2(20) DEFAULT 'pending' NOT NULL,
    meeting_note VARCHAR2(1000),
    request_date DATE DEFAULT SYSDATE NOT NULL,
    CONSTRAINT fk_requests_book      FOREIGN KEY (book_id) REFERENCES books(id),
    CONSTRAINT fk_requests_requester FOREIGN KEY (requester_id) REFERENCES users(id),
    CONSTRAINT chk_requests_status
        CHECK (status IN ('pending', 'accepted', 'rejected'))
);
```

#### 2.4 Table `EXCHANGES` — Échanges Finalisés

```sql
CREATE TABLE exchanges (
    id            NUMBER PRIMARY KEY,
    book_id       NUMBER NOT NULL,
    owner_id      NUMBER NOT NULL,
    receiver_id   NUMBER NOT NULL,
    exchange_date DATE DEFAULT SYSDATE NOT NULL,
    status        VARCHAR2(20) DEFAULT 'completed' NOT NULL,
    CONSTRAINT fk_exchanges_book     FOREIGN KEY (book_id)     REFERENCES books(id),
    CONSTRAINT fk_exchanges_owner    FOREIGN KEY (owner_id)    REFERENCES users(id),
    CONSTRAINT fk_exchanges_receiver FOREIGN KEY (receiver_id) REFERENCES users(id)
);
```

#### 2.5 Table `NOTIFICATIONS` — Notifications

```sql
CREATE TABLE notifications (
    id          NUMBER PRIMARY KEY,
    user_id     NUMBER NOT NULL,
    sender_name VARCHAR2(120) DEFAULT 'Systeme' NOT NULL,
    message     VARCHAR2(1000) NOT NULL,
    is_read     NUMBER(1) DEFAULT 0 NOT NULL,
    created_at  DATE DEFAULT SYSDATE NOT NULL,
    CONSTRAINT fk_notifications_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT chk_notifications_read CHECK (is_read IN (0, 1))
);
```

---

&nbsp;

<div align="center">

## Chapitre IV
### Relations et Contraintes d'Intégrité

</div>

---

### 1. Diagramme des Relations

```
subjects ◄──────── class_subjects ────────► school_classes
                                                    │
                                                    │ (validation niveau/classe)
                                                    │
                                              books ◄──── users (owner_id)
                                                │              │
                                          requests ◄───────────┘ (requester_id)
                                                │
                                          exchanges ◄── users (owner_id, receiver_id)
                                                │
                                       notifications ◄── users (user_id)
```

### 2. Récapitulatif des Clés Étrangères

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

### 3. Valeurs Contrôlées par Contraintes CHECK

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

## Chapitre V
### Séquences et Triggers d'Auto-incrément

</div>

---

Oracle XE ne dispose pas d'un type `IDENTITY` natif dans les versions utilisées. Les identifiants sont générés via une paire **séquence + trigger BEFORE INSERT**, appliquée uniformément sur toutes les tables.

**Exemple pour la table `books` :**

```sql
-- Séquence
CREATE SEQUENCE seq_books START WITH 1 INCREMENT BY 1 NOCACHE;

-- Trigger d'auto-incrément
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

**Séquences définies dans le projet :**

| Séquence | Table | Trigger associé |
|---|---|---|
| `seq_users` | `users` | `trg_users_pk` |
| `seq_subjects` | `subjects` | `trg_subjects_pk` |
| `seq_school_classes` | `school_classes` | `trg_school_classes_pk` |
| `seq_class_subjects` | `class_subjects` | `trg_class_subjects_pk` |
| `seq_books` | `books` | `trg_books_pk` |
| `seq_requests` | `requests` | `trg_requests_pk` |
| `seq_exchanges` | `exchanges` | `trg_exchanges_pk` |
| `seq_notifications` | `notifications` | `trg_notifications_pk` |

---

&nbsp;

<div align="center">

## Chapitre VI
### Index de Performance

</div>

---

Dix index sont définis pour accélérer les requêtes les plus fréquentes de l'application.

```sql
-- Tables de référence
CREATE INDEX idx_subjects_active        ON subjects(is_active, sort_order);
CREATE INDEX idx_school_classes_level   ON school_classes(school_level, sort_order);
CREATE INDEX idx_class_subjects_class   ON class_subjects(class_id, sort_order);
CREATE INDEX idx_class_subjects_subject ON class_subjects(subject_id, sort_order);

-- Table books
CREATE INDEX idx_books_owner   ON books(owner_id);
CREATE INDEX idx_books_subject ON books(subject);
CREATE INDEX idx_books_level   ON books(school_level);

-- Table requests
CREATE INDEX idx_requests_book      ON requests(book_id);
CREATE INDEX idx_requests_requester ON requests(requester_id);

-- Table notifications
CREATE INDEX idx_notifications_user ON notifications(user_id);
```

| Index | Requête optimisée |
|---|---|
| `idx_books_level` | Filtrage du catalogue par niveau scolaire |
| `idx_books_subject` | Filtrage du catalogue par matière |
| `idx_books_owner` | Affichage des livres d'un utilisateur dans son dashboard |
| `idx_requests_book` | Récupération des demandes liées à un livre |
| `idx_notifications_user` | Lecture des notifications d'un utilisateur |

---

&nbsp;

<div align="center">

## Chapitre VII
### Vue de Reporting

</div>

---

La vue `v_book_overview` centralise les informations essentielles sur les livres et leurs propriétaires :

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

Cette vue est accessible à l'utilisateur `bookcycle_report` (droits SELECT uniquement) et peut être utilisée pour générer des rapports sans accéder directement aux tables métier.

---

&nbsp;

<div align="center">

## Chapitre VIII
### Requêtes SQL Illustratives

</div>

---

### 1. Sélection et Projection

```sql
-- Sélection simple
SELECT * FROM users;

-- Projection : titres et matières des livres
SELECT title, subject FROM books;

-- Sélection avec condition : livres disponibles
SELECT * FROM books WHERE status = 'available';

-- Recherche multicritère : livres de Lycée en Physique
SELECT * FROM books
WHERE school_level = 'Lycee' AND subject LIKE '%Phys%';
```

### 2. Jointures

```sql
-- Jointure interne : livres avec le nom du propriétaire
SELECT b.title, b.subject, u.name AS owner_name
FROM books b
JOIN users u ON u.id = b.owner_id;

-- Jointure multiple : demandes avec livre et demandeur
SELECT r.id, b.title, u.name AS requester_name, r.status
FROM requests r
JOIN books b ON b.id = r.book_id
JOIN users u ON u.id = r.requester_id;
```

### 3. Agrégations

```sql
-- Nombre de livres par niveau scolaire
SELECT school_level, COUNT(*) AS total_books
FROM books
GROUP BY school_level;

-- Niveaux avec au moins 2 livres
SELECT school_level, COUNT(*) AS total_books
FROM books
GROUP BY school_level
HAVING COUNT(*) >= 2;

-- Rapport financier : économie estimée
SELECT COUNT(*) AS total_exchanges,
       NVL(SUM(b.estimated_price), 0) AS money_saved_dt
FROM exchanges e
JOIN books b ON b.id = e.book_id;
```

### 4. Sous-requêtes

```sql
-- Utilisateurs ayant publié au moins un livre
SELECT name, email FROM users
WHERE id IN (SELECT owner_id FROM books);

-- Livres appartenant à un administrateur
SELECT title FROM books
WHERE owner_id IN (SELECT id FROM users WHERE role = 'admin');
```

### 5. Modifications

```sql
-- Mise à jour : réserver un livre
UPDATE books SET status = 'reserved', updated_at = SYSDATE WHERE id = 1;

-- Suppression logique : masquer un livre
UPDATE books SET is_active = 0, updated_at = SYSDATE WHERE id = 2;

-- Suppression physique : supprimer les notifications lues
DELETE FROM notifications WHERE is_read = 1;
```

---

&nbsp;

<div align="center">

## Chapitre IX
### Objets PL/SQL

</div>

---

### 1. Procédure `add_notification`

**Rôle :** Insérer une notification pour un utilisateur donné.

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

**Usage :** Appelée par la procédure `accept_request` et directement par l'administrateur via l'interface web.

---

### 2. Procédure `accept_request`

**Rôle :** Accepter une demande de manière atomique — mise à jour de la demande, rejet des autres, changement de statut du livre, notification du demandeur.

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
    -- Récupération des données liées à la demande
    SELECT r.book_id, r.requester_id, b.owner_id, b.title
    INTO v_book_id, v_requester_id, v_owner_id, v_title
    FROM requests r JOIN books b ON b.id = r.book_id
    WHERE r.id = p_request_id;

    -- Acceptation de la demande
    UPDATE requests
    SET status = 'accepted', meeting_note = p_meeting_note
    WHERE id = p_request_id;

    -- Rejet automatique des autres demandes du même livre
    UPDATE requests
    SET status = 'rejected'
    WHERE book_id = v_book_id
      AND id <> p_request_id
      AND status = 'pending';

    -- Mise à jour du statut du livre
    UPDATE books SET status = 'reserved', updated_at = SYSDATE
    WHERE id = v_book_id;

    -- Notification du demandeur
    add_notification(v_requester_id,
        'Votre demande pour le livre "' || v_title || '" a été acceptée.');
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        DBMS_OUTPUT.PUT_LINE('Demande introuvable : ' || p_request_id);
END;
/
```

**Apport :** Cette procédure garantit la cohérence des données lors d'une acceptation — plusieurs tables sont mises à jour en un seul appel, sans risque d'état intermédiaire incohérent.

---

### 3. Fonction `count_books_by_user`

**Rôle :** Retourner le nombre de livres actifs publiés par un utilisateur.

```sql
CREATE OR REPLACE FUNCTION count_books_by_user (
    p_user_id IN users.id%TYPE
) RETURN NUMBER IS
    v_total NUMBER;
BEGIN
    SELECT COUNT(*) INTO v_total
    FROM books
    WHERE owner_id = p_user_id AND is_active = 1;
    RETURN v_total;
END;
/
```

---

### 4. Fonction `calculate_money_saved`

**Rôle :** Calculer l'économie totale estimée sur la base des prix des livres ayant fait l'objet d'un échange.

```sql
CREATE OR REPLACE FUNCTION calculate_money_saved
RETURN NUMBER IS
    v_total NUMBER;
BEGIN
    SELECT NVL(SUM(b.estimated_price), 0) INTO v_total
    FROM exchanges e
    JOIN books b ON b.id = e.book_id;
    RETURN v_total;
END;
/
```

**Résultat :** Retourne la somme en dinars tunisiens (DT). `NVL` garantit le retour de 0 si aucun échange n'existe.

---

### 5. Trigger `trg_books_updated_at`

**Rôle :** Mettre à jour automatiquement le champ `updated_at` à chaque modification d'un livre.

```sql
CREATE OR REPLACE TRIGGER trg_books_updated_at
BEFORE UPDATE ON books
FOR EACH ROW
BEGIN
    :NEW.updated_at := SYSDATE;
END;
/
```

---

### 6. Trigger `trg_book_exchange_log`

**Rôle :** Inscrire automatiquement un enregistrement dans la table `exchanges` lorsqu'un livre passe au statut `exchanged`.

```sql
CREATE OR REPLACE TRIGGER trg_book_exchange_log
AFTER UPDATE OF status ON books
FOR EACH ROW
WHEN (NEW.status = 'exchanged' AND OLD.status <> 'exchanged')
DECLARE
    v_receiver_id requests.requester_id%TYPE;
BEGIN
    SELECT requester_id INTO v_receiver_id
    FROM requests
    WHERE book_id = :NEW.id AND status = 'accepted' AND ROWNUM = 1;

    INSERT INTO exchanges (book_id, owner_id, receiver_id, exchange_date, status)
    VALUES (:NEW.id, :NEW.owner_id, v_receiver_id, SYSDATE, 'completed');
EXCEPTION
    WHEN NO_DATA_FOUND THEN NULL;
END;
/
```

---

### 7. Curseurs et Blocs PL/SQL de Démonstration

#### 7.1 Curseur Implicite — `SQL%ROWCOUNT`

```sql
BEGIN
    UPDATE notifications
    SET is_read = 1
    WHERE user_id = 2 AND is_read = 0;

    DBMS_OUTPUT.PUT_LINE('Notifications mises à jour : ' || SQL%ROWCOUNT);
    ROLLBACK;
END;
/
```

#### 7.2 Curseur Explicite — Parcours des Livres Disponibles

```sql
DECLARE
    CURSOR c_available_books IS
        SELECT id, title, subject
        FROM books WHERE status = 'available'
        ORDER BY created_at DESC;

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

## Chapitre X
### Éléments Oracle Spécifiques

</div>

---

| Élément | Utilisation dans le projet |
|---|---|
| `%TYPE` | Typage des variables PL/SQL d'après les colonnes Oracle (ex. : `users.id%TYPE`) |
| `%ROWTYPE` | Typage d'une variable selon la structure d'un curseur (`c_available_books%ROWTYPE`) |
| `SQL%ROWCOUNT` | Comptage des lignes affectées par un UPDATE (curseur implicite) |
| `DBMS_OUTPUT.PUT_LINE` | Affichage de messages de débogage dans SQL Developer |
| `NVL` | Remplacement des valeurs NULL par une valeur par défaut (`NVL(SUM(...), 0)`) |
| `SYSDATE` | Date et heure courantes du serveur Oracle |
| `ROWNUM` | Limitation à 1 résultat dans le trigger `trg_book_exchange_log` |
| `EXCEPTION … WHEN NO_DATA_FOUND` | Gestion des cas où aucune ligne ne correspond à la requête |
| Séquence + trigger | Pattern d'auto-incrément pour toutes les tables |

---

&nbsp;

<div align="center">

## Chapitre XI
### Scripts Fournis et Ordre d'Exécution

</div>

---

### 1. Liste des Scripts

| Fichier | Description |
|---|---|
| `01_users_privileges.sql` | Création des utilisateurs Oracle et attribution des privilèges |
| `02_schema.sql` | Création des tables, contraintes, séquences, triggers, index et vue |
| `03_sample_data.sql` | Insertion des données de démonstration (comptes, livres, demandes, notifications) |
| `04_queries.sql` | Requêtes SQL illustratives (SELECT, JOIN, GROUP BY, UPDATE, DELETE) |
| `05_plsql_objects.sql` | Procédures, fonctions, triggers métier et blocs PL/SQL de démonstration |

### 2. Ordre d'Exécution Recommandé

```
1. 01_users_privileges.sql   ← Créer les utilisateurs Oracle et leurs droits
2. 02_schema.sql             ← Créer le schéma (tables, contraintes, index, vue)
3. 03_sample_data.sql        ← Insérer les données de démonstration
4. 05_plsql_objects.sql      ← Créer les procédures, fonctions et triggers métier
5. 04_queries.sql            ← Exécuter les requêtes illustratives (TP / soutenance)
```

> **Note :** Le script `04_queries.sql` termine par un `ROLLBACK` pour annuler les modifications de démonstration et permettre de rejouer le script en soutenance.

### 3. Comptes de Démonstration

| Compte | Email | Mot de passe | Rôle |
|---|---|---|---|
| Administrateur | `admin@bookcycle.tn` | `admin123` | `admin` |
| Utilisateur | `ahmed@bookcycle.tn` | `user123` | `user` |

---

&nbsp;

<div align="center">

## Conclusion

</div>

---

La partie SGBD de **BookCycle Tunisia** livre une base de données Oracle robuste, cohérente et bien documentée.

**Points forts :**

- Schéma normalisé avec tables de référence dynamiques pour les données académiques
- Contraintes d'intégrité complètes garantissant la cohérence des données
- Index couvrant toutes les requêtes fréquentes de l'application
- Objets PL/SQL encapsulant la logique métier critique (acceptation atomique des demandes, traçabilité des échanges)
- Démonstration de l'ensemble des concepts Oracle vus en cours : séquences, triggers, vues, procédures, fonctions, curseurs implicites et explicites, `%TYPE`, `%ROWTYPE`, `SQL%ROWCOUNT`

**Perspectives :**

L'ajout d'une procédure de relance automatique des demandes anciennes et d'un package PL/SQL regroupant les objets liés à la gestion des demandes constitueraient des extensions naturelles de ce travail.

---

*Rapport réalisé dans le cadre du Projet Intégré — Licence 2 Big Data et Intelligence Artificielle — ESEN — Université de la Manouba — 2025/2026*
