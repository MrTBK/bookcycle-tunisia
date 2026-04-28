/*
    Script 6 : triggers (declencheurs) PL/SQL.

    Un trigger est un bloc PL/SQL execute automatiquement par Oracle
    lorsqu'un evenement se produit sur une table (INSERT, UPDATE ou DELETE).
    Il ne peut pas etre appele manuellement : Oracle le declenche seul.

    Types de triggers presentes ici :
      - BEFORE row-level  : s'execute avant la modification, ligne par ligne
      - AFTER  row-level  : s'execute apres la modification, ligne par ligne
      - AFTER  statement  : s'execute une seule fois apres toute l'instruction DML
      - WHEN              : condition supplementaire pour filtrer les lignes concernees

    :NEW = nouvelle valeur de la ligne (disponible dans les triggers row-level)
    :OLD = ancienne valeur de la ligne (disponible dans les triggers row-level UPDATE/DELETE)
    Dans la clause WHEN, on ecrit NEW et OLD sans les deux-points.
*/

-- ================================================================
-- Trigger 1 : mettre a jour automatiquement la date de modification
-- Type     : BEFORE UPDATE, niveau ligne (FOR EACH ROW)
-- Table    : books
-- Objectif : garantir que updated_at est toujours exact sans
--            que le code PHP ait besoin de le gerer manuellement
-- ================================================================
CREATE OR REPLACE TRIGGER trg_books_updated_at
BEFORE UPDATE ON books        -- se declenche juste avant chaque UPDATE sur books
FOR EACH ROW                  -- s'execute une fois par ligne modifiee
BEGIN
    :NEW.updated_at := SYSDATE;  -- ecrase la valeur avec la date et l'heure actuelles
END;
/

-- ================================================================
-- Trigger 2 : journaliser un echange quand le statut devient 'exchanged'
-- Type     : AFTER UPDATE OF colonne, niveau ligne (FOR EACH ROW)
-- Table    : books
-- Objectif : inserer automatiquement une ligne dans exchanges
--            quand un livre passe a l'etat 'exchanged'
-- WHEN     : filtre les lignes avant d'entrer dans le corps du trigger
--            (plus efficace qu'un IF a l'interieur du BEGIN)
-- ================================================================
CREATE OR REPLACE TRIGGER trg_book_exchange_log
AFTER UPDATE OF status ON books           -- declenche apres modification du champ status uniquement
FOR EACH ROW                              -- s'execute une fois par ligne modifiee
WHEN (NEW.status = 'exchanged'            -- condition : le nouveau statut est 'exchanged'
  AND OLD.status <> 'exchanged')          -- et l'ancien statut etait different (evite les doublons)
DECLARE
    v_receiver_id requests.requester_id%TYPE;  -- %TYPE recupere le type exact de la colonne Oracle
BEGIN
    -- retrouver l'identifiant du demandeur dont la demande a ete acceptee
    SELECT requester_id
    INTO v_receiver_id
    FROM requests
    WHERE book_id = :NEW.id        -- livre concerne par cette mise a jour
      AND status   = 'accepted'    -- uniquement la demande deja acceptee
      AND ROWNUM   = 1;            -- prendre une seule ligne (par securite)

    -- enregistrer l'echange dans la table dediee
    INSERT INTO exchanges (book_id, owner_id, receiver_id, exchange_date, status)
    VALUES (
        :NEW.id,         -- identifiant du livre
        :NEW.owner_id,   -- identifiant du proprietaire (donne le livre)
        v_receiver_id,   -- identifiant du receveur (prend le livre)
        SYSDATE,         -- date de l'echange = maintenant
        'completed'      -- statut de l'echange
    );
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        NULL;  -- aucune demande acceptee trouvee : on ignore sans erreur
END;
/

-- ================================================================
-- Trigger 3 : notifier le proprietaire quand une demande arrive
-- Type     : AFTER INSERT, niveau ligne (FOR EACH ROW)
-- Table    : requests
-- Objectif : creer automatiquement une notification pour le
--            proprietaire du livre des qu'une demande est inseree
-- ================================================================
CREATE OR REPLACE TRIGGER trg_notify_owner_on_request
AFTER INSERT ON requests    -- declenche apres chaque insertion dans requests
FOR EACH ROW                -- s'execute une fois par ligne inseree
DECLARE
    v_owner_id books.owner_id%TYPE;  -- type herite de la colonne books.owner_id
    v_title    books.title%TYPE;     -- type herite de la colonne books.title
