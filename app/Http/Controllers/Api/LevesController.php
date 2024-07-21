<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\LeveResource;
use App\Models\GameEntities\Job;
use App\Models\GameEntities\JobCategory;
use App\Models\GameEntities\Leve;
use App\Providers\GameServiceProvider;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LevesController extends Controller
{
    public const WITH = [
        'requirements.recipes.item.category',
        'location',
    ];

    public function search(?int $jobId = null, ?int $level = null): AnonymousResourceCollection
    {
        if ($jobId && $level) {
            $data = compact('jobId', 'level');
        } else {
            $data = request()->validate([
                'jobId' => 'required|exists:' . Job::class . ',id',
                'level' => 'required|numeric|min:1|max:' . GameServiceProvider::$game['settings']['maxLevel'],
            ]);
        }

        $job = Job::findOrFail($data['jobId']);
        // All Job Categories that we're interested are solo 'ARM', 'CUL', etc
        //  There aren't any Disciple of the Hand global Leves
        $jobClassIds = JobCategory::where('name', $job->abbr)->pluck('id');

        return LeveResource::collection(
            Leve::with(self::WITH)
            ->whereIn('job_category_id', $jobClassIds)
            ->where('level', $data['level'])
            ->get()
        );
    }
}
