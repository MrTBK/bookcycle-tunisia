<div align="center">

**Ecole Supérieure d'Economie Numérique**
**UNIVERSITÉ DE LA MANOUBA**

&nbsp;

&nbsp;

**Projet de Fin d'Année**

*Filière : Licence 2 — Big Data et Intelligence Artificielle*

&nbsp;

&nbsp;

Application web de don, d'échange et de réutilisation des livres scolaires
**\<\<BookCycle Tunisia\>\>**

&nbsp;

**Modules : Atelier Génie Logiciel (AGL) · Réingénierie des Processus d'Affaires (RPA)**

&nbsp;

&nbsp;

**Réalisé par :**

&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Mortadha Yakoubi

&nbsp;

**Présenté le :** 28/04/2026

&nbsp;

**Année Universitaire: 2025/2026**

</div>

---

&nbsp;

## Sommaire

```
I    Introduction                                                              5

II   Partie AGL — Atelier Génie Logiciel                                      6
     Introduction                                                             6
     1)   Démarche Scrum adoptée pour BookCycle Tunisia                       6
     2)   Identification des Acteurs                                          7
          2.1  Visiteur (non authentifié)                                     7
          2.2  Utilisateur Connecté                                           7
          2.3  Administrateur                                                 8
     3)   Besoins Fonctionnels                                                8
     4)   Besoins Non Fonctionnels                                            9
     5)   User Stories de BookCycle Tunisia                                  10
     6)   Product Backlog                                                    11
     7)   Sprint Backlog et Avancement                                       12
          7.1  Sprint 1 — Fondations                                         12
          7.2  Sprint 2 — Fonctionnalités Cœur                               13
          7.3  Sprint 3 — Administration et PL/SQL                           13
     8)   Architecture Logique                                               14
     9)   Définition of Done                                                 15

III  Partie RPA — Réingénierie des Processus d'Affaires                      16
     Introduction                                                            16
     1)   Cartographie des processus de BookCycle Tunisia                    16
     2)   Évaluation de la performance — État As-Is                          17
     3)   Processus sélectionné pour le BPR                                  18
     4)   Analyse SWOT — État As-Is                                          19
     5)   Méthodologie BPR adoptée                                           20
     6)   Solution cible — État To-Be                                        21
     7)   Analyse SWOT — État To-Be                                          23
     8)   Comparaison As-Is vs To-Be                                         24
     9)   KPI de pilotage de BookCycle Tunisia                               24

IV   Conclusion                                                              25
```

---

&nbsp;

<div align="center">

## Chapitre I
### Introduction

</div>

---

Ce rapport présente la partie **Atelier Génie Logiciel (AGL)** et la partie **Réingénierie des Processus d'Affaires (RPA)** du projet **BookCycle Tunisia**.

**BookCycle Tunisia** est une plateforme web de don, d'échange et de réutilisation des livres scolaires en Tunisie. Elle met en relation des propriétaires de manuels scolaires et des demandeurs, avec un espace d'administration dédié.

La partie **AGL** applique le framework **Scrum** au développement de BookCycle Tunisia : identification des acteurs (Visiteur, Utilisateur, Admin), rédaction des besoins fonctionnels et non fonctionnels, user stories, product backlog priorisé avec estimation des efforts, sprint backlog en trois sprints.

La partie **RPA** (Data-Driven Reengineering) analyse les processus métier de BookCycle Tunisia, évalue leur performance actuelle (As-Is) via des SLA et KPI, sélectionne le processus à fort potentiel de transformation — le **traitement d'une demande de livre** — et propose une solution cible (To-Be) intégrant l'Intelligence Artificielle et l'automatisation RPA.

---

&nbsp;

<div align="center">

## Chapitre II
### Partie AGL — Atelier Génie Logiciel

</div>

---

### Introduction

Au niveau de ce chapitre, nous appliquons la démarche **Scrum** au développement de BookCycle Tunisia. Nous identifions les acteurs de la plateforme, définissons leurs besoins, rédigeons les user stories, construisons le product backlog et planifions les sprints de développement.

### 1. Démarche Scrum adoptée pour BookCycle Tunisia

Le développement de BookCycle Tunisia a été conduit selon une démarche **Scrum** simplifiée, adaptée au contexte académique d'un développeur solo.

