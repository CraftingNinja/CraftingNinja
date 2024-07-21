<template>
    <Page :title="title">
        <LoadingDisplay v-if="loading" />
        <div
            v-else
            class="space-y-8"
        >
            <ToolSection
                v-if="unknownItems.length > 0"
                title="Unknowns"
                :items="unknownItems"
                @open-information-slide-over="openInformationSlideOver"
            />

            <ToolSection
                v-if="shoppingItems.length > 0"
                title="Shopping"
                :items="shoppingItems"
                @open-information-slide-over="openInformationSlideOver"
            />

            <ToolSection
                v-if="gatheredItems.length > 0"
                title="Gathering"
                :items="gatheredItems"
                @open-information-slide-over="openInformationSlideOver"
            />

            <template
                v-for="(section, key) in craftingItems"
                :key="key"
            >
                <ToolSection
                    :title="`${section.job.name} Crafting`"
                    :classjob-icon="section.job.name"
                    :items="section.items"
                    @open-information-slide-over="openInformationSlideOver"
                />
            </template>
        </div>

        <ItemInformationSlideOver
            :item="informationItem"
            :show="informationShow"
            @close="informationShow = false"
        />
    </Page>
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import LoadingDisplay from "./Partials/Loading.vue";
import ListComposable from "@S/List/composable.js";
import CraftComposable from "./composable.js";
import ItemsComposable from "@S/Loaders/items.js";
import ItemInformationSlideOver from "./Partials/ItemInformationSlideOver.vue";
import ToolSection from "./Partials/ToolSection.vue";
import Page from "@/Layouts/Page.vue";

// TODO 1 - Idea - Fireworks animation if they check everything? Letting them know they're done?

const { loading, items } = ItemsComposable();
const { registerWantedItem, reset, state, savePreference } = CraftComposable();

const props = defineProps({
    list: [Object, Boolean]
});

const jobs = Object.values(usePage().props.jobs);

const nodeTypeToAssetName = [
    'mining', // Node Type 0 is golden pick
    'quarrying', // Node Type 1 is blue pick
    'logging', // 2 is golden feather
    'harvesting', // 3 is blue feather
];

const title = ref('Craft');

const isGathering = (item) => ['nodes', 'mobs', 'fishing'].includes(item.preference.type);
const isShopping = (item) => ['shops'].includes(item.preference.type);
const isRecipes = (item) => ['recipes'].includes(item.preference.type);
const isDone = (item) => item.needed && (item.ignore || item.needed <= item.obtained);

const informationShow = ref(false);
const informationItem = ref({});

const openInformationSlideOver = (item) => {
    informationItem.value = item;
    informationShow.value = true;
};

const unknownItems = computed(() => Object.values(state.items).filter((item) => !item.preference.identifier));
const gatheredItems = computed(() => Object.values(state.items).filter((item) => isGathering(item)));
const shoppingItems = computed(() => Object.values(state.items).filter((item) => isShopping(item)));
const craftingItems = computed(() => {
	// Group crafting items by their Class/Job
	const recipeItems = Object.values(state.items).filter((item) => isRecipes(item));
	const sections = {};

	recipeItems.forEach((item) => {
		const jobId = item.preference.entity.job_id;

		if (sections[jobId] === undefined) {
			sections[jobId] = {
				job: jobs.find((job) => job.id === parseInt(jobId)),
				items: []
			};
		}

		sections[jobId].items.push(item);
	});

	// Sort sections by their item length, shortest to longest
	// And internally sort each section's items by their recipe level
	const sortable = Object.values(sections).sort((a, b) => a.items.length - b.items.length);

	sortable.forEach((section) => {
		section.items = section.items.sort((a, b) => a.preference.entity.recipe_level - b.preference.entity.recipe_level);
	});

	return sortable;
});

onMounted(() => {
    if (props.list === false) {
        title.value = 'Craft Your List';
        const { activeList } = ListComposable();
        activeList.value.forEach((entry) => registerWantedItem(entry));
    } else {
        title.value = `Craft "${props.list.name}"`;
        props.list.items.forEach((entry) => registerWantedItem(entry));
    }
});

onUnmounted(reset);
</script>
