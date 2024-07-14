<?php

namespace App\Models\GameEntities;

class Achievement extends GameEntityAbstract {

	protected $table = 'achievement';

	public function item()
	{
		return $this->belongsTo(Item::class);
	}

}
