<template>
    <div
        aria-live="assertive"
        class="pointer-events-none fixed inset-0 z-[60] px-4 py-6 print:hidden sm:px-6 sm:py-8"
    >
        <div
            class="flex h-full w-full flex-col items-center gap-4 sm:flex-wrap-reverse sm:content-start sm:items-end"
        >
            <Toast v-for="(toast, key) in toasts" :key="key" :toast="toast" />
        </div>
    </div>
</template>

<script setup>
import { onMounted, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import LayoutComposable from "@/Layouts/composable.js";
import Toast from "./Toast.vue";

const { toasts, addToast } = LayoutComposable();

const page = usePage();

const loadInToasts = () => {
    // Parse the "prop" toasts - Passed with Inertia::render or ::share
    (page.props.toasts || []).forEach((item) => addToast(item));

    // Parse the "redirect" toasts - Passed on redirect()s or back()s using ->with('success', 'message')
    Object.values(page.props.flash?.toasts).forEach((item) => addToast(item));
};

watch(() => [page.props.toasts, page.props.flash?.toasts], loadInToasts);

onMounted(loadInToasts);
</script>
