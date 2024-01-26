<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class FFXIVModel extends EloquentModel {
    protected $connection = 'pgsql.ffxiv';
}
