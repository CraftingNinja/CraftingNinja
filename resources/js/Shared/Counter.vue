<template>
    <span
        v-if="number > 0"
        class="text-3xs font-bold leading-none w-4.5 h-4.5 rounded-full inline-flex justify-center items-center"
    >
        <component
            :is="icon"
            v-if="number > 9"
            class="h-3.5 w-3.5 relative"
        />
        <template v-else>
            {{ number }}
        </template>
    </span>
</template>

<script setup>
import ExtraIcon from "~icons/game-icons/round-star"
defineProps({
    number: Number,
    icon: {
        default: ExtraIcon
    }
})
</script>
