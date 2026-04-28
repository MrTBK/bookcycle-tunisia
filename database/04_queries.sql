/*
    Script 4 : requetes SQL d'interrogation et de modification.
    Chaque bloc montre un type de requete a presenter en TP/Cours.
*/

/*
    This file is not used directly by the website.
    It is a learning/report file that shows useful SQL examples:
    - select
    - join
    - group by
    - update
    - delete
    - reporting queries
*/

-- 1. Selection simple : afficher tous les utilisateurs.
SELECT * FROM users;

-- 2. Projection : afficher uniquement le titre et la matiere des livres.
SELECT title, subject
FROM books;

-- 3. Selection avec condition : livres encore disponibles.
SELECT *
FROM books
WHERE status = 'available';

-- 4. Recherche avec plusieurs criteres.
SELECT *
FROM books
WHERE school_level = 'Lycee'
  AND subject LIKE '%Phys%';

-- 5. Tri des livres par date de creation decroissante.
SELECT title, created_at
FROM books
ORDER BY created_at DESC;

-- 6. Jointure interne entre livres et proprietaires.
SELECT b.title, b.subject, u.name AS owner_name
FROM books b
JOIN users u ON u.id = b.owner_id;

-- 7. Jointure multiple entre demandes, livres et demandeurs.
SELECT r.id, b.title, u.name AS requester_name, r.status
FROM requests r
JOIN books b ON b.id = r.book_id
JOIN users u ON u.id = r.requester_id;

-- 8. Aggregation : nombre total de livres par niveau scolaire.
SELECT school_level, COUNT(*) AS total_books
FROM books
GROUP BY school_level;

-- 9. Aggregation avec HAVING : niveaux contenant au moins 2 livres.
SELECT school_level, COUNT(*) AS total_books
FROM books
GROUP BY school_level
HAVING COUNT(*) >= 2;

-- 10. Sous-requete : utilisateurs ayant deja publie un livre.
SELECT name, email
FROM users
WHERE id IN (
    SELECT owner_id
    FROM books
);

-- 11. Sous-requete corrigee : livres dont le proprietaire est administrateur.
SELECT title
FROM books
WHERE owner_id IN (
    SELECT id
    FROM users
    WHERE role = 'admin'
);

-- 12. Vue de synthese.
SELECT *
FROM v_book_overview;

-- 13. Mise a jour : reserver un livre.
UPDATE books
SET status = 'reserved',
    updated_at = SYSDATE
WHERE id = 1;

-- 14. Mise a jour multi-table logique : marquer les autres demandes comme rejetees.
UPDATE requests
SET status = 'rejected'
WHERE book_id = 1
  AND status = 'pending'
  AND requester_id <> 3;

-- 15. Suppression logique : desactiver un livre.
UPDATE books
SET is_active = 0,
    updated_at = SYSDATE
WHERE id = 2;

-- 16. Suppression physique : supprimer les notifications deja lues.
DELETE FROM notifications
WHERE is_read = 1;

-- 17. Rapport statistique : nombre de demandes par statut.
SELECT status, COUNT(*) AS total_requests
FROM requests
GROUP BY status;

-- 18. Rapport financier : economie estimee basee sur le prix des livres echanges.
SELECT COUNT(*) AS total_exchanges,
       NVL(SUM(b.estimated_price), 0) AS money_saved_dt
FROM exchanges e
JOIN books b ON b.id = e.book_id;

-- ROLLBACK annule les modifications de demonstration pour pouvoir
-- rejouer le script pendant le TP ou la soutenance.
ROLLBACK;

-- ============================================================
-- SECTION 2 : REQUETES COMPLEMENTAIRES (tous les types de cours)
-- ============================================================

-- 19. INSERT : ajouter un utilisateur de demonstration.
-- INSERT permet d'inserer une nouvelle ligne dans une table.
INSERT INTO users (name, email, password, phone, role, is_active)
VALUES ('Demo User', 'demo@bookcycle.tn', 'hash_demo', '20000000', 'user', 1);

-- 20. DISTINCT : afficher les niveaux scolaires sans doublons.
-- DISTINCT elimine les lignes en double dans le resultat.
SELECT DISTINCT school_level
FROM books
WHERE is_active = 1
ORDER BY school_level;

-- 21. LEFT JOIN : afficher tous les livres avec le nombre de demandes recues,
--     y compris les livres sans aucune demande (le COUNT sera 0).
-- LEFT JOIN conserve toutes les lignes de la table gauche (books),
-- meme si aucune ligne de la table droite (requests) ne correspond.
SELECT b.title,
       b.subject,
       b.school_level,
       COUNT(r.id) AS nb_demandes
FROM books b
LEFT JOIN requests r ON r.book_id = b.id
WHERE b.is_active = 1
GROUP BY b.title, b.subject, b.school_level
ORDER BY nb_demandes DESC;

-- 22. Fonctions d'agregation : AVG, MIN, MAX, SUM sur les prix des livres.
-- Ces fonctions calculent respectivement la moyenne, le minimum,
-- le maximum et la somme d'une colonne numerique.
SELECT
    COUNT(*)                         AS total_livres,
    AVG(estimated_price)             AS prix_moyen,
    MIN(estimated_price)             AS prix_minimum,
    MAX(estimated_price)             AS prix_maximum,
    SUM(estimated_price)             AS valeur_totale,
    NVL(SUM(estimated_price), 0)     AS valeur_totale_securisee
FROM books
WHERE is_active = 1;

