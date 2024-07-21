<template>
    <Page title='Welcome' hero="Crafting Ninja - Welcome">
        <div class="grid grid-cols-12 gap-x-6 gap-y-9 md:gap-x-6 lg:gap-x-7.5">
            <div
                v-for="game in games"
                :key="game.id"
                class="col-span-full sm:col-span-6 bg-gray-50 dark:bg-gray-700"
            >
                <figure class="group relative overflow-hidden bg-gray-100">
                    <!-- aspect-[2/1] w-full object-cover  -->
                    <img
                        class="transition-transform duration-300 group-hover:scale-110"
                        :src="game.assetUrl + game.meta.splash.logo"
                        :alt="`Visit ${game.name}`"
                    >

                    <!-- Intentional hard anchor so it forces the page to the subdomain. -->
                    <a
                        :href="game.url"
                        class="block absolute inset-0 bg-white dark:bg-gray-900 opacity-0 group-hover:opacity-90 transition-opacity"
                    ></a>

                    <div class="flex absolute justify-center left-0 right-0 top-1/2 -translate-y-1/2 scale-90 [@media(hover:hover)]:opacity-0 [@media(hover:hover)]:transition-all [@media(hover:hover)]:duration-300 [@media(hover:hover)]:group-hover:scale-100 [@media(hover:hover)]:group-hover:opacity-100">
                        <a
                            :href="game.url"
                            class="inline-flex text-center font-bold leading-none transition-colors uppercase justify-center gap-x-3 py-4 px-4 md:py-[18px] lg:px-8 text-sm text-white bg-primary hover:bg-primary/90"
                        >
                            {{ game.meta.splash.flavor || 'Crafting Time!' }}
                        </a>
                    </div>
                </figure>

                <div class="flex flex-col items-center p-5 md:p-7 xl:p-9">
                    <h3 class="mb-1 font-bold leading-tight tracking-tighter text-lg uppercase text-center text-gray-900 dark:text-white">
                        <a
                            :href="game.url"
                            class="text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary"
                        >
                            {{ game.meta.splash.name }}
                        </a>
                    </h3>

                    <div class="font-bold gap-2 text-primary leading-snug tracking-tight">
                        <ins class="no-underline">{{ game.meta.patch.version }} {{ game.meta.patch.tag }}</ins>
                    </div>

                    <!-- TODO 1 - Trademark needed here? -->
                    <!--<div class="font-bold gap-2 text-secondary leading-snug tracking-tight opacity-50">-->
                    <!--    <ins class="no-underline">&copy; {{ game.meta.trademark }}</ins>-->
                    <!--</div>-->
                </div>
            </div>
        </div>
    </Page>
</template>

<script setup>
import Page from "@/Layouts/Page.vue";
import { defaultArray } from "@H/propDefaults.js";

defineProps({
    games: defaultArray,
})
</script>
