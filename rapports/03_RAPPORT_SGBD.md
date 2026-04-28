# RAPPORT SGBD — BASE DE DONNÉES ORACLE
## BookCycle Tunisia

---

**Université de la Manouba — ESEN**
**Licence 2 — Big Data et Intelligence Artificielle**
**Année universitaire 2025 / 2026**

**Réalisé par :** Mortadha Yakoubi
**Module :** Systèmes de Gestion de Bases de Données (SGBD)

---

## Table des Matières

1. [Introduction](#introduction)
2. [Choix du SGBD](#choix-du-sgbd)
3. [Schéma Relationnel](#schéma-relationnel)
4. [Relations et Contraintes d'Intégrité](#relations-et-contraintes)
5. [Séquences et Triggers d'Auto-incrément](#séquences-et-triggers)
6. [Index de Performance](#index-de-performance)
7. [Vue de Reporting](#vue-de-reporting)
8. [Requêtes SQL Illustratives](#requêtes-sql)
9. [Objets PL/SQL](#objets-plsql)
10. [Éléments Oracle Spécifiques](#éléments-oracle-spécifiques)
11. [Scripts Fournis et Ordre d'Exécution](#scripts-et-ordre)
12. [Conclusion](#conclusion)

---

## 1. Introduction

La partie SGBD du projet **BookCycle Tunisia** a pour objectif de concevoir, implémenter et documenter une base de données Oracle capable de gérer l'ensemble des entités de la plateforme : utilisateurs, livres scolaires, demandes, échanges et notifications.

Le projet exploite pleinement les capacités d'**Oracle XE** :

- **SQL DDL** pour la définition du schéma (tables, contraintes, index, vues)
- **SQL DML** pour la manipulation des données (SELECT, INSERT, UPDATE, DELETE)
- **PL/SQL** pour l'encapsulation de la logique métier (procédures, fonctions, triggers, curseurs)

Ce rapport présente chaque composant de la base de données avec son rôle, sa structure et les extraits SQL correspondants.

---

## 2. Choix du SGBD

### 2.1 Oracle Database Express Edition (XE)

Le SGBD retenu est **Oracle XE**, conformément aux objectifs du module SGBD. Ce choix est justifié par :

| Critère | Justification |
|---|---|
| **Robustesse** | Oracle est un SGBD professionnel de référence, largement utilisé en entreprise |
| **Richesse PL/SQL** | Support complet des procédures stockées, fonctions, triggers, curseurs |
| **Contraintes d'intégrité** | Gestion avancée des clés étrangères, contraintes CHECK, UNIQUE |
| **Séquences** | Mécanisme propre Oracle pour la génération d'identifiants |
| **Compatibilité pédagogique** | Aligné avec le contenu du module SGBD enseigné à l'ESEN |

### 2.2 Configuration de Connexion

L'application PHP se connecte à Oracle via **PDO_OCI** avec les paramètres suivants :

```
DSN       : oci:dbname=//localhost:1521/XE;charset=AL32UTF8
Utilisateur : bookcycle_app
Mot de passe : BookCycle2026
```

Un utilisateur de reporting à droits limités (`bookcycle_report`) est également créé pour les accès en lecture seule.

---

## 3. Schéma Relationnel

Le schéma comprend **8 tables** réparties en trois catégories.

### 3.1 Tables de Référence Académique

Ces tables centralisent les données de référence pour les niveaux scolaires, les classes et les matières. Elles remplacent les listes codées en dur dans le code PHP, garantissant la cohérence des données à la source.

#### Table `SUBJECTS` — Matières Scolaires

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

#### Table `SCHOOL_CLASSES` — Classes Scolaires

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

#### Table `CLASS_SUBJECTS` — Correspondances Classe ↔ Matières

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

### 3.2 Tables Métier Principales

#### Table `USERS` — Utilisateurs

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

#### Table `BOOKS` — Livres

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

#### Table `REQUESTS` — Demandes

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

#### Table `EXCHANGES` — Échanges Finalisés

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

#### Table `NOTIFICATIONS` — Notifications

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

## 4. Relations et Contraintes d'Intégrité

### 4.1 Diagramme des Relations

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

### 4.2 Récapitulatif des Clés Étrangères

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

### 4.3 Valeurs Contrôlées par Contraintes CHECK

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

## 5. Séquences et Triggers d'Auto-incrément

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

## 6. Index de Performance

Dix index sont définis pour accélérer les requêtes les plus fréquentes de l'application.

```sql
-- Tables de référence
CREATE INDEX idx_subjects_active      ON subjects(is_active, sort_order);
CREATE INDEX idx_school_classes_level ON school_classes(school_level, sort_order);
CREATE INDEX idx_class_subjects_class ON class_subjects(class_id, sort_order);
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

## 7. Vue de Reporting

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

## 8. Requêtes SQL Illustratives

Le fichier `04_queries.sql` regroupe les requêtes SQL représentatives du projet, classées par type.

### 8.1 Sélection et Projection

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

### 8.2 Jointures

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

### 8.3 Agrégations

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

### 8.4 Sous-requêtes

```sql
-- Utilisateurs ayant publié au moins un livre
SELECT name, email FROM users
WHERE id IN (SELECT owner_id FROM books);

-- Livres appartenant à un administrateur
SELECT title FROM books
WHERE owner_id IN (SELECT id FROM users WHERE role = 'admin');
```

### 8.5 Modifications

```sql
-- Mise à jour : réserver un livre
UPDATE books SET status = 'reserved', updated_at = SYSDATE WHERE id = 1;

-- Suppression logique : masquer un livre
UPDATE books SET is_active = 0, updated_at = SYSDATE WHERE id = 2;

-- Suppression physique : supprimer les notifications lues
DELETE FROM notifications WHERE is_read = 1;
```

---

## 9. Objets PL/SQL

### 9.1 Procédure `add_notification`

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

### 9.2 Procédure `accept_request`

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

### 9.3 Fonction `count_books_by_user`

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

**Usage :** Utilisée dans les blocs de démonstration PL/SQL et potentiellement dans le tableau de bord.

---

### 9.4 Fonction `calculate_money_saved`

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

### 9.5 Trigger `trg_books_updated_at`

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

**Apport :** Supprime le besoin de gérer `updated_at` dans chaque requête UPDATE du code PHP — la cohérence est assurée au niveau de la base de données.

---

### 9.6 Trigger `trg_book_exchange_log`

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

**Apport :** La traçabilité des échanges est automatique — dès qu'un livre passe à `exchanged`, l'échange est enregistré sans action supplémentaire du code PHP.

---

### 9.7 Curseurs et Blocs PL/SQL de Démonstration

#### Curseur Implicite — `SQL%ROWCOUNT`

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

#### Curseur Explicite — Parcours des Livres Disponibles

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

## 10. Éléments Oracle Spécifiques

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

## 11. Scripts Fournis et Ordre d'Exécution

### 11.1 Liste des Scripts

| Fichier | Description |
|---|---|
| `01_users_privileges.sql` | Création des utilisateurs Oracle et attribution des privilèges |
| `02_schema.sql` | Création des tables, contraintes, séquences, triggers, index et vue |
| `03_sample_data.sql` | Insertion des données de démonstration (comptes, livres, demandes, notifications) |
| `04_queries.sql` | Requêtes SQL illustratives (SELECT, JOIN, GROUP BY, UPDATE, DELETE) |
| `05_plsql_objects.sql` | Procédures, fonctions, triggers métier et blocs PL/SQL de démonstration |

### 11.2 Ordre d'Exécution Recommandé

```
1. 01_users_privileges.sql   ← Créer les utilisateurs Oracle et leurs droits
2. 02_schema.sql             ← Créer le schéma (tables, contraintes, index, vue)
3. 03_sample_data.sql        ← Insérer les données de démonstration
4. 05_plsql_objects.sql      ← Créer les procédures, fonctions et triggers métier
5. 04_queries.sql            ← Exécuter les requêtes illustratives (TP / soutenance)
```

> **Note :** Le script `04_queries.sql` termine par un `ROLLBACK` pour annuler les modifications de démonstration et permettre de rejouer le script en soutenance.

### 11.3 Comptes de Démonstration

| Compte | Email | Mot de passe | Rôle |
|---|---|---|---|
| Administrateur | `admin@bookcycle.tn` | `admin123` | `admin` |
| Utilisateur | `ahmed@bookcycle.tn` | `user123` | `user` |

---

## 12. Conclusion

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

## Annexe — Inventaire Complet des Objets de la Base de Données

### Utilisateurs Oracle

| Utilisateur | Rôle | Privilèges |
|---|---|---|
| `bookcycle_app` | Utilisateur applicatif principal | Création et manipulation des tables, exécution des procédures |
| `bookcycle_report` | Utilisateur de reporting (lecture seule) | SELECT sur toutes les tables et la vue `v_book_overview` |

### Tables

| Table | Colonnes | Clé primaire | Séquence |
|---|---|---|---|
| `users` | id, name, email, password, phone, role, is_active, created_at | `id` | `seq_users` |
| `subjects` | id, name, sort_order, is_active | `id` | `seq_subjects` |
| `school_classes` | id, school_level, class_name, sort_order, is_active | `id` | `seq_school_classes` |
| `class_subjects` | id, class_id, subject_id, sort_order, is_active | `id` | `seq_class_subjects` |
| `books` | id, title, subject, class_name, school_level, condition_label, estimated_price, description, owner_id, status, is_active, created_at, updated_at | `id` | `seq_books` |
| `requests` | id, book_id, requester_id, status, meeting_note, request_date | `id` | `seq_requests` |
| `exchanges` | id, book_id, owner_id, receiver_id, exchange_date, status | `id` | `seq_exchanges` |
| `notifications` | id, user_id, sender_name, message, is_read, created_at | `id` | `seq_notifications` |

### Séquences

| Séquence | Valeur de départ | Incrément | Table associée |
|---|---|---|---|
| `seq_users` | 1 | 1 | `users` |
| `seq_subjects` | 1 | 1 | `subjects` |
| `seq_school_classes` | 1 | 1 | `school_classes` |
| `seq_class_subjects` | 1 | 1 | `class_subjects` |
| `seq_books` | 1 | 1 | `books` |
| `seq_requests` | 1 | 1 | `requests` |
| `seq_exchanges` | 1 | 1 | `exchanges` |
| `seq_notifications` | 1 | 1 | `notifications` |

### Triggers

| Trigger | Type | Table | Événement | Rôle |
|---|---|---|---|---|
| `trg_users_pk` | BEFORE INSERT | `users` | INSERT | Auto-incrément de l'ID |
| `trg_subjects_pk` | BEFORE INSERT | `subjects` | INSERT | Auto-incrément de l'ID |
| `trg_school_classes_pk` | BEFORE INSERT | `school_classes` | INSERT | Auto-incrément de l'ID |
| `trg_class_subjects_pk` | BEFORE INSERT | `class_subjects` | INSERT | Auto-incrément de l'ID |
| `trg_books_pk` | BEFORE INSERT | `books` | INSERT | Auto-incrément de l'ID |
| `trg_requests_pk` | BEFORE INSERT | `requests` | INSERT | Auto-incrément de l'ID |
| `trg_exchanges_pk` | BEFORE INSERT | `exchanges` | INSERT | Auto-incrément de l'ID |
| `trg_notifications_pk` | BEFORE INSERT | `notifications` | INSERT | Auto-incrément de l'ID |
| `trg_books_updated_at` | BEFORE UPDATE | `books` | UPDATE | Met à jour `updated_at` automatiquement |
| `trg_book_exchange_log` | AFTER UPDATE | `books` | UPDATE (status) | Journalise l'échange quand status = `exchanged` |

### Procédures Stockées

| Procédure | Paramètres | Description |
|---|---|---|
| `add_notification` | `p_user_id IN users.id%TYPE`, `p_message IN notifications.message%TYPE` | Insère une notification pour un utilisateur |
| `accept_request` | `p_request_id IN requests.id%TYPE`, `p_meeting_note IN requests.meeting_note%TYPE` | Accepte une demande de manière atomique (mise à jour demande, autres demandes, livre, notification) |

### Fonctions

| Fonction | Paramètres | Retour | Description |
|---|---|---|---|
| `count_books_by_user` | `p_user_id IN users.id%TYPE` | `NUMBER` | Nombre de livres actifs d'un utilisateur |
| `calculate_money_saved` | aucun | `NUMBER` | Somme des prix estimés des livres échangés (en DT) |

### Vues

| Vue | Tables source | Description |
|---|---|---|
| `v_book_overview` | `books`, `users` | Informations complètes sur les livres avec le nom et email du propriétaire |

### Index

| Index | Table | Colonnes indexées | Utilisation |
|---|---|---|---|
| `idx_subjects_active` | `subjects` | `is_active, sort_order` | Chargement des matières actives |
| `idx_school_classes_level` | `school_classes` | `school_level, sort_order` | Filtrage par niveau scolaire |
| `idx_class_subjects_class` | `class_subjects` | `class_id, sort_order` | Matières d'une classe |
| `idx_class_subjects_subject` | `class_subjects` | `subject_id, sort_order` | Classes d'une matière |
| `idx_books_owner` | `books` | `owner_id` | Livres d'un utilisateur |
| `idx_books_subject` | `books` | `subject` | Filtrage catalogue par matière |
| `idx_books_level` | `books` | `school_level` | Filtrage catalogue par niveau |
| `idx_requests_book` | `requests` | `book_id` | Demandes pour un livre |
| `idx_requests_requester` | `requests` | `requester_id` | Demandes envoyées par un utilisateur |
| `idx_notifications_user` | `notifications` | `user_id` | Notifications d'un utilisateur |

### Contraintes

| Contrainte | Table | Type | Définition |
|---|---|---|---|
| `uq_subjects_name` | `subjects` | UNIQUE | `name` |
| `uq_school_classes` | `school_classes` | UNIQUE | `(school_level, class_name)` |
| `uq_class_subjects` | `class_subjects` | UNIQUE | `(class_id, subject_id)` |
| `chk_users_role` | `users` | CHECK | `role IN ('admin','user')` |
| `chk_users_active` | `users` | CHECK | `is_active IN (0,1)` |
| `chk_school_classes_level` | `school_classes` | CHECK | `school_level IN ('Primaire','College','Lycee')` |
| `chk_books_status` | `books` | CHECK | `status IN ('available','reserved','exchanged')` |
| `chk_books_active` | `books` | CHECK | `is_active IN (0,1)` |
| `chk_requests_status` | `requests` | CHECK | `status IN ('pending','accepted','rejected')` |
| `chk_notifications_read` | `notifications` | CHECK | `is_read IN (0,1)` |
| `fk_books_owner` | `books` | FK | `owner_id → users(id)` |
| `fk_requests_book` | `requests` | FK | `book_id → books(id)` |
| `fk_requests_requester` | `requests` | FK | `requester_id → users(id)` |
| `fk_exchanges_book` | `exchanges` | FK | `book_id → books(id)` |
| `fk_exchanges_owner` | `exchanges` | FK | `owner_id → users(id)` |
| `fk_exchanges_receiver` | `exchanges` | FK | `receiver_id → users(id)` |
| `fk_notifications_user` | `notifications` | FK | `user_id → users(id)` |
| `fk_class_subjects_class` | `class_subjects` | FK | `class_id → school_classes(id)` |
| `fk_class_subjects_subject` | `class_subjects` | FK | `subject_id → subjects(id)` |

---

*Rapport réalisé dans le cadre du Projet Intégré — Licence 2 Big Data et Intelligence Artificielle — ESEN — Université de la Manouba — 2025/2026*