| Artefact Scrum | Application dans BookCycle Tunisia |
|---|---|
| **Product Backlog** | Liste priorisée des fonctionnalités de la plateforme avec effort estimé |
| **Sprint Backlog** | Fonctionnalités sélectionnées pour chaque sprint |
| **Incrément** | Application fonctionnelle livrée à la fin de chaque sprint |
| **Definition of Done** | Critères de complétion d'une fonctionnalité |

### 2. Identification des Acteurs

#### 2.1 Visiteur (non authentifié)

Le visiteur est tout internaute qui accède à BookCycle Tunisia sans être connecté.

| Action | Page BookCycle |
|---|---|
| Consulter l'accueil avec les livres récents | `/` |
| Parcourir le catalogue avec filtres niveau/classe/matière | `/catalog` |
| Voir la fiche d'un livre disponible | `/catalog?id=X` |
| Lire les pages institutionnelles | `/about`, `/contact`, `/privacy-policy` |
| Accéder à l'inscription et à la connexion | `/register`, `/login` |

#### 2.2 Utilisateur Connecté

L'utilisateur connecté est un membre inscrit sur BookCycle Tunisia.

| Action | Description |
|---|---|
| Accéder à son tableau de bord | Voir ses statistiques, livres, demandes, notifications |
| Publier un livre scolaire | Avec niveau, classe, matière, état et prix |
| Modifier un livre publié | Modifier l'état, le prix et la description |
| Envoyer une demande pour un livre | Solliciter un livre d'un autre utilisateur |
| Accepter ou refuser une demande reçue | Avec note de rendez-vous obligatoire |
| Consulter ses notifications | Nouvelles demandes, acceptations, messages admin |

#### 2.3 Administrateur

L'administrateur dispose de tous les droits de l'utilisateur connecté, plus les droits de gestion de la plateforme.

| Action | Description |
|---|---|
| Consulter les statistiques globales | Utilisateurs, livres, échanges, économie estimée |
| Activer / Désactiver un compte | Suppression logique (compte bloqué mais données conservées) |
| Supprimer définitivement un utilisateur | DELETE physique — bloqué si l'utilisateur a des livres actifs |
| Masquer / Restaurer un livre | Suppression logique d'un livre |
| Annuler une demande | Forcer le statut `rejected` sur n'importe quelle demande |
| Envoyer une notification | Ciblée (un utilisateur) ou globale (tous les actifs) |

### 3. Besoins Fonctionnels

| ID | Besoin | Acteur | Priorité |
|---|---|---|---|
| BF-01 | Inscription avec validation email unique et téléphone 8 chiffres | Visiteur | Haute |
| BF-02 | Connexion / déconnexion sécurisée | Visiteur | Haute |
| BF-03 | Catalogue filtrable par niveau, classe et matière (dynamique Oracle) | Tous | Haute |
| BF-04 | Publication d'un livre avec validation niveau/classe/matière via Oracle | Utilisateur | Haute |
| BF-05 | Modification d'un livre (état, prix, description) | Utilisateur | Haute |
| BF-06 | Envoi d'une demande avec contrôle anti-doublon et anti-selfdemande | Utilisateur | Haute |
| BF-07 | Acceptation / refus d'une demande avec note de rendez-vous | Utilisateur | Haute |
| BF-08 | Tableau de bord personnel avec statistiques, livres, demandes, notifications | Utilisateur | Haute |
| BF-09 | Notifications automatiques pour chaque événement important | Utilisateur | Moyenne |
| BF-10 | Tableau de bord administrateur avec statistiques globales | Admin | Haute |
| BF-11 | Gestion des comptes (activation / désactivation / suppression) | Admin | Haute |
| BF-12 | Modération des livres (masquer / restaurer) | Admin | Haute |
| BF-13 | Gestion des demandes (annulation admin) | Admin | Haute |
| BF-14 | Envoi de notifications ciblées ou globales | Admin | Moyenne |

### 4. Besoins Non Fonctionnels

| Catégorie | Exigence pour BookCycle Tunisia |
|---|---|
| **Architecture** | Séparation stricte MVC (Modèle, Vue, Contrôleur) |
| **Sécurité** | Requêtes préparées PDO, `htmlspecialchars()` dans les vues, contrôle des rôles |
| **Performance** | 10 index Oracle sur les colonnes interrogées fréquemment |
| **Maintenabilité** | Namespaces PHP (`App\Models`, `App\Controllers`, `App\Core`) |
| **Compatibilité** | Oracle XE + PHP 7.4 + PDO_OCI + Windows 11 |
| **Déployabilité** | Lancement local en une commande (`start_oracle_app.bat`) |

