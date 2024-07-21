<template>
    <div
        class="not-prose flex flex-row mb-9 items-end"
        :class="{
            'justify-center': center,
            'justify-between': !center,
        }"
    >
        <div class="flex flex-col">
            <div v-if="flavor" class="text-base font-medium uppercase tracking-tight text-primary md:text-lg lg:text-xl xl:text-1.5xl">{{ flavor }}</div>
            <h2
                class="font-bold uppercase leading-none tracking-tighter text-gray-900 before:inline-block before:content-['.'] before:mr-[0.15em] dark:text-white text-2.5xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-6.5xl xl:leading-[0.85em]"
                :class="{
                    'before:text-accent': dot === 'accent',
                    'before:text-primary': !dot
                }"
            >
                {{ title }}
            </h2>
        </div>
    </div>
</template>

<script setup>
import { defaultString, defaultFalse } from "@H/propDefaults.js";

defineProps({
    flavor: defaultString,
    title: defaultString,
    dot: defaultString,
    center: defaultFalse
})
</script>
