/*
    Script 6 : annexe du rapport.
    Permet d'afficher les utilisateurs et tous les objets de la base.
*/

/*
    This script is useful for the written report or oral presentation.
    It helps show what really exists inside Oracle:
    - users
    - tables
    - views
    - procedures
    - functions
    - triggers
    - indexes
*/

-- ALL_USERS est une vue systeme Oracle qui liste les utilisateurs connus.
-- Afficher les utilisateurs Oracle lies au projet.
SELECT username, created
FROM all_users
WHERE username IN ('BOOKCYCLE_APP', 'BOOKCYCLE_REPORT');

-- USER_OBJECTS est une vue systeme Oracle qui liste les objets de
-- l'utilisateur actuellement connecte.
-- Afficher les tables du schema applicatif.
SELECT object_name, object_type, status
FROM user_objects
WHERE object_type = 'TABLE'
ORDER BY object_name;

-- Afficher les vues.
SELECT object_name, object_type, status
FROM user_objects
WHERE object_type = 'VIEW'
ORDER BY object_name;

-- Afficher les procedures et fonctions.
SELECT object_name, object_type, status
FROM user_objects
WHERE object_type IN ('PROCEDURE', 'FUNCTION')
ORDER BY object_type, object_name;

-- Afficher les triggers.
SELECT object_name, object_type, status
FROM user_objects
WHERE object_type = 'TRIGGER'
ORDER BY object_name;

-- Afficher les index.
SELECT object_name, object_type, status
FROM user_objects
WHERE object_type = 'INDEX'
ORDER BY object_name;