### 5. User Stories de BookCycle Tunisia

```
US-01 : En tant que visiteur,
        je veux consulter le catalogue de livres sans me connecter,
        afin de voir les livres disponibles avant de créer un compte.

US-02 : En tant qu'utilisateur,
        je veux publier un livre avec son niveau, sa classe, sa matière et son état,
        afin de le mettre à disposition d'autres familles tunisiennes.

US-03 : En tant qu'utilisateur,
        je veux filtrer le catalogue par niveau scolaire, classe et matière,
        afin de trouver rapidement le livre dont j'ai besoin.

US-04 : En tant qu'utilisateur,
        je veux envoyer une demande pour un livre disponible,
        afin de prendre contact avec son propriétaire.

US-05 : En tant que propriétaire,
        je veux accepter ou refuser une demande avec une note de rendez-vous,
        afin de finaliser ou décliner l'échange depuis mon tableau de bord.

US-06 : En tant qu'utilisateur,
        je veux recevoir une notification quand ma demande est traitée,
        afin d'être informé sans surveiller constamment mon tableau de bord.

US-07 : En tant qu'administrateur,
        je veux consulter les statistiques globales de la plateforme,
        afin de surveiller l'activité et prendre des décisions de modération.

US-08 : En tant qu'administrateur,
        je veux désactiver un compte ou masquer un livre non conforme,
        afin de modérer BookCycle Tunisia de manière ciblée et réversible.
```

### 6. Product Backlog

| ID | Fonctionnalité | Priorité | Effort (JD) | Statut |
|---|---|---|---|---|
| PB-01 | Catalogue public consultable | Haute | 1 | Done |
| PB-02 | Inscription utilisateur | Haute | 1 | Done |
| PB-03 | Connexion / déconnexion | Haute | 0.5 | Done |
| PB-04 | Ajout d'un livre avec validation Oracle | Haute | 2 | Done |
| PB-05 | Envoi d'une demande | Haute | 1 | Done |
| PB-06 | Acceptation / refus d'une demande | Haute | 2 | Done |
| PB-07 | Tableau de bord utilisateur | Haute | 1.5 | Done |
| PB-08 | Tableau de bord administrateur | Haute | 2 | Done |
| PB-09 | Gestion des comptes (admin) | Haute | 1 | Done |
| PB-10 | Modération des livres (admin) | Haute | 0.5 | Done |
| PB-11 | Système de notifications | Moyenne | 1 | Done |
| PB-12 | Filtres dynamiques classe → matières | Haute | 1.5 | Done |
| PB-13 | Tables de référence Oracle | Haute | 1 | Done |
| PB-14 | Modification d'un livre (/edit-book) | Haute | 1 | Done |
| PB-15 | Suppression permanente d'un utilisateur | Haute | 0.5 | Done |
| PB-16 | Validation téléphone 8 chiffres | Haute | 0.5 | Done |
| PB-17 | Upload d'image de couverture | Basse | 2 | To Do |
| PB-18 | Token CSRF | Moyenne | 0.5 | To Do |
| PB-19 | Tests automatisés PHPUnit | Basse | 3 | To Do |

**Effort total réalisé :** ~19 jours-développeur &nbsp;|&nbsp; **Effort restant :** ~5.5 jours-développeur

### 7. Sprint Backlog et Avancement

#### 7.1 Sprint 1 — Fondations (Semaines 1-2)

**Objectif :** Architecture MVC, base Oracle et authentification.

| Tâche BookCycle Tunisia | État |
|---|---|
| Création du schéma Oracle (8 tables, séquences, triggers, index) | Done |
| Mise en place de l'architecture MVC PHP | Done |
| Connexion PDO Oracle (`bookcycle_app`) | Done |
| Inscription et connexion utilisateur | Done |
| Layout partagé (header/footer) avec navigation adaptée | Done |
| Page d'accueil avec statistiques et derniers livres | Done |

#### 7.2 Sprint 2 — Fonctionnalités Cœur (Semaines 3-4)

**Objectif :** Catalogue, publication de livres et demandes.

| Tâche BookCycle Tunisia | État |
|---|---|
| Catalogue public avec filtres niveau/classe/matière | Done |
| Tables de référence Oracle (subjects, school_classes, class_subjects) | Done |
| Filtres dynamiques JavaScript (classe → matières depuis Oracle) | Done |
| Formulaire d'ajout de livre avec validation Oracle | Done |
| Envoi d'une demande avec contrôle anti-doublon | Done |
| Tableau de bord utilisateur (4 sections) | Done |

