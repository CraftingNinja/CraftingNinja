<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex text-center font-bold leading-none transition-colors uppercase justify-center gap-x-3 py-3 px-4 md:py-[18px] lg:px-7 text-sm text-gray-625 ring-1 ring-inset ring-gray-625 bg-transparent hover:bg-primary hover:text-white hover:ring-primary">
        <slot />
    </button>
</template>
