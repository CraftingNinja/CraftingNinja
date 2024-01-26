<?php

namespace App\Models;

class Achievement extends FFXIVModel {

	protected $table = 'achievement';

	public function item()
	{
		return $this->belongsTo(Item::class);
	}

}
