<template>
    <div>
        <EmptyState
            :icon="ScrollIcon"
            v-if="lists.data.length === 0"
        >
            <template #default>
                No Lists Found
            </template>
            <template #content>
                Try another search or filter.
            </template>
        </EmptyState>
        <ul
            v-else
            role="list"
            class="divide-y divide-gray-500"
        >
            <li
                v-for="list in lists.data" :key="list.id" class="flex flex-wrap items-center justify-between gap-x-6 gap-y-4 py-5 sm:flex-nowrap">
                <div>
                    <p class="text-xl font-semibold leading-6 text-white">
                        <Link :href="route('lists.show', list.sqid)" class="hover:underline">{{ list.name }}</Link>
                    </p>
                    <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-white">
                        <p>
                            {{ list.user.name }}
                        </p>
                        <svg viewBox="0 0 2 2" class="h-0.5 w-0.5 fill-current">
                            <circle cx="1" cy="1" r="1" />
                        </svg>
                        <p>
                            <time :datetime="list.created_at">
                                {{ DateTime.fromISO(list.created_at).toLocaleString(DateTime.DATE_SHORT) }}
                            </time>
                        </p>
                        <svg viewBox="0 0 2 2" class="h-0.5 w-0.5 fill-current">
                            <circle cx="1" cy="1" r="1" />
                        </svg>
                        <p>
                            {{ list.item_count }} Items
                        </p>
                        <svg viewBox="0 0 2 2" class="h-0.5 w-0.5 fill-current">
                            <circle cx="1" cy="1" r="1" />
                        </svg>
                        <p>
                            {{ list.item_quantity }} quantity
                        </p>
                    </div>
                </div>
                <div class="flex w-full flex-none justify-between gap-x-8 sm:w-auto">
                    <div class="flex gap-x-2.5">
                        <Link :href="route('lists.show', list.sqid)">
                            <ChevronRightIcon class="h-6 w-6 text-white" aria-hidden="true" />
                        </Link>
                    </div>
                </div>
            </li>
        </ul>

        <Pagination
            :links="lists.links"
        />
    </div>
</template>

<script setup>
import ScrollIcon from "~icons/game-icons/scroll-unfurled"
import ChevronRightIcon from "~icons/ion/chevron-right"
import { Link } from "@inertiajs/vue3"
import { DateTime } from "luxon";
import Pagination from "@/Shared/Pagination.vue";
import EmptyState from "@/Shared/EmptyState.vue";

defineProps({
    lists: Object
})
</script>
