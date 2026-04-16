# Oracle Setup

Ce projet est prepare pour une utilisation avec Oracle et PL/SQL.

## Scripts a executer dans l'ordre

1. `01_users_privileges.sql`
2. `02_schema.sql`
3. `03_sample_data.sql`
4. `05_plsql_objects.sql`
5. `04_queries.sql`
6. `06_annex_objects.sql`

## Comptes prevus

- utilisateur principal : `bookcycle_app`
- utilisateur reporting : `bookcycle_report`

## Configuration PHP attendue

Dans `app/Config/config.php`, l'application vise par defaut :

- DSN : `oci:dbname=//localhost:1521/XEPDB1;charset=AL32UTF8`
- user : `bookcycle_app`
- password : `BookCycle2026`

## Comptes de test applicatifs

- admin : `admin@bookcycle.tn` / `admin123`
- user : `ahmed@bookcycle.tn` / `user123`

## Important

Pour que l'application PHP fonctionne avec Oracle sous XAMPP, il faut :

- Oracle XE ou une base Oracle accessible
- Oracle Instant Client
- activer `oci8` et `pdo_oci` dans `C:\xampp\php\php.ini`

Sans cela, les scripts PL/SQL peuvent etre executes dans SQL Developer, mais l'application PHP ne pourra pas se connecter a Oracle.
