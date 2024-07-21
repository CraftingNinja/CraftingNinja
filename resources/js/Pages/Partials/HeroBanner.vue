<template>
    <div class="relative h-[360px] w-full overflow-hidden sm:h-[420px] md:h-[440px] lg:h-[540px] xl:h-[640px]">
        <div class="relative z-10 mx-auto max-w-[1440px] px-5">
            <div class="absolute top-32 left-0 w-full px-5 text-center md:top-36 lg:top-32 lg:left-1/2 lg:w-1/2 lg:px-0 lg:text-left xl:-ml-10">
                <h1 class="text-5xl font-bold uppercase tracking-tighter text-gray-900 dark:text-white sm:text-6xl md:text-7xl lg:text-9xl lg:leading-[0.75em] xl:text-[150px]">
                    <span class="text-primary">{{ game.meta.splash.name }}</span>
                    Crafting
                </h1>
                <div class="text-lg font-medium uppercase tracking-tighter text-gray-900 dark:text-white sm:text-xl lg:pt-4 lg:text-2xl xl:pt-5 xl:pl-3 xl:text-[26px]">
                    {{ game.meta.patch.version }} {{ game.meta.patch.tag }}
                </div>
            </div>

            <div class="hidden md:block">
                <div class="absolute left-5 top-8 aspect-square w-3.5 bg-white"></div>
                <div class="absolute right-[91px] top-[120px] aspect-square w-1 bg-primary"></div>
                <div class="absolute right-[168px] top-[120px] aspect-square w-1 bg-primary"></div>
                <div class="absolute right-[91px] top-[197px] aspect-square w-1 bg-primary"></div>
                <div class="absolute right-[91px] top-8 aspect-square w-1 bg-primary"></div>
                <div class="absolute right-[168px] top-8 aspect-square w-1 bg-primary"></div>
                <div class="absolute right-[244px] top-8 aspect-square w-1 bg-primary"></div>
                <div class="absolute right-[321px] top-8 aspect-square w-1 bg-primary"></div>
                <div class="absolute right-[398px] top-8 aspect-square w-1 bg-primary"></div>
                <div class="absolute right-[474px] top-8 aspect-square w-1 bg-primary"></div>
                <div class="absolute right-[551px] top-8 aspect-square w-1 bg-primary"></div>
                <div class="absolute right-[627px] top-8 aspect-square w-1 bg-primary"></div>
                <div class="absolute right-[704px] top-8 aspect-square w-1 bg-primary"></div>
                <div class="absolute right-[91px] top-8 aspect-square w-1 bg-primary"></div>
                <div class="absolute right-1 top-8 aspect-square w-3.5 bg-primary"></div>
                <div class="absolute right-1 top-[120px] aspect-square w-1 bg-primary"></div>
                <div class="absolute right-1 top-[197px] aspect-square w-1 bg-primary"></div>
                <div class="absolute right-1 top-[274px] aspect-square w-1 bg-primary"></div>
                <div class="absolute right-1 top-[351px] aspect-square w-1 bg-primary"></div>
            </div>
        </div>

        <div class="vv-hero-decoration-left absolute left-0 top-0 z-[5] h-full w-[calc(50vw-130px)] bg-primary"></div>

        <div class="absolute left-1/2 bottom-0 z-[5] ml-40 flex w-[50w]">
            <div class="vv-hero-decoration-parallelogram h-14 w-28 bg-primary"></div>
            <div class="vv-hero-decoration-parallelogram h-14 w-28 bg-primary"></div>
            <div class="vv-hero-decoration-parallelogram h-14 w-28 bg-primary"></div>
            <div class="vv-hero-decoration-parallelogram h-14 w-28 bg-primary"></div>
            <div class="vv-hero-decoration-parallelogram h-14 w-28 bg-primary"></div>
            <div class="vv-hero-decoration-parallelogram h-14 w-28 bg-primary"></div>
        </div>

        <img
            class="absolute left-1/3 z-[6] hidden -translate-x-[75%] lg:block h-full"
            :src="gameAsset(game.meta.splash.hero)"
            alt=""
        />

        <div
            class="left-0 top-0 block h-full w-full bg-cover bg-center bg-no-repeat opacity-10 dark:opacity-100"
            :style="{
                'background': 'url(' + gameAsset(game.meta.splash.backdrop) + ')',
            }"
        >
            <div class="absolute inset-0 z-[1] bg-black mix-blend-color"></div>
            <div class="absolute inset-0 z-[2] mix-blend-multiply dark:bg-gray-800"></div>
            <div class="absolute inset-0 z-[3] opacity-40 mix-blend-screen dark:bg-[#283341]"></div>
        </div>
    </div>
</template>

<script setup>
import { gameAsset } from "@H/assets.js";
import { usePage } from "@inertiajs/vue3";

const game = usePage().props.game;
</script>

<style scoped>
.vv-hero-decoration-left {
    clip-path: polygon(0 0, calc(100% - 484px) 0, 100% 100%, 0% 100%);
}
.vv-hero-decoration-parallelogram {
    clip-path: polygon(0 0, 65% 0, 100% 100%, 35% 100%)
}
</style>
