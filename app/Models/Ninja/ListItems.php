<?php

namespace App\Models\Ninja;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
