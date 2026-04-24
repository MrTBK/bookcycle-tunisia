# Diagrammes Simplifies Pour La Soutenance

Ce fichier regroupe 6 diagrammes simples et propres a montrer au professeur.

## 1. Diagramme Des Cas D'utilisation

```mermaid
flowchart LR
    V["Visiteur"] --> UC1["Consulter l'accueil"]
    V --> UC2["Consulter le catalogue"]
    V --> UC3["Filtrer les livres"]
    V --> UC4["S'inscrire"]
    V --> UC5["Se connecter"]

    U["Utilisateur"] --> UC6["Ajouter un livre"]
    U --> UC7["Envoyer une demande"]
    U --> UC8["Consulter le tableau de bord"]
    U --> UC9["Consulter les notifications"]
    U --> UC10["Accepter ou refuser une demande"]

    A["Administrateur"] --> UC11["Consulter les statistiques"]
    A --> UC12["Gerer les utilisateurs"]
    A --> UC13["Moderer les livres"]
    A --> UC14["Annuler une demande"]
    A --> UC15["Envoyer des notifications"]
```

## 2. Diagramme De Classes De La Plateforme (To-Be)

```mermaid
classDiagram
    class Controller
    class PageController
    class AuthController
    class BookController
    class RequestController
    class AdminController
    class AcademicOption
    class User
    class Book
    class BookRequest
    class Notification
    class Database

    Controller <|-- PageController
    Controller <|-- AuthController
    Controller <|-- BookController
    Controller <|-- RequestController
    Controller <|-- AdminController

    PageController --> AcademicOption
    PageController --> Book
    PageController --> BookRequest
    PageController --> Notification
    PageController --> User
    AuthController --> User
    BookController --> Book
    BookController --> AcademicOption
    RequestController --> Book
    RequestController --> BookRequest
    RequestController --> Notification
    RequestController --> User
    AdminController --> User
    AdminController --> Book
    AdminController --> BookRequest
    AdminController --> Notification

    AcademicOption --> Database
    User --> Database
    Book --> Database
    BookRequest --> Database
    Notification --> Database
```

## 3. Diagramme As-Is / To-Be

```mermaid
flowchart LR
    subgraph A["As-Is : avant la plateforme"]
        A1["Recherche manuelle de livres"] --> A2["Contact direct entre personnes"]
        A2 --> A3["Traitement manuel des demandes"]
        A3 --> A4["Pas de suivi centralise"]
    end

    subgraph B["To-Be : BookCycle Tunisia"]
        B1["Catalogue en ligne"] --> B2["Demande depuis la plateforme"]
        B2 --> B3["Dashboard proprietaire"]
        B3 --> B4["Notifications et suivi"]
    end

    A4 --> B1
```

## 4. Diagramme De Sequence - Envoi D'une Demande

```mermaid
sequenceDiagram
    participant U as Utilisateur
    participant C as Catalogue
    participant RC as RequestController
    participant B as Book
    participant R as BookRequest
    participant N as Notification
    participant DB as Oracle

    U->>C: Cliquer sur "Envoyer une demande"
    C->>RC: POST /request-book
    RC->>B: find(bookId)
    B->>DB: Lire le livre
    DB-->>B: Livre trouve
    B-->>RC: Livre
    RC->>R: existsPending(bookId, userId)
    R->>DB: Verifier doublon
    DB-->>R: Resultat
    R-->>RC: OK
    RC->>R: create(bookId, userId)
    R->>DB: Inserer la demande
    RC->>N: create(ownerId, message)
    N->>DB: Inserer notification
    RC-->>U: Redirection vers dashboard
```

## 5. Diagramme De Sequence - Acceptation D'une Demande

```mermaid
sequenceDiagram
    participant P as Proprietaire
    participant D as Dashboard
    participant RC as RequestController
    participant R as BookRequest
    participant B as Book
    participant U as User
    participant N as Notification
    participant DB as Oracle

    P->>D: Cliquer sur "Accepter"
    D->>RC: POST /accept-request
    RC->>R: find(requestId)
    R->>DB: Lire la demande
    DB-->>R: Demande
    RC->>B: find(bookId)
    B->>DB: Lire le livre
    DB-->>B: Livre
    RC->>R: accept(requestId, bookId, note)
    R->>DB: Accepter la demande
    R->>DB: Rejeter les autres demandes
    RC->>B: updateStatus(reserved)
    B->>DB: Mettre le livre en reserve
    RC->>U: findById(...)
    U->>DB: Lire les utilisateurs
    RC->>N: create(...)
    N->>DB: Inserer notifications
    RC-->>P: Message de succes
```

## 6. Diagramme Entite-Relation

```mermaid
erDiagram
    USERS ||--o{ BOOKS : owns
    USERS ||--o{ REQUESTS : sends
    USERS ||--o{ NOTIFICATIONS : receives
    USERS ||--o{ EXCHANGES : owner
    USERS ||--o{ EXCHANGES : receiver
    BOOKS ||--o{ REQUESTS : gets
    BOOKS ||--o{ EXCHANGES : becomes

    USERS {
        int id
        string name
        string email
        string role
    }

    BOOKS {
        int id
        string title
        string subject
        string class_name
        string school_level
        float estimated_price
        int owner_id
        string status
    }

    REQUESTS {
        int id
        int book_id
        int requester_id
        string status
        string meeting_note
    }

    NOTIFICATIONS {
        int id
        int user_id
        string sender_name
        string message
        int is_read
    }

    EXCHANGES {
        int id
        int book_id
        int owner_id
        int receiver_id
        string status
    }
```

## Conseils De Presentation

- montre d'abord le diagramme des cas d'utilisation
- puis le diagramme de classes de la plateforme
- montre le diagramme `As-Is / To-Be` seulement si on te demande la partie BPR
- ensuite un seul diagramme de sequence si le temps est court
- termine par le diagramme entite-relation pour relier la partie web a la base
