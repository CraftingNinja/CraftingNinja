<?php

namespace App\Models\Ninja;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public $timestamps = false;

    protected $appends = [
        'url',
        'assetUrl',
        'meta',
        'settings',
    ];

    public function getUrlAttribute(): ?string
    {
        return preg_replace('/\/\//', '//' . $this->slug . '.', config('app.url'));
    }

    public function getAssetUrlAttribute(): ?string
    {
        return config('services.assets.url') . $this->slug . '/';
    }

    public function getMetaAttribute(): ?array
    {
        return config("games.{$this->slug}.meta");
    }

    public function getSettingsAttribute(): ?array
    {
        return config("games.{$this->slug}.settings");
    }
}
