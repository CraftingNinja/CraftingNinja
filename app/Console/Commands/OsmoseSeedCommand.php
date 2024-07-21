<?php

namespace App\Console\Commands;

use App\Providers\GameServiceProvider;
use Database\Seeders\GameDataSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class OsmoseSeedCommand extends Command
{
    protected $signature = 'osmose:seed {slug}';

    protected $description = 'Run any configured seeders for the specified game';

    public function handle(): void
    {
        // {slug} handled by GameServiceProvider and populates the static values

        foreach (GameServiceProvider::$seeders as $seeder) {
            $this->call('db:seed', [
                '--class' => $seeder
            ]);
        }
    }
}
