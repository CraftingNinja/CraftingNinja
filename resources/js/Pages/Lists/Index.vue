<template>
    <Page title="Lists">
		<Filter :filters="filters" />
        <List :lists="lists" />
    </Page>
</template>

<script setup>
import Page from "@/Layouts/Page.vue";
import List from "@/Pages/Lists/Partials/IndexListOfLists.vue";
import Filter from "@/Pages/Lists/Partials/IndexFilter.vue";

defineProps({
	filters: Object,
    lists: Object
})
</script>
