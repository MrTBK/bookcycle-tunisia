# Explication Du Code Comme A Un Enfant

Ce document explique comment **BookCycle Tunisia** fonctionne avec des mots tres simples.

Imagine que le projet est une petite bibliotheque magique sur internet.
Les gens viennent :

- pour regarder des livres
- pour proposer des livres
- pour demander un livre
- pour gerer la plateforme si ce sont des admins

Le projet a plusieurs petites equipes qui travaillent ensemble.

## 1. Les personnages du projet

### Le visiteur

C'est une personne qui ouvre le site sans etre connectee.
Elle peut :

- voir l'accueil
- voir le catalogue
- filtrer les livres
- lire les pages d'information
- aller sur inscription et connexion

### L'utilisateur

C'est une personne connectee.
Elle peut :

- ajouter un livre
- voir ses livres
- demander un livre
- voir ses demandes
- lire ses notifications

### L'admin

C'est le chef de la plateforme.
Il peut :

- voir les statistiques
- gerer les utilisateurs
- cacher ou remettre des livres
- annuler des demandes
- envoyer des notifications

## 2. Les grandes boites du projet

Le projet est range avec la logique **MVC**.

### `public/`

C'est la porte d'entree.
Quand quelqu'un arrive sur le site, il passe d'abord par ici.

### `app/Controllers/`

Les controleurs sont comme des chefs d'orchestre.
Ils recoivent la demande et disent :

- quoi verifier
- quel modele appeler
- quelle page afficher

### `app/Models/`

Les modeles sont les personnes qui parlent avec la base Oracle.
Ils savent :

- lire les donnees
- ajouter des donnees
- modifier des donnees
- compter des donnees

### `app/Views/`

Les vues sont les pages que l'utilisateur voit.
Ce sont les habits du projet.

## 3. Le chemin d'une demande

Quand quelqu'un ouvre une page, il se passe ceci :

1. le navigateur envoie une demande
2. `public/index.php` attrape cette demande
3. `public/index.php` regarde le chemin
4. il choisit le bon controleur
5. le controleur demande des informations au modele
6. le modele parle a Oracle
7. le controleur recupere la reponse
8. la vue affiche le resultat

En image dans la tete :

`Utilisateur -> Porte -> Controleur -> Modele -> Base de donnees -> Vue -> Utilisateur`

## 4. La porte principale : `public/index.php`

Ce fichier est tres important.
C'est lui qui ouvre l'application.

Il fait plusieurs choses :

- il charge `app/bootstrap.php`
- il lit l'URL demandee
- il appelle la bonne methode de controleur

En mots tres simples :

> "Quelqu'un frappe a la porte. Qui doit lui repondre ?"

## 5. La boite a outils : `app/bootstrap.php`

Avant que le projet fonctionne, il faut preparer des outils.

`bootstrap.php` aide a :

- charger les classes automatiquement
- preparer la session
- preparer des fonctions utiles

Sans lui, l'application aurait du mal a demarrer correctement.

## 7. Le gardien de session : `app/Core/Auth.php`

Ce fichier aide a savoir :

- si quelqu'un est connecte
- qui est connecte
- si la personne est admin

Il garde dans la session seulement ce qu'il faut :

- id
- name
- email
- role

Quand quelqu'un se deconnecte, il efface ces informations.

## 8. Le pont vers Oracle : `app/Core/Database.php`

Ce fichier ouvre la connexion avec la base de donnees Oracle.

Il lit la configuration puis cree un objet `PDO`.

Pourquoi c'est utile ?

Parce que tout le projet a besoin de parler a la base.
Au lieu de recreer une connexion partout, le projet reutilise la meme connexion.

## 9. Le chef des pages : `PageController`

`PageController` sert surtout a afficher des pages.

Il s'occupe de :

- `home()`
- `about()`
- `catalog()`
- `contact()`
- `login()`
- `register()`
- `privacyPolicy()`
- `dashboard()`
- `addBook()`
- `admin()`

### Ce qu'il fait vraiment

