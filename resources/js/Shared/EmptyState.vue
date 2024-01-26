<template>
    <div class="text-center py-8">
        <component
            v-if="icon"
            :is="icon"
            class="mx-auto h-16 w-16 text-white"
        />
        <h3 class="mt-2 font-semibold text-gray-900 dark:text-white">
            <slot />
        </h3>
        <p class="mt-1 text-gray-300 empty:hidden">
            <slot name="content" />
        </p>
    </div>
</template>

<script setup>
defineProps({
    icon: {
        default: null
    }
})
</script>
