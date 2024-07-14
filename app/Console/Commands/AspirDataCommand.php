<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AspirDataCommand extends Command
{
    protected $signature = 'aspir:data {slug} {--fresh}';

    protected $description = 'Build Importable JSON Data';

    public function handle(): void
    {
        $slug = strtolower($this->argument('slug'));

        $class = config("games.$slug.internals.aspir.service");

        (new $class($this, $this->option('fresh')))->collectData();
    }
}
