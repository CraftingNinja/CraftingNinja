<?php

use App\Services\Aspir\FFXIVService;
use Database\Seeders\GameDataSeeder;

return [
    'internals' => [
        'connection' => 'ffxiv',
        'aspir' => [
            'service' => FFXIVService::class,
            'cache_slug' => 'ffxiv',
            'assets_dir' => base_path('public/assets/ffxiv/i/'),
            'data_dir' => storage_path('app/data/ffxiv/'),
            'compiled_dir' => storage_path('app/aspir/ffxiv/'),
        ],
    ],
    'attributes' => [
        'maxLevel' => 100,
        'maxItemLevel' => 999,
    ],
];
