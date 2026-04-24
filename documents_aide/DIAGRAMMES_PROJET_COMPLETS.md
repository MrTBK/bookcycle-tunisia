# Diagrammes Projet Complets

Ce fichier regroupe des diagrammes Mermaid utiles pour comprendre et presenter **BookCycle Tunisia**.

## 1. Vue D'Ensemble De L'Architecture

```mermaid
flowchart LR
    U["Utilisateur"] --> P["public/index.php"]
    P --> C["Controllers"]
    C --> M["Models"]
    M --> D["Oracle Database"]
    C --> V["Views"]
    V --> U
```

## 2. Structure MVC Du Projet

```mermaid
flowchart TD
    A["public/index.php"] --> C["PageController"]
    A --> D["AuthController"]
    A --> E["BookController"]
    A --> F["RequestController"]
    A --> G["AdminController"]
    A --> H["NotificationController"]

    C --> I["Views/pages"]
    D --> J["User model"]
    E --> K["Book model"]
    E --> O["AcademicOption model"]
    F --> L["BookRequest model"]
    F --> K
    F --> M["Notification model"]
    G --> J
    G --> K
    G --> L
    G --> M

    J --> N["Oracle"]
    K --> N
    L --> N
    M --> N
```

## 3. Diagramme Des Acteurs Et Fonctions

```mermaid
flowchart LR
    V["Visiteur"] --> V1["Voir accueil"]
    V --> V2["Voir catalogue"]
    V --> V3["Filtrer livres"]
    V --> V4["S'inscrire / se connecter"]

    U["Utilisateur"] --> U1["Ajouter un livre"]
    U --> U2["Envoyer une demande"]
    U --> U3["Voir dashboard"]
    U --> U4["Voir notifications"]

    A["Admin"] --> A1["Voir stats"]
    A --> A2["Gerer utilisateurs"]
    A --> A3["Moderer livres"]
    A --> A4["Annuler demandes"]
    A --> A5["Envoyer notifications"]
```

## 4. Diagramme Des Donnees

```mermaid
erDiagram
    USERS ||--o{ BOOKS : owns
    USERS ||--o{ REQUESTS : sends
    USERS ||--o{ NOTIFICATIONS : receives
    USERS ||--o{ EXCHANGES : gives
    USERS ||--o{ EXCHANGES : receives
    SUBJECTS ||--o{ CLASS_SUBJECTS : allows
    SCHOOL_CLASSES ||--o{ CLASS_SUBJECTS : maps
    SUBJECTS }o--o{ BOOKS : labels
    SCHOOL_CLASSES }o--o{ BOOKS : structures
    BOOKS ||--o{ REQUESTS : gets
    BOOKS ||--o{ EXCHANGES : becomes

    USERS {
        int id
        string name
        string email
        string password
        string phone
        string role
        int is_active
    }

    SUBJECTS {
        int id
        string name
        int sort_order
        int is_active
    }

    SCHOOL_CLASSES {
        int id
        string school_level
        string class_name
        int sort_order
        int is_active
    }

    CLASS_SUBJECTS {
        int id
        int class_id
        int subject_id
        int sort_order
        int is_active
    }

    BOOKS {
        int id
        string title
        string subject
        string class_name
        string school_level
        string condition_label
        float estimated_price
        int owner_id
        string status
        int is_active
    }

    REQUESTS {
        int id
        int book_id
        int requester_id
        string status
        string meeting_note
        date request_date
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

## 5. Demarrage D'Une Page

```mermaid
sequenceDiagram
    participant U as Utilisateur
    participant I as index.php
    participant C as Controller
    participant M as Model
    participant V as View
    participant DB as Oracle

    U->>I: Ouvrir une URL
    I->>C: Choisir la bonne action
    C->>M: Demander des donnees
    M->>DB: Executer SQL
    DB-->>M: Retour des resultats
    M-->>C: Donnees
    C->>V: Render page
    V-->>U: HTML affiche
```

## 6. Inscription

```mermaid
sequenceDiagram
    participant U as Utilisateur
    participant A as AuthController
    participant M as User model
    participant DB as Oracle

    U->>A: POST /register
    A->>A: Verifier champs
    A->>M: findByEmail(email)
    M->>DB: SELECT user
    DB-->>M: resultat
    M-->>A: resultat
    A->>M: create(data)
    M->>DB: INSERT user
    DB-->>M: user cree
    M-->>A: userId
    A-->>U: Redirection vers /login
