# National Wilderness Areas Grapichal Information System
[![Circle CI](https://circleci.com/gh/sayyidyofa/nwa-gis-laravel.svg?style=svg)](https://circleci.com/gh/sayyidyofa/nwa-gis-laravel)

Geographic Information System using Laravel Framework

### Reference
1. Dataset: [ArcGIS](https://hub.arcgis.com/datasets/usfs::national-wilderness-areas)
2. CartoDB Site: [CartoDB](https://sayyidyofa.carto.com/builder/85da0184-1639-4f01-9f17-b268bac6da20/embed)
3. LeafletJS [docs](https://leafletjs.com/reference-1.6.0.html)

### Demo
https://nwagis.projects.lazydev.me

### Development (First time)
- `composer install`
- Set up `.env` file:
    - `php artisan key:generate`
    - Set Database(MySQL) username, password 
    - Set Database(MySQL) schema name: `nwagis`
    - Set MailTrap username, password
    - Set [Pixabay](https://pixabay.com/api/docs/) API Key
- Set up schema `nwagis` in your MySQL database
- `php artisan migrate:fresh --seed`
- Good to go.

### Development (pulling/fetching)
- `composer update`
- `php artisan migrate:fresh --seed`

### Default Credentials and Roles
This is generated via `database/seeds/UserSeeder`
| email              | password | role   |
|--------------------|----------|--------|
| user@example.com   | secret   | user   |
| admin@example.com  | secret   | admin  |
| sadmin@example.com | secret   | sadmin |


