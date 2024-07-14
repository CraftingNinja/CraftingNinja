<?php

namespace App\Models\GameEntities;

class NotebookdivisionCategories extends GameEntityAbstract
{
	public $table = 'notebookdivision_category';

    public function divisions()
    {
    	return $this->hasMany(Notebookdivision::class, 'category_id');
    }
}
