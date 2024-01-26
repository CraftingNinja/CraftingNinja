<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'zone_id' => $this->zone_id,
            'level' => $this->level,
            'location' => $this->relationLoaded('location') ? new LocationResource($this->location) : [],
        ];
    }
}
