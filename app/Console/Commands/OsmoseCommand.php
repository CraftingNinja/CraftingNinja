<?php

namespace App\Console\Commands;

use App\Providers\GameServiceProvider;
use Database\Seeders\GameDataSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class OsmoseCommand extends Command
{
    protected $signature = 'osmose {slug}';

    protected $description = 'Read AspirData\'s JSON files and import them';

    public function handle(): void
    {
        // {slug} handled by GameServiceProvider and populates the static values

        $connection = GameServiceProvider::$connection;
        $class = GameServiceProvider::$aspir['service'];

        $aspirService = new $class($this);

        // sail artisan migrate:refresh --database=ffxiv --path=/database/migrations/GameStructure
        $this->call("migrate:fresh", [
            '--database' => $connection,
            '--path' => '/database/migrations/GameStructure',
        ]);

        // sail artisan db:seed --class GameDataSeeder [with a connection twist]
        (new GameDataSeeder($this, $connection, $aspirService))->run();
    }
}
