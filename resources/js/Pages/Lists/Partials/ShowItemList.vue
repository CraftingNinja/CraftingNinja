<template>
    <div class="col-span-4">
        <EmptyState
            v-if="items.length === 0"
            :icon="ScrollIcon"
        >
            <template #default>
                Active List Empty
            </template>
            <template #content>
                Get started by <Link :href="route('library.index')" class="underline">searching for items</Link>.
            </template>
        </EmptyState>
        <div
            v-else
            class="grid grid-cols-1 sm:grid-cols-3 gap-1 items-start"
            :class="{
                'sm:grid-cols-3': items.length > 10,
                'sm:grid-cols-2': items.length <= 10
            }"
        >
            <div
                v-if="loading"
                class="text-center py-8 sm:col-span-3"
            >
                <Spinner class="w-8 h-8 inline" />
            </div>
            <template v-else>
                <template
                    v-for="entry in items"
                    :key="entry.item_id"
                >
                    <ItemCard
                        v-if="loadedItems[entry.item_id]"
                        :item="loadedItems[entry.item_id]"
                        :recipe-id="entry.recipe_id"
                        :view-only="true"
                    >
                        <div class="justify-items-end leading-6 whitespace-nowrap">
                            <span class="text-gray-600 mr-0.5">x</span>{{ entry.quantity }}
                        </div>
                    </ItemCard>
                </template>
            </template>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from "vue";
import { Link } from '@inertiajs/vue3';
import ItemCard from "@S/ItemCard.vue";
import ScrollIcon from "~icons/lucide/scroll-text";
import Spinner from "@S/Spinner.vue";
import EmptyState from "@S/EmptyState.vue";
import ItemsComposable from "@S/Loaders/items.js";

const { loading, items: loadedItems, loadItem, loadRecipe } = ItemsComposable();

const props = defineProps({
    items: {
        type: Array,
        default: []
    }
})

onMounted(() => {
    Object.values(props.items).forEach((entry) => loadItem(entry.item_id));
});
</script>
