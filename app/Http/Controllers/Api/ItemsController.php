<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ItemResource as ItemResource;
use App\Http\Resources\RecipeResource as RecipeResource;
use App\Models\Item;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemsController extends Controller
{
    public const WITH = [
        'category',
        'recipes.reagents',
        'shops.npcs.location.location',
        'mobs.location.location',
        'nodes.zone.location',
        'nodes.area',
        'fishing.zone.location',
        'fishing.area',
    ];

    public function search()
    {
        request()->validate([
            'search' => 'nullable|min:3|max:255',
            'rlevel_min' => 'numeric',
            'rlevel_max' => 'numeric',
            'ilvl_min' => 'numeric',
            'ilvl_max' => 'numeric',
            'jobs' => 'nullable|array',
            'stars' => 'nullable|array',
            'per_page' => 'numeric|min:15|max:60',
        ]);

        $query = Item::with(self::WITH);

        // Search
        $query->when(request('search') ?? null, function ($query, $search) {
            $search = preg_replace('/\*/', '%', $search);
            $query->whereLike(Item::localized_name_variable(), '%' . $search . '%');
        });

        // ilvl filter
        $imin = 1;
        $imax = config('ffxiv.maxItemLevel');
        $ilvlMin = min(max(request('ilvl_min', $imin), $imin), $imax);
        $ilvlMax = min(max(request('ilvl_max', $imax), $imin), $imax);
        if ($ilvlMin !== $imin && $ilvlMax !== $imax) {
            $query->whereBetween('ilvl', [$ilvlMin, $ilvlMax]);
        }

        // Recipe filters
        if (request()->hasAny(['rlevel_min', 'rlevel_max', 'stars', 'jobs'])) {
            $rmin = 1;
            $rmax = (int) config('ffxiv.maxLevel');
            $rlvlMin = (int) min(max(request('rlevel_min', $rmin), $rmin), $rmax);
            $rlvlMax = (int) min(max(request('rlevel_max', $rmax), $rmin), $rmax);
            $rlvlRange = $rlvlMin !== $rmin && $rlvlMax !== $rmax ? [$rlvlMin, $rlvlMax] : false;
            $stars = request('stars');
            $jobs = request('jobs');

            // Don't invoke the whereHas unless necessary; it'll join the table and only items with recipes will be returned
            if ($rlvlRange || $stars || $jobs) {
                $query->whereHas('recipes', fn ($query) =>
                    $query
                        ->when($rlvlRange, fn ($query, $rlvlRange) => $query->whereBetween('recipe_level', $rlvlRange))
                        ->when($stars, fn ($query, $stars) => $query->whereIn('stars', $stars))
                        ->when($jobs, fn ($query, $jobs) => $query->whereIn('job_id', $jobs))
                );
            }
        }

        // Sort and Order
        $sort = request('sort');
        $order = request('order');
        if ($sort === 'name') {
            $sort = Item::localized_name_variable();
        } elseif ($sort === 'rlevel') {
            $query->withAggregate('recipes', 'recipe_level');
            $sort = 'recipes_recipe_level'; // withAggregate's generated table_column_name format
        }
        $query->orderBy($sort, $order);

        $pagination = $query->paginate(request('per_page'), page: request('page', 1));

        return ItemResource::collection($pagination);
    }

	public function show($id): ItemResource
    {
        $item = Item::with(self::WITH)
            ->withAggregate('recipes', 'recipe_level')
            ->findOrFail($id);

		return new ItemResource($item);
	}

    public function many(): ResourceCollection
    {
        return ItemResource::collection(Item::with(self::WITH)->whereIn('id', request('items'))->get());
    }

    // TODO 1 - I think this can be removed? But it's referenced in recipes. Dig into it later.
	public function packageItem($item)
	{
		return [
			'id'          => $item->id,
			'name'        => $item->name,
			'price'       => $item->price,
			'gc_price'    => $item->gc_price,
			'special_buy' => !! $item->special_buy,
			'tradeable'   => $item->tradeable,
			'ilvl'        => $item->ilvl,
			'category_id' => $item->item_category_id,
			'rarity'      => $item->rarity,
			'icon'        => icon($item->icon),
			'recipes'     => $item->recipes->pluck('id')->toArray(),
		];
	}
}
