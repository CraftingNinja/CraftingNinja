<?php

namespace App\Models\GameEntities;

use App\Casts\IconPathCast;

class Quest extends GameEntityAbstract {

	protected $table = 'quest';

	protected $guarded = ['id'];

    protected $casts = [
        'icon' => IconPathCast::class,
    ];

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
