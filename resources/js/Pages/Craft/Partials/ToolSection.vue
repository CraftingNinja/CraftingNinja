<template>
    <div class="flex flex-col space-y-4">
        <div class="flex space-x-4">
            <img
                v-if="classjobIcon"
                class="inline w-8 h-8"
                :src='gameAsset(`classjob/${classjobIcon.toLowerCase()}.png`)'
                :alt='`Icon of ${classjobIcon}`'
            >
            <p class="text-semibold text-2xl">
                {{ title }}
            </p>

            <!-- TODO 1 - Location Filtering! -->
            <!--<LocationDropdown-->
            <!--    class="relative z-20 w-full"-->
            <!--    :locations="state.locations"-->
            <!--/>-->
        </div>

        <div class="flex flex-wrap gap-2 p-3 rounded bg-accent/[.05] max-w-full">
            <Tooltip
                class="relative leading-none"
                tooltip-bubble-classes="absolute z-10 bg-gray-800 text-white drop-shadow-md whitespace-nowrap rounded-md px-2 py-1 text-xs"
            >
                <template #hoverElement>
                    <div class="w-8 h-8 min-w-8 rounded-md border-2 border-accent opacity-50">
                        <CheckMarkIcon class="w-6 h-6 mt-0.5 mx-auto text-gray-100" />
                    </div>
                </template>
                <template #text>
                    Completed Items
                </template>
            </Tooltip>
            <template
                v-for="item in items"
                :key="item.id"
            >
                <Tooltip
                    v-if="isDone(item)"
                    class="relative leading-none"
                    tooltip-bubble-classes="absolute z-10 bg-gray-800 text-white drop-shadow-md whitespace-nowrap rounded-md px-2 py-1 text-xs"
                >
                    <template #hoverElement>
                        <button
                            type="button"
                            class="relative block min-w-8 group"
                            :class="{
                                'cursor-not-allowed': item.ignore
                            }"
                            @click="moveBack(item)"
                        >
                            <img
                                :src="gameAsset(item.icon)"
                                class="w-8 h-8 self-center opacity-50"
                                :alt="item.name"
                            />
                            <MoveUpIcon
                                v-if="!item.ignore"
                                class="w-8 h-8 absolute inset-0 invisible group-hover:visible"
                            />
                            <span class="absolute bottom-[2px] right-[2px] leading-none font-mono text-xs">
                                {{ item.needed }}
                            </span>
                        </button>
                    </template>
                    <template #text>
                        {{ item.name }} x {{ item.needed}}
                    </template>
                </Tooltip>
            </template>
        </div>

        <div class="grid grid-cols-2 gap-3 lg:grid-cols-3">
            <template
                v-for="item in items"
                :key="item.id"
            >
                <GatheringItemCard
                    v-if="!isDone(item)"
                    :item="item"
                    @information-pane="emit('openInformationSlideOver', item)"
                />
            </template>
        </div>
    </div>
</template>

<script setup>
import { defaultObject, defaultString } from "@H/propDefaults.js";
import { gameAsset } from "@H/assets.js";
import GatheringItemCard from "@/Pages/Craft/Partials/GatheringItemCard.vue";
import Tooltip from "@S/Tooltip.vue";
import MoveUpIcon from "~icons/iconoir/move-up";
import CheckMarkIcon from "~icons/game-icons/check-mark";

defineProps({
    title: defaultString,
    items: defaultObject,
    classjobIcon: defaultString,
});

const emit = defineEmits(['openInformationSlideOver']);

const isDone = (item) => item.needed && (item.ignore || item.needed <= item.obtained);
const moveBack = (item) => (item.obtained = 0);
</script>
