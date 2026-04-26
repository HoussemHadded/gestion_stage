
---

<div align="center">

# République Tunisienne
## Ministère de l'Enseignement Supérieur et de la Recherche Scientifique

---

**Établissement :** ___________________________________

**Département :** Informatique

**Année Universitaire :** 2025 / 2026

---

# CAHIER DES CHARGES

## Plateforme Intelligente de Gestion des Stages

---

**Présenté par :**
Houssem Hadded

**Encadré par :**
___________________________________

**Date :**
2026

</div>

---
---

## REMERCIEMENTS

Je tiens à exprimer ma profonde gratitude envers toutes les personnes qui ont contribué, de près ou de loin, à l'élaboration de ce projet de fin d'études.

Je remercie sincèrement mon encadrant pour ses précieux conseils, sa disponibilité et son soutien constant tout au long de la réalisation de ce travail. Ses orientations m'ont été d'une aide inestimable.

Je remercie également l'ensemble du corps enseignant du département Informatique pour la qualité de la formation dispensée, ainsi que pour les compétences techniques et méthodologiques transmises au fil des années.

Mes vifs remerciements vont aussi aux membres du jury pour l'honneur qu'ils me font en acceptant d'évaluer ce travail.

Enfin, je remercie ma famille et mes amis pour leur soutien indéfectible, leur encouragement et leur patience tout au long de mon parcours académique.

---

## DÉDICACE

*Je dédie ce travail...*

À mes chers parents, pour leur amour inconditionnel, leurs sacrifices, et leur soutien moral et matériel sans faille. Vous êtes ma force et mon inspiration.

À ma famille, pour l'affection et la chaleur qu'elle m'a toujours apportées.

À mes amis et collègues, pour les moments partagés, les encouragements mutuels et la solidarité qui ont rendu ce parcours plus agréable.

À tous ceux qui, de près ou de loin, ont cru en moi et m'ont accompagné dans cette aventure académique.

> *"L'éducation est l'arme la plus puissante que vous puissiez utiliser pour changer le monde."*
> — Nelson Mandela

---

## RÉSUMÉ DU PROJET

Le présent cahier des charges décrit la conception et le développement d'une **Plateforme Intelligente de Gestion des Stages**, développée dans le cadre d'un projet de fin d'études (PFE).

La plateforme vise à digitaliser et à moderniser l'ensemble du processus de gestion des stages académiques, en réunissant sur un même écosystème numérique trois acteurs clés : les **étudiants**, les **entreprises partenaires** et l'**administration universitaire**. Elle intègre des fonctionnalités avancées d'**Intelligence Artificielle**, notamment l'analyse automatique des CV, le matching intelligent entre candidats et offres, ainsi qu'un assistant IA de conseil carrière.

Développée avec le framework **Laravel 12** (PHP 8.2), une base de données **MySQL**, et une interface **Blade / Bootstrap**, la plateforme constitue une solution complète, scalable et sécurisée, prête à être déployée dans un contexte académique ou professionnel réel.

**Mots-clés :** Gestion des stages, Intelligence Artificielle, Laravel, Matching CV, Plateforme numérique, PFE.

---

## TABLE DES MATIÈRES

