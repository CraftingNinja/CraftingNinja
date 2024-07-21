# Crafting Ninja


## Local Development

The `Data` and `Assets` repositories should be sibling folders to this repository. See `docker-compose.yml` for references.


### Crafting Ninja Side

```
sail artisan migrate:refresh --seed
```


### Game Side

Aspir the data and assets.

```
sail artisan aspir:data ffxiv 
    -- OR --
sail artisan aspir:data ffxiv --fresh

sail artisan aspir:assets ffxiv
```

Osmose the data.

```
sail artisan osmose ffxiv
```


### Development Seeders

```
sail artisan db:seed --class TODO 1?
```


### Adding a new game

You'll need to make obvious changes to these files:

```
init.sql
database.php
config/games/[slug].php
```
