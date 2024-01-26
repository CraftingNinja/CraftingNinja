<template>
    <div>
        <SectionHead title="Lists" />

		<Filter :filters="filters" />
        <List :lists="lists" />
    </div>
</template>

<script setup>
import SectionHead from '@/Shared/SectionHead.vue';
import List from "@/Pages/Lists/Partials/IndexListOfLists.vue";
import Filter from "@/Pages/Lists/Partials/IndexFilter.vue";

defineProps({
	filters: Object,
    lists: Object
})
</script>
