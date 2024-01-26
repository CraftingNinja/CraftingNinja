<template>
    <Link
		:href="routeName ? route(routeName) : '#'"
		@click="routeName ? (activeRouteName = routeName) : false"
        class="flex items-center justify-between py-1.5 transition-colors"
        :class="{
            'text-primary': activeRouteName === routeName,
            'text-gray-900 dark:text-white hover:text-primary dark:hover:text-primary': activeRouteName !== routeName,
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
