<?php

namespace App\Models\GameEntities;

use App\Casts\IconPathCast;

class Instance extends GameEntityAbstract {

	protected $table = 'instance';

    protected $casts = [
        'icon' => IconPathCast::class,
    ];

	public function items()
	{
		return $this->belongsToMany(Item::class);
	}

	public function mobs()
	{
		return $this->belongsToMany(Mob::class);
	}

	public function location()
	{
		return $this->belongsTo(Location::class, 'zone_id');
	}

}
