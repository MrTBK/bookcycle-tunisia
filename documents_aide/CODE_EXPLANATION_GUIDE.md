# Code Explanation Guide

This file explains `BookCycle Tunisia` in very simple words, step by step.

## 1. What this project is

`BookCycle Tunisia` is a PHP web app connected to an Oracle database.

It helps people:
- create an account
- log in
- add school books
- search for books
- ask for a book
- accept or reject requests
- receive notifications
- manage the platform as an admin

## 2. The big idea

The project follows a simple MVC structure:

- `public/index.php`
  This is the front door of the website.
- `app/Controllers/`
  Controllers receive the request and decide what to do.
- `app/Models/`
  Models talk to the Oracle database.
- `app/Views/`
  Views show HTML to the user.

So the path is usually:

1. browser sends request
2. `public/index.php` starts the app
3. `public/index.php` chooses the right controller action
4. controller asks model for data
5. model talks to Oracle
6. controller sends data to a view
7. view shows the page

## 3. How one request enters the app

### Step 1: `public/index.php`

This file:
- loads the bootstrap
- reads the request URL
- calls the matching controller action directly

In simple words:

> "Somebody opened a page. Which part of the app should answer?"

### Step 2: `app/bootstrap.php`

This file prepares the app:
- helper string functions
- session storage
- autoload for classes

In simple words:

> "Before the app works, prepare the toolbox."

## 4. How pages work

### Public pages

Handled mostly by `PageController`:
- home
- about
- catalog
- contact
- privacy policy
- login page
- register page

These methods usually:
- collect data
- call `render(...)`
- show a page

### Private pages

Also handled by `PageController`:
- dashboard
- add-book page
- admin page

Before showing them, the controller checks:
- is the user logged in?
- is the user admin?

## 5. How login works

Files:
- `app/Views/pages/login.php`
- `app/Controllers/AuthController.php`
- `app/Models/User.php`
- `app/Core/Auth.php`

Flow:

1. user fills login form
2. form sends `POST /login`
3. `AuthController::login()` reads email and password
4. it asks `User::findByEmail(...)`
5. it checks the hashed password with `password_verify(...)`
6. if valid, `Auth::login($user)` stores the user in session
7. user is redirected to dashboard

In simple words:

> "Find the user, check the secret password, remember the user in session."

## 6. How registration works

Files:
- `register.php`
- `AuthController::register()`
- `User::create()`

Flow:

1. user fills name, email, phone, password
2. controller checks that fields are not empty
3. controller checks that email is not already used
4. model creates user row
5. password is hashed before saving
6. user is sent to login page

Important:
- passwords are not stored as plain text
- they are stored as hashes

## 7. How adding a book works

Files:
- `add-book.php`
- `BookController::store()`
- `Book::create()`

Flow:

1. connected user opens add-book page
2. user chooses:
   - subject
   - level
   - class
   - condition
   - estimated price
3. controller checks required fields
4. controller checks that class belongs to level using `school_classes`
5. controller checks that subject exists in `subjects`
6. controller builds a title automatically
7. model inserts the book in Oracle

In simple words:

> "Take book information, verify it, then save it."

## 8. How the catalogue works

Files:
- `catalog.php`
- `PageController::catalog()`
- `Book::all()`

Flow:

1. visitor chooses filters
2. filters go into URL:
   - `level`
   - `class_name`
   - `subject`
3. controller reads filters from `$_GET`
4. model builds one SQL query step by step
5. result is shown as book cards

Extra behavior:
- the class list reacts to the chosen level
- if a book id is selected, the page shows book details below

## 9. How a request for a book works

Files:
- `catalog.php`
- `RequestController::store()`
- `BookRequest::create()`
- `Notification::create()`

Flow:

1. connected user clicks "Envoyer une demande"
2. controller reads the book id
3. it checks:
   - book exists
   - user is not the owner
   - no pending duplicate request already exists
4. model inserts new request with status `pending`
5. owner receives a notification

In simple words:

> "Ask for a book, but only if the request is valid."

## 10. How accepting a request works

Files:
- `dashboard.php`
- `RequestController::accept()`
- `BookRequest::accept()`
- `Book::updateStatus()`
- `Notification::create()`

Flow:

1. owner opens dashboard
2. owner writes a meeting note
3. owner clicks accept
4. controller checks:
   - user is logged in
   - request exists
   - book belongs to this owner
   - meeting note is not empty
5. model starts a transaction
6. chosen request becomes `accepted`
7. other pending requests for same book become `rejected`
8. book becomes `reserved`
9. both sides receive notifications with contact details

Why transaction?

Because several related updates must succeed together.

In simple words:

> "If one person gets the book, the others must stop waiting."

## 11. How rejection works

Files:
- `RequestController::reject()`
- `BookRequest::reject()`

Flow:

1. owner chooses reject
2. controller checks access and ownership
3. request status becomes `rejected`
4. requester receives a notification

## 12. How notifications work

Files:
- `Notification` model
- `NotificationController`
- header dropdown
- dashboard notifications section

Notifications are small in-app messages.

They are created when:
- a new request is sent
- a request is accepted
- a request is rejected
- admin sends a message

They are shown:
- in the navbar dropdown
- on the dashboard

When user opens one notification:
- `NotificationController::read()` marks it as read

## 13. How admin works

Files:
- `PageController::admin()`
- `AdminController`
- `admin.php`

Admin can:
- see global statistics
- search users
- activate or deactivate users
- hide or restore books
- cancel requests
- send notifications

Important admin rule:

> normal users must not access admin page

That is why controllers check `Auth::isAdmin()`.

## 14. How models talk to Oracle

The models use `PDO`.

The Oracle connection is created in:
- `app/Core/Database.php`

Configuration comes from:
- `app/Config/config.php`

Important Oracle details:
- `ROWNUM` is used to limit rows
- sequences and triggers generate ids
- `NVL(...)` is used for null-safe sums

## 15. Database structure in simple words

### `users`
People using the platform.

### `books`
Books added by users.

### `subjects`
Official list of school subjects.

### `school_classes`
Official list of classes grouped by school level.

### `requests`
Requests from one user asking for one book.

### `exchanges`
History of completed exchanges.

### `notifications`
Messages shown inside the app.

## 16. Main Oracle scripts

### `01_users_privileges.sql`
Creates Oracle users and grants rights.

### `02_schema.sql`
Creates tables, constraints, sequences, triggers, indexes, and view.

### `03_sample_data.sql`
Adds demo users, subjects, classes, books, requests, exchanges, and notifications.

### `04_queries.sql`
Shows sample SQL queries for learning and reporting.

### `05_plsql_objects.sql`
Adds procedures, functions, triggers, and PL/SQL examples.

## 18. Why comments were added this way

The comments explain:
- what a file is for
- what a method does
- why a check exists
- what happens before and after database updates

They do **not** explain every single obvious line, because too many comments can make code harder to read.

## 19. Best files to study first

If you want to understand the project quickly, read them in this order:

1. `README.md`
2. `public/index.php`
3. `app/bootstrap.php`
4. `app/Controllers/PageController.php`
5. `app/Controllers/AuthController.php`
6. `app/Controllers/BookController.php`
7. `app/Controllers/RequestController.php`
8. `app/Models/AcademicOption.php`
9. `app/Models/User.php`
10. `app/Models/Book.php`
11. `app/Models/BookRequest.php`
12. `database/02_schema.sql`
13. `database/05_plsql_objects.sql`

## 20. Short summary

If you remember only one thing, remember this:

> `public/index.php` picks a controller action, the controller asks a model, the model talks to Oracle, and the view shows the result.
