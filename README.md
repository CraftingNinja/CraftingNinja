# FFXIV @ Crafting Ninja

## Local Development

### Data Setup

The CraftingAsAService/Data repo needs added as a submodule.

```
git submodule add git@github.com:CraftingAsAService/Data.git resources/aspir/
```

To update data: 

```
cd resources/aspir; git pull; cd -
```

### Crafting Ninja Side

```
sail artisan migrate:refresh --seed
```

### FFXIV Side

Aspir the data and assets.

```
sail artisan aspir:data ffxiv 
    -- OR --
sail artisan aspir:data ffxiv --fresh

sail artisan aspir:assets ffxiv
```

Required: `../data` and `../assets` should be sibling folders to this repository. See `docker-compose.yml` for references.

Osmose the data.

```
sail artisan osmose ffxiv
```

### Development Seeders

```
sail artisan db:seed
sail artisan db:seed --class ListsSeeder
```
