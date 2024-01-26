<script setup>
defineProps({
    value: String,
});
</script>

<template>
    <label class="block text-sm font-bold uppercase tracking-tight text-gray-900 dark:text-white [&:not(:empty)]:mb-2.5">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
