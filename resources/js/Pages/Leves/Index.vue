<template>
    <Page title="Levequests">
        <div class="rounded-tl rounded-tr rounded-br p-3 bg-gray-700">
            <ul class="grid grid-cols-8 gap-3">
                <li v-for="job in jobs" :key="job.id">
                    <button
                        type="button"
                        class="job-button space-x-1 border-[1px] w-full rounded py-0.5 whitespace-nowrap"
                        :class="{
                            '-is-selected': selectedJob === job.id,
                            'border-primary text-primary': selectedJob === job.id,
                            'hover:border-accent hover:text-accent': selectedJob !== job.id,
                        }"
                        @click="selectedJob = job.id"
                    >
                        <img
                            class="inline w-7 h-7"
                            :src='gameAsset(`classjob/${job.name.toLowerCase()}.png`)'
                            :alt='`Icon of ${job.name}`'
                        />
                        {{ job.abbr }}
                    </button>
                </li>
            </ul>
        </div>
        <div class="grid grid-cols-12 space-x-4">
            <div class="col-span-3 rounded-bl rounded-br p-3 bg-gray-700">
                <ul class="grid grid-cols-5 gap-3 border-y border-primary/90 py-6 mb-6">
                    <li v-for="(level, key) in levels" :key="key">
                        <button
                            type="button"
                            class="space-x-1 border-[1px] w-full rounded py-0.5 whitespace-nowrap"
                            :class="{
                                'border-primary text-primary': selectedLevel === level,
                                'hover:border-accent hover:text-accent': selectedLevel !== level,
                            }"
                            @click="selectedLevel = level"
                        >
                            {{ level }}
                        </button>
                    </li>
                </ul>
                <div>
                    <label
                        role="button"
                        class="select-none"
                    >
                        <input
                            type='checkbox'
                            v-model='selectedHQ'
                            class="hidden"
                        />
                        <img
                            :src="gameAsset('leves/hq-icon.png')"
                            class="w-6 h-6 inline"
                            alt="Quality Icon"
                            :class="{
                                'opacity-50': !selectedHQ
                            }"
                        />
                        High Quality Turn-ins
                    </label>
                </div>
            </div>
            <div class="col-span-9">
                <div class="grid grid-cols-2 gap-5 py-4">
                    <div
                        v-for="leve in data"
                        :key="leve.id"
                    >
                        <div class="flex space-x-2 p-3 bg-gray-700 hover:bg-accent/[.20] rounded-tr rounded-tl pb-3">
                            <div class='relative overflow-hidden w-[37px]'>
                                <img
                                    :src='gameAsset(leve.frame)'
                                    class="w-[37px] h-[58px] absolute"
                                />
                                <img
                                    :src='gameAsset(leve.plate)'
                                    class="w-[37px] h-[58px]"
                                />
                            </div>
                            <div class="flex-1 overflow-ellipsis flex flex-col space-y-2 align-middle">
                                <div class="leading-none text-xl font-medium truncate">
                                    <img
                                        :src="gameAsset('leves/leve' + (leve.repeats ? '-repeatable' : '') + '.png')"
                                        class="inline h-7 -ml-1"
                                    />
                                    <span>{{ leve.name }}</span>
                                </div>
                                <div class="leading-none text-md font-medium">{{ leve.location.name }}</div>
                            </div>
                            <div class="flex flex-col text-right">
                                <div>
                                    {{ (leve.xp * (selectedHQ ? 2 : 1)).toLocaleString() }}
                                    <img
                                        :src="gameAsset('leves/xp.png')"
                                        class="inline w-5 h-5"
                                    />
                                </div>
                                <div>
                                    {{ (leve.gil * (selectedHQ ? 2 : 1)).toLocaleString() }}
                                    <img
                                        :src="gameAsset('leves/coin.png')"
                                        class="inline w-5 h-5"
                                    />
                                </div>
                            </div>
                        </div>
                        <ItemCard
                            :item="leve.item"
                            :recipe-id="leve.recipe.id"
                            class="rounded-tr-none rounded-tl-none"
                            :quality="selectedHQ"
                        />
                    </div>
                </div>
            </div>
        </div>
    </Page>
</template>

<script setup>
import Page from "@/Layouts/Page.vue";
import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import { gameAsset } from "@H/assets.js";
import ItemCard from "@S/ItemCard.vue";

const game = usePage().props.game;

const props = defineProps({
    jobs: Object,
    initialResults: Object,
});

const levels = [
    '01', '05',
    ...Array.from(Array(9).keys()).map((i) => i * 5 + 10), // 10-45
    ...Array.from(Array((game.settings.maxLevel - 52) / 2).keys()).map((i) => i * 2 + 52) // 52-78[maxLevel - 2]
];

const selectedJob = ref(Object.values(props.jobs)[0].id);
const selectedLevel = ref(levels[0]);
const selectedHQ = ref(false);

const data = ref(props.initialResults['data']);

const search = () => {
    axios.post(route('api.leves.search'), { jobId: selectedJob.value, level: selectedLevel.value })
        .then((result) => (data.value = result.data.data));
};

watch(selectedJob, search);
watch(selectedLevel, search);
</script>

<style scoped>
.job-button {
    @apply overflow-hidden;
}
.job-button:hover > img {
    @apply translate-y-[-100px];
    filter: drop-shadow(0px 100px 0 theme('colors.accent.DEFAULT'));
}
.job-button.-is-selected > img {
    @apply translate-y-[-100px];
    filter: drop-shadow(0px 100px 0 theme('colors.primary.DEFAULT'));
}
</style>
