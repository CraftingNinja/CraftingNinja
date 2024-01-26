<?php

namespace App\Models;

class ItemCategory extends FFXIVModel {

	protected $table = 'item_category';

	public function items()
	{
		return $this->hasMany(Item::class);
	}

}
