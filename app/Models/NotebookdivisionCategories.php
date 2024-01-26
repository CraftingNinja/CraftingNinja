<?php

namespace App\Models;

class NotebookdivisionCategories extends FFXIVModel
{
	public $table = 'notebookdivision_category';

    public function divisions()
    {
    	return $this->hasMany(Notebookdivision::class, 'category_id');
    }
}