- [INTRODUCTION GÉNÉRALE](#introduction-générale)
- [CHAPITRE 1 : ÉTUDE DE L'EXISTANT](#chapitre-1--étude-de-lexistant)
- [CHAPITRE 2 : ANALYSE DES BESOINS](#chapitre-2--analyse-des-besoins)
- [CHAPITRE 3 : CONCEPTION FONCTIONNELLE](#chapitre-3--conception-fonctionnelle)
- [CHAPITRE 4 : ARCHITECTURE TECHNIQUE](#chapitre-4--architecture-technique)
- [CHAPITRE 5 : BASE DE DONNÉES](#chapitre-5--base-de-données)
- [CHAPITRE 6 : MODULE INTELLIGENCE ARTIFICIELLE](#chapitre-6--module-intelligence-artificielle)
- [CHAPITRE 7 : SÉCURITÉ](#chapitre-7--sécurité)
- [CHAPITRE 8 : INTERFACES PRINCIPALES](#chapitre-8--interfaces-principales)
- [CHAPITRE 9 : PLANNING DU PROJET](#chapitre-9--planning-du-projet)
- [CHAPITRE 10 : RÉSULTATS ATTENDUS](#chapitre-10--résultats-attendus)
- [CONCLUSION GÉNÉRALE](#conclusion-générale)
- [ANNEXES](#annexes)

---

## INTRODUCTION GÉNÉRALE

### Contexte et présentation du domaine

Dans un monde en constante évolution technologique, le secteur de l'enseignement supérieur est confronté à un impératif croissant : celui de moderniser ses pratiques administratives et pédagogiques. La gestion des stages représente l'une des dimensions les plus critiques de ce secteur, car elle constitue le pont entre la formation académique et le monde professionnel.

Les établissements d'enseignement supérieur, les étudiants et les entreprises partenaires sont liés par un processus complexe d'identification d'opportunités, de candidature, de suivi et d'évaluation. Ce processus, s'il est mal géré, peut engendrer des délais, des erreurs et une expérience utilisateur dégradée pour toutes les parties prenantes.

### Importance de la digitalisation

La transformation numérique n'est plus une option, mais une nécessité absolue. Les organisations qui adoptent des outils numériques adaptés bénéficient d'une meilleure productivité, d'une réduction des coûts opérationnels et d'une capacité accrue à s'adapter aux changements. La digitalisation des processus de gestion des stages s'inscrit dans cette logique, en permettant :

- **L'automatisation** des tâches répétitives et chronophages ;
- **La centralisation** des informations sur une plateforme unique et accessible ;
- **La traçabilité** et le suivi en temps réel de chaque candidature ;
- **La communication fluide** entre toutes les parties prenantes.

### Importance des stages

Le stage constitue une étape fondamentale dans le parcours de formation d'un étudiant. Il lui permet d'acquérir une expérience professionnelle concrète, de développer ses compétences techniques et relationnelles, et de s'intégrer progressivement dans le monde du travail. Pour les entreprises, le stage représente une opportunité de découvrir de nouveaux talents et d'investir dans la formation de futurs collaborateurs.

### Problématique actuelle

Malgré l'importance stratégique des stages, leur gestion reste souvent archaïque dans de nombreux établissements :

- Les candidatures sont transmises par courrier physique ou par e-mail non structuré ;
- Le suivi des dossiers est assuré manuellement, avec des risques de perte d'information ;
- Il n'existe aucun système de correspondance automatique entre les profils étudiants et les offres disponibles ;
- La communication entre étudiants, entreprises et administration est fragmentée et inefficace ;
- L'administration ne dispose d'aucun tableau de bord centralisé pour superviser l'ensemble du processus.

### Objectif du projet

L'objectif principal de ce projet est de concevoir et développer une **Plateforme Intelligente de Gestion des Stages** permettant de :

1. Digitaliser l'ensemble du cycle de vie du stage, de la recherche d'offres à l'évaluation finale ;
2. Offrir une expérience utilisateur moderne, intuitive et accessible sur tous les appareils ;
3. Intégrer des modules d'Intelligence Artificielle pour automatiser l'analyse des CV et le matching candidat/offre ;
4. Fournir à l'administration des outils de suivi, de statistiques et de gouvernance centralisés ;
5. Garantir la sécurité des données et la confidentialité des informations personnelles.

---

## CHAPITRE 1 : ÉTUDE DE L'EXISTANT

### 1.1 Gestion Traditionnelle des Stages

Avant l'avènement des solutions numériques, la gestion des stages reposait entièrement sur des processus manuels et papier. L'étudiant devait :

- Se rendre physiquement au bureau des stages pour obtenir une liste d'entreprises partenaires ;
- Rédiger et imprimer son CV ainsi que sa lettre de motivation ;
- Déposer ou envoyer par courrier son dossier de candidature à chaque entreprise ciblée ;
- Attendre une réponse sans possibilité de suivi en temps réel.

Du côté des entreprises, la réception de nombreuses candidatures papier rendait le traitement long, fastidieux et exposé aux erreurs humaines. L'administration, quant à elle, peinait à maintenir un registre à jour de tous les stages en cours.

### 1.2 Problèmes Rencontrés

Les principaux dysfonctionnements identifiés dans les systèmes traditionnels sont les suivants :

#### 🔴 Lenteur du processus
Le délai entre le dépôt d'une candidature et la réception d'une réponse pouvait s'étendre sur plusieurs semaines, voire plusieurs mois, en raison du traitement manuel de chaque dossier.

#### 🔴 Perte de dossiers
Les documents physiques étaient fréquemment égarés, endommagés ou mal classés, entraînant des situations de blocage pour les étudiants concernés.

#### 🔴 Manque de suivi
Ni les étudiants ni l'administration ne disposaient d'un moyen fiable de connaître l'état d'avancement d'une candidature à un instant donné.

#### 🔴 Absence de matching intelligent
La correspondance entre les compétences d'un étudiant et les exigences d'une offre de stage reposait uniquement sur la lecture humaine, sans aucune aide algorithmique.

#### 🔴 Communication difficile
Les échanges entre étudiants, entreprises et administration étaient décentralisés, utilisant des canaux hétérogènes (téléphone, e-mail, courrier) sans traçabilité ni historique unifié.

### 1.3 Analyse Critique

L'analyse de l'existant révèle une inadéquation structurelle entre les besoins modernes de gestion des stages et les outils disponibles. Cette inadéquation se manifeste par :

- Un **coût humain élevé** : beaucoup de temps et d'énergie sont consacrés à des tâches à faible valeur ajoutée ;
- Un **manque de transparence** : les parties prenantes ne disposent pas d'une vision claire et partagée du processus ;
- Une **expérience utilisateur dégradée** : les étudiants et les entreprises se retrouvent souvent frustrés par la lourdeur administrative ;
- Une **impossibilité de scalabilité** : le système traditionnel ne peut pas absorber une augmentation du volume de candidatures sans une mobilisation proportionnelle de ressources humaines supplémentaires.

### 1.4 Solution Proposée

Face à ces constats, nous proposons le développement d'une **Plateforme Intelligente de Gestion des Stages**, une application web complète qui centralise et automatise l'ensemble du processus. Cette plateforme intègre :

- Un système de gestion des comptes multi-rôles (Étudiant, Entreprise, Administrateur) ;
- Un moteur de matching basé sur l'Intelligence Artificielle pour optimiser la correspondance candidat/offre ;
- Un tableau de bord dédié à chaque acteur avec des indicateurs clés en temps réel ;
- Un système de notifications automatiques pour tenir toutes les parties informées ;
- Un module de messagerie intégrée pour fluidifier la communication.

### 1.5 Tableau Comparatif : Ancien Système vs Nouveau Système

| Critère | Ancien Système | Nouvelle Plateforme |
|---|---|---|
| **Mode de candidature** | Papier / e-mail non structuré | Formulaire numérique en ligne |
| **Suivi de candidature** | Inexistant | Tableau de bord en temps réel |
| **Matching candidat/offre** | Manuel, subjectif | Algorithmique, basé sur l'IA |
| **Communication** | Fragmentée (tél., e-mail) | Messagerie intégrée centralisée |
| **Gestion des CV** | Fichiers physiques ou e-mails | Upload PDF, stockage sécurisé |
| **Analyse des CV** | Lecture humaine uniquement | Extraction automatique par IA |
| **Notifications** | Aucune | Notifications automatiques temps réel |
| **Statistiques** | Absentes | Tableaux de bord analytiques |
| **Accessibilité** | Heures de bureau uniquement | 24h/24, 7j/7, tous appareils |
| **Sécurité des données** | Faible (papier, e-mails) | Chiffrement, CSRF, middleware |
| **Scalabilité** | Très limitée | Architecture cloud-ready |
| **Coût opérationnel** | Élevé (ressources humaines) | Réduit (automatisation) |

---

## CHAPITRE 2 : ANALYSE DES BESOINS

### 2.1 Besoins Fonctionnels

Les besoins fonctionnels décrivent l'ensemble des fonctionnalités que la plateforme doit offrir à chaque type d'acteur.

#### 👨‍🎓 Étudiant

| # | Fonctionnalité | Description |
|---|---|---|
| F01 | Création de compte | S'inscrire avec e-mail, mot de passe et informations académiques |
| F02 | Connexion sécurisée | Authentification via e-mail et mot de passe |
| F03 | Gestion du profil | Modifier ses informations personnelles et académiques |
| F04 | Import de CV (PDF) | Téléverser son CV au format PDF pour analyse IA |
| F05 | Consultation des offres | Parcourir et filtrer les offres de stage disponibles |
| F06 | Candidature en ligne | Postuler à une offre en un clic |
| F07 | Suivi des candidatures | Visualiser l'état de chaque candidature (En attente, Acceptée, Refusée) |
| F08 | Système de favoris | Sauvegarder des offres pour y revenir ultérieurement |
| F09 | Notifications | Recevoir des alertes pour chaque changement de statut |
| F10 | Coach IA | Interagir avec un assistant IA pour des conseils carrière personnalisés |
| F11 | Évaluation du stage | Remplir un formulaire d'évaluation à la fin du stage |

#### 🏢 Entreprise

| # | Fonctionnalité | Description |
|---|---|---|
| F12 | Création de compte entreprise | S'inscrire avec SIRET, raison sociale, secteur d'activité |
| F13 | Publication d'offres | Créer, modifier et supprimer des offres de stage |
| F14 | Consultation des candidatures | Voir la liste des étudiants ayant postulé |
| F15 | Téléchargement des CV | Accéder au CV PDF de chaque candidat |
| F16 | Score IA | Consulter le score de compatibilité calculé par l'IA |
| F17 | Shortlist des candidats | Sélectionner les profils les plus pertinents |
| F18 | Gestion des statuts | Accepter ou refuser des candidatures |
| F19 | Tableau de bord | Visualiser les statistiques de ses offres et candidatures |

#### 🛡️ Administration

| # | Fonctionnalité | Description |
|---|---|---|
| F20 | Gestion des utilisateurs | Créer, modifier, suspendre ou supprimer des comptes |
| F21 | Gestion des entreprises | Valider et superviser les comptes entreprises |
| F22 | Validation des comptes | Approuver les nouvelles inscriptions avant activation |
| F23 | Tableau de bord statistique | Visualiser les KPIs globaux de la plateforme |
| F24 | Supervision des offres | Modérer les offres publiées par les entreprises |
| F25 | Supervision des candidatures | Suivre l'évolution globale des candidatures |
| F26 | Gestion des évaluations | Consulter et archiver les évaluations de fin de stage |
| F27 | Notifications système | Envoyer des annonces à l'ensemble des utilisateurs |

### 2.2 Besoins Non Fonctionnels

Les besoins non fonctionnels définissent les contraintes qualitatives que la plateforme doit respecter.

| Critère | Exigence |
|---|---|
| **Performance** | Temps de réponse inférieur à 2 secondes pour toutes les pages |
| **Disponibilité** | Disponibilité 24h/24, 7j/7, avec un taux d'uptime ≥ 99% |
| **Sécurité** | Chiffrement bcrypt, protection CSRF, validation des entrées, HTTPS |
| **Ergonomie** | Interface intuitive, navigation fluide, courbe d'apprentissage minimale |
| **Responsive Design** | Adaptation parfaite sur desktop, tablette et mobile (mobile-first) |
| **Maintenabilité** | Code structuré MVC, commenté, suivant les conventions Laravel |
| **Scalabilité** | Architecture permettant d'absorber une croissance du nombre d'utilisateurs |
| **Accessibilité** | Conformité aux standards WCAG 2.1 pour l'accessibilité numérique |
| **Internationalisation** | Support multi-langue (Français prioritaire) |
| **Compatibilité** | Compatible avec les navigateurs modernes (Chrome, Firefox, Edge, Safari) |

---

## CHAPITRE 3 : CONCEPTION FONCTIONNELLE

### 3.1 Identification des Acteurs

La plateforme est articulée autour de trois acteurs principaux, chacun disposant d'un rôle, de droits et d'interfaces spécifiques :

| Acteur | Rôle | Accès |
|---|---|---|
| **Étudiant** | Cherche et postule à des offres de stage | Dashboard étudiant, offres, candidatures, IA Coach |
| **Entreprise** | Publie des offres et gère les candidatures | Dashboard entreprise, offres, candidats |
| **Administrateur** | Supervise et administre la plateforme | Panneau d'administration complet |

### 3.2 Cas d'Utilisation Principaux

#### Diagramme des cas d'utilisation — Étudiant

```
[Étudiant]
    ├── S'inscrire / Se connecter
    ├── Gérer son profil
    ├── Importer son CV (PDF)
    ├── Consulter les offres de stage
    │     └── Filtrer par secteur, durée, localisation
    ├── Postuler à une offre
    ├── Suivre ses candidatures
    ├── Consulter les notifications
    ├── Utiliser le Coach IA
    └── Évaluer son stage
```

#### Diagramme des cas d'utilisation — Entreprise

```
[Entreprise]
    ├── S'inscrire / Se connecter
    ├── Gérer le profil entreprise
    ├── Publier / Modifier / Supprimer une offre
    ├── Consulter les candidatures reçues
    │     ├── Télécharger le CV du candidat
    │     └── Consulter le score IA
    ├── Shortlister des candidats
    └── Accepter / Refuser une candidature
```

#### Diagramme des cas d'utilisation — Administrateur

```
[Administrateur]
    ├── Gérer les comptes utilisateurs
    ├── Valider les inscriptions
    ├── Superviser les offres
    ├── Consulter les statistiques globales
    └── Envoyer des notifications système
```

### 3.3 Scénarios Détaillés

#### Scénario 1 : Déposer une Candidature

| Étape | Acteur | Action |
|---|---|---|
| 1 | Étudiant | Se connecte à son espace personnel |
| 2 | Système | Affiche le tableau de bord avec les offres recommandées |
| 3 | Étudiant | Consulte la liste des offres disponibles |
| 4 | Étudiant | Sélectionne une offre et consulte son détail |
| 5 | Étudiant | Clique sur "Postuler" |
| 6 | Système | Vérifie qu'un CV a bien été importé |
| 7 | Système | Enregistre la candidature avec statut "En attente" |
| 8 | Système | Notifie l'entreprise d'une nouvelle candidature |
| 9 | Étudiant | Reçoit une confirmation de candidature |

#### Scénario 2 : Publier une Offre de Stage

| Étape | Acteur | Action |
|---|---|---|
| 1 | Entreprise | Se connecte à son espace entreprise |
| 2 | Entreprise | Accède à "Mes offres" et clique sur "Nouvelle offre" |
| 3 | Entreprise | Remplit le formulaire (titre, description, durée, compétences requises) |
| 4 | Système | Valide les données saisies |
| 5 | Système | Publie l'offre et la rend visible aux étudiants |
| 6 | Système | Notifie les étudiants correspondants via le moteur de matching |

#### Scénario 3 : Analyse IA du CV

| Étape | Acteur | Action |
|---|---|---|
| 1 | Étudiant | Importe son CV au format PDF |
| 2 | Système | Reçoit le fichier et déclenche le pipeline IA |
| 3 | IA (OpenAI API) | Extrait les compétences, formations et expériences |
| 4 | Système | Stocke les données structurées en base de données |
| 5 | Système | Met à jour le profil IA de l'étudiant |
| 6 | Étudiant | Consulte son profil enrichi avec les compétences détectées |

#### Scénario 4 : Matching IA Candidat / Offre

| Étape | Acteur | Action |
|---|---|---|
| 1 | Système | Reçoit une nouvelle candidature |
| 2 | IA | Compare le profil IA de l'étudiant avec les exigences de l'offre |
| 3 | IA | Calcule un score de compatibilité (0% à 100%) |
| 4 | Système | Enregistre et affiche ce score à l'entreprise |
| 5 | Entreprise | Trie les candidats par score décroissant |

---

## CHAPITRE 4 : ARCHITECTURE TECHNIQUE

### 4.1 Architecture Globale

La plateforme adopte une architecture **MVC (Modèle-Vue-Contrôleur)**, conforme aux bonnes pratiques du framework Laravel. Cette architecture garantit une séparation claire des responsabilités et facilite la maintenance et l'évolution du code.

```
┌─────────────────────────────────────────────────────────────┐
│                        CLIENT (Navigateur)                  │
│              HTML / Blade / Bootstrap / JavaScript           │
└──────────────────────────┬──────────────────────────────────┘
                           │ HTTP/HTTPS
┌──────────────────────────▼──────────────────────────────────┐
│                    SERVEUR WEB (Apache)                      │
│                    Laravel 12 / PHP 8.2                      │
│  ┌─────────────┐  ┌──────────────┐  ┌───────────────────┐  │
│  │   Routes    │  │ Controllers  │  │    Middleware      │  │
│  └─────────────┘  └──────────────┘  └───────────────────┘  │
│  ┌─────────────┐  ┌──────────────┐  ┌───────────────────┐  │
│  │   Models    │  │    Views     │  │    Policies       │  │
│  │  (Eloquent) │  │   (Blade)    │  │    (Auth/ACL)     │  │
│  └─────────────┘  └──────────────┘  └───────────────────┘  │
└──────────┬───────────────────────────────────┬──────────────┘
           │                                   │
┌──────────▼────────────┐         ┌────────────▼──────────────┐
│   Base de Données     │         │       OpenAI API           │
│       MySQL           │         │   (Analyse IA / Chat)      │
└───────────────────────┘         └───────────────────────────┘
```

### 4.2 Stack Technique

#### Backend

| Technologie | Version | Rôle |
|---|---|---|
| **PHP** | 8.2+ | Langage de programmation serveur |
| **Laravel** | 12.x | Framework MVC principal |
| **Eloquent ORM** | (inclus Laravel) | Gestion des relations base de données |
| **Laravel Sanctum** | (inclus Laravel) | Authentification et gestion de sessions |
| **Artisan** | (inclus Laravel) | CLI pour les migrations, seeders, etc. |

#### Frontend

| Technologie | Version | Rôle |
|---|---|---|
| **Blade** | (inclus Laravel) | Moteur de templates HTML |
| **Bootstrap** | 5.x | Framework CSS responsive |
| **JavaScript (ES6+)** | — | Interactivité et dynamisme UI |
| **Chart.js** | 4.x | Visualisation des données statistiques |

#### Base de Données

| Technologie | Version | Rôle |
|---|---|---|
| **MySQL** | 8.x | Système de gestion de base de données relationnelle |
| **phpMyAdmin** | — | Interface d'administration de la BDD |

#### Intelligence Artificielle

| Technologie | Rôle |
|---|---|
| **OpenAI API (GPT-4)** | Analyse de CV, matching, conseils carrière, chatbot |
| **Guzzle HTTP** | Client HTTP pour les appels à l'API OpenAI |

#### Environnement & Déploiement

| Outil | Rôle |
|---|---|
| **XAMPP** | Environnement de développement local (Apache + MySQL) |
| **Composer** | Gestionnaire de dépendances PHP |
| **npm** | Gestionnaire de dépendances JavaScript |
| **Git / GitHub** | Versioning et collaboration |

---

## CHAPITRE 5 : BASE DE DONNÉES

### 5.1 Schéma Relationnel Global

La base de données est organisée autour de plusieurs tables interconnectées, reflétant les entités métier de la plateforme.

### 5.2 Description Détaillée des Tables

---

#### Table : `users`

**Rôle :** Table centrale de gestion des utilisateurs. Stocke les informations d'authentification et le rôle de chaque compte.

| Champ | Type | Description |
|---|---|---|
| `id` | BIGINT (PK) | Identifiant unique auto-incrémenté |
| `name` | VARCHAR(255) | Nom complet de l'utilisateur |
| `email` | VARCHAR(255) UNIQUE | Adresse e-mail (identifiant de connexion) |
| `password` | VARCHAR(255) | Mot de passe hashé (bcrypt) |
| `role` | ENUM | Rôle : `student`, `company`, `admin` |
| `is_active` | BOOLEAN | Statut d'activation du compte |
| `email_verified_at` | TIMESTAMP | Date de vérification de l'e-mail |
| `remember_token` | VARCHAR(100) | Token de session persistante |
| `created_at` | TIMESTAMP | Date de création |
| `updated_at` | TIMESTAMP | Date de dernière modification |

**Relations :** Un utilisateur est lié à un profil `students` ou `companies` selon son rôle.

---

#### Table : `students`

**Rôle :** Stocke les informations académiques et personnelles des étudiants.

| Champ | Type | Description |
|---|---|---|
| `id` | BIGINT (PK) | Identifiant unique |
| `user_id` | BIGINT (FK → users.id) | Référence vers le compte utilisateur |
| `matricule` | VARCHAR(50) | Numéro d'étudiant |
| `filiere` | VARCHAR(100) | Filière de formation |
| `niveau` | VARCHAR(50) | Niveau d'études (L1, L2, L3, M1, M2) |
| `etablissement` | VARCHAR(255) | Établissement d'origine |
| `date_naissance` | DATE | Date de naissance |
| `telephone` | VARCHAR(20) | Numéro de téléphone |
| `adresse` | TEXT | Adresse postale |
| `photo` | VARCHAR(255) | Chemin vers la photo de profil |
| `created_at` | TIMESTAMP | Date de création |
| `updated_at` | TIMESTAMP | Date de dernière modification |

---

#### Table : `companies`

**Rôle :** Stocke les informations des entreprises partenaires.

| Champ | Type | Description |
|---|---|---|
| `id` | BIGINT (PK) | Identifiant unique |
| `user_id` | BIGINT (FK → users.id) | Référence vers le compte utilisateur |
| `nom_societe` | VARCHAR(255) | Raison sociale de l'entreprise |
| `secteur` | VARCHAR(100) | Secteur d'activité |
| `description` | TEXT | Présentation de l'entreprise |
| `adresse` | TEXT | Adresse du siège social |
| `site_web` | VARCHAR(255) | URL du site internet |
| `logo` | VARCHAR(255) | Chemin vers le logo |
| `is_verified` | BOOLEAN | Statut de vérification par l'admin |
| `created_at` | TIMESTAMP | Date de création |
| `updated_at` | TIMESTAMP | Date de dernière modification |

---

#### Table : `offers` (Offres de stage)

**Rôle :** Centralise toutes les offres de stage publiées par les entreprises.

| Champ | Type | Description |
|---|---|---|
| `id` | BIGINT (PK) | Identifiant unique |
| `company_id` | BIGINT (FK → companies.id) | Référence vers l'entreprise |
| `titre` | VARCHAR(255) | Intitulé du stage |
| `description` | TEXT | Description détaillée du poste |
| `competences_requises` | TEXT | Liste des compétences souhaitées |
| `duree` | VARCHAR(50) | Durée du stage (ex. : 3 mois) |
| `localisation` | VARCHAR(255) | Lieu du stage |
| `remuneration` | DECIMAL(8,2) | Indemnité mensuelle (optionnel) |
| `date_debut` | DATE | Date de début souhaitée |
| `date_limite` | DATE | Date limite de candidature |
| `statut` | ENUM | Statut : `ouverte`, `fermée`, `pourvue` |
| `created_at` | TIMESTAMP | Date de publication |
| `updated_at` | TIMESTAMP | Date de modification |

---

#### Table : `applications` (Candidatures)

**Rôle :** Enregistre chaque candidature déposée par un étudiant à une offre.

| Champ | Type | Description |
|---|---|---|
| `id` | BIGINT (PK) | Identifiant unique |
| `student_id` | BIGINT (FK → students.id) | Référence vers l'étudiant |
| `offer_id` | BIGINT (FK → offers.id) | Référence vers l'offre |
| `statut` | ENUM | Statut : `en_attente`, `acceptée`, `refusée` |
| `lettre_motivation` | TEXT | Lettre de motivation (optionnel) |
| `date_candidature` | TIMESTAMP | Date de dépôt de la candidature |
| `commentaire_entreprise` | TEXT | Retour de l'entreprise (optionnel) |
| `created_at` | TIMESTAMP | Date de création |
| `updated_at` | TIMESTAMP | Date de modification |

---

#### Table : `cv_uploads`

**Rôle :** Gère les fichiers CV téléversés par les étudiants.

| Champ | Type | Description |
|---|---|---|
| `id` | BIGINT (PK) | Identifiant unique |
| `student_id` | BIGINT (FK → students.id) | Référence vers l'étudiant |
| `chemin_fichier` | VARCHAR(500) | Chemin de stockage du fichier PDF |
| `nom_original` | VARCHAR(255) | Nom original du fichier |
| `taille` | INT | Taille du fichier en octets |
| `is_principal` | BOOLEAN | Indique si c'est le CV actif |
| `created_at` | TIMESTAMP | Date d'upload |

---

#### Table : `ai_scores`

**Rôle :** Stocke les résultats de l'analyse IA pour chaque couple candidature/offre.

| Champ | Type | Description |
|---|---|---|
| `id` | BIGINT (PK) | Identifiant unique |
| `application_id` | BIGINT (FK → applications.id) | Référence vers la candidature |
| `score` | DECIMAL(5,2) | Score de compatibilité (0.00 à 100.00) |
| `competences_extraites` | JSON | Compétences détectées dans le CV |
| `analyse_detaillee` | TEXT | Analyse narrative générée par l'IA |
| `recommandations` | TEXT | Conseils personnalisés de l'IA |
| `created_at` | TIMESTAMP | Date d'analyse |

---

#### Table : `messages`

**Rôle :** Gère les échanges de messages entre utilisateurs.

| Champ | Type | Description |
|---|---|---|
| `id` | BIGINT (PK) | Identifiant unique |
| `sender_id` | BIGINT (FK → users.id) | Expéditeur |
| `receiver_id` | BIGINT (FK → users.id) | Destinataire |
| `contenu` | TEXT | Contenu du message |
| `is_read` | BOOLEAN | Statut de lecture |
| `created_at` | TIMESTAMP | Date d'envoi |

---

#### Table : `notifications`

**Rôle :** Centralise toutes les notifications générées par le système.

| Champ | Type | Description |
|---|---|---|
| `id` | BIGINT (PK) | Identifiant unique |
| `user_id` | BIGINT (FK → users.id) | Destinataire de la notification |
| `titre` | VARCHAR(255) | Titre court de la notification |
| `message` | TEXT | Contenu de la notification |
| `type` | VARCHAR(50) | Type : `candidature`, `offre`, `système` |
| `is_read` | BOOLEAN | Statut de lecture |
| `lien` | VARCHAR(500) | URL de redirection (optionnel) |
| `created_at` | TIMESTAMP | Date de création |

---

#### Table : `admins`

**Rôle :** Stocke les informations spécifiques aux administrateurs de la plateforme.

| Champ | Type | Description |
|---|---|---|
| `id` | BIGINT (PK) | Identifiant unique |
| `user_id` | BIGINT (FK → users.id) | Référence vers le compte utilisateur |
| `super_admin` | BOOLEAN | Droits super-administrateur |
| `derniere_connexion` | TIMESTAMP | Dernière date de connexion |
| `created_at` | TIMESTAMP | Date de création |

---

### 5.3 Diagramme des Relations (ERD simplifié)

```
users (1) ────── (1) students ────── (N) cv_uploads
                       │
                       └── (N) applications ────── (N) offers ────── (1) companies
                                   │
                                   └── (1) ai_scores

users (1) ────── (1) companies
users (1) ────── (1) admins
users (1,N) ────── (N) messages ────── (N,1) users
users (1) ────── (N) notifications
```

---

## CHAPITRE 6 : MODULE INTELLIGENCE ARTIFICIELLE

### 6.1 Vue d'Ensemble

Le module d'Intelligence Artificielle constitue le cœur différenciateur de la plateforme. Il s'appuie sur l'API **OpenAI (GPT-4)** pour offrir des fonctionnalités avancées d'analyse, de matching et de conseil, inaccessibles aux systèmes traditionnels.

### 6.2 Fonctionnalités IA

#### 🤖 6.2.1 Analyse Automatique des CV

Lorsqu'un étudiant téléverse son CV au format PDF, le système déclenche automatiquement un pipeline d'analyse :

1. **Extraction du texte** : Le contenu du PDF est extrait et converti en texte brut ;
2. **Envoi à l'API OpenAI** : Le texte est transmis avec un prompt structuré ;
3. **Analyse sémantique** : L'IA identifie les sections (formations, expériences, compétences, projets) ;
4. **Retour structuré** : Les données sont retournées au format JSON et stockées en base.

#### 🎯 6.2.2 Extraction des Compétences

L'IA extrait automatiquement et catégorise les compétences :

| Catégorie | Exemples |
|---|---|
| **Compétences techniques** | Python, Laravel, MySQL, React, Docker |
| **Compétences linguistiques** | Français (C1), Anglais (B2), Arabe (natif) |
| **Soft skills** | Travail en équipe, Leadership, Autonomie |
| **Outils** | Git, Figma, VS Code, Jira |

#### 🔗 6.2.3 Matching CV / Offre

Le moteur de matching compare le profil extrait de l'étudiant avec les exigences de l'offre :

```
Score de compatibilité (%) = f(
    correspondance_compétences_techniques × 0.50 +
    niveau_études_requis              × 0.20 +
    expériences_pertinentes           × 0.20 +
    langues_requises                  × 0.10
)
```

Le score final (0% à 100%) est affiché sur le dashboard entreprise, permettant un classement objectif et instantané des candidats.

#### 🏆 6.2.4 Classement Automatique des Candidats

Les candidats sont automatiquement triés par score décroissant, offrant à l'entreprise une **shortlist IA** des profils les plus adaptés à son besoin.

| Rang | Candidat | Score IA | Statut |
|---|---|---|---|
| 🥇 1 | Ahmed B. | 94% | Shortlisté |
| 🥈 2 | Mariem K. | 87% | En attente |
| 🥉 3 | Sami T. | 71% | En attente |

#### 💬 6.2.5 Coach IA — Assistant Intelligent

L'assistant IA est accessible aux étudiants directement depuis leur tableau de bord. Il peut :

- Analyser le CV de l'étudiant et identifier ses points forts et axes d'amélioration ;
- Proposer des offres de stage adaptées à son profil ;
- Rédiger ou améliorer des lettres de motivation ;
- Répondre à des questions liées à la recherche de stage, aux entretiens ou à l'orientation professionnelle ;
- Suggérer des formations et certifications pour renforcer l'employabilité.

#### 📋 6.2.6 Conseils Carrière Personnalisés

Sur la base de l'analyse du profil, l'IA génère un rapport de recommandations personnalisées incluant :

- Les compétences à acquérir en priorité ;
- Les secteurs d'activité les plus compatibles avec le profil ;
- Les entreprises les plus susceptibles de répondre positivement ;
- Des conseils pour optimiser le profil LinkedIn et le CV.

### 6.3 Sécurité et Confidentialité du Module IA

- Les données transmises à l'API OpenAI sont anonymisées avant envoi ;
- Aucune donnée personnelle identifiable n'est partagée sans consentement explicite de l'utilisateur ;
- Les résultats IA sont stockés de manière chiffrée en base de données.

---

## CHAPITRE 7 : SÉCURITÉ

### 7.1 Stratégie Globale de Sécurité

La sécurité de la plateforme repose sur une approche multicouche, combinant les mécanismes natifs de Laravel avec des bonnes pratiques de développement sécurisé.

### 7.2 Mécanismes de Sécurité Implémentés

#### 🔐 Authentification Laravel

- Utilisation du système d'authentification natif de Laravel ;
- Gestion des sessions sécurisées avec expiration automatique ;
- Option "Se souvenir de moi" avec token sécurisé ;
- Vérification de l'adresse e-mail avant activation du compte.

#### 🛡️ Middleware et Contrôle d'Accès

```php
// Exemple de middleware de rôle
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'index']);
});
```

- Chaque route est protégée par des middlewares de rôle (`student`, `company`, `admin`) ;
- Accès strictement limité aux ressources correspondant au rôle de l'utilisateur connecté ;
- Redirection automatique vers la page de connexion en cas d'accès non autorisé.

#### 🔒 Hachage des Mots de Passe

- Tous les mots de passe sont hachés avec l'algorithme **bcrypt** avant stockage ;
- Le facteur de coût est configuré selon les recommandations OWASP ;
- Aucun mot de passe n'est jamais stocké en clair dans la base de données.

#### ✅ Validation des Données

- Toutes les données saisies par l'utilisateur sont validées côté serveur via les **Form Requests** Laravel ;
- Validation du type, de la taille et du format des fichiers uploadés (CV PDF) ;
- Sanitisation des entrées textuelles pour prévenir les attaques XSS.

#### 🔑 Protection CSRF

- Laravel génère automatiquement un token CSRF unique pour chaque session ;
- Tous les formulaires intègrent le token CSRF via `@csrf` ;
- Toute requête POST sans token CSRF valide est automatiquement rejetée.

#### 🛡️ Protection contre les Injections SQL

- L'utilisation exclusive d'**Eloquent ORM** et du **Query Builder** de Laravel garantit la protection contre les injections SQL par paramétrisation automatique des requêtes ;
- Aucune requête SQL brute construite par concaténation de chaînes n'est utilisée.

#### 📁 Sécurité des Fichiers

- Les fichiers CV sont stockés dans le répertoire `storage/app/private` (non accessible publiquement) ;
- L'accès aux fichiers est contrôlé via des routes sécurisées et des vérifications de propriété ;
- Validation stricte du type MIME pour les uploads (PDF uniquement).

| Menace | Mesure de protection |
|---|---|
| Injection SQL | Eloquent ORM + Query Builder paramétré |
| XSS | Échappement automatique Blade (`{{ }}`) |
| CSRF | Token CSRF sur tous les formulaires |
| Brute Force | Rate limiting sur les routes de connexion |
| Accès non autorisé | Middleware de rôle sur toutes les routes |
| Fuite de données | Hash bcrypt + stockage privé des fichiers |
| Session Hijacking | Configuration sécurisée des cookies de session |

---

## CHAPITRE 8 : INTERFACES PRINCIPALES

### 8.1 Page d'Accueil (Landing Page)

La page d'accueil est la vitrine publique de la plateforme. Elle présente :

- Une section héro avec un titre accrocheur et un appel à l'action (CTA) ;
- Une présentation des fonctionnalités clés de la plateforme ;
- Les dernières offres de stage publiées ;
- Un compteur dynamique (nombre d'étudiants, entreprises, offres) ;
- Les témoignages d'utilisateurs ;
- Un pied de page avec les liens utiles et informations de contact.

### 8.2 Page de Connexion / Inscription

- Formulaire de connexion épuré avec validation en temps réel ;
- Formulaire d'inscription avec sélection du rôle (Étudiant / Entreprise) ;
- Option "Mot de passe oublié" avec réinitialisation par e-mail ;
- Design responsive, adapté aux appareils mobiles.

### 8.3 Dashboard Étudiant

Le tableau de bord étudiant offre une vue centralisée de :

- **Résumé du profil** : photo, nom, filière, niveau, score de profil ;
- **Candidatures récentes** : statut et date des dernières candidatures ;
- **Offres recommandées** : suggestions IA basées sur le profil ;
- **Notifications** : alertes non lues ;
- **Accès rapide** au Coach IA.

### 8.4 Dashboard Entreprise

Le tableau de bord entreprise permet de visualiser :

- **Statistiques de l'entreprise** : nombre d'offres actives, candidatures reçues ;
- **Offres en cours** : liste des offres avec nombre de candidats ;
- **Candidatures récentes** : derniers profils reçus avec score IA ;
- **Actions rapides** : publier une nouvelle offre, voir les shortlists.

### 8.5 Dashboard Administrateur

Le panneau d'administration offre une vision globale :

- **KPIs de la plateforme** : nombre total d'utilisateurs, entreprises, offres, candidatures ;
- **Graphiques** : évolution des inscriptions, répartition par rôle, activité hebdomadaire ;
- **Gestion des utilisateurs** : liste, filtrage, activation/désactivation ;
- **Modération des offres** : validation ou suppression des offres publiées.

### 8.6 Liste des Offres

- Affichage en grille ou liste des offres disponibles ;
- Filtres avancés : secteur, durée, localisation, rémunération, date ;
- Barre de recherche plein texte ;
- Pagination optimisée pour les performances ;
- Badge de score IA pour les offres recommandées.

### 8.7 Page Profil

- Affichage et modification des informations personnelles ;
- Gestion des CV uploadés (ajout, suppression, définir comme principal) ;
- Visualisation des compétences extraites par l'IA ;
- Historique des candidatures avec statuts.

### 8.8 Module AI Coach

- Interface de chat conversationnelle et intuitive ;
- Historique des conversations persisté en session ;
- Suggestions de questions prédéfinies pour guider l'utilisateur ;
- Affichage formaté des réponses avec mise en forme Markdown ;
- Bouton de partage ou d'export de la conversation.

---

## CHAPITRE 9 : PLANNING DU PROJET

### 9.1 Tableau Gantt — Planning sur 4 Mois

| Tâche | Mois 1 — Analyse | Mois 2 — Conception | Mois 3 — Développement | Mois 4 — Tests & Finalisation |
|---|:---:|:---:|:---:|:---:|
| **Étude de l'existant** | ████ | | | |
| **Recueil des besoins** | ████ | | | |
| **Rédaction du cahier des charges** | ████ | | | |
| **Modélisation UML (use cases)** | | ████ | | |
| **Conception de la base de données** | | ████ | | |
| **Maquettage des interfaces (Figma)** | | ████ | | |
| **Configuration de l'environnement** | | ████ | | |
| **Développement Backend (Laravel)** | | | ████ | |
| **Développement Frontend (Blade)** | | | ████ | |
| **Intégration du module IA** | | | ████ | |
| **Développement des dashboards** | | | ████ | |
| **Tests unitaires et fonctionnels** | | | | ████ |
| **Correction des bugs** | | | | ████ |
| **Optimisation des performances** | | | | ████ |
| **Rédaction de la documentation** | | | | ████ |
| **Déploiement et démonstration** | | | | ████ |

### 9.2 Jalons Clés

| Jalon | Date prévisionnelle | Livrable |
|---|---|---|
| **Jalon 1** | Fin Mois 1 | Cahier des charges validé, diagrammes UML |
| **Jalon 2** | Fin Mois 2 | Maquettes validées, BDD créée |
| **Jalon 3** | Fin Mois 3 | Version bêta fonctionnelle |
| **Jalon 4** | Fin Mois 4 | Version finale, documentée et déployée |

---

## CHAPITRE 10 : RÉSULTATS ATTENDUS

### 10.1 Gains pour les Étudiants

- **Réduction du temps de recherche** : accès à toutes les offres en un seul endroit ;
- **Candidature en un clic** : processus simplifié et entièrement numérique ;
- **Suivi en temps réel** : visibilité permanente sur l'état de chaque candidature ;
- **Recommandations personnalisées** : offres suggérées en adéquation avec le profil ;
- **Accompagnement IA** : conseils carrière et aide à la rédaction disponibles 24h/24.

### 10.2 Gains pour les Entreprises

- **Recrutement intelligent** : sélection des meilleurs candidats grâce au scoring IA ;
- **Réduction des délais** : classement automatique des candidats en quelques secondes ;
- **Accès à une base de talents** : visibilité sur l'ensemble des profils étudiants disponibles ;
- **Communication centralisée** : toutes les interactions dans une interface unique.

### 10.3 Gains pour l'Administration

- **Supervision complète** : vue d'ensemble de tous les stages en cours ;
- **Tableaux de bord analytiques** : indicateurs clés pour le pilotage et la décision ;
- **Réduction de la charge administrative** : automatisation des processus répétitifs ;
- **Traçabilité totale** : historique complet de toutes les actions et interactions.

### 10.4 Impact Global

| Indicateur | Avant la plateforme | Après la plateforme |
|---|---|---|
| Temps moyen de traitement d'une candidature | 7 à 14 jours | < 24 heures |
| Taux de matching candidat/offre | Non mesuré | ≥ 80% de satisfaction |
| Accessibilité des offres | Limitée (affichage physique) | 100% en ligne, 24h/24 |
| Suivi administratif | Manuel, incomplet | Automatisé, exhaustif |
| Satisfaction des utilisateurs | Non mesurée | Objectif ≥ 4,5/5 |
| Digitalisation du processus | < 20% | 100% |

### 10.5 Vision à Long Terme

La plateforme est conçue pour évoluer et intégrer à terme :

- Un module de **vidéo-entretien en ligne** intégré ;
- Un système de **recommandation prédictive** basé sur le machine learning ;
- Une **application mobile** iOS et Android ;
- Une **API publique** pour l'intégration avec d'autres systèmes universitaires ;
- Un module de **formation en ligne** complémentaire aux stages.

---

## CONCLUSION GÉNÉRALE

Ce cahier des charges présente une vision complète et structurée de la **Plateforme Intelligente de Gestion des Stages**, un projet ambitieux qui répond à un besoin réel et urgent de modernisation dans le secteur de l'enseignement supérieur.

En combinant les puissantes capacités du framework **Laravel**, la robustesse de **MySQL** et l'intelligence de l'**API OpenAI**, cette plateforme propose une solution innovante, sécurisée et scalable qui transforme fondamentalement l'expérience de la recherche et de la gestion des stages.

Les fonctionnalités développées — de l'analyse automatique des CV au matching IA, en passant par les tableaux de bord analytiques et le Coach IA — positionnent cette plateforme comme un outil de référence, capable de répondre aux exigences actuelles et futures des établissements d'enseignement supérieur.

Ce projet démontre qu'il est possible, avec les bons outils et une vision claire, de digitaliser des processus complexes tout en offrant une expérience utilisateur exceptionnelle. Il constitue une base solide pour de futures évolutions et une contribution significative à la transformation numérique du secteur éducatif.

La réalisation de ce projet a permis d'acquérir et d'approfondir des compétences essentielles en développement web moderne, en conception de systèmes d'information et en intégration de l'Intelligence Artificielle — des compétences hautement valorisées dans le marché du travail actuel.

---

## ANNEXES

### Annexe A : Technologies et Versions

| Technologie | Version | Licence | URL officielle |
|---|---|---|---|
| PHP | 8.2.x | PHP License | https://php.net |
| Laravel | 12.x | MIT | https://laravel.com |
| MySQL | 8.0.x | GPL / Commercial | https://mysql.com |
| Bootstrap | 5.3.x | MIT | https://getbootstrap.com |
| JavaScript (ES6+) | ES2022 | Open | — |
| Chart.js | 4.x | MIT | https://chartjs.org |
| Composer | 2.x | MIT | https://getcomposer.org |
| Node.js / npm | 20.x LTS | MIT | https://nodejs.org |
| XAMPP | 8.2.x | GPL | https://apachefriends.org |
| Git | 2.x | GPL-2.0 | https://git-scm.com |

### Annexe B : APIs et Services Externes

| Service | Usage | Documentation |
|---|---|---|
| **OpenAI API (GPT-4)** | Analyse CV, matching, chatbot IA | https://platform.openai.com/docs |
| **Guzzle HTTP** | Client HTTP pour appels API | https://docs.guzzlephp.org |
| **Laravel Storage** | Gestion des fichiers uploadés | https://laravel.com/docs/filesystem |
| **Laravel Notifications** | Système de notifications multi-canaux | https://laravel.com/docs/notifications |

### Annexe C : Outils de Développement

| Outil | Usage |
|---|---|
| **Visual Studio Code** | Éditeur de code principal |
| **GitHub** | Versioning et gestion du code source |
| **Postman** | Test des routes API et endpoints |
| **phpMyAdmin** | Administration de la base de données MySQL |
| **Figma** | Maquettage des interfaces utilisateur |
| **XAMPP** | Serveur de développement local |

### Annexe D : Glossaire

| Terme | Définition |
|---|---|
| **API** | Application Programming Interface — Interface de programmation permettant la communication entre systèmes |
| **IA** | Intelligence Artificielle — Simulation des processus cognitifs humains par des algorithmes |
| **Matching** | Processus d'appariement automatique entre deux entités (CV et offre) |
| **MVC** | Modèle-Vue-Contrôleur — Patron d'architecture logicielle |
| **ORM** | Object-Relational Mapping — Technique de correspondance entre objets et tables de BDD |
| **PFE** | Projet de Fin d'Études — Travail de recherche et développement réalisé en fin de cycle universitaire |
| **CSRF** | Cross-Site Request Forgery — Type d'attaque web et mécanisme de protection associé |
| **Bcrypt** | Algorithme de hachage cryptographique utilisé pour sécuriser les mots de passe |
| **KPI** | Key Performance Indicator — Indicateur clé de performance |
| **SaaS** | Software as a Service — Modèle de distribution logicielle en ligne |

---

<div align="center">

---

*Document généré dans le cadre du Projet de Fin d'Études*
*Département Informatique — Année Universitaire 2025/2026*

**Houssem Hadded**

---

</div>
