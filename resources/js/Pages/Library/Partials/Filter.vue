<template>
	<Disclosure
        v-slot="{ open }"
        as="section"
        aria-labelledby="filter-heading"
        class="grid items-center bg-gray-700 rounded-tl rounded-tr"
        :class="{
            'rounded-bl rounded-br': ! open
        }"
    >
		<h2 id="filter-heading" class="sr-only">Filters</h2>
		<div class="relative col-start-1 row-start-1 py-2">
			<div class="mx-auto flex max-w-7xl space-x-6 divide-x divide-primary px-4 text-sm sm:px-6 lg:px-8">
				<div class="py-2">
					<DisclosureButton class="group flex items-center font-medium text-white hover:text-primary">
						<FunnelIcon class="mr-2 h-5 w-5 flex-none text-white group-hover:text-primary" aria-hidden="true" />
						{{ pluralize('Filter', numberOfFilters, true) }}
					</DisclosureButton>
				</div>
				<div
					class="pl-6 py-2"
					v-if="numberOfFilters > 0"
				>
					<button
						type="button"
						class="text-white hover:text-primary"
						@click="clearFilters"
					>
						Clear all
					</button>
				</div>
			</div>
		</div>
		<DisclosurePanel
            class="py-10 bg-gray-700 border-t border-primary/70"
            :class="{
                'rounded-bl rounded-br': open
            }"
        >
			<div class="mx-auto grid max-w-7xl grid-cols-2 gap-x-4 px-4 text-sm sm:px-6 md:gap-x-6 lg:px-8">
				<div class="grid auto-rows-min grid-cols-1 gap-y-10 md:grid-cols-1 md:gap-x-10">
					<fieldset>
						<legend class="block font-medium text-primary text-lg">Recipe Jobs</legend>
						<div class="grid grid-cols-4 gap-4 pt-6 sm:pt-4">
							<div
								v-for="(job, jobIdx) in jobs"
								:key="job.value"
								class="flex"
							>
								<input
									:id="`job-${jobIdx}`"
									name="jobs[]"
									:value="job.value"
									type="checkbox"
                                    class="invisible peer"
									:checked="searchData.jobs.includes(job.id)"
									@click="toggleJob(job.id)"
								/>
								<label
									:for="`job-${jobIdx}`"
									class="ml-3 min-w-0 px-2 text-center text-white text-lg border-2 border-transparent peer-checked:border-accent peer-checked:rounded"
								>
									<img
										class="inline"
										:src='asset(`classjob/${job.name.toLowerCase()}.png`)'
										:alt='`Icon of ${job.name}`'
										width="48"
										height="48"
									>
                                    <div class="text-xs">{{ job.abbr }}</div>
								</label>
							</div>
						</div>
					</fieldset>
				</div>
				<div class="grid auto-rows-min grid-cols-1 gap-y-10 md:grid-cols-2 md:gap-x-6">
					<fieldset>
                        <legend class="block font-medium text-primary text-lg">Recipe Level Range</legend>
                        <div class="flex py-4 w-[75%]">
                            <TextInput
                                v-model="searchData.rlevel_min"
                                type="number"
                                min="1"
                                :max="maxLevel"
                                class="rounded-tl rounded-bl leading-none"
                            />
                            <TextInput
                                v-model="searchData.rlevel_max"
                                type="number"
                                min="1"
                                :max="maxLevel"
                                class="rounded-tr rounded-br leading-none"
                            />
                        </div>

                        <legend class="block font-medium text-primary text-lg">iLvl Range</legend>
                        <div class="flex py-4 w-[75%]">
                            <TextInput
                                v-model="searchData.ilvl_min"
                                type="number"
                                min="1"
                                :max="maxItemLevel"
                                class="rounded-tl rounded-bl leading-none"
                            />
                            <TextInput
                                v-model="searchData.ilvl_max"
                                type="number"
                                min="1"
                                :max="maxItemLevel"
                                class="rounded-tr rounded-br leading-none"
                            />
                        </div>
					</fieldset>
					<fieldset>
						<legend class="block font-medium text-primary text-lg">Recipe Difficulty</legend>
						<div class="space-y-6 pt-6 sm:space-y-4 sm:pt-4">
							<div v-for="(option, optionIdx) in stars" :key="option.value" class="flex items-center text-base sm:text-sm">
								<input
									:id="`stars-${optionIdx}`"
									name="stars[]"
									:value="option.value"
									type="checkbox"
									class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-accent focus:ring-indigo-500"
									:checked="searchData.stars.includes(option.value)"
									@click="toggleStars(option.value)"
								/>
								<label
									:for="`stars-${optionIdx}`"
									class="ml-3 min-w-0 flex-1 text-white"
								>
									{{ option.label }}
								</label>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
		</DisclosurePanel>
		<div class="col-start-1 row-start-1">
			<div class="mx-auto flex max-w-7xl space-x-6 divide-x divide-primary justify-end px-4 sm:px-6 lg:px-8">
				<div class="flex relative bg-gray-800 p-2 rounded w-7/12">
					<input
						ref="searchInput"
						type="text"
						v-model="searchData.search"
						class="inline-block text-sm p-0 pl-2 bg-transparent border-0 focus:!outline-0 focus:!border-0 focus:!ring-0 w-full"
					/>
					<button
						type="button"
						class="text-white hover:text-primary"
						@click="searchInput.focus()"
					>
						<MagnifyingGlassIcon class="w-5 h-5 ml-3" />
					</button>
				</div>
				<Menu as="div" class="pl-6 relative block">
					<div class="flex py-2">
						<MenuButton class="group inline-flex justify-center text-sm font-medium text-white hover:text-primary">
							Sort
							<ChevronDownIcon class="-mr-1 ml-1 h-5 w-5 flex-shrink-0 text-white group-hover:text-primary" aria-hidden="true" />
						</MenuButton>
					</div>

					<transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
						<MenuItems class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white dark:bg-gray-700 shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none">
							<div class="py-1">
								<MenuItem v-for="option in sortOptions" :key="option.name" v-slot="{ active }">
									<button
										type="button"
										:class="[
											searchData.sort === option.sort && searchData.order === option.order ? 'font-medium text-primary' : 'text-white hover:text-primary',
											active ? 'bg-gray-100 dark:bg-gray-700' : '',
											'block px-4 py-2 text-sm'
										]"
										@click="sortSelected(option)"
									>
										{{ option.name }}
									</button>
								</MenuItem>
							</div>
						</MenuItems>
					</transition>
				</Menu>
			</div>
		</div>
	</Disclosure>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { usePage } from "@inertiajs/vue3";
