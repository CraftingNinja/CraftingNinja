<?php

namespace App\Models;

class Notebook extends FFXIVModel
{
	public $table = 'notebook';

    public function notebookdivisions()
    {
    	return $this->belongsToMany(Notebookdivision::class, 'notebook_notebookdivision');
    }

    public function recipes()
    {
    	return $this->belongsToMany(Recipe::class, 'notebook_recipe', 'notebook_id', 'recipe_id');
    }
}
