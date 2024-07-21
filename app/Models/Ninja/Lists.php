<?php

namespace App\Models\Ninja;

use App\Models\Scopes\GameScope;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RossBearman\Sqids\HasSqid;
use RossBearman\Sqids\SqidBasedRouting;

// NOTE: `List` is a reserved word in PHP
class Lists extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasSqid;
    use SqidBasedRouting;

    protected $fillable = [
        'name',
        'description',
        'is_public',
    ];

    protected $appends = ['sqid'];

    protected static function booted(): void
    {
        static::addGlobalScope(new GameScope);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ListItems::class, 'list_id');
    }

    public function scopePublic($query): void
    {
        $query->where('is_public', true);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['author'] ?? null, function ($query, $author) {
            $query->whereHas('user', function ($query) use ($author) {
                $author = preg_replace('/\*|\s/', '%', $author);
                $query->whereLike('name', '%' . $author . '%');
            });
        });

        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function($query) use ($search) {
                $search = preg_replace('/\*|\s/', '%', $search);
                $query->whereLike('name', '%' . $search . '%')
                    ->orWhereLike('description', '%' . $search . '%');
            });
        });

        $sort = $filters['sort'] ?? 'created_at';
        $order = $filters['order'] ?? 'desc';

        if ($sort === 'users.name') {
            $query
                ->join('users', 'users.id', '=', 'lists.user_id')
                ->select('lists.*');
        }
        $query->orderBy($sort, $order);
    }
}