```

## 7. Connexion

```mermaid
sequenceDiagram
    participant U as Utilisateur
    participant A as AuthController
    participant M as User model
    participant S as Session
    participant DB as Oracle

    U->>A: POST /login
    A->>M: findByEmail(email)
    M->>DB: SELECT user
    DB-->>M: user
    M-->>A: user
    A->>A: password_verify(...)
    A->>S: Auth::login(user)
    S-->>A: session user saved
    A-->>U: Redirection /dashboard
```

## 8. Ajout D'Un Livre

```mermaid
sequenceDiagram
    participant U as Utilisateur
    participant B as BookController
    participant M as Book model
    participant DB as Oracle

    U->>B: POST /add-book
    B->>B: Verifier auth
    B->>B: Verifier champs
    B->>B: Verifier classe/niveau
    B->>B: Verifier matiere autorisee pour la classe
    B->>B: Construire titre
    B->>M: create(book data)
    M->>DB: INSERT books
    DB-->>M: book saved
    M-->>B: bookId
    B-->>U: Redirection /dashboard
```

## 9. Consultation Du Catalogue

```mermaid
flowchart TD
    A["Utilisateur ouvre /catalog"] --> B["PageController::catalog"]
    B --> C["Lire filtres GET"]
    C --> D["Book::all(filters)"]
    D --> E["Oracle execute la requete"]
    E --> F["Liste des livres"]
    F --> G["Render catalog.php"]
```

## 10. Envoi D'Une Demande

```mermaid
sequenceDiagram
    participant U as Utilisateur
    participant R as RequestController
    participant B as Book model
    participant Q as BookRequest model
    participant N as Notification model
    participant DB as Oracle

    U->>R: POST /request-book
    R->>R: Verifier auth
    R->>B: find(bookId)
    B->>DB: SELECT book
    DB-->>B: book
    B-->>R: book
    R->>Q: existsPending(bookId, userId)
    Q->>DB: SELECT count
    DB-->>Q: count
    Q-->>R: yes/no
    R->>Q: create(bookId, userId)
    Q->>DB: INSERT request
    DB-->>Q: request saved
    R->>N: create(ownerId, message)
    N->>DB: INSERT notification
    DB-->>N: notification saved
    R-->>U: Success
```

## 11. Acceptation D'Une Demande

```mermaid
sequenceDiagram
    participant O as Proprietaire
    participant R as RequestController
    participant Q as BookRequest model
    participant B as Book model
    participant U as User model
    participant N as Notification model
    participant DB as Oracle

    O->>R: POST /accept-request?id=...
    R->>R: Verifier auth
    R->>Q: find(requestId)
    Q->>DB: SELECT request
    DB-->>Q: request
    Q-->>R: request
    R->>B: find(bookId)
    B->>DB: SELECT book
    DB-->>B: book
    B-->>R: book
    R->>Q: accept(requestId, bookId, note)
    Q->>DB: UPDATE accepted request
    Q->>DB: UPDATE other requests to rejected
    R->>B: updateStatus(bookId, reserved)
    B->>DB: UPDATE books
    R->>U: find owner and requester
    U->>DB: SELECT users
    DB-->>U: users
    R->>N: create notifications
    N->>DB: INSERT notifications
    R-->>O: Success
```

## 12. Rejet D'Une Demande

```mermaid
flowchart TD
    A["Owner clicks reject"] --> B["RequestController::reject"]
    B --> C["Check auth"]
    C --> D["Find request"]
    D --> E["Find book"]
    E --> F["Verify owner"]
    F --> G["BookRequest::reject"]
    G --> H["Notification::create for requester"]
    H --> I["Redirect to dashboard"]
```

## 13. Notifications

```mermaid
flowchart LR
    A["Event happens"] --> B["Notification model"]
    B --> C["Insert row in notifications"]
    C --> D["Navbar dropdown"]
    C --> E["Dashboard notifications"]
    D --> F["NotificationController::read"]
    E --> F
    F --> G["markAsRead"]
```

## 14. Tableau De Bord Admin

```mermaid
flowchart TD
    A["Admin opens /admin"] --> B["PageController::admin"]
    B --> C["User model"]
    B --> D["Book model"]
    B --> E["BookRequest model"]
    C --> F["Total users / inactive users / user search"]
    D --> G["Books / inactive books / books by level / requested subjects"]
    E --> H["Accepted count / request list / money saved"]
    F --> I["Render admin.php"]
    G --> I
    H --> I
