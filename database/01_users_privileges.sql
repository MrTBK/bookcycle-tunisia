/*
    Script 1 : creation des utilisateurs de la base et gestion des privileges.
    A executer avec un compte administrateur (exemple : SYSTEM).
*/

-- Suppression optionnelle si les utilisateurs existent deja.
BEGIN
    EXECUTE IMMEDIATE 'DROP USER bookcycle_app CASCADE';
EXCEPTION
    WHEN OTHERS THEN
        IF SQLCODE != -1918 THEN
            RAISE;
        END IF;
END;
/

BEGIN
    EXECUTE IMMEDIATE 'DROP USER bookcycle_report CASCADE';
EXCEPTION
    WHEN OTHERS THEN
        IF SQLCODE != -1918 THEN
            RAISE;
        END IF;
END;
/

-- Utilisateur principal qui possede les tables, triggers, procedures et fonctions.
CREATE USER bookcycle_app IDENTIFIED BY "BookCycle2026";

-- Utilisateur secondaire pour consultation et reporting.
CREATE USER bookcycle_report IDENTIFIED BY "BookCycleReport2026";

-- Privileges de base pour se connecter.
GRANT CREATE SESSION TO bookcycle_app;
GRANT CREATE SESSION TO bookcycle_report;

-- Privileges necessaires pour creer les objets du schema applicatif.
GRANT CREATE TABLE TO bookcycle_app;
GRANT CREATE VIEW TO bookcycle_app;
GRANT CREATE PROCEDURE TO bookcycle_app;
GRANT CREATE TRIGGER TO bookcycle_app;
GRANT CREATE SEQUENCE TO bookcycle_app;
GRANT UNLIMITED TABLESPACE TO bookcycle_app;

-- Le compte reporting ne peut que lire les donnees.
GRANT CREATE VIEW TO bookcycle_report;

/*
    Les droits fins sur les tables seront accordes apres la creation du schema
    dans le script 02_schema.sql.
*/
