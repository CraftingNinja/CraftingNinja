<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class GameScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('game_id', config('game.id'));
    }
}
