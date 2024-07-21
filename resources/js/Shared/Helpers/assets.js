import {usePage} from "@inertiajs/vue3";

export const gameAsset = (filepath) => usePage().props.game.assetUrl + filepath;
