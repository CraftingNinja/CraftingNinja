<?php

namespace App\Models;

class Location extends FFXIVModel {

	protected $table = 'location';

	public function node_zones()
	{
		return $this->hasMany(Node::class, 'zone_id');
	}

	public function node_areas()
	{
		return $this->hasMany(Node::class, 'area_id');
	}

	public function fishing_zones()
	{
		return $this->hasMany(Fishing::class, 'zone_id');
	}

	public function fishing_areas()
	{
		return $this->hasMany(Fishing::class, 'area_id');
	}

	public function mobs()
	{
		return $this->hasMany(Mob::class, 'zone_id');
	}

	public function location()
	{
		return $this->belongsTo(Location::class);
	}

	public function instances()
	{
		return $this->hasMany(Instance::class, 'zone_id');
	}

	public function npcs()
	{
		return $this->hasMany(Npc::class, 'zone_id');
	}

}
