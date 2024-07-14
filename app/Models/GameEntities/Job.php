<?php

namespace App\Models\GameEntities;

class Job extends GameEntityAbstract {

	protected $table = 'job';

	public function categories()
	{
		return $this->belongsToMany(JobCategory::class);
	}

	public function recipe()
	{
		return $this->hasMany(Recipe::class);
	}

	static public function get_by_type($type)
	{
		return Job::whereIn('id', config('site.job_ids')[$type])->get();
	}

	static public function get_by_abbr($abbr)
	{
		return Job::where('abbr', $abbr)->first();
	}

}
