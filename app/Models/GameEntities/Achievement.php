<?php

namespace App\Models\GameEntities;

use App\Casts\IconPathCast;

class Achievement extends GameEntityAbstract {

	protected $table = 'achievement';

    protected $casts = [
        'icon' => IconPathCast::class,
    ];

	public function item()
	{
		return $this->belongsTo(Item::class);
	}

}
