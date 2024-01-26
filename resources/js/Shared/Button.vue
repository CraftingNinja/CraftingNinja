<template>
    <button
        type="button"
        class="flex items-center font-bold leading-none transition-colors uppercase disabled:opacity-25"
        :class="{
            [classes[type] ?? '']: true,
            'p-3.5 px-6': ! thin,
            'p-2 px-4': thin,
        }"
    >
        <component
            v-if="icon"
            :is="icon"
            class="w-6 h-6"
        />
        <div
            class="flex-1"
            :class="{
                'pl-3.5': icon,
                'leading-6': !icon
            }"
        >
            <slot />
        </div>
    </button>
</template>

<script setup>
const props = defineProps({
    type: {
        type: String,
        default: 'primary',
    },
    thin: Boolean,
    icon: {
        default: null
    },
});

const classes = {
    'primary': 'text-white bg-primary hover:bg-primary/90',
    'secondary': 'text-white bg-accent hover:bg-accent/90',
    'subtle': 'text-gray-600 hover:text-gray-900 dark:hover:text-white',
    'danger': 'text-primary/50 border-2 border-primary/50 hover:border-transparent hover:bg-primary/50 hover:text-white',
};
</script>
