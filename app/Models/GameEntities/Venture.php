<?php

namespace App\Models\GameEntities;

class Venture extends GameEntityAbstract {

	protected $table = 'venture';

	public function items()
	{
		return $this->belongsToMany(Item::class);
	}

	public function job_category()
	{
		return $this->belongsTo(JobCategory::class);
	}

}
