<template>
    <div>
        <SectionHead title="Hunting Log" />

        <div class="grid grid-cols-12 space-x-4">
            <div class="col-span-3 rounded p-3 bg-gray-700">
                <ul class="space-y-3">
                    <li
                        class="flex font-semibold border-b border-primary/90"
                    >
                        <span class="flex-1">
                            Class
                        </span>
                        <span>
                            Rank
                        </span>
                    </li>
                    <template
                        v-for="(data, kind) in {jobs, companies}"
                        :key="kind"
                    >
                        <li
                            v-for="(name, abbr) in data"
                            :key="abbr"
                            class="flex space-x-2 cursor-pointer select-none"
                            role="button"
                            @click.exact="increaseJobTracker(abbr, kind, 1)"
                            @click.shift="increaseJobTracker(abbr, kind, -1)"
                        >
                            <img
                                class="inline w-7 h-7"
                                :src='asset(kind === "jobs" ? `classjob/${name.toLowerCase()}.png` : `companies/square_${abbr.toLowerCase()}.png`)'
                                :alt='`Icon of ${name}`'
                            >
                            <span class="flex-1 truncate">
                                {{ name }}
                            </span>
                            <component
                                :is="numberToIcon[tracker.jobs[abbr] || 0]"
                                class="w-5.5 h-5.5"
                            />
                        </li>
                    </template>
                </ul>
            </div>
            <div class="col-span-9 space-y-4">
                <EmptyState
                    v-if="activeHunts.length === 0"
                    :icon="SkullIcon"
                >
                    <template #default>
                        Select your ranks
                    </template>
                    <template #content>
                        Get started by selecting your ranks!
                    </template>
                </EmptyState>
                <div
                    v-else
                    v-for="(area, akey) in activeHunts"
                    :key="akey"
                >
                    <h2 class="font-semibold border-b border-primary/90">{{ area.name }}</h2>
                    <ul
                        role="list"
                        class="mt-3 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3"
                    >
                        <li
                            v-for="(hunt, hkey) in area.hunts"
                            :key="hkey"
                            class="group col-span-1 flex rounded-md shadow-sm bg-gray-700 text-white cursor-pointer select-none"
                            :class="{
                                'opacity-40': tracker.hunts[huntTrackerKey(hunt)]
                            }"
                            role="button"
                            @click="toggleHuntTracker(hunt)"
                        >
                            <div
                                class="flex px-2 flex-shrink-0 items-center justify-center"
                            >
                                <img
                                    :src="asset(`hunting/${hunt.image}`)"
                                    class="inline w-[44px] h-[48px]"
                                    :alt="hunt.task"
                                />
                            </div>
                            <div class="flex flex-1 items-center justify-between truncate rounded-r-md">
                                <div class="flex-1 truncate p-2 text-sm">
                                    <p>{{ hunt.task }}</p>
                                    <p>
                                        <img
                                            class="inline w-5 h-5 mr-1"
                                            :src='asset(jobs[hunt.class] ? `classjob/${jobs[hunt.class].toLowerCase()}.png` : `companies/square_${hunt.class.toLowerCase()}.png`)'
                                            :alt='`Icon of ${hunt.class}`'
                                        >
                                        <span class="text-gray-500">#</span>{{ hunt.rank }}<span v-if="hunt.slot">.{{ hunt.slot }}</span>
                                    </p>
                                    <p
                                        v-if="hunt.location"
                                        class="text-gray-500"
                                    >
                                        <MapMarkerIcon class="inline w-4 h-4" />
                                        {{ hunt.location }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0 pt-1 pr-2">
                                    <button
                                        type="button"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-full text-white"
                                        :class="
                                            [tracker.hunts[huntTrackerKey(hunt)]
                                                ? ''
                                                : 'bg-primary group-hover:text-gray-700'
                                        ]"
                                    >
                                        <component
                                            :is="tracker.hunts[huntTrackerKey(hunt)] ? SkullIcon : SwordIcon"
                                            class="h-5 w-5"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import SectionHead from '@/Shared/SectionHead.vue';
import { useStorage } from "@/Shared/Helpers/useStorage.js";
import { asset } from "@/Shared/Helpers/assets.js";
import Number0Icon from "~icons/mdi/numeric-0-box-outline";
import Number1Icon from "~icons/mdi/numeric-1-box";
import Number2Icon from "~icons/mdi/numeric-2-box";
import Number3Icon from "~icons/mdi/numeric-3-box";
import Number4Icon from "~icons/mdi/numeric-4-box";
import Number5Icon from "~icons/mdi/numeric-5-box";
import MapMarkerIcon from "~icons/mdi/map-marker";
import SwordIcon from "~icons/mdi/sword";
import SkullIcon from "~icons/mdi/skull";
import EmptyState from "@/Shared/EmptyState.vue";

const numberToIcon = [
    Number0Icon,
    Number1Icon,
    Number2Icon,
    Number3Icon,
    Number4Icon,
    Number5Icon
];

const props = defineProps({
    jobs: Object,
    companies: Object,
    huntingData: Array
});

const activeHunts = computed(() => {
    const data = [];

    props.huntingData.forEach((zone) => {
        zone.areas.forEach((area) => {
            const hunts = area.hunts.filter((hunt) => {
                // Ranks are 1-5. Mob Ranks are 1-50. Rank 1 has access to mobs 1-10.
                const rank = tracker.value.jobs[hunt.class] || 0;
                const maxRank = rank * 10;
                const minRank = maxRank - 9;

                return hunt.rank >= minRank && hunt.rank <= maxRank;
            });

            if (hunts.length) {
                // Not replicating the "zone" here, it's not needed
                data.push({
                    name: area.name,
                    hunts,
                });
            }
        });
    });

    return data;
});

const tracker = useStorage('huntingLog', { jobs: {}, hunts: {}});
const increaseJobTracker = (abbr, type, amount) => {
    // Jobs have 5 ranks, Companies have 3
    const max = type === 'jobs' ? 5 : 3;

    if (tracker.value.jobs[abbr] === undefined) {
        tracker.value.jobs[abbr] = amount;
    } else {
        tracker.value.jobs[abbr] += amount;
    }

    if (tracker.value.jobs[abbr] > max) {
        tracker.value.jobs[abbr] = 0;
    }

    if (tracker.value.jobs[abbr] < 0) {
        tracker.value.jobs[abbr] = max;
    }
};

const huntTrackerKey = (hunt) => `${hunt.class}${hunt.rank}${hunt.slot || ''}`;
const toggleHuntTracker = (hunt) => {
    const key = huntTrackerKey(hunt);
    tracker.value.hunts[key] = ! tracker.value.hunts[key];
};


</script>
