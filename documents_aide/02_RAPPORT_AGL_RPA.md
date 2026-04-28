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
     1)   Démarche Scrum                                                      6
     2)   Identification des Acteurs                                          7
          2.1  Visiteur (non authentifié)                                     7
          2.2  Utilisateur Connecté                                           7
          2.3  Administrateur                                                 7
     3)   Besoins Fonctionnels et Non Fonctionnels                            8
     4)   User Stories                                                        9
     5)   Product Backlog                                                    10
     6)   Sprint Backlog et Avancement                                       11
          6.1  Sprint 1 — Fondations                                         11
          6.2  Sprint 2 — Fonctionnalités Cœur                               12
          6.3  Sprint 3 — Administration et PL/SQL                           12
     7)   Architecture Logique                                               13
     8)   Définition of Done                                                 14

III  Partie RPA — Réingénierie des Processus d'Affaires                      15
     Introduction                                                            15
     1)   Cartographie des Processus Métier                                  15
     2)   Évaluation de la Performance — État As-Is                          16
     3)   Processus Sélectionné pour le BPR                                  17
     4)   Analyse SWOT — État As-Is                                          18
     5)   Méthodologie BPR Adoptée                                           19
     6)   Solution Cible — État To-Be                                        20
     7)   Analyse SWOT — État To-Be                                          22
     8)   Comparaison As-Is vs To-Be                                         23
     9)   KPI et Indicateurs de Pilotage                                     23

IV   Conclusion                                                              25
```

---

&nbsp;

<div align="center">

## Chapitre I
### Introduction

</div>

---

Ce rapport présente la partie **Atelier Génie Logiciel (AGL)** et la partie **Réingénierie des Processus d'Affaires (RPA)** du projet intégré **BookCycle Tunisia**.

**BookCycle Tunisia** est une plateforme web académique visant à faciliter la réutilisation des livres scolaires en Tunisie, en mettant en relation des propriétaires de manuels et des demandeurs, avec une interface d'administration dédiée.

La partie **AGL** applique le framework **Scrum** : backlog produit priorisé avec estimation des efforts, sprint backlog avec états (Done / Doing / To-Do), et sprint review.

La partie **RPA** (Data-Driven Reengineering) analyse les processus métier de la plateforme, évalue leur performance actuelle (As-Is) via des SLA et KPI, choisit un processus à forte opportunité de rupture, et propose une solution cible (To-Be) intégrant l'**Intelligence Artificielle**, l'**automatisation RPA** et un **workflow moderne**.

---

&nbsp;

<div align="center">

## Chapitre II
### Partie AGL — Atelier Génie Logiciel

</div>

---

### Introduction

Au niveau de ce chapitre, nous appliquons la démarche **Scrum** au projet BookCycle Tunisia. Nous y présentons la cartographie des acteurs, les besoins fonctionnels et non fonctionnels, les user stories, le product backlog structuré par sprints, l'architecture logique, et les critères de complétion (Definition of Done).

### 1. Démarche Scrum

Le projet a été conduit selon une démarche **Scrum** simplifiée, adaptée au contexte académique d'un développeur solo sur une durée de quelques semaines.

| Artefact Scrum | Contenu dans ce projet |
|---|---|
| **Product Backlog** | Liste priorisée de toutes les fonctionnalités avec effort estimé |
| **Sprint Backlog** | Fonctionnalités sélectionnées pour le sprint en cours |
| **Incrément** | Application fonctionnelle livrée à la fin de chaque sprint |
| **Definition of Done** | Critères de complétion d'une fonctionnalité |

### 2. Identification des Acteurs

#### 2.1 Visiteur (non authentifié)

| Action | Description |
|---|---|
| Consulter l'accueil | Page d'accueil avec présentation et livres récents |
| Parcourir le catalogue | Catalogue public avec filtres niveau/classe/matière |
| Voir le détail d'un livre | Fiche complète d'un livre disponible |
| Pages institutionnelles | À propos, Contact, Politique de confidentialité |
| S'inscrire / se connecter | Accès aux formulaires d'authentification |

#### 2.2 Utilisateur Connecté

| Action | Description |
|---|---|
| Tableau de bord | Accès à l'espace personnel |
| Publier un livre | Formulaire d'ajout avec validation Oracle |
| Modifier un livre | Modifier l'état, le prix et la description d'un livre publié |
| Consulter ses livres | Liste de ses publications avec statut et bouton Modifier |
| Envoyer une demande | Solliciter un livre d'un autre utilisateur |
| Gérer ses demandes | Accepter ou refuser les demandes reçues |
| Consulter les notifications | Lire les notifications système |

#### 2.3 Administrateur

| Action | Description |
|---|---|
| Statistiques globales | Utilisateurs, livres, échanges, économie estimée |
| Gestion des comptes | Activer / désactiver des utilisateurs |
| Suppression permanente | Supprimer définitivement un utilisateur sans livres actifs |
| Modération des livres | Masquer / restaurer des livres |
| Annulation de demandes | Annuler n'importe quelle demande |
| Envoi de notifications | Notifier un utilisateur ou tous les utilisateurs actifs |

### 3. Besoins Fonctionnels et Non Fonctionnels

#### 3.1 Besoins Fonctionnels

| ID | Besoin | Acteur | Priorité |
|---|---|---|---|
| BF-01 | Inscription avec validation email | Visiteur | Haute |
| BF-02 | Connexion / déconnexion sécurisée | Visiteur | Haute |
| BF-03 | Catalogue filtrable (niveau, classe, matière) | Tous | Haute |
| BF-04 | Publication d'un livre avec validation Oracle | Utilisateur | Haute |
| BF-05 | Envoi d'une demande | Utilisateur | Haute |
| BF-06 | Acceptation / refus d'une demande | Utilisateur | Haute |
| BF-07 | Tableau de bord personnel | Utilisateur | Haute |
| BF-08 | Système de notifications | Utilisateur | Moyenne |
| BF-09 | Tableau de bord administrateur | Admin | Haute |
| BF-10 | Modération livres et utilisateurs | Admin | Haute |

#### 3.2 Besoins Non Fonctionnels

| Catégorie | Exigence |
|---|---|
| **Architecture** | Séparation MVC stricte |
| **Sécurité** | Requêtes préparées PDO, contrôle des rôles, XSS prevention |
| **Performance** | Index Oracle sur les colonnes interrogées fréquemment |
| **Maintenabilité** | Namespaces PHP, classes séparées par responsabilité |
| **Compatibilité** | Oracle XE + PHP 7.4 + PDO_OCI |
| **Déployabilité** | Lancement local en une commande |

### 4. User Stories

```
US-01 : En tant que visiteur,
        je veux consulter le catalogue sans me connecter,
        afin de voir les livres disponibles avant de créer un compte.

