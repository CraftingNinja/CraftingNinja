<?php

namespace App\Models\Ninja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListItems extends Model
{
    use HasFactory;

    protected $table = 'item_list';

    public $timestamps = false;

    protected $fillable = [
        // These aren't relationships because they're technically pointing to a different database
        'item_id',
        'recipe_id',
        'quantity',
    ];

    public function list(): BelongsTo
    {
        return $this->belongsTo(Lists::class);
    }
}
