# Rapport SGBD - BookCycle Tunisia

## Introduction

La partie SGBD du projet **BookCycle Tunisia** a pour objectif de concevoir une base de donnees Oracle capable de gerer les utilisateurs, les livres, les demandes, les echanges et les notifications de la plateforme.

Le projet exploite :

- SQL pour la definition et la manipulation des donnees
- PL/SQL pour les traitements metier avances
- des objets Oracle comme les sequences, triggers et vues

---

## 1. Choix Du SGBD

Le SGBD retenu est **Oracle XE**.  
Ce choix permet d'utiliser une solution relationnelle robuste tout en respectant les attentes du module SGBD.

Les avantages principaux dans ce projet sont :

- support de SQL et PL/SQL
- gestion des contraintes d'integrite
- support des procedures, fonctions et triggers
- possibilite de construire des vues de reporting

---

## 2. Schema Relationnel

Le schema comporte cinq tables principales.

### Table `USERS`

- `id`
- `name`
- `email`
- `password`
- `phone`
- `role`
- `is_active`
- `created_at`

### Table `BOOKS`

- `id`
- `title`
- `subject`
- `class_name`
- `school_level`
- `condition_label`
- `estimated_price`
- `description`
- `owner_id`
- `status`
- `is_active`
- `created_at`
- `updated_at`

### Table `REQUESTS`

- `id`
- `book_id`
- `requester_id`
- `status`
- `meeting_note`
- `request_date`

### Table `EXCHANGES`

- `id`
- `book_id`
- `owner_id`
- `receiver_id`
- `exchange_date`
- `status`

### Table `NOTIFICATIONS`

- `id`
- `user_id`
- `sender_name`
- `message`
- `is_read`
- `created_at`

---

## 3. Relations Et Contraintes

Les relations principales sont :

- `books.owner_id -> users.id`
- `requests.book_id -> books.id`
- `requests.requester_id -> users.id`
- `exchanges.book_id -> books.id`
- `exchanges.owner_id -> users.id`
- `exchanges.receiver_id -> users.id`
- `notifications.user_id -> users.id`

Le schema contient aussi :

- des cles primaires
- des cles etrangeres
- des contraintes `CHECK`
- une contrainte `UNIQUE` sur `users.email`

Exemples de valeurs controlees :

- `users.role` : `admin` ou `user`
- `books.status` : `available`, `reserved`, `exchanged`
- `requests.status` : `pending`, `accepted`, `rejected`
- `notifications.is_read` : `0` ou `1`

---

## 4. Sequences, Triggers Et Vue

Pour rester compatible avec Oracle XE, les identifiants sont generes a l'aide de sequences et triggers :

- `seq_users` / `trg_users_pk`
- `seq_books` / `trg_books_pk`
- `seq_requests` / `trg_requests_pk`
- `seq_exchanges` / `trg_exchanges_pk`
- `seq_notifications` / `trg_notifications_pk`

Le projet contient aussi une vue utile pour le reporting :

- `v_book_overview`

Cette vue combine les informations des livres et de leurs proprietaires.

---

## 5. Index

Les index presents dans le schema sont :

- `idx_books_owner`
- `idx_books_subject`
- `idx_books_level`
- `idx_requests_book`
- `idx_requests_requester`
- `idx_notifications_user`

Ils permettent d'ameliorer les recherches les plus frequentes dans l'application.

---

## 6. Scripts Fournis

Les scripts Oracle du projet sont :

1. `01_users_privileges.sql`
2. `02_schema.sql`
3. `03_sample_data.sql`
4. `04_queries.sql`
5. `05_plsql_objects.sql`
6. `06_annex_objects.sql`

Ordre d'execution recommande :

1. `01_users_privileges.sql`
2. `02_schema.sql`
3. `03_sample_data.sql`
4. `05_plsql_objects.sql`
5. `04_queries.sql`
6. `06_annex_objects.sql`

---

## 7. Requetes SQL Demandees

Le projet couvre les types de requetes suivants :

- affichage simple de tables
- projection de colonnes
- selection avec conditions
- recherche multicritere
- jointures
- agregations avec `COUNT`, `GROUP BY` et `HAVING`
- insertion
- mise a jour
- suppression logique et suppression simple

Ces requetes sont utilisees pour les besoins metier et pour la demonstration en soutenance.

---

## 8. Objets PL/SQL

### Procedures

- `add_notification(p_user_id, p_message)`
- `accept_request(p_request_id, p_meeting_note)`

### Fonctions

- `count_books_by_user(p_user_id)`
- `calculate_money_saved()`

### Triggers Metier

- `trg_books_updated_at`
- `trg_prevent_self_request`
- `trg_book_exchange_log`

---

## 9. Apport De PL/SQL

PL/SQL apporte plusieurs avantages dans le projet :

- centralisation de certaines regles metier
- automatisation des mises a jour complexes
- reduction de la duplication de logique
- securisation supplementaire au niveau de la base

Exemples :

- une demande acceptee met a jour plusieurs elements coherents
- une notification peut etre creee par procedure
- une demande sur son propre livre peut etre bloquee par trigger

---

## 10. Elements Oracle Specifiques

Le projet conserve volontairement certains elements Oracle importants :

- `EXECUTE IMMEDIATE`
- `sequence + trigger`
- `%TYPE`
- `%ROWTYPE`
- `SQL%ROWCOUNT`
- `DBMS_OUTPUT.PUT_LINE`
- `ALL_USERS`
- `USER_OBJECTS`

Ces elements enrichissent la demonstration technique du projet.

---

## Conclusion

La partie SGBD de **BookCycle Tunisia** fournit une base solide a toute l'application.  
Le schema relationnel, les contraintes, les index et les objets PL/SQL assurent une gestion coherente des donnees et renforcent plusieurs traitements metier importants.
