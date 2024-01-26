<template>
    <div>
        <SectionHead title="Library" />
        <Filter />
        <Results />
        <!-- TODO 1 - Build a seeder for all the Notebooks. Carpentry levels 1 to 5, etc -->
    </div>
</template>

<script setup>
import SectionHead from '@/Shared/SectionHead.vue';
import Filter from "@/Pages/Library/Partials/Filter.vue";
import Results from "@/Pages/Library/Partials/Results.vue";
</script>
