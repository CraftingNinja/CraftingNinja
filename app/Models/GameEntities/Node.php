<?php

namespace App\Models\GameEntities;

class Node extends GameEntityAbstract {

	protected $table = 'node';

	public function items()
	{
		return $this->belongsToMany(Item::class);
	}

	public function zone()
	{
		return $this->belongsTo(Location::class, 'zone_id');
	}

	public function area()
	{
		return $this->belongsTo(Location::class, 'area_id');
	}

	public function bonuses()
	{
		return $this->belongsTo(NodeBonuses::class, 'bonus_id');
	}

}
