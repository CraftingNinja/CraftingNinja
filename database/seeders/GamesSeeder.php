<?php

namespace Database\Seeders;

use App\Models\Ninja\Game;
use Illuminate\Database\Seeder;

class GamesSeeder extends Seeder
{
    public function run(): void
    {
        $games = Game::pluck('slug');
        if ( ! $games->contains('ffxiv')) {
            Game::create([
                'name' => 'FFXIV',
                'slug' => 'ffxiv',
            ]);
        }
    }
}
