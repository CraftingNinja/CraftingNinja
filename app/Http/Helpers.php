<?php

function icon($icon)
{
    // Going to assume for now that all icons that we're interested in are five digits, otherwise we'd have different rules.
    //  See https://xivapi.com/docs/Icons
    $icon = '0' . $icon;
    $folder = substr($icon, 0, 3) . "000";
    $iconBase = 'i/' . $folder . '/' . $icon . '.png';

    return assetcdn($iconBase);
}

function assetcdn($asset)
{
    $cdn = config('services.assets.cdn');

    // Check if we added cdn's to the config file
    if( ! $cdn)
        return asset( $asset );

    return 'https://' . $cdn . '/' . $asset;
}
