<template>
    <Link
        :href="routeName ? route(routeName) : '#'"
		@click="routeName ? (activeRouteName = routeName) : false"
        class="relative inline-flex leading-10 transition-colors after:absolute after:bottom-[7px] after:left-0 after:h-[2px] after:transition-transform after:w-full"
        :class="{
            'after:origin-left after:scale-x-100 text-primary after:bg-primary': activeRouteName === routeName,
            'after:bg-gray-900 dark:after:bg-white after:origin-right hover:after:origin-left after:scale-x-0 hover:after:scale-x-100 hover:text-primary hover:after:bg-primary dark:hover:after:bg-primary': activeRouteName !== routeName,
        }"
    >
        <slot />
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import Composable from "@/Layouts/composable.js";

const { activeRouteName } = Composable();

defineProps({
    routeName: String
});
</script>
