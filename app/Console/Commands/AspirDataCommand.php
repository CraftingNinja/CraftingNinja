<?php

namespace App\Console\Commands;

use App\Providers\GameServiceProvider;
use Illuminate\Console\Command;

class AspirDataCommand extends Command
{
    protected $signature = 'aspir:data {slug} {--fresh}';

    protected $description = 'Build Importable JSON Data';

    public function handle(): void
    {
        // {slug} handled by GameServiceProvider and populates the static values

        $class = GameServiceProvider::$aspir['service'];

        (new $class($this, $this->option('fresh')))->collectData();
    }
}
