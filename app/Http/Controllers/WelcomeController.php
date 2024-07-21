<?php

namespace App\Http\Controllers;

use App\Models\Ninja\Game;
use App\Providers\GameServiceProvider;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function index(): Response
    {
        return GameServiceProvider::$game
            ? $this->gameIndex()
            : $this->ninjaIndex()
        ;
    }

    private function gameIndex(): Response
    {
        return Inertia::render('Welcome');
    }

    private function ninjaIndex(): Response
    {
        $games = Game::all();
        return Inertia::render('Ninja/Welcome', compact('games'));
    }
}
