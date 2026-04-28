/*
    Script 5 : procedures, fonctions, curseurs et triggers en PL/SQL.
    Tous les commentaires peuvent etre repris dans le rapport.
*/

-- Remarque :
-- %TYPE permet de reutiliser le type d'une colonne Oracle.
-- Cela evite d'ecrire manuellement NUMBER, VARCHAR2, etc.

-- Procedure 1 : ajouter une notification pour un utilisateur donne.
CREATE OR REPLACE PROCEDURE add_notification (
    p_user_id IN users.id%TYPE,
    p_message IN notifications.message%TYPE
) IS
BEGIN
    INSERT INTO notifications (user_id, message, is_read, created_at)
    VALUES (p_user_id, p_message, 0, SYSDATE);
END;
/

-- Procedure 2 : accepter une demande et mettre a jour les autres tables.
CREATE OR REPLACE PROCEDURE accept_request (
    p_request_id IN requests.id%TYPE,
    p_meeting_note IN requests.meeting_note%TYPE
) IS
    v_book_id requests.book_id%TYPE;
    v_requester_id requests.requester_id%TYPE;
    v_owner_id books.owner_id%TYPE;
    v_title books.title%TYPE;
BEGIN
    SELECT r.book_id, r.requester_id, b.owner_id, b.title
    INTO v_book_id, v_requester_id, v_owner_id, v_title
    FROM requests r
    JOIN books b ON b.id = r.book_id
    WHERE r.id = p_request_id;

    UPDATE requests
    SET status = 'accepted',
        meeting_note = p_meeting_note
    WHERE id = p_request_id;

    UPDATE requests
    SET status = 'rejected'
    WHERE book_id = v_book_id
      AND id <> p_request_id
      AND status = 'pending';

    UPDATE books
    SET status = 'reserved',
        updated_at = SYSDATE
    WHERE id = v_book_id;

    add_notification(v_requester_id, 'Votre demande pour le livre "' || v_title || '" a ete acceptee.');
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        DBMS_OUTPUT.PUT_LINE('Aucune demande ne correspond a l''identifiant fourni.');
END;
/

-- Fonction 1 : calculer le nombre de livres publies par un utilisateur.
CREATE OR REPLACE FUNCTION count_books_by_user (
    p_user_id IN users.id%TYPE
) RETURN NUMBER IS
    v_total NUMBER;
BEGIN
    SELECT COUNT(*)
    INTO v_total
    FROM books
    WHERE owner_id = p_user_id
      AND is_active = 1;

    RETURN v_total;
END;
/

-- Fonction 2 : calculer l'economie totale basee sur les echanges realises.
CREATE OR REPLACE FUNCTION calculate_money_saved
RETURN NUMBER IS
    v_total NUMBER;
BEGIN
    SELECT NVL(SUM(b.estimated_price), 0)
    INTO v_total
    FROM exchanges e
    JOIN books b ON b.id = e.book_id;

    RETURN v_total;
END;
/

/*
    Les triggers ont ete deplace dans le script dedie :
    database/06_triggers.sql

    Ce fichier contient uniquement les procedures, fonctions et curseurs.
*/

/*
    Bloc PL/SQL 1 : exemple de curseur implicite.
    SQL%ROWCOUNT permet de savoir combien de lignes ont ete modifiees.
    DBMS_OUTPUT.PUT_LINE affiche un message dans la console SQL Developer.
*/
BEGIN
    UPDATE notifications
    SET is_read = 1
    WHERE user_id = 2
      AND is_read = 0;

    DBMS_OUTPUT.PUT_LINE('Notifications marquees comme lues : ' || SQL%ROWCOUNT);
    ROLLBACK;
END;
/

/*
    Bloc PL/SQL 2 : exemple de curseur explicite pour parcourir les livres disponibles.
    %ROWTYPE cree une variable ayant la meme structure qu'une ligne du curseur.
*/
DECLARE
    CURSOR c_available_books IS
        SELECT id, title, subject
        FROM books
        WHERE status = 'available'
        ORDER BY created_at DESC;

    v_book c_available_books%ROWTYPE;
BEGIN
    OPEN c_available_books;
    LOOP
        FETCH c_available_books INTO v_book;
        EXIT WHEN c_available_books%NOTFOUND;

        DBMS_OUTPUT.PUT_LINE('Livre #' || v_book.id || ' : ' || v_book.title || ' - ' || v_book.subject);
    END LOOP;
    CLOSE c_available_books;
END;
/

/*
    Bloc PL/SQL 3 : utilisation de la procedure et des fonctions.
*/
DECLARE
    v_total_books NUMBER;
    v_money_saved NUMBER;
BEGIN
    add_notification(2, 'Test manuel de notification depuis PL/SQL.');
    accept_request(2, 'Rendez-vous confirme demain a 10h.');
    v_total_books := count_books_by_user(2);
    v_money_saved := calculate_money_saved();

    DBMS_OUTPUT.PUT_LINE('Nombre de livres de l''utilisateur 2 : ' || v_total_books);
    DBMS_OUTPUT.PUT_LINE('Economie totale estimee : ' || v_money_saved || ' DT');

    ROLLBACK;
END;
/