#### 7.3 Sprint 3 — Administration et PL/SQL (Semaines 5-6)

**Objectif :** Espace admin, objets PL/SQL et fonctionnalités avancées.

| Tâche BookCycle Tunisia | État |
|---|---|
| Acceptation / refus d'une demande avec note de rendez-vous | Done |
| Procédure PL/SQL `accept_request` | Done |
| Tableau de bord administrateur avec statistiques globales | Done |
| Modération livres et utilisateurs | Done |
| Suppression permanente d'un utilisateur (garde-fou livres actifs) | Done |
| Système de notifications (navbar + tableau de bord) | Done |
| Triggers : `trg_books_updated_at`, `trg_book_exchange_log`, `trg_notify_owner_on_request` | Done |
| Modification d'un livre (/edit-book) | Done |
| Validation téléphone 8 chiffres à l'inscription | Done |

### 8. Architecture Logique

```
                   ┌──────────────────────────────────────────────┐
                   │           router.php (Front Controller)      │
  Requête HTTP ───►│   Analyse l'URL → dispatche vers le bon      │
                   │   contrôleur et la bonne action              │
                   └──────────────────────┬───────────────────────┘
                                          │
                   ┌──────────────────────▼───────────────────────┐
                   │                  Contrôleur                  │
                   │   Orchestre les appels Modèle ↔ Vue          │
                   │   Contrôle les droits (Auth::check/isAdmin)  │
                   └──────────┬───────────────────────┬───────────┘
                              │                       │
              ┌───────────────▼─────────┐   ┌────────▼────────────┐
              │     Modèle (Oracle)     │   │   Vue (HTML/PHP)    │
              │  User, Book, Request,   │   │  home, catalog,     │
              │  Notification,          │   │  dashboard, admin,  │
              │  AcademicOption         │   │  add-book, etc.     │
              └───────────────┬─────────┘   └────────────────────┘
                              │
              ┌───────────────▼─────────┐
              │  Oracle XE (bookcycle_app)│
              │  8 tables, séquences,   │
              │  triggers, PL/SQL       │
              └─────────────────────────┘
```

### 9. Définition of Done

Une fonctionnalité BookCycle Tunisia est considérée **Done** si :

1. La logique métier est implémentée dans le contrôleur et le modèle correspondants
2. Toutes les entrées utilisateur sont validées côté serveur (jamais côté client uniquement)
3. La vue est accessible et affiche correctement les données Oracle
4. Les messages de succès et d'erreur sont gérés via flash messages
5. Les règles métier sont respectées (ex. : pas de demande sur son propre livre, pas de doublon)
6. La fonctionnalité a été testée manuellement dans le navigateur avec des données réelles

---

&nbsp;

<div align="center">

## Chapitre III
### Partie RPA — Réingénierie des Processus d'Affaires

</div>

---

### Introduction

Au niveau de ce chapitre, nous analysons les processus métier de **BookCycle Tunisia** selon une approche **Data-Driven Reengineering**. Nous cartographions les processus existants, évaluons leur performance via des SLA et KPI, sélectionnons le processus à fort potentiel de rupture et proposons une solution cible intégrant l'IA et l'automatisation RPA.

### 1. Cartographie des processus de BookCycle Tunisia

#### 1.1 Processus Cœur

| Processus | Description | Acteurs |
|---|---|---|
| **Publication d'un livre** | Saisie, validation Oracle et mise en ligne | Utilisateur |
| **Recherche et filtrage** | Exploration du catalogue avec filtres dynamiques | Tous |
| **Envoi d'une demande** | Sollicitation d'un livre auprès de son propriétaire | Utilisateur |
| **Traitement d'une demande** | Acceptation ou refus par le propriétaire | Utilisateur |
| **Finalisation d'un échange** | Enregistrement de l'échange dans la base | Utilisateur + Système |

#### 1.2 Processus Support

| Processus | Description |
|---|---|
| Gestion des comptes | Inscription, connexion, activation/désactivation |
| Notifications système | Envoi automatique et lecture des notifications |
| Maintenance Oracle | Scripts SQL, sauvegarde, administration |

#### 1.3 Processus de Management

| Processus | Description |
|---|---|
| Administration plateforme | Supervision globale via le dashboard admin |
| Modération | Contrôle des contenus et des utilisateurs |
| Suivi des KPI | Analyse de l'activité et des statistiques globales |

