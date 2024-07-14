<template>
    <div>
        <SectionHead :title="list.name" />

        <div class="grid grid-cols-6 gap-4">
            <div
                class="col-span-2 flex flex-col gap-4"
            >
                <div class="rounded-lg bg-gray-900 shadow-sm ring-1 ring-gray-900/5">
                    <dl class="flex flex-wrap space-y-4 p-4">
                        <div class="flex w-full flex-none gap-x-4 border-t border-gray-900/5">
                            <dt class="flex-none">
                                <span class="sr-only">Name</span>
                                <ScrollIcon class="h-6 w-5 text-primary" aria-hidden="true" />
                            </dt>
                            <dd class="text-sm font-medium leading-6 text-white">{{ list.description }}</dd>
                        </div>
                        <div class="flex w-full flex-none gap-x-4 border-t border-gray-900/5">
                            <dt class="flex-none">
                                <span class="sr-only">User</span>
                                <NinjaHeroicStanceIcon class="h-6 w-5 text-primary" aria-hidden="true" />
                            </dt>
                            <dd class="text-sm font-medium leading-6 text-white">{{ list.user.name }}</dd>
                        </div>
                        <div class="flex w-full flex-none gap-x-4">
                            <dt class="flex-none">
                                <span class="sr-only">Due date</span>
                                <CalendarIcon class="h-6 w-5 text-primary" aria-hidden="true" />
                            </dt>
                            <dd class="text-sm leading-6 text-white">
                                <time :datetime="list.created_at">{{ DateTime.fromISO(list.created_at).toLocaleString(DateTime.DATE_SHORT) }}</time>
                            </dd>
                        </div>
                        <div
                            v-if="list.user_id === usePage().props.auth.user?.id"
                            class="flex w-full flex-none gap-x-4"
                        >
                            <dt class="flex-none">
                                <span class="sr-only">Status</span>
                                <EyeIcon class="h-6 w-5 text-primary" aria-hidden="true" />
                            </dt>
                            <dd class="text-sm leading-6 text-white">{{ list.is_public ? 'Public' : 'Private' }}</dd>
                        </div>
                    </dl>
                </div>
                <div class="mt-1 space-y-4">
                    <Button
                        :icon="CraftingIcon"
                        class="w-full"
                        @click="craftList"
                    >
                        Craft List
                    </Button>

                    <div class="flex">
                        <Button
                            type="subtle"
                            :icon="AddToListIcon"
                            class="w-full"
                            @click="addToMyList"
                        >
                            {{ added ? 'Added!' : 'Add to My List' }}
                        </Button>

                        <Button
                            type="subtle"
                            :icon="ShareIcon"
                            class="w-full"
                            @click="copyLink"
                        >
                            {{ copied ? 'Copied!' : 'Copy Link' }}
                        </Button>
                    </div>

                    <div class="flex">
                        <Button
                            v-if="userIsOwner"
                            type="subtle"
                            :icon="EditIcon"
                            class="w-full"
                            @click="router.visit(route('lists.edit', list.sqid))"
                        >
                            Edit
                        </Button>

                        <Button
                            v-if="userIsOwner"
                            type="danger"
                            :icon="DeleteIcon"
                            @click="confirmClick(deleteList)"
                            class="w-full"
                        >
                            {{ confirmAgain ? 'Really?' : 'Delete List' }}
                        </Button>
                    </div>
                </div>
            </div>
            <ItemList :items="list.items" />
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { DateTime } from "luxon"
import NinjaHeroicStanceIcon from "~icons/game-icons/ninja-heroic-stance"
import CalendarIcon from "~icons/mdi/calendar-month"
import EyeIcon from "~icons/mdi/eye-outline"
import ItemList from "./Partials/ShowItemList.vue"
import CraftingIcon from '~icons/game-icons/claw-hammer';
import AddToListIcon from "~icons/lucide/scroll-text";
import ScrollIcon from "~icons/lucide/scroll";
import ShareIcon from '~icons/mdi/share-variant';
import EditIcon from '~icons/mdi/file-document-edit';
import Button from "@/Shared/Button.vue";
import DeleteIcon from '~icons/mdi/delete';
import SectionHead from "@/Shared/SectionHead.vue";

import AddToListComposable from "@/Shared/List/composable.js";
const { addToList } = AddToListComposable();

const props = defineProps({
    list: Object
})

const userIsOwner = usePage().props.auth.user?.id === props.list.user.id;

const craftList = () => router.visit(route('craft.list', props.list.sqid));


const copied = ref(false);

const copyLink = () => {
    navigator.clipboard
        .writeText(window.location.href)
        .then(() => {
            copied.value = true;
            setTimeout(() => copied.value = false, 2000);
        });
}

const added = ref(false);

const addToMyList = () => {
    Object.values(props.list.items).forEach((entry) => addToList(entry.item_id, entry.quantity, entry.recipe_id));
    added.value = true;
    setTimeout(() => added.value = false, 2000);
}

const deleteList = () => router.delete(route('lists.destroy', props.list.sqid))

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
