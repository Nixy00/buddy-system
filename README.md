# SAÉ 3.01 - Développement d'une application

## 📖 Introduction

Buddy System est une application web permettant de faciliter l'intégration des étudiants internationaux à l'Université de Reims Champagne-Ardenne. L'application permet aux étudiants locaux de se mettre en relation avec des étudiants internationaux via un système de parrainage, d'événements et de messagerie.

## 📂 Sommaire

- [Contributeurs](#-contributeurs)
- [Contexte du Projet](#-contexte-du-projet)
- [Fonctionnalités](#-fonctionnalités)
- [Prérequis](#-prérequis)
- [Technologies](#-technologies)
- [Tests de l'application](#-tests-de-lapplication)
- [Installation / Configuration](#-installation--configuration)
- [Scripts Composer](#-scripts-composer)
- [Architecture du Projet](#-architecture-du-projet)
- [Captures d'écran](#-captures-décran)
- [Licence & Droits](#-licence--droits)

## 👥 Contributeurs

- **Ayoub SEGHIR**     (segh0010)
- **Yacine MOUNSI**    (moun0023)
- **Matteo GODART**    (goda0054)
- **Sacha DEUTSCHE**   (deut0005)
- **Iyed BOUZENADA**   (bouz0078)

## 📌 Contexte du Projet

- Ce projet a été réalisé dans le cadre d'une SAÉ (Situation d'Apprentissage et d'Évaluation) du BUT Informatique.
- Durée : Octobre 2025 - Janvier 2026
- Objectif principal : Développer une application web en utilisant le framework Symfony.
- Enseignants : M. Desjardin, Mme Zahia Guessoum

## ✨ Fonctionnalités

- Consulter la liste des événements (Sport, Culture, Loisirs).
- S'inscrire ou se désinscrire aux événements.
- Consulter les détails d'un événement (lieu, date, places disponibles).
- Système de messagerie privée entre utilisateurs.
- Recherche de buddy avec filtres (langues, disponibilité).
- Gestion de profil personnalisé (bio, langues, avatar).
- Signalement de messages, événements ou utilisateurs inappropriés.
- Dashboard personnel pour suivre ses événements et messages.
- Toutes les pages sont responsives et adaptées aux mobiles.

**Fonctionnalités Admin**

- Gérer les utilisateurs, événements et signalements (CRUD).
- Dashboard avec statistiques (nombre d'utilisateurs, événements populaires, etc.).

## ⚙️ Prérequis

- **PHP** >= 8.2
- **Symfony CLI**
- **Composer** >= 2.0
- **MySQL** ou **MariaDB**

## 💻 Technologies

- Backend: **Symfony** 7.3
- Frontend: **Twig**, **CSS**
- Database: **MySQL**
- Tests: **Codeception**

## 🛠️ Tests de l'application

Vous pouvez tester l'application en local en suivant les instructions d'installation ci-dessous.

Dans le contexte de développement, voici quelques utilisateurs que vous pouvez utiliser pour vous connecter à l'application, tous ayant le mot de passe **test** :

- Admin : **root@example.com**
- User : **user@example.com**

### Données disponibles

Les fixtures génèrent automatiquement :

- **+100 utilisateurs** (profils complets avec bio, langues, centres d'intérêt)
- **+30 événements** (Sport, Culture, Loisirs avec dates variées)
- **+15 conversations** entre utilisateurs
- **+80 messages** répartis dans les conversations
- **+30 signalements** de messages et utilisateurs
- **+25 demandes de relations** (entre deux utilisateurs)

### Procédures de test suggérées

Voici les parcours clés pour tester les fonctionnalités principales :

1.  **Authentification**
    * **Utilisateur :** `user@example.com` / `test`
    * **Admin :** `root@example.com` / `test`

2.  **Cycle de vie d'un Événement**
    * Connectez-vous en tant qu'**Utilisateur** et proposez un événement (il passera en statut "En attente").
    * Connectez-vous en tant qu'**Admin**, allez dans la page **Evenements** puis **Voir les demandes en attente** et vous pourrez alors gérer le statut des événements en attente comme vous le souhaitez.
    * L'événement est désormais visible et ouvert aux inscriptions.

3.  **Messagerie & Social**
    * Recherchez un buddy et envoyez une demande.
    * Échangez des messages (observez le compteur de notifications en haut).
    * Testez l'**archivage** d'une conversation pour vérifier le mode "lecture seule".

4.  **Administration**
    * Accédez à `/admin` pour gérer les utilisateurs, les signalements et modérer les contenus.

## 🚀 Installation / Configuration

Pour installer ce projet, clonez simplement le dépôt git sur votre machine.

```bash
git clone https://iut-info.univ-reims.fr/gitlab/segh00101/sae3-01.git
```

### Installer Composer

Exécutez la commande suivante :

```bash
composer install
```

### Installer Symfony CLI

Si non installé, téléchargez **scoop** en exécutant les commandes suivantes dans un terminal **PowerShell** (Windows uniquement) :

```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
Invoke-RestMethod -Uri https://get.scoop.sh | Invoke-Expression
```

Puis exécutez la commande que vous pouvez trouver sur le site Symfony : [Symfony CLI](https://symfony.com/download) (attention à exécuter la commande selon votre OS ici l'équipe avait principalement travaillé sur windows).

### Configuration de l'accès à la base de données

À la racine de votre projet, créez un fichier `.env.local` et ajoutez-y votre URL de connexion à la base de données :

```env
DATABASE_URL="mysql://USER:PASSWORD@127.0.0.1:3306/buddy_system?serverVersion=mariadb-10.6.0&charset=utf8mb4"
```

**Remplacez `USER` et `PASSWORD` par vos identifiants MySQL.**

💡 **Note** : La base de données sera créée automatiquement lors de l'exécution du script `composer db`.

### Créer et initialiser la base de données

Exécutez la commande suivante pour créer la base de données et charger les données de test :

```bash
composer db
```

Cette commande va :
1. Supprimer la base existante (si elle existe)
2. Créer une nouvelle base de données
3. Appliquer les migrations (créer les tables)
4. Charger les fixtures (données de test)

*Veuillez avoir une bonne connexion internet pour que la commande fonctionne correctement*

⚠️ **Attention** : Cette commande supprime toutes les données existantes !



### Lancer le serveur local en environnement dev

Exécutez la commande suivante :

```bash
composer start
```

## 📝 Scripts Composer

Pour exécuter un script composer, utilisez la commande suivante :

```bash
composer script_name
```

Voici les scripts disponibles :

- `"start"` : Lance le serveur Symfony
- `"db"` : Réinitialise la base de données (drop, create, migrate, fixtures)
- `"fix:twigcs"` : Corrige le code Twig avec Twig CS Fixer
- `"test:twigcs"` : Teste le code Twig avec Twig CS Fixer
- `"fix:phpcs"` : Corrige le code PHP avec PHP CS Fixer
- `"test:phpcs"` : Teste le code PHP avec PHP CS Fixer
- `"test:codeception"` : Lance tous les tests Codeception
- `"test"` : Exécute tous les scripts de test (Twig, PHP, Codeception)

## 🏗️ Architecture du Projet

### 📁 Structure Principale

```bash
├── config/            # Configuration Symfony
├── migrations/        # Migrations Doctrine
├── public/            # Fichiers publics (CSS, JS, images)
├── src/               # Code source PHP
│   ├── Controller/        # Contrôleurs
│   ├── DataFixtures/      # Données de test
│   ├── Entity/            # Entités Doctrine
│   ├── Factory/           # Factories
│   ├── Form/              # Formulaires Symfony
│   ├── Repository/        # Repositories Doctrine
│   └── Security/          # Classes de sécurité
├── templates/         # Templates Twig pour les vues
├── tests/             # Tests automatisés
├── .env               # Variables d'environnement
├── composer.json      # Dépendances Composer
└── README.md          # Documentation du projet
```

### 🗄️ Modèle de Données

- **User** : Représente un utilisateur de l'application.
- **Profil** : Représente le profil détaillé d'un utilisateur (bio, langues, centres d'intérêt).
- **Evenement** : Représente un événement (sport, culture, loisirs).
- **Participation** : Représente l'inscription d'un utilisateur à un événement.
- **Conversation** : Représente une conversation entre deux utilisateurs.
- **Message** : Représente un message dans une conversation.
- **Signalement** : Représente un signalement de message ou d'utilisateur.
- **Relation** : Représente une relation buddy (parrainage).
- **Role** : Représente un rôle utilisateur (ROLE_USER, ROLE_ADMIN).
- **Organisation** : Représente le lien entre un utilisateur organisateur et un événement.
- **Possession** : Représente l'attribution d'un rôle spécifique à un utilisateur.

## 📸 Captures d'écran

### Page d'Accueil

![Page d'accueil](screenshots/home1.png)

La page d'accueil présente Buddy System avec un design moderne et épuré :
- **Hero section** : Slogan "Trouvez votre binôme idéal à l'URCA"
- **Call-to-action** : Boutons "S'inscrire gratuitement" et "Découvrir les événements"
- **Navigation** : Accès aux principales sections (Accueil, Événements, Rechercher)
- **Section "Comment ça marche ?"** : Explication en 3 étapes du processus
- **Prochains événements** : Aperçu des événements à venir

---

### Authentification

![Connexion](screenshots/connexion.png)

**Page de connexion** avec formulaire simple :
- Email étudiant
- Mot de passe
- Lien "Mot de passe oublié ?"

![Inscription](screenshots/inscription.png)

**Formulaire d'inscription** complet :
- Informations personnelles (Prénom, Nom, Email, Âge)
- Mot de passe avec confirmation
- **Type d'étudiant** : Local ou International
- Acceptation des CGU (CGU accessibles depuis le footer)

---

### Dashboard

![Dashboard utilisateur](screenshots/dashboard_user.png)

**Dashboard personnel** avec 4 raccourcis principaux :
- **Mon Profil** : Gérer ses informations
- **Mes Buddies** : Accès rapide à toutes nos relations Buddy
- **Messagerie** : Conversations actives
- **Mes Événements** : Accès rapide aux événement dans lesquels nous sommes inscrits

Un bouton "Supprimer mon compte" est disponible pour la suppression du compte.

![Dashboard admin](screenshots/dashboard_admin.png)

**Dashboard administrateur** avec accès au back-office EasyAdmin via le logo en haut à droite.

---

### Événements

![Liste des événements (vue utilisateur)](screenshots/events_list.png)

**Page de découverte des événements** avec :
- Bouton **"Faire une demande de création"** pour les utilisateurs connectés
- **Filtres** par catégorie et statut
- **Barre de recherche** pour trouver rapidement un événement
- **Cartes événements** affichant : titre, catégorie, date, lieu, places disponibles
- **États visuels** : Ouvert (vert), Fermé(Complet/Expiré) (rouge)

![Création d'événement](screenshots/creation_event.png)

**Formulaire de création d'événement** permettant de renseigner :
- **Informations principales** : Titre, Catégorie, Image de couverture
- **Détails** : Description complète
- **Informations pratiques** : Lieu, Dates (début/fin), Capacité, Statut

![Détail d'un événement](screenshots/event_detail.png)

**Page détaillée** d'un événement avec :
- Titre, catégorie et description
- Informations pratiques (date, heure, lieu, places)
- Nom de l'organisateur
- **Bouton d'inscription/désinscription**
- **Liste des participants** (liens cliquables vers profils)

![Liste des événements (vue admin)](screenshots/events_list_admin.png)

**Vue administrative des événements** avec :
- **Boutons d'action** : "Créer un événement" et "Voir les demandes en attente"
- **Filtres avancés** par catégorie et statut
- Même système de cartes avec états visuels clairs
- Le bouton "Voir les demandes en attente" redirige directement automatiquement vers la page suivante.

![Gestion des demandes en attente (admin)](screenshots/admin_event_attente.png)

**Panel admin** pour gérer les événements en attente de validation :
- Tableau avec ID, Titre, Lieu, Dates, Capacité et **surtout** Statut
- Filtrage par statut "EN_ATTENTE" (automatique en cliquant sur le lien de la page précèdente "Voir les demandes en attente")
- Actions : Éditer, Supprimer

![Modification d'événement (admin)](screenshots/admin_event_edit.png)

**Interface EasyAdmin** pour publier et modifier un événement :
- Pour publier, modifier le statut : **EN_ATTENTE** --> **OUVERT**
- Tous les champs éditables (titre, description, lieu, dates, capacité, statut)

---

### Profil Utilisateur

![Mon profil](screenshots/my_profil.png)

**Page profil personnel** affichant :
- Photo de profil (avatar BLOB)
- Informations (nom, prénom, âge)
- Biographie personnelle
- Langues parlées
- Disponibilités (jours et horaires)
- Bouton "Modifier mon profil"

![Édition du profil](screenshots/edit_profil.png)

**Formulaire de modification** permettant de changer :
- Avatar (upload d'image)
- Biographie
- Langues (sélection multiple)
- Disponibilités

![Profil public](screenshots/profil.png)

**Consultation d'un profil public** avec :
- Informations visibles publiquement
- Bouton **"Envoyer un message"**
- Bouton **"Demander en Buddy"**


---

### Recherche de Buddy

![Recherche](screenshots/buddy_search.png)

**Interface de recherche avancée** avec :
- **Filtres** : Nom, Prénom, Langues, Disponibilités
- **Résultats** en cartes avec photo, nom, langues


---

### Messagerie

![Liste des conversations](screenshots/messagerie.png)

**Vue d'ensemble** des conversations :
- Photo et nom/prénom du contact
- Date du dernier message
- Possibilité de supprimer/archiver une conversation
- Accéder a ses conversations archivées

![Conversation](screenshots/messagerie2.png)

**Interface de chat** avec :
- Historique des messages
- Messages alignés (gauche = reçus, droite = envoyés)
- Formulaire d'envoi
- Bouton "Signaler" pour les messages reçus
- Bouton "Supprimer" pour les messages envoyés
- icône check + "Envoyé" **ou** icone double-check + "Vu" selon l'état du message

---

### Signalement

![Signalement](screenshots/signalement.png)

**Formulaire de signalement** pour :
- Messages inappropriés
- Événements problématiques
- Utilisateurs à comportement inapproprié

Champs : Motif (plusieurs choix proposés) + Description détaillée

---

### Chatbot

![Chatbot](screenshots/chatbot.png) | ![Chatbot2](screenshots/chatbot2.png)

**Assistant virtuel** accessible via le bouton bleu en bas à droite pour aider à la navigation.

---

### Administration

![Dashboard admin](screenshots/admin.png)

**Panel EasyAdmin** avec statistiques :
- **182 utilisateurs**
- **34 événements**
- **32 signalements**

Menu latéral pour accéder aux sections de gestion.

![Gestion utilisateurs](screenshots/admin1.png)

**CRUD utilisateurs** avec :
- Actions : Créer, Modifier, Supprimer, Consulter
- Filtres et recherche
- Sélection multiple

![Gestion événements](screenshots/admin2.png)

**CRUD événements** avec les mêmes fonctionnalités de gestion complète.

---

### Responsive Design

![Version mobile](screenshots/mobilefirst.png)

Application entièrement **responsive** (mobile-first) :
- Menu burger
- Cartes empilées verticalement
- Formulaires adaptés
- Expérience fluide sur tous écrans

## 📝 Licence & Droits

Ce projet a été développé dans un cadre universitaire pour l'**IUT de Reims**.
Tous droits réservés aux auteurs.

---

# FIN