### 2. Évaluation de la performance — État As-Is

#### 2.1 SLA actuels de BookCycle Tunisia

| Processus | SLA défini | Performance réelle | Statut |
|---|---|---|---|
| Publication d'un livre | < 2 min | 3 à 5 min (config initiale incluse) | Dégradé |
| Recherche et filtrage | < 3 sec | < 1 sec (Oracle + index) | Acceptable |
| Envoi d'une demande | Immédiat | Immédiat | Acceptable |
| **Traitement d'une demande** | **< 24h** | **24h à 7 jours** | **Inacceptable** |
| Finalisation d'un échange | Automatique | Manuel et variable | Dégradé |

#### 2.2 KPI As-Is de BookCycle Tunisia

| KPI | Valeur As-Is | Cible To-Be |
|---|---|---|
| Délai moyen de traitement d'une demande | 48 à 96 heures | < 12 heures |
| Taux de réponse des propriétaires | ~60% (oublis fréquents) | > 90% |
| Taux d'acceptation des demandes | ~40% | > 60% |
| Taux d'abandon de demandes | ~25% | < 10% |

### 3. Processus sélectionné pour le BPR

**Processus sélectionné : le traitement d'une demande de livre sur BookCycle Tunisia**

Ce processus présente le plus fort potentiel de rupture car :
- Son SLA actuel est **inacceptable** (délai de 24h à 7 jours)
- Il constitue le **cœur de valeur** de BookCycle Tunisia
- Il génère le plus d'insatisfaction et d'abandons

#### Flux As-Is détaillé

```
 Demandeur          Propriétaire        Système BookCycle
     │                    │                │
     ├── Envoie demande ──►│                │
     │                    │                │
     │          [Propriétaire doit se      │
     │          connecter manuellement]    │
     │                    │◄─── Consulte dashboard ──┤
     │                    │◄─── Lit demandes reçues  │
     │              [Décision manuelle,    │
     │               sans délai imposé]   │
     │                    ├── Accepte ou ──►│
     │                    │    refuse       │
     │◄── Notification ───┼─────────────────┤
     │                    │                │
```

**Durée totale As-Is :** 24h à 7 jours — dépendance 100% manuelle côté propriétaire

### 4. Analyse SWOT — État As-Is

#### Forces

| Force | Description |
|---|---|
| Processus simple | Peu d'étapes, facile à comprendre |
| Autonomie du propriétaire | Décision finale reste humaine |
| Traçabilité | Historique des demandes conservé dans Oracle |

#### Faiblesses

| Faiblesse | Impact |
|---|---|
| Dépendance totale à l'action manuelle | Délais imprévisibles |
| Aucun rappel automatique | Risque élevé d'oubli |
| Pas de clôture automatique des autres demandes | Demandes redondantes en attente |

#### Opportunités

| Opportunité | Description |
|---|---|
| Automatisation des rappels | Réduction significative des oublis |
| Intégration IA | Scoring des demandes, priorisation |
| Analytics enrichis | KPI temps réel pour l'admin |

#### Menaces

| Menace | Description |
|---|---|
| Résistance au changement | Propriétaires mal à l'aise avec les relances auto |
| Faux positifs | Rappels trop fréquents perçus comme spam |

### 5. Méthodologie BPR adoptée

La méthodologie retenue est une **réingénierie progressive mais radicale** : conserver la base Oracle, le code PHP et l'expérience utilisateur existants de BookCycle Tunisia, tout en y greffant des composants IA et d'automatisation visant un gain de performance **≥ 50%** sur le délai de traitement.

| Critère | Greenfield | Progressive Radicale | Choix |
|---|---|---|---|
| Conservation des données Oracle | Non | Oui | Progressive |
| Risque pour les utilisateurs | Très élevé | Maîtrisé | Progressive |
| Compatibilité PHP/Oracle existant | Non | Oui | Progressive |
| Gain de performance | Maximum | > 50% | Progressive |

### 6. Solution cible — État To-Be

#### Architecture To-Be avec IA et Automatisation

```
 Demandeur    Propriétaire    Système BookCycle    Moteur IA    Moteur RPA
     │               │               │               │              │
     ├── Envoie ────►│               │               │              │
     │   demande     ├──────────────►│               │              │
     │               │◄─ Notif. ─────┤               │              │
     │               │   immédiate   │               │              │
     │               │               │──── Score ───►│              │
     │               │               │◄── Priorité ──┤              │
     │               │         [Si pending > 48h]                   │
     │               │◄──────────────────────────── Relance auto ───┤
     │               │                                              │
     │               ├── Accepte ───►│                              │
     │               │               │──── Clôture auto autres ─────►
     │◄── Notif. ────┤               │    demandes                  │
```

