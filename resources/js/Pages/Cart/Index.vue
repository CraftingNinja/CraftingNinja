<template>
    <div>
        <SectionHead title="Your List" />

        <div class="flex gap-4">
            <ItemList />
            <Options />
        </div>
    </div>
</template>

<script setup>
import SectionHead from '@/Shared/SectionHead.vue';
import ItemList from "./Partials/ItemList.vue";
import Options from "./Partials/Options.vue";
</script>
