<?php

namespace App\Models;

class Mob extends FFXIVModel {

	protected $table = 'mob';

	public function items()
	{
		return $this->belongsToMany(Item::class);
	}

	public function instances()
	{
		return $this->belongsToMany(Instance::class);
	}

	public function location()
	{
		return $this->belongsTo(Location::class, 'zone_id');
	}

}
