# Projet Symfony - Product Trial

## Fonctionnalités principales

- Gestion des utilisateurs avec inscription et authentification.
- Gestion des produits avec opérations CRUD (création, lecture, mise à jour, suppression).
- Gestion du panier d'achat (ajout, mise à jour, suppression, vidage).
- Gestion de la liste de souhaits.
- Sécurité avec contrôle d'accès basé sur les rôles.

## Structure du projet

- `src/Controller/` : Contrôleurs gérant les routes HTTP.
- `src/Service/` : Services contenant la logique métier.
- `src/Entity/` : Entités Doctrine représentant les modèles de données.
- `src/Repository/` : Repositories pour l'accès aux données.
- `src/Dto/` : Objets de transfert de données (Data Transfer Objects).
- `config/` : Configuration de l'application Symfony.

## Prérequis

- PHP 8.x
- Composer
- Serveur web (ex: Apache, Nginx)
- Base de données compatible Doctrine (ex: MySQL, PostgreSQL)

## Installation

1. Cloner le dépôt :
   ```
   git clone https://github.com/maggoubi/product-trial.git
   cd product-trial
   ```

2. Installer les dépendances :
   ```
   composer install
   ```

3. Configurer la base de données dans `.env` ou `.env.local`.

4. Exécuter les migrations pour créer les tables :
   ```
   php bin/console doctrine:migrations:migrate
   ```

5. Lancer le serveur de développement Symfony :
   ```
   symfony server:start
   ```

## Endpoints API

### Produits

- `GET /products` : Récupérer la liste de tous les produits.
- `GET /products/{id}` : Récupérer un produit par son ID.
- `POST /products` : Créer un nouveau produit (requiert le rôle ADMIN_ACCESS).
- `PATCH /products/{id}` : Mettre à jour un produit existant (requiert le rôle ADMIN_ACCESS).
- `DELETE /products/{id}` : Supprimer un produit (requiert le rôle ADMIN_ACCESS).

### Utilisateurs

- `POST /account` : Inscription d'un nouvel utilisateur.

### Authentification

- `POST /token`.

### Panier

- `GET /cart` : Récupérer les articles du panier de l'utilisateur connecté.
- `POST /cart` : Ajouter un article au panier.
- `PUT /cart/{id}` : Mettre à jour la quantité d'un article du panier.
- `DELETE /cart/{id}` : Supprimer un article du panier.
- `DELETE /cart` : Vider le panier.

### Wishlist

- `GET /wishlist` : Récupérer les articles de la liste de souhaits de l'utilisateur connecté.
- `POST /wishlist` : Ajouter un article à la liste de souhaits.
- `DELETE /wishlist/{id}` : Supprimer un article de la liste de souhaits.
- `DELETE /wishlist` : Vider la liste de souhaits.

## Tests API

Utiliser l'outil [Postman] pour tester les endpoints API. Un fichier Postman Collection est disponible dans le projet (`postman_collection.json`) pour faciliter les tests.

## Utilisation

- Accéder à l'application via `http://localhost:8000` (ou le port configuré).
- Utiliser les endpoints API pour gérer les utilisateurs, produits, panier, et wishlist.