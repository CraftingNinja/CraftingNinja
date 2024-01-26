<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'job_id'       => $this->job_id,
            'recipe_level' => $this->recipe_level,
            'stars'        => $this->stars,
            'yield'        => $this->yield,
            'reagents'     => $this->reagents->mapWithKeys(function($row) {
                return [$row->id => $row->pivot->amount];
            }),
        ];
    }
}