Pour chaque page, il :

- prepare les donnees
- appelle `render(...)`
- envoie la bonne vue

### Exemple pour l'accueil

Pour `home()` il va chercher :

- les derniers livres
- le nombre total de livres
- le nombre d'echanges acceptes
- l'argent economise

Puis il donne tout cela a la page d'accueil.

### Exemple pour le catalogue

Pour `catalog()` il lit les filtres de l'URL :

- niveau
- classe
- matiere
- statut
- id du livre

Puis il demande au modele `Book` :

- la liste des livres filtres
- le detail d'un livre s'il y a un id

## 10. Le chef de connexion : `AuthController`

`AuthController` s'occupe de :

- inscrire
- connecter
- deconnecter
- dire qui est connecte avec `me()`

### Comment l'inscription marche

1. il lit les donnees envoyees
2. il verifie que les champs importants ne sont pas vides
3. il verifie que l'email n'existe pas deja
4. il demande a `User` de creer la personne
5. il redirige vers la page de connexion

### Comment la connexion marche

1. il lit l'email et le mot de passe
2. il cherche l'utilisateur avec `findByEmail`
3. il compare le mot de passe avec `password_verify`
4. il verifie que le compte est actif
5. il met l'utilisateur dans la session
6. il redirige vers le tableau de bord

### Pourquoi le mot de passe est securise

Le mot de passe n'est pas garde en clair.
Le projet utilise `password_hash`.

Donc dans la base, on ne voit pas le vrai mot de passe comme un texte simple.

## 11. Le chef des livres : `BookController`

`BookController` s'occupe des livres.

Il peut :

- renvoyer les derniers livres
- renvoyer la liste du catalogue
- ajouter un livre
- renvoyer les livres du user connecte
- renvoyer des statistiques

### L'ajout d'un livre

Quand quelqu'un ajoute un livre :

1. on verifie qu'il est connecte
2. on lit les donnees du formulaire
3. on verifie les champs obligatoires
4. on verifie que la classe correspond au niveau grace a la table `school_classes`
5. on verifie que la matiere existe dans la table `subjects`
6. on construit automatiquement un titre
7. on enregistre le livre

Le titre est construit comme ceci :

`matiere - classe - niveau`

Cela rend les titres plus propres et plus uniformes.

## 12. Le chef des demandes : `RequestController`

`RequestController` gere le grand moment du projet :

- demander un livre
- voir ses demandes
- voir les demandes recues
- accepter une demande
- rejeter une demande

### Quand un utilisateur demande un livre

Le controleur verifie :

- la personne est connectee
- le livre existe
- la personne n'est pas le proprietaire
- il n'y a pas deja une demande `pending`

Si tout est bon :

- une ligne est ajoutee dans `requests`
- une notification est creee pour le proprietaire

### Quand le proprietaire accepte

Le controleur :

- lit la demande
- retrouve le livre
- verifie que le livre appartient bien au proprietaire
- verifie qu'il y a une note de rendez-vous

Puis il fait plusieurs choses importantes :

- accepte la bonne demande
- rejette les autres demandes encore en attente
- met le livre en `reserved`
- envoie des notifications avec les contacts

### Pourquoi c'est important

Si un livre est donne a une personne, il ne doit pas rester disponible pour les autres.

## 13. Les petits travailleurs des donnees : les modeles

Les modeles ne montrent pas des pages.
Ils parlent a la base.

### `User`

Ce modele sait :

- creer un utilisateur
- trouver un utilisateur par email
- trouver un utilisateur par id
- compter les utilisateurs
- lister les utilisateurs
- activer ou desactiver un compte

### `Book`

Ce modele sait :

- lire les derniers livres
- filtrer le catalogue
- ajouter un livre
- lire les livres d'un proprietaire
- retrouver un livre
- compter les livres
- compter les livres par niveau
- cacher un livre
- remettre un livre
- changer le statut d'un livre

### `BookRequest`

Ce modele sait :

