<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Models\Recipe;
use Illuminate\Support\Facades\Cache;

class JobController extends Controller
{
	public function types(string $type)
	{
		if ($type === 'crafting') {
			$jobs = $this->getCraftingJobs();
        }

		return response()->json($jobs ?? []);
	}

    public function getCraftingJobs(): array
    {
        return Cache::rememberForever('crafting.jobs', fn () =>
            Job::whereIn('id', Recipe::selectRaw('DISTINCT job_id')->pluck('job_id'))
                ->get()
                ->keyBy('id')
                ->toArray()
        );
    }
}
