<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\JobController;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(): Response
    {
        $jobs = (new JobController)->getCraftingJobs();
        $lists = auth()->user()?->lists->pluck('name', 'id') ?? [];

        return Inertia::render('Cart/Index', compact('jobs', 'lists'));
    }

    public function store(): Response
    {
        dd(request()->all());
    }
}
