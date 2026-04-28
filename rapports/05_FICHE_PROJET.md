# FICHE PROJET
## BookCycle Tunisia

---

**Université de la Manouba — École Supérieure d'Économie Numérique (ESEN)**
**Licence 2 — Big Data et Intelligence Artificielle**
**Année universitaire 2025 / 2026**

---

## Informations Générales

| Champ | Valeur |
|---|---|
| **Intitulé du projet** | BookCycle Tunisia |
| **Thème** | Plateforme web de don, d'échange et de réutilisation des livres scolaires |
| **Filière** | Licence 2 — Big Data et Intelligence Artificielle |
| **Établissement** | ESEN — Université de la Manouba |
| **Année universitaire** | 2025 / 2026 |
| **Réalisé par** | Mortadha Yakoubi |
| **Encadré par** | *(à compléter)* |

---

## Problématique

Chaque année, des familles tunisiennes dépensent des sommes importantes pour acquérir des manuels scolaires, alors que de nombreux anciens exemplaires en bon état restent inutilisés.

> **Comment concevoir une plateforme numérique simple et administrable permettant la mise en relation des propriétaires de livres scolaires et des demandeurs, en s'appuyant sur une architecture MVC PHP et une base de données Oracle ?**

---

## Objectifs

- Permettre la **publication** de livres scolaires par des utilisateurs inscrits
- Offrir un **catalogue filtrable** par niveau scolaire, classe et matière
- Gérer l'**envoi, le suivi et le traitement des demandes** d'échange
- **Notifier** les utilisateurs des événements importants sur la plateforme
- Fournir un espace d'**administration** avec statistiques et outils de modération

---

## Modules Académiques Couverts

| Module | Apport principal dans le projet |
|---|---|
| **AGL** | Analyse des besoins, acteurs, user stories, backlog, architecture logique |
| **SGBD** | Schéma Oracle, contraintes d'intégrité, SQL, PL/SQL, index, vues |
| **Programmation Web 2** | Application MVC PHP, sessions, formulaires, sécurité, PDO_OCI |
| **RPA** | Cartographie des processus, analyse As-Is / To-Be, scénarios d'automatisation |

---

## Stack Technique

| Couche | Technologie |
|---|---|
| **Langage serveur** | PHP 7.4 |
| **Base de données** | Oracle XE |
| **Accès données** | PDO_OCI |
| **Frontend** | HTML5, CSS3, JavaScript natif |
| **Architecture** | MVC |
| **Procédural avancé** | PL/SQL (procédures, fonctions, triggers, curseurs) |

---

## Architecture de l'Application

```
router.php (Front Controller)
└── public/index.php (Bootstrap)
    └── app/
        ├── Core/      → Auth, Database, Controller
        ├── Models/    → User, Book, BookRequest, Notification, AcademicOption
        ├── Controllers/ → Auth, Book, Request, Admin, Page, Notification
        └── Views/     → layouts/ + pages/
```

---

## Fonctionnalités Implémentées

| Fonctionnalité | Statut |
|---|---|
| Inscription et connexion | Réalisé |
| Catalogue public avec filtres dynamiques | Réalisé |
| Ajout d'un livre (validation niveau/classe/matière via Oracle) | Réalisé |
| Envoi et suivi de demandes | Réalisé |
| Acceptation / refus d'une demande | Réalisé |
| Système de notifications | Réalisé |
| Tableau de bord utilisateur | Réalisé |
| Tableau de bord administrateur | Réalisé |
| Modération des livres et des utilisateurs | Réalisé |
| Statistiques globales et économie estimée | Réalisé |

---

## Base de Données Oracle

### Tables

| Table | Description |
|---|---|
| `users` | Comptes utilisateurs (`admin` / `user`) |
| `subjects` | Matières scolaires (table de référence) |
| `school_classes` | Classes par niveau (table de référence) |
| `class_subjects` | Correspondances classe ↔ matières autorisées |
| `books` | Livres publiés |
| `requests` | Demandes d'échange |
| `exchanges` | Échanges finalisés |
| `notifications` | Notifications système |

### Objets PL/SQL

| Objet | Type | Rôle |
|---|---|---|
| `add_notification` | Procédure | Créer une notification pour un utilisateur |
| `accept_request` | Procédure | Accepter une demande de manière atomique |
| `count_books_by_user` | Fonction | Compter les livres actifs d'un utilisateur |
| `calculate_money_saved` | Fonction | Calculer l'économie totale estimée |
| `trg_books_updated_at` | Trigger | Mettre à jour `updated_at` automatiquement |
| `trg_book_exchange_log` | Trigger | Journaliser les échanges finalisés |

---

## Pages Principales

| URL | Description |
|---|---|
| `/` | Accueil avec livres récents |
| `/catalog` | Catalogue filtrable |
| `/login` / `/register` | Authentification |
| `/dashboard` | Espace personnel |
| `/add-book` | Publier un livre |
| `/admin` | Administration |

---

## Valeur Ajoutée

| Dimension | Bénéfice |
|---|---|
| **Économique** | Réduction des dépenses scolaires des familles |
| **Environnemental** | Réutilisation des manuels, moins de gaspillage |
| **Social** | Accès aux manuels pour les familles à revenus limités |
| **Académique** | Projet intégré cohérent couvrant 4 modules |

---

## Axes d'Amélioration

| Amélioration | Priorité |
|---|---|
| Token CSRF sur les formulaires | Haute |
| Upload d'images pour les livres | Haute |
| Tests automatisés (PHPUnit) | Haute |
| Responsive design mobile | Moyenne |
| Automatisation des relances de demandes (RPA) | Moyenne |
| Déploiement sur serveur distant | Basse |

---

## Lancement Rapide

```powershell
# Depuis la racine du projet
start_oracle_app.bat

# Puis ouvrir dans le navigateur
http://localhost:8000
```

**Comptes de démonstration :**
- Admin : `admin@bookcycle.tn` / `admin123`
- Utilisateur : `ahmed@bookcycle.tn` / `user123`

---

*Fiche réalisée dans le cadre du Projet Intégré — Licence 2 Big Data et Intelligence Artificielle — ESEN — Université de la Manouba — 2025/2026*
