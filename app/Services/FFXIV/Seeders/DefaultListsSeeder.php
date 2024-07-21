<?php

namespace App\Services\FFXIV\Seeders;

use App\Models\GameEntities\Recipe;
use App\Models\Ninja\ListItems;
use App\Models\Ninja\Lists;
use App\Providers\GameServiceProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultListsSeeder extends Seeder
{
    public function run(): void
    {
        if (!GameServiceProvider::$game) {
            throw new \Exception('Seeder can only be triggered from `osmose:seed {slug}`');
        }

        DB::transaction(function() {
            $gameId = GameServiceProvider::$game->id;

            $recipes = Recipe::with('notebooks.notebookdivisions.category', 'job')
                ->get();

            $lists = [];
            foreach ($recipes as $recipe) {
                foreach ($recipe->notebooks as $notebook) {
                    foreach ($notebook->notebookdivisions as $division) {
                        if (!preg_match('/^\d+-\d+$/', $division->name)) {
                            continue;
                        }
                        $key = $recipe->job->name . ' ' . $division->category->name . ' ' . $division->name;
                        $lists[$key] ??= [];
                        $lists[$key][$notebook->pivot->slot] = $recipe->id;
                    }
                }
            }

            ksort($lists);

            $itemIds = Recipe::pluck('item_id', 'id')->toArray();

            foreach ($lists as $name => $ids) {
                $list = Lists::firstOrCreate([
                    'name' => $name,
                    'user_id' => 0,
                    'game_id' => $gameId,
                ], [
                    'is_public' => true,
                ]);

                if (!$list->wasRecentlyCreated) {
                    $this->command->info($name . ' already exists. Skipping.');
                    continue;
                }

                ListItems::insert(collect($ids)->map(fn ($id) => [
                    'list_id' => $list->id,
                    'item_id' => $itemIds[$id],
                    'recipe_id' => $id,
                    'quantity' => 1,
                ])->toArray());

                $this->command->info($name . ' created.');
            }
        });
    }
}
