# National Wilderness Areas Grapichal Information System

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
#### Front
- Set initial map zoom correctly
- Show a sidebar that views a list of each Wilderness metadata
- Small info widgets on homepage
#### Dashboard
- Build a CRUD dashboard
#### Misc
- Move this To-Do list somewhere else (issues?)

