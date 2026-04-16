/*
    Script 3 : insertion de donnees de demonstration.
    A executer connecte en tant que BOOKCYCLE_APP.
*/

INSERT INTO users (name, email, password, phone, role)
VALUES ('Administrateur', 'admin@bookcycle.tn', '$2y$10$ejRkCqICFke3GUQ/eDtqQ.PidaEXSfj2MIt9a8TCHQo2ry/Uf8oRm', '00000000', 'admin');

INSERT INTO users (name, email, password, phone, role)
VALUES ('Ahmed Ben Salem', 'ahmed@bookcycle.tn', '$2y$10$eHXcPxUFSHoHsVhGf12v9OsIxPcWEnPb6LRHTTnay9dw329avsr3e', '22111222', 'user');

INSERT INTO users (name, email, password, phone, role)
VALUES ('Sarra Trabelsi', 'sarra@bookcycle.tn', '$2y$10$8NCFh6eMdQ.T9OFQaCtY5eOrRFglwyWrViDmCIzbeTgVk1k18Vy.q', '55111333', 'user');

INSERT INTO users (name, email, password, phone, role)
VALUES ('Youssef Hamdi', 'youssef@bookcycle.tn', '$2y$10$6wBxyuNDL1CkBuldMDMnKuF91Bs8cuCDs9K6IC4Uw8HMy2Z3tUf.u', '99111444', 'user');

INSERT INTO books (title, subject, class_name, school_level, condition_label, estimated_price, description, owner_id, status, is_active)
VALUES ('Mathematiques - 9eme - College', 'Mathematiques', '9eme', 'College', 'Bon', 28, 'Livre propre et complet.', 2, 'available', 1);

INSERT INTO books (title, subject, class_name, school_level, condition_label, estimated_price, description, owner_id, status, is_active)
VALUES ('Sciences - 1ere annee - Lycee', 'Sciences', '1ere annee', 'Lycee', 'Neuf', 35, 'Edition recente.', 2, 'available', 1);

INSERT INTO books (title, subject, class_name, school_level, condition_label, estimated_price, description, owner_id, status, is_active)
VALUES ('Arabe - 6eme - Primaire', 'Arabe', '6eme', 'Primaire', 'Usage', 18, 'Bon etat general.', 3, 'reserved', 1);

INSERT INTO books (title, subject, class_name, school_level, condition_label, estimated_price, description, owner_id, status, is_active)
VALUES ('Physique - Bac - Lycee', 'Physique', 'Bac', 'Lycee', 'Bon', 42, 'Avec exercices supplementaires.', 4, 'exchanged', 1);

INSERT INTO requests (book_id, requester_id, status, meeting_note, request_date)
VALUES (1, 3, 'pending', NULL, SYSDATE - 2);

INSERT INTO requests (book_id, requester_id, status, meeting_note, request_date)
VALUES (3, 2, 'accepted', 'Rendez-vous devant le lycee demain a 16h.', SYSDATE - 5);

INSERT INTO requests (book_id, requester_id, status, meeting_note, request_date)
VALUES (4, 3, 'rejected', 'Livre deja donne.', SYSDATE - 9);

INSERT INTO exchanges (book_id, owner_id, receiver_id, exchange_date, status)
VALUES (4, 4, 3, SYSDATE - 8, 'completed');

INSERT INTO notifications (user_id, message, is_read, created_at)
VALUES (2, 'Vous avez recu une nouvelle demande pour le livre Mathematiques 9eme.', 0, SYSDATE - 2);

INSERT INTO notifications (user_id, message, is_read, created_at)
VALUES (3, 'Votre demande pour Arabe 6eme a ete acceptee.', 1, SYSDATE - 4);

COMMIT;