US-02 : En tant qu'utilisateur,
        je veux publier un livre avec niveau, classe, matière et état,
        afin de le mettre à disposition d'autres utilisateurs.

US-03 : En tant qu'utilisateur,
        je veux filtrer le catalogue par niveau, classe et matière,
        afin de trouver rapidement le livre dont j'ai besoin.

US-04 : En tant qu'utilisateur,
        je veux envoyer une demande pour un livre disponible,
        afin de prendre contact avec son propriétaire.

US-05 : En tant que propriétaire,
        je veux accepter ou refuser une demande avec une note de rendez-vous,
        afin de finaliser ou décliner l'échange depuis mon tableau de bord.

US-06 : En tant qu'utilisateur,
        je veux recevoir une notification lorsque ma demande est traitée,
        afin d'être informé sans consulter constamment mon tableau de bord.

US-07 : En tant qu'administrateur,
        je veux consulter les statistiques globales de la plateforme,
        afin de surveiller l'activité et prendre des décisions de modération.

US-08 : En tant qu'administrateur,
        je veux désactiver un compte ou masquer un livre non conforme,
        afin de modérer la plateforme de manière ciblée et réversible.
```

### 5. Product Backlog

Le backlog est priorisé selon la valeur métier et l'urgence. L'effort est estimé en **jours-développeur (JD)**.

| ID | User Story | Priorité | Effort (JD) | Statut |
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
| PB-13 | Tables de référence Oracle (subjects, classes) | Haute | 1 | Done |
| PB-14 | Modification d'un livre (edit-book) | Haute | 1 | Done |
| PB-15 | Suppression permanente d'un utilisateur (admin) | Haute | 0.5 | Done |
| PB-16 | Validation téléphone 8 chiffres à l'inscription | Haute | 0.5 | Done |
| PB-17 | Upload d'image de couverture | Basse | 2 | To Do |
| PB-18 | Token CSRF | Moyenne | 0.5 | To Do |
| PB-19 | Tests automatisés PHPUnit | Basse | 3 | To Do |

**Effort total réalisé :** ~19 jours-développeur
**Effort restant :** ~5.5 jours-développeur

### 6. Sprint Backlog et Avancement

#### 6.1 Sprint 1 — Fondations (Semaines 1-2)

**Objectif :** Mise en place de l'architecture, de la base Oracle et de l'authentification.

| Tâche | État |
|---|---|
| Création du schéma Oracle (tables, séquences, triggers, index) | Done |
| Mise en place de l'architecture MVC PHP | Done |
| Implémentation de la connexion PDO Oracle | Done |
| Inscription et connexion utilisateur | Done |
| Layout partagé (header/footer) | Done |
| Page d'accueil | Done |

#### 6.2 Sprint 2 — Fonctionnalités Cœur (Semaines 3-4)

**Objectif :** Catalogue, ajout de livres et gestion des demandes.

| Tâche | État |
|---|---|
| Catalogue public avec filtres | Done |
| Tables de référence Oracle (subjects, school_classes, class_subjects) | Done |
| Filtres dynamiques JavaScript (classe → matières) | Done |
| Formulaire d'ajout de livre avec validation Oracle | Done |
| Envoi d'une demande | Done |
| Tableau de bord utilisateur (livres + demandes) | Done |

#### 6.3 Sprint 3 — Administration et PL/SQL (Semaines 5-6)

**Objectif :** Espace admin, procédures PL/SQL et polish.

| Tâche | État |
|---|---|
| Acceptation / refus d'une demande | Done |
| Procédure PL/SQL `accept_request` | Done |
| Tableau de bord administrateur | Done |
| Modération livres et utilisateurs | Done |
| Suppression permanente d'un utilisateur (admin) | Done |
| Système de notifications | Done |
| Objets PL/SQL complets (fonctions, triggers métier) | Done |
| Pages institutionnelles (About, Contact, Privacy) | Done |
| Modification d'un livre (`/edit-book`) | Done |
| Validation téléphone 8 chiffres à l'inscription | Done |

#### Backlog Restant (Non réalisé dans ce sprint)

| Tâche | État |
|---|---|
| Upload d'image de couverture | To Do |
| Token CSRF sur les formulaires | To Do |
| Tests automatisés PHPUnit | To Do |

### 7. Architecture Logique

```
                   ┌──────────────────────────────────────────────┐
                   │             router.php (Front Controller)    │
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
              │         Modèle          │   │        Vue          │
              │   Requêtes SQL/PDO      │   │   Rendu HTML PHP    │
              │   sur Oracle XE         │   │   + Layout partagé  │
              └───────────────┬─────────┘   └────────────────────┘
                              │
              ┌───────────────▼─────────┐
              │       Oracle XE         │
              │   Tables, Séquences,    │
              │   Triggers, PL/SQL      │
              └─────────────────────────┘
