<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            // If we don't know where the NPC is, ignore them; reporting the shop name is going to have to be good enough
            'npcs' => $this->relationLoaded('npcs') ? NPCResource::collection($this->npcs->filter(fn ($npc) => $npc->zone_id)) : [],
        ];
    }
}
