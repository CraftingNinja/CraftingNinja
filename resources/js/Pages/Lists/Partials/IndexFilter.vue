<template>
    <div as="section" aria-labelledby="filter-heading" class="grid items-center">
        <h2 id="filter-heading" class="sr-only">Filters</h2>
        <div class="relative col-start-1 row-start-1 py-4 border-b border-primary">
            <div class="mx-auto flex max-w-7xl space-x-6 divide-x divide-primary px-4 text-sm sm:px-6 lg:px-8">
                <div>
                    <div class="flex items-center font-medium text-white ">
                        <FunnelIcon class="mr-2 h-5 w-5 flex-none text-white" aria-hidden="true" />
                        {{ pluralize('Filter', numberOfFilters, true) }}
                    </div>
                </div>
                <div
                    class="pl-6"
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
        <div class="col-start-1 row-start-1 py-4">
            <div class="mx-auto flex max-w-7xl space-x-6 divide-x divide-primary justify-end px-4 sm:px-6 lg:px-8">
                <div class="flex relative">
                    <input
                        ref="authorInput"
                        type="text"
                        v-model="form.author"
                        class="inline-block text-sm p-0 bg-transparent border-0 focus:!outline-0 focus:!border-0 focus:!ring-0"
                        placeholder="Search By Author"
                    />
                    <button
                        type="button"
                        class="text-white hover:text-primary"
                        @click="authorInput.focus()"
                        tabindex="-1"
                    >
                        <AccountSearch class="w-5 h-5 ml-3" />
                    </button>
                </div>
                <div class="flex relative pl-6">
                    <input
                        ref="searchInput"
                        type="text"
                        v-model="form.search"
                        class="w-[200px] inline-block text-sm p-0 bg-transparent border-0 focus:!outline-0 focus:!border-0 focus:!ring-0"
                        placeholder="Search By Name/Description"
                    />
                    <button
                        type="button"
                        class="text-white hover:text-primary"
                        @click="searchInput.focus()"
                        tabindex="-1"
                    >
                        <MagnifyingGlassIcon class="w-5 h-5 ml-3" />
                    </button>
                </div>
                <Menu as="div" class="pl-6 relative block">
                    <div class="flex">
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
											form.sort === option.sort && form.order === option.order ? 'font-medium text-primary' : 'text-white hover:text-primary',
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
    </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { router } from "@inertiajs/vue3"
import pluralize from "pluralize";
import AccountSearch from "~icons/mdi/account-search"
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { ChevronDownIcon, FunnelIcon } from '@heroicons/vue/20/solid'
import { MagnifyingGlassIcon } from '@heroicons/vue/24/solid'
import {throttle} from "lodash";

const props = defineProps({
    filters: Object,
})

const sortOptions = [
    { name: 'Date: New to Old', sort: 'created_at', order: 'desc' },
    { name: 'Date: Old to New', sort: 'created_at', order: 'asc' },
    { name: 'Name: A to Z', sort: 'name', order: 'asc' },
    { name: 'Name: Z to A', sort: 'name', order: 'desc' },
    { name: 'Author: A to Z', sort: 'users.name', order: 'asc' },
    { name: 'Author: Z to A', sort: 'users.name', order: 'desc' },
];

const searchInput = ref(null);
const authorInput = ref(null);

const form = ref({
    author: props.filters.author || '',
    search: props.filters.search || '',
    sort: props.filters.sort || 'created_at',
    order: props.filters.order || 'desc',
})

const clearFilters = () => {
    form.value = {
        author: '',
        search: '',
        sort: 'created_at',
        order: 'desc',
    }
}

watch(form, throttle(() => router.get(route('lists.index'), form.value, { preserveState: true })), { deep: true })

const numberOfFilters = computed(() => {
    let counter = 0;

    if (form.value.author) {
        counter++;
    }

    if (form.value.search) {
        counter++;
    }

    return counter;
});

const sortSelected = (option) => {
    form.value.sort = option.sort;
    form.value.order = option.order;
};
</script>
