<?php

namespace App\Models;

class ItemAttribute extends FFXIVModel {

	protected $table = 'item_attribute';

	public function item()
	{
		return $this->belongsTo(Item::class);
	}

}
