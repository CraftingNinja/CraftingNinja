<?php

namespace App\Models;

class Npc extends FFXIVModel {

	protected $table = 'npc';

	public function bases()
	{
		return $this->belongsToMany(NpcBase::class);
	}

	public function quests()
	{
		return $this->belongsToMany(Quest::class);
	}

	public function shops()
	{
		return $this->belongsToMany(Shop::class);
	}

	public function location()
	{
		return $this->belongsTo(Location::class, 'zone_id');
	}

}
