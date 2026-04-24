/*
    Script 3 : insertion de donnees de demonstration.
    A executer connecte en tant que BOOKCYCLE_APP.
*/

INSERT INTO users (name, email, password, phone, role, is_active)
VALUES ('Administrateur', 'admin@bookcycle.tn', '$2y$10$ejRkCqICFke3GUQ/eDtqQ.PidaEXSfj2MIt9a8TCHQo2ry/Uf8oRm', '00000000', 'admin', 1);

INSERT INTO users (name, email, password, phone, role, is_active)
VALUES ('Ahmed Ben Salem', 'ahmed@bookcycle.tn', '$2y$10$eHXcPxUFSHoHsVhGf12v9OsIxPcWEnPb6LRHTTnay9dw329avsr3e', '22111222', 'user', 1);

INSERT INTO users (name, email, password, phone, role, is_active)
VALUES ('Sarra Trabelsi', 'sarra@bookcycle.tn', '$2y$10$8NCFh6eMdQ.T9OFQaCtY5eOrRFglwyWrViDmCIzbeTgVk1k18Vy.q', '55111333', 'user', 1);

INSERT INTO users (name, email, password, phone, role, is_active)
VALUES ('Youssef Hamdi', 'youssef@bookcycle.tn', '$2y$10$6wBxyuNDL1CkBuldMDMnKuF91Bs8cuCDs9K6IC4Uw8HMy2Z3tUf.u', '99111444', 'user', 1);

