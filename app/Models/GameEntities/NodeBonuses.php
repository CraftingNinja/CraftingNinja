<?php

namespace App\Models\GameEntities;

class NodeBonuses extends GameEntityAbstract {

	protected $table = 'node_bonuses';

	public function nodes()
	{
		return $this->hasMany(Node::class, 'bonus_id');
	}

}
