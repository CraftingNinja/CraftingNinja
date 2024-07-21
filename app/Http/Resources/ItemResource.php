<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'equip'       => $this->equip,
            'slot'        => $this->slot,
            'price'       => $this->price,
            'gc_price'    => $this->gc_price,
            'special_buy' => !! $this->special_buy,
            'tradeable'   => $this->tradeable,
            'ilvl'        => $this->ilvl,
            'rarity'      => $this->rarity,
            'icon'        => $this->icon,
            'category'    => $this->relationLoaded('category') ? new CategoryResource($this->category) : [],
            'recipes'     => $this->relationLoaded('recipes') ? RecipeResource::collection($this->recipes) : [],
            'shops'       => $this->relationLoaded('shops') ? ShopResource::collection($this->shops) : [],
            'mobs'        => $this->relationLoaded('mobs') ? MobResource::collection($this->mobs) : [],
            'nodes'       => $this->relationLoaded('nodes') ? NodeResource::collection($this->nodes) : [],
            'fishing'     => $this->relationLoaded('fishing') ? FishingResource::collection($this->fishing) : [],
        ];
    }
}
