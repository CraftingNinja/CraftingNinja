<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\JobController;
use Inertia\Inertia;
use Inertia\Response;

class LibraryController extends Controller
{
    public function index(): Response {
        $jobs = (new JobController)->getCraftingJobs();

        return Inertia::render('Library/Index', compact('jobs'));
    }
}