```

### 8. Définition of Done

Une fonctionnalité est considérée **Done** si :

1. La logique métier est implémentée dans le contrôleur et le modèle
2. Toutes les entrées utilisateur sont validées côté serveur
3. La vue est accessible et affiche les bonnes informations
4. Les messages de succès et d'erreur sont correctement gérés
5. Les règles métier spécifiques sont respectées (ex. : pas de demande sur son propre livre)
6. La fonctionnalité a été testée manuellement via le navigateur avec des données réelles

---

&nbsp;

<div align="center">

## Chapitre III
### Partie RPA — Réingénierie des Processus d'Affaires

</div>

---

### Introduction

Au niveau de ce chapitre, nous analysons les processus métier de la plateforme BookCycle Tunisia selon une approche **Data-Driven Reengineering**. Nous cartographions les processus, évaluons leur performance actuelle via des SLA et KPI, sélectionnons le processus à fort potentiel de rupture, et proposons une solution cible intégrant Intelligence Artificielle et automatisation RPA.

### 1. Cartographie des Processus Métier

La plateforme **BookCycle Tunisia** s'organise autour de trois catégories de processus.

#### 1.1 Processus Cœur (Valeur directe pour l'utilisateur)

| Processus | Description | Acteurs |
|---|---|---|
| **Publication d'un livre** | Saisie, validation et mise en ligne d'un livre scolaire | Utilisateur |
| **Recherche et filtrage** | Exploration du catalogue avec critères | Tous |
| **Envoi d'une demande** | Sollicitation d'un livre auprès de son propriétaire | Utilisateur |
| **Traitement d'une demande** | Réponse du propriétaire (acceptation ou refus) | Utilisateur |
| **Finalisation d'un échange** | Confirmation et enregistrement de l'échange | Utilisateur + Système |

#### 1.2 Processus Support (Infrastructure)

| Processus | Description |
|---|---|
| Gestion des comptes | Inscription, authentification, activation/désactivation |
| Notifications système | Envoi et lecture des notifications |
| Maintenance Oracle | Scripts SQL, sauvegardes, administration de la BD |
| Maintenance applicative | Déploiement, mises à jour du code PHP |

#### 1.3 Processus de Management (Pilotage)

| Processus | Description |
|---|---|
| Administration | Supervision globale de la plateforme |
| Modération | Contrôle des contenus et des utilisateurs |
| Suivi des statistiques | Analyse des KPI et de l'activité de la plateforme |

### 2. Évaluation de la Performance — État As-Is

#### 2.1 SLA (Service Level Agreements) Actuels

| Processus | SLA défini | Performance réelle | Statut |
|---|---|---|---|
| Publication d'un livre | < 2 min | 3 à 5 min (configuration initiale incluse) | Dégradé |
| Recherche et filtrage | < 3 sec | < 1 sec (Oracle + index) | Acceptable |
| Envoi d'une demande | Immédiat | Immédiat | Acceptable |
| **Traitement d'une demande** | **< 24h** | **24h à 7 jours (dépend du propriétaire)** | **Inacceptable** |
| Finalisation d'un échange | Automatique | Manuel et variable | Dégradé |

#### 2.2 KPI Actuels (As-Is)

| KPI | Valeur estimée As-Is | Cible To-Be |
|---|---|---|
| Délai moyen de traitement d'une demande | 48 à 96 heures | < 12 heures |
| Taux de réponse des propriétaires | ~60% (oublis fréquents) | > 90% |
| Taux d'acceptation des demandes | ~40% | > 60% |
| Taux d'abandon de demandes | ~25% | < 10% |
| Satisfaction utilisateur (estimée) | Moyenne | Élevée |

### 3. Processus Sélectionné pour le BPR

**Processus sélectionné : le traitement d'une demande de livre**

Ce processus présente le plus fort potentiel de rupture car :
- Son SLA actuel est **inacceptable** (délai de 24h à 7 jours)
- Il constitue le **cœur de valeur** de la plateforme
- Il génère le plus d'insatisfaction et d'abandons utilisateurs
- Il se prête à des gains de performance supérieurs à **50%** par automatisation

#### 3.1 Flux As-Is Détaillé

```
 Demandeur          Propriétaire        Système
     │                    │                │
     ├── Envoie demande ──►│                │
     │                    │                │
     │          [Propriétaire doit se      │
     │          connecter manuellement]    │
     │                    │◄─── Consulte dashboard ──┤
     │                    │◄─── Lit demandes reçues  │
     │                    │                │
     │              [Décision manuelle     │
     │               sans délai imposé]   │
     │                    ├── Accepte ou ──►│
     │                    │    refuse       │
     │◄── Notification ───┼─────────────────┤
     │                    │                │
