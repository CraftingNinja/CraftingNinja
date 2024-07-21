<?php

namespace App\Http\Controllers;

use App\Models\GameEntities\Job;
use App\Providers\GameServiceProvider;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class HuntingController extends Controller
{
    public function index(): Response
    {
        // TODO - This is a FFXIV only route, but maybe this could be improved.
        $data = Cache::remember('huntingLog', now()->addDay(), function() {
            $huntingJobs = ['ACN', 'ARC', 'CNJ', 'GLA', 'LNC', 'MRD', 'PGL', 'ROG', 'THM'];
            $jobs = Job::whereIn('abbr', $huntingJobs)->orderBy('abbr')->pluck('name', 'abbr');

            $companies = [
                'IMF' => 'Immortal Flames',
                'MLS' => 'Maelstrom',
                'ORD' => 'Order of the Twin Adder',
            ];

            $huntingData = json_decode(file_get_contents(GameServiceProvider::$aspir['data_dir'] . 'huntingData.json'));

            return compact('jobs', 'companies', 'huntingData');
        });

        return Inertia::render('Hunting/Index', $data);
    }
}
