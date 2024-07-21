<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\LevesController as LevesAPIController;
use App\Providers\GameServiceProvider;
use Inertia\Inertia;
use Inertia\Response;

class LevesController extends Controller
{
    public function index(): Response
    {
        $jobs = (new JobController())->getCraftingJobs();

        $initialResults = GameServiceProvider::cache()->remember(
            'initial_leves_result',
            now()->addDay(),
            fn () => (new LevesAPIController())->search(array_key_first($jobs), 1)
        );

        return Inertia::render('Leves/Index', compact('jobs', 'initialResults'));
    }
}
