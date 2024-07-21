<template>
    <Page title="Welcome" :hero="false">
        <template #above>
            <HeroBanner />
        </template>

        <!-- TODO 1 Feature Banners -->
    </Page>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import Page from "@/Layouts/Page.vue";
import HeroBanner from "@/Pages/Partials/HeroBanner.vue";

const game = usePage().props.game;
</script>
