<?php

namespace App\Providers;

use Illuminate\Database\PostgresConnection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Builder::macro('withWhereHas', fn ($relation, $constraint) =>
            $this->whereHas($relation, $constraint)->with([$relation => $constraint])
        );

        Builder::macro('whereLike', function (string $column, string $search) {
            // ILIKE is postgres specific, and is case-insensitive.
            //  Mysql's LIKE is case-insensitive by default.
            $operator = DB::connection()::class === PostgresConnection::class
                ? 'ILIKE'
                : 'LIKE';

            return $this->where($column, $operator, $search);
        });

        Builder::macro('orWhereLike', function (string $column, string $search) {
            $operator = DB::connection()::class === PostgresConnection::class
                ? 'ILIKE'
                : 'LIKE';

            return $this->orWhere($column, $operator, $search);
        });
    }
}
