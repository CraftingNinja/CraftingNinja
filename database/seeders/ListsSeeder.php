<?php

// sail artisan db:seed ListsSeeder

namespace Database\Seeders;

use App\Models\Ninja\ListItems;
use App\Models\Ninja\Lists;
use Illuminate\Database\Seeder;

class ListsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lists = Lists::factory()->count(125)->create();

        foreach ($lists as $list) {
            ListItems::factory()->count(rand(1, 10))->create([ 'list_id' => $list->id ]);
        }
    }
}
