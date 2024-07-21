<?php

namespace App\Providers;

use Illuminate\Database\PostgresConnection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->whereLikeMacros();
    }

    public static function caseInsensitiveLikeOperator(): string
    {
        // ILIKE is postgres specific, and is case-insensitive.
        //  Mysql's LIKE is case-insensitive by default.
        return DB::connection()::class === PostgresConnection::class
            ? 'ILIKE'
            : 'LIKE';
    }

    private function whereLikeMacros(): void
    {
        $operator = self::caseInsensitiveLikeOperator();

        Builder::macro('whereLike', fn (string $column, string $search) => $this->where($column, $operator, $search));

        Builder::macro('orWhereLike', fn (string $column, string $search) => $this->orWhere($column, $operator, $search));

        Builder::macro('whereLikeRaw', fn (string $column, string $search) => $this->whereRaw("{$column} {$operator} '{$search}'"));

        Builder::macro('orWhereLikeRaw', fn (string $column, string $search) => $this->orWhereRaw("{$column} {$operator} '{$search}'"));
    }
}
