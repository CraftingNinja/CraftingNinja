<?php

namespace App\Models;

class Venture extends FFXIVModel {

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
