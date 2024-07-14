<?php

namespace Database\Factories\Ninja;

use App\Models\Ninja\Lists;
use App\Models\Ninja\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListsFactory extends Factory
{
    protected $model = Lists::class;

    public function definition(): array
    {
        return [
            'game_id' => 1,
            'user_id' => User::factory()->create(),
            'name' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'is_public' => true,
        ];
    }

    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => false,
        ]);
    }
}
