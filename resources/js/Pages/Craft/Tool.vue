<template>
    <div>
        <SectionHead :title="title" />

        <LoadingDisplay v-if="loading" />
        <div v-else>
            <div class="flex space-x-4">
                <div class="flex-1 space-y-4">
					<template v-if="remainingGatheredItems.length > 0">
						<div class="flex space-x-4">
							<p class="text-semibold text-2xl">Gathering</p>

							<!-- TODO 1 - Location Filtering! -->
							<!--<LocationDropdown-->
							<!--    class="relative z-20 w-full"-->
							<!--    :locations="state.locations"-->
							<!--/>-->
						</div>

						<div class="space-y-4">
							<div class="grid grid-cols-2 gap-3 lg:grid-cols-3">
								<GatheringItemCard
									v-for="item in remainingGatheredItems"
									:key="item.id"
									:item="item"
									@information-pane="openInformationSlideOver"
								/>
							</div>
						</div>
					</template>

					<template v-if="remainingShoppingItems.length > 0">
						<div class="flex space-x-4">
							<p class="text-semibold text-2xl">Shopping</p>
						</div>

						<div class="space-y-4">
							<div class="grid grid-cols-2 gap-3 lg:grid-cols-3">
								<GatheringItemCard
									v-for="item in remainingShoppingItems"
									:key="item.id"
									:item="item"
									@information-pane="openInformationSlideOver"
								/>
							</div>
						</div>
					</template>

					<template
						v-for="(section, key) in remainingCraftingItems"
						:key="key"
					>
						<div class="flex space-x-4">
							<img
								class="inline w-[32px] h-[32px]"
								:src='asset(`classjob/${section.job.name.toLowerCase()}.png`)'
								:alt='`Icon of ${section.job.name}`'
							>
							<p class="text-semibold text-2xl">
								{{ section.job.name }} Crafting
							</p>
						</div>

						<div class="space-y-4">
							<div class="grid grid-cols-2 gap-3 lg:grid-cols-3">
								<GatheringItemCard
									v-for="item in section.items"
									:key="item.id"
									:item="item"
									@information-pane="openInformationSlideOver"
								/>
							</div>
						</div>
					</template>
                </div>
                <div>
                    <div class="rounded bg-gray-700 p-1 space-y-1">
						<button
							v-for="item in completedItems"
							:key="item.id"
							type="button"
							class="relative block"
							:class="{
								'cursor-not-allowed': item.ignore
							}"
							@click="moveBack(item)"
						>
							<img
								:src="item.icon"
								class="w-[32px] h-[32px] self-center opacity-50"
								alt="Icon for item"
							/>
							<span class="absolute bottom-[2px] right-[2px] leading-none font-mono text-xs">
								{{ item.needed }}
							</span>
						</button>
                    </div>
                </div>
            </div>
        </div>

        <ItemInformationSlideOver
            :item="informationItem"
            :show="informationShow"
            @close="informationShow = false"
        />
    </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref } from "vue";
import SectionHead from "@/Shared/SectionHead.vue";
import LoadingDisplay from "./Partials/Loading.vue";
import ListComposable from "@/Shared/List/composable.js";
import CraftComposable from "./composable.js";
import ItemsComposable from "@/Shared/Loaders/items.js";
import { asset } from "@/Shared/Helpers/assets.js";
import ItemInformationSlideOver from "@/Pages/Craft/Partials/ItemInformationSlideOver.vue";
import GatheringItemCard from "@/Pages/Craft/Partials/GatheringItemCard.vue";
import { usePage } from "@inertiajs/vue3";

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

const moveBack = (item) => (item.obtained = 0);

const informationShow = ref(false);
const informationItem = ref({});

const openInformationSlideOver = (item) => {
    informationItem.value = item;
    informationShow.value = true;
};

const remainingGatheredItems = computed(() => Object.values(state.items).filter((item) => ! isDone(item) && isGathering(item)));
const remainingShoppingItems = computed(() => Object.values(state.items).filter((item) => ! isDone(item) && isShopping(item)));
const remainingCraftingItems = computed(() => {
	// Group crafting items by their Class/Job
	const recipeItems = Object.values(state.items).filter((item) => ! isDone(item) && isRecipes(item));
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
const completedItems = computed(() => Object.values(state.items).filter((item) => isDone(item)));

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
