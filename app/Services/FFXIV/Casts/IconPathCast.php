<?php

namespace App\Services\FFXIV\Casts;

use Illuminate\Database\Eloquent\Model;

class IconPathCast
{
    static public function get(string $icon): string
    {
        // Assuming that all icons that we're interested in are five digits, otherwise we would need different rules.
        // See https://xivapi.com/docs/Icons
        $icon = '0' . $icon;
        $folder = substr($icon, 0, 3) . "000";
        return 'i/' . $folder . '/' . $icon . '.png';
    }

    public function set(string $value): string
    {
        return $value;
    }

}
