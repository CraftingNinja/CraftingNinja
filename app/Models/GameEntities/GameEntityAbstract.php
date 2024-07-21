<?php

namespace App\Models\GameEntities;

use App\Providers\GameServiceProvider;
use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class GameEntityAbstract extends EloquentModel
{
    // Dynamically switch databases for any Game Model.
    // Schema based on game slug.
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(GameServiceProvider::$connection);
    }
}
