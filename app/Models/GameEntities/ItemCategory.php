<?php

namespace App\Models\GameEntities;

class ItemCategory extends GameEntityAbstract {

	protected $table = 'item_category';

	public function items()
	{
		return $this->hasMany(Item::class);
	}

}
