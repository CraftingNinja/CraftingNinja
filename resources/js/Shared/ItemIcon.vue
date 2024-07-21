<template>
    <div class="inline-block relative w-[40px] h-[40px]">
        <img
            v-if="quality"
            :src="gameAsset('leves/hq-overlay.png')"
            class="w-[40px] h-[40px] absolute"
            alt="Quality Overlay"
        />
        <img
            :src="gameAsset(item.icon)"
            class="w-[40px] h-[40px]"
            :alt="item.name"
            :title="item.name"
        />
    </div>
</template>

<script setup>
import { gameAsset } from "@H/assets.js";
import { defaultObject, defaultFalse } from "@H/propDefaults.js";

defineProps({
	item: defaultObject,
    quality: defaultFalse,
});
</script>
