# FreelanceZone — Plateforme Freelance Laravel

## 📋 Prérequis

- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL 8+ ou PostgreSQL 14+
- Git

## 🚀 Installation

```bash
# 1. Cloner le projet
git clone https://github.com/votre-repo/freelancezone.git
cd freelancezone

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances Node
npm install && npm run build

# 4. Copier le fichier d'environnement
cp .env.example .env

# 5. Générer la clé d'application
php artisan key:generate

# 6. Configurer la base de données dans .env
# DB_CONNECTION=mysql
# DB_DATABASE=freelancezone
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Exécuter les migrations et le seeder
php artisan migrate --seed

# 8. Créer le lien symbolique storage
php artisan storage:link

# 9. Lancer le serveur de développement
php artisan serve
```

## 🌐 Accès

- **Application** : http://localhost:8000
- **Admin** : admin@freelancezone.ma / password
- **Client** : youssef@client.ma / password
- **Freelance** : abdel@freelance.ma / password

## 🗂️ Structure des fichiers clés

```
app/
├── Http/Controllers/
│   ├── AuthController.php       # Authentification
│   ├── ProjectController.php    # Gestion des projets
│   ├── BidController.php        # Gestion des offres
│   ├── ContractController.php   # Gestion des contrats
│   ├── MessageController.php    # Messagerie
│   ├── ReviewController.php     # Avis & évaluations
│   ├── ProfileController.php    # Profils utilisateurs
│   ├── DashboardController.php  # Tableau de bord
│   └── AdminController.php      # Administration
├── Models/
│   ├── User.php                 # Utilisateur (client/freelance/admin)
│   ├── Project.php              # Projet
│   ├── Bid.php                  # Offre
│   ├── Contract.php             # Contrat
│   ├── Message.php              # Message
│   ├── Review.php               # Avis
│   ├── Category.php             # Catégorie
│   ├── Payment.php              # Paiement
│   └── Notification.php         # Notification
resources/views/
├── layouts/app.blade.php        # Layout principal
├── welcome.blade.php            # Page d'accueil
├── auth/                        # Authentification
├── projects/                    # Projets
├── dashboard/                   # Tableaux de bord
├── contracts/                   # Contrats
├── messages/                    # Messagerie
├── profile/                     # Profils
└── admin/                       # Administration
```

## 🔧 Fonctionnalités

### Pour les Clients
- ✅ Publier des projets avec budget, deadline, compétences requises
- ✅ Recevoir et comparer les offres des freelances
- ✅ Accepter une offre (création automatique du contrat)
- ✅ Messagerie directe avec les freelances
- ✅ Marquer un contrat comme terminé
- ✅ Laisser des évaluations

### Pour les Freelances
- ✅ Explorer et filtrer les projets
- ✅ Soumettre des offres avec lettre de motivation
- ✅ Gérer ses contrats actifs
- ✅ Profil public avec portfolio et évaluations
- ✅ Messagerie avec les clients

### Administration
- ✅ Dashboard avec statistiques en temps réel
- ✅ Gestion des utilisateurs (activer/désactiver)
- ✅ Surveillance des projets et contrats
- ✅ Calcul des revenus de la plateforme (10% commission)

## 📦 Dépendances principales

```json
{
  "require": {
    "php": "^8.2",
    "laravel/framework": "^11.0",
    "laravel/sanctum": "^4.0",
    "laravel/tinker": "^2.9"
  },
  "require-dev": {
    "fakerphp/faker": "^1.23",
    "laravel/pint": "^1.13",
    "phpunit/phpunit": "^11.0"
  }
}
```
