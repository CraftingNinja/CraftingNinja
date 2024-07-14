<?php

namespace App\Http\Middleware;

use App\Models\Ninja\Game;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class GameSetup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        [$subdomain, $backup] = explode('.', $request->getHost());

        // Allow for dev domains, like qa.ffxiv.crafting.ninja
        if (app()->environment($subdomain)) {
            $subdomain = $backup;
        }

        $game = Cache::rememberForever($subdomain . '-game', fn () => Game::whereSlug($subdomain)->sole()->toArray());

        config([
            'game' => [
                ...$game,
                ...config("games.$subdomain.attributes", [])
            ],
            'gameInternals' => config("games.$subdomain.internals", [])
        ]);

        return $next($request);
    }
}
