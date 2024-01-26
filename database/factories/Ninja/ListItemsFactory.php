<?php

namespace Database\Factories\Ninja;

use App\Models\Item;
use App\Models\Ninja\ListItems;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListItemsFactory extends Factory
{
    protected $model = ListItems::class;

    public function definition(): array
    {
        return [
            'list_id' => null,
            'item_id' => Item::inRandomOrder()->limit(1)->first()->id,
            'quantity' => rand(1, 100),
        ];
    }
}
