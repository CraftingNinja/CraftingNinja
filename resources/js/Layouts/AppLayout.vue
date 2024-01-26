<template>
    <div id="site-wrapper" class="flex flex-col h-full">
        <Header />
        <main id="main-content" class="grow lg:pt-0">
            <section class="py-10">
                <div class="container">
                    <slot />
                </div>
            </section>
        </main>
        <Footer />
    </div>
    <Toasts />
</template>

<script setup>
import { watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import Footer from "./Partials/Footer.vue";
import Header from "@/Layouts/Partials/Header.vue";
import Toasts from "@/Layouts/Partials/Toasts.vue";
import Composable from "@/Layouts/composable.js";

const { activeRouteName } = Composable();

defineProps({
    title: String,
});

const logout = () => {
    router.post(route('logout'));
};

activeRouteName.value = usePage().props.currentRoute;

watch(() => usePage().props.currentRoute, (val) => (activeRouteName.value = val));
</script>
