/*
    Script 4 : requetes SQL d'interrogation et de modification.
    Chaque bloc montre un type de requete a presenter en TP/Cours.
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

-- 18. Rapport financier simple : economie estimee a 25 DT par echange.
SELECT COUNT(*) AS total_exchanges,
       COUNT(*) * 25 AS money_saved_dt
FROM exchanges;

-- ROLLBACK annule les modifications de demonstration pour pouvoir
-- rejouer le script pendant le TP ou la soutenance.
ROLLBACK;
