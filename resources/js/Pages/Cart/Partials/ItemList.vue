<template>
    <div class="flex-1">
        <EmptyState
            v-if="activeList.length === 0"
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
        >
            <div
                v-if="loading"
                class="text-center py-8 sm:col-span-3"
            >
                <Spinner class="w-8 h-8 inline" />
            </div>
            <template v-else>
                <template
                    v-for="entry in activeList"
                    :key="entry.item_id"
                >
                    <ItemCard
                        v-if="items[entry.item_id]"
                        :item="items[entry.item_id]"
                    >
                        <div class="grid grid-cols-1 justify-items-end">
                            <div class="whitespace-nowrap">

                                <button
                                    type="button"
                                    @click.exact="updateQuantity(entry.item_id, -1, false)"
                                    @click.shift="updateQuantity(entry.item_id, -10, false)"
                                    @click.ctrl="updateQuantity(entry.item_id, -100, false)"
                                    @click.meta="updateQuantity(entry.item_id, -100, false)"
                                >
                                    <MinusIcon class="w-5 h-5 inline-block hover:text-primary" />
                                </button>

                                <span class="px-2 cursor-default">{{ entry.quantity }}</span>

                                <button
                                    type="button"
                                    @click.exact="updateQuantity(entry.item_id, 1, false)"
                                    @click.shift="updateQuantity(entry.item_id, 10, false)"
                                    @click.ctrl="updateQuantity(entry.item_id, 100, false)"
                                    @click.meta="updateQuantity(entry.item_id, 100, false)"
                                >
                                    <PlusIcon class="w-5 h-5 inline-block hover:text-primary" />
                                </button>
                            </div>
                            <button
                                type="button"
                                @click="updateQuantity(entry.item_id, -entry.quantity, false)"
                            >
                                <DeleteIcon class="w-5 h-5 hover:text-primary" />
                            </button>
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
import ItemCard from "@/Shared/ItemCard.vue";
import PlusIcon from "~icons/ic/baseline-add-circle";
import MinusIcon from "~icons/ic/baseline-remove-circle";
import DeleteIcon from "~icons/mdi/basket-remove-outline";
import ScrollIcon from "~icons/lucide/scroll-text";
import Spinner from "@/Shared/Spinner.vue";
import EmptyState from "@/Shared/EmptyState.vue";
import ListComposable from "@/Shared/List/composable.js";
import ItemsComposable from "@/Shared/Loaders/items.js";

const { activeList, updateQuantity } = ListComposable();
const { loading, items, loadItem } = ItemsComposable();

onMounted(() => {
    Object.values(activeList.value).forEach((entry) => loadItem(entry.item_id));
});
</script>
