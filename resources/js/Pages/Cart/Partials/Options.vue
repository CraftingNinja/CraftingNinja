<template>
    <div
        v-if="activeList.length"
        class="flex flex-col gap-4 p-4"
    >
        <Button
            :icon="CraftingIcon"
            @click="craftList"
        >
            Craft List
        </Button>

        <Button
            type="secondary"
            :icon="SaveIcon"
            :disabled="!usePage().props.auth.user"
            @click="saveList"
        >
            {{ !usePage().props.auth.user ? 'Log in to save' : 'Save List' }}
        </Button>

        <Button
            type="subtle"
            :icon="DeleteIcon"
            @click="confirmClick(emptyList)"
        >
            {{ confirmAgain ? 'Really?' : 'Empty List' }}
        </Button>

        <hr class="mt-2 pb-2 border-t-gray-500/50">

        <div class="group relative [&>.sub-menu]:hover:visible [&>.sub-menu]:hover:animate-popper-pop-in [&>.sub-menu]:hover:opacity-100">
            <Button
                type="subtle"
                :icon="ExportIcon"
                class="group-hover:text-white w-full"
            >
                Export to&hellip;
            </Button>
            <div class="sub-menu w-full invisible absolute z-20 flex flex-col bg-white dark:bg-gray-700 p-3 text-sm font-bold opacity-0 shadow-2xl transition-all">
                <button
                    type="button"
                    class="flex items-center py-1.5 transition-colors text-gray-900 dark:text-white hover:text-primary dark:hover:text-primary whitespace-nowrap"
                >
                    <img src="/images/logos/ffxivteamcraft.png" class="w-4 h-4 inline-block mr-2" alt="FFXIV Teamcraft Logo" />
                    FFXIV Teamcraft
                </button>
                <button
                    type="button"
                    class="flex items-center py-1.5 transition-colors text-gray-900 dark:text-white hover:text-primary dark:hover:text-primary whitespace-nowrap"
                >
                    <ShareIcon class="w-4 h-4 inline-block mr-2" />
                    Quick Share
                </button>
                <button
                    type="button"
                    class="flex items-center py-1.5 transition-colors text-gray-900 dark:text-white hover:text-primary dark:hover:text-primary whitespace-nowrap"
                >
                    <CSVIcon class="w-4 h-4 inline-block mr-2" />
                    CSV File
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { router, usePage } from '@inertiajs/vue3';
import Button from "@S/Button.vue";
import CraftingIcon from '~icons/game-icons/claw-hammer';
import ExportIcon from '~icons/mdi/export';
import SaveIcon from '~icons/mdi/content-save-plus';
import DeleteIcon from '~icons/mdi/delete-empty';
import ShareIcon from '~icons/mdi/share-variant';
import CSVIcon from '~icons/tabler/file-type-csv';

import ListComposable from "@S/List/composable.js";
const { activeList, emptyList } = ListComposable();

const state = ref('default');

const craftList = () => router.get(route('craft.active'));
const saveList = () => router.post(route('lists.create-from-cart'), { items: activeList.value });

const confirmAgain = ref(false);
let confirmTimeout = null;
const confirmClick = (callbackIfConfirmed) => {
    if (confirmAgain.value === true) {
        if (confirmTimeout) {
            clearTimeout(confirmTimeout);
        }
        (callbackIfConfirmed)();
    } else {
        confirmAgain.value = true;
        confirmTimeout = setTimeout(() => (confirmAgain.value = false), 3000);
    }
}
</script>
