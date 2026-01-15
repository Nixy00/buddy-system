# SAÉ 3.01 - Développement d'une application

## 📖 Introduction

Buddy System est une application web permettant de faciliter l'intégration des étudiants internationaux à l'Université de Reims Champagne-Ardenne. L'application permet aux étudiants locaux de se mettre en relation avec des étudiants internationaux via un système de parrainage, d'événements et de messagerie.

## 🗂️ Table of Contents

- [Contributors](#-contributors)
- [Project Context](#-project-context)
- [Features](#-features)
- [Requirements](#-requirements)
- [Technologies](#-technologies)
- [Testing the application](#-testing-the-application)
- [Installation / Configuration](#-installation--configuration)
- [Composer scripts](#-composer-scripts)
- [Project Architecture](#-project-architecture)
- [Screenshots](#-screenshots)

## 👥 Contributors

- **Ayoub SEGHIR**     (segh0010)
- **Yacine MOUNSI**    (moun0023)
- **Matteo GODART**    (goda0054)
- **Sacha DEUTSCHE**   (deut0005)
- **Iyed BOUZENADA**   (bouz0078)

## 📌 Project Context

- Ce projet a été réalisé dans le cadre d'une SAÉ (Situation d'Apprentissage et d'Évaluation) du BUT Informatique.
- Durée : Octobre 2025 - Janvier 2026
- Objectif principal : Développer une application web en utilisant le framework Symfony.
- Enseignants : M. Desjardin, Mme Zahia Guessoum

## ✨ Features

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

## ⚙️ Requirements

- **PHP** >= 8.2
- **Symfony CLI**
- **Composer** >= 2.0
- **MySQL** ou **MariaDB**

## 💻 Technologies

- Backend: **Symfony** 7.3
- Frontend: **Twig**, **CSS**
- Database: **MySQL**
- Tests: **Codeception**

## 🛠️ Testing the application

Vous pouvez tester l'application en local en suivant les instructions d'installation ci-dessous.

Dans le contexte de développement, voici quelques utilisateurs que vous pouvez utiliser pour vous connecter à l'application, tous ayant le mot de passe **test** :

- Admin : **root@example.com**
- User : **user@example.com**

### Données disponibles

Les fixtures génèrent automatiquement :

- **+183 utilisateurs** (profils complets avec bio, langues, centres d'intérêt)
- **+34 événements** (Sport, Culture, Loisirs avec dates variées)
- **+50 conversations** entre utilisateurs  
- **+200 messages** répartis dans les conversations
- **+32 signalements** de messages et utilisateurs

### Procédures de test suggérées

1. **Authentification** : Se connecter avec `user@example.com` / `test`
2. **Inscription événement** : Aller sur `/evenements`, s'inscrire à un événement ouvert
3. **Messagerie** : Rechercher un buddy (`/buddy`), lui envoyer un message
4. **Administration** : Se connecter avec `root@example.com` / `test`, accéder à `/admin`

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

1) Copiez le fichier `.env` en `.env.local` à la racine de votre projet.

2) Modifiez la ligne suivante pour l'adapter à vos identifiants phpmyadmin :

```env
DATABASE_URL="mysql://USER:PASSWORD@127.0.0.1:3306/buddy_system?serverVersion=mariadb-10.6.0&charset=utf8mb4"
```

> 💡 Si vous n'avez pas de base de données, elle sera créée avec le nom spécifié.

### Créer les fixtures

Exécutez la commande suivante :

```bash
composer db
```

### Lancer le serveur local en environnement dev

Exécutez la commande suivante :

```bash
composer start
```

## 📝 Composer scripts

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

## 🏗️ Project Architecture

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

## 📸 Screenshots

### Page d'accueil

![Page d'accueil 1](screenshots/home1.png)

Sur la page d'accueil, les visiteurs découvrent une présentation claire de Buddy System.  
La section hero présente le slogan "Trouvez votre binôme idéal à l'URCA" avec deux boutons principaux :
- **Explorez gratuitement** : Accès direct aux fonctionnalités 
- **Découvrez les événements** : Consultation de la liste des événements

La barre de navigation en haut permet d'accéder aux sections principales : Accueil, Événements, Rechercher, ainsi qu'aux boutons Connexion et Inscription.

![Page d'accueil 2](screenshots/home2.png)

La section "Comment ça marche ?" explique le processus en 3 étapes simples :
1. **Créez votre profil** : Inscription rapide avec choix Local/International
2. **Trouvez votre binôme** : Recherche selon critères (langue, disponibilités)
3. **Participez aux événements** : Découverte et inscription aux activités

![Page d'accueil 3](screenshots/home3.png)

En bas de page, une liste des prochains événements est affichée avec :
- Titre, catégorie (Sport, Culture, Loisirs)
- Date et nombre de places restantes
- Bouton "Voir les détails" pour chaque événement

### Authentification

![Page de connexion](screenshots/connexion.png)

Page de connexion permettant aux utilisateurs d'accéder à leur compte.  
Formulaire simple avec :
- Email étudiant
- Mot de passe
- Lien "Mot de passe oublié ?"

Un lien vers l'inscription est disponible pour les nouveaux utilisateurs.

![Page d'inscription](screenshots/inscription.png)

Formulaire d'inscription avec les champs suivants :
- Prénom, Nom
- Email universitaire
- Mot de passe (avec confirmation)
- **Type de compte** : Local ou International (choix important)
- Acceptation des CGU (CGU d'ailleurs accessibles depuis le footer)

### Dashboard

![Dashboard utilisateur](screenshots/dashboard_user.png)

Le dashboard personnel affiche un message de bienvenue et 4 accès rapides :
- **Mon Profil** : Consulter et modifier ses informations personnelles
- **Recherche** : Trouver un buddy selon critères
- **Messagerie** : Échanger avec ses contacts
- **Mes Événements** : Voir ses inscriptions

Un bouton rouge "Supprimer mon compte" est disponible pour ceux qui souhaitent quitter la plateforme.

![Dashboard administrateur](screenshots/dashboard_admin.png)

Dashboard admin avec le **petit logo administrateur en haut à droite** donne accès au back-office EasyAdmin pour la gestion complète.

### Événements

![Liste des événements](screenshots/events_list.png)

Cette page affiche tous les événements disponibles avec :
- **Filtres par catégorie** : Sport, Culture, Loisirs
- Pour chaque événement : titre, date, lieu, places disponibles
- Badge de catégorie coloré (indiquant l'état de l'événement: ouvert, fermé,annulé)
- Bouton "Voir les détails" pour accéder à la page complète

Les événements passés sont grisés et marqués comme "Terminé".

![Détail d'un événement](screenshots/event_detail.png)

Page détaillée d'un événement affichant :
- **Titre** et **catégorie**
- **Description complète** de l'activité
- **Informations pratiques** :
  - Date et heure
  - Lieu (adresse complète)
  - Places disponibles / Places totales
  - Organisateur
- **Bouton d'inscription** (ou "Se désinscrire" si déjà inscrit (si connecté) )
- Liste des participants (liens cliquables vers leurs profils)

### Profil

![Mon profil](screenshots/my_profil.png)

Page de consultation du profil utilisateur affichant :
- Photo de profil (avatar)
- Nom, prénom, type de compte (Local/International)
- **Biographie** personnelle
- **Langues parlées** (avec drapeaux)
- **Disponibilités** (jours et créneaux horaires)
- Bouton "Modifier mon profil"

![Édition du profil](screenshots/edit_profil.png)

Formulaire complet de modification permettant de changer :
- Avatar (upload d'image)
- Biographie (zone de texte)
- **Langues** : Sélection multiple (Français, Anglais, Espagnol, etc.)
- **Disponibilités** : Choix des jours et horaires disponibles
- Sauvegarde ou annulation des modifications

![Profil d'un utilisateur](screenshots/profil.png)

Consultation du profil public d'un autre utilisateur.  
Affiche les mêmes informations que "Mon profil" avec en plus :
- **Bouton "Envoyer un message"** : Ouvre une conversation privée si demande de buddy acceptée
- **Bouton "Signaler"** : Si comportement inapproprié
- Historique des événements communs (si applicable)

### Recherche de Buddy

![Recherche de Buddy](screenshots/buddy_search.png)

Interface de recherche avancée avec **filtres multiples** :
- **Langues** : Filtrer par langue parlée
- **Disponibilités** : Matcher selon créneaux horaires compatibles
- **Type de compte** : Local ou International
- **Résultats** : Cartes avec photo, nom, langues et bouton "Voir le profil"

La recherche se met à jour en temps réel lors de la modification des filtres.

### Messagerie

![Liste des conversations](screenshots/messagerie.png)

Liste de toutes les conversations actives avec :
- Photo et nom du contact
- **Date** du dernier échange
- **Badge** si nouveaux messages non lus

Clic sur une conversation pour accéder à l'historique complet.

![Conversation](screenshots/messagerie2.png)

Interface de chat complète avec :
- **Historique des messages** (déroulant)
- Messages alignés à gauche (reçus) et à droite (envoyés)
- **Formulaire d'envoi** en bas avec champ de texte et bouton
- Bouton **"Signaler"** en cas de contenu inapproprié

### Signalement

![Formulaire de signalement](screenshots/signalement.png)

Formulaire permettant de signaler :
- **Un message** (depuis la messagerie)
- **Un événement** (contenu inapproprié)
- **Un utilisateur** (comportement problématique)

Champs obligatoires :
- **Raison** du signalement (menu déroulant)
- **Description détaillée** (zone de texte)

Les signalements sont envoyés aux administrateurs pour traitement.

### Chatbot

![Chatbot](screenshots/chatbot.png)

Assistant chatbot accessible via le bouton bleu en bas à droite.  
Aide les utilisateurs à :
- Naviguer dans l'application
- Trouver rapidement une fonctionnalité

Interface de discussion simple avec propositions de questions prédéfinies.

### Administration

![Panneau d'administration](screenshots/admin.png)

Dashboard EasyAdmin accessible uniquement aux administrateurs.  
Affiche un **résumé des données** :
- **182 utilisateurs** inscrits
- **34 événements** créés
- **32 signalements** en attente

Menu latéral gauche pour accéder aux sections de gestion.

![Gestion des utilisateurs](screenshots/admin1.png)

Liste de tous les utilisateurs avec actions CRUD :
- **Créer** : Bouton "add" en haut
- **Modifier** : Bouton ...
- **Supprimer** : Bouton ...
- **Consulter** : Bouton ... 

Filtres et recherche disponibles en haut du tableau.  
**Sélection multiple** possible pour supprimer plusieurs utilisateurs d'un coup.

![Gestion des événements](screenshots/admin2.png)

Interface similaire pour la gestion des événements.  
Les administrateurs peuvent :
- Créer de nouveaux événements
- Modifier les détails (date, lieu, places)
- Supprimer des événements obsolètes


Même principe pour la gestion des signalements : consultation, validation ou rejet.

### Responsive Design

![Version mobile](screenshots/mobilefirst.png)

L'application est entièrement **responsive** et suit le principe **mobile-first**.  
Sur mobile :
- Menu burger pour la navigation
- Cartes empilées verticalement
- Formulaires adaptés aux petits écrans

L'expérience utilisateur reste fluide quelle que soit la taille de l'écran.
