<?php

namespace App\Models\GameEntities;

class NpcBase extends GameEntityAbstract {

	protected $table = 'npc_base';

	public function npcs()
	{
		return $this->belongsToMany(Npc::class);
	}

}