BEGIN
    -- recuperer le proprietaire et le titre du livre concerne
    SELECT owner_id, title
    INTO v_owner_id, v_title
    FROM books
    WHERE id = :NEW.book_id;    -- :NEW.book_id = valeur du livre dans la nouvelle demande

    -- inserer une notification pour informer le proprietaire
    INSERT INTO notifications (user_id, sender_name, message, is_read, created_at)
    VALUES (
        v_owner_id,                                                  -- destinataire = proprietaire du livre
        'Systeme',                                                   -- expediteur automatique
        'Un utilisateur a demande votre livre "' || v_title || '".', -- texte avec le titre du livre
        0,                                                           -- 0 = notification non lue
        SYSDATE                                                      -- horodatage de la notification
    );
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        NULL;  -- livre introuvable : ne doit pas arriver grace aux cles etrangeres
END;
/

-- ================================================================
-- Trigger 4 : valider le format de l'email avant insertion
-- Type     : BEFORE INSERT, niveau ligne (FOR EACH ROW)
-- Table    : users
-- Objectif : bloquer l'insertion si l'email ne contient pas '@'
-- RAISE_APPLICATION_ERROR annule l'operation et retourne un message
-- Les codes entre -20000 et -20999 sont reserves aux erreurs custom
-- ================================================================
CREATE OR REPLACE TRIGGER trg_validate_user_email
BEFORE INSERT ON users   -- declenche avant chaque insertion dans users
FOR EACH ROW             -- s'execute une fois par ligne inseree
BEGIN
    -- INSTR retourne la position du caractere dans la chaine
    -- si '@' est absent, INSTR retourne 0 => email invalide
    IF INSTR(:NEW.email, '@') = 0 THEN
        -- interrompre l'insertion avec une erreur applicative explicite
        RAISE_APPLICATION_ERROR(-20001, 'Email invalide : le caractere @ est obligatoire.');
    END IF;
END;
/

-- ================================================================
-- Trigger 5 : trigger de niveau instruction (statement-level)
-- Type     : AFTER INSERT OR UPDATE OR DELETE, sans FOR EACH ROW
-- Table    : books
-- Objectif : montrer la difference entre row-level et statement-level
--
-- SANS FOR EACH ROW = trigger de niveau instruction :
--   - s'execute une seule fois par ordre DML, peu importe le nombre
--     de lignes affectees (1 ligne ou 1000 lignes = 1 seule execution)
--   - :NEW et :OLD ne sont PAS disponibles ici
--   - utile pour des audits globaux ou des controles d'acces
-- ================================================================
CREATE OR REPLACE TRIGGER trg_books_audit_statement
AFTER INSERT OR UPDATE OR DELETE ON books  -- reagit aux trois types d'operation
-- pas de FOR EACH ROW = declenchement au niveau instruction
BEGIN
    -- TO_CHAR convertit SYSDATE en texte lisible avec le format choisi
    DBMS_OUTPUT.PUT_LINE(
        'La table BOOKS a ete modifiee le ' ||
        TO_CHAR(SYSDATE, 'DD/MM/YYYY HH24:MI:SS')  -- format : jour/mois/annee heure:minute:seconde
    );
END;
/

-- ================================================================
-- Verification : lister tous les triggers du schema courant
-- USER_TRIGGERS est une vue du dictionnaire de donnees Oracle.
-- Elle contient les triggers qui appartiennent a l'utilisateur connecte.
-- ================================================================
SELECT
    trigger_name,       -- nom du trigger
    trigger_type,       -- BEFORE EACH ROW / AFTER EACH ROW / AFTER STATEMENT etc.
    triggering_event,   -- INSERT, UPDATE, DELETE ou combinaison
    table_name,         -- table sur laquelle le trigger est pose
    status              -- ENABLED = actif, DISABLED = desactive
FROM user_triggers
ORDER BY table_name, trigger_name;  -- trie par table puis par nom alphabetique
