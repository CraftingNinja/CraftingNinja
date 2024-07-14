<?php

namespace App\Models\GameEntities;

class Fate extends GameEntityAbstract {

	protected $table = 'fate';

	public function location()
	{
		return $this->belongsTo(Location::class, 'zone_id');
	}

}
