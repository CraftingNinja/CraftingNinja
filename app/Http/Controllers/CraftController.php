<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\JobController;
use App\Models\Ninja\Lists;
use Inertia\Inertia;
use Inertia\Response;

class CraftController extends Controller
{
    public function fromActiveList(): Response
    {
        $jobs = (new JobController)->getCraftingJobs();

        return Inertia::render('Craft/Tool', [
            'jobs' => $jobs,
            'list' => false,
        ]);
    }

    public function fromList(Lists $list): Response
    {
        $list->load('items');
        $jobs = (new JobController)->getCraftingJobs();

        return Inertia::render('Craft/Tool', [
            'jobs' => $jobs,
            'list' => $list,
        ]);
    }
}