- verifier s'il y a deja une demande en attente
- creer une demande
- lister les demandes envoyees
- lister les demandes recues
- trouver une demande
- accepter une demande
- rejeter une demande
- calculer des statistiques

### `Notification`

Ce modele sait :

- creer une notification
- creer une notification pour tout le monde
- compter les notifications non lues
- lire les dernieres notifications
- marquer une notification comme lue

## 14. Les pages visibles

Dans `app/Views/pages/`, on trouve les ecrans :

- `home.php`
- `about.php`
- `catalog.php`
- `contact.php`
- `privacy-policy.php`
- `login.php`
- `register.php`
- `dashboard.php`
- `add-book.php`
- `admin.php`

Ces fichiers montrent le resultat final a l'ecran.

Ils ne doivent pas contenir toute la logique metier.
Le gros travail doit rester dans les controleurs et modeles.

## 16. La base de donnees comme une boite a tiroirs

Imagine une armoire avec plusieurs tiroirs.

### Tiroir `users`

Il garde les personnes.

### Tiroir `books`

Il garde les livres.

### Tiroir `subjects`

Il garde la liste officielle des matieres.

### Tiroir `school_classes`

Il garde la liste officielle des classes et leur niveau.

### Tiroir `requests`

Il garde qui a demande quel livre.

### Tiroir `exchanges`

Il garde l'historique des echanges termines.

### Tiroir `notifications`

Il garde les petits messages du site.

## 17. Un vrai exemple complet

Prenons un exemple tres simple.

### Histoire

Ahmed voit un livre de mathematiques.
Il clique sur "Envoyer une demande".

### Ce qui se passe en coulisse

1. le navigateur envoie une requete
2. la route pointe vers `RequestController::store`
3. le controleur lit `bookId`
4. il demande au modele `Book` si le livre existe
5. il demande au modele `BookRequest` s'il existe deja une demande pending
6. si tout est bon, `BookRequest::create()` ajoute une ligne
7. `Notification::create()` ajoute un message pour le proprietaire
8. le navigateur recoit une reponse de succes

### Ce que voit l'utilisateur

Ahmed voit un message de succes.
Le proprietaire voit une nouvelle notification.

## 18. Pourquoi certains controles existent

### Interdire de demander son propre livre

Sinon quelqu'un pourrait creer de fausses demandes sur ses propres livres.

### Interdire deux demandes pending identiques

Sinon la meme personne pourrait spammer le meme livre.

### Verifier le role admin

Sinon un utilisateur normal pourrait faire des actions reservees au chef.

### Verifier le niveau et la classe

Sinon on pourrait enregistrer un livre avec des informations incoherentes.

## 19. Ce que fait Oracle en plus

Oracle ne sert pas juste a stocker.
Il aide aussi avec :

- les sequences pour generer des ids
- les triggers
- les fonctions
- les procedures
- la vue `v_book_overview`

Cela rend la base plus intelligente.

## 20. Si tu veux comprendre vite

Lis les fichiers dans cet ordre :

1. `public/index.php`
2. `app/Core/Auth.php`
3. `app/Controllers/PageController.php`
4. `app/Controllers/AuthController.php`
5. `app/Controllers/BookController.php`
6. `app/Controllers/RequestController.php`
7. `app/Models/AcademicOption.php`
8. `app/Models/User.php`
9. `app/Models/Book.php`
10. `app/Models/BookRequest.php`
11. `app/Models/Notification.php`
12. `database/02_schema.sql`

## 21. La phrase la plus importante

Si tu dois retenir une seule idee, retiens celle-ci :

> `public/index.php` choisit le bon controleur, le controleur demande au modele, le modele parle a Oracle, puis la vue montre le resultat.

## 22. Resume ultra simple

Le site fonctionne comme une ecole bien organisee :

- la porte laisse entrer
- le surveillant dirige
- le chef decide
- le secretaire cherche les informations
- l'armoire garde les donnees
- l'ecran montre la reponse

Et tout cela ensemble fait marcher **BookCycle Tunisia**.