import pluralize from "pluralize";
import { cloneDeep } from "lodash";
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { ChevronDownIcon, FunnelIcon } from '@heroicons/vue/20/solid'
import { MagnifyingGlassIcon } from '@heroicons/vue/24/solid'
import TextInput from "@/Components/Jetstream/TextInput.vue";
import { toggleValue } from "@/Shared/Helpers/array.js";
import { useStorage } from "@/Shared/Helpers/useStorage.js";
import { asset } from "@/Shared/Helpers/assets.js";
import Composable from '../composable.js'

const { search, page } = Composable();

const jobs = Object.values(usePage().props.jobs);
const maxLevel = usePage().props.game.maxLevel;
const maxItemLevel = usePage().props.game.maxItemLevel;
const open = false;

const defaultSearchData = {
	search: '',
	sort: 'name',
	order: 'asc',
    rlevel_min: "1",
    rlevel_max: `${maxLevel}`, // String required
    ilvl_min: "1",
    ilvl_max: `${maxItemLevel}`, // String required
	stars: [],
	jobs: [],
	// page: 1, // Handled separately
	per_page: 36, // Divisible by 3 and 4 (and 2) for the grid
};

const searchData = useStorage('recipeSearchData', cloneDeep(defaultSearchData));

const stars = [
	{ value: '0', label: '☆☆☆☆' },
	{ value: '1', label: '★☆☆☆' },
	{ value: '2', label: '★★☆☆' },
	{ value: '3', label: '★★★☆' },
	{ value: '4', label: '★★★★' },
];

const sortOptions = [
	{ name: 'Name: A to Z', sort: 'name', order: 'asc' },
	{ name: 'Name: Z to A', sort: 'name', order: 'desc' },
    { name: 'Level: Low to High', sort: 'rlevel', order: 'asc' },
    { name: 'Level: High to Low', sort: 'rlevel', order: 'desc' },
    { name: 'iLvl: Low to High', sort: 'ilvl', order: 'asc' },
    { name: 'iLvl: High to Low', sort: 'ilvl', order: 'desc' },
];

const sortSelected = (option) => {
	searchData.value.sort = option.sort;
	searchData.value.order = option.order;
};

const numberOfFilters = computed(() => {
	let counter = 0;

	if (searchData.value.search) {
		counter++;
	}
    if (searchData.value.rlevel_min !== "1") {
        counter++;
    }
    if (searchData.value.rlevel_max !== `${maxLevel}`) {
        counter++;
    }
    if (searchData.value.ilvl_min !== "1") {
        counter++;
    }
    if (searchData.value.ilvl_max !== `${maxItemLevel}`) {
        counter++;
    }
	if (searchData.value.stars.length > 0) {
		counter += searchData.value.stars.length;
	}
	if (searchData.value.jobs.length > 0) {
		counter += searchData.value.jobs.length;
	}

	return counter;
});

const clearFilters = () => (searchData.value = cloneDeep(defaultSearchData));

const toggleJob = (jobId) => (searchData.value.jobs = toggleValue(searchData.value.jobs, jobId));
const toggleStars = (starId) => (searchData.value.stars = toggleValue(searchData.value.stars, starId));

const searchInput = ref(null);

watch(searchData, () => search(searchData), { deep: true });
watch(page, () => search(searchData, page.value));
onMounted(() => search(searchData));
</script>
