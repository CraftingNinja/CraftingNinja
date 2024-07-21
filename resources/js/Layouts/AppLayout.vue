<template>
    <div id="site-wrapper" class="flex flex-col h-full">
        <Header />
        <main id="main-content" class="grow lg:pt-0">
            <slot />
        </main>
        <Footer />
    </div>
    <Toasts />
</template>

<script setup>
import { watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Footer from "./Partials/Footer.vue";
import Header from "@/Layouts/Partials/Header.vue";
import Toasts from "@/Layouts/Partials/Toasts.vue";
import Composable from "@/Layouts/composable.js";

const { activeRouteName } = Composable();

activeRouteName.value = usePage().props.currentRoute;

watch(() => usePage().props.currentRoute, (val) => (activeRouteName.value = val));
</script>