```

**Durée totale As-Is :** 24h à 7 jours
**Dépendance :** 100% manuelle côté propriétaire

### 4. Analyse SWOT — État As-Is

#### 4.1 Forces (Strengths)

| Force | Description |
|---|---|
| Processus simple | Peu d'étapes, facile à comprendre pour l'utilisateur |
| Autonomie du propriétaire | Décision finale reste humaine |
| Traçabilité | Historique des demandes conservé dans Oracle |
| Notification existante | Le demandeur est informé une fois la décision prise |

#### 4.2 Faiblesses (Weaknesses)

| Faiblesse | Impact |
|---|---|
| Dépendance totale à l'action manuelle | Délais imprévisibles et non maîtrisés |
| Aucun rappel automatique | Risque élevé d'oubli de la part du propriétaire |
| Absence de priorisation | Toutes les demandes ont le même traitement |
| Pas d'indicateurs de performance | Impossible de mesurer et d'améliorer |
| Pas de clôture automatique | Les demandes redondantes restent en attente |

#### 4.3 Opportunités (Opportunities)

| Opportunité | Description |
|---|---|
| Automatisation des rappels | Réduction significative des oublis |
| Intégration IA | Prédiction des acceptations, scoring des demandes |
| Tableaux de bord enrichis | Meilleure visibilité pour l'admin et les utilisateurs |
| Réengagement utilisateurs | Relances ciblées pour réactiver la plateforme |

#### 4.4 Menaces (Threats)

| Menace | Description |
|---|---|
| Résistance au changement | Les propriétaires habitués peuvent mal accepter les relances automatiques |
| Faux positifs des rappels | Des rappels trop fréquents peuvent être perçus comme du spam |
| Dépendance à la donnée | La qualité des prédictions IA dépend du volume de données |

### 5. Méthodologie BPR Adoptée

#### Choix : Réingénierie Progressive mais Radicale

La méthodologie retenue est une **réingénierie progressive mais radicale**, et non une approche "Greenfield" (table rase).

| Critère | Greenfield | Progressive Radicale | Choix |
|---|---|---|---|
| Conservation des données existantes | Non | Oui | Progressive |
| Risque de rupture pour les utilisateurs | Très élevé | Maîtrisé | Progressive |
| Délai de mise en œuvre | Long | Moyen | Progressive |
| Saut de performance potentiel | Maximum | > 50% | Les deux |
| Compatibilité avec Oracle/PHP existant | Non | Oui | Progressive |

L'approche progressive radicale permet de conserver la base Oracle, le code PHP et l'expérience utilisateur existants, tout en y greffant des composants IA et d'automatisation qui transforment radicalement le processus de traitement des demandes, en visant un gain de performance **≥ 50%** sur le délai de traitement.

### 6. Solution Cible — État To-Be

#### 6.1 Architecture To-Be avec IA et Automatisation

```
 Demandeur    Propriétaire    Système Central    Moteur IA    Moteur RPA
     │               │               │               │              │
     ├── Envoie ────►│               │               │              │
     │   demande     ├──────────────►│               │              │
     │               │               │               │              │
     │               │ ◄─ Notif. ────┤               │              │
     │               │   immédiate   │               │              │
     │               │               │──── Score ───►│              │
     │               │               │   demande    │              │
     │               │               │◄── Score ─────┤              │
     │               │               │   (priorité)  │              │
     │               │               │                              │
     │               │         [Si pending > 48h]                   │
     │               │◄──────────────────────────── Relance auto ───┤
     │               │                                              │
     │               ├── Accepte ───►│                              │
     │               │               │                              │
     │◄── Notif. ────┤               │──── Clôture auto ────────────►
     │  (accepté)    │               │    autres demandes            │
     │               │               │                              │
     [Autres]◄── Notif. (rejeté) ────┤                              │
