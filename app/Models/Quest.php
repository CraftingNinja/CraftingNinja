<?php

namespace App\Models;

class Quest extends FFXIVModel {

	protected $table = 'quest';

	protected $guarded = ['id'];

	public function rewards()
	{
		return $this->belongsToMany(Item::class, 'quest_reward');
	}

	public function requirements()
	{
		return $this->belongsToMany(Item::class, 'quest_required');
	}

	public function npcs()
	{
		return $this->belongsToMany(Npc::class);
	}

	public function location()
	{
		return $this->belongsTo(Location::class, 'zone_id');
	}

	public function job_category()
	{
		return $this->belongsTo(JobCategory::class);
	}

}
