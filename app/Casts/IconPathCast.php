<?php

namespace App\Casts;

use App\Providers\GameServiceProvider;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class IconPathCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        // You can't use an interface as a Cast and you can't `bind` a non-interface.
        // Otherwise, this would be a GameServiceProvider binding.
        return isset(GameServiceProvider::$casts[self::class])
            ? GameServiceProvider::$casts[self::class]::get($value)
            : $value;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        return isset(GameServiceProvider::$casts[self::class])
            ? GameServiceProvider::$casts[self::class]::set($value)
            : $value;
    }
}
