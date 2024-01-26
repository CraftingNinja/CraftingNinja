<?php

namespace App\Models;

class NodeBonuses extends FFXIVModel {

	protected $table = 'node_bonuses';

	public function nodes()
	{
		return $this->hasMany(Node::class, 'bonus_id');
	}

}
