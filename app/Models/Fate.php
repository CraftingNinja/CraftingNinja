<?php

namespace App\Models;

class Fate extends FFXIVModel {

	protected $table = 'fate';

	public function location()
	{
		return $this->belongsTo(Location::class, 'zone_id');
	}

}
