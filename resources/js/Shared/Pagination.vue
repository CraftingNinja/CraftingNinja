<template>
    <nav
        v-if="previous && next"
        class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0"
    >
        <div class="-mt-px flex w-0 flex-1">
            <button
                type="button"
                @click="linkClicked(meta.current_page - 1)"
                class="inline-flex items-center border-t-2 pr-1 pt-4 text-sm font-medium"
                :class="{
                    'border-transparent text-white hover:border-accent hover:text-accent': previous.url !== null && !previous.active,
                    'border-accent text-accent': previous.url !== null && previous.active,
                    'border-transparent text-gray-500': previous.url === null
                }"
                :disabled="previous.url === null"
            >
                <ArrowLongLeftIcon class="mr-3 h-5 w-5" aria-hidden="true" />
                Previous
            </button>
        </div>
        <div class="hidden md:-mt-px md:flex">
            <template
                v-for="link in links"
                :key="link.label"
            >
                <span
                    v-if="link.url === null"
                    class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500"
                >
                    &hellip;
                </span>
                <button
                    v-else
                    type="button"
                    @click="linkClicked(link.label)"
                    class="inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium"
                    :class="{
                        'border-transparent text-white hover:border-accent hover:text-accent': !link.active,
                        'border-accent text-accent': link.active,
                    }"
                    :disabled="link.active"
                >
                    {{ link.label }}
                </button>
            </template>
        </div>
        <div class="-mt-px flex w-0 flex-1 justify-end">
            <button
                type="button"
                @click="linkClicked(meta.current_page + 1)"
                class="inline-flex items-center border-t-2 pr-1 pt-4 text-sm font-medium"
                :class="{
                    'border-transparent text-white hover:border-accent hover:text-accent': next.url !== null && !next.active,
                    'border-accent text-accent': next.url !== null && next.active,
                    'border-transparent text-gray-500': next.url === null
                }"
                :disabled="next.url === null"
            >
                Next
                <ArrowLongRightIcon class="ml-3 h-5 w-5" aria-hidden="true" />
            </button>
        </div>
    </nav>
</template>

<script setup>
import { watch, ref } from "vue";
import { ArrowLongLeftIcon, ArrowLongRightIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    links: Array,
    meta: Object
});

const previous = ref({});
const links = ref([]);
const next = ref({});

const emit = defineEmits(["page-updated"]);

const linkClicked = (newPage) => {
    emit('page-updated', parseInt(newPage));
};

const splitMeta = () => {
    const data = props.links || props.meta.links;
    previous.value = data[0];
    next.value = data[data.length - 1];
    links.value = data.slice(1, data.length - 2);
};

watch(() => props.links || props.meta, splitMeta, { immediate: true, deep: true });
</script>
