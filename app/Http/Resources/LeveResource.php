<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeveResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            // 'item'     => new ItemResource($this->requirements->first()),
            'recipe'   => new RecipeResource($this->requirements->first()->recipes->first()),
            'quantity' => $this->requirements[0]->pivot->amount,
            'level'    => $this->level,
            'xp'       => $this->xp,
            'gil'      => $this->gil,
            'repeats'  => $this->repeats,
            'frame'    => icon($this->frame),
            'plate'    => icon($this->plate),
            'location' => new LocationResource($this->location),
        ];
    }
}