```

**Durée totale To-Be :** < 12 heures (objectif)
**Gain estimé :** > 75% de réduction du délai de traitement

#### 6.2 Composants Technologiques de la Solution To-Be

**1. Intelligence Artificielle — Scoring des Demandes**

Un module de scoring IA analyse chaque nouvelle demande et calcule un score de priorité basé sur :

- L'historique de réponse du propriétaire (délai moyen de traitement)
- Le nombre de demandes en attente pour le même livre
- L'ancienneté du livre sur la plateforme
- Le profil du demandeur (nombre d'échanges réussis antérieurs)

```
Score de priorité = f(délai_historique, nb_demandes_concurrentes, ancienneté_livre, profil_demandeur)
```

Les demandes à score élevé remontent automatiquement en tête du tableau de bord du propriétaire.

**2. Automatisation RPA — Relances Intelligentes**

Le moteur RPA détecte les demandes `pending` depuis plus de 48 heures et déclenche une relance automatique :

```sql
SELECT r.id, r.book_id, b.owner_id, b.title,
       ROUND(SYSDATE - r.request_date) AS jours_attente
FROM requests r
JOIN books b ON b.id = r.book_id
WHERE r.status = 'pending'
  AND r.request_date < SYSDATE - 2
ORDER BY jours_attente DESC;
```

La procédure PL/SQL `add_notification` est appelée automatiquement pour envoyer le rappel.

**3. Workflow Moderne — Clôture Automatique**

La procédure `accept_request` implémente déjà la clôture automatique des demandes redondantes. Dans le To-Be, ce mécanisme est complété par :

- Notification automatique de tous les demandeurs rejetés
- Mise à jour du score IA des propriétaires actifs (renforcement positif)
- Enregistrement automatique de l'échange dans `exchanges` via trigger

**4. Interface Utilisateur Unifiée**

- Dashboard propriétaire enrichi avec indicateurs de délai et alertes visuelles
- Tri automatique des demandes par score de priorité IA
- Indicateurs de performance visibles par l'administrateur en temps réel

### 7. Analyse SWOT — État To-Be

#### 7.1 Forces (Strengths)

| Force | Description |
|---|---|
| Délai de traitement réduit | < 12h grâce aux relances automatiques et à la prioritisation IA |
| Moins d'oublis | Les relances automatiques réduisent le risque d'abandon |
| Décision mieux informée | Le scoring IA aide le propriétaire à prioriser |
| Clôture automatique | Cohérence des données garantie sans action manuelle |
| Indicateurs de pilotage | KPI en temps réel pour l'administrateur |

#### 7.2 Faiblesses (Weaknesses)

| Faiblesse | Description |
|---|---|
| Dépendance à la qualité des données | Le scoring IA nécessite un volume de données suffisant |
| Complexité accrue | Composants IA et RPA à maintenir en plus du code PHP |
| Risque de faux positifs | Relances potentiellement perçues comme spam si mal calibrées |

#### 7.3 Opportunités (Opportunities)

| Opportunité | Description |
|---|---|
| Données d'usage | Chaque échange enrichit le modèle IA et améliore le scoring |
| Extension du modèle IA | Prédiction de la disponibilité future des livres |
| Analytics avancés | Rapports automatiques pour l'administration |

#### 7.4 Menaces (Threats)

| Menace | Description |
|---|---|
| Biais algorithmiques | Le scoring peut favoriser certains profils utilisateurs |
| Dépendance technologique | Panne du moteur RPA = retour au mode manuel |
| Adoption utilisateurs | Les propriétaires peuvent désactiver les notifications |

### 8. Comparaison As-Is vs To-Be

| Dimension | As-Is | To-Be | Gain |
|---|---|---|---|
| Délai de traitement moyen | 48 à 96 heures | < 12 heures | **> 75%** |
| Taux de réponse des propriétaires | ~60% | > 90% | **+50%** |
| Taux d'abandon des demandes | ~25% | < 10% | **-60%** |
| Intervention manuelle pour relances | 100% | 0% (automatisé) | **100%** |
| Clôture des demandes redondantes | Manuelle | Automatique (PL/SQL) | **100%** |
| Visibilité KPI pour l'admin | Nulle | Temps réel | Nouveau |

### 9. KPI et Indicateurs de Pilotage

| Indicateur | Formule | Unité | Cible |
|---|---|---|---|
| **Livres actifs** | `COUNT(*) WHERE is_active=1 AND status='available'` | Nb | Croissance continue |
| **Taux de disponibilité** | Livres `available` / Total livres actifs | % | > 70% |
| **Demandes en attente** | `COUNT(*) WHERE status='pending'` | Nb | < 20% du total |
| **Délai moyen de traitement** | `AVG(date_réponse - request_date)` | Heures | < 12h |
| **Taux d'acceptation** | `COUNT(accepted)` / `COUNT(total)` | % | > 60% |
| **Taux d'abandon** | Demandes sans réponse > 7 jours | % | < 10% |
| **Échanges finalisés** | `COUNT(*) FROM exchanges` | Nb | Croissance continue |
| **Économie estimée** | `SUM(estimated_price)` des livres échangés | DT | Croissance continue |

---

&nbsp;

<div align="center">

## Conclusion

</div>

---

### Bilan AGL

La partie AGL démontre une maîtrise de la démarche Scrum : backlog produit complet avec estimation des efforts, organisation en sprints avec états d'avancement clairs (Done / Doing / To-Do), et définition rigoureuse des critères de complétion. L'architecture MVC adoptée répond aux exigences de séparation des responsabilités et facilite l'évolution future du projet.

### Bilan RPA

La partie RPA confirme l'existence d'une opportunité réelle de transformation. Le processus de traitement des demandes présente un SLA inacceptable en l'état actuel, et les analyses SWOT As-Is et To-Be montrent clairement comment les automatisations proposées transforment les faiblesses en forces. La solution To-Be — combinant scoring IA, relances RPA automatiques et workflow Oracle PL/SQL — vise un gain de performance supérieur à **75% sur le délai de traitement**, bien au-delà de la barre des 50% requise pour justifier une approche BPR.

La comparaison SWOT entre As-Is et To-Be illustre en particulier comment les menaces initiales (dépendance à l'action manuelle, risque d'oubli) deviennent des opportunités grâce à la réingénierie, tout en introduisant de nouveaux risques à maîtriser (qualité des données IA, dépendance technologique).

---

*Rapport réalisé dans le cadre du Projet Intégré — Licence 2 Big Data et Intelligence Artificielle — ESEN — Université de la Manouba — 2025/2026*
