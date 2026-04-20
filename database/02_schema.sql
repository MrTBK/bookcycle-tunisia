/*
    Script 2 : creation du schema relationnel Oracle.
    A executer connecte en tant que BOOKCYCLE_APP.
*/

/*
    This is the main database script.
    It creates the real structure used by the app:
    - tables
    - constraints
    - sequences
    - triggers
    - indexes
    - one reporting view
*/

-- TABLE USERS : stocke les differents acteurs de la plateforme.
-- This table stores the people who use the platform.
CREATE TABLE users (
    id NUMBER PRIMARY KEY,
    name VARCHAR2(120) NOT NULL,
    email VARCHAR2(160) NOT NULL UNIQUE,
    password VARCHAR2(255) NOT NULL,
    phone VARCHAR2(30) NOT NULL,
    role VARCHAR2(20) DEFAULT 'user' NOT NULL,
    is_active NUMBER(1) DEFAULT 1 NOT NULL,
    created_at DATE DEFAULT SYSDATE NOT NULL,
    CONSTRAINT chk_users_role CHECK (role IN ('admin', 'user')),
    CONSTRAINT chk_users_active CHECK (is_active IN (0, 1))
);

-- TABLE BOOKS : stocke les livres publies par les utilisateurs.
-- This table stores the books shared on the platform.
CREATE TABLE books (
    id NUMBER PRIMARY KEY,
    title VARCHAR2(180) NOT NULL,
    subject VARCHAR2(120) NOT NULL,
    class_name VARCHAR2(60) DEFAULT 'Non precise' NOT NULL,
    school_level VARCHAR2(40) NOT NULL,
    condition_label VARCHAR2(40) NOT NULL,
    estimated_price NUMBER(10,2) DEFAULT 0 NOT NULL,
    description VARCHAR2(1000),
    owner_id NUMBER NOT NULL,
    status VARCHAR2(20) DEFAULT 'available' NOT NULL,
    is_active NUMBER(1) DEFAULT 1 NOT NULL,
    created_at DATE DEFAULT SYSDATE NOT NULL,
    updated_at DATE DEFAULT SYSDATE NOT NULL,
    CONSTRAINT fk_books_owner FOREIGN KEY (owner_id) REFERENCES users(id),
    CONSTRAINT chk_books_status CHECK (status IN ('available', 'reserved', 'exchanged')),
    CONSTRAINT chk_books_active CHECK (is_active IN (0, 1))
);

-- TABLE REQUESTS : stocke les demandes de reservation des livres.
-- This table stores who asked for which book.
CREATE TABLE requests (
    id NUMBER PRIMARY KEY,
    book_id NUMBER NOT NULL,
    requester_id NUMBER NOT NULL,
    status VARCHAR2(20) DEFAULT 'pending' NOT NULL,
    meeting_note VARCHAR2(1000),
    request_date DATE DEFAULT SYSDATE NOT NULL,
    CONSTRAINT fk_requests_book FOREIGN KEY (book_id) REFERENCES books(id),
    CONSTRAINT fk_requests_requester FOREIGN KEY (requester_id) REFERENCES users(id),
    CONSTRAINT chk_requests_status CHECK (status IN ('pending', 'accepted', 'rejected'))
);

-- TABLE EXCHANGES : historise les echanges finalises.
-- This table stores completed exchanges for history and reporting.
CREATE TABLE exchanges (
    id NUMBER PRIMARY KEY,
    book_id NUMBER NOT NULL,
    owner_id NUMBER NOT NULL,
    receiver_id NUMBER NOT NULL,
    exchange_date DATE DEFAULT SYSDATE NOT NULL,
    status VARCHAR2(20) DEFAULT 'completed' NOT NULL,
    CONSTRAINT fk_exchanges_book FOREIGN KEY (book_id) REFERENCES books(id),
    CONSTRAINT fk_exchanges_owner FOREIGN KEY (owner_id) REFERENCES users(id),
    CONSTRAINT fk_exchanges_receiver FOREIGN KEY (receiver_id) REFERENCES users(id)
);

