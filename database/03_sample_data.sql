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
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Francais', 2, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Anglais', 3, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Mathematiques', 4, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Sciences', 5, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Physique', 6, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Chimie', 7, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Informatique', 8, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Histoire', 9, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Geographie', 10, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Education islamique', 11, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Philosophie', 12, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Economie', 13, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Gestion', 14, 1);
INSERT INTO subjects (name, sort_order, is_active) VALUES ('Technique', 15, 1);

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

INSERT INTO books (title, subject, class_name, school_level, condition_label, estimated_price, description, owner_id, status, is_active)
VALUES ('Mathematiques - 9eme annee - College', 'Mathematiques', '9eme annee', 'College', 'Bon', 28, 'Livre propre et complet.', 2, 'available', 1);

INSERT INTO books (title, subject, class_name, school_level, condition_label, estimated_price, description, owner_id, status, is_active)
VALUES ('Sciences - 1ere secondaire - Lycee', 'Sciences', '1ere secondaire', 'Lycee', 'Neuf', 35, 'Edition recente.', 2, 'available', 1);

INSERT INTO books (title, subject, class_name, school_level, condition_label, estimated_price, description, owner_id, status, is_active)
VALUES ('Arabe - 6eme annee - Primaire', 'Arabe', '6eme annee', 'Primaire', 'Usage', 18, 'Bon etat general.', 3, 'reserved', 1);

INSERT INTO books (title, subject, class_name, school_level, condition_label, estimated_price, description, owner_id, status, is_active)
VALUES ('Physique - bac math - Lycee', 'Physique', 'bac math', 'Lycee', 'Bon', 42, 'Avec exercices supplementaires.', 4, 'exchanged', 1);

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
