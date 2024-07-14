<?php

namespace App\Models\GameEntities;

class Fishing extends GameEntityAbstract {

	protected $table = 'fishing';

	public function items()
	{
		return $this->belongsToMany(Item::class);
	}

	public function zone()
	{
		return $this->belongsTo(Location::class, 'zone_id');
	}

	public function area()
	{
		return $this->belongsTo(Location::class, 'area_id');
	}
}