-- 23. BETWEEN : livres dont le prix estime est compris entre 5 et 20 DT.
-- BETWEEN est equivalent a >= 5 AND <= 20.
SELECT title, subject, estimated_price
FROM books
WHERE estimated_price BETWEEN 5 AND 20
  AND is_active = 1
ORDER BY estimated_price;

-- 24. IN avec liste de valeurs : livres de College ou Lycee.
-- IN permet de tester l'appartenance a un ensemble de valeurs.
SELECT title, school_level, subject
FROM books
WHERE school_level IN ('College', 'Lycee')
  AND is_active = 1;

-- 25. EXISTS : sous-requete correlee - utilisateurs qui ont fait au moins une demande.
-- EXISTS retourne vrai si la sous-requete retourne au moins une ligne.
-- Contrairement a IN, EXISTS s'arrete des la premiere correspondance (plus efficace).
SELECT name, email
FROM users
WHERE EXISTS (
    SELECT 1
    FROM requests r
    WHERE r.requester_id = users.id   -- liaison correlee avec la requete principale
);

-- 26. UNION : combiner les utilisateurs qui ont publie un livre
--     avec ceux qui ont fait une demande (sans doublons).
-- UNION supprime les doublons. UNION ALL les conserverait.
SELECT name, email, 'Proprietaire' AS role_activite
FROM users
WHERE id IN (SELECT owner_id FROM books WHERE is_active = 1)
UNION
SELECT name, email, 'Demandeur' AS role_activite
FROM users
WHERE id IN (SELECT requester_id FROM requests)
ORDER BY name;

-- 27. MINUS : utilisateurs qui ont publie un livre mais n'ont jamais fait de demande.
-- MINUS retourne les lignes du premier SELECT qui n'apparaissent pas dans le second.
SELECT id, name, email
FROM users
WHERE id IN (SELECT owner_id FROM books)
MINUS
SELECT id, name, email
FROM users
WHERE id IN (SELECT requester_id FROM requests);

-- 28. Fonctions de date Oracle : TO_CHAR, TO_DATE, MONTHS_BETWEEN.
-- TO_CHAR convertit une date en chaine formatee pour l'affichage.
-- MONTHS_BETWEEN calcule le nombre de mois entre deux dates.
SELECT
    title,
    TO_CHAR(created_at, 'DD/MM/YYYY')          AS date_creation,
    TO_CHAR(created_at, 'DD/MM/YYYY HH24:MI')  AS date_heure_creation,
    ROUND(MONTHS_BETWEEN(SYSDATE, created_at))  AS mois_depuis_creation
FROM books
WHERE is_active = 1
ORDER BY created_at DESC;

-- ROLLBACK final pour annuler le INSERT de demonstration de la requete 19.
ROLLBACK;

/*
    Dictionnaire de donnees Oracle.
    Les vues USER_* appartiennent au schema courant (bookcycle_app).
    Elles permettent de lister tous les objets crees : tables, index,
    triggers, sequences, procedures, fonctions, vues etc.
    Tres utiles pendant la soutenance pour prouver que les objets existent.
*/

-- 19. Lister tous les index du schema courant
-- USER_INDEXES contient un index par ligne avec son nom, sa table et son type
SELECT
    index_name,    -- nom de l'index
    table_name,    -- table sur laquelle l'index est cree
    uniqueness     -- UNIQUE ou NONUNIQUE
FROM user_indexes
ORDER BY table_name, index_name;  -- tri par table puis par nom

-- 20. Lister tous les triggers du schema courant
-- USER_TRIGGERS liste les triggers avec leur type et leur table cible
SELECT
    trigger_name,       -- nom du trigger
    trigger_type,       -- BEFORE EACH ROW, AFTER EACH ROW, AFTER STATEMENT etc.
    triggering_event,   -- INSERT, UPDATE, DELETE ou combinaison
    table_name,         -- table sur laquelle le trigger est pose
    status              -- ENABLED = actif, DISABLED = desactive
FROM user_triggers
ORDER BY table_name, trigger_name;

-- 21. Lister tous les objets du schema courant (vue generale)
-- USER_OBJECTS regroupe tous les objets : TABLE, VIEW, INDEX, TRIGGER,
-- PROCEDURE, FUNCTION, SEQUENCE, PACKAGE etc.
SELECT
    object_name,    -- nom de l'objet
    object_type,    -- type : TABLE, VIEW, INDEX, TRIGGER, PROCEDURE, FUNCTION, SEQUENCE
    status          -- VALID = compile correctement, INVALID = erreur de compilation
FROM user_objects
ORDER BY object_type, object_name;  -- trie par type puis par nom alphabetique

-- 22. Lister les sequences du schema courant
-- USER_SEQUENCES donne l'etat actuel de chaque sequence
SELECT
    sequence_name,    -- nom de la sequence
    last_number,      -- derniere valeur generee
    increment_by,     -- valeur d'incrementation (ici 1)
    min_value,        -- valeur minimale
    max_value         -- valeur maximale
FROM user_sequences
ORDER BY sequence_name;

-- 23. Lister toutes les tables du schema avec le nombre de colonnes
-- USER_TAB_COLUMNS donne les colonnes de chaque table
SELECT
    table_name,           -- nom de la table
    COUNT(*) AS nb_colonnes  -- nombre de colonnes dans cette table
FROM user_tab_columns
GROUP BY table_name       -- regrouper par table
ORDER BY table_name;      -- tri alphabetique
