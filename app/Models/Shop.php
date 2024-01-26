<?php

namespace App\Models;

class Shop extends FFXIVModel {

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
