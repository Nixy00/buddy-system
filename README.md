# SAÉ 3.01 - Buddy System

Bienvenue sur le dépôt du projet **Buddy System**, une application web réalisée dans le cadre de la SAÉ 3.01 (Développement d'une application).

## L'Équipe

| Nom | Prénom | Login | Rôle (Scrum) |
| :--- | :--- | :--- | :--- |
| **Seghir** | Ayoub | `segh0010` | Développeur |
| **Mounsi** | Yacine | `moun0023` | Développeur |
| **Godart** | Matteo | `goda0054` | Développeur |
| **Deutsche** | Sacha | `deut0005` | Développeur |
| **Bouzenada** | Iyed | `bouz0078` | Développeur |

**Enseignant référent :** Monsieur Desjardin (`desjar01`)

---

##  Présentation du Projet

**Buddy System** est une plateforme destinée à faciliter l'intégration des étudiants internationaux à l'Université de Reims Champagne-Ardenne. L'application permet de mettre en relation des étudiants locaux ("parrains") avec des étudiants arrivants ("filleuls") pour favoriser l'échange culturel et l'entraide.

### Fonctionnalités principales
* **Gestion des comptes :** Inscription et profils différenciés (Local / International).
* **Messagerie :** Conversations privées et système de signalement.
* **Événements :** Création, publication et inscriptions aux événements.
* **Administration :** Back-office pour la modération et la gestion des utilisateurs.

---

##  Prérequis Techniques

Avant de lancer le projet, assurez-vous d'avoir installé sur votre machine :

* **PHP** >= 8.2
* **Composer** (Gestionnaire de dépendances PHP)
* **Symfony CLI** (Recommandé pour le serveur local)

---

## Installation & Configuration

Pour évaluer notre travail, suivez ces étapes afin de lancer l'application en local.

### 1. Cloner le dépôt
```bash
git clone [https://iut-info.univ-reims.fr/gitlab/sae3-01.git](https://iut-info.univ-reims.fr/gitlab/sae3-01.git)
cd sae3-01
```

### 2. Installer les dépendances
Installation des librairies PHP et des assets Front-end.

```bash 
composer install
composer start
```

---

## Scripts Composer

Les scripts Composer suivants sont fournis dans `composer.json` pour faciliter le développement et la vérification du code.

| Commande | Description |
|----------|-------------|
| `composer start` | Démarre le serveur de développement Symfony sans limite de temps. |
| `composer test:phpcs` | Vérifie le style PHP avec PHP CS Fixer (mode dry-run, sans modification). |
| `composer fix:phpcs` | Corrige automatiquement le style PHP avec PHP CS Fixer. |
| `composer test:twigcs` | Vérifie la conformité des templates Twig. |
| `composer fix:twigcs` | Corrige automatiquement les templates Twig. |
| `composer test:codeception` | Initialise la base de test et exécute les tests fonctionnels Codeception. |
| `composer test` | Exécute les vérifications de style : `test:phpcs` et `test:twigcs`. |
| `composer fix` | Applique toutes les corrections : `fix:phpcs` et `fix:twigcs`. |

### Utilisation rapide

```bash
# Démarrer le serveur de développement
composer start

# Vérifier le style du code
composer test

# Corriger automatiquement le style
composer fix
```

---

## Qualité du code

- **PHP CS Fixer** est configuré avec les règles Symfony dans `.php-cs-fixer.dist.php`.
- **Twig CS Fixer** vérifie et corrige les templates Twig.

---

## Base de données & fixtures

### Prérequis
- MySQL/MariaDB démarré (ex: XAMPP)
- Une base locale (ex: `bouz0078_buddy`)

### Configuration
Créer un fichier `.env.local` à la racine du projet (non versionné) et y définir :

```env
DATABASE_URL="mysql://USER:PASSWORD@mysql:3306/bouz0078_buddy?serverVersion=mariadb-10.2.25&charset=utf8mb4"
```

### Initialisation complète (BDD + migrations + fixtures)
Executez les commandes suivantes dans le terminal : 

```bash
composer install
composer db
```
---

## Développement

*En attente de la suite de la SAE*
