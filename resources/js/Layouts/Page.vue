<template>
    <Head :title="headerTitle" />
    <slot name="above" />
    <Section>
        <SectionHead
            v-if="hero"
            :title="hero === true ? title : hero"
            :flavor="heroFlavor"
            :hero-center="heroCenter"
        />
        <slot name="default" />
    </Section>
    <slot name="below" />
</template>

<script setup>
import { computed } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import { defaultString, defaultFalse } from "@H/propDefaults.js";
import Section from "@S/Section.vue";
import SectionHead from "@S/SectionHead.vue";

const props = defineProps({
    title: defaultString,
    hero: {
        type: [String, Boolean],
        default: true,
    },
    heroFlavor: defaultString,
    heroCenter: defaultFalse,
});

const game = usePage().props.game;
const headerTitle = computed(() => props.title + ' - ' + (game ? game.slug.toUpperCase() + ' @ ' : ''));
</script>
