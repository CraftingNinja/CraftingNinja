<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'level' => $this->level,
            'zone_id' => $this->zone_id,
            'area_id' => $this->area_id,
            'coordinates' => $this->coordinates,
            'timer' => $this->timer,
            'timer_type' => $this->timer_type,
            'zone' => $this->relationLoaded('zone') ? new LocationResource($this->zone) : [],
            'area' => $this->relationLoaded('area') ? new LocationResource($this->area) : [],
        ];
    }
}
