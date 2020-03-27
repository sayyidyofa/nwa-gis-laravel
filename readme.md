# National Wilderness Areas Grapichal Information System

### Reference
1. Dataset: [ArcGIS](https://hub.arcgis.com/datasets/usfs::national-wilderness-areas)
2. CartoDB Site: [CartoDB](https://sayyidyofa.carto.com/builder/85da0184-1639-4f01-9f17-b268bac6da20/embed)

### Development
- Set up `.env` file:
    - `php artisan key:generate`
    - Set Database(MySQL) username, password 
    - Set Database(MySQL) schema name: `nwagis`
- Set up schema `nwagis` in your MySQL database
- `composer install`
- `php artisan migrate`
- Run MySQL inserts in this order:
    1. `nwagis_wildernesses.sql`
    2. `nwagis_geometries.sql`
- Good to go.

### To-Do
- Set initial map zoom correctly
- Show a sidebar that views a list of each Wilderness metadata
- Build a CRUD dashboard
- Move this To-Do list somewhere else (issues?)
- Small info widgets on homepage

### References
- LeafletJS [docs](https://leafletjs.com/reference-1.6.0.html)
