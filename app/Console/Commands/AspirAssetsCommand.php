<?php

namespace App\Console\Commands;

use App\Providers\GameServiceProvider;
use Illuminate\Console\Command;

class AspirAssetsCommand extends Command
{
    protected $signature = 'aspir:assets {slug}';

    protected $description = 'Get image assets';

    public function handle(): void
    {
        // {slug} handled by GameServiceProvider and populates the static values

        $class = GameServiceProvider::$aspir['service'];

        (new $class($this))->collectAssets();
    }
}
