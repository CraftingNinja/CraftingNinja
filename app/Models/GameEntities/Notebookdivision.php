<?php

namespace App\Models\GameEntities;

class Notebookdivision extends GameEntityAbstract
{
	public $table = 'notebookdivision';

    public function categories()
    {
    	return $this->belongsTo(NotebookdivisionCategories::class, 'category_id');
    }

    public function notebooks()
    {
    	return $this->belongsToMany(Notebook::class, 'notebook_notebookdivision', 'notebook_id', 'notebookdivision_id');
    }
}
