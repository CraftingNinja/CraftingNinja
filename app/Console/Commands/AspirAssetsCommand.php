<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AspirAssetsCommand extends Command
{
    protected $signature = 'aspir:assets {slug}';

    protected $description = 'Get image assets';

    public function handle(): void
    {
        $slug = strtolower($this->argument('slug'));

        $class = config("games.$slug.internals.aspir.service");

        (new $class($this))->collectAssets();
    }
}
