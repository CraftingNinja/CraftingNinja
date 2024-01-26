<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FishingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level,
            'radius' => $this->radius,
            'x' => $this->x,
            'y' => $this->y,
            'zone_id' => $this->zone_id,
            'area_id' => $this->area_id,
            'zone' => $this->relationLoaded('zone') ? new LocationResource($this->zone) : [],
            'area' => $this->relationLoaded('area') ? new LocationResource($this->area) : [],
        ];
    }
}
