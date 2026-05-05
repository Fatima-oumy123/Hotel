# Mon Hôtel - Système de Gestion Hôtelière

Un système complet de gestion d'hôtel développé avec Laravel, permettant de gérer les réservations, chambres, clients, factures, inventaire et restaurant.

## Fonctionnalités

### Gestion des Chambres
- Création et gestion des types de chambres
- Suivi du statut des chambres (libre, occupé, maintenance)
- Gestion des tarifs saisonniers

### Réservations
- Réservation en ligne pour les clients
- Gestion des check-in/check-out
- Calendrier des réservations
- Gestion des invités par réservation

### Clients
- Base de données des clients
- Historique des réservations
- Gestion des informations personnelles

### Facturation
- Génération automatique des factures
- Suivi des paiements
- Intégration des services (restaurant, etc.)

### Restaurant
- Gestion du menu
- Commandes et facturation
- Suivi des ventes

### Inventaire
- Gestion des stocks
- Mouvements d'inventaire
- Alertes de rupture

### Employés
- Gestion des employés et shifts
- Assignation des tâches
- Gestion des rôles et permissions

### Rapports
- Tableaux de bord avec statistiques
- Rapports financiers
- Export des données

## Comptes Utilisateurs

Le système utilise des rôles pour différencier les accès :

- **Directeur** (directeur@hoel.com) : Accès complet à toutes les fonctionnalités
- **Réceptionniste** (reception@hotel.com) : Gestion des réservations, check-in/out, clients
- **Admin** (admin@hotel.com) : Accès complet comme le directeur

Mot de passe par défaut pour tous les comptes : `password`

## Installation

### Prérequis
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL ou PostgreSQL

### Étapes d'installation

1. **Cloner le repository**
   ```bash
   git clone <url-du-repo>
   cd mon-hotel
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Installer les dépendances JavaScript**
   ```bash
   npm install
   ```

4. **Configuration de l'environnement**
   ```bash
   cp .env.example .env
   ```
   Modifier `.env` avec vos paramètres de base de données

5. **Générer la clé d'application**
   ```bash
   php artisan key:generate
   ```

6. **Migrer la base de données**
   ```bash
   php artisan migrate
   ```

7. **Seeder les données**
   ```bash
   php artisan db:seed
   ```

8. **Créer les rôles et permissions**
   ```bash
   php artisan db:seed --class=RolesAndPermissionsSeeder
   ```

9. **Compiler les assets**
   ```bash
   npm run build
   ```

10. **Démarrer le serveur**
    ```bash
    php artisan serve
    ```

## Utilisation

- Accédez à `http://localhost:8000`
- Connectez-vous avec un des comptes ci-dessus
- Le tableau de bord s'adapte selon le rôle

## Technologies Utilisées

- **Laravel** : Framework PHP
- **Livewire** : Composants dynamiques
- **Spatie Permission** : Gestion des rôles et permissions
- **Tailwind CSS** : Framework CSS
- **Alpine.js** : JavaScript interactif
- **MySQL/PostgreSQL** : Base de données

## Structure du Projet

```
app/
├── Http/Controllers/
│   ├── Admin/          # Contrôleurs admin
│   └── Guest/          # Contrôleurs invités
├── Models/             # Modèles Eloquent
├── Services/           # Services métier
└── Policies/           # Politiques d'autorisation

resources/views/        # Vues Blade
database/
├── migrations/         # Migrations
└── seeders/            # Seeders

routes/
├── web.php             # Routes web
└── api.php             # Routes API
```

## Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/nouvelle-fonction`)
3. Commit les changements (`git commit -am 'Ajout nouvelle fonction'`)
4. Push la branche (`git push origin feature/nouvelle-fonction`)
5. Créer une Pull Request

## Licence

Ce projet est sous licence MIT.

## Support

Pour toute question ou problème, contactez l'équipe de développement.

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
