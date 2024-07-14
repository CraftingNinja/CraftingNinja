<?php

namespace App\Models\GameEntities;

class Shop extends GameEntityAbstract {

	protected $table = 'shop';

	public function npcs()
	{
		return $this->belongsToMany(Npc::class);
	}

	public function items()
	{
		return $this->belongsToMany(Item::class);
	}

	// public function name()
	// {
	// 	return $this->belongsTo(ShopName::class);
	// }

}
