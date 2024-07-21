<?php

use App\Casts\IconPathCast;
use App\Services\Aspir\FFXIVService;
use App\Services\FFXIV\Casts\IconPathCast as FFXIVIconPathCast;
use App\Services\FFXIV\Seeders\DefaultListsSeeder;

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
        'casts' => [
            IconPathCast::class => FFXIVIconPathCast::class,
        ],
        'bindings' => [
        ],
        'seeders' => [
            DefaultListsSeeder::class,
        ],
    ],
    'meta' => [
        'splash' => [
            'name' => 'Final Fantasy XIV',
            'logo' => 'ffxiv.jpg',
            'flavor' => 'Eorzea awaits!',
            'hero' => [
                'splash/erenville.png',
                'splash/krile.png',
                'splash/adventurers.png',
            ][rand(0,2)],
            'backdrop' => 'splash/backdrop.jpg',
        ],
        'trademark' => 'SQUARE ENIX',
        'legal' => 'ALL FINAL FANTASY XIV CONTENT IS PROPERTY OF SQUARE ENIX CO., LTD',
        'patch' => [
            'version' => '7.0',
            'tag' => 'Dawntrail',
        ],
    ],
    'settings' => [
        'maxLevel' => 100,
        'maxItemLevel' => 999,
    ],
];
