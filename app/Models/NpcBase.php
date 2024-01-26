<?php

namespace App\Models;

class NpcBase extends FFXIVModel {

	protected $table = 'npc_base';

	public function npcs()
	{
		return $this->belongsToMany(Npc::class);
	}

}
