<template>
    <div>
        <SectionHead :title="title" />

        <LoadingDisplay v-if="loading" />
        <div v-else-if="Object.values(locationTracker).length > 0">
            <div class="flex space-x-4">
                <div class="flex-1 space-y-4">
                    <p class="text-semibold text-2xl">Gathering</p>

                    <template
                        v-for="(locationItems, location) in locationTracker"
                        :key="location"
                    >
                        <div
                            class=""
                        >
                            <div class="font-bold text-lg">
                                {{ location }}
                            </div>
                            <div class="space-y-4">
                                <template
                                    v-for="(locData, locItemId) in locationItems"
                                    :key="locItemId"
                                >
                                    <ItemCard
                                        v-if="itemTracker[locItemId]"
                                        :item="items[locItemId]"
                                        :viewOnly="true"
                                    >
                                        <!--<template #after-image>-->
                                        <!--    <button-->
                                        <!--        type="button"-->
                                        <!--        class="absolute inset-0 z-10 h-[40px] rounded-md invisible group-hover:visible backdrop-blur-sm"-->
                                        <!--    >-->
                                        <!--        <CheckMarkIcon class="w-7 h-7 mx-auto text-white" />-->
                                        <!--    </button>-->
                                        <!--</template>-->
                                        <template #after-name>
                                            <span class="text-base">
                                                <span class="text-gray-600">x</span>
                                                <span class="font-bold font-mono ml-0.5">
                                                    {{ itemTracker[locItemId].needed }}
                                                </span>
                                            </span>
                                        </template>
                                        <template #bonus>
                                            <span class="text-sm">
                                                <template v-if="itemTracker[locItemId].preference.type === 'mobs'">
                                                    <span class="space-x-2">
                                                        <img
                                                            class="inline w-5 h-5"
                                                            :src="asset('gathering/fight.png')"
                                                        />
                                                        <span class="capitalize">{{ locData.name }}</span>
                                                        <span>
                                                            <span class="text-gray-600">lv</span><span class="text-sky-300">{{ locData.level }}</span>
                                                        </span>
                                                        <span
                                                            v-if="items[locItemId].mobs.length - 1 > 0"
                                                            class="text-gray-600"
                                                        >
                                                            others available
                                                        </span>
                                                    </span>
                                                </template>
                                                <template v-else-if="itemTracker[locItemId].preference.type === 'shops'">
                                                    [SHOPS]
                                                </template>
                                                <template v-else-if="itemTracker[locItemId].preference.type === 'nodes'">
                                                    [NODES]
                                                </template>
                                                <template v-else-if="itemTracker[locItemId].preference.type === 'fishing'">
                                                    <span class="space-x-2">
                                                        <img
                                                            class="inline w-5 h-5"
                                                            :src="asset('gathering/fishing.png')"
                                                        />
                                                        <span class="capitalize">{{ locData.name }}</span>
                                                        <span>
                                                            <span class="text-gray-600">lv</span><span class="text-sky-300">{{ locData.level }}</span>
                                                        </span>
                                                        <span
                                                            v-if="items[locItemId].fishing.length - 1 > 0"
                                                            class="text-gray-600"
                                                        >
                                                            others available
                                                        </span>
                                                    </span>
                                                </template>

                                            </span>
                                        </template>
                                        <template #default>
                                            <button
                                                type="button"
                                                class="w-[40px] h-[40px] rounded-md border-2 border-accent group"
                                            >
                                                <CheckMarkIcon
                                                    class="w-7 h-7 mx-auto text-white invisible group-hover:visible"
                                                />
                                            </button>
                                        </template>
                                    </ItemCard>
                                </template>
                            </div>
                        </div>
                    </template>


                    <!--<template-->
                    <!--    v-for="item in items"-->
                    <!--    :key="itemId"-->
                    <!--&gt;-->
                    <!--    <div-->
                    <!--        v-if="itemTracker[item.id].preference.type !== 'recipes' && itemTracker[item.id].needed && ! itemTracker[item.id].ignore && itemTracker[item.id].needed > itemTracker[item.id].obtained"-->
                    <!--        class="odd:bg-gray-700 p-3"-->
                    <!--    >-->
                    <!--        {{ item.name }} {{ itemTracker[item.id] }}-->
                    <!--    </div>-->
                    <!--</template>-->
                </div>
                <div class="rounded bg-gray-700 p-1 space-y-1">
                    <template v-for="item in items" :key="item.id">
                        <div
                            v-if="itemTracker[item.id].needed && (itemTracker[item.id]?.ignore || itemTracker[item.id].needed <= itemTracker[item.id].obtained)"
                            class="relative"
                        >
                            <img
                                :src="item.icon"
                                class="w-[32px] h-[32px] self-center opacity-50"
                                alt="Icon for item"
                            />
                            <div class="absolute bottom-[2px] right-[2px] leading-none font-mono text-xs">
                                {{ itemTracker[item.id].needed }}
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from "vue";
import SectionHead from "@/Shared/SectionHead.vue";
import LoadingDisplay from "./Partials/Loading.vue";
import ListComposable from "@/Shared/List/composable.js";
import CraftComposable from "./composable.js";
import ItemsComposable from "@/Shared/Loaders/items.js";
import ItemCard from "@/Shared/ItemCard.vue";
import { asset } from "@/Shared/Helpers/assets.js";
import CheckMarkIcon from "~icons/game-icons/check-mark";

const { loading, items } = ItemsComposable();
const { registerWantedItem, reset, itemTracker, locationTracker } = CraftComposable();

const props = defineProps({
    list: [Object, Boolean]
});

const title = ref('Craft');

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
