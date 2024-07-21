<template>
    <div>
        <div
            v-if="loading"
            class="text-center py-8"
        >
            <Spinner class="w-8 h-8 inline" />
        </div>
        <EmptyState
            v-else-if="items.data.length === 0"
            :icon="MortarPestleIcon"
        >
            <template #default>
                No Items Found
            </template>
            <template #content>
                Try another search or filter.
            </template>
        </EmptyState>
        <div v-else>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-1 py-8">
                <ItemCard
                    v-for="item in items.data"
                    :key="item.id"
                    :item="item"
                />
            </div>

            <Pagination
                :meta="items.meta"
                @page-updated="pageUpdated"
            />
        </div>
    </div>
</template>

<script setup>
import Spinner from "@S/Spinner.vue";
import MortarPestleIcon from "~icons/game-icons/pestle-mortar";
import ItemCard from "@S/ItemCard.vue";
import Pagination from "@S/Pagination.vue";
import Composable from '../composable.js';
import EmptyState from "@S/EmptyState.vue";

const { items, loading, page } = Composable();

const pageUpdated = (newPage) => (page.value = newPage);
</script>