-- TABLE NOTIFICATIONS : gere les notifications applicatives.
-- This table stores in-app messages shown to users.
CREATE TABLE notifications (
    id NUMBER PRIMARY KEY,
    user_id NUMBER NOT NULL,
    sender_name VARCHAR2(120) DEFAULT 'Systeme' NOT NULL,
    message VARCHAR2(1000) NOT NULL,
    is_read NUMBER(1) DEFAULT 0 NOT NULL,
    created_at DATE DEFAULT SYSDATE NOT NULL,
    CONSTRAINT fk_notifications_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT chk_notifications_read CHECK (is_read IN (0, 1))
);

-- Sequences 11g pour remplacer les identity columns.
-- Dans Oracle XE / 11g, on utilise souvent une sequence + un trigger
-- pour generer automatiquement l'identifiant primaire.
-- Each sequence is like an automatic number generator.
CREATE SEQUENCE seq_users START WITH 1 INCREMENT BY 1 NOCACHE;
CREATE SEQUENCE seq_books START WITH 1 INCREMENT BY 1 NOCACHE;
CREATE SEQUENCE seq_requests START WITH 1 INCREMENT BY 1 NOCACHE;
CREATE SEQUENCE seq_exchanges START WITH 1 INCREMENT BY 1 NOCACHE;
CREATE SEQUENCE seq_notifications START WITH 1 INCREMENT BY 1 NOCACHE;

-- Triggers d'auto-incrementation compatibles Oracle 11g XE.
-- Each trigger takes the next number from its sequence before an INSERT.
CREATE OR REPLACE TRIGGER trg_users_pk
BEFORE INSERT ON users
FOR EACH ROW
BEGIN
    IF :NEW.id IS NULL THEN
        SELECT seq_users.NEXTVAL INTO :NEW.id FROM dual;
    END IF;
END;
/

CREATE OR REPLACE TRIGGER trg_books_pk
BEFORE INSERT ON books
FOR EACH ROW
BEGIN
    IF :NEW.id IS NULL THEN
        SELECT seq_books.NEXTVAL INTO :NEW.id FROM dual;
    END IF;
END;
/

CREATE OR REPLACE TRIGGER trg_requests_pk
BEFORE INSERT ON requests
FOR EACH ROW
BEGIN
    IF :NEW.id IS NULL THEN
        SELECT seq_requests.NEXTVAL INTO :NEW.id FROM dual;
    END IF;
END;
/

CREATE OR REPLACE TRIGGER trg_exchanges_pk
BEFORE INSERT ON exchanges
FOR EACH ROW
BEGIN
    IF :NEW.id IS NULL THEN
        SELECT seq_exchanges.NEXTVAL INTO :NEW.id FROM dual;
    END IF;
END;
/

CREATE OR REPLACE TRIGGER trg_notifications_pk
BEFORE INSERT ON notifications
FOR EACH ROW
BEGIN
    IF :NEW.id IS NULL THEN
        SELECT seq_notifications.NEXTVAL INTO :NEW.id FROM dual;
    END IF;
END;
/

-- INDEX utiles pour accelerer les recherches courantes.
-- Indexes make frequent searches faster.
CREATE INDEX idx_books_owner ON books(owner_id);
CREATE INDEX idx_books_subject ON books(subject);
CREATE INDEX idx_books_level ON books(school_level);
CREATE INDEX idx_requests_book ON requests(book_id);
CREATE INDEX idx_requests_requester ON requests(requester_id);
CREATE INDEX idx_notifications_user ON notifications(user_id);

-- Vue utile pour le reporting et les requetes multi-tables.
-- Une vue est une requete enregistree que l'on peut reutiliser comme table logique.
-- This view combines book data and owner data into one easier reporting result.
CREATE OR REPLACE VIEW v_book_overview AS
SELECT
    b.id AS book_id,
    b.title,
    b.subject,
    b.class_name,
    b.school_level,
    b.condition_label,
    b.estimated_price,
    b.status,
    u.name AS owner_name,
    u.email AS owner_email,
    b.created_at
FROM books b
JOIN users u ON u.id = b.owner_id;

/*
    Privileges donnes a l'utilisateur de reporting.
*/
-- The reporting user can read data but not modify it.
GRANT SELECT ON users TO bookcycle_report;
GRANT SELECT ON books TO bookcycle_report;
GRANT SELECT ON requests TO bookcycle_report;
GRANT SELECT ON exchanges TO bookcycle_report;
GRANT SELECT ON notifications TO bookcycle_report;
GRANT SELECT ON v_book_overview TO bookcycle_report;
