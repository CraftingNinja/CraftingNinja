<?php

namespace App\Providers;

use App\Models\Ninja\Game;
use Illuminate\Cache\TaggedCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Input\ArgvInput;

class GameServiceProvider extends ServiceProvider
{
    static public string $slug = '';

    static public ?Game $game;
    static public ?string $connection;

    // This is nuts, convert it all to simply $internals
    static public array $aspir = [];
    static public array $casts = [];
    static public array $seeders = [];
    // static public array $dynamicBindings = [];

    public function boot(): void
    {
        self::parseSlug();
        self::registerGame();
        // self::bindGameServices();
    }

    static public function parseSlug(): void
    {
        if (app()->runningInConsole()) {
            $slug = (new ArgvInput())->getRawTokens()[1] ?? '';
        } elseif (app()->runningUnitTests()) {
            // TODO 1 - When running unit tests, how do I get the game slug?
            $slug = '';
        } else {
            // Edge case: This will likely be "crafting" at times (parsed from a root `crafting.ninja`).
            // I'm okay with that. Hopefully there's never a game I integrate with simply called "crafting".
            [$slug, $backup] = explode('.', request()->getHost());
            // Allow for dev domains, like qa.ffxiv.crafting.ninja
            if (app()->environment($slug)) {
                $slug = $backup;
            }
        }

        self::$slug = $slug;
    }

    static public function registerGame(?string $override = null): void
    {
        $slug = $override ?? self::$slug;

        if (!$slug) {
            return;
        }

        self::$game = Cache::rememberForever($slug . '-game', fn () => Game::whereSlug($slug)->first());

        if (self::$game) {
            self::$connection = config("games.$slug.internals.connection");
            self::$aspir = config("games.$slug.internals.aspir");
            self::$casts = config("games.$slug.internals.casts");
            self::$seeders = config("games.$slug.internals.seeders");
            // self::$dynamicBindings = config("games.$slug.internals.bindings");
        }
    }

    // static public function bindGameServices(): void
    // {
    //     if (!self::$slug || empty(self::$dynamicBindings)) {
    //         return;
    //     }
    //
    //     foreach (self::$dynamicBindings as $from => $to) {
    //         $this->app->bind($from, $to);
    //     }
    // }

    static public function cache(): TaggedCache
    {
        return Cache::tags(self::$game?->slug ?? 'masamune');
    }
}