**Durée To-Be :** < 12 heures &nbsp;|&nbsp; **Gain estimé :** > 75% sur le délai de traitement

#### Composants de la solution To-Be

**1. Scoring IA des demandes**

Un module IA analyse chaque demande et calcule un score de priorité basé sur :
- L'historique de réponse du propriétaire sur BookCycle Tunisia
- Le nombre de demandes concurrentes pour le même livre
- L'ancienneté du livre sur la plateforme
- Le profil du demandeur (échanges réussis antérieurs)

Les demandes à score élevé remontent en tête du tableau de bord du propriétaire.

**2. Relances automatiques RPA**

Le moteur RPA détecte les demandes `pending` depuis plus de 48h et déclenche une relance via la procédure Oracle `add_notification` :

```sql
SELECT r.id, b.owner_id, b.title,
       ROUND(SYSDATE - r.request_date) AS jours_attente
FROM requests r JOIN books b ON b.id = r.book_id
WHERE r.status = 'pending' AND r.request_date < SYSDATE - 2
ORDER BY jours_attente DESC;
```

**3. Clôture automatique**

La procédure `accept_request` implémente déjà la clôture automatique des demandes redondantes. Dans le To-Be, elle est étendue avec :
- Notification automatique de tous les demandeurs rejetés
- Enregistrement automatique de l'échange via le trigger `trg_book_exchange_log`

### 7. Analyse SWOT — État To-Be

#### Forces

| Force | Description |
|---|---|
| Délai < 12h | Grâce aux relances automatiques et à la prioritisation IA |
| Moins d'oublis | Relances automatiques sur les demandes > 48h |
| Clôture automatique | Cohérence des données garantie sans action manuelle |

#### Faiblesses

| Faiblesse | Description |
|---|---|
| Dépendance à la qualité des données | Le scoring IA nécessite du volume |
| Complexité accrue | Composants IA et RPA à maintenir |

### 8. Comparaison As-Is vs To-Be

| Dimension | As-Is | To-Be | Gain |
|---|---|---|---|
| Délai de traitement moyen | 48 à 96 heures | < 12 heures | **> 75%** |
| Taux de réponse des propriétaires | ~60% | > 90% | **+50%** |
| Taux d'abandon des demandes | ~25% | < 10% | **-60%** |
| Relances manuelles | 100% | 0% (automatisé) | **100%** |
| Clôture des demandes redondantes | Manuelle | Automatique PL/SQL | **100%** |

### 9. KPI de pilotage de BookCycle Tunisia

| Indicateur | Formule Oracle | Cible |
|---|---|---|
| **Livres actifs** | `COUNT(*) WHERE is_active=1 AND status='available'` | Croissance |
| **Délai moyen de traitement** | `AVG(date_réponse - request_date)` | < 12h |
| **Taux d'acceptation** | `COUNT(accepted)` / `COUNT(total)` | > 60% |
| **Taux d'abandon** | Demandes sans réponse > 7 jours | < 10% |
| **Échanges finalisés** | `COUNT(*) FROM exchanges` | Croissance |
| **Économie estimée** | `SUM(estimated_price)` des livres échangés | En DT |

---

&nbsp;

<div align="center">

## Conclusion

</div>

---

### Bilan AGL

La partie AGL démontre une maîtrise de la démarche Scrum appliquée à BookCycle Tunisia : backlog produit complet avec estimation des efforts, organisation en 3 sprints avec états clairs (Done / To-Do), et définition rigoureuse des critères de complétion. L'architecture MVC adoptée répond aux exigences de séparation des responsabilités et facilite l'évolution de la plateforme.

### Bilan RPA

La partie RPA confirme l'existence d'une opportunité réelle de transformation sur BookCycle Tunisia. Le processus de traitement des demandes présente un SLA inacceptable en l'état actuel. La solution To-Be — combinant scoring IA, relances RPA automatiques et workflow Oracle PL/SQL — vise un gain de performance supérieur à **75% sur le délai de traitement**, bien au-delà de la barre des 50% requise pour justifier une approche BPR.

---

*Rapport réalisé dans le cadre du Projet Intégré — Licence 2 Big Data et Intelligence Artificielle — ESEN — Université de la Manouba — 2025/2026*
