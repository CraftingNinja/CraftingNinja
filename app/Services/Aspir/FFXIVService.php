<?php

namespace App\Services\Aspir;

use App\Providers\GameServiceProvider;
use App\Models\GameEntities\Achievement;
use App\Models\GameEntities\Instance;
use App\Models\GameEntities\Item;
use App\Models\GameEntities\Leve;
use App\Models\GameEntities\Quest;
use App\Services\Aspir\FFXIV\GarlandTools;
use App\Services\Aspir\FFXIV\ManualData;
use App\Services\Aspir\FFXIV\XIVAPI;

final class FFXIVService extends AspirAbstract implements AspirService
{
    public function __construct(public &$command, bool $fresh = false)
    {
        $this->assetsDir = GameServiceProvider::$aspir['assets_dir'];
        $this->dataDir = GameServiceProvider::$aspir['data_dir'];
        $this->compiledDir = GameServiceProvider::$aspir['compiled_dir'];

        $this->setCache(GameServiceProvider::$aspir['cache_slug']);

        if ($fresh) {
            $this->clearCache();
        }
    }

    public function collectData(): void
    {
        set_time_limit(0);

        //
        // KEEP
        //
        // If you're interested in more translation, it can take quite a bit of processing to get all the useful data out.  All my stuff is open source here, https://github.com/ufx/GarlandTools/blob/master/Garland.Data/Modules/Nodes.cs.  For the most part the s prefix stands for "SaintCoinach" and generally represents what you'll find on xivapi.
        // > I've spent hours looking for a way to connect BNPCs to zone and level
        // This isn't in the client data files so xivapi can't provide it. My site uses a combination of a private server with packet-scraped data, and the defunct Libra Eorzea database for this. See: https://github.com/ufx/GarlandTools/blob/master/Garland.Data/Modules/Mobs.cs
        // > ENPC to zone
        // There's an algorithm you can use to connect a lot of these via the Level table. A significant amount still come from the defunct Libra Eorzea, but I've been working on an alternative method to pull them from the binary world data. See: https://github.com/ufx/GarlandTools/blob/master/Garland.Data/Modules/Territories.cs and https://github.com/ufx/SaintCoinach/blob/master/SaintCoinach/Xiv/Map.cs#L205
        // > Items to an instance
        // Those also mostly come from Libra and won't be updated anymore. Best alternative is scraping the lodestone HTML, but I haven't had time for this.
        // To be honest, crafters rely on lots of disparate data sources that I put a lot of work into bringing together. The raw game data is just one piece of the puzzle - there's manual input sources (https://docs.google.com/spreadsheets/d/1hEj9KCDv0TT1NiGJ0S7afS4hfGMPb6tetqXQetYETUE/edit#gid=953424709), reverse-engineered algorithms acting on the data, a few piles of hacks for weird stuff, that defunct Libra Eorzea database, and some web scraping to bring it all together. You may have better luck picking up my data imports via my open source Garland code. There's a setup & contribution guide if you're interested: https://github.com/ufx/GarlandTools/blob/master/CONTRIBUTING.md. Happy to help with any questions you've got for it.

        $run = [
            'xivapi' => [
                'class' => new XIVAPI($this),
                'calls' => [
                    'recipeNotebooks', // TODO 1 move to bottom of list; testing
                    'achievements',
                    'locations',
                    'nodes',
                    'fishingSpots',
                    'mobs',
                    'npcs',
                    'quests',
                    'instances',
                    'jobs',
                    'jobCategories',
                    'ventures',
                    'leves',
                    'itemCategories',
                    'items',
                    'recipes',
                    'companyCrafts',
                    'notebookDivisions',
                    'notebookDivisionCategories',
                ],
            ],
            'garlandtools' => [
                'class' => new GarlandTools($this),
                'calls' => [
                    'mobs',
                    'npcs',
                    'instances',
                    'notebookNotebookDivision',
                ],
            ],
            'manualdata' => [
                'class' => new ManualData($this),
                'calls' => [
                    // 'nodes',
                    // 'nodeCoordinates',
                    // 'nodeTimers',
                    'randomVentureItems',
                    'leveTypes',
                ],
            ],
        ];

        $rowCounts = [];
        foreach ($run as $type => $entity) {
            $this->command->comment('Beginning ' . $type . ' Calls');

            foreach ($entity['calls'] as $function) {
                $prevRowCounts = $rowCounts;

                $entity['class']->$function();

                $rowCounts = array_filter(
                    array_map(fn ($values) => count($values), $this->data),
                    fn ($amount) => $amount > 0
                );

                foreach (array_diff($rowCounts, $prevRowCounts) as $k => $count) {
                    $this->command->info($k . ' now has ' . $count . ' rows');
                }
            }
        }

        $this->saveData();
    }

    public function collectAssets(): void
    {
        $domain = 'https://xivapi.com/i/';

        // A stream context to ignore http warnings
        $streamContext = stream_context_create([
            'http' => ['ignore_errors' => true],
        ]);

        $pluckRawOriginal = fn (string $model, string $key) =>
            $model::select($key)
                ->get()
                ->map(fn ($r) => (string) $r->getRawOriginal($key))
                ->unique();

        $iconSets = [
            $pluckRawOriginal(Item::class, 'icon'),
            $pluckRawOriginal(Instance::class, 'icon'),
            $pluckRawOriginal(Quest::class, 'icon'),
            $pluckRawOriginal(Achievement::class, 'icon'),
            $pluckRawOriginal(Leve::class, 'plate'),
            $pluckRawOriginal(Leve::class, 'frame'),
        ];

        exec('find "' . $this->assetsDir . '" -name *.png', $existingImages);
        $existingImages = array_map(
            fn ($value) => str_replace($this->assetsDir, '', $value),
            $existingImages
        );

        foreach ($iconSets as $set) {
            foreach ($set as $icon) {
                // All icons are five digits, otherwise we'd have different rules.
                //  See https://xivapi.com/docs/Icons
                $icon = (strlen($icon) == 6 ? '' : '0') . $icon;

                if (strlen($icon) != 6) {
                    continue;
                }

                $folder = substr($icon, 0, 3) . "000";
                $iconBase = $this->assetsDir . $folder . '/';
                $icon = $icon . '.png';

                if (in_array($folder . '/' . $icon, $existingImages)) {
                    continue;
                }

                $image = file_get_contents($domain . $folder . '/' . $icon, false, $streamContext);
                $this->command->info('Downloading ' . $icon);

                if (str_contains($image, '"Code":404')) {
                    $this->command->error('Download failed, 404');
                    continue;
                }

                if (!is_dir($iconBase)) {
                    exec('mkdir "' . $iconBase . '"');
                }

                file_put_contents($iconBase . $icon, $image);

                $existingImages[] = $folder . '/' . $icon;
            }
        }
    }
}
