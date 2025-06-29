# TaskManager

TaskManager est une application Symfony de gestion de tâches, conteneurisée avec Docker pour un environnement de développement facile et cohérent.

---

## Stack technique

-   **Backend** : PHP 8.3 avec Symfony 7
-   **Base de données** : MySQL 8+
-   **Serveur web** : Nginx
-   **Frontend** : Node.js 22.14 avec TailwindCSS pour le style, géré via npm/webpack
-   **Gestionnaire de dépendances PHP** : Composer
-   **Gestionnaire de dépendances JS** : npm
-   **Interface DB** : phpMyAdmin
-   **Conteneurisation** : Docker + Docker Compose

---

## Prérequis

-   Docker & Docker Compose installés
-   Make (optionnel, pour simplifier les commandes)

---

## Commandes Docker & Makefile

Le conteneur PHP s'appelle `task_manager_php` dans ce projet.

| Commande Make           | Description                                                       |
| ----------------------- | ----------------------------------------------------------------- |
| `make up`               | Démarrer les conteneurs en mode détaché et attendre               |
| `make down`             | Arrêter et supprimer les conteneurs et volumes                    |
| `make logs`             | Afficher les logs en temps réel                                   |
| `make bash`             | Ouvrir un shell bash dans le conteneur PHP                        |
| `make composer-install` | Installer les dépendances PHP via Composer                        |
| `make migrate`          | Exécuter les migrations Doctrine                                  |
| `make create-db`        | Créer la base de données si elle n'existe pas                     |
| `make update-db`        | Mettre à jour le schéma de la base de données                     |
| `make fixtures`         | Charger les données de test (fixtures)                            |
| `make cache-clear`      | Vider le cache Symfony                                            |
| `make permissions`      | Donner tous les droits (777) au dossier `var`                     |
| `make symfony-serve`    | Démarrer le serveur Symfony local (hors Docker)                   |
| `make restart`          | Redémarrer les conteneurs en reconstruisant l’image               |
| `make node-watch`       | Lancer la compilation JS/SCSS en mode watch (dans conteneur node) |

---

## Utilisation rapide

```bash
make up
make create-db
make migrate
make fixtures
make node-watch
```
