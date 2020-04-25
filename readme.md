# National Wilderness Areas Grapichal Information System

!["alt text"](https://img.shields.io/circleci/build/github/sayyidyofa/nwa-gis-laravel?style=flat-square "CircleCI Build")

### Reference
1. Dataset: [ArcGIS](https://hub.arcgis.com/datasets/usfs::national-wilderness-areas)
2. CartoDB Site: [CartoDB](https://sayyidyofa.carto.com/builder/85da0184-1639-4f01-9f17-b268bac6da20/embed)
3. LeafletJS [docs](https://leafletjs.com/reference-1.6.0.html)

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

### To-Do
To-Dos, issues and feature requests has been moved to [issues](https://github.com/sayyidyofa/nwa-gis-laravel/issues)
#### Front
- ~~Set initial map zoom correctly~~
#### Dashboard
- ~~Build a CRUD dashboard~~
#### Misc
- ~~Move this To-Do list somewhere else (issues?)~~

