<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        default: false,
    },
    value: {
        type: String,
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});
</script>


<template>
    <input
        v-model="proxyChecked"
        type="checkbox"
        :value="value"
        class="h-4 w-4 border-gray-600 bg-white checked:border-primary checked:bg-white checked:text-primary checked:hover:border-primary checked:hover:bg-white dark:border-gray-600 dark:bg-gray-900 dark:checked:border-primary dark:checked:hover:border-primary dark:checked:hover:bg-gray-900"
    >
</template>
