<?php

namespace App\Models\GameEntities;

class ItemAttribute extends GameEntityAbstract {

	protected $table = 'item_attribute';

	public function item()
	{
		return $this->belongsTo(Item::class);
	}

}
