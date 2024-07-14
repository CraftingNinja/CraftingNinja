<template>
    <SectionHead :title="`Crafting Ninja - ${game.name}`" />

</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import SectionHead from '@/Shared/SectionHead.vue';

const game = usePage().props.game;
</script>
