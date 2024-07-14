<?php

namespace Database\Seeders;

use App\Models\Ninja\Game;
use Illuminate\Database\Seeder;

class GamesSeeder extends Seeder
{
    public function run(): void
    {
        $games = Game::pluck('slug');

        // Slug => Name
        $officialGameList = [
            'ffxiv' => 'FFXIV',
        ];

        foreach ($officialGameList as $slug => $name) {
            if ($games->contains('ffxiv')) {
                continue;
            }

            Game::create(compact('slug', 'name'));
        }

    }
}
