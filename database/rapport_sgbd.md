# Partie SGBD - BookCycle Tunisia

## 1. Schema relationnel

Le schema relationnel retenu contient au minimum 5 tables:

- `USERS(id, name, email, password, phone, role, created_at)`
- `BOOKS(id, title, subject, school_level, condition_label, description, owner_id, status, is_active, created_at, updated_at)`
- `REQUESTS(id, book_id, requester_id, status, meeting_note, request_date)`
- `EXCHANGES(id, book_id, owner_id, receiver_id, exchange_date, status)`
- `NOTIFICATIONS(id, user_id, message, is_read, created_at)`

Relations:

- `BOOKS.owner_id -> USERS.id`
- `REQUESTS.book_id -> BOOKS.id`
- `REQUESTS.requester_id -> USERS.id`
- `EXCHANGES.book_id -> BOOKS.id`
- `EXCHANGES.owner_id -> USERS.id`
- `EXCHANGES.receiver_id -> USERS.id`
- `NOTIFICATIONS.user_id -> USERS.id`

## 2. Scripts fournis

- `01_users_privileges.sql`
  Creation des utilisateurs de la base et attribution des privileges.
- `02_schema.sql`
  Creation des tables, contraintes, index et vue.
- `03_sample_data.sql`
  Insertion des donnees de test pour la demonstration.
- `04_queries.sql`
  Requetes SQL d'interrogation, de recherche, de jointure, d'agregation et de modification.
- `05_plsql_objects.sql`
  Procedures, fonctions, triggers, curseur implicite et curseur explicite en PL/SQL.
- `06_annex_objects.sql`
  Liste des utilisateurs et objets BD a joindre dans l'annexe du rapport.

## 3. Objets PL/SQL crees

- Procedure `ADD_NOTIFICATION`
- Procedure `ACCEPT_REQUEST`
- Fonction `COUNT_BOOKS_BY_USER`
- Fonction `CALCULATE_MONEY_SAVED`
- Trigger `TRG_BOOKS_UPDATED_AT`
- Trigger `TRG_PREVENT_SELF_REQUEST`
- Trigger `TRG_BOOK_EXCHANGE_LOG`

## 4. Requetes a montrer en soutenance

- Affichage simple de toutes les tables
- Recherche avec criteres multiples
- Jointures entre utilisateurs, livres et demandes
- Statistiques avec `COUNT`, `GROUP BY`, `HAVING`
- `UPDATE`, `DELETE`, suppression logique
- Execution des procedures et fonctions avec `DBMS_OUTPUT`
- Verification automatique des triggers
- Affichage des utilisateurs et objets via `ALL_USERS` et `USER_OBJECTS`

## 5. Remarque pour le rapport

Dans le rapport, vous pouvez expliquer que:

- Oracle XE et SQL Developer ont ete choisis pour respecter la recommandation de l'enonce.
- Le schema a ete derive du diagramme de classes de l'application BookCycle Tunisia.
- Les tables couvrent l'inscription, la publication des livres, les demandes, les echanges et les notifications.
- Le code PL/SQL automatise une partie des traitements metier et securise certaines operations.
- Certains elements sont specifiques a Oracle et sont gardes volontairement : `EXECUTE IMMEDIATE`, `sequence + trigger`, `%TYPE`, `%ROWTYPE`, `SQL%ROWCOUNT`, `DBMS_OUTPUT`, `ALL_USERS`, `USER_OBJECTS`.
