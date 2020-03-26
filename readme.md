# National Wilderness Areas Grapichal Information System

### Reference
1. Dataset: [ArcGIS](https://hub.arcgis.com/datasets/usfs::national-wilderness-areas)
2. CartoDB Site: [CartoDB](https://sayyidyofa.carto.com/builder/85da0184-1639-4f01-9f17-b268bac6da20/embed)

### Development
- Set up `.env` file (database, key, etc). This project uses MySQL
- `composer install`
- `php artisan migrate`
- Run MySQL inserts in `nwagis_geometries.sql` and `nwagis_wildernesses.sql` file
- Good to go.

### To-Do
- Set initial map zoom correctly
- Show a sidebar that views a list of each Wilderness metadata
- Build a CRUD dashboard
- Move this To-Do list somewhere else (issues?)
- Small info widgets on homepage