INSERT INTO subjects (name, sort_order, is_active) VALUES ('Arabe', 1, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Mathematiques', 2, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Francais', 3, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Anglais', 4, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Education islamique', 5, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Education civique', 6, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Sciences', 7, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('SVT', 8, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Physique-Chimie', 9, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Histoire-Geographie', 10, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Histoire', 11, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Geographie', 12, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Informatique', 13, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Technique', 14, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Philosophie', 15, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Sport', 16, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Programmation', 17, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Economie', 18, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Gestion', 19, 1);

INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Primaire', '1ere annee', 1, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Primaire', '2eme annee', 2, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Primaire', '3eme annee', 3, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Primaire', '4eme annee', 4, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Primaire', '5eme annee', 5, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Primaire', '6eme annee', 6, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('College', '7eme annee', 1, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('College', '8eme annee', 2, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('College', '9eme annee', 3, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', '1ere secondaire', 1, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', '2eme informatique', 2, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', '2eme sc', 3, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', '2eme lettre', 4, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', '2eme eco', 5, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', '3eme math', 6, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', '3eme tech', 7, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', '3eme info', 8, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', '3eme sc', 9, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', '3eme lettre', 10, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', '3eme eco', 11, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', 'bac math', 12, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', 'bac tech', 13, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', 'bac info', 14, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', 'bac sc', 15, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', 'bac lettre', 16, 1);
INSERT INTO school_classes (school_level, class_name, sort_order, is_active) VALUES ('Lycee', 'bac eco', 17, 1);

DECLARE
    PROCEDURE add_class_subject(
        p_level VARCHAR2,
        p_class_name VARCHAR2,
        p_subject_name VARCHAR2,
        p_sort_order NUMBER
    ) IS
    BEGIN
        INSERT INTO class_subjects (class_id, subject_id, sort_order, is_active)
        SELECT c.id, s.id, p_sort_order, 1
        FROM school_classes c
        INNER JOIN subjects s ON s.name = p_subject_name
        WHERE c.school_level = p_level
          AND c.class_name = p_class_name;
    END;
BEGIN
    add_class_subject('Primaire', '1ere annee', 'Arabe', 1);
    add_class_subject('Primaire', '1ere annee', 'Mathematiques', 2);
    add_class_subject('Primaire', '1ere annee', 'Education islamique', 3);
    add_class_subject('Primaire', '1ere annee', 'Education civique', 4);

    add_class_subject('Primaire', '2eme annee', 'Arabe', 1);
    add_class_subject('Primaire', '2eme annee', 'Mathematiques', 2);
    add_class_subject('Primaire', '2eme annee', 'Education islamique', 3);
    add_class_subject('Primaire', '2eme annee', 'Education civique', 4);

    add_class_subject('Primaire', '3eme annee', 'Arabe', 1);
    add_class_subject('Primaire', '3eme annee', 'Mathematiques', 2);
    add_class_subject('Primaire', '3eme annee', 'Francais', 3);
    add_class_subject('Primaire', '3eme annee', 'Education islamique', 4);
    add_class_subject('Primaire', '3eme annee', 'Education civique', 5);

    add_class_subject('Primaire', '4eme annee', 'Arabe', 1);
    add_class_subject('Primaire', '4eme annee', 'Mathematiques', 2);
    add_class_subject('Primaire', '4eme annee', 'Francais', 3);
    add_class_subject('Primaire', '4eme annee', 'Sciences', 4);
    add_class_subject('Primaire', '4eme annee', 'Histoire-Geographie', 5);
    add_class_subject('Primaire', '4eme annee', 'Education islamique', 6);
    add_class_subject('Primaire', '4eme annee', 'Education civique', 7);

    add_class_subject('Primaire', '5eme annee', 'Arabe', 1);
    add_class_subject('Primaire', '5eme annee', 'Mathematiques', 2);
    add_class_subject('Primaire', '5eme annee', 'Francais', 3);
    add_class_subject('Primaire', '5eme annee', 'Anglais', 4);
    add_class_subject('Primaire', '5eme annee', 'Sciences', 5);
    add_class_subject('Primaire', '5eme annee', 'Histoire-Geographie', 6);
    add_class_subject('Primaire', '5eme annee', 'Education islamique', 7);
    add_class_subject('Primaire', '5eme annee', 'Education civique', 8);

    add_class_subject('Primaire', '6eme annee', 'Arabe', 1);
    add_class_subject('Primaire', '6eme annee', 'Mathematiques', 2);
    add_class_subject('Primaire', '6eme annee', 'Francais', 3);
    add_class_subject('Primaire', '6eme annee', 'Sciences', 4);
    add_class_subject('Primaire', '6eme annee', 'Histoire-Geographie', 5);
    add_class_subject('Primaire', '6eme annee', 'Education islamique', 6);
    add_class_subject('Primaire', '6eme annee', 'Education civique', 7);
    add_class_subject('Primaire', '6eme annee', 'Anglais', 8);

    add_class_subject('College', '7eme annee', 'Arabe', 1);
    add_class_subject('College', '7eme annee', 'Francais', 2);
    add_class_subject('College', '7eme annee', 'Anglais', 3);
    add_class_subject('College', '7eme annee', 'Mathematiques', 4);
    add_class_subject('College', '7eme annee', 'SVT', 5);
    add_class_subject('College', '7eme annee', 'Physique-Chimie', 6);
    add_class_subject('College', '7eme annee', 'Histoire-Geographie', 7);
    add_class_subject('College', '7eme annee', 'Informatique', 8);
    add_class_subject('College', '7eme annee', 'Education islamique', 9);
    add_class_subject('College', '7eme annee', 'Technique', 10);

    add_class_subject('College', '8eme annee', 'Arabe', 1);
    add_class_subject('College', '8eme annee', 'Francais', 2);
    add_class_subject('College', '8eme annee', 'Anglais', 3);
    add_class_subject('College', '8eme annee', 'Mathematiques', 4);
    add_class_subject('College', '8eme annee', 'SVT', 5);
    add_class_subject('College', '8eme annee', 'Physique-Chimie', 6);
    add_class_subject('College', '8eme annee', 'Histoire-Geographie', 7);
    add_class_subject('College', '8eme annee', 'Informatique', 8);
    add_class_subject('College', '8eme annee', 'Education islamique', 9);
    add_class_subject('College', '8eme annee', 'Technique', 10);

    add_class_subject('College', '9eme annee', 'Arabe', 1);
    add_class_subject('College', '9eme annee', 'Francais', 2);
    add_class_subject('College', '9eme annee', 'Anglais', 3);
    add_class_subject('College', '9eme annee', 'Mathematiques', 4);
    add_class_subject('College', '9eme annee', 'SVT', 5);
    add_class_subject('College', '9eme annee', 'Physique-Chimie', 6);
    add_class_subject('College', '9eme annee', 'Histoire-Geographie', 7);
    add_class_subject('College', '9eme annee', 'Informatique', 8);
    add_class_subject('College', '9eme annee', 'Technique', 9);
    add_class_subject('College', '9eme annee', 'Education islamique', 10);

    add_class_subject('Lycee', '1ere secondaire', 'Arabe', 1);
    add_class_subject('Lycee', '1ere secondaire', 'Francais', 2);
    add_class_subject('Lycee', '1ere secondaire', 'Anglais', 3);
    add_class_subject('Lycee', '1ere secondaire', 'Mathematiques', 4);
    add_class_subject('Lycee', '1ere secondaire', 'Physique-Chimie', 5);
    add_class_subject('Lycee', '1ere secondaire', 'SVT', 6);
    add_class_subject('Lycee', '1ere secondaire', 'Histoire', 7);
    add_class_subject('Lycee', '1ere secondaire', 'Geographie', 8);
    add_class_subject('Lycee', '1ere secondaire', 'Technique', 9);
    add_class_subject('Lycee', '1ere secondaire', 'Informatique', 10);
    add_class_subject('Lycee', '1ere secondaire', 'Philosophie', 11);
    add_class_subject('Lycee', '1ere secondaire', 'Sport', 12);

    add_class_subject('Lycee', '2eme informatique', 'Mathematiques', 1);
    add_class_subject('Lycee', '2eme informatique', 'Physique-Chimie', 2);
    add_class_subject('Lycee', '2eme informatique', 'Programmation', 3);
    add_class_subject('Lycee', '2eme informatique', 'Arabe', 4);
    add_class_subject('Lycee', '2eme informatique', 'Francais', 5);
    add_class_subject('Lycee', '2eme informatique', 'Anglais', 6);
    add_class_subject('Lycee', '2eme informatique', 'Histoire-Geographie', 7);
    add_class_subject('Lycee', '2eme informatique', 'Technique', 8);

    add_class_subject('Lycee', '2eme sc', 'Mathematiques', 1);
    add_class_subject('Lycee', '2eme sc', 'Physique-Chimie', 2);
    add_class_subject('Lycee', '2eme sc', 'SVT', 3);
    add_class_subject('Lycee', '2eme sc', 'Arabe', 4);
    add_class_subject('Lycee', '2eme sc', 'Francais', 5);
    add_class_subject('Lycee', '2eme sc', 'Anglais', 6);
    add_class_subject('Lycee', '2eme sc', 'Histoire-Geographie', 7);
    add_class_subject('Lycee', '2eme sc', 'Informatique', 8);
    add_class_subject('Lycee', '2eme sc', 'Technique', 9);

    add_class_subject('Lycee', '2eme lettre', 'Arabe', 1);
    add_class_subject('Lycee', '2eme lettre', 'Francais', 2);
    add_class_subject('Lycee', '2eme lettre', 'Anglais', 3);
    add_class_subject('Lycee', '2eme lettre', 'Histoire', 4);
    add_class_subject('Lycee', '2eme lettre', 'Geographie', 5);
    add_class_subject('Lycee', '2eme lettre', 'Mathematiques', 6);

    add_class_subject('Lycee', '2eme eco', 'Economie', 1);
    add_class_subject('Lycee', '2eme eco', 'Gestion', 2);
    add_class_subject('Lycee', '2eme eco', 'Mathematiques', 3);
    add_class_subject('Lycee', '2eme eco', 'Arabe', 4);
    add_class_subject('Lycee', '2eme eco', 'Francais', 5);
    add_class_subject('Lycee', '2eme eco', 'Anglais', 6);
    add_class_subject('Lycee', '2eme eco', 'Histoire-Geographie', 7);

    add_class_subject('Lycee', '3eme math', 'Mathematiques', 1);
    add_class_subject('Lycee', '3eme math', 'Physique-Chimie', 2);
    add_class_subject('Lycee', '3eme math', 'SVT', 3);
    add_class_subject('Lycee', '3eme math', 'Arabe', 4);
    add_class_subject('Lycee', '3eme math', 'Francais', 5);
    add_class_subject('Lycee', '3eme math', 'Anglais', 6);
    add_class_subject('Lycee', '3eme math', 'Philosophie', 7);

    add_class_subject('Lycee', '3eme tech', 'Technique', 1);
    add_class_subject('Lycee', '3eme tech', 'Physique-Chimie', 2);
    add_class_subject('Lycee', '3eme tech', 'Mathematiques', 3);
    add_class_subject('Lycee', '3eme tech', 'Arabe', 4);
    add_class_subject('Lycee', '3eme tech', 'Francais', 5);
    add_class_subject('Lycee', '3eme tech', 'Anglais', 6);
    add_class_subject('Lycee', '3eme tech', 'Philosophie', 7);

    add_class_subject('Lycee', '3eme info', 'Informatique', 1);
    add_class_subject('Lycee', '3eme info', 'Programmation', 2);
    add_class_subject('Lycee', '3eme info', 'Mathematiques', 3);
    add_class_subject('Lycee', '3eme info', 'Physique-Chimie', 4);
    add_class_subject('Lycee', '3eme info', 'Arabe', 5);
    add_class_subject('Lycee', '3eme info', 'Francais', 6);
    add_class_subject('Lycee', '3eme info', 'Anglais', 7);
    add_class_subject('Lycee', '3eme info', 'Philosophie', 8);

    add_class_subject('Lycee', '3eme sc', 'SVT', 1);
    add_class_subject('Lycee', '3eme sc', 'Physique-Chimie', 2);
    add_class_subject('Lycee', '3eme sc', 'Mathematiques', 3);
    add_class_subject('Lycee', '3eme sc', 'Arabe', 4);
    add_class_subject('Lycee', '3eme sc', 'Francais', 5);
    add_class_subject('Lycee', '3eme sc', 'Anglais', 6);
    add_class_subject('Lycee', '3eme sc', 'Philosophie', 7);

    add_class_subject('Lycee', '3eme lettre', 'Arabe', 1);
    add_class_subject('Lycee', '3eme lettre', 'Francais', 2);
    add_class_subject('Lycee', '3eme lettre', 'Anglais', 3);
    add_class_subject('Lycee', '3eme lettre', 'Histoire', 4);
    add_class_subject('Lycee', '3eme lettre', 'Geographie', 5);
    add_class_subject('Lycee', '3eme lettre', 'Philosophie', 6);

    add_class_subject('Lycee', '3eme eco', 'Economie', 1);
    add_class_subject('Lycee', '3eme eco', 'Gestion', 2);
    add_class_subject('Lycee', '3eme eco', 'Mathematiques', 3);
    add_class_subject('Lycee', '3eme eco', 'Arabe', 4);
    add_class_subject('Lycee', '3eme eco', 'Francais', 5);
    add_class_subject('Lycee', '3eme eco', 'Anglais', 6);
    add_class_subject('Lycee', '3eme eco', 'Philosophie', 7);

    add_class_subject('Lycee', 'bac math', 'Mathematiques', 1);
    add_class_subject('Lycee', 'bac math', 'Physique-Chimie', 2);
    add_class_subject('Lycee', 'bac math', 'SVT', 3);
    add_class_subject('Lycee', 'bac math', 'Arabe', 4);
    add_class_subject('Lycee', 'bac math', 'Francais', 5);
    add_class_subject('Lycee', 'bac math', 'Anglais', 6);
    add_class_subject('Lycee', 'bac math', 'Philosophie', 7);

    add_class_subject('Lycee', 'bac tech', 'Technique', 1);
    add_class_subject('Lycee', 'bac tech', 'Physique-Chimie', 2);
    add_class_subject('Lycee', 'bac tech', 'Mathematiques', 3);
    add_class_subject('Lycee', 'bac tech', 'Arabe', 4);
    add_class_subject('Lycee', 'bac tech', 'Francais', 5);
    add_class_subject('Lycee', 'bac tech', 'Anglais', 6);
    add_class_subject('Lycee', 'bac tech', 'Philosophie', 7);

    add_class_subject('Lycee', 'bac info', 'Informatique', 1);
    add_class_subject('Lycee', 'bac info', 'Programmation', 2);
    add_class_subject('Lycee', 'bac info', 'Mathematiques', 3);
    add_class_subject('Lycee', 'bac info', 'Physique-Chimie', 4);
    add_class_subject('Lycee', 'bac info', 'Arabe', 5);
    add_class_subject('Lycee', 'bac info', 'Francais', 6);
    add_class_subject('Lycee', 'bac info', 'Anglais', 7);
    add_class_subject('Lycee', 'bac info', 'Philosophie', 8);

    add_class_subject('Lycee', 'bac sc', 'SVT', 1);
    add_class_subject('Lycee', 'bac sc', 'Physique-Chimie', 2);
    add_class_subject('Lycee', 'bac sc', 'Mathematiques', 3);
    add_class_subject('Lycee', 'bac sc', 'Arabe', 4);
    add_class_subject('Lycee', 'bac sc', 'Francais', 5);
    add_class_subject('Lycee', 'bac sc', 'Anglais', 6);
    add_class_subject('Lycee', 'bac sc', 'Philosophie', 7);

    add_class_subject('Lycee', 'bac lettre', 'Arabe', 1);
    add_class_subject('Lycee', 'bac lettre', 'Francais', 2);
    add_class_subject('Lycee', 'bac lettre', 'Anglais', 3);
    add_class_subject('Lycee', 'bac lettre', 'Histoire', 4);
    add_class_subject('Lycee', 'bac lettre', 'Geographie', 5);
    add_class_subject('Lycee', 'bac lettre', 'Philosophie', 6);

    add_class_subject('Lycee', 'bac eco', 'Economie', 1);
    add_class_subject('Lycee', 'bac eco', 'Gestion', 2);
    add_class_subject('Lycee', 'bac eco', 'Mathematiques', 3);
    add_class_subject('Lycee', 'bac eco', 'Arabe', 4);
    add_class_subject('Lycee', 'bac eco', 'Francais', 5);
    add_class_subject('Lycee', 'bac eco', 'Anglais', 6);
    add_class_subject('Lycee', 'bac eco', 'Philosophie', 7);
END;
/

INSERT INTO books (title, subject, class_name, school_level, condition_label, estimated_price, description, owner_id, status, is_active)
VALUES ('Mathematiques - 9eme annee - College', 'Mathematiques', '9eme annee', 'College', 'Bon', 28, 'Livre propre et complet.', 2, 'available', 1);

INSERT INTO books (title, subject, class_name, school_level, condition_label, estimated_price, description, owner_id, status, is_active)
VALUES ('SVT - 1ere secondaire - Lycee', 'SVT', '1ere secondaire', 'Lycee', 'Neuf', 35, 'Edition recente.', 2, 'available', 1);

INSERT INTO books (title, subject, class_name, school_level, condition_label, estimated_price, description, owner_id, status, is_active)
VALUES ('Arabe - 6eme annee - Primaire', 'Arabe', '6eme annee', 'Primaire', 'Usage', 18, 'Bon etat general.', 3, 'reserved', 1);

INSERT INTO books (title, subject, class_name, school_level, condition_label, estimated_price, description, owner_id, status, is_active)
VALUES ('Physique-Chimie - bac math - Lycee', 'Physique-Chimie', 'bac math', 'Lycee', 'Bon', 42, 'Avec exercices supplementaires.', 4, 'exchanged', 1);

INSERT INTO requests (book_id, requester_id, status, meeting_note, request_date)
VALUES (1, 3, 'pending', NULL, SYSDATE - 2);

INSERT INTO requests (book_id, requester_id, status, meeting_note, request_date)
VALUES (3, 2, 'accepted', 'Rendez-vous devant le lycee demain a 16h.', SYSDATE - 5);

INSERT INTO requests (book_id, requester_id, status, meeting_note, request_date)
VALUES (4, 3, 'rejected', 'Livre deja donne.', SYSDATE - 9);

INSERT INTO exchanges (book_id, owner_id, receiver_id, exchange_date, status)
VALUES (4, 4, 3, SYSDATE - 8, 'completed');

INSERT INTO notifications (user_id, sender_name, message, is_read, created_at)
VALUES (2, 'Sarra Trabelsi', 'Vous avez recu une nouvelle demande pour le livre Mathematiques 9eme.', 0, SYSDATE - 2);

INSERT INTO notifications (user_id, sender_name, message, is_read, created_at)
VALUES (3, 'Ahmed Ben Salem', 'Votre demande pour Arabe 6eme a ete acceptee.', 1, SYSDATE - 4);

COMMIT;
