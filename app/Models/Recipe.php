<?php

namespace App\Models;

class Recipe extends FFXIVModel {

	protected $table = 'recipe';

	public function reagents()
	{
		return $this->belongsToMany(Item::class, 'recipe_reagents')->withPivot('amount');
	}

	public function item()
	{
		return $this->belongsTo(Item::class);
	}

	public function job()
	{
		return $this->belongsTo(Job::class);
	}

	public function notebooks()
	{
		return $this->belongsToMany(Notebook::class, 'notebook_recipe', 'recipe_id', 'notebook_id')->withPivot('slot');
	}

}