```

## 15. Diagramme De Classes De La Plateforme (To-Be)

```mermaid
classDiagram
    class Controller {
        <<abstract>>
        +render(view, data)
        +json(payload, statusCode)
    }

    class Auth {
        <<static>>
        +user()
        +id()
        +check()
        +isAdmin()
        +login(user)
        +logout()
    }

    class Database {
        <<singleton>>
        +connection()
    }

    class PageController {
        +home()
        +catalog()
        +dashboard()
        +addBook()
        +admin()
    }

    class AuthController {
        +login()
        +register()
        +logout()
    }

    class BookController {
        +latest()
        +index()
        +store()
        +mine()
        +stats()
    }

    class RequestController {
        +store()
        +mine()
        +received()
        +accept()
        +reject()
    }

    class AdminController {
        +stats()
        +toggleUser()
        +deleteBook()
        +restoreBook()
        +cancelRequest()
        +notify()
    }

    class NotificationController {
        +read()
    }

    class AcademicOption {
        +subjects(level, className)
        +levels()
        +classesByLevel()
        +classSubjectsByLevel()
    }

    class User {
        +findByEmail()
        +findById()
        +create()
        +countAll()
    }

    class Book {
        +all(filters)
        +find(id)
        +create(data)
        +updateStatus(id, status)
        +countByLevel()
    }

    class BookRequest {
        +create(bookId, requesterId)
        +find(id)
        +accept(id, bookId, note)
        +reject(id)
        +existsPending(bookId, userId)
    }

    class Notification {
        +create(userId, message, sender)
        +markAsRead(id, userId)
        +latestForUser(userId, limit)
    }

    Controller <|-- PageController
    Controller <|-- AuthController
    Controller <|-- BookController
    Controller <|-- RequestController
    Controller <|-- AdminController
    Controller <|-- NotificationController

    PageController --> AcademicOption : charge listes
    PageController --> Book : catalogue
    PageController --> BookRequest : stats dashboard
    PageController --> Notification : dashboard
    PageController --> User : admin
    AuthController --> User : authentifie
    BookController --> Book : CRUD livre
    BookController --> BookRequest : stats
    BookController --> AcademicOption : validation academique
    RequestController --> Book : verifier livre
    RequestController --> BookRequest : workflow demande
    RequestController --> Notification : notifier
    RequestController --> User : lire contacts
    AdminController --> User : moderer
    AdminController --> Book : moderer
    AdminController --> BookRequest : annuler
    AdminController --> Notification : diffuser
    NotificationController --> Notification : lecture

    PageController ..> Auth : controle acces
    AuthController ..> Auth : session
    BookController ..> Auth : session
    RequestController ..> Auth : session
    AdminController ..> Auth : role admin
    NotificationController ..> Auth : session

    AcademicOption --> Database
    User --> Database
    Book --> Database
    BookRequest --> Database
    Notification --> Database
```

## 16. Diagramme As-Is / To-Be Du Processus Metier

```mermaid
flowchart LR
    subgraph A["As-Is : fonctionnement manuel avant la plateforme"]
        A1["Parents / eleves cherchent un livre"] --> A2["Contact par bouche-a-oreille, telephone ou reseaux sociaux"]
        A2 --> A3["Le proprietaire repond manuellement"]
        A3 --> A4["Le rendez-vous est organise hors systeme"]
        A4 --> A5["Aucun suivi centralise des demandes"]
    end

    subgraph B["To-Be : BookCycle Tunisia"]
        B1["Visiteur consulte le catalogue"] --> B2["Utilisateur envoie une demande"]
        B2 --> B3["Le proprietaire traite depuis le dashboard"]
        B3 --> B4["Reservation et notifications automatiques"]
        B4 --> B5["Suivi centralise et statistiques admin"]
    end

    A5 --> B1
```

## 17. Cycle De Vie D'Un Livre

```mermaid
stateDiagram-v2
    [*] --> Available
    Available --> Reserved : demande acceptee
    Reserved --> Exchanged : echange termine
    Available --> Hidden : admin desactive
    Reserved --> Hidden : admin desactive
    Hidden --> Available : admin reactive
```

## 18. Cycle De Vie D'Une Demande

```mermaid
stateDiagram-v2
    [*] --> Pending
    Pending --> Accepted : owner accepts
    Pending --> Rejected : owner rejects
    Pending --> Rejected : another request accepted
```

## 19. Carte Simple Des Dossiers

```mermaid
flowchart TD
    A["bookcycle-tunisia"] --> B["app"]
    A --> C["public"]
    A --> E["database"]
    A --> F["rapports"]
    A --> G["documents_aide"]

    B --> B1["Controllers"]
    B --> B2["Models"]
    B --> B3["Views"]
    B --> B4["Core"]
    B --> B5["Config"]
```
