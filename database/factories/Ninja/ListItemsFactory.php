<?php

namespace Database\Factories\Ninja;

use App\Models\GameEntities\Item;
use App\Models\Ninja\ListItems;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListItemsFactory extends Factory
{
    protected $model = ListItems::class;

    public function definition(): array
    {
        return [
            'list_id' => null,
            'quantity' => rand(1, 100),
        ];
    }
}
